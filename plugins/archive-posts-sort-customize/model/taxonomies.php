<?php

if ( !class_exists( 'APSC_Model_Taxonomies' ) ) :

final class APSC_Model_Taxonomies extends APSC_Model_Archive
{

	public function __construct()
	{
		
		global $APSC;
		
		$this->record = $APSC->main_slug . '_taxonomies';
		
		$this->initial_data = array();
		
		$taxonomies = $APSC->Helper->get_taxonomies();
		
		if( !empty( $taxonomies ) ) {
			
			foreach( $taxonomies as $taxonomy ) {
				
				$terms = $APSC->Helper->get_terms( $taxonomy->name );
				
				$field_key = esc_html( 'default_' . $taxonomy->name );
				$this->initial_data[ $field_key ] = $this->get_model_fields();
				
				if( empty( $terms ) ) {
					
					continue;
					
				}
				
				foreach( $terms as $term ) {
					
					$field_key = esc_html( $taxonomy->name . '_' . $term->term_id );
					$this->initial_data[ $field_key ] = $this->get_model_fields();

				}
				
			}
			
		}
		
		$this->setup_default_data();
		
		parent::__construct();

	}
	
	public function update_data( $post_data = array() )
	{
		
		global $APSC;
		
		$errors = new WP_Error();

		if( empty( $post_data['data'] ) ) {
			
			$errors->add( 'empty_update_data' , sprintf( __( 'Empty Data.' , $APSC->ltd ) ) );
			return $errors;
			
		}
		
		$update_data = array();

		foreach( $post_data['data'] as $type => $data ) {
		
			$update_data[ $type ] = $this->data_format( $data );
			
		}
		
		$settings_data = $this->get_blog_datas();
		
		if( !empty( $settings_data ) ) {
			
			foreach( $update_data as $type => $data ) {
				
				$settings_data[ $type ] = $data;

			}
			
		} else {
			
			$settings_data = $update_data;
			
		}
		
		$this->update_blog_record( $settings_data );
		
		return $errors;

	}
	
	public function remove_data( $select_tax = false )
	{
		
		global $APSC;

		$errors = new WP_Error();
		
		if( empty( $select_tax ) ) {
			
			$errors->add( 'empty_remove_data' , sprintf( __( 'Empty Data.' , $APSC->ltd ) ) );
			return $errors;
			
		}
		
		$taxonomy = get_taxonomy( $select_tax );
		
		if( empty( $taxonomy ) or is_wp_error( $taxonomy ) ) {
			
			$errors->add( 'empty_remove_data' , sprintf( __( 'Empty Data.' , $APSC->ltd ) ) );
			return $errors;

		}

		$settings_data = $this->get_blog_datas();
		
		if( empty( $settings_data ) ) {
			
			$errors->add( 'empty_remove_data' , sprintf( __( 'Empty Data.' , $APSC->ltd ) ) );
			return $errors;
			
		}
		
		$field_key = esc_html( 'default_' . $taxonomy->name );
		
		if( isset( $settings_data[ $field_key ] ) ) {
			
			unset( $settings_data[ $field_key ] );
			
		}
		
		$terms = $APSC->Helper->get_terms( $select_tax );
		
		if( !empty( $terms ) ) {
			
			foreach( $terms as $term ) {
				
				$field_key = esc_html( $taxonomy->name . '_' . $term->term_id );

				if( isset( $settings_data[ $field_key ] ) ) {
					
					unset( $settings_data[ $field_key ] );
					
				}
				
			}
			
		}

		$this->update_blog_record( $settings_data );
		
		return $errors;
		
	}
	
}

endif;
