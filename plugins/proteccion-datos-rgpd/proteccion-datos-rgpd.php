<?php
/*
Plugin Name: Protección de datos - RGPD
Plugin URI:  https://taller.abcdatos.net/plugin-rgpd-wordpress/
Description: Arrange your site to GDPR (General Data Protection Regulation) and LSSICE as well as other required tasks based on required configurations ettings.
Version:     0.65
Author:      ABCdatos
Author URI:  https://taller.abcdatos.net/
License:     GPLv2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: proteccion-datos-rgpd
Domain Path: /languages
*/

defined( 'ABSPATH' ) || die( 'No se permite el acceso.' );

// i18n.
// O usamos este hook o el requisito mínimo es WP 4.6.
add_action( 'plugins_loaded', 'pdrgpd_load_plugin_textdomain' );
function pdrgpd_load_plugin_textdomain() {
	load_plugin_textdomain( 'proteccion-datos-rgpd', false, basename( dirname( __FILE__ ) ) . '/languages' );
}

// Administration features (settings).
if ( is_admin() ) {
	include_once 'admin/options.php';
}

// Legal Advice related code loading.
require_once plugin_dir_path( __FILE__ ) . 'aviso-legal.php';

// Privacy Policy related code loading.
require_once plugin_dir_path( __FILE__ ) . 'politica-privacidad.php';

// Primera capa del deber de información y casilla de aceptación en formularios.
require_once plugin_dir_path( __FILE__ ) . 'formularios.php';

// Cookie Policy related code loading.
require_once plugin_dir_path( __FILE__ ) . 'politica-cookies.php';

// Cookies loading related code.
require_once plugin_dir_path( __FILE__ ) . 'insercion-cookies.php';

// Notas a pie de página.
require_once plugin_dir_path( __FILE__ ) . 'pie.php';

// Lista de variables usadas en tabla options.
require_once plugin_dir_path( __FILE__ ) . 'lista_opciones.php';

// Si está disponible Jetpack y en las opciones se indicó que se utiliza, carga el código para el shortcode de remplazo del formulario de suscripción.
if ( class_exists( 'Jetpack' ) ) {
	if ( pdrgpd_existe_suscripcion_jetpack() ) {
		include_once plugin_dir_path( __FILE__ ) . 'jetpack-suscripcion.php';
	}
}

add_filter( 'plugin_action_links', 'pdrgpd_plugin_action_links', 10, 2 );
/** Settings link in Plugins admin page
// Based on https://www.smashingmagazine.com/2011/03/ten-things-every-wordpress-plugin-developer-should-know/ */
function pdrgpd_plugin_action_links( $links, $file ) {
	static $this_plugin;
	if ( ! $this_plugin ) {
		$this_plugin = plugin_basename( __FILE__ );
	}
	if ( $file === $this_plugin ) {
		// The "page" query string value must be equal to the slug of the Settings admin page.
		$settings_link = '<a href="' . admin_url( 'admin.php?page=proteccion-datos-rgpd' ) . '">' . __( 'Settings' ) . '</a>';
		array_unshift( $links, $settings_link );
	}
	return $links;
}

/** Plugin version for options page header. */
function pdrgpd_get_version() {
	$plugin_data    = get_plugin_data( __FILE__ );
	$plugin_version = $plugin_data['Version'];
	return $plugin_version;
}

/** Common functions. */
function pdrgpd_nif_o_cif( $codigo ) {
	// Determina si es NIF, CIF o NIE por la sintaxis.
	$tipo = 'DNI';
	if ( preg_match( '/^[a-z]/i', $codigo ) ) {
		$tipo = 'CIF';
		if ( preg_match( '/^(x|y|z)/i', $codigo ) ) {
			$tipo = 'NIE';
		}
	}
	return $tipo;
}

/** Determina el tema actual, o el padre si se usa un child theme. */
function tema_padre() {
	$tema_actual = wp_get_theme();
	$tema_padre  = $tema_actual->Name;
	if ( $tema_actual->parent() ) {
		$tema_padre = $tema_actual->parent()->Name;
	}
	return $tema_padre;
}

function pdrgpd_enlace_nueva_ventana( $url, $anchor ) {
	$html = '<a href="' . $url . '" target="_blank" rel="noopener noreferrer">' . esc_attr( $anchor ) . '</a>';
	return $html;
}

function pdrgpd_retira_punto_final( $texto ) {
	$texto = rtrim( $texto, '.' );
	return $texto;
}

function pdrgpd_agrega_punto_final( $texto ) {
	$texto  = pdrgpd_retira_punto_final( $texto );
	$texto .= '.';
	return $texto;
}

/** Pone punto final si no estaba y agrega un espacio. */
function pdrgpd_finaliza_frase( $texto ) {
	$texto  = pdrgpd_agrega_punto_final( $texto );
	$texto .= ' ';
	return $texto;
}

function pdrgpd_modulo_jetpack_comentarios_activo() {
	return pdrgpd_modulo_jetpack_activo( 'comments' );
}

function pdrgpd_modulo_jetpack_suscripciones_activo() {
	return pdrgpd_modulo_jetpack_activo( 'subscriptions' );
}

function pdrgpd_modulo_jetpack_activo( $modulo ) {
	$activo = false;
	if ( class_exists( 'Jetpack' ) ) {
		$modulos_jetpack = get_option( 'jetpack_active_modules' );
		$activo          = in_array( $modulo, $modulos_jetpack, true );
	}
	return $activo;
}
