<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="woocommerce-layout__activity-panel-tabs">
	<button type="button" id="activity-panel-tab-help" class="components-button woocommerce-layout__activity-panel-tab"><span class="dashicons dashicons-menu"></span></button>
</div>
<div class="woocommerce-layout__activity-panel-wrapper">
	<div class="woocommerce-layout__activity-panel-content" id="activity-panel-true">
		<div>
			<ul class="woocommerce-list woocommerce-quick-links__list">
				<?php foreach ( $menu_items as $item ) { ?>
					<li class="woocommerce-list__item has-action">
						<a href="<?php echo esc_url( $item['link'] ); ?>" class="woocommerce-list__item-inner" <?php echo isset( $item['target'] ) ? esc_html($item['target']) : ''; ?>>
							<div class="woocommerce-list__item-before">
								<img class="ts4wc_help_logo" src="<?php echo esc_url( trackship_for_woocommerce()->plugin_dir_url() ); ?>assets/images/<?php echo esc_html( $item['image'] ); ?>">
							</div>
							<div class="woocommerce-list__item-text">
								<span class="woocommerce-list__item-title">
									<div class="woocommerce-list-Text">
										<?php esc_html_e( $item['label'] ); ?>
									</div>
								</span>
							</div>
						</a>
					</li>
				<?php } ?>
			</ul>
		</div>
	</div>
</div>
