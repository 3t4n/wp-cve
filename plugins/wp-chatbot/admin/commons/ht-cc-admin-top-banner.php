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
<link href="https://fonts.googleapis.com/css?family=Open+Sans:400,600,700,800&display=swap" rel="stylesheet">
<style>
    #promo_app{
        z-index: 999;
        transition: all 0.5s ease;
        background-color: #FFFFFF;
        display: none;
        position: fixed;
        top: 30%;
        left: 0;
        right: 0;
        width: 563px;
        margin: 0 auto;
        color: #525252;
        border: 1px solid #000;
        border-radius: 10px;
    }
    #promo_app{
        border: none;
        width: 400px;
        height: auto;
    }
    #promo_app p{
        font-family: "Open Sans",sans-serif;
        font-style: normal;
        font-weight: bold;
        font-size: 18px;
        line-height: 25px;
        color: #525252;
        text-align: center;
        margin-top: 35px;
    }
    #promo_app .modal_close {
        background-size: contain;
        width: 15px;
        height: 15px;
        background-repeat: no-repeat;
        background-position: center;
        position: absolute;
        right: 12px;
        top: 12px;
        cursor: pointer;
        z-index: 999;
    }
    #promo_app .modal_close{
        color: #A6A3A3;
    }
    .mobile-button {
        background: linear-gradient(180deg, #3952FA 0%, #945DF8 100%);
        border-radius: 6px;
        text-decoration: none;
        width: 100%;
    }
    .mobile-button:focus,.mobile-button:active,.mobile-button:hover{
        box-shadow: none;
    }
    h3, p {
        margin-bottom: 10px;
        margin-top: 0;
    }
    .main_notice__wrap{
        position: relative;
        width: 98%;
        margin-top: 54px;
        display: flex;
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
        padding: 0;
        font-weight: 900;
        font-size: 18px;
        flex-basis: 30%;
        text-align: center;
        flex-direction: column;
        display: flex;
        align-items: flex-end;
        margin-right: 55px;
    }

    .notification__info {
        padding: 0;
    }
    .info__heading{
        color: #FFFFFF;
        font-family: "Open Sans",sans-serif;
        font-style: normal;
        font-weight: normal;
        font-size: 17px;
        line-height: 31px;
        margin-bottom: 5px;
    }
    .info__heading b{
        display: flex;
        line-height: 19px;
        margin-top: 1px;
    }
    .complete__button{
        width: 121px;
        padding: 5px;
        line-height: 16px;
        background: #FFFFFF;
        border-radius: 5px;
        cursor: pointer;
    }
    .complete__button span{
        font-family: "Open Sans",sans-serif;
        font-style: normal;
        font-weight: normal;
        font-size: 13px;
        line-height: 18px;
        color: #525252;
    }
    .ios_code{
        background:  url(<?php echo plugins_url('admin/assets/img/31c07c81b2d635598c3776cfb31eb240.png',HTCC_PLUGIN_FILE)?>);
        margin-left: auto;
        margin-right: auto;
        height: 300px;
        width: 300px;
    }
    .dismiss__button{
        position: absolute;
        top: 3px;
        right: 5px;
        display: flex;
        align-self: flex-end;
        cursor: pointer;
    }
    .dismiss__button .button__wrapper_text{
        color: #F1F1F1;
        font-family: "Open Sans",sans-serif;
        font-weight: 900;
        line-height: 32px;
        display: flex;
        font-size: 15px;
    }
    @media (max-width: 720px) {
        .notification__wrapper{
            flex-direction: column;
        }
        .notification__button {
            margin-left: 0;
            margin-bottom: 15px;
            margin-right: 0;
        }
        .main_notice__wrap{
            text-align: center;
        }
    }

    @media (max-width: 992px) {

    }

    @media (max-width: 1200px) {


    }
    .notification__images{
        background-image: url(<?php echo plugins_url('admin/assets/img/phone.png',HTCC_PLUGIN_FILE)?>)!important;
        background-repeat: no-repeat;
        background-size: cover;
        background-position: center center;
        height: 80px;
        width: 125px;
        position: relative;
        margin: 0 30px;
    }
    .promo-app__wrapper .android_app img{
        width: 270px;
    }
    .promo-app__wrapper .ios_app{
        width: 235px;
        cursor: pointer;
    }
    .promo-app__wrapper{
        text-align: center;
        margin-bottom: 25px;
    }
    .promo-app__wrapper a{
        display: block;
    }
    .promo-app__wrapper a:hover,.promo-app__wrapper a:active,.promo-app__wrapper a:focus{
        border: none;
        box-shadow: none;
    }
    .ios-app__wrap p {
        margin-top: 41px;
    }
    .ios-app__wrap{
        display: none;
    }
    .modal-overlays {
        position: fixed;
        margin-left: -20px;
        right: 0;
        height: auto;
        width: auto;
        background: #000;
        will-change: opacity;
        opacity: 0.4;
        z-index: 998;
        top: -25%;
        left: 0;
        bottom: 0;
        display: none;
    }
</style>
<script>
    jQuery(document).ready(function($){
        $(document).on("click", ".button_download_app", function() {
            $('#promo_app').show();
            $('#promo_app').find(".ios-app__wrap").hide();
            $('#promo_app').find(".promo-app__wrapper").show();
            $('#modal-overlay').show();
        });
        $(document).on("click",".ios_app",function () {
            $(this).parent(".promo-app__wrapper").hide();
            $(this).parents("#promo_app").find('.ios-app__wrap').show();
        });
        $(document).on("click",".promo .complete__button",function () {
            $('#promo_app').show();
            $('#promo_app').find(".ios-app__wrap").hide();
            $('#promo_app').find(".promo-app__wrapper").show();
            $('#modal-overlay').show();
        });
        $('.dismiss_but').on('click',function (e) {
            let type = $(this).attr('data-type');
            console.log(type);
             $.ajax({
               type: 'POST',
               url: ajaxurl,
               data: {
                 action:'notice_'+type,
                 _ajax_nonce: '<?php echo wp_create_nonce('htcc_nonce') ?>',
               },
               dataType: 'json',
               success: function (data,response) {
                 $('.dismiss_but').parents('.main_notice__wrap').fadeOut(300, function(){ $(this).remove();});;

               }
             });
        });
        $(".modal .modal_close").on("click", function(event) {
            event.preventDefault();
            $(".modal").hide();
            $('body').css({'overflow': 'auto'});
            $('#modal-overlay').hide();
        });
    });

</script>
<?php
$href='';
if ($type!=='promo') {
	$href = " href=" . admin_url('admin.php?page=wp-chatbot');
} ?>
<div class="main_notice__wrap">
    <div class="dismiss__button" >
        <span data-type="<?php echo $type ?>" class="button__wrapper_text dismiss_but"><i class="fa fa-times" aria-hidden="true"></i></span>
    </div>
    <a class="mobile-button new_leads <?php echo $type ?>"<?php echo $href?>>
        <div class="notification__wrapper">
            <div class="notification__images">
            </div>
            <div class="notification__info">
                <h3 class="info__heading"><?php echo $header_text ?><b><?php echo $p_text ?></b></h3>
            </div>
            <div class="notification__button">
                <div class="complete__button">
                    <span class="button__wrapper_text"><?php echo $button_text ?></span>
                </div>
            </div>
        </div>
    </a>
</div>
<div id="promo_app" class="modal">
    <div class="modal_close"><i class="fa fa-times" aria-hidden="true"></i></div>
    <div class="promo-app__wrapper">
        <p>Download App</p>
        <a target="_blank" class="android_app" href='https://play.google.com/store/apps/details?id=com.mobilemonkey&pcampaignid=pcampaignidMKT-Other-global-all-co-prtnr-py-PartBadge-Mar2515-1'><img alt='Get it on Google Play' src='<?php echo plugins_url('admin/assets/img/get_it_on_google_play.png', HTCC_PLUGIN_FILE) ?>'/></a>
        <img src="<?php echo plugins_url('admin/assets/img/download_app_store.svg', HTCC_PLUGIN_FILE) ?>" alt="" class="ios_app">
    </div>
    <div class="ios-app__wrap">
        <p>Scan on your mobile device to view in the App Store</p>
        <div class="ios_code"></div>
    </div>
</div>
<div class="modal-overlays" id="modal-overlay">
</div>
