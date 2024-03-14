<?php

defined( 'ABSPATH' ) or die( 'This plugin requires WordPress' );

$fmc_settings = get_option( 'fmc_settings' );
$search_listing_template_version = $fmc_settings['search_listing_template_version'];
$search_listing_template_primary_color = $fmc_settings['search_listing_template_primary_color'];
$search_listing_template_heading_font = $fmc_settings['search_listing_template_heading_font'];
$search_listing_template_body_font = $fmc_settings['search_listing_template_body_font'];

add_thickbox();

?>
<form action="<?php echo admin_url( 'admin.php?page=fmc_admin_settings&tab=style' ); ?>" method="post">
	<h3>Template Settings</h3>
	<table class="form-table">
		<tbody>
			<tr>
				<th scope="row">
					<label for="search-listing-template-version">Which version of search &amp; listing templates to use?</label>
				</th>
				<td>
					<p>
						<select id="search-listing-template-version" name="fmc_settings[search_listing_template_version]">
							<option value="v1" <?php selected( $search_listing_template_version, 'v1' ); ?>>Version 1</option>
							<option value="v2" <?php selected( $search_listing_template_version, 'v2' ); ?>>Version 2</option>
						</select>
					</p>
				</td>
			</tr>
		</tbody>
	</table>

	<h3>Colors</h3>
	<p>Used for various text and button elements on the search &amp; listing templates.<br>Only used if you've selected "Version 2" above.</p>

	<table class="form-table">
		<tbody>
			<tr>
				<th scope="row">
					<label for="search-listing-template-primary-color">Primary color</label>
				</th>
				<td>
					<p>
						<input type="text" name="fmc_settings[search_listing_template_primary_color]" class="wp-color-picker" value="<?php echo esc_attr( $search_listing_template_primary_color ); ?>">
					</p>
				</td>
			</tr>
		</tbody>
	</table>

	<h3>Typography</h3>
	<p>Used for text on the search &amp; listing templates. Leave as "default" to use theme fonts.<br>Only used if you've selected "Version 2" above.</p>

	<table class="form-table">
		<tbody>
			<tr>
				<th scope="row">
					<label for="search-listing-template-heading-font">Heading font</label>
				</th>
				<td>
					<p>
						<input type="text"
							class="font-picker"
							id="search-listing-template-heading-font"
							name="fmc_settings[search_listing_template_heading_font]"
							value="<?php echo esc_attr( $search_listing_template_heading_font ); ?>"
							data-fonts='<?php echo json_encode( fmcWidget::available_fonts() ); ?>'
							>
					</p>
				</td>
			</tr>
			<tr>
				<th scope="row">
					<label for="search-listing-template-body-font">Body font</label>
				</th>
				<td style="padding-bottom: 120px;">
					<p>
						<input type="text"
							class="font-picker"
							id="search-listing-template-body-font"
							name="fmc_settings[search_listing_template_body_font]"
							value="<?php echo esc_attr( $search_listing_template_body_font ); ?>"
							data-fonts='<?php echo json_encode( fmcWidget::available_fonts() ); ?>'
							>
					</p>
				</td>
			</tr>
		</tbody>
	</table>

	<script type="text/javascript">
		jQuery( '.font-picker' ).each( function () {
			jQuery( this ).fontselect( {
				googleFonts: false,
				systemFonts: jQuery( this ).data( 'fonts' )
			} );
		} );
	</script>

	<p><?php wp_nonce_field( 'update_fmc_style_action', 'update_fmc_style_nonce' ); ?><button type="submit" class="button-primary">Save Settings</button></p>
</form>
