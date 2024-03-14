<?php

namespace WcMipConnector\View\Module\MinimumRequirements;

defined('ABSPATH') || exit;

use WcMipConnector\View\Module\MinimumRequirementsView;

class MemoryLimit
{
    /** @var CheckStatus  */
    protected $checkStatus;

    public function __construct()
    {
        $this->checkStatus = new CheckStatus();
    }

    /**
     * @param int $memoryLimit
     */
    public function showMemoryLimitView(int $memoryLimit): void
    {
        ?>
            <div class="flex-row step-requisite">
                <div class="check-status">
                    <?php
                        $memoryLimitValue = $memoryLimit / MinimumRequirementsView::BYTES_TO_MB;
                        if ($memoryLimitValue < MinimumRequirementsView::RECOMMENDED_MEMORY_LIMIT) {
                            $this->checkStatus->getErrorStatus();
                        }else{
                            $this->checkStatus->getCorrectStatus();
                        }
                    ?>
                </div>
                <div class="step-requisite-info-box">
                    <h4 class="mg0 strongBB"><?php esc_html_e('Memory Limit', 'WC-Mipconnector');?>:</h4>
                    <div class="step-requisite-info">
                        <p id="elementMemoryLimit" class="mg0  step-requisite-info-width">
                            <strong class="strongBB"><?php esc_html_e('Detected', 'WC-Mipconnector');?>:</strong>
                            <?php echo esc_html($memoryLimitValue);
                            esc_html_e('MB', 'WC-Mipconnector');?>
                        </p>
                        <p class="mg0">
                            <b class="strongBB"><?php esc_html_e('Recommended', 'WC-Mipconnector');?>:</b>
                            <?php echo MinimumRequirementsView::RECOMMENDED_MEMORY_LIMIT;
                            esc_html_e('MB', 'WC-Mipconnector');?>
                        </p>
                    </div>
                </div>
            </div>
        <?php
    }
}
