// PORTFOLIO FILTERING - ISOTOPE
//**********************************
jQuery.noConflict()(function($){
"use strict";

	var mobileHover = function () {
		$('.st_sf_strange_portfolio_item').on('touchstart', function () {
			$(this).trigger('hover');
		}).on('touchend', function () {
			$(this).trigger('hover');
		});
	};
	mobileHover();
	var $container = $('.st_sf_port_container');
	if($container.length) {
		//$('.st_sf_portfolio_page_holder').css('min-height',$(window).height())
		$container.waitForImages(function() {
			
			// initialize isotope
			$container.isotope({
			  itemSelector : '.st_sf_strange_portfolio_item',
			  layoutMode : 'masonry',
			});
			
			$('#filters li:first-child').addClass('current-cat');
			// filter items when filter link is clicked
			$('#filters a').click(function(){
			  var selector = $(this).attr('data-filter');
			  $container.isotope({ filter: selector });
			  $(this).parent('li').addClass('current-cat').siblings().removeClass('current-cat');
			  
			  return false;
			});
			
			
			
		},null,true);
	
	}
	
var $containerr = $('.st_sf_posts_ul.st_sf_ul_will_be_masonry');
	if($containerr.length) {
		//$('.st_sf_portfolio_page_holder').css('min-height',$(window).height())
		$containerr.waitForImages(function() {
			
			// initialize isotope
			$containerr.isotope({
			  itemSelector : '.st_sf_format_will_be_masonry',
			  layoutMode : 'masonry',
			});
			
		},null,true);
	
	}


	
});
	
	
	


// Load More Masorny PORTFOLIO
//**********************************

jQuery.noConflict()(function($){
"use strict";

	$( "#load_more_port_masorny_posts" ).each(function() {
		$(this).click(function(e) {
				var tempurl = st_sf_theme_plugin.theme_url;
				var url = tempurl+'/framework/ajax-portfolio.php';
				var offset = $( "#load_more_port_masorny_posts" ).attr('data-offset');
				var load_posts_count = $( "#load_more_port_masorny_posts" ).attr('data-load-posts_count');
				var layout_mode = $( "#load_more_port_masorny_posts" ).attr('data-layout-mode');
				var column_count = '123';
				var tag = $( "#load_more_port_masorny_posts" ).attr('data-tag');
				
				column_count = parseInt(column_count,10)
				offset = parseInt(offset,10)
				load_posts_count = parseInt(load_posts_count,10)
				
				var st_sf_mas_post_count = $('.st_sf_strange_portfolio_item').length;
				$('#load_more_port_masorny_posts:not(disabled)').html('<span style=""> <i class="fa fa-spinner fa-spin"></i></span>');
				$('#load_more_port_masorny_posts').addClass('do_not_hover');
				$.get
				  (
				  st_sf_theme_plugin.ajax_url,{'action': 'silconfolio_ajax_request', 'st_sf_modal' : offset.toString(), 'st_sf_post_count' : st_sf_mas_post_count.toString(), 'st_sf_tag': tag.toString(), 'st_sf_load_post_count':load_posts_count.toString(), 'st_sf_layout_mode':layout_mode.toString(), 'st_sf_column_count':column_count.toString()},function(result,status)
					{
						
						$(result.new_posts).imagesLoaded( function(){
						$('.st_sf_port_container').isotope( 'insert', $(result.new_posts) );
						$('.st_sf_strange_portfolio_item_holder').css('opacity',1);
						offset = $( "#load_more_port_masorny_posts" ).attr('data-offset',offset + load_posts_count);
						$('#st_sf_masorny_posts_per_page').html($('.st_sf_strange_portfolio_item').length);
						$('#load_more_port_masorny_posts').html(result.loading)
						$('#load_more_port_masorny_posts').removeClass('do_not_hover');
						if ( parseInt($('#st_sf_max_masorny_posts').html()) == $('.st_sf_strange_portfolio_item').length ){
							$('#load_more_port_masorny_posts').html('<span style="">THE END</span>');
							$('#load_more_port_masorny_posts').addClass('disabled');
							  	var quotes = new Array('Stop','Do not Click','Nothing Here','Hey?','What?','Are you Crazy?', 'OMG', 'Unbelievable','Get Lost!'),
								randno = quotes[Math.floor( Math.random() * quotes.length )];

							$('.st_sf_load_more.disabled').click(function(e){$('#load_more_port_masorny_posts:not(disabled)').html('<span style="">'+randno+'</span>');})
						};
						var target = $('html,body'); 
						target.animate({scrollTop: target.height()}, 1000);
						});
						
						
					},
				  "json"
				 );
			e.preventDefault();
		}); 
	});
	
});

