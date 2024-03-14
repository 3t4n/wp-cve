<?php
/**
 * @copyright (C) 2021 - 2024 Holger Brandt IT Solutions
 * @license GPL2
*/

if(!defined('ABSPATH')){
    exit;
}

class WADA_Settings
{
    const DELETE_DB_DATA_ON_UNINSTALL = 1;
    const DB_VERSION = 2;
    const LOGGING_LEVEL = 3;
    const LOGGING_LOG_FILE_WARNING_SIZE_MB = 4;
    const LOGGING_FORCED = 5;
    const EVENT_LOG_RETENTION_PERIOD_NUM = 6;
    const EVENT_LOG_RETENTION_PERIOD_UNIT = 7;
    const ANONYMIZE_IP_ADDRESS = 8;
    const WIDGET_LAST_ACTIVITIES_ENABLED = 9;
    const WIDGET_LAST_ACTIVITIES_NR_ITEMS = 10;
    const UAC_AUTO_ADJUST_ENABLED = 11;
    const UAC_AUTO_ADJUST_ROLES_IN_SCOPE = 12;
    const UAC_AUTO_ADJUST_INACTIVE_SINCE_DAYS_CRITERIA = 13;
    const UAC_AUTO_ADJUST_CHANGE_TO_ROLE = 14;
    const UAC_ENF_PW_CHG_ENABLED = 15;
    const UAC_ENF_PW_CHG_ROLES_IN_SCOPE = 16;
    const UAC_ENF_PW_CHG_EVERY_X_DAYS = 17;
    const UAC_ENF_PW_CHG_NOTIFICATIONS = 18;
    const REPL_LOGGLY_ENABLED = 19;
    const REPL_LOGGLY_TOKEN = 20;
    const REPL_LOGGLY_TAGS = 21;
    const REPL_LOGTAIL_ENABLED = 22;
    const REPL_LOGTAIL_TOKEN = 23;
    const LICENSE_KEY = 24;
    const LICENSE_STATUS = 25;
    const WIDGET_LOGIN_ATTEMPTS_ENABLED = 26;
    const INTEG_LOGSNAG_ENABLED = 27;
    const INTEG_LOGSNAG_TOKEN = 28;
    const INTEG_LOGSNAG_PROJECT = 29;
    const DATE_FORMAT_DATE_ONLY = 30;
    const DATE_FORMAT_DATE_TIME = 31;

    public static function getMetadataForAllSettings(){
        $metaDataArray = array(
            'setting'.self::DELETE_DB_DATA_ON_UNINSTALL => (object) array(
                'settingId' => self::DELETE_DB_DATA_ON_UNINSTALL,
                'defaultVal' => '0',
                'getMethod' => 'isDeleteDatabaseDataOnUninstall',
                'setMethod' => 'setIsDeleteDatabaseDataOnUninstall',
                'externalType' => 'bool',
                'storageType' => 'int',
                'field' => 'checkbox',
                'label' => __('Delete data when uninstalling the plugin', 'wp-admin-audit'),
                'title' => __('Checking this option will remove all WP Admin Audit related data, including your event log, configuration settings etc.', 'wp-admin-audit')
            ),
            'setting'.self::DB_VERSION => (object) array(
                'settingId' => self::DB_VERSION,
                'defaultVal' => '1.2.9',
                'getMethod' => 'getDatabaseVersion',
                'setMethod' => 'setDatabaseVersion',
                'externalType' => 'string',
                'storageType' => 'string',
                'field' => 'hidden',
                'label' => __('Database version', 'wp-admin-audit'),
                'title' => __('WP Admin Audit database schema version', 'wp-admin-audit')
            ),
            'setting'.self::LOGGING_LEVEL => (object) array(
                'settingId' => self::LOGGING_LEVEL,
                'defaultVal' =>  WADA_Constants::LOG_LEVEL_INFO,
                'getMethod' => 'getLoggingLevel',
                'setMethod' => 'setLoggingLevel',
                'externalType' => 'int',
                'storageType' => 'int',
                'field' => 'select',
                'selectOptions' => array(
                    WADA_Constants::LOG_LEVEL_OFF =>  __("Disabled", 'wp-admin-audit'),
                    WADA_Constants::LOG_LEVEL_ERROR =>  __("Errors only", 'wp-admin-audit'),
                    WADA_Constants::LOG_LEVEL_INFO =>  __("Normal", 'wp-admin-audit'),
                    WADA_Constants::LOG_LEVEL_DEBUG =>  __("Max. Logging (Debug)", 'wp-admin-audit')
                ),
                'label' => __('Logging level', 'wp-admin-audit'),
                'title' => __("The logging level determines which kind of internal plugin events (e.g. an error while storing some data) should be written to WP Admin Audit's log file. Note this is independent from your site's event log tracking your admin's activities. The purpose of the log file is that you can provide it to the WP Admin Audit tech support for review in case the plugin is not working as expected.", 'wp-admin-audit'),
                'disabledSelectOptions' => array(),
                'multiSelect' => false
            ),
            'setting'.self::LOGGING_LOG_FILE_WARNING_SIZE_MB => (object) array(
                'settingId' => self::LOGGING_LOG_FILE_WARNING_SIZE_MB,
                'defaultVal' =>  '50',
                'getMethod' => 'getLogFileSizeWarningLevel',
                'setMethod' => 'setLogFileSizeWarningLevel',
                'externalType' => 'int',
                'storageType' => 'int',
                'field' => 'input',
                'label' => __('Log file warning size limit (MB)', 'wp-admin-audit'),
                'title' => __('When this size of the log file (in megabyte) is exceeded a warning message is shown', 'wp-admin-audit')
            ),
            'setting'.self::LOGGING_FORCED => (object) array(
                'settingId' => self::LOGGING_FORCED,
                'defaultVal' =>  '0',
                'getMethod' => 'isLoggingForced',
                'setMethod' => 'setIsLoggingForced',
                'externalType' => 'bool',
                'storageType' => 'int',
                'field' => 'checkbox',
                'label' => __('Force logging', 'wp-admin-audit'),
                'title' => __('Deactivates checks whether log directory is existing and log file is writable. Use with caution! This can not work e.g. if your log path is configured wrong.', 'wp-admin-audit')
            ),
            'setting'.self::EVENT_LOG_RETENTION_PERIOD_NUM => (object) array(
                'settingId' => self::EVENT_LOG_RETENTION_PERIOD_NUM,
                'defaultVal' =>  '30',
                'getMethod' => 'getRetentionPeriodNum',
                'setMethod' => 'setRetentionPeriodNum',
                'externalType' => 'int',
                'storageType' => 'int',
                'field' => 'input',
                'label' => __('Retention period', 'wp-admin-audit'),
                'title' => __('How long events should be kept in the event log before being deleted', 'wp-admin-audit')
            ),
            'setting'.self::EVENT_LOG_RETENTION_PERIOD_UNIT => (object) array(
                'settingId' => self::EVENT_LOG_RETENTION_PERIOD_UNIT,
                'defaultVal' =>  'd',
                'getMethod' => 'getRetentionPeriodUnit',
                'setMethod' => 'setRetentionPeriodUnit',
                'externalType' => 'string',
                'storageType' => 'string',
                'field' => 'select',
                'selectOptions' => array(
                    'd' =>  __("Days", 'wp-admin-audit'),
                    'm' =>  __("Months", 'wp-admin-audit'),
                    'y' =>  __("Years", 'wp-admin-audit')
                ),
                'label' => __('Retention period', 'wp-admin-audit'),
                'title' => __('How long events should be kept in the event log before being deleted', 'wp-admin-audit')
            ),
            'setting'.self::ANONYMIZE_IP_ADDRESS => (object) array(
                'settingId' => self::ANONYMIZE_IP_ADDRESS,
                'defaultVal' =>  '1',
                'getMethod' => 'isAnonymizeIPAddress',
                'setMethod' => 'setIsAnonymizeIPAddress',
                'externalType' => 'bool',
                'storageType' => 'int',
                'field' => 'checkbox',
                'label' => __('Anonymize IP Address', 'wp-admin-audit'),
                'title' => __('In the context of the data stored in the event log: removes part of the IP address (for anonymization of the user) to comply with privacy regulations', 'wp-admin-audit')
            ),
            'setting'.self::WIDGET_LAST_ACTIVITIES_ENABLED => (object) array(
                'settingId' => self::WIDGET_LAST_ACTIVITIES_ENABLED,
                'defaultVal' =>  '1',
                'getMethod' => 'isLastActivitiesWidgetEnabled',
                'setMethod' => 'setLastActivitiesWidgetEnabled',
                'externalType' => 'bool',
                'storageType' => 'int',
                'field' => 'checkbox',
                'label' => __('Show "Last Activities" widget', 'wp-admin-audit'),
                'title' => __('Enable or disable the admin dashboard widget "Last Activities"', 'wp-admin-audit')
            ),
            'setting'.self::WIDGET_LAST_ACTIVITIES_NR_ITEMS => (object) array(
                'settingId' => self::WIDGET_LAST_ACTIVITIES_NR_ITEMS,
                'defaultVal' =>  '5',
                'getMethod' => 'getLastActivitiesWidgetNrOfItems',
                'setMethod' => 'setLastActivitiesWidgetNrOfItems',
                'externalType' => 'int',
                'storageType' => 'int',
                'field' => 'input',
                'label' => __('"Last Activities" widget: show #events', 'wp-admin-audit'),
                'title' => __('Determines how many events should be displayed in the "Last Activities" widget', 'wp-admin-audit'),
                'hideLike' => (object) array('setting_controlling' => self::WIDGET_LAST_ACTIVITIES_ENABLED, 'setting_value_to_show' => '1')
            ),
            'setting'.self::UAC_AUTO_ADJUST_ENABLED => (object) array(
                'settingId' => self::UAC_AUTO_ADJUST_ENABLED,
                'defaultVal' =>  '0',
                'getMethod' => 'isUserAccountAutoAdjustEnabled',
                'setMethod' => 'setUserAccountAutoAdjustEnableStatus',
                'externalType' => 'bool',
                'storageType' => 'int',
                'field' => 'checkbox',
                'label' => __('Auto-adjust user accounts', 'wp-admin-audit'),
                'title' => __('User accounts of specific roles (e.g. administrators) can be auto-adjusted (through a user role demotion) if they are no longer used', 'wp-admin-audit')
            ),
            'setting'.self::UAC_AUTO_ADJUST_ROLES_IN_SCOPE => (object) array(
                'settingId' => self::UAC_AUTO_ADJUST_ROLES_IN_SCOPE,
                'defaultVal' =>  array('administrator'),
                'getMethod' => 'getUserAccountAutoAdjustRolesInScope',
                'setMethod' => 'setUserAccountAutoAdjustRolesInScope',
                'externalType' => 'array',
                'storageType' => 'string',
                'field' => 'select',
                'input_class' => 'select2-multiple',
                'selectOptions' => array('WADA_Settings', 'getUserAccountAutoAdjustRolesInScopeSelectOptions'),
                'multiSelect' => true,
                'label' => __('Roles in scope', 'wp-admin-audit'),
                'title' => __('The specific roles in scope for the automatic adjustment', 'wp-admin-audit'),
                'hideLike' => (object) array('setting_controlling' => self::UAC_AUTO_ADJUST_ENABLED, 'setting_value_to_show' => '1')
            ),
            'setting'.self::UAC_AUTO_ADJUST_INACTIVE_SINCE_DAYS_CRITERIA => (object) array(
                'settingId' => self::UAC_AUTO_ADJUST_INACTIVE_SINCE_DAYS_CRITERIA,
                'defaultVal' =>  '90d',
                'getMethod' => 'getUserAccountAutoAdjustInactiveSinceDaysCriteria',
                'setMethod' => 'setUserAccountAutoAdjustInactiveSinceDaysCriteria',
                'externalType' => 'int',
                'storageType' => 'int',
                'field' => 'select',
                'selectOptions' => array(
                    '7' =>  sprintf(__('%d days', 'wp-admin-audit'), 7),
                    '14' =>  sprintf(__('%d days', 'wp-admin-audit'), 14),
                    '30' =>  sprintf(__('%d days', 'wp-admin-audit'), 30),
                    '60' =>  sprintf(__('%d days', 'wp-admin-audit'), 60),
                    '90' =>  sprintf(__('%d days', 'wp-admin-audit'), 90),
                    '180' =>  sprintf(__('%d days', 'wp-admin-audit'), 180),
                    '365' =>  sprintf(__('%d days', 'wp-admin-audit'), 365),
                ),
                'label' => __('Accounts inactive since', 'wp-admin-audit'),
                'title' => __("User accounts that are inactive longer than defined will be in scope for auto-adjustment", 'wp-admin-audit'),
                'hideLike' => (object) array('setting_controlling' => self::UAC_AUTO_ADJUST_ENABLED, 'setting_value_to_show' => '1')
            ),
            'setting'.self::UAC_AUTO_ADJUST_CHANGE_TO_ROLE => (object) array(
                'settingId' => self::UAC_AUTO_ADJUST_CHANGE_TO_ROLE,
                'defaultVal' =>  'subscriber',
                'getMethod' => 'getUserAccountAutoAdjustChangeToRole',
                'setMethod' => 'setUserAccountAutoAdjustChangeToRole',
                'externalType' => 'string',
                'storageType' => 'string',
                'field' => 'select',
                'selectOptions' => array('WADA_Settings', 'getUserAccountAutoAdjustChangeToRoleSelectOptions'),
                'label' => __('Adjust identified accounts to', 'wp-admin-audit'),
                'title' => __("Users of the roles in scope being inactive for the defined period are automatically adjusted to the user role defined here", 'wp-admin-audit'),
                'hideLike' => (object) array('setting_controlling' => self::UAC_AUTO_ADJUST_ENABLED, 'setting_value_to_show' => '1')
            ),
            'setting'.self::UAC_ENF_PW_CHG_ENABLED => (object) array(
                'settingId' => self::UAC_ENF_PW_CHG_ENABLED,
                'defaultVal' =>  '0',
                'getMethod' => 'isUserAccountEnforcePwChangeEnabled',
                'setMethod' => 'setUserAccountEnforcePwChangeEnableStatus',
                'externalType' => 'bool',
                'storageType' => 'int',
                'field' => 'checkbox',
                'label' => __('Enforce periodic password changes', 'wp-admin-audit'),
                'title' => __('User accounts of specific roles (e.g. administrators) can be forced to change their password on a regular basis', 'wp-admin-audit')
            ),
            'setting'.self::UAC_ENF_PW_CHG_ROLES_IN_SCOPE => (object) array(
                'settingId' => self::UAC_ENF_PW_CHG_ROLES_IN_SCOPE,
                'defaultVal' =>  array('administrator'),
                'getMethod' => 'getUserAccountEnforcePwChangeRolesInScope',
                'setMethod' => 'setUserAccountEnforcePwChangeRolesInScope',
                'externalType' => 'array',
                'storageType' => 'string',
                'field' => 'select',
                'input_class' => 'select2-multiple',
                'selectOptions' => array('WADA_Settings', 'getUserAccountEnforcePwChangeRolesInScopeSelectOptions'),
                'multiSelect' => true,
                'label' => __('Roles in scope', 'wp-admin-audit'),
                'title' => __('The specific roles in scope for the periodic password change', 'wp-admin-audit'),
                'hideLike' => (object) array('setting_controlling' => self::UAC_ENF_PW_CHG_ENABLED, 'setting_value_to_show' => '1')
            ),
            'setting'.self::UAC_ENF_PW_CHG_EVERY_X_DAYS => (object) array(
                'settingId' => self::UAC_ENF_PW_CHG_EVERY_X_DAYS,
                'defaultVal' =>  '90',
                'getMethod' => 'getUserAccountEnforcePwChangeEveryXDays',
                'setMethod' => 'setUserAccountEnforcePwChangeEveryXDays',
                'externalType' => 'int',
                'storageType' => 'int',
                'field' => 'input',
                'label' => __('Every X days', 'wp-admin-audit'),
                'title' => __('Determines how often a user has to change the password. Enter e.g. 90 to enforce that a password has to be changed at least every 90 days.', 'wp-admin-audit'),
                'hideLike' => (object) array('setting_controlling' => self::UAC_ENF_PW_CHG_ENABLED, 'setting_value_to_show' => '1')
            ),
            'setting'.self::UAC_ENF_PW_CHG_NOTIFICATIONS => (object) array(
                'settingId' => self::UAC_ENF_PW_CHG_NOTIFICATIONS,
                'defaultVal' =>  array('30','7','1'),
                'getMethod' => 'getUserAccountEnforcePwChangeNotifications',
                'setMethod' => 'setUserAccountEnforcePwChangeNotifications',
                'externalType' => 'array',
                'storageType' => 'string',
                'field' => 'select',
                'input_class' => 'select2-multiple',
                'selectOptions' => array(
                    '1' =>  sprintf(__('one day', 'wp-admin-audit'), 1),
                    '2' =>  sprintf(__('%d days', 'wp-admin-audit'), 2),
                    '3' =>  sprintf(__('%d days', 'wp-admin-audit'), 3),
                    '7' =>  sprintf(__('%d days', 'wp-admin-audit'), 7),
                    '14' =>  sprintf(__('%d days', 'wp-admin-audit'), 14),
                    '30' =>  sprintf(__('%d days', 'wp-admin-audit'), 30),
                    '60' =>  sprintf(__('%d days', 'wp-admin-audit'), 60),
                    '90' =>  sprintf(__('%d days', 'wp-admin-audit'), 90)
                ),
                'multiSelect' => true,
                'label' => __('Send notification before expiry', 'wp-admin-audit'),
                'title' => __("Send email notification(s) before the password expires so that the user can change the password before", 'wp-admin-audit'),
                'hideLike' => (object) array('setting_controlling' => self::UAC_ENF_PW_CHG_ENABLED, 'setting_value_to_show' => '1')
            ),
            'setting'.self::REPL_LOGGLY_ENABLED => (object) array(
                'settingId' => self::REPL_LOGGLY_ENABLED,
                'defaultVal' =>  '0',
                'getMethod' => 'isReplicationToLogglyEnabled',
                'setMethod' => 'setReplicationToLogglyEnableStatus',
                'externalType' => 'bool',
                'storageType' => 'int',
                'field' => 'checkbox',
                'label' => __('Loggly', 'wp-admin-audit'),
                'title' => __('Activate replication to Loggly (https://www.loggly.com/)', 'wp-admin-audit')
            ),
            'setting'.self::REPL_LOGGLY_TOKEN => (object) array(
                'settingId' => self::REPL_LOGGLY_TOKEN,
                'defaultVal' =>  '',
                'getMethod' => 'getReplicationToLogglyToken',
                'setMethod' => 'setReplicationToLogglyToken',
                'externalType' => 'string',
                'storageType' => 'string',
                'field' => 'input',
                'placeholder' => 'xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx',
                'input_class' => 'regular-text',
                'label' => __('Customer Token', 'wp-admin-audit'),
                'title' => __("Your customer token for Loggly, see https://documentation.solarwinds.com/en/success_center/loggly/content/admin/customer-token-authentication-token.htm", 'wp-admin-audit'),
                'hideLike' => (object) array('setting_controlling' => self::REPL_LOGGLY_ENABLED, 'setting_value_to_show' => '1')
            ),
            'setting'.self::REPL_LOGGLY_TAGS => (object) array(
                'settingId' => self::REPL_LOGGLY_TAGS,
                'defaultVal' =>  'wp-admin-audit,wordpress',
                'getMethod' => 'getReplicationToLogglyTags',
                'setMethod' => 'setReplicationToLogglyTags',
                'externalType' => 'string',
                'storageType' => 'string',
                'field' => 'input',
                'placeholder' => 'wordpress,website,event',
                'input_class' => 'regular-text',
                'label' => __('Tag(s)', 'wp-admin-audit'),
                'title' => __("Comma-separated list of tags you can use for segmentation & filtering in Loggly", 'wp-admin-audit'),
                'hideLike' => (object) array('setting_controlling' => self::REPL_LOGGLY_ENABLED, 'setting_value_to_show' => '1')
            ),
            'setting'.self::REPL_LOGTAIL_ENABLED => (object) array(
                'settingId' => self::REPL_LOGTAIL_ENABLED,
                'defaultVal' =>  '0',
                'getMethod' => 'isReplicationToLogtailEnabled',
                'setMethod' => 'setReplicationToLogtailEnableStatus',
                'externalType' => 'bool',
                'storageType' => 'int',
                'field' => 'checkbox',
                'label' => __('Better Stack (formerly: Logtail)', 'wp-admin-audit'),
                'title' => __('Activate replication to Better Stack (https://betterstack.com/logs)', 'wp-admin-audit')
            ),
            'setting'.self::REPL_LOGTAIL_TOKEN => (object) array(
                'settingId' => self::REPL_LOGTAIL_TOKEN,
                'defaultVal' =>  '',
                'getMethod' => 'getReplicationToLogtailToken',
                'setMethod' => 'setReplicationToLogtailToken',
                'externalType' => 'string',
                'storageType' => 'string',
                'field' => 'input',
                'placeholder' => 'AbcDEFghIjkLmNOpQrstUv1w',
                'input_class' => 'regular-text',
                'label' => __('API Token', 'wp-admin-audit'),
                'title' => __('Create a new "HTTP" source in Better Stack (formerly: Logtail) and use the API token ("Source token").', 'wp-admin-audit'),
                'hideLike' => (object) array('setting_controlling' => self::REPL_LOGTAIL_ENABLED, 'setting_value_to_show' => '1')
            ),
            'setting'.self::LICENSE_KEY => (object) array(
                'settingId' => self::LICENSE_KEY,
                'defaultVal' =>  '',
                'getMethod' => 'getLicenseKey',
                'setMethod' => 'setLicenseKey',
                'externalType' => 'string',
                'storageType' => 'string',
                'field' => 'input',
                'placeholder' => 'wada-xxxx-xxxxxxxx-xxxxxxxx-xxxxxxxx',
                'input_class' => 'regular-text',
                'label' => __('License key', 'wp-admin-audit'),
                'title' => __('Your WP Admin Audit license key, you can find it at https://wpadminaudit.com/my-account/', 'wp-admin-audit')
            ),
            'setting'.self::LICENSE_STATUS => (object) array(
                'settingId' => self::LICENSE_STATUS,
                'defaultVal' =>  '',
                'getMethod' => 'getLicenseStatus',
                'setMethod' => 'setLicenseStatus',
                'externalType' => 'object',
                'storageType' => 'json',
                'field' => 'none'
            ),
            'setting'.self::WIDGET_LOGIN_ATTEMPTS_ENABLED => (object) array(
                'settingId' => self::WIDGET_LOGIN_ATTEMPTS_ENABLED,
                'defaultVal' =>  '1',
                'getMethod' => 'isLoginAttemptsWidgetEnabled',
                'setMethod' => 'setLoginAttemptsWidgetEnabled',
                'externalType' => 'bool',
                'storageType' => 'int',
                'field' => 'checkbox',
                'label' => __('Show "Login Attempts" widget', 'wp-admin-audit'),
                'title' => __('Enable or disable the admin dashboard widget "Login Attempts"', 'wp-admin-audit')
            ),
            'setting'.self::INTEG_LOGSNAG_ENABLED => (object) array(
                'settingId' => self::INTEG_LOGSNAG_ENABLED,
                'defaultVal' =>  '0',
                'getMethod' => 'isIntegrationForLogsnagEnabled',
                'setMethod' => 'setIntegrationForLogsnagEnableStatus',
                'externalType' => 'bool',
                'storageType' => 'int',
                'field' => 'checkbox',
                'label' => __('Logsnag', 'wp-admin-audit'),
                'title' => __('Activate integration with Logsnag (https://logsnag.com/)', 'wp-admin-audit')
            ),
            'setting'.self::INTEG_LOGSNAG_TOKEN => (object) array(
                'settingId' => self::INTEG_LOGSNAG_TOKEN,
                'defaultVal' =>  '',
                'getMethod' => 'getIntegrationForLogsnagToken',
                'setMethod' => 'setIntegrationForLogsnagToken',
                'externalType' => 'string',
                'storageType' => 'string',
                'field' => 'input',
                'placeholder' => '1a2b3cd45e67890f123456789a123abc',
                'input_class' => 'regular-text',
                'label' => __('API Token', 'wp-admin-audit'),
                'title' => __("Your API token for Logsnag, see https://app.logsnag.com/dashboard/settings/api", 'wp-admin-audit'),
                'hideLike' => (object) array('setting_controlling' => self::INTEG_LOGSNAG_ENABLED, 'setting_value_to_show' => '1')
            ),
            'setting'.self::INTEG_LOGSNAG_PROJECT => (object) array(
                'settingId' => self::INTEG_LOGSNAG_PROJECT,
                'defaultVal' =>  'my-wp-events',
                'getMethod' => 'getIntegrationForLogsnagProject',
                'setMethod' => 'setIntegrationForLogsnagProject',
                'externalType' => 'string',
                'storageType' => 'string',
                'field' => 'input',
                'placeholder' => 'my-wp-events',
                'input_class' => 'regular-text',
                'label' => __('Project', 'wp-admin-audit'),
                'title' => __("The name of the project in Logsnag into which the WordPress activities and events should be posted", 'wp-admin-audit'),
                'hideLike' => (object) array('setting_controlling' => self::INTEG_LOGSNAG_ENABLED, 'setting_value_to_show' => '1')
            ),
            'setting'.self::DATE_FORMAT_DATE_ONLY => (object) array(
                'settingId' => self::DATE_FORMAT_DATE_ONLY,
                'defaultVal' =>  'medium',
                'getMethod' => 'getDateFormatForDateOnly',
                'setMethod' => 'setDateFormatForDateOnly',
                'externalType' => 'string',
                'storageType' => 'string',
                'field' => 'select',
                'selectOptions' => array('WADA_Settings', 'getDateFormatForDateOnlySelectOptions'),
                'label' => __('Date format', 'wp-admin-audit'),
                'title' => __('The date-only format used within WP Admin Audit', 'wp-admin-audit')
            ),
            'setting'.self::DATE_FORMAT_DATE_TIME => (object) array(
                'settingId' => self::DATE_FORMAT_DATE_TIME,
                'defaultVal' =>  'short/short',
                'getMethod' => 'getDateFormatForDatetime',
                'setMethod' => 'setDateFormatForDatetime',
                'externalType' => 'string',
                'storageType' => 'string',
                'field' => 'select',
                'selectOptions' => array('WADA_Settings', 'getDateFormatForDatetimeSelectOptions'),
                'label' => __('Datetime format', 'wp-admin-audit'),
                'title' => __('The date/time format used within WP Admin Audit', 'wp-admin-audit')
            )
        );
        return (object) $metaDataArray;
    }

    public static function renderDisabledSettingField($settingId, $asTableRow=true, $useDefaultsIfNeeded=true, $echoImmediately=true, $renderChildIfApplicable=true, $options=array()){
        $options['disabled'] = true;
        return self::renderSettingField($settingId, $asTableRow, $useDefaultsIfNeeded, $echoImmediately, $renderChildIfApplicable, $options);
    }

    public static function renderSettingField($settingId, $asTableRow=true, $useDefaultsIfNeeded=true, $echoImmediately=true, $renderChildIfApplicable=true, $options=array()){
        $returnVal = null;
        $metaData = self::getMetadataForSetting($settingId);
        if($metaData){
            $name = $settingId;
            $options['title'] = property_exists($metaData, 'title') ? $metaData->title : '';
            $options['title_as_info_icon'] = array_key_exists('title_as_info_icon', $options) ? $options['title_as_info_icon'] : true;
            $options['id'] = 'setting'.$name;
            $options['data'] = array();
            $options['input_class'] = property_exists($metaData, 'input_class') ? $metaData->input_class : (array_key_exists('input_class', $options) ? $options['input_class'] : '');
            $options['placeholder'] = property_exists($metaData, 'placeholder') ? $metaData->placeholder : '';
            if($asTableRow){
                $options['render_as_table_row'] = true;
            }
            if(!$echoImmediately){
                $options['return_as_str'] = true;
            }
            if($renderChildIfApplicable && property_exists($metaData, 'hasChild')){
                $childReturnVal = self::renderSettingField($metaData->hasChild, false, $useDefaultsIfNeeded ? true : null, false);
                $options['html_suffix'] = $childReturnVal;
            }
            if(property_exists($metaData, 'hideLike')){
                $settingIdControlling = 'setting'.$metaData->hideLike->setting_controlling;
                $settingValueToShow = $metaData->hideLike->setting_value_to_show;
                $options['data']['hide-like'] = 1;
                $options['data']['hide-like-controlling'] = $settingIdControlling;
                $options['data']['hide-like-value-to-show'] = $settingValueToShow;
            }
            $label = property_exists($metaData, 'label') ? $metaData->label : null;
            $defaultValue = property_exists($metaData, 'defaultVal') ? $metaData->defaultVal : null;
            $value = property_exists($metaData, 'getMethod') && is_callable(array(__CLASS__, $metaData->getMethod)) ? call_user_func(array(__CLASS__, $metaData->getMethod), $defaultValue)  : self::getSetting($settingId, $useDefaultsIfNeeded ? $defaultValue : null);
            WADA_Log::debug('Render '.$name.', label: '.$label.', value: '.(is_array($value) ? print_r($value, true) : $value));
            switch($metaData->field){
                case 'checkbox':
                    $returnVal = WADA_HtmlUtils::checkboxField($name, $label, $value, $options);
                    break;
                case 'input':
                    $returnVal = WADA_HtmlUtils::inputField($name, $label, $value, $options);
                    break;
                case 'password':
                    $returnVal = WADA_HtmlUtils::passwordField($name, $label, $value, $options);
                    break;
                case 'textarea':
                    $returnVal = WADA_HtmlUtils::textareaField($name, $label, $value, null, null, $options);
                    break;
                case 'select':
                    $selectOptions = $metaData->selectOptions;
                    if(is_callable($selectOptions, true)){
                        $selectOptions = call_user_func($selectOptions);
                    }
                    $returnVal = WADA_HtmlUtils::selectField($name, $label, $value, $selectOptions,
                        property_exists($metaData, 'disabledSelectOptions') ? $metaData->disabledSelectOptions : array(),
                        property_exists($metaData, 'multiSelect') ? $metaData->multiSelect : false,
                        $options);
                    break;
                case 'hidden':
                    $returnVal = WADA_HtmlUtils::hiddenField($name, $value, $options);
                    break;
                case 'none':
                    WADA_Log::warning('Field should not be rendered (type=none) / '.$name.', label: '.$label.', value: '.((is_array($value)||is_object($value)) ? print_r($value, true) : $value));
                    $returnVal = '';
                    break;
            }
        }
        return $returnVal;
    }

    public static function getMetadataForSetting($settingId){
        if((strlen($settingId) > 7) && substr($settingId, 0, strlen('setting')) === 'setting'){
            $settingId = intval(substr($settingId, strlen('setting')));
        }
        $settingProperty = 'setting'.$settingId;
        $allMetaData = self::getMetadataForAllSettings(); // get metadata from all settings
        if(property_exists($allMetaData, $settingProperty)) {
            $allMetaData->$settingProperty->settingId = $settingId;
            return $allMetaData->$settingProperty; // pull out this setting's metadata
        }
        return false;
    }

    public static function doesSettingExist($settingId){
        if((strlen($settingId) > 7) && substr($settingId, 0, strlen('setting')) === 'setting'){
            $settingId = intval(substr($settingId, strlen('setting')));
        }
        global $wpdb;
        $query = 'SELECT setting_value FROM '.WADA_Database::tbl_settings().' WHERE id = %d';
        $settingValue = $wpdb->get_var($wpdb->prepare($query, $settingId));
        if(is_null($settingValue)){
            return false;
        }
        return true;
    }

    public static function isDeleteDatabaseDataOnUninstall($default=false){
        return self::getSetting(self::DELETE_DB_DATA_ON_UNINSTALL, $default);
    }

    public static function setIsDeleteDatabaseDataOnUninstall($deleteDbDataOnUninstall){
        return self::setSetting(self::DELETE_DB_DATA_ON_UNINSTALL, $deleteDbDataOnUninstall);
    }

    public static function getDatabaseVersion($default=null, $storeInDbIfNotExists=false){
        return self::getSetting(self::DB_VERSION, $default ?: '1.0', $storeInDbIfNotExists);
    }

    public static function setDatabaseVersion($dbVersion){
        return self::setSetting(self::DB_VERSION, $dbVersion);
    }

    public static function getLoggingLevel($default=WADA_Constants::LOG_LEVEL_INFO) {
        return self::getSetting(self::LOGGING_LEVEL, $default);
    }

    public static function setLoggingLevel($loggingLevel) {
        return self::setSetting(self::LOGGING_LEVEL, $loggingLevel);
    }

    public static function getLogFileSizeWarningLevel($default=50) {
        return self::getSetting(self::LOGGING_LOG_FILE_WARNING_SIZE_MB, $default);
    }

    public static function setLogFileSizeWarningLevel($warningLevelInMb) {
        return self::setSetting(self::LOGGING_LOG_FILE_WARNING_SIZE_MB, $warningLevelInMb);
    }

    public static function isLoggingForced($default=0) {
        return self::getSetting(self::LOGGING_FORCED, $default);
    }

    public static function setIsLoggingForced($isLoggingForced) {
        return self::setSetting(self::LOGGING_FORCED, $isLoggingForced);
    }

    public static function getRetentionPeriodInDays($retentionPeriodNumber = null, $retentionPeriodUnit = null){
        $retentionPeriodNumber = (is_null($retentionPeriodNumber) ? absint(self::getRetentionPeriodNum()) : $retentionPeriodNumber);
        $retentionPeriodUnit = (is_null($retentionPeriodUnit) ? absint(self::getRetentionPeriodUnit()) : $retentionPeriodUnit);
        switch($retentionPeriodUnit){
            case 'd':
                $res = $retentionPeriodNumber;
                break;
            case 'm':
                $res = $retentionPeriodNumber*31;
                break;
            case 'y':
                $res = $retentionPeriodNumber*365;
                break;
            default:
                $res = $retentionPeriodNumber;
        }
        return $res;
    }

    public static function setRetentionPeriod($retentionPeriodNumber, $retentionPeriodUnit = 'd') {
        $retentionPeriodNumber = absint($retentionPeriodNumber);
        $retDays = self::getRetentionPeriodInDays($retentionPeriodNumber, $retentionPeriodUnit);
        if(absint(WADA_Version::getFtSetting(WADA_Version::FT_ID_RET)) < $retDays || (absint(WADA_Version::getFtSetting(WADA_Version::FT_ID_RET))<100000 && $retDays == 0)){
            $retentionPeriodNumber = absint(WADA_Version::getFtSetting(WADA_Version::FT_ID_RET)) ;
            $retentionPeriodUnit = 'd';
        }
        self::setRetentionPeriodUnit($retentionPeriodUnit);
        return self::setSetting(self::EVENT_LOG_RETENTION_PERIOD_NUM, $retentionPeriodNumber);
    }

    public static function getRetentionPeriodNum($default=30){
        return absint(self::getSetting(self::EVENT_LOG_RETENTION_PERIOD_NUM, $default));
    }

    public static function setRetentionPeriodNum($retentionPeriodNumber) {
        $retentionPeriodNumber = absint($retentionPeriodNumber);
        return self::setSetting(self::EVENT_LOG_RETENTION_PERIOD_NUM, $retentionPeriodNumber);
    }

    public static function getRetentionPeriodUnit($default='d'){
        return self::getSetting(self::EVENT_LOG_RETENTION_PERIOD_UNIT, $default);
    }

    public static function setRetentionPeriodUnit($retentionPeriodUnit) {
        $retentionPeriodUnit = strtolower($retentionPeriodUnit);
        $allowedValues = array('d', 'm', 'y');
        if(!in_array($retentionPeriodUnit, $allowedValues)){
            $retentionPeriodUnit = 'd';
        }
        return self::setSetting(self::EVENT_LOG_RETENTION_PERIOD_UNIT, $retentionPeriodUnit);
    }

    public static function isAnonymizeIPAddress($default=1) {
        return self::getSetting(self::ANONYMIZE_IP_ADDRESS, $default);
    }

    public static function setIsAnonymizeIPAddress($anonymizeIPAddress) {
        return self::setSetting(self::ANONYMIZE_IP_ADDRESS, $anonymizeIPAddress);
    }

    public static function isLastActivitiesWidgetEnabled($default=1) {
        return self::getSetting(self::WIDGET_LAST_ACTIVITIES_ENABLED, $default);
    }

    public static function setLastActivitiesWidgetEnabled($status) {
        return self::setSetting(self::WIDGET_LAST_ACTIVITIES_ENABLED, $status);
    }

    public static function getLastActivitiesWidgetNrOfItems($default=5) {
        return self::getSetting(self::WIDGET_LAST_ACTIVITIES_NR_ITEMS, $default);
    }

    public static function setLastActivitiesWidgetNrOfItems($nrOfItems) {
        return self::setSetting(self::WIDGET_LAST_ACTIVITIES_NR_ITEMS, ($nrOfItems > 0) ? $nrOfItems : 1);
    }

    public static function isLoginAttemptsWidgetEnabled($default=1) {
        return self::getSetting(self::WIDGET_LOGIN_ATTEMPTS_ENABLED, $default);
    }

    public static function setLoginAttemptsWidgetEnabled($status) {
        return self::setSetting(self::WIDGET_LOGIN_ATTEMPTS_ENABLED, $status);
    }

    public static function isUserAccountAutoAdjustEnabled($default=0) {
        return self::getSetting(self::UAC_AUTO_ADJUST_ENABLED, $default);
    }

    public static function setUserAccountAutoAdjustEnableStatus($status) {
        return self::setSetting(self::UAC_AUTO_ADJUST_ENABLED, $status);
    }

    public static function getUserAccountAutoAdjustRolesInScope($default=array('administrator')) {
        return self::getSetting(self::UAC_AUTO_ADJUST_ROLES_IN_SCOPE, $default);
    }

    protected static function getWPRoles(){
        global $wp_roles;
        if ( ! isset( $wp_roles ) ) {
            $wp_roles = new WP_Roles();
        }
        return $wp_roles;
    }

    protected static function getUserAccountAutoAdjustRolesInScopeSelectOptions(){
        $wp_roles = self::getWPRoles();
        return $wp_roles->get_names();
    }

    public static function setUserAccountAutoAdjustRolesInScope($rolesArray) {
        $allowedRoles = array_keys(self::getUserAccountAutoAdjustRolesInScopeSelectOptions());
        if(is_null($rolesArray)){
            $rolesArray = array();
        }
        $selectedRoles = array();
        foreach($rolesArray as $role){
            if(in_array($role, $allowedRoles)){
                $selectedRoles[] = $role;
            }
        }
        return self::setSetting(self::UAC_AUTO_ADJUST_ROLES_IN_SCOPE, $selectedRoles);
    }

    public static function getUserAccountAutoAdjustInactiveSinceDaysCriteria($default=90) {
        return self::getSetting(self::UAC_AUTO_ADJUST_INACTIVE_SINCE_DAYS_CRITERIA, $default);
    }

    public static function setUserAccountAutoAdjustInactiveSinceDaysCriteria($inActiveSinceDays) {
        $inActiveSinceDays = intval($inActiveSinceDays);
        if($inActiveSinceDays <= 0 || $inActiveSinceDays > (365*3)){
            $inActiveSinceDays = 90;
        }
        return self::setSetting(self::UAC_AUTO_ADJUST_INACTIVE_SINCE_DAYS_CRITERIA, $inActiveSinceDays);
    }

    public static function getUserAccountAutoAdjustChangeToRoleSelectOptions(){
        $wp_roles = self::getWPRoles();
        $roleNamesAndKeys = $wp_roles->get_names();
        return (array('none' => __('No role', 'wp-admin-audit')) + $roleNamesAndKeys); // prepend the "none" option
    }

    public static function getUserAccountAutoAdjustChangeToRole($default='subscriber'){
        return self::getSetting(self::UAC_AUTO_ADJUST_CHANGE_TO_ROLE, $default);
    }

    public static function setUserAccountAutoAdjustChangeToRole($changeToRole){
        $allowedValues = array_keys(self::getUserAccountAutoAdjustChangeToRoleSelectOptions());
        if(!in_array($changeToRole, $allowedValues)){
            $changeToRole = 'subscriber';
        }
        return self::setSetting(self::UAC_AUTO_ADJUST_CHANGE_TO_ROLE, $changeToRole);
    }

    public static function isUserAccountEnforcePwChangeEnabled($default=0) {
        return (intval(self::getSetting(self::UAC_ENF_PW_CHG_ENABLED, $default)) > 0);
    }

    public static function setUserAccountEnforcePwChangeEnableStatus($status) {
        return self::setSetting(self::UAC_ENF_PW_CHG_ENABLED, $status);
    }

    public static function getUserAccountEnforcePwChangeRolesInScope($default=array('administrator')) {
        return self::getSetting(self::UAC_ENF_PW_CHG_ROLES_IN_SCOPE, $default);
    }

    public static function setUserAccountEnforcePwChangeRolesInScope($rolesArray) {
        $allowedRoles = array_keys(self::getUserAccountAutoAdjustRolesInScopeSelectOptions());
        if(is_null($rolesArray)){
            $rolesArray = array();
        }
        $selectedRoles = array();
        foreach($rolesArray as $role){
            if(in_array($role, $allowedRoles)){
                $selectedRoles[] = $role;
            }
        }
        return self::setSetting(self::UAC_ENF_PW_CHG_ROLES_IN_SCOPE, $selectedRoles);
    }

    protected static function getUserAccountEnforcePwChangeRolesInScopeSelectOptions(){
        $wp_roles = self::getWPRoles();
        return $wp_roles->get_names();
    }

    public static function getUserAccountEnforcePwChangeEveryXDays($default=90) {
        return self::getSetting(self::UAC_ENF_PW_CHG_EVERY_X_DAYS, $default);
    }

    public static function setUserAccountEnforcePwChangeEveryXDays($everyXDays) {
        return self::setSetting(self::UAC_ENF_PW_CHG_EVERY_X_DAYS, ($everyXDays > 0) ? $everyXDays : 1);
    }

    public static function getUserAccountEnforcePwChangeNotifications($default=array('30','7','1')) {
        return self::getSetting(self::UAC_ENF_PW_CHG_NOTIFICATIONS, $default);
    }
    public static function setUserAccountEnforcePwChangeNotifications($daysBeforeNotificationArray) {
        if(is_null($daysBeforeNotificationArray)){
            $daysBeforeNotificationArray = array();
        }
        $selectValues = array();
        WADA_Log::debug('setUserAccountEnforcePwChangeNotifications daysBeforeNotificationArray: '.print_r($daysBeforeNotificationArray, true));
        foreach($daysBeforeNotificationArray as $daysBeforeNotification){
            if(intval($daysBeforeNotification)>0 && intval($daysBeforeNotification) <= 365){
                $selectValues[] = intval($daysBeforeNotification);
            }
        }
        return self::setSetting(self::UAC_ENF_PW_CHG_NOTIFICATIONS, $selectValues);
    }

    public static function isReplicationToLogglyEnabled($default=0) {
        return self::getSetting(self::REPL_LOGGLY_ENABLED, $default);
    }

    public static function setReplicationToLogglyEnableStatus($status) {
        return self::setSetting(self::REPL_LOGGLY_ENABLED, $status);
    }

    public static function getReplicationToLogglyToken($default=null){
        return self::getSetting(self::REPL_LOGGLY_TOKEN, $default);
    }

    public static function setReplicationToLogglyToken($token){
        return self::setSetting(self::REPL_LOGGLY_TOKEN, $token);
    }

    public static function getReplicationToLogglyTags($default=null){
        return self::getSetting(self::REPL_LOGGLY_TAGS, $default);
    }

    public static function setReplicationToLogglyTags($tags){
        return self::setSetting(self::REPL_LOGGLY_TAGS, $tags);
    }

    public static function isReplicationToLogtailEnabled($default=0) {
        return self::getSetting(self::REPL_LOGTAIL_ENABLED, $default);
    }

    public static function setReplicationToLogtailEnableStatus($status) {
        return self::setSetting(self::REPL_LOGTAIL_ENABLED, $status);
    }

    public static function getReplicationToLogtailToken($default=null){
        return self::getSetting(self::REPL_LOGTAIL_TOKEN, $default);
    }

    public static function setReplicationToLogtailToken($token){
        return self::setSetting(self::REPL_LOGTAIL_TOKEN, $token);
    }

    public static function isIntegrationForLogsnagEnabled($default=0){
        return self::getSetting(self::INTEG_LOGSNAG_ENABLED, $default);
    }

    public static function setIntegrationForLogsnagEnableStatus($default=null){
        return self::setSetting(self::INTEG_LOGSNAG_ENABLED, $default);
    }

    public static function getIntegrationForLogsnagToken($default=null){
        return self::getSetting(self::INTEG_LOGSNAG_TOKEN, $default);
    }

    public static function setIntegrationForLogsnagToken($token){
        return self::setSetting(self::INTEG_LOGSNAG_TOKEN, $token);
    }

    public static function getIntegrationForLogsnagProject($default=null){
        return self::getSetting(self::INTEG_LOGSNAG_PROJECT, $default);
    }

    public static function setIntegrationForLogsnagProject($project){
        return self::setSetting(self::INTEG_LOGSNAG_PROJECT, $project);
    }

    public static function getDateFormatForDateOnly($returnAsIntlDateFormatter = false, $default='medium'){
        $strFormat = self::getSetting(self::DATE_FORMAT_DATE_ONLY, $default);
        if($returnAsIntlDateFormatter){
            return self::intlDateTimeFromStrToInt($strFormat);
        }
        return $strFormat;
    }

    public static function setDateFormatForDateOnly($dateFormat){
        $allowedValues = array_keys(self::getDateFormatForDateOnlySelectOptions());
        if(!in_array($dateFormat, $allowedValues)){
            $dateFormat = 'medium';
        }
        return self::setSetting(self::DATE_FORMAT_DATE_ONLY, $dateFormat);
    }

    protected static function getExampleDateTimeString($dateType, $timeType, $locale, $timeZone){
        $exampleDatetime = new DateTime();
        $exampleDatetime->setTimezone($timeZone);

        $dateFormatter = new IntlDateFormatter(
            $locale,
            $dateType,
            $timeType,
            $timeZone
        );
        return $dateFormatter->format($exampleDatetime);
    }

    public static function getDateFormatForDateOnlySelectOptions(){
        $timeZone = wp_timezone();
        $locale = get_user_locale();

        return array(
            'short' => self::getExampleDateTimeString(IntlDateFormatter::SHORT, IntlDateFormatter::NONE, $locale, $timeZone),
            'medium' => self::getExampleDateTimeString(IntlDateFormatter::MEDIUM, IntlDateFormatter::NONE, $locale, $timeZone),
            'long' => self::getExampleDateTimeString(IntlDateFormatter::LONG, IntlDateFormatter::NONE, $locale, $timeZone),
            'full' => self::getExampleDateTimeString(IntlDateFormatter::FULL, IntlDateFormatter::NONE, $locale, $timeZone)
        );
    }

    public static function getDateFormatForDatetimeSelectOptions(){
        $timeZone = wp_timezone();
        $locale = get_user_locale();

        return array(
            'short/short' => self::getExampleDateTimeString(IntlDateFormatter::SHORT, IntlDateFormatter::SHORT, $locale, $timeZone),
            'short/medium' => self::getExampleDateTimeString(IntlDateFormatter::SHORT, IntlDateFormatter::MEDIUM, $locale, $timeZone),
            'short/long' => self::getExampleDateTimeString(IntlDateFormatter::SHORT, IntlDateFormatter::LONG, $locale, $timeZone),
            'medium/short' => self::getExampleDateTimeString(IntlDateFormatter::MEDIUM, IntlDateFormatter::SHORT, $locale, $timeZone),
            'medium/medium' => self::getExampleDateTimeString(IntlDateFormatter::MEDIUM, IntlDateFormatter::MEDIUM, $locale, $timeZone),
            'medium/long' => self::getExampleDateTimeString(IntlDateFormatter::MEDIUM, IntlDateFormatter::LONG, $locale, $timeZone),
            'long/short' => self::getExampleDateTimeString(IntlDateFormatter::LONG, IntlDateFormatter::SHORT, $locale, $timeZone),
            'long/medium' => self::getExampleDateTimeString(IntlDateFormatter::LONG, IntlDateFormatter::MEDIUM, $locale, $timeZone),
            'long/long' => self::getExampleDateTimeString(IntlDateFormatter::LONG, IntlDateFormatter::LONG, $locale, $timeZone),
            'full/short' => self::getExampleDateTimeString(IntlDateFormatter::FULL, IntlDateFormatter::SHORT, $locale, $timeZone),
            'full/medium' => self::getExampleDateTimeString(IntlDateFormatter::FULL, IntlDateFormatter::MEDIUM, $locale, $timeZone),
            'full/long' => self::getExampleDateTimeString(IntlDateFormatter::FULL, IntlDateFormatter::LONG, $locale, $timeZone)
        );
    }

    protected static function intlDateTimeFromStrToInt($strFormat){
        $intFormat = IntlDateFormatter::MEDIUM;
        switch($strFormat){
            case 'none':
                $intFormat = IntlDateFormatter::NONE;
                break;
            case 'short':
                $intFormat = IntlDateFormatter::SHORT;
                break;
            case 'medium':
                $intFormat = IntlDateFormatter::MEDIUM;
                break;
            case 'long':
                $intFormat = IntlDateFormatter::LONG;
                break;
            case 'full':
                $intFormat = IntlDateFormatter::FULL;
                break;
        }
        return $intFormat;
    }

    public static function getDateFormatForDatetime($returnSplit = false, $returnAsIntlDateFormatter = false, $default='short/short'){
        $dateTimeFormat = self::getSetting(self::DATE_FORMAT_DATE_TIME, $default);
        $allowedValues = array_keys(self::getDateFormatForDatetimeSelectOptions());
        if(!in_array($dateTimeFormat, $allowedValues)){
            $dateTimeFormat = 'short/short';
        }
        if($returnSplit){
            list($dateType, $timeType) = explode('/', $dateTimeFormat);
            $dateType = self::intlDateTimeFromStrToInt($dateType);
            $timeType = self::intlDateTimeFromStrToInt($timeType);
            return array($dateType, $timeType);
        }
        return $dateTimeFormat;
    }

    public static function setDateFormatForDatetime($dateFormat){
        $allowedValues = array_keys(self::getDateFormatForDatetimeSelectOptions());
        if(!in_array($dateFormat, $allowedValues)){
            $dateFormat = 'short/short';
        }
        return self::setSetting(self::DATE_FORMAT_DATE_TIME, $dateFormat);
    }

    public static function getLicenseKey($default=null){
        return self::getSetting(self::LICENSE_KEY, $default);
    }

    public static function setLicenseKey($licenseKey){
        return self::setSetting(self::LICENSE_KEY, $licenseKey);
    }

    public static function getLicenseStatus($default=null){
        $res = self::getSetting(self::LICENSE_STATUS, $default);
        if(is_null($res)){ // handle debug with expected structure
            $res = new stdClass();
            $res->key = null;
            $res->license_status = 'not-ready';
            $res->activation_status = 'not-ready';
            $res->activation_at = null;
            $res->activation_msg = null;
            $res->activation_err = null;
            $res->last_checked = null;
            $res->last_msg = null;
            $res->last_error = null;
            $res->is_expired = null;
            $res->valid_from = null;
            $res->expires_on = null;
            $res->is_test_install = null;
            $res->main_install = null;
            return $res;
        }
        return $res;
    }

    public static function setLicenseStatus($status){
        return self::setSetting(self::LICENSE_STATUS, $status);
    }

    public static function getAllSettings($useNameAsIndex = false){
        $class = new ReflectionClass(__CLASS__);
        $constants = array_flip($class->getConstants());
        $settings = array();
        $metaData = self::getMetadataForAllSettings();
        foreach ($constants as $settingId => $settingName) {
            $settingProperty = 'setting'.$settingId;
            $isInMetaData = property_exists($metaData, $settingProperty);
            if($isInMetaData){
                $settingMetaData = $metaData->$settingProperty;
                $getValueMethod = $settingMetaData->getMethod;
                $value = is_callable(array(__CLASS__, $getValueMethod)) ? call_user_func(array(__CLASS__, $getValueMethod)) : null;
                $niceValue = self::getNiceValueRendering($value, $settingMetaData);
                $settingObj = (object)array(
                    'id' => $settingId,
                    'name' => $settingName,
                    'value' => $value,
                    'niceValue' => $niceValue,
                    'metaData' => $metaData->$settingProperty
                );
                if($useNameAsIndex){
                    $settings[$settingName] = $settingObj;
                }else{
                    $settings[$settingId] = $settingObj;
                }
            }
        }
        //WADA_Log::debug('getAllSettings settings: '.print_r($settings, true));
        return $settings;
    }

    protected static function getNiceValueRendering($value, $settingMetaData){
        $niceValue = $value;
        if($settingMetaData){
            if($settingMetaData->externalType == 'bool' && $settingMetaData->storageType == 'int'){
                $niceValue = (intval($value) > 0) ? __('Yes', 'wp-admin-audit') : __('No', 'wp-admin-audit');
            }elseif($settingMetaData->externalType == 'object' && $settingMetaData->storageType == 'json'){
                $niceValue = json_encode($value, JSON_PRETTY_PRINT);
            }elseif($settingMetaData->externalType == 'array'){
                if(is_array($value)) {
                    $niceValue = implode(', ', $value);
                }else{
                    if(is_null($value)){
                        $niceValue = '';
                    }else{
                        $niceValue = $value;
                    }
                }
            }elseif($settingMetaData->externalType == 'int'){
                $niceValue = intval($value);
            }
        }
        return $niceValue;
    }

    protected static function getSetting($settingId, $default=null, $storeInDbIfNotExists=false){
        if((strlen($settingId) > 7) && substr($settingId, 0, strlen('setting')) === 'setting'){
            $settingId = substr($settingId, strlen('setting'));
        }
        $settingId = intval($settingId);
        global $wpdb;
        $query = 'SELECT setting_value FROM '.WADA_Database::tbl_settings().' WHERE id = %d';
        $settingValue = $wpdb->get_var($wpdb->prepare($query, $settingId));
        if(is_null($settingValue)){
            // do not log for log related settings, otherwise will cause infinite loop!!!
            if($settingId !== 3 && $settingId !== 4 & $settingValue !== 5) {
                // or better not risk it, just comment it out for production version
                //WADA_Log::debug('take default for: ' . $settingId);
            }
            if(!is_null($default) && $storeInDbIfNotExists){
                self::setSetting($settingId, $default);
            }
            return $default;
        }
        $metaData = self::getMetadataForSetting($settingId);
        if($metaData){
            if($metaData->externalType == 'bool' && $metaData->storageType == 'int'){
                $settingValue = (intval($settingValue) > 0);
            }elseif($metaData->externalType == 'array' && $metaData->storageType == 'string'){
                $settingValue = explode(',', $settingValue);
            }elseif($metaData->externalType == 'object' && $metaData->storageType == 'json'){
                $settingValue = json_decode($settingValue);
            }elseif($metaData->externalType == 'int'){
                $settingValue = intval($settingValue);
            }
        }
        return $settingValue;
    }

    public static function setSettingForSettingId($settingId, $value){
        $metaData = self::getMetadataForSetting($settingId);
        if($metaData && $metaData->setMethod){
            return call_user_func(array('WADA_Settings', $metaData->setMethod), $value);
        }
        return false;
    }

    protected static function setSetting($settingId, $value, $doStringSanitizing = false){
        $metaData = self::getMetadataForSetting($settingId);
        if($metaData){
            if($metaData->externalType == 'bool' && $metaData->storageType == 'int'){
                if(is_bool($value)===true){
                    $value = $value ? '1' : '0';
                }else{
                    $value = intval($value);
                }
            }elseif($metaData->externalType == 'array'){
                if(is_scalar($value)){
                    $value = array($value);
                }
                if(count($value) == 0){
                    $value = ' '; // empty array, but not null!
                }else {
                    $value = implode(',', $value);
                }
            }elseif($metaData->externalType == 'object' && $metaData->storageType == 'json'){
                $value = json_encode($value);
            }elseif($metaData->storageType == 'int'){
                $value = intval($value);
            }
        }

        $value = strval($value); // convert (back) to string since that is what the DB has
        if($doStringSanitizing){ // normally all string sanitizations are expected to be done elsewhere, but we also offer it here
            if(!$metaData || ($metaData->externalType == 'string')){
                $value = sanitize_text_field($value);
            }
        }

        global $wpdb;
        return $wpdb->replace(WADA_Database::tbl_settings(),
            array(
                'id' => intval($settingId),
                'setting_value' => $value
            ),
            array(
               '%d',
               '%s'
            )
        );
    }

    public static function canSendEmailTo($to, $subject){
        $emailSendingDeactivated = ('false' === 'true');
        if($emailSendingDeactivated){
            $overwriteInactiveMailSendingFor = '';
            if(strlen($overwriteInactiveMailSendingFor)){
                $overwriteAddresses = explode(',', $overwriteInactiveMailSendingFor);
                //WADA_Log::debug('overwriteAddresses: '.print_r($overwriteAddresses, true));
                foreach($overwriteAddresses AS $overwriteAddress){
                    if(trim(strtolower($overwriteAddress)) == trim(strtolower($to))){
                        WADA_Log::debug('canSendEmailTo Mail sending is deactivated, but allowed for email: '.$to);
                        return true;
                    }
                }
            }
            if($emailSendingDeactivated) {
                WADA_Log::info('canSendEmailTo Mail sending is deactivated on dev system, skip sending to: ' . $to . ', subject: ' . $subject);
                return false;
            }
        }
        return true;
    }
}