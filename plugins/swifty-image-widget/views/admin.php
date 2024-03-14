<?php 
global $_wp_additional_image_sizes;
$sizes = array();
$get_intermediate_image_sizes = get_intermediate_image_sizes(); 
	foreach ($get_intermediate_image_sizes as $size) {

		if ( in_array( $size, array( 'thumbnail', 'medium', 'large' ) ) ) {
			$sizes[ $size ] = array( 
				'width'  => get_option( $size . '_size_w' ),
				'height' => get_option( $size . '_size_h' )
			);
		} elseif ( isset( $_wp_additional_image_sizes[ $size ])) {
			$sizes[ $size ] = array( 
				'width'  => $_wp_additional_image_sizes[$size]['width'],
				'height' => $_wp_additional_image_sizes[$size]['height']
			);
		}
	}
?>

<ul class="swifty_imgs_clone" style="display:none">
	<li style="margin-bottom:0;">
		<div class="imgContainer" style="text-align:center;border: 1px dashed #ddd;padding:15px;"></div>
		</br>
		<p>
			<label><?php _e('Image Link(Optional)', 'swifty-img-widget'); ?>:</label>
			<input type="text" name="<?php echo $this->get_field_name( 'img_link' ); ?>[]" class="widefat" />
		</p>
		<p>
			<label><?php _e('Image Caption(Optional)', 'swifty-img-widget'); ?>:</label>
			<textarea rows="6" class="widefat" name="<?php echo $this->get_field_name( 'img_caption' ); ?>[]"></textarea>
		</p>
		<input type="hidden" class="imgIdInput" name="<?php echo $this->get_field_name( 'img_id' ); ?>[]" value="">
		<a href="#" class="button swifty_add_image"><?php _e('Add Image', 'swifty-img-widget'); ?></a>
		<a href="#" class="swifty_remove_img button"><?php _e('Remove this image', 'swifty-img-widget'); ?></a>
		<hr style="margin:20px 0;">
	</li>
</ul>
<p>
	<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'swifty-img-widget' ); ?></label> 
	<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
</p>
<p>
	<label for="<?php echo $this->get_field_id( 'size' ); ?>"><?php _e( 'Images Size:', 'swifty-img-widget' ); ?></label><br/>

	<select class="widefat swifty-img-size" id="<?php echo $this->get_field_id( 'size' ); ?>" name="<?php echo $this->get_field_name('size'); ?>">
		<option <?php selected($instance['size'],'full'); ?> value="full" ><?php _e('Full', 'swifty-img-widget'); ?></option>
		<?php foreach ($sizes as $size_name => $size_attr) { ?>
			<option <?php selected($instance['size'],$size_name); ?> value="<?php echo $size_name; ?>" ><?php echo $size_name . ' ('.$size_attr["width"].' x '.$size_attr["height"].')'; ?></option>	
		<?php } ?>
		<option <?php selected($instance['size'],'custom'); ?> value="custom" ><?php _e('Custom', 'swifty-img-widget'); ?></option>
	</select>
</p>
<?php 
	$custom_display = $instance['size'] == 'custom' ? 'display:block;' : 'display:none'; 
?>
<p style="<?php echo $custom_display; ?>">
	<?php _e('Width', 'swifty-img-widget'); ?>: 
	<input id="<?php echo $this->get_field_id( 'img_width' ); ?>" type="text" name="<?php echo $this->get_field_name( 'img_width' ); ?>" value="<?php echo absint($instance['img_width']); ?>" class="small-text" />px
	<br/><?php _e('Height', 'swifty-img-widget'); ?>:
	<input id="<?php echo $this->get_field_id( 'img_height' ); ?>" type="text" name="<?php echo $this->get_field_name( 'img_height' ); ?>" value="<?php echo absint($instance['img_height']); ?>" class="small-text" />px
</p>
<p>
	<label data-attr="<?php echo $instance['align']; ?>" for="<?php echo $this->get_field_id( 'align' ); ?>"><?php _e( 'Image align:', 'swifty-img-widget' ); ?></label><br/>
	<select class="widefat" id="<?php echo $this->get_field_id( 'align' ); ?>" name="<?php echo $this->get_field_name('align'); ?>">
		<option <?php selected($instance['align'],'center'); ?> value="center" ><?php _e('Center', 'swifty-img-widget'); ?></option>
		<option <?php selected($instance['align'],'left'); ?> value="left" ><?php _e('Left', 'swifty-img-widget'); ?></option>
		<option <?php selected($instance['align'],'right'); ?> value="right" ><?php _e('Right', 'swifty-img-widget'); ?></option>
	</select>
</p>
<p>
	<label data-attr="<?php echo $instance['img_open_in']; ?>" for="<?php echo $this->get_field_id( 'img_open_in' ); ?>"><?php _e( 'Open Link in:', 'swifty-img-widget' ); ?></label><br/>
	<select class="widefat" id="<?php echo $this->get_field_id( 'img_open_in' ); ?>" name="<?php echo $this->get_field_name('img_open_in'); ?>">
		<option <?php selected($instance['img_open_in'],'new'); ?> value="new" ><?php _e('New Window', 'swifty-img-widget'); ?></option>
		<option <?php selected($instance['img_open_in'],'same'); ?> value="same" ><?php _e('Same Window', 'swifty-img-widget'); ?></option>
	</select>
</p>
<p>
	<label data-attr="<?php echo $instance['rel']; ?>" for="<?php echo $this->get_field_id( 'rel' ); ?>"><?php _e( 'Rel follow for links: (Search Engines)', 'swifty-img-widget' ); ?></label><br/>
	<select class="widefat" id="<?php echo $this->get_field_id( 'rel' ); ?>" name="<?php echo $this->get_field_name('rel'); ?>">
		<option <?php selected($instance['rel'],'nofollow'); ?> value="nofollow" ><?php _e('No follow (Bots wont follow)', 'swifty-img-widget'); ?></option>
		<option <?php selected($instance['rel'],'follow'); ?> value="follow" ><?php _e('Follow (Bots will follow)', 'swifty-img-widget'); ?></option>
	</select>
</p>
<hr style="margin:25px 0;">
<p>
	<ul class="swifty_img_holder">
		<?php foreach ($imgs as $img) { ?>
		<li style="margin-bottom:0;">
			<div class="imgContainer" style="text-align:center;border: 1px dashed #ddd;padding: 15px;"><?php echo wp_get_attachment_image( $img['img'], 'thumbnail' ); ?></div>
			<p>
				<label><?php _e('Image Link(Optional)', 'swifty-img-widget'); ?>:</label>
				<input type="text" name="<?php echo $this->get_field_name( 'img_link' ); ?>[]" value="<?php echo $img['link']; ?>" class="widefat" />
			</p>
			<p>
				<label><?php _e('Image Caption(Optional)', 'swifty-img-widget'); ?>:</label>
				<textarea rows="6" class="widefat" name="<?php echo $this->get_field_name( 'img_caption' ); ?>[]"><?php echo format_to_edit($img['caption']); ?></textarea>
			</p>
			<input type="hidden" class="imgIdInput" name="<?php echo $this->get_field_name( 'img_id' ); ?>[]" value="<?php echo $img['img']; ?>">
			<a href="#" class="button swifty_add_image"><?php _e('Edit Image','swifty-img-widget'); ?></a>
			<a href="#" class="swifty_remove_img button"><?php _e('Remove this image', 'swifty-img-widget'); ?></a>
			<hr style="margin:20px 0;">
		</li>
		<?php } ?>
	</ul>
	<br/><a href="#" class="swifty_add_img button"><?php _e('Add New Image', 'swifty-img-widget'); ?></a>
</p>