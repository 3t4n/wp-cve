(function() {
	/**
	 * Open a popup
	 *
	 * @param {MouseEvent} evt
	 * @returns {boolean}
	 */
	function openSocialSharingPopup (evt) {
		const top = (screen.availHeight - 500) / 2;
		const left = (screen.availWidth - 500) / 2;
		const popup = window.open(
			this.href,
			'social',
			'width=550,height=420,left=' + left + ',top=' + top + ',location=0,menubar=0,toolbar=0,status=0,scrollbars=1,resizable=1'
		);

		if (popup) {
			popup.focus();
			evt.preventDefault();
		}
	}

	/**
	 * Adds the click event handler to all links inside the .dvk-social-sharing wrapper element
	 */
	function initSocialSharing () {
		const links = document.querySelectorAll('.dvk-social-sharing a');
		for (let i = 0; i < links.length; i++) {
			links[i].addEventListener('click', openSocialSharingPopup);
		}
	}

	window.addEventListener('load', initSocialSharing)
})();
