<?php
$ids = ( $settings->photos );
?>
<div class="njba-gallery-main" itemscope itemtype="http://schema.org/ImageObject">
    <div class="njba-gallery-body">
        <div class="njba-gallery-wrapper njba-gallery-<?php echo $settings->layout; ?>-section">
			<?php
			if (!empty( $ids)){
			//print_r($ids);
			foreach ( $ids as $id ) {
			$i                            = 1;
			$photo_attachment_data[ $id ] = FLBuilderPhoto::get_attachment_data( $id );
			$photo                        = $photo_attachment_data[ $id ];
			if ( ! empty( $photo ) )
			{
			$photo_url        = $photo->url;
			$caption          = $photo->caption;
			$filename         = $photo->filename;
			$image_size       = $settings->image_size;
			$photo_size_array = $photo->sizes;
			if ( $settings->layout === 'masonary' ){
			?>
            <div class="njba-gallery-box njba-gallery-<?php echo $i; ?> njba-masonary-gallery">
				<?php }else if ( $settings->layout === 'grid' ){ ?>
                <div class="njba-gallery-box  njba-gallery-<?php echo $i; ?>">
					<?php } ?>
                    <div class="njba-gallery-section">
						<?php if ( $settings->click_action === 'lightbox' ){ ?>
                        <a href="<?php echo $photo_url; ?>" title="<?php echo $caption; ?>" class="lightbox magnific njba-gallery" data-effect="mfp-newspaper">
							<?php } ?>
                            <div class="njba-image-box-img">
								<?php
								if ( array_key_exists( $settings->image_size, $photo_size_array ) ) {
									$photo_src = $photo_size_array->$image_size->url;
									echo '<img src="' . $photo_src . '" class="' . $settings->hover_effects . ' njba-image-responsive">';
								} else {
									echo '<img src="' . $photo_url . '" class="' . $settings->hover_effects . ' njba-image-responsive">';
								}
								?>
                            </div>
                            <div class="njba-image-box-overlay <?php echo $settings->hover_effects; ?>">
                                <div class="njba-image-box-content njba-image-box-hover">
                                	<?php if($settings->click_action === 'lightbox') { ?>
                                    	<i class="<?php echo $settings->hover_icon ?>" aria-hidden="true"></i>
                                    <?php } ?>
                                </div>
                            </div>
							<?php
							if ( $settings->click_action === 'lightbox' ){ ?>
                        </a>
					<?php } ?>
                    </div>
                </div>
				<?php
				}
				$i ++;
				}
				}
				?>
            </div><!--njba-gallery-wrapper-->
        </div><!--njba-gallery-body-->
    </div><!--njba-gallery-main-->
    
	
	
	
