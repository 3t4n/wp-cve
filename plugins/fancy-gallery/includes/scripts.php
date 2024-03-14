<?php

namespace WordPress\Plugin\GalleryManager;

abstract class Scripts
{
    public static function init(): void
    {
        add_Action('wp_enqueue_scripts', [static::class, 'enqueueScripts']);
        add_Action('admin_init', [static::class, 'registerAdminScripts']);
    }

    public static function enqueueScripts(): void
    {
        $arr_options = Options::get();
        unset($arr_options['disable_update_notification'], $arr_options['update_username'], $arr_options['update_password']);
        $arr_options['ajax_url'] = Admin_Url('admin-ajax.php');

        WP_Register_Script('gallery-manager', Core::$base_url . '/assets/js/gallery-manager.js', ['jquery'], Core::version, Options::get('script_position') != 'header');
        WP_Localize_Script('gallery-manager', 'GalleryManager', $arr_options);

        if (Options::get('lightbox'))
            WP_Enqueue_Script('gallery-manager');
    }

    public static function registerAdminScripts(): void
    {
        WP_Register_Script('dynamic-gallery', Core::$base_url . '/assets/js/dynamic-gallery.js', ['jquery'], null, true);
        WP_Localize_Script('dynamic-gallery', 'DynamicGallery', [
            'warn_remove_image' => I18n::__('Do you want to remove this image from the gallery? It will not be deleted from your media library.')
        ]);

        WP_Register_Script('gallery-meta-boxes', Core::$base_url . '/meta-boxes/meta-boxes.js', ['jquery', 'dynamic-gallery'], Core::version, true);
    }
}

Scripts::init();
