<div class="wtbpAdminFooterShell woobewoo-hidden">
	<div class="wtbpAdminFooterCell">
		<?php echo esc_html(WTBP_WP_PLUGIN_NAME); ?>
		<?php esc_html_e('Version', 'woo-product-tables'); ?>:
		<a target="_blank" href="http://wordpress.org/plugins/woo-product-tables/changelog/"><?php echo esc_html(WTBP_VERSION); ?></a>
	</div>
	<div class="wtbpAdminFooterCell">|</div>
	<?php if (!FrameWtbp::_()->getModule(implode('', array('l', 'ic', 'e', 'ns', 'e')))) { ?>
	<div class="wtbpAdminFooterCell">
		<?php esc_html_e('Go', 'woo-product-tables'); ?>&nbsp;<a target="_blank" href="<?php echo esc_url($this->getModule()->getMainLink()); ?>"><?php esc_html_e('PRO', 'woo-product-tables'); ?></a>
	</div>
	<div class="wtbpAdminFooterCell">|</div>
	<?php } ?>
	<div class="wtbpAdminFooterCell">
		<a target="_blank" href="https://wordpress.org/support/plugin/woo-product-tables"><?php esc_html_e('Support', 'woo-product-tables'); ?></a>
	</div>
	<div class="wtbpAdminFooterCell">|</div>
	<div class="wtbpAdminFooterCell">
		Add your <a target="_blank" href="http://wordpress.org/support/view/plugin-reviews/woo-product-tables?filter=5#postform">&#9733;&#9733;&#9733;&#9733;&#9733;</a> on wordpress.org.
	</div>
</div>
