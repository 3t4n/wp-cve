jQuery(document).ready(function($){
	$(document).on('click', '.team-popup-box .close', function(){
		$('.team-popup-box').fadeOut();
		$('.team-slide').fadeOut();
	})
	$('.team-popup').click(function(event){
		event.preventDefault();
		var teamid = $(this).attr('teamid');
		$('.team-popup-box-'+teamid).fadeIn();
		$('.team-slide-'+teamid).css("display",'inline-block');
	});
});