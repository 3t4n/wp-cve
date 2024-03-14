<?php



if ( ! defined('ABSPATH')) exit;  // if direct access 

class class_job_bm_post_types{
	
	public function __construct(){
		
		add_action( 'init', array( $this, 'job_bm_posttype_job' ), 0 );
		add_action( 'init', array( $this, 'job_bm_register_job_category' ), 0 );
		add_action( 'init', array( $this, 'job_bm_register_job_tags' ), 0 );

        add_action( 'init', array( $this, 'job_bm_posttype_application' ), 0 );
        //add_action( 'init', array( $this, 'job_bm_posttype_payments' ), 0 );


    }
	
	public function job_bm_posttype_job(){
		if ( post_type_exists( "job" ) )
		return;

		$singular  = __( 'Job', 'job-board-manager' );
		$plural    = __( 'Jobs', 'job-board-manager' );
	 
	 
		register_post_type( "job",
			apply_filters( "job_bm_post_type_job", array(
				'labels' => array(
					'name' 					=> $plural,
					'singular_name' 		=> $singular,
					'menu_name'             => $singular,
					'all_items'             => sprintf( __( 'All %s', 'job-board-manager' ), $plural ),
					'add_new' 				=> sprintf( __( 'Add %s', 'job-board-manager' ), $singular ),
					'add_new_item' 			=> sprintf( __( 'Add %s', 'job-board-manager' ), $singular ),
					'edit' 					=> __( 'Edit', 'job-board-manager' ),
					'edit_item' 			=> sprintf( __( 'Edit %s', 'job-board-manager' ), $singular ),
					'new_item' 				=> sprintf( __( 'New %s', 'job-board-manager' ), $singular ),
					'view' 					=> sprintf( __( 'View %s', 'job-board-manager' ), $singular ),
					'view_item' 			=> sprintf( __( 'View %s', 'job-board-manager' ), $singular ),
					'search_items' 			=> sprintf( __( 'Search %s', 'job-board-manager' ), $plural ),
					'not_found' 			=> sprintf( __( 'No %s found', 'job-board-manager' ), $plural ),
					'not_found_in_trash' 	=> sprintf( __( 'No %s found in trash', 'job-board-manager' ), $plural ),
					'parent' 				=> sprintf( __( 'Parent %s', 'job-board-manager' ), $singular )
				),
				'description' => sprintf( __( 'This is where you can create and manage %s.', 'job-board-manager' ), $plural ),
				'public' 				=> true,
				'show_ui' 				=> true,
				'capability_type' 		=> 'post',
				'map_meta_cap'          => true,
				'publicly_queryable' 	=> true,
				'exclude_from_search' 	=> false,
				'hierarchical' 			=> false,
				'rewrite' 				=> true,
				'query_var' 			=> true,
				'supports' 				=> array('title','editor','custom-fields','author','excerpt'),
				'show_in_nav_menus' 	=> false,
				'menu_icon' => 'dashicons-megaphone',
			) )
		); 
	 
	 
		}
		
		
		
		
		
		
		
		
	public function job_bm_register_job_category(){

			$singular  = __( 'Job Category', 'job-board-manager' );
			$plural    = __( 'Job Categories', 'job-board-manager' );
	 
			register_taxonomy( "job_category",
				apply_filters( 'job_bm_job_category_object_type', array( 'job' ) ),
				apply_filters( 'job_bm_job_category_args', array(
					'hierarchical' 			=> true,
					'show_admin_column' 	=> true,					
					'update_count_callback' => '_update_post_term_count',
					'label' 				=> $plural,
					'labels' => array(
						'name'              => $plural,
						'singular_name'     => $singular,
						'menu_name'         => ucwords( $plural ),
						'search_items'      => sprintf( __( 'Search %s', 'job-board-manager' ), $plural ),
						'all_items'         => sprintf( __( 'All %s', 'job-board-manager' ), $plural ),
						'parent_item'       => sprintf( __( 'Parent %s', 'job-board-manager' ), $singular ),
						'parent_item_colon' => sprintf( __( 'Parent %s:', 'job-board-manager' ), $singular ),
						'edit_item'         => sprintf( __( 'Edit %s', 'job-board-manager' ), $singular ),
						'update_item'       => sprintf( __( 'Update %s', 'job-board-manager' ), $singular ),
						'add_new_item'      => sprintf( __( 'Add New %s', 'job-board-manager' ), $singular ),
						'new_item_name'     => sprintf( __( 'New %s Name', 'job-board-manager' ),  $singular )
					),
					'show_ui' 				=> true,
					'public' 	     		=> true,
					'rewrite' => array(
						'slug' => 'job_category', // This controls the base slug that will display before each term
						'with_front' => false, // Don't display the category base before "/locations/"
						'hierarchical' => true // This will allow URL's like "/locations/boston/cambridge/"
				),
				) )
			);

		}	
		
		
		
		
	public function job_bm_register_job_tags(){

			$singular  = __( 'Job Tag', 'job-board-manager' );
			$plural    = __( 'Job Tags', 'job-board-manager' );
	 
			register_taxonomy( "job_tag",
				apply_filters( 'job_bm_job_tag_object_type', array( 'job' ) ),
				apply_filters( 'job_bm_job_tag_args', array(
					'hierarchical' 			=> false,
					'show_admin_column' 	=> true,					
					'update_count_callback' => '_update_post_term_count',
					'label' 				=> $plural,
					'labels' => array(
						'name'              => $plural,
						'singular_name'     => $singular,
						'menu_name'         => ucwords( $plural ),
						'search_items'      => sprintf( __( 'Search %s', 'job-board-manager' ), $plural ),
						'all_items'         => sprintf( __( 'All %s', 'job-board-manager' ), $plural ),
						'parent_item'       => sprintf( __( 'Parent %s', 'job-board-manager' ), $singular ),
						'parent_item_colon' => sprintf( __( 'Parent %s:', 'job-board-manager' ), $singular ),
						'edit_item'         => sprintf( __( 'Edit %s', 'job-board-manager' ), $singular ),
						'update_item'       => sprintf( __( 'Update %s', 'job-board-manager' ), $singular ),
						'add_new_item'      => sprintf( __( 'Add New %s', 'job-board-manager' ), $singular ),
						'new_item_name'     => sprintf( __( 'New %s Name', 'job-board-manager' ),  $singular )
					),
					'show_ui' 				=> true,
					'public' 	     		=> true,
					'rewrite' => array(
						'slug' => 'job_tag', // This controls the base slug that will display before each term
						'with_front' => false, // Don't display the category base before "/locations/"
						'hierarchical' => false // This will allow URL's like "/locations/boston/cambridge/"
				),
				) )
			);

		}




    public function job_bm_posttype_application(){
        if ( post_type_exists( "application" ) )
            return;

        $singular  = __( 'Application', 'job-board-manager' );
        $plural    = __( 'Applications', 'job-board-manager' );


        register_post_type( "application",
            apply_filters( "job_bm_post_type_application", array(
                'labels' => array(
                    'name' 					=> $plural,
                    'singular_name' 		=> $singular,
                    'menu_name'             => $singular,
                    'all_items'             => sprintf( __( 'All %s', 'job-board-manager' ), $plural ),
                    'add_new' 				=> __( 'Add New', 'job-board-manager' ),
                    'add_new_item' 			=> sprintf( __( 'Add %s', 'job-board-manager' ), $singular ),
                    'edit' 					=> __( 'Edit', 'job-board-manager' ),
                    'edit_item' 			=> sprintf( __( 'Edit %s', 'job-board-manager' ), $singular ),
                    'new_item' 				=> sprintf( __( 'New %s', 'job-board-manager' ), $singular ),
                    'view' 					=> sprintf( __( 'View %s', 'job-board-manager' ), $singular ),
                    'view_item' 			=> sprintf( __( 'View %s', 'job-board-manager' ), $singular ),
                    'search_items' 			=> sprintf( __( 'Search %s', 'job-board-manager' ), $plural ),
                    'not_found' 			=> sprintf( __( 'No %s found', 'job-board-manager' ), $plural ),
                    'not_found_in_trash' 	=> sprintf( __( 'No %s found in trash', 'job-board-manager' ), $plural ),
                    'parent' 				=> sprintf( __( 'Parent %s', 'job-board-manager' ), $singular )
                ),
                'description' => sprintf( __( 'This is where you can create and manage %s.', 'job-board-manager' ), $plural ),
                'public' 				=> true,
                'show_ui' 				=> true,
                'capability_type' 		=> 'post',
                'map_meta_cap'          => true,
                'publicly_queryable' 	=> true,
                'exclude_from_search' 	=> false,
                'hierarchical' 			=> false,
                'rewrite' 				=> true,
                'query_var' 			=> true,
                'supports' 				=> array('title','editor','author','comments'),
                'show_in_nav_menus' 	=> false,
                'show_in_menu' 	=> 'edit.php?post_type=job',
                'menu_icon' => 'dashicons-admin-users',
            ) )
        );


    }



    public function job_bm_posttype_payments(){
        if ( post_type_exists( "payment" ) )
            return;

        $singular  = __( 'Payment', 'job-board-manager' );
        $plural    = __( 'Payments', 'job-board-manager' );


        register_post_type( "payment",
            apply_filters( "job_bm_post_type_payments", array(
                'labels' => array(
                    'name' 					=> $plural,
                    'singular_name' 		=> $singular,
                    'menu_name'             => $singular,
                    'all_items'             => $plural,
                    'add_new' 				=> __( 'Add New', 'job-board-manager' ),
                    'add_new_item' 			=> sprintf( __( 'Add %s', 'job-board-manager' ), $singular ),
                    'edit' 					=> __( 'Edit', 'job-board-manager' ),
                    'edit_item' 			=> sprintf( __( 'Edit %s', 'job-board-manager' ), $singular ),
                    'new_item' 				=> sprintf( __( 'New %s', 'job-board-manager' ), $singular ),
                    'view' 					=> sprintf( __( 'View %s', 'job-board-manager' ), $singular ),
                    'view_item' 			=> sprintf( __( 'View %s', 'job-board-manager' ), $singular ),
                    'search_items' 			=> sprintf( __( 'Search %s', 'job-board-manager' ), $plural ),
                    'not_found' 			=> sprintf( __( 'No %s found', 'job-board-manager' ), $plural ),
                    'not_found_in_trash' 	=> sprintf( __( 'No %s found in trash', 'job-board-manager' ), $plural ),
                    'parent' 				=> sprintf( __( 'Parent %s', 'job-board-manager' ), $singular )
                ),
                'description' => sprintf( __( 'This is where you can create and manage %s.', 'job-board-manager' ), $plural ),
                'public' 				=> true,
                'show_ui' 				=> true,
                'capability_type' 		=> 'post',
                'map_meta_cap'          => true,
                'publicly_queryable' 	=> true,
                'exclude_from_search' 	=> false,
                'hierarchical' 			=> false,
                'rewrite' 				=> true,
                'query_var' 			=> true,
                'supports' 				=> array('title','author','custom-fields'),
                'show_in_nav_menus' 	=> false,
                'show_in_menu' 	=> 'edit.php?post_type=job',
                'menu_icon' => 'dashicons-admin-users',
            ) )
        );


    }




}
	

new class_job_bm_post_types();