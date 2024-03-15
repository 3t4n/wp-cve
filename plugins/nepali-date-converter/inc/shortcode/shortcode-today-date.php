<?php
add_shortcode('ndc-today-date','ndc_today_shortcode');
if ( ! function_exists( 'ndc_today_shortcode' ) ) :

    function ndc_today_shortcode($atts){

        extract(shortcode_atts(array(
            'before' => '',
            'after' => '',
            'before_title' => '',
            'after_title' => '',
            'title' => __( 'Date', 'nepali-date-converter' ),
            'disable_today_nep_date' =>'',
            'disable_today_eng_date' =>'',
            'nepali_date_lang' => 'nep_char',
            'result_format' => 'D, F j, Y'
        ), $atts));
        ob_start();

        $front_date_converter = ndc_frontend();
        $front_date_converter->today_date(array(
            'before'=> $before,
            'after'=> $after,
            'before_title'=> $before_title,
            'after_title'=> $after_title,
            'title'=> $title,
            'disable_today_nep_date'=> $disable_today_nep_date,
            'disable_today_eng_date'=> $disable_today_eng_date,
            'nepali_date_lang'=> $nepali_date_lang,
            'result_format'=> $result_format
        ));
        $content = ob_get_contents();
        ob_end_clean();
        return $content;
    }

endif;