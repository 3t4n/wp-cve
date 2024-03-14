<?php

namespace WordPress\Plugin\GalleryManager;

abstract class ShortcodeFilter
{

    public static function init(): void
    {
        add_Filter('shortcode_atts_gallery', [static::class, 'filterGalleryAttributes']);
    }

    public static function filterGalleryAttributes(array $attributes): array
    {
        # the link attribute can be "none" or "file"
        if ($attributes['link'] != 'none')
            $attributes['link'] = 'file';

        # set the colums and image size if this is the id of a gallery is set
        if (!empty($attributes['id']) && Post::isGallery($attributes['id'])) {
            $attributes['columns'] = PostType::getMeta('columns', null, $attributes['id']);
            $attributes['size'] = PostType::getMeta('image_size', null, $attributes['id']);
        }

        return $attributes;
    }
}

ShortcodeFilter::init();
