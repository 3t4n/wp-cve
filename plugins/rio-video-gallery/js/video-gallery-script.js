
/*===========================================================
 *Custom Functions
 *=========================================================== */
jQuery(document).ready(function($){
	if($('#vpost_order').is(':checked'))
		{
        	//$('#tbl_image_settings').toggle();
			$('#tbl_vpostorder_settings').show();
		}
		else
		{
			$('#tbl_vpostorder_settings').hide();
		}

//toggle postorder asc and desc..
	  $('#vpost_order').click(function(){
		if($(this).is(':checked'))
		{
        	//$('#tbl_image_settings').toggle();
			$('#tbl_vpostorder_settings').show();
		}
		else
		{
			$('#tbl_vpostorder_settings').hide();
		}

  });

	$("#sel_shortcode" ).change(function() { //shortcode writing..
	  var value = $(this).val();
	  $('#shortcodeDisplay').val('[videogallery view="'+value+'"]');
	});

	//Popup Show
		$(document).on('click','.video-item figure a',function() {
			var currentID = $(this).attr('id');
			var pageWidth = $(window).width();
			var popupWidth = $(this).attr('data-width');
			var popupHeight = $(this).attr('data-height');
			$('.poup_window').hide();
			if(pageWidth <= popupWidth) {
				$('#show_content'+currentID+' .popup-box').css('width','90%');
				$('#show_content'+currentID+' .popup-box').css('height','auto');
    	} else {
				$('#show_content'+currentID+' .popup-box').css('width',popupWidth);
				$('#show_content'+currentID+' .popup-box').css('height',popupHeight);
			}
			$('#show_content'+currentID).show();
    });



		$(window).resize(function() {
			var pageWidth = $(window).width();
			var popupWidth = $('.video-item figure a').attr('data-width');
			var popupHeight = $('.video-item figure a').attr('data-height');
			if(pageWidth <= popupWidth) {
				$('.popup-box').css('width','90%');
				$('.popup-box').css('height','auto');
    	} else {
				$('.popup-box').css('width',popupWidth);
				$('.popup-box').css('height',popupHeight);
			}
    });

		$( document ).on( 'keydown', function (e) {
	    if ( e.keyCode === 27 ) { // ESC
	      $('.poup_window').hide();
	    }
		});

		$(document).on('click','.close_this', function(e) {
			e.preventDefault();
      $(this).parent().parent().find('iframe').attr("src", $(this).parent().parent().find('iframe').attr("src"));
			$('.poup_window').hide();
		});

});
