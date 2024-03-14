const Masonry = require("masonry-layout");
var imagesLoaded = require("imagesloaded");

document.addEventListener("DOMContentLoaded", () => {
	const isPro = Boolean(blockonsDetails.isPremium);

	const msnryEle = document.querySelector(".blockons-gallery.masonry");
	if (msnryEle) {
		const msnry = new Masonry(msnryEle, {
			itemSelector: ".blockons-gallery-item.masonry",
			columnWidth: ".blockons-gallery-item.masonry",
			percentPosition: true,
		});

		imagesLoaded(msnryEle, () => msnry.layout());
	}

	// Add Venobox if isPro, else hide elements
	// const blockonsGals = document.querySelectorAll(".blockons-gallery");
	// if (isPro && blockonsGals) {
	// 	console.log(blockonsGals);

	// 	blockonsGals.forEach((gallery) => {
	// 		const popupSettings = JSON.parse(gallery.getAttribute("data-popup"));
	// 		// console.log("Popup Settings: ", popupSettings);

	// 		if (popupSettings) {
	// 			const venoboxItems = document.querySelectorAll(".blockons-venobox-item");

	// 			if (venoboxItems) {
	// 				venoboxItems.forEach((item, i) => {
	// 					item.classList.add("blockons-venobox");
	// 				});
	// 			}
	// 		}
	// 	});
	// }
});
