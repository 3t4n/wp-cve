<?php
/**
 * Gestiona el banner de cookies
 *
 * @package   Protección de datos - RGPD
 * @author    ABCdatos
 * @license   GPLv2
 * @link      https://taller.abcdatos.net/
 */

defined( 'ABSPATH' ) || die( 'No se permite el acceso.' );

// gtag (Google Global Site Tag).
if ( '' !== pdrgpd_conf_google_analytics_id() ) {
	add_action( 'wp_head', 'pdrgpd_inserta_gtag' );
	function pdrgpd_inserta_gtag() {
		$pdrgpd_google_analytics_id = pdrgpd_conf_google_analytics_id();
		// Debe ir en el head, antes de cualquier llamada a comandos gtag.
		?>

<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo $pdrgpd_google_analytics_id; ?>"></script>
<script>
window.dataLayer = window.dataLayer || [];
function gtag(){window.dataLayer.push(arguments);}
gtag('js', new Date());
		<?php
		/*
		// Si se usa el banner y no están aceptadas las cookies, las rechaza de entrada
		if ( pdrgpd_conf_mostrar_banner_cookies() && !pdrgpd_cookie_estadisticas() ) {
		?>
		gtag('consent', 'default', {
		'ad_storage': 'denied',
		'analytics_storage': 'denied'
		});
		<?php
		}
		*/
		?>
gtag('config', '<?php echo $pdrgpd_google_analytics_id; ?>');
</script>

		<?php
	}
}


// Facebook Pixel.
if ( '' != pdrgpd_conf_facebook_pixel_id() ) {
	add_action( 'wp_head', 'pdrgpd_inserta_fb_pixel' );
	function pdrgpd_inserta_fb_pixel() {
		$pdrgpd_facebook_pixel_id = pdrgpd_conf_facebook_pixel_id();
		// Debe ir en el head.
		?>

<!-- Facebook Pixel Code -->
<script>
	!function(f,b,e,v,n,t,s)
	{if(f.fbq)return;n=f.fbq=function(){n.callMethod?
	n.callMethod.apply(n,arguments):n.queue.push(arguments)};
	if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
	n.queue=[];t=b.createElement(e);t.async=!0;
	t.src=v;s=b.getElementsByTagName(e)[0];
	s.parentNode.insertBefore(t,s)}(window, document,'script',
	'https://connect.facebook.net/en_US/fbevents.js');
		<?php
		/*
		// Si se usa el banner y no están aceptadas las cookies, las rechaza de entrada
		if ( pdrgpd_conf_mostrar_banner_cookies() && !pdrgpd_cookie_estadisticas() ) {
		?>
		fbq('consent', 'revoke');
		<?php
		}
		*/
		?>
	fbq('init', '<?php echo $pdrgpd_facebook_pixel_id; ?>');
	fbq('track', 'PageView');
</script>
<noscript>
	<img height="1" width="1" style="display:none" src="https://www.facebook.com/tr?id=<?php echo $pdrgpd_facebook_pixel_id; ?>&ev=PageView&noscript=1"/>
</noscript>
<!-- End Facebook Pixel Code -->

		<?php
	}
}


/*
 * Valores cookies
 */

function pdrgpd_cookie_estadisticas() {
	if ( isset( $_COOKIE['pdrgpd_estadisticas'] ) ) {
		if ( true == $_COOKIE['pdrgpd_estadisticas'] ) {
			return true;
		} else {
			return false;
		}
	}
}

/*
 * Valores configurados
 */

// Google Tracking code.
function pdrgpd_conf_google_analytics_id() {
	return esc_html( get_option( 'pdrgpd_google_analytics_id', '' ) );
}

// Facebook Pixel.
function pdrgpd_conf_facebook_pixel_id() {
	return esc_html( get_option( 'pdrgpd_facebook_pixel_id', '' ) );
}
