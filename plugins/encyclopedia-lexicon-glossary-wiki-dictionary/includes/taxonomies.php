<?php

namespace WordPress\Plugin\Encyclopedia;

use WP_Taxonomy, WP_Term, WP_Post_Type;

abstract class Taxonomies
{
    public static function init(): void
    {
        add_action('init', [static::class, 'registerTagTaxonomy'], 9);
        add_action('init', [static::class, 'registerExistingTaxonomies'], 11);
        add_filter('nav_menu_meta_box_object', [static::class, 'changeTaxonomyMenuLabel']);
        add_action('init', [static::class, 'addTaxonomyArchiveUrls'], 99);
    }

    public static function registerExistingTaxonomies(): void
    {
        $arr_taxonomies = static::getTaxonomies();
        $tax_options = (array) Options::get('taxonomies');

        foreach ($arr_taxonomies as $taxonomy) {
            if (in_Array($taxonomy->name, $tax_options)) {
                register_Taxonomy_for_Object_Type($taxonomy->name, PostType::post_type_name);
                add_filter("{$taxonomy->name}_rewrite_rules", [static::class, 'addPrefixFilterRewriteRules']);
            }
        }
    }

    public static function registerTagTaxonomy(): void
    {
        $taxonomy_name = 'encyclopedia-tag';
        $slug = trim(I18n::_x('encyclopedia/tag', 'URL Slug'), '/');

        $labels = [
            'name' => I18n::__('Tags'),
            'singular_name' => I18n::__('Tag'),
            'search_items' => I18n::__('Search Tags'),
            'all_items' => I18n::__('All Tags'),
            'edit_item' => I18n::__('Edit Tag'),
            'update_item' => I18n::__('Update Tag'),
            'add_new_item' => I18n::__('Add New Tag'),
            'new_item_name' => I18n::__('New Tag')
        ];

        $args = [
            'label' => sprintf(I18n::__('%s: Tags'), PostTypeLabels::getEncyclopediaType()),
            'labels' => $labels,
            'show_admin_column' => true,
            'hierarchical' => false,
            'show_ui' => true,
            'query_var' => true,
            'rewrite' => [
                'with_front' => false,
                'slug' => $slug
            ],
            'show_in_rest' => true,

            'show_in_graphql' => true,
            'graphql_single_name' => 'encyclopediaTag',
            'graphql_plural_name' => 'encyclopediaTags'
        ];

        register_Taxonomy($taxonomy_name, null, $args);
        add_filter("{$taxonomy_name}_rewrite_rules", [static::class, 'addPrefixFilterRewriteRules']);
    }

    public static function addPrefixFilterRewriteRules(array $rules): array
    {
        global $wp_rewrite;

        $current_filter = current_Filter();
        $filter_suffix_pos = StrPos($current_filter, '_rewrite_rules');
        if (!$filter_suffix_pos) return $rules;
        $taxonomy_name = SubStr($current_filter, 0, $filter_suffix_pos);
        $taxonomy = get_Taxonomy($taxonomy_name);
        if (!$taxonomy) return $rules;

        $new_rules = [];
        $taxonomy_slug = $taxonomy->rewrite['slug'];

        if ($taxonomy->rewrite['with_front'])
            $taxonomy_slug = $wp_rewrite->front . $taxonomy_slug;

        $new_rules[ltrim(sprintf('%s/([^/]+)/prefix:([^/]+)/?$', $taxonomy_slug), '/')] = sprintf('index.php?%s=$matches[1]&prefix=$matches[2]', $taxonomy->query_var);
        $new_rules[ltrim(sprintf('%s/([^/]+)/prefix:([^/]+)/page/([0-9]{1,})/?$', $taxonomy_slug), '/')] = sprintf('index.php?%s=$matches[1]&prefix=$matches[2]&paged=$matches[3]', $taxonomy->query_var);

        $rules = Array_Merge($new_rules, $rules);

        return $rules;
    }

    public static function changeTaxonomyMenuLabel($tax)
    {
        if (isset($tax->object_type) && in_Array(PostType::post_type_name, $tax->object_type)) {
            $tax->labels->name = sprintf('%1$s (%2$s)', $tax->labels->name, PostTypeLabels::getEncyclopediaType());
        }

        return $tax;
    }

    public static function addTaxonomyArchiveUrls(): void
    {
        foreach (get_Object_Taxonomies(PostType::post_type_name) as $taxonomy) {
            add_action("{$taxonomy}_edit_form_fields", [static::class, 'printTaxonomyArchiveUrls'], 10, 3);
        }
    }

    public static function printTaxonomyArchiveUrls(WP_Term $tag, string $taxonomy): void
    {
        $taxonomy = get_Taxonomy($taxonomy);
        $archive_url = get_Term_Link(get_Term($tag->term_id, $taxonomy->name));
        $archive_feed = get_Term_Feed_Link($tag->term_id, $taxonomy->name);

        ?>
        <tr class="form-field">
            <th scope="row" valign="top"><?php echo I18n::__('Archive URL') ?></th>
            <td>
                <code><a href="<?php echo $archive_url ?>" target="_blank"><?php echo $archive_url ?></a></code><br>
                <span class="description"><?php printf(I18n::__('This is the URL to the archive of this %s.'), $taxonomy->labels->singular_name) ?></span>
            </td>
        </tr>
        <tr class="form-field">
            <th scope="row" valign="top"><?php I18n::_e('Feed URL') ?></th>
            <td>
                <code><a href="<?php echo $archive_feed ?>" target="_blank"><?php echo $archive_feed ?></a></code><br>
                <span class="description"><?php printf(I18n::__('This is the URL to the feed of the archive of this %s.'), $taxonomy->labels->singular_name) ?></span>
            </td>
        </tr>
        <?php
    }

    public static function getTaxonomies(array $args = []): array
    {
        $default_args = ['show_ui' => true];
        $arr_taxonomies = get_Taxonomies($args + $default_args, 'objects');

        # Add taxonomy details
        foreach ($arr_taxonomies as &$taxonomy) {
            # Add post types for each taxonomy
            $taxonomy->post_types = [];
            foreach ($taxonomy->object_type as $post_type_name) {
                if ($post_type = get_Post_Type_Object($post_type_name))
                    $taxonomy->post_types[$post_type_name] = $post_type;
            }

            # Load taxonomy options
            #$taxonomy->options = static::getTaxOptions($taxonomy->name);
        }
        unset($taxonomy);

        return $arr_taxonomies;
    }
}

Taxonomies::init();
