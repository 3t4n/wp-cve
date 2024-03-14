<?php

namespace WcMipConnector\View\Module;

defined('ABSPATH') || exit;

use WcMipConnector\Model\View\ConfigurationView;
use WcMipConnector\View\Module\MinimumRequirements\Academy;
use WcMipConnector\View\Module\MinimumRequirements\Cron;
use WcMipConnector\View\Module\MinimumRequirements\MaxExecutionTime;
use WcMipConnector\View\Module\MinimumRequirements\MemoryInfo;
use WcMipConnector\View\Module\MinimumRequirements\MemoryLimit;
use WcMipConnector\View\Module\MinimumRequirements\Taxes;

class MinimumRequirementsView
{
    public const RECOMMENDED_MEMORY_LIMIT = 1024;
    public const BYTES_TO_MB = 1048576;
    public const RECOMMENDED_MAX_EXECUTION_TIME = 3600;
    public const DEFAULT_CURRENCY = 'EUR';

    /** @var Academy  */
    protected $academyView;
    /** @var MemoryInfo  */
    protected $memoryInfoView;
    /** @var Taxes  */
    protected $taxesView;
    /** @var MemoryLimit  */
    protected $memoryLimitView;
    /** @var MaxExecutionTime  */
    protected $maxExecutionTime;
    /** @var Cron  */
    protected $cronView;

    public function __construct()
    {
        $this->academyView = new Academy();
        $this->memoryInfoView = new MemoryInfo();
        $this->taxesView = new Taxes();
        $this->memoryLimitView = new MemoryLimit();
        $this->maxExecutionTime = new MaxExecutionTime();
        $this->cronView = new Cron();
    }

    /**
     * @param ConfigurationView $configurationView
     */
    public function showMinimumRequirementsView(ConfigurationView $configurationView): void
    {
        ?>
            <div id="minimum-requirements-body" class="minimum-requirements-container">
                <section class="minimum-requirements-content">
                    <div class="step">
                        <div id="data-requirements" class="step-content">
                            <h3><?php esc_html_e('Minimum requirements', 'WC-Mipconnector');?></h3>
                            <?php
                                $this->academyView->showAcademyView($configurationView->defaultIsoCode);

                                if ($configurationView->memoryInfo) {
                                    $this->memoryInfoView->showMemoryInfoView($configurationView->memoryInfo);
                                }

                                $this->taxesView->showTaxesView($configurationView->taxes);
                                $this->memoryLimitView->showMemoryLimitView($configurationView->accountReport->MemoryLimit);
                                $this->maxExecutionTime->showMaxExecutionTimeView((int)$configurationView->accountReport->MaxExecutionTime);
                                $this->cronView->showCronView($configurationView->cron);
                            ?>
                        </div>
                    </div>
                </section>
            </div>
        <?php
    }
}


