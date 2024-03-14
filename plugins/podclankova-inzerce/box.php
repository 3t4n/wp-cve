<?php
$ppbutton = $wdbutton = '';
$post_link = base64_encode("http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");

if(get_option('pdckl_api_username') != '' && get_option('pdckl_api_password') != '' && get_option('pdckl_api_signature') != ''){$ppbutton = '<input type="submit" name="paypal_submit" id="paypal_submit" value="PayPal" />';}
if(get_option('pdckl_wd_token') != '') {
  $wdbutton = '<input type="submit" name="wd_submit" id="wd_submit" class="pdckl_submit" value="'.$box_lang['copywriting_pay'].'">';
  //$wdbutton = ' <input type="submit" name="cd_submit" id="cd_submit" class="card_checkout_button tooltips" value="Přejít k zaplacení">';
}
if(get_option('pdckl_showform')) {$showform = '';} else {$showform = '<a class="pdckl_showform_link" onclick="sh(\'#pdckl_gateway_form\').toggle(\'normal\')">'.$box_lang['showform'].'</a>';}
if(get_option('pdckl_jquery')) {$howitlooks = '<div id="pdckl_howitlooks"><div class="pdckl_howitworks">'.$box_lang['howitlooks'].'</div><a href="#" id="pdckl_display_link" target="_blank"><span id="pdckl_display_title"></span></a> - <span id="pdckl_display_desc"></span></div>';} else {$howitlooks = '';}
if(get_option('pdckl_type') == 'both') {
  $link_option = '<label><input type="radio" name="pdckl_gateway_type" value="follow" checked="checked"> '.$box_lang['type_follow'].'</label><label><input type="radio" name="pdckl_gateway_type" value="nofollow"> '.$box_lang['type_nofollow'].'</label>';
} elseif(get_option('pdckl_type') == 'follow') {
  $link_option = '<p style="font-size: 0.75em;">'.$box_lang['follow'].'</p>';
} elseif(get_option('pdckl_type') == 'nofollow') {
  $link_option = '<p style="font-size: 0.75em;">'.$box_lang['nofollow'].'</p>';
}
if(!isset($gateway)) {
    $gateway = '';
}
$gateway .= '
<div class="pdckl_purchase">
  <div class="pdckl_form_li">
    '.$howitlooks.'
    <span id="pdckl_headline">
      <div class="pdckl_title">' . str_replace('$price', $price, get_option('pdckl_title')) . '</div>
      ' . $showform . '
    </span>
    <form id="pdckl_gateway_form" action="?pdckl=checkout" METHOD="POST" style="display: ' . ($showform ? 'none' : 'block') . ';">
      <div>
        <label for="pdckl_gateway_link">'.$box_lang['link'].'</label> <input type="text" name="pdckl_gateway_link" value="https://" id="pdckl_gateway_link" class="pdckl_input" title="'.$box_lang['link_title'].'" required>
      </div>
      <div>
        <label for="pdckl_gateway_link_name">'.$box_lang['name'].'</label> <input type="text" name="pdckl_gateway_link_name" id="pdckl_gateway_title" class="pdckl_input" maxlength="96">
      </div>
      <div>
        <label for="pdckl_gateway_desc">'.$box_lang['desc'].'</label> <input type="text" name="pdckl_gateway_desc" id="pdckl_gateway_desc" class="pdckl_input pdckl_input_large" maxlength="128">
      </div>
      <div>
      ' . $link_option . '
      </div>
      <div style="margin-top: 10px;">
        <input type="hidden" name="id_post" id="id_post" value="' . get_the_ID() . '">
        <input type="hidden" name="url_post" value="'.$post_link.'">
        ' . $wdbutton . ' ' . $ppbutton . '
      </div>
    </form>
  </div>
</div>';
?>
