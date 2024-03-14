<?php

if ( !class_exists( 'APSC_Api' ) ) :

final class APSC_Api
{

	public function __construct() {}
	
	public function is_archive( $query = false )
	{
		
		global $APSC;
		
		$query = $APSC->Helper->get_wp_query( $query );
		
		if( ! is_object( $query ) ) {
			
			return false;
			
		}
		
		if( ! $query->is_main_query() ) {
			
			return false;
			
		}
		
		if( $query->is_archive() ) {
			
			return true;
			
		}

		if( $query->is_home() ) {
			
			return true;
			
		}

		if( $query->is_search() ) {
			
			return true;
			
		}

		return false;
		
	}
	
	public function get_archive_type( $query = false )
	{
		
		global $APSC;

		if( ! $this->is_archive( $query ) ) {
			
			return false;
			
		}

		$query = $APSC->Helper->get_wp_query( $query );
		
		$archive_type = array( 'id' => '' , 'args' => array() );
		
		if( !empty( $query->query['order'] ) ) {
			
			$archive_type['id'] = 'custom';
			
		} elseif( $query->is_home() ) {
			
			$archive_type['id'] = 'home';
			
		} elseif( $query->is_search() ) {
			
			$archive_type['id'] = 'search';

		} elseif( $query->is_archive() ) {
			
			if( $query->is_date() ) {
				
				$archive_type['id'] = 'date';
				
				if( $query->is_year() ) {
					
					$archive_type['args']['date'] = 'yearly';

				} elseif( $query->is_month() ) {
					
					$archive_type['args']['date'] = 'monthly';

				} elseif( $query->is_day() ) {
					
					$archive_type['args']['date'] = 'daily';

				}

			} elseif( $query->is_category() or $query->is_tag() or $query->is_tax() ) {
				
				$archive_type['id'] = 'taxonomies';

				$queried_object = $query->get_queried_object();
				
				if( !empty( $queried_object->taxonomy ) && !empty( $queried_object->term_id ) ) {
					
					$archive_type['args']['taxonomy'] = strip_tags( $queried_object->taxonomy );
					$archive_type['args']['term_id'] = strip_tags( $queried_object->term_id );
					
				}

			}

		}
		
		return $archive_type;
		
	}
	
	public function get_archive_settings( $model_type = false , $args = array() )
	{
		
		global $APSC;
		
		if( $model_type == false ) {
			
			return false;
			
		}
		
		$cache_key = __FUNCTION__ . '_' . $model_type . '_' . json_encode( $args );
		
		$cache = $APSC->Helper->get_object_cache( $cache_key );
		
		if( !empty( $cache ) ) {
			
			return $cache;
			
		}
		
		$Model = false;
		$fields = array( 'primary' => '' , 'secondary' => '' );
		
		if( $model_type == 'home' ) {
			
			$Model = new APSC_Model_Home();

			$fields['primary'] = 'default';
			
		} elseif( $model_type == 'date' ) {
			
			$Model = new APSC_Model_Date();
			
			if( !empty( $args['date'] ) ) {
				
				$fields['primary'] = $args['date'];
				$fields['secondary'] = 'default';

			}

		} elseif( $model_type == 'search' ) {
			
			$Model = new APSC_Model_Search();
			
			$fields['primary'] = 'default';

		} elseif( $model_type == 'taxonomies' ) {
			
			$Model = new APSC_Model_Taxonomies();
			
			if( !empty( $args['taxonomy'] ) && !empty( $args['term_id'] ) ) {
				
				$fields['primary'] = $args['taxonomy'] . '_' . $args['term_id'];
				$fields['secondary'] = 'default_' . $args['taxonomy'];

			}

		}
		
		if( ! is_object( $Model ) ) {
			
			return false;
			
		}
		
		$get_data = $Model->get_blog_datas();
		
		if( empty( $get_data ) ) {
			
			return false;
			
		}
		
		$setting_data = false;

		if( !empty( $fields['primary'] ) ) {
			
			if( !empty( $get_data[ $fields['primary'] ] ) ) {

				$primary_data = $get_data[ $fields['primary'] ];
				
				if( !empty( $primary_data['use'] ) ) {
					
					$setting_data = $primary_data;
					
				}
				
			}
			
		}
		
		if( empty( $setting_data ) ) {
			
			if( !empty( $fields['secondary'] ) ) {
				
				if( !empty( $get_data[ $fields['secondary'] ] ) ) {
				
					$secondary_data = $get_data[ $fields['secondary'] ];
					
					if( !empty( $secondary_data['use'] ) ) {
						
						$setting_data = $secondary_data;
						
					}
					
				}
				
			}

		}

		$APSC->Helper->set_object_cache( $cache_key , $setting_data );

		return $setting_data;

	}
	
	public function is_custom_field_values_numeric( $custom_field_key = false )
	{
		
		global $wpdb;
		global $APSC;

		if( empty( $custom_field_key ) ) {
			
			return false;
			
		}
		
		$custom_field_key = strip_tags( $custom_field_key );
		
		$sql = $wpdb->prepare( "SELECT DISTINCT meta_value FROM $wpdb->postmeta WHERE meta_key LIKE %s ORDER BY meta_value ASC" , $custom_field_key );
		
		$results = $wpdb->get_col( $sql );
		
		if( empty( $results ) ) {
			
			return false;
			
		}

		$numeric = true;
		
		foreach( $results as $value ) {
			
			if( !is_numeric( $value ) ) {
				
				$numeric = false;
				break;
				
			}
			
		}
		
		return $numeric;

	}
	
}

endif;
