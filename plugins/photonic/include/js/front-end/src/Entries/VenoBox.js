import {Core} from "../Core";
import {PhotonicVenoBox} from "../Lightboxes/VenoBox";
import * as Listeners from "../Listeners";
import * as Layout from "../Layouts/Layout";

document.addEventListener('DOMContentLoaded', () => {
	const lightbox = new PhotonicVenoBox();
	Core.setLightbox(lightbox);
	lightbox.initialize();

	Core.executeCommon();
	Listeners.addAllListeners();
	Layout.initializeLayouts(lightbox);
});
