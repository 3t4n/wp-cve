<?php

namespace WcMipConnector\View\Module\MinimumRequirements;

defined('ABSPATH') || exit;

use WcMipConnector\View\Assets\Assets;

class Academy
{
    /** @var Assets  */
    protected $assets;

    public function __construct()
    {
        $this->assets = new Assets();
    }

    /**
     * @param string $isoCode
     */
    public function showAcademyView(string $isoCode): void
    {
        ?>
            <div class="step-requisite">
                <a class="txt-decoration-none" href="<?php echo esc_attr('https://www.bigbuy.eu/academy/'.$isoCode.'/?utm_source=modulos&utm_medium=referral&utm_campaign=woocommerce');?>" target="_blank">
                    <div class="academy-button">
                        <img class=""  width="25" height="25" src="<?php echo $this->assets->getImageAsset('academy.svg') ?>">
                        <span>
                             <strong> <?php esc_html_e('Academy', 'WC-Mipconnector');?></strong> -
                            <?php esc_html_e('How to synchronise WooCommerce via the ', 'WC-Mipconnector');
                            esc_html_e('Multi-Channel Integration Platform', 'WC-Mipconnector');?>
                        </span>
                    </div>
                </a>
            </div>
        <?php
    }
}
