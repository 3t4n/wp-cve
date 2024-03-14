<?php

class Thumbnail_Settings {
	
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		add_filter( 'media_row_actions', array( $this, 'media_row_actions' ), 10, 3); 
		add_filter( 'attachment_fields_to_edit', array( $this, 'media_thumbnail_edit_link' ), 10, 20 );
	}
	
	public function media_row_actions($actions, $post, $detached){
		$actions['thued'] = '<a href="options-general.php?page=thumbnail_editor_setup_data&att_id='.$post->ID.'" title="Crop Thumbnail">'.__('Crop Thumbnail','thumbnail-editor').'</a>';
		return $actions;
	}
	
	public function media_thumbnail_edit_link( $form_fields, $post ) {
		$hide_info = '<style>.media-types-required-info{display:none;}</style>';
		
		$form_fields['th-editor-link'] = array(
			'label' => '',
			'input' => 'html',
			'html'  => '<a href="options-general.php?page=thumbnail_editor_setup_data&att_id='.$post->ID.'" title="Crop Thumbnail" class="button">'.__('Crop Thumbnail','thumbnail-editor').'</a>' . $hide_info,
		);
		
		return $form_fields;
	}
	
	public function admin_menu () {
		add_options_page( 'Thumbnail Editor','Thumbnail Editor','manage_options','thumbnail_editor_setup_data', array( $this, 'view' ) );
	}

	public function view(){
		$att_id = '';
		if(isset($_REQUEST['att_id'])){
			$att_id = (int)sanitize_text_field($_REQUEST['att_id']);
		}
		if($att_id == 0 || $att_id == ''){
			$this->settings();
		} else {
			$this->editor($att_id);
		}
	}
	
	public function show_message(){
		if(isset($GLOBALS['msg']) and $GLOBALS['msg'] != ''){
        	echo '<div class="updated notice notice-success"><p>'.$GLOBALS['msg'].'</p></div>';
			unset($GLOBALS['msg']);
		}
	}
	
	public function settings() {
		$thep_disable_srcset 		= get_option('thep_disable_srcset');
		$thep_disable_wh 			= get_option('thep_disable_wh');
		
		echo '<div class="wrap">';
		$this->show_message();
		$this->help_support();
		include( THE_PLUGIN_PATH . '/view/admin/settings.php');
		$this->thumbnail_editor_pro_add();
		$this->donate();
		echo '</div>';
		
	}

	public function editor() {
		echo '<div class="wrap">';
		$att_id = '';
		
		$this->show_message();
		$this->help_support();

		if(isset($_REQUEST['att_id'])){
			$att_id = sanitize_text_field($_REQUEST['att_id']);
		}
		
		if($att_id == ''){
			echo '<p>Goto <a href="upload.php"><strong>Media Library</strong></a> and select <strong>Crop Thumbnail</strong> link to modify image thumbnails.</p>';
			return;
		}
		
		$full_image = wp_get_attachment_image_src( $att_id, 'full' );
		
		if($full_image == ''){
			echo '<p>Please select an <strong>Image</strong> file to edit. Go back to <a href="upload.php"><strong>Media Library</strong></a></p>';
			return;
		}

		include( THE_PLUGIN_PATH . '/view/admin/editor-settings.php');

		$this->donate();
		echo '<div>';
	}
	

	public function help_support(){
		include( THE_PLUGIN_PATH . '/view/admin/help.php');
	}
	
	public function donate(){
		include( THE_PLUGIN_PATH . '/view/admin/donate.php');
	}
	
	public function thumbnail_editor_pro_add(){
		include( THE_PLUGIN_PATH . '/view/admin/pro-add.php');
	}
	
	public function get_image_sizes( $size = '' ) {
		global $_wp_additional_image_sizes;
		$sizes = array();
		$get_intermediate_image_sizes = get_intermediate_image_sizes();
		// Create the full array with sizes and crop info
		foreach( $get_intermediate_image_sizes as $_size ) {
				if ( in_array( $_size, array( 'thumbnail', 'medium', 'large' ) ) ) {
						$sizes[ $_size ]['width'] = get_option( $_size . '_size_w' );
						$sizes[ $_size ]['height'] = get_option( $_size . '_size_h' );
						$sizes[ $_size ]['crop'] = (bool) get_option( $_size . '_crop' );
		
				} elseif ( isset( $_wp_additional_image_sizes[ $_size ] ) ) {
						$sizes[ $_size ] = array( 
								'width' => $_wp_additional_image_sizes[ $_size ]['width'],
								'height' => $_wp_additional_image_sizes[ $_size ]['height'],
								'crop' =>  $_wp_additional_image_sizes[ $_size ]['crop']
						);
				}
		}
		if ( $size ) {
				if( isset( $sizes[ $size ] ) ) {
						return $sizes[ $size ];
				} else {
						return false;
				}
		}
		return $sizes;
	}
}