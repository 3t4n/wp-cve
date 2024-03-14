// frontend scripts
//console.log( soulmatch_data.options );

jQuery(function($){
	function SoulMatch_init() {
		$.each(soulmatch_data.options,function(i, value){
			$(value.selector).filter(':visible').matchHeight({
				byRow: '0' === value.byrow ? false : true,
			});
		});
	}
	jQuery( window ).resize( SoulMatch_init );
	SoulMatch_init();
});
