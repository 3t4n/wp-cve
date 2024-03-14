<?php

namespace WcMipConnector\View\Module;

defined('ABSPATH') || exit;

use WcMipConnector\Exception\WooCommerceApiExceptionInterface;
use WcMipConnector\View\Module\Configuration\ApiKey;
use WcMipConnector\View\Module\Configuration\CarrierOption;
use WcMipConnector\View\Module\Configuration\EmailOrder;
use WcMipConnector\View\Module\Configuration\ProductOption;
use WcMipConnector\View\Module\Configuration\TagOption;

class ConfigurationModuleView
{
    /** @var ProductOption  */
    protected $productOption;
    /** @var TagOption  */
    protected $tagOption;
    /** @var EmailOrder  */
    protected $emailOrderOption;
    /** @var CarrierOption  */
    protected $carrierOption;
    /** @var ApiKey */
    private $apiKey;

    public function __construct()
    {
        $this->productOption = new ProductOption();
        $this->tagOption = new TagOption();
        $this->emailOrderOption = new EmailOrder();
        $this->carrierOption = new CarrierOption();
        $this->apiKey = new ApiKey();
    }

    /**
     * @throws WooCommerceApiExceptionInterface
     */
    public function showConfigurationModuleView(): void
    {
        ?>
            <div id="configuration-body">
                <h3><?php esc_html_e('Additional settings', 'WC-Mipconnector');?></h3>
                <form id="saveConfiguration" method="post">
                    <section class="flex-col content-step2-section2-mip tag-content">
                        <?php
                            $this->productOption->showProductOption();
                            $this->tagOption->showTagOption();
                            $this->emailOrderOption->showEmailOrderOption();
                        ?>
                        <div class="flex-row tag-item-last">
                            <?php
                                $this->carrierOption->showCarrierOption();
                            ?>
                        </div>
                        <?php
                        $this->apiKey->showApiKey();
                        ?>
                    </section>
                    <input id="send-form-button" class="button button-send button-primary" type="submit" name='save' value='<?php esc_attr_e('Save', 'WC-Mipconnector'); ?>'/>
                </form>
            </div>
        <?php
    }
}


