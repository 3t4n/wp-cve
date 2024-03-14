( function( $ ) {

	$( document ).ready( function() {

		var customPostView = elementor.modules.controls.BaseData.extend( {
			onReady: function() {

				var self = this;

				$( self.el ).find( 'select' ).select2( {
					ajax: {
						url: cpConfig.ajaxurl,
						dataType: 'json',
						delay: 250,
						data: function( params ) {
							var query = {
								posts_per_page: 10,
								q: params.term,
								paged: params.page || 1,
								action: 'handler_custom_posts'
							};

							return query;
						},
						processResults: function (data, params) {
							params.page = params.page || 1;

							return {
								results: data.results,
								pagination: {
									more: data.more
								}
							};
						},
						cache: true
					},
					allowClear: true,
					minimumInputLength: 3,
					placeholder : 'Select post',
					templateSelection: function(state){
						var $state = state;

						if ( !isNaN( state.text.trim() ) ) {
							$state = $.ajax({
								global: false,
								type: "POST",
								url: cpConfig.ajaxurl,
								dataType: 'json',
								delay: 250,
								data: {
									post_id: state.text.trim(),
									action: 'handler_post_title'
								},
								async:false
							} ).responseText;
						} else {
							$state = state.text;
						}

						return $state;
					},
				} );

			},
		} );

		elementor.addControlView( 'custom_post', customPostView );
	} );
} )( jQuery );
