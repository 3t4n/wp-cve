import props from './variables';

class PodcastHeader {

	/**
	 * Screen resize timeout.
	 */
	resizeTimeout = null;

	/**
	 * Manage podcast player header elements.
	 * 
	 * @since 1.3
	 * 
	 * @param {string} id Podcast player ID. 
	 */
	constructor(id) {

		// Define variables.
		this.id = id;
		this.podcast = props[id].podcast;
		this.instance = props[id].instance;

		// Display Main Subscription menu items.
		this.mainMenuItems();

		this.menuToggle = this.podcast.find('.pod-items__menu-open');
		this.infoToggle = this.podcast.find('.pod-launch__info');

		// Run methods.
		this.events();
	}

	// Display main menu items.
	mainMenuItems() {
		const count = this.podcast.attr('data-main-items');
		const launcher = this.podcast.find('.pod-content__launcher');
		const wrap = this.podcast.find('.pod-items__navi-menu');
		if (! count) {
			// Place this menu in the launcher.
			if (launcher.length) {
				launcher.prepend(wrap.clone());
			}
			return;
		}

		const menu  = wrap.find('#podcast-menu-' + this.instance);
		if (! menu.length) return;
		const items  = menu.find('.menu-item > a').slice(0, count);
		const mitems = items.clone();
		items.closest('.menu-item').hide();
		jQuery.each(
			mitems, function(index, item) {
				const $item = jQuery(item);
				const elems = $item.find('.subscribe-item');
				$item.addClass(elems.attr('class')).addClass('pp-badge');
				$item.html(elems.html());
			}
		);
		wrap.prepend(mitems);

		if (menu.find('.menu-item').length <= count) {
			menu.closest('.pod-items__menu').hide();
		}

		// Place this menu in the launcher.
		if (launcher.length) {
			launcher.prepend(wrap.clone());
		}
	}

	// Event handling.
	events() {

		this.menuToggle.on('click', function() {
			this.menuToggle.closest('.pod-items__menu').toggleClass('toggled-window');
			this.menuToggle.toggleClass('toggled-on').attr('aria-expanded', this.menuToggle.hasClass('toggled-on'));
		}.bind(this) );

		this.infoToggle.on('click', function() {
			this.podcast.toggleClass('header-toggle');
			this.infoToggle.toggleClass('toggled-on').attr('aria-expanded', this.menuToggle.hasClass('toggled-on'));
		}.bind(this) );
	}
}

export default PodcastHeader;
