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

    /*
    |--------------------------------------------------------------------------
    | Markdown
    |--------------------------------------------------------------------------
    |
    | Configuration determining how markdown is parsed and rendered.
    |
    */

    'markdown' => [

        // The markdown flavor to parse.
        // Available strategies: github, traditional and extra.
        // See https://github.com/cebe/markdown for more information on each.
        'strategy' => 'github',

    ],

];
