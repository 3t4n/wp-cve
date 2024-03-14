const config = Object.assign({}, window.ECT_APP);

export function getNonce() {
	return config.nonce;
}

export function getAjaxNonce() {
	return config._ajax_nonce;
}

export const toBoolean = (value) => {
	return "true" === value || 1 == value ? true : false;
};

export function isWcLoaded() {
	return config.woocommerce.loaded;
}

export function isWcInstalled() {
	return config.woocommerce.installed;
}

export function isWCECTPlacement() {
	return config.woocommerce.placement;
}

export function isCF7Loaded() {
	return config.cf7.loaded;
}

export function isCF7Installed() {
	return config.cf7.installed;
}

export function isWPFormsLoaded() {
	return config.wpforms.loaded;
}

export function isWPFormsInstalled() {
	return config.wpforms.installed;
}

export function wpformsECTPlacement() {
	return config.wpforms.placement;
}

export function wpformsDisableIds() {
	return config.wpforms.ids;
}

export function isBuddyPressLoaded() {
	return config.buddypress.loaded;
}

export function isBuddyPressInstalled() {
	return config.buddypress.installed;
}

export function isElementorLoaded() {
	return config.elementor.loaded;
}

export function isElementorInstalled() {
	return config.elementor.installed;
}

export function isGravityFormsLoaded() {
	return config.gravityforms.loaded;
}

export function isGravityFormsInstalled() {
	return config.gravityforms.installed;
}

export function gravityECTPlacement() {
	return config.gravityforms.placement;
}

export function gravityDisableIds() {
	return config.gravityforms.ids;
}

export function isFormidableFormsInstalled() {
	return config.formidable.installed;
}

export function isFormidableFormsLoaded() {
	return config.formidable.loaded;
}

export function formidableECTPlacement() {
	return config.formidable.placement;
}

export function formidableDisableIds() {
	return config.formidable.ids;
}

export function isMailChimpFormsInstalled() {
	return config.mailchimp.installed;
}

export function isMailChimpFormsLoaded() {
	return config.mailchimp.loaded;
}

export function isForminatorFormsInstalled() {
	return config.forminator.installed;
}

export function isForminatorFormsLoaded() {
	return config.forminator.loaded;
}

export function isWpDiscuzInstalled() {
	return config.wpdiscuz.installed;
}

export function isWpDiscuzLoaded() {
	return config.wpdiscuz.loaded;
}

export function isHappyFormsInstalled() {
	return config.happyforms.installed;
}

export function isHappyFormsLoaded() {
	return config.happyforms.loaded;
}

export function isBbPressLoaded() {
	return config.bbpress.loaded;
}

export function isBbPressInstalled() {
	return config.bbpress.installed;
}

export function isWPUFLoaded() {
	return config.wpuf.loaded;
}

export function isWPUFInstalled() {
	return config.wpuf.installed;
}

export function isJetPackInstalled() {
	return config.jetpack.installed;
}

export function isJetPackLoaded() {
	return config.jetpack.loaded;
}

export function getSettings() {
	return config.settings.saved;
}


export function getValidationStatus() {
	return config.settings.validated;
}

export function getStore() {
	return config.store;
}