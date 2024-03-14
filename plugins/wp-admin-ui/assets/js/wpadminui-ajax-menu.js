jQuery(document).ready(function($) {
    var stop = false;
    jQuery(".accordion-section h3").click(function(event) {
        if (stop) {
            event.stopImmediatePropagation();
            event.preventDefault();
            stop = false;
        }
    });
    jQuery( ".accordion-section" )
    .accordion({
        collapsible: true,
        active: false,
    });
    jQuery('.accordion-container').sortable({
    	items: '.accordion-section',
    	opacity: 0.6,
    	cursor: 'move',
    	//axis: 'y',
        handle: "h3",
        placeholder: "highlight",
        start: function (event, ui) {
            ui.item.toggleClass("highlight");
        },
        stop: function (event, ui) {
            ui.item.toggleClass("highlight");
            stop = true;
        },
    	update: function() {
    		var order = jQuery(this).sortable('serialize') + '&action=wpui_menu_update_order';
    		jQuery.post(ajaxurl, order, function(response){
    		});
    	}
   	});
});

jQuery(document).ready(function(){
    jQuery('#wpui-reset').on('click', function() {
        jQuery.ajax({
            method : 'POST',
            url : wpuiAjaxResetMenu.wpui_post_url,
            data : {
                action: 'wpui_menu_reset_order',
                _ajax_nonce: wpuiAjaxResetMenu.wpui_nonce,
            },
            success : function( data ) {
                window.location.reload(true);
            },
        });
    });
});

jQuery(document).ready(function(){
    jQuery('#wpui-reset').on('click', function() {
        jQuery(this).attr("disabled", "disabled");
        jQuery( '.spinner' ).css( "visibility", "visible" );
        jQuery( '.spinner' ).css( "float", "left" );
    });
});