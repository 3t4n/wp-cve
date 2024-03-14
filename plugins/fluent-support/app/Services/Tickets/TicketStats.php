<?php

namespace FluentSupport\App\Services\Tickets;

use FluentSupport\App\Modules\StatModule;
use FluentSupport\App\Services\TicketHelper;

class TicketStats
{
    public function getQuickLinks()
    {
        $urlBase = apply_filters(
            'fst_menu_url_base',
            admin_url('admin.php?page=fluent-support#/')
        );

        return apply_filters('fst_quick_links', [
            [
                'title' => __('Total Tickets', 'fluent-support'),
                'url'   => esc_url_raw( $urlBase . 'tickets' ),
                'number'=> TicketHelper::countAllTickets(),
                'icon'  => 'el-icon-user'
            ],
            [
                'title' => __('Active Tickets', 'fluent-support'),
                'url'   => esc_url_raw( $urlBase . 'tickets?filter_type=simple&status_type=active' ),
                'number'=> TicketHelper::countActiveTickets(),
                'icon'  => 'el-icon-message'
            ],
            [
                'title' => __('New Tickets', 'fluent-support'),
                'url'   => esc_url_raw( $urlBase . 'tickets?filter_type=simple&status_type=new' ),
                'number'=> TicketHelper::countNewTickets(),
                'icon'  => 'el-icon-folder'
            ],
            [
                'title' => __('Un-Assigned Tickets', 'fluent-support'),
                'url'   => esc_url_raw( $urlBase . 'tickets?agent_id=unassigned' ),
                'number'=> TicketHelper::countUnassignedTickets(),
                'icon'  => 'el-icon-folder'
            ],
            [
                'title' => __('Closed Tickets', 'fluent-support'),
                'url'   => esc_url_raw( $urlBase . 'tickets?filter_type=simple&status_type=closed' ),
                'number'=> TicketHelper::countClosedTickets(),
                'icon'  => 'el-icon-message'
            ],
            [
                'title' => __('Awaiting Replies', 'fluent-support'),
                'url'   => esc_url_raw( $urlBase . 'tickets?status_type=open&waiting_for_reply=yes' ),
                'number'=> StatModule::countAwaitingTickets(),
                'icon'  => 'el-icon-message'
            ]
        ]);
    }
}
