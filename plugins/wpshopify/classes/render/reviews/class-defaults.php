<?php

namespace ShopWP\Render\Reviews;

use ShopWP\Utils\Data;

if (!defined('ABSPATH')) {
    exit();
}

class Defaults
{
    public $plugin_settings;
    public $Render_Attributes;

    public function __construct($plugin_settings, $Render_Attributes)
    {
        $this->plugin_settings = $plugin_settings;
        $this->Render_Attributes = $Render_Attributes;
    }

    public function reviews($attrs) {
        return array_replace(apply_filters('shopwp_reviews_default_settings', [
            'reviews' => $this->Render_Attributes->attr(
                $attrs,
                'reviews',
                false
            ),
            'product_id' => $this->Render_Attributes->attr(
                $attrs,
                'product_id',
                false
            ),
            'show_rating' => $this->Render_Attributes->attr(
                $attrs,
                'show_rating',
                false
            ),
            'show_listing' => $this->Render_Attributes->attr(
                $attrs,
                'show_listing',
                true
            ),
            'show_create_new' => $this->Render_Attributes->attr(
                $attrs,
                'show_create_new',
                false
            ),
            'reviews_shown' => $this->Render_Attributes->attr(
                $attrs,
                'show_create_new',
                5
            ),
            'reviews_shown_increment' => $this->Render_Attributes->attr(
                $attrs,
                'reviews_shown_increment',
                5
            ),
            'reviews_list_heading' => $this->Render_Attributes->attr(
                $attrs,
                'reviews_list_heading',
                __('Customer Reviews', 'shopwp')
            ),
            'dropzone_rating' => $this->Render_Attributes->attr(
                $attrs,
                'dropzone_rating',
                false
            ),
        ]), $attrs);
    }

    public function all_attrs($attrs = [])
    {
        return $this->reviews($attrs);
    }
}