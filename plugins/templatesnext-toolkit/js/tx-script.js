jQuery(document).ready(function ($) {
	
		"use strict";
		
	var $window = jQuery(window),
		body = jQuery('body'),
		//windowheight = page.getViewportHeight(),
		sitewidth = $('.site').width(),
		maxwidth = $('.site-main').width(),		
		windowheight = $window.height(),
		pageheight = $( document ).height(),		
		windowwidth = $window.width();		
	
	
	//Testimonials carousel
	$('.tx-testimonials').each(function () {
		$(this).owlCarousel({
			autoPlay : 8000,
			stopOnHover : true,
			//navigation:true,
			paginationSpeed : 1000,
			goToFirstSpeed : 2000,
			singleItem : true,
			autoHeight : false,
			//navigationText:	["<i class=\"fa fa-angle-left\"></i>","<i class=\"fa fa-angle-right\"></i>"],
			//theme: "tx-custom-slider",
			addClassActive: true
		});
	});
	
	
	//blog and portfolio carousel
	$('.tx-carousel').each(function () {
	
		var _this = $('.tx-carousel');
		var car_columns = _this.data('columns');
			
		$(this).owlCarousel({
			items : car_columns,
			stopOnHover : true,
			paginationSpeed : 1000,
			navigation : true,
			goToFirstSpeed : 2000,
			singleItem : false,
			autoHeight : true,
			navigationText: ['<span class="genericon genericon-leftarrow"></span>','<span class="genericon genericon-rightarrow"></span>'],
			addClassActive: true,
			theme : "tx-owl-theme"
		});
	});
	
	
	//Products carousel
	$('.tx-prod-carousel').each(function () {
	
		var _this = $(this);
		var car_columns = _this.data('columns');
			
		$(this).children('div').children('ul').owlCarousel({
			items : car_columns,
			stopOnHover : true,
			//navigation:true,
			paginationSpeed : 1000,
			navigation : true,
			goToFirstSpeed : 2000,
			singleItem : false,
			autoHeight : true,
			//navigationText:	["<i class=\"fa fa-angle-left\"></i>","<i class=\"fa fa-angle-right\"></i>"],
			//theme: "tx-custom-slider",
			navigationText: ['<span class="genericon genericon-leftarrow"></span>','<span class="genericon genericon-rightarrow"></span>'],
			addClassActive: true,
			theme : "tx-owl-theme"
		});
	});	
	
	//Related Product
	$('.related.products').each(function () {
	
		var _this = $(this);
		var car_columns = _this.data('columns');
		
		car_columns = 4;
			
		$(this).children('ul').owlCarousel({
			items : car_columns,
			stopOnHover : true,
			//navigation:true,
			paginationSpeed : 1000,
			navigation : true,
			goToFirstSpeed : 2000,
			singleItem : false,
			autoHeight : true,
			//navigationText:	["<i class=\"fa fa-angle-left\"></i>","<i class=\"fa fa-angle-right\"></i>"],
			//theme: "tx-custom-slider",
			navigationText: ['<span class="genericon genericon-leftarrow"></span>','<span class="genericon genericon-rightarrow"></span>'],
			addClassActive: true,
			theme : "tx-owl-theme"
		});
	});
	
	
	$('.tx-slider').each(function () {
		
		var _this = $(this);
		var slider_delay = _this.data('delay');
		var slider_transition = _this.data('transition');
		
		if( slider_transition == 'slide' )
		{
			$(this).owlCarousel({
				autoPlay : slider_delay,
				stopOnHover : true,
				navigation: true,
				paginationSpeed : 1000,
				goToFirstSpeed : 2000,
				singleItem : true,
				autoHeight : true,
				navigationText: ['<span class="genericon genericon-rightarrow"></span>','<span class="genericon genericon-leftarrow"></span>'],
				addClassActive: true,
				theme : "tx-owl-theme",
				pagination : true	
			});
		} else
		{
			$(this).owlCarousel({
				autoPlay : slider_delay,
				stopOnHover : true,
				navigation: true,
				paginationSpeed : 1000,
				goToFirstSpeed : 2000,
				singleItem : true,
				autoHeight : true,
				navigationText: ['<span class="genericon genericon-rightarrow"></span>','<span class="genericon genericon-leftarrow"></span>'],
				addClassActive: true,
				theme : "tx-owl-theme",
				transitionStyle : slider_transition,
				pagination : true	
			});			
		}
				

	});			
		
			
	// colorboxpopup
	$('.tx-colorbox').each(function () {
		$(this).colorbox();
	});
	
	// blog area masonry
	//if ( $('.tx-post-row').length > 0 )
	
	//$(window).load(function()
	$(window).on('load', function () {	
		$('.tx-masonry').each(function () {
			$(this).masonry({});
		});
	});	
	
	/*
	$('.tx-blog').each(function () {
		
		console.log ('maso');
		
		var _this = $(this);
		var container_3 = document.querySelector('.tx-blog');
		var msnry_3 = new Masonry( container_3, {
		  //itemSelector: '.widget'
		});	
	});
	*/
	
	
	/////////////////////////////////////////////
	// Forcing Wide
	/////////////////////////////////////////////	

	$.fn.widify = function() {
		
		this.each( function() {
			var _this = $(this);
			var fwheight = $(this).children('div').outerHeight();
			var extrawidth = (sitewidth-maxwidth)/2+32;
			
			
			if(sitewidth >= 1200)	
			{
				_this.wrapInner( "<div class='tx-fullwidthinner'></div>" );

				_this.css({"overflow":"visible"});
				_this.children('.tx-fullwidthinner').css({"width":sitewidth+"px","position":"relative","margin-left":"-"+extrawidth+"px","overflow":"hidden"});
				
				//console.log ("yo max width : "+maxwidth+" sitewidth : "+sitewidth+" left: "+extrawidth);				
				
			}
		

			$(window).resize(function() {
				//console.log("resized : "+$('.site').width()+",  Site width : "+sitewidth+", max width : "+maxwidth);
				maxwidth = $('.site-main').width();
				sitewidth = $('.site').width();
				extrawidth = (sitewidth-maxwidth)/2+32;
				
				if(sitewidth > 1200) {
					
					if(!_this.children('div').hasClass('tx-fullwidthinner'))
					{
						_this.wrapInner( "<div style='position: relative; overflow: hidden;' class='tx-fullwidthinner'></div>" );
						//console.log("added");
					}					
					
					_this.css({"overflow":"visible"});
					_this.children('.tx-fullwidthinner').css({"width":sitewidth+"px","position":"relative","margin-left":"-"+extrawidth+"px"});				
				} else
				{
					
					if(_this.children('div').hasClass('tx-fullwidthinner'))
					{
						_this.children('.tx-fullwidthinner').children().unwrap();
					}	
					_this.css({"height":"auto","overflow":"hidden"});
					//console.log("should be here");		
				}
				
			});				
		
		});	
    };

	// forcing wide
	$('.tx-fullwidthrow').each(function () {
		//if( $('body.tx-boxed').length < 1 && $('.has-left-sidebar').length < 1 && $('.has-right-sidebar').length < 1 )
		if( $('.has-left-sidebar').length < 1 && $('.has-right-sidebar').length < 1 )
		{
			$(this).widify();
		}		
	});	
	
	if ( $('.other-slider').length > 0 )
	{	
		var slider_parallax = $('.other-slider').children('.tx-slider').data('parallax');
		var slidetop2 = parseInt($('.other-slider').offset().top);
		
		if( $( window ).width() > 999 && slider_parallax == "yes" )
		{	
			$(window).scroll(function(){
				var newvalue2 = parseInt($(this).scrollTop()*0.70)-100;
				
				if ($(this).scrollTop() > slidetop2)
				{
					$('.other-slider img').css('margin-top', newvalue2+'px');
				}
				
				if ($(this).scrollTop() <= slidetop2)
				{
					var slideheight2 = $('.other-slider .active img').height();
					$('.other-slider img').css('margin-top', 0+'px');
					$('.other-slider .owl-wrapper-outer').css('max-height', slideheight2+'px');
				}		
				//console.log('margin-top : '+newvalue+'px, ' + 'SlideTop : ' +slidetop+'px, ' + 'Scrolltop : ' +$(this).scrollTop()+'px');
			});
		}		
	}
	

	//blog and portfolio carousel
	$('.tx-animate').each(function () {
	
		var _this = $(this);
		var animation = _this.data('animation');
		var duration = _this.data('animation-duration');
		var delay = _this.data('animation-delay');
		
		duration = duration+'s';
		delay = delay+'s'
		
		_this.on('inview', function(event, isInView) {
			if (isInView) {
				_this.css({'visibility': 'visible', 'animation-duration': duration, 'animation-delay': delay}).addClass('animated '+animation);
			} else {
			// element has gone out of viewport
			}
		});
	});	
	
	
	if ( $('.tx-vslider').length > 0 )
	{
		var header_height = $('.tx-vslider').data('vslider-height');
		var header_reduct = $('.tx-vslider').data('vslider-reduct');
		
		var winheight = $( window ).height();
		var winwidth = $( window ).width();
		
		if( winwidth > 1200 )
		{
			$('.tx-vslider').css( "height", ((winheight-header_reduct)/100)*header_height );
		} else
		{
			$('.tx-vslider').css( "height", winheight/2);
		}
				
	}	


	// progress/skill bar
	$('.prograss-container').each(function (index) {
		var _this = $(this);

		var bar_percent = _this.data("progress-percent")+'%';

		_this.children('.pbar-outer').children('.pbar-text').children('.bpercent').html(bar_percent);
		
		_this.one('inview', function () {
			setTimeout( function () {
				_this.children('.pbar-outer').children('.pbar-inner').width(bar_percent);
   			}, (Math.floor((Math.random() * 400) + 40)));
		});
		
	});

});

