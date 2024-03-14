<?php
return [
    'bootstrap' => [
        'activation',
        'admin',
        'editorBlock',
        'front',
        'mcePlugin',
        'shortcode',
    ],
    'pluginsLoadedBootstrap' => [
        'settings',
    ],
    'components' => [
        'activation' => \luckywp\tableOfContents\plugin\Activation::class,
        'admin' => \luckywp\tableOfContents\admin\Admin::class,
        'front' => \luckywp\tableOfContents\front\Front::class,
        'mcePlugin' => \luckywp\tableOfContents\plugin\mcePlugin\McePlugin::class,
        'options' => \luckywp\tableOfContents\core\wp\Options::class,
        'rate' => \luckywp\tableOfContents\admin\Rate::class,
        'request' => \luckywp\tableOfContents\core\base\Request::class,
        'settings' => [
            'class' => \luckywp\tableOfContents\plugin\Settings::class,
            'initGroupsConfigFile' => __DIR__ . '/settings.php',
        ],
        'editorBlock' => \luckywp\tableOfContents\plugin\editorBlock\EditorBlock::class,
        'shortcode' => \luckywp\tableOfContents\plugin\Shortcode::class,
        'view' => \luckywp\tableOfContents\core\base\View::class,
    ],
];
