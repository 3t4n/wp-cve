<div class='ewd-upcp-single-product-thumbnails'>
	
	<?php foreach ( $this->product->get_all_images() as $count => $image ) { ?>
		
		<a class='ewd-upcp-thumbnail-anchor <?php echo ( ! empty( $image->video_key ) ? 'ewd-upcp-video-thumbnail' : '' ); ?> <?php echo esc_attr( $this->get_additional_images_lightbox_class() ); ?>' href='<?php echo esc_url( $image->url ); ?>' data-ulbsource='<?php echo ( ! empty( $image->video_key ) ? esc_attr( $image->embed_url ) : esc_attr( $image->url ) ); ?>' data-ulbtitle='<?php echo esc_attr( $image->description ); ?>' data-ulbdescription='<?php echo esc_attr( $image->description ); ?>' data-video_key='<?php echo ( ! empty( $image->video_key ) ? esc_attr( $image->video_key ) : '' ); ?>'>
			<img src='<?php echo esc_url( $image->url ); ?>' class='ewd-upcp-single-product-thumbnail' alt='<?php echo esc_attr( $image->description ); ?>' />
		</a>

	<?php } ?>

</div>