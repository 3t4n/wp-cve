// wp-admin scripts
//console.log( soulmatch_admin_data.options );

jQuery(function($){
	jQuery('.soulrepeater-delete').click(soulmatch.delete_repeater);
	jQuery('.soulrepeater-add').click(soulmatch.add_repeater);
});

var soulmatch = {
	delete_repeater : function(e){
		if ( confirm( soulmatch_admin_data.confirm ) ) {
			jQuery(this).parents('.postbox').remove();
			e.preventDefault();
		}
	},
	add_repeater : function(e){
		jQuery.get(
			ajaxurl,
			{
				'action' : 'add_repeater',
			},
			soulmatch.insert_repeater
		);
	},
	insert_repeater : function( response ) {
		if ( !response.success ){
			console.log( response.data );
			return;
		}
		jQuery('#post-body-content .meta-box-sortables').append( response.data );
		jQuery('#post-body-content .meta-box-sortables .postbox').last().find('.soulrepeater-delete').click(soulmatch.delete_repeater);
		jQuery('html, body').animate({
	    scrollTop: jQuery('#post-body-content .meta-box-sortables .postbox').last().offset().top
		}, 1000);
	},
}
