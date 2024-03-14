jQuery(document).ready(function($){
/*Accordion Settings on settings page*/
	var icons = {
		header: 'ui-icon-plus',
		activeHeader: 'ui-icon-minus'
	};
	$( '#accordion' ).accordion({
		collapsible: true,
		active: false,
		icons: icons,
		heightStyle: 'content',
	});
});
