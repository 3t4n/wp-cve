<?php

namespace BingMapPro_Shapes;

if( ! defined('ABSPATH') ) die('No Access to this page');

include_once( 'BingMapPro_Includes.php');
include_once( 'BingMapPro_Infobox.php');

use BingMapPro_MasterMaps;
use BingMapPro_MasterShapes;
use BingMapPro_Includes\BingMapPro_Includes as BingMapPro_Includes;
use BingMapPro_Infobox\BingMapPro_Infobox as BingMapPro_Infobox;

class BingMapPro_Shapes{
    public static function bmp_shapesPageHtml( $bmp_menu_links, $bmp_api_key, $bmp_infobox_sizes ){ 
    ?>
        
        <div class='wrap'>
            <div class="container-fluid">
                <?php
                    BingMapPro_Includes::bmp_internalization();
                    BingMapPro_Includes::bmp_loading_screen();  
                    BingMapPro_Includes::bmp_error_screen();                 
                    BingMapPro_Includes::bmp_donate();
                    wp_enqueue_media();

                    $map_obj    = new BingMapPro_MasterMaps\BingMapPro_Map_Full();
                    $shape_obj  = new BingMapPro_MasterShapes\BingMapPro_Shape();
                    $active_maps    = $map_obj->getActiveMaps( false );
                    $shapes_to_map  = $shape_obj->getShapesLinkedToMap(); 
                                        
                    echo $bmp_menu_links;


                    wp_nonce_field( 'nonce_action_bing_map_pro', 'nonce_bing_map_pro');             
                ?>

                <div class="panel panel-default bmp-shapes-main-panel">

                    <div class="panel-heading">
                        <h2> <?php  esc_html_e('Shapes', 'bing-map-pro'); ?> </h2>
                    </div>

                    <div class="panel-body">
                        <div class="row shapes-menu-div"> <!-- menu -->
                            <ul>
                                <li>
                                    <img id="shapes_menu_line" class='ripple' title="<?php esc_html_e('New Line Shape', 'bing-map-pro'); ?>" 
                                    src="<?php echo BMP_PLUGIN_URL.'/images/icons/shapes-line.png';?>" data-toggle='tooltip' data-placement='bottom'
                                     title="<?php esc_html_e("New Line Shape", 'bing-map-pro');?>" />
                                </li>
                                <li>
                                    <img  id="shapes_menu_circle" class='ripple'  data-toggle='tooltip' data-placement='bottom'
                                    title="<?php esc_html_e('New Circle Shape', 'bing-map-pro'); ?>" 
                                        src="<?php echo BMP_PLUGIN_URL.'/images/icons/shapes-circle.png';?>" alt="<?php esc_html_e("New Circle Shape", 'bing-map-pro');?>" />
                                </li>
                                <li>
                                    <img id="shapes_menu_polygon" class='ripple' title="<?php esc_html_e('New Polygon Shape', 'bing-map-pro'); ?>" 
                                        data-toggle='tooltip' data-placement='bottom'
                                        src="<?php echo BMP_PLUGIN_URL.'/images/icons/shapes-polygon.png';?>" alt="<?php esc_html_e("New Polygon Shape", 'bing-map-pro');?>" />
                                </li>
                            </ul>

                        </div>

                        <table  id="bmp_table_shapes" data-unique-id='id'>

                        </table>
                    
                    </div>
               
               </div>
            </div>
        </div>

        <!--  modals -->
        <!-- line   -->
        <div class="modal bmp-modal-shape-line" data-backdrop="static" style="z-index: 10001;">
            <div class="modal-dialog modal-lg" style="min-width: 65%;">
            <div class="modal-content">
                <div class="modal-headline"></div>
                    <div class="modal-header">
                    <!--    <button type='button' class="close" data-dismiss='modal'> &times; </button> -->
                        <h3 class="modal-title"> <?php esc_html_e('New Polyline', 'bing-map-pro');?></h3>                        
                    </div>
                    <div class="modal-body">
                      
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="row info-block">

                                <div class="form-inline">
                                    <label for='bmp_line_name'> <?php esc_html_e('Name', 'bing-map-pro'); ?> </label>
                                    <input type='text' placeholder="<?php esc_html_e('Polyline One', 'bing-map-pro');?>"  id="bmp_line_name" class='form-control required' value="" />
                                </div>  

                                <div class="form-inline">
                                    <label for='bmp_line_color'> <?php esc_html_e('Stroke Color', 'bing-map-pro'); ?> </label>
                                    <input type='color' style="width: 140px;"  id="bmp_line_color" class='form-control' value="" />
                                </div>  


                                <div class="form-inline">
                                    <label for='bmp_line_color'> <?php esc_html_e('Stroke Thickness', 'bing-map-pro'); ?> </label>
                                    
                                    <input type='number'   class="form-control" id="bmp_line_thickness" value="1"/>                                            
                                    
                                </div>  

                                <div style="height: 20px"></div>

                                <div class="panel panel-default">
                                    <div class="panel-header" style="text-align: center;">                                      
                                    </div>

                                    <div class="panel-body">                               
     
                                    <div class="row bmp-set-row">
                                        <div class="col-sm-4">
                                            <div class='h5'> <?php esc_html_e('Polyline Infobox: ', 'bing-map-pro'); ?> </div>
                                            
                                        </div>
                                        <div class="col-sm-7 div-bmp-pin-info">
                                            <div class="row">

                                                <label class='radio-inline' for="radio_bmp_pin_none">
                                                    <input type="radio" checked='checked' style="position: relative;" name="radio_bmp_pin_use" id="radio_bmp_line_none" 
                                                        data-toggle='tooltip' data-value='none' data-placement="left" title="<?php esc_html_e('Show on map', 'bing-map-pro');?>" /> 
                                                    <?php esc_html_e('None', 'bing-map-pro'); ?>    
                                                </label>
                                            </div>
                                            <div class="row">
                                                <label class='radio-inline'>                                                  
                                                        <input type="radio" style="position: relative;"  name="radio_bmp_pin_use" id="radio_bmp_line_simple" 
                                                           data-value='simple' data-toggle='tooltip'  data-placement="left" title="<?php esc_html_e('Show on map', 'bing-map-pro');?>" />
                                                        <input type="button" class="button button-primary btn_bmp_pin_info_simple" id='' 
                                                         value="<?php esc_html_e('Simple', 'bing-map-pro'); ?>">                                                  
                                                </label>
                                            </div>
                                            <div class="row">
                                                <label for="" class='radio-inline'>
                                                    <input type="radio" style="position: relative;" name="radio_bmp_pin_use" id="radio_bmp_line_advanced" 
                                                     data-value='advanced'  data-toggle='tooltip' data-placement="left" title="<?php esc_html_e('Show on map', 'bing-map-pro');?>" />
                                                    <input type="button" class="button button-primary btn_bmp_pin_info_advanced" id='' 
                                                             value="<?php esc_html_e('Advanced', 'bing-map-pro'); ?>">
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                             
                                    </div>

                                </div>


                            </div>
                        </div>

                        <div class="col-sm-8" id='bmp_map_section'>
                            <div class="map-lines-controls">                                
                                <button class="button btn-new-line" title="<?php esc_html_e('New','bing-map-pro');?>" 
                                    data-toggle='tooltip' data-placement='top' >
                                    <i class="fa fa-plus-circle" style="color: #6fa1cc;"></i>
                                </button>
                                <button class="button btn-save-line" title="<?php esc_html_e('Save','bing-map-pro');?>" 
                                    data-toggle='tooltip' data-placement='top' > 
                                    <i class="fa fa-save" style="color: green"></i> 
                                </button>
                                <button class="button btn-edit-line" title="<?php esc_html_e('Edit','bing-map-pro');?>" 
                                    data-toggle='tooltip' data-placement='top' > 
                                    <i class="fa fa-edit" style="color:green;"></i> 
                                </button>
                                <button class="button btn-delete-line" title="<?php esc_html_e('Delete','bing-map-pro');?>" 
                                    data-toggle='tooltip' data-placement='top' >
                                    <i class="fa fa-trash" style="color: red;"></i>
                                </button>
                            </div> 
                            <div class="map-lines-panning" style="float: right; margin-top: -38px;">
                                <?php esc_html_e('Map View', 'bing-map-pro'); ?> <input type="checkbox" id="map_lines_panning" checked data-toggle="toggle" data-on="<?php esc_html_e('UnLocked', 'bing-map-pro');?>"
                                 data-off="<?php esc_html_e('Locked', 'bing-map-pro'); ?>" data-onstyle="success" data-offstyle="danger">
                            </div>

                            <div id="bmp_shapes_map_line" style="height: 400px" ></div>
                            
                            <!-- Show saved polylines -->
                            <p></p>
                            <div>
                                <input type="checkbox" data-size='mini' data-on="<?php esc_html_e('Hide Saved Polylines', 'bing-map-pro');?>" 
                                data-off="<?php esc_html_e('Show Saved Polylines', 'bing-map-pro');?>" data-toggle="toggle" id='showSavedPolylines'>

                                <input type="checkbox" data-size='mini' data-on="<?php esc_html_e('Hide All Saved Shapes', 'bing-map-pro');?>" 
                                    data-off="<?php esc_html_e('Show All Saved Shapes', 'bing-map-pro');?>" data-toggle="toggle" id='showSavedShapesLin'>
                            </div>    
 
                        </div>
                    </div>
                       
                        <div>
                            <div style='text-align: left;'>
                                <p></p>                                                    
                                <button type='button' id='save' class='button button-primary' > <?php esc_html_e('Save', 'bing-map-pro');?> </button>
                                <button type='button' id='saveAndNew' class='button button-primary' > <?php esc_html_e('Save & New', 'bing-map-pro');?> </button>
                                <button type='button' id='cancel' class='button button-secondary' data-dismiss='modal'> <?php esc_html_e('Cancel', 'bing-map-pro');?> </button>  
                            </div>
                        </div>
                    </div>
                </div> 
            </div>
            <input type='hidden' id='bmp_line_action' value='new' />
        </div>  
        <!-- circle -->
        <div class="modal bmp-modal-shape-circle" style="z-index: 10001;">
            <div class="modal-dialog modal-lg" style="min-width: 65%; z-index: 100001;">
            <div class="modal-content">
                <div class="modal-headline"></div>
                    <div class="modal-header">
                        
                        <h3 class="modal-title"> <?php esc_html_e('New Circle', 'bing-map-pro');?></h3>                        
                    </div>
                    <div class="modal-body">
                      
                        <div class="row">
                        <div class="col-sm-4">
                            <div class="row info-block">
                                <div class="form-inline">
                                    <label for='bmp_circle_name'> <?php esc_html_e('Name', 'bing-map-pro'); ?> </label>
                                    <input type='text' placeholder="<?php esc_html_e('Circle One', 'bing-map-pro');?>"  id="bmp_circle_name" class='form-control required' value="" />
                                </div>  

                                <div class="form-inline">
                                    <label for='bmp_circle_fill_color'> <?php esc_html_e('Fill Color', 'bing-map-pro'); ?> </label>
                                    <input type='color' style="width: 140px;"  id="bmp_circle_fill_color" class='form-control' value="#fff000" />
                                </div>  

                                <div class="form-inline">
                                    <label for='bmp_cirle_stroke_color'> <?php esc_html_e('Stroke Color', 'bing-map-pro'); ?> </label>
                                    <input type='color' style="width: 140px;"  id="bmp_circle_stroke_color" class='form-control' value="" />
                                </div>  


                                <div class="form-inline">
                                    <label for='bmp_circle_thickness'> <?php esc_html_e('Stroke Thickness', 'bing-map-pro'); ?> </label>
                                    
                                    <input type='number'   class="form-control" id="bmp_circle_thickness" value="1"/>                                            
                                </div> 

                                <div class="form-inline">
                                    <label for='bmp_circle_radius'> <?php esc_html_e('Radius', 'bing-map-pro'); ?> </label>
                                    
                                    <input type='number' class="form-control" id="bmp_circle_radius" value="1"/>                                            
                                </div> 
                                

                                <div class="form-inline">
                                    <label for='bmp_circle_fill_opacity'> <?php esc_html_e('Fill Color Opacity', 'bing-map-pro'); ?> </label>
                                    
                                    <div class="slidecontainer">
                                            <input style="width: 90%;" type="range" min="0.1" step='0.1' max="1.0" id="bmp_circle_fill_opacity" value="0.4" class="slider" id="myRange">
                                                <strong> <span id='bmp_circle_fill_opacity_val'> 0.4 </span> </strong>
                                        </div>                                          
                                </div> 

                                <div style="height: 20px"></div>

                                <div class="panel panel-default">
                                    <div class="panel-header" style="text-align: center;">                                      
                                    </div>

                                    <div class="panel-body">                               

                                    <div class="row bmp-set-row">
                                        <div class="col-sm-4">
                                            <div class='h5'> <?php esc_html_e('Circle Infobox: ', 'bing-map-pro'); ?> </div>
                                            
                                        </div>
                                        <div class="col-sm-7 div-bmp-pin-info">
                                            <div class="row">

                                                <label class='radio-inline' for="radio_bmp_pin_none">
                                                    <input type="radio" checked='checked' style="position: relative;" name="radio_bmp_pin_use" id="radio_bmp_circle_none" 
                                                        data-toggle='tooltip' data-value='none' data-placement="left" title="<?php esc_html_e('Show on map', 'bing-map-pro');?>" /> 
                                                    <?php esc_html_e('None', 'bing-map-pro'); ?>    
                                                </label>
                                            </div>
                                            <div class="row">
                                                <label class='radio-inline'>                                                  
                                                        <input type="radio" style="position: relative;"  name="radio_bmp_pin_use" id="radio_bmp_circle_simple" 
                                                        data-value='simple' data-toggle='tooltip'  data-placement="left" title="<?php esc_html_e('Show on map', 'bing-map-pro');?>" />
                                                        <input type="button" class="button button-primary btn_bmp_pin_info_simple" id='' 
                                                        value="<?php esc_html_e('Simple', 'bing-map-pro'); ?>">                                                  
                                                </label>
                                            </div>
                                            <div class="row">
                                                <label for="" class='radio-inline'>
                                                    <input type="radio" style="position: relative;" name="radio_bmp_pin_use" id="radio_bmp_circle_advanced" 
                                                    data-value='advanced'  data-toggle='tooltip' data-placement="left" title="<?php esc_html_e('Show on map', 'bing-map-pro');?>" />
                                                    <input type="button" class="button button-primary btn_bmp_pin_info_advanced" id='' 
                                                            value="<?php esc_html_e('Advanced', 'bing-map-pro'); ?>">
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                            
                                    </div>

                                </div>
                                


                            </div>
                        </div>

                            <div class="col-sm-8" id='bmp_map_section'>
                                <div class="map-circle-controls">                                
                                    <button class="button btn-new-circle" title="<?php esc_html_e('New','bing-map-pro');?>" 
                                        data-toggle='tooltip' data-placement='top' >
                                        <i class="fa fa-plus-circle" style="color: #6fa1cc;"></i>
                                    </button>
                                    <button class="button btn-save-circle" title="<?php esc_html_e('Save','bing-map-pro');?>" 
                                        data-toggle='tooltip' data-placement='top' > 
                                        <i class="fa fa-save" style="color: green"></i> 
                                    </button>
                                    <button class="button btn-edit-circle" title="<?php esc_html_e('Edit','bing-map-pro');?>" 
                                        data-toggle='tooltip' data-placement='top' > 
                                        <i class="fa fa-edit" style="color:green;"></i> 
                                    </button>
                                    <button class="button btn-delete-circle" title="<?php esc_html_e('Delete','bing-map-pro');?>" 
                                        data-toggle='tooltip' data-placement='top' >
                                        <i class="fa fa-trash" style="color: red;"></i>
                                    </button>
                                </div>                             

                                <div id="bmp_shapes_map_circle" style="height: 400px" ></div>   

                                <!-- Show other circles -->
                                <p></p>
                                <div>
                                    <input type="checkbox" data-size='mini' data-on="<?php esc_html_e('Hide Saved Circles', 'bing-map-pro');?>" 
                                    data-off="<?php esc_html_e('Show Saved Circles', 'bing-map-pro');?>" data-toggle="toggle" id='showSavedCircles'>

                                    <input type="checkbox" data-size='mini' data-on="<?php esc_html_e('Hide All Saved Shapes', 'bing-map-pro');?>" 
                                    data-off="<?php esc_html_e('Show All Saved Shapes', 'bing-map-pro');?>" data-toggle="toggle" id='showSavedShapesCir'>
                                </div>   
                            </div>
                        </div>
                        <div>
                            <div style='text-align: left;'>
                                <p></p>                                                     
                                <button type='button' id='save' class='button button-primary' > <?php esc_html_e('Save', 'bing-map-pro');?> </button>
                                <button type='button' id='saveAndNew' class='button button-primary' > <?php esc_html_e('Save & New', 'bing-map-pro');?> </button>
                                <button type='button' id='cancel' class='button button-secondary' data-dismiss='modal'> <?php esc_html_e('Cancel', 'bing-map-pro');?> </button> 
                            </div>
                        </div>
                    </div>
                </div> 
            </div>
            <input type='hidden' id='bmp_circle_action' value='new' />
        </div>  

        <!-- polygon -->
        <div class="modal bmp-modal-shape-polygon" style="z-index: 10001;">
            <div class="modal-dialog modal-lg" style="min-width: 65%; z-index: 100001;">
            <div class="modal-content">
                <div class="modal-headline"></div>
                    <div class="modal-header">                        
                        <h3 class="modal-title"> <?php esc_html_e('New Polygon', 'bing-map-pro');?></h3>                        
                    </div>
                    <div class="modal-body">
                      
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="row info-block">
                                    <div class="form-inline">
                                        <label for='bmp_polygon_name'> <?php esc_html_e('Name', 'bing-map-pro'); ?> </label>
                                        <input type='text' placeholder="<?php esc_html_e('Polygon One', 'bing-map-pro');?>"  id="bmp_polygon_name" class='form-control required' value="" />
                                    </div>  

                                    <div class="form-inline">
                                        <label for='bmp_polygon_fill_color'> <?php esc_html_e('Fill Color', 'bing-map-pro'); ?> </label>
                                        <input type='color' style="width: 140px;"  id="bmp_polygon_fill_color" class='form-control' value="#fff000" />
                                    </div>  

                                    <div class="form-inline">
                                        <label for='bmp_polygon_stroke_color'> <?php esc_html_e('Stroke Color', 'bing-map-pro'); ?> </label>
                                        <input type='color' style="width: 140px;"  id="bmp_polygon_stroke_color" class='form-control' value="" />
                                    </div>  


                                    <div class="form-inline">
                                        <label for='bmp_polygon_thickness'> <?php esc_html_e('Stroke Thickness', 'bing-map-pro'); ?> </label>
                                        
                                        <input type='number'   class="form-control" id="bmp_polygon_thickness" value="1"/>                                            
                                    </div> 
                                    

                                    <div class="form-inline">
                                        <label for='bmp_polygon_fill_opacity'> <?php esc_html_e('Fill Color Opacity', 'bing-map-pro'); ?> </label>
                                        
                                        <div class="slidecontainer">
                                                <input style="width: 90%;" type="range" min="0.1" step='0.1' max="1.0" id="bmp_polygon_fill_opacity" value="0.4" class="slider">
                                                    <strong> <span id='bmp_polygon_fill_opacity_val'> 0.4 </span> </strong>
                                            </div>                                          
                                    </div> 

                                    <div style="height: 20px"></div>

                                    <div class="panel panel-default">
                                        <div class="panel-header" style="text-align: center;">                                      
                                        </div>

                                        <div class="panel-body">                               

                                        <div class="row bmp-set-row">
                                            <div class="col-sm-4">
                                                <div class='h5'> <?php esc_html_e('Polygon Infobox: ', 'bing-map-pro'); ?> </div>
                                                
                                            </div>
                                            <div class="col-sm-7 div-bmp-pin-info">
                                                <div class="row">

                                                    <label class='radio-inline' for="radio_bmp_pin_none">
                                                        <input type="radio" checked='checked' style="position: relative;" name="radio_bmp_pin_use" id="radio_bmp_polygon_none" 
                                                            data-toggle='tooltip' data-value='none' data-placement="left" title="<?php esc_html_e('Show on map', 'bing-map-pro');?>" /> 
                                                        <?php esc_html_e('None', 'bing-map-pro'); ?>    
                                                    </label>
                                                </div>
                                                <div class="row">
                                                    <label class='radio-inline'>                                                  
                                                            <input type="radio" style="position: relative;"  name="radio_bmp_pin_use" id="radio_bmp_polygon_simple" 
                                                            data-value='simple' data-toggle='tooltip'  data-placement="left" title="<?php esc_html_e('Show on map', 'bing-map-pro');?>" />
                                                            <input type="button" class="button button-primary btn_bmp_pin_info_simple" id='' 
                                                            value="<?php esc_html_e('Simple', 'bing-map-pro'); ?>">                                                  
                                                    </label>
                                                </div>
                                                <div class="row">
                                                    <label for="" class='radio-inline'>
                                                        <input type="radio" style="position: relative;" name="radio_bmp_pin_use" id="radio_bmp_polygon_advanced" 
                                                        data-value='advanced'  data-toggle='tooltip' data-placement="left" title="<?php esc_html_e('Show on map', 'bing-map-pro');?>" />
                                                        <input type="button" class="button button-primary btn_bmp_pin_info_advanced" id='' 
                                                                value="<?php esc_html_e('Advanced', 'bing-map-pro'); ?>">
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                                
                                        </div>

                                    </div>
                                    


                                </div>
                            </div>

                            <div class="col-sm-8" id='bmp_map_section'>
                                <div class="map-polygon-controls">                                
                                    <button class="button btn-new-polygon" title="<?php esc_html_e('New','bing-map-pro');?>" 
                                        data-toggle='tooltip' data-placement='top' >
                                        <i class="fa fa-plus-circle" style="color: #6fa1cc;"></i>
                                    </button>
                                    <button class="button btn-save-polygon" title="<?php esc_html_e('Save','bing-map-pro');?>" 
                                        data-toggle='tooltip' data-placement='top' > 
                                        <i class="fa fa-save" style="color: green"></i> 
                                    </button>
                                    <button class="button btn-edit-polygon" title="<?php esc_html_e('Edit','bing-map-pro');?>" 
                                        data-toggle='tooltip' data-placement='top' > 
                                        <i class="fa fa-edit" style="color:green;"></i> 
                                    </button>
                                    <button class="button btn-delete-polygon" title="<?php esc_html_e('Delete','bing-map-pro');?>" 
                                        data-toggle='tooltip' data-placement='top' >
                                        <i class="fa fa-trash" style="color: red;"></i>
                                    </button>
                                </div>   
                                
                                <div class="map-polygon-panning" style="float: right; margin-top: -38px;">
                                    <?php esc_html_e('Map View', 'bing-map-pro'); ?> <input type="checkbox" id="map_polygon_panning" checked data-toggle="toggle" data-on="<?php esc_html_e('UnLocked', 'bing-map-pro');?>"
                                    data-off="<?php esc_html_e('Locked', 'bing-map-pro'); ?>" data-onstyle="success" data-offstyle="danger">
                                </div>

                                <div id="bmp_shapes_map_polygon" style="height: 400px" ></div>  
                                <!-- Show other polygons -->
                                <p></p>
                                <div>
                                    <input type="checkbox" data-size='mini' data-on="<?php esc_html_e('Hide Saved Polygons', 'bing-map-pro');?>" 
                                    data-off="<?php esc_html_e('Show Saved Polygons', 'bing-map-pro');?>" data-toggle="toggle" id='showSavedPolygons'>

                                    <input type="checkbox" data-size='mini' data-on="<?php esc_html_e('Hide All Saved Shapes', 'bing-map-pro');?>" 
                                    data-off="<?php esc_html_e('Show All Saved Shapes', 'bing-map-pro');?>" data-toggle="toggle" id='showSavedShapesPol'>
                                </div>  
                            </div>
                        </div>
                        <div>
                            <div style='text-align: left;'>
                                <p></p>                                                     
                                <button type='button' id='save' class='button button-primary' > <?php esc_html_e('Save', 'bing-map-pro');?> </button>
                                <button type='button' id='saveAndNew' class='button button-primary' > <?php esc_html_e('Save & New', 'bing-map-pro');?> </button>
                                <button type='button' id='cancel' class='button button-secondary' data-dismiss='modal'> <?php esc_html_e('Cancel', 'bing-map-pro');?> </button> 
                            </div>
                        </div>
                    </div>
                </div> 
            </div>
            <input type='hidden' id='bmp_circle_polygon' value='new' />
        </div> 
        
        <div class="modal bmp-modal-assign-shape" id="bmp_modal_assign_shape" role="dialog">
            <div class="modal-dialog">
                    
                <!-- Modal content-->
                <div class="modal-content">
                <div class='modal-headline'></div>   
                    <div class="modal-header">     
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>  </button>                                
                        <h4 class="modal-title"> <?php esc_html_e('Assign Shape to Map', 'bing-map-pro');?> - <span id="assign_shape_name"> Shape Name</span></h4>
                    </div>
                    <div class="modal-body">  
                                                                    
                        <div class="row">
                            <div class="col-sm-6" id="body_pins_assigned">
                            <p> <?php esc_html_e('Add to Map', 'bing-map-pro');?></p>
                                <ul id="ul_pins_assigned" class="connectedSortable">

                                </ul>
                            </div>
                            <div id="drag_drop_pin_bi_img"><img src="<?php echo BMP_PLUGIN_URL.'/images/icons/bi-arrows.png';?>" alt=""></div>
                            <div class="col-sm-6" id="body_all_maps">
                                <p> <?php esc_html_e('Available Maps', 'bing-map-pro');?></p>
                                <ul id="ul_all_maps" class="connectedSortable">

                                </ul>
                            </div>
                        </div>
                        <p> <i> ***<?php esc_html_e('Drag and drop from one list to another', 'bing-map-pro'); ?>***</i> </p>
                        <p> <i> ***<?php esc_html_e('Pin will be active when assigned to map', 'bing-map-pro'); ?>***</i> </p>
                    </div>
                
                    <div class="modal-footer">
                        <input type='hidden' value='' id='bmp_assign_shape_id' />
                        <button type="button" class="button button-default" id="bmp_close_assign_shape" data-dismiss="modal"> <?php esc_html_e('Close', 'bing-map-pro');?></button>                   
                    </div>
                </div>
        
            </div>
        </div> 
<?php
        BingMapPro_Infobox::bmp_simple_info();
        BingMapPro_Infobox::bmp_advanced_info();
        BingMapPro_Infobox::bmp_infobox_infoEditorError();
?>

        <script type='text/javascript' >
            var bmpIconsUrl             = "<?php echo BMP_PLUGIN_URL.'/images/icons/';?>";
            var bmpPinSizes              = <?php if( $bmp_infobox_sizes !== null ){ echo json_encode( $bmp_infobox_sizes );}else{ echo '[]';} ?>;
            var s_bmp_edit              = "<?php esc_html_e('Edit', 'bing-map-pro');?>";
            var s_bmp_delete            = "<?php esc_html_e('Delete', 'bing-map-pro');?>";
            var s_bmp_assign_map        = "<?php esc_html_e('Assign to Map', 'bing-map-pro');?>";
            var s_bmp_used_on_map       = "<?php esc_html_e('Used on Map', 'bing-map-pro');?>";
            var bmp_api_key             = "<?php echo $bmp_api_key;?>";
            var s_bmp_yes               = "<?php esc_html_e('Yes', 'bing-map-pro');?>";
            var s_bmp_no                = "<?php esc_html_e('No', 'bing-map-pro');?>";            
            var s_bmp_confirm_title     = "<?php esc_html_e('Confirmation', 'bing-map-pro');?>";
            var s_bmp_delete_polyline   = "<?php esc_html_e('Are you sure you want to delete the Polyline?', 'bing-map-pro');?>";
            var s_bmp_delete_circle     = "<?php esc_html_e('Are you sure you want to delete the Circle?', 'bing-map-pro');?>";
            var s_bmp_delete_polygon    = "<?php esc_html_e('Are you sure you want to delete the Polygon?', 'bing-map-pro');?>";
            var s_bmp_required_name     = "<?php esc_html_e('Name is required!', 'bing-map-pro');?>";
            var s_bmp_no_polyline       = "<?php esc_html_e('No Polyline drawn on the map. Click on the New button to start!', 'bing-map-pro');?>";
            var s_bmp_no_circle         = "<?php esc_html_e('No Circle drawn on the map. Click on the New button to start!', 'bing-map-pro');?>";
            var s_bmp_no_polygon        = "<?php esc_html_e('No Polygon drawn on the map. Click on the New button to start!', 'bing-map-pro');?>";
            var s_bmp_no_shape_drawn    = "<?php esc_html_e('First draw the shape. Click on the New button to start!', 'bing-map-pro');?>";
            var s_bmp_new_circle_info   = "<?php esc_html_e('Hold, and drag the mouse cursor on the map to create the circle!', 'bing-map-pro');?>";
            var s_bmp_delete_shape      = "<?php esc_html_e('Are you sure you want to delete this Shape?', 'bing-map-pro');?>";
            var s_bmp_delete_ref_mess   = "<?php esc_html_e('Any references to any maps will be deleted.', 'bing-map-pro'); ?>";
            var s_simple                = "<?php esc_html_e('simple', 'bing-map-pro');?>";
            var s_none                  = "<?php esc_html_e('none', 'bing-map-pro');?>";
            var s_advanced              = "<?php esc_html_e('advanced', 'bing-map-pro');?>";
            var bmpDataMaps             = JSON.parse( JSON.stringify( <?php echo json_encode($active_maps, true ); ?> ) );
            var bmpDataShapeMaps        = JSON.parse( JSON.stringify( <?php echo json_encode($shapes_to_map, true ); ?> ) );


        </script>
        <script type='text/javascript' async defer src="https://www.bing.com/api/maps/mapcontrol?callback=bmp_setupmaps&key=<?php echo esc_attr( $bmp_api_key );?>"></script>
  <?php

    }
}