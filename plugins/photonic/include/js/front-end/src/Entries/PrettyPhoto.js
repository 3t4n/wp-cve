import {Core} from "../Core";
import {PhotonicPrettyPhoto} from "../Lightboxes/PrettyPhoto";
import * as Listeners from "../Listeners";
import * as Layout from "../Layouts/Layout";

jQuery(document).ready(function($) {
	const lightbox = new PhotonicPrettyPhoto($);
	Core.setLightbox(lightbox);
	lightbox.handleSolos();
	lightbox.initializeForExisting();

	Core.executeCommon();
	Listeners.addAllListeners();
	Layout.initializeLayouts(lightbox);
});
