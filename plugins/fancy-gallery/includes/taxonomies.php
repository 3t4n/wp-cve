<?php

namespace WordPress\Plugin\GalleryManager;

use WP_Taxonomy;
use WP_Term;

abstract class Taxonomies
{
    public static function init(): void
    {
        add_Action('init', [static::class, 'registerTaxonomies'], 9);
        add_Action('init', [static::class, 'addTaxonomyArchiveUrls'], 50);
        add_Filter('nav_menu_meta_box_object', [static::class, 'changeTaxonomyMenuLabel']);
    }

    public static function getTaxonomies(): array
    {
        return [
            'gallery-category' => [
                'label' => I18n::__('Gallery Categories'),
                'labels' => [
                    'name' => I18n::__('Categories'),
                    'singular_name' => I18n::__('Category'),
                    'all_items' => I18n::__('All Categories'),
                    'edit_item' => I18n::__('Edit Category'),
                    'view_item' => I18n::__('View Category'),
                    'update_item' => I18n::__('Update Category'),
                    'add_new_item' => I18n::__('Add New Category'),
                    'new_item_name' => I18n::__('New Category'),
                    'parent_item' => I18n::__('Parent Category'),
                    'parent_item_colon' => I18n::__('Parent Category:'),
                    'search_items' =>  I18n::__('Search Categories'),
                    'popular_items' => I18n::__('Popular Categories'),
                    'separate_items_with_commas' => I18n::__('Separate Categories with commas'),
                    'add_or_remove_items' => I18n::__('Add or remove Categories'),
                    'choose_from_most_used' => I18n::__('Choose from the most used Categories'),
                    'not_found' => I18n::__('No Categories found.')
                ],
                'show_admin_column' => true,
                'show_in_rest' => true,
                'hierarchical' => false,
                'show_ui' => true,
                'query_var' => true,
                'rewrite' => [
                    'with_front' => false,
                    'slug' => sprintf(I18n::_x('%s/category', 'URL slug'), I18n::_x('galleries', 'URL slug'))
                ],
            ],

            'gallery-tag' => [
                'label' => I18n::__('Gallery Tags'),
                'labels' => [
                    'name' => I18n::__('Tags'),
                    'singular_name' => I18n::__('Tag'),
                    'all_items' => I18n::__('All Tags'),
                    'edit_item' => I18n::__('Edit Tag'),
                    'view_item' => I18n::__('View Tag'),
                    'update_item' => I18n::__('Update Tag'),
                    'add_new_item' => I18n::__('Add New Tag'),
                    'new_item_name' => I18n::__('New Tag'),
                    'parent_item' => I18n::__('Parent Tag'),
                    'parent_item_colon' => I18n::__('Parent Tag:'),
                    'search_items' =>  I18n::__('Search Tags'),
                    'popular_items' => I18n::__('Popular Tags'),
                    'separate_items_with_commas' => I18n::__('Separate Tags with commas'),
                    'add_or_remove_items' => I18n::__('Add or remove Tags'),
                    'choose_from_most_used' => I18n::__('Choose from the most used Tags'),
                    'not_found' => I18n::__('No Tags found.')
                ],
                'show_admin_column' => true,
                'show_in_rest' => true,
                'hierarchical' => false,
                'show_ui' => true,
                'query_var' => true,
                'rewrite' => [
                    'with_front' => false,
                    'slug' => sprintf(I18n::_x('%s/tag', 'URL slug'), I18n::_x('galleries', 'URL slug'))
                ],
            ],

        ];
    }

    public static function registerTaxonomies(): void
    {
        # Load Taxonomies
        $arr_taxonomies = static::getTaxonomies();
        $arr_taxonomies = apply_Filters('gallery_manager_taxonomies', $arr_taxonomies);

        # Check the enabled taxonomies
        $enabled_taxonomies = (array) Options::get('gallery_taxonomies');

        # Register Taxonomies
        if (!empty($enabled_taxonomies)){
            foreach ($enabled_taxonomies as $taxonomie_name => $attributes) {
                if (isset($arr_taxonomies[$taxonomie_name])) {
                    $taxonomy_args = Array_Merge($arr_taxonomies[$taxonomie_name], $attributes);
                    register_Taxonomy($taxonomie_name, PostType::post_type_name, $taxonomy_args);
                }
            }
        }
    }

    public static function addTaxonomyArchiveUrls(): void
    {
        foreach (get_Object_Taxonomies(PostType::post_type_name) as $taxonomy) {
            add_Action($taxonomy . '_edit_form_fields', [static::class, 'printTaxonomyArchiveUrls'], 10, 3);
        }
    }

    public static function printTaxonomyArchiveUrls(WP_Term $tag, string $taxonomy_name): void
    {
        $taxonomy = get_Taxonomy($taxonomy_name);
        $archive_url = get_Term_Link(get_Term($tag->term_id, $taxonomy->name));
        $archive_feed = get_Term_Feed_Link($tag->term_id, $taxonomy->name);
        ?>
        <tr class="form-field">
            <th scope="row" valign="top"><?php I18n::_e('Archive Url') ?></th>
            <td>
                <a href="<?php echo $archive_url ?>" target="_blank"><?php echo $archive_url ?></a><br>
                <span class="description"><?php printf(I18n::__('This is the URL to the archive of this %s.'), $taxonomy->labels->singular_name) ?></span>
            </td>
        </tr>
        <tr class="form-field">
            <th scope="row" valign="top"><?php I18n::_e('Archive Feed') ?></th>
            <td>
                <a href="<?php echo $archive_feed ?>" target="_blank"><?php echo $archive_feed ?></a><br />
                <span class="description"><?php printf(I18n::__('This is the URL to the feed of the archive of this %s.'), $taxonomy->labels->singular_name) ?></span>
            </td>
        </tr>
        <?php
    }

    public static function changeTaxonomyMenuLabel($tax)
    {
        if (isset($tax->object_type) && in_Array(PostType::post_type_name, $tax->object_type)) {
            $gallery_post_type_object = get_Post_Type_Object(PostType::post_type_name);
            $tax->labels->name = sprintf('%1$s (%2$s)', $tax->labels->name, $gallery_post_type_object->label);
        }

        return $tax;
    }

    public static function updateTaxonomyNames(): void
    {
        global $wpdb;

        $arr_rename = [
            'gallery_category' => 'gallery-category',
            'gallery_tag' => 'gallery-tag',
        ];

        foreach ($arr_rename as $rename_from => $rename_to) {
            $wpdb->update(
                $wpdb->term_taxonomy, # table name
                ['taxonomy' => $rename_to], # set "taxonomy" to...
                ['taxonomy' => $rename_from] # where "taxonomy" is...
            );
        }
    }
}

Taxonomies::init();
