<?php
/**
 * Output for the frontend display.
 *
 * @package    Genesis Widgetized Archive
 * @subpackage Frontend
 * @author     David Decker - DECKERWEB
 * @copyright  Copyright (c) 2012-2013, David Decker - DECKERWEB
 * @license    http://www.opensource.org/licenses/gpl-license.php GPL-2.0+
 * @link       http://genesisthemes.de/en/wp-plugins/genesis-widgetized-archive/
 * @link       http://deckerweb.de/twitter
 *
 * @since      1.0.0
 */

/**
 * Prevent direct access to this file.
 *
 * @since 1.2.0
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Sorry, you are not allowed to access this file directly.' );
}


add_action( 'genesis_meta', 'gwat_archive_template_content' );
/**
 * Add the new widgetized Archive Page Template content.
 *
 * @since 1.0.0
 *
 * @uses  is_active_sidebar()
 */
function gwat_archive_template_content() {

	/** Do only if original Genesis template function exists and one of the widget areas is active */
	if ( function_exists( 'genesis_page_archive_content' )
		&& ( is_active_sidebar( 'gwat-archive-widget-one' )
			|| is_active_sidebar( 'gwat-archive-widget-two' )
			|| is_active_sidebar( 'gwat-archive-widget-three' )
		)
	) {

		/** Remove original Genesis archive page content */
		remove_action( 'genesis_post_content', 'genesis_page_archive_content' );	// XHTMTL, pre G2.0
		remove_action( 'genesis_entry_content', 'genesis_page_archive_content' );	// HTML5, G2.0+

		/** Add our widgetized content */
		add_action( 'genesis_post_content', 'gwat_display_archive_template_widgets' );	// XHTMTL, pre G2.0
		add_action( 'genesis_entry_content', 'gwat_display_archive_template_widgets' );	// HTML5, G2.0+
		
	}  // end-if widget check

}  // end of function gwat_archive_template_content


/**
 * Display the 1st widget area.
 *
 * @since  1.0.0
 *
 * @uses   dynamic_sidebar()
 *
 * @return strings Widget area #1 display.
 */
function ddw_gwat_display_widget_area_one() {

	echo '<div id="gwat-archive-area-one" class="gwat-archive-area">';
		dynamic_sidebar( 'gwat-archive-widget-one' );
	echo '</div><!-- end #gwat-archive-area-one .gwat-archive-area -->';

}  // end of function ddw_gwat_display_widget_area_one


/**
 * Display the 2nd widget area.
 *
 * @since  1.0.0
 *
 * @uses   dynamic_sidebar()
 *
 * @return strings Widget area #2 display.
 */
function ddw_gwat_display_widget_area_two() {

	echo '<div id="gwat-archive-area-two" class="gwat-archive-area">';
		dynamic_sidebar( 'gwat-archive-widget-two' );
	echo '</div><!-- end #gwat-archive-area-two .gwat-archive-area -->';

}  // end of function ddw_gwat_display_widget_area_two


/**
 * Display the 3rd widget area.
 *
 * @since  1.0.0
 *
 * @uses   dynamic_sidebar()
 *
 * @return strings Widget area #3 display.
 */
function ddw_gwat_display_widget_area_three() {

	echo '<div id="gwat-archive-area-three" class="gwat-archive-area">';
		dynamic_sidebar( 'gwat-archive-widget-three' );
	echo '</div><!-- end #gwat-archive-area-three .gwat-archive-area -->';

}  // end of function ddw_gwat_display_widget_area_three


/**
 * Display the "Archive Page Template" widgets.
 *
 * @since 1.0.0
 *
 * @uses  is_active_sidebar()
 * @uses  ddw_gwat_display_widget_area_one()
 * @uses  ddw_gwat_display_widget_area_two()
 * @uses  ddw_gwat_display_widget_area_three()
 */
function gwat_display_archive_template_widgets() {

	/**
	 * Action hook: optionally add custom stuff before widgetized section.
	 *
	 * @since 1.0.0
	 */
	echo has_action( 'gwat_before_widgetized_area' ) ? '<div class="gwat-before-widgetized">' : '';
		do_action( 'gwat_before_widgetized_area' );
	echo has_action( 'gwat_before_widgetized_area' ) ? '</div><!-- end .gwat-before-widgetized -->' : '';

	/** Case one: only 1st area active = 1 column */
	if ( is_active_sidebar( 'gwat-archive-widget-one' )
		&& ( ! is_active_sidebar( 'gwat-archive-widget-two' ) && ! is_active_sidebar( 'gwat-archive-widget-three' ) )
	) {

		ddw_gwat_display_widget_area_one();

	}  // end if case 1: 1st widget check

	/** Case two: 1st + 2nd areas active = 2 columns */
	if ( ( is_active_sidebar( 'gwat-archive-widget-one' ) && is_active_sidebar( 'gwat-archive-widget-two' ) )
		&& ! is_active_sidebar( 'gwat-archive-widget-three' )
		&& ! GWAT_NO_SECOND_WIDGET_AREA
	) {

		echo '<div class="one-half first gwat-columns">';
			ddw_gwat_display_widget_area_one();
		echo '</div><!-- end .one-half .first .gwat-columns -->';

		echo '<div class="one-half gwat-columns">';
			ddw_gwat_display_widget_area_two();
		echo '</div><!-- end .one-half .gwat-columns -->';

		echo '<div class="clear"></div>';

	} elseif ( is_active_sidebar( 'gwat-archive-widget-one' )
			&& ! is_active_sidebar( 'gwat-archive-widget-three' )
			&& GWAT_NO_SECOND_WIDGET_AREA
	) {

		ddw_gwat_display_widget_area_one();

	}  // end if case 2: 1st+2nd widgets check

	/** Case three: 1st + 3rd areas active = 2 columns */
	if ( ( is_active_sidebar( 'gwat-archive-widget-one' ) && is_active_sidebar( 'gwat-archive-widget-three' ) )
		&& ! is_active_sidebar( 'gwat-archive-widget-two' )
		&& ! GWAT_NO_THIRD_WIDGET_AREA
	) {

		echo '<div class="one-half first gwat-columns">';
			ddw_gwat_display_widget_area_one();
		echo '</div><!-- end .one-half .first .gwat-columns -->';

		echo '<div class="one-half gwat-columns">';
			ddw_gwat_display_widget_area_three();
		echo '</div><!-- end .one-half .gwat-columns -->';

		echo '<div class="clear"></div>';

	} elseif ( is_active_sidebar( 'gwat-archive-widget-one' )
			&& ! is_active_sidebar( 'gwat-archive-widget-two' )
			&& GWAT_NO_THIRD_WIDGET_AREA
	) {

		ddw_gwat_display_widget_area_one();

	}  // end if case 3: 1st+3rd widgets check

	/** Case four: 1st + 2nd + 3rd areas active = 3 columns */
	if ( ( is_active_sidebar( 'gwat-archive-widget-one' ) && is_active_sidebar( 'gwat-archive-widget-three' ) && is_active_sidebar( 'gwat-archive-widget-two' ) )
		&& ! GWAT_NO_SECOND_WIDGET_AREA
		&& ! GWAT_NO_THIRD_WIDGET_AREA
	) {

		echo '<div class="one-third first gwat-columns">';
			ddw_gwat_display_widget_area_one();
		echo '</div><!-- end .one-third .first .gwat-columns -->';

		echo '<div class="one-third gwat-columns">';
			ddw_gwat_display_widget_area_two();
		echo '</div><!-- end .one-third .gwat-columns -->';

		echo '<div class="one-third gwat-columns">';
			ddw_gwat_display_widget_area_three();
		echo '</div><!-- end .one-third .gwat-columns -->';

		echo '<div class="clear"></div>';

	}  // end if case 4: 1st+2nd+3rd widgets check

	/** Case five: 2nd + 3rd areas active = 2 columns */
	if ( ( is_active_sidebar( 'gwat-archive-widget-two' ) && is_active_sidebar( 'gwat-archive-widget-three' ) )
		&& ! is_active_sidebar( 'gwat-archive-widget-one' )
		&& ! GWAT_NO_SECOND_WIDGET_AREA
		&& ! GWAT_NO_THIRD_WIDGET_AREA
	) {

		echo '<div class="one-half first gwat-columns">';
			ddw_gwat_display_widget_area_two();
		echo '</div><!-- end .one-half .first .gwat-columns -->';

		echo '<div class="one-half gwat-columns">';
			ddw_gwat_display_widget_area_three();
		echo '</div><!-- end .one-half .gwat-columns -->';

		echo '<div class="clear"></div>';

	}  // end if case 5: 2nd+3rd widgets check

	/** Case six: only 2nd area active = 1 column */
	if ( is_active_sidebar( 'gwat-archive-widget-two' )
		&& ( ! is_active_sidebar( 'gwat-archive-widget-one' ) && ! is_active_sidebar( 'gwat-archive-widget-three' ) )
		&& ! GWAT_NO_SECOND_WIDGET_AREA
	) {

		ddw_gwat_display_widget_area_two();

	}  // end if case 6: 2nd widget check

	/** Case seven: only 3rd area active = 1 column */
	if ( is_active_sidebar( 'gwat-archive-widget-three' )
		&& ( ! is_active_sidebar( 'gwat-archive-widget-one' ) && ! is_active_sidebar( 'gwat-archive-widget-two' ) )
		&& ! GWAT_NO_THIRD_WIDGET_AREA
	) {

		ddw_gwat_display_widget_area_three();

	}  // end if case 7: 3rd widget check

	/**
	 * Action hook: optionally add custom stuff after widgetized section.
	 *
	 * @since 1.0.0
	 */
	echo has_action( 'gwat_after_widgetized_area' ) ? '<div class="gwat-after-widgetized">' : '';
		do_action( 'gwat_after_widgetized_area' );
	echo has_action( 'gwat_after_widgetized_area' ) ? '</div><!-- end .gwat-after-widgetized -->' : '';

}  // end of function gwat_display_archive_template_widget


/**
 * 1st Helper function for detecting active templates for adding our stylesheet.
 * 
 * @since  1.2.0
 *
 * @uses   is_page_template()
 *
 * @param  string 	$page_templates Filename of page template.
 *
 * @return bool True if page template exists, otherwise false.
 */
function ddw_gwat_detect_active_templates( $page_templates ) {

	/** Check for more active page templates */
	if ( isset( $page_templates[ 'templates' ] ) ) {

		foreach ( $page_templates[ 'templates' ] as $template ) {

			if ( is_page_template( $template ) ) {

				return true;

			}  // end if

		} // end foreach

	}  // end if

	/** No active template found to exist */
	return false;

}  // end of function ddw_gwat_detect_plugin_active_templates


/**
 * 2nd Helper function for detecting active templates for adding our stylesheet.
 *   Filterable via 'gwat_filter_styles_template_check' filter hook.
 * 
 * @since  1.2.0
 *
 * @uses   ddw_gwat_detect_active_templates() Helper function doing the actual page template check.
 *
 * @return bool true OR false If custom templates are in use returns true, otherwise false.
 */
function ddw_gwat_do_detect_active_templates() {

	return ddw_gwat_detect_active_templates(
		/** Use this filter to adjust plugin tests */
		apply_filters(
			'gwat_filter_styles_template_check',
			/** Add to this array to add new plugin checks. */
			array(

				/** Templates to detect */
				'templates' => array(
					'page_archive.php',
					'templates/page_archive.php',
				),  // end of array

			)  // end of array

		)  // end of apply_filters

	);  // end of return function

}  // end of function ddw_gwat_do_detect_active_templates


add_action( 'wp_enqueue_scripts', 'ddw_gwat_styles' );
/**
 * Enqueue a few additional CSS rules,
 *    for enhanced compatibility with Genesis Child Themes.
 * 
 * @since 1.0.0
 *
 * @uses  ddw_gwat_do_detect_active_templates() For detecting certain archive page templates.
 * @uses  is_active_sidebar() For checking our active widget areas.
 */
function ddw_gwat_styles() {

	/** Check for the active widget areas */
	if ( ddw_gwat_do_detect_active_templates()
		 && ( is_active_sidebar( 'gwat-archive-widget-one' )
			|| is_active_sidebar( 'gwat-archive-widget-two' )
			|| is_active_sidebar( 'gwat-archive-widget-three' )
		)
	) {

		/** Check for Genesis HTML5 */
		$gwat_genesis_html = ( function_exists( 'genesis_html5' ) && genesis_html5() ) ? 'html5-' : '';

		/** Register our styles */
		wp_register_style(
			'genesis-widgetized-archive',
			plugins_url( 'css/gwat-' . $gwat_genesis_html . 'styles' . GWAT_SCRIPT_SUFFIX . '.css', dirname( __FILE__ ) ),
			false,
			( ( defined( 'WP_DEBUG' ) && WP_DEBUG ) || ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ) ? time() : filemtime( plugin_dir_path( __FILE__ ) ),
			'all'
		);

		/** Enqueue our styles */
		wp_enqueue_style( 'genesis-widgetized-archive' );

		/** Action hook: 'gwat_load_styles' - allows for enqueueing additional custom styles */
		do_action( 'gwat_load_styles' );

	}  // end if active widget check

}  // end of function ddw_gwat_styles