<?php
/**
 * Mantiene la política de privacidad
 *
 * @package   Protección de datos - RGPD
 * @author    ABCdatos
 * @license   GPLv2
 * @link      https://taller.abcdatos.net/
 */

defined( 'ABSPATH' ) || die( 'No se permite el acceso.' );

// Shortcodes.

add_shortcode( 'pdrgpd-politica-privacidad', 'pdrgpd_politica_privacidad' );
function pdrgpd_politica_privacidad() {
	$html  = "[pdrgpd-politica-privacidad-presentacion]\n";
	$html .= "[pdrgpd-politica-privacidad-responsable]\n";
	$html .= "[pdrgpd-politica-privacidad-finalidad]\n";
	$html .= "[pdrgpd-politica-privacidad-legitimacion]\n";
	$html .= "[pdrgpd-politica-privacidad-transferencia]\n";
	$html .= "[pdrgpd-politica-privacidad-derechos]\n";
	return do_shortcode( $html );
}

add_shortcode( 'pdrgpd-politica-privacidad-presentacion', 'pdrgpd_politica_privacidad_presentacion' );
function pdrgpd_politica_privacidad_presentacion() {
	$locale = get_locale();
	if ( 'ca' === $locale ) {
		$html  = "<h3>INTRODUCCIÓ</h3>\n";
		$html .= '<p>A [pdrgpd-sitio], sensibilitzats amb les necessitats dels usuaris d’Internet i conscients de la importància de la rigorosa privacitat de la informació personal que ens confien, incloem aquesta declaració de privacitat amb l’objecte del fet que siguis conscient de la política en el tractament de les dades personals que s’obtenen dels visitants i usuaris..</p>';
		$html .= '<p>Com usuari, acceptes aquestes condicions pel sol fet de llegir, visualitzar o navegar en la web. Si no ho acceptes, has d’abandonar la web, sense fer ús d’ella ni del seu contingut, i sense accedir a les pàgines enllaçades.</p>';
	} else {
		$html  = "<h3>INTRODUCCIÓN</h3>\n";
		$html .= '<p>En [pdrgpd-sitio], sensibilizados con las necesidades de los usuarios de Internet y conscientes de la importancia de la rigurosa privacidad de la información personal que nos confían, incluimos esta declaración de privacidad con el objeto de que seas consciente de la política en el tratamiento de los datos personales que obtiene de sus visitantes y usuarios.</p>';
		$html .= '<p>Como usuario, aceptas estas condiciones por el mero hecho de leer, visualizar o navegar en el sitio. De no aceptarlas, debes abandonar el sitio, sin hacer uso alguno de él y su contenido, y sin acceder a las páginas enlazadas.</p>';
	}
	return do_shortcode( $html );
}

add_shortcode( 'pdrgpd-politica-privacidad-responsable', 'pdrgpd_politica_privacidad_responsable' );
function pdrgpd_politica_privacidad_responsable() {
	$locale = get_locale();
	if ( 'ca' === $locale ) {
		$html  = "<h3 id='responsable'>RESPONSABLE DEL TRACTAMENT DE DADES</h3>\n";
		$html .= '<p>El titular de la web i responsable del tractament de dades és [pdrgpd-titular]';
		$html .= ', amb ' . pdrgpd_nif_o_cif( pdrgpd_conf_nif() ) . ' [pdrgpd-nif]';
		$html .= ', per contactar pots emprar';
		if ( pdrgpd_conf_email() ) {
			$html .= ' l\'adreça de correu electrònic [pdrgpd-email]';
		}
		if ( pdrgpd_conf_telefono() ) {
			if ( pdrgpd_conf_email() ) {
				$html .= ',';
			}
			$html .= ' el teléfon [pdrgpd-telefono]';
		}
		if ( pdrgpd_conf_telefono() || pdrgpd_conf_email() ) {
			$html .= ' o';
		}
		$html .= ' correu postal a [pdrgpd-direccion]';
		if ( pdrgpd_conf_cp() ) {
			$html .= ', codi postal [pdrgpd-cp]';
		}
		$html .= ' [pdrgpd-poblacion]';
		if ( pdrgpd_conf_poblacion() !== pdrgpd_conf_provincia() && pdrgpd_conf_provincia() ) {
			$html .= ', [pdrgpd-provincia]';
		}
		$html .= '.</p>';
	} else {
		$html  = "<h3 id='responsable'>RESPONSABLE DEL TRATAMIENTO DE DATOS</h3>\n";
		$html .= '<p>El titular del sitio y responsable del tratamiento de datos es [pdrgpd-titular]';
		$html .= ', con ' . pdrgpd_nif_o_cif( pdrgpd_conf_nif() ) . ' [pdrgpd-nif]';
		$html .= ', para contactar puedes utilizar';
		if ( pdrgpd_conf_email() ) {
			$html .= ' la dirección de correo electrónico [pdrgpd-email]';
		}
		if ( pdrgpd_conf_telefono() ) {
			if ( pdrgpd_conf_email() ) {
				$html .= ',';
			}
			$html .= ' el teléfono [pdrgpd-telefono]';
		}
		if ( pdrgpd_conf_telefono() || pdrgpd_conf_email() ) {
			$html .= ' o';
		}
		$html .= ' correo postal a [pdrgpd-direccion]';
		if ( pdrgpd_conf_cp() ) {
			$html .= ', código postal [pdrgpd-cp]';
		}
		$html .= ' de [pdrgpd-poblacion]';
		if ( pdrgpd_conf_poblacion() !== pdrgpd_conf_provincia() && pdrgpd_conf_provincia() ) {
			$html .= ', [pdrgpd-provincia]';
		}
		$html .= '.</p>';
	}
	return do_shortcode( $html );
}

add_shortcode( 'pdrgpd-politica-privacidad-finalidad', 'pdrgpd_politica_privacidad_finalidad' );
function pdrgpd_politica_privacidad_finalidad() {
	$locale = get_locale();
	if ( 'ca' === $locale ) {
		$html  = "<h3 id='finalidad'>FINALITAT DEL TRACTAMENT DE DADES</h3>\n";
		$html .= '<p>En la nostra web, existeixen apartats específics on pots anotar les teves dades per rebre informació sobre actualitzacions de la nostra web i d’alguns programes que es distribueixen. Nosaltres t’assegurem que la informació que ens facilitis serà gestionada de manera confidencial.</p>';

		if ( pdrgpd_conf_existencia_formulario_contacto() ) {
			$html .= '[pdrgpd-finalidad-formulario-contacto]';
		}
		if ( pdrgpd_conf_existencia_formulario_comentar() ) {
			$html .= '[pdrgpd-finalidad-formulario-comentar]';
		}
		if ( pdrgpd_conf_existencia_boletin() ) {
			$html .= '[pdrgpd-finalidad-suscripcion-boletin]';
		}
	} else {
		$html  = "<h3 id='finalidad'>FINALIDAD DEL TRATAMIENTO DE DATOS</h3>\n";
		$html .= '<p>En nuestros sitios web, existen unos apartados específicos donde puedes anotar tus datos para recibir información sobre actualizaciones de nuestra web y de algunos los programas que se distribuyen. Nosotros te aseguramos que la información que nos facilites será gestionada de forma totalmente confidencial.</p>';

		if ( pdrgpd_conf_existencia_formulario_contacto() ) {
			$html .= '[pdrgpd-finalidad-formulario-contacto]';
		}
		if ( pdrgpd_conf_existencia_formulario_comentar() ) {
			$html .= '[pdrgpd-finalidad-formulario-comentar]';
		}
		if ( pdrgpd_conf_existencia_boletin() ) {
			$html .= '[pdrgpd-finalidad-suscripcion-boletin]';
		}
	}

	if ( pdrgpd_existe_suscripcion_jetpack() ) {
		$html .= '<p>';
		$html .= __( 'Site suscription form', 'proteccion-datos-rgpd' );
		$html .= ': ';
		$html .= __( 'Inform you by e-mail of new posts in the site.', 'proteccion-datos-rgpd' );
		$html .= "</p>\n";
	}

	if ( 'ca' === $locale ) {
		/*
		Upcoming new fields?
		Registro
		Foros
		Contenidos
		Pedidos
		*/
		$html .= '<p>Les dades s\'emmagatzemaran mentre existeixi previsió del seu ús per a la fi per al qual van ser recollides..</p>';
		$html .= '<p>No es fan preses de decisions automatitzades amb les teves dades.</p>';
		$html .= '<p>La web pot utilitzar cookies, consulta la nostra <a href="[pdrgpd-uri-cookies]">política de cookies</a>.</p>';
	} else {
		/*
		Upcoming new fields?
		Registro
		Foros
		Contenidos
		Pedidos
		*/
		$html .= '<p>Los datos se almacenarán mientras exista previsión de su uso para el fin por el que fueron recabados.</p>';
		$html .= '<p>No se realizan tomas de decisiones automatizadas con tus datos.</p>';
		$html .= '<p>La web puede utilizar cookies, consulta nuestra <a href="[pdrgpd-uri-cookies]">política de cookies</a>.</p>';
	}
	return do_shortcode( $html );
}

add_shortcode( 'pdrgpd-politica-privacidad-legitimacion', 'pdrgpd_politica_privacidad_legitimacion' );
function pdrgpd_politica_privacidad_legitimacion() {
	$locale = get_locale();
	if ( 'ca' === $locale ) {
		$html  = "<h3 id='legitimacion'>LEGITIMACIÓ DEL TRACTAMENT DE DADES</h3>\n";
		$html .= '<p>L\'ús de les teves dades s’efectua perquè ens dónes el teu consentiment per utilitzar les que ens proporciones en els formularis per un ús específic que s\'indica en cadascun d\'ells. Totes les teves dades només són necessàries per a usos concrets pels quals se\'t  sol·liciten, si no ho fas, aquests serveis no són possibles.</p>';
	} else {
		$html  = "<h3 id='legitimacion'>LEGITIMACIÓN DEL TRATAMIENTO DE DATOS</h3>\n";
		$html .= '<p>El uso de tus datos se realiza porque nos das tu consentimiento para usar los que nos proporcionas en los formularios para un uso específico que se indica en cada uno de ellos. Tus datos solo son necesarios para los usos concretos por los que se te solicitan, si no nos los facilitas, esos servicios no son posibles.</p>';
	}
	return do_shortcode( $html );
}

add_shortcode( 'pdrgpd-politica-privacidad-transferencia', 'pdrgpd_politica_privacidad_transferencia' );
function pdrgpd_politica_privacidad_transferencia() {
	$locale = get_locale();
	if ( 'ca' === $locale ) {
		$html  = "<h3 id='transferencia'>TRANSFERÈNCIES I CESSIONS DE DADES</h3>\n";
		$html .= '<p>Existeix un compromís ferm per part nostre que les dades que proporcionis a [pdrgpd-sitio], no seran venudes ni cedides a terceres persones sense el teu previ consentiment, per a cap concepte o circumstància, excepte consentiment exprés o obligació legal.</p>';
		if ( pdrgpd_existe_akismet() ) {
			$html .= '<p>Les dades incorporades en el formulari de comentaris, seràn tractades per Automattic Inc., amb domicili a EEUU amb la finalitat de filtrar el spam als comentaris. Pots consultar la <a href="https://automattic.com/privacy-notice/">política de privadesa d\'Automattic Inc.</a>.</p>';
		}
		if ( pdrgpd_conf_akismet_formulario_contacto() ) {
			$html .= '<p>Les dades incorporades en el formulari de conacte, seràn tractades per Automattic Inc., amb domicili a EEUU amb la finalitat de filtrar el spam als missatges. Pots consultar la <a href="https://automattic.com/privacy-notice/">política de privadesa d\'Automattic Inc.</a>.</p>';
		}
	} else {
		$html  = "<h3 id='transferencia'>TRANSFERENCIAS Y CESIONES DE DATOS</h3>\n";
		$html .= '<p>Existe un compromiso firme por nuestra parte de que los datos que proporcione a [pdrgpd-sitio], no serán vendidos ni cedidos a terceras personas sin el previo consentimiento del interesado bajo ningún concepto o circunstancia, salvo consentimiento expreso u obligación legal.</p>';
		if ( pdrgpd_existe_akismet() ) {
			$html .= '<p>Los datos incorporados en el formulario de comentarios, serán tratados por Automattic Inc., con domicilio en EEUU con la finalidad de filtrar el spam en los comentarios. Puede consultar la <a href="https://automattic.com/privacy-notice/">política de privacidad de Automattic Inc.</a>.</p>';
		}
		if ( pdrgpd_conf_akismet_formulario_contacto() ) {
			$html .= '<p>Los datos incorporados en el formulario de contacto, serán tratados por Automattic Inc., con domicilio en EEUU con la finalidad de filtrar el spam en los mensajes. Puede consultar la <a href="https://automattic.com/privacy-notice/">política de privacidad de Automattic Inc.</a>.</p>';
		}
	}

	if ( pdrgpd_existe_suscripcion_jetpack() ) {
		$html .= '<p>';
		$html .= __( 'Form to subscribe to the site by e-mail will be processed by Automattic Inc., with quarters in the US with the purpose to provide that service.', 'proteccion-datos-rgpd' );
		$html .= ' ';
		$html .= __( 'You may examine the', 'proteccion-datos-rgpd' );
		$html .= ' <a href="';
		$html .= pdrgpd_url_privacidad_jetpack();
		$html .= '">';
		$html .= __( 'privacy policy', 'proteccion-datos-rgpd' );
		$html .= ' ';
		$html .= __( 'of', 'proteccion-datos-rgpd' );
		$html .= ' Automattic Inc.';
		$html .= '</a>.';
		$html .= '</p>';
	}

	if ( 'ca' === $locale ) {
		$html .= '<p>La nostra web conté enllaços cap a altres webs de tercers. [pdrgpd-sitio] no es fa responsable per les polítiques i pràctiques de privacitat d\'aquestes altres webs.</p>';
	} else {
		$html .= '<p>Nuestro sitio web contiene enlaces hacia sitios web de terceros. [pdrgpd-sitio] no se hace responsable por las políticas y prácticas de privacidad de estos otros sitios.</p>';
	}

	return do_shortcode( $html );
}

function pdrgpd_politica_privacidad_transferencia_mini( $formulario ) {
	if ( ( pdrgpd_existe_akismet() && 'comentar' === $formulario ) || ( pdrgpd_conf_akismet_formulario_contacto() && 'contacto' === $formulario ) ) {
		$html = 'Automattic Inc., EEUU ' . __( 'to spam filtering', 'proteccion-datos-rgpd' ) . '.';
	} else {
		// No están previstas cesiones de datos, en ese caso con un valor en blanco bastará para que el formulario genere el texto adecuado.
		$html = '';
	}
	return $html;
}

add_shortcode( 'pdrgpd-politica-privacidad-derechos', 'pdrgpd_politica_privacidad_derechos' );
function pdrgpd_politica_privacidad_derechos() {
	$locale = get_locale();
	if ( 'ca' === $locale ) {
		$html  = "<h3 id='derechos'>DRETS DELS INTERESSATS</h3>\n";
		$html .= '<p>Tens dret a accedir a la informació que sobre la teva persona estigui emmagatzemada en les nostres bases de dades, rectificar-la en cas d’errada, suprimir-la, limitar-la oposar-te al seu tractament i retirar-ne el consentiment si aquest és el teu desig.';
		if ( pdrgpd_conf_email() ) {
			$html .= ' Per fer-ho només has d’escriure un e-mail a l’adreça de correu electrònic: [pdrgpd-email] don et contestarem qualsevol consulta, comentari, aclariment que ens facis al respecte.';
		}
		$html .= '</p>';
		$html .= '<p>Per a més informació sobre temes relacionats, la web de referència de la Xarxa espanyola és l’<a href="https://www.agpd.es/">Agencia de Protección de Datos</a>, on tens dret a reclamar.</p>';
	} else {
		$html  = "<h3 id='derechos'>DERECHOS DE LOS INTERESADOS</h3>\n";
		$html .= '<p>Tienes el derecho de acceder a la información que sobre tu persona está almacenada en nuestras bases de datos, rectificarla si existiera alguna errata, suprimirla, limitarla, oponerte a su tratamiento y retirar tu consentimiento si ese es tu deseo.';
		if ( pdrgpd_conf_email() ) {
			$html .= ' Para ello simplemente debes escribir un e-mail a la dirección de correo electrónico [pdrgpd-email] donde te atenderemos gustosamente cualquier consulta, comentario o aclaración requerida al respecto.';
		}
		$html .= '</p>';
		$html .= '<p>Para mayor información sobre temas relacionados, el sitio de referencia en la Red española es la <a href="https://www.agpd.es/">Agencia de Protección de Datos</a>, donde tienes derecho a reclamar.</p>';
	}
	return do_shortcode( $html );
}

add_shortcode( 'pdrgpd-finalidad-formulario-contacto', 'pdrgpd_finalidad_formulario_contacto' );
function pdrgpd_finalidad_formulario_contacto() {
	if ( pdrgpd_conf_finalidad_formulario_contacto() || pdrgpd_conf_finalidad_formulario_contacto_mini() ) {
		$html = '<p>' . __( 'Contact form', 'proteccion-datos-rgpd' ) . ': ';
		if ( pdrgpd_conf_finalidad_formulario_contacto() ) {
			$html .= esc_html( pdrgpd_conf_finalidad_formulario_contacto() );
		} elseif ( pdrgpd_conf_finalidad_formulario_contacto_mini() ) {
			$html .= esc_html( pdrgpd_conf_finalidad_formulario_contacto_mini() );
		}
		$html .= "</p>\n";
		return $html;
	}
}

function pdrgpd_conf_existencia_formulario_contacto() {
	return get_option( 'pdrgpd_existencia_formulario_contacto' );
}

function pdrgpd_conf_finalidad_formulario_contacto_mini() {
	return get_option( 'pdrgpd_finalidad_formulario_contacto_mini', __( 'Keep in contact with you or other required actions.', 'proteccion-datos-rgpd' ) );
}

function pdrgpd_conf_finalidad_formulario_contacto() {
	return get_option( 'pdrgpd_finalidad_formulario_contacto' );
}

function pdrgpd_conf_akismet_formulario_contacto() {
	return get_option( 'pdrgpd_akismet_formulario_contacto' );
}

add_shortcode( 'pdrgpd-finalidad-suscripcion-boletin', 'pdrgpd_finalidad_suscripcion_boletin' );
function pdrgpd_finalidad_suscripcion_boletin() {
	if ( pdrgpd_conf_finalidad_suscripcion_boletin() || pdrgpd_conf_finalidad_suscripcion_boletin_mini() ) {
		$html = '<p>' . __( 'Newsletter suscription', 'proteccion-datos-rgpd' ) . ': ';
		if ( pdrgpd_conf_finalidad_suscripcion_boletin() ) {
			$html .= esc_html( pdrgpd_conf_finalidad_suscripcion_boletin() );
		} elseif ( pdrgpd_conf_finalidad_suscripcion_boletin_mini() ) {
			$html .= esc_html( pdrgpd_conf_finalidad_suscripcion_boletin_mini() );
		}
		$html .= "</p>\n";
		return $html;
	}
}

function pdrgpd_conf_existencia_boletin() {
	return get_option( 'pdrgpd_existencia_boletin' );
}

function pdrgpd_conf_finalidad_suscripcion_boletin_mini() {
	return get_option( 'pdrgpd_finalidad_suscripcion_boletin_mini', __( 'Sending newsletters to your e-mail address.', 'proteccion-datos-rgpd' ) );
}

function pdrgpd_conf_finalidad_suscripcion_boletin() {
	return get_option( 'pdrgpd_finalidad_suscripcion_boletin' );
}

add_shortcode( 'pdrgpd-finalidad-formulario-comentar', 'pdrgpd_finalidad_formulario_comentar' );
function pdrgpd_finalidad_formulario_comentar() {
	if ( pdrgpd_conf_finalidad_formulario_comentar() || pdrgpd_conf_finalidad_formulario_comentar_mini() ) {
		$html = '<p>' . __( 'Comment form', 'proteccion-datos-rgpd' ) . ': ';
		if ( pdrgpd_conf_finalidad_formulario_comentar() ) {
			// Quita espacios o un punto final).
			$html .= esc_html( rtrim( pdrgpd_conf_finalidad_formulario_comentar(), ' .' ) );
		} elseif ( pdrgpd_conf_finalidad_formulario_comentar_mini() ) {
			// Quita espacios o un punto final).
			$html .= esc_html( rtrim( pdrgpd_conf_finalidad_formulario_comentar_mini(), ' .' ) );
		}
		if ( pdrgpd_existe_akismet() ) {
			// Asegurar que hay valor en pdrgpd_conf_finalidad_formulario_comentar() y quitar el punto final.
			$html .= ', incluyendo filtrar el spam';
		}
		$html .= ".</p>\n";
		return $html;
	}
}

function pdrgpd_conf_existencia_formulario_comentar() {
	return get_option( 'pdrgpd_existencia_formulario_comentar' );
}

function pdrgpd_conf_finalidad_formulario_comentar_mini() {
	return get_option( 'pdrgpd_finalidad_formulario_comentar_mini', __( 'Manage and moderate your comments.', 'proteccion-datos-rgpd' ) );
}

function pdrgpd_conf_finalidad_formulario_comentar() {
	return get_option( 'pdrgpd_finalidad_formulario_comentar' );
}

function pdrgpd_url_privacidad_jetpack() {
	return pdrgpd_url_traducida( 'https://automattic.com/es/privacy/', 'en' );
}

/** Traduce una URL desde el idioma de origen si lo requiere, al estilo de:
 *  https://translate.google.com/translate?hl=en&sl=es&tl=en&u=https%3A%2F%2Fautomattic.com%2Fautomattic-and-the-general-data-protection-regulation-gdpr%2F
 *
 * @param string $url_original URL que puede requerir traducción.
 * @param string $idioma_origen Idioma de la URL original.
 * @return string URL, traducida si procede.
 */
function pdrgpd_url_traducida( $url_original, $idioma_origen ) {
	$locale = get_locale();
	if ( $locale === $idioma_origen ) {
		$uri_localizada = $url_original;
	} else {
		$uri_localizada  = 'https://translate.google.com/translate';
		$uri_localizada .= '?hl=' . $locale;        // FrontEnd.
		$uri_localizada .= '&sl=' . $idioma_origen; // Original.
		$uri_localizada .= '&tl=' . $locale;        // Traducido.
		$uri_localizada .= '&u=' . rawurlencode( $url_original );
	}
	return $uri_localizada;
}
