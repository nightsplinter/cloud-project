<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('app:daily-api-call --count=5')
    ->daily()
    ->at('18:00')
    ->withoutOverlapping();
