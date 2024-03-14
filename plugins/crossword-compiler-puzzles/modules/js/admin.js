vex.defaultOptions.className = 'vex-theme-os';
jQuery(document).ready(function($){

  $('#crossword_method').change(function(){
	if( $('#crossword_method').val() == 'url' ){
		$('.ccpuz_file_class').hide();
		$('.ccpuz_url_class').show();
	}
	if( $('#crossword_method').val() == 'local' ){
		
		$('.ccpuz_url_class').hide();
		$('.ccpuz_file_class').show();
	}
  })
  $('#crossword_method').change();
  
  $('#post').attr('enctype', 'multipart/form-data');
  
 
}); // main jquery container

