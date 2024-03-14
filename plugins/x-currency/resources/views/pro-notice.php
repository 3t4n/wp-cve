<?php defined( 'ABSPATH' ) || exit; ?>
<div class="notice x-currency-notice" style="display:flex;font-size:18px;font-weight:500">
    <img style="width:90px; padding-top: 5px;" src="https://ps.w.org/x-currency/assets/icon-128x128.gif" alt="">
    <div style="padding-top: 14px;padding-left:15px">
        Great News - We are offering x-currency pro FREE for only our facebook community members. You will find X-Currency Pro update version in our Facebook community. So stay connected with us.
        <button class="button maybe-later" style="margin-top: 20px">Maybe Later</button>
        <a class="button-primary" style="margin-top: 20px" href="https://www.facebook.com/groups/doatkolom">Join DoatKolom FaceBook Community</a>
    </div>
</div>
<script data-cfasync="false" type="text/javascript">
    jQuery(function ($) {
        $('.x-currency-notice .maybe-later').on('click', function(event) {
            event.preventDefault();
            let button = $(this);
            button.attr('disabled', true);
            $.ajax({
                url: "<?php echo esc_url( get_rest_url( '', '/x-currency/notice_maybe_latter' ) )?>",
                beforeSend: function ( xhr ) {
                    xhr.setRequestHeader( 'X-WP-Nonce', "<?php x_currency_render( wp_create_nonce( 'wp_rest' ) )?>" );
                },
                success: function() {
                    let $notice = button.closest('.notice')
                    $notice.fadeTo(100, 0, function() {
                        $notice.slideUp(100, function() {
                            $notice.remove();
                        });
                    });
                }
            });
        });
    })
</script>