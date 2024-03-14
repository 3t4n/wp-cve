import {Core} from "../Core";

const adaptiveHeight = (slideshow, slide, splide) => {
	let options = splide.options;
	let currentlyActive = splide.index;
	if (slide.isWithin(parseInt(currentlyActive), parseInt(options.perPage) - 1)) {
		const allSlides = splide.Components.Elements.slides;
		let lastVisible = parseInt(currentlyActive) + parseInt(options.perPage);
		let visibleSlides = allSlides.slice(currentlyActive, lastVisible);
		let maxHeight = 0;
		// Need requestAnimationFrame, otherwise offsetHeight returns 0 for first photo
		requestAnimationFrame(() => {
			Array.prototype.forEach.call(visibleSlides, (visible) => {
				let visibleImage = visible.querySelector('img');
				let offsetHeight = visibleImage.offsetHeight;
				if (visibleImage && offsetHeight > maxHeight) {
					maxHeight = offsetHeight;
				}
			});
			slide.slide.style.height = `${maxHeight}px`;

			const splideTrack = slideshow.querySelector('.splide__track');
			const splideTrackHeight = splideTrack ? splideTrack.offsetHeight : 0;

			if (maxHeight !== splideTrackHeight) {
				const splideList = slideshow.querySelector('.splide__list');
				splideList.style.height = `${maxHeight}px`;
				splideTrack.style.height = `${maxHeight}px`;
			}
		});
	}
};

const fixedHeight = (slideshow, splideObj) => {
	let maxHeight = 0,
		maxAspect = 0,
		containerWidth = slideshow.offsetWidth,
		children = slideshow.querySelectorAll('.splide__slide img');

	Array.prototype.forEach.call(children, (img) => {
		if (img.naturalHeight !== 0) {
			const childAspect = img.naturalWidth / img.naturalHeight;
			if (childAspect >= maxAspect) {
				maxAspect = childAspect;
				let heightFactor = img.naturalWidth > containerWidth ? (containerWidth / img.naturalWidth) : 1;
				const cols = parseInt(splideObj.options.perPage, 10);
				if (!isNaN(cols) && cols !== 0) {
					heightFactor = heightFactor / cols;
				}
				maxHeight = img.naturalHeight * heightFactor;
			}
		}
	});

	Array.prototype.forEach.call(children, (img) => {
		img.style.height = maxHeight + 'px';
	});

	Array.prototype.forEach.call(slideshow.querySelectorAll('.splide__slide, .splide__list'), (slideOrList) => {
		slideOrList.style.height = maxHeight + 'px';
	});
	slideshow.style.height = maxHeight + 'px';
};

export const Slider = (slideshow) => {
	if (slideshow) {
		const content = slideshow.querySelector('.photonic-slideshow-content');
		if (content) {
			Core.waitForImages(slideshow).then(() => {
				const idStr = '#' + slideshow.getAttribute('id');

				let splideThumbs = document.querySelector(idStr + '-thumbs');
				if (splideThumbs != null) {
					splideThumbs = new Splide(idStr + '-thumbs');
					splideThumbs.mount();
				}

				const splide = new Splide(idStr);
				splide.on('mounted resize', function (slide) {
					if (slideshow.classList.contains('photonic-slideshow-side-white') || slideshow.classList.contains('photonic-slideshow-start-next')) {
						fixedHeight(slideshow, splide);
					}
				});

				splide.on('visible', function (slide) {
					if (slideshow.classList.contains('photonic-slideshow-adapt-height')) {
						adaptiveHeight(slideshow, slide, splide);
					}
				});

				if (splideThumbs == null) {
					splide.mount();
				}
				else {
					splide.sync(splideThumbs).mount();
				}

				slideshow.querySelectorAll('img').forEach((img) => {
					img.style.display = 'inline';
				});
			});
		}
	}
};

export const initializeSliders = () => {
	const primarySliders = document.querySelectorAll('.photonic-slideshow');
	if (typeof Splide != "undefined") {
		primarySliders.forEach((slideshow) => Slider(slideshow));
	}
	else if (console !== undefined && primarySliders.length > 0) {
		console.error('Splide not found! Please ensure that the Splide script is available and loaded before Photonic.');
	}
};
