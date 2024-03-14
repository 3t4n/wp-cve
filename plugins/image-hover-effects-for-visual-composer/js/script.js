jQuery(document).ready(function(){
	jQuery('.hover').hover(function(){
		jQuery(this).addClass('flip');
	},function(){
		jQuery(this).removeClass('flip');
	});
	jQuery('.image-flip-up, .image-flip-down, .rotate-image-down, .tilt-image, .image-flip-right, .image-flip-left').closest('.image-caption-box').css('overflow', 'visible');
});