<?php

namespace WordPress\Plugin\Encyclopedia;

use WP_Post;

abstract class PostType
{
    const
        post_type_name = 'encyclopedia'; # Name of the post type

    public static function init()
    {
        add_action('init', [static::class, 'registerPostType']);
        add_filter(static::post_type_name . '_rewrite_rules', [static::class, 'addPrefixFilterRewriteRules']);
        add_filter('post_updated_messages', [static::class, 'filterUpdatedMessages']);
        add_filter('post_type_link', [static::class, 'filterPostTypeLink'], 1, 2);
        add_filter('gutenberg_can_edit_post_type', [static::class, 'enableBlockEditor'], 10, 2); # WP 4.x
        add_filter('use_block_editor_for_post_type', [static::class, 'enableBlockEditor'], 10, 2); # WP 5.x
    }

    public static function registerPostType(): void
    {
        $labels = [
            'name' => PostTypeLabels::getEncyclopediaType(),
            'singular_name' => PostTypeLabels::getItemSingularName(),
            'add_new' => sprintf(I18n::__('Add %s'), PostTypeLabels::getItemSingularName()),
            'add_new_item' => sprintf(I18n::__('New %s'), PostTypeLabels::getItemSingularName()),
            'edit_item' => sprintf(I18n::__('Edit %s'), PostTypeLabels::getItemSingularName()),
            'view_item' => sprintf(I18n::__('View %s'), PostTypeLabels::getItemSingularName()),
            'search_items' => sprintf(I18n::__('Search %s'), PostTypeLabels::getItemPluralName()),
            'not_found' =>  sprintf(I18n::__('No %s found'), PostTypeLabels::getItemPluralName()),
            'not_found_in_trash' => sprintf(I18n::__('No %s found in Trash'), PostTypeLabels::getItemPluralName()),
            'all_items' => sprintf(I18n::__('All %s'), PostTypeLabels::getItemPluralName()),
            'archives' => sprintf(I18n::__('%s Index Page'), PostTypeLabels::getEncyclopediaType())
        ];

        $post_type_args = [
            'labels' => $labels,
            'public' => true,
            'show_ui' => true,
            'menu_icon' => 'dashicons-welcome-learn-more',
            'register_meta_box_cb' => [static::class, 'addMetaBoxes'],
            'has_archive' => Options::get('enable_archive') ? PostTypeLabels::getArchiveSlug() : false,
            'map_meta_cap' => true,
            'hierarchical' => false,
            'rewrite' => [
                'slug' => PostTypeLabels::getItemSlug(),
                'with_front' => false
            ],
            'supports' => ['title', 'author'],
            'menu_position' => 20, # below Pages
            'show_in_rest' => true,

            'show_in_graphql' => true,
            'graphql_single_name' => 'encyclopediaEntry',
            'graphql_plural_name' => 'encyclopediaEntries'
        ];

        register_Post_Type(static::post_type_name, $post_type_args);

        # Add optionally post type support
        if (Options::get('enable_editor'))
            add_Post_Type_Support(static::post_type_name, 'editor');

        if (Options::get('enable_excerpt'))
            add_Post_Type_Support(static::post_type_name, 'excerpt');

        if (Options::get('enable_custom_fields'))
            add_Post_Type_Support(static::post_type_name, 'custom-fields');
    }

    public static function addPrefixFilterRewriteRules(array $rules): array
    {
        $post_type = get_Post_Type_Object(static::post_type_name);
        $new_rules = [];

        # Add filter permalink structure for post type archive
        if ($post_type->has_archive) {
            $archive_url_path = (true === $post_type->has_archive) ? $post_type->rewrite['slug'] : $post_type->has_archive;
            $new_rules[ltrim(sprintf('%s/prefix:([^/]+)/?$', $archive_url_path), '/')] = sprintf('index.php?post_type=%s&prefix=$matches[1]', PostType::post_type_name);
            $new_rules[ltrim(sprintf('%s/prefix:([^/]+)/page/([0-9]{1,})/?$', $archive_url_path), '/')] = sprintf('index.php?post_type=%s&prefix=$matches[1]&paged=$matches[2]', PostType::post_type_name);
        }

        $rules = Array_Merge($new_rules, $rules);

        return $rules;
    }

    public static function addMetaBoxes(): void
    {
        # There wont be added other meta boxes yet
    }

    public static function getAssociatedTaxonomies()
    {
        $arr_all_taxonomies = get_Taxonomies(null, 'objects');
        if (empty($arr_all_taxonomies)) return false;

        $arr_associated_taxonomies = [];

        foreach ($arr_all_taxonomies as $taxonomy) {
            if (in_Array(PostType::post_type_name, $taxonomy->object_type)) {
                $arr_associated_taxonomies[] = $taxonomy;
            }
        }

        return empty($arr_associated_taxonomies) ? false : $arr_associated_taxonomies;
    }

    public static function filterUpdatedMessages($arr_messages): array
    {
        $revision_id = empty($_GET['revision']) ? false : IntVal($_GET['revision']);

        $arr_messages[static::post_type_name] = [
            1 => sprintf(I18n::__('%1$s updated. (<a href="%2$s">View %1$s</a>)'), PostTypeLabels::getItemSingularName(), get_Permalink()),
            2 => I18n::__('Custom field updated.'),
            3 => I18n::__('Custom field deleted.'),
            4 => sprintf(I18n::__('%s updated.'), PostTypeLabels::getItemSingularName()),
            5 => sprintf(I18n::__('%1$s restored to revision from %2$s'), PostTypeLabels::getItemSingularName(), WP_Post_Revision_Title($revision_id, false)),
            6 => sprintf(I18n::__('%1$s published. (<a href="%2$s">View %1$s</a>)'), PostTypeLabels::getItemSingularName(), get_Permalink()),
            7 => sprintf(I18n::__('%s saved.'), PostTypeLabels::getItemSingularName()),
            8 => sprintf(I18n::__('%s submitted.'), PostTypeLabels::getItemSingularName()),
            9 => sprintf(I18n::__('%1$s scheduled. (<a target="_blank" href="%2$s">View %1$s</a>)'), PostTypeLabels::getItemSingularName(), get_Permalink()),
            10 => sprintf(I18n::__('Draft updated. (<a target="_blank" href="%1$s">Preview %2$s</a>)'), add_Query_Arg(['preview' => 'true'], get_Permalink()), PostTypeLabels::getItemSingularName())
        ];

        return $arr_messages;
    }

    public static function getArchiveLink(string $filter = '', $taxonomy_term = null)
    {
        if (apply_filters('encyclopedia_use_plain_prefix_url_structure', false))
            $permalink_structure = '';
        else
            $permalink_structure = get_Option('permalink_structure');

        # Get base url
        if ($taxonomy_term)
            $base_url = get_Term_Link($taxonomy_term);
        else
            $base_url = get_Post_Type_Archive_Link(static::post_type_name);

        if (empty($base_url))
            return false;

        if (empty($permalink_structure))
            return add_Query_Arg(['prefix' => RawURLEncode($filter)], $base_url);
        else
            return User_TrailingSlashIt(sprintf('%1$s/prefix:%2$s', rtrim($base_url, '/'), RawURLEncode($filter)));
    }

    public static function filterPostTypeLink(string $link, WP_Post $post): string
    {
        static $associated_taxonomies;

        if (!empty($post->post_type) && $post->post_type == static::post_type_name) {
            # Get the taxonomies for this post type
            if (empty($associated_taxonomies))
                $associated_taxonomies = static::getAssociatedTaxonomies();

            if ($associated_taxonomies) {
                foreach ($associated_taxonomies as $taxonomy) {
                    $virtual_slug = "%{$taxonomy->name}%";
                    if (StrPos($link, $virtual_slug)) {
                        $terms = wp_get_Object_Terms($post->ID, $taxonomy->name);
                        if ($terms) {
                            $first_term = reset($terms);
                            $term_slug = $first_term->slug;
                        } else {
                            $term_slug = sanitize_Title(I18n::__('Uncategorized'));
                        }

                        $link = str_replace($virtual_slug, $term_slug, $link);
                    }
                }
            }
        }

        return $link;
    }

    public static function enableBlockEditor(bool $editable, string $post_type_name): bool
    {
        if (PostType::post_type_name == $post_type_name) {
            return (bool) Options::get('enable_block_editor');
        }
        return $editable;
    }
}

PostType::init();
