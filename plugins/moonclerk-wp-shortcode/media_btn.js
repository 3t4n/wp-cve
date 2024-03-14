/**
 * This has not yet been programed but is in place if feature is wanted in the future with API integration.
 */
console.log('online moonclerk');

jQuery(function($) {
  $(document).ready(function(){
    $('#insert-moonclerk-media').click(open_media_window);
  });

  function open_media_window() {
    wp.media.editor.insert('[moonclerk id=""]');
  }
});