var fmcElementor = (function($){
    console.log('fmcElementor init');
    return {
        get_field_name: function(field_name){
            return 'fmc_shortcode_field_' + field_name;
        },
        get_field_id: function(field_name){
            return field_name;
        }        
    }
}(jQuery));