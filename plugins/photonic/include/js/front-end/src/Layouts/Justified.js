/**
 * Photonic Justified Grid Layout (JS-based)
 * Photonic can render a justified grid using CSS or JS. The CSS layout is the default. The JS one is used when the sizes of the images are not available upfront.
 * The CSS layout renders quicker, but the JS layouts are a lot prettier.
 *
 * License: GPL v3.0
 */

import {Core} from "../Core.js";
import * as Util from "../Util.js";

const linearMin = arr => {
	let computed, result, x, _i, _len;
	for (_i = 0, _len = arr.length; _i < _len; _i++) {
		x = arr[_i];
		computed = x[0];
		if (!result || computed < result.computed) {
			result = {
				value: x,
				computed: computed
			};
		}
	}
	return result.value;
};

const linearPartition = (seq, k) => {
	let ans, i, j, m, n, solution, table, x, y, _i, _j, _k, _l;
	n = seq.length;
	if (k <= 0) {
		return [];
	}
	if (k > n) {
		return seq.map(x => [x]);
	}
	table = (() => {
		let _i, _results;
		_results = [];
		for (y = _i = 0; 0 <= n ? _i < n : _i > n; y = 0 <= n ? ++_i : --_i) {
			_results.push((function() {
				let _j, _results1;
				_results1 = [];
				for (x = _j = 0; 0 <= k ? _j < k : _j > k; x = 0 <= k ? ++_j : --_j) {
					_results1.push(0);
				}
				return _results1;
			})());
		}
		return _results;
	})();
	solution = (() => {
		let _i, _ref, _results;
		_results = [];
		for (y = _i = 0, _ref = n - 1; 0 <= _ref ? _i < _ref : _i > _ref; y = 0 <= _ref ? ++_i : --_i) {
			_results.push((function() {
				let _j, _ref1, _results1;
				_results1 = [];
				for (x = _j = 0, _ref1 = k - 1; 0 <= _ref1 ? _j < _ref1 : _j > _ref1; x = 0 <= _ref1 ? ++_j : --_j) {
					_results1.push(0);
				}
				return _results1;
			})());
		}
		return _results;
	})();

	for (i = _i = 0; 0 <= n ? _i < n : _i > n; i = 0 <= n ? ++_i : --_i) {
		table[i][0] = seq[i] + (i ? table[i - 1][0] : 0);
	}
	for (j = _j = 0; 0 <= k ? _j < k : _j > k; j = 0 <= k ? ++_j : --_j) {
		table[0][j] = seq[0];
	}
	for (i = _k = 1; 1 <= n ? _k < n : _k > n; i = 1 <= n ? ++_k : --_k) {
		for (j = _l = 1; 1 <= k ? _l < k : _l > k; j = 1 <= k ? ++_l : --_l) {
			m = linearMin((() => {
				let _m, _results;
				_results = [];
				for (x = _m = 0; 0 <= i ? _m < i : _m > i; x = 0 <= i ? ++_m : --_m) {
					_results.push([Math.max(table[x][j - 1], table[i][0] - table[x][0]), x]);
				}
				return _results;
			})());
			table[i][j] = m[0];
			solution[i - 1][j - 1] = m[1];
		}
	}
	n = n - 1;
	k = k - 2;
	ans = [];
	while (k >= 0) {
		ans = [
			(() => {
				let _m, _ref, _ref1, _results;
				_results = [];
				for (i = _m = _ref = solution[n - 1][k] + 1, _ref1 = n + 1; _ref <= _ref1 ? _m < _ref1 : _m > _ref1; i = _ref <= _ref1 ? ++_m : --_m) {
					_results.push(seq[i]);
				}
				return _results;
			})()
		].concat(ans);
		n = solution[n - 1][k];
		k = k - 1;
	}
	return [
		(() => {
			let _m, _ref, _results;
			_results = [];
			for (i = _m = 0, _ref = n + 1; 0 <= _ref ? _m < _ref : _m > _ref; i = 0 <= _ref ? ++_m : --_m) {
				_results.push(seq[i]);
			}
			return _results;
		})()
	].concat(ans);
};

function part(seq, k) {
	if (k <= 0) {
		return [];
	}
	while (k) {
		try {
			return linearPartition(seq, k--);
		}
		catch (_error) {
			//
		}
	}
}

export const JustifiedGrid = (resized, jsLoaded, selector, lightbox) => {
	if (console !== undefined && Photonic_JS.debug_on !== '0' && Photonic_JS.debug_on !== '') console.time('Justified Grid');
	let selection = document.querySelectorAll(selector);

	if (selector == null || selection.length === 0) {
		selection = document.querySelectorAll('.photonic-random-layout');
	}

	if (!resized && selection.length > 0) {
		Core.showSpinner();
	}

	const setupTitles = () => {
		Core.blankSlideupTitle();
		Core.showSlideupTitle();
		if (!resized && !jsLoaded) {
			Core.hideLoading();
		}
	};

	selection.forEach((container) => {
		// If there are some nodes for which the sizes are missing, play safe and run this in JS mode.
		// Otherwise render the gallery using CSS, and just display the images once they have downloaded.
		if (container.classList.contains('sizes-missing') || !window.CSS || !CSS.supports('color', 'var(--fake-var)')) {
			const viewportWidth = Math.floor(container.getBoundingClientRect().width),
				windowHeight = Math.max(document.documentElement.clientHeight, window.innerHeight || 0),
				idealHeight = Math.max(parseInt(windowHeight / 4), Photonic_JS.tile_min_height);

			const gap = Photonic_JS.tile_spacing * 2;

			Core.waitForImages(container).then(() => {
				const photos = [],
					images = Array.from(container.getElementsByTagName('img'));

				images.forEach(image => {
					if (image.closest('.photonic-panel') !== null) {
						return;
					}

					const div = image.parentNode.parentNode;

					if (!(image.naturalHeight === 0 || image.naturalHeight === undefined || image.naturalWidth === undefined)) {
						photos.push({tile: div, aspect_ratio: (image.naturalWidth) / (image.naturalHeight)});
					}
				});

				const summedWidth = photos.reduce(((sum, p) => sum += p.aspect_ratio * idealHeight + gap), 0);

				const rows = Math.max(Math.round(summedWidth / viewportWidth), 1), // At least 1 row should be shown
					weights = photos.map(p => Math.round(p.aspect_ratio * 100));

				const partition = part(weights, rows);
				let index = 0;

				const oLen = partition.length;
				for (let o = 0; o < oLen; o++) {
					const onePart = partition[o];
					let summedRatios;
					const rowBuffer = photos.slice(index, index + onePart.length);
					index = index + onePart.length;

					summedRatios = rowBuffer.reduce(((sum, p) => sum += p.aspect_ratio), 0);

					const rLen = rowBuffer.length;
					for (let r = 0; r < rLen; r++) {
						const item = rowBuffer[r],
							existing = item.tile;
						existing.style.width = parseInt(viewportWidth / summedRatios * item.aspect_ratio)+"px";
						existing.style.height = parseInt(viewportWidth / summedRatios)+"px";
					}
				}

				container.querySelectorAll('.photonic-thumb, .photonic-thumb img').forEach((thumb) => Util.fadeIn(thumb));
				setupTitles();
			});
		}
		else {
			Core.watchForImages(container);
			setupTitles();
		}

		if (lightbox && !resized) {
			if (Photonic_JS.lightbox_library === 'lightcase') {
				lightbox.initialize('.photonic-random-layout');
			}
			else if (['bigpicture', 'featherlight', 'glightbox'].indexOf(Photonic_JS.lightbox_library) > -1) {
				lightbox.initialize(container);
			}
			else if (Photonic_JS.lightbox_library === 'fancybox3') {
				lightbox.initialize('.photonic-random-layout');
			}
			else if (Photonic_JS.lightbox_library === 'photoswipe') {
				lightbox.initialize();
			}
		}
	});
	if (console !== undefined && Photonic_JS.debug_on !== '0' && Photonic_JS.debug_on !== '') console.timeEnd('Justified Grid');
};
