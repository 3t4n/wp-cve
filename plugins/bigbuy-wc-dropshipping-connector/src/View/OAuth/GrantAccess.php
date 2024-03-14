<?php

namespace WcMipConnector\View\OAuth;

defined('ABSPATH') || exit;

use WcMipConnector\Controller\OAuthController;
use WcMipConnector\Enum\MipWcConnector;
use WcMipConnector\Model\ShopData;
use WcMipConnector\View\Assets\Assets;

class GrantAccess
{
    /** @var Assets  */
    protected $assets;

    /**
     * GrantAccess constructor.
     */
    public function __construct()
    {
        $this->assets = new Assets();
    }

    /**
     * @param ShopData $shopData
     */
    public function getGrantAccessForm(ShopData $shopData): void
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
                    <div id="wpwrap" class="wc-auth-content">
                        <h1 id="wc-logo"><img src="<?php echo esc_url($this->assets->getImageAsset('logo.png'));?>" alt="" /></h1>
                        <div>
                            <h1>Multi-Channel Integration Platform <?php esc_html_e( 'requests permission to connect to your store','WC-Mipconnector');?></h1>
                        <p>
                            <?php
                            esc_html_e('Click on the "Approve" button to accept the permissions and carry out the synchronisation.','WC-Mipconnector');
                            ?>
                        </p>

                        <div class="wc-auth-logged-in-as">
                            <p>
                                <?php
                                $currentUser = wp_get_current_user();
                                /* Translators: %s display name. */
                                printf(esc_html__( 'Logged in as %s', 'WC-Mipconnector'), esc_html($currentUser->nickname));
                                ?>
                                <a href="<?php echo wp_logout_url(esc_url($shopData->CallbackUrl)); ?>" class="wc-auth-logout"><?php esc_html_e( 'Logout', 'WC-Mipconnector'); ?></a>
                        </div>
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
                            <a href="
                            <?php
                                if (get_option('woocommerce_version') >= MipWcConnector::WC_VERSION) {
                                    echo esc_url(get_site_url().OAuthController::GRANT_ACCESS_ENDPOINT.'?shop='.esc_html($shopData->RawData));
                                }
                            ?>"
                            <?php
                                if (get_option('woocommerce_version') < MipWcConnector::WC_VERSION) {
                                    echo 'disabled';
                                }
                            ?>
                            class="button button-primary wc-auth-approve"><?php esc_html_e( 'Approve', 'WC-Mipconnector'); ?></a>
                            <a href="<?php echo esc_url($shopData->CallbackUrl); ?>" class="button wc-auth-deny"><?php esc_html_e( 'Reject', 'WC-Mipconnector'); ?></a>
                        </p>

                        </div>
                    </div>
                </body>
            </html>
        <?php
    }
}

