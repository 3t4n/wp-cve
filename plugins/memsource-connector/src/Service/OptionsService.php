<?php

namespace Memsource\Service;

use Memsource\Utils\AuthUtils;
use Memsource\Utils\DatabaseUtils;

class OptionsService
{
    /** @var string Using WPML. */
    public const MULTILINGUAL_PLUGIN_WPML = 'WPML';

    /** @var string Using MultilingualPress */
    public const MULTILINGUAL_PLUGIN_MLP = 'MultilingualPress';

    public const OPTION_ON = 'on';
    public const OPTION_OFF = 'off';

    private $restNamespace = 'memsource/v1/connector';

    private $optionVersion = 'memsource_version';
    private $optionDbVersion = 'memsource_db_version';
    private $optionDebugMode = 'memsource_debug_mode';
    private $optionToken = 'memsource_token';
    private $optionAdminUser = 'memsource_admin_user';
    private $optionListStatus = 'memsource_list_status';
    private $optionInsertStatus = 'memsource_insert_status';
    private $optionUrlRewrite = 'memsource_url_rewrite';
    private $optionCopyPermalink = 'memsource_copy_permalink';
    private $optionTranslationWorkflow = 'memsource_translation_workflow';
    private $optionAutomationWidgetId = 'memsource_automation_widget_id';
    private $optionSourceLanguage = 'memsource_source_language';
    private $optionTargetLanguages = 'memsource_target_languages';
    private $optionMultilingualPlugin = 'memsource_multilingual_plugin';

    /**
     * @param string $translationPlugin One of self::MULTILINGUAL_PLUGIN_*
     * @param bool $overwrite Force use default values instead of the current one.
     */
    public function initOptions(string $translationPlugin, bool $overwrite = false)
    {
        add_option($this->optionVersion, '1.0');
        add_option($this->optionDbVersion, null);
        add_option($this->optionDebugMode, false);
        add_option($this->optionToken, AuthUtils::createNewToken());
        add_option($this->optionAdminUser, get_current_user_id());
        add_option($this->optionListStatus, 'publish');
        add_option($this->optionInsertStatus, 'publish');
        add_option($this->optionUrlRewrite, self::OPTION_ON);
        add_option($this->optionCopyPermalink, self::OPTION_ON);
        add_option($this->optionTranslationWorkflow, '[]');
        add_option($this->optionAutomationWidgetId, null);
        add_option($this->optionSourceLanguage, null);
        add_option($this->optionTargetLanguages, []);
        add_option($this->optionMultilingualPlugin, $translationPlugin);

        if ($overwrite) {
            update_option($this->optionMultilingualPlugin, $translationPlugin);
        }
    }

    public function getActiveTranslationPluginKey(): string
    {
        return get_option($this->optionMultilingualPlugin);
    }

    public function updateVersion()
    {
        update_option($this->optionVersion, MEMSOURCE_PLUGIN_VERSION);
    }

    public function getVersion()
    {
        $version = get_option($this->optionVersion);

        if (!$version) {
            $version = '1.0';
        }

        return $version;
    }

    public function updateDbVersion($version)
    {
        update_option($this->optionDbVersion, $version);
    }

    public function getDbVersion()
    {
        return get_option($this->optionDbVersion);
    }

    public function setDebugMode($debugMode)
    {
        update_option($this->optionDebugMode, $debugMode);
    }

    public function isDebugMode()
    {
        return get_option($this->optionDebugMode) ?: false;
    }

    public function getRestNamespace(): string
    {
        return $this->restNamespace;
    }

    public function updateListStatuses(array $listStatuses)
    {
        update_option($this->optionListStatus, implode("|", $listStatuses));
    }

    public function updateInsertStatus($insertStatus)
    {
        update_option($this->optionInsertStatus, $insertStatus);
    }

    public function updateTranslationWorkflow(array $config)
    {
        update_option($this->optionTranslationWorkflow, json_encode($config));
    }

    public function getTranslationWorkflow(): array
    {
        return json_decode(get_option($this->optionTranslationWorkflow), true);
    }

    public function getListStatuses()
    {
        $status = get_option($this->optionListStatus);

        if (!$status) {
            $status = "publish";
        }

        return explode("|", $status);
    }

    public function getInsertStatus()
    {
        $status = get_option($this->optionInsertStatus);

        if (!$status) {
            $status = "publish";
        }

        return $status;
    }

    public function enableUrlRewrite()
    {
        update_option($this->optionUrlRewrite, self::OPTION_ON);
    }

    public function disableUrlRewrite()
    {
        update_option($this->optionUrlRewrite, self::OPTION_OFF);
    }

    public function isUrlRewriteEnabled(): bool
    {
        return get_option($this->optionUrlRewrite) !== self::OPTION_OFF;
    }

    public function enableCopyPermalink()
    {
        update_option($this->optionCopyPermalink, self::OPTION_ON);
    }

    public function disableCopyPermalink()
    {
        update_option($this->optionCopyPermalink, self::OPTION_OFF);
    }

    public function isCopyPermalinkEnabled(): bool
    {
        return get_option($this->optionCopyPermalink) !== self::OPTION_OFF;
    }

    public function getAdminUser()
    {
        return get_option($this->optionAdminUser);
    }

    public function updateAdminUser($userId)
    {
        update_option($this->optionAdminUser, $userId);
    }

    public function generateAndSaveToken()
    {
        update_option($this->optionToken, AuthUtils::createNewToken());
    }

    public function getToken()
    {
        return get_option($this->optionToken);
    }

    public function getAllMemsourceOptions()
    {
        global $wpdb;
        $tableName = $wpdb->prefix . DatabaseUtils::TABLE_OPTIONS;
        $sql = "select *
                  from {$tableName}
                  where option_name like 'memsource%'";
        return $wpdb->get_results($sql, ARRAY_A);
    }
}
