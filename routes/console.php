<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Models\NotifikasiKenaikan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::call(function () {
    NotifikasiKenaikan::generateH30();
})->dailyAt('07:00')
  ->name('generate-notifikasi-h30')
  ->withoutOverlapping();
