<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [App\Http\Controllers\Admin\DashboardController::class, 'home'])->name('dashboard')->middleware('auth');

//Auth::routes(['register' => false]);
Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
//Inventarios
Route::resource('inventarios', App\Http\Controllers\Admin\InventarioController::class)->middleware('auth');
Route::get('inventarios/create/{tipo_de_propiedad}', [App\Http\Controllers\Admin\InventarioController::class, 'create'])->name('inventarios.create')->middleware('auth');
//Observaciones
Route::resource('observaciones-inventarios', App\Http\Controllers\Admin\ObservacionAdicionalController::class)->middleware('auth');
Route::get('show/observation/inventory/{inventario}', [App\Http\Controllers\Admin\InventarioController::class, 'verObservaciones'])->name('ver-observacion')->middleware('auth');

Route::get('/inventarios/informacion-de-la-propiedad/{inventario}',[App\Http\Controllers\Admin\InventarioController::class, 'informacionPropiedad'])->name('informacionPropiedad')->middleware('auth');
Route::get('/inventarios/informacion-de-la-propiedad/observaciones/{propiedad}',[App\Http\Controllers\Admin\InventarioController::class, 'observacionPropiedad'])->name('observacionPropiedad')->middleware('auth');

Route::get('/informacion-propiedad/habitaciones/{propiedad}',[App\Http\Controllers\Admin\InventarioController::class, 'informacionHabitacion'])->name('informacionHabitaciones')->middleware('auth');
//Infomacion de baños
Route::get('/informacion-propiedad/baños/{propiedad}',[App\Http\Controllers\Admin\InventarioController::class, 'informacionBanos'])->name('informacionBanos')->middleware('auth');
//Ver documento
Route::get('sign-document/{inventario}', [App\Http\Controllers\Admin\FirmaInventarioController::class, 'signDocument'])->name('sign-document')->middleware('auth');
Route::resource('signed-documents', App\Http\Controllers\Admin\FirmaInventarioController::class)->middleware('auth');

Route::get('view-signed-document/{inventario}', [App\Http\Controllers\Admin\FirmaInventarioController::class, 'viewSignedDocument'])->name('view-document')->middleware('auth');
Route::get('download-signed-document/{inventario}', [App\Http\Controllers\Admin\FirmaInventarioController::class, 'exportToPdf'])->name('download-document')->middleware('auth');

/* Route::resource('propiedades', App\Http\Controllers\Admin\PropiedaController::class)->middleware('auth'); */
Route::resource('habitaciones', App\Http\Controllers\Admin\HabitacionController::class)->middleware('auth');
Route::resource('condominios', App\Http\Controllers\Admin\CondominioController::class)->middleware('auth');

Route::resource('usuarios', App\Http\Controllers\Admin\UserController::class)->middleware('auth');
Route::resource('roles', App\Http\Controllers\Admin\RoleController::class)->middleware('auth');

Route::resource('entrega-de-llaves', App\Http\Controllers\Admin\EntregaLlavesController::class)->middleware('auth');
