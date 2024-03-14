<?php
    
    if( !function_exists('wpfilm_custom_post_register') ){

        function wpfilm_custom_post_register(){



$cus_post_movie = wpfilm_get_option( 'wpfilm_cus_post_movie', 'settings' );

    if(!empty($cus_post_movie)){
        $cus_post_movie = wpfilm_get_option( 'wpfilm_cus_post_movie', 'settings' );
        $cus_post_movie_slug = wpfilm_get_option( 'wpfilm_cus_post_movie', 'settings' );
        $cus_post_movie_slug = strtolower( $cus_post_movie_slug);
        $cus_post_movie_slug = str_replace(' ', '-', $cus_post_movie_slug);
    }else{
        $cus_post_movie = 'Movies';
        $cus_post_movie_slug = "wpfilm_movie";
    }

            // Register Movie Post Type
            $labels = array(
                'name'                  => _x( $cus_post_movie, 'Post Type General Name', 'wpfilm-studio' ),
                'singular_name'         => _x( 'Movie', 'Post Type Singular Name', 'wpfilm-studio' ),
                'menu_name'             => esc_html__( 'Movie', 'wpfilm-studio' ),
                'name_admin_bar'        => esc_html__( 'Movie', 'wpfilm-studio' ),
                'archives'              => esc_html__( 'Item Archives', 'wpfilm-studio' ),
                'parent_item_colon'     => esc_html__( 'Parent Item:', 'wpfilm-studio' ),
                'add_new_item'          => esc_html__( 'Add New Item', 'wpfilm-studio' ),
                'add_new'               => esc_html__( 'Add New', 'wpfilm-studio' ),
                'new_item'              => esc_html__( 'New Item', 'wpfilm-studio' ),
                'edit_item'             => esc_html__( 'Edit Item', 'wpfilm-studio' ),
                'update_item'           => esc_html__( 'Update Item', 'wpfilm-studio' ),
                'view_item'             => esc_html__( 'View Item', 'wpfilm-studio' ),
                'search_items'          => esc_html__( 'Search Item', 'wpfilm-studio' ),
                'not_found'             => esc_html__( 'Not found', 'wpfilm-studio' ),
                'not_found_in_trash'    => esc_html__( 'Not found in Trash', 'wpfilm-studio' ),
                'featured_image'        => esc_html__( 'Featured Image', 'wpfilm-studio' ),
                'set_featured_image'    => esc_html__( 'Set featured image', 'wpfilm-studio' ),
                'remove_featured_image' => esc_html__( 'Remove featured image', 'wpfilm-studio' ),
                'use_featured_image'    => esc_html__( 'Use as featured image', 'wpfilm-studio' ),
                'insert_into_item'      => esc_html__( 'Insert into item', 'wpfilm-studio' ),
                'uploaded_to_this_item' => esc_html__( 'Uploaded to this item', 'wpfilm-studio' ),
                'items_list'            => esc_html__( 'Items list', 'wpfilm-studio' ),
                'items_list_navigation' => esc_html__( 'Items list navigation', 'wpfilm-studio' ),
                'filter_items_list'     => esc_html__( 'Filter items list', 'wpfilm-studio' ),
            );
            $args = array(
                'labels'                => $labels,
                'supports'              => array( 'title','editor', 'thumbnail','tag' ),
                'hierarchical'          => false,
                'public'                => true,
                'show_ui'               => true,
                'show_in_menu'          => 'wpfilm',
                'menu_position'         => 5,
                'menu_icon'             => 'dashicons-archive',
                'show_in_admin_bar'     => true,
                'show_in_nav_menus'     => true,
                'can_export'            => true,
                'has_archive'           => true,        
                'exclude_from_search'   => false,
                'publicly_queryable'    => true,
                'capability_type'       => 'post',
                'rewrite'           => array( 'slug' => $cus_post_movie_slug ),
            );
            register_post_type( 'wpfilm_movie', $args );

           // Movie Category
           $labels = array(
            'name'              => _x( 'Movies Categories', 'wpfilm-studio' ),
            'singular_name'     => _x( 'Movies Category', 'wpfilm-studio' ),
            'search_items'      => esc_html__( 'Search Category' ),
            'all_items'         => esc_html__( 'All Category' ),
            'parent_item'       => esc_html__( 'Parent Category' ),
            'parent_item_colon' => esc_html__( 'Parent Category:' ),
            'edit_item'         => esc_html__( 'Edit Category' ),
            'update_item'       => esc_html__( 'Update Category' ),
            'add_new_item'      => esc_html__( 'Add New Category' ),
            'new_item_name'     => esc_html__( 'New Category Name' ),
            'menu_name'         => esc_html__( 'Movies Category' ),
           );

           $args = array(
            'hierarchical'      => true,
            'labels'            => $labels,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => array( 'slug' => 'wpfilm_movie_category' ),
           );

           register_taxonomy('wpfilm_movie_category','wpfilm_movie',$args);

        // Tag Movie
        $labels = array(
            'name' => _x( 'Tags', 'taxonomy general name' ),
            'singular_name' => _x( 'Tag', 'taxonomy singular name' ),
            'search_items' =>  __( 'Search Tags' ),
            'popular_items' => __( 'Popular Tags' ),
            'all_items' => __( 'All Tags' ),
            'parent_item' => null,
            'parent_item_colon' => null,
            'edit_item' => __( 'Edit Tag' ), 
            'update_item' => __( 'Update Tag' ),
            'add_new_item' => __( 'Add New Tag' ),
            'new_item_name' => __( 'New Tag Name' ),
            'separate_items_with_commas' => __( 'Separate tags with commas' ),
            'add_or_remove_items' => __( 'Add or remove tags' ),
            'choose_from_most_used' => __( 'Choose from the most used tags' ),
            'menu_name' => __( 'Tags' ),
          ); 

        register_taxonomy('movie_tag','wpfilm_movie',array(
          'hierarchical' => false,
          'labels' => $labels,
          'show_ui' => true,
          'update_count_callback' => '_update_post_term_count',
          'query_var' => true,
          'rewrite' => array( 'slug' => 'movie_tag' ),
        ));

      //Trailer Post Type
       $labels = array(

        'name'                  => _x( 'Trailer', 'Post Type General Name', 'wpfilm-studio' ),
        'singular_name'         => _x( 'Trailer', 'Post Type Singular Name', 'wpfilm-studio' ),
        'menu_name'             => esc_html__( 'Trailer', 'wpfilm-studio' ),
        'name_admin_bar'        => esc_html__( 'Trailer', 'wpfilm-studio' ),
        'archives'              => esc_html__( 'Trailer Archives', 'wpfilm-studio' ),
        'parent_item_colon'     => esc_html__( 'Parent Trailer:', 'wpfilm-studio' ),
        'add_new_item'          => esc_html__( 'Add New Trailer', 'wpfilm-studio' ),
        'add_new'               => esc_html__( 'Add New', 'wpfilm-studio' ),
        'new_item'              => esc_html__( 'New Trailer', 'wpfilm-studio' ),
        'edit_item'             => esc_html__( 'Edit Trailer', 'wpfilm-studio' ),
        'update_item'           => esc_html__( 'Update Trailer', 'wpfilm-studio' ),
        'view_item'             => esc_html__( 'View Trailer', 'wpfilm-studio' ),
        'search_items'          => esc_html__( 'Search Trailer', 'wpfilm-studio' ),
        'not_found'             => esc_html__( 'Not found', 'wpfilm-studio' ),
        'not_found_in_trash'    => esc_html__( 'Not found in Trash', 'wpfilm-studio' ),
        'featured_image'        => esc_html__( 'Trailer Image', 'wpfilm-studio' ),
        'set_featured_image'    => esc_html__( 'Set Trailer image', 'wpfilm-studio' ),
        'remove_featured_image' => esc_html__( 'Remove Trailer image', 'wpfilm-studio' ),
        'use_featured_image'    => esc_html__( 'Use as Trailer image', 'wpfilm-studio' ),
        'insert_into_item'      => esc_html__( 'Insert into Trailer', 'wpfilm-studio' ),
        'uploaded_to_this_item' => esc_html__( 'Uploaded to this Trailer', 'wpfilm-studio' ),
        'items_list'            => esc_html__( 'Items list', 'wpfilm-studio' ),
        'items_list_navigation' => esc_html__( 'Items list navigation', 'wpfilm-studio' ),
        'filter_items_list'     => esc_html__( 'Filter Trailer list', 'wpfilm-studio' ),
       );

       $args = array(
        'labels'             => $labels,
              'description'        => esc_html__( 'Description.', 'wpfilm-studio' ),
        'public'             => true,
        'menu_icon'     => 'dashicons-format-image',   
        'publicly_queryable' => false,
        'show_ui'            => true,
        'show_in_menu'       => 'wpfilm',
        'query_var'          => true,
        'rewrite'            => array( 'slug' => 'ftage_trailer' ),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => null,
        'supports'           => array( 'title', 'thumbnail',)
       );
       register_post_type( 'wpfilm_trailer', $args );

       // Taxonomy Trailer
       $labels = array(
        'name'              => _x( 'Trailer Categories', 'wpfilm-studio' ),
        'singular_name'     => _x( 'Trailer Category', 'wpfilm-studio' ),
        'search_items'      => esc_html__( 'Search Category' ),
        'all_items'         => esc_html__( 'All Category' ),
        'parent_item'       => esc_html__( 'Parent Category' ),
        'parent_item_colon' => esc_html__( 'Parent Category:' ),
        'edit_item'         => esc_html__( 'Edit Category' ),
        'update_item'       => esc_html__( 'Update Category' ),
        'add_new_item'      => esc_html__( 'Add New Category' ),
        'new_item_name'     => esc_html__( 'New Category Name' ),
        'menu_name'         => esc_html__( 'Trailer Category' ),
       );

       $args = array(
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array( 'slug' => 'wpfilm_trailer_category' ),
       );

       register_taxonomy('wpfilm_trailer_category','wpfilm_trailer',$args);

$cus_post_campaign = wpfilm_get_option( 'wpfilm_cus_post_campaign', 'settings' );

    if(!empty($cus_post_campaign)){
        $cus_post_campaign = wpfilm_get_option( 'wpfilm_cus_post_campaign', 'settings' );
        $cus_post_campaign_slug = wpfilm_get_option( 'wpfilm_cus_post_campaign', 'settings' );
        $cus_post_campaign_slug = strtolower( $cus_post_campaign_slug);
        $cus_post_campaign_slug = str_replace(' ', '-', $cus_post_campaign_slug);
    }else{
        $cus_post_campaign = 'Campaigns';
        $cus_post_campaign_slug = "wpcampaign";
    }

            // Register Campaign Post Type
            $labels = array(
                'name'                  => _x( $cus_post_campaign, 'Post Type General Name', 'wpfilm-studio' ),
                'archives'              => __( 'Campaign Archives', 'wpfilm-studio' ),
                'parent_item_colon'     => __( 'Parent Campaign:', 'wpfilm-studio' ),
                'add_new_item'          => __( 'Add New Campaign', 'wpfilm-studio' ),
                'add_new'               => __( 'Add New', 'wpfilm-studio' ),
                'new_item'              => __( 'New Campaign', 'wpfilm-studio' ),
                'edit_item'             => __( 'Edit Campaign', 'wpfilm-studio' ),
                'update_item'           => __( 'Update Campaign', 'wpfilm-studio' ),
                'view_item'             => __( 'View Campaign', 'wpfilm-studio' ),
                'search_items'          => __( 'Search Campaign', 'wpfilm-studio' ),
                'not_found'             => __( 'Not found', 'wpfilm-studio' ),
                'not_found_in_trash'    => __( 'Not found in Trash', 'wpfilm-studio' ),
                'featured_image'        => __( 'Featured Image', 'wpfilm-studio' ),
                'set_featured_image'    => __( 'Set featured image', 'wpfilm-studio' ),
                'remove_featured_image' => __( 'Remove featured image', 'wpfilm-studio' ),
                'use_featured_image'    => __( 'Use as featured image', 'wpfilm-studio' ),
                'insert_into_item'      => __( 'Insert into item', 'wpfilm-studio' ),
                'uploaded_to_this_item' => __( 'Uploaded to this item', 'wpfilm-studio' ),
                'items_list'            => __( 'Campaigns list', 'wpfilm-studio' ),
                'items_list_navigation' => __( 'Campaigns list navigation', 'wpfilm-studio' ),
                'filter_items_list'     => __( 'Filter items list', 'wpfilm-studio' ),
            );
            $args = array(
                'labels'                => $labels,
                'supports'              => array( 'title','editor', 'thumbnail','tag' ),
                'hierarchical'          => false,
                'public'                => true,
                'show_ui'               => true,
                'show_in_menu'          => 'wpfilm',
                'menu_position'         => 5,
                'menu_icon'             => 'dashicons-archive',
                'show_in_admin_bar'     => true,
                'show_in_nav_menus'     => true,
                'can_export'            => true,
                'has_archive'           => false,        
                'exclude_from_search'   => false,
                'publicly_queryable'    => true,
                'capability_type'       => 'post',
                'rewrite'           => array( 'slug' => $cus_post_campaign_slug ),
            );
            register_post_type( 'wpcampaign', $args );

           // Campaign Category
           $labels = array(
            'name'              => _x( 'Campaigns Categories', 'wpfilm-studio' ),
            'singular_name'     => _x( 'Campaigns Category', 'wpfilm-studio' ),
            'search_items'      => esc_html__( 'Search Category' ),
            'all_items'         => esc_html__( 'All Category' ),
            'parent_item'       => esc_html__( 'Parent Category' ),
            'parent_item_colon' => esc_html__( 'Parent Category:' ),
            'edit_item'         => esc_html__( 'Edit Category' ),
            'update_item'       => esc_html__( 'Update Category' ),
            'add_new_item'      => esc_html__( 'Add New Category' ),
            'new_item_name'     => esc_html__( 'New Category Name' ),
            'menu_name'         => esc_html__( 'Campaigns Category' ),
           );

           $args = array(
            'hierarchical'      => true,
            'labels'            => $labels,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => array( 'slug' => $cus_post_campaign_slug ),
           );

           register_taxonomy('campaign_category','wpcampaign',$args);
      }

          add_action( 'init', 'wpfilm_custom_post_register', 10 );

    }
?>