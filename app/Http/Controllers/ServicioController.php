<?php

namespace App\Http\Controllers;

use App\Models\Servicio;
use App\Http\Requests\ServicioRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ServicioController extends Controller
{
    /**
     * Mostrar el catálogo público de servicios.
     */
    public function index(): View
    {
        $servicios = Servicio::activo()->orderBy('nombre')->get();
        return view('servicios.index', compact('servicios'));
    }

    /**
     * Mostrar el formulario para crear un nuevo servicio.
     */
    public function create(): View
    {
        return view('servicios.create');
    }

    /**
     * Almacenar un nuevo servicio en la base de datos.
     */
    public function store(ServicioRequest $request): RedirectResponse
    {
        Servicio::create($request->validated());

        return redirect()->route('admin.servicios.index')
            ->with('success', 'Servicio creado exitosamente.');
    }

    /**
     * Mostrar el formulario para editar un servicio.
     */
    public function edit(Servicio $servicio): View
    {
        return view('servicios.edit', compact('servicio'));
    }

    /**
     * Actualizar un servicio en la base de datos.
     */
    public function update(ServicioRequest $request, Servicio $servicio): RedirectResponse
    {
        $servicio->update($request->validated());

        return redirect()->route('admin.servicios.index')
            ->with('success', 'Servicio actualizado exitosamente.');
    }

    /**
     * Eliminar un servicio de la base de datos.
     */
    public function destroy(Servicio $servicio): RedirectResponse
    {
        $servicio->delete();

        return redirect()->route('admin.servicios.index')
            ->with('success', 'Servicio eliminado exitosamente.');
    }
}
