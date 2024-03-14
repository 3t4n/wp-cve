class FeedEditor {

	/**
	 * Manage Feed editor options.
	 * 
	 * @since 3.3
	 * 
	 * @param {string} id Podcast player ID. 
	 */
	constructor() {

		// Define variables.
		this.data = window.ppjsAdminOpt || {};
		this.adminPage = jQuery('#pp-options-page');
		this.index = jQuery('.select-pp-feed-index').first();
		this.refresh = jQuery('.pp-feed-refresh');
		this.reset = jQuery('.pp-feed-reset');
		this.feedback = jQuery('.pp-toolkit-feedback').first();
		this.newFeedback = jQuery('#pp-action-feedback');
		this.managePodcastList = this.adminPage.find('.pp-podcasts-list');

		// Run methods.
		this.events();
	}

	// Event handling.
	events() {
		const _this = this;
		this.refresh.on('click', function() { this.ajaxFeedEditor('refresh') }.bind(this) );
		this.reset.on('click', function() { this.ajaxFeedEditor('reset') }.bind(this) );
		jQuery('.pp-feed-del').on('click', function(){
			const val = _this.index.val();
			if (!val) {
				_this.response(_this.data.messages.nourl, 'pp-error');
			} else {
				jQuery(this).next('.pp-toolkit-del-confirm').slideDown("fast");
				_this.response();
			}
		});
		jQuery('.pp-feed-cancel').on('click', function(){
			jQuery(this).parents('.pp-toolkit-del-confirm').hide();
		});
		this.managePodcastList.on('click', '.pp-podcast-refresh-btn', (e) => {
			const target = jQuery(e.currentTarget);
			this.feedRefreshDelete('refresh', target);
		});
		this.managePodcastList.on('click', '.pp-podcast-delete-btn', (e) => {
			const target = jQuery(e.currentTarget);
			this.feedRefreshDelete('reset', target);
		});
		this.newFeedback.on('click', '.pp-error-close', (e) => {
			this.newFeedback.removeClass('pp-error');
		})
	}

	/**
	 * Feed editor Ajax.
	 * 
	 * @since 2.0
	 * 
	 * @param string type
	 */
	ajaxFeedEditor(type) {
		const ajaxConfig = this.getAjaxConfig(type);
		this.response(this.data.messages.running, 'pp-running');
		if (!ajaxConfig) {
			this.response(this.data.messages.nourl, 'pp-error');
			return;
		}

		// Let's get next set of episodes.
		jQuery.ajax( {
			url: this.data.ajaxurl,
			data: ajaxConfig,
			type: 'POST',
			timeout: 60000,
			success: response => {
				const details = JSON.parse( response );
				if (!jQuery.isEmptyObject(details)) {
					if ('undefined' !== typeof details.error) {
						this.response(details.error, 'pp-error');
					} else if ('undefined' !== typeof details.message) {
						this.response(details.message, 'pp-success');
					}
				}
			},
			error: (jqXHR, textStatus, errorThrown) => {
				this.response(errorThrown, 'pp-error');
			}
		} );
	}

	/**
	 * Refresh or delete the podcast feed.
	 * 
	 * @since 6.6.0
	 * 
	 * @param string type
	 * @param object target element
	 */
	feedRefreshDelete(type, target) {
		const listItem = target.closest('.pp-podcast-list-item');
		const podcast  = listItem.data('podcast');
		const data = {
			action  : 'pp_feed_editor',
			security: this.data.security,
			atype   : type,
			feedUrl : podcast,
		}

		target.addClass('pp-wip');
		this.managePodcastList.find('.pp-toolkit-buttons').prop('disabled', true);

		// Let's get next set of episodes.
		jQuery.ajax( {
			url: this.data.ajaxurl,
			data: data,
			type: 'POST',
			timeout: 60000,
			success: response => {
				const details = JSON.parse( response );
				if (!jQuery.isEmptyObject(details)) {
					if ('undefined' !== typeof details.error) {
						this.newResponse(details.error, 'pp-error');
					} else if ('undefined' !== typeof details.message) {
						this.newResponse(details.message, 'pp-success');
						if ('reset' == type) {
							listItem.fadeOut(200, function() {
								jQuery(this).remove();
							});
						}
					}
					target.removeClass('pp-wip');
					setTimeout( () => {
						this.managePodcastList.find('.pp-toolkit-buttons').prop('disabled', false);
					}, 1500 );
				}
			},
			error: (jqXHR, textStatus, errorThrown) => {
				this.newResponse(errorThrown, 'pp-error');
				target.removeClass('pp-wip');
				this.managePodcastList.find('.pp-toolkit-buttons').prop('disabled', false);
			}
		} );
	}

	/**
	 * Get args for Ajax request.
	 * 
	 * @since 3.4.0
	 * 
	 * @param string type
	 */
	getAjaxConfig(type) {

		// Get feed key from dropdown list.
		let url = this.index.val();
		if ( url ) {
			return {
				action  : 'pp_feed_editor',
				security: this.data.security,
				atype   : type,
				feedUrl : url,
			};
		}

		return false;
	}

	/**
	 * Display request feedback.
	 * 
	 * @since 3.3.0
	 * 
	 * @param string message
	 * @param string type
	 */
	response(message = '', type = false) {
		this.feedback.removeClass('pp-error pp-success pp-running');
		if (false !== type) {
			this.feedback.addClass(type);
			this.feedback.find('.pp-feedback').text(message);
		}
	}

	/**
	 * Display action feedback.
	 * 
	 * @since 6.6.0
	 * 
	 * @param string message
	 * @param string type
	 */
	newResponse(message = '', type = false) {
		this.newFeedback.removeClass('pp-error pp-success pp-running');
		if (false !== type) {
			this.newFeedback.addClass(type);
			this.newFeedback.find('.pp-feedback').text(message);
		}

		// Remove classes after 2 seconds
		setTimeout(function() {
			this.newFeedback.removeClass('pp-success pp-running');
		}.bind(this), 1500);
	}
}

export default FeedEditor;
