jQuery(document).ready(function ($) {
	$('a[rel="external nofollow"],a[rel="external"],a[rel="nofollow"],a[rel="nofollow external"]').click(function(){
		window.open( $(this).attr('href') );
		return false;
	});
});

jQuery(document).ready(function ($) {
    $('a').each(function(){
        this.href = this.href.replace('\/dontfollow', '');
    });
});
