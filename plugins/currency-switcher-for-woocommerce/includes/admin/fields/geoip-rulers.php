<?php
class WC_PMCS_GeoIP_Rulers_Field {
	protected $list_currency;
	public function __construct() {
		$this->setup_currencies();
		add_action( 'woocommerce_admin_field_geoip_rulers', array( $this, 'add_field' ) );
		add_action( 'woocommerce_admin_settings_sanitize_option', array( $this, 'sanitize_options' ), 35, 3 );
	}

	public function get_default() {
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

	public function get_flag_url( $code ) {
		$url = PMCS_URL . '/assets/flags/' . strtolower( $code ) . '.png';
		return $url;
	}

	public function get_flag_folder() {
		$url = PMCS_URL . '/assets/flags/';
		return $url;
	}

	public function sanitize_options( $value, $option, $raw_value ) {
		if ( 'geoip_rulers' == $option['type'] ) {
			$autoload = isset( $option['autoload'] ) && 'yes' == $option['autoload'] ? 'yes' : 'no';
			update_option( $option['id'], $value, $autoload );
			return null; // Skips save content.
		}

		return $value;
	}

	public function setup_currencies() {
		$currency_code_options = get_woocommerce_currencies();
		$this->list_currency = array();
		foreach ( $currency_code_options as $code => $name ) {
			$this->list_currency[ $code ] = $name . ' (' . get_woocommerce_currency_symbol( $code ) . ')';
		}
	}

	public function currency_row( $value = array(), $id = '', $saved_values = array() ) {
		$wc_currency_code = get_option( 'pmcs_default_currency' );
		if ( empty( $wc_currency_code ) ) {
			$wc_currency_code = pmcs()->switcher->get_woocommerce_currency();
		}

		$attrs = ' multiple="multiple" ';

	
			$countries = array(
				'ec_1' => __( 'Example Country 1', 'pmcs' ),
				'ec_2' => __( 'Example Country 2', 'pmcs' ),
			);
			$selected = array( 'ec_1', 'ec_2' );
			pmcs()->admin->set_show_submit_btn( false ); 
			$attrs .= ' disabled="disabled" ';
		

		$selections = array();
		$flag = false;
		if ( $value['currency_code'] ) {
			$flag = $this->get_flag_url( $value['currency_code'] );
		}
		$text = $value['currency_code'];
		if ( isset( $this->list_currency[ $value['currency_code'] ] ) ) {
			$text = $this->list_currency[ $value['currency_code'] ];
		}

		$select_name = $id . '[' . $value['currency_code'] . '][]';

		?>
		<tr class="tr <?php echo ( $wc_currency_code == $value['currency_code'] ? 'default-currency' : '' ); ?>">
			<td>
				<?php echo $text; // WPCS: XSS ok. ?>
			</td>
			<td>
				<select <?php echo $attrs; // WPCS: XSS ok.  ?> name="<?php echo $select_name; // WPCS: XSS ok. ?>" class="geoip-select wc-enhanced-select">
					<?php
					foreach ( $countries as $key => $val ) {
						echo '<option value="' . esc_attr( $key ) . '"' . wc_selected( $key, $selected ) . '>' . esc_html( $val ) . '</option>'; // WPCS: XSS ok.
					}
					?>
				</select>
			</td>
		</tr>
		<?php
	}

	public function add_field( $option ) {
		$saved_values = get_option( $option['id'], $option['default'] );
		$currencies = get_option( 'pmcs_currencies', array() );
		if ( ! is_array( $saved_values ) ) {
			$saved_values = array();
		}

		?>
		<tr valign="top">
			<td colspan="2" style="padding-left: 0px; padding-right: 0px;">
				<table class="pmcs-table-lm pmcs-geoip-list wp-list-table striped widefat">
					<tbody id="the-list" data-name="<?php echo esc_attr( $option['id'] ); ?>" class="tbody the-list">
						<?php
						foreach ( (array) $currencies as $key => $currency ) {
							$currency = wp_parse_args( $currency, $this->get_default() );
							$this->currency_row( $currency, $option['id'], $saved_values );
						}
						?>
					</tbody>
				</table>
			</td>
		</tr>
		<?php
	}

}

new WC_PMCS_GeoIP_Rulers_Field();




