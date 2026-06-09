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

Route::get('/', [App\Http\Controllers\Admin\DashboardController::class, 'home'])->name('dashboard')->middleware(['auth', 'can:panel.admin']);

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

Route::resource('pqrs', App\Http\Controllers\Admin\PqrsController::class)->middleware('auth')->only(['index', 'update', 'destroy' => 'pqrs.destroy']);
Route::get('pqrs/create', [App\Http\Controllers\Admin\PqrsController::class, 'create'])->name('pqrs.create');
Route::post('pqrs', [App\Http\Controllers\Admin\PqrsController::class, 'store'])->name('pqrs.store');

Route::get('pqrs/show/admin/{pqrs}', [App\Http\Controllers\Admin\PqrsController::class, 'showTicket'])->name('pqrs.show-admin')->middleware('auth');

Route::get('pqrs/{pqrs}/attachments', [App\Http\Controllers\Admin\PqrsController::class, 'attachments'])->name('pqrs.attachments')->middleware('auth');

Route::post('pqrs/{pqrs}/assign', [App\Http\Controllers\Admin\PqrsController::class, 'assignContractor'])->name('pqrs.assign')->middleware('auth');

//Validar el ticket por parte del operador
Route::put('pqrs/{ticket}/validate', [App\Http\Controllers\Admin\PqrsController::class, 'validateTicket'])->name('pqrs.validate')->middleware('auth');

Route::resource('contractors', App\Http\Controllers\Admin\ContractorController::class)->middleware('auth');
//Ruta para la vista para la vista de administración de contratistas con Livewire
Route::get('contractors-admin', [App\Http\Controllers\Admin\ContractorController::class, 'admin'])->name('contractors.admin')->middleware('auth');
//Ruta para aceptar el trabajo por parte del contratista
Route::post('contractors/accept-ticket/{ticket}', [App\Http\Controllers\Admin\ContractorController::class, 'acceptAssignment'])->name('contractors.accept-ticket')->middleware('auth');
//Ruta para generar la cotización por parte del contratista
Route::post('contractors/quote-ticket/{ticket}', [App\Http\Controllers\Admin\ContractorController::class, 'generateQuote'])->name('contractors.quote-ticket')->middleware('auth');
//Ruta para que el arrendatario revise la cotización y acepte o rechace
Route::get('pqrs/view-quote/{ticket}', [App\Http\Controllers\Admin\PqrsController::class, 'viewQuote'])->name('tenant.view-quote');
Route::post('/pqrs/quote/approve/{quote_id}', [App\Http\Controllers\Admin\PqrsController::class, 'approveQuote'])->name('pqrs.quote.approve');
Route::post('/pqrs/quote/reject/{quote_id}', [App\Http\Controllers\Admin\PqrsController::class, 'rejectQuote'])->name('pqrs.quote.reject');
//Ruta para enviar agenda de visita al contratista
Route::post('pqrs/schedule-visit/{ticket}', [App\Http\Controllers\Admin\PqrsController::class, 'scheduleVisit'])->name('pqrs.schedule.store')->middleware('auth');
//Ruta para mostrar la agenda de visita al inquilino
Route::get('pqrs/show-schedule/{ticket}', [App\Http\Controllers\Admin\PqrsController::class, 'showSchedule'])->name('pqrs.show-schedule');
//Ruta para que el inquilino o propietario confirme la visita programada
Route::post('pqrs/confirm-schedule/{ticket}', [App\Http\Controllers\Admin\PqrsController::class, 'confirmSchedule'])->name('tenant.schedule.confirm');
//Ruta para registrar la visita
Route::get('pqrs/report-visit/{ticket}', [App\Http\Controllers\Admin\PqrsController::class, 'registerScheuldeView'])->name('contractors.register-schedule-view')->middleware('auth');
//Ruta para confirmar la visita por parte del contratista
Route::post('contractors/register-scheulde/{ticket}', [App\Http\Controllers\Admin\PqrsController::class, 'registerScheuldeContractor'])->name('contractors.register-scheulde')->middleware('auth');
//Ruta para validar la visita por parte del operador del sistema
Route::post('pqrs/validate-visit/{ticket}', [App\Http\Controllers\Admin\PqrsController::class, 'validateVisit'])->name('pqrs.validate-visit')->middleware('auth');

Route::post('pqrs/assignments/{ticket}', [App\Http\Controllers\Admin\PqrsController::class, 'assignTicket'])->name('pqrs.assignments')->middleware('auth');

//Ruta para que el contratista inicie la ejecucion del trabajo de reparación
Route::put('pqrs/work-start-schedule/{ticket}', [App\Http\Controllers\Admin\ContractorController::class, 'startWork'])->name('start.work-schedule')->middleware('auth');

//Ruta para finalizar el ticket por parte del contratista
Route::put('/tickets/{ticket}/finalizar', [App\Http\Controllers\Admin\ContractorController::class, 'finishTicket'])->name('contractors.finish-ticket');

//Ruta para que el operador cierre el ticket
Route::post('pqrs/ticket/close/{ticket}', [App\Http\Controllers\Admin\PqrsController::class, 'closeTicket'])->name('pqrs.close')->middleware('auth');

//Gestion de proietarios
Route::get('owners/list', [App\Http\Controllers\Admin\OwnerController::class, 'index'])->name('owners.list')->middleware('auth');

// Ruta pública para que el arrendatario consulte su estado
Route::get('/ticket-status/{ticket}', [App\Http\Controllers\Admin\PqrsController::class, 'publicStatus'])->name('pqrs.public-status');