<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ServicioController;
use App\Http\Controllers\CitaController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UsuariosController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Rutas Públicas
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    $servicios = \App\Models\Servicio::activo()->orderBy('nombre')->take(6)->get();
    return view('welcome', compact('servicios'));
})->name('home');

Route::get('/servicios', [ServicioController::class, 'index'])->name('servicios.index');

/*
|--------------------------------------------------------------------------
| Rutas Autenticadas
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    // Dashboard del usuario
    Route::get('/dashboard', function () {
        $misCitas = auth()->user()->citas()
            ->with('servicios')
            ->orderBy('fecha', 'desc')
            ->limit(5)
            ->get();
        return view('dashboard', compact('misCitas'));
    })->name('dashboard');

    // Perfil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Citas del usuario
    Route::get('/citas/disponibilidad', [CitaController::class, 'disponibilidad'])->name('citas.disponibilidad');
    Route::resource('citas', CitaController::class)->except(['edit', 'update']);
});

/*
|--------------------------------------------------------------------------
| Rutas de Administración
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {

    // Dashboard admin
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // Gestión de servicios (CRUD completo)
    Route::resource('servicios', ServicioController::class)->except(['index', 'show']);

    // Listado admin de servicios
    Route::get('/servicios', [AdminController::class, 'servicios'])->name('servicios.index');

    // Gestión de citas
    Route::get('/citas', [AdminController::class, 'citas'])->name('citas');
    Route::patch('/citas/{cita}/estado', [AdminController::class, 'cambiarEstadoCita'])->name('citas.estado');

    // Gestión de usuarios
    Route::get('/usuarios', [AdminController::class, 'usuarios'])->name('usuarios');
    Route::get('/usuarios/crear', [UsuariosController::class, 'create'])->name('usuarios.create');
    Route::post('/usuarios', [UsuariosController::class, 'store'])->name('usuarios.store');
    Route::get('/usuarios/{usuario}/editar', [UsuariosController::class, 'edit'])->name('usuarios.edit');
    Route::put('/usuarios/{usuario}', [UsuariosController::class, 'update'])->name('usuarios.update');
    Route::delete('/usuarios/{usuario}', [UsuariosController::class, 'destroy'])->name('usuarios.destroy');
    Route::patch('/usuarios/{user}/toggle', [AdminController::class, 'toggleUsuario'])->name('usuarios.toggle');

    // Reportes
    Route::get('/reportes', [AdminController::class, 'reportes'])->name('reportes');
    Route::get('/reportes/csv', [AdminController::class, 'exportarCSV'])->name('reportes.csv');
});

require __DIR__.'/auth.php';