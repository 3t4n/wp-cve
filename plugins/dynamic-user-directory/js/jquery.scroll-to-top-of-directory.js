(function( $ ) {

	$(document).ready(function() {
		
		try 
		{
			let dudSearchParams = new URLSearchParams(window.location.search);
			
			dudSearchParams.has('letter'); 
			let letter = dudSearchParams.get('letter');
						
			dudSearchParams.has('dud_page_number_clicked'); 
			let dud_page_number_clicked = dudSearchParams.get('dud_page_number_clicked');
			
			dudSearchParams.has('dud_right_arrow_clicked'); 
			let dud_right_arrow_clicked = dudSearchParams.get('dud_right_arrow_clicked');
			
			dudSearchParams.has('dud_left_arrow_clicked'); 
			let dud_left_arrow_clicked = dudSearchParams.get('dud_left_arrow_clicked');
			
			let dud_user_srch_val = "";
			
			if(jQuery.trim( $("#dud_user_srch_val").val() ) !== "")
				dud_user_srch_val = "notempty";
			
			if(letter || dud_user_srch_val || dud_page_number_clicked || dud_right_arrow_clicked || dud_left_arrow_clicked)
			{
				let dudATag = $("a[name='dud-top-of-directory']");
				$('html,body').animate({scrollTop: dudATag.offset().top - 35},'slow');
			}
		}
		catch(err){;}
		
	}); 

})( jQuery );

