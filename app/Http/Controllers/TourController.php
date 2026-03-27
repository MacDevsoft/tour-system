<?php

namespace App\Http\Controllers;

use App\Models\Tour;
use Illuminate\Http\Request;

class TourController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    
    public function index()
    {
         $tours = Tour::all();

    return view('tours.index', compact('tours'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            abort(403);
        }

        return view('tours.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            abort(403);
        }

        Tour::create([
        'nombre' => $request->nombre,
        'descripcion' => $request->descripcion,
        'precio_total' => $request->precio_total,
        'anticipo' => $request->anticipo,
        'cupos_totales' => $request->capacidad,
        'cupos_disponibles' => $request->capacidad,
        'ubicacion' => $request->ubicacion,
        'punto_encuentro' => $request->punto_encuentro,
        'hora_salida' => $request->hora_salida,
        'transporte' => $request->transporte,
        'capacidad' => $request->capacidad,
        'fecha_inicio' => $request->fecha_inicio,
        'fecha_fin' => $request->fecha_fin,
    ]);

    return redirect('/tours');
    }

    /**
     * Display the specified resource.
     */
    public function show(Tour $tour)
    {
        return view('tours.show', compact('tour'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tour $tour)
    {
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            abort(403);
        }

        return view('tours.edit', compact('tour'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tour $tour)
    {
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            abort(403);
        }

        $data = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'precio_total' => 'nullable|numeric',
            'anticipo' => 'nullable|numeric',
            'capacidad' => 'nullable|integer',
            'ubicacion' => 'nullable|string',
            'punto_encuentro' => 'nullable|string',
            'hora_salida' => 'nullable|string',
            'transporte' => 'nullable|string',
            'fecha_inicio' => 'nullable|date',
            'fecha_fin' => 'nullable|date',
        ]);

        $tour->update([
            'nombre' => $data['nombre'],
            'descripcion' => $data['descripcion'] ?? $tour->descripcion,
            'precio_total' => $data['precio_total'] ?? $tour->precio_total,
            'anticipo' => $data['anticipo'] ?? $tour->anticipo,
            'capacidad' => $data['capacidad'] ?? $tour->capacidad,
            'cupos_totales' => $data['capacidad'] ?? $tour->cupos_totales,
            'ubicacion' => $data['ubicacion'] ?? $tour->ubicacion,
            'punto_encuentro' => $data['punto_encuentro'] ?? $tour->punto_encuentro,
            'hora_salida' => $data['hora_salida'] ?? $tour->hora_salida,
            'transporte' => $data['transporte'] ?? $tour->transporte,
            'fecha_inicio' => $data['fecha_inicio'] ?? $tour->fecha_inicio,
            'fecha_fin' => $data['fecha_fin'] ?? $tour->fecha_fin,
        ]);

        // Ajustar cupos_disponibles si la capacidad disminuyó
        if (isset($data['capacidad'])) {
            $newCap = (int) $data['capacidad'];
            if ($tour->cupos_disponibles > $newCap) {
                $tour->cupos_disponibles = $newCap;
                $tour->save();
            }
        }

        return redirect()->route('tours.show', $tour->id)->with('status', 'Tour actualizado correctamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tour $tour)
    {
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            abort(403);
        }

        $tour->delete();

        return redirect('/tours')->with('status', 'Tour eliminado correctamente');
    }

    /**
     * Toggle tour enabled/disabled status
     */
    public function toggle(Tour $tour)
    {
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            abort(403);
        }

        $tour->update(['is_enabled' => !$tour->is_enabled]);

        return back()->with('status', $tour->is_enabled ? 'Tour habilitado' : 'Tour deshabilitado');
    }
}
