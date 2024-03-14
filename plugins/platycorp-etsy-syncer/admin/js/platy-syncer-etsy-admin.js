(function( $ ) {

	/**
	 * All of the code for your admin-facing JavaScript source
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

	$(function(){
		var openPlatyModal = new Event("open_platy_modal");
		$("#doaction").on("click", (e) =>{
			var selected = $("#bulk-action-selector-top").find(":selected").val();
			if(selected=="platy-syncer-etsy"){
				e.preventDefault();
				document.dispatchEvent(openPlatyModal);
			}
		});
		$("#doaction2").on("click", (e) =>{
			var selected = $("#bulk-action-selector-bottom").find(":selected").val();
			if(selected=="platy-syncer-etsy"){
				e.preventDefault();
				document.dispatchEvent(openPlatyModal);
			}
		});
	})

	


})( jQuery );

function platy_get_selected_posts(){
	var posts = jQuery('.check-column input[type=checkbox][name="post[]"]');
	ret = [];
	posts.each(function(){ if(jQuery(this).prop("checked")) ret.push(jQuery(this).val())});

	return ret;
}

function get_platy_form_data(){
	var posts = [];
	const queryString = window.location.search;
	const urlParams = new URLSearchParams(queryString);
	var ret = {};
	for(const entry of urlParams.entries()) {
		if(!/post\[\d+\]/.test(entry[0])){
			ret[entry[0]] = entry[1];
		}else{
			
		}
	}
	ret['plty-select-everything'] = jQuery('[name="plty-select-everything"').val() === "true" ? 1 : 0;
	ret['action'] = "platy-syncer-etsy";
	ret['action2'] = "-1";
	return ret;
}