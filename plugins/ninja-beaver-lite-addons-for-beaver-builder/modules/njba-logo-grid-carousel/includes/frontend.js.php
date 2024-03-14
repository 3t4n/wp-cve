<?php if($settings->logos_layout_view === 'carousel'){ ?>
(function ($) {
	<?php if ( count( $settings->logos ) > 1 ) : ?>
    var window_width = $(window).width();
    var section_width = $(".fl-node-<?php echo $id; ?> .njba-logo-carousel-main").width();

    var sliderOptions = {
        auto: true,
        autoStart: <?php echo $settings->autoplay; ?>,
        autoHover: <?php echo $settings->hover_pause; ?>,
		<?php echo $settings->adaptive_height === 'no' ? 'adaptiveHeight: true,' : ''; ?>
        pause: <?php echo $settings->pause * 1000; ?>,
        mode: '<?php echo $settings->transition; ?>',
        speed: <?php echo $settings->speed * 1000;  ?>,
        infiniteLoop: <?php echo $settings->loop;  ?>,
        pager: <?php echo $settings->dots; ?>,

        nextText: '<i class="fa fa-angle-right"></i>',
        prevText: '<i class="fa fa-angle-left"></i>',
        controls: <?php echo $settings->arrows; ?>,
        onSliderLoad: function () {
            $('.fl-node-<?php echo $id; ?> .njba-logo-carousel-main').addClass('njba-logo-carousel-loaded');
        }
    };
    if (window_width > 990) {

        var max_slide = <?php if ( $settings->max_slides['desktop'] !== '' ) {
			echo $settings->max_slides['desktop'];
		} else {
			echo '1';
		}?>;
        var slide_margin = <?php if ( $settings->slide_margin['desktop'] !== '' ) {
			echo $settings->slide_margin['desktop'];
		} else {
			echo '0';
		}?>;
        var slide_width_cal = section_width / max_slide;
        var slide_width = slide_width_cal - slide_margin;

        var carouselOptions = {
            minSlides: 1,
            maxSlides: max_slide,
            moveSlides: 1,
            slideWidth: slide_width,
            slideMargin: slide_margin
        };


    } else if (window_width > 767 && window_width <= 990) {

        var max_slide = <?php if ( $settings->max_slides['medium'] !== '' ) {
			echo $settings->max_slides['medium'];
		} else {
			echo '1';
		}?>;
        var slide_margin = <?php if ( $settings->slide_margin['medium'] !== '' ) {
			echo $settings->slide_margin['medium'];
		} else {
			echo '0';
		}?>;
        var slide_width_cal = section_width / max_slide;
        var slide_width = slide_width_cal - slide_margin;

        var carouselOptions = {
            minSlides: 1,
            maxSlides: max_slide,
            moveSlides: 1,
            slideWidth: slide_width,
            slideMargin: slide_margin
        };

    } else if (window_width <= 767) {
        var max_slide = <?php if ( $settings->max_slides['small'] !== '' ) {
			echo $settings->max_slides['small'];
		} else {
			echo '1';
		}?>;
        var slide_margin = <?php if ( $settings->slide_margin['small'] !== '' ) {
			echo $settings->slide_margin['small'];
		} else {
			echo '0';
		}?>;
        var slide_width_cal = section_width / max_slide;
        var slide_width = slide_width_cal - slide_margin;

        var carouselOptions = {
            minSlides: 1,
            maxSlides: max_slide,
            moveSlides: 1,
            slideWidth: slide_width,
            slideMargin: slide_margin
        };

    }

    $('.fl-node-<?php echo $id; ?> .njba-logo-carousel-wrapper').bxSlider($.extend({}, sliderOptions, carouselOptions));
	<?php endif; ?>

})(jQuery);
<?php } ?>
