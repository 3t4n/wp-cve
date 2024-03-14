<div id="g-main-slider_<?php echo $galleryID; ?>"
     class="gallery-img-content g-main-slider liquid-slider view-<?php echo $view_slug; ?>"
     data-pause-hover="<?php echo $gallery[0]->pause_on_hover; ?>"
     data-autoslide="<?php echo $gallery[0]->autoslide; ?>"
     data-slide-duration="500"
     data-slide-interval="<?php echo $gallery[0]->param; ?>"
     data-rating-type="<?php echo $like_dislike; ?>"
>
	<?php
	foreach ( $images as $key => $row ) {
		global $wpdb;
		if ( ! isset( $_COOKIE[ 'Like_' . $row->id . '' ] ) ) {
			$_COOKIE[ 'Like_' . $row->id . '' ] = '';
		}
		if ( ! isset( $_COOKIE[ 'Dislike_' . $row->id . '' ] ) ) {
			$_COOKIE[ 'Dislike_' . $row->id . '' ] = '';
		}
		$num2   = $wpdb->prepare( "SELECT `image_status`,`ip` FROM " . $wpdb->prefix . "ux_gallery_like_dislike WHERE image_id = %d AND `ip` = '" . $ux_ip . "'", (int) $row->id );
		$res3   = $wpdb->get_row( $num2 );
		$num3   = $wpdb->prepare( "SELECT `image_status`,`ip`,`cook` FROM " . $wpdb->prefix . "ux_gallery_like_dislike WHERE image_id = %d AND `cook` = '" . $_COOKIE[ 'Like_' . $row->id . '' ] . "'", (int) $row->id );
		$res4   = $wpdb->get_row( $num3 );
		$num4   = $wpdb->prepare( "SELECT `image_status`,`ip`,`cook` FROM " . $wpdb->prefix . "ux_gallery_like_dislike WHERE image_id = %d AND `cook` = '" . $_COOKIE[ 'Dislike_' . $row->id . '' ] . "'", (int) $row->id );
		$res5   = $wpdb->get_row( $num4 );
		$imgurl = explode( ";", $row->image_url );
		//array_pop($imgurl);
		$link       = str_replace( '__5_5_5__', '%', $row->sl_url );
		$descnohtml = strip_tags(
			str_replace( '__5_5_5__', '%', $row->description ) );
		$result     = substr( $descnohtml, 0, 50 );
		?>
		<div class="slider-content">
			<div class="slider-content-wrapper">
				<div class="image-block_<?php echo $galleryID; ?>">
					<?php if ( $like_dislike == 'heart' ): ?>
						<div class="uxgallery_like_cont_<?php echo $galleryID . $pID; ?>">
							<div class="uxgallery_like_wrapper">
						<span class="ux_like">
							<?php if ( $like_dislike == 'heart' ): ?>
								<i class="uxgallery-icons-heart likeheart"></i>
							<?php endif; ?>
							<?php if ( $like_dislike == 'dislike' ): ?>
								<i class="uxgallery-icons-thumbs-up like_thumb_up"></i>
							<?php endif; ?>
							<span class="ux_like_thumb" id="<?php echo $row->id ?>"
							      data-status="<?php if ( isset( $res3->image_status ) && $res3->image_status == 'liked' ) {
								      echo $res3->image_status;
							      } elseif ( isset( $res4->image_status ) && $res4->image_status == 'liked' ) {
								      echo $res4->image_status;
							      } else {
								      echo 'unliked';
							      } ?>">
							<?php if ( $like_dislike == 'heart' ): ?>
								<?php echo $row->like; ?>
							<?php endif; ?>
							</span>
							<span
								class="ux_like_count <?php if ( $uxgallery_get_option['uxgallery_ht_contentsl_rating_count'] == 'off' ) {
									echo 'ux_hide';
								} ?>"
								id="<?php echo $row->id ?>"><?php if ( $like_dislike != 'heart' ): ?><?php echo $row->like; ?><?php endif; ?></span>
						</span>
							</div>
							<?php if ( $like_dislike != 'heart' ): ?>
								<div class="uxgallery_dislike_wrapper">
						<span class="ux_dislike">
							<i class="uxgallery-icons-thumbs-down dislike_thumb_down"></i>
							<span class="ux_dislike_thumb" id="<?php echo $row->id ?>"
							      data-status="<?php if ( isset( $res3->image_status ) && $res3->image_status == 'disliked' ) {
								      echo $res3->image_status;
							      } elseif ( isset( $res5->image_status ) && $res5->image_status == 'disliked' ) {
								      echo $res5->image_status;
							      } else {
								      echo 'unliked';
							      } ?>">
							</span>
							<span
								class="ux_dislike_count <?php if ( $uxgallery_get_option['uxgallery_ht_contentsl_rating_count'] == 'off' ) {
									echo 'ux_hide';
								} ?>" id="<?php echo $row->id ?>"><?php echo $row->dislike; ?></span>
						</span>
								</div>
							<?php endif; ?>
						</div>
					<?php endif; ?>
					<?php
					$imagerowstype = $row->sl_type;
					if ( $row->sl_type == '' ) {
						$imagerowstype = 'image';
					}
					switch ( $imagerowstype ) {
						case 'image':
							?>
							<?php if ( $row->image_url != ';' ) { ?>
							<a class="gallery_group<?php echo $galleryID; ?>" href="<?php echo $imgurl[0]; ?>"
							   title="<?php echo str_replace( '__5_5_5__', '%', $row->name ); ?>"><img
									alt="<?php echo str_replace( '__5_5_5__', '%', $row->name ); ?>" class="main-image"
									src="<?php echo esc_url( uxgallery_get_image_by_sizes_and_src( $imgurl[0], array(
										get_option( 'uxgallery_ht_view5_main_image_width' ),
										''
									), false ) ); ?>"/></a>
						<?php } else { ?>
							<img alt="<?php echo str_replace( '__5_5_5__', '%', $row->name ); ?>" class="main-image"
							     src="images/noimage.jpg"/>
							<?php
						} ?>
							<?php
							break;
						case 'video':
							?>
							<?php
							$videourl = uxgallery_get_video_id_from_url( $row->image_url );
							if ( $videourl[1] == 'youtube' ) {
								?>
								<a class="gallery_group<?php echo $galleryID; ?> giyoutube uxgallery_item"
								   href="https://www.youtube.com/embed/<?php echo $videourl[0]; ?>"
								   title="<?php echo str_replace( '__5_5_5__', '%', $row->name ); ?>">
									<img alt="<?php echo str_replace( '__5_5_5__', '%', $row->name ); ?>"
									     src="https://img.youtube.com/vi/<?php echo $videourl[0]; ?>/mqdefault.jpg" class="main-image" />
									<div class="play-icon <?php echo $videourl[1]; ?>-icon"></div>
								</a>
								<?php
							} else {
								$hash   = unserialize( wp_remote_fopen( "https://vimeo.com/api/v2/video/" . $videourl[0] . ".php" ) );
								$imgsrc = $hash[0]['thumbnail_large'];
								?>
								<a class="givimeo uxgallery_item"
								   href="https://player.vimeo.com/video/<?php echo $videourl[0]; ?>"
								   title="<?php echo str_replace( '__5_5_5__', '%', $row->name ); ?>">
									<img alt="<?php echo str_replace( '__5_5_5__', '%', $row->name ); ?>" class="main-image"
									     src="<?php echo esc_attr( $imgsrc ); ?>"/>
									<div class="play-icon <?php echo $videourl[1]; ?>-icon"></div>
								</a>
								<?php
							}
							?>
							<?php
							break;
					}
					?>
				</div>
				<div class="right-block">                    <?php if ( $row->name != '' && $row->name != null ) { ?>
						<div><h2 class="title"><?php echo str_replace( '__5_5_5__', '%', $row->name ); ?></h2>
						</div>                    <?php } ?>
					<?php if ( $uxgallery_get_option["uxgallery_ht_view5_show_description"] == 'on' ) { ?>
						<div
							class="description"><?php echo str_replace( '__5_5_5__', '%', $row->description ); ?></div><?php } ?>
					<?php if ( $like_dislike != 'off' && $like_dislike != 'heart' ): ?>
						<div class="uxgallery_like_cont_<?php echo $galleryID . $pID; ?>">
							<div class="uxgallery_like_wrapper">
						<span class="ux_like">
							<?php if ( $like_dislike == 'heart' ): ?>
								<i class="uxgallery-icons-heart likeheart"></i>
							<?php endif; ?>
							<?php if ( $like_dislike == 'dislike' ): ?>
								<i class="uxgallery-icons-thumbs-up like_thumb_up"></i>
							<?php endif; ?>
							<span class="ux_like_thumb" id="<?php echo $row->id ?>"
							      data-status="<?php if ( isset( $res3->image_status ) && $res3->image_status == 'liked' ) {
								      echo $res3->image_status;
							      } elseif ( isset( $res4->image_status ) && $res4->image_status == 'liked' ) {
								      echo $res4->image_status;
							      } else {
								      echo 'unliked';
							      } ?>">
							<?php if ( $like_dislike == 'heart' ): ?>
								<?php echo $row->like; ?>
							<?php endif; ?>
							</span>
							<span
								class="ux_like_count <?php if ( $uxgallery_get_option['uxgallery_ht_contentsl_rating_count'] == 'off' ) {
									echo 'ux_hide';
								} ?>"
								id="<?php echo $row->id ?>"><?php if ( $like_dislike != 'heart' ): ?><?php echo $row->like; ?><?php endif; ?></span>
						</span>
							</div>
							<?php if ( $like_dislike != 'heart' ): ?>
								<div class="uxgallery_dislike_wrapper">
						<span class="ux_dislike">
							<i class="uxgallery-icons-thumbs-down dislike_thumb_down"></i>
							<span class="ux_dislike_thumb" id="<?php echo $row->id ?>"
							      data-status="<?php if ( isset( $res3->image_status ) && $res3->image_status == 'disliked' ) {
								      echo $res3->image_status;
							      } elseif ( isset( $res5->image_status ) && $res5->image_status == 'disliked' ) {
								      echo $res5->image_status;
							      } else {
								      echo 'unliked';
							      } ?>">
							</span>
							<span
								class="ux_dislike_count <?php if ( $uxgallery_get_option['uxgallery_ht_contentsl_rating_count'] == 'off' ) {
									echo 'ux_hide';
								} ?>" id="<?php echo $row->id ?>"><?php echo $row->dislike; ?></span>
						</span>
								</div>
							<?php endif; ?>
						</div>
					<?php endif; ?>
					<?php if ( $uxgallery_get_option["uxgallery_ht_view5_show_linkbutton"] == 'on' ) {
						if ( $link != '' ) {
							?>
							<div class="button-block">
								<a href="<?php echo $link; ?>" <?php if ( $row->link_target == "on" ) {
									echo 'target="_blank"';
								} ?>><?php echo $uxgallery_get_option["uxgallery_ht_view5_linkbutton_text"]; ?></a>
							</div>
						<?php }
					} ?>
				</div>
			</div>
		</div>
		<?php
	} ?>
</div>