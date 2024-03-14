<?php
/**
 * WP-JoomSport
 * @author      BearDev
 * @package     JoomSport
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
require_once JOOMSPORT_ACHV_PATH_OBJECTS.'class-jsport-season.php';
require_once JOOMSPORT_ACHV_PATH_OBJECTS.'class-jsport-stage.php';
class classJsportAchvCalendar
{
    private $id = null;

    public $object = null;
    public $lists = null;

    const VIEW = 'calendar';

    public function __construct($id = 0, $season_id = null)
    {
        if (!$id) {
            $this->id = get_the_ID();
        } else {
            $this->id = $id;
        }
        if (!$this->id) {
            die('ERROR! Season ID not DEFINED');
        }
        $this->object = new modelJsportAchvSeason($this->id);
        $this->getStages();
        $this->setHeaderOptions();
    }


    public function getObject()
    {
        
        
        return $this;
    }

    public function getStages()
    {
        global $wpdb;
        
        $stages = $this->object->getAllStages();

        for($intA=0;$intA<count($stages); $intA++){
            $this->lists['stages'][] = new classJsportAchvStage($stages[$intA]);
        }
        $this->lists['stages_cat'] = $wpdb->get_results('SELECT id,name FROM '.$wpdb->jsprtachv_stages.' WHERE published="1" ORDER BY ordering', OBJECT_K) ;
        
        $this->lists['stages_ef'] = $wpdb->get_col('SELECT name FROM '.$wpdb->jsprtachv_ef.' WHERE published="1" AND type="2" AND display_table="1" ORDER BY ordering') ;
    }

    public function getRow()
    {
        
        return $this;
    }

    public function getView()
    {
        return self::VIEW;
    }

    public function setHeaderOptions()
    {
        $this->lists['options']['standings'] = $this->id;
    }
}
