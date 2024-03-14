<?php
/**
 * WP-JoomSport
 * @author      BearDev
 * @package     JoomSport
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

require_once JOOMSPORT_ACHV_PATH_MODELS.'model-jsport-season.php';
//require_once JOOMSPORT_PATH_ENV_CLASSES.'class-jsport-participant.php';

class classJsportAchvSeason
{
    private $id = null;
    public $object = null;
    public $season = null;
    public $lists = null;
    public $modelObj = null;

    public function __construct($id = 0)
    {
        $this->id = $id;
        if (!$this->id) {
            $this->id = get_the_ID();
        }
        if (!$this->id) {
            die('ERROR! SEASON ID not DEFINED');
        }
        $this->loadObject($this->id);
        
    }

    private function loadObject($id)
    {
        $obj = $this->modelObj = new modelJsportAchvSeason($id);
        $this->object = $obj->getRow();

        $this->lists = $obj->loadLists();
        if(!empty($this->object)){
            //$this->lists['optionsT']['title'] = $this->object->tsname;
        }
    }

    public function getObject()
    {
        return $this->object;
    }

    //


    public function getRow()
    {
        if(!empty($this->object)){

            
            $this->setHeaderOptions();
            return $this;
        }else{
            JError::raiseError('404', 'Not found');
        }
        
        
    }

    public function setHeaderOptions()
    {
       
    }
    
    public function isComplex(){
        //return get_post_meta($this->id,'_joomsport_season_complex',true);
    }
    public function getSeasonChildrens(){
        $args = array(
                'post_parent' => $this->id,
                'post_type'   => 'joomsport_season', 
                'numberposts' => -1,
                'post_status' => 'published',
                'orderby' => 'menu_order title',
                'order'   => 'ASC',
        );
        $children = get_children( $args );
        return $children;
    }
    public function getDescription()
    {
        $about = get_post_meta($this->id,'_jsprt_achv_season_about',true);
        return classJsportAchvText::getFormatedText($about);
    }
}
