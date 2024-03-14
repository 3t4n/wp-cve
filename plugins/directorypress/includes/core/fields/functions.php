<?php

function directorypress_is_field_label_on_grid() {
	global $directorypress_object;
	//$status = 'hide';
	global $wpdb;
	$field_ids = $wpdb->get_results('SELECT id, is_hide_name_on_grid FROM '.$wpdb->prefix.'directorypress_fields');
	foreach( $field_ids as $field_id ) {
		$singlefield_id = $field_id->id;
		if($field_id->is_hide_name_on_grid == 'show_only_label'){	
			$status = 'show_only_label';
		}elseif($field_id->is_hide_name_on_grid == 'show_icon_label'){	
			$status = 'show_icon_label';
		}elseif($field_id->is_hide_name_on_grid == 'show_only_icon'){	
			$status = 'show_only_icon';
		}else{
			$status = 'hide';
		}
												
	}
	return $status;
}

function isField_on_exerpt(){
	global $directorypress_object;
	
	foreach ($directorypress_object->fields->fields_array as $field) {
		if ($field->on_exerpt_page)
			return true;
	}
	return false;
}

function isField_on_exerpt_list(){
	global $directorypress_object;
	
	foreach ($directorypress_object->fields->fields_array as $field) {
		if ($field->on_exerpt_page_list)
			return true;
	}
	return false;
}

function isField_inLine(){
	global $directorypress_object;
	
	foreach ($directorypress_object->fields->fields_array as $field) {
		if ($field->is_field_in_line)
			return true;
	}
	return false;
}

function isField_not_empty($listing){
	global $directorypress_object, $wpdb;
	$field_ids = $wpdb->get_results('SELECT id, type, slug, group_id FROM '. $wpdb->prefix .'directorypress_fields');

	
	foreach ($field_ids as $field) {
    	if(!is_null($listing->fields[$field->id])){
    		if($listing->fields[$field->id]->is_field_not_empty($listing)){
    			return true;
    		}
    	}
	}
	return false;
}


function isField_inBlock(){
	global $directorypress_object;
	
	foreach ($directorypress_object->fields->fields_array as $field) {
		if (!$field->is_field_in_line)
			return true;
	}
	return false;
}

function directorypress_is_field_label_on_list() {
	global $directorypress_object;
	//$status = 'hide';
	global $wpdb;
	$field_ids = $wpdb->get_results('SELECT id, is_hide_name_on_list FROM '.$wpdb->prefix.'directorypress_fields');
	foreach( $field_ids as $field_id ) {
		$singlefield_id = $field_id->id;
		if($field_id->is_hide_name_on_list == 'show_only_label'){	
			$status = 'show_only_label';
		}elseif($field_id->is_hide_name_on_list == 'show_icon_label'){	
			$status = 'show_icon_label';
		}elseif($field_id->is_hide_name_on_list == 'show_only_icon'){	
			$status = 'show_only_icon';
		}else{
			$status = 'hide';
		}
												
	}
	return $status;
}

/* === backend ajax === */

// fields list
if( !function_exists('directorypress_fields_list') ){
	function directorypress_fields_list(){
		global $directorypress_object;             	
        $response 	= ''; 
		$response .= $directorypress_object->fields_handler_property->fields_list_ajax();
		echo wp_kses_post($response); 
		die();
		
	}
	add_action('wp_ajax_directorypress_fields_list', 'directorypress_fields_list');
    add_action('wp_ajax_nopriv_directorypress_fields_list', 'directorypress_fields_list');
}

// fields order
if( !function_exists('directorypress_fields_reorder') ){
	function directorypress_fields_reorder(){
		global $directorypress_object;            	
       // $response 	= array(); 
		if ( !current_user_can( 'manage_options' ) ) {
			$response = esc_html__('No Permission!', 'DIRECTORYPRESS'); 
		}
		$new_order = sanitize_text_field($_POST['new_order']);
		$action = 'reorder';
		$response = $directorypress_object->fields_handler_property->fields_list_order($new_order, $action);
		$response = directorypress_renderMessages();
		echo wp_kses_post($response); 
		die();
		
	}
	add_action('wp_ajax_directorypress_fields_reorder', 'directorypress_fields_reorder');
    add_action('wp_ajax_nopriv_directorypress_fields_reorder', 'directorypress_fields_reorder');
}
// create new fields action
if( !function_exists('directorypress_fields_create_new_action') ){
	function directorypress_fields_create_new_action(){
		global $directorypress_object;             	
        $response 	= ''; 
		$response .= $directorypress_object->fields_handler_property->add_or_edit_fields();
		echo wp_kses_post($response); 
		die();
		
	}
	add_action('wp_ajax_directorypress_fields_create_new_action', 'directorypress_fields_create_new_action');
    add_action('wp_ajax_nopriv_directorypress_fields_create_new_action', 'directorypress_fields_create_new_action');
}
// create new fields
if( !function_exists('directorypress_fields_create_new') ){
	function directorypress_fields_create_new(){
		global $directorypress_object;              	
        $response 	= array(); 
		
		$id = '';
		$action = 'submit';
		$directorypress_object->fields_handler_property->add_or_edit_fields($id, $action);

		$html = directorypress_renderMessages();
		echo wp_kses_post($html); 
		die();
		
	}
	add_action('wp_ajax_directorypress_fields_create_new', 'directorypress_fields_create_new');
    add_action('wp_ajax_nopriv_directorypress_fields_create_new', 'directorypress_fields_create_new');
}

// fields delete action
if( !function_exists('directorypress_fields_delete_action') ){
	function directorypress_fields_delete_action(){
		global $directorypress_object;             	
        $response 	= ''; 
		$id = sanitize_text_field($_POST['id']);
		$action = '';
		$response .= '<input type="hidden" name="id" value="'.$id.'" />';
		$response .= $directorypress_object->fields_handler_property->delete_field($id, $action);
		
		echo wp_kses_post($response); 
		die();
		
	}
	add_action('wp_ajax_directorypress_fields_delete_action', 'directorypress_fields_delete_action');
    add_action('wp_ajax_nopriv_directorypress_fields_delete_action', 'directorypress_fields_delete_action');
}

// fields delete
if( !function_exists('directorypress_fields_delete') ){
	function directorypress_fields_delete(){
		global $directorypress_object;            	
       // $response 	= array(); 
		$id = sanitize_text_field($_POST['id']);
		$action = 'delete';
		$directorypress_object->fields_handler_property->delete_field($id, $action);
		$response = directorypress_renderMessages();
		echo wp_kses_post($response); 
		die();
		
	}
	add_action('wp_ajax_directorypress_fields_delete', 'directorypress_fields_delete');
    add_action('wp_ajax_nopriv_directorypress_fields_delete', 'directorypress_fields_delete');
}

// fields edit action
if( !function_exists('directorypress_fields_edit_action') ){
	function directorypress_fields_edit_action(){
		global $directorypress_object;             	
        $response 	= ''; 
		$id = sanitize_text_field($_POST['id']);
		$action = '';
		$response .= $directorypress_object->fields_handler_property->add_or_edit_fields($id, $action);
		
		echo wp_kses_post($response); 
		die();
		
	}
	add_action('wp_ajax_directorypress_fields_edit_action', 'directorypress_fields_edit_action');
    add_action('wp_ajax_nopriv_directorypress_fields_edit_action', 'directorypress_fields_edit_action');
}

// fields edit
if( !function_exists('directorypress_fields_edit') ){
	function directorypress_fields_edit(){
		global $directorypress_object;            	
        $response 	= array(); 
		$do_check = check_ajax_referer('directorypress_fields_nonce', 'directorypress_fields_nonce', false);
		if ($do_check == false) {
           $response = esc_html__('No kiddies please!', 'DIRECTORYPRESS');        
        }
		$id = sanitize_text_field($_POST['id']);
		$action = 'submit';
		$directorypress_object->fields_handler_property->add_or_edit_fields($id, $action);
		$response = directorypress_renderMessages();
		echo wp_kses_post($response); 
		die();
		
	}
	add_action('wp_ajax_directorypress_fields_edit', 'directorypress_fields_edit');
    add_action('wp_ajax_nopriv_directorypress_fields_edit', 'directorypress_fields_edit');
}

// fields edit action
if( !function_exists('directorypress_fields_config_action') ){
	function directorypress_fields_config_action(){
		global $directorypress_object;             	
        $response 	= ''; 
		$id = sanitize_text_field($_POST['id']);
		$action = '';
		$response .= $directorypress_object->fields_handler_property->field_settings($id, $action);
		
		echo wp_kses_post($response); 
		die();
		
	}
	add_action('wp_ajax_directorypress_fields_config_action', 'directorypress_fields_config_action');
    add_action('wp_ajax_nopriv_directorypress_fields_config_action', 'directorypress_fields_config_action');
}

// fields edit
if( !function_exists('directorypress_fields_config') ){
	function directorypress_fields_config(){
		global $directorypress_object;            	
        $response 	= array(); 
		$do_check = check_ajax_referer('directorypress_configure_fields_nonce', 'directorypress_configure_fields_nonce', false);
		if ($do_check == false) {
           $response = esc_html__('No kiddies please!', 'DIRECTORYPRESS');        
        }
		$id = sanitize_text_field($_POST['id']);
		$action = 'config';
		$directorypress_object->fields_handler_property->field_settings($id, $action);
		$response = directorypress_renderMessages();
		echo wp_kses_post($response); 
		die();
		
	}
	add_action('wp_ajax_directorypress_fields_config', 'directorypress_fields_config');
    add_action('wp_ajax_nopriv_directorypress_fields_config', 'directorypress_fields_config');
}

// fields edit action
if( !function_exists('directorypress_fields_search_config_action') ){
	function directorypress_fields_search_config_action(){
		global $directorypress_object;             	
        $response 	= ''; 
		$id = sanitize_text_field($_POST['id']);
		$action = '';
		$response .= $directorypress_object->search_fields->search_field_settings($id, $action);
		
		echo wp_kses_post($response);
		die();
		
	}
	add_action('wp_ajax_directorypress_fields_search_config_action', 'directorypress_fields_search_config_action');
    add_action('wp_ajax_nopriv_directorypress_fields_search_config_action', 'directorypress_fields_search_config_action');
}

// fields edit
if( !function_exists('directorypress_fields_search_config') ){
	function directorypress_fields_search_config(){
		global $directorypress_object;            	
        $response 	= array(); 
		$do_check = check_ajax_referer('directorypress_configure_fields_nonce', 'directorypress_configure_fields_nonce', false);
		if ($do_check == false) {
           $response = esc_html__('No kiddies please!', 'DIRECTORYPRESS');        
        }
		$id = sanitize_text_field($_POST['id']);
		$action = 'search_config';
		$directorypress_object->search_fields->search_field_settings($id, $action);
		$response = directorypress_renderMessages();
		echo wp_kses_post($response); 
		die();
		
	}
	add_action('wp_ajax_directorypress_fields_search_config', 'directorypress_fields_search_config');
    add_action('wp_ajax_nopriv_directorypress_fields_search_config', 'directorypress_fields_search_config');
}


// fields edit
if( !function_exists('directorypress_fields_assign_group') ){
	function directorypress_fields_assign_group(){
		global $directorypress_object;            	
        $response 	= array(); 
		$do_check = check_ajax_referer('directorypress_fields_nonce', 'directorypress_fields_nonce', false);
		if ($do_check == false) {
           $response = esc_html__('No kiddies please!', 'DIRECTORYPRESS');        
        }
		$id = sanitize_text_field($_POST['id']);
		$group_id = sanitize_text_field($_POST['group_id']);
		$action = 'submit';
		$directorypress_object->fields_handler_property->assign_field_group($id, esc_attr($group_id));
		$response = directorypress_renderMessages();
		echo wp_kses_post($response); 
		die();
		
	}
	add_action('wp_ajax_directorypress_fields_assign_group', 'directorypress_fields_assign_group');
    add_action('wp_ajax_nopriv_directorypress_fields_assign_group', 'directorypress_fields_assign_group');
}

// fields configuration
if( !function_exists('directorypress_fields_configure') ){
	function directorypress_fields_configure(){
		global $directorypress_object;             	
        $response 	= ''; 
		$id = sanitize_text_field($_POST['id']);		
		$response .= $directorypress_object->fields_handler_property->configure(esc_attr($id));
		echo wp_kses_post($response);
		die();
		
	}
	add_action('wp_ajax_directorypress_fields_configure', 'directorypress_fields_configure');
    add_action('wp_ajax_nopriv_directorypress_fields_configure', 'directorypress_fields_configure');
}


/* === Fields Group Ajax === */

// fields list
if( !function_exists('directorypress_fields_group_list') ){
	function directorypress_fields_group_list(){
		global $directorypress_object;             	
        $response 	= ''; 
		$response .= $directorypress_object->fields_handler_property->fields_group_list_ajax();
		echo wp_kses_post($response); 
		die();
		
	}
	add_action('wp_ajax_directorypress_fields_group_list', 'directorypress_fields_group_list');
    add_action('wp_ajax_nopriv_directorypress_fields_group_list', 'directorypress_fields_group_list');
}

// fields order
if( !function_exists('directorypress_fields_group_reorder') ){
	function directorypress_fields_group_reorder(){
		global $directorypress_object;            	
       // $response 	= array(); 
		$new_order = sanitize_text_field($_POST['new_order']);
		$action = 'reorder';
		$directorypress_object->fields_handler_property->fields_list_order(esc_attr($new_order), $action);
		$response = directorypress_renderMessages();
		echo wp_kses_post($response); 
		die();
		
	}
	add_action('wp_ajax_directorypress_fields_group_reorder', 'directorypress_fields_group_reorder');
    add_action('wp_ajax_nopriv_directorypress_fields_group_reorder', 'directorypress_fields_group_reorder');
}
// create new fields action
if( !function_exists('directorypress_fields_group_create_new_action') ){
	function directorypress_fields_group_create_new_action(){
		global $directorypress_object;             	
        $response 	= ''; 
		$response .= $directorypress_object->fields_handler_property->add_or_edit_field_groups();
		echo wp_kses_post($response); 
		die();
		
	}
	add_action('wp_ajax_directorypress_fields_group_create_new_action', 'directorypress_fields_group_create_new_action');
    add_action('wp_ajax_nopriv_directorypress_fields_group_create_new_action', 'directorypress_fields_group_create_new_action');
}
// create new fields
if( !function_exists('directorypress_fields_group_create_new') ){
	function directorypress_fields_group_create_new(){
		global $directorypress_object;              	
        $response 	= array(); 
		$do_check = check_ajax_referer('directorypress_fields_group_nonce', 'directorypress_fields_group_nonce', false);
		if ($do_check == false) {
           $response = esc_html__('No kiddies please!', 'DIRECTORYPRESS');        
        }
		$id = '';
		$action = 'submit';
		$directorypress_object->fields_handler_property->add_or_edit_field_groups($id, $action);
		$response = directorypress_renderMessages();
		echo wp_kses_post($response); 
		die();
		
	}
	add_action('wp_ajax_directorypress_fields_group_create_new', 'directorypress_fields_group_create_new');
    add_action('wp_ajax_nopriv_directorypress_fields_group_create_new', 'directorypress_fields_group_create_new');
}

// fields delete action
if( !function_exists('directorypress_fields_group_delete_action') ){
	function directorypress_fields_group_delete_action(){
		global $directorypress_object;             	
        $response 	= ''; 
		$id = sanitize_text_field($_POST['id']);
		$action = '';
		$response .= '<input type="hidden" name="id" value="'.$id.'" />';
		$response .= $directorypress_object->fields_handler_property->delete_field_group(esc_attr($id), $action);
		
		echo $response; 
		die();
		
	}
	add_action('wp_ajax_directorypress_fields_group_delete_action', 'directorypress_fields_group_delete_action');
    add_action('wp_ajax_nopriv_directorypress_fields_group_delete_action', 'directorypress_fields_group_delete_action');
}

// fields delete
if( !function_exists('directorypress_fields_group_delete') ){
	function directorypress_fields_group_delete(){
		global $directorypress_object;            	
       // $response 	= array(); 
		$id = sanitize_text_field($_POST['id']);
		$action = 'delete';
		$directorypress_object->fields_handler_property->delete_field_group(esc_attr($id), $action);
		$response = directorypress_renderMessages();
		echo wp_kses_post($response); 
		die();
		
	}
	add_action('wp_ajax_directorypress_fields_group_delete', 'directorypress_fields_group_delete');
    add_action('wp_ajax_nopriv_directorypress_fields_group_delete', 'directorypress_fields_group_delete');
}

// fields edit action
if( !function_exists('directorypress_fields_group_edit_action') ){
	function directorypress_fields_group_edit_action(){
		global $directorypress_object;             	
        $response 	= ''; 
		$id = sanitize_text_field($_POST['id']);
		$action = '';
		$response .= $directorypress_object->fields_handler_property->add_or_edit_field_groups(esc_attr($id), $action);
		
		echo wp_kses_post($response); 
		die();
		
	}
	add_action('wp_ajax_directorypress_fields_group_edit_action', 'directorypress_fields_group_edit_action');
    add_action('wp_ajax_nopriv_directorypress_fields_group_edit_action', 'directorypress_fields_group_edit_action');
}

// fields edit
if( !function_exists('directorypress_fields_group_edit') ){
	function directorypress_fields_group_edit(){
		global $directorypress_object;            	
        $response 	= array(); 
		$do_check = check_ajax_referer('directorypress_fields_group_nonce', 'directorypress_fields_group_nonce', false);
		if ($do_check == false) {
           $response = esc_html__('No kiddies please!', 'DIRECTORYPRESS');        
        }
		$id = sanitize_text_field($_POST['id']);
		$action = 'submit';
		$directorypress_object->fields_handler_property->add_or_edit_field_groups($id, $action);
		$response = directorypress_renderMessages();
		echo wp_kses_post($response); 
		die();
		
	}
	add_action('wp_ajax_directorypress_fields_group_edit', 'directorypress_fields_group_edit');
    add_action('wp_ajax_nopriv_directorypress_fields_group_edit', 'directorypress_fields_group_edit');
}

// fields configuration
if( !function_exists('directorypress_fields_group_configure') ){
	function directorypress_fields_group_configure(){
		global $directorypress_object;             	
        $response 	= '';
		$id = sanitize_text_field($_POST['id']);		
		$response .= $directorypress_object->fields_handler_property->group_configure($id);
		echo wp_kses_post($response);
		die();
		
	}
	add_action('wp_ajax_directorypress_fields_group_configure', 'directorypress_fields_group_configure');
    add_action('wp_ajax_nopriv_directorypress_fields_group_configure', 'directorypress_fields_group_configure');
}
