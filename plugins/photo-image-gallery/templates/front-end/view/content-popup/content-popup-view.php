<?php
switch (get_option("uxgallery_album_popup_onhover_effects")) {
    case 0:
        $hover_class = "view-first";
        break;
    case 1:
        $hover_class = "view-second";
        break;
    case 2:
        $hover_class = "view-third";
        break;
    case 3:
        $hover_class = "view-forth";
        break;
    case 4:
        $hover_class = "view-fifth";
        break;
    default:
        $hover_class = "view-first";
        break;
}
?>

    <input type="hidden" name="view_style" value="<?= $hover_class ?>">

    <section id="uxgallery_content_<?php echo $galleryID; ?>" class="gallery-img-content"
             data-image-behaviour="<?php echo get_option('uxgallery_image_natural_size_contentpopup'); ?>"
             data-gallery-id="<?php echo $galleryID; ?>"
             data-content-per-page="<?php echo $num; ?>"
             data-rating-type="<?php echo $like_dislike; ?>"
             data-pages-count="<?php echo absint($total); ?>">
        <div id="uxgallery_container_<?php echo $galleryID; ?>"
             class="uxgallery_container super-list variable-sizes clearfix view-<?php echo $view_slug; ?>"
             data-show-center="<?php echo get_option('uxgallery_ht_view2_content_in_center'); ?>">
            <div id="uxgallery_container_moving_<?php echo $galleryID; ?>">
                <input type="hidden" class="pagenum" value="1"/>
                <input type="hidden" id="total" value="<?php echo $total; ?>"/>
                <?php
                foreach ($page_images as $key => $row) {
                    if (!isset($_COOKIE['Like_' . $row->id . ''])) {
                        $_COOKIE['Like_' . $row->id . ''] = '';
                    }
                    if (!isset($_COOKIE['Dislike_' . $row->id . ''])) {
                        $_COOKIE['Dislike_' . $row->id . ''] = '';
                    }
                    $num2 = $wpdb->prepare("SELECT `image_status`,`ip` FROM " . $wpdb->prefix . "ux_gallery_like_dislike WHERE image_id = %d AND `ip` = '" . $ux_ip . "'", (int)$row->id);
                    $res3 = $wpdb->get_row($num2);
                    $num3 = $wpdb->prepare("SELECT `image_status`,`ip`,`cook` FROM " . $wpdb->prefix . "ux_gallery_like_dislike WHERE image_id = %d AND `cook` = '" . $_COOKIE['Like_' . $row->id . ''] . "'", (int)$row->id);
                    $res4 = $wpdb->get_row($num3);
                    $num4 = $wpdb->prepare("SELECT `image_status`,`ip`,`cook` FROM " . $wpdb->prefix . "ux_gallery_like_dislike WHERE image_id = %d AND `cook` = '" . $_COOKIE['Dislike_' . $row->id . ''] . "'", (int)$row->id);
                    $res5 = $wpdb->get_row($num4);
                    $title = $row->name;
                    $link = str_replace('__5_5_5__', '%', $row->sl_url);
                    $descnohtml = strip_tags(str_replace('__5_5_5__', '%', $row->description));
                    $result = substr($descnohtml, 0, 50);
                    ?>
                    <div class="element <?= $hover_class ?> view element_<?php echo $galleryID; ?> <?php if ($title == '' && $link == '') {
                        echo 'no-title';
                    } ?>"
                         tabindex="0"
                         data-symbol="<?php echo str_replace('__5_5_5__', '%', $row->name); ?>"
                         data-category="alkaline-earth">
                        <div class="<?= $hover_class ?>-wrapper view-wrapper gallery-image-overlay">
                            <a href="#<?php echo $row->id; ?>"
                               title="<?php echo str_replace('__5_5_5__', '%', $row->name); ?>">
                                <?php
                                $imagerowstype = $row->sl_type;
                                if ($row->sl_type == '') {
                                    $imagerowstype = 'image';
                                }
                                switch ($imagerowstype) {
                                    case 'image':
                                        ?>
                                        <?php $imgurl = explode(";", $row->image_url); ?>
                                        <?php if ($row->image_url != ';') { ?>
                                        <img alt="<?php echo str_replace('__5_5_5__', '%', $row->name); ?>"
                                             id="wd-cl-img<?php echo $key; ?>"
                                             src="<?php if (get_option('uxgallery_image_natural_size_contentpopup') == 'resize') {
                                                 echo esc_url(uxgallery_get_image_by_sizes_and_src($imgurl[0], array(
                                                     get_option('uxgallery_ht_view2_element_width'),
                                                     get_option('uxgallery_ht_view2_element_height')
                                                 ), false));
                                             } else {
                                                 echo $imgurl[0];
                                             } ?>"/>
                                    <?php } else { ?>
                                        <img alt="<?php echo str_replace('__5_5_5__', '%', $row->name); ?>"
                                             id="wd-cl-img<?php echo $key; ?>" src="images/noimage.jpg"/>
                                        <?php
                                    } ?>
                                        <?php
                                        break;
                                    case 'video':
                                        ?>
                                        <?php
                                        $videourl = uxgallery_get_video_id_from_url($row->image_url);
                                        if ($videourl[1] == 'youtube') {
                                            ?>
                                            <img alt="<?php echo str_replace('__5_5_5__', '%', $row->name); ?>"
                                                 src="https://img.youtube.com/vi/<?php echo $videourl[0]; ?>/mqdefault.jpg"/>
                                            <?php
                                        } else {
                                            $hash = unserialize(wp_remote_fopen("https://vimeo.com/api/v2/video/" . $videourl[0] . ".php"));
                                            $imgsrc = $hash[0]['thumbnail_large'];
                                            ?>
                                            <img alt="<?php echo str_replace('__5_5_5__', '%', $row->name); ?>"
                                                 src="<?php echo esc_attr($imgsrc); ?>"/>
                                            <?php
                                        }
                                        ?>
                                        <?php
                                        break;
                                }
                                ?>
                                <?php if (str_replace('__5_5_5__', '%', $row->sl_url) == '') {
                                    $viwMoreButton = '';
                                } else {
                                    if ($row->link_target == "on") {
                                        $target = 'target="_blank"';
                                    } else {
                                        $target = '';
                                    }
                                    $viwMoreButton = '<div class="button-block"><a href="' . str_replace('__5_5_5__', '%', $row->sl_url) . '" ' . $target . ' >' . $uxgallery_get_option["uxgallery_ht_view2_element_linkbutton_text"] . '</a></div>';
                                }
                                ?>
                                <div class="gallery-image-overlay mask">

                                    <div class="mask-text">
                                        <?php if (in_array(get_option("uxgallery_album_popup_show_title"), array('true', 'yes', 'on')) && $row->name != "") { ?>
                                            <h2><?= $row->name ?></h2>
                                        <?php } ?>
                                        <span class="text-category"><?= $row->description ?></span>
                                    </div>


                                    <a href="#<?php echo $row->id; ?>"
                                       title="<?php echo str_replace('__5_5_5__', '%', $row->name); ?>">
                                        <div class="mask-bg">

                                        </div>
                                    </a>
                                </div>
                            </a>
                            <?php if ($like_dislike != 'off'): ?>
                                <div
                                        class="uxgallery_like_cont uxgallery_like_cont_<?php echo $galleryID . $pID; ?>">
                                    <div class="uxgallery_like_wrapper">
						<span class="ux_like">
							<?php if ( $like_dislike == 'heart' ): ?>
								<i class="uxgallery-icons-heart likeheart"></i>
							<?php endif; ?>
							<?php if ( $like_dislike == 'dislike' ): ?>
								<i class="uxgallery-icons uxgallery-icons-thumbs-up like_thumb_up"></i>
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
								class="ux_like_count <?php if ( $uxgallery_get_option['uxgallery_ht_popup_rating_count'] == 'off' ) {
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
								class="ux_dislike_count <?php if ( $uxgallery_get_option['uxgallery_ht_popup_rating_count'] == 'off' ) {
									echo 'ux_hide';
								} ?>"
								id="<?php echo $row->id ?>"><?php echo $row->dislike; ?></span>
						</span>
										</div>
									<?php endif; ?>
								</div>
							<?php endif; ?>
						</div>
				
				</div>
				<?php
			} ?>
		</div>
		<div class="clear"></div>
	</div>
	<?php
	$a = $disp_type;
    if ( ( $a == 1 ) && ( $total != 1 ) ) {
		$uxgallery_content_load_nonce = wp_create_nonce( 'uxgallery_content_load_nonce' );
		?>
		<div class="load_more5">
			<div class="load_more_button5"
			     data-content-nonce-value="<?php echo $uxgallery_content_load_nonce; ?>"><?php echo $uxgallery_get_option['uxgallery_video_ht_view1_loadmore_text']; ?></div>
			<div class="loading5"><img alt="<?php echo str_replace( '__5_5_5__', '%', $row->name ); ?>"
			                           src="<?php if ( $uxgallery_get_option['uxgallery_video_ht_view1_loading_type'] == '1' ) {
				                           echo UXGALLERY_IMAGES_URL . '/front_images/arrows/loading1.gif';
			                           } elseif ( $uxgallery_get_option['uxgallery_video_ht_view1_loading_type'] == '2' ) {
				                           echo UXGALLERY_IMAGES_URL . '/front_images/arrows/loading4.gif';
			                           } elseif ( $uxgallery_get_option['uxgallery_video_ht_view1_loading_type'] == '3' ) {
				                           echo UXGALLERY_IMAGES_URL . '/front_images/arrows/loading36.gif';
			                           } elseif ( $uxgallery_get_option['uxgallery_video_ht_view1_loading_type'] == '4' ) {
				                           echo UXGALLERY_IMAGES_URL . '/front_images/arrows/loading51.gif';
			                           } ?>">
			</div>

		</div>
		<?php
    } elseif (  $a == 0  ) {
		?>
		<div class="paginate5">
			<?php
			$protocol    = stripos( $_SERVER['SERVER_PROTOCOL'], 'https' ) === true ? 'https://' : 'http://';
            $actual_link = esc_url($protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] . "");
			$checkREQ    = '';
			$pattern     = "/\?p=/";
			$pattern2    = "/&page-img[0-9]+=[0-9]+/";
			//$res=preg_match($pattern, $actual_link);
                $pattern_2 = "/\?page_id=/";
                if (preg_match($pattern, $actual_link) || preg_match($pattern_2, $actual_link)) {
				if ( preg_match( $pattern2, $actual_link ) ) {
					$actual_link = preg_replace( $pattern2, '', $actual_link );
				}
				$checkREQ = $actual_link . '&page-img' . $galleryID . $pID;
			} else {
				$checkREQ = '?page-img' . $galleryID . $pID;
			}
			$pervpage = '';
			if ( $page != 1 ) {
				$pervpage = '<a href= ' . $checkREQ . '=1><i class="icon-style5 uxgallery-icons-fast-backward" ></i></a>  
			                               <a href= ' . $checkREQ . '=' . ( $page - 1 ) . '><i class="icon-style5 uxgallery-icons-chevron-left"></i></a> ';
			}
			$nextpage = '';
			if ( $page != $total ) {
				$nextpage = ' <a href= ' . $checkREQ . '=' . ( $page + 1 ) . '><i class="icon-style5 uxgallery-icons-chevron-right"></i></a>  
			                                   <a href= ' . $checkREQ . '=' . $total . '><i class="icon-style5 uxgallery-icons-fast-forward" ></i></a>';
			}
			echo $pervpage . $page . '/' . $total . $nextpage;
			?>
		</div>
		<?php
	}
	?>
</section>
<ul id="uxgallery_popup_list_<?php echo $galleryID; ?>" class="uxgallery_popup_list gallery-img-content"
    data-rating-type="<?php echo $like_dislike; ?>">
	<?php
	$changePopup = 1;
	foreach ( $images as $key => $row ) {
		$imgurl     = explode( ";", $row->image_url );
		$link       = str_replace( '__5_5_5__', '%', $row->sl_url );
		$descnohtml = strip_tags(
			str_replace( '__5_5_5__', '%', $row->description ) );
		$result     = substr( $descnohtml, 0, 50 );
		?>
		<li class="popup-element active" id="uxgallery_popup_element_<?php echo $row->id; ?>">
			<div class="heading-navigation heading-navigation_<?php echo $galleryID; ?>">
				<div class="dispFloat">
					<div class="left-change"><a href="#<?php echo $changePopup - 1; ?>"
					                            data-popupid="#<?php echo $row->id; ?>"><</a></div>
					<div class="right-change"><a href="#<?php echo $changePopup + 1; ?>"
					                             data-popupid="#<?php echo $row->id; ?>">></a></div>
				</div>
				<?php $changePopup = $changePopup + 1; ?>
				<a href="#close" class="close"></a>
				<div class="clear"></div>
			</div>
			<div class="popup-wrapper popup-wrapper_<?php echo $galleryID; ?>">
				<div class="image-block image-block_<?php echo $galleryID; ?>">
					<?php if ( $like_dislike == 'heart' ): ?>
						<div
							class="uxgallery_like_cont uxgallery_like_cont_<?php echo $galleryID . $pID; ?>">
							<div class="uxgallery_like_wrapper">
						<span class="ux_like">
							<?php if ( $like_dislike == 'heart' ): ?>
								<i class="uxgallery-icons-heart likeheart"></i>
							<?php endif; ?>
							<?php if ( $like_dislike == 'dislike' ): ?>
								<i class="uxgallery-icons uxgallery-icons-thumbs-up like_thumb_up"></i>
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
								} ?>"
								id="<?php echo $row->id ?>"><?php echo $row->dislike; ?></span>
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
							<img alt="<?php echo str_replace( '__5_5_5__', '%', $row->name ); ?>"
							     id="wd-cl-big-img<?php echo $key; ?>" src="<?php echo esc_attr( $imgurl[0] ); ?>"/>
						<?php } else { ?>
							<img alt="<?php echo str_replace( '__5_5_5__', '%', $row->name ); ?>"
							     id="wd-cl-big-img<?php echo $key; ?>" src="images/noimage.jpg"/>
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
								<iframe src="//www.youtube.com/embed/<?php echo $videourl[0]; ?>" frameborder="0"
								        allowfullscreen></iframe>
								<?php
							} else {
								?>
								<iframe
									src="//player.vimeo.com/video/<?php echo $videourl[0]; ?>?title=0&amp;byline=0&amp;portrait=0"
									frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
								<?php
							}
							?>
							<?php
							break;
					}
					?>
					<?php if ( str_replace( '__5_5_5__', '%', $row->sl_url ) == '' ) {
						$viwMoreButton = '';
					} else {
						if ( $row->link_target == "on" ) {
							$target = 'target="_blank"';
						} else {
							$target = '';
						}
						$viwMoreButton = '<div class="button-block"><a href="' . str_replace( '__5_5_5__', '%', $row->sl_url ) . '" ' . $target . ' >' . $uxgallery_get_option["uxgallery_ht_view2_popup_linkbutton_text"] . '</a></div>';
					}
					?>
				</div>
				<div
					class="right-block"><?php if ( $uxgallery_get_option["uxgallery_ht_view2_show_popup_title"] == 'on' && $row->name != '' && $row->name != null ) { ?>
						<h3 class="title"><?php echo str_replace( '__5_5_5__', '%', $row->name ); ?></h3><?php } ?>
					<?php if ( $uxgallery_get_option["uxgallery_ht_view2_show_description"] == 'on' ) { ?>
						<div class="description"><?php echo str_replace( '__5_5_5__', '%', $row->description ); ?></div>
					<?php } ?>
					<?php if ( $like_dislike != 'off' && $like_dislike != 'heart' ): ?>
						<div
							class="uxgallery_like_cont uxgallery_like_cont_<?php echo $galleryID . $pID; ?>">
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
								class="ux_like_count <?php if ( $uxgallery_get_option['uxgallery_ht_popup_rating_count'] == 'off' ) {
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
								class="ux_dislike_count <?php if ( $uxgallery_get_option['uxgallery_ht_popup_rating_count'] == 'off' ) {
									echo 'ux_hide';
								} ?>"
								id="<?php echo $row->id ?>"><?php echo $row->dislike; ?></span>
							</span>
								</div>
							<?php endif; ?>
						</div>
					<?php endif; ?>
					<?php if ( $uxgallery_get_option["uxgallery_ht_view2_show_popup_linkbutton"] == 'on' ) { ?>
						<?php echo $viwMoreButton; ?>
					<?php } ?>
					<div class="clear"></div>
				</div>
				<div class="clear"></div>
			</div>
		</li>
		<?php
	} ?>
</ul>
<?php if ($hover_class == "view-fifth") { ?>

    <script type="text/javascript">
        jQuery(document).ready(function () {
            jQuery('.view-fifth ').each(function () {
                jQuery(this).hoverdir();
            });
        });
    </script>
<?php } ?>