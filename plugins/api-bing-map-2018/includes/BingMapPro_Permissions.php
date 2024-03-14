<?php

namespace BingMapPro_Permissions;

if( ! defined('ABSPATH') ) die('No Access to this page');

include_once( 'BingMapPro_Includes.php');
use BingMapPro_Includes;

class BingMapPro_Permissions{
    public static function bmp_show_permissions_html( $bmp_permissions, $bmp_menu_links ){
        ?>

        <div class='wrap'>
            <div class="container-fluid">  
                <?php
                    BingMapPro_Includes\BingMapPro_Includes::bmp_loading_screen();  
                    BingMapPro_Includes\BingMapPro_Includes::bmp_error_screen();             
                    BingMapPro_Includes\BingMapPro_Includes::bmp_donate();   
                    echo $bmp_menu_links; 
                    wp_nonce_field( 'nonce_action_bing_map_pro', 'nonce_bing_map_pro');    
                ?>

                <div class="panel panel-default bmp-permissions-new-panel">
                        <div class="panel-heading">
                           <h3> <?php esc_html_e('Role Permissions', 'bing-map-pro') ?> </h3>
                        </div>
                        <div class="panel-body">

                            <div class="row">
                                <label style="min-width: 130px; margin-left: 30px; ">
                                    <h5 style="font-size: 1.3em;"> <?php esc_html_e('Administrator', 'bing-map-pro'); ?>    </h5>
                                </label>   
                                                                                                
                                <input type="button" style="width: 100px;" class="button button-primary" data-toggle="toggle"  value="<?php esc_html_e('Allow', 'bing-map-pro'); ?>"  
                                       id="bmp_editor_admin"  name="bmp_editor_admin"  data-size='small'
                                />
                            </div>

                            <div class="row">
                                <label style="min-width: 130px; margin-left: 30px;">
                                    <h5 style="font-size: 1.3em;" > <?php esc_html_e('Editor', 'bing-map-pro'); ?>    </h5>
                                </label>   
                                                                                                
                                <input type="checkbox" style="min-width: 85px;" data-toggle="toggle" data-on="<?php esc_html_e('Allow', 'bing-map-pro'); ?>" data-off="<?php esc_html_e('Restrict', 'bing-map-pro');?>" 
                                       id="bmp_editor_permissions" data-width='100px' data-size='small' name="bmp_editor_permissions" 
                                        <?php if($bmp_permissions['editor']){echo 'checked';}?>  
                                />
                            </div>
                            <div class="row">
                            <label style="min-width: 130px; margin-left: 30px;">
                                    <h5 style="font-size: 1.3em;" > <?php esc_html_e('Author', 'bing-map-pro'); ?>    </h5>
                                </label>   
                                                                                                
                                <input type="checkbox" style="min-width: 85px;" data-toggle="toggle" 
                                data-on="<?php esc_html_e('Allow', 'bing-map-pro'); ?>" data-off="<?php esc_html_e('Restrict', 'bing-map-pro');?>" 
                                       id="bmp_author_permissions" data-width='100px' data-size='small'  name="bmp_author_permissions" 
                                        <?php if($bmp_permissions['author']){echo 'checked';}?>
                                />
                            </div>

                            <div class="row">
                            <label style="min-width: 130px; margin-left: 30px;">
                                    <h5 style="font-size: 1.3em;" > <?php esc_html_e('Contributor', 'bing-map-pro'); ?>    </h5>
                                </label>   
                                                                                                
                                <input type="checkbox" style="min-width: 85px;" data-toggle="toggle" 
                                data-on="<?php esc_html_e('Allow', 'bing-map-pro'); ?>" data-off="<?php esc_html_e('Restrict', 'bing-map-pro');?>" 
                                       id="bmp_contributor_permissions" data-width='100px' data-size='small' name="bmp_contributor_permissions"
                                        <?php if($bmp_permissions['contributor']){echo 'checked';}?>
                                />
                            </div>

                            <div class="row">
                            <label style="min-width: 130px; margin-left: 30px;">
                                    <h5 style="font-size: 1.3em;"  > <?php esc_html_e('Hide API Key', 'bing-map-pro'); ?>    </h5>
                                </label>   
                                                                                                
                                <input type="checkbox" style="min-width: 85px;" data-toggle="toggle" data-on="<?php esc_html_e('Yes', 'bing-map-pro'); ?>" 
                                       data-off="<?php esc_html_e('No', 'bing-map-pro');?>" 
                                       id="bmp_hide_api_key" data-width='100px' data-size='small' name="bmp_hide_api_key" 
                                       <?php if($bmp_permissions['hide_api_key']){echo 'checked';}?>
                                />
                                <i class="fas fa-info-circle" data-toggle="tooltip" data-placement="right" data-html="true" title=""
                                 data-original-title="<?php esc_html_e('Hide API Key from ALLOWED roles Editor, Author, Contributor', 'bing-map-pro');  ?>">
                                </i>
                            </div>


                        </div>
                    <div style="margin-left: 30px;" >
                        <button class='button button-primary' id="bmp_save_permissions"> <?php esc_html_e('Save', 'bing-map-pro'); ?> </button>
                    </div>

                    <div style='height: 30px'>

                    </div>

                </div>




            </div>
        </div>

        <script>
            var bmp_data_perms = <?php echo json_encode( $bmp_permissions );?>;
        </script>

    <?php
    }
}