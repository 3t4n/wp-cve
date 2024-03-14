<?php $njba_fb_setting=new NJBA_FB_Setting();?>
;(function($) {
	$(document).ready(function() {
		new NJBA_FB_Button_Module({
			id: '<?php echo $id; ?>',
			sdkUrl: '<?php echo $njba_fb_setting->njbaGetFbSdkUrl(); ?>',
			currentUrl: '<?php get_permalink(); ?>'
		});
	});
})(jQuery);
