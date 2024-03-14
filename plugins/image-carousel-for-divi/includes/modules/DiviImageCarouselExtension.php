<?php
namespace WPT_Divi_Carousel_Images_Modules;

use DiviExtension;

class DiviImageCarouselExtension extends DiviExtension
{
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
    public $name = 'et_pb_wptools_image_carousel';

    /**
     * The extension's version
     *
     * @var string
     * @since 1.0.0
     */
    public $version;

    /**
     * container
     */
    protected $container;

    /**
     * Constructor.
     *
     * @param string $name
     * @param array  $args
     */
    public function __construct($container)
    {
        $this->gettext        = $container['slug'];
        $this->version        = $container['version'];
        $this->plugin_dir     = $container['dir'] . '/';
        $this->plugin_dir_url = $container['url'] . '/';

        $this->container = $container;
        parent::__construct($this->name, []);
    }
}
