<?php
/**
 * Description of Video
 *
 * 
 */
class TNT_Video {
    private $videoID; 
    private $videoTitle;
    private $videoType;
    private $videoLink;
    private $videoCat;
    private $videoStatus;
    private $videoOrder;
    private $dateCreated;
    private $dateModified;
    private $userID;
    
    private $_getters = array('videoID', 'videoTitle', 'videoType', 'videoLink', 'videoCat', 'videoStatus', 'videoOrder', 'dateCreated', 'dateModified', 'userID');
    private $_setters = array('videoID', 'videoTitle', 'videoType', 'videoLink', 'videoCat', 'videoStatus', 'videoOrder', 'dateCreated', 'dateModified', 'userID');
    
    public function __construct()
    {
        $this->videoID = 0;
        $this->videoTitle = "";
        $this->videoType = 0;
        $this->videoLink = "";
        $this->videoCat = 0;
        $this->videoStatus = 1;
        $this->videoOrder = 100;
        $this->dateCreated = 0;
        $this->dateModified = 0;
        $this->userID = 0;
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
     * Function Insert Video
     *
     * @return 0 : if failed 
     *         ID generated for the AUTO_INCREMENT: if sucessful
     */
    function tntInsertVideo()
    {
        $result = "";        
        global $wpdb;
        $tableName = $wpdb->prefix."tnt_videos";
        if($this->videoTitle == "" || $this->videoLink == "")
        {
            $result = 0;
        }
        else
        {
            $wpdb->insert( 
                $tableName, 
                array( 
                    'video_title'       =>  $this->videoTitle,
                    'video_link_type'   =>  $this->videoType,
                    'video_link'        =>  $this->videoLink,
                    'video_cat'         =>  $this->videoCat,
                    'video_status'      =>  $this->videoStatus,
                    'video_order'       =>  $this->videoOrder,
                    'date_created'      =>  $this->dateCreated,
                    'date_modified'     =>  $this->dateModified,
                    'user_id'           =>  $this->userID
                ), 
                array( 
                    '%s', 
                    '%d',
                    '%s',
                    '%d',
                    '%d',
                    '%d',
                    '%d',
                    '%d',
                    '%d' 
                ) 
            );
            $result = $wpdb->insert_id;
        }
        return $result;
    }

    /**
     * Function Insert Video
     *
     * @return false : if errors 
     *         the number of rows affected : if successful.
     */
    function tntUpdateVideo()
    {
        $result = "";        
        global $wpdb;
        $tableName = $wpdb->prefix."tnt_videos";
        if($this->videoTitle == "" || $this->videoLink == "")
        {
            $result = false;
        }
        else
        {
            $result = $wpdb->update( 
                $tableName, 
                array( 
                    'video_title'       =>  $this->videoTitle,
                    'video_link_type'   =>  $this->videoType,
                    'video_link'        =>  $this->videoLink,
                    'video_cat'         =>  $this->videoCat,
                    'video_status'      =>  $this->videoStatus,
                    'video_order'       =>  $this->videoOrder,
                    'date_created'      =>  $this->dateCreated,
                    'date_modified'     =>  $this->dateModified,
                    'user_id'           =>  $this->userID
                ), 
                array('video_id' => $this->videoID),
                array( 
                    '%s', 
                    '%d',
                    '%s',
                    '%d',
                    '%d',
                    '%d',
                    '%d',
                    '%d',
                    '%d' 
                ),
                array('%d') 
            );
        }
        return $result;
    }

    /**
     * Function Delete Video
     *
     * @return false : if errors 
     *         the number of rows affected : if successful.
     */
    function tntDeleteVideo()
    {
        $result = false;        
        global $wpdb;
        $tableName = $wpdb->prefix."tnt_videos";
        if($this->videoID != 0)
        {
            $sql = "DELETE FROM $tableName WHERE video_id = $this->videoID";
            $result = $wpdb->query($sql);    
        }
        return $result;
    }

    /**
     * Function: Get videos by videoID, catID, typeID, or all
     *
     * @param   array     $args     $args('videoID' => $videoID, 'videoCat' => $catID, 'videoType' => $typeID)
     * @return  object              List of videos
     */

    public static function tntGetVideos($args = null, $keyword = null)
    {
        global $wpdb;
        $tableName1 = $wpdb->prefix."tnt_videos";
        $tableName2 = $wpdb->prefix."tnt_videos_type";
        $tableName3 = $wpdb->prefix."tnt_videos_cat";
        $v = "";
        $sql = "";
        $videoID    = (isset($args["videoID"])) ? $args["videoID"] : "0";
        $catID      = (isset($args["catID"])) ? $args["catID"] : "0";
        $typeID     = (isset($args["typeID"])) ? $args["typeID"] : "0";
        $isPublish  = (isset($args["isPublish"])) ? $args["isPublish"] : null;
        $limitText  = (isset($args["limitText"])) ? $args["limitText"] : null;
        $orderBy    = (isset($args["orderBy"])) ? $args["orderBy"] : null;
        $order      = (isset($args["order"])) ? $args["order"] : null;

        $sql = "SELECT $tableName1.video_id, $tableName1.video_title, $tableName1.video_link_type, $tableName1.video_link, $tableName1.video_cat, $tableName1.video_status, $tableName1.video_order, $tableName1.date_created, $tableName1.date_modified, $tableName1.user_id, $tableName2.video_type_title, $tableName3.video_cat_title
                FROM $tableName1, $tableName2, $tableName3
                WHERE $tableName1.video_link_type = $tableName2.video_type_id AND $tableName1.video_cat = $tableName3.video_cat_id";
        if($keyword != null)
        {
            $keyword_analysis = explode(' ', $keyword);
            $keyAmount = count($keyword_analysis);
            if($keyAmount <= 1)
            {
                $sql .= " AND $tableName1.video_title like '%".$keyword."%'";
            }
            else
            {
                $sql .= " AND (";
                $sql .= "$tableName1.video_title like '%".$keyword."%'";    
                for($i=0; $i<count($keyword_analysis); $i++)
                {
                    if(strlen($keyword_analysis[$i]) >= 3)
                    {
                        $sql .= " OR $tableName1.video_title like '%".$keyword_analysis[$i]."%'";        
                    }
                }
                $sql .= ")";
            }
        }

        if($videoID != 0)
        {
            $sql .= " AND $tableName1.video_id = $videoID";
        }
        
        if($catID != 0)
        {
            $sql .= " AND $tableName1.video_cat = $catID";
        }
        
        if($typeID != 0)
        {
            $sql .= " AND $tableName1.video_link_type = $typeID";
        }

        if($isPublish != null)
        {
            $sql .= " AND $tableName1.video_status = $isPublish";
        }

        if($orderBy != null)
        {
            $sql .= " ORDER BY $orderBy";
        }
        if($order != null)
        {
            $sql .= " $order";   
        }

        if($limitText != null)
        {
            $sql .= " $limitText";
        }
        
        $results = $wpdb->get_results($sql);
        return $results;
    }

    /**
     * Function: Get video by ID
     *
     * @param   int             $ID     ID of Video (optional)
     * @return  0: if errors
     *          object Video    Video
     */
    public function tntGetVideo($videoID = 0)
    {
        global $wpdb;
        $tableName = $wpdb->prefix."tnt_videos";
        $sql = "";
        if($videoID != 0)
        {
            $sql = "SELECT video_id, video_title, video_link_type, video_link, video_cat, video_status, video_order, date_created, date_modified, user_id
                    FROM $tableName 
                    WHERE video_id = $videoID";
            $video = $wpdb->get_row($sql);
            $this->videoID      = $video->video_id;
            $this->videoTitle   = $video->video_title;
            $this->videoType    = $video->video_link_type;
            $this->videoLink    = $video->video_link;
            $this->videoCat     = $video->video_cat;
            $this->videoStatus  = $video->video_status;
            $this->videoOrder   = $video->video_order;
            $this->dateCreated  = $video->date_created;
            $this->dateModified = $video->date_modified;
            $this->userID       = $video->user_id; 
        }
        else
        {
            wp_die("Error function tntGetVideo : not found videoID"); 
            exit;
        }
    }
}

?>
