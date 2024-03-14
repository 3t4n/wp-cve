if (typeof ai_adsense_ad_names !== 'undefined') {

//jQuery(window).on ('load', function () {
window.addEventListener ('load', (event) => {
  setTimeout (function() {
//    var google_auto_placed = jQuery ('.google-auto-placed > ins');
//    google_auto_placed.before ('<section class=\"ai-debug-bar ai-debug-adsense ai-adsense-auto-ads\">' + ai_front.automatically_placed + '</section>');
    document.querySelectorAll ('.google-auto-placed > ins').forEach ((el, index) => {
      el.insertAdjacentHTML ('afterbegin',  '<section class=\"ai-debug-bar ai-debug-adsense ai-adsense-auto-ads\">' + ai_front.automatically_placed + '</section>');
    });

  }, 150);
});

}
