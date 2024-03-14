<?php

namespace WcMipConnector\View\Module\MinimumRequirements;

defined('ABSPATH') || exit;

use WcMipConnector\View\Module\MinimumRequirementsView;

class MemoryInfo
{
    /** @var CheckStatus  */
    protected $checkStatus;

    public function __construct()
    {
        $this->checkStatus = new CheckStatus();
    }

    /**
     * @param array $memoryInfo
     */
    public function showMemoryInfoView(array $memoryInfo): void
    {
        ?>
            <div class="flex-row step-requisite">
                <div class="check-status">
                    <?php
                        if ($memoryInfo['MemTotal'] < MinimumRequirementsView::RECOMMENDED_MEMORY_LIMIT) {
                            $this->checkStatus->getErrorStatus();
                        } else {
                            $this->checkStatus->getCorrectStatus();
                        }
                    ?>
                </div>
                <div class="step-requisite-info-box">
                    <h4 class="mg0 strongBB"><?php esc_html_e('RAM', 'WC-Mipconnector');?>:</h4>
                    <div class="step-requisite-info">
                        <p class="mg0  step-requisite-info-width">
                            <strong class="strongBB"><?php esc_html_e('Total', 'WC-Mipconnector');?>:</strong> <?php echo $memoryInfo['MemTotal']  === 0 ? esc_html_e('Unknown', 'WC-Mipconnector') : esc_html($memoryInfo['MemTotal']. 'MB') ?>
                        </p>
                        <p class="mg0">
                            <b class="strongBB"><?php esc_html_e('Available', 'WC-Mipconnector');?>:</b> <?php echo $memoryInfo['MemFree']  === 0 ? esc_html_e('Unknown', 'WC-Mipconnector') : esc_html($memoryInfo['MemFree']. 'MB') ?>
                        </p>
                    </div>
                </div>
            </div>
        <?php
    }
}
