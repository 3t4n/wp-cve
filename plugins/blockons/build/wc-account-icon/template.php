<?php
/**
 * All of the parameters passed to the function where this file is being required are accessible in this scope:
 *
 * @param array    $attributes     The array of attributes for this block.
 * @param string   $content        Rendered block output. ie. <InnerBlocks.Content />.
 * @param WP_Block $block_instance The instance of the WP_Block class that represents the block being rendered.
 *
 * @package blockons
 */
$custom_classes = 'align-' . $attributes['alignment'];
?>
<div <?php echo wp_kses_data( get_block_wrapper_attributes(['class' => $custom_classes]) ); ?>>
	<div class="blockons-wc-account-icon-block <?php echo isset($attributes['dropPosition']) ? sanitize_html_class($attributes['dropPosition']) : 'bottomleft'; ?>">
		<a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>" class="blockons-wc-account-icon" style="<?php echo isset($attributes['iconBgColor']) ? 'background-color: ' . esc_attr($attributes['iconBgColor']) . ';' : ''; ?> <?php echo isset($attributes['iconSize']) ? 'font-size: ' . esc_attr($attributes['iconSize']) . 'px;' : ''; ?> <?php echo isset($attributes['iconPadding']) ? 'padding: ' . esc_attr($attributes['iconPadding']) . 'px;' : ''; ?>">
			<span class="<?php echo $attributes['customIcon'] && $attributes['icon'] == "custom" ? esc_attr($attributes['customIcon']) : esc_attr($attributes['icon']); ?>" style="<?php echo isset($attributes['iconColor']) ? 'color: ' . esc_attr($attributes['iconColor']) : ''; ?>;"
			></span>
		</a>

		<?php if ( is_user_logged_in() ) : ?>
			<?php if ($attributes['hasDropdown']) : ?>
				<div class="blockons-wc-account-icon-dropdown" style="<?php echo isset($attributes['dropBgColor']) ? 'background-color: ' . esc_attr($attributes['dropBgColor']) . ';' : ''; ?> <?php echo isset($attributes['dropColor']) ? 'color: ' . esc_attr($attributes['dropColor']) . ';' : ''; ?>">
					<?php if ($attributes['showDashboard']) : ?>
						<div class="blockons-wc-account-icon-item">
							<a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>">
								<?php echo esc_html($attributes['textDashboard']); ?>
							</a>
						</div>
					<?php endif; ?>

					<?php if ($attributes['showOrders']) : ?>
						<div class="blockons-wc-account-icon-item">
							<a href="<?php echo esc_url( wc_get_account_endpoint_url( 'orders' ) ); ?>">
								<?php echo esc_html($attributes['textOrders']); ?>
							</a>
						</div>
					<?php endif; ?>

					<?php if ($attributes['showDownloads']) : ?>
						<div class="blockons-wc-account-icon-item">
							<a href="<?php echo esc_url( wc_get_account_endpoint_url( 'downloads' ) ); ?>">
								<?php echo esc_html($attributes['textDownloads']); ?>
							</a>
						</div>
					<?php endif; ?>

					<?php if ($attributes['showAddresses']) : ?>
						<div class="blockons-wc-account-icon-item">
							<a href="<?php echo esc_url( wc_get_account_endpoint_url( 'edit-address' ) ); ?>">
								<?php echo esc_html($attributes['textAddresses']); ?>
							</a>
						</div>
					<?php endif; ?>

					<?php if ($attributes['showAccountDetails']) : ?>
						<div class="blockons-wc-account-icon-item">
							<a href="<?php echo esc_url( wc_customer_edit_account_url() ); ?>">
								<?php echo esc_html($attributes['textAccountDetails']); ?>
							</a>
						</div>
					<?php endif; ?>

					<?php if ($attributes['showLogout']) : ?>
						<div class="blockons-wc-account-icon-item">
							<a href="<?php echo esc_url( wc_logout_url() ); ?>">
								<?php echo esc_html($attributes['textLogout']); ?>
							</a>
						</div>
					<?php endif; ?>
				</div>
			<?php endif; ?>
		<?php endif; ?>
	</div>
</div>
