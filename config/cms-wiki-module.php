<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Home
    |--------------------------------------------------------------------------
    |
    | The default home page for the wiki. Set slug to use for the home page.
    |
    */

    'home' => [
        'slug' => 'home',
    ],

    /*
    |--------------------------------------------------------------------------
    | Menu
    |--------------------------------------------------------------------------
    |
    | Configuration of the wiki presence in the menu.
    | The basics may be set here. Use the cms-modules menu configuration
    | where customization beyond these options is required.
    |
    */

    'menu' => [
        'home' => [
            'label'            => 'Documentation',
            'label_translated' => 'wiki.menu.home',
            'icon'             => null,
        ],
    ],

];
