<?php $njba_fb_setting = new NJBA_FB_Setting();?>
;(function ($) {
    $(document).ready(function () {
        new Njba_Facebook_Comments({
            id: '<?php echo $id; ?>',
            sdkUrl: '<?php echo $njba_fb_setting->njbaGetFbSdkUrl(); ?>'
        });
    });
})(jQuery);
