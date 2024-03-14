<?php

namespace WcMipConnector\View\Module\MinimumRequirements;

defined('ABSPATH') || exit;

class Taxes
{
    /** @var CheckStatus  */
    protected $checkStatus;

    public function __construct()
    {
        $this->checkStatus = new CheckStatus();
    }

    /**
     * @param array $shopTaxes
     */
    public function showTaxesView(array $shopTaxes): void
    {
        ?>
            <div class="flex-row step-requisite">
                <div class="check-status">
                    <?php
                        if (!$shopTaxes) {
                            $this->checkStatus->getErrorStatus();
                        } else {
                            $this->checkStatus->getCorrectStatus();
                        }
                    ?>
                </div>
                <div class="step-requisite-info-box">
                    <h4 class="mg0 strongBB"><?php esc_html_e('Taxes Created', 'WC-Mipconnector');?>:</h4>
                    <div class="step-requisite-info">
                        <p id="elementTaxes" class="mg0  step-requisite-info-width">
                            <?php
                                if ($shopTaxes) {
                                    ?> <strong class="strongBB"><?php esc_html_e('Yes', 'WC-Mipconnector') ?></strong><?php
                                } else {
                                    ?> <strong class="strongBB"><?php esc_html_e('No', 'WC-Mipconnector') ?></strong><?php
                                }
                            ?>
                        </p>
                    </div>
                </div>
            </div>
        <?php
    }
}
