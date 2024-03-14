<?php
/**
 * @copyright (C) 2021 - 2024 Holger Brandt IT Solutions
 * @license GPL2
*/

if(!defined('ABSPATH')){
    exit;
}

class WADA_View_Settings extends WADA_View_BaseForm
{
    const VIEW_IDENTIFIER = 'wada-settings';

    public function __construct() {
        add_action('admin_footer', array($this, 'loadJavascriptActions'));
        WADA_ScriptUtils::loadSelect2();
    }

    protected function saveFormSubmission(){
        $executeHookAfterSettingsUpdate = true;
        $settingsPriorSaving = WADA_Settings::getAllSettings();

        $settingsSubmitted = sanitize_text_field($_POST['form-submitted']);
        if($settingsSubmitted === 'general') {
            WADA_Settings::setRetentionPeriod(intval($_POST[WADA_Settings::EVENT_LOG_RETENTION_PERIOD_NUM]), sanitize_text_field($_POST[WADA_Settings::EVENT_LOG_RETENTION_PERIOD_UNIT]));
            WADA_Settings::setIsAnonymizeIPAddress(array_key_exists(WADA_Settings::ANONYMIZE_IP_ADDRESS, $_POST));
            WADA_Settings::setDateFormatForDateOnly(sanitize_text_field($_POST[WADA_Settings::DATE_FORMAT_DATE_ONLY]));
            WADA_Settings::setDateFormatForDatetime(sanitize_text_field($_POST[WADA_Settings::DATE_FORMAT_DATE_TIME]));
            WADA_Settings::setIsDeleteDatabaseDataOnUninstall(array_key_exists(WADA_Settings::DELETE_DB_DATA_ON_UNINSTALL, $_POST));
            WADA_Settings::setLoggingLevel(intval($_POST[WADA_Settings::LOGGING_LEVEL]));
            WADA_Settings::setLogFileSizeWarningLevel(intval($_POST[WADA_Settings::LOGGING_LOG_FILE_WARNING_SIZE_MB]));
            WADA_Settings::setIsLoggingForced(array_key_exists(WADA_Settings::LOGGING_FORCED, $_POST));
            WADA_Settings::setLastActivitiesWidgetEnabled(array_key_exists(WADA_Settings::WIDGET_LAST_ACTIVITIES_ENABLED, $_POST));
            WADA_Settings::setLastActivitiesWidgetNrOfItems(intval($_POST[WADA_Settings::WIDGET_LAST_ACTIVITIES_NR_ITEMS]));
            WADA_Settings::setLoginAttemptsWidgetEnabled(array_key_exists(WADA_Settings::WIDGET_LOGIN_ATTEMPTS_ENABLED, $_POST));
        }elseif($settingsSubmitted === 'useracc') {
            WADA_Settings::setUserAccountAutoAdjustEnableStatus(array_key_exists(WADA_Settings::UAC_AUTO_ADJUST_ENABLED, $_POST));
            WADA_Settings::setUserAccountAutoAdjustRolesInScope(array_key_exists(WADA_Settings::UAC_AUTO_ADJUST_ROLES_IN_SCOPE, $_POST) ? $_POST[WADA_Settings::UAC_AUTO_ADJUST_ROLES_IN_SCOPE] : array());
            WADA_Settings::setUserAccountAutoAdjustInactiveSinceDaysCriteria(array_key_exists(WADA_Settings::UAC_AUTO_ADJUST_INACTIVE_SINCE_DAYS_CRITERIA, $_POST) ? intval($_POST[WADA_Settings::UAC_AUTO_ADJUST_INACTIVE_SINCE_DAYS_CRITERIA]) : 365);
            WADA_Settings::setUserAccountAutoAdjustChangeToRole(array_key_exists(WADA_Settings::UAC_AUTO_ADJUST_CHANGE_TO_ROLE, $_POST) ? sanitize_text_field($_POST[WADA_Settings::UAC_AUTO_ADJUST_CHANGE_TO_ROLE]) : 'subscriber');
            WADA_Settings::setUserAccountEnforcePwChangeEnableStatus(array_key_exists(WADA_Settings::UAC_ENF_PW_CHG_ENABLED, $_POST));
            WADA_Settings::setUserAccountEnforcePwChangeEveryXDays(array_key_exists(WADA_Settings::UAC_ENF_PW_CHG_EVERY_X_DAYS, $_POST) ? intval($_POST[WADA_Settings::UAC_ENF_PW_CHG_EVERY_X_DAYS]) : 90);
            WADA_Settings::setUserAccountEnforcePwChangeRolesInScope(array_key_exists(WADA_Settings::UAC_ENF_PW_CHG_ROLES_IN_SCOPE, $_POST) ? $_POST[WADA_Settings::UAC_ENF_PW_CHG_ROLES_IN_SCOPE] : array());
            WADA_Settings::setUserAccountEnforcePwChangeNotifications(array_key_exists(WADA_Settings::UAC_ENF_PW_CHG_NOTIFICATIONS, $_POST) ? $_POST[WADA_Settings::UAC_ENF_PW_CHG_NOTIFICATIONS] : array());
        }elseif($settingsSubmitted === 'replica') {
            WADA_Settings::setReplicationToLogglyEnableStatus(array_key_exists(WADA_Settings::REPL_LOGGLY_ENABLED, $_POST));
            WADA_Settings::setReplicationToLogglyToken(array_key_exists(WADA_Settings::REPL_LOGGLY_TOKEN, $_POST) ? sanitize_text_field($_POST[WADA_Settings::REPL_LOGGLY_TOKEN]) : '');
            WADA_Settings::setReplicationToLogglyTags(array_key_exists(WADA_Settings::REPL_LOGGLY_TAGS, $_POST) ? sanitize_text_field($_POST[WADA_Settings::REPL_LOGGLY_TAGS]) : '');
            WADA_Settings::setReplicationToLogtailEnableStatus(array_key_exists(WADA_Settings::REPL_LOGTAIL_ENABLED, $_POST));
            WADA_Settings::setReplicationToLogtailToken(array_key_exists(WADA_Settings::REPL_LOGTAIL_TOKEN, $_POST) ? sanitize_text_field($_POST[WADA_Settings::REPL_LOGTAIL_TOKEN]) : '');
        }elseif($settingsSubmitted === 'integra') {
            WADA_Settings::setIntegrationForLogsnagEnableStatus(array_key_exists(WADA_Settings::INTEG_LOGSNAG_ENABLED, $_POST));
            WADA_Settings::setIntegrationForLogsnagToken(array_key_exists(WADA_Settings::INTEG_LOGSNAG_TOKEN, $_POST) ? sanitize_text_field($_POST[WADA_Settings::INTEG_LOGSNAG_TOKEN]) : '');
            WADA_Settings::setIntegrationForLogsnagProject(array_key_exists(WADA_Settings::INTEG_LOGSNAG_PROJECT, $_POST) ? sanitize_text_field($_POST[WADA_Settings::INTEG_LOGSNAG_PROJECT]) : '');
        }elseif($settingsSubmitted === 'extensi') {
            $executeHookAfterSettingsUpdate = false;
            WADA_Log::debug('Extensions Form submit! '.print_r($_POST, true));
        }

        if($executeHookAfterSettingsUpdate) {
            $settingsAfterSaving = WADA_Settings::getAllSettings();
            do_action('wp_admin_audit_settings_update', $settingsAfterSaving, $settingsPriorSaving);
        }
    }

    protected function handleFormSubmissions(){
        if(isset($_POST['submit'])){
            check_admin_referer(self::VIEW_IDENTIFIER);
            $this->saveFormSubmission();
            $this->enqueueMessage(__('Settings saved', 'wp-admin-audit'), 'success');
        }
    }

    public function cleanupEventLogAjaxResponse(){
        WADA_Log::debug('cleanupEventLogAjaxResponse');
        check_ajax_referer(self::VIEW_IDENTIFIER);

        $res = WADA_Maintenance::cleanupEventLog();
        $response = array('success' => $res);

        die( json_encode( $response ) );
    }

    public function keyActivateAjaxResponse(){
        WADA_Log::debug('keyActivateAjaxResponse');
        check_ajax_referer('wada_keyaction');

        $res = false;
        $message = $error = null;
        $key = array_key_exists('key', $_GET) ? sanitize_text_field($_GET['key']) : null;
        if($key === ''){
            $key = null;
        }
        $licenseInfo = null;
        if($key){
            $home = new WADA_BackendWoosl();
            $res = $home->activateKey($key);
            $message = $home->lastMessage;
            $error = $home->lastError;

            $now =  WADA_DateUtils::getUTCforMySQLDate();

            WADA_Settings::setLicenseKey($key); // save it to settings

            $licenseInfo = $home->checkStatusOfKeyOnFile(false);

            $newLicenseStatus = (object)array(
                'key' => $key,
                'license_status' => ($licenseInfo && property_exists($licenseInfo, 'licenseStatus')) ? $licenseInfo->licenseStatus : null,
                'activation_status' => ($res ? 'activated' : 'failed'),
                'activation_at' => $now,
                'activation_msg' => $message,
                'activation_err' => $error,
                'last_checked' => $now,
                'last_msg' => $message,
                'last_error' => $error,
                'is_expired' => ($licenseInfo && property_exists($licenseInfo, 'isExpired')) ? $licenseInfo->isExpired : null,
                'valid_from' => ($licenseInfo && property_exists($licenseInfo, 'validFrom')) ? $licenseInfo->validFrom : null,
                'expires_on' => ($licenseInfo && property_exists($licenseInfo, 'expiresOn')) ? $licenseInfo->expiresOn : null,
                'is_test_install' => ($licenseInfo && property_exists($licenseInfo, 'isTestInstall')) ? $licenseInfo->isTestInstall : null,
                'main_install' => ($licenseInfo && property_exists($licenseInfo, 'mainInstall')) ? $licenseInfo->mainInstall : null
            );
            WADA_Log::debug('keyActivateAjaxResponse newLicenseStatus: '.print_r($newLicenseStatus, true));
            WADA_Settings::setLicenseStatus($newLicenseStatus);
        }else{
            $message = __('No license key provided', 'wp-admin-audit');
        }
        $response = array('success' => $res, 'message' => $message, 'error' => $error, 'license' => $licenseInfo, 'key' => $key);

        die( json_encode( $response ) );
    }


    public function keyDeactivateAjaxResponse(){
        WADA_Log::debug('keyDeactivateAjaxResponse');
        check_ajax_referer('wada_keyaction');

        $res = false;
        $message = $error = null;
        $key = WADA_Settings::getLicenseKey();

        $home = new WADA_BackendWoosl();
        $res = $home->deactivateKey($key);
        $message = $home->lastMessage;
        $error = $home->lastError;

        // invalidate key / status in settings
        WADA_Settings::setLicenseKey(null);
        WADA_Settings::setLicenseStatus(null);

        $response = array('success' => $res, 'message' => $message, 'error' => $error, 'license' => null, 'key' => null);

        die( json_encode( $response ) );
    }

    public function checkKeyStatusAjaxResponse(){
        WADA_Log::debug('checkKeyStatusAjaxResponse');
        check_ajax_referer('wada_keyaction');

        $res = false;
        $message = $error = null;
        $home = new WADA_BackendWoosl();
        $licenseInfo = $home->checkStatusOfKeyOnFile(true);
        $message = $home->lastMessage;
        $error = $home->lastError;

        WADA_Log::debug('checkKeyStatusAjaxResponse home: '.print_r($home, true));
        $key = WADA_Settings::getLicenseKey();

        $response = array('success' => is_object($licenseInfo), 'message' => $message, 'error' => $error, 'license' => $licenseInfo, 'key' => $key);

        die( json_encode( $response ) );
    }

    protected function displayForm(){
        $tab = isset($_GET['tab']) ? sanitize_text_field($_GET['tab']) : 'tab-general';
        $periodNumMeta = WADA_Settings::getMetadataForSetting(WADA_Settings::EVENT_LOG_RETENTION_PERIOD_NUM);
        $periodUnitMeta = WADA_Settings::getMetadataForSetting(WADA_Settings::EVENT_LOG_RETENTION_PERIOD_UNIT);
        $retentionPeriodNum = WADA_Settings::getRetentionPeriodNum();
        $retentionPeriodUnit = WADA_Settings::getRetentionPeriodUnit();
        $retentionPeriodInDays = WADA_Settings::getRetentionPeriodInDays();
        $maxRetention = WADA_Version::getFtSetting(WADA_Version::FT_ID_RET);
        $norm = false;
        $lt = ($maxRetention < 100000);
        $ltSubMonth = ($maxRetention <= 31);
        if(($retentionPeriodInDays > $maxRetention) || (($retentionPeriodInDays == 0) && $lt)){
            $norm = true;
            if($ltSubMonth){
                $retentionPeriodNum = $maxRetention;
                $retentionPeriodUnit = 'd';
            }else{
                $retentionPeriodNum = floor($maxRetention/12);
                $retentionPeriodUnit = 'm';
            }
            WADA_Log::debug('Changed to num: '.$retentionPeriodNum.' unit '.$retentionPeriodUnit);
        }
    ?>
        <div class="wrap">
            <h1><?php _e('Settings', 'wp-admin-audit'); ?></h1>
                <h2 class="nav-tab-wrapper">
                    <a href="<?php echo admin_url('/admin.php?page=wp-admin-audit-settings'); ?>&tab=tab-general" class="nav-tab<?php echo ( $tab === 'tab-general' ? ' nav-tab-active' : '' ); ?>" data-slug="tab-general"><?php esc_html_e( 'General', 'wp-admin-audit' ); ?></a>
                    <a href="<?php echo admin_url('/admin.php?page=wp-admin-audit-settings'); ?>&tab=tab-sensors" class="nav-tab<?php echo ( $tab === 'tab-sensors' ? ' nav-tab-active' : '' ); ?>" data-slug="tab-sensors"><?php esc_html_e( 'Sensors', 'wp-admin-audit' ); ?></a>
                    <a href="<?php echo admin_url('/admin.php?page=wp-admin-audit-settings'); ?>&tab=tab-extensi" class="nav-tab<?php echo ( $tab === 'tab-extensi' ? ' nav-tab-active' : '' ); ?>" data-slug="tab-extensi"><?php esc_html_e( 'Extensions', 'wp-admin-audit' ); ?></a>
                    <a href="<?php echo admin_url('/admin.php?page=wp-admin-audit-settings'); ?>&tab=tab-useracc" class="nav-tab<?php echo ( $tab === 'tab-useracc' ? ' nav-tab-active' : '' ); ?>" data-slug="tab-useracc"><?php esc_html_e( 'User accounts', 'wp-admin-audit' ); ?></a>
                    <a href="<?php echo admin_url('/admin.php?page=wp-admin-audit-settings'); ?>&tab=tab-replica" class="nav-tab<?php echo ( $tab === 'tab-replica' ? ' nav-tab-active' : '' ); ?>" data-slug="tab-replica"><?php esc_html_e( 'Replication', 'wp-admin-audit' ); ?></a>
                    <a href="<?php echo admin_url('/admin.php?page=wp-admin-audit-settings'); ?>&tab=tab-integra" class="nav-tab<?php echo ( $tab === 'tab-integra' ? ' nav-tab-active' : '' ); ?>" data-slug="tab-integra"><?php esc_html_e( 'Integrations', 'wp-admin-audit' ); ?></a>
                    <?php
                    /*  */
                    ?>
                </h2>
                <div class="nav-tab-content<?php echo ( $tab === 'tab-general' ? ' nav-tab-content-active' : '' ); ?>" id="nav-tab-content-tab-general">
                    <form id="<?php echo self::VIEW_IDENTIFIER; ?>" method="post">
                        <?php wp_nonce_field(self::VIEW_IDENTIFIER); ?>
                        <input type="hidden" name="page" value="<?php echo esc_attr($this->getCurrentPage()); ?>" />
                        <input type="hidden" name="form-submitted" value="general" />
                        <table class="form-table wada-settings-table">
                            <tbody>
                                <tr>
                                    <th scope="row"><label><?php
                                            $ltInfo = ' <br/><em class="wada-greyed-out" title="'.esc_attr__('You need to upgrade your WP Admin Audit product edition to get a higher or unlimited retention period', 'wp-admin-audit').'">'.sprintf(__('Maximum', 'wp-admin-audit').': '.__('%d days', 'wp-admin-audit'), intval($maxRetention)).'</em>'
                                            .'<br/><span class="available-in-pro-edition"><a href="https://wpadminaudit.com/pricing?utm_source=wada-plg&utm_medium=referral&utm_campaign=available-pro&utm_content=retention" target="_blank">'.esc_html( 'Update to increase the retention period', 'wp-admin-audit' ).'</a></span>';
                                            echo esc_html($periodNumMeta->label); echo ($lt ? $ltInfo : ''); ?></label></th>
                                    <td><fieldset><?php
                                            WADA_HtmlUtils::boolToggleField('unlimited_retention', __('Keep event log indefinitely', 'wp-admin-audit'), $retentionPeriodNum == 0, ($lt ? array('input_class' => 'wada-hidden', 'label_class' => 'wada-hidden') : array()));
                                            ?></fieldset><fieldset class="limited_retention"><label class="inline-nomargin-middle" style="padding-right:5px"><?php _e('Keep events for:', 'wp-admin-audit'); ?></label><?php
                                            WADA_HtmlUtils::inputField(WADA_Settings::EVENT_LOG_RETENTION_PERIOD_NUM, '', $retentionPeriodNum, array('omit_label'=>true, 'input_class' => 'small-text inline-nomargin-middle', 'id'=>('setting'.WADA_Settings::EVENT_LOG_RETENTION_PERIOD_NUM)));
                                            WADA_HtmlUtils::hiddenField('setting-prior-'.WADA_Settings::EVENT_LOG_RETENTION_PERIOD_NUM, $retentionPeriodNum);
                                            WADA_HtmlUtils::hiddenField('retention-days-on-file', $retentionPeriodInDays);
                                            $disOpt = array_merge((($norm||$lt) ? array('y') : array()), ((($norm||$lt) && $ltSubMonth) ? array('m') : array()));
                                            WADA_Log::debug('disOpt: '.print_r($disOpt, true));
                                            WADA_HtmlUtils::selectField(WADA_Settings::EVENT_LOG_RETENTION_PERIOD_UNIT, '', $retentionPeriodUnit, $periodUnitMeta->selectOptions, $disOpt, false, array('omit_label'=>true,'id'=>('setting'.WADA_Settings::EVENT_LOG_RETENTION_PERIOD_UNIT)));
                                            echo '<span class="hTip" title="'.esc_attr__('Determines how long the records should be kept in the event log (before they are deleted)', 'wp-admin-audit').'"><span class="dashicons dashicons-info"></span></span>';
                                            echo '<div id="retentionWarning" class="notice notice-warning" style="display: none;padding:10px;">'.__('The new retention period is smaller than the previous one. If you save the settings, all events before the new retention period will be deleted', 'wp-admin-audit').'</div>';
                                            ?></fieldset>
                                        <?php if(isset($_GET['cleanup'])): ?><a id="cleanupEventLog" href="#"><?php _e('Cleanup event log', 'wp-admin-audit'); ?></a></td><?php endif; ?>
                                </tr>
                                <?php WADA_Settings::renderSettingField(WADA_Settings::WIDGET_LAST_ACTIVITIES_ENABLED); ?>
                                <?php WADA_Settings::renderSettingField(WADA_Settings::WIDGET_LAST_ACTIVITIES_NR_ITEMS, true, true, true, true, array('input_class' => 'small-text')); ?>
                                <?php WADA_Settings::renderSettingField(WADA_Settings::WIDGET_LOGIN_ATTEMPTS_ENABLED); ?>
                                <?php WADA_Settings::renderSettingField(WADA_Settings::ANONYMIZE_IP_ADDRESS); ?>
                                <?php WADA_Settings::renderSettingField(WADA_Settings::DATE_FORMAT_DATE_ONLY); ?>
                                <?php WADA_Settings::renderSettingField(WADA_Settings::DATE_FORMAT_DATE_TIME); ?>
                                <?php WADA_Settings::renderSettingField(WADA_Settings::DELETE_DB_DATA_ON_UNINSTALL); ?>
                                <?php WADA_Settings::renderSettingField(WADA_Settings::LOGGING_LEVEL); ?>
                                <?php WADA_Settings::renderSettingField(WADA_Settings::LOGGING_LOG_FILE_WARNING_SIZE_MB, true, true, true, true, array('input_class' => 'small-text')); ?>
                                <?php WADA_Settings::renderSettingField(WADA_Settings::LOGGING_FORCED); ?>
                            </tbody>
                        </table>
                        <p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="<?php esc_attr_e('Save general settings', 'wp-admin-audit'); ?>"></p>
                    </form>
                </div>

                <div class="nav-tab-content<?php echo ( $tab === 'tab-sensors' ? ' nav-tab-content-active' : '' ); ?>" id="nav-tab-content-tab-sensors">
                    <?php
                    $view = new WADA_View_Sensors(array(
                        'subview-mode' => true,
                        'load-only-via-ajax' => true
                    ));
                    $view->execute();
                    ?>
                </div>

                <div class="nav-tab-content<?php echo ( $tab === 'tab-extensi' ? ' nav-tab-content-active' : '' ); ?>" id="nav-tab-content-tab-extensi">
                    <?php

                    /*  */


                    /* @@REMOVE_START_WADA_enterprise@@ */
                    /* @@REMOVE_START_WADA_business@@ */
                    /* @@REMOVE_START_WADA_startup@@ */

                    ?>
                    <h3 class="wp-heading-inline"><?php echo __('Extensions', 'wp-admin-audit'); ?>
                        <span class="available-in-pro-edition"><?php echo ' &mdash; '.'<a href="https://wpadminaudit.com/pricing?utm_source=wada-plg&utm_medium=referral&utm_campaign=available-pro&utm_content=extensions" target="_blank">'.sprintf(__('Available in %s', 'wp-admin-audit'), WADA_Version::getMinV4Ft(WADA_Version::FT_ID_EXT)).'</a>'; ?></span>
                    </h3>
                    <div>
                        <?php _e('Record important events in third-party plugins with WP Admin Audit extensions.', 'wp-admin-audit'); ?>
                    </div>
                    <div>
                        <?php _e('Click the button below to check which of your plugins are supported.', 'wp-admin-audit'); ?>
                    </div>
                    <?php
                    $triggerId = 'settings-init-load';
                    $cssClass = 'wada-ui-button button button-primary';
                    $buttonLabel = __('Check supported extensions', 'wp-admin-audit');
                    $onClick = "var event = arguments[0] || window.event; if( !confirm( '".esc_js(__('This will send a list of the plugins you have installed to our server. Continue?', 'wp-admin-audit'))."' ) ) { console.log('stop'); event.stopPropagation(); event.preventDefault(); }";
                    $triggerButtonHtml = '<span id="'.$triggerId.'" ><button type="button" onClick="'.$onClick.'" class="'.$cssClass.'">'.$buttonLabel.'</button></span>';
                    $view = new WADA_View_Extensions(array(
                        'subview-mode' => true,
                        'load-only-via-ajax' => true,
                        'load-only-after-trigger' => true,
                        'trigger-html' => $triggerButtonHtml,
                        'trigger-selector' => '#'.$triggerId,
                        'no-sections' => true,
                        'no-searchbar' => true
                    ));
                    $view->execute();
                    ?>
                    <?php

                    /* @@REMOVE_END_WADA_startup@@ */
                    /* @@REMOVE_END_WADA_business@@ */
                    /* @@REMOVE_END_WADA_enterprise@@ */

                    ?>
                </div>

                <div class="nav-tab-content<?php echo ( $tab === 'tab-useracc' ? ' nav-tab-content-active' : '' ); ?>" id="nav-tab-content-tab-useracc">
                    <form id="<?php echo self::VIEW_IDENTIFIER; ?>" method="post">
                        <?php wp_nonce_field(self::VIEW_IDENTIFIER); ?>
                        <input type="hidden" name="page" value="<?php echo esc_attr($this->getCurrentPage()); ?>" />
                        <input type="hidden" name="form-submitted" value="useracc" />

                        <?php
                        /* @@REMOVE_START_WADA_startup@@ */
                        /*  */
                        /* @@REMOVE_END_WADA_startup@@ */
                        ?>

                        <?php
                        /* @@REMOVE_START_WADA_enterprise@@ */
                        /* @@REMOVE_START_WADA_business@@ */
                        // Set "demo" values to show in product editions where this is not available
                        WADA_Settings::setUserAccountAutoAdjustEnableStatus(1);
                        WADA_Settings::setUserAccountAutoAdjustRolesInScope(array('administrator'));
                        WADA_Settings::setUserAccountAutoAdjustInactiveSinceDaysCriteria(30);
                        WADA_Settings::setUserAccountAutoAdjustChangeToRole('subscriber');
                        ?>
                        <table class="form-table wada-settings-table">
                            <tbody>
                            <tr><td colspan="2"><h3><?php _e('Auto-adjust / auto-disable inactive accounts', 'wp-admin-audit'); ?>
                                        <span class="available-in-pro-edition"><?php echo ' &mdash; '.'<a href="https://wpadminaudit.com/pricing?utm_source=wada-plg&utm_medium=referral&utm_campaign=available-pro&utm_content=uac-auto-adjust" target="_blank">'.sprintf(__('Available in %s', 'wp-admin-audit'), WADA_Version::getMinV4Ft(WADA_Version::FT_ID_UAC_AUTO_A)).'</a>'; ?></span>
                                    </h3></td></tr>
                            <?php WADA_Settings::renderDisabledSettingField(WADA_Settings::UAC_AUTO_ADJUST_ENABLED); ?>
                            <?php WADA_Settings::renderDisabledSettingField(WADA_Settings::UAC_AUTO_ADJUST_ROLES_IN_SCOPE); ?>
                            <?php WADA_Settings::renderDisabledSettingField(WADA_Settings::UAC_AUTO_ADJUST_INACTIVE_SINCE_DAYS_CRITERIA); ?>
                            <?php WADA_Settings::renderDisabledSettingField(WADA_Settings::UAC_AUTO_ADJUST_CHANGE_TO_ROLE); ?>
                            </tbody>
                        </table>
                        <?php
                        WADA_Settings::setUserAccountAutoAdjustEnableStatus(0); // disable again
                        /* @@REMOVE_END_WADA_business@@ */
                        /* @@REMOVE_END_WADA_enterprise@@ */
                        ?>




                        <?php
                        /* @@REMOVE_START_WADA_startup@@ */
                        /*  */
                        /* @@REMOVE_END_WADA_startup@@ */
                        ?>

                        <?php
                        /* @@REMOVE_START_WADA_enterprise@@ */
                        /* @@REMOVE_START_WADA_business@@ */
                        // Set "demo" values to show in product editions where this is not available
                        WADA_Settings::setUserAccountEnforcePwChangeEnableStatus(1);
                        WADA_Settings::setUserAccountEnforcePwChangeEveryXDays(90);
                        WADA_Settings::setUserAccountEnforcePwChangeRolesInScope(array('administrator'));
                        WADA_Settings::setUserAccountEnforcePwChangeNotifications(array('30','7','1'));
                        ?>
                        <table class="form-table wada-settings-table">
                            <tbody>
                            <tr><td colspan="2"><h3><?php _e('Enforce periodic password changes', 'wp-admin-audit'); ?>
                                        <span class="available-in-pro-edition"><?php echo ' &mdash; '.'<a href="https://wpadminaudit.com/pricing?utm_source=wada-plg&utm_medium=referral&utm_campaign=available-pro&utm_content=uac-enf-pwc" target="_blank">'.sprintf(__('Available in %s', 'wp-admin-audit'), WADA_Version::getMinV4Ft(WADA_Version::FT_ID_UAC_ENF_PWC)).'</a>'; ?></span>
                                    </h3></td></tr>
                            <?php WADA_Settings::renderDisabledSettingField(WADA_Settings::UAC_ENF_PW_CHG_ENABLED); ?>
                            <?php WADA_Settings::renderDisabledSettingField(WADA_Settings::UAC_ENF_PW_CHG_EVERY_X_DAYS); ?>
                            <?php WADA_Settings::renderDisabledSettingField(WADA_Settings::UAC_ENF_PW_CHG_ROLES_IN_SCOPE); ?>
                            <?php WADA_Settings::renderDisabledSettingField(WADA_Settings::UAC_ENF_PW_CHG_NOTIFICATIONS); ?>
                            </tbody>
                        </table>
                        <?php
                        WADA_Settings::setUserAccountEnforcePwChangeEnableStatus(0); // disable again
                        /* @@REMOVE_END_WADA_business@@ */
                        /* @@REMOVE_END_WADA_enterprise@@ */
                        ?>
                        <p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="<?php esc_attr_e('Save user account settings', 'wp-admin-audit'); ?>"></p>
                    </form>
                </div>

                <div class="nav-tab-content<?php echo ( $tab === 'tab-replica' ? ' nav-tab-content-active' : '' ); ?>" id="nav-tab-content-tab-replica">
                    <div class="wada-settings-desc"><?php _e('To increase security and for backup purposes, you can forward the events to an external logging provider.', 'wp-admin-audit'); ?></div>
                    <form id="<?php echo self::VIEW_IDENTIFIER; ?>" method="post">
                        <?php wp_nonce_field(self::VIEW_IDENTIFIER); ?>
                        <input type="hidden" name="page" value="<?php echo esc_attr($this->getCurrentPage()); ?>" />
                        <input type="hidden" name="form-submitted" value="replica" />

                        <?php
                        /* @@REMOVE_START_WADA_startup@@ */
                        /*  */
                        /* @@REMOVE_END_WADA_startup@@ */
                        ?>

                        <?php
                        /* @@REMOVE_START_WADA_enterprise@@ */
                        /* @@REMOVE_START_WADA_business@@ */
                        // Set "demo" values to show in product editions where this is not available
                        WADA_Settings::setReplicationToLogglyEnableStatus(1);
                        WADA_Settings::setReplicationToLogglyToken('xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx');
                        WADA_Settings::setReplicationToLogglyTags('wp-admin-audit, wordpress');
                        WADA_Settings::setReplicationToLogtailEnableStatus(1);
                        WADA_Settings::setReplicationToLogtailToken('AbcDEFghIjkLmNOpQrstUv1w');
                        WADA_Settings::setIntegrationForLogsnagEnableStatus(1);
                        ?>
                        <h3><?php esc_html_e( 'Replication', 'wp-admin-audit' ); ?>
                            <span class="available-in-pro-edition"><?php echo ' &mdash; '.'<a href="https://wpadminaudit.com/pricing?utm_source=wada-plg&utm_medium=referral&utm_campaign=available-pro&utm_content=replicate" target="_blank">'.sprintf(__('Available in %s', 'wp-admin-audit'), WADA_Version::getMinV4Ft(WADA_Version::FT_ID_REPLICATE)).'</a>'; ?></span>
                        </h3>
                        <table class="form-table wada-settings-table">
                            <tbody>
                            <tr><td colspan="2"><h3><?php _e('Loggly', 'wp-admin-audit'); ?></h3></td></tr>
                            <?php WADA_Settings::renderDisabledSettingField(WADA_Settings::REPL_LOGGLY_ENABLED); ?>
                            <?php WADA_Settings::renderDisabledSettingField(WADA_Settings::REPL_LOGGLY_TOKEN); ?>
                            <?php WADA_Settings::renderDisabledSettingField(WADA_Settings::REPL_LOGGLY_TAGS); ?>
                            </tbody>
                        </table>
                        <table class="form-table wada-settings-table">
                            <tbody>
                            <tr><td colspan="2"><h3><?php _e('Better Stack (formerly: Logtail)', 'wp-admin-audit'); ?></h3></td></tr>
                            <?php WADA_Settings::renderDisabledSettingField(WADA_Settings::REPL_LOGTAIL_ENABLED); ?>
                            <?php WADA_Settings::renderDisabledSettingField(WADA_Settings::REPL_LOGTAIL_TOKEN); ?>
                            </tbody>
                        </table>
                        <?php
                        WADA_Settings::setReplicationToLogglyEnableStatus(0); // disable again
                        WADA_Settings::setReplicationToLogglyToken('');
                        WADA_Settings::setReplicationToLogtailEnableStatus(0); // disable again
                        WADA_Settings::setReplicationToLogtailToken('');
                        /* @@REMOVE_END_WADA_business@@ */
                        /* @@REMOVE_END_WADA_enterprise@@ */
                        ?>

                        <p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="<?php esc_attr_e('Save replication settings', 'wp-admin-audit'); ?>"></p>
                    </form>
                </div>


            <div class="nav-tab-content<?php echo ( $tab === 'tab-integra' ? ' nav-tab-content-active' : '' ); ?>" id="nav-tab-content-tab-integra">
                <div class="wada-settings-desc"><?php _e('Manage your third-party integrations here', 'wp-admin-audit'); ?></div>
                <form id="<?php echo self::VIEW_IDENTIFIER; ?>" method="post">
                    <?php wp_nonce_field(self::VIEW_IDENTIFIER); ?>
                    <input type="hidden" name="page" value="<?php echo esc_attr($this->getCurrentPage()); ?>" />
                    <input type="hidden" name="form-submitted" value="integra" />

                    <?php
                    /*  */
                    ?>

                    <?php
                    /* @@REMOVE_START_WADA_enterprise@@ */
                    /* @@REMOVE_START_WADA_business@@ */
                    /* @@REMOVE_START_WADA_startup@@ */
                    // Set "demo" values to show in product editions where this is not available
                    WADA_Settings::setIntegrationForLogsnagEnableStatus(1);
                    WADA_Settings::setIntegrationForLogsnagToken('1a2b3cd45e67890f123456789a123abc');
                    WADA_Settings::setIntegrationForLogsnagProject('my-wp-events');
                    ?>
                    <h3><?php _e( 'Notifications', 'wp-admin-audit' ); ?>
                        <span class="available-in-pro-edition"><?php echo ' &mdash; '.'<a href="https://wpadminaudit.com/pricing?utm_source=wada-plg&utm_medium=referral&utm_campaign=available-pro&utm_content=integ-noti" target="_blank">'.sprintf(__('Available in %s', 'wp-admin-audit'), WADA_Version::getMinV4Ft(WADA_Version::FT_ID_NOTI)).'</a>'; ?></span>
                    </h3>
                    <table class="form-table wada-settings-table">
                        <tbody>
                        <tr><td colspan="2"><h4><?php _e('Logsnag', 'wp-admin-audit'); ?></h4></td></tr>
                        <tr><td colspan="2"><?php _e( 'Receive push notifications via Logsnag. You can use it as a notification target when you setup a new notification.', 'wp-admin-audit' ); ?></td></tr>
                        <?php WADA_Settings::renderDisabledSettingField(WADA_Settings::INTEG_LOGSNAG_ENABLED); ?>
                        <?php WADA_Settings::renderDisabledSettingField(WADA_Settings::INTEG_LOGSNAG_TOKEN); ?>
                        <?php WADA_Settings::renderDisabledSettingField(WADA_Settings::INTEG_LOGSNAG_PROJECT); ?>
                        </tbody>
                    </table>
                    <?php
                    WADA_Settings::setIntegrationForLogsnagEnableStatus(0); // disable again
                    WADA_Settings::setIntegrationForLogsnagToken('');
                    /* @@REMOVE_END_WADA_startup@@ */
                    /* @@REMOVE_END_WADA_business@@ */
                    /* @@REMOVE_END_WADA_enterprise@@ */
                    ?>

                    <p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="<?php esc_attr_e('Save integration settings', 'wp-admin-audit'); ?>"></p>
                </form>
            </div>

            <?php
            /*  */
            ?>

        </div>
    <?php
    }


    function loadJavascriptActions(){ ?>
        <script type="text/javascript">
            let keyInputId = '#setting<?php echo intval(WADA_Settings::LICENSE_KEY); ?>';  // global
            function afterTabChange(){
                updateHideLikes(); // things hidden in other tabs are not affected, so make sure current tab is in order when changing to it
            }
            function updateHideLikes(){
                jQuery("[data-hide-like]").each(function() {
                    let controllingId = '#'+jQuery(this).attr('data-hide-like-controlling');
                    let valueToShow = jQuery(this).attr('data-hide-like-value-to-show');
                    let isCurrentlyVisible = jQuery(this).is(":visible");
                    let controllingElement = jQuery(controllingId);
                    let controllingElementType = controllingElement[0].type;
                    let actualValue = null;

                    if(controllingElementType == 'checkbox'){
                        let isChecked = jQuery(controllingElement[0]).is(':checked');
                        if(isChecked){
                            actualValue = jQuery(controllingId).val();
                        }
                    }else if(controllingElementType == 'select'){
                        actualValue = jQuery(controllingId).val();
                    }

                    if(actualValue == valueToShow && !isCurrentlyVisible){
                        jQuery(this).closest('tr').show();
                    }else if(actualValue != valueToShow && isCurrentlyVisible){
                        jQuery(this).closest('tr').hide();
                    }
                });
            }
            (function ($) {
                function onUnlimitedRetentionToggle(){
                    let unlimitedRetentionActive = $('#unlimited_retention').prop('checked');
                    let unitInputId = '#setting<?php echo wp_json_encode(WADA_Settings::EVENT_LOG_RETENTION_PERIOD_UNIT); ?>';
                    let retentionInputId = '#setting<?php echo wp_json_encode(WADA_Settings::EVENT_LOG_RETENTION_PERIOD_NUM); ?>';
                    let priorRetentionInputId = '#setting-prior-<?php echo wp_json_encode(WADA_Settings::EVENT_LOG_RETENTION_PERIOD_NUM); ?>';
                    let retentionNum = jQuery(retentionInputId).val();
                    let priorRetentionNum = jQuery(priorRetentionInputId).val();
                    if(unlimitedRetentionActive){
                        jQuery('label[for="unlimited_retention"]').removeClass('wada-greyed-out');
                        $('.limited_retention').hide();
                        jQuery(priorRetentionInputId).val(retentionNum);
                        jQuery(retentionInputId).val(0);
                    }else{
                        if(priorRetentionNum == 0){ // come up with some reasonable defaults
                            if(jQuery(unitInputId).val() == 'd'){
                                priorRetentionNum = 30;
                            }else{
                                priorRetentionNum = 1;
                            }
                        }
                        jQuery(retentionInputId).val(priorRetentionNum);
                        $('.limited_retention').show();
                        jQuery('label[for="unlimited_retention"]').addClass('wada-greyed-out');
                    }
                }
                function onRetentionPeriodUpdate(){
                    let numInputId = '#setting<?php echo wp_json_encode(WADA_Settings::EVENT_LOG_RETENTION_PERIOD_NUM); ?>';
                    let unitInputId = '#setting<?php echo wp_json_encode(WADA_Settings::EVENT_LOG_RETENTION_PERIOD_UNIT); ?>';
                    let retentionNum = jQuery(numInputId).val();
                    let retentionPeriodUnit = jQuery(unitInputId).val();
                    let newRetentionInDays = getRetentionDays(retentionNum, retentionPeriodUnit);
                    let retentionDaysOnFile = parseInt(jQuery('#retention-days-on-file').val());
                    console.log('new period: '+newRetentionInDays);
                    console.log('on file is: '+retentionDaysOnFile);
                    let isPeriodShortened = false;
                    if(retentionDaysOnFile != newRetentionInDays){
                        if(retentionDaysOnFile == 0){ // prev. unlimited, now limited
                            isPeriodShortened = true;
                        }
                        if(newRetentionInDays > 0 && newRetentionInDays < retentionDaysOnFile){ // new period smaller than previous
                            isPeriodShortened = true;
                        }
                    }
                    if(isPeriodShortened){
                        $('#retentionWarning').show();
                    }else{
                        $('#retentionWarning').hide();
                    }
                }
                $('#unlimited_retention').on('change', function (e) {
                    onUnlimitedRetentionToggle();
                    onRetentionPeriodUpdate();
                });
                $('#setting<?php echo wp_json_encode(WADA_Settings::EVENT_LOG_RETENTION_PERIOD_NUM); ?>').on('change', function (e) {
                    onRetentionPeriodUpdate();
                });
                $('#setting<?php echo wp_json_encode(WADA_Settings::EVENT_LOG_RETENTION_PERIOD_UNIT); ?>').on('change', function (e) {
                    onRetentionPeriodUpdate();
                });
                $('input, select').on('change', function(e){
                   updateHideLikes();
                });
                $('#activate-license').on('click', function (e) {
                    e.preventDefault();
                    activateLicense();
                });
                $('#deactivate-license').on('click', function (e) {
                    e.preventDefault();
                    deactivateLicense();
                });
                $('#check-license-status').on('click', function (e) {
                    e.preventDefault();
                    checkLicenseStatus();
                });                
                $('#cleanupEventLog').on('click', function (e) {
                    e.preventDefault();
                    let data = {};
                    jQuery.ajax({
                            url: ajaxurl,
                            data: jQuery.extend({
                            _wpnonce: jQuery('#_wpnonce').val(),
                            action: '_wada_ajax_cleanup_event_log'
                    }, data),
                        success: function (response) {
                            var resp = jQuery.parseJSON(response);
                            if(resp && resp.success){
                                console.log(data);
                            }else{
                                console.log(response);
                                console.log(resp);
                            }
                        }
                    });
                });

                function getRetentionDays(retentionNum, retentionPeriodUnit){
                    retentionNum = Math.abs(parseInt(retentionNum));
                    let res = 0;
                    switch(retentionPeriodUnit){
                        case 'd':
                            res = retentionNum;
                            break;
                        case 'm':
                            res = retentionNum*31;
                            break;
                        case 'y':
                            res = retentionNum*365;
                            break;
                        default:
                            res = retentionNum;
                    }
                    return res;
                }
                
                function getKey(){
                    let keyStr = $(keyInputId).val();
                    if(!keyStr || keyStr.length < 3){
                        return null; // key is not (really) there
                    }
                    if(keyStr){
                        keyStr = keyStr.trim(); // store back without whitespace BS
                    }
                    $(keyInputId).val(keyStr);
                    return keyStr;
                }

                function setKey(keyStr){
                    if(keyStr){
                        keyStr = keyStr.trim(); // store back without whitespace BS
                    }
                    $(keyInputId).val(keyStr);
                }

                function initLicenseRequest(){
                    $('#license-message').html('&nbsp;');
                    $('.spinner').addClass('is-active').show();
                    $('input.license-btn').attr('disabled', 'disabled');
                    $(keyInputId).attr('readonly', 'readonly');
                    $('#license-details').hide();
                }

                let processLicenseKeyUpdate = function (response){
                    $('.spinner').hide().removeClass('is-active');
                    console.log(response);
                    let data = jQuery.parseJSON(response);
                    console.log(data);
                    if(data){
                        $('#check-license-status').hide();
                        if(data.error){
                            $('#license-message').html(data.message+'<br/>'+data.error);
                        }else{
                            $('#license-message').html(data.message);
                        }
                        setKey(data.key);

                        if(data.license){
                            $('#activate-license').hide();
                            $('#deactivate-license').show();
                            $('#license-details').show();
                            let licenseStatus = data.license.licenseStatus;
                            $('#license-detail-status').removeClass('wada-green-highlight').removeClass('wada-orange-highlight');
                            if(licenseStatus){
                                if(licenseStatus == 'active'){
                                    licenseStatus = '<?php echo(esc_js(__('Active', 'wp-admin-audit'))); ?>';
                                    $('#license-detail-status').addClass('wada-green-highlight');
                                }else if(licenseStatus == 'expired'){
                                    licenseStatus = '<?php echo(esc_js(__('Expired', 'wp-admin-audit'))); ?>';
                                    $('#license-detail-status').addClass('wada-orange-highlight');
                                }
                            }else{
                                licenseStatus = '<?php echo(esc_js(__('Invalid', 'wp-admin-audit'))); ?>';
                                $('#license-detail-status').addClass('wada-orange-highlight');
                            }
                            $('#license-detail-status').html(licenseStatus);
                            if(data.license.licenseStatus){
                                let color = 'grey';
                                let boldRule = '';
                                if(data.license.daysLeft <= 30){
                                    color = 'orange';
                                    boldRule = 'font-weight:bold;'
                                }
                                if(data.license.daysLeft <= 10){
                                    color = 'red';
                                }

                                let expiresOnText = data.license.expiresOn+' <span style="color:'+color+';'+boldRule+'">('+data.license.daysLeftText+')</span>';

                                let isExpiredText = '<?php echo(esc_js(__('No', 'wp-admin-audit'))); ?>';
                                $('#license-detail-expired').removeClass('wada-error');
                                if(data.license.isExpired){
                                    isExpiredText = '<?php echo(esc_js(__('Yes', 'wp-admin-audit'))); ?>';
                                    $('#license-detail-expired').addClass('wada-error');
                                }

                                $('#license-detail-instance').removeClass('wada-license-instance-test').removeClass('wada-license-instance-prod');
                                let instanceDetailHtml = '';
                                if(data.license.isTestInstall === 1){
                                    $('#license-detail-instance').addClass('wada-license-instance-test');
                                    instanceDetailHtml = '<?php echo(esc_js(__('Test system', 'wp-admin-audit'))); ?>';
                                    if(data.license.mainInstall){
                                        instanceDetailHtml += ' (<?php echo(esc_js(__('Production system', 'wp-admin-audit'))); ?>: ';
                                        instanceDetailHtml += data.license.mainInstall;
                                        instanceDetailHtml += ')';
                                    }
                                }else{
                                    $('#license-detail-instance').addClass('wada-license-instance-prod');
                                    instanceDetailHtml = '<?php echo(esc_js(__('Production system', 'wp-admin-audit'))); ?>';
                                }
                                $('#license-detail-instance').html(instanceDetailHtml);

                                $('#license-detail-instance-row').show();
                                $('#license-detail-expired').html(isExpiredText);
                                $('#license-detail-valid-from').html(data.license.validFrom);
                                $('#license-detail-expires-on').html(expiresOnText);
                                $('#license-detail-expired-row').show();
                                $('#license-detail-valid-from-row').show();
                                $('#license-detail-expires-on-row').show();
                                $('#check-license-status').show();
                                $(keyInputId).attr('readonly', 'readonly');
                            }else{
                                $('#activate-license').show();
                                $('#deactivate-license').hide();
                                $('#license-detail-instance-row').hide();
                                $('#license-detail-expired-row').hide();
                                $('#license-detail-valid-from-row').hide();
                                $('#license-detail-expires-on-row').hide();
                                $(keyInputId).removeAttr('readonly');
                            }
                        }else{
                            $('#activate-license').show();
                            $('#deactivate-license').hide();
                            $('#license-details').hide();
                            $(keyInputId).removeAttr('readonly');
                        }
                    }else{
                        // something went wrong saving, revert UI
                        $(keyInputId).removeAttr('readonly');
                    }
                    $('input.license-btn').removeAttr('disabled');
                };

                function makeKeyActionAjaxCall(actionStr){
                    initLicenseRequest();
                    jQuery.ajax({
                        url: ajaxurl,
                        data: {
                            key: getKey(),
                            _wpnonce: jQuery('#wada-keyaction-nonce').val(),
                            action: actionStr
                        },
                        success: processLicenseKeyUpdate
                    });
                }

                function activateLicense(){
                    makeKeyActionAjaxCall('_wada_ajax_activate_key');
                }

                function deactivateLicense(){
                    makeKeyActionAjaxCall('_wada_ajax_deactivate_key');
                }
                
                function checkLicenseStatus(){
                    let key = getKey();
                    if(key){
                        makeKeyActionAjaxCall('_wada_ajax_check_key_status');
                    }else{
                        $('#activate-license').show();
                        $('#deactivate-license').hide();
                        $('#check-license-status').hide();
                    }
                }

                onUnlimitedRetentionToggle(); // for init
                updateHideLikes(); // for init
                checkLicenseStatus(); // for init

            })(jQuery);


            jQuery(document).ready(function() {
                jQuery('#setting<?php echo esc_js(WADA_Settings::UAC_AUTO_ADJUST_ROLES_IN_SCOPE); ?>, #setting<?php echo esc_js(WADA_Settings::UAC_ENF_PW_CHG_ROLES_IN_SCOPE); ?>').select2({
                    width: 'auto',
                    closeOnSelect: false,
                    multiple: true,
                    dropdownAutoWidth: true,
                    placeholder: '<?php echo esc_js(__('Select the user role(s)', 'wp-admin-audit')); ?>'
                });
                jQuery('#setting<?php echo esc_js(WADA_Settings::UAC_ENF_PW_CHG_NOTIFICATIONS); ?>').select2({
                    width: 'auto',
                    closeOnSelect: false,
                    multiple: true,
                    dropdownAutoWidth: true,
                    placeholder: '<?php echo esc_js(__('Select days prior to send a notification in advance', 'wp-admin-audit')); ?>'
                });
            });
        </script>
        <?php
    }

}