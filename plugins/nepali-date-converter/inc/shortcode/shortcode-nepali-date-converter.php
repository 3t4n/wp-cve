<?php
add_shortcode('nepali-date-converter','ndc_shortcode');

if ( ! function_exists( 'ndc_shortcode' ) ) :
    function ndc_shortcode($atts){

        extract(shortcode_atts(array(
            'before' => '',
            'after' => '',
            'before_title' => '',
            'after_title' => '',
            'title' => __( 'Nepali Date Converter', 'nepali-date-converter' ),
            'disable_ndc_convert_nep_to_eng' =>'',
            'disable_ndc_convert_eng_to_nep' =>'',
            'nep_to_eng_button_text' => __('Nepali to English','nepali-date-converter'),
            'eng_to_nep_button_text' => __('English to Nepali','nepali-date-converter'),
            'result_format' => 'D, F j, Y',
            'nepali_date_lang' => 'nep_char'
        ), $atts));
        ob_start();

        $front_date_converter = ndc_frontend();
        $front_date_converter->nepali_date_converter(array(
            'before'=> $before,
            'after'=> $after,
            'before_title'=> $before_title,
            'after_title'=> $after_title,
            'title'=> $title,
            'disable_ndc_convert_nep_to_eng'=> $disable_ndc_convert_nep_to_eng,
            'disable_ndc_convert_eng_to_nep'=> $disable_ndc_convert_eng_to_nep,
            'nep_to_eng_button_text'=> $nep_to_eng_button_text,
            'eng_to_nep_button_text'=> $eng_to_nep_button_text,
            'result_format'=> $result_format
        ));
        $content = ob_get_contents();
        ob_end_clean();
        return $content;
    }
endif;