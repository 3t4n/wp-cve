<?php
namespace WPT\UltimateDiviCarousel\Divi;

use ET_Builder_Module_Helper_ResponsiveOptions;

/**
 * MarginPadding.
 */
class MarginPadding
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
     * Margin field
     */
    public function get_margin_field(
        $prefix,
        $tab_slug,
        $toggle_slug,
        $default = ''
    ) {
        $fields = [];

        $fields[$prefix . '_custom_margin'] = [
            'label'          => esc_html__('Margin', 'ultimate-carousel-for-divi'),
            'type'           => 'custom_margin',
            'mobile_options' => true,
            'tab_slug'       => $tab_slug,
            'toggle_slug'    => $toggle_slug,
            'default'        => $default,
        ];

        return $fields;

    }

    /**
     * Margin padding field
     */
    public function get_margin_padding_field(
        $prefix,
        $tab_slug,
        $toggle_slug,
        $margin_default = '',
        $padding_default = ''
    ) {
        $margin_field  = $this->get_margin_field($prefix, $tab_slug, $toggle_slug, $margin_default);
        $padding_field = $this->get_padding_field($prefix, $tab_slug, $toggle_slug, $padding_default);

        return $margin_field + $padding_field;

    }

    /**
     * Padding field
     */
    public function get_padding_field(
        $prefix,
        $tab_slug,
        $toggle_slug,
        $default = ''
    ) {
        $fields = [];

        $fields[$prefix . '_custom_padding'] = [
            'label'          => esc_html__('Padding', 'ultimate-carousel-for-divi'),
            'type'           => 'custom_padding',
            'mobile_options' => true,
            'tab_slug'       => $tab_slug,
            'toggle_slug'    => $toggle_slug,
            'default'        => $default,
        ];

        return $fields;

    }

    /**
     * Responsive margin settings.
     */
    public function responsive_margin(
        $props,
        $prefix,
        $selector,
        $render_slug
    ) {
        $responsive = ET_Builder_Module_Helper_ResponsiveOptions::instance();

        $is_margin_responsive = $responsive->is_responsive_enabled($props, "{$prefix}_custom_margin");

        $margin_desktop = $responsive->get_any_value($props, "{$prefix}_custom_margin");
        $margin_tablet  = $is_margin_responsive ? $responsive->get_any_value($props, "{$prefix}_custom_margin_tablet") : '';
        $margin_phone   = $is_margin_responsive ? $responsive->get_any_value($props, "{$prefix}_custom_margin_phone") : '';

        $important = true;

        $margin_styles = [
            'desktop' => '' !== $margin_desktop ? rtrim(et_builder_get_element_style_css($margin_desktop, 'margin', $important)) : '',
            'tablet'  => '' !== $margin_tablet ? rtrim(et_builder_get_element_style_css($margin_tablet, 'margin', $important)) : '',
            'phone'   => '' !== $margin_phone ? rtrim(et_builder_get_element_style_css($margin_phone, 'margin', $important)) : '',
        ];

        $responsive->declare_responsive_css($margin_styles, $selector, $render_slug);
    }

    /**
     * Responsive margin padding
     */
    public function responsive_margin_padding(
        $props,
        $prefix,
        $selector,
        $render_slug
    ) {
        $this->responsive_margin($props, $prefix, $selector, $render_slug);
        $this->responsive_padding($props, $prefix, $selector, $render_slug);
    }

    public function responsive_padding(
        $props,
        $prefix,
        $selector,
        $render_slug
    ) {
        $responsive = ET_Builder_Module_Helper_ResponsiveOptions::instance();

        $is_padding_responsive = $responsive->is_responsive_enabled($props, "{$prefix}_custom_padding");

        $padding_desktop = $responsive->get_any_value($props, "{$prefix}_custom_padding");
        $padding_tablet  = $is_padding_responsive ? $responsive->get_any_value($props, "{$prefix}_custom_padding_tablet") : '';
        $padding_phone   = $is_padding_responsive ? $responsive->get_any_value($props, "{$prefix}_custom_padding_phone") : '';

        $important = true;

        $padding_styles = [
            'desktop' => '' !== $padding_desktop ? rtrim(et_builder_get_element_style_css($padding_desktop, 'padding', $important)) : '',
            'tablet'  => '' !== $padding_tablet ? rtrim(et_builder_get_element_style_css($padding_tablet, 'padding', $important)) : '',
            'phone'   => '' !== $padding_phone ? rtrim(et_builder_get_element_style_css($padding_phone, 'padding', $important)) : '',
        ];

        $responsive->declare_responsive_css($padding_styles, $selector, $render_slug);
    }
}
