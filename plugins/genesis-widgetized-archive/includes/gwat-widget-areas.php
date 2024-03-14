<?php
/**
 * Register additional widget areas.
 *
 * @package    Genesis Widgetized Archive
 * @subpackage Widgets
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


add_action( 'init', 'ddw_gwat_register_widget_areas' );
/**
 * Register additional widget areas.
 *
 * Note: Has to be early on the "init" hook in order to display translations!
 *
 * @since 1.2.0
 *
 * @uses  is_admin()
 * @uses  genesis_register_sidebar()
 */
function ddw_gwat_register_widget_areas() {

	/** Add shortcode support to widgets */
	if ( ! is_admin() && ! GWAT_NO_WIDGETS_SHORTCODE ) {

		add_filter( 'widget_text', 'do_shortcode' );

	}  // end-if constant check

	/** Set filter for "Archive Page Template #1" widget title */
	$gwat_archive_one_widget_title = apply_filters(
		'gwat_filter_archive_one_widget_title',
		__( 'Archive Page Template #1', 'genesis-widgetized-archive' )
	);

	/** Set filter for "Archive Page Template #1" widget description */
	$gwat_archive_one_widget_description = apply_filters(
		'gwat_filter_archive_one_widget_description',
		__( 'This is the first widget area for the Archive Page Template (bundled with the Genesis Framework).', 'genesis-widgetized-archive' )
	);

	/** Register the "Archive Page Template #1" widget area */
	genesis_register_sidebar(
		array(
			'id'            => 'gwat-archive-widget-one',
			'name'          => $gwat_archive_one_widget_title,
			'description'   => $gwat_archive_one_widget_description,
			'before_widget' => '<div id="%1$s" class="gwat-archive gwat-archive-one widget-area %2$s">',
			'after_widget'  => '</div>',
		)
	);

	/** Second Widget Area */		
	if ( ! GWAT_NO_SECOND_WIDGET_AREA ) {

		/** Set filter for "Archive Page Template #2" widget title */
		$gwat_archive_two_widget_title = apply_filters(
			'gwat_filter_archive_two_widget_title',
			__( 'Archive Page Template #2', 'genesis-widgetized-archive' )
		);

		/** Set filter for "Archive Page Template #2" widget description */
		$gwat_archive_two_widget_description = apply_filters(
			'gwat_filter_archive_two_widget_description',
			__( 'This is the second optional widget area for the Archive Page Template (bundled with the Genesis Framework).', 'genesis-widgetized-archive' )
		);

		/** Register the "Archive Page Template #2" widget area */
		genesis_register_sidebar(
			array(
				'id'            => 'gwat-archive-widget-two',
				'name'          => $gwat_archive_two_widget_title,
				'description'   => $gwat_archive_two_widget_description,
				'before_widget' => '<div id="%1$s" class="gwat-archive gwat-archive-two widget-area %2$s">',
				'after_widget'  => '</div>',
			)
		);

	}  // end-if constant check for second widget area

	/** Third Widget Area */
	if ( ! GWAT_NO_THIRD_WIDGET_AREA ) {

		/** Set filter for "Archive Page Template #3" widget title */
		$gwat_archive_three_widget_title = apply_filters(
			'gwat_filter_archive_three_widget_title',
			__( 'Archive Page Template #3', 'genesis-widgetized-archive' )
		);

		/** Set filter for "Archive Page Template #3" widget description */
		$gwat_archive_three_widget_description = apply_filters(
			'gwat_filter_archive_three_widget_description',
			__( 'This is the third optional widget area for the Archive Page Template (bundled with the Genesis Framework).', 'genesis-widgetized-archive' )
		);

		/** Register the "Archive Page Template #3" widget area */
		genesis_register_sidebar(
			array(
				'id'            => 'gwat-archive-widget-three',
				'name'          => $gwat_archive_three_widget_title,
				'description'   => $gwat_archive_three_widget_description,
				'before_widget' => '<div id="%1$s" class="gwat-archive gwat-archive-three widget-area %2$s">',
				'after_widget'  => '</div>',
			)
		);

	}  // end-if constant check for third widget area

}  // end of function ddw_gwat_register_widget_areas