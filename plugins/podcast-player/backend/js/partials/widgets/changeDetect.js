import FetchFilters from './fetchFilters';
import vars from './variables';

class ChangeDetect {

	/**
	 * Manage Feed editor options.
	 * 
	 * @since 3.3
	 */
	constructor() {
		// Run methods.
		this.events();
	}

	// Event handling.
	events() {
		const _this  = this;
		const widget = jQuery('#widgets-right, #elementor-editor-wrapper, #widgets-editor');
		const doc    = jQuery(document);

		doc.on('click', '.pp-settings-toggle', function(e) {
			e.preventDefault();
			_this.settingsToggle(jQuery(this));
			_this.widgetAdded(jQuery(this));
		});

		widget.on('change', '.podcast-player-pp-post-type', function() {
			_this.postType(jQuery(this));
		});

		widget.on('change', '.podcast-player-pp-taxonomy', function() {
			_this.taxonomy(jQuery(this));
		});

		widget.on('change', '.podcast-player-pp-furl-select', function() {
			const $this = jQuery(this);
			const val   = $this.val();
			$this.siblings('.pp_feed-url').val( val ).trigger('change').trigger('input');
		});

		widget.on('input', '.feed_url input', function() {
			_this.feedUrl(jQuery(this));
		});

		widget.on('change', '.podcast-player-pp-display-style', function() {
			_this.displayStyle(jQuery(this));
		});

		widget.on('change', '.podcast-player-pp-aspect-ratio', function() {
			_this.aspectRatio(jQuery(this));
		});

		widget.on('change', '.podcast-player-pp-start-when', function() {
			_this.startWhen(jQuery(this));
		});

		widget.on('change', '.d-episode input[type="checkbox"]', function() {
			const selectAll = jQuery(this);
			const edisplay  = selectAll.closest('.pp_elist').next('.pp_edisplay');
			_this.filterCheckboxes(selectAll, 'episode');
			if (selectAll.is(':checked')) {
				edisplay.hide();
			} else {
				edisplay.show();
			}
		});

		widget.on('change', '.d-season input[type="checkbox"]', function() {
			_this.filterCheckboxes(jQuery(this), 'season');
		});

		widget.on('change', '.d-cat input[type="checkbox"]', function() {
			_this.filterCheckboxes(jQuery(this), 'cat');
		});

		widget.on('change', '.pp_hide_header input[type="checkbox"]', function() {
			_this.hideHeader(jQuery(this));
		});

		widget.on('change',
		'.pp_terms-checklist input[type="checkbox"], .filterby input',
		function() {
			_this.postFetch(jQuery(this));
		});

		widget.on('change',
		'.pp_slist-checklist input[type="checkbox"], .pp_catlist-checklist input[type="checkbox"]',
		function() {
			_this.feedFetch(jQuery(this), false);
		});

		widget.on('change', 'select.podcast-player-podcast-menu', function() {
			_this.toggleMenuItems(jQuery(this));
		});

		widget.on('change', '.main_menu_items input[type="number"]', function() {
			_this.toggleDepricatedSub(jQuery(this));
		});

		widget.on('change', '.podcast-player-pp-teaser-text', function() {
			_this.toggleExcerptOptions(jQuery(this));
		});
	}

	/**
	 * Widget Added.
	 * 
	 * @since 3.3.0
	 * 
	 * @param {Obj} obj
	 */
	widgetAdded(obj) {
		if (obj.hasClass('pp-filter-toggle')) {
			const content = obj.next( '.pp_settings-content' );
			// Disable all other options if 'Show All' options is checked.
			if (content.find('.d-episode input[type="checkbox"]').is(':checked')) {
				content.find('.pp-episodes input[type="checkbox"]').attr("disabled", true);
			}
			if (content.find('.d-cat input[type="checkbox"]').is(':checked')) {
				content.find('.pp-cats input[type="checkbox"]').attr("disabled", true);
			}
			if (content.find('.d-season input[type="checkbox"]').is(':checked')) {
				content.find('.pp-seasons input[type="checkbox"]').attr("disabled", true);
			}
		}
	}

	/**
	 * settingsToggle.
	 * 
	 * @since 3.3.0
	 * 
	 * @param {Obj} obj
	 */
	settingsToggle(obj) {
		obj.next('.pp_settings-content').slideToggle('fast');
		obj.toggleClass( 'toggle-active' );
	}

	/**
	 * postType.
	 * 
	 * @since 3.3.0
	 * 
	 * @param {Obj} obj
	 */
	postType(obj) {
		const postType = obj.val();
		const wrapper  = obj.closest('.widget-content');
		const taxonomy = wrapper.find('.podcast-player-pp-taxonomy');

		// Hide all taxonomy options.
		taxonomy.find( 'option' ).hide();

		// Now display only selected options.
		taxonomy.find( '.always-visible, .' + postType ).show();
		
		// Empty taxonomy value.
		taxonomy.val('');
		
		// Fetch episodes list for episode filter option.
		this.postFetch(obj);
	}

	/**
	 * Taxonomy.
	 * 
	 * @since 3.3.0
	 * 
	 * @param {Obj} obj
	 */
	taxonomy(obj) {
		const value = obj.val();
		const wrapper = obj.closest('.widget-content');
		const terms = wrapper.find('.pp_terms')

		// Uncheck all terms options and Hide Terms checkbox dropdown.
		terms.find('.pp_terms-checklist input:checkbox').removeAttr('checked');
		terms.hide();

		// Show terms only for currently selected taxonomy.
		if (value) {
			terms.find( '.pp_terms-checklist li' ).hide();
			terms.find( '.pp_terms-checklist .' + value ).show();
			terms.show();
		}

		// Fetch episodes list for episode filter option.
		this.postFetch(obj);
	}

	/**
	 * Feed URL.
	 * 
	 * @since 3.3.0
	 * 
	 * @param {Obj} obj
	 */
	feedUrl(obj) {

		// Reset all filters if feed url has changed.
		this.resetAutoFilters();
		if (obj.val()) this.feedFetch(obj, true);
	}

	/**
	 * Aspect Ratio.
	 * 
	 * @since 3.3.0
	 * 
	 * @param {Obj} obj
	 */
	aspectRatio(obj) {
		if (obj.val()) {
			obj.closest('.widget-content').find('.pp_crop_method').show();
		} else {
			obj.closest('.widget-content').find('.pp_crop_method').hide();
		}
	}

	/**
	 * Aspect Ratio.
	 * 
	 * @since 3.3.0
	 * 
	 * @param {Obj} obj
	 */
	toggleExcerptOptions(obj) {
		if (obj.val()) {
			obj.closest('.widget-content').find('.pp_excerpt_length, .pp_excerpt_unit').hide();
		} else {
			obj.closest('.widget-content').find('.pp_excerpt_length, .pp_excerpt_unit').show();
		}
	}

	/**
	 * Start When.
	 * 
	 * @since 3.3.0
	 * 
	 * @param {Obj} obj
	 */
	startWhen(obj) {
		var value = obj.val();
		if (value && 'custom' === value) {
			obj.closest('.widget-content').find('.pp_start_time').show();
		} else {
			obj.closest('.widget-content').find('.pp_start_time').hide();
		}
	}

	/**
	 * Reset all filter checkboxes.
	 * 
	 * @since 3.3.0
	 */
	resetAutoFilters() {
		this.filterCheckboxes( jQuery('.d-episode input[type="checkbox"]'), 'episode' );
		this.filterCheckboxes( jQuery('.d-season input[type="checkbox"]'), 'season' );
		this.filterCheckboxes( jQuery('.d-cat input[type="checkbox"]'), 'cat' );
	}

	/**
	 * Episode Checkbox.
	 * 
	 * @since 3.3.0
	 * 
	 * @param {Obj} obj
	 */
	filterCheckboxes(obj, elem) {
		const wrapper = obj.closest('.widget-content');
		const element = `.pp-${elem}s`;
		if (obj.is(':checked')) {
			wrapper.find(element + ' input[type="checkbox"]')
				.attr("disabled", true)
				.prop("checked", false);
		} else {
			wrapper.find(element + ' input[type="checkbox"]').attr("disabled", false);
		}
	}

	/**
	 * Hide Header.
	 * 
	 * @since 3.3.0
	 * 
	 * @param {Obj} obj
	 */
	hideHeader(obj) {
		const wrapper = obj.closest('.widget-content');
		if (obj.is(':checked')) {
			wrapper.find('.pp_hide_cover, .pp_hide_title, .pp_hide_description, .pp_hide_subscribe').hide();
		} else {
			wrapper.find('.pp_hide_cover, .pp_hide_title, .pp_hide_description, .pp_hide_subscribe').show();
		}
	}

	/**
	 * Change in podcast fech method.
	 * 
	 * @since 3.3.0
	 * 
	 * @param {Obj} obj
	 */
	displayStyle(obj) {
		const style = obj.val();
		const wrapper = obj.closest('.widget-content');
		const aspectRatio = wrapper.find('.podcast-player-pp-aspect-ratio');
		const excerptSupport = ['lv1', 'gv1', ''];
		const thumbSupport = ['lv1', 'lv2', 'gv1', 'gv2'];
		const gridSupport = ['gv1', 'gv2'];
		const txtColorSupport = ['lv1', 'lv2', 'lv3', 'gv1'];
		const teaserText = wrapper.find('.podcast-player-pp-teaser-text').val();
		const hasExcerpt = '' === teaserText ? true : false;
		wrapper.find('.pp_header_default').toggle(!style || 'legacy' === style || 'modern' === style);
		wrapper.find('.pp_list_default').toggle(!style || 'legacy' === style || 'modern' === style);
		wrapper.find('.pp_teaser_text').toggle(excerptSupport.includes(style));
		wrapper.find('.pp_excerpt_length').toggle(excerptSupport.includes(style) && hasExcerpt);
		wrapper.find('.pp_excerpt_unit').toggle(excerptSupport.includes(style) && hasExcerpt);
		wrapper.find('.pp_grid_columns').toggle(gridSupport.includes(style));
		wrapper.find('.pp_txtcolor').toggle(txtColorSupport.includes(style));
		wrapper.find('.pp_crop_method').toggle(thumbSupport.includes(style) && !! aspectRatio.val());
		wrapper.find('.pp_aspect_ratio').toggle(thumbSupport.includes(style));
	}

	/**
	 * Post episodes fetch.
	 * 
	 * @since 3.3.0
	 * 
	 * @param {Obj} obj
	 */
	postFetch(obj) {
		// Fetch episodes list for episode filter option.
		this.fetch(obj.closest('.widget-content'), 'post');
	}

	/**
	 * Feed episodes fetch.
	 * 
	 * @since 3.3.0
	 * 
	 * @param {Obj} obj
	 * @param {bool} isReset
	 */
	feedFetch(obj, isReset = true) {
		// Fetch episodes list for episode filter option.
		this.fetch(obj.closest('.widget-content'), 'feed', isReset);
	}

	/**
	 * Run Fetch Method.
	 * 
	 * @since 3.3.0
	 * 
	 * @param {obj} wrapper Widget wrapper element.
	 * @param {string} fetchMethod Podcast fetch method.
	 * @param {bool} isReset Reset all lists.
	 */
	fetch(wrapper, fetchMethod, isReset = true) {
		if ('feed' === fetchMethod || 'post' === fetchMethod) {
			clearTimeout(vars.ajaxtimeout);
			vars.ajaxtimeout = setTimeout( () => {
				new FetchFilters(wrapper, fetchMethod, isReset);
			}, 500 );
		}
	}

	/**
	 * Toggle Menu Items.
	 * 
	 * @since 5.4.0
	 * 
	 * @param {Obj} obj
	 */
	toggleMenuItems(obj) {
		const val = obj.val();
		const mmi = obj.closest('.podcast_menu').next('.main_menu_items');
		if (!val) {
			mmi.hide();
		}
	}

	/**
	 * Toggle Menu Items.
	 * 
	 * @since 5.4.0
	 * 
	 * @param {Obj} obj
	 */
	toggleDepricatedSub(obj) {
		const val  = obj.val();
		const elem = obj.closest('.main_menu_items').siblings('.pp_apple_sub, .pp_google_sub, .pp_spotify_sub');
		if (val > 0) {
			elem.hide();
		} else {
			elem.show();
		}
	}
}

export default ChangeDetect;
