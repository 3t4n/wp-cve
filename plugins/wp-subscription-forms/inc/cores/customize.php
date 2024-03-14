<?php

global $wpsf_mobile_detector;
if (!empty($form_details['layout']['form_width']) && !$wpsf_mobile_detector->isMobile() && !$wpsf_mobile_detector->isTablet()) {
    $form_width = esc_attr($form_details['layout']['form_width']);
    $form_width_css = ".$alias_class{max-width:$form_width !important;}";
    wp_add_inline_style('wpsf-frontend-custom', $form_width_css);
}