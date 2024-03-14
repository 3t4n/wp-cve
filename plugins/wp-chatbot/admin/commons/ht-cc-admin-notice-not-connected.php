<?php

/**
 *
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 *
 */
?>
<link href="https://fonts.googleapis.com/css?family=Nunito+Sans&display=swap" rel="stylesheet">
<link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
<style>
    .main {
        width: 1200px;
        margin: 0 auto;
    }
    .mobile-button {
        width: 98%;
        margin-top: 54px;
        display: flex;
        background: linear-gradient(to right, rgba(191,0,146,1) 0%, rgba(224,0,75,1) 100%);
        border-radius: 30px;
        box-shadow: 0px 0px 5px 0px rgba(0,0,0,0.75);
        text-decoration: none;
    }
    h3, p {
        margin-bottom: 10px;
        margin-top: 0;
    }
    .notification__wrapper {
        display: flex;
        width: 100%;
        justify-content: space-between;
        color: #fff;
        align-items: center;
    }
    .notification__button {
        margin-left: auto;
        padding: 15px 24px;
        border: 2px solid #fff;
        border-radius: 10px;
        text-transform: uppercase;
        font-weight: 900;
        font-size: 18px;
        margin-right: 2em;
        text-align: center;
    }
    .notification__info {
        padding: 1rem 0;
    }
    .info-heading {
        font-size: 26px;
    }
    .info__text--bottom {
        display: flex;
    }
    .info__heading{
        color: #FFFFFF;
        font-family: "Nunito Sans";
        font-size: 28px;
        font-weight: 800;
        line-height: 38px;
    }
    .info__text{
        color: #FFFFFF;
        font-family: "Nunito Sans";
        font-size: 18px;
        font-weight: bold;
        line-height: 24px;
    }
    .button__wrapper{
        display: flex;
    }
    .button__wrapper_text{
        color: #F1F1F1;
        font-family: "Nunito Sans";
        font-size: 24px;
        font-weight: 900;
        line-height: 32px;
        display: flex;
    }
    .image-monkey{
        margin: -10px 0 0 0;
        z-index: 10;
        position: relative;
        height: 110%;
    }
    .notification__images{
        height: 155px;
        width: 300px;
        position: relative;
    }
    .button__wrapper_arrow{
        display: flex;
        align-self: center;
        margin-left: 10px;
        width: auto;
        height: auto;
        font-size: 35px;
    }
    .button__wrapper_text{
        align-self: center;
        display: flex;
    }
    .dis_main_banner{
        position: absolute;
        top: 5%;
        right: 3%;
        display: flex;
        align-self: flex-end;
        cursor: pointer;
        color: #fff;
        font-size: 18px;
        line-height: 10px;
    }
    .banner_main__wrap{
        position: relative;
    }
    @media (max-width: 720px) {
        .notification__wrapper{
            flex-direction: column;
        }
        .notification__button{
            margin-left: 0;
            margin-bottom: 20px;
            margin-right: 0;
        }
        .notification__images{
            width: auto;
        }
        .mobile-button{
            margin: 20px;
            width: 87%;
        }
    }

    @media (max-width: 992px) {
        .button__wrapper_text{
            font-size: 16px;
            line-height: 16px;
        }
        .notification__images{
            height: auto;
        }
    }

    @media (max-width: 1200px) {
        .info__heading{
            font-size: 24px;
        }
        .info__text{
            font-size: 16px;
        }
        .button__wrapper_text{
            font-size: 17px;
            line-height: 17px;
        }
        .button__wrapper_arrow{
            font-size: 22px;
        }
        .notification__button{
            padding: 15px 15px;
        }
    }
    #wpadminbar .chat-bot{
        background-image: url(<?php echo plugins_url('admin/assets/img/notice_icon_new.png',HTCC_PLUGIN_FILE)?>)!important;
        height: 100%;
        background-size: contain;
        background-repeat: no-repeat;
        width: 52px;
        padding: 0px;
        margin: 0px;
    }
    #wpadminbar .quicklinks>ul>li#wp-admin-bar-wp-chatbot a{
        padding: 0;
    }
    @media (max-width: 782px) {
        #wp-toolbar #wp-admin-bar-wp-chatbot{
            display: block;
        }
        #wpadminbar li#wp-admin-bar-wp-chatbot .chat-bot{
            width: 75px;
        }
    }
</style>
<script>
    jQuery(document).ready(function($){
        $('.main_banner_button').on('click',function (e) {
            $.ajax({
                type: 'POST',
                url: ajaxurl,
                data: {
                    action: 'main_notice',
                    _ajax_nonce: '<?php echo wp_create_nonce('htcc_nonce') ?>',
                },
                dataType: 'json',
                success: function (data,response) {
                    $('.main_banner_button').parents('.banner_main__wrap').fadeOut(300, function(){ $(this).remove();});;
                }
            });
        });
    });
</script>
<div class="banner_main__wrap">
    <div class="dis_main_banner">
        <span class="main_banner_button"><i class="fa fa-times" aria-hidden="true"></i></span>
    </div>
    <a class="mobile-button" href="<?php echo admin_url( 'admin.php?page=wp-chatbot' )?>">
        <div class="notification__wrapper">
            <div class="notification__images">
                <img src="<?php echo plugins_url('admin/assets/img/monkey.png',HTCC_PLUGIN_FILE)?>" class="images__item image-monkey">
            </div>
            <div class="notification__info">
                <h3 class="info__heading">Uh oh. Your WP-Chatbot setup is incomplete!</h3>
                <p class="info__text">Click to resolve as soon as possible</p>
                <div class="info__text--bottom">
                    <img src="<?php echo plugins_url('admin/assets/img/logo.png',HTCC_PLUGIN_FILE)?>" alt="test" class="info__image">
                </div>
            </div>
            <div class="notification__button">
                <div class="button__wrapper">
                    <span class="button__wrapper_text">Complete setup</span>
                    <span class="dashicons button__wrapper_arrow dashicons-arrow-right-alt"></span>
                </div>
            </div>
        </div>
    </a>
</div>
