jQuery( document ).ready(function() {
	//jQuery('.accordion-1').removeClass('normal');
    jQuery('.accordion-1 h3').click(function(){
		if(jQuery(this).hasClass('activeAcc')){
			jQuery(this).removeClass('activeAcc');
			jQuery(this).next('div').toggle();
		}
		else{
			jQuery(this).addClass('activeAcc');
			jQuery(this).next('div').toggle();
		}
	})
	jQuery('.all-btn').click(function (e) {
		e.preventDefault();
		if (jQuery(this).hasClass('all')) {
			jQuery(this).removeClass('all').text('See All');
			jQuery('.faq-item.nxtall').toggleClass('show');
		} else {
			jQuery(this).addClass('all').text('Less');
			jQuery('.faq-item.nxtall').toggleClass('show');
			return false;
		}
	});
});