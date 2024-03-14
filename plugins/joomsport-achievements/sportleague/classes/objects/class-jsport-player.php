<?php
/**
 * WP-JoomSport
 * @author      BearDev
 * @package     JoomSport
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

require_once JOOMSPORT_ACHV_PATH_MODELS.'model-jsport-player.php';
require_once JOOMSPORT_ACHV_PATH_OBJECTS.'class-jsport-stage.php';

class classJsportAchvPlayer
{
    private $id = null;
    public $season_id = null;
    public $object = null;
    public $lists = null;

    public function __construct($id = 0, $season_id = null, $loadLists = true)
    {
        if (!$id) {
            $this->season_id = (int) classJsportAchvRequest::get('sid');
            $this->id = get_the_ID();
        } else {
            $this->season_id = $season_id;
            $this->id = $id;
        }
        if (!$this->id) {
            die('ERROR! Player ID not DEFINED');
        }
        $this->loadObject($loadLists);
    }

    private function loadObject($loadLists)
    {
        $obj = new modelJsportAchvPlayer($this->id, $this->season_id);
        $this->object = $obj->getRow();
        if ($loadLists) {
            $this->lists = $obj->loadLists();
            
        }
        $this->lists['options']['title'] = $this->getName(false);
    }

    public function getName($linkable = false, $itemid = 0)
    {
        $pname = get_the_title($this->id);
        
        $pp = get_post($this->id);
        if(empty($pp)){
            return '';
        }
        if ($pp->post_status != 'publish' || get_post_status($this->id) == 'private') {
            $linkable = false;
        }
        if (!$linkable) {
            return $pname;
        }
        $html = '';
        if ($this->id > 0 && $pname) {
            $html = classJsportAchvLink::player($pname, $this->id, $this->season_id,false, $itemid);
        }

        return $html;
    }

    public function getDefaultPhoto()
    {
        return $this->lists['def_img'];
    }
    public function getEmblem($linkable = true, $type = 0, $class = 'emblInline', $width = 0, $light = true, $itemid = 0)
    {
        global $jsConfig;
        $html = '';
        $pp = get_post($this->id);
        if (empty($pp) || $pp->post_status != 'publish' || get_post_status($this->id) == 'private') {
            $linkable = false;
        }
        if (!isset($this->lists['def_img'])) {
            $this->loadObject(true);
        }
        
        $html = jsHelperImages::getEmblem($this->lists['def_img'], 0, $class, $width, $light);
        if ($linkable && $jsConfig->get('enbl_playerlogolinks') == '1') {
            $html = classJsportAchvLink::player($html, $this->id, $this->season_id, $itemid, $linkable);
        }

        return $html;
    }

    public function getRow()
    {
        $this->setHeaderOptions();

        return $this;
    }
    public function getRowSimple()
    {
        return $this;
    }


    public function getDescription()
    {
        $about = get_post_meta($this->id,'_jsprt_achv_player_about',true);
        return classJsportAchvText::getFormatedText($about);
    }




    public function getMatches()
    {
        
    }
    public function setHeaderOptions()
    {
        global $jsConfig;
        if ($this->season_id > 0) {
            $this->lists['options']['calendar'] = $this->season_id;
            $this->lists['options']['standings'] = $this->season_id;
        }

    }

}
