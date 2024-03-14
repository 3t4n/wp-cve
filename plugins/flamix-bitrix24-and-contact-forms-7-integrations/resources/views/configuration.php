<?php

use Flamix\Plugin\General\Checker;
use FlamixLocal\CF7\Helpers;

?>
<h2><?php _e('Diagnostics', FLAMIX_BITRIX24_CF7_CODE); ?></h2>
<ul>
    <li><?php
        try {
            $status = Helpers::send(['status' => 'check'], 'check');
            echo Checker::params('Status', ($status['status'] ?? '') === 'success', [
                __('Working', FLAMIX_BITRIX24_CF7_CODE),
                __('Bad Domain or API Key', FLAMIX_BITRIX24_CF7_CODE),
            ]);
        } catch (\Exception $e) {
            echo Checker::params('Status', false, [
                __('Working', FLAMIX_BITRIX24_CF7_CODE),
                esc_html($e->getMessage()),
            ]);
        } ?>
    </li>
    <li><?php echo Checker::params(__('Contact Form 7 exist', FLAMIX_BITRIX24_CF7_CODE), Checker::isPluginActive('contact-form-7/wp-contact-form-7.php'), [
            __('Yes', FLAMIX_BITRIX24_CF7_CODE),
            __('No. You must install theme!', FLAMIX_BITRIX24_CF7_CODE),
        ]); ?></li>
    <li><?php echo Checker::params('PHP version', version_compare(PHP_VERSION, '7.4.0') >= 0, [
            sprintf(__('Ok (%s)', FLAMIX_BITRIX24_CF7_CODE), PHP_VERSION),
            sprintf(__('Bad PHP version (%s)! Update PHP version on your hosting!', FLAMIX_BITRIX24_CF7_CODE), PHP_VERSION),
        ]); ?></li>
    <li><?php echo Checker::params('cURL', extension_loaded('curl')); ?></li>
    <li><?php echo Checker::params('SSL', is_ssl()); ?></li>
    <li><?php echo Checker::params('Backup email', !empty(Helpers::get_backup_email()), [
            sprintf(__('Valid (%s)', FLAMIX_BITRIX24_CF7_CODE), Helpers::get_backup_email()),
            __('Invalid', FLAMIX_BITRIX24_CF7_CODE),
        ]); ?></li>
</ul>