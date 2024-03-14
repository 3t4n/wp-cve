<?php 
if (!defined('ABSPATH')) exit; // Exit if accessed directly
/**
 * Responsible for managing basic settings
 *
 * @link       http://rextheme.com/
 * @since      1.0.0
 *
 * @package    Wpvr
 * @subpackage Wpvr/admin/classes
 */

 class WPVR_Basic_Setting {
     function __construct()
     {
         
     }


     /**
      * Render Basic Setting Tab Content
      * @param mixed $preview
      * @param mixed $previewtext
      * @param mixed $autoload
      * @param mixed $control
      * @param mixed $postdata
      * @param mixed $autorotation
      * @param mixed $autorotationinactivedelay
      * @param mixed $autorotationstopdelay
      * 
      * @return void
      */
     public function render_basic_setting($postdata)
     {
         ob_start();
         ?>
        <div class="basic-settings-content inner-single-content active" id="gen-basic">
            <?php $this->render_basic_setting_content_wrapper($postdata) ;?>
        </div>
        <?php
        ob_end_flush();
     }


     private function render_basic_setting_content_wrapper($postdata)
     {
        ob_start();
        $status  = get_option('wpvr_edd_license_status');
        ?>
        <div class="content-wrapper">
            <div class="left">
                <?php WPVR_Meta_Field::render_basic_settings_left_fields($postdata);?>
            </div>

            <div class="right">
                <?php WPVR_Meta_Field::render_basic_setting_right_fields($postdata) ;?>

                <div class="autorotationdata-wrapper">
                    <?php WPVR_Meta_Field::render_autorotation_data_wrapper_fields($postdata) ;?>
                </div>

                <?php 
                    if(is_plugin_active('wpvr-pro/wpvr-pro.php')) {
                        if ($status !== false && $status == 'valid') {
                            ?>
                            <?php WPVR_Meta_Field::render_basic_setting_generic_form_fields($postdata);?>
                            <div class="generic-form-associates">
                                <?php WPVR_Meta_Field::render_generic_form_associate_fields($postdata) ;?>
                            </div>

                            <?php WPVR_Meta_Field::render_basic_setting_call_to_action_fields($postdata);?>
                            <div class="call-to-action">
                                <?php WPVR_Meta_Field::render_call_to_action_associate_fields($postdata) ;?>
                            </div>

                            <?php WPVR_Meta_Field::render_basic_setting_custom_css_fields($postdata);?>
                            <div class="custom-css-field">
                                <?php WPVR_Meta_Field::render_custom_css_associate_fields($postdata) ;?>
                            </div>
                            <?php
                        }
                    }                
                ?>
            </div>

        </div>
        <?php
        ob_end_flush();
     }
 }