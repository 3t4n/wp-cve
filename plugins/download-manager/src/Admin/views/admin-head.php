<?php
if(!defined('ABSPATH')) die('Dream more!');
?><script type="text/javascript">
    var wpdmConfig = {
        siteURL: '<?php echo site_url(); ?>'
    };
    jQuery(function () {


        jQuery('#TB_closeWindowButton').click(function () {
            tb_remove();
        });

        jQuery('body').on('click', '#wpdmvnotice .notice-dismiss', function (){
            jQuery.post(ajaxurl, {action: 'wpdm_remove_admin_notice', __rnnonce: '<?php echo wp_create_nonce(WPDM_PUB_NONCE) ?>'});
        });

    });
</script>
