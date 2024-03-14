<?php
namespace webaware\gf_dpspxpay;

if (!defined('ABSPATH')) {
	exit;
}
?>

<div class="notice notice-error">
	<p>
		<?= gf_dpspxpay_external_link(
				sprintf(esc_html__('GF Windcave Free requires {{a}}Gravity Forms{{/a}} version %1$s or higher; your website has Gravity Forms version %2$s.', 'gravity-forms-dps-pxpay'),
					esc_html(MIN_VERSION_GF), esc_html(GFCommon::$version)),
				'https://webaware.com.au/get-gravity-forms'
			); ?>
	</p>
</div>
