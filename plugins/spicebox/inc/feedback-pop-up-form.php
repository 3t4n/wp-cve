<?php 
function spiceb_spicepress_feedback_scripts() {

wp_enqueue_style('spiceb_style',SPICEB_PLUGIN_URL.'inc/css/feedback-popup.css');

wp_enqueue_style('spiceb_formcss1',SPICEB_PLUGIN_URL.'inc/css/formCss.css');
wp_enqueue_style('spiceb_formcss2','https://cdn.jotfor.ms/css/printForm.css');
wp_enqueue_style('spiceb_formcss3', SPICEB_PLUGIN_URL.'inc/css/nova.css');
wp_enqueue_style('spiceb_formcss4','https://cdn.jotfor.ms/themes/CSS/566a91c2977cdfcd478b4567.css');
wp_enqueue_style('spiceb_custom1',SPICEB_PLUGIN_URL.'inc/css/custom-one.css');
wp_enqueue_style('spiceb_custome2',SPICEB_PLUGIN_URL.'inc/css/custom-two.css');

wp_enqueue_script( 'spiceb-prototype-form', SPICEB_PLUGIN_URL.'inc/js/prototype.forms.js',array('jquery'), '', true);
wp_enqueue_script( 'spiceb-jotform-js', SPICEB_PLUGIN_URL.'inc/js/jotform.forms.js',array('jquery'), '', true);
wp_register_script('spiceb-custom-script', SPICEB_PLUGIN_URL. 'inc/js/custom-js.js',array('jquery','wp-color-picker'),'1.1', true);
wp_enqueue_script('spiceb-custom-script');
}
add_action( 'admin_enqueue_scripts', 'spiceb_spicepress_feedback_scripts' );
error_reporting(0);?>
<div class="dialog-widget dialog-lightbox-widget dialog-type-buttons dialog-type-lightbox" id="spicepress-deactivate-feedback-modal" style="display: block;">

<form class="jotform-form" action="https://submit.jotform.me/submit/91673332832458/" method="post" name="form_91673332832458" id="91673332832458" accept-charset="utf-8">
  <input type="hidden" name="formID" value="91673332832458" />
  <div role="main" class="form-all">
    <ul class="form-section page-section">
      <li class="form-line" data-type="control_text" id="id_4">
        <div id="cid_4" class="form-input-wide">
          <div id="text_4" class="form-html" data-component="text">
            <p><span style="font-family: helvetica, arial, sans-serif;"><strong><span style="font-size: 18pt;"><?php _e('Quick Feedback','spicebox');?></span></strong></span></p>
            <p><span style="font-size: 10pt; font-family: helvetica, arial, sans-serif;"><?php _e('Your feedback is valuable to us.','spicebox');?></span></p>
          </div>
        </div>
      </li>
      <li class="form-line" data-type="control_radio" id="id_3">
        <label class="form-label form-label-top" id="label_3" for="input_3">  </label>
        <div id="cid_3" class="form-input-wide">
          <div class="form-single-column" data-component="radio">
            <span class="form-radio-item" style="clear:left">
              <span class="dragger-item">
              </span>
              <input type="radio" class="form-radio" id="input_3_0" name="q3_input3" value="I found a better theme" />
              <label id="label_input_3_0" for="input_3_0"> <?php _e('I found a better theme','spicebox');?> </label>
            </span>
            <span class="form-radio-item" style="clear:left">
              <span class="dragger-item">
              </span>
              <input type="radio" class="form-radio" id="input_3_1" name="q3_input3" value="It&#x27;s a temporary deactivation. I&#x27;m just debugging an issue." />
              <label id="label_input_3_1" for="input_3_1"> <?php _e("It's a temporary deactivation. I'm just debugging an issue.","spicepress");?> </label>
            </span>
            <span class="form-radio-item" style="clear:left">
              <span class="dragger-item">
              </span>
              <input type="radio" class="form-radio" id="input_3_2" name="q3_input3" value="It does not have a feature I require. If possible, mention them in text box." />
              <label id="label_input_3_2" for="input_3_2"> <?php _e('It does not have a feature I require. If possible, mention them in text box.','spicebox');?> </label>
            </span>
          </div>
        </div>
      </li>
      <li class="form-line" data-type="control_textarea" id="id_6">
        <label class="form-label form-label-top form-label-auto" id="label_6" for="input_6"> <?php _e('Others','spicebox');?> </label>
        <div id="cid_6" class="form-input-wide">
          <span class="form-sub-label-container" style="vertical-align:top">
            <textarea id="input_6" class="form-textarea" name="q6_others" cols="40" rows="6" data-component="textarea" aria-labelledby="label_6 sublabel_input_6"></textarea>
            <label class="form-sub-label" for="input_6" id="sublabel_input_6" style="min-height:13px"> <?php _e('Your feedback means a lot to us','spicebox');?> </label>
          </span>
        </div>
      </li>
      <li class="form-line form-line-column form-col-1" data-type="control_button" id="id_2">
        <div id="cid_2" class="form-input-wide">
          <div style="text-align:left" class="form-buttons-wrapper">
            <button id="input_2" type="submit" class="form-submit-button" data-component="button">
              <?php _e('Submit Feedback','spicebox');?>
            </button>
          </div>
        </div>
      </li>
      <li class="form-line form-line-column form-col-2" data-type="control_text" id="id_5">
        <div id="cid_5" class="form-input-wide">
          <div id="text_5" class="form-html" data-component="text">
            <p><a class="form-deactivation" href="#" rel="nofollow" onclick="window.location.reload()"><?php _e('Skip & Deactive','spicebox');?></a></p>
          </div>
        </div>
      </li>
    
      <li style="display:none">
        Should be Empty:
        <input type="text" name="website" value="" />
      </li>
    </ul>
  </div>
  <script>
  JotForm.showJotFormPowered = "new_footer";
  </script>
  <input type="hidden" id="simple_spc" name="simple_spc" value="91673332832458" />
  <script type="text/javascript">
  document.getElementById("si" + "mple" + "_spc").value = "91673332832458-91673332832458";
  </script>
  <div class="formFooter-heightMask">
  </div>
  <div class="formFooter f6">
    <a href="https://www.jotform.com/pricing?utm_source=formfooter&utm_medium=banner&utm_term=91673332832458&utm_content=jotform_logo&utm_campaign=powered_by_jotform_le" target="_blank" class="formFooter-logoLink"><img class="formFooter-logo" src="https://cdn.jotfor.ms/assets/img/logo/logo-new@1x.png" alt="" style="height: 44px;"></a>
    <div class="formFooter-rightSide">
      <span class="formFooter-text">
        <?php _e("Now create your own JotForm - It's free!","spicepress");?>
      </span>
      <a class="formFooter-button" href="https://www.jotform.com/?utm_source=formfooter&utm_medium=banner&utm_term=91673332832458&utm_content=jotform_button&utm_campaign=powered_by_jotform_le" target="_blank"><?php _e('Create your own JotForm','spicebox');?></a>
    </div>
  </div>
  <input type="hidden" id="input_7" name="q7_typeA" class="form-hidden" value="<?php
                global $wp;
echo home_url( $wp->request ).'/wp-admin/themes.php';
?>" data-component="hidden" />
</form>
  </div>

  <?php
  unset( $_GET['action'] );
    require ABSPATH . 'wp-admin/themes.php';