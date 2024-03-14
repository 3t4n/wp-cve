<?php
/**
 * Class Nv_Comment_Form_Js_Validation_Captcha
 */
class Nv_Comment_Form_Js_Validation_Captcha
{
    /**
     * Holds the values to be used in the fields callbacks
     */
    private $nv_cfjv_setting;
    
    /**
     * Start up
     */
    public function __construct( $nv_cfjv_setting )
    {
        $this->nv_cfjv_setting = $nv_cfjv_setting;

        if(isset($this->nv_cfjv_setting['comment_enable_google_captcha']) && $this->nv_cfjv_setting['comment_enable_google_captcha'] == 1)
            add_filter( 'comment_form_default_fields', array( $this, 'nv_cfjv_recaptcha_field' ) );
    
    }

    /**
     * reCaptcha field
     */
    public function nv_cfjv_recaptcha_field($fields) {
        $fields['captcha'] = '<p>
                    <div class="g-recaptcha" id="comment_form_recaptcha"></div>
                    <input type="hidden" class="hiddenRecaptcha required" name="hidden_recaptcha_comment" id="hidden_recaptcha_comment">
                </p>';

        return $fields;
    }
}
?>