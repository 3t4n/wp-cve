jQuery(function($) {
	$('.htmlspecialcharacter_helplink').click(function() {
		$('#htmlhelperhelp').toggle(); return false;
	});
	$('.htmlspecialcharacter_morelink').click(function() {
		$('#commoncodes, #morehtmlspecialcharacters, #htmlhelper_more, #htmlhelper_less').toggle(); return false;
	});
});
