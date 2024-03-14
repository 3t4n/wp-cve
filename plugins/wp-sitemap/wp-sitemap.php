<?php
/*
Plugin Name: WP Sitemap
Plugin URI: http://www.devdevote.com/wp-sitemap/
Description: A HTML sitemap with both post and pages. Good for people and search engines.
Author: Jens T&ouml;rnell
Version: 1.0
Author URI: http://www.jenst.se
*/

$wp_sitemap = new wp_sitemap();

add_shortcode("wp_sitemap",  array($wp_sitemap, 'shortcodes'));

class wp_sitemap
{
	# COUNT PAGES FOR PAGE NUMBERS
	private function get_page_count($sql, $post_count)
	{
		global $wpdb;
		$parsed_sql = explode('LIMIT', $sql);
		$query_count = $wpdb->get_results($parsed_sql[0], OBJECT);
		return ($wpdb->num_rows/$post_count);
	}
	
	public function get_base()
	{
		$paged = get_query_var('paged');
		$output = '';
		
		$permalink = get_option('permalink_structure');
		
		# CHECK IF PERMALINKS IS ON
		if(!empty($permalink)) :
			$output = get_permalink() . '/page/%_%';
			
			# CHECK IF LAST CHARACTER IS A SLASH
			if(substr($permalink, -1) == '/') :
				$output .= '/';
			endif;
		else :
			$output = get_permalink() . '&#038;paged=%_%';
		endif;
		
		return $output;
	}
	
	private function generate_sitemap($exclude, $include, $posts, $pages, $sort_order, $sort_column, $paging_position, $post_count)
	{
		global $wpdb;
		global $post;
		global $paged;
		
		$base = $this->get_base();
		$paged = get_query_var('paged');
		
		$include_str = ($include === 0) ? "" : "AND ID IN ($include)";
		$exclude_str = ($exclude === 0) ? "" : "AND ID NOT IN ($exclude)";
		
		if($posts == "true" && $pages == "true")
			$posts_str = "post_type = 'post' OR post_type = 'page'";
		elseif($posts == "true")
			$posts_str = "post_type = 'post'";
		elseif($pages == "true")
			$posts_str = "post_type = 'page'";
		else
			$posts_str = "";
		
		$orderby_str = "ORDER BY $sort_column";
		$order_str = "$sort_order";
		
		$limit = ($paged * $post_count);
		
		if($paged == 0) $paged = 1;
		
		$query_sql = "
			SELECT *
			FROM $wpdb->posts
			WHERE (
			$posts_str
			)
			AND post_status = 'publish'
			AND post_date <= NOW()
			$include_str
			$exclude_str
			$orderby_str $order_str
			LIMIT $limit, $post_count
		";
		
		$query_result = $wpdb->get_results($query_sql, OBJECT);
		$query_count = $this->get_page_count($query_sql, $post_count);

		$paging_position_str = paginate_links(array('total' => $query_count, 'base' => $base, 'current' => $paged, 'format' => '%#%', 'type' => 'list', 'end_size' => '3', 'mid_size' => '2', 'prev_next' => false));
		
		# REMOVES THE BUG WITH PAGE ON THE FIRST PAGE
		$paging_position_str = str_replace(array("/page/'", "//page/", "/page//'", "&#038;paged='"), array("'", "/page/", "/'", "'"), $paging_position_str);
		
		$output = '';
		if ($query_result):
			$output .= '<ul>';
			foreach ($query_result as $post):
				setup_postdata($post);
				$output .= '<li><a href="' . get_permalink() . '">'.get_the_title().'</a></li>';
			endforeach;
			$output .= '</ul>';
		else :
		endif;
		
		if($paging_position == "top") :
			$output = $paging_position_str . $output;
		elseif($paging_position == "bottom") :
			$output = $output . $paging_position_str;
		elseif($paging_position == "both") :
			$output = $paging_position_str . $output . $paging_position_str;
		endif;
		
		return $output;
	}
	
	function shortcodes($atts) {
		extract(shortcode_atts(array(
			"exclude" => 0,
			"include" => 0,
			"posts" => "true",
			"pages" => "true",
			"sort_column" => "post_date",
			"sort_order" => "DESC",
			"paging_position" => "bottom",
			"post_count" => 50
		), $atts));
		
		$output = '';
		$output .= $this->generate_sitemap($exclude, $include, $posts, $pages, $sort_order, $sort_column, $paging_position, $post_count);
		
		return $output;
	}
}

?>