<?php if ( !defined('CP_CONTACTFORMPP_AUTH_INCLUDE') ) { echo 'Direct access not allowed.';  exit; } ?>
<?php 
  $custom_styles = base64_decode(get_option('CP_CFWPP_CSS', '')); 
  if ($custom_styles != '')
      echo '<style type="text/css">'.$custom_styles.'</style>';
  $custom_scripts = base64_decode(get_option('CP_CFWPP_JS', '')); 
  if ($custom_scripts != '')
      echo '<script type="text/javascript">'.$custom_scripts.'</script>';  
?></form class="cfwppformfix">
<form class="cpp_form" name="cp_contactformpp_pform<?php echo esc_html($CP_CPP_global_form_count); ?>" id="cp_contactformpp_pform<?php echo esc_html($CP_CPP_global_form_count); ?>" action="<?php get_site_url(); ?>" method="post" enctype="multipart/form-data" onsubmit="return doValidate<?php echo esc_html($CP_CPP_global_form_count); ?>(this);"><input type="hidden" name="cp_pform_psequence" value="<?php echo esc_html($CP_CPP_global_form_count); ?>" /><input type="hidden" name="cp_contactformpp_pform_process" value="1" /><input type="hidden" name="cp_contactformpp_id" value="<?php echo $id; ?>" /><input type="hidden" name="cp_ref_page" value="<?php esc_attr(cp_contactformpp_get_FULL_site_url()); ?>" /><input type="hidden" name="form_structure<?php echo esc_html($CP_CPP_global_form_count); ?>" id="form_structure<?php echo esc_html($CP_CPP_global_form_count); ?>" size="180" value="<?php echo str_replace('"','&quot;',str_replace("\r","",str_replace("\n","",esc_attr(cp_contactformpp_cleanJSON(cp_contactformpp_translate_json(cp_contactformpp_get_option('form_structure', CP_CONTACTFORMPP_DEFAULT_form_structure,$id))))))); ?>" />
<div id="fbuilder">
  <div id="fbuilder<?php echo esc_html($CP_CPP_global_form_count); ?>">
      <div id="formheader<?php echo esc_html($CP_CPP_global_form_count); ?>"></div>
      <div id="fieldlist<?php echo esc_html($CP_CPP_global_form_count); ?>"></div>
  </div>
</div>    
<div style="display:none">
<div id="cpcaptchalayer<?php echo esc_html($CP_CPP_global_form_count); ?>">
<?php if (count($codes)) { ?>
     <?php echo __('Coupon code','cp-contact-form-with-paypal'); ?>:<br />
     <input type="text" name="couponcode" value=""><br />
<?php } ?>
  <br />
<?php if (cp_contactformpp_get_option('cv_enable_captcha', CP_CONTACTFORMPP_DEFAULT_cv_enable_captcha,$id) != 'false') { ?>
  <?php echo esc_html(__('Please enter the security code','cp-contact-form-with-paypal')); ?>:<br />
  <img src="<?php echo cp_contactformpp_get_site_url().'/?cp_contactformpp=captcha&ps='.$CP_CPP_global_form_count.'&width='.cp_contactformpp_get_option('cv_width', CP_CONTACTFORMPP_DEFAULT_cv_width,$id).'&height='.cp_contactformpp_get_option('cv_height', CP_CONTACTFORMPP_DEFAULT_cv_height,$id).'&letter_count='.cp_contactformpp_get_option('cv_chars', CP_CONTACTFORMPP_DEFAULT_cv_chars,$id).'&min_size='.cp_contactformpp_get_option('cv_min_font_size', CP_CONTACTFORMPP_DEFAULT_cv_min_font_size,$id).'&max_size='.cp_contactformpp_get_option('cv_max_font_size', CP_CONTACTFORMPP_DEFAULT_cv_max_font_size,$id).'&noise='.cp_contactformpp_get_option('cv_noise', CP_CONTACTFORMPP_DEFAULT_cv_noise,$id).'&noiselength='.cp_contactformpp_get_option('cv_noise_length', CP_CONTACTFORMPP_DEFAULT_cv_noise_length,$id).'&bcolor='.cp_contactformpp_get_option('cv_background', CP_CONTACTFORMPP_DEFAULT_cv_background,$id).'&border='.cp_contactformpp_get_option('cv_border', CP_CONTACTFORMPP_DEFAULT_cv_border,$id).'&font='.cp_contactformpp_get_option('cv_font', CP_CONTACTFORMPP_DEFAULT_cv_font,$id); ?>"  id="captchaimg<?php echo esc_html($CP_CPP_global_form_count); ?>" alt="security code" border="0" class="skip-lazy"  />
  <br />
  <?php echo esc_html(__('Security Code','cp-contact-form-with-paypal')); ?>:<br />
  <div class="dfield">
   <input type="text" size="20" name="hdcaptcha_cp_contact_form_paypal_post" id="hdcaptcha_cp_contact_form_paypal_post<?php echo esc_html($CP_CPP_global_form_count); ?>" value="" />
   <div class="cpefb_error message" id="hdcaptcha_error<?php echo esc_html($CP_CPP_global_form_count); ?>" generated="true" style="display:none;position: absolute; left: 0px; top: 25px;"><?php echo esc_html(__('Incorrect captcha code. Please try again.','cp-contact-form-with-paypal')); ?></div>
  </div>
  <br />
<?php } ?>
</div>
</div>
<div id="cp_subbtn<?php echo esc_html($CP_CPP_global_form_count); ?>" class="cp_subbtn"><?php echo esc_html(__($button_label,'cp-contact-form-with-paypal')); ?></div>
<div style="clear:both"></div>
</form>