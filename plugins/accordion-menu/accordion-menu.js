jQuery(document).ready(function() {

	(function($){

		AccordionMenu = function()
		{
			var self = this;
			var menus = [];

			self.init = function(args)
			{
				for(var i = 0; i < args.length; i++) {
					// console.log(args[i]);
					menus.push(args[i]);
				}

				appendClasses();
				initMenus();
			}

			function appendClasses() {
				for(var i = 0; i < menus.length; i++) {
					//level 0
					$(menus[i] + ' ul').addClass('accordion-menu');
					var widgetTitle0 = jQuery(menus[i]).attr('id');
					$(menus[i] + ' ul').attr('id', 'accordion-menu-' + widgetTitle0 + '-level0');

					//level 1
					$(menus[i] + ' ul ul').addClass('accordion-menu');
					var widgetTitle1 = jQuery(menus[i]).attr('id');
					$(menus[i] + ' ul ul').attr('id', 'accordion-menu-' + widgetTitle1 + '-level1');
					
					//level 2
					$(menus[i] + ' ul ul ul').addClass('accordion-menu');
					var widgetTitle2 = jQuery(menus[i]).attr('id');
					$(menus[i] + ' ul ul ul').attr('id', 'accordion-menu-' + widgetTitle2 + '-level2');	
				}
			}

			function initMenus() {
				$('ul.accordion-menu ul').hide();
				$(".current_page_item ul:first").slideDown('normal');
				$(".current_page_item").parents("ul, li").map(function () { 
					$(this).slideDown('normal');
				});
				$('ul.accordion-menu li a').click(function() {
					var checkElement = $(this).next();
					var parent = this.parentNode.parentNode.id;
					if((checkElement.is('ul')) && (checkElement.is(':visible'))) {
						if($('#' + parent).hasClass('collapsible')) {
							$('#' + parent + ' ul:visible').slideUp('normal');
						}
						return false;
					}
					if((checkElement.is('ul')) && (!checkElement.is(':visible'))) {
						$('#' + parent + ' ul:visible').slideUp('normal');
						checkElement.slideDown('normal');
						return false;
					}
				});
			}

			return self;
		}

	})(jQuery);

	var args = [".widget_pages", ".widget_categories"];

	var AccordionMenu = new AccordionMenu();
	AccordionMenu.init(args);

});

