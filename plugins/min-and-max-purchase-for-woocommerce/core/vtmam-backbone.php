<?php

class VTMAM_Backbone{   
	
	public function __construct(){
		  $this->vtmam_register_post_types();
      $this->vtmam_add_dummy_rule_category();
   //   add_filter( 'post_row_actions', array(&$this, 'vtmam_remove_row_actions'), 10, 2 );

	}
  
  public function vtmam_register_post_types() {
   global $vtmam_info;
  
  $tax_labels = array(
		'name' => _x( 'Min and Max Purchase Categories', 'taxonomy general name', 'vtmam' ),
		'singular_name' => _x( 'Min and Max Purchase Category', 'taxonomy singular name', 'vtmam' ),
		'search_items' => __( 'Search Min and Max Purchase Category', 'vtmam' ),
		'all_items' => __( 'All Min and Max Purchase Categories', 'vtmam' ),
		'parent_item' => __( 'Min and Max Purchase Category', 'vtmam' ),
		'parent_item_colon' => __( 'Min and Max Purchase Category:', 'vtmam' ),
		'edit_item' => __( 'Edit Min and Max Purchase Category', 'vtmam' ),
		'update_item' => __( 'Update Min and Max Purchase Category', 'vtmam' ),
		'add_new_item' => __( 'Add New Min and Max Purchase Category', 'vtmam' ),
		'new_item_name' => __( 'New Min and Max Purchase Category', 'vtmam' )
  ); 	

  
  $tax_args = array(
    'hierarchical' => true,
		'labels' => $tax_labels,
		'show_ui' => true,
		'query_var' => false,
		'rewrite' => array( 'slug' => 'vtmam_rule_category',  'with_front' => false, 'hierarchical' => true )
  ) ;            

  $taxonomy_name =  'vtmam_rule_category';
 
  
   //REGISTER TAXONOMY 
  	register_taxonomy($taxonomy_name, $vtmam_info['applies_to_post_types'], $tax_args); 

/*
 //all from woocommerce/woocommerce.php
		$category_base = ( get_option('woocommerce_prepend_shop_page_to_urls') == "yes" ) ? trailingslashit($base_slug) : '';

		$category_slug = ( get_option('woocommerce_product_category_slug') ) ? get_option('woocommerce_product_category_slug') : _x('product-category', 'slug', 'woocommerce');

register_taxonomy( 'vtmam_rule_category',
	        array('product'),
	        array(
	            'hierarchical' 			=> true,
	            'update_count_callback' => '_update_post_term_count',
	            'label' 				=> __( 'Min and Max Purchase Categories', 'woocommerce'),
	            'labels' => array(
	                    'name' 				=> __( 'Min and Max Purchase Categories', 'woocommerce'),
	                    'singular_name' 	=> __( 'PMin and Max Purchase Category', 'woocommerce'),
						'menu_name'			=> _x( 'Categories', 'Admin menu name', 'woocommerce' ),
	                    'search_items' 		=> __( 'Min and Max Purchase Product Categories', 'woocommerce'),
	                    'all_items' 		=> __( 'All Min and Max Purchaset Categories', 'woocommerce'),
	                    'parent_item' 		=> __( 'Min and Max Purchase Category', 'woocommerce'),
	                    'parent_item_colon' => __( 'Min and Max Purchase Product Category:', 'woocommerce'),
	                    'edit_item' 		=> __( 'Edit Min and Max Purchase Category', 'woocommerce'),
	                    'update_item' 		=> __( 'Update Min and Max Purchase Category', 'woocommerce'),
	                    'add_new_item' 		=> __( 'Add New Min and Max Purchase Category', 'woocommerce'),
	                    'new_item_name' 	=> __( 'New Min and Max Purchase Category Name', 'woocommerce')
	            	),
	            'show_ui' 				=> true,
	            'query_var' 			=> true,
	            'capabilities'			=> array(
	            	'manage_terms' 		=> 'manage_woocommerce_products',
	            	'edit_terms' 		=> 'manage_woocommerce_products',
	            	'delete_terms' 		=> 'manage_woocommerce_products',
	            	'assign_terms' 		=> 'manage_woocommerce_products',
	            ),
	            'rewrite' 				=> array( 'slug' => $category_base . $category_slug, 'with_front' => false, 'hierarchical' => true ),
	        )
	    );
*/
    
        
 //REGISTER POST TYPE
 $post_labels = array(
				'name' => _x( 'Min and Max Purchase Rules', 'post type name', 'vtmam' ),
        'singular_name' => _x( 'Min and Max Purchase Rule', 'post type singular name', 'vtmam' ),
        'add_new' => _x( 'Add New', 'admin menu: add new Min and Max Purchase Rule', 'vtmam' ),
        'add_new_item' => __('Add New Min and Max Purchase Rule', 'vtmam' ),
        'edit_item' => __('Edit Min and Max Purchase Rule', 'vtmam' ),
        'new_item' => __('New Min and Max Purchase Rule', 'vtmam' ),
        'view_item' => __('View Min and Max Purchase Rule', 'vtmam' ),
        'search_items' => __('Search Min and Max Purchase Rules', 'vtmam' ),
        'not_found' =>  __('No Min and Max Purchase Rules found', 'vtmam' ),
        'not_found_in_trash' => __( 'No Min and Max Purchase Rules found in Trash', 'vtmam' ),
        'parent_item_colon' => '',
        'menu_name' => __( 'Min and Max Purchase Rules', 'vtmam' )
			);
      
	register_post_type( 'vtmam-rule', array(
		  'capability_type' => 'post',
      'hierarchical' => true,
		  'exclude_from_search' => true,
      'labels' => $post_labels,
			'public' => true,
			'show_ui' => true,
      'query_var' => true,
      'rewrite' => false,     
      'supports' => array('title' )	 //remove 'revisions','editor' = no content/revisions boxes 
		)
	);
 
//	$role = get_role( 'administrator' );  v1.07 removed
//	$role->add_cap( 'read_vtmam-rule' );  v1.07 removed
}

  public function vtmam_add_dummy_rule_category () {
      $category_list = get_terms( 'vtmam_rule_category', 'hide_empty=0&parent=0' );
    	if ( count( $category_list ) == 0 ) {
    		wp_insert_term( __( 'Min and Max Purchase Category', 'vtmam' ), 'vtmam_rule_category', "parent=0" );
      }
  }


/*------------------------------------------------------------------------------------
  	remove quick edit for custom post type 
  ------------------------------------------------------------------------------------*/
 /*
  public function vtmam_remove_row_actions( $actions, $post )
  {
    global $current_screen;
  	if( $current_screen->post_type = 'vtmam-rule' ) {
    	unset( $actions['edit'] );
    	unset( $actions['view'] );
    	unset( $actions['trash'] );
    	unset( $actions['inline hide-if-no-js'] );
  	//$actions['inline hide-if-no-js'] .= __( 'Quick&nbsp;Edit' );
     }
  	return $actions;
  }
*/





function vtmam_register_settings() {
    register_setting( 'vtmam_options', 'vtmam_rules' );
} 


} //end class
$vtmam_backbone = new VTMAM_Backbone;
  
  
  
  class VTMAM_Functions {   
	
	public function __construct(){

	}
    
  function vtmam_getSystemMemInfo() 
  {       
      /* //V1.07
      $data = explode("\n", file_get_contents("/proc/meminfo"));
      $meminfo = array();
      foreach ($data as $line) {
          list($key, $val) = explode(":", $line);
          $meminfo[$key] = trim($val);
      }
      return $meminfo;
      */
      $meminfo = array();
      return $meminfo;      
  }
  
  } //end class