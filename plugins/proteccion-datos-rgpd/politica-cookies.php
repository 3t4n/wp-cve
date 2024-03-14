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

// Shortcodes.

add_shortcode( 'pdrgpd-politica-cookies', 'pdrgpd_politica_cookies' );
function pdrgpd_politica_cookies() {
	$html  = "[pdrgpd-politica-cookies-introduccion]\n";
	$html .= "[pdrgpd-politica-cookies-tipos]\n";
	$html .= "[pdrgpd-politica-cookies-gestionar]\n";
	$html .= "[pdrgpd-politica-cookies-necesidad]\n";
	$html .= "[pdrgpd-politica-cookies-actualizacion]\n";
	$html .= "[pdrgpd-politica-cookies-contacto]\n";
	return do_shortcode( $html );
}

add_shortcode( 'pdrgpd-politica-cookies-introduccion', 'pdrgpd_politica_cookies_introduccion' );
function pdrgpd_politica_cookies_introduccion() {
	$locale = get_locale();
	if ( 'ca' === $locale ) {
		$html  = "<h3>Aquest web utilitza cookies</h3>\n";
		$html .= '<p>En compliment de la Llei 34/202 d’11 de juliol dels Serveis de la Societat de la Informació i de Comerç Electrònic (LSSICE), t\'informem que aquesta web <strong>[pdrgpd-sitio]</strong> utilitza cookies.</p>
<p>La LSSICE s\'aplica a qualsevol mena d\'arxiu o dispositiu que es descarregui en l\'equip terminal d\'un usuari amb la finalitat d\' emmagatzemar dades que podran ser actualitzades i recuperades per l\'entitat responsable de la seva instal·lació. Una cookie és un d\'aquests arxius d\'ús generalitzat, als que anomenem genèricament com a cookies.</p>
<p>Les cookies (o galetes, en català) són petits fitxers de text enviats a un navegador des d’un servidor web per registrar activitat de l’usuari en una web de manera que pugui recuperar aquesta informació posteriorment durant la navegació per les diferents pàgines que estan connectades al servidor que les va instal·lar.</p>
<p>Les <em>cookies</em> acostumen a emmagatzemar informació de caràcter tècnic, preferències personals, personalització de continguts, estadístiques d\'ús, enllaços a xarxes socials, accés a comptes d\'usuaris, etc.</p>';
	} else {
		$html  = "<h3>Este sitio utiliza cookies</h3>\n";
		$html .= '<p>En cumplimiento de la Ley 34/2002 de 11 de julio de Servicios de la Sociedad de la Información y de Comercio Electrónico (LSSICE), te informamos de que este sitio web <strong>[pdrgpd-sitio]</strong> utiliza cookies.</p>
<p>La LSSICE se aplica a cualquier clase de archivo o dispositivo que se descargue en el equipo terminal de un usuario con el fin de almacenar datos que podrán ser actualizados y recuperados por la entidad responsable de su instalación. Una cookie es uno de esos archivos de uso generalizado a los que, denominaremos genéricamente como cookies.</p>
<p>Las cookies (en castellano, galletas) son pequeños ficheros de texto enviados a un navegador desde un servidor web para registrar actividad del usuario en un sitio web de modo que se pueda recuperar esa información con posterioridad durante la navegación por las diferentes páginas que estén conectadas al servidor que se las instaló.</p>
<p>Las <em>cookies</em> suelen almacenar información de carácter técnico, preferencias personales, personalización de contenidos, estadísticas de uso, enlaces a redes sociales, acceso a cuentas de usuario, etc.</p>';
	}
	return do_shortcode( $html );
}

add_shortcode( 'pdrgpd-politica-cookies-tipos', 'pdrgpd_politica_cookies_tipos' );
function pdrgpd_politica_cookies_tipos() {
	$locale = get_locale();
	if ( 'ca' === $locale ) {
		$html  = "<h3>Tipus de cookies</h3>\n";
		$html .= '<p>A continuació,  es fa una classificació de les cookies en funció d’una sèrie de categories.  Tanmateix  és necessari tenir en compte que una mateixa cookie pot estar inclosa en més d’una categoria.</p>';
		$html .= "<h2>Segons l'entitat que les gestiona</h2>\n";
		$html .= '<p></p>';
		$html .= '<ul>
		<li><b>Cookies propies:</b> Són aquelles que s\'envien a l\'equip terminal de l’usuari des d\'un equip o domini gestionat pel propi editor i des del qual es presta el servei sol·licitat per l\'usuari.</li>
		<li><b>Cookies de tercers:</b> Són aquelles que s\'envien a l\'equip terminal d’usuari des d\'un equip o domini que no és gestionat pel mateix editor, sinó per una altra entitat que tracta les dades obtingudes a través de les cookies.</li>
	</ul>';
		$html .= '<p>En el cas de que les cookies estiguin instal·lades des d\'un equip o domini gestionat pel propi editor però la informació que es reculli mitjançant aquestes sigui gestionada per un tercer, no són considerades cookies pròpies.</p>';
		$html .= "<h2>Segons el termini de temps d'activació</h2>\n";
		$html .= '<p>Segons el termini de temps que estiguin activades en l’equip terminal distingim:</p>';
		$html .= '<ul>
		<li><b>Cookies de sessió:</b> Són un tipus de cookies dissenyades per recavar i emmagatzemar dades mentre l’usuari accedeix a una pàgina web. S’acostumen a utilitzar per emmagatzemar informació que només interessa conservar per la prestació del servei sol·licitat per l’usuari en una única ocasió (pe, una llista de productes comprats).</li>
		<li><b>Cookies persistents:</b> Són un tipus de cookies en el que les dades siguin emmagatzemades en el terminal i a les que es pot accedir i tractades durant un període definit pel responsable de la cookie i que pot ser d’uns minuts fins a alguns anys.</li>
	</ul>';
		$html .= "<h2>Segons la seva finalitat</h2>\n";
		$html .= '<p>Segons la finalitat per la qual es tracten les dades obtingudes a través de les cookies, podem distingir entre:</p>';
		$html .= '<ul>
		<li><b>Cookies tècniques:</b> Són aquelles que permeten a l’usuari la navegació a través d’una pàgina web, plataforma o aplicació i la utilització de les diferents opcions o serveis que en ella existeixen, com per exemple, controlar el tràfic i la comunicació de dades, identificar la sessió, accedir a espais d’accés restringit, recordar els elements que integren una comanda, realitzar el procés de compra d’una comanda, realitzar la sol·licitud d’inscripció o participació en un esdeveniment, utilitzar elements de seguretat durant la navegació, emmagatzemar continguts per la difusió de vídeos o so, o compartir continguts a través de les xarxes socials.</li>
		<li><b>Cookies de personalització:</b> Són aquelles que permeten a l\'usuari accedir al servei amb algunes característiques de caràcter general predefinides en funció d\'una sèrie de criteris en el terminal de l’usuari, com l\'idioma, el tipus de navegador a través del qual s\'accedeix al servidor, etc.</li>
		<li><b>Cookies d\'anàlisi:</b> Són aquelles que permeten al responsable d\'aquestes, el seguiment i anàlisi del comportament dels usuaris dels webs als quals estan vinculades. La informació recollida mitjançant aquesta mena de cookies s\'utilitza per mesurar l\'activitat de la web, aplicació o plataforma i per l\'elaboració de perfils de navegació dels usuaris d’aquestes webs, aplicacions i plataformes, amb la finalitat d’introduir millores en funció de les anàlisis de les dades d\'ús que fan els usuaris del servei.</li>
		<li><b>Cookies publicitàries:</b> Són aquelles que permeten la gestió, de la manera més eficaç possible, dels espais publicitaris que, en el seu cas, l\'editor hagi inclòs en la seva pàgina web, aplicació o plataforma des de la qual es presta el servei sol·licitat d\'acord a criteris com el contingut editat o la freqüència en la qual es mostren els anuncis.</li>
		<li><b>Cookies de publicitat sobre comportament:</b> Són aquelles que permeten la gestió, de la forma més eficaç possible, dels espais publicitaris que, en el seu cas, l’editor hagi inclòs en una pàgina web, aplicació o plataforma des del qual presta el servei sol·licitat. Aquestes cookies emmagatzemen informació del comportament dels usuaris obtinguda a través de l’observació continuada dels seus hàbits de navegació, el que permet desenvolupar un perfil específic per mostrar publicitat en funció del mateix.</li>
	</ul>';
	} else {
		$html  = "<h3>Tipos de cookies</h3>\n";
		$html .= '<p>A continuación, se realiza una clasificación de las cookies en función de una serie de categorías. No obstante es necesario tener en cuenta que una misma cookie puede estar incluida en más de una categoría.</p>';
		$html .= "<h2>Según la entidad que las gestiona</h2>\n";
		$html .= '<p></p>';
		$html .= '<ul>
		<li><b>Cookies propias:</b> Son aquellas que  se envían al equipo terminal del usuario desde un equipo o dominio gestionado por el propio editor y desde el que se presta el servicio solicitado por el usuario.</li>
		<li><b>Cookies de terceros:</b> Son aquellas que se envían al equipo terminal del usuario desde un equipo o dominio que no es gestionado por el editor, sino por otra entidad que trata los datos obtenidos a través de las cookies.</li>
	</ul>';
		$html .= '<p>En el caso de que las cookies sean instaladas desde un equipo o dominio gestionado por el propio editor, pero la información que se recoja mediante estas sea gestionada por un tercero, no son consideradas cookies propias.</p>';
		$html .= "<h2>Según el plazo de tiempo que permanecen activadas</h2>\n";
		$html .= '<p>Según el plazo de tiempo que permanecen activadas en el equipo terminal podemos distinguir:</p>';
		$html .= '<ul>
		<li><b>Cookies de sesión:</b> Son un tipo de cookies diseñadas para recabar y almacenar datos mientras el usuario accede a una página web. Se suelen emplear para almacenar información que solo interesa conservar para la prestación del servicio solicitado por el usuario en una sola ocasión (p.ej. una lista de productos adquiridos).</li>
		<li><b>Cookies persistentes:</b> Son un tipo de cookies en el que los datos siguen almacenados en el terminal y pueden ser accedidos y tratados durante un periodo definido por el responsable de la cookie, y que puede ir de unos minutos a varios años.</li>
	</ul>';
		$html .= "<h2>Según su finalidad</h2>\n";
		$html .= '<p>Según la finalidad para la que se traten los datos obtenidos a través de las cookies, podemos distinguir entre:</p>';
		$html .= '<ul>
		<li><b>Cookies técnicas:</b> Son aquellas que permiten al usuario la navegación a través de una página web, plataforma o aplicación y la utilización de las diferentes opciones o servicios que en ella existan como, por ejemplo, controlar el tráfico y la comunicación de datos, identificar la sesión, acceder a partes de acceso restringido, recordar los elementos que integran un pedido, realizar el proceso de compra de un pedido, realizar la solicitud de inscripción o participación en un evento, utilizar elementos de seguridad durante la navegación, almacenar contenidos para la difusión de videos o sonido o compartir contenidos a través de redes sociales.</li>
		<li><b>Cookies de personalización:</b> Son aquellas que permiten al usuario acceder al servicio con algunas características de carácter general predefinidas en función de una serie de criterios en el terminal del usuario como por ejemplo serian el idioma, el tipo de navegador a través del cual accede al servicio, la configuración regional desde donde accede al servicio, etc.</li>
		<li><b>Cookies de análisis:</b> Son aquellas que permiten al responsable de las mismas, el seguimiento y análisis del comportamiento de los usuarios de los sitios web a los que están vinculadas. La información recogida mediante este tipo de cookies se utiliza en la medición de la actividad de los sitios web, aplicación o plataforma y para la elaboración de perfiles de navegación de los usuarios de dichos sitios, aplicaciones y plataformas, con el fin de introducir mejoras en función del análisis de los datos de uso que hacen los usuarios del servicio.</li>
		<li><b>Cookies publicitarias:</b> Son aquellas que permiten la gestión, de la forma más eficaz posible, de los espacios publicitarios que, en su caso, el editor haya incluido en una página web, aplicación o plataforma desde la que presta el servicio solicitado en base a criterios como el contenido editado o la frecuencia en la que se muestran los anuncios.</li>
		<li><b>Cookies de publicidad comportamental:</b> Son aquellas que permiten la gestión, de la forma más eficaz posible, de los espacios publicitarios que, en su caso, el editor haya incluido en una página web, aplicación o plataforma desde la que presta el servicio solicitado. Estas cookies almacenan información del comportamiento de los usuarios obtenida a través de la observación continuada de sus hábitos de navegación, lo que permite desarrollar un perfil específico para mostrar publicidad en función del mismo.</li>
	</ul>';
	}
	return do_shortcode( $html );
}

add_shortcode( 'pdrgpd-politica-cookies-gestionar', 'pdrgpd_politica_cookies_gestionar' );
function pdrgpd_politica_cookies_gestionar() {
	$locale = get_locale();
	if ( 'ca' === $locale ) {
		$html = '<h3>Com desactivar les cookies en els principals navegadors web</h3>
<p>Per configurar l’ús de cookies, segueix les instruccions corresponents al mateix navegador o consulta la seva ajuda: </p>
<ul>
<li><a href="https://support.apple.com/es-es/HT201265">Safari</a></li>
<li><a href="https://support.mozilla.org/ca/kb/activa-i-desactiva-les-galetes-que-les-pagines-web">Firefox</a></li>
<li><a href="https://support.google.com/chrome/answer/95647?hl=ca">Chrome</a></li>
<li><a href="https://www.opera.com/help/tutorials/security/privacy/">Ópera</a></li>
<li><a href="https://support.microsoft.com/es-es/help/17442/windows-internet-explorer-delete-manage-cookies">Internet Explorer</a></li>
<li><a href="https://privacy.microsoft.com/es-es/windows-10-microsoft-edge-and-privacy">Edge</a></li>
</ul>';
	} else {
		$html = '<h3>Cómo desactivar las cookies en los principales navegadores web</h3>
<p>Para configurar el uso de cookies, sigue las instrucciones correspondientes a tu navegador o consulta su ayuda:</p>
<ul>
<li><a href="https://support.apple.com/es-es/HT201265">Safari</a></li>
<li><a href="https://support.mozilla.org/es/kb/habilitar-y-deshabilitar-cookies-sitios-web-rastrear-preferencias?redirectlocale=es&redirectslug=habilitar-y-deshabilitar-cookies-que-los-sitios-we">Firefox</a></li>
<li><a href="https://support.google.com/chrome/answer/95647?hl=es-es">Chrome</a></li>
<li><a href="https://www.opera.com/help/tutorials/security/privacy/">Ópera</a></li>
<li><a href="https://support.microsoft.com/es-es/help/17442/windows-internet-explorer-delete-manage-cookies">Internet Explorer</a></li>
<li><a href="https://privacy.microsoft.com/es-es/windows-10-microsoft-edge-and-privacy">Edge</a></li>
</ul>';
	}
	return do_shortcode( $html );
}

add_shortcode( 'pdrgpd-politica-cookies-necesidad', 'pdrgpd_politica_cookies_necesidad' );
function pdrgpd_politica_cookies_necesidad() {
	$locale = get_locale();
	if ( 'ca' === $locale ) {
		$html = '<h3>Qué passa si no s\'accepten les cookies</h3>
<p>El rebuig de les cookies pot impedir l’accés a continguts i serveis personalitzats.</p>';
	} else {
		$html = '<h3>Qué sucede si no se aceptan las cookies</h3>
<p>El rechazo de las cookies puede impedir el acceso a contenidos y servicios personalizados.</p>';
	}
	return do_shortcode( $html );
}

add_shortcode( 'pdrgpd-politica-cookies-utilizadas', 'pdrgpd_politica_cookies_utilizadas' );
function pdrgpd_politica_cookies_utilizadas() {
	$locale = get_locale();
	if ( 'ca' === $locale ) {
		$html  = '<h3>Cookies utilitzades</h3>
<p>En aquesta web utilitzem les següents cookies:</p>
<ul>';
		$html .= '<li></li>';
		$html .= '<li></li>';
		$html .= '<li></li>';
		$html .= '</ul>';
	} else {
		$html  = '<h3>Cookies utilizadas</h3>
<p>En este sitio utilizamos las siguientes cookies:</p>
<ul>';
		$html .= '<li></li>';
		$html .= '<li></li>';
		$html .= '<li></li>';
		$html .= '</ul>';
	}
	return do_shortcode( $html );
}

add_shortcode( 'pdrgpd-politica-cookies-actualizacion', 'pdrgpd_politica_cookies_actualizacion' );
function pdrgpd_politica_cookies_actualizacion() {
	$locale = get_locale();
	if ( 'ca' === $locale ) {
		$html = '<h3>Actualitzacions i modificacions en la política de cookies</h3>
<p>[pdrgpd-sitio] pot modificar aquesta política de cookies en funció de les exigències legislatives, reglamentàries, amb la finalitat d\'adaptar aquesta política a les instruccions dictades per l\'Agencia Española de Protección de Datos, per qüestions tècniques o de reorganització de la web; per això t\'aconsellem que les visites periòdicament.';
	} else {
		$html = '<h3>Actualizaciones y modificaciones en la política de cookies</h3>
<p>[pdrgpd-sitio] puede modificar esta política de cookies en función de las exigencias legislativas, reglamentarias, con la finalidad de adaptar dicha política a las instrucciones dictadas por la Agencia Española de Protección de Datos, por cuestiones técnicas o de reorganización de la web; por ello te aconsejamos que la visites periódicamente.';
	}
	return do_shortcode( $html );
}

add_shortcode( 'pdrgpd-politica-cookies-contacto', 'pdrgpd_politica_cookies_contacto' );
function pdrgpd_politica_cookies_contacto() {
	$html = '';
	if ( pdrgpd_conf_email() ) {
		$locale = get_locale();
		if ( 'ca' === $locale ) {
			$html = '<h3>Contacte</h3>
<p>Per resoldre qualsevol dubte sobre cóm s\'utilitzen les  cookies, escriu a la direcció de correu electrònic: [pdrgpd-email].';
		} else {
			$html = '<h3>Contacto</h3>
<p>Para resolver cualquier duda sobre cómo utilizamos las cookies, escríbenos a la dirección de correo electrónico: [pdrgpd-email].';
		}
	}
	return do_shortcode( $html );
}
