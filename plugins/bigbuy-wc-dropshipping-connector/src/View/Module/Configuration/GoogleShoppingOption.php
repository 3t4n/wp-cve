<?php

namespace WcMipConnector\View\Module\Configuration;

defined('ABSPATH') || exit;

use WcMipConnector\Manager\ConfigurationOptionManager;

class GoogleShoppingOption
{
    public function showGoogleShoppingOption(): void
    {
        $gsOption = ConfigurationOptionManager::getUpdateProductUrl();
        ?>
            <div class="flex-row tag-item-content">
                <div class="tag-item-info flex-between">
                    <p><b><?php esc_html_e('Update products from Google Shopping', 'WC-Mipconnector');?></b></p>
                    <i class="fa fa-question-circle grey tooltip-position">
                        <span class="tooltip-text"><?php esc_html_e('This option enables you to synchronise your store product to Google Shopping channel', 'WC-Mipconnector');?></span>
                    </i>
                </div>

                <div id="formData" class="tag-item-options"
                     data-FormTagBlackFriday="<?php echo esc_attr($gsOption ? 1 : 0); ?>">
                    <p>
                        <input datajs-form-el="Option" type="radio" name="gsOption"
                               value="1" <?php echo esc_attr($gsOption ? 'checked' : ''); ?>><?php esc_html_e('Yes', 'WC-Mipconnector') ?>
                    </p>
                    <p>
                        <input datajs-form-el="Option" type="radio" name="gsOption"
                               value="0" <?php echo esc_attr(!$gsOption ? 'checked' : ''); ?>><?php esc_html_e('No', 'WC-Mipconnector') ?>
                    </p>
                </div>
            </div>
        <?php
    }
}