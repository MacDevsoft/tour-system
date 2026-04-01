<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\BookingPayment;
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

        $bookingsCountForTour = Booking::where('user_id', auth()->id())
            ->where('tour_id', $tour->id)
            ->count();

        if ($bookingsCountForTour >= 4) {
            return back()->with('status', 'Solo puedes registrar hasta 4 personas por tour (incluyendote).');
        }

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

        $this->touchBookingPlan($booking);

        return redirect()->route('bookings.receipt', $booking->id);
    }

    public function myTours()
    {
        if (!auth()->check() || auth()->user()->role !== 'user') {
            abort(403);
        }

        $bookings = Booking::with(['tour', 'payments', 'user'])
            ->where('user_id', auth()->id())
            ->oldest()
            ->get();

        $bookings->each(function (Booking $booking) {
            $this->touchBookingPlan($booking);
            $booking->ensureDigitalReceiptMetadata();
            $booking->payments->each->ensureDigitalReceiptMetadata();
        });

        $bookings->load(['tour', 'payments']);
        $tourGroups = $bookings->groupBy('tour_id');

        return view('bookings.my-tours', compact('tourGroups'));
    }

    public function showTour(Tour $tour)
    {
        if (!auth()->check() || auth()->user()->role !== 'user') {
            abort(403);
        }

        $bookings = Booking::with(['tour', 'payments', 'user'])
            ->where('user_id', auth()->id())
            ->where('tour_id', $tour->id)
            ->oldest()
            ->get();

        if ($bookings->isEmpty()) {
            abort(404);
        }

        $bookings->each(function (Booking $booking) {
            $this->touchBookingPlan($booking);
            $booking->ensureDigitalReceiptMetadata();
            $booking->payments->each->ensureDigitalReceiptMetadata();
        });

        $bookings->load('payments');

        $pendingBookings = $bookings->where('status', 'pending')->values();
        $approvedBookings = $bookings->where('status', 'approved')->values();
        $cancelledBookings = $bookings->where('status', 'rejected')->values();

        return view('bookings.show', compact('tour', 'pendingBookings', 'approvedBookings', 'cancelledBookings'));
    }

    public function receipt(Booking $booking)
    {
        if (!auth()->check() || auth()->id() !== $booking->user_id) {
            abort(403);
        }

        $booking->load(['tour', 'payments']);
        $this->touchBookingPlan($booking);
        $booking->ensureDigitalReceiptMetadata();
        $booking->payments->each->ensureDigitalReceiptMetadata();
        $booking->load('payments');

        return view('bookings.receipt', compact('booking'));
    }

    public function paymentReceipt(BookingPayment $payment)
    {
        if (!auth()->check() || auth()->id() !== $payment->booking->user_id) {
            abort(403);
        }

        $payment->load(['booking.tour', 'booking.user']);
        $payment->ensureDigitalReceiptMetadata();

        return view('bookings.payment-receipt', compact('payment'));
    }

    public function adminIndex()
    {
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            abort(403);
        }

        $tourId = request('tour_id');
        $status = request('status', 'pending');
        $paymentSearch = trim((string) request('payment_ref', ''));

        if (!in_array($status, ['pending', 'approved', 'rejected'], true)) {
            $status = 'pending';
        }

        $tours = Tour::where('is_enabled', true)->orderBy('nombre')->get();

        $bookings = collect();
        $selectedTour = null;
        $statusCounts = [
            'pending' => 0,
            'approved' => 0,
            'rejected' => 0,
        ];

        if ($tourId) {
            $selectedTour = Tour::find($tourId);

            if ($selectedTour) {
                $bookingsQuery = Booking::with(['tour', 'user', 'payments'])
                    ->where('tour_id', $selectedTour->id)
                    ->where('status', $status);

                if ($paymentSearch !== '') {
                    $bookingsQuery->where(function ($query) use ($paymentSearch) {
                        $query->where('purchase_id', 'like', '%' . $paymentSearch . '%')
                            ->orWhereHas('payments', function ($paymentQuery) use ($paymentSearch) {
                                $paymentQuery->where('reference', 'like', '%' . $paymentSearch . '%')
                                    ->orWhere('id', $paymentSearch);
                            });
                    });
                }

                $bookings = $bookingsQuery
                    ->oldest()
                    ->get();

                $bookings->each(function (Booking $booking) {
                    $this->touchBookingPlan($booking);
                    $booking->ensureDigitalReceiptMetadata();
                    $booking->payments->each->ensureDigitalReceiptMetadata();
                });

                $bookings->load('payments');

                $statusCounts = [
                    'pending' => Booking::where('tour_id', $selectedTour->id)->where('status', 'pending')->count(),
                    'approved' => Booking::where('tour_id', $selectedTour->id)->where('status', 'approved')->count(),
                    'rejected' => Booking::where('tour_id', $selectedTour->id)->where('status', 'rejected')->count(),
                ];
            }
        }

        return view('admin.reservations.index', compact('tours', 'bookings', 'selectedTour', 'status', 'paymentSearch', 'statusCounts'));
    }

    public function showAdminBooking(Booking $booking)
    {
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            abort(403);
        }

        $booking->load(['tour', 'user', 'payments']);
        $this->touchBookingPlan($booking);
        $booking->ensureDigitalReceiptMetadata();
        $booking->payments->each->ensureDigitalReceiptMetadata();
        $booking->load('payments');

        return view('admin.reservations.show', compact('booking'));
    }

    public function approve(Booking $booking)
    {
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            abort(403);
        }

        if ($booking->status === 'approved') {
            return back()->with('status', 'Esta reserva ya fue aprobada anteriormente.');
        }

        if ($booking->status === 'rejected') {
            return back()->with('status', 'No se puede aprobar una reserva cancelada o rechazada.');
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

        $this->touchBookingPlan($booking);

        return back()->with('status', 'Reserva aprobada correctamente y cupo descontado.');
    }

    public function cancel(Request $request, Booking $booking)
    {
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            abort(403);
        }

        if ($booking->status === 'rejected') {
            return back()->with('status', 'Esta reservación ya se encuentra cancelada.');
        }

        $data = $request->validate([
            'cancel_reason' => 'nullable|string|max:255',
        ]);

        $wasApproved = $booking->status === 'approved';
        $tour = $booking->tour;

        $booking->update([
            'status' => 'rejected',
            'cancelled_at' => now(),
            'cancellation_reason' => $data['cancel_reason'] ?? ('La reservación de ' . ($booking->passenger_name ?: $booking->user->name) . ' fue cancelada por administración.'),
        ]);

        $booking->payments()
            ->whereIn('status', ['pending', 'late', 'submitted'])
            ->update(['status' => 'cancelled']);

        if ($wasApproved && $tour) {
            $tour->update([
                'cupos_disponibles' => min((int) $tour->cupos_totales, ((int) $tour->cupos_disponibles) + 1),
            ]);
        }

        return redirect()
            ->route('admin.bookings.show', $booking->id)
            ->with('status', 'La reservación fue cancelada correctamente.' . ($wasApproved ? ' Se liberó un cupo.' : ''));
    }

    public function submitPayment(Request $request, BookingPayment $payment)
    {
        if (!auth()->check() || auth()->id() !== $payment->booking->user_id) {
            abort(403);
        }

        $booking = $payment->booking()->with('tour', 'payments')->firstOrFail();
        $this->touchBookingPlan($booking);

        if ($booking->status === 'rejected') {
            return back()->with('status', 'Esta reserva fue cancelada y ya no puede recibir pagos.');
        }

        if (!in_array($payment->status, ['pending', 'late'], true)) {
            return back()->with('status', 'Este pago ya fue enviado o aprobado anteriormente.');
        }

        $request->validate([
            'receipt' => 'required|image|max:5120',
        ]);

        $receiptPath = $request->file('receipt')->store('payment-receipts', 'public');

        $payment->update([
            'receipt_path' => $receiptPath,
            'submitted_at' => now(),
            'status' => 'submitted',
        ]);

        return redirect()->route('bookings.payments.receipt', $payment->id);
    }

    public function approvePayment(BookingPayment $payment)
    {
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            abort(403);
        }

        $booking = $payment->booking()->with('tour', 'user', 'payments')->firstOrFail();
        $this->touchBookingPlan($booking);

        if ($booking->status === 'rejected') {
            return back()->with('status', 'La reserva ya está cancelada por falta de pago.');
        }

        if ($payment->status !== 'submitted') {
            return back()->with('status', 'Este pago no está pendiente de revisión.');
        }

        $payment->update([
            'status' => 'approved',
            'approved_at' => now(),
        ]);

        return back()->with('status', 'Pago aprobado correctamente.');
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

        $absolutePath = storage_path('app/public/' . $booking->receipt_path);

        if (request()->boolean('download')) {
            $extension = pathinfo($booking->receipt_path, PATHINFO_EXTENSION) ?: 'jpg';
            return response()->download($absolutePath, 'comprobante-reserva-' . $booking->purchase_id . '.' . $extension);
        }

        return response()->file($absolutePath);
    }

    public function paymentReceiptImage(BookingPayment $payment)
    {
        if (!auth()->check()) {
            abort(403);
        }

        $isOwner = auth()->id() === $payment->booking->user_id;
        $isAdmin = auth()->user()->role === 'admin';

        if (!$isOwner && !$isAdmin) {
            abort(403);
        }

        if (!$payment->receipt_path || !Storage::disk('public')->exists($payment->receipt_path)) {
            abort(404);
        }

        $absolutePath = storage_path('app/public/' . $payment->receipt_path);

        if (request()->boolean('download')) {
            $extension = pathinfo($payment->receipt_path, PATHINFO_EXTENSION) ?: 'jpg';
            return response()->download($absolutePath, 'comprobante-pago-' . $payment->reference . '.' . $extension);
        }

        return response()->file($absolutePath);
    }

    protected function touchBookingPlan(Booking $booking): void
    {
        $booking->ensurePaymentSchedule();
        $booking->refreshPaymentPlanStatus();
    }
}
