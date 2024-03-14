import {Core} from "../Core";
import {PhotonicStrip} from "../Lightboxes/Strip";
import * as Listeners from "../Listeners";
import * as Layout from "../Layouts/Layout";

jQuery(document).ready(function($) {
	const lightbox = new PhotonicStrip($);
	Core.setLightbox(lightbox);
	lightbox.initialize();

	Core.executeCommon();
	Listeners.addAllListeners();
	Layout.initializeLayouts(lightbox);
});
