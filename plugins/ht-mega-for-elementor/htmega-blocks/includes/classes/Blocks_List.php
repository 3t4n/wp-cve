<?php
namespace HtMegaBlocks;

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Manage Blocks
 */
class Blocks_List
{

    /**
     * Block List
     * @return array
     */
    public static function get_block_list()
    {
        $blockList = [
            'accordion' => [
                'label' => __('Accordion', 'htmega-addons'),
                'name' => 'htmega/accordion',
                'server_side_render' => true,
                'type' => 'common',
                'active' => htmegaBlocks_get_option('accordion', 'htmega_gutenberg_tabs', 'off') === 'on' ? true : false,
            ],
            'accordion-card' => [
                'label' => __('Accordion Card', 'htmega-addons'),
                'name' => 'htmega/accordion-card',
                'server_side_render' => true,
                'type' => 'common',
                'active' => htmegaBlocks_get_option('accordion', 'htmega_gutenberg_tabs', 'off') === 'on' ? true : false,
            ],
            'brand' => [
                'label' => __('Brand Logo', 'htmega-addons'),
                'name' => 'htmega/brand',
                'server_side_render' => true,
                'type' => 'common',
                'active' => htmegaBlocks_get_option('brand', 'htmega_gutenberg_tabs', 'off') === 'on' ? true : false,
            ],
            'buttons' => [
                'label' => __('Buttons', 'htmega-addons'),
                'name' => 'htmega/buttons',
                'server_side_render' => true,
                'type' => 'common',
                'active' => htmegaBlocks_get_option('buttons', 'htmega_gutenberg_tabs', 'off') === 'on' ? true : false,
            ],
            'button' => [
                'label' => __('Button', 'htmega-addons'),
                'name' => 'htmega/button',
                'server_side_render' => true,
                'type' => 'common',
                'active' => htmegaBlocks_get_option('buttons', 'htmega_gutenberg_tabs', 'off') === 'on' ? true : false,
            ],
            'cta' => [
                'label' => __('Call To Action', 'htmega-addons'),
                'name' => 'htmega/cta',
                'server_side_render' => true,
                'type' => 'common',
                'active' => htmegaBlocks_get_option('cta', 'htmega_gutenberg_tabs', 'off') === 'on' ? true : false,
            ],
            'image-grid' => [
                'label' => __('Image Grid', 'htmega-addons'),
                'name' => 'htmega/image-grid',
                'server_side_render' => true,
                'type' => 'common',
                'active' => htmegaBlocks_get_option('image-grid', 'htmega_gutenberg_tabs', 'off') === 'on' ? true : false,
            ],
            'info-box' => [
                'label' => __('Info Box', 'htmega-addons'),
                'name' => 'htmega/info-box',
                'server_side_render' => true,
                'type' => 'common',
                'active' => htmegaBlocks_get_option('info-box', 'htmega_gutenberg_tabs', 'off') === 'on' ? true : false,
            ],
            'section-title' => [
                'label' => __('Section Title', 'htmega-addons'),
                'name' => 'htmega/section-title',
                'server_side_render' => true,
                'type' => 'common',
                'active' => htmegaBlocks_get_option('section-title', 'htmega_gutenberg_tabs', 'off') === 'on' ? true : false,
            ],
            'tab' => [
                'label' => __('Tab', 'htmega-addons'),
                'name' => 'htmega/tab',
                'server_side_render' => true,
                'type' => 'common',
                'active' => htmegaBlocks_get_option('tab', 'htmega_gutenberg_tabs', 'off') === 'on' ? true : false,
            ],
            'tab-content' => [
                'label' => __('Tab Content', 'htmega-addons'),
                'name' => 'htmega/tab-content',
                'server_side_render' => true,
                'type' => 'common',
                'active' => htmegaBlocks_get_option('tab', 'htmega_gutenberg_tabs', 'off') === 'on' ? true : false,
            ],
            'team' => [
                'label' => __('Team', 'htmega-addons'),
                'name' => 'htmega/team',
                'server_side_render' => true,
                'type' => 'common',
                'active' => htmegaBlocks_get_option('team', 'htmega_gutenberg_tabs', 'off') === 'on' ? true : false,
            ],
            'testimonial' => [
                'label' => __('Testimonial', 'htmega-addons'),
                'name' => 'htmega/testimonial',
                'server_side_render' => true,
                'type' => 'common',
                'active' => htmegaBlocks_get_option('testimonial', 'htmega_gutenberg_tabs', 'off') === 'on' ? true : false,
            ],
        ];
        return apply_filters('htmega_block_list', $blockList);
    }
}
