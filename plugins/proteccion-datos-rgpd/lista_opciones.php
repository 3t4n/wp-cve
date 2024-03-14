<?php
/**
 * Lista de opciones guardadas en la base de datos para configuración y desinstalación.
 *
 * @package   Protección de datos - RGPD
 * @author    ABCdatos
 * @license   GPLv2
 * @link      https://taller.abcdatos.net/
 */

defined( 'ABSPATH' ) || die( 'No se permite el acceso.' );

/** Lista de variables usadas en tabla options. */
function pdrgpd_lista_opciones() {
	return array(
		'pdrgpd_version',
		'pdrgpd_titular',
		'pdrgpd_nif',
		'pdrgpd_vies',
		'pdrgpd_direccion',
		'pdrgpd_cp',
		'pdrgpd_poblacion',
		'pdrgpd_provincia',
		'pdrgpd_telefono',
		'pdrgpd_email',
		'pdrgpd_rmercant_poblacion',
		'pdrgpd_rmercant_provincia',
		'pdrgpd_rmercant_fecha',
		'pdrgpd_rmercant_presentacion',
		'pdrgpd_rmercant_seccion',
		'pdrgpd_rmercant_libro',
		'pdrgpd_rmercant_tomo',
		'pdrgpd_rmercant_folio',
		'pdrgpd_rmercant_hoja',
		'pdrgpd_rmercant_protocolo',
		'pdrgpd_rmercant_num',
		'pdrgpd_sitio',
		'pdrgpd_dominio',
		'pdrgpd_uri_aviso',
		'pdrgpd_uri_privacidad',
		'pdrgpd_uri_cookies',
		'pdrgpd_existencia_formulario_contacto',
		'pdrgpd_finalidad_formulario_contacto_mini',
		'pdrgpd_finalidad_formulario_contacto',
		'pdrgpd_akismet_formulario_contacto',
		'pdrgpd_existencia_boletin',
		'pdrgpd_finalidad_suscripcion_boletin_mini',
		'pdrgpd_finalidad_suscripcion_boletin',
		'pdrgpd_aplicar_formulario_comentar',
		'pdrgpd_finalidad_formulario_comentar_mini',
		'pdrgpd_finalidad_formulario_comentar',
		'pdrgpd_existencia_suscripcion_jetpack',
		'pdrgpd_pie_enlace_legal',
		'pdrgpd_pie_enlace_privacidad',
		'pdrgpd_pie_enlace_cookies',
		'pdrgpd_pie_copyright',
		'pdrgpd_pie_multilinea',
		'pdrgpd_formato_primera_capa',
		'pdrgpd_google_analytics_id',
		'pdrgpd_facebook_pixel_id',
		// A eliminar.
		'pdrgpd_mostrar_banner_cookies',
	);
}
