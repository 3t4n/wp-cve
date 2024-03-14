<?php
return [
    'textDomain' => 'lwptdr',
    'bootstrap' => [
        'admin',
        'front',
    ],
    'pluginsLoadedBootstrap' => [],
    'components' => [
        'admin' => \luckywp\termDescriptionRichText\admin\Admin::className(),
        'front' => \luckywp\termDescriptionRichText\front\Front::className(),
    ],
];
