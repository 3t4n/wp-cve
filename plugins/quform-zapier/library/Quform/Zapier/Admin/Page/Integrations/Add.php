<?php

/**
 * @copyright Copyright (c) 2009-2020 ThemeCatcher (https://www.themecatcher.net)
 */

class Quform_Zapier_Admin_Page_Integrations_Add extends Quform_Zapier_Admin_Page
{
    /**
     * Process this page
     */
    public function process()
    {
        if ( ! current_user_can('quform_zapier_add_integrations')) {
            wp_die(__( 'You do not have sufficient permissions to access this page.', 'quform-zapier'), 403);
        }

        wp_safe_redirect(admin_url('admin.php?page=quform.zapier#add'));
        exit;
    }
}
