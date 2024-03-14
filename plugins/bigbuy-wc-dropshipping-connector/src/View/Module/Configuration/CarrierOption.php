<?php

namespace WcMipConnector\View\Module\Configuration;

defined('ABSPATH') || exit;

use WcMipConnector\Exception\WooCommerceApiExceptionInterface;
use WcMipConnector\Manager\ConfigurationOptionManager;

class CarrierOption
{
    /**
     * @throws WooCommerceApiExceptionInterface
     */
    public function showCarrierOption(): void
    {
        $carrierOption = ConfigurationOptionManager::getCarrierOption();
        ?>
            <div class="tag-item-content">
                <div class="tag-item-info flex-between">
                    <p><b><?php esc_html_e('Manual synchronisation of carriers', 'WC-Mipconnector');?></b></p>
                    <i class="fa fa-question-circle grey tooltip-position">
                        <span class="tooltip-text"><?php esc_html_e('If you are going to use multiple suppliers in your store, you should select the option ‘Yes’, and create the carriers manually. If you select ‘No’, we will automatically charge the prices of the BigBuy carriers.', 'WC-Mipconnector');?></span>
                    </i>
                </div>
                <div class="tag-item-options">
                    <p class="flex">
                        <input datajs-form-el="Option" type="radio" name="bigbuyCarrierOption"
                               value="1" <?php echo esc_attr($carrierOption?'checked':''); ?>><?php esc_html_e('Yes', 'WC-Mipconnector') ?></input>
                    </p>
                    <p class="flex">
                        <input datajs-form-el="Option" type="radio" name="bigbuyCarrierOption"
                               value="0" <?php echo esc_attr(!$carrierOption?'checked':''); ?>><?php esc_html_e('No', 'WC-Mipconnector') ?></input>
                    </p>
                </div>
            </div>
        <?php
    }
}