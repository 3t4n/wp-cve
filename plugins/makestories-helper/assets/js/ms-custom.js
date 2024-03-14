if(typeof $ === "undefined" && typeof jQuery === "function"){ 
    $ = jQuery; 
} 

$( document ).on('ready', function(){
	$('.category-form-wrap .add-cat').on('click', function(e) {
		e.preventDefault();
		$('.category-form-wrap .add-cat-form').toggleClass('block');
	})     

	$('.category-allow-form .category').on('change', function() {
		$value = $(this).val();

		if($(this).prop('checked') == true){
			$('.category-form-wrap').addClass('block');
		} else {
			$('.category-form-wrap').removeClass('block');
		}
	})
}) 

