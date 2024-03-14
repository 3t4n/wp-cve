import {Core} from "../Core";
import {PhotonicPhotoSwipe5} from "../Lightboxes/PhotoSwipe5";
import * as Listeners from "../Listeners";
import * as Layout from "../Layouts/Layout";

document.addEventListener('DOMContentLoaded', () => {
	const lightbox = new PhotonicPhotoSwipe5();
	Core.setLightbox(lightbox);
	lightbox.initialize();
	lightbox.initializeForExisting();

	Core.executeCommon();
	Listeners.addAllListeners();
	Layout.initializeLayouts(lightbox);
});
