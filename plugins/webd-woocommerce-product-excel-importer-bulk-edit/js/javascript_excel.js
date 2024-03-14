(function( $ ) {


	$('.excel_bulk_wrap_free #upload').attr('disabled','disabled');
    $(".excel_bulk_wrap_free #file").change(function () {
        var fileExtension = ['xls', 'xlsx'];
        if ($.inArray($(this).val().split('.').pop().toLowerCase(), fileExtension) == -1) {
            alert("Only format allowed: "+fileExtension.join(', '));	
			$(".excel_bulk_wrap_free input[type='submit']").attr('disabled','disabled');
        }else{
			$('#upload').removeAttr('disabled');
			$(".excel_bulk_wrap_free").find('form').submit();
		}
    });
	
	$(".excel_bulk_wrap_free #product_import").on("submit", function (e) {
		e.preventDefault();
				var data = new FormData();
				$.each($('#file')[0].files, function(i, file) {
					data.append('file', file);
				});	
				data.append('_wpnonce',$("#_wpnonce").val());
				data.append('importProducts',$("#importProducts").val() );
				url= window.location.href;
				$.ajax({
					url: window.location.href,
					data: data,
					cache: false,
					contentType: false,
					processData: false,
					type: 'POST',
					beforeSend: function() {	
						$("html, body").animate({ scrollTop: 0 }, "slow");
						$('.excel_bulk_wrap_free').addClass('loading');	
					},					
					success: function(response){
						$(".result").slideDown().html($(response).find(".result").html());
						$('.excel_bulk_wrap_free').removeClass('loading');	
						$("#product_import").fadeOut();
						
						excel_bulk_dragDrop();	
						excel_bulk_automatch_Columns();
						
						$(".excel_bulk_wrap_free #product_process").on('submit',function(e) {
							e.preventDefault();
							if($("input[name='post_title']").val() !='' ){
								$.ajax({
									url: $(this).attr('action'),
									data:  $(this).serialize(),
									type: 'POST',
									beforeSend: function() {									
										$("html, body").animate({ scrollTop: 0 }, "slow");
										$('.excel_bulk_wrap_free').addClass('loading');		
									},						
									success: function(response){
										$(".result").slideDown().html($(response).find(".result").html());
										//$("body").html(response);
										$('.excel_bulk_wrap_free').removeClass('loading');	
										$("form")[0].reset();
										$("#product_import").delay(5000).fadeIn();	
									}
								});	
							}else alert('Title Selection is Mandatory.');
						});	
			
					}
				});			
			});	
			//drag and drop
			function excel_bulk_dragDrop(){
				$('.excel_bulk_wrap_free .draggable').draggable({cancel:false});
				$( ".excel_bulk_wrap_free .droppable" ).droppable({
				  drop: function( event, ui ) {
					$( this ).addClass( "ui-state-highlight" ).val( $( ".ui-draggable-dragging" ).val() );
					$( this ).attr('value',$( ".ui-draggable-dragging" ).attr('key')); //ADDITION VALUE INSTEAD OF KEY	
					$( this ).attr('placeholder',$( ".ui-draggable-dragging" ).attr('value')); 
					$( this ).val($( ".ui-draggable-dragging" ).attr('key') ); //ADDITION VALUE INSTEAD OF KEY	
					
					$( ".ui-draggable-dragging" ).css('visibility','hidden'); //ADDITION + LINE
					$( this ).css('visibility','hidden'); //ADDITION + LINE
					// alert($(this).attr('key'));
					$( this ).parent().css('background','#90EE90');			
				  }		 
				});		
			}
			excel_bulk_dragDrop();
			
			function excel_bulk_automatch_Columns(){
				
				$(".excel_bulk_wrap_free #automatch_columns").on("change",function(){

					if($(".excel_bulk_wrap_free #automatch_columns").is(':checked')){
						
						$( ".excel_bulk_wrap_free .draggable" ).each(function(){
							
							var key = $( this ).attr('key') ; 
							key = key.toUpperCase();
							key = key.replace(" ", "_");							
							var valDrag = $( this ).val() ;
							
							
							$( ".excel_bulk_wrap_free .droppable" ).each(function(){
								
								var valDrop = $( this ).val();
								
								var drop = $( this ).attr('name');
								
								drop.indexOf( '_' ) == 0 ? drop = drop.replace( '_', '' ) : drop;
																
								var drop = drop.replace(/_/g, " ");								
								var nameDrop = drop.toUpperCase();
																
								if( valDrag == nameDrop ){
								
									$( this ).val( key );
									
									//$( valDrag ).css('visibility','hidden'); //ADDITION + LINE
									$( this ).css('background','#90EE90');
									$( this ).parent().css('background','#90EE90');	
								}
							});							
						});

						alert("Check your automatch - The letter after the match signifies the Excel Column Letter. If not satisfied you can always uncheck auto match and do manually");
				
					}else{
						$( ".excel_bulk_wrap_free .droppable" ).val('');
						$( ".excel_bulk_wrap_free .droppable" ).css('background','initial');
						$( ".excel_bulk_wrap_free .droppable" ).parent().css('background','initial');	
					}
												
				});			
			}			


		$("#webd_bulk_signup").on('submit',function(e){
			e.preventDefault();	
			var dat = $(this).serialize();
			$.ajax({
				
				url:	"https://extend-wp.com/wp-json/signups/v2/post",
				data:  dat,
				type: 'POST',							
				beforeSend: function(data) {								
						console.log(dat);
				},					
				success: function(data){
					alert(data);

				},
				complete: function(data){
					dismissWoopei();
				}				
			});	
		});

		function dismissWoopei(){
			
				var ajax_options = {
					action: 'webd_bulk_push_not',
					data: 'title=1',
					nonce: 'webd_bulk_push_not',
					url: wpeip_url.ajaxUrl,
				};			
				
				$.post( wpeip_url.ajaxUrl, ajax_options, function(data) {
					$(".webd_bulk_notification").fadeOut();
				});
		}
		
		$(".webd_bulk_notification .dismiss").on('click',function(e){
				var ajax_options = {
					action: 'webd_bulk_push_not',
					data: 'title=1',
					nonce: 'webd_bulk_push_not',
					url: wpeip_url.ajaxUrl,
				};			
				
				$.post( wpeip_url.ajaxUrl, ajax_options, function(data) {
					$(".webd_bulk_notification").fadeOut();
				});
		});
		
})( jQuery )