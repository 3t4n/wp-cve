import {Core} from "../Core";
import {PhotonicGLightbox} from "../Lightboxes/GLightbox";
import * as Listeners from "../Listeners";
import * as Layout from "../Layouts/Layout";

document.addEventListener('DOMContentLoaded', () => {
	const lightbox = new PhotonicGLightbox();
	Core.setLightbox(lightbox);
	lightbox.initialize();
	lightbox.initialize('.photonic-glightbox-solo, .glightbox-video, .glightbox-html5-video');

	Core.executeCommon();
	Listeners.addAllListeners();
	Layout.initializeLayouts(lightbox);
});
