<?php

/**
 * @var $app FluentSupport\Framework\Foundation\Application
 */

(new \FluentSupport\App\Hooks\Handlers\AuthHandler)->init();

$app->addCustomAction('handle_exception', 'ExceptionHandler@handle');

$app->addAction('admin_menu', 'Menu@add');
$app->addAction('admin_enqueue_scripts', 'Menu@maybeEnqueueAssets');
/*
 * Admin Bar
 */
$app->addAction('admin_bar_menu', 'AdminBarHandler@init');
$app->addAction('wp_dashboard_setup', 'AdminBarHandler@initAdminWidget');

$app->addShortcode('fluent_support_portal', 'ShortcodeHandler@fluentSupportPortal');

// init integrations
(new \FluentSupport\App\Services\Integrations\IntegrationInit())->init();

// Activities
(new \FluentSupport\App\Hooks\Handlers\ActivityLogger())->init();

/*
 * Email Notification Hooks
 */

$app->addAction('fluent_support/ticket_created', 'EmailNotificationHandler@ticketCreated', 10, 2);
$app->addAction('fluent_support/response_added_by_agent', 'EmailNotificationHandler@agentReplied', 10, 3);
$app->addAction('fluent_support/response_added_by_customer', 'EmailNotificationHandler@customerReplied', 10, 3);
$app->addAction('fluent_support/ticket_closed_by_agent', 'EmailNotificationHandler@closedByAgent', 10, 2);
$app->addAction('fluent_support/agent_assigned_to_ticket', 'EmailNotificationHandler@onAgentAssign', 10, 3);
$app->addAction('fluent_support/ticket_created_behalf_of_customer', 'EmailNotificationHandler@ticketCreatedByAgent', 10, 3);

// Cleanup
$app->addAction('fluent_support_hourly_tasks', 'CleanupHandler@initHourlyTasks');
$app->addAction('fluent_support_daily_tasks', 'CleanupHandler@initDailyTasks');
$app->addAction('fluent_support_weekly_tasks', 'CleanupHandler@maybeMaintanceTask');

$app->addAction('fluent_support/deleting_ticket', 'CleanupHandler@deleteTicketAttachments');
$app->addAction('fluent_support/ticket_closed', 'CleanupHandler@maybeDeleteAttachmentsOnClose');

if(isset($_GET['fs_view'])) {
    $app->addAction('init', 'ExternalPages@route');
}

if (isset($_GET['fst_file'])) {
    add_action('init', function () {
        (new \FluentSupport\App\Hooks\Handlers\ExternalPages())->view_attachment();
    });
}

// require the CLI
if ( defined( 'WP_CLI' ) && WP_CLI ) {
    \WP_CLI::add_command( 'fluent_support', '\FluentSupport\App\Hooks\CLI\FluentCli' );
}


(new \FluentSupport\App\Hooks\Handlers\PermissionFilterManager)->init();

// Register the WordPress personal data exporter and eraser
(new \FluentSupport\App\Hooks\Handlers\PrivacyHandler())->init();

// Action will be triggered when a support customer update their profile in wp
$app->addAction('profile_update', '\FluentSupport\App\Services\ProfileInfoService@onWPProfileUpdate', 10, 3);
$app->addAction('wp_ajax_fs_export_agent_report', 'FluentSupport\App\Hooks\Handlers\DataExporter@exportReport');

// LiteSpeed Cache ESI mode enabled issue fixed
if(defined('LSCWP_V')){
    add_action('litespeed_init', function (){
        if(isset($_SERVER['REQUEST_URI']) && strpos($_SERVER['REQUEST_URI'], 'fluent-support') !== false){
            defined( 'LITESPEED_ESI_OFF' ) || define( 'LITESPEED_ESI_OFF', true );
        }
    });
}

$app->addAction('init', 'BlockEditorHandler@init');

