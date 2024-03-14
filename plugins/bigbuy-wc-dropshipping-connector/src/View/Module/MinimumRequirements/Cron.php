<?php

namespace WcMipConnector\View\Module\MinimumRequirements;

defined('ABSPATH') || exit;

use WcMipConnector\Controller\FileController;
use WcMipConnector\Enum\MipWcConnector;
use WcMipConnector\Manager\ConfigurationOptionManager;
use WcMipConnector\Service\DirectoryService;

class Cron
{
    /** @var CheckStatus  */
    protected $checkStatus;

    public function __construct()
    {
        $this->checkStatus = new CheckStatus();
    }

    /**
     * @param bool $cronStatus
     */
    public function showCronView(bool $cronStatus): void
    {
        $siteUrl = ConfigurationOptionManager::getOptionBySiteUrl();
        ?>
            <div class="flex-row step-requisite no-border">
                <div class="check-status">
                    <?php
                        if (!$cronStatus) {
                            $this->checkStatus->getErrorStatus();
                        } else {
                            $this->checkStatus->getCorrectStatus();
                        }
                    ?>
                </div>
                <div class="step-requisite-info-box">
                    <h4 class="mg0 strongBB"><?php esc_html_e('Activate the following programmed tasks (CRON)', 'WC-Mipconnector');?>:</h4>
                    <div class="u-pdt">
                        <p class="mg0"><strong class="strongBB"><?php esc_html_e('Frequency', 'WC-Mipconnector');?>:</strong></p>
                        <p class="mg0"><?php esc_html_e('Every minute', 'WC-Mipconnector');?></p>
                        <h4 class="mg0"><b class="strongBB">URL:</b></h4>
                        <p id="data-cron-1" class="mg0 text-responsive linkBB"><?php echo $siteUrl.FileController::READ_FILES_ENDPOINT.' >> '.esc_html(DirectoryService::getInstance()->getLogDir()
                                ).'/'.MipWcConnector::CRON_IMPORT_PROCESS_LOG_FILENAME?></p>

                        <p class="text-responsive nomarginBB u-pdt">*<?php esc_html_e('If your server does not accept commands to create CRON, use this url', 'WC-Mipconnector');?>:</p>
                        <p class="mg0 text-responsive linkBB"><?php echo $siteUrl.FileController::READ_FILES_ENDPOINT ?></p>
                        <button onclick="showModalCronGeneric('modal-cron-ssh-normal')" class="cron-button mgb-1">
                            <?php esc_html_e('See CRON command for SSH', 'WC-Mipconnector');?></button>
                    </div>
                </div>
            </div>
        <?php
    }
}
