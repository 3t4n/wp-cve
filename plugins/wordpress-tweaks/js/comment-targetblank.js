jQuery(document).ready(function() {
	jQuery('#comments').find('a[href^=http]').each(function() {
		if (this.href.indexOf(location.hostname) == -1) jQuery(this).attr('target', '_blank');
	});
});