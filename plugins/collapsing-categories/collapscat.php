<?php
/*
Plugin Name: Collapsing Categories
Plugin URI: https://robfelty.com/plugins
Description: Adds a new categories widget which uses javascript to expand and collapse categories to show the posts that belong to the category <a href='https://wordpress.org/plugins/collapsing-categories/other_notes'>Manual</a> | <a href='https://wordpress.org/plugins/collapsing-categories/faq'>FAQ</a>
Author: Robert Felty
Version: 3.0.8
Author URI: http://robfelty.com
Tags: sidebar, widget, categories, menu, navigation, posts

Copyright 2007-2023 Robert Felty

This file is part of Collapsing Categories

		Collapsing Categories is free software; you can redistribute it and/or
		modify it under the terms of the GNU General Public License as published by
		the Free Software Foundation; either version 2 of the License, or (at your
		option) any later version.

		Collapsing Categories is distributed in the hope that it will be useful,
		but WITHOUT ANY WARRANTY; without even the implied warranty of
		MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.	See the
		GNU General Public License for more details.

		You should have received a copy of the GNU General Public License
		along with Collapsing Categories; if not, write to the Free Software
		Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA	02110-1301	USA
*/


global $collapsCatVersion;
$collapsCatVersion = '3.0';

if (!is_admin()) {
	//add_action( 'wp_head', array('collapsCat','get_head'));
} else {
	// call upgrade function if current version is lower than actual version
	$dbversion = get_option('collapsCatVersion');
	if (!$dbversion || $collapsCatVersion != $dbversion)
		Collapscat::init();
}
add_action('init', array('CollapsCat','init_textdomain'));
register_activation_hook(__FILE__, array('CollapsCat','init'));

class CollapsCat {

	static $term_counts;

	public static function init_textdomain() {
		$plugin_dir = basename(dirname(__FILE__)) . '/languages/';
		load_plugin_textdomain( 'collapsing-categories', WP_PLUGIN_DIR . $plugin_dir, $plugin_dir );
	}

	public static function init() {
		global $collapsCatVersion;
		include('collapsCatStyles.php');
		$defaultStyles=compact('selected','default','block','noArrows','custom');
		$dbversion = get_option('collapsCatVersion');
		if ($collapsCatVersion != $dbversion && $selected!='custom') {
			$style = $defaultStyles[$selected];
			update_option( 'collapsCatStyle', $style);
			update_option( 'collapsCatVersion', $collapsCatVersion);
		}
		if( function_exists('add_option') ) {
			update_option( 'collapsCatOrigStyle', $style);
			update_option( 'collapsCatDefaultStyles', $defaultStyles);
		}
		if (!get_option('collapsCatOptions')) {
			include('defaults.php');
			update_option('collapsCatOptions', $defaults);
		}
		if (!get_option('collapsCatStyle')) {
			add_option( 'collapsCatStyle', $style);
		}
		if (!get_option('collapsCatSidebarId')) {
			add_option( 'collapsCatSidebarId', 'sidebar');
		}
		if (!get_option('collapsCatVersion')) {
			add_option( 'collapsCatVersion', $collapsCatVersion);
		}

	}


	public static function phpArrayToJS($array,$name) {
		/* generates javscript code to create an array from a php array */
		$script = "try { $name" .
				"['catTest'] = 'test'; } catch (err) { $name = new Object(); }\n";
		foreach ($array as $key => $value){
			$script .= $name . "['$key'] = '" .
					addslashes( str_replace( ["\n", "\r"], '', $value ) ) . "';\n";
		}
		return($script);
	}
	public static function render_callback( $attributes ) {
		$wrapper_attributes = get_block_wrapper_attributes();

		include('collapsCatStyles.php');
		$html = '';
		$number = $attributes['blockId'];
		$instance = $attributes;
		$instance['number'] = $number;
		if ( ! $instance['widgetTitle'] ) {
			$title = 'Categories';
			$instance['widgetTitle'] = $title;
		} else {
			$title = $instance['widgetTitle'];
		}
		$html .= "<h2 class='widget-title'>$title</h2>";
		$html .= "<ul id='widget-collapscat-$number-top'>";
		$html .= collapsCat($instance, false, true );
		$html .= "</ul>";
		if ( ! empty( $attributes[ 'style' ] ) ) {
			$html .= '<style type="text/css">';
			$style = $defaultStyles[ $attributes['style'] ];
			$style = str_replace('{ID}', "ul#widget-collapscat-$number-top", $style);
			$html .= "$style</style>";
		}
		return sprintf(
			'<div %1$s>%2$s</div>',
			$wrapper_attributes,
			$html
		);
	}

	/**
	 * @return $term_parents_map - associative array mapping a child to its parent
	 * */
	private static function map_parent_terms( $terms ) {
		$term_parents_map = array();
		foreach ( $terms as $term ) {
			if ( $term->parent ) {
				$term_parents_map[ $term->term_id ] = $term->parent;
			}
		}
		return $term_parents_map;
	}

	private static function get_all_parent_terms( $term_parents_map, $term_id ) {
		$parents = array();
		if ( isset( $term_parents_map[ $term_id ] ) ) {
			$parent = $term_parents_map[ $term_id ];
			if ( isset( $term_parents_map[ $parent ] ) ) {
				$parents = array_merge( $parents, CollapsCat::get_all_parent_terms( $term_parents_map, $parent ) );
			}
			$parents[] = $parent;
		}
		return $parents;
	}

	/**
	 * Note that if we are expanding to posts, then we get a list of all the
	 * posts and can count uniquely. That is, if post 123 is included in
	 * categories B and C, which are both subcategories of A, we only want to
	 * count that once when showing the total for A. If we are not showing
	 * posts, then we don't query the post table at all, and must rely on the
	 * counts from the wp_terms table, which means we will end up counting post
	 * 123 twice for A.
	 * @return array $term_count_map Mapping from term_id to counts of unique posts
	 * */
	public static function map_term_counts( $posts, $categories ) {
		$term_count_map = array();
		$term_parents_map = CollapsCat::map_parent_terms( $categories );
		if ( $posts ) {
			$term_posts_map = array();
			foreach ( $posts as $post ) {
				if ( isset( $term_posts_map[ $post->term_id ] ) ) {
					$term_posts_map[ $post->term_id ][] = $post->ID;
				} else {
					$term_posts_map[ $post->term_id ] = [ $post->ID ];
				}
				if ( isset( $term_parents_map[ $post->term_id ] ) ) {
					$parents = CollapsCat::get_all_parent_terms( $term_parents_map, $post->term_id );
					foreach ( $parents as $parent ) {
						if ( isset( $term_posts_map[ $parent] ) ) {
							$term_posts_map[ $parent ][] = $post->ID;
						} else {
							$term_posts_map[ $parent ] = [ $post->ID ];
						}
					}
				}
			}
			foreach ( $term_posts_map as $term => $posts ) {
				$term_count_map[ $term ] = count( array_unique( $posts ) );
			}
		} else {
			foreach ( $categories as $category ) {
				$term_count = $category->count;
				$parents = CollapsCat::get_all_parent_terms( $term_parents_map, $category->term_id );
				foreach ( $parents as $parent ) {
					if ( isset( $term_count_map[ $parent] ) ) {
						$term_count_map[ $parent ] += $term_count;
					} else {
						$term_count_map[ $parent ] = $term_count;
					}
				}
				if ( isset( $term_count_map[ $category->term_id ] ) ) {
					$term_count_map[ $category->term_id ] += $term_count;
				} else {
					$term_count_map[ $category->term_id ] = $term_count;
				}
			}
		}

		return $term_count_map;
	}

	public static function get_term_counts( $term ) {
		if ( isset( self::$term_counts[ $term->term_id ] ) ) {
			return self::$term_counts[ $term->term_id ];
		} else {
			return $term->count;
		}
	}

}


include_once( 'collapscatlist.php' );
function collapsCat($args='', $print=true, $callback=false) {
	global $collapsCatItems;
	if (!is_admin()) {
		list($posts, $categories, $parents, $options) = get_collapscat_fromdb($args);
		list($collapsCatText, $postsInCat) = list_categories($posts, $categories,
				$parents, $options );
		// Defining some defaults here, which may be overridden by the options, but not necessarily. TODO: Do this in a less hacky way
		$number = 0;
		extract($options);
		include('symbols.php');
        $html = '';
        $html .= $collapsCatText;
        $html .= "<li style='display:none'><script type=\"text/javascript\">\n";
        $html .= "// <![CDATA[\n";
        $html .= '/* These variables are part of the Collapsing Categories Plugin
        *	Version: 3.0.8
        *	$Id: collapscat.php 3004277 2023-12-01 14:45:56Z robfelty $
        * Copyright 2007-2020 Robert Felty (robfelty.com)
        */' . "\n";
        $html .= "var expandSym='$expandSym';\n";
        $html .= "var collapseSym='$collapseSym';\n";
        // now we create an array indexed by the id of the ul for posts
        $html .= CollapsCat::phpArrayToJS($collapsCatItems, 'collapsItems');
        $html .= file_get_contents( dirname( __FILE__ ) . '/collapsFunctions.js' );
	    //$html .= "widgetRoot = document.querySelector( '#$number ul.widget-collapscat-top' );";
	    $html .= "collapsCatRoot = document.querySelector( '#widget-collapscat-$number-top' );";
        $html .= "addExpandCollapseCat(collapsCatRoot, '$expandSym', '$collapseSym', $accordion )";
        $html .= "// ]]>\n</script></li>\n";
		if ($print) {
            echo $html;
        } elseif ($callback) {
            return $html;
		} else {
			return(array($collapsCatText, $postsInCat, $collapsCatItems ) );
		}
	}

}
function collapsing_categories_rest( WP_REST_Request $request ) {
	$parameters = $request->get_params();
	return collapsCat( $parameters, $_COOKIE, false );
}
add_action( 'rest_api_init', function () {
	register_rest_route( 'collapsing-categories/v1', '/get/', array(
		'methods' => 'GET',
		'permission_callback' => '__return_true',
		'callback' => 'collapsing_categories_rest',
	) );
} );

function create_block_collapscat_block_init() {
		register_block_type(
				__DIR__ . '/build',
				[ 'render_callback' => [ CollapsCat::class, 'render_callback' ] ]
		);
}
add_action( 'init', 'create_block_collapscat_block_init' );

function collapsing_categories_set_script_translations() {
        wp_set_script_translations( 'collapsing-categories-script', 'collapsing-categories', plugin_dir_path( __FILE__ ) . 'languages' );
    }
    add_action( 'init', 'collapsing_categories_set_script_translations' );
