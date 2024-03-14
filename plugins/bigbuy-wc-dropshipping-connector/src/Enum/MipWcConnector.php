<?php

namespace WcMipConnector\Enum;

defined('ABSPATH') || exit;

class MipWcConnector
{
    const MODULE_NAME = 'bigbuy-wc-dropshipping-connector';
    const PHP_VERSION = '7.2.0.0';
    const PHP_MIN_VERSION_SUPPORT = '7.4.0.0';
    const WP_VERSION = '4.9';
    const WC_VERSION = '3.7.1';
    const WC_MIPCONNECTOR_TAG_NAME = 'black friday / cyber monday';
    const CRON_EXECUTION_LOG_FILENAME = 'cron_execution.log';
    const CRON_IMPORT_PROCESS_LOG_FILENAME = 'process_import_cron.log';
}