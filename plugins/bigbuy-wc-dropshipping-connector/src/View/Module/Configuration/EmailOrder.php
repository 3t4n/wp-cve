<?php

namespace WcMipConnector\View\Module\Configuration;

defined('ABSPATH') || exit;

use WcMipConnector\Manager\ConfigurationOptionManager;

class EmailOrder
{
    public function showEmailOrderOption(): void
    {
        $emailOrderOption = ConfigurationOptionManager::getSendEmail();
        ?>
            <div class="flex-row tag-item-content">
                <div class="tag-item-info flex-between">
                    <p><b><?php esc_html_e('Email order updates', 'WC-Mipconnector');?></b></p>
                    <i class="fa fa-question-circle grey tooltip-position">
                        <span class="tooltip-text"><?php esc_html_e('This option enables you to choose whether you want to receive emails for each status change update.', 'WC-Mipconnector');?></span>
                    </i>
                </div>
                <div class="tag-item-options">
                    <p>
                        <input datajs-form-el="Option" type="radio" name="sendEmail"
                               value="1" <?php echo esc_attr($emailOrderOption ? 'checked' : ''); ?>><?php esc_html_e('Yes', 'WC-Mipconnector') ?></input>
                    </p>
                    <p>
                        <input datajs-form-el="Option" type="radio" name="sendEmail"
                               value="0" <?php echo esc_attr(!$emailOrderOption ? 'checked' : ''); ?>><?php esc_html_e('No', 'WC-Mipconnector') ?></input>
                    </p>
                </div>
            </div>
        <?php
    }
}