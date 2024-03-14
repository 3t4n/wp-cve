<?php

namespace BingMapPro_MasterShapes;

if( ! defined('ABSPATH') ) die('No Access to this page');

    class BingMapPro_Shape{
        private $shapes_table;
        private $map_shapes_table;
        private $db;
        public function __construct(){
            global $wpdb;
            $this->shapes_table = $wpdb->prefix . 'bingmappro_shapes';
            $this->map_shapes_table = $wpdb->prefix . 'bingmappro_map_shapes';
            $this->db = $wpdb;
        }

        public function bmp_getAllShapes(){
            $allShapes = $this->db->get_results("SELECT id, 
                                                        s_name as name, 
                                                        s_type as type,
                                                        bodyopacity as fillOpacity,
                                                        shapedata as shapeData,
                                                        s_style as style,
                                                        infotype as infoType,
                                                        infosimpletitle as infoSimpleTitle,
                                                        infosimpledesc as infoSimpleDesc,
                                                        infoadvanced as infoAdvanced,
                                                        maplat, 
                                                        maplong,
                                                        mapzoom,
                                                        maptype
                                            FROM $this->shapes_table
                                            ORDER BY created_at DESC; ");
            return $allShapes;
        }

        public function bmp_getAllFilteredShapes( $map_id ){
            $allShapes = $this->db->get_results(
                         $this->db->prepare( "SELECT id, 
                        s_name as name, 
                        s_type as type,
                        infotype as infoType,
                        mapzoom,
                        maptype
                    FROM $this->shapes_table
                    WHERE ID NOT IN ( SELECT shape_id FROM $this->map_shapes_table WHERE map_id = %d )
                    ORDER BY created_at DESC; ", intval( $map_id ) ));

            return $allShapes; 
        }

        public function getShapesLinkedToMap(){
            global $wpdb;
            $query = $wpdb->get_results("SELECT * FROM {$this->map_shapes_table}  GROUP by shape_id, map_id ORDER BY shape_id ASC");
            $result = array();
            foreach( $query as $item ){
                if(isset( $result[ $item->shape_id ] )){
                    if( ! is_array( $result[ $item->shape_id ] )){
                        $result[ $item->shape_id ] = array();
                    }
                }else{
                    $result[ $item->shape_id ] = array();
                }
                
                array_push( $result[ $item->shape_id ], $item->map_id );
            }
    
            return $result;  
        }

        public function bmp_getAllActiveShapesForMap( $map_id ){
            $allShapes = $this->db->get_results( $this->db->prepare( "SELECT  id, 
                                                                s_name as name, 
                                                                s_type as type,
                                                                bodyopacity as fillOpacity,
                                                                shapedata as shapeData,
                                                                s_style as style,
                                                                infotype as infoType,
                                                                infosimpletitle as infoSimpleTitle,
                                                                infosimpledesc as infoSimpleDesc,
                                                                infoadvanced as infoAdvanced,
                                                                maplat, 
                                                                maplong, 
                                                                mapzoom,
                                                                maptype
                                                        FROM $this->map_shapes_table
                                                        INNER JOIN $this->shapes_table ON
                                                        id = map_id
                                                        WHERE map_id = %d
                                                        AND  shape_active = 1                                           
                                                        ORDER BY created_at DESC", $map_id ));
            return $allShapes;
        }

        public function bmp_getAllMapShapes( $map_id ){
            $result = $this->db->get_results( $this->db->prepare( " SELECT shape_id as id, s_name as name, s_type as type, infotype as infoType FROM $this->map_shapes_table
                                                                    INNER JOIN $this->shapes_table ON 
                                                                    $this->map_shapes_table.shape_id = $this->shapes_table.id                                                                            
                                                                    WHERE map_id = %d;", intval( $map_id ) ));
            return $result;
        }

        public function bmp_getShapeById( $bmp_shape_id ){
            $bmp_shape_id = intval( $bmp_shape_id );
           // $bmp_shape = $this->db->query( $this->db->prepare( "SELECT  id, 
           $bmp_shape = $this->db->prepare( "SELECT  id, 
                                            s_name as name, 
                                            s_type as type,
                                            bodyopacity as fillOpacity,
                                            shapedata as shapeData,
                                            s_style as style,
                                            infotype as infoType,
                                            infosimpletitle as infoSimpleTitle,
                                            infosimpledesc as infoSimpleDesc,
                                            infoadvanced as infoAdvanced,
                                            maplat,
                                            maplong, 
                                            mapzoom,
                                            maptype
                                    FROM $this->shapes_table                                                                
                                    WHERE id = %d; ", $bmp_shape_id ); 
            $bmp_result =  $this->db->get_results( $bmp_shape, ARRAY_A );
            return $bmp_result;
        }

        public function bmp_newShape( $bmp_data ){
            $name               = sanitize_text_field( $bmp_data['name'] );        
            $s_type             = sanitize_text_field( $bmp_data['type']);           
            $fill_opacity       = floatval( $bmp_data['fillOpacity']);           
            $infotype           = sanitize_text_field( $bmp_data['infoType']);           
            $infoSimpleTitle    = sanitize_text_field( $bmp_data['infoSimpleTitle']);           
            $infoSimpleDesc     = sanitize_text_field( $bmp_data['infoSimpleDesc'] );            
            $infoAdvanced       = $bmp_data['infoAdvanced'];        
            $shapeData          = $bmp_data['shapeData'];     
            $shapeStyle         = $bmp_data['style'];        

            $maplat             = sanitize_text_field( $bmp_data['maplat']);
            $maplong            = sanitize_text_field( $bmp_data['maplong']);
            $mapzoom            = intval( $bmp_data['mapzoom']);
            $maptype            = sanitize_text_field( $bmp_data['maptype'] );

            
            $result = $this->db->query( $this->db->prepare(" INSERT INTO $this->shapes_table ( s_name, s_type, bodyopacity, infotype, infosimpletitle, infosimpledesc, 
                                                                 infoadvanced, shapedata, s_style, maplat, maplong, mapzoom, maptype )
                                                                 VALUES ( %s, %s, %f, %s, %s, %s, %s, %s, %s, %s, %s, %d, %s );", 
                                                                 $name, $s_type, $fill_opacity, $infotype, $infoSimpleTitle, $infoSimpleDesc, 
                                                                 $infoAdvanced, $shapeData, $shapeStyle, $maplat, $maplong, $mapzoom, $maptype ));
              
            $result = true;                     
            return $result;
                                                            
        }

        public function bmp_updateShape( $bmp_data ){
            $id                 = intval( $bmp_data['id'] );
            $name               = sanitize_text_field( $bmp_data['name'] );
            $s_type             = sanitize_text_field( $bmp_data['type']);
            $fill_opacity       = floatval( $bmp_data['fillOpacity']);
            $infotype           = sanitize_text_field( $bmp_data['infoType']);
            $infoSimpleTitle    = sanitize_text_field( $bmp_data['infoSimpleTitle']);
            $infoSimpleDesc     = sanitize_text_field( $bmp_data['infoSimpleDesc'] );
            $infoAdvanced       = $bmp_data['infoAdvanced'];
            $shapeData          = $bmp_data['shapeData'];
            $shapeStyle         = $bmp_data['style'];  

            $maplat             = sanitize_text_field( $bmp_data['maplat']);
            $maplong            = sanitize_text_field( $bmp_data['maplong']);
            $mapzoom            = intval( $bmp_data['mapzoom']);
            $maptype            = sanitize_text_field( $bmp_data['maptype'] );

            return $this->db->query( $this->db->prepare(" UPDATE $this->shapes_table 
                                                          SET
                                                            s_name = %s,
                                                            s_type = %s,
                                                            bodyopacity = %f, 
                                                            infotype = %s,
                                                            infosimpletitle = %s, 
                                                            infosimpledesc = %s,
                                                            infoadvanced = %s,
                                                            shapedata = %s,
                                                            s_style = %s,
                                                            maplat = %s,
                                                            maplong = %s,
                                                            mapzoom = %d,
                                                            maptype = %s
                                                          WHERE 
                                                            id = %d; ", $name, $s_type, $fill_opacity, $infotype, $infoSimpleTitle, 
                                                                        $infoSimpleDesc, $infoAdvanced, $shapeData, $shapeStyle, 
                                                                        $maplat, $maplong, $mapzoom, $maptype,
                                                                        $id ) );
        }

        public function bmp_deleteShape( $bmp_shape_id ){
            $bmp_shape_id = intval( $bmp_shape_id );
            //also delete from map shapes
            $this->db->query( $this->db->prepare(" DELETE FROM $this->map_shapes_table 
                                                   WHERE shape_id = %d;", $bmp_shape_id ));

            return $this->db->query( $this->db->prepare( " DELETE FROM $this->shapes_table 
                                                            WHERE id = %d; ", $bmp_shape_id ));
        }

        public function bmp_addShapeToMap( $bmp_shape_id, $bmp_map_id ){
            $bmp_shape_id = intval( $bmp_shape_id );
            $bmp_map_id   = intval( $bmp_map_id );
            return $this->db->query( $this->db->prepare( " INSERT INTO $this->map_shapes_table ( map_id, shape_id, shape_active)
                                                           VALUE ( %d, %d, %d);", $bmp_map_id, $bmp_shape_id, 1 ) );
        }

        public function bmp_removeShapeFromMap( $bmp_shape_id, $bmp_map_id ){
            $bmp_shape_id = intval( $bmp_shape_id );
            $bmp_map_id   = intval( $bmp_map_id );
            return $this->db->query( $this->db->prepare( "DELETE FROM $this->map_shapes_table
                                                            WHERE map_id = %d AND shape_id = %d; ", $bmp_map_id, $bmp_shape_id ));
        }

        public function bmp_changeShapeStateForMap( $bmp_shape_id, $bmp_map_id, $state ){
            $bmp_shape_id = intval( $bmp_shape_id );
            $bmp_map_id   = intval( $bmp_map_id );
            $state        = intval( $state );
            return $this->db->query( $this->db->prepare( " UPDATE $this->map_shapes_table 
                                                           SET shape_active = %d 
                                                           WHERE map_id = %d AND shape_id = %d; ", $state, $bmp_map_id, $bmp_shape_id ));
        }

        public function bmp_getLastAddedShape(){
            $lastShape = $this->db->get_results("SELECT id, 
                                                        s_name as name, 
                                                        s_type as type,
                                                        bodyopacity as fillOpacity,
                                                        shapedata as shapeData,
                                                        s_style as style,
                                                        infotype as infoType,
                                                        infosimpletitle as infoSimpleTitle,
                                                        infosimpledesc as infoSimpleDesc,
                                                        infoadvanced as infoAdvanced,
                                                        maplat,
                                                        maplong,
                                                        mapzoom,
                                                        maptype
                                            FROM $this->shapes_table
                                            ORDER BY created_at DESC
                                            LIMIT 1; "); 
            return $lastShape;                                            
                                            
        }


    }