<?php

if ( !class_exists( 'APSC_Helper' ) ) :

final class APSC_Helper
{
	
	public function __construct() {}

	public function includes( $files = false )
	{
		
		global $APSC;

		if( empty( $files ) ) {

			return false;
			
		}
		
		if( is_array( $files ) ) {
			
			foreach( $files as $file ) {
			
				include_once( $APSC->plugin_dir . $file );
				
			}

		} else {
			
			include_once( $APSC->plugin_dir . $files );

		}

	}
	
	public function get_action_link( $remove_query = array() )
	{
		
		global $APSC;

		if( empty( $remove_query ) ) {
			
			$url = remove_query_arg( array( $APSC->Plugin->msg_notice ) );
			
		} else {
			
			$url = remove_query_arg( wp_parse_args( $remove_query , array( $APSC->Plugin->msg_notice ) ) );
			
		}
		
		return esc_url_raw( $url );
		
	}
	
	public function is_correctly_form( $post_data = array() )
	{
		
		global $APSC;
		
		if( empty( $post_data ) ) {

			return false;
			
		}
		
		if( empty( $post_data[ $APSC->Form->field ] ) ) {

			return false;
			
		}

		$form_field = strip_tags( $post_data[$APSC->Form->field] );

		if( $form_field !== $APSC->Form->UPFN ) {

			return false;
			
		}

		return true;
		
	}
	
	public function get_object_cache( $chache_key = false )
	{
		
		global $APSC;
		
		if( empty( $chache_key ) ) {

			return false;
			
		}

		return wp_cache_get( $chache_key , $APSC->main_slug );

	}
	
	public function set_object_cache( $chache_key = false , $data = false )
	{
		
		global $APSC;
		
		if( empty( $chache_key ) ) {

			return false;
			
		}

		wp_cache_set( $chache_key , $data , $APSC->main_slug );

	}
	
	public function delete_object_cache( $chache_key = false )
	{
		
		global $APSC;
		
		if( empty( $chache_key ) ) {

			return false;
			
		}

		return wp_cache_delete( $chache_key , $APSC->main_slug );

	}
	
	public function get_main_blog_id()
	{
		
		return 1;
		
	}
	
	public function get_blog_id( $blog_id = false )
	{
		
		global $APSC;

		if( !empty( $blog_id ) ) {

			$blog_id = absint( $blog_id );
			
		} else {
			
			$blog_id = $APSC->Site->blog_id;
			
		}
		
		return $blog_id;

	}
	
	public function check_main_blog( $blog_id = false )
	{
		
		$blog_id = $this->get_blog_id( $blog_id );
		$main_blog_id = $this->get_main_blog_id();
		
		if( $blog_id != $main_blog_id ) {

			return false;
			
		}
		
		return true;
		
	}
	
	public function set_notice( $message = false , $notice_id = false , $notice_type = 'update' )
	{
		
		global $APSC;
		
		if( ! $APSC->User->user_login ) {
			
			return false;
			
		}
		
		$notices = $this->get_notices();
		
		$notices[ $notice_type ][ $notice_id ] = $message;
		
		update_user_option( $APSC->User->user_id , $APSC->Plugin->msg_notice , $notices );

	}
	
	public function get_notices()
	{
		
		global $APSC;
		
		if( ! $APSC->User->user_login ) {
			
			return false;
			
		}
		
		$notices = get_user_option( $APSC->Plugin->msg_notice );
		
		if( empty( $notices ) ) {
			
			$notices = array();
			
		}

		return $notices;

	}
	
	public function clear_notices()
	{
		
		global $APSC;
		
		if( ! $APSC->User->user_login ) {
			
			return false;
			
		}
		
		delete_user_option( $APSC->User->user_id , $APSC->Plugin->msg_notice );

	}
	
	public function print_notices()
	{
		
		global $APSC;
		
		$notices = $this->get_notices();

		if( empty( $notices ) ) {

			return false;
			
		}
		
		if( !empty( $notices['update'] ) ) {
			
			if( $APSC->Env->is_admin ) {
				
				echo '<div class="updated">';

			} else {
				
				echo '<div class="update">';

			}

			foreach( $notices['update'] as $message ) {
				
				printf( '<p title="update">%s</p>' , $message );
				
			}
			
			echo '</div>';

		}

		if( !empty( $notices['error'] ) ) {
			
			echo '<div class="error">';

			foreach( $notices['error'] as $code => $message ) {
				
				printf( '<p title="error_%s">%s</p>' , $code , $message );
				
			}
			
			echo '</div>';
			
		}

		$this->clear_notices();
		
	}
	
	public function get_author_link( $args = array() )
	{
		
		global $APSC;

		$url = $APSC->Link->author;
		
		if( !empty( $args['translate'] ) ) {

			$url .= 'please-translation/';

		} elseif( !empty( $args['donate'] ) ) {

			$url .= 'please-donation/';

		} elseif( !empty( $args['contact'] ) ) {

			$url .= 'contact-us/';

		}
		
		$url .= $this->get_utm_link( $args );
		
		return $url;

	}
	
	public function get_utm_link( $args = array() )
	{
		
		global $APSC;

		$utm = '?utm_source=' . $args['tp'];
		$utm .= '&utm_medium=' . $args['lc'];
		$utm .= '&utm_content=' . $APSC->ltd;
		$utm .= '&utm_campaign=' . str_replace( '.' , '_' , $APSC->ver );

		return $utm;

	}

	public function get_plugin_version_checked()
	{
		
		global $APSC;

		$readme_file = $APSC->plugin_dir . 'readme.txt';
		
		if( ! file_exists( $readme_file ) ) {
			
			return false;
			
		}

		$readme = file_get_contents( $APSC->plugin_dir . 'readme.txt' );
		
		if( empty( $readme ) ) {
			
			return false;
			
		}

		$lines = explode( "\n" , $readme );
		
		$version_checked = '';

		foreach( $lines as $key => $line ) {

			if( strpos( $line , 'Requires at least: ' ) !== false ) {

				$version_checked .= str_replace( 'Requires at least: ' , '' ,  $line );
				$version_checked .= ' - ';

			} elseif( strpos( $line , 'Tested up to: ' ) !== false ) {

				$version_checked .= str_replace( 'Tested up to: ' , '' ,  $line );
				break;

			}

		}
		
		return $version_checked;

	}
	
	public function get_taxonomies()
	{
		
		global $APSC;
		
		$cache_key = __FUNCTION__;
		
		$cache = $this->get_object_cache( $cache_key );
		
		if( !empty( $cache ) ) {
			
			return $cache;
			
		}

		$excludes = array( 'nav_menu' , 'link_category' , 'post_format' );
		
		$taxonomies = get_taxonomies( array() , 'objects' );
		
		if( !empty( $taxonomies ) ) {

			foreach( $taxonomies as $key => $taxonomy ) {

				if( in_array( $taxonomy->name , $excludes ) ) {

					unset( $taxonomies[ $key ] );

				} elseif( empty( $taxonomy->rewrite ) ) {
					
					unset( $taxonomies[ $key ] );

				}

			}

		}
		
		$this->get_object_cache( $cache_key , $taxonomies );

		return $taxonomies;
		
	}
	
	public function get_terms( $taxonomy = false )
	{
		
		global $APSC;
		
		if( empty( $taxonomy ) ) {
			
			return false;
			
		}
		
		$taxonomy = strip_tags( $taxonomy );

		$cache_key = __FUNCTION__ . '_' . $taxonomy;
		
		$cache = $this->get_object_cache( $cache_key );
		
		if( !empty( $cache ) ) {
			
			return $cache;
			
		}

		$terms = get_terms( $taxonomy , array( 'hide_empty' => false ) );
		
		$this->get_object_cache( $cache_key , $terms );

		return $terms;
		
	}
	
	public function get_custom_fields()
	{
		
		global $wpdb;
		
		$cache_key = __FUNCTION__;
		
		$cache = $this->get_object_cache( $cache_key );
		
		if( !empty( $cache ) ) {
			
			return $cache;
			
		}

		$sql = "SELECT meta_key FROM $wpdb->postmeta WHERE ";
		
		$sql .= "meta_key NOT LIKE '\_%' ";
		
		// exclude advanced custom fields
		$sql .= "AND meta_key NOT LIKE 'field_%' ";
		
		$sql .= "GROUP BY meta_key ";

		$sql .= "ASC ";
		
		$results = $wpdb->get_col( $sql );
		
		if( !empty( $results ) ) {
			
			natcasesort( $results );
			
		}
		
		$this->get_object_cache( $cache_key , $results );

		return $results;
		
	}
	
	public function get_wp_query( $query = false )
	{
		
		if( empty( $query ) ) {
			
			global $wp_query;
			
			$query = $wp_query;
			
		}
		
		return $query;

	}
	
}

endif;
