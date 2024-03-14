import {Core} from "../Core";
import {PhotonicPhotoSwipe} from "../Lightboxes/PhotoSwipe";
import * as Listeners from "../Listeners";
import * as Layout from "../Layouts/Layout";

document.addEventListener('DOMContentLoaded', () => {
	const lightbox = new PhotonicPhotoSwipe();
	Core.setLightbox(lightbox);
	lightbox.initialize();
	lightbox.initializeForExisting();

	Core.executeCommon();
	Listeners.addAllListeners();
	Layout.initializeLayouts(lightbox);
});
