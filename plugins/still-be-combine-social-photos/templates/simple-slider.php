<?php

namespace StillBE\Plugin\CombineSocialPhotos;


// Do not allow direct access to the file.
if( ! defined( 'ABSPATH' ) ) {
	exit;
}



// $account = user_object
// $data = ig_object[]




$is_editor    = empty( $content );
$attr_onclick = $is_editor ? 'return false;' : '';




$author_position    = $attributes['authorPosition'] ?? 'out';
$is_show_author_in  = ! empty( $attributes['isShowAuthor'] ?? 1 ) && 'in'  === $author_position;
$is_show_author_out = ! empty( $attributes['isShowAuthor'] ?? 1 ) && 'out' === $author_position;


$time_position    = $attributes['timePosition'] ?? 'out';
$is_show_time_in  = ! empty( $attributes['isShowTime'] ?? 1 ) && 'in'  === $time_position;
$is_show_time_out = ! empty( $attributes['isShowTime'] ?? 1 ) && 'out' === $time_position;


$caption_position    = $attributes['captionPosition'] ?? 'out';
$is_show_caption_in  = ! empty( $attributes['isShowCaption'] ?? 1 ) && 'in'  === $caption_position;
$is_show_caption_out = ! empty( $attributes['isShowCaption'] ?? 1 ) && 'out' === $caption_position;


$impressions_position    = $attributes['impressionsPosition'] ?? 'out';
$is_show_impressions_in  = ! empty( $attributes['isShowImpressions'] ?? 1 ) && 'in'  === $impressions_position;
$is_show_impressions_out = ! empty( $attributes['isShowImpressions'] ?? 1 ) && 'out' === $impressions_position;
$is_show_impressions     = $is_show_impressions_in || $is_show_impressions_out;


$has_hover_elements = $is_show_caption_in  || $is_show_impressions_in  || $is_show_author_in  || $is_show_time_in;

$has_below_elements = $is_show_caption_out || $is_show_impressions_out || $is_show_author_out || $is_show_time_out;


// Fit to Container
$is_fit_container = ( $attributes['isFitToContainer'] ?? true ) ? 'fit-container' : '';


// Exclude Navigation Buttons
$is_exclude_navs = isset( $attributes['excludeNavigations'] ) && $attributes['excludeNavigations'] ? 'navigations' : '';



// Classes
$classes = ( $attributes['className'] ?? '' ). ( $has_hover_elements ? ' has-hover-elements' : '' );

// Align
if( isset( $attributes['align'] ) ) {
	$classes .= ' align'. $attributes['align'];
}

// Guide of Root
$classes .= ' sb-csp-simple-slider-root';



// Customize Styles
$styles = [];

// Base Width
if( isset( $attributes['baseWidth'] ) ) {
	$styles[] = '--sb-csp-width-base: '. $attributes['baseWidth']. 'px;';
}

// Min Width
if( isset( $attributes['minWidth'] ) ) {
	$styles[] = '--sb-csp-width-min: '. $attributes['minWidth']. 'px;';
}

// Min Columns
if( isset( $attributes['minCols'] ) ) {
	$styles[] = '--sb-csp-columns-min: '. $attributes['minCols']. ';';
}

// Rows
if( isset( $attributes['rows'] ) ) {
	$styles[] = '--sb-csp-rows: '. $attributes['rows']. ';';
}

// Columns
$columns = $attributes['columns'] ?? PHP_INT_MAX;
$columns = min( ceil( count( $data ) / ( $attributes['rows'] ?? 1 ) ), $columns );
$styles[] = '--sb-csp-columns: '. $columns. ';';

// Aspect Ratio
if( isset( $attributes['aspect'] ) ) {
	$styles[] = '--sb-csp-aspect: '. ( $attributes['aspect'] * 100 ). '%;';
}

// Gap
if( isset( $attributes['gapColumns'] ) ) {
	$styles[] = '--sb-csp-gap-columns: '. $attributes['gapColumns']. ';';
}
if( isset( $attributes['gapRows'] ) ) {
	$styles[] = '--sb-csp-gap-rows: '. $attributes['gapRows']. ';';
}

// Instagram Post Where to Open
if( empty( $attributes['linkTarget'] ) ) {
	$link_target = '_self';
} else {
	$link_target = strval( $attributes['linkTarget'] );
}

// Caption Rows
if( ! empty( $attributes['isShowCaption'] ) ) {
	$styles[] = '--sb-csp-is-show-caption-display: -webkit-box;';
	$styles[] = '--sb-csp-is-show-caption-rows: '. ( $attributes['captionRows'] ?? 4 ). ';';
}

// Is Show Author
if( ! $is_show_author_in ) {
	$styles[] = '--sb-csp-is-show-author-in: none;';
}
if( ! $is_show_author_out ) {
	$styles[] = '--sb-csp-is-show-author-out: none;';
}

// Is Show Post Time
if( ! $is_show_time_in ) {
	$styles[] = '--sb-csp-is-show-time-in: none;';
}
if( ! $is_show_time_out ) {
	$styles[] = '--sb-csp-is-show-time-out: none;';
}

// Is Show Footer
if( isset( $attributes['isShowFooter'] ) && ! $attributes['isShowFooter'] ) {
	$styles[] = '--sb-csp-is-show-footer: none;';
}

// Hover Effect; Blur
if( isset( $attributes['hoverEffectBlur'] ) && $attributes['hoverEffectBlur'] ) {
	$styles[] = '--sb-csp-hover-effect-blur: blur(0.2em);';
}

// Hover Effect; Tilt
if( isset( $attributes['hoverEffectTilt'] ) && $attributes['hoverEffectTilt'] ) {
	$styles[] = '--sb-csp-hover-effect-tilt: rotate(-2deg);';
}

// Scroll Duration Time
if( isset( $attributes['scrollDurationTime'] ) && 50 <= $attributes['scrollDurationTime'] && 2000 >= $attributes['scrollDurationTime'] ) {
	$styles[] = '--sb-csp-scroll-duration: '. intval( $attributes['scrollDurationTime'] ). 'ms;';
}

// Scroll Easing Function
if( isset( $attributes['scrollEasingFunction'] ) &&
      ( 1 <= $attributes['scrollEasingFunction'] && 5 >= $attributes['scrollEasingFunction'] ||
        99 == $attributes['scrollEasingFunction'] ) ) {
	$styles[] = '--sb-csp-easing-function: '. intval( $attributes['scrollEasingFunction'] ). ';';
}

// Cubic-bezier Params
if( isset( $attributes['cubicBezierAccuracy'] ) && is_numeric( $attributes['cubicBezierAccuracy'] ) ) {
	$styles[] = '--sb-csp-cubix-bezier-acc: '. abs( floatval( $attributes['cubicBezierAccuracy'] ) ). ';';
}
if( isset( $attributes['cubicBezierX1'] ) && is_numeric( $attributes['cubicBezierX1'] ) ) {
	$styles[] = '--sb-csp-cubix-bezier-x1: '. floatval( $attributes['cubicBezierX1'] ). ';';
}
if( isset( $attributes['cubicBezierY1'] ) && is_numeric( $attributes['cubicBezierY1'] ) ) {
	$styles[] = '--sb-csp-cubix-bezier-y1: '. floatval( $attributes['cubicBezierY1'] ). ';';
}
if( isset( $attributes['cubicBezierX2'] ) && is_numeric( $attributes['cubicBezierX2'] ) ) {
	$styles[] = '--sb-csp-cubix-bezier-x2: '. floatval( $attributes['cubicBezierX2'] ). ';';
}
if( isset( $attributes['cubicBezierY2'] ) && is_numeric( $attributes['cubicBezierY2'] ) ) {
	$styles[] = '--sb-csp-cubix-bezier-y2: '. floatval( $attributes['cubicBezierY2'] ). ';';
}

$custom_style = implode( ' ', $styles );



// Video Thumb for No Thumbnail
$no_thumb_video_path = '/asset/img/thumb-video.png';
$no_thumb_video_url  = STILLBE_CSP_BASE_URL. $no_thumb_video_path. '?ver='. @filemtime( STILLBE_CSP_BASE_DIR. $no_thumb_video_path );



// Image for No Thumbnail
$no_thumb_image_path = '/asset/img/thumb-cache-expired.png';
$no_thumb_image_url  = STILLBE_CSP_BASE_URL. $no_thumb_image_path. '?ver='. @filemtime( STILLBE_CSP_BASE_DIR. $no_thumb_image_path );



// Go Instagram App
$go_instagram_path = '/asset/img/thumb-go-ig.png';
$go_instagram_url  = STILLBE_CSP_BASE_URL. $go_instagram_path. '?ver='. @filemtime( STILLBE_CSP_BASE_DIR. $go_instagram_path );



// Profile Picture
$profile_picture = '';
if( ! empty( $hashtag ) ) {
	$icon_hash_url = STILLBE_CSP_BASE_URL. '/asset/img/hashtag-icon.svg';
} elseif( ! empty( $account->profile_picture_id ) ) {
	$profile_picture_src = wp_get_attachment_image_src( $account->profile_picture_id, 'thumbnail' );
	$profile_picture     = empty( $profile_picture_src[0] ) ? '' : $profile_picture_src[0];
} elseif( ! empty( $account->profile_picture_url ) && false === strpos( $account->profile_picture_url, 'data:image/gif;' ) ) {
	$profile_picture = $account->profile_picture_url;
} elseif( ! empty( $account->me->profile_picture_url ) ) {
	$profile_picture = $account->me->profile_picture_url;
}



ob_start();



?>
<div class="<?php echo esc_attr( $classes ); ?>" style="<?php echo esc_attr( $custom_style ); ?>"
     data-account_id="<?php echo esc_attr( $attributes['id'] ); ?>"
     data-get_media_count="<?php echo esc_attr( $get_media_count ); ?>"
     data-advanced[business_discovery]="<?php echo esc_attr( $advanced->business_discovery ); ?>"
     data-advanced[hashtag_recent]="<?php echo esc_attr( $advanced->hashtag_recent ); ?>"
     data-advanced[hashtag_top]="<?php echo esc_attr( $advanced->hashtag_top ); ?>"
     data-advanced[exclude_video]="<?php echo esc_attr( $advanced->exclude_video ? 1 : 0 ); ?>">
  <aside class="sb-csp-ig-simple-slider">
    <div class="ig-wrapper">
      <figure class="ig-feed-wrapper" data-size="<?php echo esc_attr( $is_fit_container ); ?>" data-exclude="<?php echo esc_attr( $is_exclude_navs ); ?>">
        <ul class="ig-feed-list<?php if( empty( $data ) ) { echo esc_attr( ' no-post' ); } ?>" data-no-post="<?php esc_attr_e( 'There are no posts available for display.', 'still-be-combine-social-photos' ); ?>">
<?php
	// IG Feed
	$has_modal = false;
	foreach( $data as $post ) {
		// Images
		if( empty( $post->children->data ) && ! empty( $post->media_url ) ) {
			if( empty( $post->thumbnail_url ) ) {
				$type = 'VIDEO' === $post->media_type ? 'video' : 'image';
				$imgs = [ $type. '::'. $post->media_url ];
			} else {
				$imgs = [ 'video::'. $post->media_url. '||thumb::'. $post->thumbnail_url ];
			}
		} else {
			$imgs = array_map( function( $d ) {
				if( empty( $d->thumbnail_url ) ) {
					$type = 'VIDEO' === $d->media_type ? 'video' : 'image';
					return $type. '::'. ( $d->media_url ?? null );
				}
				return 'video::'. ( $d->media_url ?? null ). '||thumb::'. $d->thumbnail_url;
			}, ( $post->children->data ?? [] ) );
		}
		$imgs = array_filter( $imgs );
		// Caption
		$caption = json_encode( $post->caption ?? '' );
		// Post Time
		$time = wp_date( esc_html__( 'Y-m-d', 'still-be-combine-social-photos' ), strtotime( $post->timestamp ) );
?>
          <li class="ig-feed">
            <div class="ig-post-container">
              <a href="<?php echo esc_url( $post->permalink ); ?>" class="ig-post sb-csp-a-tag"
                 target="<?php echo esc_attr( $link_target ); ?>" rel="<?php if( '_self' !== $link_target ) { echo 'noopener'; } ?>"
<?php
		if( 'stillbe-modal-win' === $link_target ) {
			$has_modal = true;
?>
                 data-permalink="<?php echo esc_url( $post->permalink ); ?>"
                 data-profile-picture="<?php echo esc_url( $profile_picture ); ?>"
                 data-img="<?php echo esc_attr( implode( ',', $imgs ) ); ?>"
                 data-caption="<?php echo esc_attr( $caption ); ?>"
                 data-like-count="<?php if( $is_show_impressions && isset( $post->like_count ) ) { echo esc_attr( number_format( (int) $post->like_count ) ); } ?>"
                 data-comments-count="<?php if( $is_show_impressions && isset( $post->comments_count ) ) { echo esc_attr( number_format( (int) $post->comments_count ) ); } ?>"
                 data-name="<?php if( empty( $hashtag ) ) { echo esc_attr( $account->name ?? ( $account->me->name ?? '' ) ); } ?>"
                 data-username="<?php if( empty( $hashtag ) ) { echo esc_attr( $account->me->username ); } ?>"
                 data-open-instagram="<?php esc_attr_e( 'Open in Instagram', 'still-be-combine-social-photos' ); ?>"
                 data-timestamp="<?php echo esc_attr( $post->timestamp ); ?>"
                 data-time="<?php echo esc_attr( $time ); ?>"
<?php
		}
?>
                 onclick="<?php echo esc_attr( $attr_onclick ); ?>">
                <?php
                	if( 'CAROUSEL_ALBUM' === $post->media_type ) {
                		$post->media_type    = $post->children->data[0]->media_type     ?? '';
                		$post->media_url     = $post->children->data[0]->media_url      ?? '';
                		$post->thumbnail_url = $post->children->data[0]->thumbnail_url  ?? $no_thumb_video_url;
                	}
                	if( 'VIDEO' !== $post->media_type ) {
                		echo '<img src="'. esc_url( $post->media_url ?? $no_thumb_image_url ). '" alt="'. esc_attr( wp_trim_words( $post->caption ?? '' ) ). '" loading="lazy">';
                	} else {
                		if( $has_hover_elements || empty( $post->media_url ) ) {
                			if( 'autoplay' === $attributes['displayingVideo'] ) {
                				echo '<video src="'. esc_url( $post->media_url ). '" muted autoplay playsinline loop>';
                				echo   '<p class="ig-post-video-unavailable">'. esc_html__( 'Your browser does not support video playback.', 'still-be-combine-social-photos' ). '</p>';
                				echo '</video>';
                			} else {
                				echo '<img src="'. esc_url( $post->thumbnail_url ?? $go_instagram_url ). '" alt="'. esc_attr( wp_trim_words( $post->caption ?? '' ) ). '" loading="lazy">';
                			}
                		//	echo '<img src="'. esc_url( $has_hover_elements ? $go_instagram_url : ( $post->thumbnail_url ?? $no_thumb_video_url ) ). '" alt="'. esc_attr( wp_trim_words( $post->caption ?? '' ) ). '" loading="lazy">';
                		} else{
                			$options = 'autoplay' === $attributes['displayingVideo'] ? [ 'muted', 'autoplay', 'playsinline', 'loop' ] : [ 'controls', 'muted' ];
                			echo '<video src="'. esc_url( $post->media_url ). '" poster="'. esc_url( $post->thumbnail_url ?? $no_thumb_video_url ). '" '. implode( ' ', array_map( 'esc_attr', $options ) ). ' preload="none">';
                			echo   '<p class="ig-post-video-unavailable">'. esc_html__( 'Your browser does not support video playback.', 'still-be-combine-social-photos' ). '</p>';
                			echo '</video>';
                		}
                	}
                ?>
<?php
		if( $has_hover_elements ) {
?>
                <div class="ig-post-detail in-hover-area">
<?php
			if( $is_show_caption_in ) {
?>
                  <div class="ig-post-caption-wrapper">
                    <p class="ig-post-caption">
                      <?php echo str_replace( "\n", '<br>', esc_html( wp_trim_words( $post->caption ?? '', 500 ) ) ); ?>
                    </p>
                  </div>
<?php
			}
			if( $is_show_impressions_in ) {
?>
                  <ul class="ig-post-impression">
                    <?php
                    	if( isset( $post->like_count ) ) {
                    		echo '<li class="ig-post-likes">'. esc_html( number_format( (int) $post->like_count ) ). '</li>';
                    	}
                    ?>
                    <?php
                    	if( isset( $post->comments_count ) ) {
                    		echo '<li class="ig-post-comments">'. esc_html( number_format( (int) $post->comments_count ) ). '</li>';
                    	}
                    ?>
                  </ul>
<?php
			}
			if( ! $is_show_caption_in && ! $is_show_impressions_in ) {
?>
                  <div class="dummy"><!-- Dummy elements for layout adjustment --></div>
<?php
			}
			if( empty( $hashtag ) && $is_show_author_in ) {
?>
                  <address class="ig-post-author">
                    <?php
                    	if( ( empty( $account->name ) && empty( $account->me->name ) ) ||
                    	      ( isset( $account->name ) && $account->name === $account->me->username ) ) {
                    		echo '<span>@'. esc_html( $account->me->username ). '</span>';
                    	} else {
                    		echo '<span>'. esc_html( $account->name ?? $account->me->name ). '</span>';
                    		echo '<span>'. esc_html( $account->me->username ). '</span>';
                    	}
                    	echo "\n";
                    ?>
                  </address>
<?php
			}
			if( $is_show_time_in ) {
?>
                  <time datetime="<?php echo esc_attr( $post->timestamp ); ?>" class="ig-post-time">
                    <span><?php echo esc_html( $time ); ?></span>
                  </time>
<?php
			}
?>
                </div>
<?php
		}
?>
                <?php
                	if( 'VIDEO' === $post->media_type ) {
                		echo '<b class="ig-post-type video">Video</b>';
                	}
                	if( isset( $post->children->data[1] ) ) {
                		echo '<b class="ig-post-type album">Album</b>';
                	}
                	echo "\n";
                ?>
              </a>
<?php
		if( $has_below_elements ) {
?>
              <div class="ig-post-detail below-image">
<?php
			if( $is_show_author_out || $is_show_time_out ) {
?>
                <div class="ig-author-wrapper">
<?php
				if( empty( $hashtag ) ) {
?>
                  <figure class="ig-user-picture">
                    <?php
                    	if( ! empty( $account->profile_picture_id ) ) {
                    		echo wp_get_attachment_image( $account->profile_picture_id, 'thumbnail', false, array( 'alt' => 'Profile Picture' ) );
                    	} elseif( ! empty( $account->profile_picture_url ) && false === strpos( $account->profile_picture_url, 'data:image/gif;' ) ) {
                    		echo '<img src="'. esc_url( $account->profile_picture_url ). '" alt="'. esc_attr( 'Profile Picture of '. $account->me->username ). '" loading="lazy">';
                    	} elseif( ! empty( $account->me->profile_picture_url ) ) {
                    		echo '<img src="'. esc_url( $account->me->profile_picture_url ). '" alt="'. esc_attr( 'Profile Picture of '. $account->me->username ). '" loading="lazy">';
                    	} else {
                    		$icon_ig_url = STILLBE_CSP_BASE_URL. '/asset/img/ig-icon.png';
                    		echo '<img src="'. esc_url( $icon_ig_url ). '" alt="Instagram Icon" width="150" height="150" loading="lazy" class="ig-icon">';
                    	}
                    	echo "\n";
                    ?>
                  </figure>
<?php
				}
?>
                  <div class="ig-user-info">
<?php
				if( empty( $hashtag ) ) {
?>
                    <address class="ig-post-author">
                      <a href="<?php echo esc_url( "https://www.instagram.com/{$account->me->username}/" ); ?>" aria-label="<?php esc_attr_e( 'Visit my IG account', 'still-be-combine-social-photos' ); ?>" class="ig-user-name" target="_blank" rel="noopener" onclick="<?php echo esc_attr( $attr_onclick ); ?>">
                        <?php
                        	if( ( empty( $account->name ) && empty( $account->me->name ) ) ||
                        	      ( isset( $account->name ) && $account->name === $account->me->username ) ) {
                        		echo '<span>@'. esc_html( $account->me->username ). '</span>';
                        	} else {
                        		echo '<span>'. esc_html( $account->name ?? $account->me->name ). '</span>';
                        		echo '<span>'. esc_html( $account->me->username ). '</span>';
                        	}
                        	echo "\n";
                        ?>
                      </a>
                    </address>
<?php
				}
?>
                    <time datetime="<?php echo esc_attr( $post->timestamp ); ?>" class="ig-post-time">
                      <span><?php echo esc_html( $time ); ?></span>
                    </time>
                  </div>
                </div>
<?php
			}
			if( $is_show_caption_out ) {
?>
                <p class="ig-post-caption">
                  <?php echo str_replace( "\n", '<br>', esc_html( wp_trim_words( $post->caption ?? '', 500 ) ) ); ?>
                </p>
<?php
			}
			if( $is_show_impressions_out ) {
?>
                <ul class="ig-post-impression">
                  <?php
                  	if( isset( $post->like_count ) ) {
                  		echo '<li class="ig-post-likes">'. esc_html( number_format( (int) $post->like_count ) ). '</li>';
                  	}
                  	echo "\n";
                  ?>
                  <?php
                  	if( isset( $post->comments_count ) ) {
                  		echo '<li class="ig-post-comments">'. esc_html( number_format( (int) $post->comments_count ) ). '</li>';
                  	}
                  	echo "\n";
                  ?>
                </ul>
<?php
			}
?>
              </div>
<?php
		}
?>
            </div>
          </li>
<?php
	}
	// END of IG Feed
?>
        </ul>
        <figcaption class="ig-from <?php echo esc_attr( $attributes['footerPosition'] ?? 'center' ); ?>">
          <span>Embed by</span>
          <a href="<?php echo esc_url( __( 'https://wordpress.org/plugins/still-be-image-quality-control/', 'still-be-combine-social-photos' ) ); ?>" target="_blank" rel="noopener" class="sb-csp-a-tag" title="Combine Social Photos | Still BE" onclick="<?php echo esc_attr( $attr_onclick ); ?>">Combine Social Photos</a>
          <span>from</span>
          <cite class="ig-logo">
            <a href="https://www.instagram.com/" target="_blank" rel="noopener" title="Instagram" class="sb-csp-a-tag" onclick="<?php echo esc_attr( $attr_onclick ); ?>">
              Instagram
            </a>
          </cite>
        </figcaption>
      </figure>
<?php
	if( $has_modal ) {
		include( __DIR__. '/template-modal.php' );
	}
?>
    </div>
  </aside>
</div>
<?php




$html = ob_get_clean();

$html = apply_filters( 'stillbe_csp/simple_slider__dynamic_html', $html, $attributes, $account, $data );

return $html;



