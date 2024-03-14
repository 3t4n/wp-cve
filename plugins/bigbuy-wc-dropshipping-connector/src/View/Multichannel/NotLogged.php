<?php

namespace WcMipConnector\View\Multichannel;

defined('ABSPATH') || exit;

use WcMipConnector\View\Assets\Assets;

class NotLogged
{
    /** @var Assets  */
    protected $assets;

    public function __construct()
    {
        $this->assets = new Assets();
    }

    public function showNotLoggedView(): void
    {
        $doneImageDir = $this->assets->getImageAsset('done_alt.svg');
        ?>
            <div class="info-mip-woocommerce">
                <h2><?php esc_html_e('Synchronise your ecommerce with the BigBuy product catalogue.', 'WC-Mipconnector');?></h2>
                <div>
                    <div class="cheklist">
                        <span><img src="<?php echo $doneImageDir ?>" class="done-icon" width="25" height="25"></span>
                        <p><strong><?php esc_html_e('+100,000 items of stock', 'WC-Mipconnector');?></strong></p>
                    </div>
                    <div class="cheklist">
                        <span><img src="<?php echo $doneImageDir ?>" class="done-icon" width="25" height="25"></span>
                        <p><strong><?php esc_html_e('Categories: ', 'WC-Mipconnector');?></strong><em><?php esc_html_e('Kitchen, Sports, Perfumery, Fashion, Sex Shop, Electronics, IT, Home, Toys...', 'WC-Mipconnector');?></em></p>
                    </div>
                    <div class="cheklist">
                        <span><img src="<?php echo $doneImageDir ?>" class="done-icon" width="25" height="25"></span>
                        <p><strong><?php esc_html_e('+2,500 original brands', 'WC-Mipconnector');?></strong></p>
                    </div>
                    <div class="cheklist">
                        <span><img src="<?php echo $doneImageDir ?>" class="done-icon" width="25" height="25"></span>
                        <p><strong><?php esc_html_e('Deliveries from 24', 'WC-Mipconnector');?></strong></p>
                    </div>
                    <div class="cheklist">
                        <span><img src="<?php echo $doneImageDir ?>" class="done-icon" width="25" height="25"></span>
                        <p><strong><?php esc_html_e( 'Catalogue available in 24 languages', 'WC-Mipconnector');?></strong></p>
                    </div>
                    <div class="cheklist">
                        <span><img src="<?php echo $doneImageDir ?>" class="done-icon" width="25" height="25"></span>
                        <p><strong><?php esc_html_e('European stock', 'WC-Mipconnector');?></strong></p>
                    </div>
                    <div class="cheklist">
                        <span><img src="<?php echo $doneImageDir ?>" class="done-icon" width="25" height="25"></span>
                        <p><strong><?php esc_html_e('New items daily', 'WC-Mipconnector');?></strong></p>
                    </div>
                    <div class="cheklist">
                        <span><img src="<?php echo $doneImageDir ?>" class="done-icon" width="25" height="25"></span>
                        <p><strong><?php esc_html_e('Automatic synchronisation:', 'WC-Mipconnector');?></strong> <em><?php esc_html_e( 'products, categories, orders, carriers, tracking...', 'WC-Mipconnector');?></em></p>
                    </div>
                </div>
                <p><?php esc_html_e('To start the synchronisation process, Log into the ', 'WC-Mipconnector');?>
                    <strong class="strong-mip-woocommerce"><?php esc_html_e( 'Multi-Channel Integration Platform', 'WC-Mipconnector');?></strong>
                    <?php esc_html_e(' and follow the steps', 'WC-Mipconnector');?>
                </p>
            </div>
            <div class="account-mip-woocommerce">
                <div>
                    <h2><strong><?php esc_html_e( 'Log in ', 'WC-Mipconnector');?><?php esc_html_e( 'Multi-Channel Integration Platform', 'WC-Mipconnector');?></strong></h2>
                    <a class="button-mip-woocommerce" href="https://platform.bigbuy.eu/?utm_source=modulos&utm_medium=referral&utm_campaign=woocommerce" target="_blank"><?php esc_html_e( 'Login', 'WC-Mipconnector');?></a>
                </div>
                <div>
                    <h2><strong><?php esc_html_e( 'Still don’t have a BigBuy account?', 'WC-Mipconnector');?></strong></h2>
                    <a class="button-mip-woocommerce" href="https://www.bigbuy.eu/es/account/create/?utm_source=modulos&utm_medium=referral&utm_campaign=woocommerce" target="_blank"><?php esc_html_e( 'Register now', 'WC-Mipconnector');?></a>
                </div>
            </div>
        <?php
    }
}
