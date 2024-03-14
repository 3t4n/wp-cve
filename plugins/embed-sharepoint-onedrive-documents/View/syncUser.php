<?php

namespace MoSharePointObjectSync\View;

use MoSharePointObjectSync\Wrappers\pluginConstants;
use MoSharePointObjectSync\Wrappers\wpWrapper;

class syncUser{

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
            <h1>Sharepoint User Profile Detail</h1>
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
            <div class="mo-ms-tab-content-tile mo-sps-prem-info" style="width:135%;padding: 1rem;background: #f4f4f4;border: 4px solid #A6DEE0;border-radius: 5px;margin-top:0px !important;padding-top:0px !important;">
                <div class="mo-ms-tab-content-tile-content" style="position:relative;">
                <!-- <div class="mo-ms-tab-content-tile-content"> -->
                    <span style="font-size: 18px;font-weight: 200;">
                        Manual provisioning
                        
                    </span>
                    <div class="mo-sps-prem-lock" style="top:2px;right:2px;position:absolute;">
                        <img class="filter-green" src="<?php echo esc_url(plugin_dir_url(__FILE__).'../images/lock.svg');?>">
                        <p class="mo-sps-prem-text">Available in <a target="_blank" href="https://plugins.miniorange.com/microsoft-sharepoint-wordpress-integration#pricing-cards" style="color:#ffeb00;;">Paid</a> plugins.</p>
				    </div>
                    <div  class="mo_sps_help_desc">
						<span>
                        It provides the feature to Fetch Attributes and to sync individual users.
                        </span>
                    </div>
                    <table class="mo-ms-tab-content-app-config-table">
                        <colgroup>
                            <col span="1" style="width: 40%;">
                            <col span="2" style="width: 10%;">
                        </colgroup>
                        <tbody>
                            <tr>
                                <td><span style="font-size: 15px;font-weight: 200;"> Sync an individual user</span></td>
                            </tr>
                            <tr>
                                <td>
                                    <input disabled placeholder="Enter UserPrincipalName of User To Sync" type="text" name="upn_id">
                                </td>
                                <td style="text-align:center;">
                                    <input disabled style="height:30px;background-color: #DCDAD1;border:none;" type="submit" id="saveButton" class="mo-ms-tab-content-button" value="Save">
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <b>Note:</b> You can find the <b>User Principle Name</b> of user in the user profile in Users tab in your SharePoint Online. 
                                </td>
                                <td></td>
                            </tr>

                            <tr>
                                <td></br></td>
                            </tr>
                            <tr>
                                <td><span style="font-size: 15px;font-weight: 200;"> Fetch Attribute</span></td>
                                
                            </tr>
                            
                           
                            <tr>
                                <td>
                                <b>Note:</b>(Optional)You can fetch the attributes related to the user principal name entered, saved on the Sharepoint</td>
                                <td></td>
                            </tr>
                            <tr>
                                
                                <td><input disabled style="height:30px;background-color: #DCDAD1;border:none;" id="view_attributes" type="button" class="mo-ms-tab-content-button" value="Fetch Attributes" onclick="showAttributeWindow()"></td>
                            </tr>
                            <tr><td></br></td></tr>
                            
                            
                        </tbody>
                    </table>

                    <?php $this->mo_sps_display_profile_mapping(); ?>

                    <table class="mo-ms-tab-content-app-config-table">
                        <colgroup>
                            <col span="1" style="width: 40%;">
                            <col span="2" style="width: 10%;">
                        </colgroup>
                        <tbody>
                        <tr><td></br></td></tr>
                            <tr>
                                <td><span style="font-size: 15px;font-weight: 200;"> Sync User</span></td>
                            </tr>
                            <tr>
                                <td>
                                    <b>Note:</b> This will sync this user from your SharePoint online to WordPress.
                                </td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>
                                <input disabled style="height:30px;background-color: #DCDAD1;border:none;" type="button" id="syncUserManualButton" class="mo-ms-tab-content-button" value="Sync User">
                                </td>
                            </tr>


                            <tr><td></br></td></tr>
                            <tr>
                                <td><span style="font-size: 15px;font-weight: 200;"> Sync All Users
                                    </span></span>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <b>Note:</b> This will sync all users from your SharePoint online to WordPress.
                                </td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>
                                <input disabled style="height:30px;background-color: #DCDAD1;border:none;" type="button" id="syncUserManualButton" class="mo-ms-tab-content-button" value="Sync All Users">
                                </td>
                            </tr>
                        </tbody>
                    </table>    
                </div>
            </div>
    <?php
    }




    public function mo_sps_display_profile_mapping(){
        ?>
                <div class="mo-ms-tab-content-tile-content">
                    <span style="font-size: 15px;font-weight: 200;">User Profile Mapping
                        <sup style="font-size: 12px;color:red;font-weight:600;">
                        </sup>
                    </span>
                    <div id="basic_attr_access_desc" class="mo_sps_help_desc">
						<span>Map attributes like Username, Email, First Name, Last Name, Display Name to the attributes released from your SharePoint Online.
                         While syncing the users in your WordPress site, these attributes will automatically get mapped to your WordPress user details.
                         </span>
                    </div>
                </div>
                <table class="mo-ms-tab-content-app-config-table">
                        <tr>
                            <td colspan="2">
                            </td>
                        </tr>
                        <tr>
                            <td class="left-div"><span>Username</span></td>
                            <td class="right-div">
                                <input disabled style="width:75%;" placeholder="Enter attribute name for Username" type="text" name="user_login">
                            </td>
                        </tr>
                        <tr>
                            <td class="left-div"><span>Email</span></td>
                            <td class="right-div">
                                <input disabled style="width:75%;" placeholder="Enter attribute name for Email" type="text" name="email">
                            </td>
                        </tr>
                        <tr>
                            <td class="left-div"><span>First Name</span></td>
                            <td class="right-div">
                                <input disabled style="width:75%;" placeholder="Enter attribute name for First Name" type="text" name="first_name">
                            </td>
                        </tr>
                        <tr>
                            <td class="left-div"><span>Last Name</span></td>
                            <td class="right-div">
                                <input disabled style="width:75%;" placeholder="Enter attribute name for Last Name" type="text" name="last_name">
                            </td>
                        </tr>
                        <tr>
                            <td class="left-div"><span>Display Name</span></td>
                            <td class="right-div">
                                <input disabled style="width:75%;" placeholder="Enter attribute name for Display Name" type="text" name="display_name">
                            </td>
                        </tr>
                        <tr>
                            <td></td>
                        </tr>
                        <tr>
                            <td>
                                <input disabled style="height:30px;background-color: #DCDAD1;border:none;" type="submit" class="mo-ms-tab-content-button" value="SAVE">
                                <div class="loader-placeholder"></div>
                            </td>
                        </tr>
                </table>            
        <?php
    }

    

    private function mo_sps_get_test_url(){
        return admin_url('?option=testSPSUser');
    }
}