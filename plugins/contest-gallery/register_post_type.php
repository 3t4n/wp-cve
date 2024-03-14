<?php

add_action('init', 'cg_post_type_contest_gallery_plugin');
if(!function_exists('cg_post_type_contest_gallery_plugin')){
    function cg_post_type_contest_gallery_plugin() {
        $supports = array(
            'title', // post title
            'editor', // post content
            'author', // post author
            'excerpt', // post excerpt
            'custom-fields', // custom fields
            //'comments',
            'page-attributes', // page attributes, like parent page can be changed
        );
        $labels = array(
            'name' => _x('Cgallery', 'plural'),
            'singular_name' => _x('Cgallery', 'singular'),
            'menu_name' => _x('Cgallery', 'admin menu'),
            'name_admin_bar' => _x('Cgallery', 'admin bar'),
            'add_new' => _x('Add New', 'add new'),
            'add_new_item' => __('Add New Cgallery'),
            'new_item' => __('New Cgallery'),
            'edit_item' => __('Edit Page (Contest Gallery Custom Post Type)'),
            'view_item' => __('View Cgallery'),
            'all_items' => __('All Cgallery'),
            'search_items' => __('Search Cgallery'),
            'not_found' => __('No contest gallery pages found.'),
        );
        /*    $labels = array(
                'name_admin_bar' => _x('contest-gallery-plug', 'admin bar'),
                'edit_item' => __('Edit news'),
            );*/
        // see doc here!!!
        $args = array(
            'show_in_rest' => true,
            // 'supports' => array( 'title', 'editor', 'thumbnail', 'excerpt', 'author' ),
            'supports' => $supports,
            'labels' => $labels,
            'public' => true,
            'show_in_menu' => false,
            'query_var' => true,
            //'rewrite'      => array( 'slug' => 'contest-gallery', 'with_front' => true, 'hierarchical' => true ),
            //  'rewrite'      => array( 'slug' => 'contest-gallery', 'with_front' => true ),
            'hierarchical' => true,
            //   'rewrite' => array('slug' => 'contest-gallery-plug'),
            //   'rewrite' => array('slug' => '/', 'with_front' => true),
            // 'rewrite' => array('slug' => 'contest-gallery'),
            //   'capability_type' => 'page',// only permissions related, maybe remove
          //  'has_archive' => true,
            //     'hierarchical' => true,
            'show_in_admin_bar' => true,
        );
       // $args['rewrite'] =  array( 'slug' => 'contest-gallery', 'with_front' => true);
        $args['rewrite'] =  array( 'slug' => 'contest-gallery', 'with_front' => false);
        $wp_upload_dir = wp_upload_dir();
        $slugNameFilePath = $wp_upload_dir['basedir'].'/contest-gallery/gallery-general/post-type-slug-name-do-not-edit-or-remove.txt';
        if(file_exists($slugNameFilePath)){
            $slugName = trim(file_get_contents($slugNameFilePath));
            //$args['rewrite'] =  array( 'slug' => $slugName, 'with_front' => true);
            $args['rewrite'] =  array( 'slug' => $slugName, 'with_front' => false);
        }
        // $args['rewrite'] =  array( 'slug' => 'my-own-shit', 'with_front' => true );
        //$postType = register_post_type('contest-gallery-plug', $args);

        register_post_type('contest-gallery', $args);
        // is better to flush_rewrite_rules after register_post_type, otherwise no effect
        $rewriteRulesChangedFilePath = $wp_upload_dir['basedir'].'/contest-gallery/gallery-general/rewrite-rules-changed-do-not-edit-or-remove.txt';
        // is better to flush_rewrite_rules after register_post_type, otherwise no effect
        if(file_exists($rewriteRulesChangedFilePath)){// is better to flush_rewrite_rules after register_post_type, otherwise no effect
            //flush_rewrite_rules(false); // Whether to update .htaccess (hard flush) or just update rewrite_rules option (soft flush). Default is true (hard).
            //flush_rewrite_rules(false); // With false there is simply no effect or some of existing contest gallery links works with failure
            flush_rewrite_rules(false); // 15.01.2023, seems to work good with false so far
            unlink($rewriteRulesChangedFilePath);
        }

        //flush_rewrite_rules(false); // Whether to update .htaccess (hard flush) or just update rewrite_rules option (soft flush). Default is true (hard).

        /*
        echo "<pre>";

        print_r(get_option('rewrite_rules'));

        echo "</pre>";

        die;*/

        /*    echo "<pre>";
            print_r($postType);
            echo "</pre>";

            echo "<pre>";
            print_r(get_post_types());
            echo "</pre>";*/

    }
}
