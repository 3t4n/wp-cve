const ppData = window.podcastPlayerData || {};
let podcastVariables = {
	podcastPlayerData: jQuery.extend(true, {}, ppData),
	currentlyPlaying: false,

	/**
	 * Enable scroll on the element that scrolls the document.
	 * 
	 * @since 1.2.3
	 */
	isStyleSupport(style, item) {
		const supported = window.ppmejsSettings.stSup || false;
		if (! supported || ! style) return false;
		return supported[style].includes(item);
	},

	/**
	 * Check if element is currently in viewport.
	 * 
	 * @since 5.2.0
	 */
	isInViewport(elem) {
		if (! elem || ! elem.length) return;
		const top_of_element = elem.offset().top;
		const bottom_of_element = elem.offset().top + elem.outerHeight();
		const bottom_of_screen = jQuery(window).scrollTop() + jQuery(window).innerHeight();
		const top_of_screen = jQuery(window).scrollTop();

		return bottom_of_screen > top_of_element && top_of_screen < bottom_of_element;
	},

	/**
	 * Enable scroll on the element that scrolls the document.
	 * 
	 * @since 5.2.0
	 */
	stickyonScroll() {
		if (this.currentlyPlaying) this.currentlyPlaying();
	},

	/**
	 * Create a cookie.
	 *
	 * @param {string} name 
	 * @param {object} value 
	 * @param {int} days 
	 */
	createCookie(name, value, days) {
		let expires;

		// Convert Object to string.
		value = JSON.stringify(value);	
		
		// Get number of days to keep cookies.
		if (days) {
			const date = new Date();
			date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
			expires = "; expires=" + date.toGMTString();
		} else {
			expires = "";
		}
		
		// Create cookie.
		document.cookie = encodeURIComponent(name) + "=" + encodeURIComponent(value) + expires + "; path=/ ; SameSite=Lax";
	},

	/**
	 * Read a cookie.
	 *
	 * @param {string} name 
	 * @returns 
	 */
	readCookie(name) {
		const nameEQ = encodeURIComponent(name) + "=";
		const ca = document.cookie.split(';');
		let i = 0;
		for (; i < ca.length; i++) {
			let c = ca[i];
			while (c.charAt(0) === ' ') {
				c = c.substring(1, c.length);
			}
			if (c.indexOf(nameEQ) === 0) {
				let value = decodeURIComponent(c.substring(nameEQ.length, c.length));
				return JSON.parse(value);
			}
		}
		return null;
	},

	/**
	 * Delete a cookie.
	 *
	 * @param {string} name 
	 */
	eraseCookie(name) {
		this.createCookie(name, "", -1);
	},
};
export default podcastVariables;