(function($) {
    "use strict";

	/* ===============================  Group loop  =============================== */

    if($('.better-heading.style-10').length){
        $('.better-heading.style-10').grouploop({

            // animation speed
            velocity: 2,

            // false = from left to right
            forward: false,

            // default selectors
            childNode: ".item",
            childWrapper: ".item-wrap",

            // enable pause on hover
            pauseOnHover: false,

            // stick the first item
            stickFirstItem: false,

            // callback
            complete: null

        });
    }

})(jQuery);