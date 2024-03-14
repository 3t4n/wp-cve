jQuery(document).ready(function () {
    var machine_id_field;

    window.easyPackAsyncInit = (function () {
        easyPack.init({});
    });

    jQuery('.settings-geowidget').click(function (e) {
        machine_id_field = jQuery(this);
        e.preventDefault();
        easyPack.init({
            apiEndpoint: 'https://api-pl-points.easypack24.net/v1',
            defaultLocale: 'pl',
            closeTooltip: false,
            points: {
                types: ['parcel_locker', 'pop']
            },
            map: {

                useGeolocation: true
            }
        });
        easyPack.modalMap(function (point) {
            machine_id_field.val(point.name);
            jQuery('#selected-parcel-machine').removeClass('hidden');
            jQuery('#selected-parcel-machine-id').html(parcelMachineAddressDesc);
        }, {width: 500, height: 600});

        setTimeout(function () {
            jQuery("html, body").animate({scrollTop: jQuery('#widget-modal').offset().top}, 1000);

        }, 0);
    });
});





