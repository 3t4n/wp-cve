<?php
/**
 * Description of Video
 *
 * 
 */
class TNT_VideoType {
    private $videoTypeID; 
    private $videoTypeTitle;
    
    private $_getters = array('videoTypeID', 'videoTypeTitle');
    private $_setters = array('videoTypeID', 'videoTypeTitle');
    
    public function __construct()
    {
        $this->videoTypeID          = 0;
        $this->videoTypeTitle       = "";
    } 
    
    public function __get($property) {
        if (in_array($property, $this->_getters)) 
        {
            return $this->$property;
        }         
        else if (method_exists($this, '_get_' . $property))
        {
            return call_user_func(array($this, '_get_' . $property));
        }
    }

    public function __set($property, $value) {
        if (in_array($property, $this->_setters)) 
        {
            $this->$property = $value;
        } 
        else if (method_exists($this, '_set_' . $property))
        {
            call_user_func(array($this, '_set_' . $property), $value);
        }
    }
    
    /**
     * Function Insert Video Type
     *
     * @return 0 : if failed 
     *         ID generated for the AUTO_INCREMENT: if sucessful
     */
    function tntInsertVideoType()
    {
        $result = "";        
        global $wpdb;
        $tableName = $wpdb->prefix."tnt_videos_type";
        if($this->videoTypeTitle == "")
        {
            $result = 0;
        }
        else
        {
            $wpdb->insert( 
                $tableName, 
                array( 
                    'video_type_title'          =>  $this->videoTypeTitle
                ), 
                array( 
                    '%s'
                ) 
            );
            $result = $wpdb->insert_id;
        }
        return $result;
    }

    /**
     * Function Update Video Type
     *
     * @return false : if errors 
     *         the number of rows affected : if successful.
     */
    function tntUpdateVideoType()
    {
        $result = "";        
        global $wpdb;
        $tableName = $wpdb->prefix."tnt_videos_type";
        if($this->videoTypeTitle == "")
        {
            $result = false;
        }
        else
        {
            $result = $wpdb->update( 
                $tableName, 
                array( 
                    'video_type_title'          =>  $this->videoTypeTitle
                ), 
                array('video_type_id' => $this->videoTypeID),
                array( 
                    '%s'
                ),
                array('%d') 
            );
        }
        return $result;
    }

    /**
     * Function Delete Video Type
     *
     * @return false : if errors 
     *         the number of rows affected : if successful.
     */
    function tntDeleteVideoType()
    {
        $result = false;        
        global $wpdb;
        $tableName = $wpdb->prefix."tnt_videos_type";
        if($this->typeID != 0)
        {
            $sql = "DELETE FROM $tableName WHERE video_type_id = $this->videoTypeID";
            $result = $wpdb->query($sql);    
        }
        return $result;
    }

    /**
     * Function: Get all types
     *
     * @param   int     $ID     ID of type (optional)
     * @return  object          List of types
     */
    public static function tntGetTypes($typeID = 0)
    {
        global $wpdb;
        $tableName = $wpdb->prefix."tnt_videos_type";
        $sql = "";
        if($typeID == 0)
        {
            $sql = "SELECT video_type_id, video_type_title 
                    FROM $tableName";
        }
        else
        {
            $sql = "SELECT video_type_id, video_type_title
                    FROM $tableName
                    WHERE video_type_id = $typeID";
        }
        $results = $wpdb->get_results($sql);
        return $results;
    }

    /**
     * Function: Get type by ID
     *
     * @param   int             $ID     ID of Video Type (optional)
     * @return  0: if errors
     *          object          Video Type
     */
    public function tntGetType($typeID = 0)
    {
        global $wpdb;
        $tableName = $wpdb->prefix."tnt_videos_type";
        $sql = "";
        if($typeID != 0)
        {
            $sql = "SELECT video_type_id, video_type_title
                    FROM $tableName
                    WHERE video_type_id = $typeID";
            $tntType = $wpdb->get_row($sql);
            $this->videoTypeID          = $tntType->video_type_id;
            $this->videoTypeTitle       = $tntType->video_type_title;
        }
        else
        {
            wp_die("Error function tntGetType : not found typeID"); 
            exit;
        }
    }

    /**
     * Display List of Video Type
     * @param   int     ID of type selected
     * @return  string  the selecbox contains list types
     */
    public static function tntDisplayListType($typeID = 0)
    {
        $listType = TNT_VideoType::tntGetTypes();
        $view = "";
        $view .= '<select name="vLinkType">'; 
        foreach ($listType as $type) {
            if($typeID == $type->video_type_id)
            {
                $view .= '<option value="'.$type->video_type_id.'" selected>'.$type->video_type_title.'</option>';    
            }
            else
            {
                $view .= '<option value="'.$type->video_type_id.'">'.$type->video_type_title.'</option>';        
            }
            
        }
        $view .= '</select>'; 
        return $view;
    }
}

?>
