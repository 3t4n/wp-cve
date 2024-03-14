/* eslint-disable camelcase */
( function( $ ) {
	$( document ).ready( function() {
		var lpbbEnable = $('#_lp_bbpress_forum_enable'),
			lpbbWrapper = $('.lp_bbpress_course__wrapper');

		if(lpbbWrapper.length){

			lpbbEnable.change(function(){
				if(lpbbEnable.is(":checked")){
					lpbbWrapper.removeClass('off');
					lpbbWrapper.addClass('on')
				}else{
					lpbbWrapper.removeClass('on');
					lpbbWrapper.addClass('off')
				}
			});

		}
	} );
}( jQuery ) );

