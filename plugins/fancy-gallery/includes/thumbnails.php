<?php

namespace WordPress\Plugin\GalleryManager;

abstract class Thumbnails
{
    public static function getSizes($dimensions = false)
    {
        $arr_registered_sizes = static::getRegisteredSizes();
        $arr_default_sizes = static::getDefaultSizes();
        $arr_sizes = Array_Merge($arr_registered_sizes, $arr_default_sizes);

        if ($dimensions) {
            $arr_result = [];

            foreach ($arr_sizes as $size => $caption) {
                $dimensions = static::getDimensions($size);
                if (!$dimensions) continue;

                $arr_result[$size] = (object) [
                    'name' => $size,
                    'caption' => $caption,
                    'width' => $dimensions->width ? $dimensions->width : null,
                    'height' => $dimensions->height ? $dimensions->height : null,
                    'crop' => (bool) $dimensions->crop,
                ];
            }
            $arr_sizes = $arr_result;
        }

        return $arr_sizes;
    }

    public static function getDefaultSizes(): array
    {
        /* This filter is documented in wp-admin/includes/media.php */
        $default_sizes = apply_Filters('image_size_names_choose', [
            'thumbnail' => __('Thumbnail'),
            'medium'    => __('Medium'),
            'large'     => __('Large'),
            'full'      => __('Full Size'),
        ]);

        return $default_sizes;
    }

    public static function getRegisteredSizes(): array
    {
        $arr_registered_sizes = (array) get_Intermediate_Image_Sizes();

        $arr_registered_sizes = Array_Flip($arr_registered_sizes);

        foreach ($arr_registered_sizes as $size => &$caption) {
            $caption = $size;
            $caption = Str_Replace(['_', '-'], ' ', $caption);
            $caption = UCWords($caption);
            $caption = __($caption);
        }

        return $arr_registered_sizes;
    }

    public static function getDimensions(string $size)
    {
        global $_wp_additional_image_sizes;

        #if (isSet($_wp_additional_image_sizes[$size]['width']) && isSet($_wp_additional_image_sizes[$size]['height'])){
        if (isset($_wp_additional_image_sizes[$size])) {
            $size = (object) [
                'width'  => (int) $_wp_additional_image_sizes[$size]['width'],
                'height' => (int) $_wp_additional_image_sizes[$size]['height'],
                'crop'   => (bool) $_wp_additional_image_sizes[$size]['crop']
            ];
            return $size;
        } else { #if (($width = get_Option("{$size}_size_w")) && ($height = get_Option("{$size}_size_h"))){
            $size = (object) [
                'width' => (int) get_Option("{$size}_size_w"),
                'height' => (int) get_Option("{$size}_size_h"),
                'crop' => (bool) get_Option("{$size}_crop")
            ];

            if ($size->width || $size->height)
                return $size;
        }

        return false;
    }

    public static function getDropdown(array $attributes): string
    {
        $defaults = [
            'name' => '',
            'class' => '',
            'selected' => false,
            'class' => ''
        ];

        $attributes = Array_Merge($defaults, $attributes);
        setType($attributes, 'OBJECT');

        $html = sprintf('<select name="%s" id="%s" class="%s">', $attributes->name, $attributes->id, $attributes->class);

        foreach (static::getSizes(true) as $size) {
            $html .= sprintf('<option value="%s" %s>', $size->name, selected($attributes->selected, $size->name, false));
            $html .= $size->caption;
            !empty($size->width) && !empty($size->height) && $html .= sprintf(' (%u x %u px)', $size->width, $size->height);
            $html .= '</option>';
        }

        $html .= '</select>';

        return $html;
    }
}
