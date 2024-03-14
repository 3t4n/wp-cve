document.addEventListener('DOMContentLoaded', function() {
	let widgets = document.querySelectorAll('.elementor-widget-stm_lms_pro_testimonials');
	widgets.forEach(function(widget) {
		let widgetData = JSON.parse(widget.getAttribute('data-settings')),
			bullets = widget.querySelector('.ms-lms-elementor-testimonials-swiper-pagination'),
			sliderWrapper = widget.querySelector('.elementor-testimonials-carousel'),
			sliderContainer = widget.querySelector('.stm-testimonials-carousel-wrapper');
		if ( sliderContainer.length !== 0 ) {
			const mySwiper = new Swiper( sliderContainer, {
				slidesPerView: 1,
				allowTouchMove: true,
				loop: widgetData && widgetData.loop ? true : false,
				autoplay: widgetData && widgetData.autoplay ? { delay: 2000 } : false,
				pagination: {
					el: bullets,
					clickable: true,
					renderBullet: function(index, className) {
						let userThumbnail = '',
							testimonialItem = sliderWrapper.children[index];
						if (testimonialItem !== null && typeof testimonialItem === 'object') {
							userThumbnail = testimonialItem.getAttribute('data-thumbnail');
						}
						let span = document.createElement('span');
						span.classList.add(className);
						span.style.backgroundImage = 'url(' + userThumbnail + ')';
						return span.outerHTML;
					},
				},
			});
		}
	});
});