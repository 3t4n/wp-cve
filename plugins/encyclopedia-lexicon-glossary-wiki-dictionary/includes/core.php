<?php

namespace WordPress\Plugin\Encyclopedia;

use WP_Post, WP_Query;

abstract class Core
{
    public static
        $base_url, # url to the plugin directory
        $plugin_file, # the main plugin file
        $plugin_folder; # the path to the folder the plugin files contains

    public static function init(string $plugin_file): void
    {
        static::$plugin_file = $plugin_file;
        static::$plugin_folder = DirName(static::$plugin_file);

        register_Activation_Hook(static::$plugin_file, [static::class, 'installPlugin']);
        register_Deactivation_Hook(static::$plugin_file, [static::class, 'uninstallPlugin']);
        add_action('plugins_loaded', [static::class, 'loadBaseUrl']);
        add_action('loop_start', [static::class, 'printPrefixFilter']);
        add_filter('render_block', [static::class, 'addPrefixFilterBlock'], 10, 2);
        add_action('encyclopedia_print_prefix_filter', [static::class, 'printPrefixFilter'], 10, 0);
        add_filter('wp_robots', [static::class, 'setNoindexTag']);
        add_filter('get_the_archive_title', [static::class, 'filterArchiveTitle']);
    }

    public static function loadBaseURL(): void
    {
        $absolute_plugin_folder = RealPath(static::$plugin_folder);

        if (StrPos($absolute_plugin_folder, ABSPATH) === 0)
            static::$base_url = site_url() . '/' . SubStr($absolute_plugin_folder, Strlen(ABSPATH));
        else
            static::$base_url = Plugins_Url(BaseName(static::$plugin_folder));

        static::$base_url = Str_Replace("\\", '/', static::$base_url); # Windows Workaround
    }

    public static function installPlugin(): void
    {
        Taxonomies::registerTagTaxonomy();
        PostType::registerPostType();
        flush_Rewrite_Rules();
    }

    public static function uninstallPlugin(): void
    {
        flush_Rewrite_Rules();
    }

    public static function addCrossLinks(string $content, $post = null): string
    {
        $post_id = $post->ID ?? null;
        $post_type = $post->post_type ?? null;

        # Start Cross Linker
        $cross_linker = new CrossLinker();
        $cross_linker->setSkipElements(apply_Filters('encyclopedia_cross_linking_skip_elements', ['a', 'script', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'button', 'textarea', 'select', 'style', 'pre', 'code', 'kbd', 'tt']));
        if (!$cross_linker->loadContent($content))
            return $content;

        # Build the Query
        $query_args = [
            'post_type' => PostType::post_type_name,
            'post__not_in' => [$post_id],
            'nopaging' => true,
            'orderby' => 'post_title_length',
            'order' => 'DESC',
            'no_count_rows' => true,
            'no_found_rows' => true,
            'update_post_term_cache' => false,
            'update_post_meta_cache' => false,
        ];

        # Query the items
        $query = new WP_Query($query_args);

        # Create the links
        foreach ($query->posts as $item) {
            if (apply_Filters('encyclopedia_link_item_in_content', true, $item, $post, $cross_linker)) {
                $cross_linker->linkPhrase($item->post_title, [static::class, 'getCrossLinkItemDetails'], [$item]);
            }
        }

        # Overwrite the content with the parsers document which contains the links to each term
        $content = (string) $cross_linker->getParserDocument();

        return $content;
    }

    public static function getCrossLinkItemDetails(WP_Post $item)
    {
        return (object) [
            'phrase' => $item->post_title,
            'title' => static::getCrossLinkItemTitle($item),
            'url' => get_Permalink($item),
        ];
    }

    public static function getCrossLinkItemTitle(WP_Post $post): string
    {
        $title = $more = $length = false;

        if (empty($post->post_excerpt)) {
            $more = apply_Filters('encyclopedia_link_title_more', '&hellip;');
            #$more = HTML_Entity_Decode($more, ENT_QUOTES, 'UTF-8');
            $length = apply_Filters('encyclopedia_link_title_length', Options::get('cross_link_title_length'));
            $title = strip_Shortcodes($post->post_content);
            $title = WP_Strip_All_Tags($title);
            #$title = HTML_Entity_Decode($title, ENT_QUOTES, 'UTF-8');
            $title = WP_Trim_Words($title, $length, $more);
        } else {
            $title = WP_Strip_All_Tags($post->post_excerpt, true);
            #$title = HTML_Entity_Decode($title, ENT_QUOTES, 'UTF-8');
        }

        $title = apply_Filters('encyclopedia_item_link_title', $title, $post, $more, $length);

        return $title;
    }

    public static function printPrefixFilter($query = null): bool
    {
        global $wp_the_query;

        static $loop_already_started;
        if ($loop_already_started)
            return false;

        if (empty($query)) {
            # get the current query
            $query = $wp_the_query;
        } elseif (!is_a($query, WP_Query::class)) {
            # This is a fix for the loop_start call in bbPress: bbpress/includes/users/template.php:112
            return false;
        }

        # If this is a feed we leave
        if ($query->is_Feed())
            return false;

        # If we are in head section we leave
        if (!did_Action('wp_head'))
            return false;

        # Run filter
        if (!apply_Filters('encyclopedia_is_prefix_filter_enabled', true, $query))
            return false;

        # Conditions
        if ($query->is_Main_Query() && !$query->get('suppress_filters')) {
            echo PrefixFilter::renderFilters($query);
            $loop_already_started = true;
        }

        return true;
    }

    public static function addPrefixFilterBlock(string $content, array $props): string
    {
        global $wp_query;
        static $prefix_filter_already_rendered = false;

        $block_type = (string) $props['blockName'];

        if (
            !$prefix_filter_already_rendered &&
            is_a($wp_query, WP_Query::class) &&
            apply_Filters('encyclopedia_is_prefix_filter_enabled', true, $wp_query) &&
            $wp_query->is_Main_Query() &&
            !$wp_query->get('suppress_filters') &&
            (
                ($wp_query->is_archive() && $block_type === 'core/query') ||
                ($wp_query->is_singular() && in_array($block_type, ['core/post-featured-image', 'core/post-title']))
            )
        ) {
            $prefix_filter_html = PrefixFilter::renderFilters($wp_query, ['wrapper_class' => 'alignwide']);
            if (!empty($prefix_filter_html)) {
                $content = $prefix_filter_html . $content;
            }
            $prefix_filter_already_rendered = true;
        }

        return $content;
    }

    public static function setNoindexTag(array $robots): array
    {
        if (is_archive() && StrLen(get_query_var('prefix'))) {
            $robots['noindex'] = true;
        }

        return $robots;
    }

    public static function filterArchiveTitle(string $title): string
    {
        if (is_Post_Type_Archive(PostType::post_type_name))
            return Post_Type_Archive_Title('', false);
        else
            return $title;
    }
}
