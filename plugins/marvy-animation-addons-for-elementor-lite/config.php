<?php

return [
    'bg-animation' => [
        'drop_animation' => [
            'class' => '\MarvyElementor\animation\MarvyDropAnimation',
            'dependency' => [
                'js' => [
                    [
                        'name' => 'marvy-drop',
                        'src' => 'assets/js/custom/marvy.drop.js'
                    ]
                ]
            ]
        ],
        'fancy_rotate' => [
            'class' => '\MarvyElementor\animation\MarvyFancyRotate',
            'dependency' => [
                'js' => [
                    [
                        'name' => 'particles',
                        'src' => 'assets/js/lib/particles.min.js'
                    ],
                    [
                        'name' => 'marvy-fancy-rotate',
                        'src' => 'assets/js/custom/marvy.fancy.rotate.js'
                    ]
                ],
            ]
        ],
        'flying_object' => [
            'class' => '\MarvyElementor\animation\MarvyFlyingObject',
            'dependency' => [
                'js' => [
                    [
                        'name' => 'marvy-flying-object',
                        'src' => 'assets/js/custom/marvy.flying.object.js'
                    ],
                ],
            ],
        ],
        'ripples_animation' => [
            'class' => '\MarvyElementor\animation\MarvyRipplesAnimation',
            'dependency' => [
                'js' => [
                    [
                        'name' => 'marvy-ripples',
                        'src' => 'assets/js/custom/marvy.ripples.js'
                    ],
                ],
            ],
        ],
        'waves_animation' => [
            'class' => '\MarvyElementor\animation\MarvyWavesAnimation',
            'dependency' => [
                'js' => [
                    [
                        'name' => 'three',
                        'src' => 'assets/js/lib/three.min.js'
                    ],
                    [
                        'name' => 'vanta-waves',
                        'src' => 'assets/js/lib/vanta.waves.min.js'
                    ],
                    [
                        'name' => 'marvy-waves',
                        'src' => 'assets/js/custom/marvy.waves.js'
                    ]
                ],
            ]
        ],
        'rings_animation' => [
            'class' => '\MarvyElementor\animation\MarvyRingsAnimation',
            'dependency' => [
                'js' => [
                    [
                        'name' => 'three',
                        'src' => 'assets/js/lib/three.min.js'
                    ],
                    [
                        'name' => 'vanta-rings',
                        'src' => 'assets/js/lib/vanta.rings.min.js'
                    ],
                    [
                        'name' => 'marvy-rings',
                        'src' => 'assets/js/custom/marvy.rings.js'
                    ]
                ],
            ],
        ],
        'topology_animation' => [
            'class' => '\MarvyElementor\animation\MarvyTopologyAnimation',
            'dependency' => [
                'js' => [
                    [
                        'name' => 'three',
                        'src' => 'assets/js/lib/three.min.js'
                    ],
                    [
                        'name' => 'p5',
                        'src' => 'assets/js/lib/p5.min.js'
                    ],
                    [
                        'name' => 'vanta-topology',
                        'src' => 'assets/js/lib/vanta.topology.min.js'
                    ],
                    [
                        'name' => 'marvy-topology',
                        'src' => 'assets/js/custom/marvy.topology.js'
                    ]
                ],
            ],
        ],
        'gradient_animation' => [
            'class' => '\MarvyElementor\animation\MarvyGradientAnimation',
            'dependency' => [
                'js' => [
                    [
                        'name' => 'marvy-gradientBackground',
                        'src' => 'assets/js/custom/marvy.gradient.animation.js'
                    ]
                ],
            ],
        ],
        'snow_animation' => [
            'class' => '\MarvyElementor\animation\MarvySnowAnimation',
            'dependency' => [
                'js' => [
                    [
                        'name' => 'marvy-snow-animation',
                        'src' => 'assets/js/custom/marvy-snow-animation.js'
                    ]
                ],
            ],
        ],
        'firework_animation' => [
            'class' => '\MarvyElementor\animation\MarvyFireworkAnimation',
            'dependency' => [
                'js' => [
                    [
                        'name' => 'marvy-firework-animation',
                        'src' => 'assets/js/custom/marvy.firework.animation.js'
                    ]
                ],
            ],
        ],
        'cloud_animation' => [
            'class' => '\MarvyElementor\animation\MarvyCloudAnimation',
            'dependency' => [
                'js' => [
                    [
                        'name' => 'three',
                        'src' => 'assets/js/lib/three.min.js'
                    ],
                    [
                        'name' => 'cloud-animation',
                        'src' => 'assets/js/lib/marvy.clouds2.min.js'
                    ],
                    [
                        'name' => 'marvy-cloud-animation',
                        'src' => 'assets/js/custom/marvy.cloud.animation.js'
                    ]
                ],
            ],
        ]
    ]
];
