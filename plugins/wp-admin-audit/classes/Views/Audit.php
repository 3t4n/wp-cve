<?php
/**
 * @copyright (C) 2021 - 2024 Holger Brandt IT Solutions
 * @license GPL2
*/

if(!defined('ABSPATH')){
    exit;
}

class WADA_View_Audit extends WADA_View_BaseForm
{
    const VIEW_IDENTIFIER = 'wada-audit';

    protected function handleFormSubmissions(){
        if(isset($_POST['submit'])){
            check_admin_referer(self::VIEW_IDENTIFIER);
        }
    }

    protected function displayForm(){
        $icons = array();


        $eventEntry = new stdClass();
        $eventEntry->title = __('Events', 'wp-admin-audit');
        $eventEntry->desc = __('Review events and activities', 'wp-admin-audit');
        $eventEntry->icon = 'dashicons dashicons-list-view';
        $eventEntry->link = admin_url('admin.php?page=wp-admin-audit-events');

        $userEntry = new stdClass();
        $userEntry->title = __('Users', 'wp-admin-audit');
        $userEntry->desc = __('Review and manage user accounts', 'wp-admin-audit');
        $userEntry->icon = 'dashicons dashicons-admin-users';
        $userEntry->link = admin_url('admin.php?page=wp-admin-audit-users');

        $loginEntry = new stdClass();
        $loginEntry->title = __('Logins', 'wp-admin-audit');
        $loginEntry->desc = __('Review login attempts', 'wp-admin-audit');
        $loginEntry->icon = 'dashicons dashicons-lock';
        $loginEntry->link = admin_url('admin.php?page=wp-admin-audit-logins');

        $icons[] = $eventEntry;
        $icons[] = $userEntry;
        $icons[] = $loginEntry;
    ?>

        <div class="wrap">
            <h1><?php _e('Audit', 'wp-admin-audit'); ?></h1>
            <ul class="wada-icon-table">
                <?php foreach($icons as $icon): ?>
                <li class="table-entry">
                    <a href="<?php echo esc_attr($icon->link); ?>">
                        <span class="table-entry-container">
                            <span class="table-entry-icon"><span class="<?php echo esc_attr($icon->icon); ?>"></span></span>
                            <span class="table-entry-title"><?php echo esc_html($icon->title); ?></span>
                            <span class="table-entry-description"><?php echo esc_html($icon->desc); ?></span>
                        </span>
                    </a>
                </li>
            <?php endforeach; ?>
            </ul>
        </div>

    <?php
    }
}