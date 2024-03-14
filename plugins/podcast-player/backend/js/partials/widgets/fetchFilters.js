class FetchFilters {

	/**
	 * Manage Feed editor options.
	 * 
	 * @since 3.3
	 * 
	 * @param {obj} wrapper Widget wrapper element.
	 * @param {string} fetchMethod Podcast fetch method.
	 * @param {bool} isReset Reset all data (for feeds).
	 */
	constructor(wrapper, fetchMethod, isReset) {

		// Define variables.
		this.adminData = window.ppjsAdmin || {};
		this.wrapper = wrapper;
		this.fetchMethod = fetchMethod;
		this.isReset = isReset;

		// Run methods.
		if ( this.adminData.ispremium ) {
			this.runFetchAction();
		}
	}

	/**
	 * Get appropriate data for Ajax calls.
	 * 
	 * @since 3.3.0
	 */
	runFetchAction() {
		const data = this.getAjaxData();

		if (false === data) {
			this.runFalseAction();
		} else {
			this.hideLists();
			this.makeAjaxRequest(data);
		}
	}

	/**
	 * Handle invalid requests and requests with error.
	 * 
	 * @since 3.3.0
	 * 
	 * @param {string} error
	 */
	runFalseAction(error) {
		this.hideLists();
		if (error) console.log(error);
	}

	/**
	 * Hide filter checkbox lists.
	 * 
	 * @since 3.3.0
	 */
	hideLists() {
		this.wrapper.find('.pp-episodes-list').hide();
		if ('feed' === this.fetchMethod && this.isReset) {
			this.wrapper.find('.pp-categories-list').hide();
			this.wrapper.find('.pp-seasons-list').hide();
		}
	}

	/**
	 * Get appropriate data for Ajax calls.
	 * 
	 * @since 3.3.0
	 */
	getAjaxData() {
		if ('feed' === this.fetchMethod) {
			return this.getFeedAjaxData();
		} else if ('post' === this.fetchMethod) {
			return this.getPostAjaxData();
		}
	}

	/**
	 * Get appropriate data for Feed specific Ajax calls.
	 * 
	 * @since 3.3.0
	 */
	getFeedAjaxData() {
		let feedUrl  = this.wrapper.find('.feed_url input').val();
		feedUrl = 'string' === typeof feedUrl ? feedUrl.trim() : false;
		if (!feedUrl) return false;
		const action   = 'pp_feed_data_list';
		const security = this.adminData.security;
		const getAll = this.isReset ? 'true' : 'false';
		const schecked = this.wrapper.find('.pp_slist-checklist input[type="checkbox"]:checked');
		const cchecked = this.wrapper.find('.pp_catlist-checklist input[type="checkbox"]:checked');
		let seasons = [];
		let categories = [];
		if ( ! this.isReset ) {
			jQuery.each( schecked, function() { seasons.push(jQuery(this).val()) } );
			jQuery.each( cchecked, function() { categories.push(jQuery(this).val()) } );
		}
		return { action, security, getAll, feedUrl, seasons, categories };
	}

	/**
	 * Get appropriate data for Post specific Ajax calls.
	 * 
	 * @since 3.3.0
	 */
	getPostAjaxData() {
		const action   = 'pp_post_episodes_list';
		const security = this.adminData.security;
		const postType = this.wrapper.find('select.podcast-player-pp-post-type').val();
		const taxonomy = this.wrapper.find('select.podcast-player-pp-taxonomy').val();
		const sortby   = this.wrapper.find('select.podcast-player-sortby').val();
		const filterby = this.wrapper.find('.filterby input').val();
		const tchecked = this.wrapper.find('.pp_terms-checklist input[type="checkbox"]:checked');
		let terms = [];
		jQuery.each( tchecked, function() { terms.push(jQuery(this).val()) } );
		return {action, security, postType, taxonomy, sortby, filterby, terms};
	}

	/**
	 * Make Ajax Request to server.
	 * 
	 * @since 3.3.0
	 */
	makeAjaxRequest(data) {
		const url     = this.adminData.ajaxurl;
		const type    = 'POST';
		const timeout = 10000;
		jQuery.ajax({
			url, data, type, timeout,
			success: response => {
				const details = JSON.parse( response );
				if (jQuery.isEmptyObject(details)) {
					this.runFalseAction('PP Error: Empty object received');
				} else {
					if (details.items) {
						this.createMarkup(details);
					} else if (details.error) {
						this.runFalseAction(details.error);
					}
				}
			},
			error: (jqXHR, testStatus, errorThrown) => {
				this.runFalseAction(errorThrown);
			}
		});
	}

	/**
	 * Create checkbox filter markup.
	 * 
	 * @since 3.3.0
	 *
	 * @param {Obj} details
	 */
	createMarkup(details) {
		if (details.items) {
			this.template('episode', 'elist', details.items);
			this.wrapper.find('.pp-episodes-list').show();
		}

		if (details.seasons) {
			this.template('season', 'slist', details.seasons);
			this.wrapper.find('.pp-seasons-list').show();
		}

		if (details.categories) {
			this.template('cat', 'catlist', details.categories);
			this.wrapper.find('.pp-categories-list').show();
		}
	}

	/**
	 * Create checkbox filter markup.
	 * 
	 * @since 3.3.0
	 * 
	 * @param {string} type
	 * @param {string} ltype
	 * @param {Obj} items
	 */
	template(type, ltype, items) {
		this.wrapper.find(`.d-${type} input[type="checkbox"]`).prop("checked", true);
		const container = this.wrapper.find(`.pp_${ltype}-checklist ul`);
		const mould = container.find(`li.d-${type}`).clone();
		container.empty().append(mould.clone());
		mould.removeClass(`d-${type}`).addClass(`pp-${type}s`);
		mould.find('input[type="checkbox"]').prop("checked", false).attr("disabled", true);
		jQuery.each( items, function(id, label) {
			const item = mould.clone();
			item.find('input[type="checkbox"]').val(id);
			item.find('.cblabel').html(label);
			container.append(item);
		} );
	}
}

export default FetchFilters;
