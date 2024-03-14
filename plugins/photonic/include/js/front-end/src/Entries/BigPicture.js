import {Core} from "../Core";
import {PhotonicBigPicture} from "../Lightboxes/BigPicture";
import * as Listeners from "../Listeners";
import * as Layout from "../Layouts/Layout";

document.addEventListener('DOMContentLoaded', () => {
	const lightbox = new PhotonicBigPicture();
	Core.setLightbox(lightbox);
	lightbox.initialize();

	Core.executeCommon();
	Listeners.addAllListeners();
	Layout.initializeLayouts(lightbox);
});
