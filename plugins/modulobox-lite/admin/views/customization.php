<?php
/**
 * @package   ModuloBox
 * @author    Themeone <themeone.master@gmail.com>
 * @copyright 2017 Themeone
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$allowed_html = array(
	'a' => array(
		'href'   => array(),
		'target' => array()
	)
);

$kraken = 'https://wpkraken.io/?ref=Themeone';
$documentation = 'https://theme-one.com/modulobox/documentation/';

echo '<div class="mobx-tab-content mobx-customization-content">';

	echo '<h2>' . esc_html__( 'Customization', 'modulobox' ) . '</h2>';

	echo '<p>';
		esc_html_e( 'ModuloBox offers a powerful and advanced JavaScript API to extend its functionalities.', 'modulobox' );
		echo '<br>';
		printf( wp_kses( __( 'You will find ModuloBox JavaScript API documentation <a target="_blank" href="%s">here</a>.', 'modulobox' ), $allowed_html ), esc_url( $documentation ) ) ;
		echo '<br>';
		esc_html_e( 'Customization service is out of the scope of the included support for ModuloBox Premium version.', 'modulobox' );
		echo '<br>';
		printf( wp_kses( __( 'If you are looking for customization service, you can take a look at <a target="_blank" href="%s">WP.Kraken</a>.', 'modulobox' ), $allowed_html ), esc_url( $kraken ) );
	echo '</p>';

	echo '<h3>' . esc_html__( 'Optimization', 'modulobox' ) . '</h3>';
	do_settings_sections( 'minify-scripts' );

	echo '<h3>' . esc_html__( 'Custom CSS', 'modulobox' ) . '</h3>';
	echo '<p>';
		esc_html_e( 'Enter your custom CSS code below to modify ModuloBox styles.', 'modulobox' );
		echo '<br>';
		esc_html_e( 'Be careful, CSS modifications can break functionalities or slow down animation when not correctly used.', 'modulobox' );
	echo '</p>';
	do_settings_sections( 'custom-css' );
		

	echo '<h3>' . esc_html__( 'Custom JS (Advanced users)', 'modulobox' ) . '</h3>';
	echo '<p>';
		esc_html_e( 'Enter your custom JavaScript (or jQuery) code below to modify ModuloBox behaviour.', 'modulobox' );
		echo '<br>';
		esc_html_e( 'Be careful, JavaScript modifications can break ModuloBox when not correctly used.', 'modulobox' );
	echo '</p>';
	do_settings_sections( 'custom-js' );

	echo '<h3>' . esc_html__( 'Custom JS for Core Script Modifications (Developers)', 'modulobox' ) . '</h3>';
	echo '<p>';
		esc_html_e( 'The code present in this field will be executed before instantiation of ModuloBox.', 'modulobox' );
		echo '<br>';
		esc_html_e( 'It mainly allows to modify core script prototypes of ModuloBox.', 'modulobox' );
	echo '</p>';
	do_settings_sections( 'custom-js-advanced' );

	include( MOBX_ADMIN_PATH . 'views/info-bar.php' );

echo '</div>';
