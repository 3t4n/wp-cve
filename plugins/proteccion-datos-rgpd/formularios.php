<?php
/**
 * Gestiona la inserción de avisos y casillas de aceptación en formularios
 *
 * @package   Protección de datos - RGPD
 * @author    ABCdatos
 * @license   GPLv2
 * @link      https://taller.abcdatos.net/
 */

/**
 * Formulario de comentarios.
 * Deber de información, bien en formato de tabla o párrafo
 * Obligatorio aceptar política de privacidad
 * Aceptación grabada en BBDD
 */

defined( 'ABSPATH' ) || die( 'No se permite el acceso.' );

/*
 * Primera capa del deber de información.
*/
function pdrgpd_deber_informacion_primera_capa( $finalidad, $transferencia, $responsable, $url_privacidad, $gestion ) {
	$formato = pdrgpd_conf_formato_primera_capa();
	if ( 'tabla' === $formato ) {
		$html = pdrgpd_tabla_primera_capa( $finalidad, $transferencia, $responsable, $url_privacidad, $gestion );
	} elseif ( 'parrafo' === $formato ) {
		$html = pdrgpd_parrafo_primera_capa( $finalidad, $transferencia, $responsable, $url_privacidad, $gestion );
	}
	return do_shortcode( $html );
}

// Versión en párrafo de primera capa del deber de información.
function pdrgpd_parrafo_primera_capa( $finalidad, $transferencia, $responsable, $url_privacidad, $gestion ) {
	$html  = '<p class="pdrgpd_primeracapa">';
	$html .= '<strong>' . __( 'Basic data protection information: ', 'proteccion-datos-rgpd' ) . '</strong>';
	if ( $responsable ) {
		$html .= pdrgpd_finaliza_frase( __( 'The processing responsible is', 'proteccion-datos-rgpd' ) . ' ' . esc_html( $responsable ) );
	} else {
		$html .= pdrgpd_finaliza_frase( __( 'The processing responsible is', 'proteccion-datos-rgpd' ) . ' [pdrgpd-titular]' );
	}
	// Se hace traducción cruzada de la finalidad. De este modo, unsitio encastellano que tiene configurado el texto por defecto, lo puede mostrar en otros idiomas si procede.
	$html .= pdrgpd_finaliza_frase( __( 'Your data will be processed for', 'proteccion-datos-rgpd' ) . ' ' . esc_html( lcfirst( pdrgpd_finalidad_traducida( $finalidad ) ) ) );
	$html .= pdrgpd_finaliza_frase( __( 'The processing legitimation is the consent of the concerned party', 'proteccion-datos-rgpd' ) );
	if ( $transferencia ) {
		$html .= pdrgpd_finaliza_frase( __( 'Your data will be processed by', 'proteccion-datos-rgpd' ) . ' ' . esc_html( $transferencia ) );
	} else {
		$html .= pdrgpd_finaliza_frase( __( 'No data will be transferred to third parties, except legal obligation', 'proteccion-datos-rgpd' ) );
	}
	if ( $gestion ) {
		$html .= __( 'You have the right to access, rectify and cancel the data, as well as other rights', 'proteccion-datos-rgpd' ) . ' ' . __( 'through', 'proteccion-datos-rgpd' ) . ' <a href="' . esc_html( $gestion ) . '" target="_blank" rel="noopener noreferrer">' . __( 'here', 'proteccion-datos-rgpd' ) . '</a>. ';
		$html .= __( 'You can read additional and detailed information on data protection on our page', 'proteccion-datos-rgpd' ) . ' <a href="[pdrgpd-uri-privacidad]" target="_blank" rel="noopener noreferrer">' . __( 'privacy policy', 'proteccion-datos-rgpd' ) . '</a>. ';
	} else {
		$html .= __( 'You have the right to access, rectify and cancel the data, as well as other rights, as explained in the', 'proteccion-datos-rgpd' ) . ' <a href="[pdrgpd-uri-privacidad]" target="_blank" rel="noopener noreferrer">' . __( 'privacy policy', 'proteccion-datos-rgpd' ) . '</a>.';
	}
	$html .= "</p>\n";
	return $html;
}

// Versión en tabla de primera capa del deber de información.
function pdrgpd_tabla_primera_capa( $finalidad, $transferencia, $responsable, $url_privacidad, $gestion ) {
	$html  = "<table class=\"pdrgpd_primeracapa\">\n";
	$html .= pdrgpd_titulo_tabla_primera_capa();
	if ( $responsable ) {
		$html .= pdrgpd_fila_tabla_primera_capa( __( 'Responsible', 'proteccion-datos-rgpd' ), esc_html( $responsable ) . ' ' . pdrgpd_enlace_mas_info( $url_privacidad, 'responsable' ) );
	} else {
		$html .= pdrgpd_fila_tabla_primera_capa( __( 'Responsible', 'proteccion-datos-rgpd' ), '[pdrgpd-titular] ' . pdrgpd_enlace_mas_info( $url_privacidad, 'responsable' ) );
	}
	// Se hace traducción cruzada de la finalidad. De este modo, unsitio encastellano que tiene configurado el texto por defecto, lo puede mostrar en otros idiomas si procede.
	$html .= pdrgpd_fila_tabla_primera_capa( __( 'Purpose', 'proteccion-datos-rgpd' ), esc_html( pdrgpd_finalidad_traducida( $finalidad ) ) . ' ' . pdrgpd_enlace_mas_info( $url_privacidad, 'finalidad' ) );
	$html .= pdrgpd_fila_tabla_primera_capa( __( 'Legitimation', 'proteccion-datos-rgpd' ), __( 'Consent of the concerned party.', 'proteccion-datos-rgpd' ) . ' ' . pdrgpd_enlace_mas_info( $url_privacidad, 'legitimacion' ) );
	if ( $transferencia ) {
		$html .= pdrgpd_fila_tabla_primera_capa( __( 'Recipients', 'proteccion-datos-rgpd' ), esc_html( $transferencia ) . ' ' . pdrgpd_enlace_mas_info( $url_privacidad, 'transferemcia' ) );
	} else {
		$html .= pdrgpd_fila_tabla_primera_capa( __( 'Recipients', 'proteccion-datos-rgpd' ), __( 'No data will be transferred to third parties, except legal obligation', 'proteccion-datos-rgpd' ) . ' ' . pdrgpd_enlace_mas_info( $url_privacidad, 'transferencia' ) );
	}
	if ( $gestion ) {
		$html .= pdrgpd_fila_tabla_primera_capa( __( 'Rights', 'proteccion-datos-rgpd' ), __( 'Access, rectify and cancel data, as well as some other rights', 'proteccion-datos-rgpd' ) . ' ' . __( 'through', 'proteccion-datos-rgpd' ) . ' ' . pdrgpd_enlace_mas_info( esc_html( $gestion ), '' ) );
	} else {
		$html .= pdrgpd_fila_tabla_primera_capa( __( 'Rights', 'proteccion-datos-rgpd' ), __( 'Access, rectify and cancel data, as well as some other rights', 'proteccion-datos-rgpd' ) . '. ' . pdrgpd_enlace_mas_info( $url_privacidad, 'derechos' ) );
	}
	$html .= pdrgpd_fila_tabla_primera_capa( __( 'Additional information', 'proteccion-datos-rgpd' ), __( 'You can read additional and detailed information on data protection on our page', 'proteccion-datos-rgpd' ) . ' <a href="[pdrgpd-uri-privacidad]" target="_blank" rel="noopener noreferrer">' . __( 'privacy policy', 'proteccion-datos-rgpd' ) . '</a>.' );
	$html .= "</table>\n";
	return $html;
}

function pdrgpd_enlace_mas_info( $url_privacidad, $id ) {
	$html = '<a href="';
	if ( $url_privacidad ) {
		$html .= $url_privacidad;
	} else {
		$html .= '[pdrgpd-uri-privacidad]';
		// El id solo actúa sobre la URL propia.
		if ( $id ) {
			$html .= "#$id";
		}
	}
	$html .= '" target="_blank" rel="noopener noreferrer">' . __( '+info...', 'proteccion-datos-rgpd' ) . '</a>';
	return $html;
}

function pdrgpd_titulo_tabla_primera_capa() {
	$html  = " <tr>\n";
	$html .= '  <th colspan=2 class="pdrgpd_primeracapa">' . __( 'Basic information on data protection', 'proteccion-datos-rgpd' ) . "</th>\n";
	$html .= " </tr>\n";
	return $html;
}

function pdrgpd_fila_tabla_primera_capa( $titulo, $contenido ) {
	$html  = " <tr>\n";
	$html .= pdrgpd_epígrafe_tabla_primera_capa( $titulo, $contenido );
	$html .= " </tr>\n";
	return $html;
}

function pdrgpd_epígrafe_tabla_primera_capa( $titulo, $contenido ) {
	$html  = " <th class=\"pdrgpd_primeracapa\">$titulo</th>\n";
	$html .= " <td class=\"pdrgpd_primeracapa\">$contenido</td>\n";
	return $html;
}

// Shortcode para añadir en el formulario de contacto la primera capa del deber de información.
add_shortcode( 'pdrgpd-aviso-formulario-contacto', 'pdrgpd_aviso_formulario_contacto' );
function pdrgpd_aviso_formulario_contacto() {
	if ( get_option( 'pdrgpd_existencia_formulario_contacto' ) ) {
		$html = pdrgpd_deber_informacion_primera_capa( pdrgpd_conf_finalidad_formulario_contacto_mini(), pdrgpd_politica_privacidad_transferencia_mini( 'contacto' ), '', '', '' );
		// This may create an endless loop if the user inserts bigger shortags here if using do_shortcode() here.
		return $html;
	}
}

// Shortcode para añadir en el formulario de contacto la primera capa del deber de información.
add_shortcode( 'pdrgpd-aviso-boletin', 'pdrgpd_aviso_boletin' );
function pdrgpd_aviso_boletin() {
	if ( get_option( 'pdrgpd_existencia_boletin' ) ) {
		$html = pdrgpd_deber_informacion_primera_capa( pdrgpd_conf_finalidad_boletin_mini(), pdrgpd_politica_privacidad_transferencia_mini( 'boletin' ), '', '', '' );
		// This may create an endless loop if the user inserts bigger shortags here if using do_shortcode() here.
		return $html;
	}
}

// Texto a añadir tras el formulario de comentarios, si se configuró así.
// Se puede hacer como texto lineal o como tabla, habrá que elegir.
if ( get_option( 'pdrgpd_aplicar_formulario_comentar' ) ) {
	// Evita incompatibilidad con los comentarios mediante Jetpack.
	if ( ! pdrgpd_modulo_jetpack_comentarios_activo() ) {
		add_filter( 'comment_form_defaults', 'pdrgpd_aviso_tras_form_comentar' );
	}
}
function pdrgpd_aviso_tras_form_comentar( $args ) {
	$args['comment_notes_after'] = pdrgpd_deber_informacion_primera_capa( pdrgpd_conf_finalidad_formulario_comentar_mini(), pdrgpd_politica_privacidad_transferencia_mini( 'comentar' ), '', '', '' );
	return $args;
}

// Añadir checkbox despues del campo Comentario.
if ( get_option( 'pdrgpd_aplicar_formulario_comentar' ) ) {
	if ( ! pdrgpd_modulo_jetpack_comentarios_activo() ) {
		add_filter( 'comment_form_field_comment', 'pdrgpd_comment_form_field_comment' );
	}
}
function pdrgpd_comment_form_field_comment( $comment_field ) {
	$comment_field .= '<p class="comment-subscription-form">';
	$comment_field .= '<input type="checkbox" name="pdrgpd_acepto_politica_privacidad" value="acepto" style="width: auto; -moz-appearance: checkbox; -webkit-appearance: checkbox;" required="required" id="pdrgpd_acepto_politica_privacidad" />';
	$comment_field .= ' <label class="subscribe-label" for="pdrgpd_acepto_politica_privacidad">';
	$comment_field .= __( 'I accept the', 'proteccion-datos-rgpd' ) . ' <a href="[pdrgpd-uri-privacidad]" target="_blank" rel="noopener noreferrer">' . __( 'privacy policy', 'proteccion-datos-rgpd' ) . '</a>.';
	$comment_field .= '</label>';
	$comment_field .= '</p>';
	return do_shortcode( $comment_field );
}

// Fuerza aceptar la política de privacidad salvo en el escritorio del administrador.
if ( ! is_admin() ) {
	if ( get_option( 'pdrgpd_aplicar_formulario_comentar' ) ) {
		if ( ! pdrgpd_modulo_jetpack_comentarios_activo() ) {
			add_filter( 'preprocess_comment', 'pdrgpd_requiere_aceptar_privacidad' );
		}
	}
}
function pdrgpd_requiere_aceptar_privacidad( $fields ) {
	if ( ( ! isset( $_POST['pdrgpd_acepto_politica_privacidad'] ) ) || 'acepto' !== $_POST['pdrgpd_acepto_politica_privacidad'] ) {
		wp_die( '<p><strong>ERROR</strong>: ' . esc_html( __( 'You must accept the privacy policy to send comments', 'proteccion-datos-rgpd' ) ) . '.</p>' . "\n" . '<p><a href=\'javascript:history.back()\'>&laquo; ' . esc_html( __( 'Return', 'proteccion-datos-rgpd' ) ) . '</a></p>' );
	}
	return $fields;
}

// Guarda el valor d la casilla de aceptar la privacidad en la tabla comment metadata.
if ( get_option( 'pdrgpd_aplicar_formulario_comentar' ) ) {
	add_action( 'comment_post', 'pdrgpd_aceptacion_privacidad_grabar', 1 );
}
function pdrgpd_aceptacion_privacidad_grabar( $post_id ) {
	if ( isset( $_POST['pdrgpd_acepto_politica_privacidad'] ) ) {
		$acepta_privacidad = sanitize_text_field( wp_unslash( $_POST['pdrgpd_acepto_politica_privacidad'] ) );
		if ( $acepta_privacidad ) {
			add_comment_meta( $post_id, 'pdrgpd_acepto_politica_privacidad', $acepta_privacidad, true );
		}
	}
}

// Muestra la la aceptación de la política de privacidad en la página de administración de comentarios wp-admin/edit-comments.php.
if ( is_admin() ) {
	add_action( 'comment_text', 'pdrgpd_aceptacion_privacidad_mostrar' );
}
function pdrgpd_aceptacion_privacidad_mostrar() {
	echo esc_html( get_comment_text() ), '<br><br><strong>Política privacidad: ', esc_html( get_comment_meta( get_comment_ID(), 'pdrgpd_acepto_politica_privacidad', 1 ) ), '<strong>';
}

// Valor configurado o por defecto del formato para la primera capa del deber de información.
function pdrgpd_conf_formato_primera_capa() {
	$formato = get_option( 'pdrgpd_formato_primera_capa', 'tabla' );
	if ( ! $formato ) {
		$formato = 'tabla';
	}
	return $formato;
}

// Valor configurado o por defecto de la existencia de Akismet.
function pdrgpd_existe_akismet() {
	$existe_akismet = false;
	if ( get_option( 'pdrgpd_aplicar_formulario_comentar' ) ) {
		if ( get_option( 'akismet_comment_form_privacy_notice' ) ) {
			$existe_akismet = true; // Valores "display" o "hide" desde v 4.0.4, su presencia queda ahí delatada.
		}
	}
	return $existe_akismet;
}

// Valor configurado o por defecto de la existencia de formulario de suscripcion de Jetpack.
function pdrgpd_existe_suscripcion_jetpack() {
	$existe_suscripcion_jetpack = false;
	if ( get_option( 'pdrgpd_existencia_suscripcion_jetpack' ) ) {
		$existe_suscripcion_jetpack = true;
	}
	return $existe_suscripcion_jetpack;
}

// Un valor por defecto configurado en español es traducido a francés o inglés para mostrarlo en la primera capa cuando el sitio es multiidioma.
function pdrgpd_finalidad_traducida( $finalidad ) {
	if ( 'Mantener el contacto contigo u otras acciones obligatorias.' === $finalidad || 'Mantenir contacte amb tu o altres accions requerides.' === $finalidad ) {
		$locale = get_locale();
		if ( 'ca' === $locale ) {
			$finalidad = 'Mantenir contacte amb tu o altres accions requerides.';
		} elseif ( 'en_GB' === $locale ) {
			$finalidad = 'Keep in contact with you or other required actions.';
		} elseif ( 'fr_FR' === $locale ) {
			$finalidad = 'Restez en contact avec vous ou d\'autres actions requises.';
		} else {
			$finalidad = 'Mantener el contacto contigo u otras acciones obligatorias.';
		}
	}
	return $finalidad;
}

// Enable shortcode in Contact Form 7.
// Habilita shortcodes en Contact Form 7 si está presente.
add_filter( 'wpcf7_form_elements', 'do_shortcode' );

// Enable shortcode in HTML widgets.
// Habilita shortcodes en widgets HTML.
add_filter( 'widget_text', 'do_shortcode' );
