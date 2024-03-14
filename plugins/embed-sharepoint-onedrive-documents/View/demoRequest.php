<?php

namespace MoSharePointObjectSync\View;



use MoSharePointObjectSync\Wrappers\pluginConstants;
use MoSharePointObjectSync\Wrappers\wpWrapper;


use MoSharePointObjectSync\Observer\DemoSetupHandler;

class demoRequest{

    private static $instance;
    public static $INTEGRATIONS_TITLE = array(
        'WooCommerce'                         =>  'WooCommerce',
        'BuddyPress'                          =>  'BuddyPress / Buddyboss',
        'MemberPress'                         =>  'MemberPress',
        'LearnDash'                           =>  'LearnDash',
        'ACF'                                 =>  'ACF(Advance Custom Field)',
        'AzureAd'                             =>  'AzureAd',
        
        
    );


    public static function getView(){
        if(!isset(self::$instance)){
            $class = __CLASS__;
            self::$instance = new $class;
        }
        return self::$instance;
    }

    
    

    public function mo_sps_display__tab_details(){
        ?>
        <div class="mo-ms-tab-content" style="width:77rem;">
            <h1 style="font-weight: 500;">Demo Request</h1>
            <div style="width: 68%">
                <div class="mo-ms-tab-content-left-border">
                    <?php
                    $this->mo_sps_display_wp_to_ad_sync_manual_settings();
                    ?>
                </div>
            </div>
        </div>
        <?php
    }

    private function mo_sps_display_wp_to_ad_sync_manual_settings(){
        ?>

    <form id="mo_sps_demo_request_option_value" method="post" name="wp_save_user_form">
            <input type="hidden" name="option" id="sync_user" value="mo_sps_demo_request_option">
            <?php wp_nonce_field('mo_sps_demo_request_option'); ?>
            
            <input type="hidden" name="tab" value="demo_request">

            
            <div class="mo-ms-tab-content-tile" style="width:135%;">
                <div class="mo-ms-tab-content-tile-content">
                    <span style="font-size:22px;font-weight: 500;">
                        Request For Demo
                    </span>
                    <div style="content: '';display: block;height: 5px;background: #1B9BA1;margin-top: 9px;border-radius: 30px;">
                </div>


                    <table class="mo-sf-sync-tab-content-app-config-table mo-ms-tab-content-app-config-table">
                        <tr>
                            <td class="left-div"><span>Email<sup style="color:red">*</sup>:</span></td>
                            <td>
                                <input class="mo-sf-w-3" type="email" required placeholder="person@example.com" name="demo_email" value="<?php echo wp_get_current_user()->user_email ?>">
                            </td>
                        </tr>
                        <tr>
                            <td class="left-div"><span>Description<sup style="color:red">*</sup>:</span></td>
                            <td>
                                <textarea class="mo-sf-w-3" rows="4" type="text" required placeholder="Tell us about your requirement." name="demo_description"></textarea>

                            </td>
                        </tr>
                    </table>    
                    <h2 class="mo-sf-form-head mo-sf-form-head-bar">Select the Add-ons you are interested in (Optional) :</h2>


                    <?php
                                
                             
                               
                    $column = 0;
                    $column_start = 0;
                    foreach(self::$INTEGRATIONS_TITLE as $key => $value){?>

                        <?php if($column % 3 === 0) {
                            $column_start = $column;?>
                            <div class="align-items-top mo-saml-opt-add-ons" style="flex-direction: row;align-items:right;display:flex;">
                        <?php } ?>
                        <div class="col-md-4">
                        <input type="checkbox" name="<?php esc_attr_e($key, 'miniorange-wp-sync-for-sharepoint-office365'); ?>" value="true"> <span class="mo-sf-text"><?php esc_html_e($value, 'miniorange-wp-sync-for-sharepoint-office365'); ?></span>
                        </div>
                        <?php if($column === $column_start + 2) {?>
                            </div>
                        <?php } ?>

                        <?php $column++;
                    }
                                ?>
                        
                               <div class="mo-sf-mt-4" style="display:flex;justify-content:center;">
                               <input type="submit" style="height:40px;" class="mo-ms-tab-content-button" name="submit" value="Send Request">
                               </div>
            </form>
    </div>
    </div>
        
    <?php
    }

}