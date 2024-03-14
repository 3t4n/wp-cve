<?php
/**
 * WP-JoomSport
 * @author      BearDev
 * @package     JoomSport
 */
foreach (glob(__DIR__.DIRECTORY_SEPARATOR.'extrafields/*.php') as $filename) {
    include $filename;
}
// type 0 - player, 1 - team, 2 - match, 3 - season, 4 - club
class classJsportAchvEf
{
    public $type = null;

    public function __construct($type)
    {
        $this->type = $type;
    }
    public function getValue($id, $fieldObj, $season_id = 0)
    {
        global $jsDatabase;
        $value = null;
        switch($fieldObj->type){
            case '0':

                $meta = get_post_meta($id,'_jsprt_achv_player_ef',true);
                
                break;
            case '1':
                
                break;
            case '2':
                $meta = get_post_meta($id,'_jsprt_achv_stage_ef',true);
                break;
            case '3':
                $meta = get_post_meta($id,'_jsprt_achv_season_ef',true);
                break;

        }
        
        // field type 0-text,1-radio,2-editor,3-select,4-link
        $efObj = isset($meta[$fieldObj->id])?$meta[$fieldObj->id]:null;
        //var_dump($efObj);
        if (!empty($efObj)) {
            switch ($fieldObj->field_type) {
                case 0:
                       $value = classExtrafieldAchvText::getValue($efObj);

                    break;
                case 1:
                       $value = classExtrafieldAchvRadio::getValue($efObj);

                    break;
                case 2:
                       $value = classExtrafieldAchvEditor::getValue($efObj);

                    break;
                case 3:
                       $value = classExtrafieldAchvSelect::getValue($efObj);

                    break;
                case 4:
                       $value = classExtrafieldAchvLink::getValue($efObj);

                    break;
                default:
                    $value = null;
                    break;
            }
        }

        return $value;
    }
    public function getList($id, $season_id)
    {
        global $jsDatabase;
        $return = array();
        $sql = "SELECT *"
                . " FROM {$jsDatabase->db->jsprtachv_ef}"
                . " WHERE type='".$this->type."' AND published = '1'";
                //.(classJsportUser::getUserId() ? '' : " AND faccess='0'");
        $ef = $jsDatabase->select($sql);

        for ($intA = 0; $intA < count($ef); ++$intA) {
            $return[$ef[$intA]->name] = self::getValue($id, $ef[$intA], $season_id);
        }

        return $return;
    }

    public function getListTable()
    {
        global $jsDatabase;

        $query = 'SELECT ef.name, ef.id'
                .' FROM '.$jsDatabase->db->jsprtachv_ef.' as ef '
                ." WHERE ef.published=1 AND ef.type = '".$this->type."'"
                //." AND ef.e_table_view = '1' AND ef.fdisplay = '1'"
                .' ORDER BY ef.ordering';

        $ef = $jsDatabase->select($query);

        return $ef;
    }
    public function getListDisplay()
    {
        global $jsDatabase;

        $query = 'SELECT ef.name, ef.id'
                .' FROM '.$jsDatabase->db->jsprtachv_ef.' as ef '
                ." WHERE ef.published=1 AND ef.type = '".$this->type."'"
                //." AND ef.display_playerlist = '1' AND ef.fdisplay = '1'"
                .' ORDER BY ef.ordering';

        $ef = $jsDatabase->select($query);

        return $ef;
    }
    
}
