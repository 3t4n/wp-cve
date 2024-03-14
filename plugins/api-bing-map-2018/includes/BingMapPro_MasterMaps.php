<?php

namespace BingMapPro_MasterMaps;

if( ! defined('ABSPATH') ) die('No Access to this page');

class BingMapPro_MasterMaps{
    private $maps = [];  
    private $map;
    private $map_pins_tbl;
    private $map_shapes_tbl;
    
    public function __construct(){
        global $wpdb;
        $this->map_pins_tbl     = $wpdb->prefix . 'bingmappro_map_pins';
        $this->map_shapes_tbl   = $wpdb->prefix. 'bingmappro_map_shapes';
    }

    public function loadMaps( $countInfo = false ){
        global $wpdb;
       
        $allMaps = $wpdb->get_results(" SELECT  id, map_title, map_width, 
                                                map_height, map_width_type, 
                                                map_height_type, map_active, map_shortcode
                                        FROM    {$wpdb->prefix}bingmappro_maps
                                        ORDER BY created_at DESC ");

        if( $countInfo ){
            $countDataPins = $wpdb->get_results("SELECT map_id, 
                                                    COUNT(pin_id) as pin_no 
                                             FROM $this->map_pins_tbl
                                             WHERE pin_active = true 
                                             GROUP BY map_id
                                             ORDER BY pin_id ASC"); 

            $countDataShapes = $wpdb->get_results("SELECT map_id, 
                                                    COUNT( shape_id) as shape_no
                                                   FROM {$this->map_shapes_tbl} 
                                                   GROUP by map_id ");                                                                                      
        }                                
        
        for( $i = 0; $i < count( $allMaps ); $i++ ){
            $map_id             = $allMaps[$i]->id;
            $map_title          = $allMaps[$i]->map_title;
            $map_width          = $allMaps[$i]->map_width;
            $map_height         = $allMaps[$i]->map_height;
            $map_width_type     = $allMaps[$i]->map_width_type;
            $map_height_type    = $allMaps[$i]->map_height_type;            
            $map_active         = $allMaps[$i]->map_active;
            $map_shortcode      = $allMaps[$i]->map_shortcode;
            $map = new BingMapPro_Map( $map_id, $map_title, $map_active, $map_width, $map_height, $map_width_type, $map_height_type, $map_shortcode );
            if( $countInfo ){
                foreach( $countDataPins as $item ){
                    if( $item->map_id == $map_id ){
                        $map->pin_no = $item->pin_no;  
                    }
                }

                foreach( $countDataShapes as $shape ){
                    if( $shape->map_id == $map_id ){
                        $map->shape_no = $shape->shape_no; 
                    }
                }
                  
            }

           
            $this->maps[ $map_id ] = $map;
        }
    }


    public function getCount(){ return count( $this->maps ); }
    
    public function mapsToArray(){
        $new_array = array();
        foreach( $this->maps as $new_map ){           
            $new_array[ $new_map->getMapId() ] = $new_map->mapToArray();
        }
        
        return $new_array;
     }
     
    public function saveNewMap( $map_title, $bmp_extras ){
        global $wpdb;
        //$map_shortcode = strtolower( ''. $map_title.'' );
        //$map_shortcode = str_replace( ' ', '_', $map_shortcode );
        //$map_shortcode = preg_replace("/[^A-Za-z0-9 ]/", '', $map_shortcode);
        $map_shortcode = '';
        $map_exists = false;

        //check if map title exists 
        foreach( $this->maps as $map ){
            if( strcmp( strtolower($map->getTitle()), strtolower( $map_title ) ) == 0 ){
                $map_exists = true;
            }
        }
        if( ! $map_exists ){
            $wpdb->query( $wpdb->prepare(  "INSERT INTO {$wpdb->prefix}bingmappro_maps 
                                            (map_title, map_shortcode, disable_mousewheel, compact_nav, disable_zoom, map_refresh, created_at )  
                                            VALUES (%s, %s, %d, %d, %d, %d, CURRENT_TIMESTAMP);",
                                            $map_title, $map_shortcode, $bmp_extras['dsom'] =='true', $bmp_extras['cnb'] =='true',  $bmp_extras['dz']=='true', $bmp_extras['mr'] =='true' ));

            $max_id = $wpdb->get_results( "SELECT MAX(id) as maxid FROM {$wpdb->prefix}bingmappro_maps;");
            return new BingMapPro_Map( $max_id[0]->maxid, $map_title, true, '400', '100', 'px', '%', $map_shortcode );
        }else{
            return false;
        }
    }

    public function getAllMaps(){
        return $this->maps;
    }
    
    public function deleteMap( $map_id ){
        global $wpdb;
        $wpdb->query( $wpdb->prepare( "DELETE FROM {$wpdb->prefix}bingmappro_maps WHERE id = %d;", $map_id ) );
        $wpdb->query( $wpdb->prepare( "DELETE FROM {$wpdb->prefix}bingmappro_map_pins WHERE map_id = %d;", $map_id ));
        $wpdb->query( $wpdb->prepare( "DELETE FROM {$wpdb->prefix}bingmappro_map_shortcodes WHERE map_id = %d", $map_id ));
    }
}

class BingMapPro_Map{
    private $map_id;
    private $map_title;    
    private $map_active; 

    private $map_width = '400';
    private $map_height = '100';
    private $map_width_type = 'px';
    private $map_height_type = '%';
    private $map_shortcode;

    function __construct( $map_id, $map_title, $map_active, $map_width, $map_height, $map_width_type, $map_height_type, $map_shortcode ){ 
        $this->map_id          = $map_id;
        $this->map_title       = $map_title;
        $this->map_active      = $map_active;
        $this->map_width       = $map_width;
        $this->map_height      = $map_height;
        $this->map_width_type  = $map_width_type;
        $this->map_height_type = $map_height_type;
        $this->map_shortcode   = $map_shortcode;        
    }

    function getSimpleMap(){

    }

    function getTitle(){ return $this->map_title; }
    function setTitle( $title ){ $this->map_title = $title; }

    function getMapId(){ return $this->map_id;}
    
    function getMapShortcode(){ return $this->map_shortcode; }
    function setMapShortcode( $newShortcode ){ $this->map_shortcode = $newShortcode; }

    public function mapToArray(){
        $map_array = [];
        $map_array['id']            = $this->map_id;
        $map_array['active']        = $this->map_active;
        $map_array['title']         = $this->map_title;
        $map_array['width']         = $this->map_width;
        $map_array['width_type']    = $this->map_width_type;
        $map_array['height']        = $this->map_height;
        $map_array['height_type']   = $this->map_height_type;
        $map_array['shortcode']     = $this->map_shortcode;        

        if( isset( $this->pin_no ) )
            $map_array['pin_no']      = $this->pin_no;
        
        if( isset( $this->shape_no ) )
            $map_array['shape_no']    = $this->shape_no;
        
        return $map_array;
    }
    public function disableMap( $map_id ){
        global $wpdb;
        $table = $wpdb->prefix.'bingmappro_maps';
        $map_id = intval( $map_id );

        $result = $wpdb->query( $wpdb->prepare( "UPDATE {$table} SET map_active = not map_active WHERE id=%d;", $map_id ));

        return $result;
    }

    private function getMapTableName(){
        global $wpdb;
        return $table = $wpdb->prefix.'bingmappro_maps';
    }
    


}

class BingMapPro_Map_Full{
    function __construct(){}

    private function getMapTableName(){
        global $wpdb;
        return $table = $wpdb->prefix.'bingmappro_maps';
    }

    public function getFullMap( $map_id ){
        global $wpdb;
        $table = $this->getmapTableName(); 
        $fullMap = $wpdb->get_results( $wpdb->prepare( "SELECT *, look_at_location as lock_zoom FROM {$table} WHERE id = %d;", $map_id ));
        
        if( count( $fullMap ) > 0 )
            return  $fullMap;   
        else
            return null;
    }
    public function updateFullMap( $data ){
        global $wpdb;
        $table          = $this->getMapTableName();
        $tb_title       = sanitize_text_field( $data['map_title'] );            //map_title
        $tb_id          = sanitize_text_field( (int)$data['id'] );              //id
        $tb_active      = sanitize_text_field( (int)$data['map_active']  );     //map_active
        $tb_width       = sanitize_text_field( $data['map_width']);             //map_width
        $tb_width_type  = sanitize_text_field( $data['map_width_type'] );       //map_width_type
        $tb_height      = sanitize_text_field( $data['map_height']);            //map_height
        $tb_height_type = sanitize_text_field( $data['map_height_type']);       //map_height_type
        $tb_lat         = sanitize_text_field( $data['map_start_lat']);         //map_start_lat
        $tb_long        = sanitize_text_field( $data['map_start_long']);        //map_start_long
        $tb_zoom        = sanitize_text_field( (int)$data['map_zoom']);         //map_zoom
        $tb_type        = sanitize_text_field( (int)$data['map_type']);         //map_type
        $tb_html_class  = sanitize_text_field( $data['html_class']);            //html_class
        $tb_shortcode   = strtolower( '['. $tb_title.']' );     
        $tb_shortcode   = str_replace( ' ', '_', $tb_shortcode );              //map_shortcode
        $tb_disable_mwz = sanitize_text_field( (int)$data['disable_mousewheel']);   //disable_mousewheel
        $tb_disable_zoom        = sanitize_text_field( (int)$data['disable_zoom']);        //disable_zoom
        $tb_compact_nav         = sanitize_text_field( (int)$data['compact_nav']);      //compact_nav
        $tb_show_infobox_hover  = sanitize_text_field( (int)$data['styling_enabled']);      //styling_enabled field
        $tb_toggle_fullscreen   = sanitize_text_field( (int)$data['toggle_fullscreen']);      //show fullscreen icon
        $tb_cluster             = sanitize_text_field( (int)$data['cluster']);
        $tb_lock_zoom           = sanitize_text_field( (int)$data['lock_zoom'] );

        return $wpdb->query( $wpdb->prepare( "UPDATE {$table} SET 
                                        map_active = %d,
                                        map_title = %s,
                                        map_width = %s,
                                        map_width_type = %s,
                                        map_height = %s,
                                        map_height_type = %s,
                                        map_start_lat = %s,
                                        map_start_long = %s,
                                        map_zoom = %d,
                                        map_type = %d,
                                        html_class = %s,                                        
                                        disable_mousewheel = %d,
                                        disable_zoom = %d,
                                        compact_nav = %d,
                                        styling_enabled = %d,
                                        toggle_fullscreen = %d,
                                        cluster = %d,
                                        look_at_location = %d
                                        WHERE   id = %d;",  $tb_active, $tb_title, $tb_width, $tb_width_type, $tb_height, $tb_height_type,
                                                            $tb_lat, $tb_long, $tb_zoom, $tb_type, $tb_html_class, $tb_disable_mwz, 
                                                            $tb_disable_zoom, $tb_compact_nav, $tb_show_infobox_hover, $tb_toggle_fullscreen, $tb_cluster, $tb_lock_zoom,
                                                            $tb_id ));
                                               
    }

    public function getActiveMaps( $filtered = true ){
        global $wpdb;
        $map_table = $wpdb->prefix . 'bingmappro_maps';
        $filterText = 'map_active = 1';
        if( ! $filtered )
            $filterText = '1=1';
            
        $active_maps =  $wpdb->get_results("SELECT  id,
                                                    map_title,
                                                    map_start_lat, 
                                                    map_start_long, 
                                                    map_type, 
                                                    map_zoom, 
                                                    compact_nav,
                                                    disable_mousewheel,
                                                    disable_zoom,
                                                    map_shortcode,
                                                    map_width,
                                                    map_height,
                                                    map_width_type,
                                                    map_height_type,
                                                    html_class,
                                                    styling_enabled,
                                                    toggle_fullscreen,
                                                    look_at_location as lock_zoom,
                                                    cluster
                                            FROM {$map_table} WHERE {$filterText};"); 
        return $active_maps;
    }

    public function getMapAllActivePins( $map_id ){
        global $wpdb;
        $map_pins_table     = $wpdb->prefix . 'bingmappro_map_pins';
        $pins_table         = $wpdb->prefix . 'bingmappro_pins';

        $all_map_pins = $wpdb->get_results( $wpdb->prepare("SELECT pin_id, map_id, pin_active, pin_lat, pin_long, icon, 
                                                               IFNULL(icon_link, '') AS icon_link,
                                                               IFNULL(pin_title, '') AS pin_title,
                                                               IFNULL(pin_desc, '') AS pin_desc,
                                                               IFNULL(data_json, '') AS data_json,
                                                               IFNULL(pin_image_one, '') AS pin_image_one,
                                                               IFNULL(pin_image_two, '') AS pin_image_two
                                                            FROM {$map_pins_table}
                                                            INNER JOIN {$pins_table} ON {$map_pins_table}.pin_id = {$pins_table}.id 
                                                            WHERE {$map_pins_table}.map_id = %d AND pin_active = 1;", (int)$map_id ));

        foreach( $all_map_pins as $map_pin ){
            $map_pin->data_json = stripcslashes ( $map_pin->data_json );
        }                                                        

        return $all_map_pins;
    }

    public function bmp_getAllActiveShapesForMap( $map_id ){
        global $wpdb;
        $shapes_map_table   = $wpdb->prefix . 'bingmappro_map_shapes'; 
        $shapes_table       = $wpdb->prefix . 'bingmappro_shapes'; 

        $allShapes = $wpdb->get_results($wpdb->prepare( "SELECT shape_id as id,
                                                    s_name as name,
                                                    s_type as type,
                                                    bodyopacity as fillOpacity,
                                                    shapedata as shapeData,
                                                    s_style as style,
                                                    infotype as infoType,
                                                    infosimpletitle as infoSimpleTitle,
                                                    infosimpledesc as infoSimpleDesc,
                                                    infoadvanced as infoAdvanced FROM {$shapes_map_table}
                                                    INNER JOIN {$shapes_table} ON
                                                    {$shapes_map_table}.shape_id = {$shapes_table}.id
                                                    WHERE map_id = %d;", intval( $map_id) ));
        return $allShapes;
    }



    public function getMapPins( $map_id ){
        global $wpdb;
        $map_id = (int)( sanitize_text_field( $map_id ) );
        $pins_map_table = $wpdb->prefix . 'bingmappro_map_pins'; 
        $map_pins = $wpdb->get_results( $wpdb->prepare("SELECT pin_id, pin_active FROM {$pins_map_table}                                                         
                                                        WHERE map_id = %d GROUP BY pin_id;" , $map_id ) );
        if( count( $map_pins ) > 0 ){
            $map_ids = [];
            foreach($map_pins as $map  ){
                $obj['pin_id'] = (int)$map->pin_id;
                $obj['pin_active'] = (int)$map->pin_active;
                array_push( $map_ids, $obj );
            }
            return $map_ids;
        }else    
            return 0;                   
    }

    public function getMapPinsFull( $map_id ){
        global $wpdb;
        $map_id = (int)( sanitize_text_field( $map_id ) );
        $pins_map_table = $wpdb->prefix . 'bingmappro_map_pins'; 
        $pins_table     = $wpdb->prefix . 'bingmappro_pins';
        $map_pins = $wpdb->get_results( $wpdb->prepare("SELECT pin_id, map_id, pin_active, pin_lat, pin_long, icon, 
                                                               IFNULL(icon_link, '') AS icon_link,
                                                               IFNULL(pin_title, '') AS pin_title,
                                                               IFNULL(pin_desc, '') AS pin_desc,
                                                               IFNULL(data_json, '') AS data_json,
                                                               IFNULL(pin_image_one, '') AS pin_image_one,
                                                               IFNULL(pin_image_two, '') AS pin_image_two
                                                        FROM {$pins_map_table}  
                                                        LEFT OUTER JOIN {$pins_table} ON {$pins_map_table}.pin_id = {$pins_table}.id       
                                                        WHERE map_id = %d AND pin_active = 1 GROUP BY pin_id;" , $map_id ) );
        if( count( $map_pins ) > 0 ){
            return $map_pins;
        }else    
            return 0;                   
    }

    public function addPinToMap( $map_id, $pin_id ){
        global $wpdb;
        $pins_map_table = $wpdb->prefix . 'bingmappro_map_pins'; 
        $map_id = (int)( $map_id );
        $pin_id = (int)(  $pin_id );        
        $result = $wpdb->query( $wpdb->prepare("INSERT  INTO {$pins_map_table} (map_id, pin_id, pin_active) 
                                                        VALUES (%d, %d, %d);", $map_id, $pin_id, true ));

        return $result;                                                        
    }

    public function removePinFromMap( $map_id, $pin_id ){
        global $wpdb;
        $pins_map_table = $wpdb->prefix . 'bingmappro_map_pins'; 
        $map_id = (int)( sanitize_text_field( $map_id ));
        $pin_id = (int)( sanitize_text_field( $pin_id ));        
        $result = $wpdb->query( $wpdb->prepare(" DELETE FROM {$pins_map_table} where map_id = %d AND pin_id = %d;",
                                                        $map_id, $pin_id ));

        return $result;                                                        
    }

    public function disablePinFromMap( $map_id, $pin_id ){
        global $wpdb;
        $pins_map_table = $wpdb->prefix . 'bingmappro_map_pins'; 
        $map_id = (int)( $map_id );
        $pin_id = (int)( $pin_id );        
        $result = $wpdb->query( $wpdb->prepare(" UPDATE {$pins_map_table} SET pin_active = NOT pin_active where map_id = %d AND pin_id = %d;",
                                                        $map_id, $pin_id ));

        return $result;                                                        
    }


}

class BingMapPro_MapView{
    private $tbl_map_views;
    public function __construct(){
        global $wpdb;
        $this->tbl_map_views = $wpdb->prefix . 'bingmappro_map_shortcodes';
    }

    public function addNew( $map_id, $name, $lat, $long, $zoom ){
        global $wpdb;
        $map_shortcode = preg_replace("/[^A-Za-z0-9 ]/", '', $name );

        $query = $wpdb->query( $wpdb->prepare("INSERT INTO $this->tbl_map_views (map_id, shortcode, s_lat, s_long, s_zoom)
                                                VALUES ( %d, %s, %s, %s, %d)", intval($map_id), $map_shortcode, $lat, $long, intval($zoom) ));
        return $query;                                                
    }

    public function getLastCreated( $map_id ){
        global $wpdb;
        $query = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$this->tbl_map_views} 
                                                      WHERE map_id = %d 
                                                      ORDER BY created_at DESC
                                                      LIMIT 1", intval($map_id) ));
        return $query;                                                      
    }

    public function getAllViewsForMap( $map_id ){
        global $wpdb;
        $query = $wpdb->get_results( $wpdb->prepare( "SELECT * from {$this->tbl_map_views}
                                                      WHERE map_id = %d
                                                      ORDER BY created_at DESC
                                                    ", intval( $map_id) ));
        return $query;                                                    
    }

    public function getView( $id ){
        global $wpdb;
        $query = $wpdb->get_results( $wpdb->prepare( "SELECT * from {$this->tbl_map_views}
                                                      WHERE id = %d
                                                      ORDER BY created_at DESC
                                                    ", intval( $id ) ));
        $result = null;
        if( sizeof( $query ) > 0 )
            $result = $query[0];

        return $result;      
    }

    public function getSimpleView( $id ){
        global $wpdb;
        $query = $wpdb->get_results( $wpdb->prepare( "SELECT s_lat, s_long, s_zoom from {$this->tbl_map_views}
                                                      WHERE id = %d
                                                      ORDER BY created_at DESC
                                                    ", intval( $id ) ));
        return $query;       
    }

    public function deleteView( $id ){
        global $wpdb;
        $query = $wpdb->query( $wpdb->prepare( "DELETE FROM {$this->tbl_map_views}
                                                WHERE id = %d", intval( $id )));
        return $query;                                                
    }

}