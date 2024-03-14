jQuery(document).ready(function() {
	if(team_settings.team_nav_style=="bullets")
	{
		jQuery("#team-carousel").owlCarousel({
			//navigation : true, // Show next and prev buttons
			autoplay: true,
			autoplayTimeout: team_settings.team_animation_speed,
			autoplayHoverPause: true,
			smartSpeed: team_settings.team_smooth_speed,

			loop:true, // loop is true up to 1199px screen.
			nav:false, // is true across all sizes
			margin:0, // margin 10px till 960 breakpoint

			responsiveClass:true, // Optional helper class. Add 'owl-reponsive-' + 'breakpoint' class to main element.
			//items: 5,
			dots: true,
			navText: ["<i class='fa fa-angle-left'></i>","<i class='fa fa-angle-right'></i>"],
			responsive:{
				100:{ items:1 },
				480:{ items:1 },
				768:{ items:2 },
				1000:{ items:4 }
			}
		});
	}
	else if(team_settings.team_nav_style=="navigation")
	{
	jQuery("#team-carousel").owlCarousel({
			//navigation : true, // Show next and prev buttons
			autoplay: true,
			autoplayTimeout: team_settings.team_animation_speed,
			autoplayHoverPause: true,
			smartSpeed: team_settings.team_smooth_speed,

			loop:true, // loop is true up to 1199px screen.
			nav:true, // is true across all sizes
			margin:0, // margin 10px till 960 breakpoint

			responsiveClass:true, // Optional helper class. Add 'owl-reponsive-' + 'breakpoint' class to main element.
			//items: 5,
			dots: false,
			navText: ["<i class='fa fa-angle-left'></i>","<i class='fa fa-angle-right'></i>"],
			responsive:{
				100:{ items:1 },
				480:{ items:1 },
				768:{ items:2 },
				1000:{ items:4 }
			}
		});
	}
	else
	{
		jQuery("#team-carousel").owlCarousel({
			//navigation : true, // Show next and prev buttons
			autoplay: true,
			autoplayTimeout: team_settings.team_animation_speed,
			autoplayHoverPause: true,
			smartSpeed: team_settings.team_smooth_speed,

			loop:true, // loop is true up to 1199px screen.
			nav:true, // is true across all sizes
			margin:0, // margin 10px till 960 breakpoint

			responsiveClass:true, // Optional helper class. Add 'owl-reponsive-' + 'breakpoint' class to main element.
			//items: 5,
			dots: true,
			navText: ["<i class='fa fa-angle-left'></i>","<i class='fa fa-angle-right'></i>"],
			responsive:{
				100:{ items:1 },
				480:{ items:1 },
				768:{ items:2 },
				1000:{ items:4 }
			}
		});
	}




});
