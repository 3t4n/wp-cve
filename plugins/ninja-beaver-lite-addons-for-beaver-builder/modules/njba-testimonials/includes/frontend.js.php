(function ($) {
	<?php if ( count( $settings->testimonials ) > 1 ) : ?>

    $('.fl-node-<?php echo $id; ?> .njba-arrow-wrapper .njba-slider-next').empty();
    $('.fl-node-<?php echo $id; ?> .njba-arrow-wrapper .njba-slider-prev').empty();
    var window_width = $(window).width();

    var section_width = $('.fl-node-<?php echo $id; ?> .njba-testimonial.layout-<?php echo $settings->testimonial_layout;?>').width();
    var section_height = $('.fl-node-<?php echo $id; ?> .njba-testimonial.layout-<?php echo $settings->testimonial_layout;?>').height();
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
        nextSelector: '.fl-node-<?php echo $id; ?> .njba-arrow-wrapper .njba-slider-next',
        prevSelector: '.fl-node-<?php echo $id; ?> .njba-arrow-wrapper .njba-slider-prev',
        nextText: '<i class="fa fa-angle-right"></i>',
        prevText: '<i class="fa fa-angle-left"></i>',
        controls: <?php echo $settings->arrows; ?>,
        onSliderLoad: function () {
            $('.fl-node-<?php echo $id; ?> .njba-testimonials').addClass('njba-testimonials-loaded');
        }
    };

    if (window_width > 990) {


        var max_slide = <?php if ( $settings->max_slides['desktop'] !== '' ) {
			echo $settings->max_slides['desktop'];
		} else {
			echo '1';
		} //?>;
        var slide_margin = <?php if ( $settings->slide_margin['desktop'] !== '' ) {
			echo $settings->slide_margin['desktop'];
		} else {
			echo '0';
		}//die();?>;
        var slide_width_cal = section_width / max_slide;
        var slide_width = slide_width_cal - slide_margin;

        var carouselOptions = {
            minSlides: max_slide,
            maxSlides: max_slide,
            moveSlides: 1,
            slideWidth: slide_width,
            slideMargin: slide_margin,
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
            minSlides: max_slide,
            maxSlides: max_slide,
            moveSlides: 1,
            slideWidth: slide_width,
            slideMargin: slide_margin,
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
            minSlides: max_slide,
            maxSlides: max_slide,
            moveSlides: 1,
            slideWidth: slide_width,
            slideMargin: slide_margin,
        };
    }
    $('.fl-node-<?php echo $id; ?> .njba-testimonial-wrapper').bxSlider($.extend({}, sliderOptions, carouselOptions));
	<?php endif; ?>
})(jQuery);
