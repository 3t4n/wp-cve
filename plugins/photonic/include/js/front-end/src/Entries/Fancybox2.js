import {Core} from "../Core";
import {PhotonicFancybox2} from "../Lightboxes/Fancybox2";
import * as Listeners from "../Listeners";
import * as Layout from "../Layouts/Layout";

jQuery(document).ready(function($) {
	const lightbox = new PhotonicFancybox2($);
	Core.setLightbox(lightbox);
	lightbox.initialize();

	Core.executeCommon();
	Listeners.addAllListeners();
	Layout.initializeLayouts(lightbox);
});
