jQuery( document ).ready( function( $ ) {    
    $( '.card_oracle_wizard_table.sortable tbody' ).sortable({
		items: 'tr',
		cursor: 'move',
		axis: 'y',
		scrollSensitivity: 40,
		forcePlaceholderSize: true,
		helper: 'clone',
		opacity: 0.65,
		placeholder: 'card-oracle-sortable-placeholder',
		stop: function() {
			var selectedData = new Array();

            $( '.row_position tr' ).each( function() {
				selectedData.push( $(this).attr( "id" ) );
            });

            updateOrder(selectedData);
        }
	});

	function updateOrder(data) {
        $.ajax({
            url: "?page=card-oracle-admin-menu&tab=wizard",
            type:'post',
            data:{position:data},
            success:function(result){
            	window.location.reload();
             }
		})
	}
});