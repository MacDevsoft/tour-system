<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Tour;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BookingController extends Controller
{
    public function store(Request $request, Tour $tour)
    {
        if (!auth()->check() || auth()->user()->role !== 'user') {
            abort(403);
        }

        if ((int) $tour->cupos_disponibles <= 0 || !$tour->is_enabled) {
            return back()->with('status', 'Este tour no tiene cupos disponibles en este momento.');
        }

        $request->validate([
            'receipt' => 'required|image|max:5120',
            'confirm_additional' => 'nullable|boolean',
            'passenger_name' => 'nullable|string|max:120',
        ]);

        $alreadyBooked = Booking::where('user_id', auth()->id())
            ->where('tour_id', $tour->id)
            ->exists();

        if ($alreadyBooked && !$request->boolean('confirm_additional')) {
            return back()->with('status', 'Ya te encuentras registrado en este tour. Si deseas agregar otra persona, confirma al reservar nuevamente.');
        }

        $passengerName = trim((string) $request->input('passenger_name', ''));
        if ($passengerName === '') {
            $passengerName = auth()->user()->name;
        }

        $receiptPath = $request->file('receipt')->store('receipts', 'public');

        $booking = Booking::create([
            'user_id' => auth()->id(),
            'tour_id' => $tour->id,
            'passenger_name' => $passengerName,
            'purchase_id' => 'RES-' . now()->format('Ymd') . '-' . strtoupper(substr(uniqid(), -6)),
            'amount_paid' => (float) ($tour->anticipo ?? 0),
            'receipt_path' => $receiptPath,
            'status' => 'pending',
        ]);

        return redirect()->route('bookings.receipt', $booking->id);
    }

    public function myTours()
    {
        if (!auth()->check() || auth()->user()->role !== 'user') {
            abort(403);
        }

        $bookings = Booking::with('tour')
            ->where('user_id', auth()->id())
            ->oldest()
            ->get();

        $tourGroups = $bookings->groupBy('tour_id');

        return view('bookings.my-tours', compact('tourGroups'));
    }

    public function showTour(Tour $tour)
    {
        if (!auth()->check() || auth()->user()->role !== 'user') {
            abort(403);
        }

        $bookings = Booking::with('tour')
            ->where('user_id', auth()->id())
            ->where('tour_id', $tour->id)
            ->oldest()
            ->get();

        if ($bookings->isEmpty()) {
            abort(404);
        }

        $pendingBookings = $bookings->where('status', 'pending')->values();
        $approvedBookings = $bookings->where('status', 'approved')->values();

        return view('bookings.show', compact('tour', 'pendingBookings', 'approvedBookings'));
    }

    public function receipt(Booking $booking)
    {
        if (!auth()->check() || auth()->id() !== $booking->user_id) {
            abort(403);
        }

        $booking->load('tour');

        return view('bookings.receipt', compact('booking'));
    }

    public function adminIndex()
    {
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            abort(403);
        }

        $tourId = request('tour_id');
        $status = request('status', 'pending');

        $tours = Tour::where('is_enabled', true)->orderBy('nombre')->get();

        $bookings = collect();
        $selectedTour = null;

        if ($tourId) {
            $selectedTour = Tour::find($tourId);

            if ($selectedTour) {
                $bookings = Booking::with(['tour', 'user'])
                    ->where('tour_id', $selectedTour->id)
                    ->where('status', $status)
                    ->oldest()
                    ->get();
            }
        }

        return view('admin.reservations.index', compact('tours', 'bookings', 'selectedTour', 'status'));
    }

    public function approve(Booking $booking)
    {
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            abort(403);
        }

        if ($booking->status === 'approved') {
            return back()->with('status', 'Esta reserva ya fue aprobada anteriormente.');
        }

        $tour = $booking->tour;
        if ((int) $tour->cupos_disponibles <= 0) {
            return back()->with('status', 'No se puede aprobar: el tour ya no tiene cupos disponibles.');
        }

        $booking->update([
            'status' => 'approved',
            'approved_at' => now(),
        ]);

        $tour->update([
            'cupos_disponibles' => max(0, ((int) $tour->cupos_disponibles) - 1),
        ]);

        return back()->with('status', 'Reserva aprobada correctamente y cupo descontado.');
    }

    public function receiptImage(Booking $booking)
    {
        if (!auth()->check()) {
            abort(403);
        }

        $isOwner = auth()->id() === $booking->user_id;
        $isAdmin = auth()->user()->role === 'admin';

        if (!$isOwner && !$isAdmin) {
            abort(403);
        }

        if (!Storage::disk('public')->exists($booking->receipt_path)) {
            abort(404);
        }

        return response()->file(storage_path('app/public/' . $booking->receipt_path));
    }
}
