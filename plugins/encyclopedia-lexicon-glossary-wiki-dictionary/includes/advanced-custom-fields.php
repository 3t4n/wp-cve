<?php

namespace WordPress\Plugin\Encyclopedia;

abstract class AdvancedCustomFields
{
    public static function init(): void
    {
        add_action('plugins_loaded', [static::class, 'registerContentFilter']);
    }

    public static function registerContentFilter(): void
    {
        if (!is_Admin()) {
            $cross_linker_priority = Options::get('cross_linker_priority') == 'before_shortcodes' ? 10.5 : 15;

            # For ACF >= 5.0.0
            add_filter('acf/format_value/type=wysiwyg', [static::class, 'filterFieldValue'], $cross_linker_priority, 3);
            add_filter('acf/format_value/type=textarea', [static::class, 'filterFieldValue'], $cross_linker_priority, 3);
            add_filter('acf/format_value/type=text', [static::class, 'filterFieldValue'], $cross_linker_priority, 3);
        }
    }

    public static function filterFieldValue(?string $content, $post_id, array $field): string
    {
        if (empty($content))
            return '';

        $post = is_numeric($post_id) ? get_Post($post_id) : false;

        if (empty($post))
            return $content;

        # Check if cross linking is deactivated for this field
        if (!apply_filters('encyclopedia_link_item_in_acf', true, $field, $post))
            return $content;

        if (!apply_filters(sprintf('encyclopedia_link_item_in_acf_%s', $field['name']), true, $field, $post))
            return $content;

        # Check if Cross-Linking is activated for this post type
        if (apply_Filters('encyclopedia_link_items_in_post', true, $post)) {
            $content = Core::addCrossLinks($content, $post);
        }

        return $content;
    }

    public static function filterTextValue(?string $content, $post_id, array $field): string
    {
        if (empty($content))
            return '';

        $compatible_formattings = ['html', 'br'];

        if (in_Array($field['formatting'], $compatible_formattings)) {
            $content = static::filterFieldValue($content, $post_id, $field);
        }

        return $content;
    }
}

AdvancedCustomFields::init();
