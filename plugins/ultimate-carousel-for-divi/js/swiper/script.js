var wptSwiper = {
	init: function(){
		var self = this;
		jQuery('.swiper-container').each(function(){
			var container = jQuery(this);

			var instance = container.data('swiper_instance')
			if(typeof instance !== 'undefined' && typeof instance.destroy !== 'undefined'){
				instance.destroy(true, true);
			}

			var order_class = container.data('order_class')

			var container_class = order_class + ' .swiper-container';
			if (order_class) {
				var autoplay = container.data('autoplay') === 'on' ? true : false;
				var slider_loop = container.data('slider_loop') === 'on' ? true : false;
				var pause_on_hover = container.data('pause_on_hover') === 'on' ? true : false;
				var params = self.getParams(container);

				var swiper_instance = new Swiper(container_class, params)

				container.data('swiper_instance', swiper_instance)

				if(pause_on_hover && autoplay) {
					jQuery(container_class).on('mouseenter', function(e, instance){
						var instance = jQuery(this).data('swiper_instance')
						if(typeof instance.autoplay.stop === 'function') {
							instance.autoplay.stop();
						}
					});

					jQuery(container_class).on('mouseleave', function(e, instance){
						var instance = jQuery(this).data('swiper_instance')
						if(typeof instance.autoplay.start === 'function') {
							instance.autoplay.start();
						}
					});
				} //pause + hover

				if(!slider_loop){
					jQuery(container_class).on('reachEnd', function(e, instance){
						var instance = jQuery(this).data('swiper_instance')
						instance.autoplay = false;
					});
				}
			}
		});
	},
	getParams : function(el){
		var self = this;
		var order_class = el.data('order_class')
		var effect = el.data('effect')

		var cube = false
		var coverflow = false
		var coverflow_rotate        = parseInt(el.data('coverflow_rotate'));
		var coverflow_depth         = parseInt(el.data('coverflow_depth'));
		var enable_coverflow_slide_shadow = el.data('enable_coverflow_slide_shadow') === 'on' ? true : false;
		var transition_duration = parseInt(el.data('transition_duration'))
		var space_between_desktop = parseInt(el.data('space_between_desktop'))
		var space_between_tablet = parseInt(el.data('space_between_tablet'))
		var space_between_phone = parseInt(el.data('space_between_phone'))

		var slides_per_view_desktop = parseInt(el.data('slides_per_view_desktop'))
		var slides_per_view_tablet = parseInt(el.data('slides_per_view_tablet'))
		var slides_per_view_phone = parseInt(el.data('slides_per_view_phone'))

		var autoplay = el.data('autoplay') === 'on' ? true : false;
		var pause_on_hover = el.data('pause_on_hover') === 'on' ? true : false;
		var autoplay_speed = parseInt(el.data('autoplay_speed'));
		var autoplaySlides = false;
		var slider_loop = el.data('slider_loop') === 'on' ? true : false;
		var show_control_dot = el.data('show_control_dot') === 'on' ? true : false;
		var show_arrow = el.data('show_arrow') === 'on' ? true : false;

		var initial_slide = 0;
		if(el.data('initial_slide')) {
			initial_slide = el.data('initial_slide')
		}

		var centered_slides = false;
		if(el.data('centered_slides') && (el.data('centered_slides') === 'on')) {
			centered_slides = true
		}

		var dots = false;
		var arrows = false;


		if(show_arrow) {
			arrows = {
				nextEl : order_class + ' .swiper-button-next',
				prevEl : order_class + ' .swiper-button-prev',
			}
		}

		if(show_control_dot){
			dots = {
				el : order_class + ' .swiper-pagination',
				clickable: true
			}
		}

		if(!transition_duration){
			transition_duration = 1000;
		}

		if("coverflow" === effect){
			coverflow = {
				rotate : coverflow_rotate,
				stretch :0,
				depth: coverflow_depth,
				modifier: 1,
				slideShadows: enable_coverflow_slide_shadow
			}
		}

		if(autoplay){
			if(pause_on_hover){
				autoplaySlides = {
					delay: autoplay_speed,
					disableOnInteraction: true
				}
			} else {
				autoplaySlides = {
					delay: autoplay_speed,
					disableOnInteraction: false
				}
			}
		}


		var params =  {
			centeredSlides:  centered_slides,
			initialSlide : initial_slide,
			slidesPerView: slides_per_view_desktop,
			autoplay: autoplaySlides,
			spaceBetween : space_between_desktop,
			effect: effect,
			cubeEffect: cube,
			coverflowEffect : coverflow,
			speed: transition_duration,
			loop: slider_loop,
			pagination: dots,
			navigation: arrows,
			grabCursor: true,
			breakpoints : {
				1080 : {
					slidesPerView: slides_per_view_desktop,
					spaceBetween: space_between_desktop
				},
				767 : {
					slidesPerView: slides_per_view_tablet,
					spaceBetween: space_between_tablet
				},
				0 : {
					slidesPerView: slides_per_view_phone,
					spaceBetween: space_between_phone
				},
			}
		}

		return params;
	}
}


jQuery(document).ready(function() {
	wptSwiper.init();
});