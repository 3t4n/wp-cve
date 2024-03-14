import {Core} from "../Core";
import {PhotonicBaguetteBox} from "../Lightboxes/BaguetteBox";
import * as Listeners from "../Listeners";
import * as Layout from "../Layouts/Layout";

document.addEventListener('DOMContentLoaded', () => {
	const lightbox = new PhotonicBaguetteBox();
	Core.setLightbox(lightbox);
	lightbox.initialize();
	lightbox.initialize('.photonic-baguettebox-solo');
	// lightbox.initialize('.baguettebox-video'); // YouTube / Vimeo etc. do not work
	lightbox.initialize('.baguettebox-html5-video');

	Core.executeCommon();
	Listeners.addAllListeners();
	Layout.initializeLayouts(lightbox);
});
