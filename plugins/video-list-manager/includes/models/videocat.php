<?php
/**
 * Description of Video
 *
 * 
 */
class TNT_VideoCat {
    private $videoCatID; 
    private $videoCatTitle;
    private $videoCatParent;
    
    private $_getters = array('videoCatID', 'videoCatTitle', 'videoCatParent');
    private $_setters = array('videoCatID', 'videoCatTitle', 'videoCatParent');
    
    public function __construct()
    {
        $this->videoCatID          = 0;
        $this->videoCatTitle       = "";
        $this->videoCatParent      = 0;
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
     * Function Insert Video Cat
     *
     * @return 0 : if failed 
     *         ID generated for the AUTO_INCREMENT: if sucessful
     */
    function tntInsertVideoCat()
    {
        $result = "";        
        global $wpdb;
        $tableName = $wpdb->prefix."tnt_videos_cat";
        if($this->videoCatTitle == "")
        {
            $result = 0;
        }
        else
        {
            $wpdb->insert( 
                $tableName, 
                array( 
                    'video_cat_title'       =>  $this->videoCatTitle,
                    'video_cat_parent_id'      =>  $this->videoCatParent
                ), 
                array( 
                    '%s', 
                    '%d'
                ) 
            );
            $result = $wpdb->insert_id;
        }
        return $result;
    }

    /**
     * Function Update Video Category
     *
     * @return false : if errors 
     *         the number of rows affected : if successful.
     */
    function tntUpdateVideoCat()
    {
        $result = "";        
        global $wpdb;
        $tableName = $wpdb->prefix."tnt_videos_cat";
        if($this->videoCatTitle == "")
        {
            $result = false;
        }
        else
        {
            $result = $wpdb->update( 
                $tableName, 
                array( 
                    'video_cat_title'           =>  $this->videoCatTitle,
                    'video_cat_parent_id'       =>  $this->videoCatParent
                ), 
                array('video_cat_id' => $this->videoCatID),
                array( 
                    '%s', 
                    '%d'
                ),
                array('%d') 
            );
        }
        return $result;
    }

    /**
     * Function Delete Video Category
     *
     * @return false : if errors 
     *         the number of rows affected : if successful.
     */
    function tntDeleteVideoCat()
    {
        $result = false;        
        global $wpdb;
        $tableName = $wpdb->prefix."tnt_videos_cat";
        if($this->videoCatID != 0)
        {
            $sql = "DELETE FROM $tableName WHERE video_cat_id = $this->videoCatID";
            $result = $wpdb->query($sql);    
        }
        return $result;
    }

    /**
     * Function: Get all cats
     *
     * @param   int     $ID     ID of Cat (optional)
     * @return  object          List of cats
     */
    public static function tntGetCats($catID = 0)
    {
        global $wpdb;
        $tableName = $wpdb->prefix."tnt_videos_cat";
        $sql = "";
        if($catID == 0)
        {
            $sql = "SELECT video_cat_id, video_cat_title, video_cat_parent_id 
                    FROM $tableName";
        }
        else
        {
            $sql = "SELECT video_cat_id, video_cat_title, video_cat_parent_id
                    FROM $tableName
                    WHERE video_cat_id = $catID";
        }
        $sql .= " ORDER BY video_cat_title ASC";
        $results = $wpdb->get_results($sql);
        return $results;
    }

    /**
     * Function: Get cat by ID
     *
     * @param   int             $ID     ID of Video cat (optional)
     * @return  0: if errors
     *          object          VideoCat
     */
    public function tntGetCat($catID = 0)
    {
        global $wpdb;
        $tableName = $wpdb->prefix."tnt_videos_cat";
        $sql = "";
        if($catID != 0)
        {
            $sql = "SELECT video_cat_id, video_cat_title, video_cat_parent_id
                    FROM $tableName
                    WHERE video_cat_id = $catID";
            $tntCat = $wpdb->get_row($sql);
            $this->videoCatID          = $tntCat->video_cat_id;
            $this->videoCatTitle       = $tntCat->video_cat_title;
            $this->videoCatParent      = $tntCat->video_cat_parent_id;
        }
        else
        {
            wp_die("Error function tntGetCat : not found catID"); 
            exit;
        }
    }

    /**
     * Display List of Video Category
     * @param   int     ID of category selected
     * @return  string  the selecbox contains list category
     */
    public static function tntDisplayListCat($catID = 0)
    {
        $listCat = TNT_VideoCat::tntGetCats();
        $view = "";
        $view .= '<select name="vCat">'; 
        foreach ($listCat as $cat) {
            if($catID == $cat->video_cat_id)
            {
                $view .= '<option value="'.$cat->video_cat_id.'" selected>'.$cat->video_cat_title.'</option>';    
            }
            else
            {
                $view .= '<option value="'.$cat->video_cat_id.'">'.$cat->video_cat_title.'</option>';        
            }
            
        }
        $view .= '</select>'; 
        return $view;
    }
}

?>
