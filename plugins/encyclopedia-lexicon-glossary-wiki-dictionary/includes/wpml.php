<?php

namespace WordPress\Plugin\Encyclopedia;

use
    WP_Query,
    WPML_ST_Post_Slug_Translation_Settings,
    WPML_ST_Tax_Slug_Translation_Settings;

abstract class WPML
{
    public static function init(): void
    {
        add_action('admin_init', [static::class, 'registerTranslatableStrings']);
        add_filter('register_post_type_args', [static::class, 'filterPostTypeURLs'], 10, 2);
        add_filter('encyclopedia_translate', [static::class, 'filterTranslation'], 10, 3);
        add_filter('encyclopedia_available_prefix_filters', [static::class, 'filterAvailablePrefixFilters']);
        #add_action('encyclopedia_term_related_items_query_object', [static::class, 'filterTagRelatedItems'], 10, 2);
    }

    public static function isActive(): bool
    {
        return defined('ICL_SITEPRESS_VERSION');
    }

    public static function registerTranslatableStrings(): void
    {
        $plugin = UCWords(Str_Replace(['-', '_'], ' ', I18n::textdomain));

        $post_type_labels = [
            'Encyclopedia type' => Options::get('encyclopedia_type'),
            'Item singular name' => Options::get('item_singular_name'),
            'Item plural name' => Options::get('item_plural_name'),
        ];

        foreach ($post_type_labels as $string_id => $label) {
            do_action('wpml_register_single_string', $plugin, $string_id, $label);
        }
    }

    public static function translateRegisteredString(string $text, string $string_id): string
    {
        $plugin = UCWords(Str_Replace(['-', '_'], ' ', I18n::textdomain));
        return apply_Filters('wpml_translate_single_string', $text, $plugin, $string_id);
    }

    public static function t(string $text, string $string_id): string
    {
        return static::translateRegisteredString($text, $string_id);
    }

    public static function isPostTypeSlugTranslationEnabled(): bool
    {
        global $sitepress;

        if (static::isActive() && class_exists('WPML_ST_Post_Slug_Translation_Settings')) {
            $post_type_slug_translation = new WPML_ST_Post_Slug_Translation_Settings($sitepress);
            return $post_type_slug_translation->is_Translated(PostType::post_type_name);
        }

        return false;
    }

    public static function isTaxonomySlugTranslationEnabled(string $taxonomy): bool
    {
        if (static::isActive() && class_exists('WPML_ST_Tax_Slug_Translation_Settings')) {
            $tax_slug_translation = new WPML_ST_Tax_Slug_Translation_Settings();
            return $tax_slug_translation->is_Translated($taxonomy);
        }

        return false;
    }

    public static function filterPostTypeURLs(array $args, string $post_type): array
    {
        if ($post_type == PostType::post_type_name) {
            if (static::isPostTypeSlugTranslationEnabled()) {
                $args['rewrite']['slug'] = PostTypeLabels::getArchiveSlug();
                $args['has_archive'] = true;
            }
        }

        return $args;
    }

    public static function filterTranslation(bool $state, string $text, string $context = ''): bool
    {
        if (static::isActive() && $context == 'URL Slug') {
            $state = false;
        }

        return $state;
    }

    public static function filterAvailablePrefixFilters(array $arr_filter): array
    {
        if (static::isActive() && is_Array($arr_filter)) {
            foreach ($arr_filter as $index => $filter) {
                # Check if there are posts behind this filter in this language
                $query = new WP_Query([
                    'post_type' => PostType::post_type_name,
                    'post_title_like' => $filter->prefix . '%',
                    'posts_per_page' => 1,
                    'cache_results' => false,
                    'no_count_rows' => true
                ]);

                if (!$query->have_Posts())
                    unset($arr_filter[$index]);
            }

            $arr_filter = Array_Values($arr_filter);
        }

        return $arr_filter;
    }

    /*
    public static function filterTagRelatedItems(WP_Query $query, array $arguments)
    {
        if (static::isActive()) {
            $original_post_id = $arguments->post_id;
            $arr_related_items_ids = [];

            foreach ($query->posts as $related_item)
                $arr_related_items_ids[] = $related_item->ID;

            $query->query([
                'post_type' => PostType::post_type_name,
                'post__in' => $arr_related_items_ids,
                'orderby' => 'post__in',
                'nopaging' => true,
                'ignore_sticky_posts' => false
            ]);

            foreach ($query->posts as $index => $related_item) {
                if ($related_item->ID == $original_post_id) {
                    unset($query->posts[$index]);
                    $query->post_count = count($query->posts);
                    $query->rewind_Posts();
                    break;
                }
            }
        }
    }
    */
}

WPML::init();
