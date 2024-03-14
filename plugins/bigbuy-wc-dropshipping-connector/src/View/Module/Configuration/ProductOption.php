<?php

namespace WcMipConnector\View\Module\Configuration;

defined('ABSPATH') || exit;

use WcMipConnector\Manager\ConfigurationOptionManager;

class ProductOption
{
    public function showProductOption(): void
    {
        $productDeleteDateValue = ConfigurationOptionManager::getProductOption();
        ?>
            <div class="flex-row tag-item-content">
                <div class="tag-item-info flex-between">
                    <p><b><?php esc_html_e('Delete inactive products', 'WC-Mipconnector');?></b></p>
                    <i class="fa fa-question-circle grey tooltip-position">
                        <span class="tooltip-text"><?php esc_html_e('To improve the performance of your ecommerce, we recommend you delete inactive products. If you select the "Yes" option, we will delete any products that have been inactive for over 90 days as, in the majority of cases, these are de-catalogued products.', 'WC-Mipconnector');?></span>
                    </i>
                </div>

                <div id="form-data" class="tag-item-options"
                     data-form-product="<?php echo esc_attr($productDeleteDateValue ? 1 : 0); ?>">
                    <p>
                        <label>
                            <input datajs-form-el="Option" type="radio" name="productOption"
                                   value="1" <?php echo esc_attr($productDeleteDateValue ? 'checked' : ''); ?>><?php esc_html_e('Yes', 'WC-Mipconnector') ?>
                        </label>
                    </p>
                    <p>
                        <label>
                            <input datajs-form-el="Option" type="radio" name="productOption"
                                   value="0" <?php echo esc_attr(!$productDeleteDateValue ? 'checked' : ''); ?>><?php esc_html_e('No', 'WC-Mipconnector') ?>
                        </label>
                    </p>
                </div>
            </div>
        <?php
    }
}