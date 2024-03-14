<?php

return [

    /*
     |--------------------------------------------------------------------------
     | Register css stylesheets files
     |--------------------------------------------------------------------------
     */
    'styles' => [
    ],

    /*
    |--------------------------------------------------------------------------
    | Register JavaScript files
    |--------------------------------------------------------------------------
    */
    'scripts' => [
    ],


    /*
    |--------------------------------------------------------------------------
    | View Storage Paths
    |--------------------------------------------------------------------------
    |
    | Most templating systems load templates from disk. Here you may specify
    | an array of paths that should be checked for your views. Of course
    | the usual Laravel view path has already been registered for you.
    |
    */

    'paths' => [
        \Modular\ConnectorDependencies\resource_path('views'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Compiled View Path
    |--------------------------------------------------------------------------
    |
    | This option determines where all the compiled Blade templates will be
    | stored for your application. Typically, this is within the storage
    | directory. However, as usual, you are free to change this value.
    |
    */

    'compiled' => \Modular\ConnectorDependencies\env(
        'VIEW_COMPILED_PATH',
        realpath(\Modular\ConnectorDependencies\storage_path('framework/views'))
    ),

    /*
    |--------------------------------------------------------------------------
    | View Namespaces
    |--------------------------------------------------------------------------
    |
    | Blade has an underutilized feature that allows developers to add
    | supplemental view paths that may contain conflictingly named views.
    | These paths are prefixed with a namespace to get around the conflicts.
    | A use case might be including views from within a plugin folder.
    |
    */

    'namespaces' => [
        /*
         | Given the below example, in your views use something like:
         |     @include('MyPlugin::some.view.or.partial.here')
         */
        // 'example' => WP_PLUGIN_DIR . '/example/resources/views',
    ],

];
