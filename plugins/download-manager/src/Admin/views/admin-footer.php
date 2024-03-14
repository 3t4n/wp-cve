<?php
if(!defined('ABSPATH')) die('Dream more!');
?><div id="wpdmpro-intro" style="display: none">

	<div class="xcard" id="wpdmpro_notice">
		<div class="xcontent">
			<a href="#" id="wnxclose">Dismiss</a>
			<h2>Thanks for choosing WordPress Download Manager</h2>
			You may check the pro version for multi-file package, front-end administration, email lock and 100+ other awesome features
		</div>
		<div class="xbtn">
			<a target="_blank" href="https://www.wpdownloadmanager.com/pricing/"
			   class="btx">
				Show Me
			</a>
		</div>
	</div>
</div>
<style>
    #wpdmpro_notice {
        padding: 20px;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.08);
        margin: 20px 0;
        width: calc(100% - 40px);
        max-width: calc(100% - 40px);
        border: 0;
        display: flex;
        grid-template-columns: 1fr 150px;
        background: #ffffff;
    }

    #wpdmpro_notice .xcontent{
        flex: 4;
        position: relative;
        background: url('<?php echo  WPDM_BASE_URL ?>assets/images/wpdm-logo.png') left center no-repeat;
        background-size: contain;
        padding-left: 60px
    }

    #wpdmpro_notice .xbtn {
        width: 200px;
        min-width: 200px;
        align-content: center;
        display: grid;
    }

    #wpdmpro_notice h2 {
        margin: 5px 0 5px;
    }

    #wpdmpro_notice .xbtn .btx {
        padding: 15px 20px;
        color: #ffffff;
        font-weight: 600;
        text-decoration: none;
        display: block;
        text-align: center;
        position: relative;
        border-radius: 5px;
        background: linear-gradient(135deg, #518dff 0%, #22c1ff 100%);
        border: 0;
        transition: all ease-in-out 300ms;
        box-shadow: 0 0 10px rgba(0, 100, 255, 0.57);
        margin: 0 5px;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    #wpdmpro_notice .xbtn .btx:hover {
        transition: all ease-in-out 300ms;
        box-shadow: 0 0 15px rgba(0, 100, 255, 0.8);
        opacity: 0.85;
        transform: scale(1.01);
    }

    #wnxclose {
        position: absolute;
        text-decoration: none;
        color: #fff;
        background: linear-gradient(135deg, #ff7432 19%, #f81c7f 90%);
        font-size: 9px;
        padding: 3px 10px;
        top: 0;
        z-index: 999;
        left: 0;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-top: -31px;
        opacity: 0;
        border-radius: 500px;
        transition: all ease-in-out 500ms;
    }

    #wpdmpro_notice:hover #wnxclose {
        opacity: 1;
        transition: all ease-in-out 500ms;
    }
</style>
<script>
    jQuery(function ($) {
        if (pagenow === 'dashboard') {
            $('#dashboard-widgets-wrap').before($('#wpdmpro-intro').html());
        }
        if (pagenow === 'edit-wpdmpro') {
            $('.wp-header-end').before($('#wpdmpro-intro').html());
        }
        $('#wnxclose').on('click', function (e) {
            e.preventDefault();
            $('#wpdmpro_notice').fadeOut();
            $.get(ajaxurl, {__wpnnonce: '<?php echo  wp_create_nonce(WPDM_PUB_NONCE)?>', action: 'hide_wpdmpro_notice'});
        });
    });
</script>
