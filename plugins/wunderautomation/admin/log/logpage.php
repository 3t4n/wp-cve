<?php
$urlBase = WUNDERAUTO_URLBASE . "admin/assets/datatables";

if (isset($_POST['clearlog'])) {
    $nonce = isset($_POST['security']) ? wp_unslash(sanitize_key($_POST['security'])) : '';
    $nonce = is_array($nonce) ? (string)$nonce[0] : $nonce;
    if (!wp_verify_nonce($nonce, 'clear-log')) {
        return;
    }

    if (!current_user_can('manage_options')) {
        return;
    }

    $wpdb = wa_get_wpdb();
    if (isset($_POST['clearall'])) {
        $sql = "delete from {$wpdb->prefix}wa_log";
        $wpdb->query($sql);
    }
    if (isset($_POST['clearpast']) && isset($_POST['cleardays'])) {
        $days = (int)$_POST['cleardays'];
        $sql  = "delete from {$wpdb->prefix}wa_log WHERE ";
        $sql .= "datediff(now(), time) >= $days";
        $wpdb->query($sql);
    }
}

?>
<style>
    table.dataTable thead .sorting {
        background-image: url("<?php echo esc_url($urlBase)?>/sort_both.png");
    }
    table.dataTable thead .sorting_asc {
        background-image: url("<?php echo esc_url($urlBase)?>/sort_asc.png");
    }
    table.dataTable thead .sorting_desc {
        background-image: url("<?php echo esc_url($urlBase)?>/sort_desc.png");
    }
    table.dataTable thead .sorting_asc_disabled {
        background-image: url("<?php echo esc_url($urlBase)?>/sort_asc_disabled.png");
    }
    table.dataTable thead .sorting_desc_disabled {
        background-image: url("<?php echo esc_url($urlBase)?>/sort_desc_disabled.png");
    }
</style>

<div class="wrap">
    <h2><?php _e('WunderAutomation log viewer', 'wunderauto');?></h2>

    <table id="wa-logviewer" class="wp-list-table widefat fixed striped">
        <thead>
        <tr>
            <td>Id</td>
            <td>Date</td>
            <td>Time</td>
            <td>Sess.</td>
            <td>Level</td>
            <td>Message</td>
            <td>Details</td>
        </tr>
        </thead>
    </table>
</div>
<div class="form_clearlog">
    <form method="POST">
        <input type="hidden" name="clearlog" value="1">
        <input type="hidden" name="security" value="<?php esc_attr_e(wp_create_nonce('clear-log'))?>">
        <button name="clearall" class="button-primary button-see-me clearlog">
            <?php _e('Clear entire log', 'wunderauto')?>
        </button>
        <button name="clearpast" class="button-primary clearlog">
            <?php _e('Clear entries older than', 'wunderauto')?>
        </button>
        <input name="cleardays" type="number" step="1" min="0" max="9999" maxlength="4" value="5"/>
        <?php _e('Days', 'wunderauto')?>
    </form>
</div>
