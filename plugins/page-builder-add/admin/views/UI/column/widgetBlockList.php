<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>

<input type="text" class="pbSearchWidget" placeholder="Search a widget" style="width: 100%;">
<?php

$pluginOpsWidgetBlocks = array(
    array( "wigt-pb-liveText", "fa fa-edit", "Text Editor", false ),
    array( "wigt-pb-text", "fa fa-text-width", "Heading", false ),
    array( "wigt-img", "fa fa-picture-o", "Image", false ),
    array( "wigt-btn-gen", "fa fa-mouse-pointer", "Button", false ),
    array( "wigt-pb-navmenu", "fa fa-navicon", "Nav Builder", false ),
    array( "wigt-pb-gallery", "fa fa-image fa fa-image", "Image Gallery", false ),
    array( "wigt-menu", "fa fa-navicon", "Menu", false ),
    array( "wigt-pb-poOptins", "fa fa-puzzle-piece", "PluginOps Optin", false ),
    array( "wigt-pb-formBuilder", "fab fa-wpforms", "Form Builder", false ),
    //array( "wigt-pb-contentSlider", "fab ", "Content Slider", false ),
    array( "wigt-video", "fa fa-video-camera", "Video", false ),
    array( "wigt-pb-embededVideo", "fab fa-youtube", "Embed Video", false ),
    array( "wigt-pb-audio", "fa fa-file-audio-o", "Audio", false ),
    array( "wigt-pb-accordion", "fas fa-chevron-down", "Accordion", false ),
    array( "wigt-pb-anchor", "fa fa-anchor", "Anchor", false ),
    array( "wigt-pb-tabs", "fas fa-square", "Tabs", false ),
    array( "wigt-pb-shortcode", "fa fa-code", "ShortCode", false ),
    array( "wigt-pb-icons", "fab fa-fonticons", "Icons", false ),
    array( "wigt-pb-break", "fa fa-ellipsis-h", "Break", false ),
    array( "wigt-pb-postSlider", "fa fa-file-image-o", "Posts Slider", false ),
    array( "wigt-pb-cards", "fab fa-fonticons", "Card", true ),
    array( "wigt-pb-shareThis", "fa fa-share", "Share This", false ),
    array( "wigt-WYSIWYG", "fa fa-file-text-o", "HTML Editor", false ),
    array( "wigt-pb-imageSlider", "fa fa-file-image-o", "Image Slider", true ),
    array( "wigt-pb-testimonial", "fa fa fa-quote-left", "Testimonial", true ),
    array( "wigt-pb-pricing", "fa fa-tags", "Pricing", true ),
    array( "wigt-pb-spacer", "fa fa-arrows-v", "Spacer", false ),
    array( "wigt-pb-imgCarousel", "fa fa-image fa fa-image", "Image Carousel", true ),
    array( "wigt-pb-progressBar", "fa fa-align-left", "Progress Bar", true ),
    array( "wigt-pb-iconList", "fa fa-list", "Icon List", false ),
    array( "wigt-pb-countdown", "fa fa-sort-numeric-desc", "Countdown", true ),
    array( "wigt-pb-testimonialCarousel", "fa fa-navicon", "Testimonial Slider", true ),
    array( "wigt-pb-counter", "fa fa-sort-numeric-asc", "Counter", true ),
    array( "wigt-pb-popupClose", "fa fa-remove", "PopUp Close", false ),
    array( "wigt-pb-wooCommerceProducts", "fa fa-shopping-cart", "WooCommerce Products", true ),
); //array( "datatype", "icon", "title", false ),
 
$proWidgetLoaded = false;
if (is_plugin_active( 'PluginOps-Extensions-Pack/extension-pack.php' )  ) {
    if (function_exists('ulpb_available_pro_widgets') ) {
      $proWidgetLoaded = true;
    }
}

echo '<div style="display:flex; flex-direction:row; flex-wrap: wrap; width: 360px; margin-top:10px; ">';

$pluginOpsWidgetBlocksLocked = array();

foreach ($pluginOpsWidgetBlocks as $key => $value) {
    if($proWidgetLoaded){

        echo "<div class='widget POPB_widget wdt-draggable' data-type='{$value[0]}'><i class='$value[1]'></i> <br> $value[2]</div>";

    }else{
        if($value[3]){
            array_push($pluginOpsWidgetBlocksLocked, $value);
        }else{
            echo "<div class='widget POPB_widget wdt-draggable' data-type='{$value[0]}'><i class='$value[1]'></i> <br> $value[2]</div>";
        }
    }
}

foreach ($pluginOpsWidgetBlocksLocked as $key => $value) {
    echo "<div class='widget POPB_widget prem-widget' ><i class='$value[1]'></i> <br> $value[2] <p>Pro Only</p> </div>";
}

echo '</div>';

if(!$proWidgetLoaded){
    echo ' <br><hr><br> <a href="https://pluginops.com/page-builder/?ref=widgets" target="_blank" class="premiumNoticeWidget" style="width: 300px; margin:0 auto; display:block; padding:8px 10px; text-decoration: none; font-size: 17px; text-align: center; color: #fff; background:#8BC34A; border-radius: 2px;"> Unlock All These Amazing Widgets </a> ';
}

?>