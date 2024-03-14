<?php

global $allowed_html;

$allowed_html = [
    'img' => [
        'title'             => [],
        'src'               => [],
        'alt'               => [],
        'width'             => [],
        'height'            => [],
        'class'             => [],
        'data-imgtype'      => [],
        'referrerpolicy'    => [],
    ],
    'style'                     => [],
    'div'                       => [
        'class'                 => [],
        'id'                    => [],
        'data-swiper-autoplay'  => [],
    ],
    'a' => [
        'href'      => [],
        'target'    => [],
	    'class'     => []
    ],
    'p' => [
        'id'    => [],
    ],
    'span' => [
        'class' => [],
        'id'    => [],
    ],
    'strong' => [],
    'h4' => [],
	'h3' => [
		'class' => []
	],
    'br' => [],
    'iframe' => [
        'src'       => [],
        'width'     => [],
        'height'    => [],
        'style'     => [],
        'allow'     => [],
        'id'     => []

    ],
    'input' => [
        'class'     => [],
        'id'        => [],
        'type'      => [],
        'name'      => [],
        'checked'   => [],
        'value'     => [],
        'min'       => [],
        'max'       => [],
        'step'      => [],
	    'disabled'  => [],
	    'title'     => [],
    ],
    'textarea' => [
        'id'        => [],
        'name'      => [],
        'value'     => [],
        'rows'      => [],
        'cols'      => [],
	    'disabled'  => []
    ]
];
