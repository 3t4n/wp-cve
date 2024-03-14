<?php

if ( !class_exists( 'APSC_Model_Archive' ) ) :

abstract class APSC_Model_Archive extends APSC_Model_Abstract_Record
{

	public function __construct()
	{
		
		global $APSC;
		
		parent::__construct();

	}

	protected function get_model_fields()
	{
		
		$fields = array(
			'use' => '',
			'posts_per_page' => '',
			'posts_per_page_num' => '',
			'order' => '',
			'orderby' => '',
			'orderby_set' => '',
			'ignore_words' => array(),
		);
		
		return $fields;
		
	}
	
	protected function setup_default_data()
	{
		
		global $APSC;

		$this->default_data = $this->initial_data;
		
		foreach( $this->default_data as $type => $setting ) {
			
			$this->default_data[ $type ]['use'] = 0;
			$this->default_data[ $type ]['posts_per_page'] = 'default';
			$this->default_data[ $type ]['posts_per_page_num'] = 0;
			$this->default_data[ $type ]['order'] = 'desc';
			$this->default_data[ $type ]['orderby'] = 'date';
			$this->default_data[ $type ]['orderby_set'] = false;
			
		}
		
	}
	
	public function data_format( $data )
	{
		
		global $APSC;

		if( empty( $data ) ) {

			return false;
			
		}
		
		if( is_object( $data ) ) {
			
			$data = (array) $data;
			
		}
		
		$new_data = $this->get_model_fields();
		
		if( !empty( $data['UPFN'] ) ) {

			$new_data['UPFN'] = $APSC->Form->UPFN;
			
		}

		unset( $data['UPFN'] );

		if( !empty( $data['use'] ) ) {

			$new_data['use'] = intval( $data['use'] );
			
		}

		unset( $data['use'] );

		if( !empty( $data['posts_per_page'] ) ) {

			$new_data['posts_per_page'] = strip_tags( $data['posts_per_page'] );
			
		}

		unset( $data['posts_per_page'] );

		if( !empty( $data['posts_per_page_num'] ) ) {

			$new_data['posts_per_page_num'] = intval( $data['posts_per_page_num'] );
			
		}

		unset( $data['posts_per_page_num'] );

		if( !empty( $data['order'] ) ) {

			$new_data['order'] = strip_tags( $data['order'] );
			
		}

		unset( $data['order'] );

		if( !empty( $data['orderby'] ) ) {

			$new_data['orderby'] = strip_tags( $data['orderby'] );
			
		}

		unset( $data['orderby'] );

		if( !empty( $data['orderby_set'] ) ) {

			$new_data['orderby_set'] = strip_tags( $data['orderby_set'] );
			
		}

		unset( $data['orderby_set'] );

		if( !empty( $data['ignore_words'] ) ) {
			
			foreach( $data['ignore_words'] as $key => $ignore_words ) {
				
				if( !empty( $ignore_words ) ) {

					$new_data['ignore_words'][ $key ] = strip_tags( $ignore_words );
					
				}
			
			}

		}

		unset( $data['ignore_words'] );
		
		if( $new_data['posts_per_page'] != 'set' ) {
			
			$new_data['posts_per_page_num'] = 0;
			
		}

		if( $new_data['orderby'] == 'title' ) {
			
			$new_data['orderby_set'] = '';
			
		} elseif( $new_data['orderby'] == 'custom_fields' ) {
			
			$new_data['ignore_words'] = array();

		} else {
			
			$new_data['orderby_set'] = '';
			$new_data['ignore_words'] = array();

		}
		
		if( empty( $new_data['use'] ) ) {
			
			$new_data = array();
			
		}
		
		return $new_data;
		
	}

	public function update_data( $post_data = array() )
	{
		
		global $APSC;
		
		$errors = new WP_Error();

		if( empty( $post_data['data']['default'] ) ) {
			
			$errors->add( 'empty_update_data' , sprintf( __( 'Empty Data.' , $APSC->ltd ) ) );
			return $errors;
			
		}
		
		$update_data = array();

		foreach( $post_data['data'] as $type => $data ) {
		
			$update_data[ $type ] = $this->data_format( $data );
			
		}
		
		$this->update_blog_record( $update_data );
		
		return $errors;

	}
	
	public function remove_data( $post_data = array() )
	{
		
		global $APSC;

		$errors = new WP_Error();

		$this->remove_blog_record();
		
		return $errors;
		
	}
	
}

endif;
