(function($, dataId){
    
    $(document).ready(function(){
        var start_settings = $("[dataId=" + dataId + "]").val();
        var type = $(".flexmls_connect__stat_type").val();
        var options_start = [];

        start_settings = start_settings.split(',');
        
        var options = flexmls_connect.report_options[type];
        options_start = options.map(item => {
            if(start_settings.indexOf(item.value) > -1){
                item.selected = true;
            } else {
                item.selected = false;
            }
            return item;
        })

        makeSelected(options_start);

        $('select.flexmls_connect__stat_type').on("change", function () {
            
            var options = flexmls_connect.report_options[$(":selected", this).val()];
            
            makeSelected(options);
        });

        function makeSelected(options){
            var display_select = $("select.flexmls_connect__stat_display", '.vc_edit_form_elements');
            display_select.html("");
    
            // populate the select box with the appropriate options
            for (var x = 0; x < options.length; x++) {
                $("<option value='" + options[x].value + "' " + (options[x].selected ? "selected='selected'" : "") + ">" + options[x].label + "</option>").appendTo(display_select);
            }
        }

    })
})(jQuery, vce_dd)
