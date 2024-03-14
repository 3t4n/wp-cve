(function ($) {
	const OldDashboard = {
		init() {
			$('body').on('click', '#mobile-menu-icon', this.hideSidebar);
			$('body').on('click', '.mobile-menu-icon-expend', this.showSidebar);

			// Remove legacy submenu events
			$( '#dokan-navigation .dokan-dashboard-menu li.has-submenu:not(.active)' ).off('mouseover, mouseout');

			this.setSidebarOpenCloseInitially();
		},

		setSidebarOpenCloseInitially() {
			let status = window.localStorage.getItem('dvd-sidebar-open-status');

			status =
				status === null ||
				status.toString().toLocaleLowerCase() === 'true';

			if (status) {
				this.showSidebar();
				return;
			}

			this.hideSidebar();
		},

		hideSidebar() {
			$('.dokan-dash-sidebar').animate(
				{
					width: '0px',
				},
				'fast'
			);

			$('.dokan-dashboard-content').animate(
				{
					marginLeft: '0px',
				},
				'fast'
			);

			window.localStorage.setItem('dvd-sidebar-open-status', false);
		},
		showSidebar() {
			$('.dokan-dash-sidebar').animate(
				{
					width: '252px',
				},
				'fast'
			);
			$('.dokan-dashboard-content').animate(
				{
					marginLeft: '252px',
				},
				'fast'
			);
			window.localStorage.setItem('dvd-sidebar-open-status', true);
		},
	};

	$(document).ready(function () {
		OldDashboard.init();
	});
})(jQuery);
