<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Authentication Module Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may define all of the configuration settings for the Authentication module.
    |
    */

    'name' => 'Authentication',
    'version' => '1.0.0',
    'description' => 'Authentication module for the application',
    'author' => 'Your Name',
    'email' => 'your.email@example.com',
    'website' => 'https://example.com',

    /*
    |--------------------------------------------------------------------------
    | Module Settings
    |--------------------------------------------------------------------------
    |
    | Here you may define all of the settings for the Authentication module.
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
    | Here you may list module names that Authentication requires.
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
    | Here you may define all of the permissions for the Authentication module.
    |
    */

    'permissions' => [
        'view' => 'View Authentication',
        'create' => 'Create Authentication',
        'edit' => 'Edit Authentication',
        'delete' => 'Delete Authentication',
    ],
]; 
