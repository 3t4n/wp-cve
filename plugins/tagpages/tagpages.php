<?php

/*
Plugin Name: TagPages
Plugin URI: https://www.bernhard-riedl.com/projects/
Description: Adds post-tags functionality for pages.
Author: Dr. Bernhard Riedl
Version: 1.64
Author URI: https://www.bernhard-riedl.com/
*/

/*
Copyright 2010-2017 Dr. Bernhard Riedl

This program is free software:
you can redistribute it and/or modify
it under the terms of the
GNU General Public License as published by
the Free Software Foundation,
either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope
that it will be useful,
but WITHOUT ANY WARRANTY;
without even the implied warranty of
MERCHANTABILITY or
FITNESS FOR A PARTICULAR PURPOSE.

See the GNU General Public License
for more details.

You should have received a copy of the
GNU General Public License
along with this program.

If not, see https://www.gnu.org/licenses/.
*/

/*
global instance
*/

global $tagpages;

if (empty($tagpages) || !is_object($tagpages) || !$tagpages instanceof TagPages)
	$tagpages=new TagPages();

/*
Class
*/

class TagPages {

	/*
	prefix for fields, options, etc.
	*/

	private $prefix='tagpages';

	/*
	nicename for options-page,
	meta-data, etc.
	*/

	private $nicename='TagPages';

	/*
	Constructor
	*/

	function __construct() {

		/*
		initialize object
		*/

		$this->register_hooks();
	}

	/*
	register WordPress hooks
	*/

	private function register_hooks() {

		/*
		As the initial taxonomies are
		registered twice in the
		WordPress Bootstrap, we have to
		register the function-call two times
		for the taxonomy 'post_tag'.
		*/

		add_action('plugins_loaded', array($this, 'add_page_to_tags_taxonomy'));
		add_action('init', array($this, 'add_page_to_tags_taxonomy'), 0);

		/*
		adds post_type 'page' to query vars
		of front-end tag-queries
		*/

		add_filter('pre_get_posts', array($this, 'add_page_to_tags_query'));

		/*
		add tags column and content
		in Pages section of Admin Menu
		in WordPress < 3.5;

		in higher versions this column
		is added by default

		https://core.trac.wordpress.org/ticket/21240
		*/

		global $wp_version;

		if (version_compare($wp_version, '3.5', '<')) {
			add_filter('manage_pages_columns', array($this, 'manage_pages_columns'));
			add_filter('manage_pages_custom_column', array($this, 'manage_pages_custom_column'), 10, 2);
		}

		/*
		adapt name of Posts column and
		add title to column header in
		Post Tags section of Admin Menu
		*/

		add_filter('manage_edit-post_tag_columns', array($this, 'manage_edit_post_tag_columns'));

		/*
		Admin Menu i18n
		*/

		add_action('admin_init', array($this, 'admin_menu_i18n'));

		/*
		meta-data
		*/

		add_action('wp_head', array($this, 'head_meta'));
		add_action('admin_head', array($this, 'head_meta'));
	}

	/*
	GETTERS AND SETTERS
	*/

	/*
	getter for prefix
	true with trailing _
	false without trailing _
	*/

	function get_prefix($trailing_=true) {
		if ($trailing_)
			return $this->prefix.'_';
		else
			return $this->prefix;
	}

	/*
	getter for nicename
	*/

	function get_nicename() {
		return $this->nicename;
	}

	/*
	CALLED BY HOOKS
	(and therefore public)
	*/

	/*
	include the page as post_type for post-tags
	*/

	function add_page_to_tags_taxonomy() {
		register_taxonomy_for_object_type('post_tag', 'page');
	}

	/*
	add post_type 'page'
	to query vars of
	front-end tag-queries
	*/

	function add_page_to_tags_query($query) {
		if (is_tag() && !is_admin()) {
			$post_type=$query->get('post_type');

			/*
			if post_type is set to 'any'
			or includes 'page'
			there's nothing more to do
			*/

			if (!empty($post_type) && (($post_type=='any') || (in_array('page', (array) $post_type))))
				return $query;

			/*
			otherwise include post and page
			into post_type
			*/

			$query->set('post_type', array_unique(array_merge((array) $post_type, array('post', 'page'))));
		}

		return $query;
	}

	/*
	add the tags column
	to the Pages section
	of the Admin Menu
	for WordPress < 3.5
	*/

	function manage_pages_columns($columns) {
		if (!isset($columns['tags']))
			$columns['tags']=esc_html(__('Tags'));

		return $columns;
	}

	/*
	echo tags to display in
	tags column in
	Pages section
	of the Admin Menu
	for WordPress < 3.5

	based on function single_row
	in wp-admin/includes/default-list-tables.php
	*/

	function manage_pages_custom_column($column_name, $page_id) {
		if ($column_name=='tags') {
			$tags = get_the_tags($page_id);

			if (!empty($tags)) {
				$out = array();

				foreach($tags as $c)
					$out[] = sprintf('<a href="%s">%s</a>', esc_url(add_query_arg(array('post_type' => 'page', 'tag' => $c->slug), admin_url('edit.php'))), esc_html(sanitize_term_field('name', $c->name, $c->term_id, 'tag', 'display')));

				echo join(', ', $out);
			}

			else {
				_e('No Tags');
			}
		}
	}

	/*
	adapt the name of the Posts column in
	the Post Tags section of the Admin Menu
	and give some tooltip-hint
	*/

	function manage_edit_post_tag_columns($columns) {
		$title_text=__('total number of tags in posts and pages, but only posts will be shown', $this->get_prefix(false));

		if (isset($_REQUEST['post_type']) && !empty($_REQUEST['post_type']) && $_REQUEST['post_type']=='page')
			$title_text=__('total number of tags in posts and pages, but only pages will be shown', $this->get_prefix(false));

		$columns['posts']='<span title="'.esc_html($title_text).'">'.esc_html(__('Posts').' & '.__('Pages')).'</span>';

		return $columns;
	}

	/*
	loads translation
	*/

	function admin_menu_i18n() {

		/*
		load i18n textdomain
		*/

		$plugin_dir = basename(dirname(__FILE__));
		load_plugin_textdomain($this->get_prefix(false), false, $plugin_dir.'/lang');
	}

	/*
	adds meta-information to HTML header
	*/

	function head_meta() {
		echo("<meta name=\"".$this->get_nicename()."\" content=\"1.64\"/>\n");
	}

}
