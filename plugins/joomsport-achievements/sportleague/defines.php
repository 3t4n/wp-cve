<?php
/**
 * WP-JoomSport
 * @author      BearDev
 * @package     JoomSport
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
<?php
global $wpdb;
//environment
define('JOOMSPORT_ACHV_ENV', 'wordpress');
//define root
define('JSPLW_ACHV_PATH_MAINCOMP', ABSPATH);
//environment
define('JOOMSPORT_ACHV_TEMPLATE', 'default');
// main directory
define('JOOMSPORT_ACHV_SL_PATH', __DIR__.DIRECTORY_SEPARATOR);
// css directory
define('JOOMSPORT_ACHV_PATH_CSS', JOOMSPORT_ACHV_SL_PATH.'assets'.DIRECTORY_SEPARATOR.'css'.DIRECTORY_SEPARATOR);
// js directory
define('JOOMSPORT_ACHV_PATH_JS', JOOMSPORT_ACHV_SL_PATH.'assets'.DIRECTORY_SEPARATOR.'js'.DIRECTORY_SEPARATOR);
// images directory
define('JOOMSPORT_ACHV_PATH_IMAGES', ABSPATH.DIRECTORY_SEPARATOR.'media'.DIRECTORY_SEPARATOR.'bearleague'.DIRECTORY_SEPARATOR);

//thumb
define('JOOMSPORT_ACHV_PATH_IMAGES_THUMB', JOOMSPORT_ACHV_PATH_IMAGES.'thumb'.DIRECTORY_SEPARATOR);

// classes directory
define('JOOMSPORT_ACHV_PATH_CLASSES', JOOMSPORT_ACHV_SL_PATH.'classes'.DIRECTORY_SEPARATOR);
// helpers directory
define('JOOMSPORT_ACHV_PATH_SL_HELPERS', JOOMSPORT_ACHV_SL_PATH.'helpers'.DIRECTORY_SEPARATOR);
// views directory
define('JOOMSPORT_ACHV_PATH_VIEWS', JOOMSPORT_ACHV_SL_PATH.'views'.DIRECTORY_SEPARATOR.JOOMSPORT_ACHV_TEMPLATE.DIRECTORY_SEPARATOR);
// views elements directory
define('JOOMSPORT_ACHV_PATH_VIEWS_ELEMENTS', JOOMSPORT_ACHV_PATH_VIEWS.'elements'.DIRECTORY_SEPARATOR);
// objects directory
define('JOOMSPORT_ACHV_PATH_OBJECTS', JOOMSPORT_ACHV_PATH_CLASSES.'objects'.DIRECTORY_SEPARATOR);

// classes directory
define('JOOMSPORT_ACHV_PATH_ENV', JOOMSPORT_ACHV_SL_PATH.'base'.DIRECTORY_SEPARATOR.JOOMSPORT_ACHV_ENV.DIRECTORY_SEPARATOR);
// classes directory
define('JOOMSPORT_ACHV_PATH_ENV_CLASSES', JOOMSPORT_ACHV_PATH_ENV.'classes'.DIRECTORY_SEPARATOR);
// models directory
define('JOOMSPORT_ACHV_PATH_MODELS', JOOMSPORT_ACHV_PATH_ENV.'models'.DIRECTORY_SEPARATOR);

//

define('JOOMSPORT_ACHV_LIVE_URL', get_site_url());
define('JOOMSPORT_ACHV_LIVE_URL_IMAGES', JOOMSPORT_ACHV_LIVE_URL.'media'.DIRECTORY_SEPARATOR.'bearleague'.DIRECTORY_SEPARATOR);
define('JOOMSPORT_ACHV_LIVE_URL_IMAGES_DEF', plugin_dir_url( __FILE__ ).'assets'.DIRECTORY_SEPARATOR.'images'.DIRECTORY_SEPARATOR);

define('JOOMSPORT_ACHV_LIVE_ASSETS', plugin_dir_url( __FILE__ ).'assets'.DIRECTORY_SEPARATOR);

//defines database table names



//some config

define('JSCONF_ACHV_PLAYER_DEFAULT_IMG', 'player_st.png');
define('JSCONF_ACHV_TEAM_DEFAULT_IMG', 'teams_st.png');

?>