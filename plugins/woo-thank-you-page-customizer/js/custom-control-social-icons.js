"use strict";
jQuery(document).ready(function ($) {
    $('.wtyp-radio-icons-label').on('click',function () {
        $(this).parent().find('.wtyp-radio-icons-label').removeClass('wtyp-radio-icons-active');
        $(this).addClass('wtyp-radio-icons-active');
    })
});
