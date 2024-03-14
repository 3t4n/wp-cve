class SocialTabsList {
	/* @ngInject */
	constructor() {
		this.socials = this.global.socialData;
	}

	isActive( social ) {
		return ( this.global.activeSocialTab == social.type );
	}
}

export default {
	bindings: {
		global: '='
	},
	template: sfPostFormTmpls.socialTabsList,
	controller: SocialTabsList,
}