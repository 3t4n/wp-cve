<?php

/**
 * @author DemonIa sanchoclo@gmail.com
 * @function mightyforms_shortcode_handler
 * @description This function render iframe by given form id
 * @param $atts
 * @return string
 */
function mightyforms_shortcode_handler($atts)
{
    return '<!-- MightyForms Section -->
    <div class="mighty-form" id="' . $atts['id'] . '"></div>
    <script async src="https://form.mightyforms.com/loader/v1/mightyforms.min.js"></script>
    <!-- End MightyForms Section -->';
}

add_shortcode('mightyforms', 'mightyforms_shortcode_handler');

