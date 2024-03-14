jQuery(document).ready(function() {
	jQuery('.spoiler-body').hide();
	jQuery('.spoiler-head').click(function() {
		jQuery(this).toggleClass('folded').toggleClass('unfolded').next().toggle();
	});
});