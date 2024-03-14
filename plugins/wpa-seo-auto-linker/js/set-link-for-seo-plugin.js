
jQuery(document).ready(function($) {
  
	$('#adminmenu > li').each(function() {
		var checker_niddle = 'seo';
		var not_us = 'WPA SEO Auto Linker';
		var liData = $(this).html();
        //console.log(liData);
		if (!liData.includes(not_us) && !liData.includes('themes') && liData.includes(checker_niddle))
        {
			//console.log($(this).text());
			$(this).find('ul').append('<li><a href="options-general.php?page=wpa-seo-auto-linker.php">WPA SEO Auto Linker</a></li>');
		}
	});
});
