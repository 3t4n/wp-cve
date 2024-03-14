<?php
/**
** Current version: 1.5.12
** $store_id = $post->ID
** Override this template in /[theme]/template-parts/
**/
$waze_icon = apply_filters('wps_store_get_waze_icon', wps_store_get_waze_icon(80));
$store_full_image = get_the_post_thumbnail($store_id, $attr['store_image_size']);
?>
<div class="<?= $class ?> widget_store-content" data-id="<?= $store_id ?>">
	<?php do_action('wps_before_widget_content', $store_id) ?>

	<?php if(!empty($store_image) && has_post_thumbnail() && !empty($store_full_image)) : ?>
		<div class="parent-image-container">
			<div class="image-container">
				<?php echo $store_full_image; ?>
			</div>
		</div>
	<?php endif; ?>

	<?php if($attr['show_name']) : ?>
		<p class="store-title"><?php the_title() ?></p>
	<?php endif; ?>
	<?php echo !empty($city) ? '<p class="city">' . $city . '</p>' : ''; ?>
	<?php echo !empty($address) ? '<div class="address">' . apply_filters('the_content', $address) . '</div>' : ''; ?>
	<?php echo !empty($phone) ? '<p class="phone">' . $phone . '</p>' : ''; ?>
	<?php echo !empty($description) ? '<div class="description">' . apply_filters('the_content', $description) . '</div>' : ''; ?>
	<?php echo (!empty($waze) && !empty($waze_icon)) ? '<p class="waze"><a href="' . $waze . '" target="_blank">' . $waze_icon . '</a></p>' : ''; ?>
	
	<?php do_action('wps_after_widget_content', $store_id) ?>
</div>