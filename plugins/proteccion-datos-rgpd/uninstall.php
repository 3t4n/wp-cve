<?php
/**
 * Runs on Uninstall of Protección de datos - RGPD
 *
 * @package   Protección de datos - RGPD
 * @author    ABCdatos
 * @license   GPLv2
 * @link      https://taller.abcdatos.net/
 */

// Uninstall deleting used options.
// Desinstalación, borrando las opciones empleadas.

// if uninstall.php is not called by WordPress, die.
defined( 'WP_UNINSTALL_PLUGIN' ) || die( 'No se permite el acceso.' );

require_once plugin_dir_path( __FILE__ ) . 'lista_opciones.php';

// Removes all the option values defined in the array.
foreach ( pdrgpd_lista_opciones() as $nombre_opcion ) {
	delete_option( $nombre_opcion );
	// For site options in multisite.
	delete_site_option( $nombre_opcion );
}
