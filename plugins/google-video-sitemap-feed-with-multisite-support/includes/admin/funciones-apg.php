<?php
//Definimos las variables
$apg_video_sitemap = [ 	
	'plugin' 		=> 'APG Google Video Sitemap Feed', 
	'plugin_uri' 	=> 'google-video-sitemap-feed-with-multisite-support', 
	'donacion' 		=> 'https://artprojectgroup.es/tienda/donacion',
	'soporte' 		=> 'https://artprojectgroup.es/tienda/soporte-tecnico',
	'plugin_url' 	=> 'https://artprojectgroup.es/plugins-para-wordpress/apg-google-video-sitemap-feed', 
	'ajustes' 		=> 'options-general.php?page=xml-sitemap-video', 
	'puntuacion' 	=> 'https://wordpress.org/support/view/plugin-reviews/google-video-sitemap-feed-with-multisite-support'
 ];

//Número máximo de víedos por feed
$maximo_videos  = 50000;

//Carga el idioma
function apg_video_sitemap_inicia_idioma() {
    load_plugin_textdomain( 'google-video-sitemap-feed-with-multisite-support', null, dirname( DIRECCION_apg_video_sitemap ) . '/languages' );
}
add_action( 'plugins_loaded', 'apg_video_sitemap_inicia_idioma' );

//Enlaces adicionales personalizados
function apg_video_sitemap_enlaces( $enlaces, $archivo ) {
	global $apg_video_sitemap;

	if ( $archivo == DIRECCION_apg_video_sitemap ) {
		$plugin		= apg_video_sitemap_plugin( $apg_video_sitemap[ 'plugin_uri' ] );
		$enlaces[]	= '<a href="' . $apg_video_sitemap[ 'donacion' ] . '" target="_blank" title="' . __( 'Make a donation by ', 'google-video-sitemap-feed-with-multisite-support' ) . 'APG"><span class="genericon genericon-cart"></span></a>';
		$enlaces[]	= '<a href="'. $apg_video_sitemap[ 'plugin_url' ] . '" target="_blank" title="' . $apg_video_sitemap[ 'plugin' ] . '"><strong class="artprojectgroup">APG</strong></a>';
		$enlaces[]	= '<a href="https://www.facebook.com/artprojectgroup" title="' . __( 'Follow us on ', 'google-video-sitemap-feed-with-multisite-support' ) . 'Facebook" target="_blank"><span class="genericon genericon-facebook-alt"></span></a> <a href="https://twitter.com/artprojectgroup" title="' . __( 'Follow us on ', 'google-video-sitemap-feed-with-multisite-support' ) . 'Twitter" target="_blank"><span class="genericon genericon-twitter"></span></a> <a href="https://es.linkedin.com/in/artprojectgroup" title="' . __( 'Follow us on ', 'google-video-sitemap-feed-with-multisite-support' ) . 'LinkedIn" target="_blank"><span class="genericon genericon-linkedin"></span></a>';
		$enlaces[]	= '<a href="https://profiles.wordpress.org/artprojectgroup/" title="' . __( 'More plugins on ', 'google-video-sitemap-feed-with-multisite-support' ) . 'WordPress" target="_blank"><span class="genericon genericon-wordpress"></span></a>';
		$enlaces[]	= '<a href="mailto:info@artprojectgroup.es" title="' . __( 'Contact with us by ', 'google-video-sitemap-feed-with-multisite-support' ) . 'e-mail"><span class="genericon genericon-mail"></span></a> <a href="skype:artprojectgroup" title="' . __( 'Contact with us by ', 'google-video-sitemap-feed-with-multisite-support' ) . 'Skype"><span class="genericon genericon-skype"></span></a>';
		$enlaces[]	= apg_video_sitemap_plugin( $apg_video_sitemap[ 'plugin_uri' ] );
	}
	
	return $enlaces;
}
add_filter( 'plugin_row_meta', 'apg_video_sitemap_enlaces', 10, 2 );

//Añade el botón de configuración
function apg_video_sitemap_enlace_de_ajustes( $enlaces ) { 
	global $apg_video_sitemap;

	$enlaces_de_ajustes = [
		'<a href="' . $apg_video_sitemap[ 'ajustes' ] . '" title="' . __( 'Settings of ', 'google-video-sitemap-feed-with-multisite-support' ) . $apg_video_sitemap[ 'plugin' ] .'">' . __( 'Settings', 'google-video-sitemap-feed-with-multisite-support' ) . '</a>', 
		'<a href="' . $apg_video_sitemap[ 'soporte' ] . '" title="' . __( 'Support of ', 'google-video-sitemap-feed-with-multisite-support' ) . $apg_video_sitemap[ 'plugin' ] .'">' . __( 'Support', 'google-video-sitemap-feed-with-multisite-support' ) . '</a>'
	];
	foreach ( $enlaces_de_ajustes as $enlace_de_ajustes )	{
		array_unshift( $enlaces, $enlace_de_ajustes );
	}
	
	return $enlaces; 
}
$plugin = DIRECCION_apg_video_sitemap; 
add_filter( "plugin_action_links_$plugin", 'apg_video_sitemap_enlace_de_ajustes' );

//Obtiene toda la información sobre el plugin
function apg_video_sitemap_plugin( $nombre ) {
	global $apg_video_sitemap;

	$respuesta	= get_transient( 'apg_video_sitemap_plugin' );
	if ( $respuesta === false ) {
		$respuesta = wp_remote_get( 'https://api.wordpress.org/plugins/info/1.2/?action=plugin_information&request[slug]=' . $nombre  );
		set_transient( 'apg_video_sitemap_plugin', $respuesta, 24 * HOUR_IN_SECONDS );
	}
	if ( ! is_wp_error( $respuesta ) ) {
		$plugin = json_decode( wp_remote_retrieve_body( $respuesta ) );
	} else {
	   return '<a title="' . sprintf( __( 'Please, rate %s:', 'google-video-sitemap-feed-with-multisite-support' ), $apg_video_sitemap[ 'plugin' ] ) . '" href="' . $apg_video_sitemap[ 'puntuacion' ] . '?rate=5#postform" class="estrellas">' . __( 'Unknown rating', 'google-video-sitemap-feed-with-multisite-support' ) . '</a>';
	}

    $rating = [
	   'rating'		=> $plugin->rating,
	   'type'		=> 'percent',
	   'number'		=> $plugin->num_ratings,
	];
	ob_start();
	wp_star_rating( $rating );
	$estrellas = ob_get_contents();
	ob_end_clean();

	return '<a title="' . sprintf( __( 'Please, rate %s:', 'google-video-sitemap-feed-with-multisite-support' ), $apg_video_sitemap[ 'plugin' ] ) . '" href="' . $apg_video_sitemap[ 'puntuacion' ] . '?rate=5#postform" class="estrellas">' . $estrellas . '</a>';
}

//Hoja de estilo
function apg_video_sitemap_estilo() {
	if ( strpos( $_SERVER[ 'REQUEST_URI' ], 'xml-sitemap-video' ) !== false || strpos( $_SERVER[ 'REQUEST_URI' ], 'plugins.php' ) !== false ) {
        wp_register_style( 'apg_video_sitemap_hoja_de_estilo', plugins_url( 'assets/css/style.css', DIRECCION_apg_video_sitemap ) ); //Carga la hoja de estilo
        wp_enqueue_style( 'apg_video_sitemap_hoja_de_estilo' ); //Carga la hoja de estilo global
	}
}
add_action( 'admin_enqueue_scripts', 'apg_video_sitemap_estilo' );
