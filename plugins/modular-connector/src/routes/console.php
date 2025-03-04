<?php

use Modular\ConnectorDependencies\Illuminate\Foundation\Inspiring;
use Modular\ConnectorDependencies\Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('z', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');
