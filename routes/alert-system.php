<?php

use Illuminate\Support\Facades\Route;
use Beartropy\AlertSystem\Livewire\ManageLogs;
use Beartropy\AlertSystem\Livewire\ManageTypes;
use Beartropy\AlertSystem\Livewire\ManageChannels;
use Beartropy\AlertSystem\Livewire\ManageRecipients;

Route::middleware(config('alert-system.route_middlewares'))->prefix(config('alert-system.route_prefix'))->group(function () {
    Route::get('/recipients', ManageRecipients::class)->name('alerts.recipients');
    Route::get('/types', ManageTypes::class)->name('alerts.types');
    Route::get('/channels', ManageChannels::class)->name('alerts.channels');
    Route::get('/dashboard', ManageLogs::class)->name('alerts.dashboard');
});
