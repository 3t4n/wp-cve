dayjs.extend(window.dayjs_plugin_customParseFormat);
jQuery(document).ready(function (){
    var config = {
        dateFormat: 'd-m-Y',
        disableMobile: 'true',
        enableTime: false,
        mode: "range",
        locale: resolveLang(locale),
        onChange: function(data){
            var pickUpDate = data[0];
            var returnDate = data[1];
            jQuery('#hq_pick_up_date').val(parseDateToText(pickUpDate, 'DD-MM-YYYY'));
            jQuery('#hq_return_date').val(parseDateToText(returnDate, 'DD-MM-YYYY'));
        }
    };
    var dateFormatMoment = 'DD-MM-YYYY';
    flatpickr('#hq-daterange', config);
    var pickDefault = dayjs().add(1, 'day').add(15,'minute').format(dateFormatMoment);
    var returnDefault = dayjs().add(1, 'day').add(3,'day').add(15,'minute').format(dateFormatMoment);
    jQuery('#hq_pick_up_date').val(pickDefault);
    jQuery('#hq_return_date').val(returnDefault);
    jQuery('#hq-daterange').val(pickDefault + ' to ' + returnDefault);
    jQuery('#hq_pick_up_time').on('change',function(e){
        jQuery('#hq_return_time').val( jQuery('#hq_pick_up_time').val() );
    });
    jQuery('#hq_pick_up_location').on('change',function(){
        jQuery('#hq_return_location').val(jQuery('#hq_pick_up_location').val());
        if(hqRentalsLocations && Array.isArray(hqRentalsLocations)){
            var locationsFiltered = hqRentalsLocations.filter(function(location){
                return String(location.id) === String(jQuery('#hq_pick_up_location').val());
            })
            jQuery('#location-tag').html(locationsFiltered[0].name);
        }

    });
    jQuery('#hq_vehicle_class_id').on('change',function(){
        if(hqRentalsVehicles && Array.isArray(hqRentalsVehicles)){
            var vehiclesFileteres = hqRentalsVehicles.filter(function(vehicle){
                return String(vehicle.id) === String(jQuery('#hq_vehicle_class_id').val());
            })
            jQuery('#vehicle-tag').html(vehiclesFileteres[0].name);
        }
    });
});

function parseDateToText(date,format){
    var day = dayjs(date);
    return day.format(format);
}
function resolveLang(locale){
    if(locale === 'es_ES'){
        return es.Spanish;
    }
    if(locale === 'fr_CA'){
        return fr.French;
    }
    return null;
}