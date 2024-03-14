<?php
namespace WPT_Ultimate_Divi_Carousel;

use DiviExtension;

class DiviToolkitExtension extends DiviExtension
{

    protected $container;

    /**
     * The gettext domain for the extension's translations.
     *
     * @var string
     * @since 1.0.0
     */
    public $gettext_domain;

    /**
     * The extension's WP Plugin name.
     *
     * @var string
     * @since 1.0.0
     */
    public $name = 'et_pb_wpt_divi_toolkit';

    /**
     * The extension's version
     *
     * @var string
     * @since 1.0.0
     */
    public $version;

    /**
     * Constructor.
     *
     * @param string $name
     * @param array  $args
     */
    public function __construct($container)
    {
        $this->gettext        = $container['plugin_slug'];
        $this->version        = $container['plugin_version'];
        $this->plugin_dir     = $container['plugin_dir'] . '/';
        $this->plugin_dir_url = $container['plugin_url'] . '/';

        $this->container = $container;
        parent::__construct($this->name, []);
    }
}
