<?php
/**
 * WP-JoomSport
 * @author      BearDev
 * @package     JoomSport
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

require_once JOOMSPORT_ACHV_PATH_MODELS.'model-jsport-stage.php';

class classJsportAchvStage
{
    private $id = null;
    private $season_id = null;
    public $object = null;
    public $lists = null;

    const VIEW = 'stage';

    public function __construct($id = 0, $season_id = null)
    {
        if (!$id) {
            $this->id = get_the_ID();
        } else {
            $this->id = $id;
        }
        if (!$this->id) {
            die('ERROR! Stage ID not DEFINED');
        }
        $this->season_id = (int) get_post_meta($this->id,'_jsprt_achv_stage_season',true);;
        $this->loadObject();
    }

    private function loadObject()
    {
        $obj = new modelJsportAchvStage($this->id, $this->season_id);
        $this->object = $obj->getRow();
        if ($this->object) {
            $this->lists = $obj->loadLists();
        }
    }

    public function getObject()
    {
        

        return $this->object;
    }

    public function getName($linkable = false)
    {
        $html = '';
        $pp = get_post($this->id);
        if ($pp->post_status != 'publish' || get_post_status($this->id) == 'private') {
            $linkable = false;
        }
        if (!$this->object) {
            return '';
        }
        if (!$linkable) {
            return $this->object->post_title;
        }
        if ($this->id > 0) {
            $html = classJsportAchvLink::stage($this->object->post_title, $this->id, false, '');
        }

        return $html;
    }

    public function getRow()
    {
        $this->setHeaderOptions();
        return $this;
    }
    public function getDescription()
    {
        $descr = get_post_meta($this->id,'_jsprt_achv_stage_about',true);
 
        return classJsportAchvText::getFormatedText($descr);
    }
    public function getView()
    {
        return self::VIEW;
    }

    public function setHeaderOptions()
    {
        $this->lists['options']['standings'] = $this->season_id;
    }
    public function getMeta($metaname)
    {
        return get_post_meta($this->id,$metaname,true);

    }
}
