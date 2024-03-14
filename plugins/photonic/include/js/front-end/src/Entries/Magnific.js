import {Core} from "../Core";
import {PhotonicMagnific} from "../Lightboxes/Magnific";
import * as Listeners from "../Listeners";
import * as Layout from "../Layouts/Layout";

jQuery(document).ready(function($) {
	const lightbox = new PhotonicMagnific($);
	Core.setLightbox(lightbox);
	lightbox.initialize('.photonic-standard-layout, .photonic-random-layout, .photonic-mosaic-layout, .photonic-masonry-layout');
	lightbox.initializeSolos();

	Core.executeCommon();
	Listeners.addAllListeners();
	Layout.initializeLayouts(lightbox);
});
