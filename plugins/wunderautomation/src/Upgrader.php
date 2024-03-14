<?php

namespace WunderAuto;

/**
 * Class Upgrader
 */
class Upgrader
{
    /**
     * @var string
     */
    protected $version;

    /**
     * @var WunderAuto
     */
    protected $wunderAuto;

    /**
     * @var string
     */
    protected $previousVersion = '';

    /**
     * @param WunderAuto $wunderAuto
     */
    public function __construct($wunderAuto)
    {
        $this->wunderAuto = $wunderAuto;
        $this->version    = $this->wunderAuto->getVersion();
    }

    /**
     * Check if a new version was installed and perform minor upgrade tasks
     *
     * @return void
     */
    public function upgradeCheck()
    {
        if (defined('DOING_AJAX') && DOING_AJAX) {
            return;
        }

        if ($this->previousVersion === '') {
            $this->previousVersion = get_option('wa_version', '1.0.0');
        }

        if ($this->previousVersion === $this->version) {
            return;
        }

        $this->upgrade();
        update_option('wa_version', $this->version, true);
    }

    /**
     * @param Loader $loader
     *
     * @return void
     */
    public function register($loader)
    {
        $loader->addAction(
            'in_plugin_update_message-wunderautomation/wunderautomation.php',
            $this,
            'upgradeNotification',
            10,
            2
        );
    }

    /**
     * If a new plugin version contains an upgrade_notice, display it in the plugin list
     *
     * @param object $currentPluginMetadata
     * @param object $newPluginMetadata
     *
     * @return void
     */
    public function upgradeNotification($currentPluginMetadata, $newPluginMetadata)
    {
        if (isset($newPluginMetadata->upgrade_notice) && strlen(trim($newPluginMetadata->upgrade_notice)) > 0) {
            echo '<br>' . '<strong>Important Upgrade Notice:</strong><br>';
            esc_html_e($newPluginMetadata->upgrade_notice);
        }
    }

    /**
     * For ea
     *
     * @return void
     */
    protected function upgrade()
    {
        if (version_compare($this->previousVersion, '1.5.3')) {
            $this->renameWebhook();
        }

        if (version_compare($this->previousVersion, '1.6.0')) {
            $upgrader16 = new Upgrade\Upgrade16($this->wunderAuto);
            $upgrader16->upgrade();
        }
    }

    /**
     * Before 1.5.3, Webhook triggers, parameters etc. wasn't consistently named
     * "WebHook" and "Webhook" was used mixed in class names etc. Any old workflow
     * that uses a class named "WebHook" needs to be updated
     *
     * @return void
     */
    private function renameWebhook()
    {
        $wpdb      = wa_get_wpdb();
        $workflows = $this->wunderAuto->getWorkflows();

        // We can take the easy approach since WebHook and Webhook are equally long strings
        foreach ($workflows as $workflow) {
            $sql = "UPDATE $wpdb->postmeta SET meta_value = REPLACE(meta_value, 'WebHook', 'Webhook')
                    WHERE meta_key='workflow_settings' AND post_id=%d";
            /** @var string $sql */
            $sql = $wpdb->prepare($sql, [$workflow->getPostId()]);
            $wpdb->query($sql);
        }
    }
}
