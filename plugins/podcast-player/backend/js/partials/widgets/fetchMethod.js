import FetchFilters from './fetchFilters';
import vars from './variables';

class FetchMethod {

	/**
	 * Manage Feed editor options.
	 * 
	 * @since 3.3
	 * 
	 * @param {string} id Podcast player ID. 
	 */
	constructor() {
		// Run methods.
		this.events();
	}

	// Event handling.
	events() {
		const _this = this;

		jQuery('#widgets-right, #elementor-editor-wrapper, #widgets-editor').on(
			'change',
			'.podcast-player-pp-fetch-method',
			function() {
				_this.changeFetchMethod(jQuery(this));
			}
		);
	}

	/**
	 * Change in podcast fech method.
	 * 
	 * @since 3.3.0
	 * 
	 * @param Obj obj
	 */
	changeFetchMethod(obj) {
		const wrapper = obj.closest('.widget-content');
		const fetchMethod = obj.val();
		const aspectRatio = wrapper.find('.podcast-player-pp-aspect-ratio');
		const excerptSupport = ['lv1', 'gv1', ''];
		const teaserText = wrapper.find('.podcast-player-pp-teaser-text').val();
		const hasExcerpt = '' === teaserText ? true : false;
		const txtColorSupport = ['lv1', 'lv2', 'lv3', 'gv1'];
		const thumbSupport = ['lv1', 'lv2', 'gv1', 'gv2'];
		const gridSupport = ['gv1', 'gv2'];
		const style = wrapper.find('select.podcast-player-pp-display-style').val();
		const commonHide = [
			'.pp_settings-content',
			'.pp_terms',
		];
		const feedSpecific = [
			'.feed_url',
			'.pp_hide_content',
			'.pp_slist',
			'.pp_catlist',
		];
		const postSpecific = [
			'.pp_post_type',
			'.pp_taxonomy',
			'.pp_podtitle',
		];
		const linkSpecific = [
			'.pp_audiosrc',
			'.pp_audiotitle',
			'.pp_audiolink',
			'.pp_ahide_download',
			'.pp_ahide_social',
			'.pp-lshow-toggle',
			'.pp-linfo-toggle',
		];
		const linkHide = [
			'.pp_elist',
			'.pp-filter-toggle',
			'.pp-show-toggle',
			'.pp_txtcolor',
			'.number.pp-widget-option',
			'.offset.pp-widget-option',
			'.pp_grid_columns',
			'.pp_crop_method',
			'.pp_aspect_ratio',
		];

		// Common Actions.
		wrapper.find('select.podcast-player-pp-taxonomy').val('');
		wrapper.find('.toggle-active').removeClass('toggle-active');
		wrapper.find(commonHide.join(',')).hide();

		// Fetch Method Specific actions.
		if ('feed' === fetchMethod) {
			wrapper.find(feedSpecific.join(',')).show();
			wrapper.find(postSpecific.join(',')).hide();
			wrapper.find(linkSpecific.join(',')).hide();
			wrapper.find(linkHide.join(',')).show();
		} else if ('post' === fetchMethod) {
			wrapper.find(feedSpecific.join(',')).hide();
			wrapper.find(postSpecific.join(',')).show();
			wrapper.find(linkSpecific.join(',')).hide();
			wrapper.find(linkHide.join(',')).show();
		} else if ('link' === fetchMethod) {
			wrapper.find(feedSpecific.join(',')).hide();
			wrapper.find(postSpecific.join(',')).hide();
			wrapper.find(linkSpecific.join(',')).show();
			wrapper.find(linkHide.join(',')).hide();
		}

		// Filter Checkboxes.
		if ('feed' === fetchMethod || 'post' === fetchMethod) {
			clearTimeout(vars.ajaxtimeout);
			vars.ajaxtimeout = setTimeout( () => {
				new FetchFilters(wrapper, fetchMethod, true);
			}, 500 );
		}

		// Show hide player style options for link method.
		if ('feed' === fetchMethod || 'post' === fetchMethod) {
			wrapper.find('.pp_teaser_text').toggle(excerptSupport.includes(style));
			wrapper.find('.pp_excerpt_length').toggle(excerptSupport.includes(style) && hasExcerpt);
			wrapper.find('.pp_excerpt_unit').toggle(excerptSupport.includes(style) && hasExcerpt);
			wrapper.find('.pp_txtcolor').toggle(txtColorSupport.includes(style));
			wrapper.find('.pp_grid_columns').toggle(gridSupport.includes(style));
			wrapper.find('.pp_crop_method').toggle(thumbSupport.includes(style) && !! aspectRatio.val());
			wrapper.find('.pp_aspect_ratio').toggle(thumbSupport.includes(style));
		} else {
			wrapper.find('.pp_teaser_text, .pp_excerpt_length, .pp_excerpt_unit').hide();
		}
	}
}

export default FetchMethod;
