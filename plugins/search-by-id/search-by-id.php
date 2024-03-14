<?php
/*
	Plugin Name: Search by ID
	Plugin URI: 
	Description: Enables the user to search by post ID using the built-in search within the administration area. Works for all kinds of posts (posts, pages, custom post types and media).
	Version: 1.3
	Author: Uffe Fey, WordPress consultant
	Author URI: https://wpkonsulent.dk
*/
	new WPkonsulentSearchById();
	
	class WPkonsulentSearchById
	{
		function __construct()
		{
			add_filter('posts_where', array($this, 'posts_where'));
		}
		
		function posts_where($where)
		{
			if(is_admin() && is_search())
			{
				$s = $_GET['s'];
				
				if(!empty($s))
				{
					if(is_numeric($s))
					{
						global $wpdb;
						
						$where = str_replace('(' . $wpdb->posts . '.post_title LIKE', '(' . $wpdb->posts . '.ID = ' . $s . ') OR (' . $wpdb->posts . '.post_title LIKE', $where);
					}
					elseif(preg_match("/^(\d+)(,\s*\d+)*\$/", $s)) // string of post IDs
					{
						global $wpdb;
						
						$where = str_replace('(' . $wpdb->posts . '.post_title LIKE', '(' . $wpdb->posts . '.ID in (' . $s . ')) OR (' . $wpdb->posts . '.post_title LIKE', $where);
					}
				}
			}
			
			return $where;
		}
	}
?>