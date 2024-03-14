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
//load defines
require_once dirname(__FILE__).DIRECTORY_SEPARATOR.'defines.php';

//load DB class (joomla in this case)
require_once JOOMSPORT_ACHV_PATH_ENV.'classes'.DIRECTORY_SEPARATOR.'class-jsport-database-base.php';
//require_once JOOMSPORT_ACHV_PATH_ENV.'classes'.DIRECTORY_SEPARATOR.'class-jsport-addtag.php';
// get database object
global $jsDatabase;
$jsDatabase = new classJsportAchvDatabaseBase();

//load request class 
require_once JOOMSPORT_ACHV_PATH_ENV.'classes'.DIRECTORY_SEPARATOR.'class-jsport-request.php';
//load link class
require_once JOOMSPORT_ACHV_PATH_ENV.'classes'.DIRECTORY_SEPARATOR.'class-jsport-link.php';

//load text class
require_once JOOMSPORT_ACHV_PATH_ENV.'classes'.DIRECTORY_SEPARATOR.'class-jsport-text.php';
//load extra fields class
require_once JOOMSPORT_ACHV_PATH_CLASSES.'class-jsport-extrafields.php';

//load helper
require_once JOOMSPORT_ACHV_PATH_SL_HELPERS.'js-helper-images.php';
require_once JOOMSPORT_ACHV_PATH_SL_HELPERS.'js-helper.php';

//execute task
require_once JOOMSPORT_ACHV_PATH_ENV.'classes'.DIRECTORY_SEPARATOR.'class-jsport-controller.php';
$controllerAchvSportLeague = new classJsportAchvController();
// add css

//echo memory_get_usage()/1024.0 . " kb <br />";
//echo microtime(TRUE)-$time_start;
?>