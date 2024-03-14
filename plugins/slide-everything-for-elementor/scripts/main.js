function miga_slider_everything_init() {
	var sliderConfig = document.querySelectorAll(".miga_slide_everything");
	for (var slideNr = 0; slideNr < sliderConfig.length; ++slideNr) {
		var slideObj = sliderConfig[slideNr];
		var slideId = slideObj.getAttribute("data-sliderId") || "";

		if (slideId != "" && slideId[0] != "#") {
			slideId = "#" + slideId;
		}


		if (slideId == "" || document.querySelector(slideId) == null) {
			return;
		}

		var spv = slideObj.getAttribute("data-spv") || 4;
		var spvt = slideObj.getAttribute("data-spvt") || 2;
		var spvp = slideObj.getAttribute("data-spvp") || 1;
		var loop = slideObj.getAttribute("data-loop") || false;
		var centerSlides = (slideObj.getAttribute("data-centerSlides") == 1);
		var pagination = (slideObj.getAttribute("data-pagination") == 1);
		var arrows = (slideObj.getAttribute("data-arrows") == 1);
		var autoplayEnabled = slideObj.getAttribute("data-autoplay") || false;
		var allowMouseWheel = slideObj.getAttribute("data-mousewheel") == 1;
		var autoplayDelay = slideObj.getAttribute("data-autoplay-delay") || 2000;
		var spaceBetween = slideObj.getAttribute("data-spacebetween") || 30;
		var dotPadding = slideObj.getAttribute("data-dot-padding") || 10;
		var latestSwiper = slideObj.getAttribute("data-latestSwiper") || false;
		var useNewsMethod = slideObj.getAttribute("data-newmethod") == 1;
		var autoplay = false;
		if (autoplayEnabled == 1 && autoplayDelay > 0) {
			autoplay = {
				delay: autoplayDelay,
				disableOnInteraction: false,
				pauseOnMouseEnter: true,
			}
		}

		var targetDiv = slideId + " > div";
		var targetId = slideId;
		if (document.querySelector(slideId) && document.querySelector(slideId).classList.contains("e-con-boxed")) {
			targetDiv = slideId + " > .e-con-inner > div";
			targetId = slideId + " > .e-con-inner";
		}
		var childs = document.querySelectorAll(targetDiv);
		console.log(childs.length)
		for (var i = 0; i < childs.length; ++i) {

			if (useNewsMethod) {
				var newDiv = document.createElement("div");
				newDiv.classList.add("swiper-slide");
				newDiv.append(childs[i])
				document.querySelector(targetId).append(newDiv)
			} else {
				childs[i].classList.remove("e-con-boxed")
				childs[i].classList.remove("e-flex")
				childs[i].classList.remove("e-con")
				childs[i].classList.add("swiper-slide");
			}
		}

		var paginationOptions = false;
		var newContent = "<div class='swiper-wrapper'>" + document.querySelector(targetId).innerHTML + "</div>";
		if (pagination) {
			newContent += '<div class="swiper-pagination" style="bottom:' + dotPadding + 'px"></div>';
			paginationOptions = {
				el: '.swiper-pagination',
				type: 'bullets',
			};
		}
		var arrowOptions = false;
		if (arrows) {


			newContent += '<div class="swiper-button-prev">';
			if (!latestSwiper) newContent += '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 27 44"><path d="M0 22L22 0l2.1 2.1L4.2 22l19.9 19.9L22 44 0 22z" fill="#000"/></svg>';
			newContent += '</div>';
			newContent += '<div class="swiper-button-next">';
			if (!latestSwiper) newContent += '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 27 44"><path d="M27 22L5 44l-2.1-2.1L22.8 22 2.9 2.1 5 0l22 22z" fill="#000"/></svg>';
			newContent += '</div>';
			arrowOptions = {
				nextEl: '.swiper-button-next',
				prevEl: '.swiper-button-prev',
			};
		}

		document.querySelector(slideId).innerHTML = newContent;

		var swiper = new Swiper(slideId, {
			slidesPerView: spvp,
			spaceBetween: parseInt(spaceBetween),
			loop: parseInt(loop),
			autoplay: autoplay,
			centeredSlides: centerSlides,
			navigation: arrowOptions,
			mousewheel: allowMouseWheel,
			pagination: paginationOptions,
			navigation: {
				nextEl: '.swiper-button-next',
				prevEl: '.swiper-button-prev',
			},
			breakpoints: {
				767: {
					slidesPerView: spvt,
					spaceBetween: parseInt(spaceBetween)
				},
				1024: {
					slidesPerView: spv,
					spaceBetween: parseInt(spaceBetween)
				}
			}
		});
		if (autoplayEnabled == 1 && autoplayDelay > 0) {
			jQuery(slideId).hover(function() {
				(this).swiper.autoplay.stop();
			}, function() {
				(this).swiper.autoplay.start();
			});
		}
	}
}

if (document.readyState !== 'loading') {
	migaSliderDomReady();
} else {
	document.addEventListener('DOMContentLoaded', migaSliderDomReady);
}

function migaSliderDomReady() {
	if (document.querySelectorAll(".miga_slide_everything") && document.querySelectorAll(".miga_slide_everything").length > 0) {
		miga_slider_everything_init();
	}
}
