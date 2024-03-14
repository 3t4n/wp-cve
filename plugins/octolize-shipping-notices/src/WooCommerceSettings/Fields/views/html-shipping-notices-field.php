<?php
/**
 * @var string[]            $value                 .
 * @var ShippingNotice[]    $shipping_notices      .
 * @var SettingsActionLinks $settings_action_links .
 */

use Octolize\Shipping\Notices\Model\ShippingNotice;
use Octolize\Shipping\Notices\WooCommerceSettings\Actions\NoticesOrderAction;
use Octolize\Shipping\Notices\WooCommerceSettings\Actions\NoticesStatusAction;
use Octolize\Shipping\Notices\WooCommerceSettings\SettingsActionLinks;

defined( 'ABSPATH' ) || exit;

$shipping_notices = $value['shipping_notices'] ?? [];
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
		<p class="description" style="margin-bottom: 4px;"><?php echo esc_html( $value['desc'] ); ?></p>
		<table class="wc-shipping-zone-methods widefat">
			<thead>
			<tr>
				<th class="wc-shipping-zone-method-sort"></th>
				<th class="wc-shipping-zone-method-title"><?php esc_html_e( 'Notice title', 'octolize-shipping-notices' ); ?></th>
				<th class="wc-shipping-zone-method-enabled"><?php esc_html_e( 'Enabled', 'octolize-shipping-notices' ); ?></th>
				<th class="wc-shipping-zone-method-description"><?php esc_html_e( 'Zone regions', 'octolize-shipping-notices' ); ?></th>
			</tr>
			</thead>
			<tbody class="wc-shipping-zone-method-rows">
			<?php if ( empty( $shipping_notices ) ) : ?>
				<tr>
					<td class="wc-shipping-zone-method-blank-state" colspan="4">
						<p><?php esc_html_e( 'No notices were set up.', 'octolize-shipping-notices' ); ?></p>
					</td>
				</tr>
			<?php else : ?>
				<?php
				// @phpstan-ignore-next-line
				foreach ( $shipping_notices as $shipping_notice ) :
					?>
					<tr>
						<td width="1%" class="wc-shipping-zone-method-sort">
							<input type="hidden" name="<?php echo esc_attr( NoticesOrderAction::ORDERS_NAME ); ?>[]" value="<?php echo esc_attr( $shipping_notice->get_id() ); ?>"/>
						</td>
						<td class="wc-shipping-zone-method-title">
							<a href="<?php echo esc_url( $settings_action_links->get_edit_notice_url( $shipping_notice->get_id() ) ); ?>">
								<strong><?php echo esc_html( $shipping_notice->get_title() ); ?></strong>
							</a>
							<div class="row-actions">
							<span class="edit">
								<a href="<?php echo esc_url( $settings_action_links->get_edit_notice_url( $shipping_notice->get_id() ) ); ?>" title="<?php esc_attr_e( 'Edit notice', 'octolize-shipping-notices' ); ?>">
									<?php esc_html_e( 'Edit', 'octolize-shipping-notices' ); ?>
								</a>
								|
							</span>
								<span class="trash">
								<a onclick="return confirm('<?php esc_html_e( 'Are you sure?', 'octolize-shipping-notices' ); ?>');" href="<?php echo esc_url( $settings_action_links->get_delete_notice_url( $shipping_notice->get_id() ) ); ?>" title="<?php echo esc_attr( __( 'Delete notice', 'octolize-shipping-notices' ) ); ?>">
									<?php esc_html_e( 'Delete', 'octolize-shipping-notices' ); ?>
								</a>
							</span>
							</div>
						</td>
						<td width="1%" class="wc-shipping-zone-method-enabled">
							<span
								style="cursor: pointer;"
								class="woocommerce-input-toggle
								<?php
								if ( ! $shipping_notice->is_enabled() ) :
									?>
									woocommerce-input-toggle--disabled<?php endif; ?> js--shipping-notice-change-status">
								</span>

							<input
								type="hidden" name="<?php echo esc_attr( NoticesStatusAction::STATUSES_NAME ); ?>[<?php echo esc_attr( $shipping_notice->get_id() ); ?>]" value="<?php echo esc_attr( $shipping_notice->is_enabled() ? 'yes' : 'no' ); ?>"/>
						</td>
						<td class="wc-shipping-zone-method-description">
							<?php echo wp_kses_post( implode( ', ', $shipping_notice->get_regions() ) ); ?>
						</td>
					</tr>
				<?php endforeach; ?>
			<?php endif; ?>
			</tbody>
			<tfoot>
			<tr>
				<td colspan="10">
					<a href="<?php echo esc_url( $settings_action_links->get_add_notice_url() ); ?>" class="button octolize-shipping-notices-add-notice">
						<?php esc_html_e( 'Add notice', 'octolize-shipping-notices' ); ?>
					</a>
				</td>
			</tr>
			</tfoot>
		</table>
	</td>
</tr>
