<?php
/**
 * Shipping method rates table rendering
 *
 * @package     Boxtal\BoxtalConnectWoocommerce\Assets\Views
 */

use Boxtal\BoxtalConnectWoocommerce\Util\Misc_Util;
use Boxtal\BoxtalConnectWoocommerce\Branding;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$has_shipping_classes = count( $shipping_classes ) > 1;

?>
<table class="form-table" class="<?php echo esc_html( Branding::$branding_short ); ?>-shipping-method-info">
	<thead>
		<th class="<?php echo esc_html( Branding::$branding_short ); ?>-pricing-header">
			<b><?php esc_html_e( 'Pricing rules', 'boxtal-connect' ); ?></b>
			<p>
				<?php esc_html_e( 'Set up your rules regarding the shipping costs that will be diplayed for your clients in the checkout page. The rules are prioritized from top to bottom. If no rules is applicable, the shipping method won\'t be diplayed.', 'boxtal-connect' ); ?>
				<br/>
				<?php
				if ( null !== $help_center_link ) {
					/* translators: %1$1s: link start %2$2s: link end*/
					echo sprintf( esc_html__( 'Need some help ? Just follow the instructions on %1$sthis article%2$s.', 'boxtal-connect' ), '<a href="' . esc_url( $help_center_link ) . '" target="_blank">', '</a>' );
					/* translators: %1$1s: link start %2$2s: link end*/
					echo sprintf( esc_html__( 'In need of some advice about shipping costs ? Follow %1$sthis link%2$s.', 'boxtal-connect' ), '<a href="' . esc_url( $help_center_link ) . '" target="_blank">', '</a>' );
				}
				?>
				<br/>
				<span class="description light">
					<b>
					<?php
						/* translators: %1$1s: company name */
						echo sprintf( esc_html__( '%1$s Tips', 'boxtal-connect' ), esc_html( Branding::$company_name ) );
					?>
					</b> :
					<?php
					/* translators: %1$1s: link start %2$2s: link end*/
					$bx_message = __( 'Once your rules are set up here, use our %1$sshipping rule%2$s to automatize the selection of a carrier offer, the subscription to our AXA insurance and stop importing the orders that are not to be processed through Boxtal (e.g. If the shipping method is "Store pickup")', 'boxtal-connect' );
					if ( null !== $help_center_link ) {
						echo sprintf( esc_html( $bx_message ), '<a href="' . esc_url( $help_center_link ) . '" target="_blank">', '</a>' );
					} else {
						echo sprintf( esc_html( $bx_message ), '', '' );
					}
					?>
				</span>
			</p>
		</th>
	</thead>
</table>
<table id="<?php echo esc_html( Branding::$branding_short ); ?>-rates-table" class="wc_input_table sortable widefat" data-default-shipping-class="<?php echo esc_html( array_keys( $shipping_classes )[0] ); ?>">
	<thead>
		<tr>
			<th rowspan="2" class="sort">&nbsp;</th>
			<th colspan="2" class="<?php echo esc_html( Branding::$branding_short ); ?>-center">
				<?php echo esc_html__( 'Cart price Excluding Tax', 'boxtal-connect' ) . ' (' . esc_html( get_woocommerce_currency_symbol() ) . ') '; ?>
			</th>
			<th colspan="2" class="<?php echo esc_html( Branding::$branding_short ); ?>-center"><?php echo esc_html__( 'Cart weight', 'boxtal-connect' ) . ' (kg)'; ?></th>
			<?php if ( $has_shipping_classes ) { ?>
			<th rowspan="2" class="<?php echo esc_html( Branding::$branding_short ); ?>-center">
				<?php
					echo '<span>' . esc_html__( 'Shipping class', 'boxtal-connect' ) . '</span>';
					$bx_tooltip_html  = '<ul><li>' . esc_html__( 'if you choose a shipping class, the rule will only apply to carts with all products belonging to the class', 'boxtal-connect' ) . '</li>';
					$bx_tooltip_html .= '<li>' . esc_html__( "Beware that newly created shipping classes won't be selected by default", 'boxtal-connect' ) . '</li></ul>';
					Misc_Util::echo_tooltip( $bx_tooltip_html );
				?>
			</th>
			<?php } ?>
			<th class="<?php echo esc_html( Branding::$branding_short ); ?>-center"><?php echo esc_html__( 'Parcel point\'s maps to show to your customers', 'boxtal-connect' ); ?></th>
			<th rowspan="2" class="w11 <?php echo esc_html( Branding::$branding_short ); ?>-center">
			<?php
				echo '<span class="mr2">' . esc_html__( 'Price displayed ex-Tax', 'boxtal-connect' ) . ' (' . esc_html( get_woocommerce_currency_symbol() ) . ')</span>';
				$bx_tooltip_html  = __( 'If you wish to offer the shipping for free, put 0.', 'boxtal-connect' ) . '<br/>';
				$bx_tooltip_html .= __( 'If you\'ve set up a shipping tax, it will be applied to this price for your client.', 'boxtal-connect' );
				Misc_Util::echo_tooltip( $bx_tooltip_html );
			?>
			</th>
			<th rowspan="2" class="w11 <?php echo esc_html( Branding::$branding_short ); ?>-center">
			<?php
				echo '<span class="mr2">' . esc_html__( 'Status', 'boxtal-connect' ) . '</span>';
			?>
			</th>
			<th rowspan="2" ></th>
		</tr>
		<tr>
			<th class="<?php echo esc_html( Branding::$branding_short ); ?>-center"><?php esc_html_e( 'From', 'boxtal-connect' ); ?> (≥)</th>
			<th class="<?php echo esc_html( Branding::$branding_short ); ?>-center"><?php esc_html_e( 'To', 'boxtal-connect' ); ?> (<)</th>
			<th class="<?php echo esc_html( Branding::$branding_short ); ?>-center"><?php esc_html_e( 'From', 'boxtal-connect' ); ?> (≥)</th>
			<th class="<?php echo esc_html( Branding::$branding_short ); ?>-center"><?php esc_html_e( 'To', 'boxtal-connect' ); ?> (<)</th>
			<th class="<?php echo esc_html( Branding::$branding_short ); ?>-center info-small">
				<?php esc_html_e( 'If you want your customers to be able to choose their parcel point in the checkout, select the networks below to display', 'boxtal-connect' ); ?>
			</th>
		</tr>
	</thead>
	<tbody class="ui-sortable">
		<?php
		if ( isset( $pricing_items ) && is_array( $pricing_items ) ) {
			$i = 0;
			foreach ( $pricing_items as $pricing_item ) {
				include 'html-admin-shipping-method-rate.php';
				$i++;
			}
		}
		?>
	</tbody>
</table>

<button class="<?php echo esc_html( Branding::$branding_short ); ?>-add-rate-line" data-action="<?php echo esc_html( Branding::$branding_short ); ?>_add_rate_line">
	<i class="dashicons dashicons-plus-alt"></i>
	<?php esc_html_e( 'Add rule', 'boxtal-connect' ); ?>
</button>

<input type="hidden" name="save" value="1">
