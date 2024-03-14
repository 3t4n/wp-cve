<?php
if (!defined('ABSPATH'))
    exit;

$siteurl = site_url();
$siteurl = esc_url($siteurl);
$config = get_option("zcf_crmformswpbuilder_settings");
if ($config == "") {
    $config_data = 'no';
} else {
    $config_data = 'yes';
}
require_once( ZCF_BASE_DIR_URI . "includes/crmoauthentication.php");
zcfcheckAccessToken();

if (sanitize_text_field(isset($_REQUEST['code']))) {
    $code = sanitize_text_field($_REQUEST['code']);
    $reposnseAuth = zcfgetAuthTokennew($code);
}
 $domainname = sanitize_text_field(get_option('zcrm_integ_domain_name'));
 $zcrm_integ_client_id = sanitize_text_field(get_option('zcrm_integ_client_id'));
?>
<input type="hidden" name="currentpageUrl" id="currentpageUrl" value=""/>

<div class="clearfix"></div>
<div class="">
    <div class="panel" style="width:99%;">
        <div class="panel-body">
            <input type="hidden" id="get_config" value="<?php echo sanitize_text_field($config_data) ?>" >
            <input type="hidden" id="revert_old_crm" value="crmformswpbuilder">

            <input type="hidden" id="site_url" name="site_url" value="<?php echo esc_attr($siteurl); ?>">

                <input type="hidden" name="crmforms-zoho-settings-form" value="crmforms-zoho-settings-form" />
                <input type="hidden" id="plug_URL" value="<?php echo esc_url(ZCF_PLUGIN_BASE_URL); ?>" />

                <div class="clearfix"></div>

                <div class="clearfix"></div>
                <div class="mt20">
                    <div class="form-group col-md-12">
                        <?php
                        global $wp;
                        $current_url = home_url(add_query_arg(array(), $wp->request));
                        $current_url = $current_url . '/wp-admin/admin.php?page=crmforms-builder';
                        require_once( ZCF_BASE_DIR_URI . "includes/crmoauthentication.php");
                        zcfcheckAccessToken();
                        $SettingsConfig = get_option("zcf_crmformswpbuilder_settings");
                        $authtokens = $SettingsConfig['authtoken'];
                                    if (sanitize_text_field(isset($_POST['zcrm_integ_submit'])) && !empty($_POST)) {
                                      $nonce = sanitize_text_field($_REQUEST['_wpnonce']);
                                      if (!wp_verify_nonce($nonce, 'zcrm_integ_settings_nonce')) {
                                            echo '<div class="error"><p><strong>'.esc_html__('Reload the page again').'</strong></p></div>'."\n";
                                        }
                                        else {

                                        $zcrm_integ_client_id         = sanitize_text_field($_POST['zcrm_integ_client_id']);
                                        $zcrm_integ_client_secret     = sanitize_text_field($_POST['zcrm_integ_client_secret']);
                                        $zcrm_integ_domain_name       = sanitize_text_field($_POST['zcrm_integ_domain_name']);
                                        $zcrm_integ_authorization_uri = sanitize_text_field($_POST['zcrm_integ_authorization_uri']);
                                        update_option('zcrm_integ_client_id',$zcrm_integ_client_id);
                                        update_option('zcrm_integ_client_secret',$zcrm_integ_client_secret);
                                        update_option('zcrm_integ_domain_name',$zcrm_integ_domain_name);
                                        update_option('zcrm_integ_authorization_uri',$zcrm_integ_authorization_uri);
                                        echo '<div class="updated"><p><strong>'.esc_html__('Settings saved.').'</strong></p></div>'."\n";
                                         ?>

                                         <?php

                                    }
                                  }




                            ?>
                            <span class="f14 mb20 dB"><b>Zoho CRM Form Builder</b></span>
                            <span class="f14 mb20 dB">The form builder allows you to create forms in your wordpress and push the data into your Zoho CRM. Also, you can map the third party forms with Zoho CRM</span>
                            <span class="f14 mb20 dB">You must authenticate Zoho CRM before you start building.</span>


                            <form method="post" action="<?php echo esc_url($_SERVER["REQUEST_URI"]); ?>">
                             <?php wp_nonce_field('zcrm_integ_settings_nonce'); ?>
                            <div class="page">
                        <div  >

            <div class="form">
                <div class="form__row">
                    <label class="form--label">Domain<?php echo $domainname;?></label>
                    
                    <select onchange='authToken()' id='zcrm_integ_domain_name' class="form--input form--input--select" name="zcrm_integ_domain_name" onchange='selectaccount(this)'>
                        <option value="com" <?php if($domainname == 'com'){ echo esc_html__("selected");} ?> >zoho.com</option>
                        <option value="eu" <?php if($domainname == 'eu'){ echo esc_html__("selected");} ?>>zoho.eu</option>
                          <option value="in" <?php if($domainname == 'in'){ echo esc_html__("selected");} ?>>zoho.in</option>
                            <option value="jp" <?php if($domainname == 'jp'){ echo esc_html__("selected");} ?>>zoho.jp</option>
                        <option value="com.au" <?php if($domainname == 'com.au'){ echo esc_html__("selected");} ?>>zoho.com.au</option>
                    </select>

                    <i class="form__row-info">The name of the region the account is configured</i> </div>
                      <?php   $clientsecret =  get_option('zcrm_integ_client_secret');
                              $clientid =  get_option('zcrm_integ_client_id');
                      ?>
                        <div class="form__row">
                                <label class="form--label">Client Id</label>
                                <input type="text" value="<?php echo sanitize_text_field($clientid); ?>" name="zcrm_integ_client_id" class="form--input" id="zcrm_integ_client_id" required onchange='authToken()'/>

                                  <span id='zohocomaccount'><a  href="https://accounts.zoho.com/developerconsole">How to create client id and Secret key</a> </span>
                                  <span id='zohoinaccount' class='dN'><a  href="https://accounts.zoho.in/developerconsole">How to create client id and Secret key</a> </span>
                                  <span id='zohocomjpaccount' class='dN'><a  href="https://accounts.zoho.jp/developerconsole">How to create client id and Secret key</a> </span>
                                  <span id='zohocomauaccount' class='dN'><a  href="https://accounts.zoho.com.au/developerconsole">How to create client id and Secret key</a> </span>
                                  <span  id='zohoeuaccount' class='dN'> <a href="https://accounts.zoho.eu/developerconsole">How to create client id and Secret key</a> </span> </div>

                            <div class="form__row">
                                <label class="form--label">Client Secret</label>
                                <input type="text" value="<?php echo sanitize_text_field($clientsecret); ?>" name="zcrm_integ_client_secret" class="form--input" id="zcrm_integ_client_secret"  required onchange='authToken()'> <i class="form__row-info">Created in the developer console</i> </div>
                            <div class="form__row">
                                <input type="hidden"  readonly="readonly" id='stateurl' name="state" class="form--input" value="<?php echo esc_url(admin_url().'admin.php?page=crmforms-builder'); ?>" class="regular-text" readonly="readonly" required onchange='authToken()'/>

                                <label class="form--label">Authorization Redirect URI</label>
                                <?php $redUrl = "https://extensions.zoho.".$domainname."/plugin/wordpress/callback";?>
                                <input type="text" id="zcrm_integ_authorization_uri" readonly="readonly" name="zcrm_integ_authorization_uri" class="form--input zcrm_integ_authorization_uri_us" value="<?php echo $redUrl;?>" class="regular-text" readonly="readonly" required/>


                            <div class="form__row form__row-btn">
                            <?php 
                              $stateUrl = esc_url(admin_url().'admin.php?page=crmforms-builder');
                              $url = "https://accounts.zoho.".$domainname."/oauth/v2/auth?response_type=code&client_id=".$zcrm_integ_client_id."&scope=ZohoCRM.modules.ALL,ZohoCRM.settings.ALL,ZohoCRM.users.ALL&redirect_uri=".$redUrl."&access_type=offline&prompt=consent&state=".$stateUrl;
                            if($domainname != ''){ ?>
                              <a   href='<?php echo esc_url($url);?>' id='zcrm_integ_submit' class='primarybtn'>Authenticate Zoho CRM</a>
                            <?php } else{ ?>
                                 <a  disabled href='javscript:void(0)' id='zcrm_integ_submit' class='primarybtn'>Authenticate Zoho CRM</a>
                            <?php }?>
                              </div>
                              </div>
                            </div>
                           </div>
                         </form>






                    </div>
                </div>

            <div id="loading-sync" style="display: none;"></div>
            <div id="loading-image" style="display: none;"></div>
        </div>
    </div>

</div>
<?php
require_once( ZCF_BASE_DIR_URI . "includes/crmoauthentication.php");
zcfcheckAccessToken();
$SettingsConfig = get_option("zcf_crmformswpbuilder_settings");
$authtokens = $SettingsConfig['authtoken'];
if ($authtokens != '' && sanitize_text_field(isset($_REQUEST['code']))) {
    ?>
    <script>
        jQuery(window).load(function () {
            saveConfig('save', "<php echo esc_url($current_url)?>");
            window.opener.location.reload();
            self.close();
        });


    </script>
<?php } ?>
<div class="freezelayer"></div>
