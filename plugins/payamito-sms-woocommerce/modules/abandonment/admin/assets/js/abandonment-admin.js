"use strict";

jQuery(document).ready(function ($) {


    const frequency_input = $("div").find(`[data-depend-id='frequency']`).find('input');


    frequency_input.on('change', function (e) {
        let $this = $(this);
        if ($this.val() < 20) {
            $this.val(20);
        }
    });

})
