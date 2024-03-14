$( document ).ready(function() {

	$(this).bind('keypress', function(e) {
    
		var key = (e.keyCode ? e.keyCode : e.charCode);
        
		if(key == 112){
		//alert("p was pressed");
		
			$('marquee').prop( "scrollAmount", "2" );
		
		
		}else{
			$('marquee').prop( "scrollAmount", "0" );
		}
				
				                  
    });
	
	


});