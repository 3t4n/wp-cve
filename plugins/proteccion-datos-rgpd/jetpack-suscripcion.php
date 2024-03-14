<?php
/**
 * Formulario de suscripción de Jetpack con RGPD
 * Basado en https://artprojectgroup.es/personalizando-el-widget-suscripciones-de-jetpack y http://hookr.io/functions/jetpack_do_subscription_form/
 *
 * @package   Protección de datos - RGPD
 * @author    ABCdatos
 * @license   GPLv2
 * @link      https://taller.abcdatos.net/
 */

defined( 'ABSPATH' ) || die( 'No se permite el acceso.' );

if ( pdrgpd_modulo_jetpack_suscripciones_activo() ) {
	add_shortcode( 'pdrgpd_jetpack_suscripcion', 'pdrgpd_jetpack_do_subscription_form' );
}

function pdrgpd_jetpack_do_subscription_form( $instance ) {
	// Datos para la primera capa de la suscripción mediante Jetpack.
	$finalidad      = __( 'Inform you of new posts in the site.', 'proteccion-datos-rgpd' );
	$responsable    = 'Automattic Inc., EEUU';
	$transferencia  = $responsable;
	$url_privacidad = pdrgpd_url_privacidad_jetpack();
	$gestion        = 'https://subscribe.wordpress.com/';

	if ( empty( $instance ) || ! is_array( $instance ) ) {
		$instance = array();
	}
	$instance['show_subscribers_total'] = empty( $instance['show_subscribers_total'] ) ? false : true;

	$instance = shortcode_atts(
		Jetpack_Subscriptions_Widget::defaults(),
		$instance,
		'jetpack_subscription_form'
	);
	$args     = array(
		'before_widget' => sprintf(
			'<div class="%s">',
			'jetpack_subscription_widget'
		),
	);
	ob_start();
	the_widget( 'Jetpack_Subscriptions_Widget', $instance, $args );
	$output = ob_get_clean();

	// Código a localizar para el remplazo.
	$original = '<p id="subscribe-submit">';
	// Casilla de aceptación de política de privacidad.
	$nuevo = '<p id="subscribe-policy"><input type="checkbox" name="privacidad" value="privacy-key" class="required" required="required" id="privacidad" /> <span>' . __( 'I accept the', 'proteccion-datos-rgpd' ) . ' <a target="blank" href="' . $url_privacidad . '">' . __( 'privacy policy', 'proteccion-datos-rgpd' ) . ' ' . __( 'of', 'proteccion-datos-rgpd' ) . ' ' . $responsable . '</a>.</span></p>' . "\n";
	// Primera capa de deber de información.
	$nuevo .= pdrgpd_deber_informacion_primera_capa( $finalidad, $transferencia, $responsable, $url_privacidad, $gestion );
	$nuevo .= $original;

	$output = str_replace( $original, $nuevo, $output );

	return $output;
}
