<?php

    use Illuminate\Support\Facades\Schedule;

    Schedule::command('calculate:daily-statistics')->dailyAt('23:59');
