jQuery(document).ready(function($){
    if ($("#googleads").height() > 1) {
        console.log("No ad blocker detected");
        $("#ft-blocker").html("");
    } else {
        console.log("Detects ad blockers");
		$("#ft-blocker").css("display", "block");
		var ftblockid = document.getElementById('ft-blockid').dataset.enabled === 'true';
		if (!ftblockid) {
			function blockScrollEvent(event) {
			$(window).scrollTop(0);
			}
			$(document).on('scroll', blockScrollEvent);
			$('html, body').css('overflow', 'hidden');
		}
    }
	$( "#ft-blocker-clo" ).click(function() {
		$("#ft-blocker").css("display", "none");
	});
});