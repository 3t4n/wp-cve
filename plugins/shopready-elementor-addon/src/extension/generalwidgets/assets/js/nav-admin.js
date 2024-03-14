'use strict';

/*
 * Mega menu
 */

    (function() {
        var select_tags = document.querySelectorAll('select.woo-ready-selectbox'); 
        
        [].forEach.call(select_tags, function(select_tag) {
            var title = select_tag.dataset.title;
            new BVSelect({
                selector: "#"+select_tag.id,
                width: "70%",
                searchbox: true,
                offset: false,
                placeholder: "Select "+title+ ' template',
                search_placeholder: "Search...",
                search_autofocus: true,
                breakpoint: 450
            });
        });
    })();
