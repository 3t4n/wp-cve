/**
 * @author: CodeFlavors [www.codeflavors.com]
 * @version: 1.0.1
 * @framework: jQuery
 */

(function($){
	
	$(document).ready(function(){
		
		if (typeof CFM_MENU_PARAMS == 'undefined') {
		    if( typeof console !== 'undefined' ){
		    	console.log( 'CodeFlavors floating menu warning: Floating menu params not defined. Script stopped.' );
		    }
			return;
		}
		
		var menu = $('#cfn_floating_menu').find('ul').first(),
			items = menu.children('li'),
			options = $.parseJSON(CFM_MENU_PARAMS);
		
		$('#cfn_floating_menu').css({'top':options.top_distance});
		
		if( 1 == options.animate ){
			$(window).scroll(function(e){
				var st = $(window).scrollTop();
				if( st > options.top_distance + 20 ){
					$('#cfn_floating_menu').animate({'top':st+options.top_distance},{'queue':false, 'duration':500});
				}else{
					$('#cfn_floating_menu').animate({'top':options.top_distance},{'queue':false, 'duration':500});	
				}
			});		
		}
		
		// show submenus
		$(menu).find('li').mouseenter(function(){
			$(this).children('ul').show(100);			
		}).mouseleave(function(){
			$(this).children('ul').hide(200);
		}).each( function(i, e){
			// for menus having children, add class has-children
			var submenu = $(e).children('ul.sub-menu');
			if( submenu.length > 0 ){
				$(this).addClass('has-children');
			}
		});
		
		
		
		// highlight current item from menu
		$(menu).find('li.current-menu-item').children('a').addClass('currentItem');
		
		// if first item is the trigger, show the menu only when hovering that item
		if( $(items[0]).attr('id') == 'cfm_menu_title_li' ){			
			var main = items.splice(0,1),
				menuWidth = menu.width();
			$(main).find('a').click(function(e){
				e.preventDefault();
			})
			
			$(items).hide();

			$(menu).mouseenter(function(){
				$(items).show(100);
				$(main).animate({'width':menuWidth}, 100).removeClass('closed');
				$(menu).css('width', menuWidth);
			}).mouseleave(function(){
				$(items).hide(200);
				$(main).css('width', 'auto').addClass('closed');
				$(menu).css('width', 'auto');
			})			
		}
	})
})(jQuery);