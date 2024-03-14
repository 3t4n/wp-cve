<?php

namespace BingMapPro_Pins;

if( ! defined('ABSPATH') ) die('No Access to this page');

include_once( 'BingMapPro_Includes.php');
include_once( 'BingMapPro_Infobox.php');
include_once( 'BingMapPro_MasterMaps.php');
include_once( 'BingMapPro_MasterPins.php');

use BingMapPro_MasterMaps;
use BingMapPro_MasterPins;
use BingMapPro_Includes;
use BingMapPro_Infobox\BingMapPro_Infobox as BingMapPro_Infobox;

class BingMapPro_Pins{
    public static function bmp_init_pins( $bmp_pins, $bmp_api_key, $extra_settings, $bmp_menu_links ){
    ?>

    <div class='wrap'>
        <div class="container-fluid">
            <?php
                BingMapPro_Includes\BingMapPro_Includes::bmp_loading_screen();  
                BingMapPro_Includes\BingMapPro_Includes::bmp_error_screen(); 
            //    bmp_error_api_key( $bmp_api_key, 0 );
                BingMapPro_Includes\BingMapPro_Includes::bmp_donate();
                wp_enqueue_media();
                $map_obj = new BingMapPro_MasterMaps\BingMapPro_Map_Full();
                $pin_obj = new BingMapPro_MasterPins\BingMapPro_Pin();
                $active_maps = $map_obj->getActiveMaps( false );
                $pins_to_map = $pin_obj->getPinsLinkedToMap();                

                $bmp_pin_types_def = ['Default', 'Black', 'Blue', 'Blue Pin', 'Google', 'Green', 'Light Blue', 'Yellow', 'White', 'Orange', 'Violet', 'Orange Pin', 'Red Blue Base', 'Red Vibe'];
                echo $bmp_menu_links;   
                
                wp_nonce_field( 'nonce_action_bing_map_pro', 'nonce_bing_map_pro');             
            ?>

            <div class="panel panel-default bmp-pins-new-panel">
                <div class="panel-heading">                  
                    <h3> <?php esc_html_e('Pins', 'bing-map-pro');?></h3> 
                    <div class="bmp_message_block">
                        <div class="alert alert-danger" id='bmp_alert_pin_exists' style='display:none'>
                            <?php esc_html_e('This pin already exists!', 'bing-map-pro');?>
                        </div>

                        <div class="alert alert-danger " id='bmp_alert_cannot_save' style='display:none'>
                            <?php esc_html_e('Cannot save pin! Error occured.', 'bing-map-pro');?>
                        </div>

                        <div class="alert alert-success " id='bmp_alert_pin_saved' style='display:none'>
                            <?php esc_html_e('Pin saved successfully!', 'bing-map-pro');?>
                        </div>
                    </div>                  
                </div>

                <div class="panel-body">
                        <div class="row" style="border-bottom: 2px solid #0073aa; padding-bottom: 3px;">                              
                                <span id="bmp_new_pin"> <img title="<?php esc_html_e('New Pin', 'bing-map-pro');?>" data-toggle='tooltip' data-placement='bottom'
                                src="<?php echo  BMP_PLUGIN_URL.'/images/icons/expand.png';?>"   
                                 alt="<?php esc_html_e('New Pin Image', 'bing-map-pro');?>" /> </span>
                                &nbsp; &nbsp; <span id='show_error_message' style='color:red;'></span>                                          
                        </div>
                           

                        <div class='row show-all-pins'>

                            <hr/>
                            <p></p>
                            <table class='table table-striped table-condensed table-responsive'>
                                <thead>
                                    <tr>                                                                            
                                        <th> <?php esc_html_e('Pin Name', 'bing-map-pro');?> </th>
                                        <th> <?php esc_html_e('Address', 'bing-map-pro');?> </th>                                        
                                        <th> <?php esc_html_e('Lat - Long', 'bing-map-pro');?> </th>
                                        <th> <?php esc_html_e('Infobox Type', 'bing-map-pro');?></th>
                                        <th> <?php esc_html_e('Icon', 'bing-map-pro');?> </th>    
                                        <th class='center-pos'> <?php esc_html_e('Used on Maps', 'bing-map-pro');?> </th>                                    
                                        <th class='center-pos'> <?php esc_html_e('Action', 'bing-map-pro');?> </th>                                  
                                    </tr> 
                                </thead>
                                <tbody id='bmp_tbody_pins'>
                                    <?php
                                       
                                        if( $bmp_pins !== null ){ 
                                            foreach( $bmp_pins as $key=>$pin ){
                                                $pin_id = $pin->id;
                                               
                                                $title = str_replace(  '\\', '', $pin->pin_title );
                                                $title = str_replace(  '\'', '&apos;', $title);
                                                $title = str_replace(  '\"', '&quot;', $title);                      

                                                $desc =  str_replace(  '\\', '', $pin->pin_desc ); 
                                                $desc =  str_replace(  '\'', '&apos;', $desc);
                                                $desc =  str_replace(  '\"', '&quot;', $desc);
                                                $desc =  str_replace(  'bmp_nl', '<br/>', $desc );
                                                $pin_type = esc_html__( 'none', 'bing-map-pro');
                                              
                                                $pin_name = str_replace(  '\\', '', $pin->pin_name );
                                                $pin_address = str_replace('\\', '', $pin->pin_address );
                                                $pin_custom  = $pin->icon_link;
                                                if( $pin_custom !== '' ){
                                                    if( strpos( $pin_custom, 'http' ) === false){
                                                        $pin_custom = BMP_PLUGIN_URL.'/images/icons/custom-icons/'.$pin_custom;
                                                    }
                                                }else{
                                                    $pin_custom = BMP_PLUGIN_URL.'/images/icons/default/pin-'. $pin->icon . '.png'; 
                                                }

                                                if( $pin->pin_image_two == '' ){
                                                    if( ( $title != '' ) && ( $desc != '' ) )
                                                        $pin->pin_image_two = 'simple';
                                                    else
                                                        $pin->pin_image_two = 'none';
                                                }
                                                if( $pin->pin_image_one == '' ){
                                                    $pin->pin_image_one = 'none';
                                                }

                                                $pin_info_type = $pin->pin_image_one;
                                                $pin_info_sel  = $pin->pin_image_two; //radio
                                            //    $pin_info_html = htmlspecialchars( $pin->data_json );
                                            
                                                $pin_info_html = str_replace('\'', '&apos;', $pin->data_json );
                                                $pin_info_html = str_replace('\"', '&quot;', $pin_info_html );
                                                $pin_info_html = str_replace('\\', '', $pin_info_html );
                                                
                                                $icon_tooltip = "<td > <img style='cursor: pointer;' width='28' height='28' src='".$pin_custom." '</td>"; 
                                                
                                                if( $pin_info_sel == 'simple'){
                                                    $icon_tooltip = "<td > <img style='cursor: pointer;'  data-toggle='tooltip' data-html='true' data-placement='left'                                                   
                                                    title='{$title} <hr /> {$desc}' width='28' height='28' src='".$pin_custom." '</td>";  
                                                    $pin_type = esc_html__('simple', 'bing-map-pro'); 
                                                }else if( $pin_info_sel == 'advanced' ){
                                                    $icon_tooltip = "<td > <img style='cursor: pointer;'  data-toggle='tooltip' data-html='true' data-placement='left'                                                   
                                                    title='".$pin_info_html."' width='28' height='28' src='".$pin_custom." '</td>";     
                                                    $pin_type = esc_html__('advanced', 'bing-map-pro');
                                                }

                                                $show_str = '';
                                                $show_str .= "<tr data-id='{$pin_id}' id='pin_{$pin_id}'>";
                                          
                                                $show_str .=  "<td><b>{$pin_name} </b> </td>";
                                                $show_str .=  "<td>  {$pin_address} </td>";                                              
                                                $show_str .=  "<td> {$pin->pin_lat} || {$pin->pin_long} </td>";

                                             
                                                $show_str .= '<td>'.$pin_type.'</td>';

                                                $show_str .=  $icon_tooltip; 

                                                $show_str .= '<td class="center-pos" id="pin_used_on_maps">'. (isset( $pins_to_map[$pin_id] ) ? sizeof( $pins_to_map[$pin_id] ) : '0' ) .'</td>';

                                               
                                                $show_str .=  '<td class="td-action-pins center-pos"> '.
                                                                '<button type="button" data-id='.$pin_id.' onclick="BmpAssignPin(this);"  id="assign_bmp_pin" 
                                                                    title="'. esc_html__('Assign to Map(s)', 'bing-map-pro').'" 
                                                                    data-toggle="tooltip" data-placement="bottom" class="button btn-info assign-bmp-map">
                                                                    <i class="fa fa-map-marked"></i> </button> <span class="spacer"> </span>'.
                                                                '<button type="button" data-id='.$pin_id.' onclick="BmpEditPin(this);"  id="edit_bmp_pin" 
                                                                    title="'. esc_html__('Edit', 'bing-map-pro').'"
                                                                    data-toggle="tooltip" data-placement="bottom" class="button btn-success edit-bmp-map"> 
                                                                    <i class="fa fa-edit"></i> </button> <span class="spacer"> </span>'.
                                                                '<button type="button" data-id='.$pin_id.' onclick="BmpDeletePin(this);"  id="delete_bmp_pin"
                                                                    title="'. esc_html__('Delete', 'bing-map-pro').'"
                                                                    data-toggle="tooltip" data-placement="bottom" class="button btn-danger delete-bmp-map">
                                                                    <i class="fa fa-trash"></i> </button>'.
                                                              '</td>';
                                                $show_str .=  '</tr>';
                                                echo $show_str;
                                            }
                                
                                        }
                                        if( $bmp_pins == null ){ //no pins created
                                            echo ('<tr><td> <b>');
                                            esc_html_e(' No pins created. Press on the ADD icon above to get started. Thank you for using this plugin ', 'bing-map-pro');
                                            echo ('</b> </td> </tr>');
                                        }
                                    ?>

                                    <input type='hidden' value='' id='bmp_pin_hidden_pin_id' />                                    
                                </tbody>
                            </table>
                        </div>

 
                </div>  <!-- panel-body -->  
            
            </div> <!-- panel panel-default -->

        </div> <!-- container -->

    </div> <!-- wrap -->

    <div class="modal fade bmp-modal-del-pin" id="bmp_modal_del_pin" role="dialog">
        <div class="modal-dialog">
                    
            <!-- Modal content-->
            <div class="modal-content">
            <div class='modal-headline'></div>   
                <div class="modal-header">                            
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h3 class="modal-title"> <?php esc_html_e('Delete Pin', 'bing-map-pro');?></h3>
                </div>
                <div class="modal-body">                                                  
                    <div class="input-group">                                   
                        <p class='h4'><strong>  <span ><i class="fa fa-trash" aria-hidden="true"></i> 
                        </span> <?php esc_html_e('Are you sure you want to delete this pin?', 'bing-map-pro');?> </strong></p>
                        <p class='h4'><strong>  <span ><i class="fa fa-trash" aria-hidden="true"></i> 
                        </span> <?php esc_html_e('Any references to any maps will be deleted.', 'bing-map-pro');?> </strong></p>
                    </div>                           
                </div>

                <div class="modal-footer">
                    
                    <button type="button" class="button button-default" data-dismiss="modal"> <?php esc_html_e('No', 'bing-map-pro');?></button>
                    <button type="button" id='bmp_btn_del_pin' class="button button-primary" data-dismiss="modal"> <?php esc_html_e('Yes', 'bing-map-pro');?> </button>
                </div>
            </div>
        
        </div>
    </div> 

    <div class="modal bmp-modal-custom-pin">
        <div class="modal-dialog modal-lg">
           <div class="modal-content">
               <div class="modal-headline"></div>
                <div class="modal-header">
                    <button type='button' class="close" data-dismiss='modal'> &times; </button>
                    <h3 class="modal-title"> <?php esc_html_e('Custom Icon Library', 'bing-map-pro');?></h3>
                    <h6> <b> <?php esc_html_e('Credit: Icons are provided by', 'bing-map-pro');?> <a href="https://mapicons.mapsmarker.com" target="_blank"> mapicons.mapsmarker.com </a> <?php esc_html_e('RECOMMENDED FOR OTHER CUSTOM ICONS', 'bing-map-pro');?></b> </h6>
                </div>
                <div class="modal-body">
                    <div style='display: inline-block;'>
                        <p>  <input placeholder="<?php esc_html_e('Search', 'bing-map-pro');?>"  type="search" class="form-control" id='bmp_custom_pin_search' /> </p>
                        <span id="bmp_selected_custom_pin" class='h4'></span> 
                    </div>
                    <div id='bmp_custom_pin_content'>                        
                    </div>
                    <div>
                        <div style='text-align: right;'>
                            <p></p>
                            <button type='button' id='bmp_custom_icon_cancel' class='button button-secondary' data-dismiss='modal'> <?php esc_html_e('Cancel', 'bing-map-pro');?> </button>                      
                            <button type='button' id='bmp_custom_icon_select' class='button button-primary' > <?php esc_html_e('Select', 'bing-map-pro');?> </button>
                        </div>
                    </div>
                </div>
           </div> 
        </div>

    </div>   
    


    <div class="modal fade bmp-modal-edit-pin" id="bmp_modal_edit_pin" role="dialog">
        <div class="modal-dialog">
                    
            <!-- Modal content-->
            <div class="modal-content">
            <div class='modal-headline'></div>   
                <div class="modal-header">                            
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h3 class="modal-title"> <?php esc_html_e('Edit Pin', 'bing-map-pro');?></h3>
                </div>
                <div class="modal-body">                                                  
                    <div class="input-group">                                   
                        <p class='h4'><strong>  <span ><i class="fa fa-edit" aria-hidden="true"></i> </span> <?php esc_html_e('Changes recorded. Continue without saving?', 'bing-map-pro');?> </strong></p>
                        
                    </div>                           
                </div>

                <div class="modal-footer">
                    <input type='hidden' value='' id='bmp_pin_hidden_pin_id' />
                    <button type="button" class="button button-default" data-dismiss="modal"> <?php esc_html_e('No', 'bing-map-pro');?></button>
                    <button type="button" id='bmp_btn_edit_pin' onclick='bmp_modal_ok_edit_pin(this);' class="button button-primary" data-dismiss="modal"> <?php esc_html_e('Yes', 'bing-map-pro');?> </button>
                </div>
            </div>
        
        </div>
    </div> 

    <div class="modal bmp-modal-assign-pin" id="bmp_modal_assign_pin" role="dialog">
        <div class="modal-dialog">
                    
            <!-- Modal content-->
            <div class="modal-content">
            <div class='modal-headline'></div>   
                <div class="modal-header">     
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>  </button>                                
                    <h4 class="modal-title"> <?php esc_html_e('Assign Pin to Map', 'bing-map-pro');?> - <span id="assign_pin_name"> Pin Name</span></h4>
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
                    <input type='hidden' value='' id='bmp_assign_pin_id' />
                    <button type="button" class="button button-default" id="bmp_close_assign_pin" data-dismiss="modal"> <?php esc_html_e('Close', 'bing-map-pro');?></button>                   
                </div>
            </div>
        
        </div>
    </div> 

<!-- edit pin modal, all fields ---->                                    
    <div class="modal bmp-modal-new-edit-pin" id="bmp_modal_new_edit_pin" role="dialog">
        <div class="modal-dialog modal-lg" style="min-width: 65%;">
                    
            <!-- Modal content-->
            <div class="modal-content">
            <div class='modal-headline'></div>   
                <div class="modal-header">                                                      
                    <h4 class="modal-title"> <?php esc_html_e('New', 'bing-map-pro');?> </h4>
                </div>

                <div class="modal-body">  
                    <div class="row" >                      
                        
                        <div class="col-md-5">                                  

                            <div class='row bmp-set-row' >
                                <div class="col-sm-4 h5"> <?php esc_html_e('Pin Name:', 'bing-map-pro');?> </div>
                                    <div class="col-sm-8"> 
                                        <span class='required-star'>*</span> <input  type="text"  name="bmp_new_pin_name" class='form-control' value="" 
                                                id="bmp_new_pin_name"  placeholder="<?php esc_html_e('Name', 'bing-map-pro'); ?>" />
                                            
                                </div>
                            </div> 

                            <div class='row bmp-set-row' >
                                <div class="col-sm-4 h5" > <?php esc_html_e('Address:', 'bing-map-pro');?> <img style='display:none;'
                                 src="<?php echo esc_url( BMP_PLUGIN_URL.'/images/icons/globe.png' );?>" 
                                                                                id='lat_and_long_address'  
                                                                                title="<?php esc_html_e('Get Lat and Long from Address', 'bing-map-pro'); ?>" /> </div>
                                    <div class="col-sm-8" id='searchBoxContainer'> 
                                        <input  type="text"  name="bmp_new_pin_address" class='form-control' value="" 
                                                id="bmp_new_pin_address" placeholder="<?php esc_html_e('Address', 'bing-map-pro'); ?>" />
                                            
                                </div>
                            </div>

                            <div class='row bmp-set-row' >
                                <div class="col-sm-4 h5"> <?php esc_html_e('Latitude:', 'bing-map-pro');?> </div>
                                    <div class="col-sm-8"> 
                                    <span class='required-star'>*</span> <input  type="text"  name="bmp_new_pin_lat" class='form-control' value="" 
                                                id="bmp_new_pin_lat" placeholder="<?php esc_html_e('Latitude', 'bing-map-pro'); ?>" />
                                            
                                </div>
                            </div>

                            <div class='row bmp-set-row' >
                                <div class="col-sm-4 h5"> <?php esc_html_e('Longitude:', 'bing-map-pro');?> </div>
                                    <div class="col-sm-8"> 
                                        <span class='required-star'>*</span> <input  type="text"  name="bmp_new_pin_long" class='form-control' value="" 
                                                id="bmp_new_pin_long" placeholder="<?php esc_html_e('Longitude', 'bing-map-pro'); ?>" />
                                            
                                </div>
                            </div>

                            <div class='row bmp-set-row' >                    
                                <div class="col-sm-4 h5"><strong> <?php esc_html_e('Icon:', 'bing-map-pro');?> </strong></div> 
                                <div class="col-sm-4">                                        
                                    <?php BingMapPro_Includes\BingMapPro_Includes::bmp_createPinIconList( $bmp_pin_types_def, 0, 'bmp_new_pin_icon', 'bmp_new_pin_icon' );?>    
                                    <span id="pin_icon_img"> 
                                        <img  src="<?php echo  esc_url( BMP_PLUGIN_URL.'/images/icons/default/pin-0.png');?>"  alt="icon-image" /> </span>                                 
                                    <div id='bmp_pin_default_info'> 
                                        <i class="fas fa-info-circle"  data-toggle='tooltip' data-placement='right' data-html='true' title="<?php esc_html_e('Custom url pin takes priority over the default pin.', 'bing-map-pro'); ?>"></i> 
                                    </div>
                                </div>
                            </div>

                            <div class='row bmp-set-row' >                    
                                <div class="col-sm-4 h5"> <?php esc_html_e('Custom Icon URL:', 'bing-map-pro');?> 
                                    <img  id="bmp_custom_pin_img" src="<?php echo esc_url( BMP_PLUGIN_URL.'/images/icons/default/pin-0.png');?>"  />  
                                </div> 
                                <div class="col-sm-6">                                        
                                    <input type='text' name="bmp_pin_custom_url" class="form-control"
                                        id="bmp_pin_custom_url" placeholder="<?php esc_html_e('Icon URL or library ', 'bing-map-pro');?>"
                                    /> 
                                    <div id="pin_library_block">
                                        <img id="bmp_wp_library_icon"  title="<?php esc_html_e('Wordpress image library', 'bing-map-pro');?>"
                                            src="<?php echo esc_url( BMP_PLUGIN_URL.'/images/icons/library-add.png');?>"  />                                           
                                        <img id="bmp_libary_pin_img" title="<?php esc_html_e('Local icons library', 'bing-map-pro') ?>" 
                                            src="<?php echo esc_url( BMP_PLUGIN_URL.'/images/icons/library.png');?>"  />
                                    </div>

                                </div>
                                
                            </div>
                            <div class="row bmp-set-row">
                                <div class="col-sm-4">
                                    <div class='h5'> <?php esc_html_e('Pin Infobox: ', 'bing-map-pro'); ?> </div>
                                    
                                </div>
                                <p> </p>
                                <div class="col-sm-7 div-bmp-pin-info">
                                    <div class="col-sm-12">

                                        <label class='radio-inline' for="radio_bmp_pin_none">
                                            <input type="radio" checked='checked' name="radio_bmp_pin_use" id="radio_bmp_pin_none" 
                                                data-toggle='tooltip' data-value='none' data-placement="bottom" title="<?php esc_html_e('Show on map', 'bing-map-pro');?>" /> 
                                            <?php esc_html_e('None', 'bing-map-pro'); ?>    
                                        </label>
                                    </div>
                                    <p></p>
                                    <p></p>

                                    <div class="col-sm-12">
                                        <label class='radio-inline'>                                                  
                                                <input type="radio"  name="radio_bmp_pin_use" id="radio_bmp_pin_simple" 
                                                    data-value='simple' data-toggle='tooltip'  data-placement="bottom" title="<?php esc_html_e('Show on map', 'bing-map-pro');?>" />
                                                <input type="button" class="button button-primary" id='btn_bmp_pin_info_simple' 
                                                    value="<?php esc_html_e('Simple', 'bing-map-pro'); ?>">                                                  
                                        </label>
                                    </div>
                                    
                                    <div class="col-sm-12">
                                        <label for="" class='radio-inline'>
                                            <input type="radio" name="radio_bmp_pin_use" id="radio_bmp_pin_advanced" 
                                                data-value='advanced'  data-toggle='tooltip' data-placement="bottom" title="<?php esc_html_e('Show on map', 'bing-map-pro');?>" />
                                            <input type="button" class="button button-primary" id='btn_bmp_pin_info_advanced' 
                                                        value="<?php esc_html_e('Advanced', 'bing-map-pro'); ?>">
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="row bmp-set-row">
                                <div class="col-sm-4">
                                    
                                    
                                </div>
                                
                                <div class="col-sm-8" id="bmp_pin_bank_space" >
                                    <div id='bmp_dis_pin_title'>
                                        <p style='font-weight: bold; font-size: 16px;'></p>
                                    </div> 
                                    <div id='bmp_dis_pin_desc'>

                                    </div>                                          
                                    <div class='row col-sm-12'  id='bmp_dis_pin_footer'>
                                        <p> <a id='bmp_dis_pin_footer_a'></a> </p>
                                    </div>
                                </div>
                                
                            </div>                                

                            <div class='row bmp-set-row' >
                              
                                <div class="col-sm-8"> 
                                    <input type='submit'  class='button button-primary' id='bmp_save_new_pin' value="<?php esc_html_e('Save', 'bing-map-pro');?>" />
                                    <input type='submit'  class='button button-primary' id='bmp_save_and_new_pin' value="<?php esc_html_e('Save & New', 'bing-map-pro');?>" />                                   
                                    <input type="button"   class='button button-seconday' id='bmp_cancel_edit_pin' value="<?php esc_html_e('Cancel', 'bing-map-pro');?>">                                
                                                
                                </div>
                            </div>
                            <input type="hidden" name="bmp_map_zoom" id="bmp_map_zoom" value='2'>
                            <input type='hidden' id='pin_action' name='action' value='new-pin' />
                            <input type="hidden" id='pin_action_id' value='' />
                        
                        </div>
                    
                        <div class="col-md-7">  
                            <div id="bmp_dragdrop_pin_div"> <b> <?php esc_html_e(' Drag-Drop the Pin on Map', 'bing-map-pro'); ?> 
                                <input type="checkbox" id="bmp_dragdrop_pin" data-toggle='toggle' data-size="mini"
                                    data-off="<?php esc_html_e('No', 'bing-map-pro'); ?>" data-on="<?php esc_html_e('Yes', 'bing-map-pro'); ?>"                                            
                                    />
                            </div>                            
                            <div id="bmp_admin_show_pin" style="width: 450px; height: 400px" ></div>  
                            
                            <img src="<?php echo esc_url( BMP_PLUGIN_URL.'/images/icons/view.png' );?>" 
                                    title="<?php esc_html_e('Center the Pin on Map', 'bing-map-pro');?>" 
                                    id='bmp_view_new_pin' alt="view pin on map" style="cursor:pointer;" />

                        </div>                             
                                         
                    </div>
                </div>  
            </div>
        
        </div>
    </div> 


    <?php
        BingMapPro_Infobox::bmp_simple_info();       
        BingMapPro_Infobox::bmp_advanced_info();
        BingMapPro_Infobox::bmp_infobox_infoEditorError();
    ?>

    <script>                                            
        var bmpDataApiKey           = '<?php echo $bmp_api_key;?>';
        var bmpAllPins              = <?php if( $bmp_pins !== null ){ echo json_encode( $bmp_pins );}else{ echo '[]';} ?>;
        var bmpAlertPinExists       = "<?php esc_html_e('This pin already exists (name duplicate)!', 'bing-map-pro');?>";
        var bmpAlertPinCannotSave   = "<?php esc_html_e('Cannot save pin!', 'bing-map-pro');?>";
        var bmpIconsUrl             = "<?php echo esc_url( BMP_PLUGIN_URL.'/images/icons/');?>";
        var bmpIconsDefaultUrl      = "<?php echo esc_url( BMP_PLUGIN_URL.'/images/icons/default/'); ?>";
        var bmpIconsCustomUrl       = "<?php echo esc_url( BMP_PLUGIN_URL.'/images/icons/custom-icons/'); ?>";
        var bmpPinSizes             = <?php  echo json_encode( $extra_settings['pin'], true );?>;
        var s_simple                = "<?php esc_html_e('simple', 'bing-map-pro');?>";
        var s_none                  = "<?php esc_html_e('none', 'bing-map-pro');?>";
        var s_advanced              = "<?php esc_html_e('advanced', 'bing-map-pro');?>";
        var s_assign_to_map         = "<?php esc_html_e('Assign to Map(s)', 'bing-map-pro');?>";
        var s_edit                  = "<?php esc_html_e('Edit', 'bing-map-pro');?>";
        var s_delete                = "<?php esc_html_e('Delete', 'bing-map-pro');?>";
        var bmpDataMaps             = JSON.parse( JSON.stringify( <?php echo json_encode($active_maps, true ); ?> ) );
        var bmpDataPinMaps          = JSON.parse( JSON.stringify( <?php echo json_encode($pins_to_map, true ); ?> ) );

    </script>
<?php
}
}