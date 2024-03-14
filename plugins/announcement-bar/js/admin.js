jQuery(document).ready(
	function($) {
	
	// Tabs
	$('div.tabbed div').hide();
	$('div.t1').show();
	$('div.tabbed ul.tabs li.t1 a').addClass('tab-current');
	$('div.tabbed ul li a').css('cursor','pointer');

	$('div.tabbed ul li a').click(function(){
		var thisClass = this.className.slice(0,2);
		$('div.tabbed div').hide();
		$('div.' + thisClass).show();
		$('div.tabbed ul.tabs li a').removeClass('tab-current');
		$(this).addClass('tab-current');
	});
	
	// Questions
	$('#normal-sortables .hide').hide();
	$('#normal-sortables a.question').click(function() {
		$(this).next().next().toggleClass('hide').toggleClass('show').toggle(380);
	});
	
	/* If activate is checked
	$('input#activate').change(function() {
		var checked = $('input#activate:checked').val();
		if ( checked ) {
			$('input#slug').attr({'readonly':'','disabled':''});
		} else {
			$('input#slug').attr({'readonly':'readonly','disabled':'disabled'});
		}
	});
	*/
	
	// External links
	$('a').filter(function() {
		return this.hostname && this.hostname !== location.hostname;
	}).attr('target','_blank');
	
});