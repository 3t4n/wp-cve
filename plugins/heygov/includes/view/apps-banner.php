<?php
$heygov_banner_bg_color = get_option('heygov_banner_bg_color') ?: '#EEF4FE';
$heygov_banner_img_big = get_option('heygov_banner_img_big') ?: HEYGOV_URL . 'assets/banner.jpg';
$heygov_banner_img_small = get_option('heygov_banner_img_small') ?: HEYGOV_URL . 'assets/banner-mobile.jpg';
?>

<div class="heygov-apps-banner" style="background-color: <?php echo esc_attr($heygov_banner_bg_color) ?>">
	<div class="heygov-apps-banner-image">
		<img src="<?php echo esc_url($heygov_banner_img_big) ?>" class="heygov-apps-banner-image-big" alt="HeyGov" />
		<img src="<?php echo esc_url($heygov_banner_img_small) ?>" class="heygov-apps-banner-image-small" alt="HeyGov" />
	</div>

	<div class="heygov-apps-banner-links">
		<a href="https://apps.apple.com/app/id1560855064" class="heygov-link-ios" target="_blank"><img src="<?php echo esc_url(HEYGOV_URL . 'assets/badge-app-store.svg') ?>" alt="Apple App Store" /></a>
		<a href="https://play.google.com/store/apps/details?id=com.heygov.app" class="heygov-link-android" target="_blank"><img src="<?php echo esc_url(HEYGOV_URL . 'assets/badge-play-store.png') ?>" alt="Google Play Store" /></a>
	</div>
</div>
