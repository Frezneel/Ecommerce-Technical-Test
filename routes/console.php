<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use Illuminate\Support\Facades\Log;

use App\Models\User;
use App\Models\Product;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

//Daily
Schedule::call(function () {
    //Daily count User Registered
    $userCount = User::whereDate('created_at', now()->toDateString())->count();
    //Daily count Product Registered
    $productCount = Product::whereDate('created_at', now()->toDateString())->count();
  
    Log::info("Daily Recap : User - " . $userCount . ", Product - " . $productCount);
})->dailyAt('23:59');



