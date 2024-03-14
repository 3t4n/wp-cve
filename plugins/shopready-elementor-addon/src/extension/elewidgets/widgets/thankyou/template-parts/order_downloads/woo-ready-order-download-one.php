<?php
if (!defined('ABSPATH')) {
	exit;
}

$downloads = $order->get_downloadable_items();
$show_downloads = $order->has_downloadable_item() && $order->is_download_permitted();
$woo_ready_dl_show = true;
$woo_ready_dl_title = shop_ready_gl_get_setting('woo_ready_enable_thankyou_order_download_title', 'yes') == 'yes' ? true : false;
$show_heading = $settings['show_heading'];

if ($show_heading == '') {
	$woo_ready_dl_title = false;
}


if ($show_downloads && $woo_ready_dl_show) {

	wc_get_template(
		'order/order-downloads.php',
		array(
			'downloads' => $downloads,
			'show_title' => $woo_ready_dl_title,
		)
	);
}

?>