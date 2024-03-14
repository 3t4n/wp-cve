<?php

namespace MoSharePointObjectSync\View;
use MoSharePointObjectSync\Wrappers\pluginConstants;
use MoSharePointObjectSync\Wrappers\wpWrapper;
use MoSharePointObjectSync\API\Azure;
use MoSharePointObjectSync\Observer\shortcodeSharepoint;
use WP_Roles;

class Shortcode{

    private static $instance;

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
            <h1>Embed Library</h1>
            
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

        $wp_roles         = new WP_Roles();
        $roles            = $wp_roles->get_names();
        $drive_id         = wpWrapper::mo_sps_get_option(pluginConstants::SPS_SEL_DRIVE);
        
        ?>

    <div class="mo-ms-tab-content-tile" style="width:135%;">
    
        <div class="mo-ms-tab-content-tile-content">
            <span style="font-size: 18px;font-weight: 700;">1. Embed using WordPress Shortcode</span>
            <div id="basic_attr_access_desc" class="mo_sps_help_desc" style="font-weight:500;">
                <span>Copy this shortcode and follow the below steps to embed  sharepoint documents.
                    </span>
            </div>
            <div>
                <ol style="margin-left:20px;">
                    <li>Copy the <b>Shortcode</b> given below.</li>
                </ol>
            </div>
            <div style="background-color:#eee;display:flex;align-items:center;padding:12px;margin-top:1rem;">
                <span style="width:99%;" id="mo_copy_shortcode">[MO_SPS_SHAREPOINT width="100%" height="800px"]</span>
                <form id="mo_copy_to_clipboard" method="post" name="mo_copy_to_clipboard">
                    <input type="hidden" name="option" id="app_config" value="mo_copy_to_clipboard">
                    <input type="hidden" name="mo_sps_tab" value="app_config">
                    <?php wp_nonce_field('mo_copy_to_clipboard');?>
                    <div style="margin-left:3px;">
                        <button type="button" class="mo_copy copytooltip rounded-circle float-end" style="background-color:#eee;width:40px;height:40px;margin-top:0px;border-radius:100%;border:0 solid;">
                            <img style="width:25px;height:25px;margin-top:0px;margin-left:0px;" src="<?php echo esc_url(plugin_dir_url(__FILE__).'../images/copy.png');?>" onclick="copyToClipboard(this, '#mo_copy_shortcode', '#copy_shortcode');">
                            <span id="copy_shortcode" class="copytooltiptext">Copy to Clipboard</span>
                        </button>
                    </div>                 
                </form>
            </div>
            <div>
                <ol start="2" style="margin-left:20px;">
                    <li>Go to the <a href="<?php echo admin_url() . 'edit.php?post_type=page';?>"><b>Pages</b></a> or <a href="<?php echo admin_url() . 'edit.php?post_type=post';?>"><b>Posts</b></a> tab in your WordPress dashboard.</li>
                    <li>Click on add new / select any existing post/page on which you want to embed sharepoint library</li>
                    <li>Click the "+" icon and search for <b>Shortcode</b></li>
                    <li>Paste the copied shortcode into the shortcode block.</li>
                    <li>Modify 'width' and 'height' attributes as per your need.</li>
                    <li>Preview changes and then click <b>Publish</b> or <b>Update</b>.</li>
                </ol>
            </div>
        </div>
    </div>
    <div class="mo-ms-tab-content-tile" style="width:135%;">  
        <div class="mo-ms-tab-content-tile-content">
            <span style="font-size: 18px;font-weight: 700;">2. Embed Documents Using Gutenburg Block</span>
            </br>
            </br>
            <div style="margin-bottom:10px;"><b>Note:</b>Follow below steps to Embed documents in pages and posts using gutenburg block.</div>
            <div>
                <ol style="margin-left:20px;">
                    <li>Go to the <a href="<?php echo admin_url() . 'edit.php?post_type=page';?>"><b>Pages</b></a> or <a href="<?php echo admin_url() . 'edit.php?post_type=post';?>"><b>Posts</b></a> tab in your WordPress dashboard.</li>
                    <li>Click on add new / select any existing post/page on which you want to embed sharepoint library</li>
                    <li>Click on "+" icon and search <strong>sharepoint library</strong></li>
                    <li>Enter the height and width as per your preference</li>
                    <li>Now save this and click on pubish</li>
                    <li>Wohooo!!Now you can see your media library on updated page/post.</li>
                </ol>
            </div>
            
        </div>
    </div>
    
    
    <form id="wp_save_user_form" method="post" name="wp_save_user_form" class="mo-sps-prem-info">
            <div class="mo-ms-tab-content-tile" style="width:135%;padding: 1rem;background: #f4f4f4;border: 4px solid #A6DEE0;border-radius: 5px;margin-top:0px !important;padding-top:0px !important;">
                <div class="mo-ms-tab-content-tile-content" style="position:relative;">
                    <div style="display:flex;align-items: center;justify-content: space-between;">
                        <div style="font-weight: 350;width: 99%;">
                        <div class="mo-ms-tab-content-tile-content">
                            
                            <span style="font-size: 18px;font-weight: 500;">
                            3. Schedule Documents Sync 
                            <sup style="font-size: 12px;color:red;font-weight:600;">
                                [Available in <a target="_blank" href="https://plugins.miniorange.com/microsoft-sharepoint-wordpress-integration#pricing-cards" style="color:red;">Paid</a> Plugins]       
                            </sup>
                        </span>
                        <div class="mo-sps-prem-lock" style="top:2px;right:2px;position:absolute;">
                                <img class="filter-green"
                                 src="<?php echo esc_url(plugin_dir_url(__FILE__).'../images/lock.svg');?>">
                                <p class="mo-sps-prem-text">Available in <a target="_blank" href="https://plugins.miniorange.com/microsoft-sharepoint-wordpress-integration#pricing-cards" style="color:#ffeb00;;">Paid</a> plugins.</p>
				        </div>
                    <div class="mo_sps_help_desc">
						<span>
                        Sync SharePoint Online library to WordPress.
                         </span>
                    </div>

                  
                    <table class="mo-ms-tab-content-app-config-table">
                        <tr>
                            <td style="width:35%;word-break: break-all;"><span><h4>Sync Interval in minutes</h4></span></td>
                            <td class="right-div" >
                                <input style="width:70%" type="number" disabled name="sps_sync_interval" value="" placeholder="">
                            </td>
                        </tr>
                        
                        <tr>
                            <td></td>
                            <td>
                                <div style="display: flex;justify-content:flex-start;align-items:center;">
                                    <div style="display: flex;margin:0px 15px;">
                                        <input style="height:30px;background-color: #DCDAD1;border:none;" type="button" id="syncUsersButton" class='mo-ms-tab-content-button' value="Sync">
                                    </div>

                                    <div style="display: flex;margin:0px 6px;">
                                        <input style="height:30px;background-color: #DCDAD1;border:none;" type="button" id="resetButton" class='mo-ms-tab-content-button' value="Reset">
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        
    <div class="mo-ms-tab-content-tile" style="width:135%;padding: 1rem;background: #f4f4f4;border: 4px solid #A6DEE0;border-radius: 5px;margin-top:0px !important;padding-top:0px !important;">
                        <div class="mo-ms-tab-content-tile-content mo-sps-prem-info" style="position:relative;">
                                <span style="font-size: 18px;font-weight: 500;">4. Roles/Folders Restriction
                                    <sup style="font-size: 12px;color:red;font-weight:600;">
                                            [Available in <a target="_blank" href="https://plugins.miniorange.com/microsoft-sharepoint-wordpress-integration#pricing-cards" style="color:red;">Paid</a> Plugins]
                                    </sup>
                                </span>
                                <div class="mo-sps-prem-lock" style="top:2px;right:2px;position:absolute;">
                                <img class="filter-green"
                                 src="<?php echo esc_url(plugin_dir_url(__FILE__).'../images/lock.svg');?>">
                                <p class="mo-sps-prem-text">Available in <a target="_blank" href="https://plugins.miniorange.com/microsoft-sharepoint-wordpress-integration#pricing-cards" style="color:#ffeb00;;">Paid</a> plugins.</p>
				                </div>
                                <div id="basic_attr_access_desc" class="mo_sps_help_desc">
                                    <span>Map your WordPress Roles / BuddyPress Groups / Membership Levels to Sharepoint site URL of folders to restrict files and folders. 
                                    </span>
                                </div>
                        
                        <table class="mo-ms-tab-content-app-config-table">
                                <colgroup>
                                    <col span="1" style="width: 30%;">
                                    <col span="2" style="width: 50%;">
                                </colgroup>
                                <?php
                                    foreach($roles as $role_value => $role_name){
                                        $configured_role_value = empty($roles_configured)?'':$roles_configured[$role_value];
                                        ?>
                                            <tr>
                                                <td><span><?php echo esc_html($role_name); ?></span></td>
                                                <td>
                                                    <input disabled style="border:1px solid #eee;" value="Enter SharePoint Server Relative URL of Folders" type="text">
                                                </td>
                                            </tr>
                                        <?php
                                    }
                                ?>
                                <tr><td></br></td></tr>
                                <tr>
                                    <td>
                                        <input disabled style="background-color: #DCDAD1;border:none;width:100px;height:30px;" type="submit" class="mo-ms-tab-content-button" value="Save">
                                    </td>
                                </tr>
                        </table>
                        </div>
            </div>


    <div class="mo-ms-tab-content-tile" style="width:135%;padding: 1rem;background: #f4f4f4;border: 4px solid #A6DEE0;border-radius: 5px;margin-top:0px !important;padding-top:0px !important;">
    
                <div class="mo-ms-tab-content-tile-content mo-sps-prem-info" style="position:relative;">
                    <span style="font-size: 18px;font-weight: 500;">
                    5. Sync News And Articles
                    <sup style="font-size: 12px;color:red;font-weight:600;">
                                [Available in <a target="_blank" href="https://plugins.miniorange.com/microsoft-sharepoint-wordpress-integration#pricing-cards" style="color:red;">Paid</a> Plugins]       
                    </sup>
                    </span>
                    <div class="mo-sps-prem-lock" style="top:2px;right:2px;position:absolute;">
                                <img class="filter-green"
                                 src="<?php echo esc_url(plugin_dir_url(__FILE__).'../images/lock.svg');?>">
                                <p class="mo-sps-prem-text">Available in <a target="_blank" href="https://plugins.miniorange.com/microsoft-sharepoint-wordpress-integration#pricing-cards" style="color:#ffeb00;;">Paid</a> plugins.</p>
				    </div>
                    <div id="basic_attr_access_desc" class="mo_sps_help_desc">
                        <span>Sync All your SharePoint online news and articles into the wordpress posts.
                        </span>
                    </div>
                    <table class="mo-ms-tab-content-app-config-table">
                    <tr>
                            <td style="width:35%;word-break: break-all;"><span><h4>Enable to Sync SharePoint Social News</h4></span></td>
                            <td class="right-div">
                            <label class="switch">
                                <input type="checkbox" disabled>
                                <span class="slider round"></span>
                            </label>
                            </td>
                    </tr>
                    <tr>
                            <td style="width:35%;word-break: break-all;"><span><h4>Enable to Sync Sync SharePoint Social Articles</h4></span></td>
                            <td class="right-div">
                            <label class="switch">
                                <input type="checkbox" disabled>
                                <span class="slider round"></span>
                            </label>
                            </td>
                    </tr>
                    </table>
                </div>
    </div>
    <?php
    
    }

}