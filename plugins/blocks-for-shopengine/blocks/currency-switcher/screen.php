<?php

use ShopEngine\Utils\Helper;

defined('ABSPATH') || exit;

$module_list = \ShopEngine\Core\Register\Module_List::instance();
if ($module_list->get_list()['currency-switcher']['status'] === 'active') :
	$module_settings = $module_list->get_settings('currency-switcher');
	$session_currency_code = \ShopEngine_Pro\Modules\Currency_Switcher\Base\Currency_Switcher_Frontend::instance()->find_currency($module_settings)['code'];
?>
	<div class="shopengine shopengine-widget">
		<div class="shopengine-currency-switcher">
			<i class="shopengine-currency-switcher--icon eicon-angle-right"></i>
			<select onchange="shopengine_currency_switcher(this.value)" class="shopengine-currency-switcher--select">
				<option value=""><?php echo esc_attr($settings['shopengine_default_text']['desktop']); ?></option>
				<?php foreach ($module_settings['currencies']['value'] as $currency) :
					$symbol = '';
					if ($module_settings['symbol_show_dropdown']['value'] === 'yes') {
						$symbol = $currency['symbol'] . ' ';
					}
				?>
					<option <?php if ($currency['code'] === $session_currency_code) : echo 'selected';
							endif; ?> value="<?php echo esc_attr($currency['code']) ?>"><?php echo wp_kses($symbol, Helper::get_kses_array()) . esc_html($currency['name']); ?></option>
				<?php endforeach; ?>
			</select>
		</div>
	</div>
<?php
elseif ($block->is_editor) :
	esc_html_e('Please active shopengine currency switcher module', 'shopengine-gutenberg-addon');
endif; ?>