<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Wordpress_Lib_Custom_Search_HC_MVC extends _HC_MVC
{
	protected $types = array();
	
	public function enable( $types = array() )
	{
		if( ! is_array($types) ){
			$types= array( $types);
		}
		$this->types= $types;

		add_filter( 'posts_where', array($this, 'custom_search_where') );
		add_filter( 'posts_join', array($this, 'custom_search_join') );
		add_filter( 'posts_distinct', array($this, 'custom_search_distinct') );
	}

	public function custom_search_join( $join )
	{
		if( ! isset($_GET['s']) ){
			return $join;
		}
		global $pagenow, $wpdb;

		if ( is_admin() && $pagenow=='edit.php' && in_array($_GET['post_type'], $this->types) && $_GET['s'] != '' ){
			$join .='LEFT JOIN '.$wpdb->postmeta. ' ON '. $wpdb->posts . '.ID = ' . $wpdb->postmeta . '.post_id ';
		}
		return $join;
	}

	public function custom_search_where( $where )
	{
		if( ! isset($_GET['s']) ){
			return $where;
		}

		global $pagenow, $wpdb;

		if ( is_admin() && $pagenow=='edit.php' && in_array($_GET['post_type'], $this->types) && $_GET['s'] != ''){
			$where = preg_replace(
				"/\(\s*".$wpdb->posts.".post_title\s+LIKE\s*(\'[^\']+\')\s*\)/",
				"(".$wpdb->posts.".post_title LIKE $1) OR (".$wpdb->postmeta.".meta_value LIKE $1)",
				$where
				);
		}
		return $where;
	}

	function custom_search_distinct( $where )
	{
		if( ! isset($_GET['s']) ){
			return $where;
		}

		global $pagenow, $wpdb;

		if ( is_admin() && $pagenow=='edit.php' && in_array($_GET['post_type'], $this->types) && $_GET['s'] != '') {
			return "DISTINCT";
		}
		return $where;
	}
}