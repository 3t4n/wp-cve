<?php
/**
 * Helper functions for the admin - plugin links.
 *
 * @package    Genesis Printstyle Plus
 * @subpackage Admin
 * @author     David Decker - DECKERWEB
 * @copyright  Copyright (c) 2011-2014, David Decker - DECKERWEB
 * @license    http://www.opensource.org/licenses/gpl-license.php GPL-2.0+
 * @link       http://genesisthemes.de/en/wp-plugins/genesis-printstyle-plus/
 * @link       http://deckerweb.de/twitter
 *
 * @since      1.2.0
 */

/**
 * Prevent direct access to this file.
 *
 * @since 1.8.0
 */
if ( ! defined( 'WPINC' ) ) {
	exit( 'Sorry, you are not allowed to access this file directly.' );
}


/**
 * Setting internal plugin helper values.
 *
 * @since 1.9.0
 *
 * @uses  get_locale()
 */
function ddw_gpsp_info_values() {

	$gpsp_info = array(

		'url_translate'     => 'http://translate.wpautobahn.com/projects/genesis-plugins-deckerweb/genesis-printstyle-plus',
		'url_wporg_faq'     => 'http://wordpress.org/plugins/genesis-printstyle-plus/faq/',
		'url_wporg_forum'   => 'http://wordpress.org/support/plugin/genesis-printstyle-plus',
		'url_wporg_profile' => 'http://profiles.wordpress.org/daveshine/',
		'url_snippets'      => 'https://gist.github.com/deckerweb/9230551',
		'license'           => 'GPL-2.0+',
		'url_license'       => 'http://www.opensource.org/licenses/gpl-license.php',
		'first_release'     => absint( '2011' ),
		'url_donate'        => ( in_array( get_locale(), array( 'de_DE', 'de_AT', 'de_CH', 'de_LU', 'gsw' ) ) ) ? 'http://genesisthemes.de/spenden/' : 'http://genesisthemes.de/en/donate/',
		'url_plugin'        => ( in_array( get_locale(), array( 'de_DE', 'de_AT', 'de_CH', 'de_LU', 'gsw' ) ) ) ? 'http://genesisthemes.de/plugins/genesis-printstyle-plus/' : 'http://genesisthemes.de/en/wp-plugins/genesis-printstyle-plus/'

	);  // end of array

	return $gpsp_info;

}  // end of function ddw_gpsp_info_values


add_filter( 'plugin_row_meta', 'ddw_gpsp_plugin_links', 10, 2 );
/**
 * Add various support links to plugin page.
 *
 * @since  1.2.0
 *
 * @uses   ddw_gpsp_info_values()
 *
 * @param  string 	$gpsp_links
 * @param  string 	$gpsp_file
 *
 * @return strings plugin links
 */
function ddw_gpsp_plugin_links( $gpsp_links, $gpsp_file ) {

	/** Capability check */
	if ( ! current_user_can( 'install_plugins' ) ) {

		return $gpsp_links;

	}  // end-if cap check

	/** List additional links only for this plugin */
	if ( $gpsp_file == GPSP_PLUGIN_BASEDIR . 'genesis-printstyle-plus.php' ) {

		$gpsp_info = (array) ddw_gpsp_info_values();

		$gpsp_links[] = '<a href="' . esc_url( $gpsp_info[ 'url_wporg_faq' ] ) . '" target="_new" title="' . __( 'FAQ', 'genesis-printstyle-plus' ) . '">' . __( 'FAQ', 'genesis-printstyle-plus' ) . '</a>';

		$gpsp_links[] = '<a href="' . esc_url( $gpsp_info[ 'url_wporg_forum' ] ) . '" target="_new" title="' . __( 'Support', 'genesis-printstyle-plus' ) . '">' . __( 'Support', 'genesis-printstyle-plus' ) . '</a>';

		$gpsp_links[] = '<a href="' . esc_url( $gpsp_info[ 'url_snippets' ] ) . '" target="_new" title="' . __( 'Code Snippets for Customization', 'genesis-printstyle-plus' ) . '">' . __( 'Code Snippets', 'genesis-printstyle-plus' ) . '</a>';

		$gpsp_links[] = '<a href="' . esc_url( $gpsp_info[ 'url_translate' ] ) . '" target="_new" title="' . __( 'Translations', 'genesis-printstyle-plus' ) . '">' . __( 'Translations', 'genesis-printstyle-plus' ) . '</a>';

		$gpsp_links[] = '<a href="' . esc_url( $gpsp_info[ 'url_donate' ] ) . '" target="_new" title="' . __( 'Donate', 'genesis-printstyle-plus' ) . '"><strong>' . __( 'Donate', 'genesis-printstyle-plus' ) . '</strong></a>';

	}  // end-if plugin links

	/** Output the links */
	return apply_filters( 'gpsp_filter_plugin_links', $gpsp_links );

}  // end of function ddw_gpsp_plugin_links


add_action( 'load-toplevel_page_genesis', 'ddw_gpsp_genesis_help_tab', 16 );				// Genesis Core
add_action( 'load-genesis_page_seo-settings', 'ddw_gpsp_genesis_help_tab', 16 );			// Genesis SEO
add_action( 'load-genesis_page_genesis-import-export', 'ddw_gpsp_genesis_help_tab', 16 );	// Genesis Imp./Exp.
add_action( 'load-genesis_page_design-settings', 'ddw_gpsp_genesis_help_tab', 16 );			// Prose Child Theme
add_action( 'load-genesis_page_prose-custom', 'ddw_gpsp_genesis_help_tab', 16 );			// Prose Custom Sect.
add_action( 'load-genesis_page_dynamik-settings', 'ddw_gpsp_genesis_help_tab', 16 );		// Dynamik Child Theme
add_action( 'load-genesis_page_dynamik-design', 'ddw_gpsp_genesis_help_tab', 16 );			// Dynamik Child Des.
add_action( 'load-genesis_page_dynamik-custom', 'ddw_gpsp_genesis_help_tab', 16 );			// Dynamik Cust. Sect.
/**
 * Create and display plugin help tab.
 *
 * @since  1.6.0
 *
 * @uses   get_current_screen()
 * @uses   WP_Screen::add_help_tab()
 * @uses   WP_Screen::set_help_sidebar()
 * @uses   ddw_gpsp_help_sidebar_content()
 * @uses   ddw_gpsp_help_sidebar_content_extra()
 *
 * @global mixed $gpsp_genesis_screen
 */
function ddw_gpsp_genesis_help_tab() {

	global $gpsp_genesis_screen;

	$gpsp_genesis_screen = get_current_screen();

	/** Display help tabs only for WordPress 3.3 or higher */
	if ( ! class_exists( 'WP_Screen' )
		|| ! $gpsp_genesis_screen
		|| basename( get_template_directory() ) != 'genesis'
	) {

		return;

	}  // end if

	/** Add the help tab */
	$gpsp_genesis_screen->add_help_tab( array(
		'id'       => 'gpsp-genesis-help',
		'title'    => __( 'Genesis Printstyle Plus', 'genesis-printstyle-plus' ),
		'callback' => apply_filters( 'gpsp_filter_help_tab_content', 'ddw_gpsp_genesis_help_tab_content' ),
	) );

	/** Add help sidebar */
	$gpsp_genesis_screen->set_help_sidebar( ddw_gpsp_help_sidebar_content_extra() . ddw_gpsp_help_sidebar_content() );

}  // end of function ddw_gpsp_genesis_help_tab


/**
 * Create and display plugin help tab content.
 *
 * @since 1.6.0
 *
 * @uses  ddw_gpsp_info_values()
 * @uses  ddw_gpsp_plugin_get_data()
 * @uses  ddw_gpsp_plugin_help_content_footer()
 */
function ddw_gpsp_genesis_help_tab_content() {

	$gpsp_info = (array) ddw_gpsp_info_values();

	echo '<h3>' . __( 'Plugin', 'genesis-printstyle-plus' ) . ': ' . __( 'Genesis Printstyle Plus', 'genesis-printstyle-plus' ) . ' <small>v' . esc_attr( ddw_gpsp_plugin_get_data( 'Version' ) ) . '</small></h3>' .		
		'<ul>' . 
			'<li><strong>' . __( 'Add your own CSS styling modifications', 'genesis-printstyle-plus' ) . ':</strong>' .
			'<br />' . sprintf( __( 'Just add a CSS file named %s to your currently active child theme\'s root folder. That\'s all :)', 'genesis-printstyle-plus' ), '<code>print-additions.css</code>' ) . ' (' . sprintf( __( 'Note: Will be enqueued after the plugin\'s styles so you may have to add %s to some rules.', 'genesis-printstyle-plus' ), '<code>!important</code>' ) . ')</li>' .
			'<li><strong>' . __( 'Or, to use completely own print styles', 'genesis-printstyle-plus' ) . ':</strong>' .
			'<br />' . sprintf( __( 'Just add a file named %s to your currently active child theme\'s root folder. Then the plugin only picks up your custom styles and nothing else.', 'genesis-printstyle-plus' ), '<code>gpsp-print.css</code>' ) . '</a></li>';

		/** Optional: recommended plugins */
		if ( ! defined( 'GLE_PLUGIN_BASEDIR' )
			|| ! defined( 'GFPE_PLUGIN_BASEDIR' )
			|| ! defined( 'GTBE_PLUGIN_BASEDIR' )
			|| ! defined( 'GWNF_PLUGIN_BASEDIR' )
			|| ! defined( 'GDBN_PLUGIN_BASEDIR' )
		) {

			echo '<li><em>' . __( 'Other, recommended Genesis plugins', 'genesis-printstyle-plus' ) . '</em>:';

			if ( ! defined( 'GLE_PLUGIN_BASEDIR' ) ) {

				echo '<br />&raquo; <a href="http://wordpress.org/plugins/genesis-layout-extras/" target="_new" title="Genesis Layout Extras">Genesis Layout Extras</a> &mdash; ' . __( 'allows modifying of default layouts for homepage, various archive, attachment, search, 404 pages via theme options', 'genesis-printstyle-plus' );

			}  // end-if plugin check

			if ( ! defined( 'GFPE_PLUGIN_BASEDIR' ) ) {

				echo '<br />&raquo; <a href="http://wordpress.org/plugins/genesis-featured-page-extras/" target="_new" title="Genesis Featured Page Extras Extras">Genesis Featured Page Extras</a> &mdash; ' . __( 'extra advanced version of Genesis Featured Page Widget: custom content, images, URLs etc.', 'genesis-printstyle-plus' );

			}  // end-if plugin check

			if ( ! defined( 'GTBE_PLUGIN_BASEDIR' ) ) {

				echo '<br />&raquo; <a href="http://wordpress.org/plugins/genesis-toolbar-extras/" target="_new" title="Genesis Toolbar Extras">Genesis Toolbar Extras</a> &mdash; ' . __( 'adds useful admin settings links and massive resources for Genesis Framework and its ecosystem to the WordPress Toolbar / Admin Bar', 'genesis-printstyle-plus' );

			}  // end-if plugin check

			if ( ! defined( 'GWNF_PLUGIN_BASEDIR' ) ) {

				echo '<br />&raquo; <a href="http://wordpress.org/plugins/genesis-widgetized-notfound/" target="_new" title="Genesis Widgetized Not Found & 404">Genesis Widgetized Not Found & 404</a> &mdash; ' . __( 'be prepared for the edge cases - with easy to handle widget areas', 'genesis-printstyle-plus' );

			}  // end-if plugin check

			if ( ! defined( 'GDBN_PLUGIN_BASEDIR' ) ) {

				echo '<br />&raquo; <a href="http://wordpress.org/plugins/genesis-dashboard-news/" target="_new" title="Genesis Dashboard News">Genesis Dashboard News</a> &mdash; ' . __( 'News Planet for Genesis and its ecosystem right there in your WordPress dashboard', 'genesis-printstyle-plus' ) . ' &mdash; ' . __( 'for admins: fully customizeable with own parameters', 'genesis-printstyle-plus' );

			}  // end-if plugin check

			echo '</li>';

		}  // end-if plugins check

	echo '<li><em>' . __( 'Miscellaneous', 'genesis-printstyle-plus' ) . ':</em>' .
		'<br /><a href="http://genesisfinder.com/" target="_new" title="GenesisFinder.com"><strong>GenesisFinder.com</strong> &mdash; ' . __( 'Find then Create. Your Genesis Framework Search Engine.', 'genesis-printstyle-plus' ) . '</a>' .
		'</li>' .
		'</ul>';

	echo ddw_gpsp_plugin_help_content_footer();

}  // end of function ddw_gpsp_genesis_help_tab_content


/**
 * Create and display plugin help tab content for "footer info" part.
 *
 * @since  1.7.0
 *
 * @uses   ddw_gpsp_info_values()
 * @uses   ddw_gpsp_plugin_get_data()
 *
 * @return string HTML help content footer info.
 */
function ddw_gpsp_plugin_help_content_footer() {

	$gpsp_info = (array) ddw_gpsp_info_values();

	/** Set first release year */
	$release_first_year = ( '' != $gpsp_info[ 'first_release' ] && date( 'Y' ) != $gpsp_info[ 'first_release' ] ) ? $gpsp_info[ 'first_release' ] . '&#x02013;' : '';

	$gpsp_footer_content = '<p><h4>' . __( 'Important plugin links:', 'genesis-printstyle-plus' ) . '</h4>' .

		'<a class="button" href="' . esc_url( $gpsp_info[ 'url_plugin' ] ) . '" target="_new" title="' . __( 'Plugin website', 'genesis-printstyle-plus' ) . '">' . __( 'Plugin website', 'genesis-printstyle-plus' ) . '</a>' .

		'&nbsp;&nbsp;<a class="button" href="' . esc_url( $gpsp_info[ 'url_wporg_faq' ] ) . '" target="_new" title="' . __( 'FAQ', 'genesis-printstyle-plus' ) . '">' . __( 'FAQ', 'genesis-printstyle-plus' ) . '</a>' .

		'&nbsp;&nbsp;<a class="button" href="' . esc_url( $gpsp_info[ 'url_wporg_forum' ] ) . '" target="_new" title="' . _x( 'Support', 'Translators: Plugin support links', 'genesis-printstyle-plus' ) . '">' . _x( 'Support', 'Translators: Plugin support links', 'genesis-printstyle-plus' ) . '</a>' .

		'&nbsp;&nbsp;<a class="button" href="' . esc_url( $gpsp_info[ 'url_snippets' ] ) . '" target="_new" title="' . __( 'Code Snippets for Customization', 'genesis-printstyle-plus' ) . '">' . __( 'Code Snippets', 'genesis-printstyle-plus' ) . '</a>' .

		'&nbsp;&nbsp;<a class="button" href="' . esc_url( $gpsp_info[ 'url_translate' ] ) . '" target="_new" title="' . __( 'Translations', 'genesis-printstyle-plus' ) . '">' . __( 'Translations', 'genesis-printstyle-plus' ) . '</a>' .

		'&nbsp;&nbsp;<a class="button" href="' . esc_url( $gpsp_info[ 'url_donate' ] ) . '" target="_new" title="' . __( 'Donate', 'genesis-printstyle-plus' ) . '"><strong>' . __( 'Donate', 'genesis-printstyle-plus' ) . '</strong></a></p>' .

		'<p><a href="' . esc_url( $gpsp_info[ 'url_license' ] ). '" target="_new" title="' . esc_attr( $gpsp_info[ 'license' ] ). '">' . esc_attr( $gpsp_info[ 'license' ] ). '</a> &#x000A9; ' . $release_first_year . date( 'Y' ) . ' <a href="' . esc_url( ddw_gpsp_plugin_get_data( 'AuthorURI' ) ) . '" target="_new" title="' . esc_attr__( ddw_gpsp_plugin_get_data( 'Author' ) ) . '">' . esc_attr__( ddw_gpsp_plugin_get_data( 'Author' ) ) . '</a></p>';

	return apply_filters( 'gpsp_filter_help_footer_content', $gpsp_footer_content );

}  // end of function ddw_gpsp_plugin_help_content_footer


/**
 * Helper function for returning the Help Sidebar content.
 *
 * @since  1.0.0
 *
 * @uses   ddw_gpsp_info_values()
 * @uses   ddw_gpsp_plugin_get_data()
 *
 * @return string HTML content for help sidebar.
 */
function ddw_gpsp_help_sidebar_content() {

	$gpsp_info = (array) ddw_gpsp_info_values();

	$gpsp_help_sidebar_content = '<p><strong>' . __( 'More about the plugin author', 'genesis-printstyle-plus' ) . '</strong></p>' .
		'<p>' . __( 'Social:', 'genesis-printstyle-plus' ) . '<br /><a href="http://twitter.com/deckerweb" target="_blank" title="@ Twitter">Twitter</a> | <a href="http://www.facebook.com/deckerweb.service" target="_blank" title="@ Facebook">Facebook</a> | <a href="http://deckerweb.de/gplus" target="_blank" title="@ Google+">Google+</a> | <a href="' . esc_url( ddw_gpsp_plugin_get_data( 'AuthorURI' ) ) . '" target="_blank" title="@ deckerweb.de">deckerweb</a></p>' .
		'<p><a href="' . esc_url( $gpsp_info[ 'url_wporg_profile' ] ) . '" target="_blank" title="@ WordPress.org">@ WordPress.org</a></p>';

	return apply_filters( 'gpsp_filter_help_sidebar_content', $gpsp_help_sidebar_content );

}  // end of function ddw_gpsp_help_sidebar_content


/**
 * Helper function for returning the Help Sidebar content - extra for plugin setting page.
 *
 * @since  1.9.3
 *
 * @uses   ddw_gpsp_info_values
 *
 * @return string Extra HTML content for help sidebar.
 */
function ddw_gpsp_help_sidebar_content_extra() {

	$gpsp_info = (array) ddw_gpsp_info_values();

	$gpsp_help_sidebar_content_extra = '<p><strong>' . __( 'Actions', 'genesis-printstyle-plus' ) . '</strong></p>' .
		'<p><a class="button button-primary" href="' . esc_url( $gpsp_info[ 'url_donate' ] ) . '" target="_new">&rarr; ' . __( 'Donate', 'genesis-printstyle-plus' ) . '</a></p>' .
		'<p><a class="button button-secondary" href="' . esc_url( $gpsp_info[ 'url_wporg_forum' ] ) . '" target="_new">&rarr; ' . __( 'Support Forum', 'genesis-printstyle-plus' ) . '</a></p>';

	return apply_filters( 'gpsp_filter_help_sidebar_content_extra', $gpsp_help_sidebar_content_extra );

}  // end of function ddw_gpsp_help_sidebar_content_extra