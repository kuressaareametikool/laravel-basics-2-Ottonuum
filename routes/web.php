<?php

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
    return view('welcome');
});

Route::get('/mailable', function () {
    $url = 'https://tahvel.edu.ee/hois_back/timetableevents/timetableByGroup/38?from=2023-10-30T00:00:00Z&studentGroups=5901&thru=2023-11-05T00:00:00Z';
    $startDate = Carbon::now()->startOfWeek();
    $endDate = Carbon::now()->endOfWeek();
    $query = [
        'from' => $startDate->toIsoString(),
        'studentGroups' => '5901',
        'thru' => $endDate->toIsoString(),
    ];

    $response = Http::get($url, $query);
    $timetableEvents = collect($response->json()['timetableEvents'])
        ->sortBy(['date', 'timeStart'])
        ->groupBy(fn($event) => Carbon::parse($event['date'])->locale('et')->dayName);

    return new Timetable($timetableEvents, $startDate, $endDate);
});