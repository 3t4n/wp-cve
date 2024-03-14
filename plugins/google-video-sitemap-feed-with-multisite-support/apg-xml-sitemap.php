<?php
/*
Plugin Name: APG Google Video Sitemap Feed
Version: 2.1
Plugin URI: https://wordpress.org/plugins/google-video-sitemap-feed-with-multisite-support/
Description: Dynamically generates a Google Video Sitemap and automatically submit updates to Google and Bing. Compatible with WordPress Multisite installations. Created from <a href="https://profiles.wordpress.org/users/timbrd/" target="_blank">Tim Brandon</a> <a href="https://wordpress.org/plugins/google-news-sitemap-feed-with-multisite-support/" target="_blank"><strong>Google News Sitemap Feed With Multisite Support</strong></a> and <a href="https://profiles.wordpress.org/labnol/" target="_blank">Amit Agarwal</a> <a href="https://wordpress.org/plugins/xml-sitemaps-for-videos/" target="_blank"><strong>Google XML Sitemap for Videos</strong></a> plugins. Added new functions and ideas (Vimeo and Dailymotion support) by <a href="https://twitter.com/ludobonnet" target="_blank">Ludo Bonnet</a>.
Author: Art Project Group
Author URI: https://artprojectgroup.es/
Requires at least: 2.6
Tested up to: 6.2

Text Domain: google-video-sitemap-feed-with-multisite-support
Domain Path: /languages

@package APG Google Video Sitemap Feed
@category Core
@author Art Project Group
*/

//Igual no deberías poder abrirme
defined( 'ABSPATH' ) || exit;

//Definimos constantes
define( 'DIRECCION_apg_video_sitemap', plugin_basename( __FILE__ ) );

//Funciones generales de APG
include_once( 'includes/admin/funciones-apg.php' );

//Action Scheduler
include_once( 'vendor/autoload.php');

//Registra las opciones
function apg_video_sitemap_registra_opciones() {
    register_setting( 'apg_video_sitemap_settings_group', 'xml_video_sitemap' );
}
add_action( 'admin_init', 'apg_video_sitemap_registra_opciones' );

//Inicializa la opción Google Video Sitemap Feed Options en el menú Ajustes
function apg_video_sitemap_menu_administrador() {
	add_options_page( __( 'Google Video Sitemap Feed Options.', 'google-video-sitemap-feed-with-multisite-support' ), 'Google Video Sitemap Feed', 'manage_options', 'xml-sitemap-video', 'apg_video_sitemap_formulario' );
}
add_action( 'admin_menu', 'apg_video_sitemap_menu_administrador' );

//Pinta el formulario de configuración y guarda los campos
function apg_video_sitemap_formulario() {
    include( 'includes/formulario.php' );
}

//Clase
include( 'includes/admin/clases/xml.php' );

//Fuerza la limpieza de Action Scheduler cada mes
add_filter( 'action_scheduler_retention_period', function() {
    return WEEK_IN_SECONDS;
} );

//Obtiene información de los vídeos publicados vía Action Scheduler
function apg_video_sitemap_procesamiento( $identificador, $proveedor ) {
	APGSitemapVideo::obtiene_informacion( $identificador, $proveedor );
}
add_action( 'apg_video_sitemap_procesamiento', 'apg_video_sitemap_procesamiento', 10 , 2 );

//Controla si se ha actualizado el plugin 
function apg_video_sitemap_actualiza( $upgrader_object, $opciones ) {
    $plugin_apg = plugin_basename( __FILE__ );
 
    if ( $opciones[ 'action' ] == 'update' && $opciones[ 'type' ] == 'plugin' ) {
        foreach ( $opciones[ 'plugins' ] as $plugin ) {
            if ( $plugin == $plugin_apg ) {
                global $wp_rewrite;

                $wp_rewrite->flush_rules(); //Regenera los enlaces permanentes
                delete_option( 'gn-sitemap-video-feed-mu-version' ); //Esta opción ya no es necesaria
                delete_transient( 'xml_video_sitemap_consulta' );
            }
        }
    }
}
add_action( 'upgrader_process_complete', 'apg_video_sitemap_actualiza', 10, 2 );

//Eliminamos todo rastro del plugin al desinstalarlo
function apg_video_sitemap_desinstalar() {
	delete_transient( 'apg_video_sitemap_plugin' );
	delete_transient( 'xml_video_sitemap_consulta' );
    delete_transient( 'xml_video_sitemap_procesado' );
    delete_transient( 'xml_video_sitemap' );
    $configuracion = get_option( 'xml_video_sitemap' );
    if ( ! empty( $configuracion ) ) {
        foreach ( $configuracion as $url ) {
            delete_transient( $url );
        }
    }
	delete_option( 'xml_video_sitemap' );
}
register_uninstall_hook( __FILE__, 'apg_video_sitemap_desinstalar' );

//Controla la desactivación del plugin
function apg_video_sitemap_desactivador() {
    APGSitemapVideo::desactivar();
}
register_deactivation_hook( __FILE__, 'apg_video_sitemap_desactivador' );
