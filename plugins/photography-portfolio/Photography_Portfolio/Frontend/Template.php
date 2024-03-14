<?php


namespace Photography_Portfolio\Frontend;


class Template {

	/**
	 * Directly and instantly load portfolio template file
	 *
	 * If you're looking for a function to use in themes, have a look at `phort_get_template` function instead
	 *
	 * @param      $base - The template path ( for exmaple: '/path-to/the/entry' )
	 * @param null $slug - The portfolio layout modifier (for example: 'masonry' )
	 */
	public static function load( $base, $slug = NULL ) {

		$search = [];

		// Only include base-name.php if `name` is set ( avoid loading `slug-.php` )
		if ( $slug ) {
			$search[] = "{$base}-{$slug}.php";
		}

		// Always fall back on slug.php
		$search[] = "{$base}.php";

		// Search for the template file
		$template = self::locate_by_array( $search );

		// Allow 3rd party plugins to modify the tempalte path
		$template = apply_filters( "phort/get_template/{$base}", $template, $base, $slug );

		/**
		 * Load Template if template is found
		 */
		if ( $template ) {

			do_action_deprecated( "phort/load_template/{$base}", [ $template, $base, $slug ], '1.4.0', 'phort_get_template' );
			load_template( $template, false );

		}

	}


	public static function locate_by_array( $filenames ) {

		$filenames = array_unique( $filenames );
		$found     = false;

		foreach ( $filenames as $filename ) {
			$found = self::locate_by_filename( $filename );
			if ( $found ) {
				break;
			}
		}

		return $found;
	}


	public static function locate_by_filename( $filename ) {

		$debug_mode = false;
		$template   = false;

		// Get __THEME__/portfolio/filename.php
		if ( ! $debug_mode ) {
			$template = locate_template( CLM_THEME_PATH . $filename );
		}

		// Get __PLUGIN__/templates/filename.php
		if ( ! $template && file_exists( CLM_PLUGIN_THEME_PATH . $filename ) ) {
			$template = CLM_PLUGIN_THEME_PATH . $filename;
		}

		return $template;
	}


	public static function locate( $files ) {

		/**
		 * Find template by array of filenames
		 */
		if ( is_array( $files ) ) {
			$located = self::locate_by_array( $files );
		}

		/**
		 * Find template by string
		 */
		else {
			$located = self::locate_by_filename( $files );
		}

		/*
		 * Fallback, in case both locators above failed
		 */
		if ( ! $located ) {
			$located = self::locate_by_array( [ 'photography-portfolio.php', 'index.php' ] );
		}


		/**
		 * Return the located template path
		 */
		return $located;
	}

}