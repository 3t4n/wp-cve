<?php

namespace Modular\ConnectorDependencies\Ares\Framework\Setup\Elements;

use Modular\ConnectorDependencies\Ares\Framework\Setup\AbstractServiceProvider;
/** @internal */
class RegisterWordPressThemeAsAresProvider extends AbstractServiceProvider
{
    /**
     * Unregister core image sizes
     */
    public function unregisterImageSizes()
    {
        \add_filter('intermediate_image_sizes_advanced', function ($sizes) {
            $toRemoveSizes = ['thumbnail', 'medium', 'medium_large', 'large', '1536x1536', '2048x2048'];
            if (\class_exists('Modular\\ConnectorDependencies\\WooCommerce')) {
                // TODO Move to WC Register Provider
                $toRemoveSizes = \array_merge($toRemoveSizes, ['woocommerce_thumbnail', 'woocommerce_single', 'woocommerce_gallery_thumbnail', 'shop_catalog', 'shop_single', 'shop_thumbnail']);
            }
            \array_walk($toRemoveSizes, function ($size) {
                if (isset($sizes[$size])) {
                    unset($sizes[$size]);
                }
            });
            return $sizes;
        });
    }
    /**
     * Unregister core image sizes
     */
    public function modifyCustomLogo()
    {
        \add_filter('get_custom_logo', function () {
            $attachment_id = \get_theme_mod('custom_logo');
            $url = \get_home_url();
            $image = \wp_get_attachment_image($attachment_id, 'full', \false, ['class' => 'image', 'itemprop' => 'logo', 'itemscope' => 'itemscope', 'role' => 'presentation', 'aria-hidden' => 'true', 'data-no-lazy' => 1]);
            return $this->app->make('view')->make('items.logo', \compact('url', 'image'));
        });
        \add_filter('big_image_size_threshold', fn() => \false);
    }
    /**
     * Remove default builder elements
     *
     * @return void
     */
    public function removeDefaultBuilderElements()
    {
        if (\function_exists('Modular\\ConnectorDependencies\\vc_remove_element')) {
            $elements = ['vc_wp_meta', 'vc_wp_rss', 'vc_separator', 'vc_column_text', 'vc_icon', 'vc_zigzag', 'vc_text_separator', 'vc_message', 'vc_hoverbox', 'vc_toggle', 'vc_pinterest', 'vc_tweetmeme', 'vc_facebook', 'vc_single_image', 'vc_images_carousel', 'vc_gallery', 'vc_tta_tour', 'vc_custom_heading', 'vc_btn', 'vc_widget_sidebar', 'vc_cta', 'vc_gmaps', 'vc_video', 'vc_flickr', 'vc_raw_js', 'vc_tta_pageable', 'vc_tta_section', 'vc_posts_slider', 'vc_progress_bar', 'vc_pie', 'vc_round_chart', 'vc_line_chart', 'vc_empty_space', 'vc_basic_grid', 'vc_media_grid', 'vc_masonry_grid', 'vc_masonry_media_grid', 'vc_gutenberg', 'vc_wp_search', 'vc_wp_recentcomments', 'vc_wp_calendar', 'vc_wp_pages', 'vc_wp_tagcloud', 'vc_wp_custommenu', 'vc_wp_text', 'vc_wp_posts', 'vc_wp_categories', 'vc_wp_archives', 'vc_googleplus', 'vc_tabs', 'vc_tab', 'vc_tour', 'vc_accordion', 'vc_accordion_tab', 'vc_tta_accordion', 'vc_tta_toggle', 'vc_pricing_table'];
            \array_walk($elements, function ($element) {
                vc_remove_element($element);
            });
        }
    }
    /**
     * Remove builder JS/CSS
     *
     * @return void
     */
    public function removeBuilderJsCss()
    {
        \add_action('wp_enqueue_scripts', function () {
            \wp_deregister_style('vc_animate-css');
            \wp_deregister_style('js_composer_front');
            \wp_dequeue_style('js_composer_front');
            \wp_dequeue_script('wpb_composer_front_js');
            \wp_deregister_script('wpb_composer_front_js');
        }, 0);
    }
    /**
     * Call every function required to load after_setup_theme
     *
     * @throws \Exception
     */
    public function load() : void
    {
        $this->unregisterImageSizes();
        $this->modifyCustomLogo();
        if (\function_exists('Modular\\ConnectorDependencies\\vc_map')) {
            $this->removeDefaultBuilderElements();
            $this->removeBuilderJsCss();
        }
    }
    /**
     * Init require functions
     */
    public function register() : void
    {
        parent::register();
        if (\function_exists('is_admin') && \is_admin() && \function_exists('Modular\\ConnectorDependencies\\vc_disable_frontend')) {
            \add_action('pre_get_posts', fn() => vc_disable_frontend());
            \remove_action('admin_bar_menu', [vc_frontend_editor(), 'adminBarEditLink'], 1000);
        }
        if (\function_exists('add_action') && \function_exists('add_filter')) {
            \add_action('wp_enqueue_scripts', fn() => \wp_deregister_style('vc_animate-css'), 0);
        }
    }
}
