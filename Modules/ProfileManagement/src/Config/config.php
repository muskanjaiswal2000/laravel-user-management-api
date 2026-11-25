<?php

return [
    /*
    |--------------------------------------------------------------------------
    | ProfileManagement Module Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may define all of the configuration settings for the ProfileManagement module.
    |
    */

    'name' => 'ProfileManagement',
    'version' => '1.0.0',
    'description' => 'ProfileManagement module for the application',
    'author' => 'Your Name',
    'email' => 'your.email@example.com',
    'website' => 'https://example.com',

    /*
    |--------------------------------------------------------------------------
    | Module Settings
    |--------------------------------------------------------------------------
    |
    | Here you may define all of the settings for the ProfileManagement module.
    |
    */

    'settings' => [
        'enabled' => true,
        'debug' => false,
        'cache' => true,
        'cache_ttl' => 3600,
    ],

    /*
    |--------------------------------------------------------------------------
    | Required Modules
    |--------------------------------------------------------------------------
    |
    | Here you may list module names that ProfileManagement requires.
    |
    */

    'dependents' => [
        // 'Core',
        // 'Auth',
    ],

    /*
    |--------------------------------------------------------------------------
    | Module Permissions
    |--------------------------------------------------------------------------
    |
    | Here you may define all of the permissions for the ProfileManagement module.
    |
    */

    'permissions' => [
        'view' => 'View ProfileManagement',
        'create' => 'Create ProfileManagement',
        'edit' => 'Edit ProfileManagement',
        'delete' => 'Delete ProfileManagement',
    ],
]; 
