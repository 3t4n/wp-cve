<?php

class BSK_GFCV_Rules {
    
    private static $built_in_rules = array();
    
	public function __construct() {
		$this->init_rules();
        
        add_action( 'wp_ajax_bsk_gfcv_get_rule_html_settings_by_slug', 
                    array( $this, 'bsk_gfcv_get_rule_html_settings_by_slug_ajax_fun' ) );
	}
	
	private static function init_rules(){
		
        self::$built_in_rules['age_between'] = array(
                                 'name' => 'Age must between given years old',
                                 'MIN'  => 0,
                                 'MIN_OPER' => 'M',
                                 'MAX'  => 0,
                                 'MAX_OPER' => 'L',
                                 'settings' => '<label><span>Must</span>#BSK_CV_MIN_OPER#</label>#BSK_CV_MIN#<br />
                                                <label><span>And</span>#BSK_CV_MAX_OPER#</label>#BSK_CV_MAX#',
                                 'message' => 'Age must more than #BSK_CV_MIN# years old',
                                 'hints' => array(
                                                    '#BSK_CV_MIN# will be replaced by the min value when show message in front',
                                                    '#BSK_CV_MAX# will be replaced by the max value when show message in front',
                                                    '#BSK_CV_FIELD_LABEL# will be replaced by the field lable when show message in front',
                                                 ),
                                 'pro' => true,
                              );
        self::$built_in_rules['must_be_numberic_betwen'] = array(
                                 'name' => 'Must be numeric value and between given values',
                                 'MIN'  => 0,
                                 'MIN_OPER' => 'M',
                                 'MAX'  => 0,
                                 'MAX_OPER' => 'L',
                                 'ALLOW_PLUS' => 'YES',
                                 'ALLOW_MINUS' => 'YES',
                                 'settings' => '<label>Must be numeric</label><br />
                                                <label><span>And</span>#BSK_CV_MIN_OPER#</label>#BSK_CV_MIN#<br />
                                                <label><span>And</span>#BSK_CV_MAX_OPER#</label>#BSK_CV_MAX#<br />
                                                <label>Allow plus sign</label>#BSK_CV_ALLOW_PLUS#<br />
                                                <label>Allow minus sign</label>#BSK_CV_ALLOW_MINUS#',
                                 'message' => 'Must be numeric and between #BSK_CV_MIN# and #BSK_CV_MAX#',
                                 'hints' => array(
                                                    '#BSK_CV_MIN# will be replaced by the min value when show message in front',
                                                    '#BSK_CV_MAX# will be replaced by the max value when show message in front',
                                                    '#BSK_CV_FIELD_LABEL# will be replaced by the field lable when show message in front',
                                                 ),
                                 'pro' => true,
                            );

        self::$built_in_rules['must_be'] = array(
                                 'name' => 'Must be given value',
                                 'TEXT' => '',
                                 'settings' => '<label>Must be value of</label>#BSK_CV_TEXT#',
                                 'settings_hints' => array(
                                                    '<span class="bsk-cv-hitns-title">abc</span> means field value can only be abc',
                                                    '<span class="bsk-cv-hitns-title">[0-9]</span> means field value can only be number',
                                                    '<span class="bsk-cv-hitns-title">[a-zA-Z]</span> means field value can only from alphabeta',
                                                    '<span class="bsk-cv-hitns-title">[0-9a-zA-Z]</span> means field value can be number or letter',
                                                    '<span class="bsk-cv-hitns-title">REGEX:xxxxxxx</span> means regular expression of xxxxxxx, eg: REGEX:(((\+44)? ?(\(0\))? ?)|(0))( ?[0-9]{3,4}){3}, means the Regex you\'d like to validate is: (((\+44)? ?(\(0\))? ?)|(0))( ?[0-9]{3,4}){3} ',
                                                    '<span class="bsk-cv-hitns-title">IN:xxxxxxx</span> means can only be consist of given characters of xxxxxxx, eg: IN:A0xCE, means the filed value can be A or A0 or 0A or x or ACx...',
                                                    '<span class="bsk-gfcv-tips-box" style="display: inline-block;"><strong>REGEX:xxxxxxx</strong>, <strong>IN:xxxxxxx</strong> only available in <a href="'.BSK_GFCV_Dashboard::$_bsk_gfcv_pro_verison_url.'" target="_blank">Pro version</a>.</span>'
                                                 ),
                                 'message' => 'The value must be #BSK_CV_TEXT#',
                                 'hints' => array(
                                                    '#BSK_CV_TEXT# will be replaced by the value you set when show message in front',
                                                    '#BSK_CV_FIELD_LABEL# will be replaced by the field lable when show message in front',
                                                 )
                            );
        self::$built_in_rules['must_not_be'] = array(
                                 'name' => 'Must not be given value',
                                 'TEXT' => '',
                                 'settings' => '<label>Must not be value of</label>#BSK_CV_TEXT#',
                                 'settings_hints' => array(
                                                    '<span class="bsk-cv-hitns-title">abc</span> means field value can only be abc',
                                                    '<span class="bsk-cv-hitns-title">[0-9]</span> means field value can only be number',
                                                    '<span class="bsk-cv-hitns-title">[a-zA-Z]</span> means field value can only from alphabeta',
                                                    '<span class="bsk-cv-hitns-title">[0-9a-zA-Z]</span> means field value can be number or letter',
                                                    '<span class="bsk-cv-hitns-title">REGEX:xxxxxxx</span> means regular expression of xxxxxxx, eg: REGEX:(((\+44)? ?(\(0\))? ?)|(0))( ?[0-9]{3,4}){3}, means the Regex you\'d like to validate is: (((\+44)? ?(\(0\))? ?)|(0))( ?[0-9]{3,4}){3} ',
                                                    '<span class="bsk-cv-hitns-title">IN:xxxxxxx</span> means can only be consist of given characters of xxxxxxx, eg: IN:A0xCE, means the filed value can be A or A0 or 0A or x or ACx...',
                                                    '<span class="bsk-gfcv-tips-box" style="display: inline-block;"><strong>REGEX:xxxxxxx</strong>, <strong>IN:xxxxxxx</strong> only available in <a href="'.BSK_GFCV_Dashboard::$_bsk_gfcv_pro_verison_url.'" target="_blank">Pro version</a>.</span>'
                                                 ),
                                 'message' => 'The value must not be #BSK_CV_TEXT#',
                                 'hints' => array(
                                                    '#BSK_CV_TEXT# will be replaced by the value you set when show message in front',
                                                    '#BSK_CV_FIELD_LABEL# will be replaced by the field lable when show message in front',
                                                 )
                            );
        self::$built_in_rules['position_x_must_be'] = array(
                                 'name' => 'The character at the position X must be',
                                 'NUMBER'  => -1,
                                 'TEXT' => '',
                                 'settings' => '<label>The character at position</label>#BSK_CV_NUMBER# <br />
                                                <label>Must be</label>#BSK_CV_TEXT#',
                                 'settings_hints' => array(
                                                    'Position start from 1',
                                                    '<span class="bsk-cv-hitns-title">x</span> means field value can only be x',
                                                    '<span class="bsk-cv-hitns-title">[0-9]</span> means field value can only be number',
                                                    '<span class="bsk-cv-hitns-title">[a-zA-Z]</span> means field value can only from alphabeta',
                                                    '<span class="bsk-cv-hitns-title">[0-9a-zA-Z]</span> means field value can be number or letter',
                                                    '<span class="bsk-cv-hitns-title">REGEX:xxxxxxx</span> means regular expression of xxxxxxx, eg: REGEX:(((\+44)? ?(\(0\))? ?)|(0))( ?[0-9]{3,4}){3}, means the Regex you\'d like to validate is: (((\+44)? ?(\(0\))? ?)|(0))( ?[0-9]{3,4}){3} ',
                                                    '<span class="bsk-cv-hitns-title">IN:xxxxxxx</span> means can only be consist of given characters of xxxxxxx, eg: IN:A0xCE, means the filed value can be A or A0 or 0A or x or ACx...',
                                                 ),
                                 'message' => 'The character at position #BSK_CV_NUMBER# must be #BSK_CV_TEXT#',
                                 'hints' => array(
                                                    '#BSK_CV_NUMBER# will be replaced by the value you set when show message in front',
                                                    '#BSK_CV_TEXT# will be replaced by the value you set',
                                                    '#BSK_CV_FIELD_LABEL# will be replaced by the field lable when show message in front',
                                                 ),
                                 'pro' => true,
                             );
        self::$built_in_rules['position_x_must_not_be'] = array(
                                 'name' => 'The character at the position X must not be',
                                 'NUMBER'  => -1,
                                 'TEXT' => '',
                                 'settings' => '<label>The character at position</label>#BSK_CV_NUMBER# <br />
                                                <label>Must not be</label>#BSK_CV_TEXT#',
                                 'settings_hints' => array(
                                                    'Position start from 1',
                                                    '<span class="bsk-cv-hitns-title">x</span> means field value can only be x',
                                                    '<span class="bsk-cv-hitns-title">[0-9]</span> means field value can only be number',
                                                    '<span class="bsk-cv-hitns-title">[a-zA-Z]</span> means field value can only from alphabeta',
                                                    '<span class="bsk-cv-hitns-title">[0-9a-zA-Z]</span> means field value can be number or letter',
                                                    '<span class="bsk-cv-hitns-title">REGEX:xxxxxxx</span> means regular expression of xxxxxxx, eg: REGEX:(((\+44)? ?(\(0\))? ?)|(0))( ?[0-9]{3,4}){3}, means the Regex you\'d like to validate is: (((\+44)? ?(\(0\))? ?)|(0))( ?[0-9]{3,4}){3} ',
                                                    '<span class="bsk-cv-hitns-title">IN:xxxxxxx</span> means can only be consist of given characters of xxxxxxx, eg: IN:A0xCE, means the filed value can be A or A0 or 0A or x or ACx...',
                                                 ),
                                 'message' => 'The character at position #BSK_CV_NUMBER# must not be #BSK_CV_TEXT#',
                                 'hints' => array(
                                                    '#BSK_CV_NUMBER# will be replaced by the value you set when show message in front',
                                                    '#BSK_CV_TEXT# will be replaced by the value you set',
                                                    '#BSK_CV_FIELD_LABEL# will be replaced by the field lable when show message in front',
                                                 ),
                                 'pro' => true,
                             );
        self::$built_in_rules['r_position_x_must_be'] = array(
                                 'name' => 'The character at the revise position X must be',
                                 'NUMBER'  => -1,
                                 'TEXT' => '',
                                 'settings' => '<label>The character at revise position</label>#BSK_CV_NUMBER# <br />
                                                <label>Must be</label>#BSK_CV_TEXT#',
                                 'settings_hints' => array(
                                                    'Revise position 1 means the last one',
                                                    '<span class="bsk-cv-hitns-title">x</span> means field value can only be x',
                                                    '<span class="bsk-cv-hitns-title">[0-9]</span> means field value can only be number',
                                                    '<span class="bsk-cv-hitns-title">[a-zA-Z]</span> means field value can only from alphabeta',
                                                    '<span class="bsk-cv-hitns-title">[0-9a-zA-Z]</span> means field value can be number or letter',
                                                    '<span class="bsk-cv-hitns-title">REGEX:xxxxxxx</span> means regular expression of xxxxxxx, eg: REGEX:(((\+44)? ?(\(0\))? ?)|(0))( ?[0-9]{3,4}){3}, means the Regex you\'d like to validate is: (((\+44)? ?(\(0\))? ?)|(0))( ?[0-9]{3,4}){3} ',
                                                    '<span class="bsk-cv-hitns-title">IN:xxxxxxx</span> means can only be consist of given characters of xxxxxxx, eg: IN:A0xCE, means the filed value can be A or A0 or 0A or x or ACx...',
                                                 ),
                                 'message' => 'The character at revise position #BSK_CV_NUMBER# must be #BSK_CV_TEXT#',
                                 'hints' => array(
                                                    '#BSK_CV_NUMBER# will be replaced by the value you set when show message in front',
                                                    '#BSK_CV_TEXT# will be replaced by the value you set',
                                                    '#BSK_CV_FIELD_LABEL# will be replaced by the field lable when show message in front',
                                                 ),
                                 'pro' => true,
                             );
        self::$built_in_rules['r_position_x_must_not_be'] = array(
                                 'name' => 'The character at the revise position X must not be',
                                 'NUMBER'  => -1,
                                 'TEXT' => '',
                                 'settings' => '<label>The character at revise position</label>#BSK_CV_NUMBER# <br />
                                                <label>Must not be</label>#BSK_CV_TEXT#',
                                 'settings_hints' => array(
                                                    'Revise position 1 means the last one',
                                                    '<span class="bsk-cv-hitns-title">x</span> means field value can only be x',
                                                    '<span class="bsk-cv-hitns-title">[0-9]</span> means field value can only be number',
                                                    '<span class="bsk-cv-hitns-title">[a-zA-Z]</span> means field value can only from alphabeta',
                                                    '<span class="bsk-cv-hitns-title">[0-9a-zA-Z]</span> means field value can be number or letter',
                                                    '<span class="bsk-cv-hitns-title">REGEX:xxxxxxx</span> means regular expression of xxxxxxx, eg: REGEX:(((\+44)? ?(\(0\))? ?)|(0))( ?[0-9]{3,4}){3}, means the Regex you\'d like to validate is: (((\+44)? ?(\(0\))? ?)|(0))( ?[0-9]{3,4}){3} ',
                                                    '<span class="bsk-cv-hitns-title">IN:xxxxxxx</span> means can only be consist of given characters of xxxxxxx, eg: IN:A0xCE, means the filed value can be A or A0 or 0A or x or ACx...',
                                                 ),
                                 'message' => 'The character at revise position #BSK_CV_NUMBER# must not be #BSK_CV_TEXT#',
                                 'hints' => array(
                                                    '#BSK_CV_NUMBER# will be replaced by the value you set when show message in front',
                                                    '#BSK_CV_TEXT# will be replaced by the value you set',
                                                    '#BSK_CV_FIELD_LABEL# will be replaced by the field lable when show message in front',
                                                 ),
                                 'pro' => true,
                             );
        self::$built_in_rules['length_must_be'] = array(
                                 'name' => 'Length must same as given number',
                                 'NUMBER' => -1,
                                 'settings' => '<label>Length must be</label>#BSK_CV_NUMBER#',
                                 'message' => 'The length must be #BSK_CV_NUMBER#',
                                 'hints' => array(
                                                    '#BSK_CV_NUMBER# will be replaced by the value you set when show message in front',
                                                    '#BSK_CV_FIELD_LABEL# will be replaced by the field lable when show message in front',
                                                 )
                             );
        
        
        self::$built_in_rules['latitude'] = array(
                                 'name' => 'Latitude must be numeric and between given values',
                                 'MIN'  => 0,
                                 'MIN_OPER' => 'M',
                                 'MAX'  => 0,
                                 'MAX_OPER' => 'L',
                                 'settings' => '<label>Must be numeric</label><br />
                                                <label><span>And</span>#BSK_CV_MIN_OPER#</label>#BSK_CV_MIN#<br />
                                                <label><span>And</span>#BSK_CV_MAX_OPER#</label>#BSK_CV_MAX#',
                                 'settings_hints' => array(
                                                    'leave blank means using default values of >= -90 AND <= 90'
                                                 ),
                                 'message' => 'The latitude value must be numeric and between #BSK_CV_MIN# and #BSK_CV_MAX#',
                                 'hints' => array(
                                                    '#BSK_CV_MIN# will be replaced by the min value when show message in front',
                                                    '#BSK_CV_MAX# will be replaced by the max value when show message in front',
                                                    '#BSK_CV_FIELD_LABEL# will be replaced by the field lable when show message in front',
                                                    'Default >= -90 AND <= 90'
                                                 ),
                             );
        
        self::$built_in_rules['longitude'] = array(
                                 'name' => 'Longitude must be numeric and between given values',
                                 'MIN'  => 0,
                                 'MIN_OPER' => 'M',
                                 'MAX'  => 0,
                                 'MAX_OPER' => 'L',
                                 'settings' => '<label>Must be numeric</label><br />
                                                <label><span>And</span>#BSK_CV_MIN_OPER#</label>#BSK_CV_MIN#<br />
                                                <label><span>And</span>#BSK_CV_MAX_OPER#</label>#BSK_CV_MAX#',
                                 'settings_hints' => array(
                                                    'leave blank means using default values of >= -180 AND <= 180'
                                                 ),
                                 'message' => 'The longitude value must be numeric and between #BSK_CV_MIN# and #BSK_CV_MAX#',
                                 'hints' => array(
                                                    '#BSK_CV_MIN# will be replaced by the min value when show message in front',
                                                    '#BSK_CV_MAX# will be replaced by the max value when show message in front',
                                                    '#BSK_CV_FIELD_LABEL# will be replaced by the field lable when show message in front',
                                                 ),
                             );
        
        self::$built_in_rules['checkbox_all'] = array(
                                 'name' => 'Checkbox options must all be checked',
                                 'settings' => 'All options must be checked',
                                 'message' => 'All options must be checked',
                                 'pro' => true,
                             );
        
        self::$built_in_rules['patient_account'] = array(
                             'name' => 'Account - all numbers or fixed format',
                             'settings' => 'Account must all be numbers. <br />Or such as C111111MB( C + 6 numbers + 2 letters ) ',
                             'message' => 'Must all be numbers or such as C111111MB( C + 6 numbers + 2 letters )',
                             'pro' => true,
                         );
	}
    
    public static function get_system_rules_list(){
        if( count(self::$built_in_rules) < 1 ){
            self::init_rules();
        }
        
        $data_to_return = array();
        foreach( self::$built_in_rules as $rule_slug => $details ){
            $data_to_return[$rule_slug] = $details['name'];
        }
        
        return $data_to_return;
    }
    
    public static function get_rule_settings_by_slug( $rule_slug ){
        if( count(self::$built_in_rules) < 1 ){
            self::init_rules();
        }

        if( !array_key_exists( $rule_slug, self::$built_in_rules ) ){
            return false;
        }

        return self::$built_in_rules[$rule_slug];
    }
    
    function bsk_gfcv_get_rule_html_settings_by_slug_ajax_fun(){
        if( !check_ajax_referer( 'bsk-gfcv-rule-ajax-oper', 'nonce', false ) ){
            $array = array( 
                            'status' => false, 
                            'msg' => 'Security check failed or you need refresh the page',
                            'only_pro' => false,
                          );
            wp_die( json_encode( $array ) );
        }
        
        $rule_slug = sanitize_text_field( $_POST['slug'] );
        if( !array_key_exists( $rule_slug, self::$built_in_rules ) ){
            $array = array( 
                            'status' => false, 
                            'msg' => 'Invalid rule',
                            'only_pro' => false,
                          );
            wp_die( json_encode( $array ) );
        }
        
        $rule_details = self::$built_in_rules[$rule_slug];
        /*self::$built_in_rules['age_between'] = array(
                                 'name' => 'Age must between given years old',
                                 'min'  => 0,
                                 'max'  => 0,
                                 'settings' => 'Must be #BSK_CV_MIN#  years older and #BSK_CV_MAX# younger.'
                              );*/
        $return_html = '';
        if( isset( $rule_details['pro'] ) && $rule_details['pro'] ){
            //only available in pro
            $return_html .= '<p>
                                <label class="bsk-gfcv-admin-label"></label>
                                <span class="bsk-gfcv-tips-box" style="display: inline-block;">This rule only available in <a href="'.BSK_GFCV_Dashboard::$_bsk_gfcv_pro_verison_url.'" target="_blank">Pro version</a>.</span>
                             </p>';

        }
        $return_html .= '<h4>Rule settings:</h4>';
        
        $settings = $rule_details['settings'];
        $settings = str_replace( 
                                 '#BSK_CV_MIN_OPER#', 
                                 '<select name="bsk_gfcv_BSK_CV_MIN_OPER"><option value="M">&gt;</option><option value="M_S">&gt;=</option></select>', 
                                 $settings
                               );
        $settings = str_replace( 
                                 '#BSK_CV_MAX_OPER#', 
                                 '<select name="bsk_gfcv_BSK_CV_MAX_OPER"><option value="L">&lt;</option><option value="L_S">&lt;=</option></select>', 
                                 $settings
                               );
        $settings = str_replace( 
                                 '#BSK_CV_MIN#', 
                                 '<input type="number" value="" name="bsk_gfcv_BSK_CV_MIN" class="bsk-gfcv-BSK_CV_MIN" />', 
                                 $settings
                               );
        $settings = str_replace( 
                                 '#BSK_CV_MAX#', 
                                 '<input type="number" value="" name="bsk_gfcv_BSK_CV_MAX" class="bsk-gfcv-BSK_CV_MAX" />', 
                                 $settings 
                               );
        $settings = str_replace( 
                                 '#BSK_CV_TEXT#', 
                                 '<input type="text" value="" name="bsk_gfcv_BSK_CV_TEXT" class="bsk-gfcv-BSK_CV_TEXT" />', 
                                 $settings 
                               );
        
        $settings = str_replace( 
                                 '#BSK_CV_ALLOW_PLUS#', 
                                 '<label style="width:80px;"><input type="radio" value="YES" name="bsk_gfcv_BSK_CV_ALLOW_PLUS" class="bsk-gfcv-BSK_CV_ALLOW_PLUS" checked /> Yes</label><label style="width:80px;"><input type="radio" value="NO" name="bsk_gfcv_BSK_CV_ALLOW_PLUS" class="bsk-gfcv-BSK_CV_ALLOW_PLUS" /> No</label>', 
                                 $settings 
                               );
        $settings = str_replace( 
                                 '#BSK_CV_ALLOW_MINUS#', 
                                 '<label style="width:80px;"><input type="radio" value="YES" name="bsk_gfcv_BSK_CV_ALLOW_MINUS" class="bsk-gfcv-BSK_CV_ALLOW_PLUS" checked /> Yes</label><label style="width:80px;"><input type="radio" value="NO" name="bsk_gfcv_BSK_CV_ALLOW_MINUS" class="bsk-gfcv-BSK_CV_ALLOW_MINUS" /> No</label>', 
                                 $settings 
                               );
        
        if( $rule_slug == 'position_x_must_not_be' ||
            $rule_slug == 'position_x_must_be' ||
            $rule_slug == 'r_position_x_must_not_be' ||
            $rule_slug == 'r_position_x_must_be' ||
            $rule_slug == 'length_must_be' ){
            $settings = str_replace( 
                                 '#BSK_CV_NUMBER#', 
                                 '<input type="number" value="" name="bsk_gfcv_BSK_CV_NUMBER" class="bsk-gfcv-BSK_CV_NUMBER" min="1" />', 
                                 $settings 
                               );
        }else{
            $settings = str_replace( 
                                 '#BSK_CV_NUMBER#', 
                                 '<input type="number" value="" name="bsk_gfcv_BSK_CV_NUMBER" class="bsk-gfcv-BSK_CV_NUMBER" />', 
                                 $settings 
                               );
        }
        
        
        //convert settings to array to organize html
        $settings_array = explode( '<br />', $settings );
        $settings_html = '';
        if ( $settings_array && is_array( $settings_array ) && count( $settings_array ) > 0 ) {
            foreach ( $settings_array as $setting_txt ) {
                $setting_txt = trim( $setting_txt );
                $settings_html .= '<p><label class="bsk-gfcv-admin-label"></label>'.$setting_txt.'</p>';
            }
        }
        $return_html .= $settings_html;
        
        if ( isset( $rule_details['settings_hints'] ) && is_array( $rule_details['hints'] ) && count( $rule_details['hints'] ) > 0 ) {
            foreach( $rule_details['settings_hints'] as $hit ){
                $return_html .= '<p><label class="bsk-gfcv-admin-label"></label>'.$hit.'</p>';
            }
        }
        
        $return_html .= '<h4>Validation message:</h4>';
        $return_html .= '<div class="bsk-gfcv-rule-validation-message-container">';
        $return_html .= '<p>
                            <label class="bsk-gfcv-admin-label"></label>
                            <span class="bsk-gfcv-rule-message" style="cursor: pointer;">'.$rule_details['message'].'</span>
                         </p>';
        $return_html .= '<p>
                            <label class="bsk-gfcv-admin-label"></label>
                            <span class="bsk-gfcv-tips-box" style="display: none;">Validation message can only be changed in <a href="'.BSK_GFCV_Dashboard::$_bsk_gfcv_pro_verison_url.'" target="_blank">Pro version</a>.</span>
                         </p>';
        $return_html .= '</div>';

        if( isset($rule_details['hints']) && is_array($rule_details['hints']) && count($rule_details['hints']) > 0 ){
            foreach( $rule_details['hints'] as $hit ){
                $return_html .= '<p><label class="bsk-gfcv-admin-label"></label>'.$hit.'</p>';
            }
        }
        
        $only_pro = isset( $rule_details['pro'] ) && $rule_details['pro'] ? true : false;
        $array = array( 
                         'status' => true, 
                         'html' => $return_html,
                         'only_pro' => $only_pro,
                      );
        wp_die( json_encode( $array ) );
    }
    
    public static function validation_rule( $rule_saved_setting, $value_to_check ){
        
        $message = '';
        if( isset( self::$built_in_rules[$rule_saved_setting['slug']] ) ){
            $message = self::$built_in_rules[$rule_saved_setting['slug']]['message'];
        }
        if( isset($rule_saved_setting['MIN']) ){
            $message = str_replace( '#BSK_CV_MIN#', $rule_saved_setting['MIN'], $message );
        }
        if( isset($rule_saved_setting['MAX']) ){
            $message = str_replace( '#BSK_CV_MAX#', $rule_saved_setting['MAX'], $message );
        }
        if( isset($rule_saved_setting['TEXT']) ){
            $message = str_replace( '#BSK_CV_TEXT#', $rule_saved_setting['TEXT'], $message );
        }
        if( isset($rule_saved_setting['NUMBER']) ){
            $message = str_replace( '#BSK_CV_NUMBER#', $rule_saved_setting['NUMBER'], $message );
        }

        switch( $rule_saved_setting['slug'] ){
            
            case 'must_be':
                $numbers = strpos( $rule_saved_setting['TEXT'], '[0-9]' ) === false ? false : true;
                $alphabetas = strpos( $rule_saved_setting['TEXT'], '[a-zA-Z]' ) === false ? false : true;
                $pattern = '';
                if( $numbers && $alphabetas ){
                    $pattern = '/[0-9a-zA-Z]/';
                }else if( $numbers ){
                    $pattern = '/[0-9]/';
                }else if( $alphabetas ){
                    $pattern = '/[a-zA-Z]/';
                }
                if( $pattern ){
                    if( preg_replace( $pattern, '', $value_to_check ) != '' ){
                        return array( 'result' => false, 'message' => $message );
                    }
                }else{
                    if( $value_to_check != $rule_saved_setting['TEXT'] ){
                        return array( 'result' => false, 'message' => $message );
                    }
                }
            break;
                
            case 'must_not_be':
                $numbers = strpos( $rule_saved_setting['TEXT'], '[0-9]' ) === false ? false : true;
                $alphabetas = strpos( $rule_saved_setting['TEXT'], '[a-zA-Z]' ) === false ? false : true;
                $pattern = '';
                if( $numbers && $alphabetas ){
                    $pattern = '/[0-9a-zA-Z]/';
                }else if( $numbers ){
                    $pattern = '/[0-9]/';
                }else if( $alphabetas ){
                    $pattern = '/[a-zA-Z]/';
                }
                if( $pattern ){
                    if( preg_replace( $pattern, '', $value_to_check ) != '' ){
                        //
                    } else {
                        return array( 'result' => false, 'message' => $message );
                    }
                }else{
                    if( $value_to_check != $rule_saved_setting['TEXT'] ){
                        //
                    } else {
                        return array( 'result' => false, 'message' => $message );
                    }
                }
            break;

            case 'length_must_be':
                if( strlen( $value_to_check ) != $rule_saved_setting['NUMBER'] ){
                    return array( 'result' => false, 'message' => $message );
                }
            break;

            case 'must_not_be':
            case 'position_x_must_not_be':
            case 'r_position_x_must_not_be':
                return array( 'result' => true, 'message' => '' );
            break;

            case 'latitude':
                $latitude_val = $value_to_check;
                $divider = strpos( $value_to_check, ',' );
                if( $divider !== false ){
                    $latitude_val = substr( $latitude_val, 0, $divider );
                }
                $latitude_val = trim( $latitude_val );
                if( !is_numeric($latitude_val) ){
                    return array( 'result' => false, 'message' => $message );
                }
                if( $latitude_val < -90 || $latitude_val > 90 ){
                    return array( 'result' => false, 'message' => $message );
                }

                if( $rule_saved_setting['MIN_OPER'] == 'M' &&
                    $latitude_val <= floatval($rule_saved_setting['MIN']) ){

                    return array( 'result' => false, 'message' => $message );
                }
                if( $rule_saved_setting['MIN_OPER'] == 'M_S' &&
                    $latitude_val < floatval($rule_saved_setting['MIN']) ){

                    return array( 'result' => false, 'message' => $message );
                }
                if( $rule_saved_setting['MAX_OPER'] == 'L' &&
                    $latitude_val >= floatval($rule_saved_setting['MAX']) ){

                    return array( 'result' => false, 'message' => $message );
                }
                if( $rule_saved_setting['MAX_OPER'] == 'L_S' &&
                    $latitude_val > floatval($rule_saved_setting['MAX']) ){

                    return array( 'result' => false, 'message' => $message );
                }
            break;

            case 'longitude':
                $latitude_val = $value_to_check;
                $divider = strpos( $value_to_check, ',' );
                if( $divider !== false ){
                    $latitude_val = substr( $latitude_val, 0, $divider );
                }
                $latitude_val = trim( $latitude_val );
                if( !is_numeric($latitude_val) ){
                    return array( 'result' => false, 'message' => $message );
                }
                if( $latitude_val < -90 || $latitude_val > 90 ){
                    return array( 'result' => false, 'message' => $message );
                }

                if( $rule_saved_setting['MIN_OPER'] == 'M' &&
                    $latitude_val <= floatval($rule_saved_setting['MIN']) ){

                    return array( 'result' => false, 'message' => $message );
                }
                if( $rule_saved_setting['MIN_OPER'] == 'M_S' &&
                    $latitude_val < floatval($rule_saved_setting['MIN']) ){

                    return array( 'result' => false, 'message' => $message );
                }
                if( $rule_saved_setting['MAX_OPER'] == 'L' &&
                    $latitude_val >= floatval($rule_saved_setting['MAX']) ){

                    return array( 'result' => false, 'message' => $message );
                }
                if( $rule_saved_setting['MAX_OPER'] == 'L_S' &&
                    $latitude_val > floatval($rule_saved_setting['MAX']) ){

                    return array( 'result' => false, 'message' => $message );
                }
            break;
        }
        
        return array( 'result' => true, 'message' => '' );
    }
}