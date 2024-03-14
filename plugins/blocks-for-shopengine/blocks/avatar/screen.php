<?php

defined('ABSPATH') || exit;
require_once 'helper.php';

$avatar = ShopEngine_Pro\Modules\Avatar\Avatar::instance();
if (isset($avatar->settings['avatar']['status']) && $avatar->settings['avatar']['status'] == 'active') :

	$current_user = wp_get_current_user();
	$user_id      = $current_user->ID;
	$user_email = $current_user->user_email ? $current_user->user_email : '';
	$max_size = empty($avatar->settings['avatar']['settings']['max_size']['value']) ? 500 : $avatar->settings['avatar']['settings']['max_size']['value'];
	$svae_btn_text = $settings['shopengine_avatar_save_btn_text']['desktop'];
	$editor = $block->is_editor ? 'yes' : '';
	$random = uniqid();
	$shopengine_avatar_is_overlay = !empty($settings['shopengine_avatar_is_overlay']['desktop']) ? $settings['shopengine_avatar_is_overlay']['desktop'] : "";
?>
	<div class="<?php echo $shopengine_avatar_is_overlay ? esc_attr("shopengine_avatar_is_overlay-yes") : ""; ?>">
		<div class="shopengine shopengine-widget">
			<div class="shopengine-avatar-container" data-editor="<?php echo esc_attr($editor); ?>">
				<form action="<?php echo esc_url(admin_url('admin-ajax.php?action=shopengine_avatar')); ?>" method="post" enctype="multipart/form-data" id="upload-form">
					<div class="shopengine-avatar" data-thumbsize="<?php echo esc_attr($max_size); ?>">
						<div class="shopengine-avatar__thumbnail">
							<div class="shopengine-avatar__thumbnail--overlay-close" id="shopengine_avatar_image_cancel_button">
								<?php render_icon($settings['shopengine_avatar_image_cancel_button_icon']['desktop'], ['aria-hidden' => 'true']); ?>
							</div>
							<div class="shopengine-avatar__thumbnail--overlay"></div>
							<?php echo get_avatar($user_id, '100'); ?>
							<label for="<?php echo esc_attr($random); ?>" class="shopengine-avatar__thumbnail--btn">
								<?php render_icon($settings['shopengine_avatar_upload_icon']['desktop'], ['aria-hidden' => 'true']); ?>
							</label>
							<input id="<?php echo esc_attr($random); ?>" type="file" class="shopengine_avatar_image" name="shopengine_avatar_image">
							<input type="hidden" name="shopengine-nonce" value="<?php echo esc_attr(wp_create_nonce('shopengine-avatar')); ?>">
						</div>
						<div class="shopengine-avatar__info">
							<h3 class="shopengine-avatar__info--name"><?php echo wp_kses_post($current_user->display_name); ?></h3>
							<?php if (!empty($user_email)) : ?>
								<p class="shopengine-avatar__info--email"><?php echo esc_html($user_email); ?></p>
							<?php endif; ?>
							<input type="submit" class="shopengine-avatar__info--btn" value="<?php echo esc_attr($svae_btn_text); ?>">
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>

<?php endif; ?>