<?php

namespace MoSharePointObjectSync\View;

use MoSharePointObjectSync\Wrappers\pluginConstants;
use MoSharePointObjectSync\Wrappers\wpWrapper;

class setupGuide
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
        $app = wpWrapper::mo_sps_get_option(pluginConstants::APP_CONFIG);
        $tenant_name = ! isset($app['tenant_name'])? '' : $app['tenant_name'];
         ?>
    <table style="width:97%;">
     <tbody>
      <td style="vertical-align:top;width:77rem;">
      <div class="mo-epbr-setup-tab-content">
		<h1>
            How to configure the plugin
        </h1>
        <div class="mo-epbr-setup-tab-content-tile">
            <div class="mo-epbr-setup-tab-content-tile-content mo-epbr-guide-text">
              
              <h3>1. Plugin Configurations:</h3>
              <ol>
                <h2>Manual App Connection</h2>
                <ul>
                    <li><span style="color:red;">Note:&nbsp;</span><i>If you are configuring the plugin using <b>Manual App</b> connection, you can follow the step-by-step guide from <b><a target="_blank" href="https://plugins.miniorange.com/how-to-configure-azure-ad-application-for-wordpress">here</a></b>. Once you have connected successfully, go to <a href="#doc_preview_step"><b>Document Preview</b></a> step.</i></li>
                    
                </ul>

                <h2>Automatic App Connection</h2>
                <ul class="mo-epbr-guide-ul">
                <li>Navigate to the <b>SharePoint/OneDrive</b> plugin. </li>
                <li>Go to <b>connection</b> tab. </li>
                <li>Click on the <b>Connect to SharePoint</b> button.</li>
                <img width="95%" src="<?php echo esc_url(plugin_dir_url(__FILE__).'../images/auto_connection_step1.png');?>" loading="lazy" class="mo-epbr-guide-image" alt="Azure AD user sync with WordPress - Admin consent">
                <li>You will be prompted with Azure AD Login Page. Log in using your <b>Azure AD/SharePoint credentials</b>.</li>
                <img width="95%" src="<?php echo esc_url(plugin_dir_url(__FILE__).'../images/auto_connection_step2.png');?>" loading="lazy" class="mo-epbr-guide-image" alt="Azure AD user sync with WordPress - Admin consent">
                <li>Select <b>Consent on behalf of your organization</b> option and click on <b>Accept</b> button.</li>
                <img width="95%" src="<?php echo esc_url(plugin_dir_url(__FILE__).'../images/auto_connection_step3.png');?>" loading="lazy" class="mo-epbr-guide-image" alt="Azure AD user sync with WordPress - Admin consent">
                <li>If you are successfully connected, you can see similar window as shown in the below image</li>
                <img width="95%" src="<?php echo esc_url(plugin_dir_url(__FILE__).'../images/plugin_test_connection.png');?>" loading="lazy" class="mo-epbr-guide-image" alt="Azure AD user sync with WordPress - Admin consent">
                </ul>
              </ol>

              <h3 id="doc_preview_step">2. Document Preview:</h3>
              <ul class="mo-epbr-guide-ul">
                <li>Navigate to Document Preview Tab.</li>
                <li>Select the SharePoint Site.</li>
                <li>Select the Document library/drive from the given dropdown.</li>
                <li>You can see the document preview for selected site and drive as shown in the image below:</li>
                <img width="95%" src="<?php echo esc_url(plugin_dir_url(__FILE__).'../images/doc_preview_step.png');?>" loading="lazy" class="mo-epbr-guide-image" alt="Azure AD user sync with WordPress - Admin consent">
              </ul>

              <h3>3. Shortcode to embed document:</h3>

              <ul class="mo-epbr-guide-ul">
                <li>Navigate to Embed Option tab</li>
                <li>The following shortcode will help you to embed documents to your pages and posts</li>
                <img width="95%" src="<?php echo esc_url(plugin_dir_url(__FILE__).'../images/18_step.png');?>" loading="lazy" class="mo-epbr-guide-image" alt="Azure AD user sync with WordPress - Admin consent">
                <li>Copy and paste this shortcode into pages or posts</li>
                <img width="95%" src="<?php echo esc_url(plugin_dir_url(__FILE__).'../images/19_step.png');?>" loading="lazy" class="mo-epbr-guide-image" alt="Azure AD user sync with WordPress - Admin consent">
                <li>You can embed documents to your pages and posts using Gutenberg Block</li>
                <img width="95%" src="<?php echo esc_url(plugin_dir_url(__FILE__).'../images/gutenberg.png');?>" loading="lazy" class="mo-epbr-guide-image" alt="Azure AD user sync with WordPress - Admin consent">
                <li>Click On publish and view post to get all documents on your page/post.</li>
                <div style="margin-bottom:20px;"></div>
                <img width="95%" src="<?php echo esc_url(plugin_dir_url(__FILE__).'../images/short.png');?>" loading="lazy" class="mo-epbr-guide-image" alt="Azure AD user sync with WordPress - Admin consent">
                
              </ul>
                

            </ul>
        </div>
      </div>
      </td>
     </tbody>
   </table>
<?php
    }
}
