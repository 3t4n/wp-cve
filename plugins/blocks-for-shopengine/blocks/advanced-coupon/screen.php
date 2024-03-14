<?php

defined('ABSPATH') || exit;

$module_list = \ShopEngine\Core\Register\Module_List::instance();
if($module_list->get_list()['advanced-coupon']['status'] === 'active'):
$icon_bg = $settings['shopengine_advanced_coupon_icon_bg']['desktop'] ? $settings['shopengine_advanced_coupon_icon_bg']['desktop'] : '#D61E37';
$icon_size = $settings['shopengine_advanced_coupon_icon_size']['desktop'] ? $settings['shopengine_advanced_coupon_icon_size']['desktop'] : "18px";
?>
<div class="shopengine shopengine-widget">
	<div class="shopengine-advanced-coupon-container">
		<div class="shopengine-advanced-coupon-container-inner">
			<div class="shopengine-advanced-coupon-date">
				<?php if (!empty($settings['shopengine_advanced_coupon_date']['desktop'])) { ?>
					<p><span class="shopengine-advanced-coupon-icon"><svg width="<?php echo esc_attr($icon_size) ?>" height="<?php echo esc_attr($icon_size) ?>" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
								<circle cx="9.12766" cy="9.41178" r="8.46989" fill="<?php echo esc_attr($icon_bg) ?>" />
								<path d="M11.0274 10.3527L9.54065 9.23761V6.96622C9.54065 6.73783 9.35604 6.55322 9.12765 6.55322C8.89927 6.55322 8.71466 6.73783 8.71466 6.96622V9.44413C8.71466 9.57421 8.77579 9.69688 8.87986 9.77452L10.5318 11.0135C10.6061 11.0692 10.6929 11.0961 10.7792 11.0961C10.9051 11.0961 11.029 11.0395 11.11 10.9305C11.2471 10.7483 11.2099 10.4894 11.0274 10.3527Z" fill="white" />
								<path d="M9.12758 4.08008C6.18755 4.08008 3.79596 6.47167 3.79596 9.4117C3.79596 12.3517 6.18755 14.7433 9.12758 14.7433C12.0676 14.7433 14.4592 12.3517 14.4592 9.4117C14.4592 6.47167 12.0676 4.08008 9.12758 4.08008ZM9.12758 13.9174C6.64348 13.9174 4.62192 11.8958 4.62192 9.4117C4.62192 6.9276 6.64348 4.90604 9.12758 4.90604C11.6121 4.90604 13.6332 6.9276 13.6332 9.4117C13.6332 11.8958 11.6117 13.9174 9.12758 13.9174Z" fill="white" />
							</svg></span>
						<?php echo esc_html($settings['shopengine_advanced_coupon_date']['desktop']); ?></p>
				<?php } ?>
			</div>
			<div class="shopengine-advanced-coupon-body">
				<div class="shopengine-advanced-coupon-discount">
					<h1><span class="advanced-coupon-symbol-prefix"><?php echo esc_html($settings['shopengine_advanced_coupon_discount_price_prefix']['desktop']); ?></span><?php echo esc_html($settings['shopengine_advanced_coupon_discount_price']['desktop']) ?></h1>
					<p class="advanced-coupon-discount"><?php echo esc_html($settings['shopengine_advanced_coupon_discount_text']['desktop']); ?></p>
				</div>
				<div class="shopengine-advanced-coupon-content">
					<h5><?php echo esc_html($settings['shopengine_advanced_coupon_title']['desktop']); ?><p> <?php echo esc_html($settings['shopengine_advanced_coupon_subtitle']['desktop']); ?></p>
					</h5>
				</div>
				<div class="shopengine-advanced-coupon-button">
					<button class="shopengine-coupon-button"><i class="eicon-copy shopengine-coupon"></i></button>
				</div>
			</div>
			<div class="shopengine-advanced-coupon-footer">
				<?php if (!empty($settings['shopengine_advanced_coupon_sample_code'])) { ?>
					<button id="shopengine-coupon-code"><?php echo esc_html($settings['shopengine_advanced_coupon_sample_code']['desktop']); ?></button>
				<?php } ?>
			</div>
			<div class="shopengine-advanced-coupon-bubble-up"></div>
			<div class="shopengine-advanced-coupon-bubble-bottom"></div>
		</div>
	</div>
</div>
<?php else: ?>

	<div class="shopengine-advanced-coupon-warning">
		<h6><?php esc_html_e('Please active your advanced coupon from module', 'shopengine-gutenberg-addon'); ?></h6>
	</div>

<?php endif; ?>