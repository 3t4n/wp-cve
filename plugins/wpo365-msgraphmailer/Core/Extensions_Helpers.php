<?php

namespace Wpo\Core;

use \Wpo\Core\WordPress_Helpers;
use \Wpo\Services\Log_Service;
use \Wpo\Services\Request_Service;

// Prevent public access to this script
defined('ABSPATH') or die();

if (!class_exists('\Wpo\Core\Extensions_Helpers')) {

    class Extensions_Helpers
    {

        /**
         * Returns by default all activated WPO365 extensions.
         * Optionally can return all possible WPO365 extensions.
         */
        public static function get_extensions($all = false)
        {
            Log_Service::write_log('DEBUG', '##### -> ' . __METHOD__);

            $request_service = Request_Service::get_instance();
            $request = $request_service->get_request($GLOBALS['WPO_CONFIG']['request_id']);

            if (!$all) {
                $installed_extensions = $request->get_item('installed_extensions');

                if (!empty($installed_extensions)) {
                    Log_Service::write_log('DEBUG', sprintf(
                        '%s -> Returning installed WPO365 extensions from request cache',
                        __METHOD__
                    ));
                    return $installed_extensions;
                }
            }

            if (false === function_exists('get_plugins')) {
                require_once ABSPATH . 'wp-admin/includes/plugin.php';
            }

            $plugins = \get_plugins();

            $version = function ($_slug) use ($plugins) {
                $current = isset($plugins[$_slug]) ? $plugins[$_slug] : null;
                return !empty($current) ? $current['Version'] : null;
            };

            $extensions = array(
                'wpo365-login-premium/wpo365-login.php' => array(
                    'activated' => \is_plugin_active('wpo365-login-premium/wpo365-login.php'),
                    'description' => 'Extends WPO365 | LOGIN and gives administrators full control over who can and cannot enroll to / sign into their WordPress website and includes most of the enterprise-ready WPO365 features (Roles + Access, Custom User Fields, Avatar, Groups, Login+, Mail and Profile+).',
                    'extension_file' => dirname(dirname(__DIR__)) . '/wpo365-login-premium/wpo365-login.php',
                    'features' => array(
                        array('SYNC', 'On-demand / scheduled user synchronization from Azure AD to WordPress', 'https://www.wpo365.com/synchronize-users-between-office-365-and-wordpress/'),
                        array('ROLES + ACCESS', 'WordPress roles assignments / access restrictions based on Azure AD groups / user attributes', 'https://www.wpo365.com/downloads/wpo365-roles-access/'),
                        array('CUSTOM USER FIELDS', 'Synchronize Azure AD user attributes e.g. department, job title etc. to WordPress user profiles', 'https://www.wpo365.com/downloads/wpo365-custom-user-fields/'),
                        array('AVATAR', 'Replace the default WordPress / BuddyPress avatar with a Microsoft 365 profile picture', 'https://www.wpo365.com/downloads/wpo365-avatar/'),
                        array('GROUPS', 'Deep integration with the (itthinx) Groups plugin for group membership and access control', 'https://www.wpo365.com/downloads/wpo365-groups/'),
                        array('LOGIN+', 'Support for Dual Login, Azure AD B2B, AAD Multi-Tenancy, Private Pages and (Single) Sign-out', 'https://www.wpo365.com/downloads/wordpress-office-365-login-professional/'),
                        array('MAIL', 'Send HTML formatted email using Microsoft Graph and save messages in Sent Items', 'https://www.wpo365.com/downloads/wpo365-mail/'),
                        array('PROFILE+', 'Update a user\'s WordPress profile with (first, last, full) name, email and UPN from Azure AD', 'https://www.wpo365.com/downloads/wordpress-office-365-login-plus/'),
                    ),
                    'name' => 'wpo365-login-premium',
                    'plugin_file' => 'wpo365-login.php',
                    'plugin_folder' => 'wpo365-login-premium',
                    'slug' => 'wpo365-login-premium/wpo365-login.php',
                    'store_item_id' => 442,
                    'store_item' => 'WPO365 | SYNC',
                    'store_url' => 'https://www.wpo365.com/downloads/wordpress-office-365-login-premium/',
                    'type' => 'bundle',
                    'version' => $version('wpo365-login-premium/wpo365-login.php'),
                ),
                'wpo365-login-intranet/wpo365-login.php' => array(
                    'activated' => \is_plugin_active('wpo365-login-intranet/wpo365-login.php'),
                    'description' => 'Extends WPO365 | LOGIN and offers full integration with the Microsoft Office 365 / Azure AD and includes all available WPO365 feature (SCIM, M365 Apps, Roles + Access, Custom User Fields, Avatar, Groups, Login+, Mail and Profile+).',
                    'extension_file' => dirname(dirname(__DIR__)) . '/wpo365-login-intranet/wpo365-login.php',
                    'features' => array(
                        array('SCIM', 'Automatic (SCIM based) provisioning of users between Azure AD and WordPress', 'https://www.wpo365.com/downloads/wpo365-scim/'),
                        array('M365 APPS', 'Microsoft 365 apps for Power BI, SharePoint Online (Search + List View), OneDrive, Employee Directory / Contacts and Yammer', 'https://www.wpo365.com/downloads/wpo365-microsoft-365-apps/'),
                        array('SYNC', 'On-demand / scheduled user synchronization from Azure AD to WordPress', 'https://www.wpo365.com/synchronize-users-between-office-365-and-wordpress/'),
                        array('ROLES + ACCESS', 'WordPress roles assignments / access restrictions based on Azure AD groups / user attributes', 'https://www.wpo365.com/downloads/wpo365-roles-access/'),
                        array('CUSTOM USER FIELDS', 'Synchronize Azure AD user attributes e.g. department, job title etc. to WordPress user profiles', 'https://www.wpo365.com/downloads/wpo365-custom-user-fields/'),
                        array('AVATAR', 'Replace the default WordPress / BuddyPress avatar with a Microsoft 365 profile picture', 'https://www.wpo365.com/downloads/wpo365-avatar/'),
                        array('GROUPS', 'Integration with the (itthinx) Groups plugin for group membership and access control through Groups assignments based on Azure AD groups / user attributes', 'https://www.wpo365.com/downloads/wpo365-groups/'),
                        array('LOGIN+', 'Support for Dual Login, Azure AD B2B, AAD Multi-Tenancy, Private Pages and (Single) Sign-out', 'https://www.wpo365.com/downloads/wordpress-office-365-login-professional/'),
                        array('MAIL', 'Send HTML formatted email using Microsoft Graph and save messages in Sent Items', 'https://www.wpo365.com/downloads/wpo365-mail/'),
                        array('PROFILE+', 'Update a user\'s WordPress profile with (first, last, full) name, email and UPN from Azure AD', 'https://www.wpo365.com/downloads/wordpress-office-365-login-plus/'),
                    ),
                    'name' => 'wpo365-login-intranet',
                    'plugin_file' => 'wpo365-login.php',
                    'plugin_folder' => 'wpo365-login-intranet',
                    'slug' => 'wpo365-login-intranet/wpo365-login.php',
                    'store_item_id' => 5415,
                    'store_item' => 'WPO365 | INTRANET',
                    'store_url' => 'https://www.wpo365.com/downloads/wordpress-office-365-login-intranet/',
                    'type' => 'bundle',
                    'version' => $version('wpo365-login-intranet/wpo365-login.php'),
                ),
                'wpo365-login-plus/wpo365-login.php' => array(
                    'activated' => \is_plugin_active('wpo365-login-plus/wpo365-login.php'),
                    'description' => 'Update a user\'s WordPress profile with (first, last, full) name, email and UPN from Azure AD.',
                    'extension_file' => dirname(dirname(__DIR__)) . '/wpo365-login-plus/wpo365-login.php',
                    'features' => array(
                        array('PROFILE+', 'Update a user\'s WordPress profile with (first, last, full) name, email and UPN from Azure AD', 'https://www.wpo365.com/downloads/wordpress-office-365-login-plus/'),
                    ),
                    'name' => 'wpo365-login-plus',
                    'plugin_file' => 'wpo365-login.php',
                    'plugin_folder' => 'wpo365-login-plus',
                    'slug' => 'wpo365-login-plus/wpo365-login.php',
                    'store_item_id' => 7243,
                    'store_item' => 'WPO365 | PROFILE+',
                    'store_url' => 'https://www.wpo365.com/downloads/wordpress-office-365-login-plus/',
                    'type' => 'extension',
                    'version' => $version('wpo365-login-plus/wpo365-login.php'),
                ),
                'wpo365-mail/wpo365-mail.php' => array(
                    'activated' => \is_plugin_active('wpo365-mail/wpo365-mail.php'),
                    'description' => 'Send HTML formatted email using Microsoft Graph and save messages in Sent Items (includes the PROFILE+ extension).',
                    'extension_file' => dirname(dirname(__DIR__)) . '/wpo365-mail/wpo365-mail.php',
                    'features' => array(
                        array('MAIL', 'Send HTML formatted email using Microsoft Graph and save messages in Sent Items', 'https://www.wpo365.com/downloads/wpo365-mail/'),
                        array('PROFILE+', 'Update a user\'s WordPress profile with (first, last, full) name, email and UPN from Azure AD', 'https://www.wpo365.com/downloads/wordpress-office-365-login-plus/'),
                    ),
                    'name' => 'wpo365-mail',
                    'plugin_file' => 'wpo365-mail.php',
                    'plugin_folder' => 'wpo365-mail',
                    'slug' => 'wpo365-mail/wpo365-mail.php',
                    'store_item_id' => 12360,
                    'store_item' => 'WPO365 | MAIL',
                    'store_url' => 'https://www.wpo365.com/downloads/wpo365-mail/',
                    'type' => 'extension',
                    'version' => $version('wpo365-mail/wpo365-mail.php'),
                ),
                'wpo365-login-professional/wpo365-login.php' => array(
                    'activated' => \is_plugin_active('wpo365-login-professional/wpo365-login.php'),
                    'description' => 'Extends WPO365 | LOGIN and gives WordPress administrators more control over the complete single sign-on experience with support for Private Pages, Dual Login and Azure AD B2B, Multi-Tenancy and Single Sign-out.',
                    'extension_file' => dirname(dirname(__DIR__)) . '/wpo365-login-professional/wpo365-login.php',
                    'features' => array(
                        array('DUAL LOGIN', 'Allow users to choose how they log in to WordPress', 'https://docs.wpo365.com/article/81-enable-dual-login'),
                        array('AZURE AD B2B', 'Support for Azure AD B2B und self-service sign-up flow', ''),
                        array('AAD MULTI-TENANCY', 'Allow users from other Microsoft tenants / services', 'https://www.wpo365.com/automatically-register-new-users-from-other-tenants/'),
                        array('PRIVATE PAGES', 'Restrict access to some pages to logged-in users', 'https://docs.wpo365.com/article/38-private-pages'),
                        array('PROFILE+', 'Update a user\'s WordPress profile with (first, last, full) name, email and UPN from Azure AD', 'https://www.wpo365.com/downloads/wordpress-office-365-login-plus/'),
                        array('(SINGLE) SIGN OUT', '(Single) Sign out of Microsoft 365 / WordPress', 'https://www.wpo365.com/enable-logout-without-confirmation/'),
                    ),
                    'name' => 'wpo365-login-professional',
                    'plugin_file' => 'wpo365-login.php',
                    'plugin_folder' => 'wpo365-login-professional',
                    'slug' => 'wpo365-login-professional/wpo365-login.php',
                    'store_item_id' => 2757,
                    'store_item' => 'WPO365 | LOGIN+',
                    'store_url' => 'https://www.wpo365.com/downloads/wordpress-office-365-login-professional/',
                    'type' => 'extension',
                    'version' => $version('wpo365-login-professional/wpo365-login.php'),
                ),
                'wpo365-customers/wpo365-customers.php' => array(
                    'activated' => \is_plugin_active('wpo365-customers/wpo365-customers.php'),
                    'description' => 'Everything you need to seamlessly integrate your WordPress website with Azure AD B2C / Entra External ID.',
                    'extension_file' => dirname(dirname(__DIR__)) . '/wpo365-customers/wpo365-customers.php',
                    'features' => array(
                        array('DUAL LOGIN', 'Allow users to choose how they log in to WordPress', 'https://docs.wpo365.com/article/81-enable-dual-login'),
                        array('AZURE AD B2B', 'Support for Azure AD B2B und self-service sign-up flow', ''),
                        array('AAD MULTI-TENANCY', 'Allow users from other Microsoft tenants / services', 'https://www.wpo365.com/automatically-register-new-users-from-other-tenants/'),
                        array('PRIVATE PAGES', 'Restrict access to some pages to logged-in users', 'https://docs.wpo365.com/article/38-private-pages'),
                        array('PROFILE+', 'Update a user\'s WordPress profile with (first, last, full) name, email and UPN from Azure AD', 'https://www.wpo365.com/downloads/wordpress-office-365-login-plus/'),
                        array('(SINGLE) SIGN OUT', '(Single) Sign out of Microsoft 365 / WordPress', 'https://www.wpo365.com/enable-logout-without-confirmation/'),
                    ),
                    'name' => 'wpo365-customers',
                    'plugin_file' => 'wpo365-customers.php',
                    'plugin_folder' => 'wpo365-customers',
                    'slug' => 'wpo365-customers/wpo365-customers.php',
                    'store_item_id' => 29622,
                    'store_item' => 'WPO365 | CUSTOMERS',
                    'store_url' => 'https://www.wpo365.com/downloads/wpo365-customers/',
                    'type' => 'extension',
                    'version' => $version('wpo365-customers/wpo365-customers.php'),
                ),
                'wpo365-avatar/wpo365-avatar.php' => array(
                    'activated' => \is_plugin_active('wpo365-avatar/wpo365-avatar.php'),
                    'description' => 'Replace the default WordPress / BuddyPress avatar with a Microsoft 365 profile picture (includes the PROFILE+ extension).',
                    'extension_file' => dirname(dirname(__DIR__)) . '/wpo365-avatar/wpo365-avatar.php',
                    'features' => array(
                        array('AVATAR', 'Replace the default WordPress / BuddyPress avatar with a Microsoft 365 profile picture', 'https://www.wpo365.com/downloads/wpo365-avatar/'),
                        array('PROFILE+', 'Update a user\'s WordPress profile with (first, last, full) name, email and UPN from Azure AD', 'https://www.wpo365.com/downloads/wordpress-office-365-login-plus/'),
                    ),
                    'name' => 'wpo365-avatar',
                    'plugin_file' => 'wpo365-avatar.php',
                    'plugin_folder' => 'wpo365-avatar',
                    'slug' => 'wpo365-avatar/wpo365-avatar.php',
                    'store_item_id' => 12325,
                    'store_item' => 'WPO365 | AVATAR',
                    'store_url' => 'https://www.wpo365.com/downloads/wpo365-avatar/',
                    'type' => 'extension',
                    'version' => $version('wpo365-avatar/wpo365-avatar.php'),
                ),
                'wpo365-custom-fields/wpo365-custom-fields.php' => array(
                    'activated' => \is_plugin_active('wpo365-custom-fields/wpo365-custom-fields.php'),
                    'description' => 'Synchronize Azure AD user attributes e.g. department, job title etc. to WordPress user profiles (includes the PROFILE+ extension).',
                    'extension_file' => dirname(dirname(__DIR__)) . '/wpo365-custom-fields/wpo365-custom-fields.php',
                    'features' => array(
                        array('CUSTOM USER FIELDS', 'Synchronize Azure AD user attributes e.g. department, job title etc. to WordPress user profiles', 'https://www.wpo365.com/downloads/wpo365-custom-user-fields/'),
                        array('PROFILE+', 'Update a user\'s WordPress profile with (first, last, full) name, email and UPN from Azure AD', 'https://www.wpo365.com/downloads/wordpress-office-365-login-plus/'),
                    ),
                    'name' => 'wpo365-custom-fields',
                    'plugin_file' => 'wpo365-custom-fields.php',
                    'plugin_folder' => 'wpo365-custom-fields',
                    'slug' => 'wpo365-custom-fields/wpo365-custom-fields.php',
                    'store_item_id' => 12331,
                    'store_item' => 'WPO365 | CUSTOM USER FIELDS',
                    'store_url' => 'https://www.wpo365.com/downloads/wpo365-custom-user-fields/',
                    'type' => 'extension',
                    'version' => $version('wpo365-custom-fields/wpo365-custom-fields.php'),
                ),
                'wpo365-groups/wpo365-groups.php' => array(
                    'activated' => \is_plugin_active('wpo365-groups/wpo365-groups.php'),
                    'description' => 'Integration with the (itthinx) Groups plugin for group membership and access control through Groups assignments based on Azure AD groups / user attributes (includes the PROFILE+ extension).',
                    'extension_file' => dirname(dirname(__DIR__)) . '/wpo365-groups/wpo365-groups.php',
                    'features' => array(
                        array('GROUPS', 'Integration with the (itthinx) Groups plugin for group membership and access control through Groups assignments based on Azure AD groups / user attributes', 'https://www.wpo365.com/downloads/wpo365-groups/'),
                        array('PROFILE+', 'Update a user\'s WordPress profile with (first, last, full) name, email and UPN from Azure AD', 'https://www.wpo365.com/downloads/wordpress-office-365-login-plus/'),
                    ),
                    'name' => 'wpo365-groups',
                    'plugin_file' => 'wpo365-groups.php',
                    'plugin_folder' => 'wpo365-groups',
                    'slug' => 'wpo365-groups/wpo365-groups.php',
                    'store_item_id' => 12337,
                    'store_item' => 'WPO365 | GROUPS',
                    'store_url' => 'https://www.wpo365.com/downloads/wpo365-groups/',
                    'type' => 'extension',
                    'version' => $version('wpo365-groups/wpo365-groups.php'),
                ),
                'wpo365-apps/wpo365-apps.php' => array(
                    'activated' => \is_plugin_active('wpo365-apps/wpo365-apps.php'),
                    'description' => 'Installs advanced versions of the Microsoft 365 apps for Power BI, SharePoint Online (Search + List View), OneDrive, Employee Directory / Contacts and Yammer.',
                    'extension_file' => dirname(dirname(__DIR__)) . '/wpo365-apps/wpo365-apps.php',
                    'features' => array(
                        array('M365 APPS', 'Microsoft 365 apps for Power BI, SharePoint Online (Search + List View), OneDrive, Employee Directory / Contacts and Yammer', 'https://www.wpo365.com/downloads/wpo365-microsoft-365-apps/'),
                    ),
                    'name' => 'wpo365-apps',
                    'plugin_file' => 'wpo365-apps.php',
                    'plugin_folder' => 'wpo365-apps',
                    'slug' => 'wpo365-apps/wpo365-apps.php',
                    'store_item_id' => 12341,
                    'store_item' => 'WPO365 | MICROSOFT 365 APPS',
                    'store_url' => 'https://www.wpo365.com/downloads/wpo365-microsoft-365-apps/',
                    'type' => 'extension',
                    'version' => $version('wpo365-apps/wpo365-apps.php'),
                ),
                'wpo365-documents/wpo365-documents.php' => array(
                    'activated' => \is_plugin_active('wpo365-documents/wpo365-documents.php'),
                    'description' => 'Installs the WPO365 DOCUMENTS | PREMIUM (Gutenberg) block: A useful, usable and beautiful block for displaying a (folder in a) SharePoint Online / OneDrive library (or the logged-in user\'s recently used documents).',
                    'extension_file' => dirname(dirname(__DIR__)) . '/wpo365-documents/wpo365-documents.php',
                    'features' => array(
                        array('DOCUMENTS', 'An advanced version of the Gutenberg Block for embedding a SharePoint Online / OneDrive library into any post or page that also supports access for anonymous users and custom SharePoint (list item) fields', 'https://www.wpo365.com/downloads/wpo365-documents/'),
                    ),
                    'name' => 'wpo365-documents',
                    'plugin_file' => 'wpo365-documents.php',
                    'plugin_folder' => 'wpo365-documents',
                    'slug' => 'wpo365-documents/wpo365-documents.php',
                    'store_item_id' => 15224,
                    'store_item' => 'WPO365 | DOCUMENTS',
                    'store_url' => 'https://www.wpo365.com/downloads/wpo365-documents/',
                    'type' => 'extension',
                    'version' => $version('wpo365-documents/wpo365-documents.php'),
                ),
                'wpo365-roles-access/wpo365-roles-access.php' => array(
                    'activated' => \is_plugin_active('wpo365-roles-access/wpo365-roles-access.php'),
                    'description' => 'WordPress roles assignments / access restrictions based on Azure AD groups / user attributes (includes the PROFILE+ extension).',
                    'extension_file' => dirname(dirname(__DIR__)) . '/wpo365-roles-access/wpo365-roles-access.php',
                    'features' => array(
                        array('ROLES + ACCESS', 'WordPress roles assignments / access restrictions based on Azure AD groups / user attributes', 'https://www.wpo365.com/downloads/wpo365-roles-access/'),
                        array('PROFILE+', 'Update a user\'s WordPress profile with (first, last, full) name, email and UPN from Azure AD', 'https://www.wpo365.com/downloads/wordpress-office-365-login-plus/'),
                    ),
                    'name' => 'wpo365-roles-access',
                    'plugin_file' => 'wpo365-roles-access.php',
                    'plugin_folder' => 'wpo365-roles-access',
                    'slug' => 'wpo365-roles-access/wpo365-roles-access.php',
                    'store_item_id' => 12362,
                    'store_item' => 'WPO365 | ROLES + ACCESS',
                    'store_url' => 'https://www.wpo365.com/downloads/wpo365-roles-access/',
                    'type' => 'extension',
                    'version' => $version('wpo365-roles-access/wpo365-roles-access.php'),
                ),
                'wpo365-scim/wpo365-scim.php' => array(
                    'activated' => \is_plugin_active('wpo365-scim/wpo365-scim.php'),
                    'description' => 'Automatic (SCIM based) provisioning of users between Azure AD and WordPress (includes the PROFILE+ extension).',
                    'extension_file' => dirname(dirname(__DIR__)) . '/wpo365-scim/wpo365-scim.php',
                    'features' => array(
                        array('SCIM', 'Automatic (SCIM based) provisioning of users between Azure AD and WordPress', 'https://www.wpo365.com/downloads/wpo365-scim/'),
                        array('PROFILE+', 'Update a user\'s WordPress profile with (first, last, full) name, email and UPN from Azure AD', 'https://www.wpo365.com/downloads/wordpress-office-365-login-plus/'),
                    ),
                    'name' => 'wpo365-scim',
                    'plugin_file' => 'wpo365-scim.php',
                    'plugin_folder' => 'wpo365-scim',
                    'slug' => 'wpo365-scim/wpo365-scim.php',
                    'store_item_id' => 12364,
                    'store_item' => 'WPO365 | SCIM',
                    'store_url' => 'https://www.wpo365.com/downloads/wpo365-scim/',
                    'type' => 'extension',
                    'version' => $version('wpo365-scim/wpo365-scim.php'),
                ),
                'wpo365-sync-5y/wpo365-sync-5y.php' => array(
                    'activated' => \is_plugin_active('wpo365-sync-5y/wpo365-sync-5y.php'),
                    'description' => 'Extends WPO365 | LOGIN and gives administrators full control over who can and cannot enroll to / sign into their WordPress website and includes most of the enterprise-ready WPO365 features (Roles + Access, Custom User Fields, Avatar, Groups, Login+, Mail and Profile+).',
                    'extension_file' => dirname(dirname(__DIR__)) . '/wpo365-sync-5y/wpo365-sync-5y.php',
                    'features' => array(
                        array('SYNC', 'On-demand / scheduled user synchronization from Azure AD to WordPress', 'https://www.wpo365.com/synchronize-users-between-office-365-and-wordpress/'),
                        array('ROLES + ACCESS', 'WordPress roles assignments / access restrictions based on Azure AD groups / user attributes', 'https://www.wpo365.com/downloads/wpo365-roles-access/'),
                        array('CUSTOM USER FIELDS', 'Synchronize Azure AD user attributes e.g. department, job title etc. to WordPress user profiles', 'https://www.wpo365.com/downloads/wpo365-custom-user-fields/'),
                        array('AVATAR', 'Replace the default WordPress / BuddyPress avatar with a Microsoft 365 profile picture', 'https://www.wpo365.com/downloads/wpo365-avatar/'),
                        array('GROUPS', 'Deep integration with the (itthinx) Groups plugin for group membership and access control', 'https://www.wpo365.com/downloads/wpo365-groups/'),
                        array('LOGIN+', 'Support for Dual Login, Azure AD B2B, AAD Multi-Tenancy, Private Pages and (Single) Sign-out', 'https://www.wpo365.com/downloads/wordpress-office-365-login-professional/'),
                        array('MAIL', 'Send HTML formatted email using Microsoft Graph and save messages in Sent Items', 'https://www.wpo365.com/downloads/wpo365-mail/'),
                        array('PROFILE+', 'Update a user\'s WordPress profile with (first, last, full) name, email and UPN from Azure AD', 'https://www.wpo365.com/downloads/wordpress-office-365-login-plus/'),
                    ),
                    'name' => 'wpo365-sync-5y',
                    'plugin_file' => 'wpo365-sync-5y.php',
                    'plugin_folder' => 'wpo365-sync-5y',
                    'slug' => 'wpo365-sync-5y/wpo365-sync-5y.php',
                    'store_item_id' => 27385,
                    'store_item' => 'WPO365 | SYNC | 5Y',
                    'store_url' => 'https://www.wpo365.com/downloads/wordpress-office-365-login-premium-5y/',
                    'type' => 'bundle',
                    'version' => $version('wpo365-sync-5y/wpo365-sync-5y.php'),
                ),
                'wpo365-intranet-5y/wpo365-intranet-5y.php' => array(
                    'activated' => \is_plugin_active('wpo365-intranet-5y/wpo365-intranet-5y.php'),
                    'description' => 'Extends WPO365 | LOGIN and offers full integration with the Microsoft Office 365 / Azure AD and includes all available WPO365 feature (SCIM, M365 Apps, Roles + Access, Custom User Fields, Avatar, Groups, Login+, Mail and Profile+).',
                    'extension_file' => dirname(dirname(__DIR__)) . '/wpo365-intranet-5y/wpo365-intranet-5y.php',
                    'features' => array(
                        array('SCIM', 'Automatic (SCIM based) provisioning of users between Azure AD and WordPress', 'https://www.wpo365.com/downloads/wpo365-scim/'),
                        array('M365 APPS', 'Microsoft 365 apps for Power BI, SharePoint Online (Search + List View), OneDrive, Employee Directory / Contacts and Yammer', 'https://www.wpo365.com/downloads/wpo365-microsoft-365-apps/'),
                        array('SYNC', 'On-demand / scheduled user synchronization from Azure AD to WordPress', 'https://www.wpo365.com/synchronize-users-between-office-365-and-wordpress/'),
                        array('ROLES + ACCESS', 'WordPress roles assignments / access restrictions based on Azure AD groups / user attributes', 'https://www.wpo365.com/downloads/wpo365-roles-access/'),
                        array('CUSTOM USER FIELDS', 'Synchronize Azure AD user attributes e.g. department, job title etc. to WordPress user profiles', 'https://www.wpo365.com/downloads/wpo365-custom-user-fields/'),
                        array('AVATAR', 'Replace the default WordPress / BuddyPress avatar with a Microsoft 365 profile picture', 'https://www.wpo365.com/downloads/wpo365-avatar/'),
                        array('GROUPS', 'Integration with the (itthinx) Groups plugin for group membership and access control through Groups assignments based on Azure AD groups / user attributes', 'https://www.wpo365.com/downloads/wpo365-groups/'),
                        array('LOGIN+', 'Support for Dual Login, Azure AD B2B, AAD Multi-Tenancy, Private Pages and (Single) Sign-out', 'https://www.wpo365.com/downloads/wordpress-office-365-login-professional/'),
                        array('MAIL', 'Send HTML formatted email using Microsoft Graph and save messages in Sent Items', 'https://www.wpo365.com/downloads/wpo365-mail/'),
                        array('PROFILE+', 'Update a user\'s WordPress profile with (first, last, full) name, email and UPN from Azure AD', 'https://www.wpo365.com/downloads/wordpress-office-365-login-plus/'),
                    ),
                    'name' => 'wpo365-intranet-5y',
                    'plugin_file' => 'wpo365-intranet-5y.php',
                    'plugin_folder' => 'wpo365-intranet-5y',
                    'slug' => 'wpo365-intranet-5y/wpo365-intranet-5y.php',
                    'store_item_id' => 27341,
                    'store_item' => 'WPO365 | INTRANET | 5Y',
                    'store_url' => 'https://www.wpo365.com/downloads/wordpress-office-365-login-intranet-5y/',
                    'type' => 'bundle',
                    'version' => $version('wpo365-intranet-5y/wpo365-intranet-5y.php'),
                ),
            );

            if ($all) {
                return $extensions;
            }

            $extensions_found = array();

            foreach ($plugins as $slug => $plugin) {

                if (WordPress_Helpers::stripos($slug, 'wpo365') === 0 && isset($extensions[$slug])) {
                    $extensions_found[$slug] = $extensions[$slug];
                }
            }

            $request->set_item('installed_extensions', $extensions_found);
            return $extensions_found;
        }

        /**
         * Gets the active extension dir from cache or looks up it up if not yet cached.
         * 
         * @since 11.9
         * 
         * @return  string  Extension plugin's folder or null if not found
         */
        public static function get_active_extension_dir($slugs = array())
        {

            $extensions = self::get_extensions(true);

            foreach ($slugs as $slug) {

                if (isset($extensions[$slug]) && true === $extensions[$slug]['activated']) {
                    return \trailingslashit(dirname(dirname(__DIR__))) . $extensions[$slug]['plugin_folder'];
                }
            }

            return \trailingslashit(dirname(dirname(__DIR__))) . 'wpo365-login';
        }
    }
}
