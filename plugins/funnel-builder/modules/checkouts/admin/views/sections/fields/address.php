<?php
/**
 * @var $this WFACP_admin
 * @var $id
 */

$data            = $this->get_localize_data();
$address_options = WFACP_Common::get_single_address_fields( $id );
?>
<div class="wfacp_billing">
    <div class="wfacp_billing_row">
        <div class="wfacp_billing_label required">
            <label for="label_<?php echo $id ?>">
                <span><?php _e( 'Label', 'woofunnels-aero-checkout' ) ?></span>
            </label>
        </div>
        <div class="wfacp_billing_field-wrap">
            <div class="wrapper">
                <input id="label_<?php echo $id ?>" type="text" required="required" class="form-control" value="<?php echo $address_options['label'] ?>" readonly>
            </div>
        </div>
        <div class="wfacp_billing_clear"></div>
    </div>

    <!-----  ACCORDION STARTS HERE  ------->
    <div class="wfacp_accordion wfacp_address_sortable_area" id="wfacp_address_field_<?php echo $address_options['id'] ?>" data-address-type="<?php echo $address_options['id'] ?>">
		<?php
		$main_options      = $address_options['fields_options'];
		$same_as_field     = [];
		$same_key          = '';
		$addressOrder      = WFACP_Common::get_address_field_order( WFACP_Common::get_id() );
		$options           = $this->arrange_order_of_address_fields( $main_options, $addressOrder, $id );
		$temp_main_options = $main_options;
		foreach ( $options as $k => $v ) {
			if ( isset( $temp_main_options[ $k ] ) ) {
				unset( $temp_main_options[ $k ] );
			}
		}
		if ( count( $temp_main_options ) > 0 ) {
			$options = array_merge( $options, $temp_main_options );
		}
		foreach ( $options as $key => $field ) {
			$main_field    = array_values( $main_options[ $key ] );
			$display_label = $main_field[1];
			if ( isset( $options[ $key ] ) ) {
				$field = $options[ $key ];
			}
			if ( $key == 'same_as_shipping' || $key == 'same_as_billing' ) {
				$same_key              = $key;
				$same_as_field         = array_values( $field );
				$same_as_display_lable = $display_label;
				continue;
			}


			$field       = array_values( $field );
			$status      = wc_string_to_bool( $field['0'] );
			$label       = isset( $field['1'] ) ? $field['1'] : '';
			$placeholder = isset( $field['2'] ) ? $field['2'] : '';
			$hint        = isset( $field['3'] ) ? $field['3'] : '';
			$required    = isset( $field['4'] ) ? $field['4'] : '';

			$is_visible = 'dashicons-visibility';
			if ( ! $status ) {
				$is_visible = 'dashicons-hidden';
			}
			$unique_id = uniqid( 'wfacp_' ) . "_";
			?>
            <!------  FIRST NAME TAB STARTS  ----->
            <div class="wfacp_address_field wfacp_sortable_address_field" data-key="<?php esc_attr_e( $key ) ?>">
                <div class="wfacp_billing_accordion_inner">
                    <div class="accordion_left">
                        <i class="wfacp_drag_address_field_enable dashicons <?php echo $is_visible ?>"></i>
                        <div class="accordion_heading"><?php esc_html_e( $display_label ) ?></div>
                        <div class="wfacp_hint_small"><small><?php esc_html_e( $hint ) ?></small></div>
                    </div>
                    <div class="accordion_right">
                        <i class="dashicons dashicons-menu-alt3 wfacp_drag_address_icon"></i>
                        <i class="dashicons dashicons-arrow-down wfacp_address_open_accordion"></i>
                    </div>
                    <div class="wfacp_billing_clear"></div>
                </div>
                <div class="wfacp_billing_accordion_content">
                    <div class="wfacp_row_billing">
                        <div class="wfacp_billing_left">
                            <label for="<?php esc_html_e( $unique_id ) ?>field_label_<?php esc_html_e( $key ) ?>"><?php _e( 'Label', 'woofunnels-aero-checkout' ) ?></label>
                        </div>
                        <div class="wfacp_billing_right">
                            <input id="<?php esc_html_e( $unique_id ) ?>field_label_<?php esc_html_e( $key ) ?>" type="text" class="form-control wfacp_label" value="<?php echo esc_js( $label ) ?>">
                        </div>
                    </div>
                    <div class="wfacp_row_billing">
                        <div class="wfacp_billing_left">
                            <label for="<?php esc_html_e( $unique_id ) ?>field_label_placeholder_<?php esc_html_e( $key ) ?>"><?php _e( 'Placeholder', 'woofunnels-aero-checkout' ) ?></label>
                        </div>
                        <div class="wfacp_billing_right">
                            <input id="<?php esc_html_e( $unique_id ) ?>field_label_placeholder_<?php esc_html_e( $key ) ?>" type="text" class="form-control wfacp_placeholder" value="<?php echo esc_js( $placeholder ) ?>">
                        </div>
                    </div>
                    <div class="wfacp_row_billing">
                        <div class="wfacp_billing_left">
                            <label for="<?php esc_html_e( $unique_id ) ?>field_label_required_<?php esc_html_e( $key ) ?>"><?php _e( 'Required', 'woofunnels-aero-checkout' ) ?></label>
                        </div>
                        <div class="wfacp_billing_right">
                            <input id="<?php esc_html_e( $unique_id ) ?>field_label_required_<?php esc_html_e( $key ) ?>" type="checkbox" class="form-control wfacp_required" <?php echo wc_string_to_bool( $required ) ? 'checked' : '' ?>>
                        </div>
                    </div>
                    <div class="wfacp_billing_clear"></div>
                </div>
            </div>
			<?php
		}
		if ( '' !== $same_key ) {

			$status = wc_string_to_bool( $same_as_field['0'] );

			$label   = isset( $same_as_field['1'] ) ? $same_as_field['1'] : '';
			$label_2 = isset( $same_as_field['2'] ) ? $same_as_field['2'] : '';

			$is_visible = 'dashicons-visibility';
			if ( ! $status ) {
				$is_visible = 'dashicons-hidden';
			}
			$address_type = $addressOrder[ 'display_type_' . $address_options['id'] ];
			?>
            <div class="wfacp_address_field wfacp_not_sortable_address_field" data-key="<?php esc_attr_e( $same_key ) ?>">
                <div class="wfacp_billing_accordion_inner">
                    <div class="accordion_left">
                        <i class="dashicons wfacp_drag_address_field_enable <?php echo esc_js( $is_visible ) ?>"></i>
                        <div class="accordion_heading"><?php echo esc_js( $same_as_display_lable ) ?></div>
                    </div>
                    <div class="accordion_right">
                        <i class="dashicons dashicons-arrow-down wfacp_address_open_accordion"></i>
                    </div>
                    <div class="wfacp_billing_clear"></div>
                </div>
                <div class="wfacp_billing_accordion_content">
                    <div class="wfacp_row_billing">
                        <div class="wfacp_billing_left">
                            <label><?php echo __( 'Label', 'woofunnels-aero-checkout' ) ?></label>
                        </div>
                        <div class="wfacp_billing_right">
                            <input type="text" class="form-control wfacp_label" value="<?php echo esc_js( $label ) ?>">
                        </div>
                    </div>
					<?php
					do_action( 'wfacp_admin_' . $id . '_address_field', $same_as_field );
					?>
                </div>
            </div>
		<?php } ?>
    </div>
</div>