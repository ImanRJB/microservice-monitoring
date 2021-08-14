<?php

use Milyoona\MicroserviceMonitor\Http\Controllers\HistoryController;
use Milyoona\MicroserviceMonitor\Http\Controllers\MicroserviceController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Tool API Routes
|--------------------------------------------------------------------------
|
| Here is where you may register API routes for your tool. These routes
| are loaded by the ServiceProvider of your tool. They are protected
| by your tool's "Authorize" middleware by default. Now, go build!
|
*/

Route::get('/microservices', MicroserviceController::class . '@index');
Route::get('/supervisor/start/{microservice}', MicroserviceController::class . '@supervisorStart');
Route::get('/supervisor/restart/{microservice}', MicroserviceController::class . '@supervisorRestart');
Route::get('/supervisor/stop/{microservice}', MicroserviceController::class . '@supervisorStop');

