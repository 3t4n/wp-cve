<?php
namespace BingMapPro_GeneralSettings;
if( ! defined('ABSPATH') ) die('No Access to this page');

include_once('BingMapPro_Includes.php');
use BingMapPro_Includes;

class BingMapPro_GeneralSettings{
    public static function bmp_generalSettings( $settings, $bmp_menu_links ){
    ?>    

        <div class='wrap'>
            <script>
                var bmp_settings_page = JSON.parse( JSON.stringify( <?php echo json_encode( $settings ); ?> ));                
            </script>
            
            <div class="container-fluid">
                
                <?php
                    BingMapPro_Includes\BingMapPro_Includes::bmp_loading_screen();   
                    BingMapPro_Includes\BingMapPro_Includes::bmp_error_screen();
                //    bmp_error_api_key( $settings['bmp_api_key'], 1);
                    BingMapPro_Includes\BingMapPro_Includes::bmp_donate();
                    echo $bmp_menu_links;
                    wp_nonce_field( 'nonce_action_bing_map_pro', 'nonce_bing_map_pro');             
                ?>  
                <div class="panel panel-default bmp-settings-new-panel">
                    <div class="panel-heading">
                        <h1 class="h3"> <?php esc_html_e( 'General Settings', 'bing-map-pro' );?> </h1> 
                    </div>

                    <div class="panel-body">
                        <form action="" method='POST' class="">
                        
                        <?php if( ! $settings['hide_api_key'] ) { ?>
                            <div class='row bmp-row-settings-key'>
                                <div class="col-sm-8">
                                    <div class="input-group">

                                        <span class="input-group-addon" id="basic-addon1"> <?php esc_html_e('API KEY:', 'bing-map-pro');?> </span>
                                    
                                        <input  type="text" class="form-control" onchange="SetChanged(true);" 
                                                placeholder="<?php esc_html_e('API KEY:', 'bing-map-pro');?>"
                                                aria-label="Username" aria-describedby="basic-addon1"
                                                id='bmp_api_key' value="<?php  echo esc_html( trim( $settings['bmp_api_key']) );?>"  /> 
                                                
                                                
                                    </div>
                                </div>

                                <div class="col-sm-4">
                                    <a style="display: inline-block;" href="https://www.bingmapsportal.com/" target="_blank"> 
                                    <?php esc_html_e('Get Bing Map API Key', 'bing-map-pro');?>  </a>
                                </div>
                                
                            </div> 
                        <?php } ?>
                        
                        <div class="panel panel-default bmp-settings-new-map-default">
                            <div class="panel-heading">
                                <h4> <?php esc_html_e('New Map Default Settings', 'bing-map-pro'); ?> </h4>
                            </div>
                            <div class="panel-body">

                                <table class='table bmp_table_general_settings'>

                                    <tr>
                                        <td>                    
                                            <div class="col-sm-5">
                                                <label >                            
                                                    <?php esc_html_e('Disable Mousewheel Zoom Scroll On Map:', 'bing-map-pro');?> 
                                                </label>
                                                    <input  type="checkbox" onchange="SetChanged(true);" <?php if( $settings['bmp_dsom'] === 'true' ) echo 'checked';?>  
                                                            name="bmp_disable_scroll_on_map" id="bmp_disable_scroll_on_map" data-size="mini" data-toggle='toggle' 
                                                            data-on="<?php esc_html_e('Yes', 'bing-map-pro');?>" data-off="<?php esc_html_e('No', 'bing-map-pro') ?>" />     
                                                
                                            </div>  
                                        </td>  
                                    </tr>
                                    <tr>
                                        <td>                    
                                            <div class="col-sm-5">
                                                <label class=''>                            
                                                    <?php esc_html_e('Compact Navigation Bar:', 'bing-map-pro');?> 
                                                </label>
                                                    <input  type="checkbox" onchange="SetChanged(true);" <?php if( $settings['bmp_cnb'] === 'true' ) echo 'checked';?>  
                                                            name="bmp_compact_navigation_bar" id="bmp_compact_navigation_bar" data-size="mini" data-toggle='toggle'
                                                            data-on="<?php esc_html_e('Yes', 'bing-map-pro');?>" data-off="<?php esc_html_e('No', 'bing-map-pro') ?>"  />     
                                                
                                            </div>  
                                        </td>  
                                    </tr>

                                    <tr>
                                        <td>                    
                                            <div class="col-sm-5">
                                                <label class=''>                            
                                                    <?php esc_html_e('Disable Zoom:', 'bing-map-pro');?>
                                                </label>
                                                    <input  type="checkbox" onchange="SetChanged(true);" <?php if( $settings['bmp_dz'] === 'true' ) echo 'checked';?>  
                                                            name="bmp_disable_zoom" id="bmp_disable_zoom" data-size="mini" data-toggle='toggle' 
                                                            data-on="<?php esc_html_e('Yes', 'bing-map-pro');?>" data-off="<?php esc_html_e('No', 'bing-map-pro') ?>"  />     
                                                
                                            </div>  
                                        </td>  
                                    </tr>
                                </table>
                            
                            </div>
                        </div> 

                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4> <?php esc_html_e('Advanced Infobox Sizes', 'bing-map-pro') ?> </h4>
                            </div>
                            <div class="panel-body bmp-pnl-settings-infobox">
                                <div class="row">
                                    <div class="panel col-md-4 panel-default">
                                        <div class="panel-heading">
                                        <h5> <?php esc_html_e('Desktop (min-width: 1025px)', 'bing-map-pro'); ?></h5>
                                        </div>
                                        <div class="panel-body">

                                            <div class="form-group form-inline" style='padding-left: 10px;'>
                                                <label for='bmp_settings_pin_desktop_width' >                            
                                                    <?php esc_html_e('Width:', 'bing-map-pro');?> 
                                                </label>
                                                    <input  type="number" class='form-control' min='20' max='1200' onchange="SetChanged(true);" 
                                                            value="<?php echo esc_html( $settings['bmp_pin_desktop_width'] ); ?>" 
                                                            name="bmp_settings_pin_desktop_width" id="bmp_settings_pin_desktop_width"  />  px  
                                                
                                            </div> 

                                            <div class="form-group form-inline" style='padding-left: 10px;'>
                                                <label for='bmp_settings_pin_desktop_height' >                            
                                                    <?php esc_html_e('Height:', 'bing-map-pro');?> 
                                                </label>
                                                    <input  type="number" class='form-control' min='20' max='1200' onchange="SetChanged(true);" 
                                                            value="<?php echo esc_html( $settings['bmp_pin_desktop_height'] ); ?>" 
                                                            name="bmp_settings_pin_desktop_height" id="bmp_settings_pin_desktop_height"  />  px  
                                                
                                            </div>   
                                        </div>
                                    </div>

                                    <div class="panel col-md-4 panel-default">
                                        <div class="panel-heading">
                                        <h5> <?php esc_html_e('Tablet (min-width: 768px - max-width: 1024px)', 'bing-map-pro'); ?></h5>
                                        </div>
                                        <div class="panel-body"> 

                                            <div class="form-group form-inline" style='padding-left: 10px;'>
                                                <label for='bmp_settings_pin_tablet_width' >                            
                                                    <?php esc_html_e('Width:', 'bing-map-pro');?> 
                                                </label>
                                                    <input  type="number" class='form-control' min='20' max='1200' onchange="SetChanged(true);" 
                                                            value="<?php esc_html_e( $settings['bmp_pin_tablet_width'] ); ?>" 
                                                            name="bmp_settings_pin_tablet_width" id="bmp_settings_pin_tablet_width"  />  px  
                                                
                                            </div> 

                                            <div class="form-group form-inline" style='padding-left: 10px;'>
                                                <label for='bmp_settings_pin_tablet_height' >                            
                                                    <?php esc_html_e('Height:', 'bing-map-pro');?> 
                                                </label>
                                                    <input  type="number" class='form-control' min='20' max='1200' onchange="SetChanged(true);" 
                                                            value="<?php esc_html_e( $settings['bmp_pin_tablet_height'] ); ?>" 
                                                            name="bmp_settings_pin_tablet_height" id="bmp_settings_pin_tablet_height"  />  px  
                                                
                                            </div>  
                                        </div>
                                    </div>

                                    <div class="panel col-md-4 panel-default">
                                        <div class="panel-heading">
                                        <h5> <?php esc_html_e('Mobile (max-width: 767px)', 'bing-map-pro'); ?></h5>
                                        </div>
                                        <div class="panel-body">

                                            <div class="form-group form-inline" style='padding-left: 10px;'>
                                                <label for='bmp_settings_pin_mobile_width' >                            
                                                    <?php esc_html_e('Width:', 'bing-map-pro');?> 
                                                </label>
                                                    <input  type="number" class='form-control' min='20' max='1200' onchange="SetChanged(true);" 
                                                            value="<?php esc_html_e( $settings['bmp_pin_mobile_width'] ); ?>" 
                                                            name="bmp_settings_pin_mobile_width" id="bmp_settings_pin_mobile_width"  />  px  
                                                
                                            </div> 

                                            <div class="form-group form-inline" style='padding-left: 10px;'>
                                                <label for='bmp_settings_pin_mobile_height' >                            
                                                    <?php esc_html_e('Height:', 'bing-map-pro');?> 
                                                </label>
                                                    <input  type="number" class='form-control' min='20' max='1200' onchange="SetChanged(true);" 
                                                            value="<?php esc_html_e( $settings['bmp_pin_mobile_height'] ); ?>" 
                                                            name="bmp_settings_pin_mobile_height" id="bmp_settings_pin_mobile_height"  />  px  
                                                
                                            </div>  
                                        </div>
                                    </div>

                                </div>


                            </div>
                        </div>

                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4> <?php esc_html_e('WooCommerce Checkout Address Autosuggest') ?></h4> 
                            </div>

                            <div class="panel-body">
                                <div>
                                    <label for="bmp_settings_ckb_autosuggest">
                                        <?php esc_html_e('Enable Autosuggest ') ?>
                                        <input type="checkbox" name=""  id="bmp_settings_ckb_autosuggest" data-toggle='toggle' data-size='small' 
                                        data-on="<?php esc_html_e('Yes', 'bing-map-pro');?>" data-off="<?php esc_html_e('No', 'bing-map-pro') ?>"    />
                                    </label>
                                </div> 

                                <div>
                                   
                                        <?php esc_html_e('Restrict Autosuggest by Country (country code)') ?>
                                        <input type="text" value="<?php esc_html_e( $settings['restrict_suggest'] ); ?>"   id="bmp_restrict_autosuggest" />

                                    <?php echo sprintf( esc_html( 'Find country code %s'), "<a href='https://support.microsoft.com/en-gb/topic/add36afe-804a-44f1-ae68-cfb9c9b72f8b' target='_blank'> Here </a>" ); ?>
                                </div> 

                                <div>
                                    <h5> *** <?php esc_html_e('Requirements', 'bing-map-pro'); ?> *** </h5>
                                    <h5 class="bmp-req-woo-api">        1. <?php esc_html_e( 'Valid Bing Map API Key', 'bing-map-pro'); ?>              </h5>
                                    <h5 class="bmp-req-woo-installed">  2. <?php esc_html_e( 'WooCommerce Installed & Activated', 'bing-map-pro'); ?>   </h5>
                                </div>                           
                            </div>
                        </div>

                    
                        <table>
                            <tr>
                                <td>                    
                                    <button type="submit" onclick="SetChanged(false);" id='bmp_api_key_submit' class="button button-primary"><?php esc_html_e('Save', 'bing-map-pro');?></button> 
                                </td>  
                            </tr>
                        </table>
                                    
                            
                    </form>
                    </div>
                
                </div>
                          


            </div>
        </div>
     

        <div class="modal  modal_settings_page fade">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><?php esc_html_e('Info', 'bing-map-pro');?></h4>
                
            </div>
            <div class="modal-body">
                <p > <b> <?php esc_html_e('Changes have been recorded.', 'bing-map-pro');?> </b> </p>
                <p > <b> <?php esc_html_e('Leaving the page won\'t save the changes', 'bing-map-pro');?> </b></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="button button-secondary modal_settings_page_close" data-dismiss="modal"> 
                        <?php esc_html_e('Cancel', 'bing-map-pro');?> </button>
                <button type="button" class="button button-primary modal_settings_page_ok" onclick='BmpGoToPage()'> <?php echo esc_html_e('Ok', 'bing-map-pro');?> </button>
            </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->        

   
    <?php
    }
}