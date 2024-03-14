jQuery(document).ready(function($) {
  // Remove links to Google fonts from the head
  $('link[href*="fonts.googleapis.com"]').remove();
	$('link[href*="fonts.gstatic.com"]').remove();

  // Remove @import statements from inline styles
  $('style:contains("@import url")').each(function() {
    $(this).text($(this).text().replace(/@import\s+url\(['"]?(?<url>[^'"]+)['"]?\);/g, function(match, url) {
      if (url.indexOf('fonts.googleapis.com') !== -1) {
        return '';
      }
      return match;
    }));
  });

  // Remove @font-face statements from inline styles
  $('style:contains("@font-face")').each(function() {
  // Replace the @font-face statements that contain fonts.gstatic.com with an empty string
  $(this).text($(this).text().replace(/@font-face\s*\{[^\}]*?src:\s*url\(['"]?(?<url>[^'"]+)['"]?\).*?\}/gs, function(match, url) {
    if (url.indexOf('fonts.gstatic.com') !== -1) {
      return '';
    }
    return match;
  }));
});

 });