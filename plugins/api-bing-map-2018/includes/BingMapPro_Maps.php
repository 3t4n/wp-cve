<?php

if( ! defined('ABSPATH') ) die('No Access to this page');

include_once( 'BingMapPro_Includes.php');
include_once( 'BingMapPro_Config.php');

use BingMapPro_Includes\BingMapPro_Includes as BingMapPro_Includes;

function bmp_maps( $maps, $bmp_api_key, $bmp_menu_links ){
    ?>
        <div class="container-fluid">
            <?php
                BingMapPro_Includes::bmp_loading_screen();  
                BingMapPro_Includes::bmp_error_screen(); 
                BingMapPro_Includes::bmp_donate();
                BingMapPro_Includes::bmp_delete_modal( 'map' );
                echo $bmp_menu_links; 
                 
                wp_nonce_field( 'nonce_action_bing_map_pro', 'nonce_bing_map_pro');             
            ?>
            <script>
                var s_Yes = "<?php esc_html_e('Yes', 'bing-map-pro');?>";
                var s_No  = "<?php esc_html_e('No', 'bing-map-pro');?>";
          
            </script>


            
            <div class="panel panel-default bmp-maps-new-panel">
                <div class="panel-heading"> <h3> <?php esc_html_e('Maps', 'bing-map-pro');?> </h3></div>
                <div class="panel-body"> 
                    <div class="row"> <span class="newMap"> <img src="<?php echo esc_url( BMP_PLUGIN_URL .'/images/icons/expand.png' );?>" alt="Add Pin"> </span> </div>              
                 
                <table id="mapsTable"  class='table table-striped'>
                    <thead> <tr> 
                                <th> <?php esc_html_e('Active', 'bing-map-pro');?>      </th> 
                                <th> <?php esc_html_e('Title', 'bing-map-pro');?>       </th> 
                                <th> <?php esc_html_e('Short Code', 'bing-map-pro');?>  </th>
                                <th> <?php esc_html_e('Active Pins', 'bing-map-pro');?>        </th>
                                <th> <?php esc_html_e('Shapes', 'bing-map-pro');?>      </th>
                                <th> <?php esc_html_e('Actions', 'bing-map-pro');?>     </th>
                            </tr>
                    </thead>

                    <tbody>
                        <?php
                            foreach( $maps as $map ){ 
                                $shortcode = esc_html(  ($map['shortcode'] == '' ) ? "[bing-map-pro id={$map['id']}]" : "[{$map['shortcode']}]" );
                                echo    '<tr id="map_' . esc_html($map['id']) . '">'. 
                                            '<td> '. bmp_showActive( $map['active'], $map['id'] ) . '</td>'.
                                            '<td> '. str_replace('\\', '', esc_html( $map['title'] ) ) . '</td>'.
                                            '<td> <input type="text" readonly value="'. $shortcode .'"  /> </td>'.
                                            '<td>'.(isset($map['pin_no']) ? $map['pin_no'] : '0').'</td>' .
                                            '<td> '.(isset($map['shape_no']) ? $map['shape_no'] : '0').'</td>' .
                                            '<td> <button type="button" data-id='.esc_html($map['id']).'  id="edit_bmp_map" class="button btn-success edit-bmp-map"
                                                  data-toggle="tooltip" data-placement="bottom" title="'. esc_html__('Edit', 'bing-map-pro').'"> <i class="fa fa-edit"></i> </button> <span class="spacer"> </span> '.
                                                '<button type="button" data-id='.esc_html($map['id']).' onclick="BmpDeleteMap(this)" id="delete_bmp_map" class="button btn-danger delete-bmp-map"
                                                  data-toggle="tooltip" data-placement="bottom" title="'. esc_html__('Delete', 'bing-map-pro').'"> <i class="fa fa-trash"></i> </button>'.
                                            '</td>'.
                                        '</tr>';
                                
                            }
                            echo '<input type="hidden" name="bmp_map_action" id="bmp_map_action" value="" /> ';
                            echo '<input type="hidden" name="bmp_map_id" id="bmp_map_id" value=""/> '


                        ?>
                    </tbody>
                </table>

                </div>
            </div>


                <form action="" id='bmp_form_action' method="post">
                   <input type="hidden" name="bmp_page_action" id='bmp_page_action' />
                   <input type='hidden' name='bmp_page_map_id' id='bmp_page_map_id' /> 
                </form>
            
            
        
        
        </div>

          <div class="modal fade" id="newMapModal" role="dialog">
                <div class="modal-dialog">
                         
                    <!-- Modal content-->
                    <div class="modal-content">
                    <div class='modal-headline'></div>   
                        <div class="modal-header">
                            
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h3 class="modal-title"> <?php esc_html_e('New Map', 'bing-map-pro');?></h3>
                        </div>
                        <div class="modal-body">                      
                            
                            <div class="input-group">
                                <span class="input-group-addon"> <?php esc_html_e('New map: ', 'bing-map-pro');?> </span>
                                <input id="new_map_input" type="text" class="form-control" required='required' name="new_map_input" placeholder=" <?php esc_html_e('Map Name ', 'bing-map-pro');?> ">
                            </div>

                        </div>

                        <div class="modal-footer">
                            <button type="button" class="button button-secondary" data-dismiss="modal"> <?php esc_html_e('Close', 'bing-map-pro');?></button>
                            <button type="button" id="bmp_add_new_map" class="button button-primary" data-dismiss="modal"> <?php esc_html_e('Save', 'bing-map-pro');?> </button>
                        </div>
                    </div>
                
                </div>
            </div>

    <?php

}

function bmp_add_map_shapes( $map_id, $bmp_all_shapes, $bmp_map_shapes, $bmp_menu_links ){
    BingMapPro_Includes::bmp_loading_screen();
    BingMapPro_Includes::bmp_error_screen();
    BingMapPro_Includes::bmp_donate();
    echo $bmp_menu_links;
    ?>

    <div class="container-fluid bmp-edit-map-shapes-block">
        <?php
            wp_nonce_field( 'nonce_action_bing_map_pro', 'nonce_bing_map_pro');             
        ?>
        <script>
            var $bmp_all_shapes = <?php if( $bmp_all_shapes !== null ){ echo json_encode( $bmp_all_shapes );}else{ echo '[]';}?>;
            var $bmp_map_shapes = <?php if( $bmp_map_shapes !== null ){ echo json_encode( $bmp_map_shapes );}else{ echo '[]';}?>;
            var $bmp_map_id     = "<?php echo esc_attr( $map_id );?>";   
            var s_Action        = "<?php esc_html_e('Action', 'bing-map-pro');?>";
            var s_Name          = "<?php esc_html_e('Name', 'bing-map-pro');?>";
            var s_Type          = "<?php esc_html_e('Type', 'bing-map-pro');?>";
            var s_Infobox       = "<?php esc_html_e('Infobox', 'bing-map-pro');?>"; 
                        
        </script>


        <div class="row">
            <ul class="nav nav-tabs" id='bmp_map_page_nav'>
                <li>  <a href=''> <i class="fas fa-home" style='font-size:28px; color: blue;'></i> </a>  </li>
                <li>  <a id='bmp_edit_map_anchor' href="#"> <?php esc_html_e('Map', 'bing-map-pro'); ?></a>       </li>
                <li>  <a id='bmp_map_pins_anchor' href="#">   <?php esc_html_e('Map Pins', 'bing-map-pro');?>  </a>  </li>               
                <li class="active"> <a id='bmp_map_shapes_anchor' href="#">   <?php esc_html_e('Map Shapes', 'bing-map-pro');?>  </a>  </li>      
            </ul>
        </div>


        <div class='row col-md-6'>
            <div class="panel panel-default pnl-map-added-shapes">
                    <div class="panel-heading">                  
                        <h4> <?php esc_html_e('Map Shapes', 'bing-map-pro');?></h4> 
                    </div>
                    <div clss='panel-body'>
                        <table id='tbl_map_added_shapes' class='table table-stripped' data-unique-id='id'>
                        </table> 
                    </div>

            </div>
        </div>
     
        <div class='row col-md-6'>
            <div class="panel panel-default pnl-map-all-shapes">
                    <div class="panel-heading">                  
                        <h4> <?php esc_html_e('All Shapes', 'bing-map-pro');?></h4> 
                    </div>
                    <div clss='panel-body'>
                        <table id='tbl_map_all_shapes' class='table table-stripped' data-unique-id='id'>
                        </table>     
                    </div>

            </div>
        </div>


        <form action="" id='bmp_map_form_action' method="post">
            <input type="hidden" value='bmp-add-map-shapes' name="bmp_page_action" id='bmp_page_action' />
            <input type='hidden' value="<?php echo esc_attr( $map_id); ?>" name='bmp_page_map_id' id='bmp_page_map_id' />                  
        </form>

    </div>
    <script>
        var bmpIconsUrl   = "<?php echo esc_url( BMP_PLUGIN_URL.'/images/icons/');?>";
        bmp_RunMapShapesPage();
    </script>

    <?php
}

function bmp_add_map_pins( $map_id, $bmp_api_key, $bmp_all_pins, $bmp_map_pins, $bmp_menu_links ){
    BingMapPro_Includes::bmp_loading_screen();  
    BingMapPro_Includes::bmp_error_screen(); 
    BingMapPro_Includes::bmp_donate();
    echo $bmp_menu_links;
    ?>

    <div class="container-fluid bmp-edit-map-pins-block">

        <?php
            wp_nonce_field( 'nonce_action_bing_map_pro', 'nonce_bing_map_pro');             
        ?>

    <script>
        var $bmp_all_pins = <?php if( $bmp_all_pins !== null ){ echo json_encode( $bmp_all_pins );  }else{  echo '[]';}?>;
        var $bmp_map_pins = <?php if( $bmp_map_pins !== 0 ){ echo json_encode( $bmp_map_pins );  }else{  echo '[]';}?>;
        var $bmp_imgs_src = "<?php echo esc_url( BMP_PLUGIN_URL.'/' );?>";
        var $bmp_map_id   = "<?php echo esc_attr( $map_id);?>"; 
        var s_Yes         = "<?php esc_html_e('Yes', 'bing-map-pro');?>";
        var s_No          = "<?php esc_html_e('No', 'bing-map-pro');?>";
        <?php

        ?>
    </script>
    
        <div class="row">
            <ul class="nav nav-tabs" id='bmp_map_page_nav'>
                <li>  <a href=''> <i class="fas fa-home" style='font-size:28px; color: blue;'></i> </a>  </li>
                <li> <a id='bmp_edit_map_anchor' href="#"> <?php esc_html_e('Map', 'bing-map-pro'); ?></a>       </li>
                <li class="active"> <a id='bmp_map_pins_anchor' href="#">   <?php esc_html_e('Map Pins', 'bing-map-pro');?>  </a>  </li>               
                <li class=""> <a id='bmp_map_shapes_anchor' href="#">   <?php esc_html_e('Map Shapes', 'bing-map-pro');?>  </a>  </li>   
            </ul>
        </div>

        <div class='row col-md-6'>
            <div class="panel panel-default pnl-map-added-pins">
                    <div class="panel-heading">                  
                        <h4> <?php esc_html_e('Map Pins', 'bing-map-pro');?></h4> 
                    </div>
                    <div clss='panel-body'>
                        <table id='tbl_map_added_pins' class='table table-stripped'>
                            <thead>
                                <tr>   
                                    <th> <?php esc_html_e('Action', 'bing-map-pro');?></th>                                   
                                    <th> <?php esc_html_e('Active', 'bing-map-pro');?></th>
                                    <th> <?php esc_html_e('Name', 'bing-map-pro');?></th>
                                    <th> <?php esc_html_e('Address', 'bing-map-pro');?></th>
                                    <th> <?php esc_html_e('Lat - Long', 'bing-map-pro');?></th>
                                    <th> <?php esc_html_e('Icon', 'bing-map-pro');?></th>                                                                        
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table> 
                    </div>

            </div>
        </div>
     
        <div class='row col-md-6'>
            <div class="panel panel-default pnl-map-all-pins">
                    <div class="panel-heading">                  
                        <h4> <?php esc_html_e('All Pins', 'bing-map-pro');?></h4> 
                    </div>
                    <div clss='panel-body'>
                    <table id='tbl_map_all_pins' class='table table-stripped'>
                            <thead>
                                <tr>   
                                    <th> <?php esc_html_e('Action', 'bing-map-pro');?></th>                                                                       
                                    <th> <?php esc_html_e('Name', 'bing-map-pro');?></th>
                                    <th> <?php esc_html_e('Address', 'bing-map-pro');?></th>
                                    <th> <?php esc_html_e('Lat | Long', 'bing-map-pro');?></th>
                                    <th> <?php esc_html_e('Icon', 'bing-map-pro');?></th>                                                                       
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>     
                    </div>

            </div>
        </div>



        <form action="" id='bmp_map_form_action' method="post">
            <input type="hidden" value='edit-map' name="bmp_page_action" id='bmp_page_action' />
            <input type='hidden' value="<?php echo esc_attr( $map_id); ?>" name='bmp_page_map_id' id='bmp_page_map_id' />                  
        </form>
    </div>

    <?php
    
}

function bmp_showActive( $isActive, $id ){
    $mapStatus  = $isActive ? 'checked' : ''; 
    $s_On       = esc_html__('Yes', 'bing-map-pro');
    $s_Off      = esc_html__('No', 'bing-map-pro');   
    return  "<div class='checkbox'> " .                                    
                "<input type='checkbox' data-id='".$id."' onchange='sendBmpMapResponse(  \"".$id."\", \"active\", \"\" );' ". 
                    $mapStatus .
                 " id='map_status' data-size='mini' data-on='{$s_On}' data-off='{$s_Off}' data-toggle='toggle' />".            
            "</div>"; 
}

function bmp_editMap( $bmp_map, $bmp_api_key, $map_pins, $bmp_infobox_sizes, $bmp_menu_links, $map_shapes, $bmp_map_views ){ 

        $bmp_measure_types = array('px', '%', 'em', 'vh', 'vw');
        $bmp_map_width_name = 'bmp_map_width_type'; //same for id, drop down measure type
        $bmp_map_height_name = 'bmp_map_height_type'; //same for id, drop down measure type

        $bmp_map_types = array('Street', 'Aerial', 'Birds Eye', 'Canvas Dark', 'Canvas Light', 'Grayscale');
        $bmp_map_type_name = 'bmp_map_type';
        
      
    ?>
    <script>
        var bmpDataMap = JSON.parse( JSON.stringify(<?php echo json_encode( $bmp_map );?>));        
        var bmpDataApiKey = '<?php echo esc_html( $bmp_api_key ); ?>';
        var bmpMapAllPins = JSON.parse( JSON.stringify(<?php if( $map_pins == 0) echo '[]'; else echo json_encode(  $map_pins );?>));
        var bmpIconsSrc   = '<?php echo esc_url( BMP_PLUGIN_URL.'/');?>';
        var bmpPinSizes   = JSON.parse( '<?php echo json_encode( $bmp_infobox_sizes );?>' );
        var bmpFullscreenIconSrc = '<?php echo esc_url( BMP_PLUGIN_URL.'/images/icons/bmp-map-full-screen.png'); ?>';
        var bmpFullscreenIconSrc2 = '<?php echo esc_url( BMP_PLUGIN_URL.'/images/icons/bmp-map-full-screen2.png' ); ?>';
        var bmpMapShapes  = JSON.parse( JSON.stringify(<?php if ( $map_shapes == 0 ) echo '[]'; else echo json_encode( $map_shapes );?>));  
        var bmpMapViews   = JSON.parse('<?php echo json_encode( $bmp_map_views);?>');  
        var s_invalidLatOrLong = "<?php esc_html_e('Invalid Latitude or Longitude','bing-map-pro');?>"; 
        var s_ID          = "<?php esc_html_e('ID', 'bing-map-pro');?>";
        var s_Shortcode   = "<?php esc_html_e('Shortcode', 'bing-map-pro');?>";
        var s_Name        = "<?php esc_html_e('Name', 'bing-map-pro');?>";
        var s_Latitude    = "<?php esc_html_e('Latitude', 'bing-map-pro');?>";
        var s_Longitude   = "<?php esc_html_e('Longitude', 'bing-map-pro');?>";
        var s_Zoom        = "<?php esc_html_e('Zoom', 'bing-map-pro');?>";
        var s_Action      = "<?php esc_html_e('Action', 'bing-map-pro');?>";
        var s_SavedScrollDownToShortcode = "<?php esc_html_e('Saved. Scroll down and select the shortcode from the created view.', 'bing-map-pro');?>";
        var s_PasteItInYourPage     = "<?php esc_html_e('Paste it in your pages or posts!', 'bing-map-pro');?>";
        var s_Error                 = "<?php esc_html_e('Error Occured', 'bing-map-pro');?>";
        var s_NameCannotBeEmpty     = "<?php esc_html_e('Name cannot be empty', 'bing-map-pro');?>";
        var s_MapSaved              = "<?php esc_html_e('Map Saved Succesfully', 'bing-map-pro');?>";
        var s_ChangesRecorded       = "<?php esc_html_e('Changes Recorded', 'bing-map-pro');?>";
        var s_ContinueWithoutSaving = "<?php esc_html_e('Continue without Saving the Map?', 'bing-map-pro');?>"; 
        var s_AnyChangesWontBeSaved = "<?php esc_html_e('Any Changes made wont be saved!', 'bing-map-pro');?>"; 

        var bmpMapShortCode = bmpDataMap[0].map_shortcode;

        bmpMapViews.forEach( function( item ){
            let map_shortcode = bmp_viewToShortcode( item, bmpMapShortCode );
            item.map_shortcode = map_shortcode; 
        });
     
        bmpMapShapes.forEach( function( item ){
                    try{
                    item.shapeData = item.shapeData.replace(/\\/g, '');
                    item.shapeData = JSON.parse( item.shapeData );
                    item.style = item.style.replace(/\\/g, '' );
                    item.style = JSON.parse( item.style );
                    }catch( e ){
                        console.error( 'Error parsing ' + e.message );
                        console.error( 'Error on ' + item.name );
                    }
        });        
       
    </script>
    <?php
        BingMapPro_Includes::bmp_loading_screen();  
        BingMapPro_Includes::bmp_error_screen(); 
    //    bmp_error_api_key( $bmp_api_key, 0 );
        BingMapPro_Includes::bmp_donate();
        echo $bmp_menu_links;
    ?>
    <div class="container-fluid bmp-edit-map-block" style='background: white;'>
    
        <?php
            wp_nonce_field( 'nonce_action_bing_map_pro', 'nonce_bing_map_pro');             
        ?>

        <div class="row">
            <ul class="nav nav-tabs" id="bmp_map_page_nav">
                <li><a href=''> <i class="fas fa-home" style='font-size:28px; color: blue;'></i> </a> </li>
                <li class="active"><a href="#"> <?php esc_html_e('Map', 'bing-map-pro'); ?></a></li>
                <li><a id='bmp_map_pins_anchor' href="#">   <?php esc_html_e('Map Pins', 'bing-map-pro');?>
                    <span class="badge badge-pill badge-info"><?php echo ($map_pins == null) ? '0' : sizeof( $map_pins); ?></span> </a>  </li>               
                <li class=""> <a id='bmp_map_shapes_anchor' href="#">   <?php esc_html_e('Map Shapes', 'bing-map-pro');?>  
                <span class="badge badge-pill badge-info"><?php echo ($map_shapes == 0 ) ? '0' : sizeof( $map_shapes ); ?></span> </a>  </li>   
            </ul>
        </div>
        <div class="row">
            <div class="col-md-5" >
                <div class="h4"> <?php esc_html_e('Settings', 'bing-map-pro'); ?></div>
                    <form method='POST' action='' id='form_save_map' >
                        <div class='row bmp-set-row' >
                            <div class="col-sm-4"> </div> 
                            <div class="col-sm-8"> <button class='button button-primary bmp-map-save-map' > 
                                <?php esc_html_e('Save Map', 'bing-map-pro');?> </button> </div>
                        </div>
                        <hr />
                        <div class='row bmp-set-row' >
                            <div class="col-sm-4 h5"> <?php esc_html_e('Active', 'bing-map-pro');?>  </div> 
                            <div class="col-sm-8">  <?php  echo BingMapPro_Includes::bmp_toggleCkb( esc_html( $bmp_map[0]->map_active ), 'bmp_map_active', 'bmp_map_active'); ?> </div>
                        </div>

                    
                        <div class='row bmp-set-row' >
                            <div class="col-sm-4 h5"> <?php esc_html_e('Title', 'bing-map-pro');?> </div>
                            <div class="col-sm-8"> 
                                <?php $bmp_map_title = str_replace('"', '&#34;', esc_html( $bmp_map[0]->map_title )); $bmp_map_title = str_replace('\\', '', esc_html($bmp_map_title) ); ?>
                                <input  type="text"  name="bmp_map_title" class='form-control' value="<?php echo esc_html($bmp_map_title);?>" 
                                        id="bmp_map_title" placeholder="<?php esc_html_e('Map Title', 'bing-map-pro'); ?>" />
                                    
                            </div>
                        </div>
                    

                        <div class='row bmp-set-row' >
                            <div class="col-sm-4 h5"> <?php esc_html_e('Width', 'bing-map-pro');?> </div>                         
                            <div class="col-sm-8"> 
                                <input type="number"  name="bmp_map_width" class='form-control' id="bmp_map_width"
                                 value="<?php echo esc_attr( $bmp_map[0]->map_width );?>">                                                
                                <?php BingMapPro_Includes::bmp_createMeasureType( $bmp_measure_types, $bmp_map[0]->map_width_type, $bmp_map_width_name, $bmp_map_width_name );?>  
                            </div>   
                        </div>
                    

                        <div class='row bmp-set-row' >
                            <div class="col-sm-4 h5"> <?php esc_html_e('Height', 'bing-map-pro');?> </div>
                            <div class="col-sm-8"> 
                                <input type="number" min='10' name="bmp_map_height" class='form-control' id="bmp_map_height"
                                    value="<?php echo esc_attr( $bmp_map[0]->map_height); ?>"> 
                                <?php BingMapPro_Includes::bmp_createMeasureType( $bmp_measure_types, $bmp_map[0]->map_height_type, $bmp_map_height_name, $bmp_map_height_name );?>  
                            </div>
                        </div>

                        <div class='row bmp-set-row' >
                            <div class="col-sm-4 h5"> <?php esc_html_e('Map Center Latitude', 'bing-map-pro');?> </div>
                            <div class="col-sm-8"> 
                                <input type="text" name="bmp_map_start_lat" class='form-control' id="bmp_map_start_lat" 
                                    value="<?php echo esc_attr( $bmp_map[0]->map_start_lat );?>">                                 
                                <div id='center_map_to_location' title="<?php esc_html_e('Center map to location', 'bing_map_pro');?>">
                                    <i class="fa fa-hand-point-left"></i>
                                </div>
                            </div>
                        </div>


                        <div class='row bmp-set-row' >
                            <div class="col-sm-4 h5"> <?php esc_html_e('Map Center Longitude', 'bing-map-pro');?> </div>
                            <div class="col-sm-8"> 
                                <input type="text" name="bmp_map_start_long" class='form-control' id="bmp_map_start_long" 
                                    value="<?php echo esc_html( $bmp_map[0]->map_start_long);?>">                                 
                            </div>
                        </div>

                        <div class='row bmp-set-row' >
                            <div class="col-sm-4 h5"><?php esc_html_e('Zoom', 'bing-map-pro'); ?> </div> 
                            <div class="col-sm-8">
                                <div class="slidecontainer">
                                    <input type="range" min="2" max="20" id="bmp_map_zoom" 
                                        value="<?php echo esc_html( $bmp_map[0]->map_zoom );?>" class="slider" id="myRange">
                                         <span id='display_zoom_val'> <?php echo esc_html( $bmp_map[0]->map_zoom);?> </span>
                                </div>
                            
                             </div>
                        </div>

                        <div class='row bmp-set-row' >                    
                            <div class="col-sm-4 h5"><strong> <?php esc_html_e('Type', 'bing-map-pro');?> </strong></div> 
                            <div class="col-sm-8"><?php bmp_createMapTypes( $bmp_map_types, $bmp_map[0]->map_type, $bmp_map_type_name, $bmp_map_type_name ) ?> </div>
                        </div>

                        <div class='row bmp-set-row' >
                            <div class="col-sm-4 h5"> <?php esc_html_e('Shortcode', 'bing-map-pro');?> </div> 
                            <div class="col-sm-8"> <input type="text" name="bmp_map_shortcode" class='form-control' 
                            readonly id="bmp_map_shortcode" value="<?php echo esc_html('[bing-map-pro id=' . $bmp_map[0]->id . ']');?>"> </div>
                        </div>

                        <div class='row bmp-set-row' >
                            <div class="col-sm-4 h5"> <?php esc_html_e('Advanced Settings', 'bing-map-pro');?> </div> 
                            <div class="col-sm-8"> 
                                <input type="button" class="button button-primary" id="bmp_map_btn_adv_settings" value="<?php esc_html_e('Setup', 'bing-map-pro');?>">
                            </div>
                        </div>

                        <div class='row bmp-set-row' >
                            <div class="col-sm-4 h5"> <?php esc_html_e('Map Views', 'bing-map-pro');?> </div> 
                            <div class="col-sm-8"> 
                            <input type="button" id="btn_map_snap_view" value="<?php esc_html_e('Snap a Map View', 'bing-map-pro') ?>" class="button button-primary">
                            </div>
                        </div>                    

                        <input type='hidden' name='action' value='save_map' />
                    </form>
            </div>
            <div class="col-md-7" style="min-height: 500px; min-width: 300px">
                <div class="h4"> <?php esc_html_e('Map', 'bing-map-pro'); ?> <span id="map_info_header"> <i>  (<?php esc_html_e('You can move the map around to desired location', 'bing-map-pro');?>) </i> </span>  </div>                
                <div id="bmp_admin_show_map"  style="width: 450px; height: 400px" >                   
                </div>  
                <p></p>
                <center> <input type="button"  value="<?php esc_html_e('Save Map', 'bing-map-pro') ?>" class="button button-primary bmp-map-save-map"> </center>   
                                      
            </div> 
                    

        <form action="" id='bmp_map_form_action' method="post">
                <input type="hidden" value='bmp-add-map-pins' name="bmp_page_action" id='bmp_page_action' />
                <input type='hidden' value="<?php echo esc_html( $bmp_map[0]->id ); ?>" name='bmp_page_map_id' id='bmp_page_map_id' />                  

        </form>

        </div>
        <p></p>
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class='h4'> <?php esc_html_e('Map Views', 'bing-map-pro'); ?> </div>
            </div>
            <div class="panel-body">
                <table class='table' id="table_map_views">
                </table>
            </div>            
        </div>


    </div>

    <!-- Modal Map View -->

    <div class="modal bmp-modal-map-view" style="z-index: 10001;">
            <div class="modal-dialog" style="z-index: 100001;">
            <div class="modal-content">
                <div class="modal-headline"></div>
                    <div class="modal-header">                        
                        <h3 class="modal-title"> <?php esc_html_e('Map View', 'bing-map-pro');?></h3>                        
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-4 h5"><?php esc_html_e('View Name', 'bing-map-pro');?></div>
                            <div class="col-sm-8"> <input type="text" id='input_view_name' placeholder="<?php esc_html_e('Only alphanumeric characters allowed', 'bing-map-pro');?>" class='form-control'></div>
                        </div>
                       
                        <div class="row">
                            <div class="col-sm-4 h5"><?php esc_html_e('Latitude', 'bing-map-pro');?></div>
                            <div class="col-sm-8"> <input readonly type="text" id='input_view_lat' class='form-control'></div> 
                        </div>
                        <div class="row">
                            <div class="col-sm-4 h5"><?php esc_html_e('Longitude', 'bing-map-pro');?></div>
                            <div class="col-sm-8"> <input readonly type="text" id='input_view_long' class='form-control' ></div> 
                        </div>
                        
                        <div class="row">
                            <div class="col-sm-4 h5"><?php esc_html_e('Zoom', 'bing-map-pro');?></div>
                            <div class="col-sm-8"> <input readonly type="text" id='input_view_zoom' class='form-control' ></div> 
                        </div>
                    <div>
                        <div style='text-align: left;'>
                            <p></p>
                            <button type='button' id='cancel' class='button button-secondary' data-dismiss='modal'> <?php esc_html_e('Cancel', 'bing-map-pro');?> </button>                      
                            <button type='button' id='save_map_view' class='button button-primary' > <?php esc_html_e('Save', 'bing-map-pro');?> </button>
                        </div>
                    </div>
                </div>
            </div> 
        </div>
        <input type='hidden' id='' value='new' />
    </div>


    <div class="modal bmp-modal-map-adv-settings" style="z-index: 11115002;">
            <div class="modal-dialog" style="z-index: 1115002;">
            <div class="modal-content">
                <div class="modal-headline"></div>
                    <div class="modal-header">                        
                        <h3 class="modal-title"> <?php esc_html_e('Advanced Settings', 'bing-map-pro');?></h3>                        
                    </div>
                    <div class="modal-body">
                        
                        <div class='row' style="margin: 7px 0" >
                            <div class='col-sm-6'>
                                <label id="bmp_map_disable_mousewheel_lbl"> <?php esc_html_e('Disable Mousewheel Zoom', 'bing-map-pro');?>  </label>
                            </div>
                            <div class="col-sm-6"> 
                                <?php echo BingMapPro_Includes::bmp_toggleCkb( $bmp_map[0]->disable_mousewheel, 'bmp_map_disable_mousewheel', 'bmp_map_disable_mousewheel' ); ?> 
                            </div>
                        </div>

                        <div class='row col-restrict-map' style="margin: 7px 0" >                       
                            <div class="col-sm-6">
                                <label id="bmp_map_lock_zoom_lbl" for=""> <?php esc_html_e('Restrict Map View', 'bing-map-pro');?>  </label>
                            </div>

                            <div class="col-sm-6 " style="display: inline-block"> 
                                <?php echo BingMapPro_Includes::bmp_toggleCkb( $bmp_map[0]->lock_zoom, 'bmp_map_lock_zoom', 'bmp_map_lock_zoom' ); ?> 
                                <a href="<?php echo esc_url(BMP_URLS['restrict_view']); ?>" title="<?php esc_html_e('Click to view demo', 'bing-map-pro'); ?>" target="_blank">
                                    <span class="label label-info"> <?php esc_html_e('New', 'bing-map-pro');?></span> 
                                </a>
                            </div>                            
                        </div>
                        
                   
                        <div class='row' style="margin: 7px 0" >      
                            <div class="col-sm-6">
                                <label for="bmp_map_disable_zoom" style="min-width: 110px;"> <?php esc_html_e('Disable Zoom', 'bing-map-pro');?>  </label>
                            </div>
                            <div class="col-sm-6" data-title='<?php esc_html_e('Disabling zoom will disable mousewheel zoom too!', 'bing-map-pro') ?>'>                                
                                <?php echo BingMapPro_Includes::bmp_toggleCkb( $bmp_map[0]->disable_zoom, 'bmp_map_disable_zoom', 'bmp_map_disable_zoom' ); ?>  
                            </div>
                        </div>

                        <div class='row' style="margin: 7px 0" >
                            <div class="col-sm-6">
                                <label for="bmp_map_compact_nav" style="min-width: 110px;"><?php esc_html_e('Navigation Type', 'bing-map-pro');?> </label> 
                            </div>
                            <div class="col-sm-6">                                                            
                                <select id='bmp_map_compact_nav'>
                                    <option value='0' <?php if( $bmp_map[0]->compact_nav == 0) echo 'selected'; ?> > <?php esc_html_e('Default', 'bing-map-pro');?> </option>
                                    <option value='1' <?php if( $bmp_map[0]->compact_nav == 1) echo 'selected'; ?>  > <?php esc_html_e('Compact', 'bing-map-pro');?> </option>
                                    <option value='2' <?php if( $bmp_map[0]->compact_nav == 2) echo 'selected'; ?>  >  <?php esc_html_e('Square', 'bing-map-pro');?> </option>
                                </select>
                                
                                <a href="<?php echo esc_url( BMP_URLS['square_nav']); ?>" title="<?php esc_html_e('Click to view demo', 'bing-map-pro'); ?>" target="_blank">
                                    <span class="label label-info"> <?php esc_html_e('New', 'bing-map-pro');?></span> 
                                </a>
                            </div>

                        </div>      
                      
                        
                        <div class='row' style="margin: 7px 0" >
                            <div class="col-sm-6">
                                <label for="bmp_map_show_infobox_hover" style="min-width: 160px;"> <?php esc_html_e('Show Infobox On Hover', 'bing-map-pro');?>  </label>
                            </div>
                            <div class="col-sm-6">                                 
                                <?php  echo BingMapPro_Includes::bmp_toggleCkb( $bmp_map[0]->styling_enabled, 'bmp_map_show_infobox_hover', 'bmp_map_show_infobox_hover' );?> 
                            </div>
                        </div>    
                       
                        
                        
                        <div class='row' style="margin: 7px 0" >
                            <div class="col-sm-6">
                                <label for="bmp_map_toggle_fullscreen" style="min-width: 160px"> <?php esc_html_e('Show Fullscreen Icon', 'bing-map-pro');?>  </label>
                            </div>
                            <div class="col-sm-6">                                
                                <?php  echo BingMapPro_Includes::bmp_toggleCkb( $bmp_map[0]->toggle_fullscreen, 'bmp_map_toggle_fullscreen', 'bmp_map_toggle_fullscreen' );?> 
                            </div>
                        </div>    
                        

                        <div class='row' style="margin: 7px 0" >
                            <div class="col-sm-6 h5">
                                 <?php esc_html_e('Clustering Type', 'bing-map-pro');?> 
                            </div> 
                            <div class="col-sm-6"> 
                                <select id='bmp_map_cluster' style="width: 120px;">
                                    <option value="-1" <?php if( $bmp_map[0]->cluster == -1 ) echo 'selected'?> > <?php esc_html_e('None', 'bing-map-pro');?> </option>
                                    <option value="0" <?php if( $bmp_map[0]->cluster == 0 ) echo 'selected'?> > <?php esc_html_e('Default', 'bing-map-pro');?> </option>
                                </select>
                                <span>  <?php echo wp_sprintf( __('<a href="%s" target="_blank"> What is this??? </a>', 'bing-map-pro'),  esc_url(BMP_URLS['clustering']) );?> </span>
                            </div>
                        </div>

                        <div class='row' style="margin: 7px 0" >
                            <div class="col-sm-6 h5"> 
                                <?php esc_html_e('HTML Class', 'bing-map-pro');?> 
                            </div> 
                            <div class="col-sm-6"> 
                                <input type="text" name="bmp_map_html_class" class='form-control'  id="bmp_map_html_class"
                                value="<?php echo esc_html( $bmp_map[0]->html_class ); ?>" 
                                placeholder="<?php esc_html_e('html class  \' " [ ] not allowed', 'bing-map-pro');?>" /> 
                            </div>
                        </div>

                        <div>
                            <div style='text-align: left;'>
                                <p></p>
                                <button type='button' id='cancel' class='button button-secondary' data-dismiss='modal'> <?php esc_html_e('Cancel', 'bing-map-pro');?> </button>                      
                                <button type='button' id='save_map_adv_settings' class='button button-primary' > <?php esc_html_e('Save', 'bing-map-pro');?> </button>
                            </div>
                        </div>

                    </div>  


                    </div>
            </div> 
        </div>
        <input type='hidden' id='' value='new' />
    </div>


<?php   
}

function bmp_createMapTypes( $types, $selType, $name, $id ){
    echo '<table class="table" >';
    echo '<tr>';
    foreach( $types as $key=>$type ){
        $bmp_td = '';
        $bmp_td = '<td>';
        $bmp_td .= "<label for='{$key}_{$id}' class='radio-inline' >";
        if( $key == $selType )
            $bmp_td .= "<input type='radio' class='bmp-map-type' id='{$key}_{$id}' name='{$name}' value='{$key}' checked />";  
        else
            $bmp_td .= "<input type='radio' class='bmp-map-type' id='{$key}_{$id}' name='{$name}' value='{$key}'  />";  
            
            $bmp_td .= "{$type} </label>";
            $bmp_td .= '</td>';
        if( ( $key + 1) % 3 == 0 ){
            $bmp_td .= '</tr>';
            $bmp_td .= '<tr>';
        }
        echo $bmp_td;
    }
    echo '</tr>';
    echo '</table>';
}