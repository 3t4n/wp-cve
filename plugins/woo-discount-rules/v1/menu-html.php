<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly

?>
<style>
    body{
        background: #fff;
    }
    .btn-info {
        color: #fff;
        background-color: #5bc0de;
        border-color: #46b8da;
    }
    .btn.focus, .btn:focus, .btn:hover {
        color: #333;
        text-decoration: none;
    }
    .btn-info:hover {
        color: #fff;
        background-color: #31b0d5;
        border-color: #269abc;
    }
    .btn {
        display: inline-block;
        margin-bottom: 0;
        font-weight: 400;
        text-align: center;
        white-space: nowrap;
        vertical-align: middle;
        -ms-touch-action: manipulation;
        touch-action: manipulation;
        cursor: pointer;
        background-image: none;
        border: 1px solid transparent;
        padding: 6px 12px;
        font-size: 14px;
        line-height: 1.42857143;
        border-radius: 4px;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
</style>
<script type="text/javascript">
    (function ($) {
        jQuery(document).ready(function () {
            $('.awdr-switch-version-button').on('click', function (event) {
                event.preventDefault();
                var version = $(this).attr('data-version');
                var page = $(this).attr('data-page');
                var nonce = $(this).attr('data-nonce');
                $.ajax({
                    url: ajaxurl,
                    type: 'POST',
                    data: {action: 'awdr_switch_version', version: version, page: page, wdr_nonce: nonce},
                    success: function (data) {
                        if(data.data.status == true){
                            window.location.replace(data.data.url);
                        }
                        if(data.data.type !== undefined){
                            if(data.data.type == 'auto_install'){
                                $("#wdr_switch_popup").modal("show");
                                $(".wdr_pro_install_message").html(data.data.message);
                            } else {
                                $(".wdr_switch_message").html(data.data.message);
                            }
                        } else {
                            $(".wdr_switch_message").html(data.data.message);
                        }
                    }
                });
            });
            $(document).on('click', '#wdr_switch_popup .wdr-close-modal-box, #wdr_switch_popup .modal-sandbox', function (event) {
                $('#wdr_switch_popup').modal('hide');
            });
            $(document).on('click', '.awdr_auto_install_pro_plugin', function (event) {
                event.preventDefault();
                $(".awdr_auto_install_pro_plugin").html("Processing please wait..")
                $.ajax({
                    url: ajaxurl,
                    type: 'POST',
                    data: {action: 'awdr_auto_install_pro_plugin'},
                    success: function (data) {
                        $(".wdr_switch_message").html('');
                        $(".wdr_pro_install_message").html(data);
                        if($("#wdr_pro_install_status").val() == "1"){
                            $('.awdr-switch-version-button').trigger('click');
                        }
                    }
                });
            });
        });
    })(jQuery);
</script>
<div class="woo_discount_loader_outer">
    <div class="wdr-main">
        <h2 style="font-size: 18px;"><?php _e('Discount Rules', 'woo-discount-rules'); ?></h2>
        <?php do_action('advanced_woo_discount_rules_on_settings_head'); ?>
    </div>
</div>
