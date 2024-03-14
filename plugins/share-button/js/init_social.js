jQuery(document).ready(function(jq) {
	$ = jq; // to counter noConflict bandits

	function runSocial()
	{
		if (typeof window.maxFoundry === 'undefined')
			window.maxFoundry = {};

    window.maxFoundry.mbSocial = {}; 

		window.maxFoundry.maxadmin = new maxAdminPro();
	 	window.maxFoundry.maxadmin.init();

		window.maxFoundry.maxmodal = new maxModal();
		window.maxFoundry.maxmodal.init();

		window.maxFoundry.maxfonts = new maxFonts();
		window.maxFoundry.maxfonts.checkFonts();// init

		window.maxFoundry.maxIcons = new mbIcons();
		window.maxFoundry.maxIcons.init();

		window.maxFoundry.maxTabs = new maxTabs();
		window.maxFoundry.maxTabs.init();

		window.maxFoundry.maxLicense = new maxAdminLicense();
		window.maxFoundry.maxLicense.init();

		window.maxFoundry.maxAjax = new maxAjax();
		window.maxFoundry.maxAjax.init();

	}

	runSocial();

}); /* END OF JQUERY */
