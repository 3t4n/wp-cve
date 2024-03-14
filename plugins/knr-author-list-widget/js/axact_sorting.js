// When the document is ready set up our sortable with it's inherant function(s)
jQuery(document).ready(function() {
	console.log(dirloc_var.dirloc);
		jQuery("#author-list").sortable(
			{
				handle : '.handle',
				update : function () {
					var order = jQuery('#author-list').sortable('serialize');
					
					jQuery.ajax({
		url: dirloc_var.ajaxurl,
		type: 'get',
		data: "action=axactAuthorList_CustomSortSave&"+order,
		success: function( result ) {
			jQuery('#statusInfo').text(result);

		}
})
				}
			}
		);
	}
);
