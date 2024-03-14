<?php
defined( 'ABSPATH' ) || exit;

class xlwcty_Input_Geo_Postal_Code_Entry {

	public function __construct() {
		// vars
		$this->type = 'Geo_Postal_Code_Entry';

		$this->defaults = array(
			'multiple'      => 0,
			'allow_null'    => 0,
			'choices'       => array(),
			'default_value' => '',
			'class'         => '',
			'placeholder'   => '',
		);
	}

	public function render( $field, $value = null ) {
		$field = array_merge( $this->defaults, $field );
		if ( ! isset( $field['id'] ) ) {
			$field['id'] = sanitize_title( $field['id'] );
		}
		?>

        <table style="width:100%;">
            <tr>
                <td style="width:162px;"><?php _e( 'Distance ( km )', 'woo-thank-you-page-nextmove-lite' ); ?></td>
                <td><?php _e( 'Zip/Postalcode ( One per line )', 'woo-thank-you-page-nextmove-lite' ); ?></td>
            </tr>
            <tr>
                <td style="width:162px; vertical-align:top;">
                    <input type="text" id="<?php echo $field['id']; ?>_qty" name="<?php echo $field['name']; ?>[qty]" value="<?php echo isset( $value['qty'] ) ? $value['qty'] : 1; ?>"/>
                </td>
                <td>
					<?php echo '<textarea style="width:100%" rows="20" name="' . $field['name'] . '[codes]" type="text" id="' . esc_attr( $field['id'] ) . '" class="' . esc_attr( $field['class'] ) . '" placeholder="' . esc_attr( $field['placeholder'] ) . '">' . esc_textarea( $value['codes'] ) . '</textarea>'; ?>
                </td>
                </td>
            </tr>
        </table>
		<?php
	}

}
