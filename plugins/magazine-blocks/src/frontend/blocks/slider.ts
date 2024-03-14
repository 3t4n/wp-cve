import { Splide } from "@splidejs/splide";
import domReady from "@wordpress/dom-ready";

domReady(() => {
	new Splide(".splide", {
		perPage: 1,
		pauseOnHover: false,
		interval: 2000,
		type: "loop",
	}).mount();
});
