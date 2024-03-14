jQuery(document).ready(function() {
	jQuery('.entry-content, .post-content, .post').find('a[href^=http]').each(function() {
		if (this.href.indexOf(location.hostname) == -1) jQuery(this).attr('target', '_blank');
	});
});