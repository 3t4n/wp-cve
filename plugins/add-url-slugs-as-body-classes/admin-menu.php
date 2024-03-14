<?php

/*
	Plugin Name: Add URL Slugs as Body Classes
	Plugin URI: http://aarontgrogg.com/2012/02/24/wordpress-plugin-add-url-slugs-as-body-classes/
	Description: Add page name and/or category slugs as additional `&lt;body&gt;` classes.
	Version: 1.3
	Author: Aaron T. Grogg
	Author URI: http://aarontgrogg.com/
	License: GPLv2 or later
*/

	// this only applies for non-admin pages
	if ( !is_admin() && !function_exists( 'USBC_add_body_class' ) ) :


		//	Add deconstructed URI as <body> classes
		//	$classes = array of classes WP is already planning to add
		function USBC_add_body_class( $classes )  {

			// get the global post variable
			global $post;

			// make sure we're on a post page
			if ($post && $post->ID) :

				// loop through any categories
				foreach( get_the_category($post->ID) as $category ) {
					// and push the trimmed version to the $classes array
					$classes[] = trim( $category->category_nicename );
				}

				// get the current page's URI (the part _after_ your domain name)
				$uri = $_SERVER["REQUEST_URI"];
				// explode that string into an array of "pieces"
				$bodyclass = explode('/',$uri);
				// loop through those pieces and push each into the $classes array
				foreach($bodyclass as $category) {
					$classes[] = trim($category);
				}

			endif; // $post...

			// return a unique-onlye version of the array to WP
			return array_unique($classes);
		
		}

		add_filter('post_class', 'USBC_add_body_class');
		add_filter('body_class', 'USBC_add_body_class');

	endif; // !is_admin...

?>
