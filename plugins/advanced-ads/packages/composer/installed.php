<?php return array(
    'root' => array(
        'name' => 'webgilde/advanced-ads',
        'pretty_version' => '1.42.1',
        'version' => '1.42.1.0',
        'reference' => NULL,
        'type' => 'wordpress-plugin',
        'install_path' => __DIR__ . '/../../',
        'aliases' => array(),
        'dev' => false,
    ),
    'versions' => array(
        'advanced-ads/framework' => array(
            'pretty_version' => 'dev-main',
            'version' => 'dev-main',
            'reference' => '95e385daf3e3f11125711f43c5f7c437e00b075d',
            'type' => 'wordpress-plugin',
            'install_path' => __DIR__ . '/../advanced-ads/framework',
            'aliases' => array(
                0 => '9999999-dev',
            ),
            'dev_requirement' => false,
        ),
        'mobiledetect/mobiledetectlib' => array(
            'pretty_version' => '2.8.45',
            'version' => '2.8.45.0',
            'reference' => '96aaebcf4f50d3d2692ab81d2c5132e425bca266',
            'type' => 'library',
            'install_path' => __DIR__ . '/../mobiledetect/mobiledetectlib',
            'aliases' => array(),
            'dev_requirement' => false,
        ),
        'webgilde/advanced-ads' => array(
            'pretty_version' => '1.42.1',
            'version' => '1.42.1.0',
            'reference' => NULL,
            'type' => 'wordpress-plugin',
            'install_path' => __DIR__ . '/../../',
            'aliases' => array(),
            'dev_requirement' => false,
        ),
    ),
);
