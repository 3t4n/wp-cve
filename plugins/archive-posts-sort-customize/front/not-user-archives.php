<?php

if ( !class_exists( 'APSC_Front_Abstract_Not_User_Archives' ) ) :

final class APSC_Front_Abstract_Not_User_Archives
{

	public function __construct()
	{
		
		global $APSC;
		
		if( $APSC->Env->is_admin ) {
			
			return false;
			
		}

		add_action( $APSC->main_slug . '_screen' , array( $this , 'init' ) );

	}
	
	public function init()
	{
		
		global $APSC;
		
		add_action( 'pre_get_posts' , array( $this , 'pre_get_posts' ) , 9 );
		add_filter( 'posts_orderby' , array( $this , 'posts_orderby' ) , 10 , 2 );

	}
	
	public function pre_get_posts( $query )
	{
		
		global $APSC;

		if( ! $APSC->Api->is_archive( $query ) ) {
			
			return false;
			
		}
		
		$archive_type = $APSC->Api->get_archive_type( $query );
		
		if( empty( $archive_type['id'] ) ) {
			
			return false;
			
		}
		
		$data = $APSC->Api->get_archive_settings( $archive_type['id'] , $archive_type['args'] );
		
		if( empty( $data['use'] ) ) {
			
			return false;
			
		}
		
		do_action( $APSC->main_slug . '_before_sort' , $query , $data );

		if( !empty( $data['posts_per_page'] ) ) {
			
			if( $data['posts_per_page'] == 'all' ) {
				
				$query->set( 'posts_per_page' , -1 );
				
			} elseif( $data['posts_per_page'] == 'set' ) {
				
				if( !empty( $data['posts_per_page_num'] ) ) {
					
					$query->set( 'posts_per_page' , intval( $data['posts_per_page_num'] ) );
					
				}
				
			}
			
		}
		
		if( !empty( $data['orderby'] ) ) {
			
			if( $data['orderby'] != 'date' ) {
				
				if( $data['orderby'] == 'title' ) {
					
					$query->set( 'orderby' , 'title' );
					$query->set( $APSC->main_slug . '_title_order' , $data['order'] );
					
				} elseif( $data['orderby'] == 'author' ) {
					
					$query->set( 'orderby' , 'author' );
					
				} elseif( $data['orderby'] == 'comment_count' ) {
					
					$query->set( 'orderby' , 'comment_count' );
					
				} elseif( $data['orderby'] == 'comment_count' ) {
					
					$query->set( 'orderby' , 'comment_count' );
					
				} elseif( $data['orderby'] == 'id' ) {
					
					$query->set( 'orderby' , 'id' );
					
				} elseif( $data['orderby'] == 'modified' ) {
					
					$query->set( 'orderby' , 'modified' );
					
				} elseif( $data['orderby'] == 'menu_order' ) {
					
					$query->set( 'orderby' , 'menu_order' );
					
				} elseif( $data['orderby'] == 'custom_fields' ) {
					
					if( !empty( $data['orderby_set'] ) ) {
						
						if( $APSC->Api->is_custom_field_values_numeric( $data['orderby_set'] ) ) {

							$query->set( 'orderby' , 'meta_value_num' );

						} else {

							$query->set( 'orderby' , 'meta_value' );

						}

						$query->set( 'meta_key' , strip_tags( $data['orderby_set'] ) );
						
					}
					
				}
				
			}
			
		}
		
		if( !empty( $data['order'] ) ) {
			
			if( $data['order'] == 'desc' ) {
				
				$query->set( 'order' , 'DESC' );
				
			} elseif( $data['order'] == 'asc' ) {
				
				$query->set( 'order' , 'ASC' );
				
			}
			
		}
		
		do_action( $APSC->main_slug . '_after_sort' , $query , $data );

	}
	
	public function posts_orderby( $orderby_statement , $query )
	{
		
		global $wpdb;
		global $APSC;
		
		if( empty( $query->query_vars[ $APSC->main_slug . '_title_order' ] ) ) {
			
			return $orderby_statement;
			
		}

		$archive_type = $APSC->Api->get_archive_type( $query );
		
		if( empty( $archive_type['id'] ) ) {
			
			return $orderby_statement;
			
		}
		
		$data = $APSC->Api->get_archive_settings( $archive_type['id'] , $archive_type['args'] );
		
		if( empty( $data['use'] ) ) {
			
			return $orderby_statement;
			
		}

		if( empty( $data['ignore_words'] ) ) {
			
			return $orderby_statement;
			
		}

		$new_orderby = "$wpdb->posts.post_title";
		
		$trim_sqls = array();
		
		foreach( $data['ignore_words'] as $word ) {
			
			$word = strip_tags( $word );
			$trim_sqls[] = $wpdb->prepare( "TRIM( LEADING '%s' FROM `post_title` )" , $word );
			
		}
		
		$trim_sqls_count = count( $trim_sqls );
		
		$trim_sql = $trim_sqls[0];
		unset( $trim_sqls[0] );
		
		if( !empty( $trim_sqls ) ) {
			
			foreach( $trim_sqls as $trim_word ) {
				
				$explode = explode( '`post_title`' , $trim_word );
				$trim_sql = $explode[0] . $trim_sql . $explode[1];
				
			}
			
		}
		
		$new_orderby = $trim_sql;
		
		if( $query->query_vars[ $APSC->main_slug . '_title_order' ] == 'asc' ) {

			$title_order = 'ASC';

		} else {
			
			$title_order = 'DESC';

		}
		
		$new_orderby .= ' ' . $title_order;
		
		$orderby_statement = $new_orderby;

		return apply_filters( $APSC->main_slug . '_posts_orderby' , $orderby_statement , $query );

	}
	
}

new APSC_Front_Abstract_Not_User_Archives();

endif;
