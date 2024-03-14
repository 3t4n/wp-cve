(function ($) {
    $(document).ready(function () {
        var screen_width = $(window).width();
        var screen_height = $(window).height();
        var height_get = screen_height + 'px';
        $('.fl-node-<?php echo $id; ?> .bxslider').bxSlider({
            auto: true,
            autoStart: <?php echo $settings->autoplay; ?>,
            autoHover: <?php echo $settings->hover_pause; ?>,
			<?php echo $settings->adaptive_height === 'no' ? 'adaptiveHeight: true,' : ''; ?>
            pause: <?php echo $settings->pause * 1000; ?>,
            mode: '<?php echo $settings->transition; ?>',
            speed: <?php echo $settings->speed * 1000;  ?>,
            infiniteLoop: <?php echo $settings->loop;  ?>,

			<?php if($settings->dot == 1){ ?>
            pager: <?php echo $settings->dot;  ?>,

			<?php }else if($settings->dot == 2){ ?>
            pagerCustom: '#bx-pager-<?php echo $module->node; ?>',
			<?php }else {?>
            pager: false,
			<?php } //die(); ?>
            nextText: '<i class="fa fa-angle-right"></i>',
            prevText: '<i class="fa fa-angle-left"></i>',
            controls: <?php echo $settings->arrows; ?>,

            maxSlides: 1,
            moveSlides: 1,
            slideMargin: 0,

        });
		<?php if($settings->dot == 2 ){  ?>
        $('.bx-thumbnail-pager').bxSlider({
            //pagerCustom: '#bx-pager-<?php echo $module->node; ?>'
            infiniteLoop: false,
            controls: true,
            nextText: '<i class="fa fa-angle-right"></i>',
            prevText: '<i class="fa fa-angle-left"></i>',
            minSlides: 4,
            maxSlides: 4,
            slideWidth: 75,
            slideMargin: 5
        });
        $(".bx-thumbnail-pager_section .bx-wrapper").css("position", "initial");
        $(".bx-thumbnail-pager_section .pager-toggle.fa").click(function () {
            $(".bx-thumbnail-pager_section .bx-wrapper").slideToggle("slow");
            var current = $(this).attr('class');
            console.log(current);
            if (current == 'pager-toggle fa fa-chevron-down') {
                $(this).removeClass("fa-chevron-down");
                $(this).addClass("fa-chevron-up");
                $(".njba-cta-box-main-inline").css("margin-bottom", "0px");
            } else {
                $(this).removeClass("fa-chevron-up");
                $(this).addClass("fa-chevron-down");
                $(".njba-cta-box-main-inline").css("margin-bottom", "100px");
            }
        });
		<?php } ?>
    });
})(jQuery);
	
