<?php

class WPT_DiviContentRestrictorExtension extends DiviExtension
{

    /**
     * The gettext domain for the extension's translations.
     *
     * @var string
     * @since 1.0.0
     */
    public $gettext_domain = 'wpt-wpt-content-restrictor-extension';

    /**
     * The extension's WP Plugin name.
     *
     * @var string
     * @since 1.0.0
     */
    public $name = 'wpt-content-restrictor-extension';

    /**
     * The extension's version
     *
     * @var string
     * @since 1.0.0
     */
    public $version = '1.0.0';

    public $container;

    /**
     * WPT_DiviContentRestrictorExtension constructor.
     *
     * @param string $name
     * @param array  $args
     */
    public function __construct(
        $name = 'wpt-content-restrictor-extension',
        $args = [],
        $container
    ) {
        $this->plugin_dir     = $container['plugin_dir'] . '/';
        $this->plugin_dir_url = $container['plugin_url'] . '/';
        $this->container      = $container;
        parent::__construct($name, $args);
    }
}
