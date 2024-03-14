<?php

namespace WordPress\Plugin\GalleryManager;

abstract class TinyMCE
{
    public static function init(): void
    {
        add_Filter('mce_external_plugins', [static::class, 'addTinyMCEPlugins']);
    }

    public static function addTinyMCEPlugins(): array
    {
        return [
            'wpgallerypatch' => Core::$base_url . '/assets/js/tinymce-gallery-patch.js'
        ];
    }
}

TinyMCE::init();
