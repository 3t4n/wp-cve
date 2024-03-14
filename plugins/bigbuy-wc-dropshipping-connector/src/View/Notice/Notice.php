<?php

namespace WcMipConnector\View\Notice;

defined('ABSPATH') || exit;

class Notice
{
    /**
     * @param string $errorMessage
     */
    public function showErrorNotice(string $errorMessage): void
    {
        ?>
            <div class="isErrorMessage box-info mg0 u-mgb">
                <span class="box-icon box-error-icon">
                    <svg viewBox="0 0 24 24" id="gridicons-notice" width="25" height="25">
                        <g>
                            <path d="M12 2C6.477 2 2 6.477 2 12s4.477 10 10 10 10-4.477 10-10S17.523 2 12 2zm1 15h-2v-2h2v2zm0-4h-2l-.5-6h3l-.5 6z"></path>
                        </g>
                    </svg>
                </span>
                <span class="box-info-content">
                    <span class="notice__text"><?php esc_html_e($errorMessage, 'WC-Mipconnector');?></span>
                </span>
            </div>
        <?php
    }

    /**
     * @param string $warningMessage
     */
    public function showWarningNotice(string $warningMessage): void
    {
        ?>
            <div class="isErrorMessage box-info mg0 u-mgb">
                    <span class="box-icon box-warning-icon">
                        <svg viewBox="0 0 24 24" id="gridicons-notice" width="25" height="25">
                            <g>
                                <path d="M12 2C6.477 2 2 6.477 2 12s4.477 10 10 10 10-4.477 10-10S17.523 2 12 2zm1 15h-2v-2h2v2zm0-4h-2l-.5-6h3l-.5 6z"></path>
                            </g>
                        </svg>
                    </span>
                <span class="box-info-content">
                        <span class="notice__text"><?php esc_html_e($warningMessage, 'WC-Mipconnector'); ?>
                        </span>
                </span>
            </div>
        <?php
    }

    /**
     * @param string $warningVersion
     */
    public function showWarningVersion(string $warningVersion): void
    {
        ?>
            <div class="notice notice-error is-dismissible">
                <p>
                    <b><?php echo esc_html_e($warningVersion, 'WC-Mipconnector'); ?></b>
                    <b><a href="plugins.php"><?php echo esc_html_e('update the plugin.', 'WC-Mipconnector'); ?></a></b>
                </p>
            </div>
        <?php
    }

    public function showSuccessVersion(): void
    {
        ?>
            <div class="notice notice-success is-dismissible">
                <p>
                    <b><?php echo esc_html_e('Saved changes', 'WC-Mipconnector'); ?></b>
                </p>
            </div>
        <?php
    }
}
