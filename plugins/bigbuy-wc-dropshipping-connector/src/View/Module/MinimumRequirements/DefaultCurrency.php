<?php

namespace WcMipConnector\View\Module\MinimumRequirements;

defined('ABSPATH') || exit;

use WcMipConnector\View\Module\MinimumRequirementsView;

class DefaultCurrency
{
    /** @var CheckStatus  */
    protected $checkStatus;

    public function __construct()
    {
        $this->checkStatus = new CheckStatus();
    }

    /**
     * @param string $defaultCurrency
     */
    public function showDefaultCurrencyView(string $defaultCurrency): void
    {
        ?>
            <div class="flex-row step-requisite">
                <div class="check-status">
                    <?php
                        if ($defaultCurrency !== MinimumRequirementsView::DEFAULT_CURRENCY) {
                            $this->checkStatus->getErrorStatus();
                        } else {
                            $this->checkStatus->getCorrectStatus();
                        }
                    ?>
                </div>
                <div class="step-requisite-info-box">
                    <h4 class="mg0 strongBB"><?php esc_html_e('Default currency', 'WC-Mipconnector');?>:</h4>
                    <div class="step-requisite-info">
                        <p id="elementExecutionTime" class="mg0 step-requisite-info-width">
                            <strong class="strongBB"><?php esc_html_e('Detected', 'WC-Mipconnector');?>:</strong> <?php echo $defaultCurrency.' '.get_woocommerce_currency_symbol() ?>
                        </p>
                        <p class="mg0">
                            <b class="strongBB"><?php esc_html_e('Required', 'WC-Mipconnector');?>:</b> EUR €
                        </p>
                    </div>
                </div>
            </div>
        <?php
    }
}
