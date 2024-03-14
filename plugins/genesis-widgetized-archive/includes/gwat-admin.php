<?php
/**
 * Helper functions for the admin - plugin links and help tabs.
 *
 * @package    Genesis Widgetized Archive
 * @subpackage Admin
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


/**
 * Setting internal plugin helper links constants.
 *
 * @since 1.0.0
 *
 * @uses  get_locale()
 */
define( 'GWAT_URL_TRANSLATE',		'http://translate.wpautobahn.com/projects/genesis-plugins-deckerweb/genesis-widgetized-archive' );
define( 'GWAT_URL_WPORG_FAQ',		'http://wordpress.org/plugins/genesis-widgetized-archive/faq/' );
define( 'GWAT_URL_WPORG_FORUM',		'http://wordpress.org/support/plugin/genesis-widgetized-archive' );
define( 'GWAT_URL_WPORG_PROFILE',	'http://profiles.wordpress.org/daveshine/' );
define( 'GWAT_URL_SNIPPETS',		'https://gist.github.com/4106349' );
define( 'GWAT_PLUGIN_LICENSE', 		'GPLv2+' );
if ( get_locale() == 'de_DE' || get_locale() == 'de_AT' || get_locale() == 'de_CH' || get_locale() == 'de_LU' ) {
	define( 'GWAT_URL_DONATE', 		'http://genesisthemes.de/spenden/' );
	define( 'GWAT_URL_PLUGIN',		'http://genesisthemes.de/plugins/genesis-widgetized-archive/' );
} else {
	define( 'GWAT_URL_DONATE', 		'http://genesisthemes.de/en/donate/' );
	define( 'GWAT_URL_PLUGIN',		'http://genesisthemes.de/en/wp-plugins/genesis-widgetized-archive/' );
}


/**
 * Add "Widgets Page" link to plugin page.
 *
 * @since  1.0.0
 *
 * @param  $gwat_links
 * @param  $gwat_widgets_link
 * @param  $gwat_pages_link
 *
 * @return strings Widgets & Pages admin links.
 */
function ddw_gwat_widgets_page_link( $gwat_links ) {

	/** Widgets Admin link */
	$gwat_widgets_link = sprintf(
		'<a href="%s" title="%s">%s</a>',
		admin_url( 'widgets.php' ),
		__( 'Go to the Widgets settings page', 'genesis-widgetized-archive' ),
		__( 'Widgets', 'genesis-widgetized-archive' )
	);

	/** Edit Pages link */
	$gwat_pages_link = sprintf(
		'<a href="%s" title="%s">%s</a>',
		admin_url( 'edit.php?post_type=page' ),
		__( 'Go to the Manage Pages overview page', 'genesis-widgetized-archive' ),
		__( 'Pages', 'genesis-widgetized-archive' )
	);

	/** Set the order of the links */
	array_unshift( $gwat_links, $gwat_widgets_link, $gwat_pages_link );

	/** Display plugin settings links */
	return apply_filters( 'gwat_filter_settings_page_link', $gwat_links );

}  // end of function ddw_gwat_widgets_page_link


add_filter( 'plugin_row_meta', 'ddw_gwat_plugin_links', 10, 2 );
/**
 * Add various support links to plugin page.
 *
 * @since  1.0.0
 *
 * @uses   current_user_can()
 *
 * @param  $gwat_links
 * @param  $gwat_file
 *
 * @return strings plugin links
 */
function ddw_gwat_plugin_links( $gwat_links, $gwat_file ) {

	/** Capability check */
	if ( ! current_user_can( 'install_plugins' ) ) {

		return $gwat_links;

	}  // end-if cap check

	/** List additional links only for this plugin */
	if ( $gwat_file == GWAT_PLUGIN_BASEDIR . 'genesis-widgetized-archive.php' ) {

		$gwat_links[] = '<a href="' . esc_url( GWAT_URL_WPORG_FAQ ) . '" target="_new" title="' . __( 'FAQ', 'genesis-widgetized-archive' ) . '">' . __( 'FAQ', 'genesis-widgetized-archive' ) . '</a>';

		$gwat_links[] = '<a href="' . esc_url( GWAT_URL_WPORG_FORUM ) . '" target="_new" title="' . __( 'Support', 'genesis-widgetized-archive' ) . '">' . __( 'Support', 'genesis-widgetized-archive' ) . '</a>';

		$gwat_links[] = '<a href="' . esc_url( GWAT_URL_SNIPPETS ) . '" target="_new" title="' . __( 'Code Snippets for Customization', 'genesis-widgetized-archive' ) . '">' . __( 'Code Snippets', 'genesis-widgetized-archive' ) . '</a>';

		$gwat_links[] = '<a href="' . esc_url( GWAT_URL_TRANSLATE ) . '" target="_new" title="' . __( 'Translations', 'genesis-widgetized-archive' ) . '">' . __( 'Translations', 'genesis-widgetized-archive' ) . '</a>';
		
		$gwat_links[] = '<a href="' . esc_url( GWAT_URL_DONATE ) . '" target="_new" title="' . __( 'Donate', 'genesis-widgetized-archive' ) . '"><strong>' . __( 'Donate', 'genesis-widgetized-archive' ) . '</strong></a>';

	}  // end if plugin links

	/** Output the links */
	return apply_filters( 'gwat_filter_plugin_links', $gwat_links );

}  // end of function ddw_gwat_plugin_links


add_action( 'sidebar_admin_setup', 'ddw_gwat_widgets_help' );
/**
 * Load plugin help tab after core help tabs on Widget admin page.
 *
 * @since  1.0.0
 *
 * @global mixed $pagenow
 */
function ddw_gwat_widgets_help() {

	global $pagenow;

	add_action( 'admin_head-' . $pagenow, 'ddw_gwat_widgets_help_tab' );

}  // end of function ddw_gwat_widgets_help


add_action( 'load-toplevel_page_genesis', 'ddw_gwat_widgets_help_tab', 16 );			// Genesis Core
add_action( 'load-genesis_page_seo-settings', 'ddw_gwat_widgets_help_tab', 16 );		// Genesis SEO
add_action( 'load-genesis_page_genesis-import-export', 'ddw_gwat_widgets_help_tab', 16 );	// Genesis Imp./Exp.
add_action( 'load-genesis_page_design-settings', 'ddw_gwat_widgets_help_tab', 16 );		// Prose Child Theme
add_action( 'load-genesis_page_prose-custom', 'ddw_gwat_widgets_help_tab', 16 );		// Prose Custom Section
add_action( 'load-genesis_page_dynamik-settings', 'ddw_gwat_widgets_help_tab', 16 );	// Dynamik Child Theme
add_action( 'load-genesis_page_dynamik-design', 'ddw_gwat_widgets_help_tab', 16 );		// Dynamik Child Design
add_action( 'load-genesis_page_dynamik-custom', 'ddw_gwat_widgets_help_tab', 16 );		// Dynamik Custom Section
/**
 * Create and display plugin help tab.
 *
 * @since  1.0.0
 *
 * @uses   get_current_screen()
 * @uses   WP_Screen::add_help_tab()
 * @uses   WP_Screen::set_help_sidebar()
 * @uses   ddw_gwat_help_sidebar_content()
 *
 * @global mixed $gwat_widgets_screen, $pagenow
 */
function ddw_gwat_widgets_help_tab() {

	global $gwat_widgets_screen, $pagenow;

	$gwat_widgets_screen = get_current_screen();

	/** Display help tabs only for WordPress 3.3 or higher */
	if ( ! class_exists( 'WP_Screen' )
		|| ! $gwat_widgets_screen
		|| basename( get_template_directory() ) != 'genesis'
	) {
		return;
	}

	/** Add the new help tab */
	$gwat_widgets_screen->add_help_tab( array(
		'id'       => 'gwat-widgets-help',
		'title'    => __( 'Genesis Widgetized Archive', 'genesis-widgetized-archive' ),
		'callback' => 'ddw_gwat_widgets_help_content',
	) );

	/** Add help sidebar */
	if ( $pagenow != 'widgets.php' ) {

		$gwat_widgets_screen->set_help_sidebar( ddw_gwat_help_sidebar_content() );

	}  // end-if $pagehook check

}  // end of function ddw_gwat_widgets_help_tab


/**
 * Create and display plugin help tab content.
 *
 * @since 1.0.0
 *
 * @uses  ddw_gwat_plugin_get_data() To display various data of this plugin.
 */
function ddw_gwat_widgets_help_content() {

	/** Helper style */
	$gwat_ol_style = 'style="margin-left: 3px;"';
	$gwat_ol_list_style = 'style="list-style-type: decimal;"';

	/** Helper string */
	$gwat_constant_help = ' &ndash; ' . sprintf( __( 'optional, could be deactivated %svia constant%s', 'genesis-widgetized-archive' ), '<a href="' . esc_url( GWAT_URL_SNIPPETS ) . '" target="_blank" title="' . __( 'Code Snippets for customization', 'genesis-widgetized-archive' ) . '">', '</a>' );

	echo '<h3>' . __( 'Plugin', 'genesis-widgetized-archive' ) . ': ' . __( 'Genesis Widgetized Archive', 'genesis-widgetized-archive' ) . ' <small>v' . esc_attr( ddw_gwat_plugin_get_data( 'Version' ) ) . '</small></h3>';

	echo '<ol ' . $gwat_ol_style . '>' .
		'<li ' . $gwat_ol_list_style . '>' . sprintf( __( 'On the Edit Page screen you have to to choose the %sArchive%s from the "Template" drop-down menu in the "Page Attributes" meta box.', 'genesis-widgetized-archive' ), '<code>', '</code>' ) . '</li>' .
		'<li ' . $gwat_ol_list_style . '>' . __( 'Then you can use one, two or all three of this plugin\'s widget areas. If more than one widget area is active (has widgets in it) the layout of the page content then will display in two colums (2 active areas) or three columns (3 active areas). The columns have responsiveness enabled.', 'genesis-widgetized-archive' ) . '</li>' .
		'</ol>';

	echo '<p>' . __( 'Added Widget areas by the plugin - only displayed if having active widgets placed in:', 'genesis-widgetized-archive' ) . '</p>' .
		'<ul>' . 
			'<li>' . __( 'Archive Page Template #1', 'genesis-widgetized-archive' ) . '</li>' .
			'<li>' . __( 'Archive Page Template #2', 'genesis-widgetized-archive' ) . $gwat_constant_help . '</li>' .
			'<li>' . __( 'Archive Page Template #3', 'genesis-widgetized-archive' ) . $gwat_constant_help . '</li>' .
		'</ul>' .
		'<p>' . __( 'Shortcodes are supported in all three widget areas.', 'genesis-widgetized-archive' ) . '</p>';

	echo '<p>' . sprintf( __( 'For adding custom content before or after the widgetized section, 2 actions hooks are included in this plugin. See %1$sFAQ%2$s and %3$sCode Snippets%2$s for more info.', 'genesis-widgetized-archive' ), '<a href="' . esc_url( GWAT_URL_WPORG_FAQ ) . '" target="_blank" title="' . __( 'FAQ', 'genesis-widgetized-archive' ) . '">', '</a>', '<a href="' . esc_url( GWAT_URL_SNIPPETS ) . '" target="_blank" title="' . __( 'Code Snippets for customization', 'genesis-widgetized-archive' ) . '">' ) . '</p>';

	echo '<p><strong>' . __( 'Important plugin links:', 'genesis-widgetized-archive' ) . '</strong>' . 
		'<br /><a href="' . esc_url( GWAT_URL_PLUGIN ) . '" target="_new" title="' . __( 'Plugin website', 'genesis-widgetized-archive' ) . '">' . __( 'Plugin website', 'genesis-widgetized-archive' ) . '</a> | <a href="' . esc_url( GWAT_URL_WPORG_FAQ ) . '" target="_new" title="' . __( 'FAQ', 'genesis-widgetized-archive' ) . '">' . __( 'FAQ', 'genesis-widgetized-archive' ) . '</a> | <a href="' . esc_url( GWAT_URL_WPORG_FORUM ) . '" target="_new" title="' . __( 'Support', 'genesis-widgetized-archive' ) . '">' . __( 'Support', 'genesis-widgetized-archive' ) . '</a> | <a href="' . esc_url( GWAT_URL_SNIPPETS ) . '" target="_new" title="' . __( 'Code Snippets for Customization', 'genesis-widgetized-archive' ) . '">' . __( 'Code Snippets', 'genesis-widgetized-archive' ) . '</a> | <a href="' . esc_url( GWAT_URL_TRANSLATE ) . '" target="_new" title="' . __( 'Translations', 'genesis-widgetized-archive' ) . '">' . __( 'Translations', 'genesis-widgetized-archive' ) . '</a> | <a href="' . esc_url( GWAT_URL_DONATE ) . '" target="_new" title="' . __( 'Donate', 'genesis-widgetized-archive' ) . '"><strong>' . __( 'Donate', 'genesis-widgetized-archive' ) . '</strong></a></p>';

	echo '<p><a href="http://www.opensource.org/licenses/gpl-license.php" target="_new" title="' . esc_attr( GWAT_PLUGIN_LICENSE ). '">' . esc_attr( GWAT_PLUGIN_LICENSE ). '</a> &copy; 2012-' . date( 'Y' ) . ' <a href="' . esc_url( ddw_gwat_plugin_get_data( 'AuthorURI' ) ) . '" target="_new" title="' . esc_attr__( ddw_gwat_plugin_get_data( 'Author' ) ) . '">' . esc_attr__( ddw_gwat_plugin_get_data( 'Author' ) ) . '</a></p>';

}  // end of function ddw_gwat_widgets_help_tab


/**
 * Helper function for returning the Help Sidebar content.
 *
 * @since  1.2.0
 *
 * @uses   ddw_gwat_plugin_get_data()
 *
 * @return string HTML content for help sidebar.
 */
function ddw_gwat_help_sidebar_content() {

	$gwat_help_sidebar_content = '<p><strong>' . __( 'More about the plugin author', 'genesis-widgetized-archive' ) . '</strong></p>' .
			'<p>' . __( 'Social:', 'genesis-widgetized-archive' ) . '<br /><a href="http://twitter.com/deckerweb" target="_blank" title="@ Twitter">Twitter</a> | <a href="http://www.facebook.com/deckerweb.service" target="_blank" title="@ Facebook">Facebook</a> | <a href="http://deckerweb.de/gplus" target="_blank" title="@ Google+">Google+</a> | <a href="' . esc_url( ddw_gwat_plugin_get_data( 'AuthorURI' ) ) . '" target="_blank" title="@ deckerweb.de">deckerweb</a></p>' .
			'<p><a href="' . esc_url( GWAT_URL_WPORG_PROFILE ) . '" target="_blank" title="@ WordPress.org">@ WordPress.org</a></p>';

	return apply_filters( 'gwat_filter_help_sidebar_content', $gwat_help_sidebar_content );

}  // end of function ddw_gwat_help_sidebar_content