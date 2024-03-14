import "../imagegallery";

document.addEventListener("DOMContentLoaded", () => {
	const isPro = Boolean(blockonsDetails.isPremium);

	// Add Venobox if isPro, else hide elements
	const blockonsGals = document.querySelectorAll(".blockons-gallery");
	if (isPro && blockonsGals) {
		blockonsGals.forEach((gallery) => {
			const popupSettings = JSON.parse(gallery.getAttribute("data-popup"));
			// console.log("Popup Settings: ", popupSettings);

			if (popupSettings) {
				new VenoBox({
					selector: ".blockons-venobox",
					customClass: "blockons-popup",
					numeration: true,
					infinigall: popupSettings.infinite,
					share: false,
					titleattr: popupSettings.caption !== "none" ? "data-title" : "",
					titlePosition:
						popupSettings.caption !== "none" ? popupSettings.caption : "top", // bottom
					titleStyle: "pill", // 'block' | 'pill' | 'transparent' | 'bar'
					spinner: "flow",
					maxWidth: "1200px",
					toolsColor: "#FFF",
				});
			}
		});
	}
});
