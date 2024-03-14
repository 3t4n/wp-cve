/**
 * Photonic Mosaic Layout
 * Arranges photos similar to JetPack tiles, but without any of the limitations of JetPack tiles. JS-based, responsive, and images are not affected by resolution issues.
 *
 * License: GPL V3.0
 */
import {Core} from "../Core";
import * as Util from "../Util";

const getDistribution = (setSize, max, min) => {
	const distribution = [];
	let processed = 0;
	while (processed < setSize) {
		if (setSize - processed <= max && processed > 0) {
			distribution.push(setSize - processed);
			processed += setSize - processed;
		}
		else {
			let current = Math.max(Math.floor(Math.random() * max + 1), min);
			current = Math.min(current, setSize - processed);
			distribution.push(current);
			processed += current;
		}
	}
	return distribution;
};

const arrayAlternate = (array, remainder) => array.filter((value, index) => index % 2 === remainder);

const setUniformHeightsForRow = array => {
	// First, order the array by increasing height
	array.sort((a, b) => a.height - b.height);

	array[0].new_height = array[0].height;
	array[0].new_width = array[0].width;

	for (let i = 1; i < array.length; i++) {
		array[i].new_height = array[0].height;
		array[i].new_width = array[i].new_height * array[i].aspect_ratio;
	}
	const new_width = array.reduce((sum, p) => sum += p.new_width, 0);
	return { elements: array, height: array[0].new_height, width: new_width, aspect_ratio: new_width / array[0].new_height };
};

const finalizeTiledLayout = (components, containers) => {
	const cLength = components.length;
	for (let c = 0; c < cLength; c++) {
		const component = components[c];
		let rowY = component.y,
			otherRowHeight = 0,
			container;
		const ceLen = component.elements.length;
		for (let e = 0; e < ceLen; e++) {
			const element = component.elements[e];
			if (element.photo_position !== undefined) {
				// Component is a single image
				container = containers[element.photo_position];
				container.style.width = component.new_width + 'px';
				container.style.height = component.new_height + 'px';
				container.style.top = component.y + 'px';
				container.style.left = component.x + 'px';
			}
			else {
				// Component is a clique (element is a row). Widths and Heights of cliques have been calculated. But the rows in cliques need to be recalculated
				element.new_width = component.new_width;
				if (otherRowHeight === 0) {
					element.new_height = element.new_width / element.aspect_ratio;
					otherRowHeight = element.new_height;
				}
				else {
					element.new_height = component.new_height - otherRowHeight;
				}
				element.x = component.x;
				element.y = rowY;
				rowY += element.new_height;
				const totalWidth = element.elements.reduce((sum, p) => sum += p.new_width, 0);

				let rowX = 0;
				const eLength = element.elements.length;
				for (let i = 0; i < eLength; i++) {
					const image = element.elements[i];
					image.new_width = element.new_width * image.new_width / totalWidth;
					image.new_height = element.new_height; //image.new_width / image.aspect_ratio;
					image.x = rowX;

					rowX += image.new_width;

					container = containers[image.photo_position];
					container.style.width = Math.floor(image.new_width) + 'px';
					container.style.height = Math.floor(image.new_height) + 'px';
					container.style.top = Math.floor(element.y) + 'px';
					container.style.left = Math.floor(element.x + image.x) + 'px';
				}
			}
		}
	}
};

const doImageLayout = (grid, needToWait) => {
	const viewportWidth = Math.floor(grid.getBoundingClientRect().width),
		triggerWidth = (isNaN(Photonic_JS.mosaic_trigger_width) || parseInt(Photonic_JS.mosaic_trigger_width) <= 0) ? 200 : parseInt(Photonic_JS.mosaic_trigger_width),
		maxInRow = Math.floor(viewportWidth / triggerWidth),
		minInRow = viewportWidth >= (triggerWidth * 2) ? 2 : 1,
		photos = [];

	let setSize;

	const containers = [],
		images = Array.from(grid.getElementsByTagName('img'));

	images.forEach((image, position) => {
		if (image.closest('.photonic-panel') != null) {
			return;
		}

		const a = image.parentNode;
		const div = a.parentNode;
		div.setAttribute('data-photonic-photo-index', position);
		containers[position] = div;

		const height = needToWait ? image.naturalHeight : image.getAttribute('height'),
			width = needToWait ? image.naturalWidth : image.getAttribute('width');

		if (!(height === 0 || height === undefined || width === undefined)) {
			const aspectRatio = (width) / (height);
			photos.push({src: image.src, width: width, height: height, aspect_ratio: aspectRatio, photo_position: position});
		}
	});

	setSize = photos.length;
	const distribution = getDistribution(setSize, maxInRow, minInRow);

	// We got our random distribution. Let's divide the photos up according to the distribution.
	let groups = [], startIdx = 0;
	distribution.forEach((size) => {
		groups.push(photos.slice(startIdx, startIdx + size));
		startIdx += size;
	});

	let groupY = 0;

	// We now have our groups of photos. We need to find the optimal layout for each group.
	groups.forEach((group) => {
		// First, order the group by aspect ratio
		group.sort((a, b) => a.aspect_ratio - b.aspect_ratio);

		// Next, pick a random layout
		let groupLayout;
		if (group.length === 1) {
			groupLayout = [1];
		}
		else if (group.length === 2) {
			groupLayout = [1,1];
		}
		else {
			groupLayout = getDistribution(group.length, group.length - 1, 1);
		}

		// Now, LAYOUT, BABY!!!
		let cliqueF = 0,
			cliqueL = group.length - 1,
			cliques = [],
			indices = [];

		for (let i = 2; i <= maxInRow; i++) {
			let index = groupLayout.indexOf(i);
			while (-1 < index && cliqueF < cliqueL) {
				// Ideal Layout: one landscape, one portrait. But we will take any 2 with contrasting aspect ratios
				let clique = [], j = 0;
				while (j < i && cliqueF <= cliqueL) {
					clique.push(group[cliqueF++]); // One with a low aspect ratio
					j++;
					if (j < i && cliqueF <= cliqueL) {
						clique.push(group[cliqueL--]); // One with a high aspect ratio
						j++;
					}
				}
				// Clique is formed. Add it to the list of cliques.
				cliques.push(clique);
				indices.push(index); // Keep track of the position of the clique in the row
				index = groupLayout.indexOf(i, index + 1);
			}
		}

		// The ones that are not in any clique (i.e. the ones in the middle) will be given their own columns in the row.
		const remainder = group.slice(cliqueF, cliqueL + 1);

		// Now let's layout the cliques individually. Each clique is its own column.
		let rowLayout = [];
		cliques.forEach((clique, cliqueIdx) => {
			const toss = Math.floor(Math.random() * 2); // 0 --> Groups of smallest and largest, or 1 --> Alternating
			let oneRow, otherRow;
			if (toss === 0) {
				// Group the ones with the lowest aspect ratio together, and the ones with the highest aspect ratio together.
				// Lay one group at the top and the other at the bottom
				const wide = Math.max(Math.floor(Math.random() * (clique.length / 2 - 1)), 1);
				oneRow = clique.slice(0, wide);
				otherRow = clique.slice(wide);
			}
			else {
				// Group alternates together.
				// Lay one group at the top and the other at the bottom
				oneRow = arrayAlternate(clique, 0);
				otherRow = arrayAlternate(clique, 1);
			}

			// Make heights consistent within rows:
			oneRow = setUniformHeightsForRow(oneRow);
			otherRow = setUniformHeightsForRow(otherRow);

			// Now make widths consistent
			oneRow.new_width = Math.min(oneRow.width, otherRow.width);
			oneRow.new_height = oneRow.new_width / oneRow.aspect_ratio;
			otherRow.new_width = oneRow.new_width;
			otherRow.new_height = otherRow.new_width / otherRow.aspect_ratio;

			rowLayout.push({elements: [oneRow, otherRow], height: oneRow.new_height + otherRow.new_height, width: oneRow.new_width, aspect_ratio: oneRow.new_width / (oneRow.new_height + otherRow.new_height), element_position: indices[cliqueIdx]});
		});

		rowLayout.sort((a, b) => a.element_position - b.element_position);

		let orderedRowLayout = [];
		for (let position = 0; position < groupLayout.length; position++) {
			const cliqueExists = indices.indexOf(position) > -1;
			if (cliqueExists) {
				orderedRowLayout.push(rowLayout.shift());
			}
			else {
				const rem = remainder.shift();
				orderedRowLayout.push({ elements: [rem], height: rem.height, width: rem.width, aspect_ratio: rem.aspect_ratio });
			}
		}

		// Main Row layout is fully constructed and ordered. Now we need to balance heights and widths of all cliques with the "remainder"
		const totalAspect = orderedRowLayout.reduce((sum, p) => sum += p.aspect_ratio, 0);

		let elementX = 0;
		orderedRowLayout.forEach(component => {
			component.new_width = component.aspect_ratio / totalAspect * viewportWidth;
			component.new_height = component.new_width / component.aspect_ratio;
			component.y = groupY;
			component.x = elementX;
			elementX += component.new_width;
		});

		groupY += orderedRowLayout[0].new_height;
		finalizeTiledLayout(orderedRowLayout, containers);
	});

	grid.style.height = groupY + 'px';
};

export const Mosaic = (resized, jsLoaded, selector) => {
	if (console !== undefined && Photonic_JS.debug_on !== '0' && Photonic_JS.debug_on !== '') console.time('Mosaic');
	let selection = document.querySelectorAll(selector);

	if (selector == null || selection.length === 0) {
		selection = document.querySelectorAll('.photonic-mosaic-layout');
	}

	if (!resized && selection.length > 0) {
		Core.showSpinner();
	}

	selection.forEach((grid) => {
		if (!grid.hasChildNodes()) {
			return;
		}

		if (grid.classList.contains('sizes-present')) {
			Core.watchForImages(grid);
			doImageLayout(grid, false);

			Core.showSlideupTitle();
			if (!resized && !jsLoaded) {
				Core.hideLoading();
			}
		}
		else {
			Core.waitForImages(grid).then(() => {
				if (!grid.classList.contains('sizes-present')) {
					doImageLayout(grid, true);
				}
				Array.from(grid.getElementsByTagName('img')).forEach(image => Util.fadeIn(image));

				Core.showSlideupTitle();
				if (!resized && !jsLoaded) {
					Core.hideLoading();
				}
			});
		}
	});
	if (console !== undefined && Photonic_JS.debug_on !== '0' && Photonic_JS.debug_on !== '') console.timeEnd('Mosaic');
};

//Mosaic(false);
