<?php
if(!function_exists('add_action')){
	echo 'You are not allowed to access this page directly.';
	exit;
}

function beauty_gravity_links($plugin_actions, $plugin_file) {
	
	$plugin_shortcuts = array();
	 if ( 'beauty-gravity/beauty_gravity.php' === $plugin_file ) {
		$plugin_shortcuts = array(
			'<a style="color:green;" rel="noopener" href="https://sehreideas.com/beauty-gravity/" target="_blank">' . __('Upreade to pro', 'beauty-gravity') . '</a>',
		);
	 }
    return array_merge($plugin_shortcuts, $plugin_actions);
}

add_filter( 'plugin_action_links', 'beauty_gravity_links', 10, 2 );


function sibg_admin_notice_blackfriday() {
	if (   ! PAnD::is_admin_notice_active( 'sibg-notice-forever' )  ) {
		return;
	}
	
	?>
	<div data-dismissible="sibg-notice-forever" id="sibg-notice" class="notice notice-success is-dismissible">
	<label class="gftp-plugin-name">Beauty Gravity Form Styler</label>
	<h1>Limited Time Offer</h1>
	<div class="sibg-notice-innner">
		<p><strong>BlACK FRIDAY 50% OFF</strong>  for buying or renewing beauty gravity form styler pro!</p>
	</div>
	<span class="dashicons dashicons-cart" style="color: #2196f3;vertical-align:bottom;"></span><a href="https://sehreideas.com/beauty-gravity/" target="_blank">Buy Now</a>
	<span class="dashicons dashicons-dismiss" style="margin-left: 15px;color: #ff0000;vertical-align:-webkit-baseline-middle;"></span><a  style="color:#ff0000;" class="dismiss-sibg" href="#">Don't Show Me Again!</a>

	</div>
	<style>
	#sibg-notice.hide,#sibg-notice .notice-dismiss {
	display:none;
	}
	#sibg-notice a{
	color: #2196f3;
    vertical-align: sub;
	}
	#sibg-notice label.gftp-plugin-name {
    background: #4caf50;
    color: #fff;
    padding: 2px 10px;
    position: absolute;
    top: auto;
    bottom: 100%;
    right: 15px;
    -moz-border-radius: 0 0 3px 3px;
    -webkit-border-radius: 0 0 3px 3px;
    border-radius: 4px 4px 0px 0px;
    left: auto;
    font-size: 12px;
    font-weight: bold;
    cursor: auto;
	}
	div#sibg-notice {
    padding: 10px 15px;
	}
	</style>
	<?php
}


if (!defined("SIBGP_VERSION")) {
//add_action( 'admin_notices', 'sibg_admin_notice_blackfriday' );
}