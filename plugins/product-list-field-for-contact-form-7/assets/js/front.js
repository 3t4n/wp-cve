jQuery( document ).ready(function() {
    var forms = document.getElementsByTagName('form');
    for (var i = 0; i < forms.length; i++) {

        jQuery('select.wpcf7-products', forms[i]).each(function() {
            var placeholder = jQuery(this).attr('placeholder');
            var allow_clear = (jQuery(this).attr('allow-clear') == 'true') ? true : false;
            var products_search_box = (jQuery(this).attr('search-box') == 'true') ? 2 : Infinity;
           jQuery(this).select2({
                placeholder : placeholder,
                allowClear : allow_clear,
                minimumResultsForSearch : products_search_box,
                templateResult: formatOptions_product
            });
        });
    }
});

function formatOptions_product (state) {
    if (!state.id) { return state.text; }
    var pro_imageformat = jQuery(state.element).data('pro_image_url');
    var pro_contentdata = jQuery(state.element).data('pro_content');
    var width = jQuery(state.element).data('width');
    var metas = jQuery(state.element).data('meta');
    var meta_data = '';
    if(metas){
        var meta = jQuery(state.element).data('meta').split('|');
    } else {
        var meta = '';
    }
    if(pro_imageformat != undefined) {
            thumbnail = "<img style='width:" + width + "px; display: inline-block;' src='" + pro_imageformat + "''  />";
    } else {
        thumbnail = '';
    }
    if(pro_contentdata === undefined){
            	pro_description = '';
            } else {
            	pro_description = '<strong>Price </strong>'+pro_contentdata; 
          }	
    if(meta != undefined){
        if(meta.length > 0) {
            meta_data = "<div class='wplfcf7_meta_data'><ul>";

            jQuery.each(meta, function( index, value ) {
                if(value != ''){
                    meta_data += "<li>" + value + "</li>";
                }
            });

            meta_data += "</ul></div>";
        }
    }
    var $state = jQuery(
    '<div class="wplfcf7_main woocommerce"><div class="wplfcf7_left_box">' + thumbnail + '</div><div class="wplfcf7_right_box"><div class="wplfcf7_title" >' + state.text + '</div><div class="wplfcf7_description" >' + pro_description + '</div>' + meta_data + '</div></div>'
    );
    return $state;
}