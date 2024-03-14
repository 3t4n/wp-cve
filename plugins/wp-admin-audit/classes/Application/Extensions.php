<?php
/**
 * @copyright (C) 2021 - 2024 Holger Brandt IT Solutions
 * @license GPL2
*/

if(!defined('ABSPATH')){
    exit;
}

// Handles the relationship with the _extensions table
class WADA_Extensions
{

    private static function storeSomeExtensionRelatedString(){
        $messages = array();
        $messages[] = __('Form no longer existing', 'wp-admin-audit');
        $messages[] = __('Product no longer existing', 'wp-admin-audit');
        $messages[] = __('Redirection no longer existing', 'wp-admin-audit');
        $messages[] = __('%s requires WP Admin Audit to be installed and activated', 'wp-admin-audit'); // e.g. That 3rd party extension so-and-so needs the main plugin
        $messages[] = __('%s no longer existing', 'wp-admin-audit');
        $messages[] = __('Product no longer existing', 'wp-admin-audit');
        $messages[] = __('Form no longer existing', 'wp-admin-audit');
        $messages[] = __('More information about %s', 'wp-admin-audit');
        $messages[] = __('Check for updates', 'wp-admin-audit');
        $messages[] = __('Form submission (Contact Form 7)', 'wp-admin-audit');
        $messages[] = __('Records when a Contact Form 7 form was submitted.', 'wp-admin-audit');
        $messages[] = __('Form creation (Contact Form 7)', 'wp-admin-audit');
        $messages[] = __('Records when a Contact Form 7 form was created.', 'wp-admin-audit');
        $messages[] = __('Form update (Contact Form 7)', 'wp-admin-audit');
        $messages[] = __('Records when a Contact Form 7 form was updated.', 'wp-admin-audit');
        $messages[] = __('Form deletion (Contact Form 7)', 'wp-admin-audit');
        $messages[] = __('Records when a Contact Form 7 form was deleted.', 'wp-admin-audit');
        $messages[] = __('Product creation', 'wp-admin-audit');
        $messages[] = __('Records when a product was created.', 'wp-admin-audit');
        $messages[] = __('Product updated', 'wp-admin-audit');
        $messages[] = __('Records when a product was updated.', 'wp-admin-audit');
        $messages[] = __('Product published', 'wp-admin-audit');
        $messages[] = __('Records when a product was published.', 'wp-admin-audit');
        $messages[] = __('Product unpublished', 'wp-admin-audit');
        $messages[] = __('Records when a product was unpublished.', 'wp-admin-audit');
        $messages[] = __('Product trashed', 'wp-admin-audit');
        $messages[] = __('Records when a product was moved into trash.', 'wp-admin-audit');
        $messages[] = __('Product deleted', 'wp-admin-audit');
        $messages[] = __('Records when a product was deleted.', 'wp-admin-audit');
        $messages[] = __('Form submission (WPForms)', 'wp-admin-audit');
        $messages[] = __('Records when a WPForms form was submitted.', 'wp-admin-audit');
        $messages[] = __('Form creation (WPForms)', 'wp-admin-audit');
        $messages[] = __('Records when a WPForms form was created.', 'wp-admin-audit');
        $messages[] = __('Form update (WPForms)', 'wp-admin-audit');
        $messages[] = __('Records when a WPForms form was updated.', 'wp-admin-audit');
        $messages[] = __('Form deletion (WPForms)', 'wp-admin-audit');
        $messages[] = __('Records when a WPForms form was deleted.', 'wp-admin-audit');
        $messages[] = __('Form trashed (WPForms)', 'wp-admin-audit');
        $messages[] = __('Records when a WPForms form was moved to the trash.', 'wp-admin-audit');
        $messages[] = __('Form restored (WPForms)', 'wp-admin-audit');
        $messages[] = __('Records when a WPForms form was restored from the trash.', 'wp-admin-audit');
        $messages[] = __('Settings updated (WPForms)', 'wp-admin-audit');
        $messages[] = __('Records when the WPForms settings were updated.', 'wp-admin-audit');
        $messages[] = __('Redirect creation (Redirection)', 'wp-admin-audit');
        $messages[] = __('Records when a new redirection is setup via the Redirection plugin.', 'wp-admin-audit');
        $messages[] = __('Redirect update (Redirection)', 'wp-admin-audit');
        $messages[] = __('Records when a redirection is updated via the Redirection plugin.', 'wp-admin-audit');
        $messages[] = __('Redirect deletion (Redirection)', 'wp-admin-audit');
        $messages[] = __('Records when a redirection is deleted via the Redirection plugin.', 'wp-admin-audit');
        $messages[] = __('Redirect enabled (Redirection)', 'wp-admin-audit');
        $messages[] = __('Records when a redirection is enabled via the Redirection plugin.', 'wp-admin-audit');
        $messages[] = __('Redirect disabled (Redirection)', 'wp-admin-audit');
        $messages[] = __('Records when a redirection is disabled via the Redirection plugin.', 'wp-admin-audit');
        $messages[] = __('Settings updated (Redirection)', 'wp-admin-audit');
        $messages[] = __('Records when the settings of the Redirection plugin were updated.', 'wp-admin-audit');
        return $messages;
    }

    public static function getAllExtensions($activeOnly = false){
        global $wpdb;
        $query = 'SELECT * FROM '. WADA_Database::tbl_extensions();
        if($activeOnly){
            $query .= ' WHERE active > 0';
        }
        $query .= ' ORDER BY name, id';
        return $wpdb->get_results($query);
    }

    /**
     * @param $pluginFolder
     * @return object|null
     */
    public static function getExtensionByPluginFolder($pluginFolder){
        global $wpdb;
        $query = 'SELECT * FROM '. WADA_Database::tbl_extensions().' WHERE plugin_folder = %s';
        return $wpdb->get_row($wpdb->prepare($query, $pluginFolder));
    }

    /**
     * Updates the extension table according to the plugin's status in WordPress.
     * Will not create a new entry, if extension not existing.
     * Only installed and activated plugins are active in our extensions table.
     * @param string $pluginFolder
     * @return bool
     */
    public static function updateExtensionStatus($pluginFolder){
        global $wpdb;
        $extension = self::getExtensionByPluginFolder($pluginFolder);
        $extensionId = $extension ? intval($extension->id) : 0;
        $wasActiveBefore = $extension ? (intval($extension->active) > 0) : false;
        WADA_Log::debug('updateExtensionStatus pluginFolder: '.$pluginFolder.', extensionId: '.$extensionId.' (old status: '.($wasActiveBefore ? 'active':'inactive').')');

        if($extensionId){
            $isInstalledAndActive = WADA_PluginUtils::isPluginActive($pluginFolder);
            $active = $isInstalledAndActive ? 1 : 0;
            WADA_Log::debug('updateExtensionStatus pluginFolder: '.$pluginFolder.', set to new active status: '.$active);

            // if active before, and now no longer (i.e. active was set to 0)
            if($wasActiveBefore && !$isInstalledAndActive){
                WADA_Log::debug('updateExtensionStatus pluginFolder: '.$pluginFolder.' gets inactivated, deactivate sensors first');
                self::deactivateSensorsOfExtension($extensionId);
            }

            $query = 'UPDATE ' . WADA_Database::tbl_extensions();
            $query .= ' SET active = \''.$active.'\'';
            $query .= ' WHERE id=\''.$extensionId.'\'';
            $affRows = $wpdb->query( $query );

            if($affRows === false){
                return false;
            }

            return true;
        }
        return false;
    }

    public static function deactivateSensorsOfExtension($extensionId){
        global $wpdb;
        WADA_Log::debug('deactivateSensorsOfExtension of extensionId: '.$extensionId);
        $query =  'UPDATE ' . WADA_Database::tbl_sensors();
        $query .= ' SET active = \'0\'';
        $query .= ' WHERE extension_id = %d';
        $affRows = $wpdb->query($wpdb->prepare($query, $extensionId));

        if($affRows === false){
            return false;
        }

        WADA_Log::debug('deactivateSensorsOfExtension extensionId: '.$extensionId.', affRows: '.$affRows);
        return true;
    }
    
}