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




$is_show_follows_count   = ! empty( $attributes['isShowFollows']     ?? 1 );
$is_show_followers_count = ! empty( $attributes['isShowFollowers']   ?? 1 );
$is_show_impressions     = ! empty( $attributes['isShowImpressions'] ?? 1 ) && ( $is_show_follows_count || $is_show_followers_count );


$has_detail = $is_show_impressions;




// Classes
$classes = $attributes['className'] ?? '';




// Customize Styles
$styles = [];

// Outline Gap
if( isset( $attributes['outlineGap'] ) ) {
	$styles[] = '--sb-csp-outline-gap: '. $attributes['outlineGap']. ';';
}
if( isset( $attributes['outlineGapTablet'] ) ) {
	$styles[] = '--sb-csp-outline-gap-tablet: '. $attributes['outlineGapTablet']. ';';
}
if( isset( $attributes['outlineGapSp'] ) ) {
	$styles[] = '--sb-csp-outline-gap-sp: '. $attributes['outlineGapSp']. ';';
}

// Columns
if( isset( $attributes['columns'] ) ) {
	$styles[] = '--sb-csp-cols: '. $attributes['columns']. ';';
}
if( isset( $attributes['columnsTablet'] ) ) {
	$styles[] = '--sb-csp-cols-tablet: '. $attributes['columnsTablet']. ';';
}
if( isset( $attributes['columnsSp'] ) ) {
	$styles[] = '--sb-csp-cols-sp: '. $attributes['columnsSp']. ';';
}

// Aspect Ratio
if( isset( $attributes['aspect'] ) ) {
	$styles[] = '--sb-csp-aspect: '. ( $attributes['aspect'] * 100 ). '%;';
}
if( isset( $attributes['aspectTablet'] ) ) {
	$styles[] = '--sb-csp-aspect-tablet: '. ( $attributes['aspectTablet'] * 100 ). '%;';
}
if( isset( $attributes['aspectSp'] ) ) {
	$styles[] = '--sb-csp-aspect-sp: '. ( $attributes['aspectSp'] * 100 ). '%;';
}

// Gap
if( isset( $attributes['gap'] ) ) {
	$styles[] = '--sb-csp-gap: '. $attributes['gap']. ';';
}
if( isset( $attributes['gapTablet'] ) ) {
	$styles[] = '--sb-csp-gap-tablet: '. $attributes['gapTablet']. ';';
}
if( isset( $attributes['gapSp'] ) ) {
	$styles[] = '--sb-csp-gap-sp: '. $attributes['gapSp']. ';';
}

// Highlight
if( ! empty( $attributes['isHighlight'] ) && isset( $attributes['highlightSize'] ) ) {
	$styles[] = '--sb-csp-highlight-size: '. $attributes['highlightSize']. ';';
}
if( ! empty( $attributes['isHighlightTablet'] ) && isset( $attributes['highlightSizeTablet'] ) ) {
	$styles[] = '--sb-csp-highlight-size-tablet: '. $attributes['highlightSizeTablet']. ';';
}
if( ! empty( $attributes['isHighlightSp'] ) && isset( $attributes['highlightSizeSp'] ) ) {
	$styles[] = '--sb-csp-highlight-size-sp: '. $attributes['highlightSizeSp']. ';';
}

// Highlight Position
if( ! empty( $attributes['isHighlight'] ) && isset( $attributes['highlightTop'] ) ) {
	$styles[] = '--sb-csp-highlight-top: '. $attributes['highlightTop']. ';';
}
if( ! empty( $attributes['isHighlight'] ) && isset( $attributes['highlightLeft'] ) ) {
	$styles[] = '--sb-csp-highlight-left: '. $attributes['highlightLeft']. ';';
}
if( ! empty( $attributes['isHighlightTablet'] ) && isset( $attributes['highlightTopTablet'] ) ) {
	$styles[] = '--sb-csp-highlight-top-tablet: '. $attributes['highlightTopTablet']. ';';
}
if( ! empty( $attributes['isHighlightTablet'] ) && isset( $attributes['highlightLeftTablet'] ) ) {
	$styles[] = '--sb-csp-highlight-left-tablet: '. $attributes['highlightLeftTablet']. ';';
}
if( ! empty( $attributes['isHighlightSp'] ) && isset( $attributes['highlightTopSp'] ) ) {
	$styles[] = '--sb-csp-highlight-top-sp: '. $attributes['highlightTopSp']. ';';
}
if( ! empty( $attributes['isHighlightSp'] ) && isset( $attributes['highlightLeftSp'] ) ) {
	$styles[] = '--sb-csp-highlight-left-sp: '. $attributes['highlightLeftSp']. ';';
}

// Has Ouline Shadow
if( isset( $attributes['hasOutlineShadow'] ) ) {
	if( $attributes['hasOutlineShadow'] ) {
		$styles[] = '--sb-csp-outline-shadow: 0.5em 0.5em 3em rgba(64,64,64,0.08);';
	} else {
		$styles[] = '--sb-csp-outline-shadow: none;';
	}
} else {
	if( ! preg_match( '/\bis-style-simple\b/i', $classes ) ) {
		$styles[] = '--sb-csp-outline-shadow: 0.5em 0.5em 3em rgba(64,64,64,0.08);';
	} else {
		$styles[] = '--sb-csp-outline-shadow: none;';
	}
}

// Instagram Post Where to Open
if( empty( $attributes['linkTarget'] ) ) {
	$link_target = '_self';
} else {
	$link_target = strval( $attributes['linkTarget'] );
}

// Is Show Header
if( isset( $attributes['isShowHeader'] ) && ! $attributes['isShowHeader'] ) {
	$styles[] = '--sb-csp-is-show-header: none;';
	$styles[] = '--sb-csp-margin-above-feed: 0;';
}

// Is Show Footer
if( isset( $attributes['isShowFooter'] ) && ! $attributes['isShowFooter'] ) {
	$styles[] = '--sb-csp-is-show-footer: none;';
}

// Post Information Position
$info_position = 'inner';
if( ! empty( $attributes['infoPosition'] ) ) {
	$info_position = $attributes['infoPosition'];
	$styles[]         = '--sb-csp-caption-pos: '. $attributes['infoPosition']. ';';
}

// Is Show Caption
if( ! empty( $attributes['isShowCaption'] ) ) {
	$styles[] = '--sb-csp-is-show-caption-display: block;';
	$styles[] = '--sb-csp-is-show-caption-rows: '. ( $attributes['captionRows'] ?? 4 ). ';';
	$has_detail = true;
}

// Is Show Author
if( empty( $attributes['isShowAuthor'] ) ) {
	$styles[] = '--sb-csp-is-show-author: none;';
} else {
	$has_detail = true;
}

// Is Show Time
if( isset( $attributes['isShowTime'] ) && ! $attributes['isShowTime'] ) {
	$styles[] = '--sb-csp-is-show-time: none;';
} else {
	$has_detail = true;
}

// Is Show Impressions
if( ! $is_show_impressions ) {
	$styles[] = '--sb-csp-is-show-impressions: none;';
}

// Hover Effect; Blur
if( isset( $attributes['hoverEffectBlur'] ) && $attributes['hoverEffectBlur'] ) {
	$styles[] = '--sb-csp-hover-effect-blur: blur(0.2em);';
}

// Hover Effect; Tilt
if( isset( $attributes['hoverEffectTilt'] ) && $attributes['hoverEffectTilt'] ) {
	$styles[] = '--sb-csp-hover-effect-tilt: rotate(-2deg);';
}

$has_detail = $has_detail && 'inner' === $info_position;

// Hover Overlay Color
if( isset( $attributes['hoverOverlayColor'] ) ) {
	if( 'light' !== $attributes['hoverOverlayColor'] ) {
		$styles[] = '--sb-csp-hover-overlay-bg-color: rgba(0,0,0,0.75);';
		$styles[] = '--sb-csp-hover-overlay-font-color: white;';
	} else {
		$overlay_opacity = $has_detail ? '0.95' : '0.5';
		$styles[] = "--sb-csp-hover-overlay-bg-color: rgba(255,255,255,{$overlay_opacity});";
		$styles[] = '--sb-csp-hover-overlay-font-color: black;';
	}
}

// Has Detail
if( $has_detail ) {
	$styles[] = '--sb-csp-detail-opacity: 0.8;';
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




// Align
if( isset( $attributes['align'] ) ) {
	$classes .= ' align'. $attributes['align'];
}

// Guide of Root
$classes .= ' sb-csp-simple-grid-root';


ob_start();


?>
<div class="<?php echo esc_attr( $classes ); ?>" style="<?php echo esc_attr( $custom_style ); ?>"
     data-account_id="<?php echo esc_attr( $attributes['id'] ); ?>"
     data-get_media_count="<?php echo esc_attr( $get_media_count ); ?>"
     data-advanced[business_discovery]="<?php echo esc_attr( $advanced->business_discovery ); ?>"
     data-advanced[hashtag_recent]="<?php echo esc_attr( $advanced->hashtag_recent ); ?>"
     data-advanced[hashtag_top]="<?php echo esc_attr( $advanced->hashtag_top ); ?>"
     data-advanced[exclude_video]="<?php echo esc_attr( $advanced->exclude_video ? 1 : 0 ); ?>">
  <aside class="sb-csp-ig-simple-grid">
    <div class="ig-wrapper">
      <header class="ig-header <?php echo esc_attr( $attributes['headerPosition'] ?? 'center' ); ?>">
        <figure class="ig-user-picture<?php if( ! empty( $hashtag ) ) { echo ' hashtag-icon'; } ?>">
          <?php
          	$profile_picture = '';
          	if( ! empty( $hashtag ) ) {
          		$icon_hash_url = STILLBE_CSP_BASE_URL. '/asset/img/hashtag-icon.svg';
          		echo '<img src="'. esc_url( $icon_hash_url ). '" alt="'. esc_attr( $hashtag ). '" width="150" height="150" loading="lazy">';
          	} elseif( ! empty( $account->profile_picture_id ) ) {
          		$profile_picture_src = wp_get_attachment_image_src( $account->profile_picture_id, 'thumbnail' );
          		$profile_picture     = empty( $profile_picture_src[0] ) ? '' : $profile_picture_src[0];
          		echo wp_get_attachment_image( $account->profile_picture_id, 'thumbnail', false, array( 'alt' => 'Profile Picture' ) );
          	} elseif( ! empty( $account->profile_picture_url ) && false === strpos( $account->profile_picture_url, 'data:image/gif;' ) ) {
          		$profile_picture = $account->profile_picture_url;
          		echo '<img src="'. esc_url( $account->profile_picture_url ). '" alt="'. esc_attr( 'Profile Picture of '. $account->me->username ). '" loading="lazy">';
          	} elseif( ! empty( $account->me->profile_picture_url ) ) {
          		$profile_picture = $account->me->profile_picture_url;
          		echo '<img src="'. esc_url( $account->me->profile_picture_url ). '" alt="'. esc_attr( 'Profile Picture of '. $account->me->username ). '" loading="lazy">';
          	} else {
          		$icon_ig_url = STILLBE_CSP_BASE_URL. '/asset/img/ig-icon.png';
          		echo '<img src="'. esc_url( $icon_ig_url ). '" alt="Instagram" width="150" height="150" loading="lazy" class="ig-icon">';
          	}
          ?>
        </figure>
        <div class="ig-user-info">
          <a href="<?php echo esc_url( empty( $hashtag ) ? "https://www.instagram.com/{$account->me->username}/" : "https://www.instagram.com/explore/tags/{$hashtag}/" ); ?>"
             aria-label="<?php echo esc_attr( empty( $hashtag ) ? __( 'Visit my IG account', 'still-be-combine-social-photos' ) : __( 'Explore posts related to the hashtag', 'still-be-combine-social-photos' ) ); ?>"
             class="ig-user-name sb-csp-a-tag" target="_blank" rel="noopener" onclick="<?php echo esc_attr( $attr_onclick ); ?>">
            <?php
            	if( ! empty( $hashtag ) ) {
            		echo '<span class="hashtag-name">'. esc_html( $hashtag ). '</span>';
            	} elseif( ( empty( $account->name ) && empty( $account->me->name ) ) ||
            	      ( isset( $account->name ) && $account->name === $account->me->username ) ) {
            		echo '<span>@'. esc_html( $account->me->username ). '</span>';
            	} else {
            		echo '<span>'. esc_html( $account->name ?? $account->me->name ). '</span>';
            		echo '<span>'. esc_html( $account->me->username ). '</span>';
            	}
            ?>
          </a>
<?php
	if( empty( $hashtag ) ) {
?>
          <ul class="ig-user-attributes">
            <?php
            	if( isset( $account->me->media_count ) ) {
            		echo '<li class="ig-user-media"><span>'. esc_html( number_format( (int) $account->me->media_count ) ). '</span></li>';
            	}
            ?>
            <?php
            	if( $is_show_follows_count && isset( $account->me->follows_count ) ) {
            		echo '<li class="ig-user-follows"><span>'. esc_html( number_format( (int) $account->me->follows_count ) ). '</span></li>';
            	}
            ?>
            <?php
            	if( $is_show_followers_count && isset( $account->me->followers_count ) ) {
            		echo '<li class="ig-user-followers"><span>'. esc_html( number_format( (int) $account->me->followers_count ) ). '</span></li>';
            	}
            ?>
          </ul>
<?php
	}
?>
        </div>
      </header>
      <figure class="ig-feed-wrapper">
        <ul class="ig-feed-list<?php if( empty( $data ) ) { echo esc_attr( ' no-post' ); } ?>" data-no-post="<?php esc_attr_e( 'There are no posts available for display.', 'still-be-combine-social-photos' ); ?>">
<?php
	// IG Feed
	$i = 0;
	$has_modal = false;
	foreach( $data as $post ) {
		$li_class = [ 'ig-feed' ];
		// PC
		if( $i < $media_count_pc ) {
			$li_class[] = 'pc';
		}
		// Tablet
		if( $i < $media_count_tablet ) {
			$li_class[] = 'tablet';
		}
		// SP
		if( $i < $media_count_sp ) {
			$li_class[] = 'sp';
		}
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
		// Post Information Position
		$li_class[] = 'ig-post-info-pos-'. preg_replace( '/\W+/', '_', $info_position );
		// Caption
		$caption    = json_encode( $post->caption ?? '' );
		// Post Time
		$time = wp_date( esc_html__( 'Y-m-d', 'still-be-combine-social-photos' ), strtotime( $post->timestamp ) );
		// 
		++$i;
?>
          <li class="<?php echo esc_attr( implode( ' ', $li_class ) ); ?>">
            <div class="ig-post-wrapper">
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
                  		$post->media_url     = $post->children->data[0]->media_url      ?? null;
                  		$post->thumbnail_url = $post->children->data[0]->thumbnail_url  ?? null;
                  	}
                  	if( 'VIDEO' !== $post->media_type ) {
                  		echo '<img src="'. esc_url( $post->media_url ?? $go_instagram_url ). '" alt="'. esc_attr( wp_trim_words( $post->caption ?? '' ) ). '" loading="lazy">';
                  	} else {
                  		if( 'autoplay' === $attributes['displayingVideo'] ) {
                  			echo '<video src="'. esc_url( $post->media_url ). '" muted autoplay playsinline loop>';
                  			echo   '<p class="ig-post-video-unavailable">'. esc_html__( 'Your browser does not support video playback.', 'still-be-combine-social-photos' ). '</p>';
                  			echo '</video>';
                  		} else {
                  			echo '<img src="'. esc_url( $post->thumbnail_url ?? $go_instagram_url ). '" alt="'. esc_attr( wp_trim_words( $post->caption ?? '' ) ). '" loading="lazy">';
                  		}
                  	}
                  	echo "\n";
                  	if( 'inner' === $info_position ) {
                  ?>
                  <div class="ig-post-detail ig-post-detail-inner">
                    <div class="ig-post-caption-wrapper">
                      <p class="ig-post-caption">
                        <?php echo str_replace( "\n", '<br>', esc_html( wp_trim_words( $post->caption ?? '', 500 ) ) ); ?>
                      </p>
                    </div>
                    <?php
                    	echo "\n";
                    ?>
                    <ul class="ig-post-impression">
                      <?php
                      	if( $is_show_impressions && isset( $post->like_count ) ) {
                      		echo '<li class="ig-post-likes">'. esc_html( number_format( (int) $post->like_count ) ). '</li>';
                      	}
                      	echo "\n";
                      ?>
                      <?php
                      	if( $is_show_impressions && isset( $post->comments_count ) ) {
                      		echo '<li class="ig-post-comments">'. esc_html( number_format( (int) $post->comments_count ) ). '</li>';
                      	}
                      	echo "\n";
                      ?>
                    </ul>
                    <div class="is-post-attributes">
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
                      <time datetime="<?php echo esc_attr( $post->timestamp ); ?>" class="ig-post-time">
                        <span><?php echo esc_html( $time ); ?></span>
                      </time>
                    </div>
                  </div>
                  <?php
                  	}
                  	if( 'VIDEO' === $post->media_type ) {
                  		echo '<b class="ig-post-type video">Video</b>';
                  	}
                  	if( isset( $post->children->data[1] ) ) {
                  		echo '<b class="ig-post-type album">Album</b>';
                  	}
                  	echo "\n";
                  ?>
                </a>
              </div>
            </div>
            <?php
            	if( 'inner' !== $info_position ) {
            ?>
            <div class="ig-post-detail ig-post-detail-outer">
              <div class="ig-post-caption-wrapper">
                <p class="ig-post-caption">
                  <?php echo str_replace( "\n", '<br>', esc_html( wp_trim_words( $post->caption ?? '', 500 ) ) ); ?>
                </p>
              </div>
              <?php
              	echo "\n";
              ?>
              <ul class="ig-post-impression">
                <?php
                	if( $is_show_impressions && isset( $post->like_count ) ) {
                		echo '<li class="ig-post-likes">'. esc_html( number_format( (int) $post->like_count ) ). '</li>';
                	}
                	echo "\n";
                ?>
                <?php
                	if( $is_show_impressions && isset( $post->comments_count ) ) {
                		echo '<li class="ig-post-comments">'. esc_html( number_format( (int) $post->comments_count ) ). '</li>';
                	}
                	echo "\n";
                ?>
              </ul>
              <div class="is-post-attributes">
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
            ?>
          </li>
<?php
	}
	// END of IG Feed
?>
        </ul>
        <figcaption class="ig-from <?php echo esc_attr( $attributes['footerPosition'] ?? 'center' ); ?>">
          <span>Embed by</span>
          <a href="<?php echo esc_url( __( 'https://wordpress.org/plugins/still-be-image-quality-control/', 'still-be-combine-social-photos' ) ); ?>" target="_blank" rel="noopener" class="sb-csp-a-tag" onclick="<?php echo esc_attr( $attr_onclick ); ?>">Combine Social Photos</a>
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

$html = apply_filters( 'stillbe_csp/simple_grid__dynamic_html', $html, $attributes, $account, $data );

return $html;



