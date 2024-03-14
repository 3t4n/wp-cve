<?php

namespace WcMipConnector\View\OAuth;

defined('ABSPATH') || exit;

use WcMipConnector\Controller\OAuthController;
use WcMipConnector\Enum\MipWcConnector;
use WcMipConnector\Model\ShopData;
use WcMipConnector\View\Assets\Assets;

class LoginForm
{
    /** @var Assets  */
    protected $assets;

    public function __construct()
    {
        $this->assets = new Assets();
    }

    /**
     * @param ShopData $shopData
     */
    public function getLoginForm(ShopData $shopData): void
    {
        ?>
        <!DOCTYPE html>
            <html <?php language_attributes(); ?>>
                <head>
                    <meta name="viewport" content="width=device-width" />
                    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
                    <meta name="robots" content="noindex, nofollow" />
                    <title>Multi-Channel Integration Platform</title>
                    <?php wp_head(); ?>
                    <?php wp_admin_css( 'install', true ); ?>
                </head>
                <body class="wc-auth wp-core-ui">
                <h1 id="wc-logo"><img src="<?php echo esc_url($this->assets->getImageAsset('logo.png'));?>" alt="" /></h1>
                <div class="wc-auth-content">
                    <h1>Multi-Channel Integration Platform <?php esc_html_e( 'would like to connect to your store','WC-Mipconnector');?></h1>
                    <p><?php esc_html_e('To synchronise your store with the BigBuy Multi-channel Integration Platform, you need to be logged in WordPress account on the form below ', 'WC-Mipconnector');?></p>

                    <form method="post" class="wc-auth-login">
                        <p class="form-row form-row-wide">
                            <label for="username"><?php esc_html_e('Username or email', 'WC-Mipconnector'); ?>&nbsp;<span class="required">*</span></label>
                            <input type="text" class="input-text" name="username" id="username" value="<?php echo (!empty($_POST['username'])) ? esc_html($_POST['username']) : ''; ?>" />
                        </p>
                        <p class="form-row form-row-wide">
                            <label for="password"><?php esc_html_e('Password', 'WC-Mipconnector'); ?>&nbsp;<span class="required">*</span></label>
                            <input class="input-text" type="password" name="password" id="password" />
                        </p>
                        <?php
                        if (get_option('woocommerce_version') < MipWcConnector::WC_VERSION) {
                            ?>
                            <div class="wc-auth-incompatible-warning fa-exclamation-triangle">
                                <p><?php esc_html_e('Incompatible with your version of Woocommerce. Please update the plugin.', 'WC-Mipconnector');?></p>
                            </div>
                            <?php
                        }
                        ?>

                        <p class="wc-auth-actions">
                            <?php wp_nonce_field( 'woocommerce-login', 'woocommerce-login-nonce' ); ?>
                            <button type="submit"
                                <?php
                                if (get_option('woocommerce_version') < MipWcConnector::WC_VERSION) {
                                    echo esc_attr('disabled');
                                }
                                ?>
                                    class="button button-large button-primary wc-auth-login-button" name="login" value="<?php esc_attr_e('Login', 'WC-Mipconnector'); ?>"><?php esc_html_e( 'Login', 'WC-Mipconnector'); ?></button>
                            <input type="hidden" name="redirect" value="<?php echo esc_url(get_site_url().OAuthController::AUTHORIZE_ENDPOINT.'?shop='.esc_html($shopData->RawData)); ?>" />
                        </p>
                    </form>
                </div>
                </body>
            </html>
        <?php
    }
}