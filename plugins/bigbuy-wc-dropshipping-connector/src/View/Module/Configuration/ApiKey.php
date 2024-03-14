<?php

namespace WcMipConnector\View\Module\Configuration;

defined('ABSPATH') || exit;

use WcMipConnector\Manager\ConfigurationOptionManager;

class ApiKey
{
    public function showApiKey(): void
    {
        $apiKey = ConfigurationOptionManager::getApiKey();
        ?>
        <div class="container-api-key">
            <div class="flex-row tag-item-content-api-key">
                <div class="tag-item-info-api flex-between">
                    <p>
                        <b><?php esc_html_e('API BigBuy', 'WC-Mipconnector'); ?></b>
                    </p>
                    <i class="fa fa-question-circle grey tooltip-position">
                        <span class="tooltip-text"><?php esc_html_e('In order to have BigBuy’s shipping charges automatically, you should complete this field, otherwise you need to manage the shipping costs manually. To obtain the API key, go to BigBuy’s control panel within the section My Account > Synchronise with BigBuy, in the field for the API Key Production.', 'WC-Mipconnector'); ?></span>
                    </i>
                </div>
            </div>
            <div class="tag-input">
                <input type="text" id="apiKey" name="apiKey" value="<?php echo esc_html($apiKey); ?>">
            </div>
        </div>
        <?php
    }
}