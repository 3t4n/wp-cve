<?php

namespace WcMipConnector\View\Module\Configuration;

defined('ABSPATH') || exit;

use WcMipConnector\Manager\ConfigurationOptionManager;

class TagOption
{
    public function showTagOption(): void
    {
        $tagOption = ConfigurationOptionManager::getActiveTag();
        ?>
            <div class="flex-row tag-item-content">
                <div class="tag-item-info flex-between">
                    <p><b><?php esc_html_e('Tag ‘Black Friday/Cyber Monday’ ', 'WC-Mipconnector');?></b></p>
                    <i class="fa fa-question-circle grey tooltip-position">
                        <span class="tooltip-text"><?php esc_html_e('This option enables you to synchronise the Black Friday/Cyber Monday tag in your store. If you don’t want to have this tag in your store, select No.', 'WC-Mipconnector');?></span>
                    </i>
                </div>

                <div id="formData" class="tag-item-options"
                     data-FormTagBlackFriday="<?php echo esc_attr($tagOption ? 1 : 0); ?>">
                    <p>
                        <input datajs-form-el="Option" type="radio" name="tagOption"
                               value="1" <?php echo esc_attr($tagOption ? 'checked' : ''); ?>><?php esc_html_e('Yes', 'WC-Mipconnector') ?></input>
                    </p>
                    <p>
                        <input datajs-form-el="Option" type="radio" name="tagOption"
                               value="0" <?php echo esc_attr(!$tagOption ? 'checked' : ''); ?>><?php esc_html_e('No', 'WC-Mipconnector') ?></input>
                    </p>
                </div>
            </div>
        <?php
    }
}