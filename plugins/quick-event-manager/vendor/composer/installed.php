<?php return array(
    'root' => array(
        'pretty_version' => 'dev-master',
        'version' => 'dev-master',
        'type' => 'wordpress-plugin',
        'install_path' => __DIR__ . '/../../',
        'aliases' => array(),
        'reference' => 'f39e1bc3697f86ce216ea720b46a8e945cc660ca',
        'name' => 'fullworks/quick-event-manager',
        'dev' => true,
    ),
    'versions' => array(
        'composer/installers' => array(
            'pretty_version' => 'v1.0.12',
            'version' => '1.0.12.0',
            'type' => 'composer-installer',
            'install_path' => __DIR__ . '/./installers',
            'aliases' => array(),
            'reference' => '4127333b03e8b4c08d081958548aae5419d1a2fa',
            'dev_requirement' => false,
        ),
        'freemius/wordpress-sdk' => array(
            'pretty_version' => 'dev-master',
            'version' => 'dev-master',
            'type' => 'library',
            'install_path' => __DIR__ . '/../freemius/wordpress-sdk',
            'aliases' => array(
                0 => '9999999-dev',
            ),
            'reference' => 'ea3a288e52ff74c755b3c1eb857f41c5a2d899e5',
            'dev_requirement' => false,
        ),
        'fullworks/quick-event-manager' => array(
            'pretty_version' => 'dev-master',
            'version' => 'dev-master',
            'type' => 'wordpress-plugin',
            'install_path' => __DIR__ . '/../../',
            'aliases' => array(),
            'reference' => 'f39e1bc3697f86ce216ea720b46a8e945cc660ca',
            'dev_requirement' => false,
        ),
        'gamajo/template-loader' => array(
            'pretty_version' => 'dev-master',
            'version' => 'dev-master',
            'type' => 'library',
            'install_path' => __DIR__ . '/../gamajo/template-loader',
            'aliases' => array(),
            'reference' => '05057216f60baebfc4939e1a2b9aae6e89d25c91',
            'dev_requirement' => false,
        ),
        'shama/baton' => array(
            'dev_requirement' => false,
            'replaced' => array(
                0 => '*',
            ),
        ),
    ),
);
