(function( $ ) {

	$('.excel_bulk_wrap_free .nav-tab-wrapper a').click(function(e){
		e.preventDefault();
		var url = $(this).attr("href");
		
		if($(this).hasClass("premium") ){
			$(".excel_bulk_wrap_free .premium_msg").slideDown('slow');
				
		}else if($(this).hasClass("gopro")){
			$(".excel_bulk_wrap_free .the_Content").hide();
			$(".excel_bulk_wrap_free .premium_msg").hide();
			$(".excel_bulk_wrap_free .right_wrap").fadeIn();
			$("#excel_bulk_wrap_free_instructionsVideo").hide();
		}else if($(this).hasClass("contant")) {
			$('.excel_bulk_wrap_free').addClass('loading');
			$(".excel_bulk_wrap_free .premium_msg").hide();	
			$("body").load($(this).attr("href"),function(){
				window.history.replaceState("object or string", "Title", url );
			});				
			//$(".excel_bulk_wrap_free .the_Content").show();				
		
		}else if($(this).hasClass("excel_bulk_wrap_free_instructionsVideo")) {
			$(".excel_bulk_wrap_free .the_Content").hide();
			$(".excel_bulk_wrap_free .premium_msg").hide();
			$(".excel_bulk_wrap_free .right_wrap").hide();
			$("#excel_bulk_wrap_free_instructionsVideo").slideDown();			
		}else{
			$("body").load($(this).attr("href"),function(){
				window.history.replaceState("object or string", "Title", url );
			});	
		}
		
	});	
	

	$(".excel_bulk_wrap_free .proOnly").on('click',function(){
		$(".excel_bulk_wrap_free .premium_msg").slideDown('slow').delay(3000).fadeOut('slow');	
	});
	

	//COUNT NUMBER OF ROWS IN TABLE
			function checkTableResults(){
				 if($('table tr').length > 1 ){
					 $('table').show();
				 }else{
					 $('table').hide();
					 $('input[type=submit]').hide();
					 $(".msg").addClass('success').slideDown().html("<p>Nothing to show here. </p>")
				 }
			}

	  
	$(".excel_bulk_wrap_free #selectTaxonomy").on("submit", function (e) {		
		e.preventDefault();
		
		localStorage.setItem('taxonomy',$("#selectTaxonomy #vocabularySelect ").val() );
		data = $(this).serialize();
			$.ajax({
				url: $(this).attr('action'),
				data:  data,
				type: 'POST',
				beforeSend: function() {								
					$('.excel_bulk_wrap_free').addClass('loading');
				},						
				success: function(response){
					$(".vocabularySelect").slideDown().html($(response).find(".vocabularySelect").html());
					//$("body").html(response);
					console.log('subnmitted');
					$('.excel_bulk_wrap_free').removeClass('loading');
					$("#selectTaxonomy #vocabularySelect ").val(localStorage.getItem('taxonomy'));
					toRunAfterAjax(); //THINGS TO DO AFTER AJAX RUN
				}
			});				
	});
	
	$(".excel_bulk_wrap_free #vocabularySelect").on("change", function (e) {
		console.log('changed');
		$('.excel_bulk_wrap_free #selectTaxonomy').submit();	
	});
	
	
	
		
	$(".excel_bulk_wrap_free #editProductDisplay").on("submit", function (e) {
		e.preventDefault();
		data = $(this).serialize();
		//if checkbox is checked
		$(".fieldsToShow").each(function(){
			if($(this).is(':checked')) {
			}else localStorage.setItem($(this).attr('name') ,$(this).attr('name') );
		});		
		localStorage.setItem('taxTerm',$("#editProductDisplay #taxTerm").val() );
		localStorage.setItem('keyword',$("#editProductDisplay #keyword").val() );
		localStorage.setItem('sku',$("#editProductDisplay #sku").val() );
		localStorage.setItem('regular_price',$("#editProductDisplay #regular_price").val() );
		localStorage.setItem('price_selector',$("#editProductDisplay #price_selector").val() );
		localStorage.setItem('sale_price_selector',$("#editProductDisplay #sale_price_selector").val() );
		localStorage.setItem('sale_price',$("#editProductDisplay #sale_price ").val() );
		var url = $(this).attr('action');
						$.ajax({
							url: $(this).attr('action'),
							data:  data,
							type: 'POST',
							beforeSend: function() {								
								//$("html, body").animate({ scrollTop: 0 }, "slow");
								$('.excel_bulk_wrap_free').addClass('loading');
							},						
							success: function(response){
								
								$(".result").slideDown().html($(response).find(".result").html());
								$('.excel_bulk_wrap_free').removeClass('loading');
								
								$(".excel_bulk_wrap_free .togglerMassive").show();								
								$("#editProductDisplay #taxTerm").val(localStorage.getItem('taxTerm'));
								$("#editProductDisplay #keyword").val(localStorage.getItem('keyword'));
								$("#editProductDisplay #sku").val(localStorage.getItem('sku'));
								$("#editProductDisplay #regular_price").val(localStorage.getItem('regular_price'));
								$("#editProductDisplay #sale_price").val(localStorage.getItem('sale_price'));
								$("#editProductDisplay #price_selector").val(localStorage.getItem('price_selector'));
								$("#editProductDisplay #sale_price_selector").val(localStorage.getItem('sale_price_selector'));	
								//if checkbox is checked
								$(".fieldsToShow").each(function(){									
									if (localStorage.getItem($(this).attr('name')) ) {
										$(this).attr('checked', false);
										//console.log($(this).attr('name') +" is not checked");
									}//else $(this).attr('checked', false);
									
									localStorage.removeItem($(this).attr('name'));	
								});								
								//hide seach forms for better display
								$(".excel_bulk_wrap_free #editProductDisplay").slideUp();
								$(".excel_bulk_wrap_free #selectTaxonomy").slideUp();
								$(".excel_bulk_wrap_free .toggler").show(); //show filter toggle though
								$(".excel_bulk_wrap_free .togglerMassive").show();
								toRunAfterAjax();
								
								$(".excel_bulk_wrap_free #massiveId").val('');
							}							
						});				
	});
	
	
	


		//TOGGLERS
		$(".excel_bulk_wrap_free .toggler").click(function(){
				$(".excel_bulk_wrap_free #editProductDisplay").slideToggle();
				$(".excel_bulk_wrap_free #selectTaxonomy").slideToggle();
				$(".excel_bulk_wrap_free #updateMassive").slideUp();
		});
		$(".excel_bulk_wrap_free .togglerMassive").click(function(){
				$(".excel_bulk_wrap_free #updateMassive").slideToggle();
					$(".excel_bulk_wrap_free #editProductDisplay").slideUp();
					$(".excel_bulk_wrap_free #selectTaxonomy").slideUp();		
		});

		
	
	
	

		//BULK UPDATE FEATURES
		
		$(".excel_bulk_wrap_free #updateMassive").on("submit", function (e) {
			e.preventDefault();
			var massiveId = $(".excel_bulk_wrap_free #massiveId").val();
			if(massiveId.length >0){
						data = $(this).serialize();
							$.ajax({
								url: $(this).attr('action'),
								data:  data,
								type: 'POST',
								beforeSend: function() {								
									//$("html, body").animate({ scrollTop: 0 }, "slow");
									$('.excel_bulk_wrap_free').addClass('loading');
								},						
								success: function(response){
									$('.excel_bulk_wrap_free #editProductDisplay').submit();
									$(".msg").addClass('success').slideDown().html("<p>Updated</p>").delay(3000).fadeOut('slow');
									
								}
							});				
			}else alert('Please select products from the list below.');
				
		});	
	
	
		// on click of ALL selector select all parent checkboxes and grab the value to be updated
		var ids=[];
		$(".excel_bulk_wrap_free .selectAll").on('click',function(){
			check = $(this).is(":checked");
			if( check ){	
				$(".excel_bulk_wrap_free .selectThis").attr("checked", "checked");
				
				$(".excel_bulk_wrap_free .selectThis").each(function(){
					ids.push($(this).val() );
					$(".excel_bulk_wrap_free #massiveId").val(ids.join(","));
					$(".excel_bulk_wrap_free #massiveId").val() ==ids.join(",");
				});
			}else{
				$(".excel_bulk_wrap_free .selectThis").removeAttr("checked", "checked");	
				ids=[];
				$(".excel_bulk_wrap_free #massiveId").val(ids);
				$(".excel_bulk_wrap_free #massiveId").val() ==ids;
			}
		});

		
		$(".excel_bulk_wrap_free .selectThis").on('click',function(){
			check = $(this).is(":checked");
			if( check ){	
				console.log($(this).val());
				console.log($(this).next().val());
				id = $(this).next().val();
				
				if(jQuery.inArray($(this).val(), ids) !== -1){
				}else ids.push($(this).val() );

					ids.push(id);		

			}else{
				if(jQuery.inArray($(this).val(), ids) !== -1){
					//console.log('in array unchecked');				
					ids.splice( $.inArray($(this).val(), ids), 1 );
				}
					ids.splice( $.inArray(id, ids), 1 );
					
			}
			$(".excel_bulk_wrap_free #massiveId").val(ids.join(","));
			//console.log(ids);
		});		
		
	function toRunAfterAjax(){
			
		// UPDATE INLINE
		$(".excel_bulk_wrap_free .single_edit_list").on("submit", function (e) {
			e.preventDefault();
			data = $(this).serialize();
							$.ajax({
								url: $(this).attr('action'),
								data:  data,
								type: 'POST',
								beforeSend: function() {								
									//$("html, body").animate({ scrollTop: 0 }, "slow");
									$('.excel_bulk_wrap_free').addClass('loading');
									$(".excel_bulk_wrap_free .single_edit_list .button-primary").attr('disabled',false);
								},						
								success: function(response){
									$('.excel_bulk_wrap_free').removeClass('loading');							
									$(".msg").addClass('success').slideDown().html("<p>Updated</p>").delay(3000).fadeOut('slow');
								}
							});				
		});	


		//WHEN EDITING INLINE DISABLE THE REST UPDATE FORMS
		$(".excel_bulk_wrap_free  .single_edit_list ").click(function() {
			$(".excel_bulk_wrap_free .single_edit_list .button-primary").attr('disabled',true);
			$(".excel_bulk_wrap_free .single_edit_list input").attr('readonly',true);		
			$(".excel_bulk_wrap_free .single_edit_list textarea").attr('readonly',true);
			$(this).closest(".excel_bulk_wrap_free  .single_edit_list").find(".button-primary").attr('disabled',false);
			$(this).closest(".excel_bulk_wrap_free  .single_edit_list").find("input").attr('readonly',false);
			$(this).closest(".excel_bulk_wrap_free  .single_edit_list").find("textarea").attr('readonly',false);
		});

		// on click of ALL selector select all parent checkboxes and grab the value to be updated
		var ids=[];
		$(".excel_bulk_wrap_free .selectAll").on('click',function(){
			check = $(this).is(":checked");
			if( check ){	
				$(".excel_bulk_wrap_free .selectThis").attr("checked", "checked");
				
				$(".excel_bulk_wrap_free .selectThis").each(function(){
					ids.push($(this).val() );
					$(".excel_bulk_wrap_free #massiveId").val(ids.join(","));
					$(".excel_bulk_wrap_free #massiveId").val() ==ids.join(",");
				});
			}else{
				$(".excel_bulk_wrap_free .selectThis").removeAttr("checked", "checked");	
				ids=[];
				$(".excel_bulk_wrap_free #massiveId").val(ids);
				$(".excel_bulk_wrap_free #massiveId").val() ==ids;
			}
		});

		
		$(".excel_bulk_wrap_free .selectThis").on('click',function(){
			check = $(this).is(":checked");
			if( check ){	
				console.log($(this).val());
				console.log($(this).next().val());
				id = $(this).next().val();
				
				if(jQuery.inArray($(this).val(), ids) !== -1){
				}else ids.push($(this).val() );

					ids.push(id);		

			}else{
				if(jQuery.inArray($(this).val(), ids) !== -1){
					//console.log('in array unchecked');				
					ids.splice( $.inArray($(this).val(), ids), 1 );
				}
					ids.splice( $.inArray(id, ids), 1 );
					
			}
			$(".excel_bulk_wrap_free #massiveId").val(ids.join(","));
			//console.log(ids);
		});

	}
	

	
	//ENABLE OPERATOR WHEN PRICE SELECTED
	
	$(".excel_bulk_wrap_free #bulk_regular_price").keyup(function(){
		$(".excel_bulk_wrap_free #regular_operator").removeAttr('disabled');
	});
	$(".excel_bulk_wrap_free #regular_price").keyup(function(){
		$(".excel_bulk_wrap_free #operator").removeAttr('disabled');
	});

	//DISALLOW NEGATIVE VALUES
		
	$('body').on('input', 'input[name=_regular_price]', function() {
	  $(this).val($(this).val().replace('/',''));
	});	
	$('body').on('input', 'input[name=regular_price]', function() {
	  $(this).val($(this).val().replace('/',''));
	});	
	$('body').on('input', 'input[name=bulk_regular_price]', function() {
	  $(this).val($(this).val().replace('/',''));
	});	
	$(".excel_bulk_wrap_free #bulk_sale_price").keyup(function(){
		$(".excel_bulk_wrap_free #sale_operator").removeAttr('disabled');
	});
	$('body').on('input', 'input[name=_sale_price]', function() {
	  $(this).val($(this).val().replace('/',''));
	});	
	$('body').on('input', 'input[name=sale_price]', function() {
	  $(this).val($(this).val().replace('/',''));
	});	
	$('body').on('input', 'input[name=bulk_sale_price]', function() {
	  $(this).val($(this).val().replace('/',''));
	});	

		
	
})( jQuery )