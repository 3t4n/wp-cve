<?php

namespace MoSharePointObjectSync\View;

use MoSharePointObjectSync\Wrappers\pluginConstants;
use MoSharePointObjectSync\Wrappers\wpWrapper;
use MoSharePointObjectSync\API\Azure;

class appConfig
{

    private static $instance;

    public static function getView()
    {
        if (!isset(self::$instance)) {
            $class = __CLASS__;
            self::$instance = new $class;
        }
        return self::$instance;
    }

    public function mo_sps_display__tab_details()
    {
?>
        <div class="mo-ms-tab-content" style="width:77rem;">
            <div class="mo-ms-tab-app-config-connection-main-div">
                <div class="mo-ms-tab-app-config-connection-sub-div">
                    <span style="font-size:20px;font-weight:700;"> Manage Accounts </span>
                    <span style="padding:15px 10px 15px 0px;color:gray;">Manage your SharePoint/Onedrive Accounts</span>
                </div>
                <div style="margin-left:auto;position: relative;">
                    <input disabled style="height:30px;border-radius:8px;margin: 0px 40px 10px 0px;" type="submit" id="AddAccountButton" class="mo-ms-tab-content-button" onmouseover="showTooltip()" onmouseout="hideTooltip()" value="+ Add New" data-tooltip="tooltip">
                    <div class="mo-sps-add-new-tooltip" id="tooltip" style="display:none;">
                        <p style="text-align:center;font-size:15px;font-weight:bold;margin-bottom:-7px;">Restricted Content </p>
                        <p style="text-align:center;">You are currently on the free version of the plugin, please upgrade to add more than one connections. </p>
                    </div>
                </div>
            </div>
            <?php
            $this->mo_sps_create__client_config_form();
            ?>
            <script>
                function showTooltip() {
                    document.getElementById('tooltip').style.display = 'block';
                }

                function hideTooltip() {
                    document.getElementById('tooltip').style.display = 'none';
                }
            </script>
        </div>
    <?php
    }

    private function mo_sps_create__client_config_form()
    { ?>
        <div style="width: 68%">
            <div class="mo-ms-tab-content-left-border">
                <?php
                $option = get_option(('mo_sps_test_connection_status'));
                if ("success" == get_option("mo_sps_test_connection_status")) {
                    $response = get_option('mo_sps_test_connection_user_details');
                    $this->mo_sps_display_user_info($response);
                } else {
                    $this->mo_sps_display_app_connection();
                }
                ?>
            </div>
        </div>

        <div class="mo-ms-tab-content-left-border" style="display:flex;align-items:center;margin-bottom: 15px;">
            <div style="height:0;margin-left:1.2rem; border: 1px solid #ccc;width:46.5%;border-top: none;"></div>
            <?php if ("success" != get_option("mo_sps_test_connection_status")) { ?>
                &nbsp;<h2>OR</h2>&nbsp;
            <?php } ?>
            <div style="height:0;border: 1px solid #ccc;width:46.5%;border-top: none;"></div>
        </div>

        <form class="mo_sps_ajax_submit_form" action="" method="post">
            <input type="hidden" name="option" id="app_config" value="mo_sps_azure_config_option">
            <input type="hidden" name="mo_sps_tab" value="app_config">
            <?php wp_nonce_field('mo_sps_azure_config_option'); ?>
            <div style="width: 68%">
                <div class="mo-ms-tab-content-left-border">
                    <?php
                    if ("success" != get_option("mo_sps_test_connection_status")) {
                        $this->mo_sps_display__azure_config();
                    }

                    ?>
                </div>
            </div>
        </form>
        <script>
            function showAttributeWindow(type) {
                document.getElementById("app_config").value = "mo_sps_app_test_config_option";
                var myWindow = window.open("<?php echo esc_url_raw($this->mo_sps_get_test_url()); ?>" + "&type=" + type, "TEST User Attributes", "scrollbars=1, width=800, height=600");
            }
        </script>
    <?php }

    private function mo_sps_display_app_connection()
    {
        $refresh_token = wpWrapper::mo_sps_get_option(pluginConstants::SPS_RFTK);
        $reconnect = $refresh_token ? true : false;
        $connector = isset($app['connector']) && !empty($app['connector']) ? $app['connector'] : '';
        $selected_connector = isset($connector) && $connector ? $connector : '';
        $mo_app_config_js_url = plugins_url('../includes/js/appConfig.js', __FILE__);
        wp_enqueue_script('mo_sps_app_config_js', $mo_app_config_js_url, array('jquery'));

        wp_localize_script('mo_sps_app_config_js', 'appConfig', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'admin_url' => admin_url('admin.php'),
            'nonce' =>  wp_create_nonce('mo_sps_app_config__nonce'),
            'test_url' => $this->mo_sps_get_test_url(),
            'add_new' => esc_url(plugin_dir_url(__FILE__) . '../images/add-new.svg'),
        ]);

    ?>
        <div class="mo-ms-tab-content-tile" style="width:135%;">
            <div class="mo-ms-tab-content-tile-content">
                <div style="display: inline">
                    <span style="font-size: 18px;font-weight: 650;display: inline-block">Automatic Connection &nbsp;&nbsp;</span>
                </div>
                <div id="basic_attr_access_desc" class="mo_sps_help_desc" style="margin-bottom:20px;font-weight:500;">
                    <span>
                        You can Connect your Wordpress site with SharePoint and Onedrive business through pre integrated application.
                    </span>
                </div>
                <div class="mo_sps_auto_connection_container">
                    <span class="mo_sps_auto_connection" id="mo_sps_auto_connection_span" style="position:relative">
                        <button id="mo_sps_auto_connection_connect" class="mo_sps_auto_connection_connect" type="button">
                            <div>Connect</div>
                        </button>
                        <div style="font-size: 15px;position: relative;" id="mo_sps_auto_connection_select_container">
                            <button class="mo_sps_auto_connection_select" id="mo_sps_auto_connection_select" data-test="dropdown-trigger" type="button">
                                <img id="mo_sps_auto_connection_arrow_down" class="mo_sps_auto_connection_arrow_down" src="<?php echo esc_url(plugin_dir_url(__FILE__) . '../images/down-arrow.svg'); ?>">
                                <img id="mo_sps_auto_connection_arrow_up" class="mo_sps_auto_connection_arrow_up" src="<?php echo esc_url(plugin_dir_url(__FILE__) . '../images/down-arrow.svg'); ?>">
                            </button>
                        </div>
                        <div class="mo_sps_auto_connection_select_drpdn" id="mo_sps_auto_connection_select_drpdn">
                            <ul class="mo_sps_auto_connection_select_ul">
                                <li id="mo_sps_auto_connection_type_sharepoint" data-type="sharepoint" class="mo_sps_auto_connection_select_li <?php echo ($selected_connector == 'sharepoint' ? 'mo_sps_auto_connection_selected_li' : ''); ?>">Sharepoint</li>
                                <li id="mo_sps_auto_connection_type_onedrive" data-type="onedrive" class="mo_sps_auto_connection_select_li <?php echo ($selected_connector == 'onedrive' ? 'mo_sps_auto_connection_selected_li' : ''); ?>">Onedrive Business</li>
                                <li id="mo_sps_auto_connection_type_personal" data-type="personal" class="mo_sps_auto_connection_select_li <?php echo ($selected_connector == 'personal' ? 'mo_sps_auto_connection_selected_li' : ''); ?>">Onedrive Personal</li>
                            </ul>
                        </div>
                    </span>
                </div>
            </div>
        </div>
    <?php
    }

    private function mo_sps_display__azure_config()
    {
        $app = wpWrapper::mo_sps_get_option(pluginConstants::APP_CONFIG);
        $client_id = !empty($app['client_id']) ? $app['client_id'] : '';
        $tenant_id = !empty($app['tenant_id']) ? $app['tenant_id'] : '';

        if (isset($app['client_secret']) && !empty($app['client_secret'])) {
            $client_secret = wpWrapper::mo_sps_decrypt_data($app['client_secret'], hash("sha256", $client_id));
        } else {
            $client_secret = '';
        }

        $disabled = ($client_id != '' && $client_secret != '' && $tenant_id != '') ? '' : 'disabled';
    ?>

        <div class="mo-ms-tab-content-tile" style="width:135%;">
            <div class="mo-ms-tab-content-tile-content">
                <div style="display: inline">
                    <span style="font-size: 18px;font-weight: 650;display: inline-block"> Manual Configuration &nbsp;&nbsp;</span>
                </div>
                <div id="basic_attr_access_desc" class="mo_sps_help_desc" style="margin-bottom:20px;font-weight:500;">
                    <span>In order to sync the documents from sharepoint, first you'll need to create an Azure AD application. This application will allow us to securely communicate with Sharepoint Content.
                    </span>
                </div>
                <table class="mo-ms-tab-content-app-config-table">
                    <tr>
                        <td class="left-div"><span>Application ID <span style="color:red;font-weight:bold;">*</span></span></td>
                        <td class="right-div"><input placeholder="Enter Your Application (Client) ID" style="width:75%;" type="text" name="client_id" value="<?php echo esc_html($client_id); ?>"></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>
                            <b>Note:</b> You can find the <b>Application ID</b> in your Active Directory application's Overview tab.
                        </td>
                    </tr>
                    <tr>
                        <td></br></td>
                    </tr>
                    <tr>
                        <td class="left-div"><span>Client Secrets <span style="color:red;font-weight:bold;">*</span></span></td>
                        <td class="right-div"><input autoComplete="new-password" placeholder="Enter Your Client Secret" style="width:75%;" type="password" name="client_secret" value="<?php echo esc_html($client_secret); ?>"></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>
                            <b>Note:</b> You can find the <b>Client Secret</b> value in your Active Directory application's Certificates & Secrets tab.
                        </td>
                    </tr>
                    <tr>
                        <td></br></td>
                    </tr>

                    <tr>
                        <td class="left-div"><span>Tenant ID <span style="color:red;font-weight:bold;">*</span></span></td>
                        <td class="right-div"><input placeholder="Enter Your Directory (Tenant) ID" style="width:75%;" type="text" name="tenant_id" value="<?php echo esc_html($tenant_id); ?>"></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>
                            <b>Note:</b> You can find the <b>Tenant ID</b> in your Active Directory application's Overview tab.
                        </td>
                    </tr>
                    <tr>
                        <td></br></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>
                            <div style="display: flex;justify-content:flex-start;align-items:center;">
                                <div style="display: flex;margin:1px;">
                                    <input style="height:30px;" type="submit" id="saveAzureButton" class="mo-ms-tab-content-button" value="Save">
                                </div>
                                <div style="margin:10px;">
                                    <input <?php echo $disabled; ?> style="height:30px;" id="mo_sps_auto_connection_type_personal" data-type="sharepoint" type="button" class="mo-ms-tab-content-button" value="Test Configuration">
                                </div>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    <?php
    }

    private function bytesToGB($bytes)
    {
        if ($bytes < 1024) {
            return number_format($bytes, 2) . ' B';
        } elseif ($bytes < 1024 * 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        } elseif ($bytes < 1024 * 1024 * 1024) {
            return number_format($bytes / 1024 / 1024, 2) . ' MB';
        } else {
            return number_format($bytes / 1024 / 1024 / 1024, 2) . ' GB';
        }
    }

    private function mo_sps_display_user_info($response)
    {
        $config = wpWrapper::mo_sps_get_option(pluginConstants::APP_CONFIG);
        $app_type = isset($config) && isset($config['app_type']) ? $config['app_type'] : 'manual';
        $connector = isset($config) && isset($config['connector']) ? $config['connector'] : 'sharepoint';
        $user_details = [];
        if($app_type == 'auto') {
            $user_details['user_detail'] = 'Email ID';
            $user_details['connection_type'] = 'Automatic';
            $user_details['connection_status'] = 'Active';

            switch($connector) {
                case 'personal': $owner = isset($response['owner']) ? $response['owner'] : [];
                    $user = isset($owner['user']) ? $owner['user'] : [];
                    $user_details['user_name'] = isset($user['displayName']) ? $user['displayName'] : "";
                    $user_details['user_email'] = isset($config['email']) ? $config['email'] : '';
                    $quota = isset($response['quota']) ? $response['quota'] : [];
                    $total_storage = isset($quota['total']) ? $this->bytesToGB($quota['total']) : '-';
                    $used_storage = isset($quota['used']) ? $this->bytesToGB($quota['used']) : '-';
                    $storage = $used_storage . " / " . $total_storage;
                    break;
                case 'onedrive': $response = isset($response[0]) && !empty($response[0]) ? $response[0] : [];
                    $owner = isset($response['owner']) ? $response['owner'] : [];
                    $user = isset($owner['user']) ? $owner['user'] : [];
                    $user_details['user_name'] = isset($user['displayName']) ? $user['displayName'] : "";
                    $user_details['user_email'] = isset($user['email']) ? $user['email'] : '';
        
                    $quota = isset($response['quota']) ? $response['quota'] : [];
                    $total_storage = isset($quota['total']) ? $this->bytesToGB($quota['total']) : '-';
                    $used_storage = isset($quota['used']) ? $this->bytesToGB($quota['used']) : '-';
                    $storage = $used_storage . " / " . $total_storage;
                    break;
                case 'sharepoint': 
                    $apiHandler = Azure::getClient($config);
                    if(!isset($config['email']) || !isset($config['name'])) {
                        $user = $apiHandler->mo_sps_get_my_user();
                        if($user['status']) {
                            $config['name'] = isset($user['data']['displayName']) ? $user['data']['displayName'] : '';
                            $config['upn'] = isset($user['data']['userPrincipalName']) ? $user['data']['userPrincipalName'] : '';
                            $config['email'] = isset($user['data']['mail']) ? $user['data']['mail'] : '';
                        }
                        wpWrapper::mo_sps_set_option(pluginConstants::APP_CONFIG, $config);
                    }

                    $user_details['user_email'] = isset($config['email']) ? $config['email'] : '';
                    $user_details['user_name'] = isset($config['name']) ? $config['name'] : '';
                    $sites = wpWrapper::mo_sps_get_option(pluginConstants::SPS_SITES);
                    if($sites && !empty($sites)) {
                        $storage = 'No. of sites : '.sizeof($sites);
                    }
                    break;
            }

        } else {
            $user_details['user_detail'] = "Application ID :";
            $user_details['connection_type'] = "Manual";
            $user_details['connection_status'] = "Active";

            $config = wpWrapper::mo_sps_get_option(pluginConstants::APP_CONFIG);
            $client = Azure::getClient($config);
            $access_token = $client->mo_sps_send_access_token();
            $access_token = $access_token && isset(explode('.',$access_token)[1]) ? explode('.', $access_token)[1] : '';
            $jwt_object = $access_token != '' ? json_decode(base64_decode(str_replace('_', '/', str_replace('-', '+', $access_token))), true) : [];
            
            $user_details['user_name'] = isset($jwt_object['app_displayname']) ? $jwt_object['app_displayname'] : '';
            $user_details['user_email'] = isset($jwt_object['appid']) ? $jwt_object['appid'] : '';
            $sites = wpWrapper::mo_sps_get_option(pluginConstants::SPS_SITES);
            if($sites && !empty($sites)) {
                $storage = 'No. of sites : '.sizeof($sites);
            }
        }

    ?>
        <form class="mo_sps_ajax_submit_form" action="" method="post">
            <input type="hidden" name="option" id="app_config" value="mo_sps_remove_configured_account">
            <input type="hidden" name="mo_sps_tab" value="app_config">
            <?php wp_nonce_field('mo_sps_azure_config_option'); ?>
            <div class="mo-ms-tab-content-tile" style="width:138%; padding: 10px; border: 1px solid #ddd;">
                <div class="mo-ms-tab-content-tile-content">
                    <div style="display:flex;padding: 0px 0px 15px 5px;margin-left: -45px;">
                        <div class="mo-ms-display-user-info-div1">
                            <img style="width:50px;height:50px;margin-left:10px;" src="<?php echo esc_url(plugin_dir_url(__FILE__) . '../images/microsoft-sharepoint.svg'); ?>">
                            <div class="mo-ms-display-user-info-div1-innerdiv">
                                <div class="circle"></div>
                                <?php echo $user_details['connection_status']; ?>
                            </div>
                        </div>
                        <div class="mo-ms-display-user-info-div2">
                            <p><?php echo $user_details['user_name'] ?></p>
                            <span style="color:gray;margin-bottom: 5px;"><span class="dashicons dashicons-email"></span> <?php echo $user_details['user_detail']; ?> <?php echo $user_details['user_email']; ?></span>
                            <div class="mo-ms-display-user-info-div2-innerdiv1">
                                <div style="width:30%;"><span class="dashicons dashicons-database"></span> <?php echo $storage; ?> </div>
                                <div><span class="dashicons dashicons-share"></span> <?php echo $user_details['connection_type']; ?> </div>
                            </div>
                        </div>
                        <div style="border-left: thin solid #8080806e;"></div>
                        <div class="mo-ms-display-user-info-div3">
                            <p>Actions</p>
                            <div style="display:flex;flex-direction:row;">
                                <!-- <div><button style="border:none;background:transparent;"><span class="dashicons dashicons-edit"></span></button></div> -->
                                <div><button style="border:none;background:transparent;cursor:pointer;" type="submit" id="RemoveAccountButton" value="Remove Account"><span class="dashicons dashicons-trash"></span></button></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
<?php
    }

    private function mo_sps_get_test_url()
    {
        return admin_url('?option=testSPSApp');
    }

    function getSharePointPermissionPageURL($tenant_name)
    {
        return "https://" . $tenant_name . "-admin.sharepoint.com/_layouts/15/appinv.aspx";
    }
}
