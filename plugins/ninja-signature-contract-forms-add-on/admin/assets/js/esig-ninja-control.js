(function($){

    
	//* displaying fall back msg . 
       /*  $( "#esig-ninja-addon-fallback-modal" ).dialog({
			  dialogClass: 'esig-dialog',
			  height:300,
			  width:300,
			  modal: true,
			  buttons:[ 
				{
				 text:"OK ",
				 "id":"esig-primary-dgr-btn",
				 click: function() {
				  $( this ).dialog( "close" );
				  return false ;
					}
				}]
			  
			});*/
		 
	  // almost done modal dialog here 
       $( "#esig-ninja-almost-done" ).dialog({
			  dialogClass: 'esig-dialog',
			  height:350,
			  width:350,
			  modal: true,
			});
            
      // do later button click 
       $( "#esig-ninja-setting-later" ).click(function() {
          $( '#esig-ninja-almost-done' ).dialog( "close" );
        });
      
     
		
})(jQuery);


