<?php
$output ='';
$styles ='';
$helper_classes ='';
$value = '';
$required = '';
$required_icon = '';
$field_id = $this->_ch($element['custom_id'],'elementinvader_addons_for_elementor_f_field_id_'.$element['_id']).strtolower(str_replace(' ', '_', $element['field_label']));
$value = $this->_ch($element['field_value']);
$this->add_field_css($element);
$field_tyle = 'text';
?>
<div class="elementinvader_addons_for_elementor_f_group recaptcha elementinvader_addons_for_elementor_f_group_el_<?php echo $element['_id'];?>">
    <?php if(empty($settings['recaptcha_site_key']) || empty($settings['recaptcha_secret_key'])):?>
    <div class="elementinvader_addons_for_elementor_alert elementinvader_addons_for_elementor_alert-info" role="alert">
      <?php esc_html_e( 'Please configurate recaptcha', 'elementinvader-addons-for-elementor' );?>
    </div>
    <?php return false; endif;?>
    <script src="https://www.google.com/recaptcha/api.js"></script>
    <?php
    static $called = 0;
    static $recaptcha_array = array();
    global $elementinvader_addons_for_elementor_recaptcha_init;
    global $elementinvader_addons_for_elementor_recaptcha_called;

    if(isset($settings['recaptcha_version_3']) &&  $settings['recaptcha_version_3'] == 'yes') {
      ?>
          <div class="elementinvader_addons_for_elementor_alert elementinvader_addons_for_elementor_alert-info" role="alert">
            <?php esc_html_e( 'Recaptcha version 3 not use accept checkbox, you can remove it field', 'elementinvader-addons-for-elementor' );?>
          </div></div>  
      <?php
      return false;
    } elseif(!isset($elementinvader_addons_for_elementor_recaptcha_called))
    {
        echo "<script src='https://www.google.com/recaptcha/api.js?onload=CaptchaCallback_".$this->get_id_int()."&amp;render=explicit'></script>";
        $recaptcha_init = true;
    }else {
        ?>
            <div class="elementinvader_addons_for_elementor_alert elementinvader_addons_for_elementor_alert-info" role="alert">
              <?php esc_html_e( 'Only one field can be recaptcha', 'elementinvader-addons-for-elementor' );?>
            </div> </div> 
        <?php
        return false;
    }

    
    $called++;
    $compact_tag='';
    $size_tag='';

    echo '<div id="recaptcha_called_'.$this->get_id_int().'" class="g-recaptcha" '.$compact_tag.' data-sitekey="'.$settings['recaptcha_site_key'].'"></div>';
    ?>

    <script>
    var CaptchaCallback_<?php echo $this->get_id_int();?> = function(){
         //   grecaptcha.render(document.getElementById('recaptcha_called_<?php echo $this->get_id_int();?>'), {'size' : '',  'sitekey' : '<?php echo $settings['recaptcha_site_key'];?>'});
   };
    </script>
</div>