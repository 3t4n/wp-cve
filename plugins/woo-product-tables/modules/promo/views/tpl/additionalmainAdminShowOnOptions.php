<?php 
$str = __('Show when user tries to exit from your site.', 'woo-product-tables') . '<a target="_blank" href="https://woobewoo.com/">' . __('Check example', 'woo-product-tables') . '.</a>'; 
?>

<label class="woobewoo-tooltip-right" title="<?php echo esc_attr($str); ?>">
	<a target="_blank" href="<?php echo esc_url($this->promoLink); ?>" class="sup-promolink-input">
		<?php 
			HtmlWtbp::radiobutton('promo_show_on_opt', array(
				'value' => 'on_exit_promo',
				'checked' => false,
			));
			?>
		<?php esc_html_e('On Exit from Site', 'woo-product-tables'); ?>
	</a>
	<a target="_blank" href="<?php echo esc_url($this->promoLink); ?>"><?php esc_html_e('Available in PRO'); ?></a>
</label>
