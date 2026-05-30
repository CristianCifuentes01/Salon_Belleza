<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class UsuariosController extends Controller
{
    /**
     * Mostrar la lista de usuarios (admin).
     */
    public function index(): View
    {
        $usuarios = User::orderBy('created_at', 'desc')->get();
        return view('usuario.index', compact('usuarios'));
    }

    /**
     * Mostrar el formulario para crear un usuario.
     */
    public function create(): View
    {
        return view('usuario.create');
    }

    /**
     * Guardar el nuevo usuario en la base de datos.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'nombre' => 'required|string|max:60',
            'apellido' => 'required|string|max:60',
            'email' => 'required|email|max:30|unique:users,email',
            'password' => 'required|string|min:6',
            'telefono' => 'nullable|string|max:10',
            'role' => 'required|in:admin,empleado,cliente',
        ]);

        User::create([
            'nombre' => $request->nombre,
            'apellido' => $request->apellido,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'telefono' => $request->telefono,
            'admin' => $request->role === 'admin',
            'role' => $request->role,
            'confirmado' => 1,
            'token' => '',
            'api_token' => hash('sha256', Str::random(60) . $request->email),
        ]);

        return redirect()->route('admin.usuarios')
            ->with('success', 'Usuario creado exitosamente.');
    }

    /**
     * Mostrar los datos de un usuario específico.
     */
    public function show(User $usuario): View
    {
        $usuario->load('citas.servicios');
        return view('usuario.show', compact('usuario'));
    }

    /**
     * Mostrar el formulario para editar un usuario.
     */
    public function edit(User $usuario): View
    {
        return view('usuario.edit', compact('usuario'));
    }

    /**
     * Actualizar los datos del usuario en la base de datos.
     */
    public function update(Request $request, User $usuario): RedirectResponse
    {
        $request->validate([
            'nombre' => 'required|string|max:60',
            'apellido' => 'required|string|max:60',
            'email' => 'required|email|max:30|unique:users,email,' . $usuario->id,
            'telefono' => 'nullable|string|max:10',
            'role' => 'required|in:admin,empleado,cliente',
            'regenerar_api_token' => 'sometimes|boolean',
        ]);

        $data = $request->only(['nombre', 'apellido', 'email', 'telefono']);
        $data['role'] = $request->role;
        $data['admin'] = $request->role === 'admin';

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        if ($request->boolean('regenerar_api_token')) {
            $data['api_token'] = hash('sha256', Str::random(60) . $request->email);
        }

        $usuario->update($data);

        return redirect()->route('admin.usuarios')
            ->with('success', 'Usuario actualizado exitosamente.');
    }

    /**
     * Eliminar un usuario.
     */
    public function destroy(User $usuario): RedirectResponse
    {
        $usuario->delete();

        return redirect()->route('admin.usuarios')
            ->with('success', 'Usuario eliminado exitosamente.');
    }
}
