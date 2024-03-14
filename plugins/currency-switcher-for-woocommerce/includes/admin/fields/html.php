<?php
class WC_PMCS_HTML_Field {
	public function __construct() {
		add_action( 'woocommerce_admin_field_pmcs_custom_html', array( $this, 'add_field' ) );
	}

	public function add_field( $value ) {
		// Description handling.
		$field_description = WC_Admin_Settings::get_field_description( $value );
		$description       = $field_description['description'];
		$tooltip_html      = $field_description['tooltip_html'];

		if ( isset( $value['hook'] ) && $value['hook'] ) {
			?>
			<tr valign="top">
				<td colspan="2" style="padding-left: 0px; padding-top: 0px;">
					<?php do_action( $value['hook'] ); ?>
				</td>
			</tr>
			<?php
		} else {

			?>
		<tr valign="top">
			<th scope="row" class="titledesc">
				<label for="<?php echo esc_attr( $value['id'] ); ?>"><?php echo esc_html( $value['title'] ); ?> <?php echo $tooltip_html; // WPCS: XSS ok. ?></label>
			</th>
			<td class="forminp forminp-<?php echo esc_attr( sanitize_title( $value['type'] ) ); ?>">
				<?php

				if ( isset( $value['html'] ) ) {
					echo '<div class="custom-html">' . $value['html'] . '</div>'; // WPCS: XSS ok.
				}

				echo $description; // WPCS: XSS ok.

				?>
			</td>
		</tr>
			<?php
		}
	}

}

new WC_PMCS_HTML_Field();




