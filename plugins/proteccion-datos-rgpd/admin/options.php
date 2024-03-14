<?php
/**
 * Runs on Uninstall of Protección de datos - RGPD
 *
 * @package   Protección de datos - RGPD
 * @author    ABCdatos
 * @license   GPLv2
 * @link      https://taller.abcdatos.net/
 */

defined( 'ABSPATH' ) || die( 'No se permite el acceso.' );

define( 'AVISO_ROJO', '<span style="color:red;">&#9679;&nbsp;</span> ' );
define( 'AVISO_AMARILLO', '<span style="color:orange;">&#9679;&nbsp;</span>' );
define( 'AVISO_VERDE', '<span style="color:green;">&#10004;&nbsp;</span> ' );

// Dashboard menu settings.
add_action( 'admin_menu', 'pdrgpd_add_admin_menu' );
function pdrgpd_add_admin_menu() {
	if ( pdrgpd_errores_config() ) {
		$notificacion_contenido = '!';
		$notificacion_globo     = " <span class=\"awaiting-mod\">$contenido_notificacion</span>";
	} else {
		$notificacion_globo = '';
	}
	add_menu_page(
		__( 'Protección Datos RGPD', 'proteccion-datos-rgpd' ) . ' - ' .
		__( 'Settings', 'proteccion-datos-rgpd' ),                                    // Page title.
		__( 'Protección Datos RGPD', 'proteccion-datos-rgpd' ) . $notificacion_globo, // Menu title.
		'administrator',                                                              // Capability.
		'proteccion-datos-rgpd',                                                      // Menu slug.
		'pdrgpd_admin',                                                               // Function.
		'data:image/svg+xml;base64,' . base64_encode( '<svg viewBox="0 0 488.85 488.85" xmlns="http://www.w3.org/2000/svg"><path fill="#a7aaad" d="M244.425,98.725c-93.4,0-178.1,51.1-240.6,134.1c-5.1,6.8-5.1,16.3,0,23.1c62.5,83.1,147.2,134.2,240.6,134.2   s178.1-51.1,240.6-134.1c5.1-6.8,5.1-16.3,0-23.1C422.525,149.825,337.825,98.725,244.425,98.725z M251.125,347.025   c-62,3.9-113.2-47.2-109.3-109.3c3.2-51.2,44.7-92.7,95.9-95.9c62-3.9,113.2,47.2,109.3,109.3   C343.725,302.225,302.225,343.725,251.125,347.025z M248.025,299.625c-33.4,2.1-61-25.4-58.8-58.8c1.7-27.6,24.1-49.9,51.7-51.7   c33.4-2.1,61,25.4,58.8,58.8C297.925,275.625,275.525,297.925,248.025,299.625z"/></svg>' )         // Icon. Parameters width="20" height="20" are optional for svg tag. Parameter fill is required for colouring in the path tag.
	);
}

// Settings page function.
function pdrgpd_admin() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( 'No tienes suficientes permisos para acceder a esta página.' );
	}
	?>
	<div class="wrap">
		<h1><img src="<?php echo plugin_dir_url( __FILE__ ); ?>images/proteccion-datos-rgpd-32x32.png" width=32 height=32 alt="Protección Datos - RGPD" /> <?php echo __( 'Protección Datos - RGPD Settings', 'proteccion-datos-rgpd' ) . ' <small>v' . pdrgpd_get_version(); ?></small></h1>
		<?php settings_errors(); ?>
		<form method="POST" action="options.php">
			<?php
				settings_fields( 'proteccion-datos-rgpd-ajustes' );
				do_settings_sections( 'proteccion-datos-rgpd-ajustes' );
				submit_button();
			?>
		</form>
	</div>
	<?php
}

// Settings page functionality.

add_action( 'admin_init', 'pdrgpd_settings_init' );
function pdrgpd_settings_init() {

	// Registers all the option values defined.
	foreach ( pdrgpd_lista_opciones() as $nombre_opcion ) {
		register_setting( 'proteccion-datos-rgpd-ajustes', $nombre_opcion );
	}

	// Crea las páginas legales si se ha solicitado.
	if ( isset( $_POST['pdrgpd_crear_paginas_legales'] ) ) {
		pdrgpd_cear_paginas_legales();
	}

	// Owner and liable data / Datos del titular y responsable.

	add_settings_section(
		'pdrgpd_seccion_titular',                                         // $id (string) (Required) Slug-name to identify the section. Used in the 'id' attribute of tags.
		__( 'Site owner and responsible data', 'proteccion-datos-rgpd' ), // $title (string) (Required) Formatted title of the section. Shown as the heading for the section.
		'pdrgpd_seccion_titular_callback',                                // $callback (callable) (Required) Function that echos out any content at the top of the section (between heading and fields).
		'proteccion-datos-rgpd-ajustes'                                   // $page (string) (Required) The slug-name of the settings page on which to show the section. Built-in pages include 'general', 'reading', 'writing', 'discussion', 'media', etc. Create your own using add_options_page();
	);

	add_settings_field(
		'pdrgpd_titular',                                        // $id (string) (Required) Slug-name to identify the field. Used in the 'id' attribute of tags.
		__( 'Name or corporate name', 'proteccion-datos-rgpd' ), // $title (string) (Required) Formatted title of the field. Shown as the label for the field during output.
		'pdrgpd_titular_callback',                               // $callback (callable) (Required) Function that fills the field with the desired form inputs. The function should echo its output.
		'proteccion-datos-rgpd-ajustes',                         // $page (string) (Required) The slug-name of the settings page on which to show the section (general, reading, writing, ...).
		'pdrgpd_seccion_titular',                                // $section (string) (Optional) The slug-name of the section of the settings page in which to show the box.
		array()                                                  // $args (array) (Optional) Extra arguments used when outputting the field.
	);

	add_settings_field(
		'pdrgpd_nif',
		__( 'DNI/NIE/CIF', 'proteccion-datos-rgpd' ),
		'pdrgpd_nif_callback',
		'proteccion-datos-rgpd-ajustes',
		'pdrgpd_seccion_titular'
	);

	add_settings_field(
		'pdrgpd_vies',
		__( 'VIES', 'proteccion-datos-rgpd' ),
		'pdrgpd_vies_callback',
		'proteccion-datos-rgpd-ajustes',
		'pdrgpd_seccion_titular'
	);

	add_settings_field(
		'pdrgpd_direccion',
		__( 'Address', 'proteccion-datos-rgpd' ),
		'pdrgpd_direccion_callback',
		'proteccion-datos-rgpd-ajustes',
		'pdrgpd_seccion_titular'
	);

	add_settings_field(
		'pdrgpd_poblacion',
		__( 'Town', 'proteccion-datos-rgpd' ),
		'pdrgpd_poblacion_callback',
		'proteccion-datos-rgpd-ajustes',
		'pdrgpd_seccion_titular'
	);

	add_settings_field(
		'pdrgpd_cp',
		__( 'ZIP code', 'proteccion-datos-rgpd' ),
		'pdrgpd_cp_callback',
		'proteccion-datos-rgpd-ajustes',
		'pdrgpd_seccion_titular'
	);

	add_settings_field(
		'pdrgpd_provincia',
		__( 'Province/State', 'proteccion-datos-rgpd' ),
		'pdrgpd_provincia_callback',
		'proteccion-datos-rgpd-ajustes',
		'pdrgpd_seccion_titular'
	);

	add_settings_field(
		'pdrgpd_telefono',
		__( 'Phone number', 'proteccion-datos-rgpd' ),
		'pdrgpd_telefono_callback',
		'proteccion-datos-rgpd-ajustes',
		'pdrgpd_seccion_titular'
	);

	add_settings_field(
		'pdrgpd_email',
		__( 'E-mail', 'proteccion-datos-rgpd' ),
		'pdrgpd_email_callback',
		'proteccion-datos-rgpd-ajustes',
		'pdrgpd_seccion_titular'
	);

	// Inscripción en el registro mercantil.

	add_settings_section(
		'pdrgpd_seccion_rmercant',
		__( 'Commercial registry registration', 'proteccion-datos-rgpd' ),
		'pdrgpd_seccion_rmercant_callback',
		'proteccion-datos-rgpd-ajustes'
	);

	add_settings_field(
		'pdrgpd_rmercant_poblacion',
		__( 'Town', 'proteccion-datos-rgpd' ),
		'pdrgpd_rmercant_poblacion_callback',
		'proteccion-datos-rgpd-ajustes',
		'pdrgpd_seccion_rmercant'
	);

	add_settings_field(
		'pdrgpd_rmercant_provincia',
		__( 'Province/State', 'proteccion-datos-rgpd' ),
		'pdrgpd_rmercant_provincia_callback',
		'proteccion-datos-rgpd-ajustes',
		'pdrgpd_seccion_rmercant'
	);

	add_settings_field(
		'pdrgpd_rmercant_fecha',
		ucfirst( __( 'date', 'proteccion-datos-rgpd' ) ),
		'pdrgpd_rmercant_fecha_callback',
		'proteccion-datos-rgpd-ajustes',
		'pdrgpd_seccion_rmercant'
	);

	add_settings_field(
		'pdrgpd_rmercant_presentacion',
		ucfirst( __( 'presentation', 'proteccion-datos-rgpd' ) ),
		'pdrgpd_rmercant_presentacion_callback',
		'proteccion-datos-rgpd-ajustes',
		'pdrgpd_seccion_rmercant'
	);

	add_settings_field(
		'pdrgpd_rmercant_seccion',
		ucfirst( __( 'section', 'proteccion-datos-rgpd' ) ),
		'pdrgpd_rmercant_seccion_callback',
		'proteccion-datos-rgpd-ajustes',
		'pdrgpd_seccion_rmercant'
	);

	add_settings_field(
		'pdrgpd_rmercant_libro',
		ucfirst( __( 'book', 'proteccion-datos-rgpd' ) ),
		'pdrgpd_rmercant_libro_callback',
		'proteccion-datos-rgpd-ajustes',
		'pdrgpd_seccion_rmercant'
	);

	add_settings_field(
		'pdrgpd_rmercant_tomo',
		ucfirst( __( 'volume', 'proteccion-datos-rgpd' ) ),
		'pdrgpd_rmercant_tomo_callback',
		'proteccion-datos-rgpd-ajustes',
		'pdrgpd_seccion_rmercant'
	);

	add_settings_field(
		'pdrgpd_rmercant_folio',
		ucfirst( __( 'page', 'proteccion-datos-rgpd' ) ),
		'pdrgpd_rmercant_folio_callback',
		'proteccion-datos-rgpd-ajustes',
		'pdrgpd_seccion_rmercant'
	);

	add_settings_field(
		'pdrgpd_rmercant_hoja',
		ucfirst( __( 'sheet', 'proteccion-datos-rgpd' ) ),
		'pdrgpd_rmercant_hoja_callback',
		'proteccion-datos-rgpd-ajustes',
		'pdrgpd_seccion_rmercant'
	);

	add_settings_field(
		'pdrgpd_rmercant_protocolo',
		ucfirst( __( 'protocol', 'proteccion-datos-rgpd' ) ),
		'pdrgpd_rmercant_protocolo_callback',
		'proteccion-datos-rgpd-ajustes',
		'pdrgpd_seccion_rmercant'
	);

	add_settings_field(
		'pdrgpd_rmercant_num',
		ucfirst( __( 'number', 'proteccion-datos-rgpd' ) ),
		'pdrgpd_rmercant_num_callback',
		'proteccion-datos-rgpd-ajustes',
		'pdrgpd_seccion_rmercant'
	);

	// Datos del sitio.

	add_settings_section(
		'pdrgpd_seccion_sitio',
		__( 'Site data', 'proteccion-datos-rgpd' ),
		'pdrgpd_seccion_sitio_callback',
		'proteccion-datos-rgpd-ajustes'
	);

	add_settings_field(
		'pdrgpd_version',
		__( 'Plugin version', 'proteccion-datos-rgpd' ),
		'pdrgpd_version_callback',
		'proteccion-datos-rgpd-ajustes',
		'pdrgpd_seccion_sitio',
		array( 'class' => 'hidden' )
	);

	add_settings_field(
		'pdrgpd_sitio',
		__( 'Site name', 'proteccion-datos-rgpd' ),
		'pdrgpd_sitio_callback',
		'proteccion-datos-rgpd-ajustes',
		'pdrgpd_seccion_sitio'
	);

	add_settings_field(
		'pdrgpd_dominio',
		__( 'Site domain', 'proteccion-datos-rgpd' ),
		'pdrgpd_dominio_callback',
		'proteccion-datos-rgpd-ajustes',
		'pdrgpd_seccion_sitio'
	);

	// Checkbox pdrgpd_crear_paginas_legales si alguna no existe.
	if ( pdrgpd_faltan_paginas_legales() ) {
		add_settings_field(
			'pdrgpd_oferta_paginas_legales',
			__( 'Create legal pages', 'proteccion-datos-rgpd' ),
			'pdrgpd_crear_paginas_legales_callback',
			'proteccion-datos-rgpd-ajustes',
			'pdrgpd_seccion_sitio'
		);
	}

	add_settings_field(
		'pdrgpd_uri_aviso',
		__( 'URL address of the legal announcement', 'proteccion-datos-rgpd' ),
		'pdrgpd_uri_aviso_callback',
		'proteccion-datos-rgpd-ajustes',
		'pdrgpd_seccion_sitio'
	);

	add_settings_field(
		'pdrgpd_uri_privacidad',
		__( 'URL address of the privacy policy', 'proteccion-datos-rgpd' ),
		'pdrgpd_uri_privacidad_callback',
		'proteccion-datos-rgpd-ajustes',
		'pdrgpd_seccion_sitio'
	);

	add_settings_field(
		'pdrgpd_uri_cookies',
		__( 'URL address of the cookie policy', 'proteccion-datos-rgpd' ),
		'pdrgpd_uri_cookies_callback',
		'proteccion-datos-rgpd-ajustes',
		'pdrgpd_seccion_sitio'
	);

	// Privacy policy / Política de privacidad.

	add_settings_section(
		'pdrgpd_seccion_privacidad',
		__( 'Privacy policy', 'proteccion-datos-rgpd' ),
		'pdrgpd_seccion_privacidad_callback',
		'proteccion-datos-rgpd-ajustes'
	);

	add_settings_field(
		'pdrgpd_existencia_formulario_contacto',
		__( 'Contact form', 'proteccion-datos-rgpd' ),
		'pdrgpd_existencia_formulario_contacto_callback',
		'proteccion-datos-rgpd-ajustes',
		'pdrgpd_seccion_privacidad'
	);
	add_settings_field(
		'pdrgpd_finalidad_formulario_contacto_mini',
		__( 'Contact form purpose summary', 'proteccion-datos-rgpd' ),
		'pdrgpd_finalidad_formulario_contacto_mini_callback',
		'proteccion-datos-rgpd-ajustes',
		'pdrgpd_seccion_privacidad'
	);
	add_settings_field(
		'pdrgpd_finalidad_formulario_contacto',
		__( 'Contact form purpose', 'proteccion-datos-rgpd' ),
		'pdrgpd_finalidad_formulario_contacto_callback',
		'proteccion-datos-rgpd-ajustes',
		'pdrgpd_seccion_privacidad'
	);
	add_settings_field(
		'pdrgpd_akismet_formulario_contacto',
		__( 'Contact form Akismet filtered', 'proteccion-datos-rgpd' ),
		'pdrgpd_akismet_formulario_contacto_callback',
		'proteccion-datos-rgpd-ajustes',
		'pdrgpd_seccion_privacidad'
	);

	add_settings_field(
		'pdrgpd_existencia_boletin',
		__( 'Newsletter', 'proteccion-datos-rgpd' ),
		'pdrgpd_existencia_boletin_callback',
		'proteccion-datos-rgpd-ajustes',
		'pdrgpd_seccion_privacidad'
	);
	add_settings_field(
		'pdrgpd_finalidad_suscripcion_boletin_mini',
		__( 'Subscription purpose summary', 'proteccion-datos-rgpd' ),
		'pdrgpd_finalidad_suscripcion_boletin_mini_callback',
		'proteccion-datos-rgpd-ajustes',
		'pdrgpd_seccion_privacidad'
	);
	add_settings_field(
		'pdrgpd_finalidad_suscripcion_boletin',
		__( 'Subscription purpose', 'proteccion-datos-rgpd' ),
		'pdrgpd_finalidad_suscripcion_boletin_callback',
		'proteccion-datos-rgpd-ajustes',
		'pdrgpd_seccion_privacidad'
	);

	add_settings_field(
		'pdrgpd_aplicar_formulario_comentar',
		__( 'Comment form', 'proteccion-datos-rgpd' ),
		'pdrgpd_aplicar_formulario_comentar_callback',
		'proteccion-datos-rgpd-ajustes',
		'pdrgpd_seccion_privacidad'
	);
	add_settings_field(
		'pdrgpd_finalidad_formulario_comentar_mini',
		__( 'Comment form purpose summary', 'proteccion-datos-rgpd' ),
		'pdrgpd_finalidad_formulario_comentar_mini_callback',
		'proteccion-datos-rgpd-ajustes',
		'pdrgpd_seccion_privacidad'
	);
	add_settings_field(
		'pdrgpd_finalidad_formulario_comentar',
		__( 'Comment form purpose', 'proteccion-datos-rgpd' ),
		'pdrgpd_finalidad_formulario_comentar_callback',
		'proteccion-datos-rgpd-ajustes',
		'pdrgpd_seccion_privacidad'
	);

	add_settings_field(
		'pdrgpd_existencia_suscripcion_jetpack',
		__( 'Jetpack suscription form', 'proteccion-datos-rgpd' ),
		'pdrgpd_existencia_suscripcion_jetpack_callback',
		'proteccion-datos-rgpd-ajustes',
		'pdrgpd_seccion_privacidad'
	);

	// Aspecto.

	add_settings_section(
		'pdrgpd_seccion_aspecto',
		__( 'Appearance', 'proteccion-datos-rgpd' ),
		'pdrgpd_seccion_aspecto_callback',
		'proteccion-datos-rgpd-ajustes'
	);
	add_settings_field(
		'pdrgpd_formato_primera_capa',
		__( 'Information due first layer appearance', 'proteccion-datos-rgpd' ),
		'pdrgpd_formato_primera_capa_callback',
		'proteccion-datos-rgpd-ajustes',
		'pdrgpd_seccion_aspecto'
	);

	// Carga de cookies y aviso del banner.
	add_settings_section(
		'pdrgpd_seccion_cookies',
		__( 'Cookies insertion', 'proteccion-datos-rgpd' ),
		'pdrgpd_seccion_cookies_callback',
		'proteccion-datos-rgpd-ajustes'
	);

	add_settings_field(
		'pdrgpd_google_analytics_id',
		__( 'Google Analytics Measurement ID', 'proteccion-datos-rgpd' ),
		'pdrgpd_google_analytics_id_callback',
		'proteccion-datos-rgpd-ajustes',
		'pdrgpd_seccion_cookies'
	);

	add_settings_field(
		'pdrgpd_facebook_pixel_id',
		__( 'Facebook Pixel ID', 'proteccion-datos-rgpd' ),
		'pdrgpd_facebook_pixel_id_callback',
		'proteccion-datos-rgpd-ajustes',
		'pdrgpd_seccion_cookies'
	);

	// Page footer / Pie de página.

	add_settings_section(
		'pdrgpd_seccion_pie',
		__( 'Page footer', 'proteccion-datos-rgpd' ),
		'pdrgpd_seccion_pie_callback',
		'proteccion-datos-rgpd-ajustes'
	);

	add_settings_field(
		'pdrgpd_pie_enlace_legal',
		__( 'Legal notice link', 'proteccion-datos-rgpd' ),
		'pdrgpd_pie_enlace_legal_callback',
		'proteccion-datos-rgpd-ajustes',
		'pdrgpd_seccion_pie'
	);

	add_settings_field(
		'pdrgpd_pie_enlace_privacidad',
		__( 'Privacy policy link', 'proteccion-datos-rgpd' ),
		'pdrgpd_pie_enlace_privacidad_callback',
		'proteccion-datos-rgpd-ajustes',
		'pdrgpd_seccion_pie'
	);

	add_settings_field(
		'pdrgpd_pie_enlace_cookies',
		__( 'Cookie policy link', 'proteccion-datos-rgpd' ),
		'pdrgpd_pie_enlace_cookies_callback',
		'proteccion-datos-rgpd-ajustes',
		'pdrgpd_seccion_pie'
	);

	add_settings_field(
		'pdrgpd_pie_copyright',
		__( 'Copyright notice from year', 'proteccion-datos-rgpd' ),
		'pdrgpd_pie_copyright_callback',
		'proteccion-datos-rgpd-ajustes',
		'pdrgpd_seccion_pie'
	);

	add_settings_field(
		'pdrgpd_pie_multilinea',
		__( 'Multiline footer', 'proteccion-datos-rgpd' ),
		'pdrgpd_pie_multilinea_callback',
		'proteccion-datos-rgpd-ajustes',
		'pdrgpd_seccion_pie'
	);
}

/*
 * Callbacks to show options data.
 * Callbacks para la presentación de datos de opciones.
 */

function pdrgpd_seccion_titular_callback() {
	echo __( 'General data required to fulfill legal notice according law 34/2002, of July 11, on information society services and electronic commerce (LSSICE) and others.<br />Fill appropriate fields.', 'proteccion-datos-rgpd' );
}

/** Hidden field to save version number too. */
function pdrgpd_version_callback() {
	echo '<input name="pdrgpd_version" type="hidden" id="pdrgpd_version" value="' . esc_attr( pdrgpd_get_version() ) . '" />';
}

function pdrgpd_titular_callback() {
	echo '<input name="pdrgpd_titular" type="text" id="pdrgpd_titular" value="' . esc_attr( pdrgpd_conf_titular() ) . '" class="regular-text" />';
	echo '<p class="description" id="tagline-description">También se le considerará  responsable de protección de datos.</p>';
}

function pdrgpd_nif_callback() {
	echo '<input name="pdrgpd_nif" type="text" id="pdrgpd_nif" value="' . esc_attr( pdrgpd_conf_nif() ) . '" class="regular-text" />';
	echo '<p class="description" id="tagline-description">Número o código del documento identificativo.</p>';
}

function pdrgpd_vies_callback() {
	echo "<input type='checkbox' name='pdrgpd_vies' ";
	checked( get_option( 'pdrgpd_vies' ), 1 );
	echo " value='1'> ";
	echo __( 'VIES registered', 'proteccion-datos-rgpd' );
	echo '<p class="description" id="tagline-description">';
	echo __( 'Select if registered in the', 'proteccion-datos-rgpd' );
	echo ' ';
	echo pdrgpd_enlace_nueva_ventana( 'https://www2.agenciatributaria.gob.es/viescoes.html', __( 'intra-comunnity operators regisry', 'proteccion-datos-rgpd' ) );
	echo '.</p>';
}

function pdrgpd_direccion_callback() {
	echo '<input name="pdrgpd_direccion" type="text" id="pdrgpd_direccion" value="' . esc_attr( pdrgpd_conf_direccion() ) . '" class="regular-text" />';
	echo '<p class="description" id="tagline-description">Dirección postal (Calle, número, piso, etc.) del titular del sitio.</p>';
}

function pdrgpd_poblacion_callback() {
	echo '<input name="pdrgpd_poblacion" type="text" id="pdrgpd_poblacion" value="' . esc_attr( pdrgpd_conf_poblacion() ) . '" class="regular-text" />';
}

function pdrgpd_cp_callback() {
	echo '<input name="pdrgpd_cp" type="text" id="pdrgpd_cp" value="' . esc_attr( pdrgpd_conf_cp() ) . '" class="regular-text" />';
}

function pdrgpd_provincia_callback() {
	echo '<input name="pdrgpd_provincia" type="text" id="pdrgpd_provincia" value="' . esc_attr( pdrgpd_conf_provincia() ) . '" class="regular-text" />';
	echo '<p class="description" id="tagline-description">Requerida para completar el apartado Jurisdicción.</p>';
}

function pdrgpd_telefono_callback() {
	echo '<input name="pdrgpd_telefono" type="text" id="pdrgpd_telefono" value="' . esc_attr( pdrgpd_conf_telefono() ) . '" class="regular-text" />';
	echo '<p class="description" id="tagline-description">Opcional, para utilizar junto a otros datos de contacto del titular en el aviso legal.<br />';
	echo 'Agrega el prefijo internacional precedido por un símbolo + para obtener un enlace pulsable en dispositivos móviles.</p>';
}

function pdrgpd_email_callback() {
	echo '<input name="pdrgpd_email" type="text" id="pdrgpd_email" value="' . esc_attr( pdrgpd_conf_email() ) . '" class="regular-text" />';
}

function pdrgpd_seccion_rmercant_callback() {
	echo __( 'Only for corporations', 'proteccion-datos-rgpd' ) . '. ';
	echo __( 'LSSICE requirement', 'proteccion-datos-rgpd' ) . '. ';
}

function pdrgpd_rmercant_poblacion_callback() {
	echo '<input name="pdrgpd_rmercant_poblacion" type="text" id="pdrgpd_rmercant_poblacion" value="' . esc_attr( pdrgpd_conf_rmercant_poblacion() ) . '" class="regular-text" />';
}

function pdrgpd_rmercant_provincia_callback() {
	echo '<input name="pdrgpd_rmercant_provincia" type="text" id="pdrgpd_rmercant_provincia" value="' . esc_attr( pdrgpd_conf_rmercant_provincia() ) . '" class="regular-text" />';
}

function pdrgpd_rmercant_fecha_callback() {
	echo '<input name="pdrgpd_rmercant_fecha" type="text" id="pdrgpd_rmercant_fecha" value="' . esc_attr( pdrgpd_conf_rmercant_fecha() ) . '" class="regular-text" />';
}

function pdrgpd_rmercant_presentacion_callback() {
	echo '<input name="pdrgpd_rmercant_presentacion" type="text" id="pdrgpd_rmercant_presentacion" value="' . esc_attr( pdrgpd_conf_rmercant_presentacion() ) . '" class="regular-text" />';
}

function pdrgpd_rmercant_seccion_callback() {
	echo '<input name="pdrgpd_rmercant_seccion" type="text" id="pdrgpd_rmercant_seccion" value="' . esc_attr( pdrgpd_conf_rmercant_seccion() ) . '" class="regular-text" />';
}

function pdrgpd_rmercant_libro_callback() {
	echo '<input name="pdrgpd_rmercant_libro" type="text" id="pdrgpd_rmercant_libro" value="' . esc_attr( pdrgpd_conf_rmercant_libro() ) . '" class="regular-text" />';
}

function pdrgpd_rmercant_tomo_callback() {
	echo '<input name="pdrgpd_rmercant_tomo" type="text" id="pdrgpd_rmercant_tomo" value="' . esc_attr( pdrgpd_conf_rmercant_tomo() ) . '" class="regular-text" />';
}

function pdrgpd_rmercant_folio_callback() {
	echo '<input name="pdrgpd_rmercant_folio" type="text" id="pdrgpd_rmercant_folio" value="' . esc_attr( pdrgpd_conf_rmercant_folio() ) . '" class="regular-text" />';
}

function pdrgpd_rmercant_hoja_callback() {
	echo '<input name="pdrgpd_rmercant_hoja" type="text" id="pdrgpd_rmercant_hoja" value="' . esc_attr( pdrgpd_conf_rmercant_hoja() ) . '" class="regular-text" />';
}

function pdrgpd_rmercant_protocolo_callback() {
	echo '<input name="pdrgpd_rmercant_protocolo" type="text" id="pdrgpd_rmercant_protocolo" value="' . esc_attr( pdrgpd_conf_rmercant_protocolo() ) . '" class="regular-text" />';
}

function pdrgpd_rmercant_num_callback() {
	echo '<input name="pdrgpd_rmercant_num" type="text" id="pdrgpd_rmercant_num" value="' . esc_attr( pdrgpd_conf_rmercant_num() ) . '" class="regular-text" />';
}

function pdrgpd_seccion_sitio_callback() {
	echo __( 'Site build data.', 'proteccion-datos-rgpd' );
}

function pdrgpd_sitio_callback() {
	echo '<input name="pdrgpd_sitio" type="text" id="pdrgpd_sitio" value="' . esc_attr( pdrgpd_conf_sitio() ) . '" class="regular-text" />';
}

function pdrgpd_dominio_callback() {
	echo '<input name="pdrgpd_dominio" type="text" id="pdrgpd_dominio" value="' . esc_attr( pdrgpd_conf_dominio() ) . '" class="regular-text" />';
}

/** Ofrece crear las páginas legales si faltan */
function pdrgpd_crear_paginas_legales_callback() {
	pdrgpd_ofrece_paginas_legales();
}

function pdrgpd_uri_aviso_callback() {
	echo '<input name="pdrgpd_uri_aviso" type="text" id="pdrgpd_uri_aviso" value="' . esc_attr( pdrgpd_conf_uri_aviso() ) . '" class="regular-text" />';
	echo '<p class="description" id="tagline-description">Dirección donde se ubica o ubicará el aviso legal.<br /><br />';
	pdrgpd_advertencia_pagina_legal( pdrgpd_conf_uri_aviso(), 'pdrgpd-aviso-legal' );
	echo '</p>';
}

function pdrgpd_uri_privacidad_callback() {
	echo '<input name="pdrgpd_uri_privacidad" type="text" id="pdrgpd_uri_privacidad" value="' . esc_attr( pdrgpd_conf_uri_privacidad() ) . '" class="regular-text" />';
	echo '<p class="description" id="tagline-description">Dirección donde se ubica o ubicará la política de privacidad acorde al RGPD.<br />';
	pdrgpd_advertencia_pagina_legal( pdrgpd_conf_uri_privacidad(), 'pdrgpd-politica-privacidad' );
	echo '</p>';
}

function pdrgpd_uri_cookies_callback() {
	echo '<input name="pdrgpd_uri_cookies" type="text" id="pdrgpd_uri_cookies" value="' . esc_attr( pdrgpd_conf_uri_cookies() ) . '" class="regular-text" />';
	echo '<p class="description" id="tagline-description">Dirección donde se ubica o ubicará la política de cookies.<br />';
	pdrgpd_advertencia_pagina_legal( pdrgpd_conf_uri_cookies(), 'pdrgpd-politica-cookies' );
	echo '</p>';
}

function pdrgpd_seccion_privacidad_callback() {
	echo __( 'Specific data to follow up privacy policy agreeable to General Data Protection Regulation (GDPR).<br />Fill appropriate fields.', 'proteccion-datos-rgpd' );
	echo '<p class="description" id="tagline-description">La ley obliga a que todos los formularios que recojan datos personales muestren información resumida sobre su uso.</p>';
}

function pdrgpd_seccion_aspecto_callback() {
	echo __( 'Optional settings for data displaying.', 'proteccion-datos-rgpd' );
}

function pdrgpd_formato_primera_capa_callback() {
	echo '<input type="radio" name="pdrgpd_formato_primera_capa" value="tabla" ' . checked( 'tabla', pdrgpd_conf_formato_primera_capa(), false ) . '>' . __( 'Table', 'proteccion-datos-rgpd' );
	echo ' <span class="description" id="tagline-description">' . __( '(AEPD recommendation).', 'proteccion-datos-rgpd' ) . '</span><br />';
	echo '<input type="radio" name="pdrgpd_formato_primera_capa" value="parrafo" ' . checked( 'parrafo', pdrgpd_conf_formato_primera_capa(), false ) . '>' . __( 'Paragraph', 'proteccion-datos-rgpd' );
}

/** Contact form / Formulario de contacto- */
function pdrgpd_existencia_formulario_contacto_callback() {
	echo "<input type='checkbox' name='pdrgpd_existencia_formulario_contacto' ";
	checked( get_option( 'pdrgpd_existencia_formulario_contacto' ), 1 );
	echo " value='1'> ";
	echo __( 'Contact form exists', 'proteccion-datos-rgpd' );
	echo '<p class="description" id="tagline-description">Marca la casilla si en tu web hay un formulario de contacto<br />';
	echo 'Para cumplir con la ley, agrega al formulario una casilla que fuerce a aceptar tu política de privacidad y la etiqueta <b>[pdrgpd-aviso-formulario-contacto]</b> Consulta las ';
	echo pdrgpd_enlace_nueva_ventana( 'https://es.wordpress.org/plugins/proteccion-datos-rgpd/#faq-header', 'preguntas frecuentes' );
	echo ' para más información.</p>';
}

function pdrgpd_finalidad_formulario_contacto_mini_callback() {
	echo '<input name="pdrgpd_finalidad_formulario_contacto_mini" type="text" id="pdrgpd_finalidad_formulario_contacto_mini" value="' . esc_attr( pdrgpd_conf_finalidad_formulario_contacto_mini() ) . '" class="regular-text" />';
	echo '<p class="description" id="tagline-description">Texto que se incluirá en el formulario de contacto, modifícalo si el resultado no es de tu agrado.</p>';
}

function pdrgpd_finalidad_formulario_contacto_callback() {
	echo '<textarea cols="50" rows="5" name="pdrgpd_finalidad_formulario_contacto">' . esc_html( pdrgpd_conf_finalidad_formulario_contacto() ) . '</textarea>';
	echo '<p class="description" id="tagline-description">Opcionalmente, un mayor detalle de la finalidad del formulario de contacto para mostrar en la política de privacidad.</p>';
}

function pdrgpd_akismet_formulario_contacto_callback() {
	echo "<input type='checkbox' name='pdrgpd_akismet_formulario_contacto' ";
	checked( get_option( 'pdrgpd_akismet_formulario_contacto' ), 1 );
	echo " value='1'> ";
	echo __( 'Akismet filtered contact form', 'proteccion-datos-rgpd' );
	echo '<p class="description" id="tagline-description">Marca la casilla si el formulario de contacto se filtra mediante Akismet.</p>';
}

/** Newsletter / Boletín */
function pdrgpd_existencia_boletin_callback() {
	echo "<input type='checkbox' name='pdrgpd_existencia_boletin' ";
	checked( get_option( 'pdrgpd_existencia_boletin' ), 1 );
	echo " value='1'> ";
	echo __( 'Newsletter subscription form exists', 'proteccion-datos-rgpd' );
	echo '<p class="description" id="tagline-description">Marca la casilla si en tu web hay un formulario de suscripción a boletines/newsletters.<br />';
	echo 'Para cumplir con la ley, agrega al formulario una casilla que fuerce a aceptar tu política de privacidad y la etiqueta <b>[pdrgpd-aviso-boletin]</b> Consulta las ';
	echo pdrgpd_enlace_nueva_ventana( 'https://es.wordpress.org/plugins/proteccion-datos-rgpd/#faq-header', 'preguntas frecuentes' );
	echo ' para más información.</p>';
}

function pdrgpd_finalidad_suscripcion_boletin_mini_callback() {
	echo '<input name="pdrgpd_finalidad_suscripcion_boletin_mini" type="text" id="pdrgpd_finalidad_suscripcion_boletin_mini" value="' . esc_attr( pdrgpd_conf_finalidad_suscripcion_boletin_mini() ) . '" class="regular-text" />';
	echo '<p class="description" id="tagline-description">Texto que se incluirá en el formulario de suscripción a boletines/newsletters, modifícalo si el resultado no es de tu agrado.</p>';
}

function pdrgpd_finalidad_suscripcion_boletin_callback() {
	echo '<textarea cols="50" rows="5" name="pdrgpd_finalidad_suscripcion_boletin">' . esc_html( pdrgpd_conf_finalidad_suscripcion_boletin() ) . '</textarea>';
	echo '<p class="description" id="tagline-description">Opcionalmente, un mayor detalle de la finalidad del formulario de suscripción al boletín/newsletter para mostrar en la política de privacidad.</p>';
}

/** Formulario de comentar / // Comment form. */
function pdrgpd_aplicar_formulario_comentar_callback() {
	echo "<input type='checkbox' name='pdrgpd_aplicar_formulario_comentar' ";
	checked( get_option( 'pdrgpd_aplicar_formulario_comentar' ), 1 );
	echo " value='1'> ";
	echo __( 'Automatically apply GDPR to comment form.', 'proteccion-datos-rgpd' );
	if ( pdrgpd_existe_akismet() ) {
		echo '<p class="description" id="tagline-description">Se contempla automáticamente la existencia de Akismet filtrando el spam en comentarios.</p>';
		if ( get_option( 'akismet_comment_form_privacy_notice' ) === 'display' ) {
			echo '<p class="description" id="tagline-description">Puedes <a href="admin.php?page=akismet-key-config">deshabiltar el aviso de Akismet</a>, es redundante si activas esta opción.</p>';
		}
	}
	if ( pdrgpd_modulo_jetpack_comentarios_activo() ) {
		echo '<p class="description" id="tagline-description">';
		if ( get_option( 'pdrgpd_aplicar_formulario_comentar' ) ) {
			echo AVISO_ROJO;
		}
		echo 'Esta funcionalidad es incompatible con la opción de identificación mediante redes sociales de Jetpack que se configura o desactiva en su caso en <a href="' . admin_url( 'admin.php?page=jetpack#discussion' ) . '">Jetpack -> Ajustes -> Debate</a> -> Comentarios -> Permite a los lectores usar cuentas de WordPress.com, Twitter, Facebook o Google+ para comentar.</p>';
	}
}

function pdrgpd_finalidad_formulario_comentar_mini_callback() {
	echo '<input name="pdrgpd_finalidad_formulario_comentar_mini" type="text" id="pdrgpd_finalidad_formulario_comentar_mini" value="' . esc_attr( pdrgpd_conf_finalidad_formulario_comentar_mini() ) . '" class="regular-text" />';
	echo '<p class="description" id="tagline-description">Texto que se incluirá en el formulario de comentar, modifícalo si el resultado no es de tu agrado.</p>';
}

function pdrgpd_finalidad_formulario_comentar_callback() {
	echo '<textarea cols="50" rows="5" name="pdrgpd_finalidad_formulario_comentar">' . esc_html( pdrgpd_conf_finalidad_formulario_comentar() ) . '</textarea>';
	echo '<p class="description" id="tagline-description">Opcionalmente, un mayor detalle de la finalidad del formulario de comentar para mostrar en la política de privacidad.</p>';
}

function pdrgpd_existencia_suscripcion_jetpack_callback() {
	if ( class_exists( 'Jetpack' ) ) {
		if ( pdrgpd_modulo_jetpack_suscripciones_activo() ) {
			echo "<input type='checkbox' name='pdrgpd_existencia_suscripcion_jetpack' ";
			checked( get_option( 'pdrgpd_existencia_suscripcion_jetpack' ), 1 );
			echo " value='1'> ";
			echo __( 'Jetpack subscription form exists', 'proteccion-datos-rgpd' );
			echo '<p class="description" id="tagline-description">Marca la casilla si en tu web hay un formulario de suscripción a nuevas entradas mediante Jetpack.<br />';
			echo 'Para cumplir con la ley, en lugar de emplear el widget "Suscripciones al blog..." de Jetpack, utiliza un widget "HTML`personalizado" conteniendo la etiqueta <b>[pdrgpd_jetpack_suscripcion]</b> que lo amplía.</p>';
		} else {
			echo 'No están habilitadas las suscripciones en Jetpack que se configuran o desactiva en su caso en <a href="' . admin_url( 'admin.php?page=jetpack#discussion' ) . '">Jetpack -> Ajustes -> Debate</a> -> Suscripciones -> Permite a los usuarios suscribirse a tus entradas y comentarios y que reciban notificaciones a través de correo electrónico.<br />';
		}
	} else {
		echo 'No se dispone de Jetpack<br />';
	}
}

/** Inserciones. */
function pdrgpd_seccion_cookies_callback() {
	echo __( 'You must require permission to load non mandatory cookies.', 'proteccion-datos-rgpd' );
	echo ' ';
	echo sprintf( __( 'If your site uses cookies, we suggest using the %1$s%2$s%3$s plugin to create the banner and control the loading of cookies.', 'proteccion-datos-rgpd' ), '<em><a href="' . __( 'https://wordpress.org/plugins/cookies-and-content-security-policy/', 'proteccion-datos-rgpd' ) . '" target="_blank">', 'Cookies and Content Security Policy', '</a></em>' );
	echo '<br />';
}

function pdrgpd_google_analytics_id_callback() {
	echo '<input name="pdrgpd_google_analytics_id" type="text" id="pdrgpd_google_analytics_id" value="' . esc_attr( pdrgpd_conf_google_analytics_id() ) . '" class="regular-text" />';
	echo '<p class="description" id="tagline-description">' . __( 'Insert', 'proteccion-datos-rgpd' ) . ' <a href="https://analytics.google.com/" target="_blank">Google Analytics</a> ' . __( 'and', 'proteccion-datos-rgpd' ) . ' <a href="https://ads.google.com/" target="_blank">Ads</a> ' . __( 'Tracking Code with this Measurement ID', 'proteccion-datos-rgpd' ) . '.</p>';
}

function pdrgpd_facebook_pixel_id_callback() {
	echo '<input name="pdrgpd_facebook_pixel_id" type="text" id="pdrgpd_facebook_pixel_id" value="' . esc_attr( pdrgpd_conf_facebook_pixel_id() ) . '" class="regular-text" />';
	echo '<p class="description" id="tagline-description">' . __( 'Insert', 'proteccion-datos-rgpd' ) . ' <a href="https://www.facebook.com/events_manager2/list/get_started" target="_blank">Facebook Pixel</a> ' . __( 'code with this ID', 'proteccion-datos-rgpd' ) . '.</p>';
}

/** Page footer / Pie de página. */
function pdrgpd_seccion_pie_callback() {
	echo __( 'Page footer included data.', 'proteccion-datos-rgpd' );
	echo '<br />';
	echo __( 'Compatible themes', 'proteccion-datos-rgpd' );
	echo ': Twenty Twelve, Twenty Thirteen, Twenty Fourteen, Twenty Fifteen, Twenty Sixteen, Twenty Seventeen, Twenty Nineteen, Twenty Twenty, Twenty Twenty-One, Twenty Twenty-Two, Twenty Twenty-Three, Storefront, Flash, ColorMag, eStore, Spacious, Cenote, Envo Shop, Industro ';
	echo __( 'and', 'proteccion-datos-rgpd' );
	echo ' GeneratePress.<br />';
	echo __( 'Parent theme', 'proteccion-datos-rgpd' ) . ': <b>' . esc_html( tema_padre() ) . '</b>.<br />';
}

function pdrgpd_pie_enlace_legal_callback() {
	echo "<input type='checkbox' name='pdrgpd_pie_enlace_legal' ";
	checked( get_option( 'pdrgpd_pie_enlace_legal' ), 1 );
	echo " value='1'> ";
	echo __( 'Link to legal notice at page footer', 'proteccion-datos-rgpd' );
}

function pdrgpd_pie_enlace_privacidad_callback() {
	echo "<input type='checkbox' name='pdrgpd_pie_enlace_privacidad' ";
	checked( get_option( 'pdrgpd_pie_enlace_privacidad' ), 1 );
	echo " value='1'> ";
	echo __( 'Link to privacy policy at page footer', 'proteccion-datos-rgpd' );
}

function pdrgpd_pie_enlace_cookies_callback() {
	echo "<input type='checkbox' name='pdrgpd_pie_enlace_cookies' ";
	checked( get_option( 'pdrgpd_pie_enlace_cookies' ), 1 );
	echo " value='1'> ";
	echo __( 'Link to cookies policy at page footer', 'proteccion-datos-rgpd' );
}

function pdrgpd_pie_copyright_callback() {
	echo '<input name="pdrgpd_pie_copyright" type="text" id="pdrgpd_pie_copyright" value="' . esc_attr( pdrgpd_conf_pie_copyright() ) . '" class="regular-text" />';
	echo '<p class="description" id="tagline-description">' . __( 'Site creation year if you want a page footer copyright notice, blank if undesired', 'proteccion-datos-rgpd' ) . '.</p>';
}

function pdrgpd_pie_multilinea_callback() {
	echo "<input type='checkbox' name='pdrgpd_pie_multilinea' ";
	checked( get_option( 'pdrgpd_pie_multilinea' ), 1 );
	echo " value='1'> ";
	echo __( 'Distinct lines for links and copyright', 'proteccion-datos-rgpd' );
}

/** Other functions / Otras funciones. */
function pdrgpd_advertencia_pagina_legal( $url, $shortcode ) {
	if ( pdrgpd_bajo_control_wp( $url ) ) {
		$pagina = pdrgpd_carga_pagina_sitio( $url );
		if ( $pagina ) {
			// echo $slug . ': ' . get_the_title( $pagina ) . '</p>' ;
			// La posición cero o cualquier otra.
			if ( pdrgpd_existe_shortcode_o_derivado_en_pagina_sitio( $pagina, $shortcode ) ) {
				echo AVISO_VERDE . 'La página <em>' . pdrgpd_enlace_pagina_wp( $url ) . '</em> está manejada por el plugin';
			} else {
				echo AVISO_AMARILLO . 'La página ' . pdrgpd_enlace_pagina_wp( $url ) . ' no contiene la etiqueta <b>[' . $shortcode . ']</b> ni sus derivadas, si quieres que el plugin maneje el texto, ponle esa etiqueta como único contenido.';
			}
		} else {
			echo AVISO_ROJO . 'La página ' . pdrgpd_enlace_pagina_wp( $url ) . ' no existe, créala poniendo como único contenido <b>[' . $shortcode . ']</b> y el plugin se ocupará de convertirlo en el contenido adecuado.';
		}
	} else {
		echo AVISO_AMARILLO . 'La página ' . pdrgpd_enlace_pagina_wp( $url ) . ' no está en esta instalación de WordPress ' . get_bloginfo( 'wpurl' ) . ', este plugin no puede trabajar su contenido.';
	}
}

function pdrgpd_bajo_control_wp( $url ) {
	$controlada = false;
	if ( strpos( $url, get_bloginfo( 'wpurl' ) ) !== false ) {
		$controlada = true;
	}
	return $controlada;
}

function pdrgpd_carga_pagina_sitio( $url ) {
	// Retira la URL get_bloginfo( 'wpurl' ) . '/' para quedarse con el slug.
	// Antes de llamar a esta funcion ya sabemos que sí es del sitio.
	$slug = pdrgpd_slug_pagina( $url );
	return get_page_by_path( $slug );
}

function pdrgpd_existe_shortcode_o_derivado_en_pagina_sitio( $pagina, $shortcode ) {
	$existe = false;
	// echo $slug . ': ' . get_the_title( $pagina ) . '</p>' ;
	// Solo se repasa el primer corchete, el final del shortag puede variar por usar las versiones ampliadas
	// La posición cero o cualquier otra significarían que sí lo contiene.
	// https://codex.wordpress.org/Class_Reference/WP_Post .
	if ( strpos( $pagina->post_content, '[' . $shortcode ) !== false ) {
		$existe = true;
	}
	return $existe;
}

function pdrgpd_enlace_pagina_wp( $url ) {
	$pagina = pdrgpd_carga_pagina_sitio( $url );
	// $anchor = get_the_title( $pagina );
	$anchor = $pagina->post_title;
	$html   = pdrgpd_enlace_nueva_ventana( $url, $anchor );
	return $html;
}

/** Retira la URL get_bloginfo( 'wpurl' ) . '/' para quedarse con el slug. */
function pdrgpd_slug_pagina( $url ) {
	$slug = '';
	if ( pdrgpd_bajo_control_wp( $url ) ) {
		$slug = str_replace( get_bloginfo( 'wpurl' ) . '/', '', $url );
	}
	return $slug;
}

/** Creación de páginas legales. */
function pdrgpd_ofrece_paginas_legales() {
	// Ofrece crear las páginas legales si faltan.
	if ( pdrgpd_faltan_paginas_legales() ) {
		echo "<input type='checkbox' name='pdrgpd_crear_paginas_legales' ";
		checked( 1, 1 );
		echo " value='1'> ";
		echo __( 'Automatically create legal pages', 'proteccion-datos-rgpd' );
		echo '<p class="description" id="tagline-description">Marca la casilla para que el plugin cree automáticamente las páginas legales que falten.con las direcciones que indiques en los siguientes campos.<br />';
		echo 'En caso de existir ya alguna de las páginas, se respetará sin alterar su contenido.</p>';
	}
}

function pdrgpd_faltan_paginas_legales() {
	// Verifica la existencia de todas las páginas legales configuradas.
	$faltan = false;
	$slug   = pdrgpd_slug_pagina( pdrgpd_conf_uri_aviso() );
	if ( $slug && ! pdrgpd_existe_pagina( $slug ) ) {
		$faltan = true; }
	$slug = pdrgpd_slug_pagina( pdrgpd_conf_uri_privacidad() );
	if ( $slug && ! pdrgpd_existe_pagina( $slug ) ) {
		$faltan = true; }
	$slug = pdrgpd_slug_pagina( pdrgpd_conf_uri_cookies() );
	if ( $slug && ! pdrgpd_existe_pagina( $slug ) ) {
		$faltan = true; }
	return $faltan;
}

function pdrgpd_cear_paginas_legales() {
	pdrgpd_cear_pagina_legal( pdrgpd_slug_pagina( pdrgpd_conf_uri_aviso() ), 'aviso-legal' );
	pdrgpd_cear_pagina_legal( pdrgpd_slug_pagina( pdrgpd_conf_uri_privacidad() ), 'privacidad' );
	pdrgpd_cear_pagina_legal( pdrgpd_slug_pagina( pdrgpd_conf_uri_cookies() ), 'cookies' );
}

function pdrgpd_cear_pagina_legal( $slug, $tipo ) {
	// La página aviso-legal/ ya existe, no se crea.Creada página privacidad2/.La página cookies/ ya existe, no se crea.pdrgpd_cear_paginas_legales
	// Solo se crea si tiene slug previsto.
	if ( $slug ) {
		if ( pdrgpd_existe_pagina( $slug ) ) {
			// Indicar que ya existe.
			// echo "La página $slug ya existe, no se crea.";
		} else {
			// Crear
			switch ( $tipo ) {
				case 'aviso-legal':
					$titulo    = __( 'Legal notice', 'proteccion-datos-rgpd' );
					$contenido = pdrgpd_shortcode_gutenberg( 'pdrgpd-aviso-legal' );
					break;
				case 'privacidad':
					$titulo    = __( 'Privacy policy', 'proteccion-datos-rgpd' );
					$contenido = pdrgpd_shortcode_gutenberg( 'pdrgpd-politica-privacidad' );
					break;
				case 'cookies':
					$titulo    = __( 'Cookies policy', 'proteccion-datos-rgpd' );
					$contenido = pdrgpd_shortcode_gutenberg( 'pdrgpd-politica-cookies' );
					break;
			}
			$nueva_pagina = array(
				'post_type'      => 'page',
				'post_name'      => $slug,
				'post_title'     => $titulo,
				'post_content'   => $contenido,
				'post_status'    => 'publish',
				'comment_status' => 'closed',
			);
			if ( wp_insert_post( $nueva_pagina, true ) ) {
				$url     = get_permalink( get_page_by_path( $slug ) );
				$message = sprintf( __( 'Page %s created.', 'proteccion-datos-rgpd' ), pdrgpd_enlace_pagina_wp( $url ) );
				$type    = 'updated';
				// Los dos primeros parámetros son ciencia ficción.
				add_settings_error( 'pdrgpd_ajustes', 'pdrgpd_mensaje', $message, $type );
			}
		}
	}
}

function pdrgpd_shortcode_gutenberg( $shortcode ) {
	$html  = "<!-- wp:shortcode -->\r\n";
	$html .= '[' . $shortcode . "]\r\n";
	$html .= "<!-- /wp:shortcode -->\r\n";
	return $html;
}

function pdrgpd_existe_pagina( $slug ) {
	$existe = false;
	if ( $slug ) {
		$pagina = get_page_by_path( $slug );
		if ( $pagina ) {
			$existe = true;
		}
	}
	return $existe;
}

function pdrgpd_errores_config() {
	$errores = false;
	return $errores;
}

function pdrgpd_traducir_para_posteriores() {
	// $temporal  = __( '' , 'proteccion-datos-rgpd' );
}
