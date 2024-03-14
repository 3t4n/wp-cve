
jQuery(document).ready( function() {
	jQuery('#jl-wpt-tweaks-list-search > input').focus().keyup(function() {
		var search = jQuery(this).val();
		jQuery('#jl-wpt-empty-search-results').toggle(jQuery('#jl-wpt-tweaks-list tr.jl-wpt-group').hide().find('tr').hide().find('.jl-wpt-label,.jl-wpt-info').removeHighlight().filter(function() {
			return (search.length == 0 || jQuery(this).text().toLowerCase().indexOf(search.toLowerCase()) >= 0);
		}).highlight(search).parentsUntil('#jl-wpt-tweaks-list').filter('tr').show().length == 0);
	});
	jQuery('#jl-wpt-form').submit(function() {
		window.onbeforeunload = function() { };
	}).find('input, select').not('[hidden]').change(function() {
		window.onbeforeunload = function() { return jlWPTweaksL10n.confirmUnload; };
	});
});