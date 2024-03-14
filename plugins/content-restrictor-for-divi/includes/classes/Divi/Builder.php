<?php
namespace WPT\RestrictContent\Divi;

/**
 * Builder.
 */
class Builder
{
    protected $container;

    /**
     * Constructor.
     */
    public function __construct($container)
    {
        $this->container = $container;
    }

    /**
     * Triggered when divi framework is loaded.
     */
    public function on_framework_loaded()
    {
        add_filter(
            'et_pb_module_shortcode_attributes',
            [$this->container['divi_section'], 'modify_props'],
            10,
            5
        );

        add_filter(
            'et_pb_all_fields_unprocessed_et_pb_section',
            [$this->container['divi_section'], 'add_fields']
        );

        add_filter(
            'et_builder_get_parent_modules',
            [$this->container['divi_section'], 'pre_process_modules'],
            10,
            2
        );

        add_filter(
            'et_pb_module_content',
            [$this->container['divi_section'], 'process_content'],
            10,
            6
        );
    }

    /**
     * Is divi visual builder request
     */
    public function is_visual_builder_request()
    {
        // phpcs:ignore
        return (wp_doing_ajax() || isset($_GET['et_fb']));
    }

    public function extensions_init()
    {
        require_once $this->container['plugin_dir'] . '/includes/DiviContentRestrictorExtension.php';

        new \WPT_DiviContentRestrictorExtension('wpt-content-restrictor-extension', [], $this->container);
    }

}
