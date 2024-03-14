jQuery(function($) {

	$('#opd-sort-tbl tbody').sortable({
		axis: 'y',
		handle: '.column-order img',
		placeholder: 'ui-state-highlight',
		forcePlaceholderSize: true,
		update: function(event, ui) {
			var theOrder = $(this).sortable('toArray');

			var data = {
				action: 'sld_update_post_order',
				postType: $(this).attr('data-post-type'),
				order: theOrder
			};

			$.post(ajaxurl, data);
		}
	}).disableSelection();

	//Filter by Taxonomy
	$(".filter-sld-tax > a"). on("click", function(event){

        event.preventDefault();

        var filterName = $(this).attr("data-filter");

        if( filterName == "all" )
        {
            $("#opd-sort-tbl .tbl-body tr").show("slow");
        }
        else
        {
            $("#opd-sort-tbl .tbl-body tr").hide();
            $("#opd-sort-tbl .tbl-body tr."+filterName+"").show("slow");
        }

    });

});