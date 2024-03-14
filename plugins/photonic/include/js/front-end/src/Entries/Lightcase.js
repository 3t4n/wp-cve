import {Core} from "../Core";
import {PhotonicLightcase} from "../Lightboxes/Lightcase";
import * as Listeners from "../Listeners";
import * as Layout from "../Layouts/Layout";

jQuery(document).ready(function($) {
	const lightbox = new PhotonicLightcase($);
	Core.setLightbox(lightbox);
	lightbox.initialize('.photonic-standard-layout,.photonic-masonry-layout,.photonic-mosaic-layout');
	lightbox.initialize('a[data-rel="photonic-lightcase"]');
	lightbox.initialize('a[data-rel="photonic-lightcase-video"]');
	lightbox.initialize('a[data-rel="photonic-html5-video"]');

	Core.executeCommon();
	Listeners.addAllListeners();
	Layout.initializeLayouts(lightbox);
});
