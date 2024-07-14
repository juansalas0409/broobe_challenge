<?php

use App\Http\Controllers\MetricHistoryRunController;
use App\Http\Controllers\PageSpeedMetricController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    $categories = \App\Models\Category::all();
    $strategies = \App\Models\Strategy::all();

    return view('index')->with(['categories' => $categories, 'strategies' => $strategies]);
})->name('home');

Route::post('/metrics', [PageSpeedMetricController::class, 'getMetrics'])->name('pageSpeedMetric.getMetrics');

Route::get('/metric-history-runs', [MetricHistoryRunController::class, 'index'])->name('metricHistoryRun.index');
Route::post('/metric-history-runs', [MetricHistoryRunController::class, 'store'])->name('metricHistoryRun.store');
