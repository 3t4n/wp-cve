<?php
/**
 * @var string[]                $value               .
 * @var array<string, string[]> $shipping_continents .
 * @var string[]                $allowed_countries   .
 * @var World                   $region_all          .
 * @var WC_Countries            $wc_countries        .
 */

use Octolize\Shipping\Notices\Model\World;

defined( 'ABSPATH' ) || exit;

?>

<tr valign="top">
	<th scope="row" class="titledesc">
		<label for="<?php echo esc_attr( $value['id'] ); ?>">
			<?php echo esc_html( $value['title'] ); ?>

			<?php if ( isset( $value['desc_tip'] ) && ! empty( $value['desc_tip'] ) ) : ?>
				<?php echo wc_help_tip( $value['desc_tip'] );// WPCS: XSS ok. ?>
			<?php endif; ?>
		</label>
	</th>
	<td class="forminp">
		<select multiple="multiple"
				data-attribute="zone_locations"
				id="<?php echo esc_attr( $value['id'] ); ?>"
				name="<?php echo esc_attr( $value['id'] ); ?>[]"
				required
				data-placeholder="<?php esc_attr_e( 'Select regions for this notice', 'octolize-shipping-notices' ); ?>"
				class="wc-shipping-zone-region-select chosen_select">
			<?php
			echo '<option value="' . esc_attr( $region_all->get_code() ) . '" ' . wc_selected( $region_all->get_code(), $value['value'] ) . '>' . wp_kses_post( $region_all->get_name() ) . '</option>'; //phpcs:ignore

			foreach ( $shipping_continents as $continent_code => $continent ) {
				echo '<option value="continent:' . esc_attr( $continent_code ) . '"' . wc_selected( "continent:$continent_code", $value['value'] ) . '>' . esc_html( $continent['name'] ) . '</option>'; //phpcs:ignore

				// @phpstan-ignore-next-line
				$countries = array_intersect( array_keys( $allowed_countries ), $continent['countries'] ); //phpcs:ignore

				foreach ( $countries as $country_code ) {
					echo '<option value="country:' . esc_attr( $country_code ) . '"' . wc_selected( "country:$country_code", $value['value'] ) . '>' . esc_html( '&nbsp;&nbsp; ' . $allowed_countries[ $country_code ] ) . '</option>'; //phpcs:ignore

					$states = $wc_countries->get_states( $country_code );

					if ( $states ) {
						foreach ( $states as $state_code => $state_name ) {
							echo '<option value="state:' . esc_attr( $country_code . ':' . $state_code ) . '"' . wc_selected( "state:$country_code:$state_code", $value['value'] ) . '>' . esc_html( '&nbsp;&nbsp;&nbsp;&nbsp; ' . $state_name . ', ' . $allowed_countries[ $country_code ] ) . '</option>'; //phpcs:ignore
						}
					}
				}
			}
			?>
		</select>

		<?php if ( isset( $value['desc'] ) && ! empty( $value['desc'] ) ) : ?>
			<p class="description"><?php echo wp_kses_post( $value['desc'] ); ?></p>
		<?php endif; ?>
	</td>
</tr>
