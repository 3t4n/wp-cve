<?php
/**
 * Plugin Name: Modular Connector
 * Plugin URI: https://modulards.com/herramienta-gestion-webs/
 * Description: Conecta y gestiona tus webs de WordPress de una manera más fácil y eficiente. Actualizaciones de plugins y temas. Conexión con Google Analytics para generar reportes. Y una aplicación para que tu cliente entienda y valore su web. Nosotros lo llamamos #LaRevolucionDeLasWebs
 * Version: 1.0.2
 * License: GPL v3.0
 * License URI: https://www.gnu.org/licenses/gpl.html
 * Requires PHP: 7.4
 * Requires at least: 5.6
 * Author: Modular DS
 * Author URI: https://modulards.com/
 * Text Domain: modular-connector
 * Domain Path: /languages/
 */

require_once __DIR__ . '/src/bootstrap/autoload.php';
require_once __DIR__ . '/src/routes/api.php';

define('MODULAR_CONNECTOR_BASENAME', plugin_basename(__DIR__ . '/init.php'));

\Modular\Connector\Facades\Manager::init();

if (function_exists('add_action')) {
    add_action('plugins_loaded', function () {
        do_action('modular_queue_start');
    });

    add_action('plugins_loaded', function () {
        load_plugin_textdomain('modular-connector', false, dirname(plugin_basename(__FILE__)) . '/languages');
    });
}
