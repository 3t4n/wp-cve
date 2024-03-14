//jQuery('body').addClass(settings.type);

jQuery(document).ready(function(){

	jQuery('a:not([href^=\\#])').click(function(){

		jQuery('body').addClass('nice_page_transition_'+settings.type);

		setTimeout(function(){ jQuery('body').removeClass('nice_page_transition_'+settings.type); }, 2000);

	});

	setTimeout(function(){ jQuery('body').removeClass(settings.type); }, 1000);

});