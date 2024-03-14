import {Core} from "../Core";
import {PhotonicFeatherlight} from "../Lightboxes/Featherlight";
import * as Listeners from "../Listeners";
import * as Layout from "../Layouts/Layout";

jQuery(document).ready(function($) {
	const lightbox = new PhotonicFeatherlight($);
	Core.setLightbox(lightbox);
	lightbox.initialize('.photonic-standard-layout,.photonic-random-layout,.photonic-masonry-layout,.photonic-mosaic-layout');

	Core.executeCommon();
	Listeners.addAllListeners();
	Layout.initializeLayouts(lightbox);
});
