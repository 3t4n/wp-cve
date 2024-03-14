jQuery(document).ready(function($)
{
    
    //Masonary Grid
    $('.qc-grid').packery({
      itemSelector: '.qc-grid-item',
      gutter: 10
    });

    //Filter Directory Lists
    $(".filter-area > a"). on("click", function(event){

        event.preventDefault();

        var filterName = $(this).attr("data-filter");

        $(".filter-area > a").removeClass("filter-active");
        $(this).addClass("filter-active");

        if( filterName == "all" )
        {
            $("#opd-list-holder .qc-grid-item").css("display", "block");
        }
        else
        {
            $("#opd-list-holder .qc-grid-item").css("display", "none");
            $("#opd-list-holder .qc-grid-item."+filterName+"").css("display", "block");
        }

        $('.qc-grid').packery({
          itemSelector: '.qc-grid-item',
          gutter: 10
        });

    });

    //UpvoteCount
/*    $(".upvote-btn-ilist").on("click", function(event){
		
        event.preventDefault();
		
        var data_id = $(this).attr("data-post-id");
        var data_title = $(this).attr("data-item-title");
        var data_link = $(this).attr("data-item-link");
        var data_desc = $(this).attr("data-item-desc");
        var data_item_id = $(this).attr("data-item-id");

        var parentLI = $(this).closest('li').attr("id");

        var selectorBody = $('.qc-grid-item span[data-post-id="'+data_id+'"][data-item-title="'+data_title+'"][data-item-link="'+data_link+'"]');

        var selectorWidget = $('.widget span[data-post-id="'+data_id+'"][data-item-title="'+data_title+'"][data-item-link="'+data_link+'"]');

        var bodyLiId = $(".qc-grid-item").find(selectorBody).closest('li').attr("id");
        var WidgetLiId = $(selectorWidget).closest('li').attr("id");
		//alert(parentLI);
        //alert( bodyLiId );

        $.post(ajaxurl, {            
            action: 'qcld_upvote_action', 
            post_id: data_id,
            meta_title: data_title,
            meta_link: data_link,
            meta_desc: data_desc,
            meta_id: data_item_id,
            li_id: parentLI
                
        }, function(data) {
            var json = $.parseJSON(data);
            console.log(json);
            //console.log(json.exists);
            if( json.vote_status == 'success' )
            {
                $('#'+parentLI+' .upvote-section .upvote-count-ilist').html(json.votes);
                $('#'+parentLI+' .upvote-section .upvote-btn-ilist').css("color", "green");
                $('#'+parentLI+' .upvote-section .upvote-count-ilist').css("color", "green");

                $('#'+bodyLiId+' .upvote-section .upvote-count-ilist').html(json.votes);
                $('#'+bodyLiId+' .upvote-section .upvote-btn-ilist').css("color", "green");
                $('#'+bodyLiId+' .upvote-section .upvote-count-ilist').css("color", "green");

                $('#'+WidgetLiId+' .upvote-section .upvote-count-ilist').html(json.votes);
                $('#'+WidgetLiId+' .upvote-section .upvote-btn-ilist').css("color", "green");
                $('#'+WidgetLiId+' .upvote-section .upvote-count-ilist').css("color", "green");
            }
        });
       
    });*/



    //UpvoteCount
    $(".upvote-btn-ilist").on("click", function(event){

        event.preventDefault();
        
        var data_id = $(this).attr("data-post-id");
        var data_title = $(this).attr("data-item-title");
        var data_link = $(this).attr("data-item-link");
        var data_desc = $(this).attr("data-item-desc");
        
        var parentLI = $(this).closest('li').attr("id");
        
        if(typeof(parentLI)=='undefined'){
            var parentLI = $(this).closest('.single-item').attr("id");
            
        }
        if(typeof(parentLI)=='undefined'){
            var parentLI = $(this).closest('.upvote-section').attr("id");
            
        }

        //console.log(parentLI);
        
        //alert(parentLI);
        var selectorBody = $('.qc-grid-item span[data-post-id="'+data_id+'"][data-item-title="'+data_title+'"][data-item-link="'+data_link+'"]');

        var selectorWidget = $('.widget span[data-post-id="'+data_id+'"][data-item-title="'+data_title+'"][data-item-link="'+data_link+'"]');

        var bodyLiId = $(".qc-grid-item").find(selectorBody).closest('li').attr("id");
        var WidgetLiId = $(selectorWidget).closest('li').attr("id");
        //alert(parentLI);
        //alert( bodyLiId );

        $.post(ajaxurl, {            
            action: 'qcld_upvote_action', 
            post_id: data_id,
            meta_title: data_title,
            meta_link: data_link,
            meta_desc: data_desc,
            li_id: parentLI,
            security: qc_ilist_get_ajax_nonce
                
        }, function(data) {
            var json = $.parseJSON(data);
            //console.log(json);
            //console.log(json.exists);
            if( json.vote_status == 'success' )
            {
                $('#'+parentLI+' .upvote-count-ilist').html(json.votes);

            }
        });
       
    });
    


	
	$(document).on('click', '.sldclickdisable', function(e){
		e.preventDefault();
		return false;
	})

});


jQuery(document).ready(function($)
{
	
    $("#filter").keyup(function(){
 
        // Retrieve the input field text and reset the count to zero
        var filter = $(this).val(), count = 0;
 
        // Loop through the comment list
        $("#opd-list-holder ul li").each(function(){

            var dataTitleTxt = $(this).children('a').attr('data-title');

            if( typeof(dataTitleTxt) == 'undefined' ){
                dataTitleTxt = "-----";
            }
 
            // If the list item does not contain the text phrase fade it out
            if ($(this).text().search(new RegExp(filter, "i")) < 0 && dataTitleTxt.search(new RegExp(filter, "i")) < 0) {
                $(this).fadeOut();
				$(this).removeClass("showMe");			
 
            // Show the list item if the phrase matches and increase the count by 1
            }
            else {
                $(this).show();
				$(this).addClass("showMe");
                count++;
            }
        });
		
		$(".qcopd-single-list, .qcopd-single-list-1, .opd-list-style-8, .opd-list-style-9").each(function(){
            
			var visibleItems = $(this).find("li.showMe").length;
			
			//console.log(visibleItems);
			
			if(visibleItems==0){
				$(this).hide();
			}else{
				$(this).show();
			}
		});
		
		$('.qc-grid').packery({
          itemSelector: '.qc-grid-item',
          gutter: 10
        });
		
		
 
    });

});