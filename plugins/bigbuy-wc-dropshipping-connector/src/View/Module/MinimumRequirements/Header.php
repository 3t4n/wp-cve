<?php

namespace WcMipConnector\View\Module\MinimumRequirements;

defined('ABSPATH') || exit;

use WcMipConnector\Model\View\ConfigurationView;
use WcMipConnector\View\Assets\Assets;

class Header
{
    private const BYTES_TO_MB = 1048576;
    private const RECOMMENDED_MEMORY_LIMIT = 1024;
    private const RECOMMENDED_MAX_EXECUTION_TIME = 3600;

    /** @var Assets  */
    protected $assets;

    public function __construct()
    {
        $this->assets = new Assets();
    }

    /**
     * @param ConfigurationView $configurationView
     */
    public function showHeader(ConfigurationView $configurationView): void
    {
        ?>
            <header>
                <div class="hide">
                    <span id="constant-recommend-memory"><?php echo self::RECOMMENDED_MEMORY_LIMIT?></span>
                    <span id="constant-max-execution"><?php echo self::RECOMMENDED_MAX_EXECUTION_TIME ?></span>
                    <span id="memory-limit"><?php echo esc_html($configurationView->accountReport->MemoryLimit / self::BYTES_TO_MB) ?> </span>
                    <span id="max-execution-time"> <?php echo esc_html($configurationView->accountReport->MaxExecutionTime) ?> </span>
                    <span id="cron"><?php echo esc_html($configurationView->cron) ?></span>
                    <span id="taxes" data-taxes="<?php if (empty($configurationView->taxes)) { echo esc_html(0); } else { echo esc_html(1); } ?>"></span>
                </div>
            </header>
        <?php
    }
}