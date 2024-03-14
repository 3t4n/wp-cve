jQuery(document).ready(function($) { //if the DOM is ready

	'use strict';

	var solidTabs = {

		resize_timer: '',
		scroll_timer: '',
		default_responsiveness: 'stacked',
		animation_value: 'animation-bounce', //default animation value
		responsiveness_width: 767,

		init: function() {

			this.cacheDom();
			this.bindEvents();
			this.resizeSettings();
			this.checkResponsiveNess();
			this.activateTooltipster();

		},
		cacheDom: function() {

			this.$window = $(window);
			this.$document = $(document);
			this.$body = $('body');
			this.$solid_tabs = this.$body.find('.solid-tabs');
			this.$tab_main_container = $('.tab-main-container');
			this.$tab_marker = this.$tab_main_container.find('.tab-marker');
			this.$tab_marker_list = this.$tab_main_container.find('.tab-marker > li');
			this.$tab_marker_links = this.$tab_main_container.find('.tab-marker > li > a');
			this.$tab_content = this.$tab_main_container.find('.tab-content');
			this.$tab_content_div = this.$tab_main_container.find('.tab-content > div');
			this.$tab_pane = this.$tab_main_container.find('.tab-pane');
			this.$tab_marker_fc = this.$tab_main_container.find('.tab-marker > li:first-child');
			this.$tab_link = this.$body.find('.tab-link');
			this.$close_x = $('.close-x');
			this.$alert_btn = $('.alert-btn');
			this.$tab_design_16_markers = $('#solid-tabs .tab-design-16 .tab-marker');
			this.$tooltipster = this.$tab_main_container.find('.tooltipster');

		},
		bindEvents: function() {

			/* for tabs events */
			this.$window.on('resize', this.resizeSettings.bind(this));
			this.$window.on('scroll', this.hideTabsOnScroll.bind(this));
			this.$body.on('click', '.tab-marker > li', this.activeTabs);
			this.$body.on('click', '.solid-tabs .close-x', this.closeX);
			this.$body.on('click', '.solid-tabs .alert-btn', this.closeAlertBox);
			this.$body.on('click', '.tab-design-15 .tab-marker > li', this.cornerTabs);
			this.$body.on('click', '.tab-link', this.activateTabMarker);
			this.$body.on('mouseenter', 'div[data-responsiveness="stacked"] .tab-marker[data-trigger="hover"] > li > a, div[data-responsiveness="icon"] .tab-marker[data-trigger="hover"] > li > a', this.activeTabsHover);
			this.$body.on('click', '.tab-to-accordion .tab-marker > li > a', this.toggleActive);
			this.$body.on('touchstart', '.image-container, .tooltip-marker', function() {});

		},
		checkResponsiveNess: function() {

			var data_responsiveness = $(".solid-tabs[data-responsiveness]");

			if (data_responsiveness.length === 0) { //if no data-responsiveness exists, provide one

				this.$solid_tabs.attr('data-responsiveness', this.default_responsiveness);

			}

		},
		activateTooltipster: function() {

			if (this.$tooltipster.length > 0) {

				this.$tooltipster.tooltipster({

					animation: 'grow',
					delay: 150,
					distance: 8

				});

			}

		},
		activeTabs: function() { //for all tabs

			var _this = $(this);
			var parent_tab_marker = _this.parent('.tab-marker');
			var tab_marker_sibs = parent_tab_marker.siblings('.tab-marker');
			var tab_marker_li = tab_marker_sibs.children('li');

			tab_marker_li.removeClass('active');
		
		},
		activeTabsHover: function(e) {

			e.preventDefault();
			var _this = $(this);
			_this.tab('show');

		},
		activateTabMarker: function () { //for step by step 

			var _this = $(this);
			var tab_marker_links = $('.tab-marker > li > a');
			var href_value = _this.attr('href');

			$.each(tab_marker_links, function(index, element) {

				var el = $(element);

				if (el.attr('href') === href_value) {

					el.parent('li').addClass('active');
					el.parent('li').siblings('li').removeClass('active');

				}

			});


		},
		toggleActive: function() { //for tab-to-accordion

			var _this = $(this);

			_this.parent().addClass('active');
			_this.siblings('.tab-pane').toggle();

			_this.parent().siblings('li').removeClass('active');
			_this.parent().siblings('li').children('.tab-pane').hide();
		
		},
		cornerTabs: function() { //for corner tabs

			var _this = $(this);
			var parent_tab_marker = _this.parent('.tab-marker');

			_this.siblings('li').removeClass('active');
			parent_tab_marker.siblings('.tab-marker').children('li').removeClass('active').removeAttr('style');
	
			if (_this.hasClass('top-left-corner')) {

				_this.css('border-top-color','black');

			} else if (_this.hasClass('top-right-corner')) {

				_this.css('border-right-color','black');

			} else if (_this.hasClass('bottom-left-corner')) {

				_this.css('border-left-color','black');

			} else {

				_this.css('border-bottom-color','black');

			}

		},
		hideTabsOnScroll: function() { //for tab design 16 only

			var that = this;
			var hide_scroll_value = this.$tab_design_16_markers.attr('data-scroll-hide');

			clearTimeout(this.scroll_timer);

			if (this.$window.scrollTop() >= hide_scroll_value) {

				this.scroll_timer = setTimeout(function() {

					that.$tab_design_16_markers.fadeOut(500,'swing');

				}, 100);

			} else {

				this.$tab_design_16_markers.fadeIn(500,'swing');

			}

		},
		closeX: function() { //feed tabs

			var _this = $(this);
			_this.parents('.tab-pane').removeClass('active in');

		},
		closeAlertBox: function() { //for alert boxes

			var _this = $(this);
			_this.parents('.solid-tabs .alert-box').fadeOut();

		},
		resizeSettings: function() { //behavior when browser is resized

			var that = this;
			var window_w = window.innerWidth;

			clearTimeout(this.resize_timer); //clear the interval, timeout
			this.resize_timer = setTimeout(function() {

				that.toAccordion(window_w);

			}, 100); //delay executing transform accordion when window is resizing, A.K.A. throttle

		},
		toAccordion: function(window_w) {

			var that = this; //assign this to var that, context of this has changed

			var responsiveness_value = that.$solid_tabs.attr("data-responsiveness");
			var cloned_tab_content = $('.tab-content.cloned');

			if (window_w > that.responsiveness_width) { 

				cloned_tab_content.remove();

			}

			if (responsiveness_value === "accordion") { //if data-responsiveness value is accordion if there is transform to accordion

				that.$solid_tabs.addClass('tab-to-accordion') //if there is data-accordion, add this class

				if (window_w <= that.responsiveness_width) { //window size is less than this one, transform to accordion

					this.$solid_tabs.addClass('tab-to-accordion'); //add class tab-to-accordion, trigger
					this.appendTabMarkers();
					this.$tab_marker_links.attr('data-toggle','collapse'); //from tab, switch value to toggle to emulate accordion in bootstrap
					this.$tab_content.find('.tab-pane').removeClass('fade'); //remove fade class in tab-pane, if there is any

					
					if (cloned_tab_content.length === 0) {

						cloned_tab_content = this.$tab_content.clone().insertAfter('.tab-marker').addClass('cloned'); //clone tab-content
					
					}
					
					$.each(cloned_tab_content.find('.tab-pane'), function(index, element) {

						var tab_pane = $(element);
						var tab_pane_id = $(element).attr('id');
						var tab_marker_links = $('.tab-marker > li > a');

						$.each(tab_marker_links, function(index, element) {

							var el = $(element);

							if (el.siblings('.tab-pane').length === 0) {

								var href_value = el.attr('href'); //eg. #tab-1 
								var strip_hash = href_value.substring(1); //eg #tab-1, is stripped of '#' equals tab-1

								if (tab_pane_id === strip_hash) { //eg tab-1 === tab-1

									tab_pane.addClass('collapse');
									tab_pane.insertAfter(el);
									
								} 

							} 
							
						});

					});

				} else { //if window with is greater than (767, responsive_width that is set)

					cloned_tab_content.remove(); //remove cloned elements
					this.$solid_tabs.removeClass('tab-to-accordion'); //removeclass that triggers accordion responsiveness
					this.$tab_marker_links.siblings('.tab-pane').remove(); //remove .tab-panes copied inside tab-marker links
					this.$tab_marker_links.attr('data-toggle','tab'); //set data-toggle value to 'tab' again
					this.$tab_marker_links.children('.accordion-marker').remove();
					$('.tab-pane').css('height','auto'); //set the .tab-pane height again to auto

				}

			} else {

				this.$solid_tabs.removeClass('tab-to-accordion');
				cloned_tab_content.remove();

			}

		},
		appendTabMarkers: function() {

			if (this.$tab_marker_links.children('.accordion-marker').length === 0) {

				this.$tab_marker_links.append('<i class="fa fa-angle-down fa-fg accordion-marker">');

			}
			
		},
		removeClassRegEx: function(target_element,target_pattern) { //target pattern must be a regular expression

			var target = target_element;
			var classes = target.attr('class').split(" "); //attribute classes to array
			var pattern = target_pattern;
			var the_index = 0;

			for (the_index in classes) { //iterate each array and remove class based on the pattern match

				if (classes[the_index].match(pattern)) {

					target.removeClass(classes[the_index]);

				}

			}

		}

	}

	solidTabs.init();

});
