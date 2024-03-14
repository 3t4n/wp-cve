<?php

/*
Plugin Name: BigBuy Dropshipping Connector for WooCommerce
Plugin URI: https://www.bigbuy.eu/blog/en/bigbuy-plugin-woocommerce
Text Domain: WC-Mipconnector
Domain Path: /app/translations
Description: Sync the BigBuy catalog in your WooCommerce with our plugin
Version: 1.9.12
Author: BigBuy
Author URI: https://bigbuy.eu
*/

defined('ABSPATH') || exit;

use WcMipConnector\Enum\MipWcConnector;
use WcMipConnector\Service\LoggerService;

$initializationError = null;

try {
    require_once __DIR__.'/vendor/autoload.php';

    $classMipconnector = new WcMipconnector();
    register_activation_hook(__FILE__, [$classMipconnector,'installMipConnector']);
    $issues = $classMipconnector->getIssues();

    if (!$issues) {
        require_once 'WcMipConnectorHook.php';
    }
} catch (\Throwable $exception) {
    $initializationError = $exception->getMessage();
}

try {
    $loggerService = new LoggerService();

    if ($initializationError) {
        $loggerService->critical(__FILE__.' Plugin initialization: '.$initializationError);
    }
} catch (\Throwable $exception) {
    \error_log('Logger initialization: '.$exception->getMessage());
}

class WcMipconnector
{
    /**
     * @throws Exception
     */
    public function installMipConnector()
    {
        $issues = $this->getIssues();

        if ($issues) {
            die($this->showValidateRequirementsMessages($issues));
        }

        require_once 'src/Setup/Install.php';
    }

    /**
     * @return array
     */
    public function getIssues(): array
    {
        $issues = [];

        $issues['phpVersion'] = PHP_VERSION < MipWcConnector::PHP_VERSION;
        $issues['wpVersion']  = get_bloginfo('version', 'display') < MipWcConnector::WP_VERSION;
        $issues['wcVersion']  = get_option('woocommerce_version') < MipWcConnector::WC_VERSION;
        $issues['activatedCurl']  = !function_exists('curl_init');
        $issues['curlExtension']  = !extension_loaded('curl');

        foreach ($issues as $issue => $issueFound) {
            if (!$issueFound) {
                unset($issues[$issue]);
            }
        }

        return $issues;
    }

    /**
     * @param $issues
     */
    public function showValidateRequirementsMessages($issues)
    {
        $translationRequirements = $this->getValidateTranslationMessages();

        foreach ($issues as $issue => $issueFound) {
            ?>
                <p><?php esc_html_e($translationRequirements[$issue], 'WC-Mipconnector');?></p>
            <?php
        }
    }

    /**
     * @return array
     */
    public function getValidateTranslationMessages()
    {
        preg_match("#^\d+(\.\d+)*#", PHP_VERSION, $match);
        $translationRequirements['phpVersion'] = 'Your PHP Version: '. $match[0] . ' is lower than the minimum recommended: ' . MipWcConnector::PHP_VERSION;
        $translationRequirements['wpVersion']  = 'Your WordPress Version: ' . get_bloginfo('version', 'display') .' is lower than the minimum recommended: ' . MipWcConnector::WP_VERSION;
        $translationRequirements['wcVersion']  = 'Your WooCommerce Version: ' . get_option('woocommerce_version') .' is lower than the minimum recommended: ' . MipWcConnector::WC_VERSION;
        $translationRequirements['activatedCurl']  =  'The function of PHP curl_init is not allowed in your server';
        $translationRequirements['curlExtension']  =  'The extension curl is not installed in your server';

        return $translationRequirements;
    }
}
