<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('app:daily-api-call 10')
    ->daily()
    ->at('18:00')
    ->withoutOverlapping();
