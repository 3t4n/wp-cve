jQuery(document).ready(function($){

	var productsTable = $('#xoo-wl-products-table').DataTable({
		"order": [],
		"columnDefs": [ {
			"targets"  : 'no-sort',
	      	"orderable": false,
	    }]
	});


	var usersTable = $('#xoo-wl-users-table').DataTable({
		"order": [
			[0, 'desc']
		],
		"columnDefs": [ {
			"targets"  : 'no-sort',
	      	"orderable": false,
	    }]
	});

	var historyTable = $('#xoo-wl-history-table').DataTable({
		"order": [
			[0, 'desc']
		],
		"columnDefs": [ {
			"targets"  : 'no-sort',
	      	"orderable": false,
	    }]
	});


	$('body').on('click', '.xoo-wl-remove-row', function(e){
		e.preventDefault();

		var remove 		= $(this).parents('table').attr('id') === "xoo-wl-users-table" ? 'user' : 'product',
			$tr 		= $(this).parents('tr'),
			trNotice 	= new TableRowNotice( $tr ),
			dataTable 	= remove === 'user' ? usersTable : productsTable,
			rowID 		= null,
			productID 	= null;

		if( remove === 'product' ){
			var confirmUser = confirm( "Are you sure? This is irreversible" );
			if( !confirmUser ) return;
			productID 	= $tr.attr('data-product_id');
		}
		else{
			rowID = $tr.attr('data-row_id');
			productID 	= $tr.parents('table').attr('data-product_id');
		}


		trNotice.addNotice( xoo_wl_admin_table_localize.strings.deleting );

		$.ajax({
			url: xoo_wl_admin_table_localize.adminurl,
			type: 'POST',
			data: {
				'action': 'xoo_wl_table_remove_row',
				'rowID': rowID,
				'productID': productID,
				'remove': remove
			},
			success: function(response){

				if( response.notice ){
					trNotice.addNotice( response.notice );
				}

				setTimeout(function(){
					if( !response.error ){
						dataTable.row( $tr ).remove().draw();
					}
					else{
						$tr.html( trNotice.$trClone );
					}
				}, response.notice ? 5000 : 0 );
				

				if( response.count ){
					$('.xoo-wl-ut-ucount span').html(response.count['rowsCount']);
					$('.xoo-wl-ut-qcount span').html(response.count['totalQuantity']);  
				}
			}
		});
	})



	function TableRowNotice( $tr ){

		this.$tr 		= $tr;
		this.$trClone 	= $tr.html();
		this.colspan 	= $tr.parents('table').find('th').length;

		this.addNotice = function( notice ){
			this.$tr.html( '<td class="xoo-wl-tr-notice" colspan="'+this.colspan+'">'+ notice +'</td>' );
		}

		this.setToDefault = function(){
			this.$tr.html( $trClone );
		}

		this.afterNotice = function( callback, noticeTime ){
			setTimeout( callback, noticeTime );
		}
	}


	//Send Email
	$( 'body' ).on( 'click', '.xoo-wl-bis-btn', function(){

		var formData = {
			'action': 'xoo_wl_table_send_email'
		};

		var $tr 		= $(this).parents('tr'),
			trNotice 	= new TableRowNotice( $tr ),
			dataTable 	= '';

		if( $tr.attr('data-row_id') ){
			formData['rowID'] 	= $tr.attr('data-row_id');
			dataTable 			= usersTable;
		}

		if( $tr.attr('data-product_id') ){
			formData['productID'] 	= $tr.attr('data-product_id');
			dataTable 				= productsTable;
		}

		trNotice.addNotice(xoo_wl_admin_table_localize.strings.sending);

		$.ajax({
			url: xoo_wl_admin_table_localize.adminurl,
			type: 'POST',
			data: formData,
			success: function(response){

				if( response.notice ){
					trNotice.addNotice(response.notice);
				}

				setTimeout( function(){
					if( response.delete_row ){
						console.log($tr);
						dataTable.row( $tr ).remove().draw();
					}
					else{
						$tr.html( trNotice.$trClone );
						if( response.sent_count && $tr.find( '.xoo-wl-sent-count' ) ){
							$tr.find('.xoo-wl-sent-count').html( '( '+ response.sent_count +' )' );
						}
					}
				}, response.notice ? 10000 : 0 )
				

				if( response.count ){
					$('.xoo-wl-ut-ucount span').html(response.count['rowsCount']);
					$('.xoo-wl-ut-qcount span').html(response.count['totalQuantity']);  
				}
			}
		});
	} )



	function showTableNotice( $notice, timeout = 5000 ){

		var $noticeCont = $('.xoo-wl-notices');

		$noticeCont.show().html($notice);

		setTimeout(function(){
			$noticeCont.hide();
		}, timeout );
	}

})