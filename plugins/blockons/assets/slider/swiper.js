document.addEventListener("DOMContentLoaded", () => {
	const blockonSliders = document.querySelectorAll(".blockons-slider.slider");

	if (blockonSliders) {
		blockonSliders.forEach((slider) => {
			const sliderSettings = JSON.parse(slider.getAttribute("data-settings"));
			const sliderElement = slider.firstElementChild.classList[0];
			// console.log(sliderElement, sliderSettings);

			if (sliderElement) {
				// const blockonSwiper = new Swiper(`.${sliderElement}`, sliderSettings);
				new Swiper(`.${sliderElement}`, sliderSettings);
			}
		});
	}
});
