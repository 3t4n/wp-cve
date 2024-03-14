(function( $ ) {
	'use strict';

	/**
	 * All of the code for your public-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */

	 jQuery(document).ready(function($) {

	 	/*
	 	var theme_message_div = document.createElement('div');
	theme_message_div.setAttribute("id", "show_message");
	document.getElementsByTagName('body')[0].appendChild(theme_message_div);

	function show_toast_message(message_value) {
	  var x = document.getElementById("show_message");
	  x.innerHTML = message_value;
	  x.className = "show";
	  setTimeout(function(){ x.className = x.className.replace("show", ""); }, 3000);
	}

  window.onload = function() {
    document.addEventListener("contextmenu", function(e){
      e.preventDefault();
      show_toast_message('Mouse Right Click Disable');
    }, false);
    document.addEventListener("keydown", function(e) {
    //document.onkeydown = function(e) {
      // "I" key
      if (e.ctrlKey && e.shiftKey && e.keyCode == 73) {
        disabledEvent(e);
        show_toast_message('Inspect Element Disable');
      }
      // "J" key
      if (e.ctrlKey && e.shiftKey && e.keyCode == 74) {
        disabledEvent(e);
        show_toast_message('J key Disable');

      }
      // "S" key + macOS
      if (e.keyCode == 83 && (navigator.platform.match("Mac") ? e.metaKey : e.ctrlKey)) {
        disabledEvent(e);
        show_toast_message('Save Disable');

      }
      // "U" key
      if (e.ctrlKey && e.keyCode == 85) {
        disabledEvent(e);
        show_toast_message('Sorce Code Disable');

      }
      // "F12" key
      if (event.keyCode == 123) {
        disabledEvent(e);
        show_toast_message('F12 key Disable');
      }
       // "C" key
      if (event.keyCode == 67) {
        disabledEvent(e);
        show_toast_message('Copy Disable');

      }

    }, false);
    function disabledEvent(e){
      if (e.stopPropagation){
        e.stopPropagation();
      } else if (window.event){
        window.event.cancelBubble = true;
      }
      e.preventDefault();
      return false;
    }
  }; 
  */

	 });


})( jQuery );
