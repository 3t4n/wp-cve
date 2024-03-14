import {Core} from "../Core";
import {PhotonicColorbox} from "../Lightboxes/Colorbox";
import * as Listeners from "../Listeners";
import * as Layout from "../Layouts/Layout";

jQuery(document).ready(function($) {
	const lightbox = new PhotonicColorbox($);
	Core.setLightbox(lightbox);
	lightbox.initialize();

	Core.executeCommon();
	Listeners.addAllListeners();
	Layout.initializeLayouts(lightbox);
});
