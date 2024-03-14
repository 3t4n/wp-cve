<?php
/*
Plugin Name: Forum_wordpress_fr
Description: Questionnaire du forum https://wpfr.net/support
Author: Andre Renaut
Author URI: https://www.mailpress.org
Requires at least: 5.2
Tested up to: 5.4
Version: 4.2
*/

/** Absolute path to the Forum_wordpress_fr directory. */
define ( 'FWF_ABSPATH', 	__DIR__ . '/' );

/** Folder name of Forum_wordpress_fr plugin. */
define ( 'FWF_FOLDER', 	basename( FWF_ABSPATH ) );

/** Relative path to the Forum_wordpress_fr directory. */
define ( 'FWF_PATH', 		PLUGINDIR . '/' . FWF_FOLDER . '/' );

class Forum_wordpress_fr 
{
	function __construct() 
	{
		if ( get_option( 'Forum_wordpress_fr' ) ) delete_option( 'Forum_wordpress_fr' );

		$this->site_url = $this->get_site_url();

		if ( is_admin() ) add_action( 'wp_dashboard_setup', array( &$this, 'wp_dashboard_setup' ), 8 );
	}

	function wp_dashboard_setup()
	{
		if ( !function_exists( 'wp_add_dashboard_widget' ) ) return;

	// for gettext
		load_plugin_textdomain( 'Forum_wordpress_fr', false, FWF_PATH . 'languages' );

	// for css
		wp_register_style( __CLASS__, '/' . FWF_PATH . 'css/fwf.css' );
		wp_enqueue_style( __CLASS__ );

	// for javascript
		wp_register_script( __CLASS__, '/' . FWF_PATH . 'js/fwf.js', array('jquery', 'clipboard'), false, 1 );
		wp_localize_script( __CLASS__, 	'fwf_L10n', array(
			'ko' => esc_js( __( 'S&eacute;lectionner le texte ci-dessous puis CTRL+C ou Pomme+C', 'Forum_wordpress_fr' ) ) . "\n\n\n",
			'ok' => esc_js( __( 'Copi&eacute; dans le presse-papier', 'Forum_wordpress_fr' ) ),
		));
		wp_enqueue_script( __CLASS__ );

	// for widget
		wp_add_dashboard_widget( 'Forum_wordpress_fr', __( 'wpfr.net/support', 'Forum_wordpress_fr' ), array( &$this, 'widget' ) );
	}

	function widget() 
	{
	// version wordpress
		global $wp_version, $wpdb;
		$txt[] = sprintf( __( '<strong>- Version de WordPress :</strong> %1$s%2$s', 'Forum_wordpress_fr' ), $wp_version, ( is_multisite() ) ? ' ' . __( 'multi-site', 'Forum_wordpress_fr' ) : '' );

	// version php/mysql
		$php_ver = phpversion();
		$mysql_ver = $wpdb->db_version();
		$txt[] = sprintf( __( '<strong>- Version de PHP/MySQL :</strong> %1$s / %2$s', 'Forum_wordpress_fr' ), $php_ver, $mysql_ver );

	// theme
		if ( function_exists( 'wp_get_theme' ) )
		{
			$wp_theme = wp_get_theme( get_stylesheet() );
			$wp_theme_name = $wp_theme->display( 'Name', true, false );
			$wp_theme_url = $wp_theme->display( 'ThemeURI', true, false );
			if ( !empty( $wp_theme_url ) ) $wp_theme_url = sprintf( __( '<strong>- Th&egrave;me URI :</strong> %s', 'Forum_wordpress_fr' ), $wp_theme_url );
		}
		else
		{
			global $wp_themes;
			$wp_theme_name = get_current_theme();
			$wp_theme = $wp_themes[$wp_theme_name];
			$wp_theme_url = $wp_theme['Author URI'];
			if ( !empty( $wp_theme_url ) ) $wp_theme_url = sprintf( __('<strong>- Th&egrave;me Auteur URI :</strong> %s', 'Forum_wordpress_fr' ), $wp_theme_url );
		}
		$txt[] = sprintf( __( '<strong>- Th&egrave;me utilis&eacute; :</strong> %s', 'Forum_wordpress_fr' ), $wp_theme_name );
		if ( !empty( $wp_theme_url ) ) 
			$txt[] = $wp_theme_url;

	// plugins
		foreach ( (array) get_plugins() as $plugin_file => $plugin_data ) 
		{
			if ( is_plugin_active_for_network( $plugin_file ) ) 
				$ms_plugins[] = $plugin_data['Name'] . ' (' . $plugin_data['Version'] . ')';
			elseif ( is_plugin_active( $plugin_file ) ) 
				$wp_plugins[] = $plugin_data['Name'] . ' (' . $plugin_data['Version'] . ')';
		}
		if ( isset( $wp_plugins ) )
			$txt[] = sprintf( __( '<strong>- Extensions en place :</strong> %s', 'Forum_wordpress_fr' ), join( ', ', $wp_plugins ) );

		if ( isset( $ms_plugins ) )
			$txt[] = sprintf( __( '<strong>- Extensions r&eacute;seau en place :</strong> %s', 'Forum_wordpress_fr' ), join( ', ', $ms_plugins ) );

	// site url
		$siteurl = $this->site_url;
		$txt[] = sprintf( __( '<strong>- Adresse du site :</strong> %s', 'Forum_wordpress_fr' ), $siteurl );

	// host
		$host = $_SERVER['SERVER_SOFTWARE'];
		$txt[] = sprintf( __( '<strong>- Nom de l\'h&eacute;bergeur :</strong> %s', 'Forum_wordpress_fr' ), $host );

	// os
	//	$os = php_uname();
	//	$txt[] = sprintf( __( '<strong>- Nom de l\'o.s. :</strong> %s', 'Forum_wordpress_fr' ), $os );

		$out  = '';
		$out .= '<div id="fwf_content"><strong>' . __( 'Ma configuration WP actuelle :', 'Forum_wordpress_fr' ) . '</strong>';
		$out .= "\n";
		$out .= '<ul><li>' . join( "</li>\n<li>", $txt ) . '</li></ul>';
		$out .= '</div>';
		$out .= '<div style="position:relative;">';
		$out .= '<div style="position:absolute;">';
		foreach( $txt as $k => $v ) $txt[$k] = strip_tags( $txt[$k] );
		$out .= '<textarea id="fwf_copied" style="height:0; width:0; opacity:0;">' .  __( 'Ma configuration WP actuelle :', 'Forum_wordpress_fr' ) . "\r\n" . join( "\r\n", $txt ) . '</textarea>';
		$out .= '</div>';

		$out .= '<div id="fwf_button">';
		$out .= '<input id="fwf_copy" class="fwf_copy button-primary" type="button" data-clipboard-target="#fwf_copied" value="' . esc_attr( __( 'Copier', 'Forum_wordpress_fr' ) ) . '" />';
		$out .= '</div>';

		global $wpdb;
		$out .= '<!--' . print_r( array ( '$wpdb->blogid' => $wpdb->blogid, 'get_current_blog_id()' => get_current_blog_id() ), true ) . '-->';
		$out .= '</div>';

		echo $out;
	}

	function get_site_url()
	{
		return ( defined( 'WP_SITEURL' ) ) ? WP_SITEURL : site_url();
	}
}
new Forum_wordpress_fr();