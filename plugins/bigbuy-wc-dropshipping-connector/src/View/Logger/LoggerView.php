<?php

namespace WcMipConnector\View\Logger;

use WcMipConnector\Service\LoggerService;

defined('ABSPATH') || exit;

class LoggerView
{
    /**
     * @param array $filesInLogDir
     */
    public function showLoggerView(array $filesInLogDir): void
    {
        foreach ($filesInLogDir as $file) {
            if (!LoggerService::isLogFile($file)) {
                continue;
            }

            ?>

            <div class="step-requisite">
                <a class="txt-decoration-none" href="<?php echo $this->showLoggerViewByDate($file); ?>"><?php echo $file;?></a>
            </div>

            <?php
        }
    }

    /**
     * @param string $file
     * @return string
     */
    public function showLoggerViewByDate(string $file): string
    {
        return 'api?messageType=SYSTEM&operationType=LOG&Id=' . $file;
    }
}
