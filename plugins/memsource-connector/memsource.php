<?php

/*
Plugin Name: Phrase TMS Integration for WordPress
Plugin URI: https://support.phrase.com/hc/en-us/articles/5709657294620
Description: Localize WordPress websites with the help of professional translation tools: translation memories, terminology bases and quality checkers.
Version: 4.6.1
Text Domain: memsource
Domain Path: /locale
Author: Phrase
Author URI: https://phrase.com
License: GPL v2
*/

use Memsource\Registry\AppRegistry;
use Memsource\Service\OptionsService;
use Memsource\Utils\LogUtils;

define('MEMSOURCE_PLUGIN_PATH', dirname(__FILE__));
define('MEMSOURCE_PLUGIN_VERSION', '4.6.1');
define('MEMSOURCE_PLUGIN_DIR_URL', plugin_dir_url(__FILE__));

if (!defined('ABSPATH')) exit;

if (!defined('WP_CLI')) {
    if (file_exists(ABSPATH . 'wp-config.php')) {
        require_once ABSPATH . 'wp-config.php';
    } elseif (file_exists(dirname(ABSPATH) . DIRECTORY_SEPARATOR . 'wp-config.php')) {
        require_once dirname(ABSPATH) . DIRECTORY_SEPARATOR . 'wp-config.php';
    }

    if (file_exists(ABSPATH . WPINC . '/class-wpdb.php')) {
        require_once ABSPATH . WPINC . '/class-wpdb.php';
    } else {
        require_once ABSPATH . WPINC . '/wp-db.php';
    }
}
require_once ABSPATH . 'wp-admin/includes/plugin.php';
require_once ABSPATH . 'wp-admin/includes/post.php';
require_once ABSPATH . 'wp-admin/includes/taxonomy.php';

require_once dirname(__FILE__) . '/src/Registry/AppRegistry.php';
global $appRegistry;
$appRegistry = new AppRegistry;

// Plugin activation
register_activation_hook(__FILE__, 'memsource_plugin_activate');
add_action('wpmu_new_blog', 'memsource_plugin_activate_new_blog', 10, 6);
add_action('plugins_loaded', 'memsource_plugin_upgrade', 1);
add_action('admin_enqueue_scripts', 'memsource_enqueue_resources');

// Admin menu
if ($appRegistry->getTranslationPlugin()->supportsNetworkAdminMenu()) {
    add_action('network_admin_menu', 'memsource_plugin_setup_menu');
}
if ($appRegistry->getTranslationPlugin()->supportsAdminMenu()) {
    add_action('admin_menu', 'memsource_plugin_setup_menu');
}

// Process forms and AJAX requests
add_action('wp_ajax_generate_token', 'memsource_generate_token');
add_action('wp_ajax_zip_and_email_log', 'memsource_zip_and_email_log');
add_action('wp_ajax_delete_log', 'memsource_delete_log');
add_action('admin_action_save_connector_options', 'memsource_save_connector_options');
add_action('admin_action_set_debug_mode', 'memsource_set_debug_mode');
add_action('admin_action_download_logs', 'memsource_download_logs');
add_action('admin_action_add_update_short_code', [$appRegistry->getShortcodeService(), 'addOrUpdateShortcodeEndpoint']);
add_action('admin_action_delete_short_code', [$appRegistry->getShortcodeService(), 'deleteShortcodeEndpoint']);
add_action('admin_action_add_update_block', [$appRegistry->getBlockService(), 'storeBlockFormSubmit']);
add_action('admin_action_edit_blocks', [$appRegistry->getBlockService(), 'editBlocksFormSubmit']);
add_action('admin_action_delete_block', [$appRegistry->getBlockService(), 'deleteBlockFormSubmit']);
add_action('admin_post_memsource_language_mapping_form', [$appRegistry->getLanguageMappingPage(), 'formSubmit']);
add_action('admin_post_memsource_content_settings_form', [$appRegistry->getCustomFieldsPage(), 'formSubmit']);
add_action('rest_api_init', 'memsource_rest_routes');
add_action('delete_post', 'memsource_delete_post');
add_action('delete_post_meta', 'memsource_delete_post_meta');
add_action('wpml_translation_update', 'memsource_translation_language_change');
add_action('registered_post_type', 'memsource_registered_post_type');
add_action('registered_taxonomy', 'memsource_registered_taxonomy');
add_filter('plugin_action_links', 'memsource_plugin_action_links', 10, 2);

function memsource_plugin_upgrade()
{
    global $appRegistry;
    $appRegistry->getMigrateService()->migrate();
    $appRegistry->getOptionsService()->updateVersion();
    $appRegistry->getShortcodeService()->init();
    if (function_exists('is_multisite') && is_multisite() && memsource_is_mlp_active_for_network()) {
        $appRegistry->initOptions(OptionsService::MULTILINGUAL_PLUGIN_MLP);
    } elseif (memsource_is_wpml_active() || memsource_is_wpml_active_for_network()) {
        $appRegistry->initOptions(OptionsService::MULTILINGUAL_PLUGIN_WPML);
    }
}

function memsource_enqueue_resources()
{
    wp_register_script('memsource_js', plugins_url('js/memsource.js', __FILE__), [], MEMSOURCE_PLUGIN_VERSION, false);
    wp_register_script('clipboard_js', plugins_url('js/clipboard.min.js', __FILE__), [], MEMSOURCE_PLUGIN_VERSION, false);
    wp_register_style('memsource_css', plugins_url('css/memsource.css', __FILE__), false, MEMSOURCE_PLUGIN_VERSION, 'all');
    wp_enqueue_script('memsource_js');
    wp_enqueue_script('clipboard_js');
    wp_enqueue_style('memsource_css');
}

function memsource_plugin_setup_menu()
{
    global $appRegistry;
    $appRegistry->initPages();
}

function memsource_plugin_activate($networkwide)
{
    memsource_check_php_version();
    if (function_exists('is_multisite') && is_multisite() && $networkwide) {
        if (memsource_is_wpml_active_for_network()) {
            // Network activate -> WPML
            memsource_plugin_activate_all_sites(OptionsService::MULTILINGUAL_PLUGIN_WPML);
        } elseif (memsource_is_mlp_active_for_network()) {
            // Network activate -> MLP
            memsource_plugin_activate_all_sites(OptionsService::MULTILINGUAL_PLUGIN_MLP);
        } else {
            memsource_die_requiered_plugin_not_found();
        }
    } else {
        // Not a multisite -> WPML
        if (!memsource_is_wpml_active()) {
            memsource_die_requiered_plugin_not_found();
        }
        memsource_plugin_activate_single_site(OptionsService::MULTILINGUAL_PLUGIN_WPML);
    }
}

function memsource_plugin_activate_new_blog($blogId, $userId, $domain, $path, $siteId, $meta)
{
    global $wpdb;
    if (memsource_is_active_for_network() && memsource_is_wpml_active_for_network()) {
        $oldBlogId = $wpdb->blogid;
        switch_to_blog($blogId);
        memsource_plugin_activate_single_site(OptionsService::MULTILINGUAL_PLUGIN_WPML);
        switch_to_blog($oldBlogId);
    }
}

function memsource_plugin_activate_all_sites($multilingualPlugin)
{
    global $wpdb;
    $oldBlog = $wpdb->blogid;
    $blogIds = $wpdb->get_col("SELECT blog_id FROM $wpdb->blogs");
    foreach ($blogIds as $blogId) {
        switch_to_blog($blogId);
        memsource_plugin_activate_single_site($multilingualPlugin);
    }
    switch_to_blog($oldBlog);
}

function memsource_plugin_activate_single_site($multilingualPlugin)
{
    global $appRegistry;
    $appRegistry->forceInitOptions($multilingualPlugin);
}

function memsource_is_wpml_active()
{
    return is_plugin_active('sitepress-multilingual-cms/sitepress.php');
}

function memsource_is_active_for_network()
{
    return is_plugin_active_for_network('memsource-connector/memsource.php');
}

function memsource_is_wpml_active_for_network()
{
    return is_plugin_active_for_network('sitepress-multilingual-cms/sitepress.php');
}

function memsource_is_mlp_active_for_network()
{
    return is_plugin_active_for_network('multilingualpress/multilingualpress.php');
}

function memsource_die_requiered_plugin_not_found()
{
    wp_die(__('Plugin requieres one of the following plugins to be installed:
                        <ul>
                            <li><a href="https://wpml.org/" target="_blank">WPML Multilingual Plugin</a></li>
                            <li><a href="https://multilingualpress.org" target="_blank">MultilingualPress Plugin</a></li>
                        </ul>
                        For more information you can visit  
                        <a href="https://support.phrase.com/hc/en-us/articles/5709657294620" target="_blank">Phrase TMS Help Center</a>.',
        'memsource-connector'));
}

function memsource_check_php_version()
{
    $phpVersion = phpversion();
    if (!version_compare($phpVersion, '7.3', '>=')) {
        wp_die(__(sprintf('Plugin requires PHP 7.3 or higher. Your version is %s. Please, update the version.', $phpVersion), 'memsource-connector'));
    }
}

function memsource_rest_routes()
{
    global $appRegistry;
    $appRegistry->initRestRoutes();
}

function memsource_plugin_action_links($links, $file)
{
    if ($file == basename(dirname(__FILE__)) . '/memsource.php') {
        $links[] = '<a href="' . menu_page_url('memsource-connector', false) . '">' . __('Configure', 'memsource') . '</a>';
    }

    return $links;
}

function memsource_generate_token()
{
    global $appRegistry;
    $appRegistry->getOptionsService()->generateAndSaveToken();
    $appRegistry->getOptionsService()->updateAdminUser(get_current_user_id());
    header('Content-Type: application/json');
    echo json_encode(['token' => $appRegistry->getOptionsService()->getToken()]);
    wp_die();
}

function memsource_save_connector_options()
{
    global $appRegistry;
    $optionsService = $appRegistry->getOptionsService();

    // Post statuses:
    $listStatuses = [];
    if (isset($_POST['list-status-publish'])) {
        $listStatuses[] = "publish";
    }
    if (isset($_POST['list-status-draft'])) {
        $listStatuses[] = "draft";
    }
    $optionsService->updateListStatuses($listStatuses);
    $optionsService->updateInsertStatus($_POST['insert-status']);

    // URL rewrite:
    isset($_POST['url-rewrite']) ? $optionsService->enableUrlRewrite() : $optionsService->disableUrlRewrite();
    isset($_POST['copy-permalink']) ? $optionsService->enableCopyPermalink() : $optionsService->disableCopyPermalink();

    // Translation workflow:
    $translationWorkflowService = $appRegistry->getTranslationWorkflowService();

    if ($translationWorkflowService->isAcfEnabled()) {
        $translationWorkflowService->storeWorkflowSettings($_POST['translation-workflow'] ?? []);
    }

    wp_safe_redirect(wp_get_referer());
    exit;
}

function memsource_set_debug_mode()
{
    global $appRegistry;
    $appRegistry->getOptionsService()->setDebugMode(isset($_POST['debugMode']));
    if (isset($_POST['debugMode']) && $_POST['debugMode'] == 'on') {
        LogUtils::logSystemInfo();
    }
    wp_safe_redirect(wp_get_referer());
    exit;
}

function memsource_download_logs()
{
    LogUtils::logSystemInfo();
    ob_start();
    ob_clean();
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header('Content-Description: File Transfer');
    header("Content-type: text/plain");
    header("Content-Disposition: attachment; filename=" . LogUtils::LOG_FILE_NAME);
    header("Expires: 0");
    header("Pragma: public");
    readfile(LogUtils::getLogFilePath());
    exit;
}

function memsource_zip_and_email_log()
{
    LogUtils::logSystemInfo();
    header('Content-Type: application/json');
    $zipFile = LogUtils::zipAndEmailLogFile();
    echo json_encode(['zipFile' => $zipFile, 'email' => LogUtils::LOG_EMAIL_RECIPIENT]);
    wp_die();
}

function memsource_delete_log()
{
    header('Content-Type: application/json');
    $result = LogUtils::deleteLogFile();
    echo json_encode($result);
    wp_die();
}

function memsource_delete_post($id)
{
    global $appRegistry;
    $database = $appRegistry->getDatabaseService();
    return $database->deleteTranslationsBySetId($id);
}

function memsource_delete_post_meta($id)
{
    global $appRegistry;
    $database = $appRegistry->getDatabaseService();
    return $database->deleteTranslationByTargetIdAndType($id[0], 'customfield_meta');
}

function memsource_translation_language_change($args)
{
    global $appRegistry, $sitepress;
    $database = $appRegistry->getDatabaseService();

    // change language on custom labels
    $elementTypes = ['post_post', 'post_page'];

    if (isset($args['element_id'], $args['element_type']) && in_array($args['element_type'], $elementTypes, true)) {
        $language = (array)$sitepress->get_element_language_details($args['element_id'], $args['element_type']);
        if (isset($language['language_code'])) {
            $database->updateTargetLanguageBySetIdAndType($language['language_code'], $args['element_id'], 'customfield_meta');
        }
    }

    return true;
}

function memsource_registered_post_type($type)
{
    global $appRegistry;

    $params = ['_builtin' => false]; // only custom types
    $postTypes = get_post_types($params, 'objects');
    $customType = $postTypes[$type] ?? null;

    if ($customType !== null) {
        $customPost = $appRegistry->createCustomPostService($customType);
        $appRegistry->addContentServiceToContentController($customPost, false);
    }
}

function memsource_registered_taxonomy($type)
{
    global $appRegistry;

    $params = ['_builtin' => false]; // only custom types
    $taxonomies = get_taxonomies($params, 'objects');
    $taxonomy = $taxonomies[$type] ?? null;

    if ($taxonomy !== null) {
        $customTaxonomy = $appRegistry->createCustomTaxonomyService($taxonomy);
        $appRegistry->addContentServiceToContentController($customTaxonomy, false);
    }
}
