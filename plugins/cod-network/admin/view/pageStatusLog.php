<?php
/**
 * Admin View: Page - Activity Logs show files
 *
 */

use CODNetwork\Services\CODN_Cookie_Service;
use CODNetwork\Services\CODN_File_Log_Service;

if (!defined('ABSPATH')) {
    exit;
}
$fileLogService = new CODN_File_Log_Service();
$cookie = new CODN_Cookie_Service();
$logs = $fileLogService->getLogFiles();
?>
<?php
if (isset($_POST['delete_log'])) : ?>
    <div class="updated woocommerce-message inline p-3">
        <h5 class="m-0"><?php
            esc_html_e('file logs are deleted successfully', 'COD.NETWORK'); ?></h5></div>
<?php
endif; ?>
<?php
if (isset($_POST['sendEmail'])) : ?>
    <div class="updated woocommerce-message inline p-3">
        <h5 class="m-0"><?php
            esc_html_e('Reporting logs are sent via email successfully', 'COD.NETWORK'); ?></h5></div>
<?php
endif; ?>
<?php
if (!empty($log) && !isset($_POST['delete_log'])) : ?>
    <div id="log-viewer-select">
        <h2>
            <div class="row">
                <div class="card col-md-12 p-0">
                    <div class="card-header pb-0">
                        <div id="log-viewer-select">
                            <div class="alignright">
                                <form action="<?php
                                echo esc_url(admin_url('admin.php?page=codNetwork-status')); ?>" method="post">
                                    <input type="hidden" name="search_file" value="true">
                                    <select name="log_file">
                                        <?php
                                        foreach ($logs as $logKey => $logFile) : ?>
                                            <option value="<?php
                                            echo esc_attr($logFile); ?>" <?php
                                            if (isset($searchFile) && $searchFile === $logFile) {
                                                echo 'selected="selected"';
                                            } ?>><?php
                                                echo esc_html($logFile); ?></option>
                                        <?php
                                        endforeach; ?>
                                    </select>
                                    <button type="submit" name="submit" class="button button mt-2" value="<?php
                                    esc_attr_e('View', 'woocommerce'); ?>"><?php
                                        esc_html_e('View', 'woocommerce'); ?></button>
                                </form>
                            </div>
                            <div class="clear"></div>
                            <form method="post">
                                <input type="hidden" name="send_via_slack" value="true">
                                <input type="hidden" name="send_selected_file" value="<?php echo esc_html($searchFile) ?>">
                                <h5 class="card-title h5 float-right mt-3">
                                    <button type="submit" name="submit" class="btn btn-primary btn-outline-primary"
                                        <?php
                                        if ($cookie->hasReachedReportLimit()):
                                            ?>
                                            readonly="readonly" disabled="disabled"
                                        <?php
                                        endif; ?>
                                    >Report
                                    </button>
                                </h5>
                            </form>
                        </div>
                    </div>
                    <div class="card-body">
                        <pre style="font-size: 13px;color: #3c434b;"><?php
                            echo esc_html($log); ?></pre>
                    </div>
                </div>
            </div>

            <?php
            if (!empty($log)) : ?>
                <form method="post">
                    <input type="hidden" name="delete_log" value="true">
                    <input type="hidden" name="send_selected_file" value="<?php
                    echo esc_html($searchFile) ?>">
                    <h5 class="card-title h5 float-right">
                        <button type="submit" name="submit" class="btn btn-danger btn-outline-danger mt-3">Delete log
                        </button>
                    </h5>
                </form>
            <?php
            endif; ?>
        </h2>

        <div class="clear"></div>
    </div>
<?php else : ?>
    <div id="log-viewer-select" class="mt-5">
        <div class="alignright">
            <form action="<?php
            echo esc_url(admin_url('admin.php?page=codNetwork-status')); ?>" method="post">
                <input type="hidden" name="search_file" value="true">
                <select name="log_file" class="mt-2">
                    <option value="null">Select file log</option>
                    <?php
                    foreach ($logs as $logKey => $logFile) : ?>
                        <option value="<?php
                        echo esc_attr($logFile); ?>" <?php
                        if (isset($searchFile) && $searchFile === $logFile) {
                            echo 'selected="selected"';
                        } ?>><?php
                            echo esc_html($logFile); ?></option>
                    <?php
                    endforeach; ?>
                </select>
                <button type="submit" name="submit" class="button button mt-2" value="<?php
                esc_attr_e('View', 'woocommerce'); ?>"><?php
                    esc_html_e('View', 'woocommerce'); ?></button>
            </form>
        </div>
        <div class="clear"></div>
    </div>
    <div class="card-body">
        <pre class="h6"><?php
            echo esc_html($log); ?></pre>
    </div>

    <div class="updated woocommerce-message inline">
        <p><?php
            esc_html_e('There are currently no logs to view.', 'woocommerce'); ?></p></div>
<?php endif; ?>
