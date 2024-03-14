<?php
/**
 * Mantiene el Aviso Legal
 *
 * @package   Protección de datos - RGPD
 * @author    ABCdatos
 * @license   GPLv2
 * @link      https://taller.abcdatos.net/
 */

defined( 'ABSPATH' ) || die( 'No se permite el acceso.' );

// Inclusiones a pie de página.

// Año de inicio para nota de copyright, introducido en v0.41.
function pdrgpd_conf_pie_copyright() {
	return esc_html( get_option( 'pdrgpd_pie_copyright', pdrgpd_anyo_pie_copyright_propuesto() ) );
}
function pdrgpd_anyo_pie_copyright_propuesto() {
	// Si compara 0.41.0 con 0.41, la 0.41 es anterior.
	if ( version_compare( get_option( 'pdrgpd_version' ), '0.41' ) < 0 ) {
		// En versiones anteriores a la 0.41 no existía el campo, evitamos activarlo sin que lo pida el usuario tras una actualización entregándolo vacío.
		$anyo = '';
	} else {
		// Por omisión, el año del primer post, o en su defecto, el actual.
		global $wpdb;
		$sql       = "SELECT YEAR(min(post_date_gmt)) FROM $wpdb->posts WHERE post_status = 'publish';";
		$resultado = $wpdb->get_var( $sql );
		if ( $resultado ) {
			$anyo = $resultado;
		} else {
			$anyo = gmdate( 'Y' );
		}
	}
	return $anyo;
}

// Cualquiera de las configuraciones del pie activa su uso.
function pdrgpd_pie_utilizado() {
	return pdrgpd_pie_linea_copyright_utilizada() || pdrgpd_pie_linea_enlaces_utilizada();
}

function pdrgpd_pie_linea_copyright_utilizada() {
	return pdrgpd_conf_pie_copyright();
}

function pdrgpd_pie_linea_enlaces_utilizada() {
	return get_option( 'pdrgpd_pie_enlace_legal' ) || get_option( 'pdrgpd_pie_enlace_privacidad' ) || get_option( 'pdrgpd_pie_enlace_cookies' );
}

// Activa la función si está configurado.
if ( pdrgpd_pie_utilizado() ) {
	add_action( 'template_redirect', 'pdrgpd_template_redirect' );
}

function pdrgpd_template_redirect() {
	ob_start();
	ob_start( 'pdrgpd_ob_pie_callback' );
}

function pdrgpd_ob_pie_callback( $buffer ) {

	// Genera el código a incorporar en base a la configuración.
	$linea_copyright = pdrgpd_pie_linea_copyright();
	$linea_enlaces   = pdrgpd_pie_linea_enlaces();
	$pie_completo    = $linea_copyright;
	if ( pdrgpd_pie_linea_copyright_utilizada() && pdrgpd_pie_linea_enlaces_utilizada() && get_option( 'pdrgpd_pie_multilinea' ) ) {
		$pie_completo .= '<br />';
	} else {
		$pie_completo .= ' ';
	}
	$pie_completo .= $linea_enlaces;

	// Aplica el código remplazando el bloque si su estructura de conocida. Por defecto, los temas de WP de los últimos años.
	// Para el resto, comparamos con el nombre del tema en uso y no desperdiciamos recursos.
	switch ( tema_padre() ) {
		case 'Flash':               // Tema Flash de ThemeGrill.
			$buffer = preg_replace( '/<span class="copyright-text">(.*?)<\/span>/su', '<span class="copyright-text">' . $pie_completo . '</span>', $buffer );
			break;
		case 'ColorMag':            // Tema ColorMag de ThemeGrill.
			$buffer = preg_replace( '/<span class="copyright">(.*?)<\/span>/su', '<span class="copyright">' . $pie_completo . '</span>', $buffer );
			break;
		case 'eStore':              // Tema eStore de ThemeGrill.
			$buffer = preg_replace( '/<div class="copy-right">(.*?)<\/div>/su', '<div class="copy-right">' . $pie_completo . '</div>', $buffer );
			break;
		case 'Spacious':            // Tema Spacious de ThemeGrill.
			$buffer = preg_replace( '/<div class="copyright">(.*?)<\/div>/su', '<div class="copyright">' . $pie_completo . '</div>', $buffer );
			break;
		case 'Industro':            // Tema Industro de OceanThemes.
			$buffer = preg_replace( '/<div class="footer-copyright">(.*?)<\/div>/su', '<div class="footer-copyright">' . $pie_completo . '</div>', $buffer );
			break;
		case 'Envo Shop':           // Tema Envo Shop de EnvoThemes.
			$buffer = preg_replace( '/<div class="footer-credits-text text-center">(.*?)<\/div>/su', '<div class="footer-credits-text text-center">' . $pie_completo . '</div>', $buffer );
			break;
		case 'Parabola':            // Tema Parabola de Cryout Creations.
			// Contiene un par de divs dentro del inicio que se remplaza y se localiza el final fuera del anidado, reconstruyéndolo..
			$buffer = preg_replace( '/<div id="footer2-inner">(.*?)<\/div><!-- #footer2 -->/su', '<div id="footer2-inner">' . $pie_completo . '</div></div>\n\t\t<!-- #footer2 -->', $buffer );
			break;
		case 'GeneratePress':            // Tema GeneratePress de Tom Usborne.
			$buffer = preg_replace( '/<div class="copyright-bar">(.*?)<\/div>/su', '<div class="copyright-bar">' . $pie_completo . '</div>', $buffer );
			break;
		case 'Twenty Twenty':       // Tema Twenty Twenty de WordPress.
			$buffer = preg_replace( '/<div class="footer-credits">(.*?)<\/div>/su', '<div class="copyright">' . $pie_completo . '</div>', $buffer );
			break;
		case 'Twenty Twenty-One':   // Tema Twenty Twenty-One de WordPress.
			$buffer = preg_replace( '/<div class="powered-by">(.*?)<\/div>/su', '<div class="powered-by">' . $pie_completo . '</div>', $buffer );
			break;
		case 'Twenty Twenty-Two':   // Tema Twenty Twenty-Two de WordPress.
		case 'Twenty Twenty-Three': // Tema Twenty Twenty-Three de WordPress.
			$buffer = preg_replace( '/<p class="has-text-align-right">(\s|\w)*<a href="https:\/\/(\w)*\.?wordpress\.org" rel="nofollow">WordPress<\/a>\s*<\/p>\s*/ius', '<p class="has-text-align-right">' . $pie_completo . '</p>', $buffer );
			break;
		default:                    // Temas originales de WordPress compatibles: Twenty Twelve, Twenty Thirteen, Twenty Fourteen, Twenty Fifteen, Twenty Sixteen, Twenty Seventeen y Twenty Nineteen de WordPress.
									// También tema Cenote de ThemeGrill.
			$buffer = preg_replace( '/<div class="site-info">(.*?)<\/div>/su', '<div class="site-info">' . $pie_completo . '</div>', $buffer );
			break;
	}

	return $buffer;
}

function pdrgpd_pie_linea_copyright() {
	$html = '';
	if ( pdrgpd_pie_linea_copyright_utilizada() ) {
		$anyo_inicial = esc_html( pdrgpd_conf_pie_copyright() );
		$anyo_actual  = date( 'Y' );
		$html         = 'Copyright &copy; ' . $anyo_inicial;
		if ( $anyo_inicial + 1 == $anyo_actual ) {
			$html .= ', ' . $anyo_actual;
		} elseif ( $anyo_inicial < $anyo_actual ) {
			$html .= '-' . $anyo_actual;
		}
		$html .= ' ';
		if ( 'CIF' === pdrgpd_nif_o_cif( pdrgpd_conf_nif() ) ) {
			// Empresas.
			$html .= pdrgpd_conf_titular();
		} else {
			// Particulares o autónomos.
			$html .= pdrgpd_conf_sitio();
		}
		$html .= '.';
	}
	return $html;
}

function pdrgpd_pie_linea_enlaces() {
	$html = '';
	if ( pdrgpd_pie_linea_enlaces_utilizada() ) {
		$pie_enlace_aviso_utilizado      = get_option( 'pdrgpd_pie_enlace_legal' );
		$pie_enlace_privacidad_utilizada = get_option( 'pdrgpd_pie_enlace_privacidad' );
		$pie_enlace_cookies_utilizada    = get_option( 'pdrgpd_pie_enlace_cookies' );
		if ( $pie_enlace_aviso_utilizado ) {
			$html .= '<a href="' . esc_attr( pdrgpd_conf_uri_aviso() ) . '">' . ucfirst( __( 'legal notice', 'proteccion-datos-rgpd' ) ) . '</a>';
		}
		if ( $pie_enlace_privacidad_utilizada && $pie_enlace_cookies_utilizada ) {
			// Falta determinar si fuese la misma URL.
			if ( $pie_enlace_aviso_utilizado ) {
				$html .= ', ';
				if ( pdrgpd_conf_uri_privacidad() === pdrgpd_conf_uri_cookies() ) {
					$html .= sprintf( __( '<a href="%s">privacy and cookies</a> policy', 'proteccion-datos-rgpd' ), esc_attr( pdrgpd_conf_uri_privacidad() ) );
				} else {
					$html .= sprintf( __( '<a href="%1$s">privacy</a> and <a href="%2$s">cookies</a> policies', 'proteccion-datos-rgpd' ), esc_attr( pdrgpd_conf_uri_privacidad() ), esc_attr( pdrgpd_conf_uri_cookies() ) );
				}
			} else {
				if ( pdrgpd_conf_uri_privacidad() === pdrgpd_conf_uri_cookies() ) {
					$html .= sprintf( __( '<a href="%s">Privacy and cookies policy', 'proteccion-datos-rgpd' ), esc_attr( pdrgpd_conf_uri_privacidad() ) );
				} else {
					$html .= sprintf( __( '<a href="%1$s">Privacy</a> and <a href="%2$s">cookies</a> policies', 'proteccion-datos-rgpd' ), esc_attr( pdrgpd_conf_uri_privacidad() ), esc_attr( pdrgpd_conf_uri_cookies() ) );
				}
			}
		} elseif ( $pie_enlace_privacidad_utilizada ) {
			if ( $pie_enlace_aviso_utilizado ) {
				$html .= ' ';
				$html .= __( 'and', 'proteccion-datos-rgpd' );
				$html .= ' ';
				$html .= pdrgpd_enlace( pdrgpd_conf_uri_privacidad(), __( 'privacy policy', 'proteccion-datos-rgpd' ) );
			} else {
				$html .= pdrgpd_enlace( pdrgpd_conf_uri_privacidad(), ucfirst( __( 'privacy policy', 'proteccion-datos-rgpd' ) ) );
			}
		} elseif ( $pie_enlace_cookies_utilizada ) {
			if ( $pie_enlace_aviso_utilizado ) {
				$html .= ' ';
				$html .= __( 'and', 'proteccion-datos-rgpd' );
				$html .= ' ';
				$html .= pdrgpd_enlace( pdrgpd_conf_uri_cookies(), __( 'cookies policy', 'proteccion-datos-rgpd' ) );
			} else {
				$html .= pdrgpd_enlace( pdrgpd_conf_uri_cookies(), ucfirst( __( 'cookies policy', 'proteccion-datos-rgpd' ) ) );
			}
		}
		$html .= '.';
	}
	return $html;
}

function pdrgpd_enlace( $uri, $anchor ) {
	$html = sprintf( '<a href="%s">%s</a>', esc_attr( $uri ), $anchor );
	return $html;
}
