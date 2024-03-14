<?php
/**
** Current version: 1.5.17
** $store_id = get_the_ID()
** Override this template in /[theme]/template-parts/
**/
$waze_icon = apply_filters('wps_store_get_vc_waze_icon', wps_store_get_waze_icon(120));
$store_full_image = get_the_post_thumbnail($store_id, $store_image_size);
?>

<div class="store-wrapper" data-id="<?= $store_id ?>">
	<div class="store-box-content">
		<div class="store-map-pin" <?= $icon_background ?>>
			<i class="fas fa-map-marker-alt" <?= $icon_color ?>></i>
		</div>
		<?php if(has_post_thumbnail() && $atts['store_image'] && $store_full_image) : ?>
			<div class="store-image">
				<?= $store_full_image; ?>
			</div>
		<?php endif; ?>
		<div class="store-content">
			<?php do_action('wps_before_vc_content', $store_id) ?>

			<?php if($store_city) : ?>
				<h4><?= apply_filters('wps_store_city', $store_city, $store_id); ?></h4>
			<?php endif; ?>

			<?php if($atts['store_name']) : ?>
				<p><strong><?= get_the_title($store_id) ?></strong></p>
			<?php endif; ?>

			<?php if(!empty($store_direction)) : ?>
				<div class="store-direction">
					<?= apply_filters('the_content', $store_direction) ?>
				</div>
			<?php endif; ?>
			<?php if(!empty($store_phone)) : ?>
				<p class="store-phone"><i class="fa fa-phone"></i><?= $store_phone ?></p>
			<?php endif; ?>
			<?php if(!empty($store_description)) : ?>
				<div class="description">
					<?= apply_filters('the_content', $store_description) ?>
				</div>
			<?php endif; ?>

			<?php if(!empty($store_waze_link) && !empty($waze_icon)) : ?>
				<a class="waze-link" href="<?= $store_waze_link ?>"><?= $waze_icon ?></a>
			<?php endif; ?>

			<?php do_action('wps_after_vc_content', $store_id) ?>
		</div>
	</div>
</div>