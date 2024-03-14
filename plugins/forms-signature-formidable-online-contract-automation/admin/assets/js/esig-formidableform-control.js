(function($){

	 
       //almost done modal dialog here 
       $( "#esig-formidableform-almost-done" ).dialog({
			  dialogClass: 'esig-dialog',
			  height:350,
			  width:350,
			  modal: true,
			});
            
      // do later button click 
       $( "#esig-formidable-setting-later" ).click(function() {
          $( '#esig-formidableform-almost-done' ).dialog( "close" );
        });
      
     
		
})(jQuery);



