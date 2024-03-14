<?php
class WC_PMCS_Currency_Field
{
	protected $list_currency;


	public function __construct()
	{
		$this->setup_currencies();
		add_action('woocommerce_admin_field_currency_list', array($this, 'field'));
		add_action('admin_footer', array($this, 'template'));
		add_action('woocommerce_admin_settings_sanitize_option', array($this, 'sanitize_options'), 35, 3);
	}

	public function get_default()
	{
		$default = array(
			'currency_code'      => '',
			'sign_position'      => 'before',
			'thousand_separator' => ',',
			'decimal_separator'  => '.',
			'num_decimals'       => '2',
			'rate'               => '',
			'display_text'       => '',
		);
		return $default;
	}

	public function get_flag_url($code)
	{
		$url = PMCS_URL . '/assets/flags/' . strtolower($code) . '.png';
		return $url;
	}

	public function get_flag_folder()
	{
		$url = PMCS_URL . '/assets/flags/';
		return $url;
	}

	public function sanitize_options($value, $option, $raw_value)
	{
		if ('currency_list' == $option['type']) {
			$autoload = isset($option['autoload']) && 'yes' == $option['autoload'] ? 'yes' : 'no';
			$new_values = array();
			$default_code = isset($_POST['pmcs_default_currency']) ? sanitize_text_field(wp_unslash($_POST['pmcs_default_currency'])) : '';
			$default_args = array();

			foreach ($raw_value as $k => $v) {
				$v = wp_parse_args($v, $this->get_default());
				if ($v['currency_code']) {
					$new_values[] = $v;
					if ($v['currency_code'] == $default_code) {
						$default_args = $v;
					}
				}
			}

			update_option($option['id'], $new_values, $autoload);
			update_option('pmcs_default_currency', $default_code);
			if (!empty($default_args)) {
				foreach (pmcs()->admin->wc_currency_fields as $key => $index) {
					if (isset($default_args[$index])) {
						update_option($key, $default_args[$index]);
					}
				}
			}

			return null; // Skips save content.
		}

		return $value;
	}

	public function setup_currencies()
	{
		$currency_code_options = get_woocommerce_currencies();

		$this->list_currency = array();

		foreach ($currency_code_options as $code => $name) {
			$this->list_currency[$code] = $name . ' (' . get_woocommerce_currency_symbol($code) . ')';
		}

		if (!empty($this->list_currency)) {
			$this->list_currency = array_merge(array('' => __('Selectc currency', 'pmcs')), $this->list_currency);
		}
	}

	public function template()
	{
?>
		<script id="wc_pmcs_currency_row" type="text/html">
			<?php
			$this->currency_row();
			?>
		</script>
	<?php
	}

	public function currency_row($value = array(), $read_only = false)
	{
		if (!is_array($value)) {
			$value  = array();
		}
		$value = wp_parse_args($value, $this->get_default());
		$selections = array();
		$flag = false;
		$id = '';
		if ($value['currency_code']) {
			$flag = $this->get_flag_url($value['currency_code']);
			$id = uniqid('_');
		}
		$wc_currency_code = get_option('pmcs_default_currency');
		if (empty($wc_currency_code)) {
			$wc_currency_code = pmcs()->switcher->get_woocommerce_currency();
		}
		$is_default = $wc_currency_code == $value['currency_code'];

	?>
		<tr class="tr">
			<th class="td td_currency_default" width="25px">
				<input class="pmcs_default_currency" type="radio" <?php echo ($is_default) ? ' checked="checked" ' : ''; ?> name="pmcs_default_currency" value="<?php echo esc_attr($value['currency_code']); ?>">
			</th>
			<td class="td td_currency_code">
				<select data-name="__name__[__i__][currency_code]" style="width:250px" placeholder="<?php esc_attr_e('Select currency', 'pmcs'); ?>" class="pmcs-currency-select riname wc-enhanced-select">
					<?php
					if (!empty($this->list_currency)) {
						foreach ($this->list_currency as $key => $val) {
							echo '<option value="' . esc_attr($key) . '"' . wc_selected($key, $value['currency_code']) . '>' . esc_html($val) . '</option>'; // WPCS: XSS ok.
						}
					}
					?>
				</select>
			</td>
			<td class="td currency_position">
				<select class="riname" data-name="__name__[__i__][sign_position]"">
					<option <?php echo wc_selected('left', $value['sign_position']); ?> value=" left"><?php _e('Left'); ?></option>
					<option <?php echo wc_selected('right', $value['sign_position']); ?> value="right"><?php _e('Right'); ?></option>
					<option <?php echo wc_selected('left_space', $value['sign_position']); ?> value="left_space"><?php _e('Left with space'); ?></option>
					<option <?php echo wc_selected('right_space', $value['sign_position']); ?> value="right_space"><?php _e('Right with space'); ?></option>
				</select>
			</td>
			<td class="td num_seperator">
				<input size="2" class="riname" placeholder="," type="text" data-name="__name__[__i__][thousand_separator]" value="<?php echo esc_attr($value['thousand_separator']); ?>">
			</td>
			<td class="td num_seperator">
				<input size="2" class="riname" placeholder="." type="text" data-name="__name__[__i__][decimal_separator]" value="<?php echo esc_attr($value['decimal_separator']); ?>">
			</td>
			<td class="td num_seperator">
				<input size="2" placeholder="2" class="riname num_decimals" type="text" data-name="__name__[__i__][num_decimals]" value="<?php echo esc_attr($value['num_decimals']); ?>">
			</td>
			<td class="td rate">
				<input class="riname pmcs-rate" placeholder="0000.00" type="text" data-name="__name__[__i__][rate]" value="<?php echo esc_attr($value['rate']); ?>">
			</td>
			<td class="td display_name">
				<input type="text" class="pmcs-currency-display riname" data-name="__name__[__i__][display_text]" placeholder="<?php esc_attr_e('Custom display name', 'pmcs'); ?>" value="<?php echo esc_attr($value['display_text']); ?>">
			</td>
			<td class="td currency_flag"><img src="<?php echo $flag; // WPCS: XSS ok. 
													?>" data-url="<?php echo esc_url($this->get_flag_folder()); ?>" alt="" /></td>
			<td class="td actions">
				<span class="pmcs-button secondary pmcs-sync-rate"><span class="dashicons dashicons-update"></span></span>
				<span class="pmcs-button secondary remove"><span class="dashicons dashicons-trash"></span></span>
				<span class="pmcs-button secondary handle"><span class="dashicons dashicons-sort"></span></span>
			</td>
		</tr>
	<?php
	}

	public function has_code($currencies, $code_check)
	{
		foreach ($currencies as $k => $c) {
			if ($c['currency_code'] == $code_check) {
				return true;
			}
		}
		return false;
	}

	public function field($option)
	{
		$value            = get_option($option['id'], $option['default']);
		$currencies       = get_woocommerce_currencies();
		$currency_code    = get_woocommerce_currency();
		$wc_currency_code = pmcs()->switcher->get_woocommerce_currency();
		if (!is_array($value)) {
			$value = array();
		}
		$has_default = $this->has_code($value, $wc_currency_code);
		if (!$has_default) {
			$value[$wc_currency_code] = array(
				'currency_code'      => $wc_currency_code,
				'sign_position'      => get_option('woocommerce_currency_pos'),
				'thousand_separator' => wc_get_price_thousand_separator(),
				'decimal_separator'  => wc_get_price_decimal_separator(),
				'num_decimals'       => wc_get_price_decimals(),
				'rate'               => 1,
				'display_text'       => $currencies[$wc_currency_code],
			);
		}

	?>
		<tr valign="top">
			<td colspan="2" style="padding-left: 0px; padding-right: 0px;">
				<table class="pmcs-table-lm pmcs-currencies-list wp-list-table widefat">
					<thead class="thead">
						<tr class="tr">
							<th class="td td_currency_default" width="25px"><?php _e('Default', 'pmcs'); ?></th>
							<th class="td td_currency_code" width="*"><?php _e('Currency', 'pmcs'); ?></th>
							<th class="td currency_position"><?php echo wc_help_tip('Currency Position'); ?></th>
							<th class="td num_seperator"><?php echo wc_help_tip('Thousand seperator', 'pmcs'); ?></th>
							<th class="td num_seperator"><?php echo wc_help_tip('Decimal seperator', 'pmcs'); ?></th>
							<th class="td num_seperator"><?php echo wc_help_tip('Number of decimals', 'pmcs'); ?></th>
							<th class="td rate"><?php _e('Rate', 'pmcs'); ?></th>
							<th class="td display_name"><?php _e('Display name', 'pmcs'); ?></th>
							<th class="td currency_flag">&nbsp;</th>
							<th class="td actions">&nbsp;</th>
						</tr>
					</thead>
					<tbody id="the-list" data-name="<?php echo esc_attr($option['id']); ?>" class="tbody the-list">
						<?php
						if (is_array($value)) {
							foreach ($value as $v) {
								$this->currency_row($v);
							}
						}
						?>
					</tbody>
				</table>


				<?php
			
				?>
					<p class="pmcs-limit-currency-msg" style="display:none;">
						<?php
						printf(
							__('By default Currency Swicther support 2 currencies only. Please update to %s to add more currencies.', 'pmcs'),
							'<a href="' . PMCS_PRO_URL . '">' . __('Pro version', 'pmcs') . '</a>'
						);
						?>
					</p>
				<?php
				
				?>


				<button class="button secondary pmcs-add-currency-list" type="button"><?php _e('Add new currency', 'pmcs'); ?></button>
				<button class="button secondary pmcs-currency-sync-all" type="button"><?php _e('Sync all currency rates', 'pmcs'); ?></button>
			</td>
		</tr>
<?php
	}
}

new WC_PMCS_Currency_Field();
