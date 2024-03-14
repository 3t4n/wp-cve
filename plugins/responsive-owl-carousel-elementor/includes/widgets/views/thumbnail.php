<?php
/**
 * @var array  $item
 * @var string $field_prefix
 */

if ( ! $settings[$field_prefix . 'image_hide'] ) { ?>
	<div class="owl-thumb">
		<?php
		// push option(image) key in the settings array because owce_get_img_with_size functon will look for that key in the $settings array
		$settings['item_image_temp'] = $item['item_image'];
		
		echo owce_get_img_with_size(
			$settings, $field_prefix . 'thumbnail',
			'item_image_temp',
			$this,
			[
				'show_lightbox'                => $settings[$field_prefix . 'lightbox'],
				'show_lightbox_title'          => $settings[$field_prefix . 'lightbox_title'],
				'show_lightbox_description'    => $settings[$field_prefix . 'lightbox_description'],
				'disable_lightbox_editor_mode' => $settings[$field_prefix . 'lightbox_editor_mode'],
			]
		);
		?>
	</div>

<?php }
