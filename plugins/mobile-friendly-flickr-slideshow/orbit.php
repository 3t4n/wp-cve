<?php
if ( ! defined( 'WPINC' ) ) {
    die();
}?><!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet prefetch" href="<?php echo plugin_dir_url( __FILE__ ) . 'css/foundation.css'; ?>">
<link rel="stylesheet" href="<?php echo plugin_dir_url( __FILE__ ) . 'css/foundation-icons.css'; ?>">
<style>
@media only screen and (max-width: 40em) {
    .orbit-next, .orbit-prev,  .orbit-timer {
        display: block !important;
    }
}
.orbit-container {
  width: 100%;
  height: 100%;
  background-color: #000;
}
.orbit-slides-container {
  width: 100%;
  height: 100% !important;
}
.orbit-slides-container li {
  height: 100%;
  background-size: cover;
  display: inline-block;
  vertical-align: middle;
}
.orbit-slides-container li img {
  max-width: 100%;
  max-height: 100%;
  bottom: 0;
  left: 0;
  margin: auto;
  overflow: auto;
  position: fixed;
  right: 0;
  top: 0;
  vertical-align: middle;
}
.bottom-bar {
    position: fixed;
    bottom: 0;
    z-index: 100;
    width: 100%;
    _position:absolute;
    _top:expression(eval(document.documentElement.scrollTop+(document.documentElement.clientHeight-this.offsetHeight)));
    height: 2em;
    background-color: rgba(0,0,0,0.5);
    text-align: center;
}
.bottom-bar .left {
    margin-left: 1em;
}
.bottom-bar .right {
    margin-right: 1em;
}
.bottom-bar, .bottom-bar .left, .bottom-bar .right, .bottom-bar .center, .bottom-bar .left a, .bottom-bar .right a, .bottom-bar .center a, a#fullscreen_link, a#back_link {
    color: #fff;
}
#fullscreen_link, #back_link {
    display: block;
    position: absolute;
    top: 0.5em;
    left: 1em;
    z-index: 100;
    background-color: rgba(0,0,0,0.5);
    padding: 0.5em;
}
</style>
<?php if (get_option('fshow_performance_mode') != "1"): ?>
    <?php wp_head(); ?>
<?php else: ?>
    <script src="<?php echo plugin_dir_url( __FILE__ ) . 'js/jquery.min.js'; ?>"></script>
<?php endif; ?>
<script src="<?php echo plugin_dir_url( __FILE__ ) . 'js/modernizr.min.js'; ?>"></script>
<script src="<?php echo plugin_dir_url( __FILE__ ) . 'js/foundation.min.js'; ?>"></script>
<script src="<?php echo plugin_dir_url( __FILE__ ) . 'js/foundation.orbit.min.js'; ?>"></script>
<script src="<?php echo plugin_dir_url( __FILE__ ) . 'js/screenfull.min.js' ?>"></script>
</head>
<body>
<a id="back_link" style="display: none;">
    <i class="fi-arrow-left"></i> <?php _e('Back','flickr_slideshow'); ?>
</a>
<a id="fullscreen_link" style="display: none;">
    <i class="fi-arrows-out"></i> <?php _e('Fullscreen','flickr_slideshow'); ?>
</a>
<div class="orbit-container" data-cache="<?php echo serialize($this->flickr->get_cache_stats()); ?>">
    <ul data-orbit>
      <?php $zindex = 0; ?>
      <?php foreach($this->get_photos() as $photo): ?>
        <?php if ($zindex === 0): ?>
            <li style="z-index: <?php echo --$zindex; ?>">
                <img src="<?php echo $photo['url']; ?>" data-page="<?php echo $photo['page_url']; ?>">
            </li>
        <?php else: ?>
            <li style="z-index: <?php echo --$zindex; ?>">
                <img src="<?php echo plugin_dir_url( __FILE__ ) . '/images/spinner.gif'; ?>" data-src="<?php echo $photo['url']; ?>" data-page="<?php echo $photo['page_url']; ?>">
            </li>
        <?php endif; ?>
      <?php endforeach; ?>
    </ul>
</div>
<div class="bottom-bar" style="display: none;">
    <div class="left">
        <a id="gallery_link" target="_blank">
            <?php _e('View Gallery','flickr_slideshow'); ?>
        </a>
    </div>
    <span class="center">
    </span>
    <div class="right">
        <a id="photo_link" target="_blank">
            <?php _e('View Photo','flickr_slideshow'); ?>
        </a>
    </div>
</div>
<script>
jQuery( document ).ready( function() {
    function fshow_lazyload(obj_li) {
        if (jQuery(obj_li).find('img').first().attr('data-src') && jQuery(obj_li).find('img').first().attr('src') != jQuery(obj_li).find('img').first().attr('data-src')) {
            var url = jQuery(obj_li).find('img').first().attr('data-src');
            var img = jQuery(obj_li).find('img').first();
            img.attr('src', url );
        }
        if (jQuery(obj_li).next()) {
            window.setTimeout( function() {
                fshow_lazyload( jQuery(obj_li).next() );
            }, 500);
        }
    }
    function fshow_load_navigation( orbit ) {
        jQuery('#gallery_link').attr('href','<?php echo $this->get_gallery_url(); ?>');
        fshow_update_image_link( orbit );
        console.log(screenfull);
        if (screenfull && screenfull.isEnabled) {
            jQuery('#fullscreen_link').on('click', function(e) {
                screenfull.request();
            });
        } else if(window.parent) {
            jQuery('#fullscreen_link').on('click', function(e) {
                window.parent.location = window.location;
            });
        }
        if (Modernizr.touch) {
            if (window.parent.location != window.location) {
                jQuery('#fullscreen_link').fadeIn();
            } else {
                jQuery('#back_link').on('click',function(e) {
                    window.history.go(-1);
                }).fadeIn();
            }
            jQuery('.bottom-bar').fadeIn();
        }
    }
    function fshow_update_image_link( orbit ) {
        url = jQuery( orbit ).find('li.active img').attr('data-page');
        jQuery('#photo_link').attr('href',url);
    }
    function fshow_hide_non_active( orbit ) {
        jQuery( orbit ).find('li').not('.active').css('opacity',0);
    }
    var orbit = jQuery('.orbit-container').foundation('orbit', {
            animation: 'fade',
            timer_speed: 4000,
            animation_speed: 200,
            stack_on_small: false,
            navigation_arrows: true,
            slide_number: false,
            pause_on_hover: false,
            resume_on_mouseout: false,
            bullets: false,
            timer: true,
            variable_height: false
    });
    orbit.on("after-slide-change.fndtn.orbit", function(event) {
        fshow_hide_non_active(this);
        fshow_update_image_link(this);
    });
    fshow_lazyload( orbit.find('li.active').first() );
    fshow_load_navigation( orbit );
    jQuery(document).on('mouseenter',function(e) {
        fsl = jQuery('#fullscreen_link');
        if (!fsl.is(':visible') && ((screenfull && screenfull.enabled) || window.parent.location != window.location)) {
            fsl.fadeIn();
        }
        bb = jQuery('.bottom-bar');
        if (!bb.is(':visible')) {
            bb.fadeIn();
        }
    });
    jQuery(document).on('mouseleave',function(e) {
        fsl = jQuery('#fullscreen_link');
        if (fsl.is(':visible')) {
            fsl.fadeOut();
        }
        bb = jQuery('.bottom-bar');
        if (bb.is(':visible')) {
            bb.fadeOut();
        }
    });
});
</script>
<?php if(get_option('fshow_performance_mode') != "1"): ?>
    <?php wp_footer(); ?>
<?php endif; ?>
</body>
</html>
