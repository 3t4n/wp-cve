<?php

if ( !class_exists( 'APSC_Admin_Controller_Manager_Archive_Tab_Taxonomies' ) ) :

final class APSC_Admin_Controller_Manager_Archive_Tab_Taxonomies {

	public $id;
	private $MainModel;

	public function __construct()
	{
		
		global $APSC;

		$this->id = 'taxonomies';

		$taxonomies = $APSC->Helper->get_taxonomies();
		
		if( empty( $taxonomies ) ) {
			
			return false;
			
		}
		
		$this->MainModel = new APSC_Model_Taxonomies();
		
		add_filter( $APSC->main_slug . '_admin_tab_view_' . $this->id , array( $this , 'admin_tab_view' ) );
		add_filter( $APSC->main_slug . '_add_tabs' , array( $this , 'add_tabs' ) );
		add_filter( $APSC->main_slug . '_get_settings_' . $this->id , array( $this , 'get_settings' ) , 10 , 2 );
		add_filter( $APSC->main_slug . '_post_data_update_' . $this->id , array( $this , 'post_data_update' ) );
		add_filter( $APSC->main_slug . '_post_data_remove_' . $this->id , array( $this , 'post_data_remove' ) );

	}
	
	public function admin_tab_view()
	{
		
		return $this->id . '.php';
		
	}
	
	public function add_tabs( $tabs )
	{
		
		global $APSC;

		$tabs[ $this->id ] = __( 'Taxonomies' , $APSC->ltd );
		
		return $tabs;
		
	}
	
	public function get_settings( $settings , $args )
	{
		
		global $APSC;

		$field = $args['name_field'];
		
		$settings_data = $this->MainModel->get_blog_datas();
		
		if( empty( $settings_data[$field] ) ) {
			
			$settings_data = $this->MainModel->get_default_data();
			
		}
		
		return $settings_data[$field];
		
	}

	public function post_data_update( $post_data )
	{
		
		$errors = $this->MainModel->update_data( $post_data );

		return $errors;
		
	}

	public function post_data_remove( $post_data )
	{
		
		global $APSC;

		$taxonomies = $APSC->Helper->get_taxonomies();
		
		$first_taxonomy = reset( $taxonomies );
		
		$select_tax = $first_taxonomy->name;
		
		if( !empty( $_GET['taxonomy'] ) ) {
			
			$select_tax = strip_tags( $_GET['taxonomy'] );
		
		}

		$errors = $this->MainModel->remove_data( $select_tax );

		return $errors;
		
	}

}

new APSC_Admin_Controller_Manager_Archive_Tab_Taxonomies();

endif;
