import * as Util from "./Util";
import {Tooltip} from "./Components/Tooltip";
import {Prompter} from "./Components/Prompter";

export class Core {
	static lightboxList = [];
	static prompterList = [];

	static lightbox;
	static deep = location.hash;

	static setLightbox = (lb) => this.lightbox = lb;
	static getLightbox = () => this.lightbox;

	static setDeep = (d) => this.deep = d;
	static getDeep = () => this.deep;

	static addToLightboxList = (idx, lightbox) => this.lightboxList[idx] = lightbox;
	static getLightboxList = () => this.lightboxList;

	static showSpinner = () => {
		let loading = document.getElementsByClassName('photonic-loading');
		if (loading.length > 0) {
			loading = loading[0];
		}
		else {
			loading = document.createElement('div');
			loading.className = 'photonic-loading';
		}
		loading.style.display = 'block';
		document.body.appendChild(loading);
	};

	static hideLoading = () => {
		let loading = document.getElementsByClassName('photonic-loading');
		if (loading.length > 0) {
			loading = loading[0];
			loading.style.display = 'none';
		}
	};

	static initializePasswordPrompter = selector => {
		const selectorNoHash = selector.replace(/^#+/g, '');
		const prompter = new Prompter(selectorNoHash);
		prompter.attach();
		this.prompterList[selector] = prompter;
		prompter.show();
	};

	static moveHTML5External = () => {
		let videos = document.getElementById('photonic-html5-external-videos');
		if (!videos) {
			videos = document.createElement('div');
			videos.id = 'photonic-html5-external-videos';
			videos.style.display = 'none';
			document.body.appendChild(videos);
		}

		const current = document.querySelectorAll('.photonic-html5-external');
		if (current) {
			const cLen = current.length;
			for (let c = 0; c < cLen; c++) {
				current[c].classList.remove('photonic-html5-external');
				videos.appendChild(current[c]);
			}
		}
	};

	static blankSlideupTitle = () => {
		document.querySelectorAll('.title-display-slideup-stick, .photonic-slideshow.title-display-slideup-stick').forEach((item) => {
			Array.from(item.getElementsByTagName('a')).forEach(a => {
				a.setAttribute('title', '');
			});
		});
	};

	static showSlideupTitle = () => {
		let titles = document.documentElement.querySelectorAll('.title-display-slideup-stick a .photonic-title');
		const len = titles.length;
		for (let i = 0; i < len; i++) {
			titles[i].style.display = 'block';
		}
	};

	static waitForImages = async (selector) => {
		let images = this.getImagesFromSelector(selector);
		let imageUrlArray = [];
		let anchorArray = [];
		let setDimensions = false;

		if (selector instanceof Element && selector.getAttribute('data-photonic-platform') === 'instagram') {
			setDimensions = true;
		}

		images.forEach(img => {
			imageUrlArray.push(img.getAttribute('src'));
			anchorArray.push(img.parentElement);
		});

		const promiseArray = []; // create an array for promises
		const imageArray = []; // array for the images

		for (const [idx, imageUrl] of imageUrlArray.entries()) {
			promiseArray.push(new Promise(resolve => {
				const img = new Image();
				img.onload = () => {
					if (setDimensions) {
						anchorArray[idx].setAttribute('data-pswp-width', img.naturalWidth);
						anchorArray[idx].setAttribute('data-pswp-height', img.naturalHeight);
					}
					resolve();
				};

				img.src = imageUrl;
				imageArray.push(img);
			}));
		}

		await Promise.all(promiseArray); // wait for all the images to be loaded
		return imageArray;
	};

	static loadSingleImage = async(image) => {
		const promiseArray = [];
		let retImage = null;
		promiseArray.push(new Promise(resolve => {
			const img = new Image();
			img.onload = () => {
				resolve();
			};

			if (image.getAttribute('data-src') !== null) {
				img.src = image.getAttribute('data-src');
			}
			else {
				img.src = image.getAttribute('src'); // Leave the 'src' in just as a backup. E.g. Gallery header images
			}

			retImage = img;
		}));

		await Promise.all(promiseArray);
		return retImage;
	};

	static watchForImages = (selector) => {
		let images = this.getImagesFromSelector(selector);

		const intersectionObserver = new IntersectionObserver((items, observer) => {
			items.forEach((item) => {
				if (item.isIntersecting || item.intersectionRatio > 0) {
					const image = item.target;
					image.closest('a').classList.add('photonic-image-loading');

					this.loadSingleImage(image).then(() => {
						if (image.getAttribute('data-src') !== null && image.getAttribute('data-src') !== '') {
							image.src = image.getAttribute('data-src');
							image.setAttribute('data-src', '');
							image.setAttribute('data-loaded', 'true');
						}

						image.closest('a').classList.remove('photonic-image-loading');
						Util.fadeIn(image);
					});
					observer.unobserve(image);
				}
			});
		});

		images.forEach((image) => {
			intersectionObserver.observe(image);
		});
	};

	static getImagesFromSelector = selector => {
		let images = [];
		if (typeof selector === 'string') {
			document.querySelectorAll(selector).forEach(selection => {
				images = Array.from(selection.getElementsByTagName('img'));
			});
		}
		else if (selector instanceof Element) {
			images = Array.from(selector.getElementsByTagName('img'));
		}
		else if (selector instanceof NodeList) {
			selector.forEach((selection) => {
				images.push(selection.querySelector('img'));
			});
		}
		return images;
	};

	static standardizeTitleWidths = () => {
		const self = this;

		const setWidths = grid => {
			grid.querySelectorAll('.photonic-thumb').forEach(item => {
				let img = item.getElementsByTagName('img');
				if (img != null) {
					img = img[0];
					let title = item.querySelector('.photonic-title-info');
					if (title) {
						title.style.width = img.width + 'px';
					}
				}
			});
		};

		document.querySelectorAll('.photonic-standard-layout.title-display-below, .photonic-standard-layout.title-display-hover-slideup-show, .photonic-standard-layout.title-display-slideup-stick').forEach(grid => {
			if (grid.classList.contains('sizes-present')) {
				self.watchForImages(grid);
				setWidths(grid);
			}
			else {
				self.waitForImages(grid).then(() => {
					setWidths(grid);
				});
			}
		});
	};

	static sanitizeTitles = () => {
		const thumbs = document.querySelectorAll('.photonic-stream a, a.photonic-level-2-thumb');
		thumbs.forEach((thumb) => {
			if (!thumb.parentNode.classList.contains('photonic-header-title')) {
				const title = thumb.getAttribute('title');
				thumb.setAttribute('title', Util.getText(title));
			}
		});
	};

	static initializeTooltips = () => {
		if (document.querySelector('.title-display-tooltip a, .photonic-slideshow.title-display-tooltip img') != null) {
			Tooltip('[data-photonic-tooltip]', '.photonic-tooltip-container');
		}
	};

	static showRegularGrids = () => {
		document.querySelectorAll('.photonic-standard-layout').forEach(grid => {
			if (grid.classList.contains('sizes-present')) {
				this.watchForImages(grid);
			}
			else {
				this.waitForImages(grid).then(() => {
					grid.querySelectorAll('.photonic-level-1, .photonic-level-2').forEach(item => {
						item.style.display = 'inline-block';
					});
				});
			}
		});
	};

	static executeCommon = () => {
		Core.moveHTML5External();
		Core.blankSlideupTitle();
		Core.standardizeTitleWidths();
		Core.sanitizeTitles();
		Core.initializeTooltips();
		Core.showRegularGrids();
	};
}
