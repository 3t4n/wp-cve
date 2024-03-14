jQuery(function($){
	$('.rew_help_tablink').on( 'click', function(e) {
	    var tab_id = $(this).attr('id')
	    $('.rew_help_tablink').removeClass('active')
	    $(this).addClass('active')

	    $('.rew_tabcontent').hide()
	    $('#'+tab_id+'_content').show()
	} )

	$('.restrict-elementor-widgets-help-heading').click(function(e){
		var $this = $(this);
		var target = $this.data('target');
		$('.restrict-elementor-widgets-help-text:not('+target+')').slideUp();
		if($(target).is(':hidden')){
			$(target).slideDown();
		}
		else {
			$(target).slideUp();
		}
	});
})