(function ($) {
    'use strict';

    let tooltipster_args = {
        distance: 5, // distance between the origin and the tooltip, in pixels
        maxWidth: 480, // maximum width for the tooltip
        theme: 'encyclopedia-tooltip', // this will be added as class to the tooltip wrapper
    };

    Encyclopedia_Tooltips.$links = $('a.encyclopedia, .widget_encyclopedia_taxonomies ul.taxonomy-list li.cat-item > a');

    // initialize the tooltips
    Encyclopedia_Tooltips.links = Encyclopedia_Tooltips.$links.tooltipster(tooltipster_args);

}(jQuery));
