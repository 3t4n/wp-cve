<?php 

// Ads for ThriveDesk
add_action('admin_notices', 'wk_td_admin_ads');
add_action('admin_init','wk_td_ads_dismiss_notice');
function wk_td_admin_ads()
{
	if (get_option("wk-td-ads-notice")) {
		return;
	}
?>
<div class="wk-td-ads-notice notice notice-success is-dismissible" style="padding: 30px 30px 20px">
    <img style="max-width:200px"
        src="<?php echo esc_attr(plugin_dir_url(__FILE__) . '../assets/images/thrivedesk-logo.png'); ?>">
    <p style="font-size:16px">
        <?php _e('Your customers deserve better customer support and You deserve the peace of mind. <a href="https://www.thrivedesk.com/?ref=widgetkit"><strong>Try ThriveDesk</strong></a>', 'widgetkit'); ?>
    </p>
</div>
<?php
}

function wk_td_ads_dismiss_notice(){
	if( isset($_GET['dismissed']) && $_GET['dismissed'] == 1 ){
		update_option('wk-td-ads-notice', 1);
	}
}