<?php
/**
 * The admin ajax functionality of the plugin.
 *
 * @package    PluginOptimizer
 * @subpackage PluginOptimizer/admin
 * @author     Simple Online Systems <admin@simpleonlinesystems.com>
 */

class SOSPO_Ajax {

	/**
	 * Initialize the class and set its properties.
	 *
	 * @param string $plugin_name The name of this plugin.
	 * @param string $version The version of this plugin.
	 */
	function __construct() {

        $this->load_hooks();
	}

	/**
	 * Register all of the hooks related to the admin area functionality of the plugin.
	 *
	 * @access   private
	 */
	function load_hooks() {

		add_action( 'wp_ajax_po_save_filter',                   [ $this, 'po_save_filter'                   ] );
		add_action( 'wp_ajax_po_save_group',                    [ $this, 'po_save_group'                    ] );
		add_action( 'wp_ajax_po_save_category',                 [ $this, 'po_save_category'                 ] );
		add_action( 'wp_ajax_po_create_category',               [ $this, 'po_create_category'               ] );
		add_action( 'wp_ajax_po_delete_elements',               [ $this, 'po_delete_elements'               ] );
		add_action( 'wp_ajax_po_publish_elements',              [ $this, 'po_publish_elements'              ] );
		add_action( 'wp_ajax_po_turn_filter_on',                [ $this, 'po_turn_filter_on'                ] );
		add_action( 'wp_ajax_po_turn_filter_off',               [ $this, 'po_turn_filter_off'               ] );
		add_action( 'wp_ajax_po_mark_tab_complete',             [ $this, 'po_mark_tab_complete'             ] );
		add_action( 'wp_ajax_po_turn_off_filter',               [ $this, 'po_turn_off_filter'               ] );
		add_action( 'wp_ajax_po_save_original_menu',            [ $this, 'po_save_original_menu'            ] );
		add_action( 'wp_ajax_po_get_post_types',                [ $this, 'po_get_post_types'                ] );
		add_action( 'wp_ajax_po_save_columns_state',            [ $this, 'po_save_columns_state'            ] );
		add_action( 'wp_ajax_po_duplicate_filter',              [ $this, 'po_duplicate_filter'              ] );
		add_action( 'wp_ajax_po_update_database',               [ $this, 'po_update_database'               ] );

	}

  /**
   * Create/Update filter
   */
  function po_save_filter() {

    global $wpdb;

    if( empty( $_POST['data'] ) ){              wp_send_json_error( [ "message" => "The data never reached the server!" ] ); }
    
    parse_str( $_POST['data'], $array);
    
    if( empty( $array['SOSPO_filter_data'] ) ){ wp_send_json_error( [ "message" => "The data never reached the server!" ] ); }
    
    $data = SOSPO_Admin_Helper::format__save_filter_data( $array['SOSPO_filter_data'] );
    // sospo_mu_plugin()->write_log( $_POST, "po_save_filter-_POST" );
    // sospo_mu_plugin()->write_log( $data,  "po_save_filter-data"  );
    
    if( is_wp_error( $data ) ){
        
        wp_send_json_error( [ "message" => $data->get_error_message() ] );
    }
    
    // If there's no ID, then we are creating a post
    if( !isset( $data['ID'] ) ){

      // query how many published plgnoptimzr_filter posts
      $filter_count = $wpdb->get_var("
        SELECT count(*) as `filters` FROM `{$wpdb->prefix}posts` 
        WHERE `post_type` = 'plgnoptmzr_filter' 
        AND `post_status` = 'publish' 
      ");

      if( intval($filter_count) >= 10 ){
        wp_send_json_error( [ "message" => 'You have reached the limit of your free Plugin Optimizer filters. You can purchase our Premium plugin at PluginOptimizer.com to create more filters.' ] );
      }
    }

		$post_data = array(
			'post_title'  => $data["title"],
			'post_type'   => 'plgnoptmzr_filter',
			'post_status' => 'publish',
			'post_author' => 1,// TODO get_current_user_id() with localize_script in enqueue function
		);
        
    if( ! empty( $data["ID"] ) ){
        $post_data["ID"] = $data["ID"];
    }

		$post_id = wp_insert_post( $post_data, true );

		if ( is_wp_error( $post_id ) ) {
			wp_send_json_error( [ "message" => $post_id->get_error_message() ] );
		}

    // check if the agent plugin is installed
    if( in_array('plugin-optimizer-agent/plugin-optimizer-agent.php', get_option('active_plugins')) ){
        
        // if it is installed then add 'premium_filter' meta key to the post_id
        update_post_meta( $post_id, 'premium_filter', 'true');
    }

    if( ! empty( $data["meta"]["categories"] ) ){
        
        $category_ids = array_map( 'intval', array_keys( $data["meta"]["categories"] ) );
        
        $set_categories = wp_set_object_terms( $post_id, $category_ids, "plgnoptmzr_categories" );
    }
    

    foreach( $data["meta"]["endpoints"] as $page ){

      if( $pid = url_to_postid( site_url() . $page ) ){

        $permalink = get_permalink($pid); 

        $row = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}po_filtered_endpoints 
          WHERE filter_id = {$post_data['ID']} AND post_id = {$pid} AND url = '{$permalink}'");

        // if this row doesn't already exist, add it
        if( empty($row) )
          {
            $inserted = $wpdb->insert( 
              $wpdb->prefix . 'po_filtered_endpoints', 
              array( 
                'filter_id' => $post_data['ID'], 
                'post_id' => $pid, 
                'url' => $permalink, 
              ) 
            );
          }
      }
    }
    
    // Let's clear the categories first.
    $set_categories = wp_set_object_terms( $post_id, array(), "plgnoptmzr_categories" );

    if( ! empty( $data["meta"]["categories"] ) ){
        
        $category_ids = array_map( 'intval', array_keys( $data["meta"]["categories"] ) );
        
        // if we have categories, then we resave them.
        $set_categories = wp_set_object_terms( $post_id, $category_ids, "plgnoptmzr_categories" );
    }

    global $wpdb;

    $wpdb->query("DELETE FROM `{$wpdb->prefix}filters_group` WHERE `filter_id` = {$post_id}");

    if( isset($data['meta']['groups_used']) && !empty($data['meta']['groups_used']) ){

      foreach( $data['meta']['groups_used'] as $key => $group ){

        $wpdb->insert("{$wpdb->prefix}filters_group", array('filter_id'=>$post_id,'group_id'=>$key), array('%d','%d'));
      }
    }

    foreach( $data["meta"] as $meta_key => $meta_value ){
        
        update_post_meta( $post_id, $meta_key, $meta_value );
    }

    
    if( isset($data['meta']['frontend']) ){
      if( empty($data['meta']['frontend']) ){
        $frontend = 'false';
      } else {
        $frontend = 'true';
      }

      update_post_meta( $post_id, 'frontend', $frontend );

    }

    wp_send_json_success( [ "message" => "All good, the filter is saved.", "id" => $post_id, ] );

  }

  /**
   * Delete elements
   */
  function po_delete_elements() {
      
      $name_post_type = sanitize_textarea_field( $_POST['name_post_type'] );
      $type_elements  = sanitize_textarea_field( $_POST['type_elements'] );
      $id_elements    = array_map( 'intval', $_POST['id_elements'] );

      if ( $name_post_type === 'cat' ) {
          
          foreach ( $id_elements as $id_element ) {
              wp_delete_term( $id_element, 'plgnoptmzr_categories' );
          }

          wp_send_json_success( [ "status" => "success", "message" => "Categories are deleted." ] );
          
      } elseif ( $type_elements === 'all' ) {
       
          foreach ( $id_elements as $post_id ) {
              wp_trash_post( $post_id );
          }
          
          wp_send_json_success( [ "status" => "success", "message" => "Items are moved to trash." ] );
          
      } else {
          
          $posts = get_posts( array(
              'post_type'   => $name_post_type,
              'include'     => $id_elements,
              'post_status' => 'trash',
          ) );

          foreach ( $posts as $post ) {
              wp_delete_post( $post->ID, true );
          }
          
          wp_send_json_success( [ "status" => "success", "message" => "Items are permanently deleted." ] );
          
      }
  }

  /**
   * Restore works
   */
  function po_publish_elements() {
      
      $name_post_type = sanitize_textarea_field( $_POST['name_post_type'] );
      $id_elements    = array_map( 'intval', $_POST['id_elements'] );

      $posts = get_posts( array(
          'post_type'   => $name_post_type,
          'include'     => $id_elements,
          'post_status' => 'trash',
      ) );

      foreach ( $posts as $post ) {
          wp_publish_post( $post->ID );
      }
      
      wp_send_json_success( [ "message" => "Items are restored." ] );
      
  }
  
  /**
   * Bulk turn filters on
   */
  function po_turn_filter_on() {
      
      $name_post_type = sanitize_textarea_field( $_POST['name_post_type'] );
      $id_elements    = array_map( 'intval', $_POST['id_elements'] );

      if( (count($id_elements) + $active_filter_count) > 10 ){        
        wp_send_json_error( [ "message" => 'Unable to activate the selected filters. You may only have 10 active filters at one time.' ] );
      }

      $posts = get_posts( array(
          'post_type'   => $name_post_type,
          'include'     => $id_elements,
      ) );

      foreach ( $posts as $post ) {
          update_post_meta( $post->ID, "turned_off", false );
      }
      
      wp_send_json_success( [ "message" => count( $posts) . " filters are turned on." ] );
      
  }
  
  /**
   * Bulk turn filters off
   */
  function po_turn_filter_off() {
      
      $name_post_type = sanitize_textarea_field( $_POST['name_post_type'] );
      $id_elements    = array_map( 'intval', $_POST['id_elements'] );

      $posts = get_posts( array(
          'post_type'   => $name_post_type,
          'include'     => $id_elements,
      ) );

		foreach ( $posts as $post ) {
			wp_update_post([
        'ID' => $post->ID,
        'post_status' => 'draft'
      ]);
		}
        
		wp_send_json_success( [ "message" => count( $posts) . " filters are turned off." ] );
        
	}
  
  /**
   * Used for the Overview page
   */
  function po_mark_tab_complete(){
    
    $tab_id  = sanitize_textarea_field( $_POST["tab_id"] );
    $user_id = intval( $_POST["user_id"] );
    
    $user_tabs_completed = get_user_meta( $user_id, "completed_overview_tabs", true );
    
    if( empty( $user_tabs_completed ) ){
        $user_tabs_completed = [];
    }
    
    if( ! in_array( $tab_id, $user_tabs_completed ) ){
        
        $user_tabs_completed[] = $tab_id;
        sort( $user_tabs_completed );
        
        update_user_meta( $user_id, "completed_overview_tabs", $user_tabs_completed );
    }
      
      wp_send_json_success( [ "message" => "Completed tabs are now remembered." ] );
      
  }
        
  /**
   * From the Filters List page
   */
  function po_turn_off_filter(){
      
    $turned_off  = $_POST["turned_off"] === "true";
    $post_id     = intval( $_POST["post_id"] );
    
    if( $turned_off ){      
      $my_post = array(
        'ID'           => $post_id,
        'post_status'   => 'draft'
      );
    } else {

      global $wpdb;
      $count = $wpdb->get_var("SELECT count(*) as count FROM `{$wpdb->prefix}posts` WHERE `post_type`= 'plgnoptmzr_filter' AND `post_status` = 'publish'");

      if( $count < 10 ){          
        $my_post = array(
          'ID'           => $post_id,
          'post_status'   => 'publish'
        );
      } else {
         wp_send_json_error( [ "message" => 'Only 10 active filters are allowed. You may turn off one filter in order to enable another within 10 active filters.' ] );
      }
    }
 
    // Update the post into the database
    wp_update_post( $my_post );

    update_post_meta( $post_id, "turned_off", $turned_off );
      
    wp_send_json_success( [ "message" => "Filter turned " . ( $turned_off ? "off" : "on" ) . " successfully." ] );
      
  }
    
	/**
	 * Save the admin menu sidebar HTML in the database
	 */
	function po_save_original_menu(){

		$menu_html          = wp_kses_post( stripcslashes( $_POST["menu_html"]        ) );
		$topbar_menu_html   = wp_kses_post( stripcslashes( $_POST["topbar_menu_html"] ) );
		$new_html           = wp_kses_post( stripcslashes( $_POST["new_html"]         ) );

		// sospo_mu_plugin()->write_log( $menu_html, "po_save_original_menu-menu_html" );

		update_option( "plgnoptmzr_original_menu", $menu_html );
		update_option( "plgnoptmzr_topbar_menu",   $topbar_menu_html );
		update_option( "plgnoptmzr_new_posts",     $new_html );
	  
	  wp_send_json_success( [ "message" => "Menu saved successfully." ] );
	  
	}
  
  /**
   * Because Ajax is not being filtered, we can get a full list of post types
   */
  function po_get_post_types(){
      
    $post_types = [];
    
    $post_types_raw = get_post_types( [], "objects" );
    
    // Check for all filter posts that are assigned to a post type
    global $wpdb;

    $post_types_filters = wp_list_pluck( $post_types_raw, 'name' );
    $post_types_filters = implode("','", $post_types_filters);
    $post_types_filters = $wpdb->get_results("SELECT `meta_value` FROM `{$wpdb->prefix}postmeta` WHERE `meta_key` = 'filter_type' AND `meta_value` IN ('{$post_types_filters}')");
    $post_types_filters = wp_list_pluck( $post_types_filters, 'meta_value' );


    foreach( $post_types_raw as $post_type ){

        if( in_array($post_type->name, $post_types_filters) ) continue;

        $post_types[ $post_type->name ] = $post_type->labels->singular_name . " (" . $post_type->name . ")";
        
    }
    
    natsort( $post_types );
      
      wp_send_json_success( [ "message" => "Post types fetched.", "post_types" => $post_types ] );
      
  }
  
	/**
	* Saves the current state of visible columns on the Filters List screen
	*/
	function po_save_columns_state(){
		  
		// sospo_mu_plugin()->write_log( $_POST, "po_save_columns_state-_POST" );

		if( empty( $_POST['action'] ) || $_POST['action'] !== "po_save_columns_state" ){    wp_send_json_error( [ "message" => "We have somehow triggered the wrong action!" ] ); }

		$data = empty( $_POST['data'] ) ? [] : $_POST['data'];

		// sospo_mu_plugin()->write_log( $data, "po_save_columns_state-data" );

		if( $data ){
		    
		    foreach( $data as $index => $data_item ){
		        
		        $data[ $index ] = sanitize_text_field( $data_item );
		        
		    }
		    
		}


		// sospo_mu_plugin()->write_log( get_current_user_id(), "po_save_columns_state-get_current_user_id" );

		update_user_meta( get_current_user_id(), "sospo_filter_columns", $data );
		  
	  	wp_send_json_success( [ "message" => "All good, the columns are saved." ] );

	}

	function po_duplicate_filter(){

		$filter_id = $_POST['filter'];

		$data_type        = get_post_meta( $filter_id, 'filter_type',      true );
		$blocking_plugins = get_post_meta( $filter_id, 'plugins_to_block', true );
		$turned_off       = get_post_meta( $filter_id, 'turned_off',       true );
		$belongs_to_value = get_post_meta( $filter_id, 'belongs_to',       true );

		$post = (array) get_post( $filter_id ); // Post to duplicate.
		unset($post['ID']); // Remove id, wp will create new post if not set.
		$post['post_title'] = $post['post_title'] . ' copy';
		$new_filter_id = wp_insert_post($post);

		update_post_meta( $new_filter_id, 'filter_type', $data_type );
		update_post_meta( $new_filter_id, 'plugins_to_block', $blocking_plugins );
		update_post_meta( $new_filter_id, 'turned_off', $turned_off );
		update_post_meta( $new_filter_id, 'belongs_to', $belongs_to_value );

		die(json_encode(array('status'=>'success', 'filter'=>$filter_id)));
	}

	function po_update_database(){

		global $wpdb;

		if( !get_option('po_db_updated-v1.2') ){

		  $charset_collate = $wpdb->get_charset_collate();

		  $sql = "CREATE TABLE `{$wpdb->prefix}po_filtered_endpoints` (
		    id int(11) NOT NULL AUTO_INCREMENT,
		    filter_id int(11) NOT NULL,
		    post_id int(11) NOT NULL,
		    url varchar(55) DEFAULT '' NOT NULL,
		    PRIMARY KEY  (id)
		  ) $charset_collate;";

		  require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		  dbDelta( $sql );

		  update_option( 'po_db_updated-v1.2', 'true' );

		  die(json_encode(array('status'=>'success')));
		}

	}
}