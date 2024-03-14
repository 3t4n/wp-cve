<?php

namespace WcMipConnector\View\Modal;

defined('ABSPATH') || exit;

use WcMipConnector\Controller\FileController;
use WcMipConnector\Enum\MipWcConnector;
use WcMipConnector\Manager\ConfigurationOptionManager;
use WcMipConnector\Service\DirectoryService;
use WcMipConnector\Service\SystemService;

class CronModal
{
    /** @var SystemService */
    private $systemService;
    /** @var DirectoryService */
    private $directoryService;

    public function __construct()
    {
        $this->systemService = new SystemService();
        $this->directoryService = new DirectoryService();
    }

    public function showCronModal(): void
    {
        ?>
            <div id="modal-cron-ssh-normal" class="modal-cron-ssh" style="display: none">
                <div class="box-content">
                    <div class="box-title">
                        <p><?php esc_html_e('Cron SSH', 'WC-Mipconnector');?></p>
                        <input id="cron-command-input" type="text" value="<?php
                        echo $this->systemService->defineSshCronTask() . ' ' . ConfigurationOptionManager::getOptionBySiteUrl().FileController::READ_FILES_ENDPOINT.' >> '.esc_html($this->directoryService->getLogDir()).'/'.MipWcConnector::CRON_IMPORT_PROCESS_LOG_FILENAME?>">
                    </div>
                    <div class="u-flex-end box-input">
                        <button id="btn-close-modal1" onclick="copyContentFromCronCommand()" class="modal-button cancel u-mr"><?php esc_html_e('Copy', 'WC-Mipconnector');?></button>
                        <button id="btn-copy-modal1" onclick="hideModalCronGeneric('modal-cron-ssh-normal')" class="modal-button copy"><?php esc_html_e('Cancel', 'WC-Mipconnector');?></button>
                    </div>
                </div>
            </div>
        <?php
    }
}
