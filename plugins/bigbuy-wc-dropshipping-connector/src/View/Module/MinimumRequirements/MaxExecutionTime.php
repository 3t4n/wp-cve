<?php

namespace WcMipConnector\View\Module\MinimumRequirements;

defined('ABSPATH') || exit;

use WcMipConnector\View\Module\MinimumRequirementsView;

class MaxExecutionTime
{
    /** @var CheckStatus  */
    protected $checkStatus;

    public function __construct()
    {
        $this->checkStatus = new CheckStatus();
    }

    /**
     * @param int $maxExecutionTime
     */
    public function showMaxExecutionTimeView(int $maxExecutionTime): void
    {
        ?>
            <div class="flex-row step-requisite">
                <div class="check-status">
                    <?php
                        if ($maxExecutionTime < MinimumRequirementsView::RECOMMENDED_MAX_EXECUTION_TIME && $maxExecutionTime !== 0) {
                            $this->checkStatus->getErrorStatus();
                        } else {
                            $this->checkStatus->getCorrectStatus();
                        }
                    ?>
                </div>
                <div class="step-requisite-info-box">
                    <h4 class="mg0 strongBB"><?php esc_html_e('Max Execution Time', 'WC-Mipconnector');?>:</h4>
                    <div class="step-requisite-info">
                        <p id="elementExecutionTime" class="mg0 step-requisite-info-width">
                            <strong class="strongBB"><?php esc_html_e('Detected', 'WC-Mipconnector');?>:</strong> <?php echo $maxExecutionTime === 0 ?  esc_html_e('Unlimited', 'WC-Mipconnector'): esc_html($maxExecutionTime)?>
                        </p>
                        <p class="mg0">
                            <b class="strongBB"><?php esc_html_e('Recommended', 'WC-Mipconnector');?>:</b> <?php echo MinimumRequirementsView::RECOMMENDED_MAX_EXECUTION_TIME ?>
                        </p>
                    </div>
                </div>
            </div>
        <?php
    }
}
