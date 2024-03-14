(function($){
    $('document').ready(function(){	
		
     	 $('.codemirror').each(function(index, elem){  		  
			wp.codeEditor.initialize($(elem), cm_settings);		   
	  	});
		
		
		$('.codemirror-accodes-css').each(function(index, elem){  
			 var cm_settings = wp.codeEditor.defaultSettings ? _.clone( wp.codeEditor.defaultSettings ) : {};
            cm_settings.codemirror = _.extend(
                {},
                cm_settings.codemirror,
                {
                    mode: 'css',
                }
            );
			wp.codeEditor.initialize($(elem), cm_settings);	
		});
		
		
		
		
    });
})(jQuery)