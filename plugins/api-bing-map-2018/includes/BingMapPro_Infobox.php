<?php
namespace BingMapPro_Infobox;

if( ! defined('ABSPATH') ) die('No Access to this page');

include_once('BingMapPro_Config.php');

class BingMapPro_Infobox{
    
    public static function bmp_simple_info(){
        $bmp_infobox        = esc_html__('Simple Infobox', 'bing-map-pro');
        $bmp_title          = esc_html__('Title', 'bing-map-pro');
        $bmp_description    = esc_html__('Description:', 'bing-map-pro');
        $bmp_save           = esc_html__('Save', 'bing-map-pro');
        $bmp_cancel         = esc_html__('Cancel', 'bing-map-pro');
        ?>
          <div class='modal bmp-modal-infobox-simple' style='z-index: 20000;'> 
            <div class='modal-dialog modal-lg'> 
            <div class='modal-content'> 
                <div class='modal-headline'></div>
                <div class='modal-header'>
                   <button type='button' class='close' data-dismiss='modal'>&times;</button> 
                 <h3 class="modal-title"><?php echo esc_html( $bmp_infobox ); ?> </h3>
                </div>   
                <div class="modal-body">

                        <div class='row bmp-set-row' >
                            <div class="col-sm-3 h5"> <?php echo esc_html( $bmp_title ); ?> </div>
                                <div class="col-sm-8"> 
                                    <input  type="text" max-length='50'  name="bmp_infobox_title" class='form-control' value="" 
                                            id="bmp_infobox_title" placeholder="<?php echo esc_html( $bmp_title );?>" />
                                        
                            </div>
                        </div>


                        <div class='row bmp-set-row' >
                            <div class="col-sm-3 h5"> <?php echo esc_html( $bmp_description ); ?> </div>
                                <div class="col-sm-8"> 
                                    <textarea rows='15'  type="text"  name="bmp_infobox_description" class='form-control' value="" 
                                            id="bmp_infobox_description" placeholder="<?php echo esc_html( $bmp_description );?>" ></textarea>
                                        
                            </div>
                        </div>

                        <div class="row bmp-set-row">
                            <div class="col-sm-3">

                            </div>
                            <div class="col-sm-8">
                                <input type="button" data-dismiss="modal" class='button button-secondary' id="bmp_btn_infobox_simple_cancel"
                                     value="<?php echo esc_html( $bmp_cancel );?>">
                                <input type="button" class='button button-primary' id='bmp_btn_infobox_simple_save' 
                                    value="<?php echo esc_html( $bmp_save );?>">
                                <input type='hidden' id="bmp_simple_shape_type" value="" />
                            </div>
                        </div>

                <!--    </div> -->
             <!--   </div> -->
                        </div>                                     
                    </div>
                </div>                                        
            </div>
<?php
    }

    public static function bmp_advanced_info(){
        ob_flush();        
        ob_start();
        ?>
        <div class="modal bmp-modal-infobox-advanced" style='z-index: 20000;'>
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-headline"></div>
                <div class="modal-header">
                    <button type='button' class='close' data-dismiss='modal'>&times;</button>                    
                    <h3 class="modal-title"> <?php esc_html_e('Advanced Infobox', 'bing-map-pro');?> </h3>

                    <div class="alert alert-warning alert-dissmissable fade in">
                        <button type='button' class='close' data-dismiss="alert" aria-label='Close'>
                            <span aria-hidden='true'>&times;</span>
                        </button>
                        <h5> <?php esc_html_e('(Title and description are in the same block)', 'bing-map-pro');?> </h5>
                        <h6> <?php esc_html_e('***Infobox content has different style in the admin page, and front pages bacause of the theme styling (css), therefore it might have different margins, paddings, and sizes ***', 'bing-map-pro');?> </h6>
                    </div>
                    <?php 
                        if( ! is_plugin_active('tinymce-advanced/tinymce-advanced.php') ){ ?>
                            <div class="alert alert-warning alert-dismissable fade in">
                                <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                                    <span aria-hidden='true'>&times;</span>";
                                    </button>
                                    <h5 style="color: red;">
                                <?php   esc_html_e('Tiny MCE Advanced is not installed, you have limited features with this editor!', 'bing-map-pro'); ?>
                                    </h5>
                                    <h5 style="color: red;">
                                        <?php    
                                            echo( 
                                                wp_sprintf( '%s <a href="%s" target="_blank"> %s </a>', 
                                                                 __('You can find the Advanced Version ', 'bing-map-pro'), 
                                                                esc_url( BMP_URLS['adv_tinymce'] ) , 
                                                                __('HERE', 'bing-map-pro')
                                                          )
                                                ); 
                                        ?>
                                    </h5>

                            </div>                      
                    <?php
                        }
                    ?>
                </div>   

                <div class="modal-body" style='min-height: 200px'>

                        <div>
                            <?php
                                $content = '';                           
                                $editor_id = 'bmp_infobox_editor_wp';                              
                               
                                wp_editor( $content, $editor_id, array(
                                    'textarea_rows' => 15
                                ));
                                                                
                            ?>
                        </div>

                        <div class="row bmp-set-row">
                            <div class="col-sm-0">

                            </div>
                            <div class="col-sm-12">
                                <input type="button" data-dismiss='modal' class='button button-secondary' id="bmp_btn_infobox_advanced_cancel" value="<?php esc_html_e('Cancel', 'bing-map-pro');?>">
                                <input type="button" class='button button-primary' id='bmp_btn_infobox_advanced_update' value="<?php esc_html_e('Save', 'bing-map-pro');?>">
                                <input type='hidden' id="bmp_adv_shape_type" value="" />
                            </div>
                        </div>

                </div>                                     
            </div>
        </div>                                        
    </div>

    <?php
    
    }

    public static function bmp_infobox_infoEditorError(){
        ?>
        <div class="modal bmp-modal-infobox-error" style='z-index: 20000;'>
            <div class="modal-dialog ">
                <div class="modal-content">
                    <div class="modal-headline"></div>
                    <div class="modal-header">
                        <button type='button' class='close' data-dismiss='modal'>&times;</button>                    
                        <h3 class="modal-title"> <?php esc_html_e('Tiny MCE html editor not loading', 'bing-map-pro');?> </h3>
                    </div>   
                    <div class="modal-body" style='min-height: 200px'>
                        <h4> <?php esc_html_e('Try Possible fixes, in order', 'bing-map-pro'); ?></h4>

                        <ul style="list-style-type: circle; margin-left: 20px">
                            <li> <?php esc_html_e('Most Common Fix: Go to Users->Edit Current User and Uncheck "Disable the visual editor when writing", and click Save', 'bing-map-pro') ?></li>
                            <li> <?php esc_html_e('Edit wp-config, add "define(\'CONCATENATE_SCRIPTS\', false);" under "define(\'db_collate\');" ', 'bing-map-pro') ?></li>
                            <li> <?php esc_html_e('Create a new user with administrator rules', 'bing-map-pro') ?></li>
                            <li> <?php esc_html_e('Open TinyMCE settings page and click save. (It will reset the options)', 'bing-map-pro');?></li>
                            <li> <?php esc_html_e('TinyMCE folder "wp-includes\js\tinymce" has to have 755 folder permissions ', 'bing-map-pro');?></li>
                        </ul>

                        <h5> <?php echo sprintf('<a href="%s" target="_blank"> %s </a>', esc_url( BMP_URLS['info_error_editor'] ) , esc_html( 'Click Here for more explained solutions', 'bing-map-pro') ) ?> </h5>

                        <div class="row bmp-set-row">

                                <div class="col-sm-12">
                                    <input type="button" data-dismiss='modal' class='button button-secondary'  value="<?php esc_html_e('Cancel', 'bing-map-pro');?>">
                                </div>
                            </div>

                    </div> 

                </div>
            </div>                                        
        </div>

    <?php      
    }
}