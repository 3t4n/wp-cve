<?php
/*
Plugin Name: Interactive Polish Map
Plugin URI: http://wordpress.org/extend/plugins/interactive-polish-map/
Description: Interactive Polish Map display Polish map using shortcode or widget.
Version: 1.2.1
Author: Marcin Pietrzak
Author URI: http://iworks.pl
*/

/**
 * $HeadURL: https://plugins.svn.wordpress.org/interactive-polish-map/tags/1.2/interactive-polish-map.php $
 * $LastChangedBy: iworks $
 * $LastChangedDate: 2018-04-21 14:39:20 +0000 (Sat, 21 Apr 2018) $
 */

/**
 * i18n
 */
add_action( 'init', 'ipm_init_load_plugin_textdomain' );
function ipm_init_load_plugin_textdomain() {
	load_plugin_textdomain( 'interactive-polish-map', false, basename( dirname( __FILE__ ) ) . '/languages' );
}

$ipm_data = array(
	'districts' => array(
		'dolnoslaskie'        => 'Województwo Dolnośląskie',
		'kujawsko_pomorskie'  => 'Województwo Kujawsko-Pomorskie',
		'lubelskie'           => 'Województwo Lubelskie',
		'lubuskie'            => 'Województwo Lubuskie',
		'lodzkie'             => 'Województwo Łódzkie',
		'malopolskie'         => 'Województwo Małopolskie',
		'mazowieckie'         => 'Województwo Mazowieckie',
		'opolskie'            => 'Województwo Opolskie',
		'podkarpackie'        => 'Województwo Podkarpackie',
		'podlaskie'           => 'Województwo Podlaskie',
		'pomorskie'           => 'Województwo Pomorskie',
		'slaskie'             => 'Województwo Śląskie',
		'swietokrzyskie'      => 'Województwo Świętokrzyskie',
		'warminsko_mazurskie' => 'Województwo Warmińsko-Mazurskie',
		'wielkopolskie'       => 'Województwo Wielkopolskie',
		'zachodniopomorskie'  => 'Województwo Zachodniopomorskie',
	),
	'menu'      => array(
		'ukryta'               => array(
			'widget' => true,
			'desc'   => __( 'hidden', 'interactive-polish-map' ),
		),
		'po_lewej'             => array(
			'widget' => false,
			'desc'   => __( 'on left', 'interactive-polish-map' ),
		),
		'po_prawej'            => array(
			'widget' => false,
			'desc'   => __( 'on right', 'interactive-polish-map' ),
		),
		'ponizej'              => array(
			'widget' => true,
			'desc'   => __( 'under', 'interactive-polish-map' ),
		),
		'ponizej dwie_kolumny' => array(
			'widget' => false,
			'desc'   => __( 'under - two columns (only for 400px & 500px)', 'interactive-polish-map' ),
		),
		'ponizej trzy_kolumny' => array(
			'widget' => false,
			'desc'   => __( 'under - three columns (only for 500px)', 'interactive-polish-map' ),
		),
	),
	'type'      => array(
		'200' => array(
			'widget' => true,
			'desc'   => '200px',
		),
		'300' => array(
			'widget' => true,
			'desc'   => '300px',
		),
		'400' => array(
			'widget' => false,
			'desc'   => '400px',
		),
		'500' => array(
			'widget' => false,
			'desc'   => '500px',
		),
	),
);

function ipm_admin_init() {

	global $ipm_data;
	foreach ( array_keys( $ipm_data['districts'] ) as $key ) {
		register_setting( 'ipm-options', 'ipm_districts_' . $key, 'wp_filter_nohtml_kses' );
	}
	register_setting( 'ipm-options', 'ipm_type', 'absint' );
	register_setting( 'ipm-options', 'ipm_menu', 'wp_filter_nohtml_kses' );
}

function ipm_add_pages() {

	add_submenu_page(
		'options-general.php',
		__( 'Interactive Polish Map', 'interactive-polish-map' ),
		__( 'Interactive Polish Map', 'interactive-polish-map' ),
		'edit_posts',
		'ipm_settings',
		'ipm_settings'
	);
}

function ipm_produce_radio( $name, $title, $options, $default ) {

	$option_value = get_option( $name, $default );
	$content      = sprintf( '<h3>%s</h3>', $title );
	$content     .= '<ul>';
	$i            = 0;
	foreach ( $options as $value => $data ) {
		$id = $name . $i++;
		if ( isset( $option['name'] ) ) {
			$id = $name . $option['name'] . $i++;
		}
		$content .= sprintf(
			'<li><label for="%s"><input type="radio" name="%s" value="%s"%s id="%s"/> %s</label></li>',
			$id,
			$name,
			$value,
			( $option_value == $value ) ? ' checked="checked"' : '',
			$id,
			$data['desc']
		);
	}
	$content .= '</ul>';
	echo $content;
}

function ipm_settings() {

	global $ipm_data;
	?>
<div class="wrap">
	<h1><?php _e( 'Interactive Polish Map', 'interactive-polish-map' ); ?></h1>
	<p class="description">
	<?php
	$shortcode = '<code>[mapa-polski]</code>';
	printf( __( 'Put shortcode %s on page or post to show map.', 'interactive-polish-map' ), $shortcode );
	?>
	</p>
	<form method="post" action="options.php">
	<?php
	ipm_produce_radio( 'ipm_type', __( 'Map width', 'interactive-polish-map' ), $ipm_data['type'], 500 );
	ipm_produce_radio( 'ipm_menu', __( 'Display list', 'interactive-polish-map' ), $ipm_data['menu'], 'ponizej' );
	?>
		<h3><?php _e( 'URL', 'interactive-polish-map' ); ?></h3>
		<table class="widefat">
	<?php
	$i = 1;
	foreach ( $ipm_data['districts'] as $key => $value ) {
		$url = get_option( 'ipm_districts_' . $key, '' );
		printf(
			'<tr%s><td style="width:150px">%s:</td><td><input type="text" name="ipm_districts_%s" value="%s" class="widefat"/></td></tr>',
			++$i % 2 ? ' class="alternate"' : '',
			esc_attr( $value ),
			esc_attr( $key ),
			esc_attr( $url )
		);
	}
	settings_fields( 'ipm-options' );
	?>
		</table>
		<p class="submit"><input type="submit" class="button-primary" value="<?php _e( 'Save Changes', 'interactive-polish-map' ); ?>" /></p>
	</form>
</div>
	<?php
}

function ipm_shortcode() {

	global $ipm_data;
	$content = sprintf( '<div id="ipm_type_%d"><ul id="w" class="%s">', get_option( 'ipm_type', 500 ), get_option( 'ipm_menu', 'ponizej' ) );
	$i       = 1;
	foreach ( $ipm_data['districts'] as $key => $value ) {
		$url = get_option( 'ipm_districts_' . $key, '%' );
		if ( ! $url ) {
			$url = '#';
		}
		$content .= sprintf(
			'<li id="w%d"><a href="%s" title="%s">%s</a></li>',
			$i++,
			esc_url( $url ),
			esc_attr( $value ),
			esc_html( $value )
		);
	}
	$content .= '</ul></div>';
	return $content;
}

function imp_plugin_links( $plugin_meta, $plugin_file, $plugin_data, $status ) {

	if ( strpos( $plugin_file, basename( __FILE__ ) ) ) {
		$plugin_meta[] = '<a href="options-general.php?page=ipm_settings">' . __( 'Settings' ) . '</a>';
		$plugin_meta[] = '<a href="http://iworks.pl/donate/ipm.php">' . __( 'Donate' ) . '</a>';
	}
	return $plugin_meta;
}

function ipm_init() {

	wp_register_script( 'interactive_polish_map', plugins_url( 'assets/js/interactive_polish_map.js', __FILE__ ), array( 'jquery' ) );
	wp_enqueue_script( 'interactive_polish_map' );
	wp_register_style( 'myStyleSheets', plugins_url( 'assets/style/interactive_polish_map.css', __FILE__ ) );
	wp_enqueue_style( 'myStyleSheets' );
	add_filter( 'plugin_row_meta', 'imp_plugin_links', 10, 4 );
}
/**
 * load snippets
 */
include_once dirname( __FILE__ ) . '/snippets/widget_map.php';

/**
 * init
 */
add_action( 'admin_menu', 'ipm_add_pages' );
add_action( 'admin_init', 'ipm_admin_init' );
add_action( 'init', 'ipm_init' );
add_shortcode( 'mapa-polski', 'ipm_shortcode' );

