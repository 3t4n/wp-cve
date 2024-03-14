<?php
/**
 * Helper functions for the admin - plugin links and help tabs.
 *
 * @package    Genesis Featured Page Extras
 * @subpackage Admin
 * @author     David Decker - DECKERWEB
 * @copyright  Copyright (c) 2014, David Decker - DECKERWEB
 * @license    http://www.opensource.org/licenses/gpl-license.php GPL-2.0+
 * @link       http://genesisthemes.de/en/wp-plugins/genesis-featured-page-extras/
 * @link       http://deckerweb.de/twitter
 *
 * @since      1.0.0
 */

/**
 * Prevent direct access to this file.
 *
 * @since 1.0.0
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Sorry, you are not allowed to access this file directly.' );
}


/**
 * Setting internal plugin helper values.
 *
 * @since 1.0.0
 *
 * @uses  get_locale()
 */
function ddw_gfpe_info_values() {

	$gfpe_info = array(

		'url_translate'     => 'http://translate.wpautobahn.com/projects/genesis-plugins-deckerweb/genesis-featured-page-extras',
		'url_wporg_faq'     => 'http://wordpress.org/plugins/genesis-featured-page-extras/faq/',
		'url_wporg_forum'   => 'http://wordpress.org/support/plugin/genesis-featured-page-extras',
		'url_wporg_profile' => 'http://profiles.wordpress.org/daveshine/',
		'url_snippets'      => 'https://gist.github.com/deckerweb/8511594',
		'license'           => 'GPL-2.0+',
		'url_license'       => 'http://www.opensource.org/licenses/gpl-license.php',
		'first_release'     => absint( '2014' ),
		'url_donate'        => ( in_array( get_locale(), array( 'de_DE', 'de_AT', 'de_CH', 'de_LU', 'gsw' ) ) ) ? 'http://genesisthemes.de/spenden/' : 'http://genesisthemes.de/en/donate/',
		'url_plugin'        => ( in_array( get_locale(), array( 'de_DE', 'de_AT', 'de_CH', 'de_LU', 'gsw' ) ) ) ? 'http://genesisthemes.de/plugins/genesis-featured-page-extras/' : 'http://genesisthemes.de/en/wp-plugins/genesis-featured-page-extras/'

	);  // end of array

	return $gfpe_info;

}  // end of function ddw_gfpe_info_values


/**
 * Add "Widgets Page" link to plugin page.
 *
 * @since  1.0.0
 *
 * @param  $gfpe_links
 * @param  $gfpe_widgets_link
 *
 * @return strings String of Widgets admin link.
 */
function ddw_gfpe_widgets_page_link( $gfpe_links ) {

	/** Widgets Admin link */
	$gfpe_widgets_link = sprintf(
		'<a href="%s" title="%s">%s</a>',
		admin_url( 'widgets.php' ),
		esc_html__( 'Go to the Widgets settings page', 'genesis-featured-page-extras' ),
		esc_attr__( 'Widgets', 'genesis-featured-page-extras' )
	);

	/** Set the order of the links */
	array_unshift( $gfpe_links, $gfpe_widgets_link );

	/** Display plugin settings links */
	return apply_filters( 'gfpe_filter_settings_page_link', $gfpe_links );

}  // end of function ddw_gfpe_widgets_page_link


add_filter( 'plugin_row_meta', 'ddw_gfpe_plugin_links', 10, 2 );
/**
 * Add various support links to plugin page.
 *
 * @since  1.0.0
 *
 * @uses   ddw_gfpe_info_values()
 *
 * @param  $gfpe_links
 * @param  $gfpe_file
 *
 * @return string String of plugin links.
 */
function ddw_gfpe_plugin_links( $gfpe_links, $gfpe_file ) {

	/** Capability check */
	if ( ! current_user_can( 'install_plugins' ) ) {

		return $gfpe_links;

	}  // end if cap check

	/** List additional links only for this plugin */
	if ( $gfpe_file == GFPE_PLUGIN_BASEDIR . 'genesis-featured-page-extras.php' ) {

		$gfpe_info = (array) ddw_gfpe_info_values();

		$gfpe_links[] = '<a href="' . esc_url( $gfpe_info[ 'url_wporg_faq' ] ) . '" target="_new" title="' . __( 'FAQ', 'genesis-featured-page-extras' ) . '">' . __( 'FAQ', 'genesis-featured-page-extras' ) . '</a>';

		$gfpe_links[] = '<a href="' . esc_url( $gfpe_info[ 'url_wporg_forum' ] ) . '" target="_new" title="' . __( 'Support', 'genesis-featured-page-extras' ) . '">' . __( 'Support', 'genesis-featured-page-extras' ) . '</a>';

		$gfpe_links[] = '<a href="' . esc_url( $gfpe_info[ 'url_snippets' ] ) . '" target="_new" title="' . __( 'Code Snippets for Customization', 'genesis-featured-page-extras' ) . '">' . __( 'Code Snippets', 'genesis-featured-page-extras' ) . '</a>';

		$gfpe_links[] = '<a href="' . esc_url( $gfpe_info[ 'url_translate' ] ) . '" target="_new" title="' . __( 'Translations', 'genesis-featured-page-extras' ) . '">' . __( 'Translations', 'genesis-featured-page-extras' ) . '</a>';

		$gfpe_links[] = '<a href="' . esc_url( $gfpe_info[ 'url_donate' ] ) . '" target="_new" title="' . __( 'Donate', 'genesis-featured-page-extras' ) . '"><strong>' . __( 'Donate', 'genesis-featured-page-extras' ) . '</strong></a>';

	}  // end if plugin links

	/** Output the links */
	return apply_filters( 'gfpe_filter_plugin_links', $gfpe_links );

}  // end of function ddw_gfpe_plugin_links


add_action( 'sidebar_admin_setup', 'ddw_gfpe_widgets_help' );
/**
 * Load plugin help tab after core help tabs on Widget admin page.
 *
 * @since  1.0.0
 *
 * @global mixed $pagenow
 */
function ddw_gfpe_widgets_help() {

	global $pagenow;

	add_action( 'admin_head-' . $pagenow, 'ddw_gfpe_widgets_help_tab' );

}  // end of function ddw_gfpe_widgets_help


add_action( 'load-toplevel_page_genesis', 'ddw_gfpe_widgets_help_tab', 16 );		// Genesis Core
add_action( 'load-genesis_page_seo-settings', 'ddw_gfpe_widgets_help_tab', 16 );		// Genesis SEO
add_action( 'load-genesis_page_genesis-import-export', 'ddw_gfpe_widgets_help_tab', 16 );	// Genesis Import/Export
add_action( 'load-genesis_page_design-settings', 'ddw_gfpe_widgets_help_tab', 16 );		// Prose Child Theme
add_action( 'load-genesis_page_prose-custom', 'ddw_gfpe_widgets_help_tab', 16 );		// Prose Custom Section
add_action( 'load-genesis_page_dynamik-settings', 'ddw_gfpe_widgets_help_tab', 16 );	// Dynamik Child Theme
add_action( 'load-genesis_page_dynamik-design', 'ddw_gfpe_widgets_help_tab', 16 );		// Dynamik Child Design
add_action( 'load-genesis_page_dynamik-custom', 'ddw_gfpe_widgets_help_tab', 16 );		// Dynamik Custom Section
/**
 * Create and display plugin help tab.
 *
 * @since  1.0.0
 *
 * @uses   get_current_screen()
 * @uses   get_template_directory()
 * @uses   WP_Screen::add_help_tab
 * @uses   WP_Screen::set_help_sidebar
 * @uses   ddw_gfpe_help_sidebar_content()
 * @uses   ddw_gfpe_help_sidebar_content_extra()
 *
 * @global mixed $gfpe_widgets_screen, $pagenow
 */
function ddw_gfpe_widgets_help_tab() {

	global $gfpe_widgets_screen, $pagenow;

	$gfpe_widgets_screen = get_current_screen();

	/** Display help tabs only for WordPress 3.3 or higher */
	if ( ! class_exists( 'WP_Screen' )
		|| ! $gfpe_widgets_screen
		|| basename( get_template_directory() ) != 'genesis'
	) {
		return;
	}

	/** Add the new help tab */
	$gfpe_widgets_screen->add_help_tab( array(
		'id'       => 'gfpe-widgets-help',
		'title'    => __( 'Genesis Featured Page Extras', 'genesis-featured-page-extras' ),
		'callback' => apply_filters( 'gfpe_filter_help_tab_content', 'ddw_gfpe_widgets_help_content' ),
	) );

	/** Add help sidebar */
	if ( $pagenow != 'widgets.php' ) {

		$gfpe_widgets_screen->set_help_sidebar( ddw_gfpe_help_sidebar_content_extra() . ddw_gfpe_help_sidebar_content() );

	}  // end if $pagehook check

}  // end of function ddw_gfpe_widgets_help_tab


/**
 * Create and display plugin help tab content.
 *
 * @since 1.0.0
 *
 * @uses  ddw_gfpe_info_values() To get some strings of info values.
 * @uses  ddw_gfpe_plugin_get_data() To display various data of this plugin.
 * @uses  ddw_gfpe_widget_shortcodes() To detect our widget shortcodes support.
 */
function ddw_gfpe_widgets_help_content() {

	$gfpe_info = (array) ddw_gfpe_info_values();

	/** Helper string */
	$gfpe_filter_help = ' &mdash; ' . sprintf(
		__( 'Fully optional, could be deactivated %svia filter%s.', 'genesis-featured-page-extras' ),
		'<a href="' . esc_url( $gfpe_info[ 'url_snippets' ] ) . '" target="_blank" title="' . __( 'Code Snippets for Customization', 'genesis-featured-page-extras' ) . '">',
		'</a>'
	);

	$gfpe_space_helper = '<div style="height: 5px;"></div>';

	/** Headline */
	echo '<h3>' . __( 'Plugin', 'genesis-featured-page-extras' ) . ': ' . __( 'Genesis Featured Page Extras', 'genesis-featured-page-extras' ) . ' <small>v' . esc_attr( ddw_gfpe_plugin_get_data( 'Version' ) ) . '</small></h3>';

	/** Widget Info */
	echo '<h4>' . __( 'Usage &amp; Instructions', 'genesis-featured-page-extras' ) . ':</h4>';
	echo '<p><blockquote><ul>' .
			'<li>' . __( 'Use side by side with original Featured Widget or other third-party plugins', 'genesis-featured-page-extras' ) . '</li>' .
			'<li>' . __( 'Optional image URL: use media uploader to add an image file, or use external image (in the latter case, be aware of copyright issues!)', 'genesis-featured-page-extras' ) . '</li>' .
			'<li>' . __( 'For image size all built-in sizes (of WordPress) and additional registered sizes are available.', 'genesis-featured-page-extras' ) . ' ' . sprintf(
				__( 'Please be careful in using the %s size which equals to the original size!', 'genesis-featured-page-extras' ),
				'<code>full</code>'
			) . '</li>' .
			'<li>' . __( 'For page content teaser, choose one of the four options: full page content (including more options like limited content), page excerpt, your own custom content or even no content at all', 'genesis-featured-page-extras' ) . '</li>' .
			'<li>' . __( 'Intro &amp; Outro texts are fully optional &ndash; will go below title (but before widget content), plus at the end of widget (after widget content)', 'genesis-featured-page-extras' ) . '</li>' .
		'<ul></blockquote></p>';

	/** Widget & Page Title link info */
	echo '<h4>' . __( 'Widget &amp; Page Title Link', 'genesis-featured-page-extras' ) . ':</h4>';
	echo '<p><blockquote><ul>' .
			'<li>' . __( 'Widget title by default has no link to it, you can use the permalink of your selected page or your own URL, including the link target', 'genesis-featured-page-extras' ) . '</li>' .
			'<li>' . __( 'Page title by default is linked to its permalink (of selected page)', 'genesis-featured-page-extras' ) . '</li>' .
			'<li>' . __( 'Both titles can also be hidden if desired', 'genesis-featured-page-extras' ) . '</li>' .
		'<ul></blockquote></p></p>';

	/** More link info */
	echo '<h4>' . sprintf( __( '%s Link', 'genesis-featured-page-extras' ), '<em>' . __( 'More', 'genesis-featured-page-extras' ) . '</em>' ) . ':</h4>';
	echo '<p><blockquote><ul>' .
			'<li>' . __( 'More link defaults to the used page\'s permalink &ndash; or use your own URL, with optional link target setting', 'genesis-featured-page-extras' ) . '</li>' .
			'<li>' . __( 'Note: link target setting is not available for the default page permalink as it cannot be tweaked in this kind of widget environment (not targetable)', 'genesis-featured-page-extras' ) . '</li>' .
		'<ul></blockquote></p>';

	/** Character limits info */
	echo '<h4>' . __( 'Character Limits', 'genesis-featured-page-extras' ) . ':</h4>';
	echo '<p><blockquote><ul>' .
			'<li>' . __( 'Note: Character limits for Page Title and Content Teaser', 'genesis-featured-page-extras' ) . ': ' . __( 'only integer values are allowed', 'genesis-featured-page-extras' ) . ', ' . __( 'plus: cutoff is always after the full word not within it!', 'genesis-featured-page-extras' ) . '</li>' .
		'<ul></blockquote></p>';

	/** Styling info */
	echo '<h4>' . __( 'Widget Styling', 'genesis-featured-page-extras' ) . ':</h4>';
	echo '<p><blockquote><ul>' .
			'<li><em>' . sprintf( __( 'Styling - %s', 'genesis-featured-page-extras' ), __( 'default', 'genesis-featured-page-extras' ) ) . ':</em> ' . __( 'By default this widget leverages the original classes of Genesis Featured Widget, so all should work from the start', 'genesis-featured-page-extras' ). '</li>' .
			'<li><em>' . sprintf( __( 'Styling - %s', 'genesis-featured-page-extras' ), __( 'custom', 'genesis-featured-page-extras' ) ) . ':</em> ' . __( 'As the widget also brings a few classes on its own you can tweak and/ or enhance styling if ever needed', 'genesis-featured-page-extras' ) . '</li>' .
		'<ul></blockquote></p>';

	/** Widgets shortcode support */
	if ( ddw_gfpe_widget_shortcodes() ) {
	
		echo $gfpe_space_helper . '<p>' . sprintf(
			__( 'Currently, shortcodes are supported in all %s plus our %s of this plugin\'s widget.', 'genesis-featured-page-extras' ) . $gfpe_filter_help,
			'<em>' . __( 'Text Widgets', 'genesis-featured-page-extras' ) . '</em>',
			'<em>' . __( 'Custom Content Field', 'genesis-featured-page-extras' ) . '</em>'
		) . '</p>';

	}  // end if constant check

	/** Set first release year */
	$release_first_year = ( '' != $gfpe_info[ 'first_release' ] && date( 'Y' ) != $gfpe_info[ 'first_release' ] ) ? $gfpe_info[ 'first_release' ] . '&#x02013;' : '';

	/** Help footer: plugin info */
	echo $gfpe_space_helper . '<p><h4>' . __( 'Important plugin links:', 'genesis-featured-page-extras' ) . '</h4>' .
	
	'<a class="button" href="' . esc_url( $gfpe_info[ 'url_plugin' ] ) . '" target="_new" title="' . __( 'Plugin website', 'genesis-featured-page-extras' ) . '">' . __( 'Plugin website', 'genesis-featured-page-extras' ) . '</a>' .

	'&nbsp;&nbsp;<a class="button" href="' . esc_url( $gfpe_info[ 'url_wporg_faq' ] ) . '" target="_new" title="' . __( 'FAQ', 'genesis-featured-page-extras' ) . '">' . __( 'FAQ', 'genesis-featured-page-extras' ) . '</a>' .

	'&nbsp;&nbsp;<a class="button" href="' . esc_url( $gfpe_info[ 'url_wporg_forum' ] ) . '" target="_new" title="' . __( 'Support', 'genesis-featured-page-extras' ) . '">' . __( 'Support', 'genesis-featured-page-extras' ) . '</a>' .

	'&nbsp;&nbsp;<a class="button" href="' . esc_url( $gfpe_info[ 'url_snippets' ] ) . '" target="_new" title="' . __( 'Code Snippets for Customization', 'genesis-featured-page-extras' ) . '">' . __( 'Code Snippets', 'genesis-featured-page-extras' ) . '</a>' .

	'&nbsp;&nbsp;<a class="button" href="' . esc_url( $gfpe_info[ 'url_translate' ] ) . '" target="_new" title="' . __( 'Translations', 'genesis-featured-page-extras' ) . '">' . __( 'Translations', 'genesis-featured-page-extras' ) . '</a>' .

	'&nbsp;&nbsp;<a class="button" href="' . esc_url( $gfpe_info[ 'url_donate' ] ) . '" target="_new" title="' . __( 'Donate', 'genesis-featured-page-extras' ) . '"><strong>' . __( 'Donate', 'genesis-featured-page-extras' ) . '</strong></a></p>';

	echo '<p><a href="' . esc_url( $gfpe_info[ 'url_license' ] ). '" target="_new" title="' . esc_attr( $gfpe_info[ 'license' ] ). '">' . esc_attr( $gfpe_info[ 'license' ] ). '</a> &#x000A9; ' . $release_first_year . date( 'Y' ) . ' <a href="' . esc_url( ddw_gfpe_plugin_get_data( 'AuthorURI' ) ) . '" target="_new" title="' . esc_attr__( ddw_gfpe_plugin_get_data( 'Author' ) ) . '">' . esc_attr__( ddw_gfpe_plugin_get_data( 'Author' ) ) . '</a></p>';

}  // end of function ddw_gfpe_widgets_help_content


/**
 * Helper function for returning the Help Sidebar content.
 *
 * @since  1.0.0
 *
 * @uses   ddw_gfpe_plugin_get_data()
 * @uses   ddw_gfpe_info_values
 *
 * @return string HTML content for help sidebar.
 */
function ddw_gfpe_help_sidebar_content() {

	$gfpe_info = (array) ddw_gfpe_info_values();

	$gfpe_help_sidebar_content = '<p><strong>' . __( 'More about the plugin author', 'genesis-featured-page-extras' ) . '</strong></p>' .
			'<p>' . __( 'Social:', 'genesis-featured-page-extras' ) . '<br /><a href="http://twitter.com/deckerweb" target="_blank" title="@ Twitter">Twitter</a> | <a href="http://www.facebook.com/deckerweb.service" target="_blank" title="@ Facebook">Facebook</a> | <a href="http://deckerweb.de/gplus" target="_blank" title="@ Google+">Google+</a> | <a href="' . esc_url( ddw_gfpe_plugin_get_data( 'AuthorURI' ) ) . '" target="_blank" title="@ deckerweb.de">deckerweb</a></p>' .
			'<p><a href="' . esc_url( $gfpe_info[ 'url_wporg_profile' ] ) . '" target="_blank" title="@ WordPress.org">@ WordPress.org</a></p>';

	return apply_filters( 'gfpe_filter_help_sidebar_content', $gfpe_help_sidebar_content );

}  // end of function ddw_gfpe_help_sidebar_content


/**
 * Helper function for returning the Help Sidebar content - extra for plugin setting page.
 *
 * @since  1.0.0
 *
 * @uses   ddw_gfpe_info_values
 *
 * @return string Extra HTML content for help sidebar.
 */
function ddw_gfpe_help_sidebar_content_extra() {

	$gfpe_info = (array) ddw_gfpe_info_values();

	$gfpe_help_sidebar_content_extra = '<p><strong>' . __( 'Actions', 'genesis-featured-page-extras' ) . '</strong></p>' .
		'<p><a class="button button-primary" href="' . esc_url( $gfpe_info[ 'url_donate' ] ) . '" target="_new">&rarr; ' . __( 'Donate', 'genesis-featured-page-extras' ) . '</a></p>' .
		'<p><a class="button button-secondary" href="' . esc_url( $gfpe_info[ 'url_wporg_forum' ] ) . '" target="_new">&rarr; ' . __( 'Support Forum', 'genesis-featured-page-extras' ) . '</a></p>';

	return apply_filters( 'gfpe_filter_help_sidebar_content_extra', $gfpe_help_sidebar_content_extra );

}  // end of function ddw_gfpe_help_sidebar_content_extra