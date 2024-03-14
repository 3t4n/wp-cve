<?php
namespace WPT\UltimateDiviCarousel\Divi;

use WPT_Ultimate_Divi_Carousel\DiviToolkitExtension;
use WPT_Ultimate_Divi_Carousel\TaxonomyCarousel\TaxonomyCarousel;
use WPT_Ultimate_Divi_Carousel\ImageCardCarousel\ImageCardCarousel;
use WPT_Ultimate_Divi_Carousel\WooProductCarousel\WooProductCarousel;
use WPT_Ultimate_Divi_Carousel\PostTypeCardCarousel\PostTypeCardCarousel;
use WPT_Ultimate_Divi_Carousel\ImageCardCarouselItem\ImageCardCarouselItem;
use WPT_Ultimate_Divi_Carousel\TaxonomyCarouselFullWidth\TaxonomyCarouselFullWidth;
use WPT_Ultimate_Divi_Carousel\ImageCardCarouselFullWidth\ImageCardCarouselFullWidth;
use WPT_Ultimate_Divi_Carousel\WooProductCarouselFullWidth\WooProductCarouselFullWidth;
use WPT_Ultimate_Divi_Carousel\PostTypeCardCarouselFullWidth\PostTypeCardCarouselFullWidth;
use WPT_Ultimate_Divi_Carousel\ImageCardCarouselItemFullWidth\ImageCardCarouselItemFullWidth;

/**
 * Divi.
 */
class Divi
{
    protected $container;

    /**
     * Constructor.
     */
    public function __construct($container)
    {
        $this->container = $container;
    }

    public function is_ajax()
    {
        return defined('DOING_AJAX') && DOING_AJAX;
    }

    /**
     * Register divi extension
     *
     * @return [type] [description]
     */
    public function divi_extensions_init()
    {
        new DiviToolkitExtension($this->container);
    }

    /**
     * Enqueue assets for divi modules
     */
    public function enqueue_assets()
    {
        if ($this->is_fb()) {
            $this->container['swiper_divi']->enqueue_assets();
            wp_enqueue_style('wpdt-divi-backend', $this->container['plugin_url'] . "/styles/backend-style.min.css", [], $this->container['plugin_version'], false);
        }
    }

    /**
     * ET builder ready hook
     *
     * @return [type] [description]
     */
    public function et_builder_ready()
    {
        new ImageCardCarousel($this->container);
        new ImageCardCarouselItem($this->container);
        new PostTypeCardCarousel($this->container);
        new TaxonomyCarousel($this->container);
        new WooProductCarousel($this->container);

        // fullwidth
        new ImageCardCarouselFullWidth($this->container);
        new ImageCardCarouselItemFullWidth($this->container);
        new PostTypeCardCarouselFullWidth($this->container);
        new TaxonomyCarouselFullWidth($this->container);
        new WooProductCarouselFullWidth($this->container);

    }

    /**
     * Check if request is from frontend builder.
     */
    public function is_fb()
    {
        // phpcs:ignore WordPress.Security.NonceVerification
        return isset($_GET['et_fb']) and ($_GET['et_fb'] == '1');
    }

    /**
     * Get the content of the child modules.
     */
    public function get_child_modules_content($json_string)
    {
        $output = '';
        $styles = '';
        // phpcs:ignore
        $json_string = base64_decode($json_string); // encode child contents as array/object format. Divi's serialize fails and errors.

        $can_strip_slashes = apply_filters('uc_carousel_can_strip_slashes', true);

        if ($can_strip_slashes) {
            $json_string = stripslashes($json_string);
        }

        $children = json_decode(html_entity_decode($json_string), true);

        if (!empty($children)) {
            foreach ($children as $props) {
                $shortcode = et_fb_process_to_shortcode([$props]);
                $output .= do_shortcode($shortcode);
                $styles .= \ET_Builder_Element::get_style();
            }
            $output = $output . '<style>' . $styles . '</style>';
        }

        return $output;
    }

    public function get_responsive_values(
        $prop_name,
        $props,
        $default
    ) {
        $desktop = et_pb_responsive_options()->get_desktop_value($prop_name, $props, $default);
        $tablet  = et_pb_responsive_options()->get_tablet_value($prop_name, $props, $desktop);
        $phone   = et_pb_responsive_options()->get_phone_value($prop_name, $props, $tablet);

        return [
            'desktop' => $desktop,
            'tablet'  => $tablet,
            'phone'   => $phone,
        ];
    }

    public function process_advanced_margin_padding_css(
        $module,
        $prop_name,
        $function_name,
        $margin_padding
    ) {
        $utils           = \ET_Core_Data_Utils::instance();
        $all_values      = $module->props;
        $advanced_fields = $module->advanced_fields;

        // Disable if module doesn't set advanced_fields property and has no VB support.
        if (!$module->has_vb_support() && !$module->has_advanced_fields) {
            return;
        }

        $allowed_advanced_fields = [$prop_name . '_margin_padding'];
        foreach ($allowed_advanced_fields as $advanced_field) {
            if (!empty($advanced_fields[$advanced_field])) {

                foreach ($advanced_fields[$advanced_field] as $option_name => $form_field) {
                    $margin_key  = "{$option_name}_custom_margin";
                    $padding_key = "{$option_name}_custom_padding";
                    if ('' !== $utils->array_get($all_values, $margin_key, '') || '' !== $utils->array_get($all_values, $padding_key, '')) {
                        $settings = $utils->array_get($form_field, 'margin_padding', []);

                        $form_field_margin_padding_css = $utils->array_get($settings, 'css.main', '');
                        if (empty($form_field_margin_padding_css)) {
                            $utils->array_set($settings, 'css.main', $utils->array_get($form_field, 'css.main', ''));
                        }

                        $margin_padding->update_styles($module, $option_name, $settings, $function_name, $advanced_field);
                    }
                }
            }
        }
    }

    public function get_prop_value(
        $module,
        $prop_name
    ) {
        return isset($module->props[$prop_name]) && $module->props[$prop_name] ? $module->props[$prop_name] : $module->get_default($prop_name);
    }

    /**
     * Add free-plan class to the divi module
     */
    public function add_free_plan_class(&$module)
    {
        if (ucfd_fs()->is_free_plan()) {
            $module->add_classname(
                [
                    'free-plan',
                ]
            );
        }
    }

    /**
     * Add card url fields.
     */
    public function add_card_url_fields(
        &$fields,
         $module
    ) {
        $fields['open_url'] = [
            'label'       => esc_html__('Open URL On Item Click?', 'ultimate-carousel-for-divi'),
            'type'        => 'yes_no_button',
            'options'     => [
                'off' => esc_html__('Off', 'ultimate-carousel-for-divi'),
                'on'  => esc_html__('On', 'ultimate-carousel-for-divi'),
            ],
            'show_if'     => [
                'show_button' => 'off',
            ],
            'tab_slug'    => 'general',
            'toggle_slug' => 'content',
            'description' => esc_html__('Open an URL when the image card is clicked.', 'ultimate-carousel-for-divi'),

            'default'     => $module->get_default('open_url'),
        ];

        $fields['card_url_new_window'] = [
            'label'       => esc_html__('Link Target', 'ultimate-carousel-for-divi'),
            'type'        => 'select',
            'options'     => [
                'off' => esc_html__('Same Window', 'ultimate-carousel-for-divi'),
                'on'  => esc_html__('New Tab', 'ultimate-carousel-for-divi'),
            ],
            'default'     => $module->get_default('card_url_new_window'),
            'show_if'     => [
                'show_button' => 'off',
                'open_url'    => 'on',
            ],
            'tab_slug'    => 'general',
            'toggle_slug' => 'content',
            'description' => esc_html__('Set the window on which you`d like the URL to open. Select "Same Window" to open link in same window and "New Tab" to open the link on a new browser tab.', 'ultimate-carousel-for-divi'),
        ];

    }

}
