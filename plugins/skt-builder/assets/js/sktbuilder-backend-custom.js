/**
 * Main script for wp-admin 
 * @param {window.jQuery} $
 */
(function($) {
    $(document).ready(function() {
        if (typeof(typenow) !== 'undefined' && typenow === 'page') {
            // Add button editor in WP admin
            var postId = jQuery('#post_ID').val();
            var url = 'post.php?post=' + postId + '&action=sktbuilder';
            var button = '<div class="cube-button-block"><div class="cube-button"><a href="' + url + '"><i class="cube"></i><span>' + sktbuilder_backend_custom.button_text + '</span></a></div></div>';
            $(button).insertAfter('div#titlediv');
        }
		
		if (typeof(typenow) !== 'undefined' && typenow === 'post') {
            // Add button editor in WP admin
            var postId = jQuery('#post_ID').val();
            var url = 'post.php?post=' + postId + '&action=sktbuilder';
            var button = '<div class="cube-button-block"><div class="cube-button"><a href="' + url + '"><i class="cube"></i><span>' + sktbuilder_backend_custom.button_text + '</span></a></div></div>';
            $(button).insertAfter('div#titlediv');
        }

        // page "sktbuilder-manage-libs"
        var form = $('#sktbuilder'),
            filters = form.find('.sktbuilder-filters');
        filters.hide();
        form.find('input:radio').change(function() {
            filters.slideUp('fast');
            switch ($(this).val()) {
                case 'url':
                    $('#sktbuilder-url').slideDown();
                    break;
                case 'file':
                    $('#sktbuilder-file').slideDown();
                    break;
            }
        });
        $('#sktbuilder-url').slideDown();
    });
})(window.jQuery);


document.addEventListener( 'DOMContentLoaded', function() {
	var dropdown = document.querySelector( '.post-type-page #split-page-title-action .dropdown' );

	if ( ! dropdown ) {
		return;
	}

	var postId = jQuery('#post_ID').val();
    var url = 'post-new.php?post_type=page&classic-editor&action=sktbuilder';

	dropdown.insertAdjacentHTML( 'afterbegin', '<a href="' + url + '">SKT Builder</a>' );
	
} );

document.addEventListener( 'DOMContentLoaded', function() {
	var dropdown = document.querySelector( '.post-type-post #split-page-title-action .dropdown' );

	if ( ! dropdown ) {
		return;
	}

	var postId = jQuery('#post_ID').val();
    var url = 'post-new.php?post_type=post&classic-editor&action=sktbuilder';

	dropdown.insertAdjacentHTML( 'afterbegin', '<a href="' + url + '">SKT Builder</a>' );
	
} );



jQuery(document).ready(function() {
	setTimeout(function() {
		var postId = jQuery('#post_ID').val();
		var url = 'post.php?post=' + postId + '&action=sktbuilder';
		var button = '<div class="cube-button-block"><div class="cube-button"><a><i class="cube"></i><span>' + sktbuilder_backend_custom.button_text + '</span></a></div></div>';
		
		jQuery(button).insertAfter('.edit-post-header-toolbar__left');
		
		jQuery(".cube-button").click(function() {
			window.location.href = url;
		});
	}, 500);
});



