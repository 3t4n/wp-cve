<?php 
if (!function_exists('add_action'))
{
    echo "an error occured! You may not be able to access this plugin via direct URL...";
    exit();
}
else if (!defined('ABSPATH'))
{
    echo "an error occured! You may not be able to access this plugin via direct URL...";
    exit();
}
/**/

$admin_color_themes = array(

    'modern' => '#3858e9',
    'fresh' => '#2271b1',
    'light' => '#04a4cc',
    'blue' => '#e1a948',
    'coffee' => '#c7a589',
    'ectoplasm' => '#a3b745',
    'midnight' => '#e14d43',
    'ocean' => '#9ebaa0',
    'sunrise' => '#dd823b',

    //admin color schemes themes
    'modern-evergreen' => '#0F4232',
    'lawn' => '#A7B145',
    'seashore' => '#456A7F',
    'primary' => '#D8B230',
    'vineyard' => '#CC9E14',
    'vinyard' => '#CC9E14',
    'kirk' => '#BD3854',
    'flat' => '#1ABC9C',
    'cruise' => '#79B591',
    'contrast-blue' => '#264D79',
    'aubergine' => '#C99416',
    'adderley' => '#1730E5',
    '80s-kid' => '#D13674',
);

?>

<style>
::-webkit-scrollbar {
    width: 6px;
}
::-webkit-scrollbar-track {
    background: #1d2327;
}
::-webkit-scrollbar-thumb {
    background: <?php if(isset($admin_color_themes[$user_color])){ echo wp_kses($admin_color_themes[$user_color], array()); }else{echo wp_kses('#192229', array());}?>;
    border-radius: 20px;
}
::-webkit-scrollbar-thumb:hover {
    background: white;
}
.persianfont-con {padding: 5px 0px;padding-left: 20px;background-color: #f1f1f1;transition: all 1s ease;padding-right: 30px;overflow-x: hidden;animation: pfmdz-maincon-anima 2s ease;overflow-x: hidden !important; overflow-y: hidden !important;height: fit-content;}
@keyframes pfmdz-maincon-anima{
    from {opacity: 0;}
    to {opacity: 1;}
}
.persianfont-con a {text-decoration: none !important;}
.persianfont-con h2 {color: #142036;}
.persianfont-con p { text-align: justify;margin-top: 35px;margin-bottom: 35px; }
.persianfont-con Sub { margin-left: 10px; margin-right: 10px; }
.persianfont-con label{position: relative;}
.persianfont-con label:before{    
    content: "";
    position: absolute;
    bottom: -14px;
    left: 0;
    width: 10%;
    height: 3px;
    border-radius: 50%;
    background: <?php if(isset($admin_color_themes[$user_color])){ echo wp_kses($admin_color_themes[$user_color], array()); }else{echo wp_kses('#192229', array());}?>;
    opacity: 0;
    transform: translateX(-50%);
    transition: all 0.2s cubic-bezier(0.68, 1.13, 0.65, 0.78);
}
.persianfont-con select {text-align:center;border-color: rgba(255, 0, 0, 0);transition: all 0.5s ease,border 0s ease;}
.persianfont-con select:hover {color: <?php if(isset($admin_color_themes[$user_color])){ echo wp_kses($admin_color_themes[$user_color], array()); }else{echo wp_kses('#192229', array());}?>;}
.persianfont-con select:focus{border-color: <?php if(isset($admin_color_themes[$user_color])){ echo wp_kses($admin_color_themes[$user_color], array()); }else{echo wp_kses('#192229', array());}?>;box-shadow: none;border-width: 2px;border-style: dashed;transform: translateY(-8px);filter: drop-shadow(0px 4px 6px #10101035);}
.persianfont-con label:hover:before {opacity: 1;transform: translateX(0%); width: 110%;}
.persianfont-con .div-center1 {
    display: flex;
    justify-content: center;
}
.persianfont-con .div-inline {
    display: flex;
    flex-direction: row;
}
.persianfont-con fieldset {
    border: 1px dashed rgb(211 211 211);
    padding: 25px;
    margin-top: 20px;
    border-radius: 3px;
    transition: all 0.7s ease;
}
.persianfont-con fieldset:hover{ box-shadow: 0px 0px 18px 6px #05223e14; }
.persianfont-con legend {
    background-color: <?php if(isset($admin_color_themes[$user_color])){ echo wp_kses($admin_color_themes[$user_color], array()); }else{echo wp_kses('#192229', array());}?>;
    padding: 5px 9px;
    color: #fff;
    text-shadow: 0px 0px 2px #ffffffc2;
    border-radius: 3px;
    font-size: 17px;
    float: none !important;
    width: fit-content !important;
    box-shadow: 0px 0px 8px 10px <?php if(isset($admin_color_themes[$user_color])){ echo wp_kses($admin_color_themes[$user_color], array()); }else{echo wp_kses('#192229', array());}?>;
}
.persianfont-con input[checked]{border: 1px green solid;}
.persianfont-con p.submit input {
    opacity: 0.7;
    transition: all 1s ease,padding 0.15s ease;
    z-index: 999999 !important;
}
.persianfont-con p.submit input:hover {
    opacity: 1;
    padding-top: 9px;
}
.persianfont-con p.submit {margin-top: 20px; margin-bottom: 0px;height: 28px !important;}
.persianfont-con .div-seperator {    
    padding: 5px;
    padding-right: 10px;
    background-color: #dbedff;
    display: flex;
    flex-direction: row;
    justify-content: space-between;
    border-radius: 20px;
}
.persianfont-con .options-con {margin: 20px 0px; min-height: 70px;}
.persianfont-con .options-con input {margin-top: 2px;margin-left: 14px;}
.persianfont-con .options-con label {height: 25px;} 
.persianfont-con option {line-height: 200px !important; height: 200px !important;}
.persianfont-con option[selected] {background-color: <?php if(isset($admin_color_themes[$user_color])){ echo wp_kses($admin_color_themes[$user_color], array()); }else{echo wp_kses('#192229', array());}?>; color: #fff;}
.persianfont-con .radios-con {display: grid;justify-items: start;padding-right: 8px;}
.persianfont-con .fontupload-con {min-height: unset;}
.persianfont-con .radios-con2 {display: inline-flex;min-height: 60px;margin-bottom: 40px;border: 1px dashed #afafaf;padding: 10px 18px;border-radius: 20px;}
.persianfont-con .actived_checkboxes sub {
    background-color: green;
    padding: 2px 6px;
    border-radius: 9px;
    color: white;
}
/*submit btns*/
@media only screen and (min-width: 1px){
    .persianfont-con p.submit {
        position: fixed;
        bottom: -29px;
    }
}
@media only screen and (min-width: 601px){
    .persianfont-con p.submit {
        position: fixed;
        top: 0;
        left: 5%;
    }
}
@media only screen and (min-width: 700px){
    .persianfont-con p.submit {
        position: fixed;
        top: 0;
        left: 15%;
    }
}
@media only screen and (min-width: 1000px){
    .persianfont-con p.submit {
        position: fixed;
        top: 0;
        left: 35%;
    }
}
/*RESP*/
@media only screen and (max-width: 782px){.persianfont-con p.submit {margin-top: 32px;}}
@media only screen and (max-width: 735px){.testText-con h4 { margin-left: 14px !important; }}
@media only screen and (max-width: 650px){
    .persianfont-con .radios-con2 {flex-direction: column;align-items: flex-start;}
    .persianfont-con .themes-inputs-con {margin-bottom: 25px;}
}
@media only screen and (max-width: 600px){
    .persianfont-con p.submit {
        width: 100%;
        height: 58px !important;
    }
    .persianfont-con p.submit input {
        width: 100%;
        transform: translateX(41px);
    }
    .persianfont-con p.submit input:hover {
        opacity: 1;
        padding-top: 0px;
        padding-bottom: 9px;
    }
    .testText-con {flex-direction: column !important;}
    .testText-con .pfmdz-btn {margin-top: -32px !important;text-align: center;}
}
@media only screen and (max-width: 500px){
    .persianfont-con .custom-select {height:40px;}
    .fontupload-con {flex-direction: column;}
    .custom-select {flex-direction: column !important;margin-bottom: 85px;}
    .custom-select select {margin: 25px 10px !important;}
    .helptexts-con p {line-height: 30px;}
    .acordion {padding: 10px 18px !important;} 
    .persianfont-con .pfmdz-front-fontspicker-con {margin-bottom: 115px;}
}
@media only screen and (max-width: 450px){
    .options-con {flex-direction: column;}
    .options-con img {margin-top: 32px;}
    .options-con label {margin-top: 12px;} 
    .options-con div {width: 135px;}
}
@media only screen and (max-width: 435px) {
    .persianfont-con fieldset {display: contents !important;}
    .persianfont-con legend {margin: 35px 0px;}
}
@media only screen and (max-width: 400px){
    .persianfont-con {padding-right: 5px;padding-left: 5px;}
    .persianfont-con p.submit input {transform: translateX(12px);}
    .persianfont-con {padding-bottom: 35px;}
}
@media only screen and (max-width: 345px){
    .persianfont-con fieldset {min-width: 75% !important;}
}
/*accordions*/
.acordion {
    transition: all 0.33s ease;
    width: 100%;
    padding: 10px 70px;
    cursor: pointer;
    background-color: <?php if(isset($admin_color_themes[$user_color])){ echo wp_kses($admin_color_themes[$user_color], array()); }else{echo wp_kses('#192229', array());}?>;
    border-radius: 22px;
    color: #fff;
    text-shadow: 0px 0px 4px azure;
}
.acordion:hover { filter:brightness(0.8);border-radius: 10px; }
.acordion-panel {
    transition: max-height 0.2s ease,margin 0.4s ease;
    max-height: 0;
    overflow: hidden;
    padding-top: 0px !important;
    padding-bottom: 0px !important;
}
.acordion-panel-show {
    margin-top: 35px !important;
    margin-bottom: 18px !important;
}
.acordion-active { filter: saturate(1.5);padding: 10px 300px; }
@media only screen and (max-width: 1100px){.acordion-active {padding: 10px 125px; }}
@media only screen and (max-width: 570px){.acordion-active {padding: 10px 25px !important; }}
/*accordions2-fieldset*/
.persianfont-con fieldset[closed="yes"] {
    padding: 0px !important;
    padding-bottom: 7px !important;
    height: 75px !important;
    cursor: pointer;
}
.persianfont-con .acordion2 {
    transition: all 1s ease;
}
.persianfont-con fieldset[closed="yes"] .acordion2 {
    max-height: 0px !important;
    padding: 0px !important;
    margin: 0px !important;
    opacity: 0 !important;
    pointer-events: none !important;
}
.persianfont-con .acordion2-btn {
    text-align: center;
}
.persianfont-con .acordion2-btn span {
    background-color: <?php if(isset($admin_color_themes[$user_color])){ echo wp_kses($admin_color_themes[$user_color], array()); }else{echo wp_kses('#192229', array());}?>;
    color: #fff;
    padding: 10px 7px 8px 7px;
    border-radius: 50%;
    transition: all .5s ease;
    transform: rotate(315deg);
    cursor: pointer;
    margin-bottom: 20px;
}
.persianfont-con .acordion2-btn span:hover {
    box-shadow: 0px 0px 1px 6px <?php if(isset($admin_color_themes[$user_color])){ echo wp_kses($admin_color_themes[$user_color], array()); }else{echo wp_kses('#192229', array());}?>;
}
.persianfont-con fieldset[closed="yes"] .acordion2-btn span {
    border-radius: 5px !important;
    transform: rotate(0deg) !important;
}
@media only screen and (max-width: 500px){
    .persianfont-con fieldset[closed="yes"] {
        height: auto !important;
    }
    .persianfont-con .acordion2-btn {margin-top: 32px;}
    .persianfont-con fieldset[closed="yes"] .acordion2-btn {
       padding-top: 10px;
       border-radius: 8px;
    }
}
.nightmodeon .acordion2-btn span {background-color: #000000 !important;}
/*misc*/
.actived_checkboxes {
    border: 1px dashed <?php if(isset($admin_color_themes[$user_color])){ echo wp_kses($admin_color_themes[$user_color], array()); }else{echo wp_kses('#192229', array());}?>;;
    padding: 10px;
    border-radius: 10px;
}
.persianfont-con .helptip { color: #9b9b9b;text-shadow: 0px 0px 5px #3e3b3b3d;cursor:help;position: relative;    margin-right: 20px; }
.persianfont-con .helptip:hover {color: #000000;} 
.persianfont-con .helptip span {position: relative;margin-left: 7px;}
.persianfont-con .helptip span:before {font-family: Dashicons;content: "";font-size: 22px;position: absolute;top: -8px;left: -24px;}
.persianfont-con[rtl= '1'] .helptip span:before {left: unset;right: -24px;}
.persianfont-con .helptip span:after {
    content: "<?php echo __('Hint', 'pfmdz') ?>";
    position: absolute;
    background-color: #101010;
    bottom: -31px;
    left: 75px;
    padding: 2px 5px;
    opacity: 0;
    color: #fff;
    border-radius: 3px;
    transition: all 0.45s cubic-bezier(0.36, 1.35, 0, 1.04);
    z-index: 99;
}
.persianfont-con .helptip span:hover:after {opacity: 1; left:-5px;}
.persianfont-con .helptip span:hover:before {color: #101010;}
.persianfont-con .pfmdzlogo { transition: all 1s ease;cursor: pointer;}
.persianfont-con .pfmdzlogo:hover{background-color: #ffffff;border-radius: 50%;padding: 10px 20px;filter: drop-shadow(0px 0px 4px #10101035);}
.persianfont-con .testtext {background-color: #fff;padding: 10px 15px;border-radius: 20px;line-height: 2em;text-align: center;}
.persianfont-con #pfmdz-testtext {transition: all 1s ease !important;}
.persianfont-con .pfmdz-loader:before {font-family: Dashicons;content: "\f100";font-size: 18px;opacity: 0;transition: opacity 0.4s ease;}
.persianfont-con .pfmdz-loader-spining {animation: pfmdz-loaderSpin 3s ease infinite;text-rendering: auto;}
.persianfont-con .pfmdz-loader-spining:before {opacity: 1 !important;}
@keyframes pfmdz-loaderSpin {
    0% {transform: rotate(35deg);}
    55% {transform: rotate(-85deg);}
    100% {transform: rotate(35deg);}
}
.persianfont-con .pfmdz-btn { margin-top: 45px;height: 20px;background-color: <?php if(isset($admin_color_themes[$user_color])){ echo wp_kses($admin_color_themes[$user_color], array()); }else{echo wp_kses('#192229', array());}?>;color: #fff;padding: 3px 20px;border-radius: 5px;cursor: pointer;transition: filter 0.5s ease,opacity 0.2s ease;width: fit-content;}
.persianfont-con .justflex {display: flex;}
.persianfont-con .exampleimgs {cursor: pointer;border: 2px solid #7c68dd;border-radius: 9px;transition: all 0.6s ease;}
.persianfont-con .exampleimgs:hover{transform: translateY(-6px);box-shadow: 0px 11px 31px -3px #7c68dd;}
.exampleimgs-show img {
    position: fixed;
    top: 0;
    width: 55% !important;
    right: 50%;
    margin-right: -25% !important;
    height: auto !important;
    margin-top: 55px !important;
    z-index: 9999999;
    border: none !important;
}
@media only screen and (max-width: 690px){.exampleimgs-show img {width: 85% !important;margin-top: 95px !important;right: 34%;}}
.exampleimgs-show {display: contents;z-index: 9999999;}
.exampleimgs-hide img{top: -100% !important; opacity: 0; pointer-events: none;}
.exampleimgs-susp img{display: none; opacity: 0;}
.persianfont-con .lightbox-bg {position: fixed; width: 100%; height: 100%;background-color: #10101065;opacity: 0;transition: opacity 0.4s ease; z-index: 999999;pointer-events: none;cursor: url(<?php echo esc_url(persianfontsmdez_URL . "admin/img/close.png"); ?>),progress;}
.persianfont-con .helptexts-con {
    max-width: 855px;
    text-align: center;
    background-color: #fff;
    padding: 25px 3px;
    border-radius: 25px;
    box-shadow: 0px 7px 13px -9px #2628447d;
    margin-left: auto;
    margin-right: auto;
    margin-top: 1px;
    margin-bottom: 15px;
}
.persianfont-con .helptexts-con p {margin: 10px 7px;}
.notice.e-notice, .notice.notice-info, .astra-notice, .notice, .is-dismissible, .error {display: none !important;}
/*CSS Code Wrapper*/
.persianfont-con .css-code-con { min-height: 250px; }
.persianfont-con .css-code-con code { min-height: 250px; }
.persianfont-con .css-code-con textarea {min-height: 250px;width: 100%;direction: ltr !important;}
/*Night Mode*/
.bg-night {background-color: #1e1e1e !important;}
.persianfont-con .nightmode-btn {cursor: pointer; transition: all 0.4s ease; position: relative;}
.persianfont-con .nightmode-btn img {vertical-align: middle;}
.persianfont-con .nightmode-btn img.nightmode-btn-img1 {border-radius: 20px;}
.persianfont-con .nightmode-btn:hover {transform: translateX(15px);}
.persianfont-con .nightmode-btn:hover img.nightmode-btn-img1 {box-shadow: 0px 0px 10px 10px #3a3a3a;}
.nightmodeon .nightmode-btn:hover {transform: translateX(-15px);}
.disabled-btn {opacity: 50%; pointer-events: none !important;}
.nightmodeon {background-color: #1e1e1e;}
.nightmodeon h2, .nightmodeon label, .nightmodeon legend, .nightmodeon sub, .nightmodeon h4, .nightmodeon span.pfmdz-btn, .nightmodeon p#pfmdz-testtext, .nightmodeon input[type="submit"], .nightmodeon span.acordion  {color: #919191 !important;text-shadow: none;}
.nightmodeon legend, .nightmodeon span.pfmdz-btn, .nightmodeon select, .nightmodeon p#pfmdz-testtext, .nightmodeon input[type="submit"], .nightmodeon span.acordion {background-color: #000000 !important;}
.nightmodeon legend {box-shadow: 0px 0px 8px 10px #000000;}
.nightmodeon select {color: <?php if(isset($admin_color_themes[$user_color])){ echo wp_kses($admin_color_themes[$user_color], array()); }else{echo wp_kses('#192229', array());}?> !important;}
.nightmodeon .helptip:hover, .nightmodeon .helptexts-con p { color: #cfcfcf; }
.nightmodeon .helptexts-con {background-color: #363636;box-shadow: 0px 7px 18px -9px <?php if(isset($admin_color_themes[$user_color])){ echo wp_kses($admin_color_themes[$user_color], array()); }else{echo wp_kses('#192229', array());}?>;}
.nightmodeon .lightbox-bg {background-color: #101010d4;}
.nightmodeon .css-code-con textarea {background-image: url("<?php echo esc_url(persianfontsmdez_URL . 'admin/img/bg-footer-noise.jpg'); ?>");background-color: #363636;color: #a0cbff;}
.nightbtn-arrow img {
    transition: all 0.33s ease;
    position: absolute;
    left: 38px;
    top: 5.5px;
}
.persianfont-con[rtl= '1'] .nightbtn-arrow img {
    left: unset;
    right: 38px;
}
.nightmodeon .nightmode-btn-img2 {transform: rotate(180deg) !important;}
.nightmode-btn-img2 {vertical-align: -webkit-baseline-middle !important;vertical-align: -moz-middle-with-baseline !important;}
.nightmodeon .nightmode-btn-img1 {background-color: #fff;border-radius: 20px;}
.nightmodeon .nightmode-btn:hover img.nightmode-btn-img1 {box-shadow: 0px 0px 10px 10px white;}
.nightmodeon span.pfmdz-loader {color: #fff;}
.nightmodeon fieldset:hover {box-shadow: 0px 0px 18px 6px #8b8b8b73 !important;}
/*gallery*/
.persianfont-con .pfmdz-row {display: flex;flex-wrap: wrap;padding: 0 4px;}
.persianfont-con .pfmdz-column {flex: 25%;padding: 0 4px;}
.persianfont-con .pfmdz-column div img {margin-top: 8px;vertical-align: middle;}
.persianfont-con .go-aparat {margin-right: 20px;}
.persianfont-con .go-aparat a {transition: 1s ease;}
.persianfont-con .go-aparat a:hover {margin-right: 35px; text-decoration: none;}
.persianfont-con .social-logo {
    background-color: #ffffff;
    border-radius: 50%;
    transition: 0.5s ease;
    margin: 0px 24px;
    box-shadow: 0px 0px 10px 10px white;
}
.persianfont-con .social-logo:hover {transform: rotate(23deg);filter: drop-shadow(0px 0px 4px #10101050);}
/*This plugin*/
.commercials-con {
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;;width: 100%;
    height: auto;margin: 20px 0px;
    text-align: center;
    transition: 1s ease;
    animation: pfmdz-commercialcon-anima 1s ease;
}
@keyframes pfmdz-commercialcon-anima {
    from {opacity: 0;}
    to {opacity: 1;}
}
.commercials-con span {
    background-color: #6130ee;
    color: #fff;
    text-shadow: 0px 0px 2px #ffffff;
    padding: 9px;
    border-radius: 20px;
    cursor: pointer;
    transition: all .3s ease;
    box-shadow: 0px 0px 10px 10px #6130ee;
}
.commercials-con span:hover {transform: translateY(-12px);box-shadow: 0px 0px 1px 12px #6130ee;}
.front-fonts-fieldsethidden {opacity: 0 !important;height: 0px !important; padding: 0px !important; margin: 0px !important; pointer-events: none !important;border: none !important;}
.front-fonts-fieldset {background-color: #f3f0ff;}
.nightmodeon .front-fonts-fieldset {background-color: #18131c;}
.warning-text-con{text-align: center;display: flex;justify-content: center;}
.warning-text-con h4{border-bottom: 1px solid;width: fit-content;font-size: 16px;line-height: 29px;}
@media only screen and (max-width: 700px){.warning-text-con h4{font-size: 13px;}}
@media only screen and (max-width: 400px){.warning-text-con h4{font-size: 10px;}}
.persianfont-con .pfmdz-notice {
    max-width: 700px;
    margin: auto;
    margin-top: 35px;
    margin-bottom: 25px;
    background-color: #7c68dd;
    box-shadow: 0px 0px 10px 10px #7c68dd;
    padding: 1px 25px;
    padding-bottom: 24px;
    border-radius: 20px;
    text-align: center;
    transition: transform 3s ease;
}
.persianfont-con .pfmdz-notice p {color: #fff;font-size: 14.5px;}
.persianfont-con .pfmdz-notice strong {color: #060722;font-size: 16px;}
.persianfont-con .pfmdz-notice div {
    display: flex;
    margin-top: 40px;
    justify-content: space-around;
}
.persianfont-con .pfmdz-notice span {
    padding: 5px 10px;
    border-radius: 10px;
}
.persianfont-con .pfmdz-notice a {text-decoration: none;color: #000;}
.persianfont-con .pfmdz-disable {opacity: 0.5;pointer-events: none;}
.persianfont-con .pfmdz-custom-fonts-con {
    display: flex;
    flex-direction: column;
    max-width: 700px;
    transition: all 0.7s ease;
}
.persianfont-con .pfmdz-custom-fonts-con input {margin: 15px 0px;}
.persianfont-con .pfmdz-custom-fonts-con label {width: fit-content;}
</style>