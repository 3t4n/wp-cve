<?php

namespace WordPress\Plugin\GalleryManager;

abstract class WebPSupport
{
    public static function init(): void
    {
        add_Filter('upload_mimes', [static::class, 'filterAllowedMimeTypes']);
        add_Filter('wp_check_filetype_and_ext', [static::class, 'filterExtensions'], 10, 4);
        add_filter('file_is_displayable_image', [static::class, 'filterFileIsdisplayable'], 10, 2);
    }

    public static function filterAllowedMimeTypes(array $arr_mime_types): array
    {
        $arr_mime_types['webp'] = 'image/webp';
        return $arr_mime_types;
    }

    public static function filterExtensions(array $types, string $file, string $filename, $mimes): array
    {
        $ext = PathInfo($filename, PATHINFO_EXTENSION);
        if ($ext == 'webp') {
            $types['ext'] = $ext;
            $types['type'] = "image/{$ext}";
        }

        return $types;
    }

    public static function filterFileIsDisplayable(bool $is_image, string $file_path): bool
    {
        $ext = PathInfo($file_path, PATHINFO_EXTENSION);
        if ($ext == 'webp') {
            $is_image = true;
        }

        return $is_image;
    }
}

WebPSupport::init();
