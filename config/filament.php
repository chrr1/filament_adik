<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Panels
    |--------------------------------------------------------------------------
    |
    | Define the panels available in the Filament admin panel.
    |
    */

    'panels' => [
        [
            'id' => 'admin',
            'label' => 'Admin Panel',
            'icon' => 'heroicon-o-home',
            'resources' => [
                App\Filament\Resources\UserResource::class,
                App\Filament\Resources\PelangganResource::class,
                App\Filament\Resources\ProdukResource::class,
                App\Filament\Resources\PenjualanResource::class,
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Broadcasting
    |--------------------------------------------------------------------------
    |
    | Configuration for broadcasting real-time notifications.
    |
    */

    'broadcasting' => [
        // Add your broadcasting settings here
    ],

    /*
    |--------------------------------------------------------------------------
    | Default Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Define the filesystem disk for storing files.
    |
    */

    'default_filesystem_disk' => env('FILAMENT_FILESYSTEM_DISK', 'public'),

    /*
    |--------------------------------------------------------------------------
    | Assets Path
    |--------------------------------------------------------------------------
    |
    | The directory where Filament's assets will be published.
    |
    */

    'assets_path' => null,

    /*
    |--------------------------------------------------------------------------
    | Cache Path
    |--------------------------------------------------------------------------
    |
    | Directory for storing cache files used to optimize components.
    |
    */

    'cache_path' => base_path('bootstrap/cache/filament'),

    /*
    |--------------------------------------------------------------------------
    | Livewire Loading Delay
    |--------------------------------------------------------------------------
    |
    | Sets the delay before loading indicators appear.
    |
    */

    'livewire_loading_delay' => 'default',

];
