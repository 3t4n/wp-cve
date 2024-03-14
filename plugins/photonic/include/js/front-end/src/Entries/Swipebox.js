import {Core} from "../Core";
import {PhotonicSwipebox} from "../Lightboxes/Swipebox";
import * as Listeners from "../Listeners";
import * as Layout from "../Layouts/Layout";

jQuery(document).ready(function($) {
	const lightbox = new PhotonicSwipebox($);
	Core.setLightbox(lightbox);
	lightbox.initialize();

	Core.executeCommon();
	Listeners.addAllListeners();
	Layout.initializeLayouts(lightbox);
});
