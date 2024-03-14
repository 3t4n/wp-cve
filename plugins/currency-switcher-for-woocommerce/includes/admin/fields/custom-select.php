<?php
class WC_PMCS_Custom_Select_Field {
	public function __construct() {
		add_action( 'woocommerce_admin_field_pmcs_custom_select', array( $this, 'add_field' ) );
	}

	public function add_field( $data ) {

		$defaults  = array(
			'title'             => '',
			'disabled'          => false,
			'class'             => '',
			'css'               => '',
			'placeholder'       => '',
			'type'              => 'text',
			'desc_tip'          => false,
			'description'       => '',
			'custom_attributes' => array(),
			'options'           => array(),
		);

		$data = wp_parse_args( $data, $defaults );

		$field_key = $data['id'];

		// Description handling.
		$field_description = WC_Admin_Settings::get_field_description( $data );
		$description       = $field_description['description'];
		$tooltip_html      = $field_description['tooltip_html'];
		$options           = $data['options'];

		$custom_attributes = array();

		if ( ! empty( $data['custom_attributes'] ) && is_array( $data['custom_attributes'] ) ) {
			foreach ( $data['custom_attributes'] as $attribute => $attribute_value ) {
				$custom_attributes[] = esc_attr( $attribute ) . '="' . esc_attr( $attribute_value ) . '"';
			}
		}

		$custom_attributes = implode( ' ', $custom_attributes );
		$save_value = get_option( $data['id'], $data['default'] );

		?>
		<tr valign="top">
			<th scope="row" class="titledesc">
				<label for="<?php echo esc_attr( $field_key ); ?>"><?php echo wp_kses_post( $data['title'] ); ?> <?php echo $tooltip_html; // WPCS: XSS ok. ?></label>
			</th>
			<td class="forminp">
				<fieldset>
					<legend class="screen-reader-text"><span><?php echo wp_kses_post( $data['title'] ); ?></span></legend>
					<select class="select <?php echo esc_attr( $data['class'] ); ?>" name="<?php echo esc_attr( $field_key ); ?>" id="<?php echo esc_attr( $field_key ); ?>" style="<?php echo esc_attr( $data['css'] ); ?>" <?php disabled( $data['disabled'], true ); ?> <?php echo $custom_attributes; // WPCS: XSS ok. ?>>
						<?php foreach ( (array) $options as $option_key => $option_value ) : ?>
							<?php if ( ! is_array( $option_value ) ) { ?>
							<option value="<?php echo esc_attr( $option_key ); ?>" <?php selected( (string) $option_key, $save_value ); ?>><?php echo esc_attr( $option_value ); ?></option>
							<?php } else { ?>
								<option <?php echo ( isset( $option_value['disable'] ) && $option_value['disable'] ) ? ' disabled="disabled" ' : ''; ?> value="<?php echo esc_attr( $option_key ); ?>" <?php selected( (string) $option_key, $save_value ); ?>><?php echo esc_attr( $option_value['label'] ); ?></option>
							<?php } ?>
						<?php endforeach; ?>
					</select>
					<?php echo $description; // WPCS: XSS ok. ?>
				</fieldset>
			</td>
		</tr>
		

		<?php
	}

}

new WC_PMCS_Custom_Select_Field();




