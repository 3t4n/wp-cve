/**
 * Photonic Masonry Layout
 * The Masonry layout is primarily controlled using CSS columns. The JS component is to facilitate responsive behaviour and breakpoints.
 *
 * License: GPL v3.0
 */
import {Core} from "../Core";
import * as Util from "../Util";

export const Masonry = (resized, jsLoaded, selector) => {
	if (console !== undefined && Photonic_JS.debug_on !== '0' && Photonic_JS.debug_on !== '') console.time('Masonry');

	let selection = document.querySelectorAll(selector);
	if (selector == null || selection.length === 0) {
		selection = document.querySelectorAll('.photonic-masonry-layout');
	}

	if (!resized && selection.length > 0) {
		Core.showSpinner();
	}

	let minWidth = (isNaN(Photonic_JS.masonry_min_width) || parseInt(Photonic_JS.masonry_min_width) <= 0) ? 200 : Photonic_JS.masonry_min_width;
	minWidth = parseInt(minWidth);

	selection.forEach((grid) => {
		let columns = grid.getAttribute('data-photonic-gallery-columns');
		columns = (isNaN(parseInt(columns)) || parseInt(columns) <= 0) ? 3 : parseInt(columns);

		const buildLayout = (grid) => {
			const viewportWidth = Math.floor(grid.getBoundingClientRect().width),
				idealColumns = (viewportWidth / columns) > minWidth ? columns : Math.floor(viewportWidth / minWidth);

			if (idealColumns !== undefined && idealColumns !== null) {
				grid.style.columnCount = idealColumns.toString();
			}

			Array.from(grid.getElementsByTagName('img')).forEach(img => {
				Util.fadeIn(img);
			});

			Core.showSlideupTitle();
			if (!resized && !jsLoaded) {
				Core.hideLoading();
			}
		};

		if (grid.classList.contains('sizes-present')) {
			Core.watchForImages(grid);
			buildLayout(grid);
		}
		else {
			Core.waitForImages(grid).then(() => {
				buildLayout(grid);
			});
		}
	});
	if (console !== undefined && Photonic_JS.debug_on !== '0' && Photonic_JS.debug_on !== '') console.timeEnd('Masonry');
};
