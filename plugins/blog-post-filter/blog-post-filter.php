<?php
/*
 * Plugin Name: Blog Post Filter
 * Plugin URI: http://www.sloth.ir/
 * Description: Blog Post Filter filters frontpage posts by their categories.
 * Version: 1.1.0
 * Author: ammar.shahraki
 * Author URI: http://www.sloth.ir/ammar-shahraki
 * Text Domain: blog-post-filter
 * Domain Path: /languages
*/
require_once 'adminPage.php';

class BlogPostFilter{
	public function __construct(){
		add_action('plugins_loaded', array(&$this, 'loadTextDomain') );
		add_action('admin_menu',		 array(&$this, 'settingPage'));
		add_action('pre_get_posts',  array(&$this, 'filterCategories'));
	}

	function getDirectPosts($categoryList){
		$directPostIds = array();
		foreach ($categoryList as $category) {
			$children = get_term_children($category, 'category');
			$chilePostIDs = array();
			if(count($children)>0)
				$chilePostIDs = get_posts(array(
					'fields'          => 'ids', // Only get post IDs
					'category'        => implode(',', $children),
					'posts_per_page'  => -1,
					'numberposts'		=> -1
				));
			$allPostIDs = get_posts(array(
				'fields'          => 'ids', // Only get post IDs
				'category'        => $category,
				'posts_per_page'  => -1,
				'numberposts'		=> -1
			));

			$directPostIds=array_merge($directPostIds, array_diff($allPostIDs, $chilePostIDs));
		}
		return $directPostIds;
	}

	function filterCategories($query) {
		if ($query->is_main_query() && is_home()) {

			$filterSticky = get_option('blogPostFilterStickyPosts');
			$allowed = get_option('blogPostFilterCategories');

			$categoryList = array();
			$hiddenCategories = array();

			foreach($allowed as $id=>$status)
				if($status == 1)
					$visibleCategories[] = $id;
				else
					$hiddenCategories[] = $id;

			if ($filterSticky == 0) {
				$query->set('category__in', $visibleCategories);
				// //$query->set('cat', implode(',', $visibleCategories));
				// //$query->set('ignore_sticky_posts', 'true');
			} else if(count($hiddenCategories)>0) {
				// $visiblePostIDs = get_posts(array(
				// 	'fields'          => 'ids', // Only get post IDs
				// 	'category'        => implode(',', $visibleCategories),
				// 	'posts_per_page'  => -1,
				// 	'numberposts'		=> -1,
				// 	'include_children' => false
				// ));
				//
				// $hiddenPostIDs = get_posts(array(
				// 	'fields'          => 'ids', // Only get post IDs
				// 	'category'        => implode(',', $hiddenCategories),
				// 	'posts_per_page'  => -1,
				// 	'numberposts'		=> -1
				// ));
				//
				// $postIDs = array_diff($hiddenPostIDs,$visiblePostIDs);

				$postIDs = $this->getDirectPosts($hiddenCategories);

				$query->set('post__not_in', $postIDs);
			}
		}
	}

	function settingPage(){
		//load_textdomain('blog-post-filter', plugin_dir_path( __FILE__ ) . '/languages/fa_IR.mo');
		new BlogPostFilterAdminPage();
	}

	function loadTextDomain(){
		//load_plugin_textdomain('blog-post-filter', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
		load_plugin_textdomain('blog-post-filter', false, basename( dirname( __FILE__ ) ) . '/languages' );
	}
}

$blogPostFilter = new BlogPostFilter();
