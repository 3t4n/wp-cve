
/*
 * JavaScript for the Related plugin.
 */


/*
 * On selection, add the post to the list in the metabox.
 */
jQuery(document).ready(function($) {
	jQuery('.related-posts-select').on('change', function() {
		var select    = jQuery(this),
			container = jQuery('#related-posts'),
			id        = select.val(),
			title     = this.options[this.options.selectedIndex].text;

		if (id != "0") {
			if (jQuery('#related-post-' + id).length == 0) {
				container.prepend('<div class="related-post" id="related-post-' +
					id +
					'"><input type="hidden" name="related-posts[]" value="' +
					id +
					'"><span class="related-post-title">' +
					title +
					'</span><a href="#" onClick="related_delete( this ); return false;">Delete</a></div>'
				);
			}
		}
	});

	/* Delete option again on click event */
	jQuery('.related-post a').on('click', function() {
		related_delete( this );
		return false;
	});

	jQuery('#related-posts').sortable();

});


/*
 * Remove the selected post from the metabox.
 */
function related_delete( a_el ) {
	var div = jQuery( a_el ).parent();

	div.css('background-color', '#ff0000').fadeOut('normal', function() {
		div.remove();
	});
	return false;
}


/*
 * Select the right tab on the options page.
 */
jQuery(document).ready(function($) {
	jQuery( '.related-nav-tab-wrapper a' ).on('click', function() {

		jQuery( '.related_options' ).removeClass( 'active' );
		jQuery( '.related-nav-tab-wrapper a' ).removeClass( 'nav-tab-active' );

		var rel = jQuery( this ).attr('rel');
		jQuery( '.' + rel ).addClass( 'active' );
		jQuery( this ).addClass( 'nav-tab-active' );

		return false;
	});
});


/*
 * Use Chosen.js to limit the number of shown options in the select-box.
 */
jQuery(document).ready(function($) {
	jQuery('select.related-posts-select').chosen({
		no_results_text: "Nothing found...",
		allow_single_deselect: true,
		search_contains: true,
		width: "100%",
	});
});


/*
 * Settings Page uninstall.
 * Checking checkbox will enable the uninstall button.
 */
jQuery(document).ready(function($) {

	jQuery("input#related_uninstall_confirmed").prop("checked", false); // init

	jQuery("input#related_uninstall_confirmed").on('change', function() {
		var checked = jQuery( "input#related_uninstall_confirmed" ).prop('checked');
		if ( checked == true ) {
			jQuery("#related_uninstall").addClass( 'button-primary' );
			jQuery("#related_uninstall").removeAttr('disabled');
		} else {
			jQuery("#related_uninstall").removeClass( 'button-primary' );
			jQuery("#related_uninstall").attr('disabled', true);
		}
	});

});
