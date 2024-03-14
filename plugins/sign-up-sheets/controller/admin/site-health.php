<?php
/**
 * Admin: Site Health
 */

namespace FDSUS\Controller\Admin;

use Exception;
use FDSUS\Id;
use FDSUS\Model\Settings;
use FDSUS\Model\SettingsMetaBoxes;

class SiteHealth
{
    public function __construct()
    {
        add_filter('debug_information', array(&$this, 'addSiteHealthInfo'));
    }

    /**
     * Adds SUS debug information on the Tools -> Site Health -> Info screen
     *
     * @param array $info
     *
     * @return array
     */
    public function addSiteHealthInfo($info)
    {
        $info['wp-core']['fields']['fdsus_date_format'] = array(
            'label' => esc_html__('Date Format', 'fdsus'),
            'value' => esc_html(get_option('date_format')),
        );
        $info['wp-core']['fields']['fdsus_time_format'] = array(
            'label' => esc_html__('Time Format', 'fdsus'),
            'value' => esc_html(get_option('time_format')),
        );
        $info['wp-core']['fields']['fdsus_admin_email'] = array(
            'label' => esc_html__('Admin Email', 'fdsus'),
            'value' => esc_html(get_option('admin_email')),
        );
        $info['wp-server']['fields']['fdsus_server_max_execution_time'] = array(
            'label' => esc_html__('Max Execution Time', 'fdsus'),
            'value' => ini_get('max_execution_time') . ' seconds',
        );
        $info['wp-server']['fields']['fdsus_user_agent_string'] = array(
            'label' => esc_html__('User Agent String', 'fdsus'),
            'value' => $_SERVER['HTTP_USER_AGENT'],
        );
        $info['sign-up-sheets'] = array(
            'label'  => esc_html__('Sign-up Sheets', 'fdsus'),
            'fields' => $this->getFields(),
        );

        return $info;
    }

    /**
     * Get fields for site health debug data
     *
     * @return array|array[]
     */
    protected function getFields()
    {
        $allOptions = wp_load_alloptions();
        $susOptions = array();

        $dataPrimary = array(
            'version'            => array(
                'label' => esc_html__('Version', 'fdsus'),
                'value' => Id::version(),
            ),
            'db_version_type'    => array(
                'label' => esc_html__('Version Type', 'fdsus'),
                'value' => $allOptions['dls_sus_db_version_type'],
            ),
            'primary_db_version' => array(
                'label' => esc_html__('DB Version', 'fdsus'),
                'value' => $allOptions['dls_sus_db_version'],
            ),
        );

        try {
            $settingsToSkip = $this->settingsToSkip();

            // Skip primary fields
            $settingsToSkip[] = 'dls_sus_db_version';
            $settingsToSkip[] = 'dls_sus_db_version_type';
            foreach ($settingsToSkip as $settingToSkip) {
                unset($allOptions[$settingToSkip]);
            }
        } catch (Exception $e) {
            Id::log('Site Health settings to skip exception... ' . $e->getMessage());
        }


        if (!empty($allOptions) && is_array($allOptions)) {
            // Add certain fields if they don't exist
            if (Id::isPro()) {
                $allOptions['fdsus_enable_confirmation_email'] = Settings::isConfirmationEmailEnabled()
                    ? 'true' : 'false';
                $allOptions['fdsus_enable_removal_confirmation_email'] = Settings::isRemovalConfirmationEmailEnabled()
                    ? 'true' : 'false';
            }

            foreach ($allOptions as $key => $value) {
                if (strpos($key, 'dls_sus_') !== 0
                    && strpos($key, 'dlssus_') !== 0
                    && strpos($key, 'fdsus_') !== 0
                ) {
                    continue;
                }
                $cleanedKey = str_replace('dls_sus_', '', $key);
                $cleanedKey = str_replace('dlssus_', '', $cleanedKey);
                $cleanedKey = str_replace('fdsus_', '', $cleanedKey);

                switch ($key) {
                    // Multi-line
                    case 'dls_sus_custom_fields':
                    case 'dls_sus_custom_task_fields':
                    case 'dls_sus_roles':
                    case 'dlssus_migrate_2.0_to_2.1':
                        $susOptions['multiline'][$cleanedKey] = $value;
                        break;
                    // One-line
                    default:
                        $susOptions['oneline'][$cleanedKey] = $value;
                }
            }
            reset($allOptions);
        }

        $dataOneline = [];
        foreach ($susOptions['oneline'] as $key => $value) {
            $dataOneline[$key] = array(
                'label' => ucwords(str_replace('_', ' ', $key)),
                'value' => $value,
            );
        }
        ksort($dataOneline);

        $dataMultiline = [];
        foreach ($susOptions['multiline'] as $key => $value) {
            $dataMultiline[$key] = array(
                'label' => ucwords(str_replace('_', ' ', $key)),
                'value' => print_r(maybe_unserialize($value), true),
            );
        }
        ksort($dataMultiline);

        return array_merge($dataPrimary, $dataOneline, $dataMultiline);
    }

    /**
     * Get any settings to skip from being output in site health due to sensitive or just unnecessary info
     *
     * @return array
     * @throws Exception
     */
    protected function settingsToSkip()
    {
        $settingsToSkip = array(
            'dls_sus_recaptcha_public_key',
            'dls_sus_recaptcha_private_key',
            'dls_sus_rerun_migrate',
            'fdsus_reset',
        );

        if (!Id::isPro()) {
            $settingsMetaBoxModel = new SettingsMetaBoxes();
            $metaBoxes = $settingsMetaBoxModel->getData();

            foreach ($metaBoxes as $metaBox) {
                if (empty($metaBox['options']) || !is_array($metaBox['options'])) {
                    continue;
                }
                foreach ($metaBox['options'] as $option) {
                    if (empty($option['pro'])) {
                        continue;
                    }
                    $settingsToSkip[] = Id::isPro() ? $option['name'] : $option['original_name'];
                }
            }
        }

        return $settingsToSkip;
    }
}
