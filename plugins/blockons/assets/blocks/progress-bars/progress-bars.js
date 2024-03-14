/*
 * Waypoints for the Progress bars to load when InView
 */
window.addEventListener("DOMContentLoaded", function () {
	const progressBars = document.getElementsByClassName("blockons-progressbar");

	if (progressBars.length) {
		for (const el of progressBars) {
			const pBar = new Waypoint({
				element: el,
				handler: function (direction) {
					// console.log("Trigger point: " + this.triggerPoint);
					el.classList.remove("pb-start");
				},
				offset: "bottom-in-view",
			});
		}
	}
});
