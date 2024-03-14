<?php
add_action('admin_init',  'wptools_process_cron_actions');
// wptools_run_action('stopbadbots_cron_hook');
function wptools_options_wpcron()
{
    global $wptools_checkversion;
    /*
			$protected_events = array(
				'wp_privacy_delete_old_export_files',
				'wp_version_check',
				'wp_update_plugins',
				'wp_update_themes',
				'wp_site_health_scheduled_check',
				'recovery_mode_clean_expired_keys',
				'wp_scheduled_delete',
				'delete_expired_transients',
				'wp_scheduled_auto_draft_delete',
				'recovery_mode_clean_expired_keys',
				'upgrader_scheduled_cleanup',
			);
            $protected_events = array(
                'wp_privacy_delete_old_export_files',
                'wp_version_check',
                'wp_update_plugins',
                'wp_update_themes',
                'wp_site_health_scheduled_check',
                'recovery_mode_clean_expired_keys',
                'wp_scheduled_delete',
                'delete_expired_transients',
                'wp_scheduled_auto_draft_delete',
                'recovery_mode_clean_expired_keys',
			*/
    $acron = _get_cron_array();
    wptools_show_logo();
    echo '<h1>' . esc_attr__('Cron Jobs', 'wptools') . '</h1>';
?>
  <br>
    <form method="post" class="alignleft" onsubmit="return confirm('<?php esc_attr_e("Do you really want delete all Cron Jobs Without Actions?","wptools");?>');">&nbsp;
        <input type="hidden" name="action" value="wptools_delete_all_cron" />
        <input type="hidden" name="cron" value="all" />
        <?php wp_nonce_field('cron_manager'); 
        if(empty($wptools_checkversion)) {
           echo '<strong>';
           echo esc_attr_e("Option to delete inactive Cron Jobs (without actions) available in Premium Version.", "wptools");
           echo '</strong>';
        }
        else {
           ?><input type="submit" class="button-primary" value="<?php esc_attr_e('Delete All Cron Jobs Without Actions', 'wptools');?>" />
        <?php } 
    echo '</form>';
    echo '<br>';
    echo '<br>';
    esc_attr_e('Cron Jobs Without action means no corresponding functionality that will be triggered when the event runs.', 'wptools'); 
    echo '<br>';
    esc_attr_e("This is often caused by plugins that don't clean up their cron events when you deactivate them.", 'wptools'); 
    ?>
    <br><br>
    <a href="https://wptoolsplugin.com/cron-jobs/"><?php esc_attr_e("Learn more","wptools");?></a>
</form>
    <br>
    <table class="wptools_admin_table" align="center">
        <tr>
            <th><?php esc_attr_e("Cron Job","wptools");?></th>
            <th><?php esc_attr_e("Schedulle","wptools");?></th>
            <th><?php esc_attr_e("Action","wptools");?></th>
            <th><?php esc_attr_e("Protected","wptools");?></th>
        </tr>
    <?php
    foreach ($acron as $row) {
        //if (in_array(key($row), $protected_events))
        //	continue;
        echo '<tr>';
        echo '<td>';
        //if( in_array ( key($row), $protected_events  ))
        //continue;
        echo esc_attr(key($row));
        echo '</td>';
        echo '<td>';
        foreach ($row as $r) {
            // var_dump($r);
            // var_dump(has_action($r[0]));
            $schedule = array_column($r, 'schedule');
            if(!empty(trim($schedule[0]))) 
                echo esc_attr($schedule[0]);
            else 
             echo 'Single Run';
            echo '</td>';
            echo '<td>';
            if (has_action(key($row)))
                echo 'Exists';
            else
                echo 'Not Exists';
            echo '</td>';
            echo '<td>';
            if (is_protected(key($row)))
                echo 'Yes';
            else
                echo 'No';
            echo '</td>';
           // is_protected(
            break;
        }
        // echo '</td>';
        echo '<tr>';
    }
    echo '</table>';
}
function wptools_process_cron_actions()
{
    if (empty($_REQUEST['action'])) {
        return;
    }
    if (!current_user_can('manage_options')) {
        return;
    }
    if ($_REQUEST['action'] == 'wptools_delete_all_cron') {
        if (!wp_verify_nonce($_REQUEST['_wpnonce'], 'cron_manager')) {
            return;
        }
        wptools_delete_all_cron();
        wp_safe_redirect(admin_url('admin.php?page=wptools_options27'));
        exit;
    }
}
function wptools_delete_all_cron()
{
    $acron = _get_cron_array();
    foreach ($acron as $row) {
        //if (in_array(key($row), $protected_events))
        //	continue;
        foreach ($row as $r) {
            // $schedule = array_column($r, 'schedule');
            if (has_action(key($row)) or is_protected(key($row)))
                break;
            wp_clear_scheduled_hook( key($row));
        }
    }
    return;
}
function is_protected( $event_hook ) {
    $protected_events = array(
        'wp_privacy_delete_old_export_files',
        'wp_version_check',
        'wp_update_plugins',
        'wp_update_themes',
        'wp_site_health_scheduled_check',
        'recovery_mode_clean_expired_keys',
        'wp_scheduled_delete',
        'delete_expired_transients',
        'wp_scheduled_auto_draft_delete',
        'recovery_mode_clean_expired_keys',
        'upgrader_scheduled_cleanup',
    );
    return in_array( $event_hook, $protected_events, true );
}
