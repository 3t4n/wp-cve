jQuery(document).ready(function($){




	var check_val = $('#qcopd-sortable-table').length;

    if(check_val > 0){

		$('#qcopd-sortable-table tbody').sortable({
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

	}


	$('#sld_shortcode_generator_meta').on('click', function(e){
		 $('#sld_shortcode_generator_meta').prop('disabled', true);
		$.post(
			ajaxurl,
			{
				action : 'show_qcsld_shortcodes'
				
			},
			function(data){
				 $('#sld_shortcode_generator_meta').prop('disabled', false);
				$('#wpwrap').append(data);
			}
		)
	});





});

