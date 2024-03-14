import {Core} from "../Core";
import {PhotonicImageLightbox} from "../Lightboxes/ImageLightbox";
import * as Listeners from "../Listeners";
import * as Layout from "../Layouts/Layout";

jQuery(document).ready(function($) {
	const lightbox = new PhotonicImageLightbox($);
	Core.setLightbox(lightbox);
	lightbox.initialize('.photonic-standard-layout,.photonic-random-layout,.photonic-masonry-layout,.photonic-mosaic-layout');
	lightbox.initialize('a[rel="photonic-imagelightbox"]');

	Core.executeCommon();
	Listeners.addAllListeners();
	Layout.initializeLayouts(lightbox);
});
