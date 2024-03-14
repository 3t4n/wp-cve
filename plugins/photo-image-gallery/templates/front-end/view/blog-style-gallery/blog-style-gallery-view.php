<div class="video_view9_cont_wrapper gallery-img-content" id="video_view9_cont_wrapper<?php echo $galleryID; ?>"
	 data-gallery-id="<?php echo $galleryID; ?>"
	 data-content-per-page="<?php echo $num; ?>"
     data-rating-type="<?php echo $like_dislike; ?>">
	<div id="video_view9_cont_list<?php echo $galleryID; ?>" class="video_view9_cont_list view-<?php echo $view_slug; ?>">
		<input type="hidden" id="total" value="<?php echo $total; ?>"/>
		<?php
		foreach ( $page_images as $key => $row ) {
			if ( ! isset( $_COOKIE[ 'Like_' . $row->id . '' ] ) ) {
				$_COOKIE[ 'Like_' . $row->id . '' ] = '';
			}
			if ( ! isset( $_COOKIE[ 'Dislike_' . $row->id . '' ] ) ) {
				$_COOKIE[ 'Dislike_' . $row->id . '' ] = '';
			}
			$num2     = $wpdb->prepare( "SELECT `image_status`,`ip` FROM " . $wpdb->prefix . "ux_gallery_like_dislike WHERE image_id = %d AND `ip` = '" . $ux_ip . "'", (int) $row->id );
			$res3     = $wpdb->get_row( $num2 );
			$num3     = $wpdb->prepare( "SELECT `image_status`,`ip`,`cook` FROM " . $wpdb->prefix . "ux_gallery_like_dislike WHERE image_id = %d AND `cook` = '" . $_COOKIE[ 'Like_' . $row->id . '' ] . "'", (int) $row->id );
			$res4     = $wpdb->get_row( $num3 );
			$num4     = $wpdb->prepare( "SELECT `image_status`,`ip`,`cook` FROM " . $wpdb->prefix . "ux_gallery_like_dislike WHERE image_id = %d AND `cook` = '" . $_COOKIE[ 'Dislike_' . $row->id . '' ] . "'", (int) $row->id );
			$res5     = $wpdb->get_row( $num4 );


			$img_src  = $row->image_url;
			$img_name = str_replace( '__5_5_5__', '%', $row->name );
			$img_desc = str_replace( '__5_5_5__', '%', $row->description );
			//////////////////////
			$imagerowstype = $row->sl_type;
			if ( $row->sl_type == '' ) {
				$imagerowstype = 'image';
			}
			switch ( $imagerowstype ) {
				case 'image':
					if ( $uxgallery_get_option['uxgallery_view9_image_position'] == 1 ) :
						?>
						<div class="view9_container">
							<input type="hidden" class="pagenum" value="1"/>
							<div class="blog_img_wrapper">
								<img class="view9_img" src="<?php echo esc_attr( $img_src ); ?>">
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
												class="ux_like_count <?php if ( $uxgallery_get_option['uxgallery_ht_blog_rating_count'] == 'off' ) {
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
													class="ux_dislike_count <?php if ( $uxgallery_get_option['uxgallery_ht_blog_rating_count'] == 'off' ) {
														echo 'ux_hide';
													} ?>"
													id="<?php echo $row->id ?>"><?php echo $row->dislike; ?></span>
											</span>
											</div>
										<?php endif; ?>
									</div>
								<?php endif; ?>
							</div>
							<h1 class="new_view_title"><?php echo $img_name; ?></h1>
							<div class="new_view_desc"><?php echo $img_desc; ?></div>
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
												class="ux_like_count <?php if ( $uxgallery_get_option['uxgallery_ht_blog_rating_count'] == 'off' ) {
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
											class="ux_dislike_count <?php if ( $uxgallery_get_option['uxgallery_ht_blog_rating_count'] == 'off' ) {
												echo 'ux_hide';
											} ?>"
											id="<?php echo $row->id ?>"><?php echo $row->dislike; ?></span>
										</span>
										</div>
									<?php endif; ?>
								</div>
							<?php endif; ?>
						</div>
						<div class="clear"></div>
						<?php
					elseif ( $uxgallery_get_option['uxgallery_view9_image_position'] == 2 ) :
						?>
						<div class="view9_container">
							<input type="hidden" class="pagenum" value="1"/>
							<h1 class="new_view_title"><?php echo $img_name; ?></h1>
							<div class="blog_img_wrapper">
								<img class="view9_img" src="<?php echo esc_attr( $img_src ); ?>">
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
												class="ux_like_count <?php if ( $uxgallery_get_option['uxgallery_ht_blog_rating_count'] == 'off' ) {
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
													class="ux_dislike_count <?php if ( $uxgallery_get_option['uxgallery_ht_blog_rating_count'] == 'off' ) {
														echo 'ux_hide';
													} ?>"
													id="<?php echo $row->id ?>"><?php echo $row->dislike; ?></span>
											</span>
											</div>
										<?php endif; ?>
									</div>
								<?php endif; ?>
							</div>
							<div class="new_view_desc"><?php echo $img_desc; ?></div>
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
												class="ux_like_count <?php if ( $uxgallery_get_option['uxgallery_ht_blog_rating_count'] == 'off' ) {
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
													class="ux_dislike_count <?php if ( $uxgallery_get_option['uxgallery_ht_blog_rating_count'] == 'off' ) {
														echo 'ux_hide';
													} ?>"
													id="<?php echo $row->id ?>"><?php echo $row->dislike; ?></span>
											</span>
										</div>
									<?php endif; ?>
								</div>
							<?php endif; ?>
						</div>
						<div class="clear"></div>
						<?php
					elseif ( $uxgallery_get_option['uxgallery_view9_image_position'] == 3 ) :
						?>
						<div class="view9_container">
							<input type="hidden" class="pagenum" value="1"/>
							<h1 class="new_view_title"><?php echo $img_name; ?></h1>
							<div class="new_view_desc"><?php echo $img_desc; ?></div>
							<div class="blog_img_wrapper">
								<img class="view9_img" src="<?php echo esc_attr( $img_src ); ?>">
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
												class="ux_like_count <?php if ( $uxgallery_get_option['uxgallery_ht_blog_rating_count'] == 'off' ) {
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
													class="ux_dislike_count <?php if ( $uxgallery_get_option['uxgallery_ht_blog_rating_count'] == 'off' ) {
														echo 'ux_hide';
													} ?>"
													id="<?php echo $row->id ?>"><?php echo $row->dislike; ?></span>
											</span>
											</div>
										<?php endif; ?>
									</div>
								<?php endif; ?>
							</div>
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
												class="ux_like_count <?php if ( $uxgallery_get_option['uxgallery_ht_blog_rating_count'] == 'off' ) {
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
													class="ux_dislike_count <?php if ( $uxgallery_get_option['uxgallery_ht_blog_rating_count'] == 'off' ) {
														echo 'ux_hide';
													} ?>"
													id="<?php echo $row->id ?>"><?php echo $row->dislike; ?></span>
											</span>
										</div>
									<?php endif; ?>
								</div>
							<?php endif; ?>
						</div>
						<div class="clear"></div>
						<?php
					endif;
					break;
				case 'video':
					$videourl = uxgallery_get_video_id_from_url( $row->image_url );
					if ( $videourl[1] == 'youtube' ) {
						if ( $uxgallery_get_option['uxgallery_view9_image_position'] == 1 ) :
							?>
							<div class="view9_container">
								<div class="iframe_cont">
									<iframe class="video_blog_view noneBorder"
									        src="//www.youtube.com/embed/<?php echo $videourl[0]; ?>"
									        allowfullscreen></iframe>
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
															class="ux_like_count <?php if ( $uxgallery_get_option['uxgallery_ht_blog_rating_count'] == 'off' ) {
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
															class="ux_dislike_count <?php if ( $uxgallery_get_option['uxgallery_ht_blog_rating_count'] == 'off' ) {
																echo 'ux_hide';
															} ?>"
															id="<?php echo $row->id ?>"><?php echo $row->dislike; ?></span>
													</span>
												</div>
											<?php endif; ?>
										</div>
									<?php endif; ?>
								</div>
								<h1 class="new_view_title"><?php echo $img_name; ?></h1>
								<div class="new_view_desc"><?php echo $img_desc; ?></div>
								<input type="hidden" class="pagenum" value="1"/>
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
														class="ux_like_count <?php if ( $uxgallery_get_option['uxgallery_ht_blog_rating_count'] == 'off' ) {
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
														class="ux_dislike_count <?php if ( $uxgallery_get_option['uxgallery_ht_blog_rating_count'] == 'off' ) {
															echo 'ux_hide';
														} ?>"
														id="<?php echo $row->id ?>"><?php echo $row->dislike; ?></span>
													</span>
											</div>
										<?php endif; ?>
									</div>
								<?php endif; ?>
							</div>
							<div class="clear"></div>
							<?php
						elseif ( $uxgallery_get_option['uxgallery_view9_image_position'] == 2 ) :
							?>
							<div class="view9_container">
								<h1 class="new_view_title"><?php echo $img_name; ?></h1>
								<div class="iframe_cont">
									<iframe class="video_blog_view noneBorder"
									        src="//www.youtube.com/embed/<?php echo $videourl[0]; ?>"
									        allowfullscreen></iframe>
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
															class="ux_like_count <?php if ( $uxgallery_get_option['uxgallery_ht_blog_rating_count'] == 'off' ) {
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
															class="ux_dislike_count <?php if ( $uxgallery_get_option['uxgallery_ht_blog_rating_count'] == 'off' ) {
																echo 'ux_hide';
															} ?>"
															id="<?php echo $row->id ?>"><?php echo $row->dislike; ?></span>
													</span>
												</div>
											<?php endif; ?>
										</div>
									<?php endif; ?>
								</div>
								<div class="new_view_desc"><?php echo $img_desc; ?></div>
								<input type="hidden" class="pagenum" value="1"/>
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
															class="ux_like_count <?php if ( $uxgallery_get_option['uxgallery_ht_blog_rating_count'] == 'off' ) {
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
															class="ux_dislike_count <?php if ( $uxgallery_get_option['uxgallery_ht_blog_rating_count'] == 'off' ) {
																echo 'ux_hide';
															} ?>"
															id="<?php echo $row->id ?>"><?php echo $row->dislike; ?></span>
													</span>
											</div>
										<?php endif; ?>
									</div>
								<?php endif; ?>
							</div>
							<div class="clear"></div>
							<?php
						elseif ( $uxgallery_get_option['uxgallery_view9_image_position'] == 3 ) :
							?>
							<div class="view9_container">
								<h1 class="new_view_title"><?php echo $img_name; ?></h1>
								<div class="new_view_desc"><?php echo $img_desc; ?></div>
								<div class="iframe_cont">
									<iframe class="video_blog_view noneBorder"
									        src="//www.youtube.com/embed/<?php echo $videourl[0]; ?>"
									        allowfullscreen></iframe>
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
															class="ux_like_count <?php if ( $uxgallery_get_option['uxgallery_ht_blog_rating_count'] == 'off' ) {
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
															class="ux_dislike_count <?php if ( $uxgallery_get_option['uxgallery_ht_blog_rating_count'] == 'off' ) {
																echo 'ux_hide';
															} ?>"
															id="<?php echo $row->id ?>"><?php echo $row->dislike; ?></span>
													</span>
												</div>
											<?php endif; ?>
										</div>
									<?php endif; ?>
								</div>
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
															class="ux_like_count <?php if ( $uxgallery_get_option['uxgallery_ht_blog_rating_count'] == 'off' ) {
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
															class="ux_dislike_count <?php if ( $uxgallery_get_option['uxgallery_ht_blog_rating_count'] == 'off' ) {
																echo 'ux_hide';
															} ?>"
															id="<?php echo $row->id ?>"><?php echo $row->dislike; ?></span>
													</span>
											</div>
										<?php endif; ?>
									</div>
								<?php endif; ?>
								<input type="hidden" class="pagenum" value="1"/>
							</div>
							<div class="clear"></div>
							<?php
						endif;
					} else {
						$hash   = unserialize( wp_remote_fopen( "http://vimeo.com/api/v2/video/" . $videourl[0] . ".php" ) );
						$imgsrc = $hash[0]['thumbnail_large'];
						if ( $uxgallery_get_option['uxgallery_view9_image_position'] == 1 ) :
							?>
							<div class="view9_container">
								<div class="iframe_cont">
									<iframe class="video_blog_view noneBorder"
									        src="//player.vimeo.com/video/<?php echo $videourl[0]; ?>"
									        allowfullscreen></iframe>
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
														class="ux_like_count <?php if ( $uxgallery_get_option['uxgallery_ht_blog_rating_count'] == 'off' ) {
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
														class="ux_dislike_count <?php if ( $uxgallery_get_option['uxgallery_ht_blog_rating_count'] == 'off' ) {
															echo 'ux_hide';
														} ?>"
														id="<?php echo $row->id ?>"><?php echo $row->dislike; ?></span>
													</span>
												</div>
											<?php endif; ?>
										</div>
									<?php endif; ?>
								</div>
								<h1 class="new_view_title"><?php echo $img_name; ?></h1>
								<div class="new_view_desc"><?php echo $img_desc; ?></div>
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
														class="ux_like_count <?php if ( $uxgallery_get_option['uxgallery_ht_blog_rating_count'] == 'off' ) {
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
														class="ux_dislike_count <?php if ( $uxgallery_get_option['uxgallery_ht_blog_rating_count'] == 'off' ) {
															echo 'ux_hide';
														} ?>"
														id="<?php echo $row->id ?>"><?php echo $row->dislike; ?></span>
													</span>
											</div>
										<?php endif; ?>
									</div>
								<?php endif; ?>
								<input type="hidden" class="pagenum" value="1"/>
							</div>
							<div class="clear"></div>
							<?php
						elseif ( $uxgallery_get_option['uxgallery_view9_image_position'] == 2 ) :
							?>
							<div class="view9_container">
								<h1 class="new_view_title"><?php echo $img_name; ?></h1>
								<div class="iframe_cont">
									<iframe class="video_blog_view noneBorder"
									        src="//player.vimeo.com/video/<?php echo $videourl[0]; ?>"
									        allowfullscreen></iframe>
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
														class="ux_like_count <?php if ( $uxgallery_get_option['uxgallery_ht_blog_rating_count'] == 'off' ) {
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
														class="ux_dislike_count <?php if ( $uxgallery_get_option['uxgallery_ht_blog_rating_count'] == 'off' ) {
															echo 'ux_hide';
														} ?>"
														id="<?php echo $row->id ?>"><?php echo $row->dislike; ?></span>
													</span>
												</div>
											<?php endif; ?>
										</div>
									<?php endif; ?>
								</div>
								<div class="new_view_desc"><?php echo $img_desc; ?></div>
								<input type="hidden" class="pagenum" value="1"/>
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
															class="ux_like_count <?php if ( $uxgallery_get_option['uxgallery_ht_blog_rating_count'] == 'off' ) {
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
															class="ux_dislike_count <?php if ( $uxgallery_get_option['uxgallery_ht_blog_rating_count'] == 'off' ) {
																echo 'ux_hide';
															} ?>"
															id="<?php echo $row->id ?>"><?php echo $row->dislike; ?></span>
													</span>
											</div>
										<?php endif; ?>
									</div>
								<?php endif; ?>
							</div>
							<div class="clear"></div>
							<?php
						elseif ( $uxgallery_get_option['uxgallery_view9_image_position'] == 3 ) :
							?>
							<div class="view9_container">
								<h1 class="new_view_title"><?php echo $img_name; ?></h1>
								<div class="new_view_desc"><?php echo $img_desc; ?></div>
								<div class="iframe_cont">
									<iframe class="video_blog_view noneBorder"
									        src="//player.vimeo.com/video/<?php echo $videourl[0]; ?>"
									        allowfullscreen></iframe>
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
															class="ux_like_count <?php if ( $uxgallery_get_option['uxgallery_ht_blog_rating_count'] == 'off' ) {
																echo 'ux_hide';
															} ?>"
															id="<?php echo $row->id ?>"><?php if ( $like_dislike != 'heart' ): ?><?php echo $row->like; ?><?php endif; ?></span>
														</span>
											</div>
											<?php if ( $like_dislike != 'heart' ): ?>
												<div class="uxgallery_dislike_wrapper">
														<span class="ux_dislike">
															<i class="uxgallery-icons-thumbs-down dislike_thumb_down"></i>
															<span class="ux_dislike_thumb"
															      id="<?php echo $row->id ?>"
															      data-status="<?php if ( isset( $res3->image_status ) && $res3->image_status == 'disliked' ) {
																      echo $res3->image_status;
															      } elseif ( isset( $res5->image_status ) && $res5->image_status == 'disliked' ) {
																      echo $res5->image_status;
															      } else {
																      echo 'unliked';
															      } ?>">
															</span>
														<span
															class="ux_dislike_count <?php if ( $uxgallery_get_option['uxgallery_ht_blog_rating_count'] == 'off' ) {
																echo 'ux_hide';
															} ?>"
															id="<?php echo $row->id ?>"><?php echo $row->dislike; ?></span>
														</span>
												</div>
											<?php endif; ?>
										</div>
									<?php endif; ?>
								</div>
								<input type="hidden" class="pagenum" value="1"/>
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
																class="ux_like_count <?php if ( $uxgallery_get_option['uxgallery_ht_blog_rating_count'] == 'off' ) {
																	echo 'ux_hide';
																} ?>"
																id="<?php echo $row->id ?>"><?php if ( $like_dislike != 'heart' ): ?><?php echo $row->like; ?><?php endif; ?></span>
														</span>
										</div>
										<?php if ( $like_dislike != 'heart' ): ?>
											<div class="uxgallery_dislike_wrapper">
														<span class="ux_dislike">
															<i class="uxgallery-icons-thumbs-down dislike_thumb_down"></i>
															<span class="ux_dislike_thumb"
															      id="<?php echo $row->id ?>"
															      data-status="<?php if ( isset( $res3->image_status ) && $res3->image_status == 'disliked' ) {
																      echo $res3->image_status;
															      } elseif ( isset( $res5->image_status ) && $res5->image_status == 'disliked' ) {
																      echo $res5->image_status;
															      } else {
																      echo 'unliked';
															      } ?>">
															</span>
															<span
																class="ux_dislike_count <?php if ( $uxgallery_get_option['uxgallery_ht_blog_rating_count'] == 'off' ) {
																	echo 'ux_hide';
																} ?>"
																id="<?php echo $row->id ?>"><?php echo $row->dislike; ?></span>
														</span>
											</div>
										<?php endif; ?>
									</div>
								<?php endif; ?>
							</div>
							<div class="clear"></div>
							<?php
						endif;
					}
			}
		}
		?>
	</div>
	<?php
	$a = $disp_type;
    if ( ( $a == 1 ) && ( $total != 1 ) ) {
		$uxgallery_blog_load_nonce = wp_create_nonce( 'uxgallery_blog_load_nonce' );
		?>
		<div class="load_more">
			<div class="load_more_button"
			     data-blog-nonce-value="<?php echo $uxgallery_blog_load_nonce; ?>"><?php echo $uxgallery_get_option['uxgallery_video_ht_view9_loadmore_text']; ?></div>
			<div class="loading"><img src="<?php if ( $uxgallery_get_option['uxgallery_loading_type'] == '1' ) {
					echo UXGALLERY_IMAGES_URL . '/front_images/arrows/loading1.gif';
				} elseif ( $uxgallery_get_option['uxgallery_loading_type'] == '2' ) {
					echo UXGALLERY_IMAGES_URL . '/front_images/arrows/loading4.gif';
				} elseif ( $uxgallery_get_option['uxgallery_loading_type'] == '3' ) {
					echo UXGALLERY_IMAGES_URL . '/front_images/arrows/loading36.gif';
				} elseif ( $uxgallery_get_option['uxgallery_loading_type'] == '4' ) {
					echo UXGALLERY_IMAGES_URL . '/front_images/arrows/loading51.gif';
				} ?>"></div>
		</div>
		<?php
    } elseif (  $a == 0  ) {
		?>
		<div class="paginate">
			<?php
			$protocol    = stripos( $_SERVER['SERVER_PROTOCOL'], 'https' ) === true ? 'https://' : 'http://';
			$actual_link = esc_url($protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] . "");
			$checkREQ    = '';
			$pattern     = "/\?p=/";
			$pattern2    = "/&page-img[0-9]+=[0-9]+/";
			$pattern3    = "/?page-img[0-9]+=[0-9]+/";
			if ( preg_match( $pattern, $actual_link ) ) {
				if ( preg_match( $pattern2, $actual_link ) ) {
					$actual_link = preg_replace( $pattern2, '', $actual_link );
				}
				$checkREQ = $actual_link . '&page-img' . $galleryID . $pID;
			} else {
				$checkREQ = '?page-img' . $galleryID . $pID;
			}
			$pervpage = '';
			if ( $page != 1 ) {
				$pervpage = '<a href= ' . $checkREQ . '=1><i class="icon-style uxgallery-icons-fast-backward" ></i></a>  
			      <a href= ' . $checkREQ . '=' . ( $page - 1 ) . '><i class="icon-style uxgallery-icons-chevron-left"></i></a> ';
			}
			$nextpage = '';
			if ( $page != $total ) {
				$nextpage = ' <a href= ' . $checkREQ . '=' . ( $page + 1 ) . '><i class="icon-style uxgallery-icons-chevron-right"></i></a>  
			      <a href= ' . $checkREQ . '=' . $total . '><i class="icon-style uxgallery-icons-fast-forward" ></i></a>';
			}
			echo $pervpage . $page . '/' . $total . $nextpage;
			?>
		</div>
		<?php
	}
	?>
</div>