<!DOCTYPE html>
<html>
<head>
  <?php wp_head(); 
    $img_url = RECAPTCHA_FOR_ALLURL.'images/background.jpg';
    $recaptcha_for_all_background = trim(sanitize_text_field(get_option('recaptcha_for_all_background', 'yes')));
    $recaptcha_for_all_background_color = trim(sanitize_text_field(get_option('recaptcha_for_all_background_color', '#000000')));
    $recaptcha_for_all_foreground_color = trim(sanitize_text_field(get_option('recaptcha_for_all_foreground_color', '#ffffff')));   
    $recaptcha_for_all_btn_background_color = trim(sanitize_text_field(get_option('recaptcha_for_all_btn_background_color', '#9E9E9E')));
    $recaptcha_for_all_btn_foreground_color = trim(sanitize_text_field(get_option('recaptcha_for_all_btn_foreground_color', '#ffffff')));   
    $recaptcha_for_all_settings_provider = trim(sanitize_text_field(get_option('recaptcha_for_all_settings_provider', 'google')));
    $recaptcha_for_all_box_position = trim(sanitize_text_field(get_option('recaptcha_for_all_box_position', 'top')));
    $recaptcha_for_all_box_width = trim(sanitize_text_field(get_option('recaptcha_for_all_box_width', '600px')));
    $recaptcha_for_all_background_position = trim(sanitize_text_field(get_option('recaptcha_for_all_background_position', '#ffffff')));   
    $recaptcha_for_all_image_option = trim(sanitize_text_field(get_option('recaptcha_for_all_image_option', 'default')));   
    $recaptcha_for_all_custom_image_background = trim(sanitize_text_field(get_option('recaptcha_for_all_custom_image_background', '')));   
    if($recaptcha_for_all_image_option == 'default'){
         $recaptcha_for_all_image_background = trim(sanitize_url(get_option('recaptcha_for_all_image_background', '')));
         if(empty(trim($recaptcha_for_all_image_background)))
            $recaptcha_for_all_image_background = esc_attr(RECAPTCHA_FOR_ALLURL).'images/background.jpg';
    }
    else {
      if(!empty($recaptcha_for_all_custom_image_background))
         $recaptcha_for_all_image_background = $recaptcha_for_all_custom_image_background ;
      else
         $recaptcha_for_all_image_background = esc_attr(RECAPTCHA_FOR_ALLURL).'images/background.jpg';  
    }
?>
  <style>
    html { 
        <?php if($recaptcha_for_all_background == 'yes')
              echo 'background: url("'.esc_url($recaptcha_for_all_image_background).'") no-repeat center center fixed;';
             if($recaptcha_for_all_image_background == RECAPTCHA_FOR_ALLURL.'images/background-cookie2.jpg' )
                 echo 'background-color: #e9dcc3;'; 
             elseif($recaptcha_for_all_image_background == RECAPTCHA_FOR_ALLURL.'images/background-plugin2.jpg' )
                 echo 'background-color: #e9dcc3;'; 
              elseif($recaptcha_for_all_image_background == RECAPTCHA_FOR_ALLURL.'images/background-plugin.jpg' )
                 echo 'background-color: #7d8cbd;'; 
              elseif($recaptcha_for_all_image_background == RECAPTCHA_FOR_ALLURL.'images/background-browser.jpg' )
                 echo 'background-color: #7d8cbd;'; 
              elseif($recaptcha_for_all_image_background == RECAPTCHA_FOR_ALLURL.'images/background-cookie.jpg' )
                 echo 'background-color: #7d8cbd;'; 
                 if($recaptcha_for_all_image_background == RECAPTCHA_FOR_ALLURL.'images/background.jpg' ){
                  ?>
                  -webkit-background-size: cover;
                  -moz-background-size: cover;
                  -o-background-size: cover;
                  background-size: cover;
                  <?php
                }
                else
                {
                  echo 'background-size: contain;';
                }
                ?>
      }
      .recaptcha_for_all_box {
        position: fixed;
        width: <?php echo $recaptcha_for_all_box_width;?>;
        max-width:100%;
        padding: 10px;
        border: 1px solid gray;
        margin: 0px auto;
        text-align: center;
        background-color: <?php echo $recaptcha_for_all_background_color;?>;
        color: <?php echo $recaptcha_for_all_foreground_color;?>;
        font-size: 16px;
        z-index: 9999999;
        <?php
            if($recaptcha_for_all_box_position == 'top'){
              echo 'margin-top: 40px;';
              echo 'top: 0;';
              echo PHP_EOL;
              echo 'left: 50%;';
              echo PHP_EOL;
              echo 'transform: translateX(-50%);';
              echo PHP_EOL;
            }
            elseif($recaptcha_for_all_box_position == 'center'){
              echo 'top: 50%;';
              echo PHP_EOL;
              echo 'left: 50%;';
              echo PHP_EOL;
              echo 'transform: translate(-50%, -50%);';
              echo PHP_EOL;
            }
            else
            {
              echo 'bottom: 0;';
              echo PHP_EOL;
              echo 'left: 50%;';
              echo PHP_EOL;
              echo 'transform: translateX(-50%);';
              echo PHP_EOL;
              echo 'margin-bottom: 5px;';
            } ?>
      }
      #recaptcha_for_all_button{
        background-color: <?php echo $recaptcha_for_all_btn_background_color;?>;
        color: <?php echo $recaptcha_for_all_btn_foreground_color;?>;
        padding: 10px 20px 10px 20px;
      }
      .recaptcha-for-all-turnstile{
        padding:20px;
      }
      body {
        <?php
        if($recaptcha_for_all_background != 'yes')
            echo 'background-color: gray !important; ';
        ?>
        max-width: 100%;
        margin: 0px auto;
        text-align: center;
        background: transparent;
      }
      @media only screen and (max-width: 780px) {
        .recaptcha_for_all_box {
           font-size: 14pt;
          /* background: white; */
        }
        #recaptcha_for_all_button {
          font-size: 16pt;
        }
      }
    </style>
</head>
<?php
$allowed_atts = array(
  'align'      => array(),
  'class'      => array(),
  'type'       => array(),
  'id'         => array(),
  'dir'        => array(),
  'lang'       => array(),
  'style'      => array(),
  'xml:lang'   => array(),
  'src'        => array(),
  'alt'        => array(),
  'href'       => array(),
  'rel'        => array(),
  'rev'        => array(),
  'target'     => array(),
  'novalidate' => array(),
  'type'       => array(),
  'value'      => array(),
  'name'       => array(),
  'tabindex'   => array(),
  'action'     => array(),
  'method'     => array(),
  'for'        => array(),
  'width'      => array(),
  'height'     => array(),
  'data'       => array(),
  'title'      => array(),
  'checked' => array(),
  'selected' => array(),
);
$my_allowed['strong']   = $allowed_atts;
$my_allowed['small']    = $allowed_atts;
$my_allowed['h1']       = $allowed_atts;
$my_allowed['h2']       = $allowed_atts;
$my_allowed['h3']       = $allowed_atts;
$my_allowed['h4']       = $allowed_atts;
$my_allowed['h5']       = $allowed_atts;
$my_allowed['h6']       = $allowed_atts;
$my_allowed['hr']       = $allowed_atts;
$my_allowed['i']        = $allowed_atts;
$recaptcha_for_all_message = trim(wp_kses(get_option('recaptcha_for_all_message', ''), $my_allowed));// 
$recaptcha_for_all_text_button = trim(sanitize_text_field(get_option('recaptcha_for_all_button', '')));
if(empty($recaptcha_for_all_text_button))
   $recaptcha_for_all_text_button = esc_attr__("I Agree", "recaptcha-for-all");
if(empty($recaptcha_for_all_message)) {
    $recaptcha_for_all_message = "<h3>Cookies</h3>".
    esc_attr__("We use cookies and javascript to improve the user experience and personalise content and ads, to provide social media 
    features and to analyse our traffic. 
    We also share information about your use of our site with our social media, 
    advertising and analytics partners who may combine it with other information 
    that you’ve provided to them or that they’ve collected from your use of their services.", "recaptcha-for-all");
    echo '<br>';
    esc_attr__("If you disagree, please, press BACK on your browser.", "recaptcha-for-all");
}
?>
<body>
  <div class="recaptcha_for_all_box">
    <?php echo (wp_kses($recaptcha_for_all_message, $my_allowed)); ?>
      <?php
      //
      if( $recaptcha_for_all_settings_provider == 'turnstile'){
            echo '<form id="recaptcha_for_all999" action="'.esc_url($_SERVER['REQUEST_URI']).'" method="POST">';
            $theme = 'dark'; 
            $language = 'auto'; 
            $unique_id = mt_rand(); 
            $callback = 'turnstileCommentCallback';
            $submit_before = '<div id="cf-turnstile-c-'.$unique_id.'" class="cf-turnstile recaptcha-for-all-turnstile" data-action="wordpress-comment" data-callback="'.$callback.'" data-sitekey="'.sanitize_text_field($recaptcha_for_all_sitekey).'" data-theme="'.sanitize_text_field($theme).'" data-language="'.sanitize_text_field($language).'" data-retry="auto" data-retry-interval="1000"></div>';
            echo $submit_before;
          }
      else{
            ?>
            <form id="recaptcha_for_all" action="<?php echo esc_url($_SERVER['REQUEST_URI']); ?>" method="POST">
            <br>
            <?php
          }
      ?>
        <input type="hidden" id="sitekey" name="sitekey" value="<?php echo esc_html($recaptcha_for_all_sitekey);?>" />
      <!--   <br> -->
      <button id="recaptcha_for_all_button" name="recaptcha_for_all_button" type="submit"><?php echo esc_html($recaptcha_for_all_text_button);?></button>
    </form>
  </div>
  <?php wp_footer(); ?>
</body>
</html>