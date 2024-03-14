<?php
return [
    'textDomain' => 'luckywp-glossary',
    'bootstrap' => [
        'activation',
        'admin',
        'front',
        'rewriteRules',
        'route',
        'termsArchiveShortcode',
    ],
    'pluginsLoadedBootstrap' => [
        'settings',
    ],
    'components' => [
        'activation' => \luckywp\glossary\plugin\Activation::class,
        'admin' => \luckywp\glossary\admin\Admin::class,
        'front' => \luckywp\glossary\front\Front::class,
        'options' => \luckywp\glossary\core\wp\Options::class,
        'request' => \luckywp\glossary\core\base\Request::class,
        'rewriteRules' => \luckywp\glossary\core\wp\RewriteRules::class,
        'route' => \luckywp\glossary\front\Route::class,
        'settings' => [
            'class' => \luckywp\glossary\core\wp\Settings::class,
            'initGroupsConfigFile' => __DIR__ . '/settings.php',
            'urlBase' => 'edit.php?post_type=' . \luckywp\glossary\plugin\Term::POST_TYPE . '&',
        ],
        'termsArchiveShortcode' => \luckywp\glossary\plugin\shortcodes\termsArchive\TermsArchiveShortcode::class,
        'view' => \luckywp\glossary\core\base\View::class,
    ],
    'defaultArchiveSlug' => 'glossary',
    'buyUrl' => 'https://theluckywp.com/product/glossary/?utm_source=free',
];
