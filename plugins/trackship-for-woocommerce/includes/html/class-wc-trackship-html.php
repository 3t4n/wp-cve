<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WC_Trackship_Html {

	/**
	 * Initialize the main plugin function
	*/
	public function __construct() {
		
	}

	/**
	 * Instance of this class.
	 *
	 * @var object Class Instance
	 */
	private static $instance;
	
	/**
	 * Get the class instance
	 *
	 * @return WC_Trackship_Html
	*/
	public static function get_instance() {

		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function get_multiple_select_html( $id, $array ) {
		?>
		<li class="multiple_select_li dis_block">
			<label><?php esc_html_e( $array['title'] ); ?>
				<?php if ( isset($array['tooltip']) ) { ?>
					<span class="woocommerce-help-tip tipTip" title="<?php esc_html_e( $array['tooltip'] ); ?>"></span>
				<?php } ?>
			</label>
			<div class="multiple_select_container">	
				<select multiple class="wc-enhanced-select" name="<?php echo esc_html( $id ); ?>[]" id="<?php echo esc_html( $id ); ?>">
				<?php
				$multi_checkbox_data = get_trackship_settings( $id, ['completed', 'partial-shipped', 'shipped'] );
				foreach ( (array) $array['options'] as $key => $val ) { 
					$selected = in_array( $key, $multi_checkbox_data ) ? 'selected' : '';
					?>
					<option value="<?php echo esc_attr( $key ); ?>" <?php echo esc_html( $selected ); ?>><?php echo esc_html( $val ); ?></option>
				<?php } ?>
				</select>
			</div>
		</li>
		<?php
	}

	public function get_tgl_checkbox_html( $id, $array ) {
		if ( get_trackship_settings($id) ) {
			$checked = 'checked';
		} else {
			$checked = '';
		}
		?>
		<li>
			<input type="hidden" name="<?php echo esc_html( $id ); ?>" value="0"/>
			<input class="ast-tgl ast-tgl-flat" id="<?php echo esc_html( $id ); ?>" name="<?php echo esc_html( $id ); ?>" type="checkbox" <?php echo esc_html( $checked ); ?> value="1"/>
			<label class="ast-tgl-btn" for="<?php echo esc_html( $id ); ?>"></label>
								
			<label class="setting_ul_tgl_checkbox_label" for="<?php echo esc_html( $id ); ?>"><?php echo esc_html( $array['title'] ); ?>
			<?php if ( isset( $array['tooltip'] ) ) { ?>
				<span class="woocommerce-help-tip tipTip" title="<?php echo esc_html( $array['tooltip'] ); ?>"></span>
			<?php } ?>
			</label>
			<?php if ( isset( $array['customize_link'] ) ) { ?>
				<a href="<?php echo esc_url( $array['customize_link'] ); ?>" class="button-primary btn_outline"><?php esc_html_e( 'Customize', 'trackship-for-woocommerce' ); ?></a>
			<?php } ?>
		</li>
		<?php
	}

	public function get_tgl_checkbox( $id, $array ) {
		if ( get_trackship_settings($id) ) {
			$checked = 'checked';
		} else {
			$checked = '';
		}
		?>
		<span class="<?php echo isset( $array['class'] ) ? esc_html( $array['class'] ) : ''; ?>">
			<input type="hidden" name="<?php echo esc_html( $id ); ?>" value="0"/>
			<input class="ast-tgl ast-tgl-flat" id="<?php echo esc_html( $id ); ?>" name="<?php echo esc_html( $id ); ?>" data-settings="<?php echo isset( $array['settings'] ) ? esc_html( $array['settings'] ) : ''; ?>" type="checkbox" <?php echo esc_html( $checked ); ?> value="1"/>
			<label class="ast-tgl-btn" for="<?php echo esc_html( $id ); ?>"></label>
		</span>
		<?php
	}

	public function get_number_html( $id, $array ) {
		?>
		<li class="dis_block">
			<label><?php esc_html_e( $array['title'] ); ?>
				<?php if ( isset($array['tooltip']) ) { ?>
					<span class="woocommerce-help-tip tipTip" title="<?php esc_html_e( $array['tooltip'] ); ?>"></span>
				<?php } ?>
			</label>
			<input class="input-text" type="number" name="<?php echo esc_html( $id ); ?>" id="<?php echo esc_html( $id ); ?>" min="1" value="<?php echo esc_attr(get_trackship_settings( $id, isset( $array['default'] ) ? $array['default'] : '' )); ?>">
		</li>
		<?php
	}

	public function get_dropdown_tpage_html( $id, $array ) {
		?>
		<li class="li_<?php esc_html_e( $id ); ?>">
			<label class="left_label"><b><?php esc_html_e( $array['title'] ); ?></b>
				<?php if ( isset( $array['tooltip'] ) ) { ?>
					<span class="woocommerce-help-tip tipTip" title="<?php esc_html_e( $array['tooltip'] ); ?>"></span>
				<?php } ?>
			</label>
			<span style="display: block; padding-top: 10px;">
				<select class="select select2 tracking_page_select" id="<?php echo esc_html( $id ); ?>" name="<?php echo esc_html( $id ); ?>">
					<?php foreach ( (array) $array['options'] as $page_id => $page_name ) { ?>
						<option <?php echo get_trackship_settings( $id ) == $page_id ? 'selected' : ''; ?> value="<?php echo esc_html( $page_id ); ?>"><?php esc_html_e( $page_name ); ?></option>
					<?php } ?>
					<option <?php echo 'other' == get_trackship_settings( $id ) ? 'selected' : ''; ?> value="other"><?php esc_html_e( 'Other', 'trackship-for-woocommerce' ); ?>
					</option>
				</select>
				<fieldset style="<?php echo 'other' != get_trackship_settings( $id ) ? 'display:none;' : 'padding-top: 10px;'; ?>" class="trackship_other_page_fieldset">
					<input type="text" name="wc_ast_trackship_other_page" id="wc_ast_trackship_other_page" value="<?php echo esc_html( get_trackship_settings('wc_ast_trackship_other_page') ); ?>">
				</fieldset>
				<p class="tracking_page_desc"><?php esc_html_e( 'Add the [trackship-track-order] shortcode in the selected page.', 'trackship-for-woocommerce' ); ?> <a href="https://www.zorem.com/docs/woocommerce-advanced-shipment-tracking/integration/" target="blank"><?php esc_html_e( 'more info', 'trackship-for-woocommerce' ); ?></a></p>
			</span>
		</li>
		<?php
	}

	public function get_button_html( $id, $array ) {
		?>
		<li>
			<?php if ( $array['title'] ) { ?>
				<label class="left_label"><?php echo esc_html( $array['title'] ); ?>
					<?php if ( isset($array['tooltip']) ) { ?>
					<span class="woocommerce-help-tip tipTip" title="<?php echo esc_html( $array['tooltip'] ); ?>"></span>
					<?php } ?>
				</label>
			<?php } ?>
			<?php if ( isset($array['customize_link']) ) { ?>
				<a href="<?php echo esc_url( $array['customize_link'] ); ?>" target="_blank" class="button-primary btn_ts_sidebar ts_customizer_btn"><?php esc_html_e( 'Customize the Tracking Widget', 'trackship-for-woocommerce' ); ?></a>
			<?php } ?>
		</li>
		<?php
	}

	public function get_text_html( $id, $array ) {
		?>
		<li class="dis_block">
			<?php if ( $array['title'] ) { ?>
				<label class="left_label"><?php echo esc_html( $array['title'] ); ?>
					<?php if ( isset($array['tooltip']) ) { ?>
					<span class="woocommerce-help-tip tipTip" title="<?php echo esc_html( $array['tooltip'] ); ?>"></span>
					<?php } ?>
				</label>
			<?php } ?>
			<fieldset>
				<input class="input-text regular-input " type="text" name="<?php echo esc_html( $id ); ?>" id="<?php echo esc_html( $id ); ?>" placeholder="<?php esc_html_e( 'E.g. {admin_email}, admin@example.org' ); ?>" value="<?php echo esc_attr(get_trackship_settings( $id, get_option('admin_email') )); ?>">
			</fieldset>
		</li>
		<?php
		
	}

	public function get_time_html( $id, $array ) {
		?>
		<li class="dis_block">
			<?php if ( $array['title'] ) { ?>
				<label class="left_label"><?php echo esc_html( $array['title'] ); ?>
					<?php if ( isset($array['tooltip']) ) { ?>
					<span class="woocommerce-help-tip tipTip" title="<?php echo esc_html( $array['tooltip'] ); ?>"></span>
					<?php } ?>
				</label>
			<?php } ?>
			<?php 
			$send_time_array = array();
			for ( $hour = 0; $hour < 24; $hour++ ) {
				for ( $min = 0; $min < 60; $min = $min + 30 ) {
					$this_time = gmdate( 'H:i', strtotime( "$hour:$min" ) );
					$send_time_array[ $this_time ] = $this_time;
				}
			}
			?>
			<select class="select daily_digest_time" name="<?php echo esc_html( $id ); ?>">
				<?php foreach ( (array) $send_time_array as $key1 => $val1 ) { ?>
					<option <?php echo get_trackship_settings( $id ) == $key1 ? 'selected' : ''; ?> value="<?php echo esc_html( $key1 ); ?>" ><?php echo esc_html( $val1 ); ?></option>
				<?php } ?>
			</select>
		</li>
		<?php
	}
}
