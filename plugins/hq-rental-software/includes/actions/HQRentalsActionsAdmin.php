<?php

namespace HQRentalsPlugin\HQRentalsActions;

class HQRentalsActionsAdmin
{
    public function __construct()
    {
        add_action('in_admin_header', [$this, 'removeNotices']);
    }
    public function isPluginAdminPage($page)
    {
        return $page === 'hq-wordpress-settings' or
            $page === 'hq-brands' or
            $page === 'hq-locations' or
            $page === 'hq-vehicle-classes';
    }

    public function removeNotices()
    {
        $page = $_GET['page'] ?? '';
        if (!$this->isPluginAdminPage($page)) {
            return;
        }
        remove_all_actions('admin_notices');
        remove_all_actions('all_admin_notices');
    }
}
