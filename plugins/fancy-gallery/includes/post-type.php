<?php

namespace WordPress\Plugin\GalleryManager;

abstract class PostType
{
    const
        meta_field_name = '_gallery', # Name of the meta field which is used in the database
        post_type_name = 'gallery'; # Name of the gallery post type

    public static
        $arr_meta_box = []; # Meta boxes for the gallery post type

    public static function init(): void
    {
        add_Action('init', [static::class, 'registerPostType'], 10); # For the permalinks it is important that the post type is registered after the taxonomies
        add_Filter('post_updated_messages', [static::class, 'filterPostUpdateMessages']);
        add_Action(sprintf('save_post_%s', static::post_type_name), [static::class, 'savePost'], 10, 2);
        add_Filter(sprintf('manage_%s_posts_columns', static::post_type_name), [static::class, 'filterPostTypeColumns']);
        add_Action(sprintf('manage_%s_posts_custom_column', static::post_type_name), [static::class, 'filterPostTypeColumnValue'], 10, 2);
        add_Filter('gutenberg_can_edit_post_type', [static::class, 'enableBlockEditor'], 10, 2); # WP 4.9.x
        add_Filter('use_block_editor_for_post_type', [static::class, 'enableBlockEditor'], 10, 2); # WP >= 5.x
    }

    public static function savePost(int $post_id, $post): void
    {
        $is_autosave = defined('DOING_AUTOSAVE') && DOING_AUTOSAVE;
        
        # If this is an autosave we dont care
        if (!$is_autosave) {
            # Delete deprecated post meta key
            delete_Post_Meta($post_id, '_wp_plugin_fancy_gallery');
            delete_Post_Meta($post_id, '_wp_plugin_fancy_gallery_pro');

            # Update post media attachments of this gallery
            if (isset($_POST['images'])) {
                $arr_images = (array) $_POST['images'];
                $arr_images = Array_Filter($arr_images);
                $arr_images = Array_Values($arr_images);
                $arr_images = Array_Unique($arr_images);
                $gallery = new Gallery($post_id);
                $gallery->setImages($arr_images);
            }

            # Save gallery meta data like columns and thumbnail sizes
            if (isset($_POST['gallery'])) {
                $arr_meta = Array_Filter($_POST['gallery']);
                update_Post_Meta($post_id, static::meta_field_name, $arr_meta);
            }
        }
    }

    public static function getMeta(string $key = '', $default = false, int $post_id = -1)
    {
        if ($post_id < 1) $post_id = get_the_id();

        # load meta data from database and convert it to array
        $arr_meta = get_Post_Meta($post_id, static::meta_field_name, true);
        setType($arr_meta, 'ARRAY');
        $arr_meta = Array_Filter($arr_meta);

        # merge meta data with the default meta values
        $arr_default_meta = static::getDefaultMeta();
        $arr_meta = Array_Merge($arr_default_meta, $arr_meta);

        # return the requested value
        if (empty($key))
            return $arr_meta;
        elseif (isset($arr_meta[$key]))
            return $arr_meta[$key];
        else
            return $default;
    }

    public static function getDefaultMeta(): array
    {
        return [
            'columns' => 3,
            'image_size' => 'thumbnail'
        ];
    }

    public static function registerPostType(): void
    {
        # Register Post Type
        $labels = [
            'name' => I18n::__('Galleries'),
            'singular_name' => I18n::__('Gallery'),
            'add_new' => I18n::__('Add Gallery'),
            'add_new_item' => I18n::__('New Gallery'),
            'edit_item' => I18n::__('Edit Gallery'),
            'view_item' => I18n::__('View Gallery'),
            'search_items' => I18n::__('Search Galleries'),
            'not_found' =>  I18n::__('No Galleries found'),
            'not_found_in_trash' => I18n::__('No Galleries found in Trash'),
            'all_items' => I18n::__('All Galleries'),
            'archives' => I18n::__('Gallery Index Page')
        ];

        $post_type_args = [
            'labels' => $labels,
            'public' => true,
            'show_ui' => true,
            'menu_position' => 10, # below Media
            'menu_icon' => 'dashicons-images-alt',
            'map_meta_cap' => true,
            'hierarchical' => false,
            'show_in_rest' => true,
            'supports' => ['title', 'author'],
            'register_meta_box_cb' => [static::class, 'addMetaBoxes'],
            'has_archive' => (bool) Options::get('enable_archive'),
            'rewrite' => [
                'slug' => I18n::_x('galleries', 'URL slug'),
                'with_front' => false
            ],
        ];

        register_Post_Type(static::post_type_name, $post_type_args);

        # Add optionally post type support
        if (Options::get('enable_editor'))
            add_Post_Type_Support(static::post_type_name, 'editor');

        if (Options::get('enable_featured_image'))
            add_Post_Type_Support(static::post_type_name, 'thumbnail');

        if (Options::get('enable_custom_fields'))
            add_Post_Type_Support(static::post_type_name, 'custom-fields');
    }

    public static function filterPostUpdateMessages(array $arr_message): array
    {
        return Array_Merge($arr_message, [static::post_type_name => [
            1 => sprintf(I18n::__('Gallery updated. (<a href="%s">View gallery</a>)'), get_Permalink()),
            2 => I18n::__('Custom field updated.'),
            3 => I18n::__('Custom field deleted.'),
            4 => I18n::__('Gallery updated.'),
            5 => isset($_GET['revision']) ? sprintf(I18n::__('Gallery restored to revision from %s'), WP_Post_Revision_Title((int) $_GET['revision'], false)) : false,
            6 => sprintf(I18n::__('Gallery published. (<a href="%s">View gallery</a>)'), get_Permalink()),
            7 => I18n::__('Gallery saved.'),
            8 => I18n::__('Gallery submitted.'),
            9 => sprintf(I18n::__('Gallery scheduled. (<a target="_blank" href="%s">View gallery</a>)'), get_Permalink()),
            10 => sprintf(I18n::__('Gallery draft updated. (<a target="_blank" href="%s">Preview gallery</a>)'), add_Query_Arg('preview', 'true', get_Permalink()))
        ]]);
    }

    public static function addMetaBoxes(): void
    {
        global $post_type_object;

        # Enqueue Edit Gallery JavaScript/CSS
        WP_Enqueue_Media();
        WP_Enqueue_Script('gallery-meta-boxes');
        WP_Enqueue_Style('gallery-meta-boxes', Core::$base_url . '/meta-boxes/meta-boxes.css', false, Core::version);

        static::addMetaBox(I18n::__('Images'), Core::$plugin_folder . '/meta-boxes/images.php', 'normal', 'high');

        static::addMetaBox(I18n::__('Appearance'), Core::$plugin_folder . '/meta-boxes/appearance.php', 'normal', 'high');

        remove_Meta_Box('authordiv', static::post_type_name, 'normal');
        if (Current_User_Can($post_type_object->cap->edit_others_posts)) {
            static::addMetaBox(I18n::__('Owner'), Core::$plugin_folder . '/meta-boxes/owner.php');
        }

        static::addMetaBox(I18n::__('Shortcode'), Core::$plugin_folder . '/meta-boxes/show-code.php', 'side', 'high');

        if (Options::get('lightbox'))
            static::addMetaBox(I18n::__('Hash'), Core::$plugin_folder . '/meta-boxes/show-hash.php', 'side', 'high');

        # Add Meta Boxes
        foreach (static::$arr_meta_box as $box_index => $meta_box) {
            add_Meta_Box(
                'meta-box-' . BaseName($meta_box['include_file'], '.php'),
                $meta_box['title'],
                [static::class, 'printMetaBox'],
                static::post_type_name,
                $meta_box['column'],
                $meta_box['priority'],
                ['include_file' => $meta_box['include_file']]
            );
        }
    }

    public static function addMetaBox(string $title, string $include_file, string $column = 'normal', string $priority = 'default'): void
    {
        if (!empty($title) && is_File($include_file)) {
            if ($column != 'side') $column = 'normal';

            static::$arr_meta_box[] = [
                'title' => $title,
                'include_file' => $include_file,
                'column' => $column,
                'priority' => $priority,
            ];
        }
    }

    public static function printMetaBox($post, array $box): void
    {
        $include_file = empty($box['args']['include_file']) ? false : $box['args']['include_file'];
        is_File($include_file) && include $include_file;
    }

    public static function filterPostTypeColumns(array $arr_columns): array
    {
        $arr_columns['shortcode'] = I18n::__('Shortcode');
        return $arr_columns;
    }

    public static function filterPostTypeColumnValue(string $column, int $post_id): void
    {
        if ($column == 'shortcode') {
            printf('<input type="text" readonly value="[gallery id=&quot;%u&quot;]" onClick="this.select();" class="gallery-code" style="max-width:100%%">', $post_id);
        }
    }

    public static function updatePostTypeName(): void
    {
        global $wpdb;
        $wpdb->update($wpdb->posts, ['post_type' => static::post_type_name], ['post_type' => 'fancy_gallery']);
        $wpdb->update($wpdb->posts, ['post_type' => static::post_type_name], ['post_type' => 'fancy-gallery']);
    }

    public static function enableBlockEditor(bool $editable, string $post_type_name): bool
    {
        if (static::post_type_name == $post_type_name) {
            return Options::get('enable_block_editor');
        }
        return $editable;
    }
}

PostType::init();
