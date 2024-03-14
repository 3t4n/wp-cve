

// Toggles ACF Repeater rows in page editor view
(function($) {
    var repeaterTitle;
    $(document).ready(function() {
        $('div.repeater').hide();
        $('.acf-input-table .row').each(function() {
            $(' table.acf_input', this).addClass('hideAcfRow');
            repeaterTitle = $(".acf_input-wrap .sub_field .acf-input-wrap input", this).val();
            var titleLink = "<a href='javascript:void(0);' onclick='acfDisplayItem(this);' class='acfAccordionRow'>" + repeaterTitle + "</a>";
            $(' table.acf_input', this).before(titleLink);

        });
        $('div.repeater').show();
    });
})(jQuery);	
function acfDisplayItem(e) {
    jQuery(e).toggleClass('acfAccordionExpanded').next().toggle();
}