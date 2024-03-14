<?php

namespace FluentSupport\App\Hooks\Handlers;

use FluentSupport\App\App;
use FluentSupport\App\Modules\PermissionManager;
use FluentSupport\App\Services\Tickets\TicketStats;
use FluentSupport\App\Services\Helper;

class AdminBarHandler
{
    public function init()
    {
        $currentUserPermissions = PermissionManager::currentUserPermissions();

        if (!$currentUserPermissions) {
            return;
        }

        if( Helper::showTicketSummaryAdminBar() ) {
            add_action('admin_bar_menu', [$this, 'showTicketSummary'], 999);
        }

    }

    public function showTicketSummary($adminBar)
    {
        $assets = App::getInstance('url.assets');

        wp_enqueue_script('fst_global_summary', $assets . 'admin/js/global_summary.js', ['jquery'], FLUENT_SUPPORT_VERSION);

        wp_localize_script('fst_global_summary', 'fst_bar_vars', [
            'rest'            => $this->getRestInfo(),
            'links'           => (new TicketStats())->getQuickLinks(),
            'trans' => [
                'Quick Summary' => __('Quick Summary', 'fluent-support')
            ]
        ]);

        $args = [
            'parent' => 'top-secondary',
            'id'     => 'fst_global_summary',
            'title'  => __('Ticket Summary', 'fluent-support'),
            'href'   => '#',
            'meta'   => false
        ];

        $adminBar->add_node( $args );
    }

    protected function getRestInfo()
    {
        $app = App::getInstance();

        $ns = $app->config->get('app.rest_namespace');
        $v = $app->config->get('app.rest_version');

        return [
            'base_url'  => esc_url_raw(rest_url()),
            'url'       => rest_url($ns . '/' . $v),
            'nonce'     => wp_create_nonce('wp_rest'),
            'namespace' => $ns,
            'version'   => $v
        ];
    }

    public function initAdminWidget ()
    {
        // This widget should be displayed for certain high-level users only.
        if (!current_user_can('manage_options')) {
            return;
        }
        $widget_key = 'fluent_support_reports_widget';

        wp_add_dashboard_widget( $widget_key,
            __('Fluent Support Stats', 'fluent-support'),
            [$this, 'dashWidgetContent']
        );
    }

    public function dashWidgetContent ()
    {
        $ticketStats = (new TicketStats())->getQuickLinks();

        ?>
        <div class="fs_dash_wrapper">
            <table class="fs_dash_table wp-list-table widefat fixed striped">
                <thead>
                <tr>
                    <th><?php _e('Title', 'fluent-support'); ?></th>
                    <th><?php _e('Count', 'fluent-support'); ?></th>
                    <th><?php _e('Action', 'fluent-support'); ?></th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($ticketStats as $stat): ?>
                    <tr>
                        <td><?php echo esc_html($stat['title']); ?></td>
                        <td><?php echo esc_html($stat['number']); ?></td>
                        <td>  <a href="<?php echo esc_url($stat['url']); ?>"> <?php echo __('View', 'fluent-support') ?> </a> </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php
    }
}
