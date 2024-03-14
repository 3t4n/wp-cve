(function () {
	var showingIntentMs = swiftControlAdminBarOpts.showingIntent;
	var hidingIntentMs = swiftControlAdminBarOpts.hidingIntent;
	var windowWidth = document.documentElement.clientWidth;
	var triggerAreaHeight = windowWidth <= 782 ? 46 : 32;
	var timeoutId = 0;

	var mouseAction = "";
	var showingActionMs = 0;
	var hidingActionMs = 0;

	function init() {
		document.documentElement.classList.add("auto-hide-admin-bar");
		window.addEventListener("resize", onWindowResize);
		window.addEventListener("mousemove", onMouseMove);
		document.body.addEventListener("mouseleave", onMouseLeave);
	}

	function onWindowResize() {
		windowWidth = document.documentElement.clientWidth;

		triggerAreaHeight = windowWidth <= 782 ? 46 : 32;
	}

	function onMouseLeave(e) {
		mouseAction = "";
	}

	function onMouseMove(e) {
		var adminbarIsShown =
			document.documentElement.classList.contains("show-wpadminbar");

		if (e.clientY > triggerAreaHeight) {
			handleMouseLeave(e.target, adminbarIsShown);
			return;
		}

		handleMouseEnter(e.target, adminbarIsShown);
	}

	function handleMouseLeave(target, adminbarIsShown) {
		var now = Date.now();

		if (mouseAction !== "mouseleave") {
			hidingActionMs = now;
			mouseAction = "mouseleave";
		}

		showingActionMs = now;

		if (!adminbarIsShown) {
			return;
		}

		timeoutId = setTimeout(function () {
			hideAdminBar(target);
		}, hidingIntentMs);
	}

	function handleMouseEnter(target, adminbarIsShown) {
		var now = Date.now();

		if (mouseAction !== "mouseenter") {
			showingActionMs = now;
			mouseAction = "mouseenter";
		}

		hidingActionMs = now;

		if (adminbarIsShown) {
			return;
		}

		timeoutId = setTimeout(function () {
			showAdminBar(target);
		}, showingIntentMs);
	}

	function hideAdminBar(target) {
		if (mouseAction !== "mouseleave") {
			return;
		}

		if (Date.now() - hidingActionMs < hidingIntentMs) {
			return;
		}

		if (target.classList.contains("ab-item")) {
			return;
		}

		if (target.parentNode && target.parentNode.classList) {
			if (
				target.parentNode.classList.contains("ab-item") ||
				target.parentNode.classList.contains("ab-submenu") ||
				target.parentNode.classList.contains("ab-sub-wrapper") ||
				target.parentNode.classList.contains("menupop")
			) {
				return;
			}
		}

		setTimeout(function () {
			document.documentElement.classList.remove("show-wpadminbar");
		}, swiftControlAdminBarOpts.transitionDelay - hidingIntentMs);
	}

	function showAdminBar() {
		if (mouseAction !== "mouseenter") {
			return;
		}

		if (Date.now() - showingActionMs < showingIntentMs) {
			return;
		}

		document.documentElement.classList.add("show-wpadminbar");
	}

	init();
})();
