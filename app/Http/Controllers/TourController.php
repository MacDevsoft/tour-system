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
        return view('tours.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tour $tour)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tour $tour)
    {
        //
    }
}
