(function($){
    var configDate = {
        format: hq_plugin_global_date_format
    };
    $("#hq-pickup-date").datetimepicker(configDate);
    $("#hq-return-date").datetimepicker(configDate);
    $("#hq-brands-form-selection").on("change",function(){
        $("#hq-brands-form").attr( 'action' , hq_motors_brands_data[$("#hq-brands-form-selection").val()].page_link );
        var newOptions = getOptionsFromBrandInformation($("#hq-brands-form-selection").val(), hq_motors_brands_data);
        $("#hq-brands-pick-up-location option").remove();
        $("#hq-brands-pick-up-location").append(newOptions);
        $("#hq-brands-return-location option").remove();
        $("#hq-brands-return-location").append(newOptions);
    });
})(jQuery);

function getOptionsFromBrandInformation(id, data){
    var options = '<option value="">Select Pickup Location</option>';
    data[id].locations.forEach(function(item){
        options += '<option value="'+ item.id +'">' + item.name + '</option>';
    });
    return options;
}