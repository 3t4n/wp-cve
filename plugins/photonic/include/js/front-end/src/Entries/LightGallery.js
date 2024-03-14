import {Core} from "../Core";
import {PhotonicLightGallery} from "../Lightboxes/LightGallery";
import * as Listeners from "../Listeners";
import * as Layout from "../Layouts/Layout";

document.addEventListener('DOMContentLoaded', () => {
	const lightbox = new PhotonicLightGallery();
	Core.setLightbox(lightbox);
	// lightbox.initialize('.photonic-standard-layout,.photonic-random-layout,.photonic-masonry-layout,.photonic-mosaic-layout');
	lightbox.initialize('.photonic-level-1-container');
	lightbox.initialize('a[rel="photonic-lightgallery"]', true);
	lightbox.initialize('a.lightgallery-video', true);
	lightbox.initialize('a.lightgallery-html5-video', true);

	Core.executeCommon();
	Listeners.addAllListeners();
	Layout.initializeLayouts(lightbox);
});
