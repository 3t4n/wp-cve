<?php

namespace WordPress\Plugin\GalleryManager;

abstract class Options
{
    const
        page_slug = 'gallery-options', # The options page slug
        options_key = 'gallery_options'; # the field identifier in the options table

    private static
        $arr_option_box = [];

    public static function init(): void
    {
        # Option boxes
        static::$arr_option_box = [
            'main' => [],
            'side' => []
        ];

        add_Action('admin_menu', [static::class, 'addOptionsPage']);
    }

    public static function addOptionsPage(): void
    {
        $handle = add_Options_Page(
            I18n::__('Gallery Options'),
            I18n::__('Galleries'),
            'manage_options',
            static::page_slug,
            [static::class, 'printOptionsPage']
        );

        # Add options page link to post type sub menu
        add_SubMenu_Page('edit.php?post_type=' . PostType::post_type_name, null, I18n::__('Settings'), 'manage_options', 'options-general.php?page=' . static::page_slug);

        # Add JavaScript to this handle
        add_Action('load-' . $handle, [static::class, 'loadOptionsPage']);

        # Add option boxes
        static::addOptionBox(I18n::__('Gallery Management'), Core::$plugin_folder . '/options-page/gallery-management.php');
        static::addOptionBox(I18n::__('Lightbox'), Core::$plugin_folder . '/options-page/lightbox.php');
        static::addOptionBox(I18n::__('Gallery Previews'), Core::$plugin_folder . '/options-page/previews.php', 'main');
        static::addOptionBox(I18n::__('Taxonomies'), Core::$plugin_folder . '/options-page/taxonomies.php', 'side');
        static::addOptionBox(I18n::__('Gallery Archive'), Core::$plugin_folder . '/options-page/archive.php', 'side');
    }

    public static function getOptionsPageUrl(array $args = []): string
    {
        $url = add_Query_Arg(['page' => static::page_slug], Admin_Url('options-general.php'));

        if (!empty($args))
            $url = add_Query_Arg($args, $url);

        return $url;
    }

    public static function addOptionBox(string $title, string $include_file, string $column = 'main'): void
    {
        # Title cannot be empty
        if (empty($title)) $title = '&nbsp;';

        # Column (can be 'side' or 'main')
        if ($column != 'main') $column = 'side';

        # Add a new box
        if (is_File($include_file)) {
            static::$arr_option_box[$column][] = (object) [
                'title' => $title,
                'file' => $include_file,
                'slug' => PathInfo($include_file, PATHINFO_FILENAME)
            ];
        }
    }

    public static function loadOptionsPage(): void
    {
        # If the Request was redirected from a "Save Options"-Post
        if (isset($_REQUEST['options_saved'])) {
            Taxonomies::updateTaxonomyNames();
            PostType::updatePostTypeName();
            flush_Rewrite_Rules();
        }

        # If this is a Post request to save the options
        if (static::saveOptions()) {
            WP_Redirect(static::getOptionsPageUrl(['options_saved' => 'true']));
        }

        WP_Enqueue_Style('dashboard');

        WP_Enqueue_Script(static::page_slug, Core::$base_url . '/options-page/options-page.js', ['jquery'], Core::version, true);
        WP_Enqueue_Style(static::page_slug, Core::$base_url . '/options-page/options-page.css');

        # Remove incompatible JS Libs
        WP_Dequeue_Script('post');
    }

    public static function printOptionsPage(): void
    {
        include Core::$plugin_folder . '/options-page/options-page.php';
    }

    public static function saveOptions(): bool
    {
        # Check if this is a post request
        if (empty($_POST)) return false;

        # Check the nonce
        check_Admin_Referer('save_gallery_manager_options');

        # Clean the Post array
        $options = stripSlashes_Deep($_POST);
        unset($options['_wpnonce'], $options['_wp_http_referer']);
        $options = Array_Filter($options, function ($value) {
            return $value == '0' || !empty($value);
        });

        # Save Options
        delete_Option('WordPress\Plugin\Fancy_Gallery\Options');
        delete_Option('wp_plugin_fancy_gallery_pro');
        delete_Option('wp_plugin_fancy_gallery');
        return (bool) update_Option(static::options_key, $options);
    }

    public static function getDefaultOptions(): array
    {
        return [
            'enable_editor' => false,
            'enable_block_editor' => false,
            'enable_featured_image' => true,
            'enable_custom_fields' => false,

            'lightbox' => true,
            'continuous' => false,
            'title_description' => true,
            'close_button' => true,
            'indicator_thumbnails' => true,
            'slideshow_button' => true,
            'slideshow_speed' => 3000, # Slideshow speed in milliseconds
            'preload_images' => 3,
            'animation_speed' => 400,
            'stretch_images' => false,
            'script_position' => 'footer',

            'gallery_taxonomy' => [],

            'enable_previews' => true,
            'enable_previews_for_custom_excerpts' => false,
            'preview_thumb_size' => 'thumbnail',
            'preview_columns' => 3,
            'preview_image_number' => 3,

            'enable_archive' => true,
        ];
    }

    public static function get(string $key = '', $default = false)
    {
        static $arr_options;

        if (empty($arr_options)) {
            # Read Options
            $arr_options = Array_Merge(
                static::getDefaultOptions(),
                (array) get_Option('wp_plugin_fancy_gallery_pro'),
                (array) get_Option('wp_plugin_fancy_gallery'),
                (array) get_Option('WordPress\Plugin\Fancy_Gallery\Options'),
                (array) get_Option(static::options_key)
            );
        }

        # Locate the option
        if (empty($key))
            return $arr_options;
        elseif (isset($arr_options[$key]))
            return $arr_options[$key];
        else
            return $default;
    }
}

Options::init();
