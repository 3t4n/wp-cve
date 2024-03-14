var geowidgetModal;

function selectPointCallback(point) {
    jQuery('#easypack_default_machine_id').val(point.name);
    jQuery('#parcel_machine_id').val(point.name);
    geowidgetModal.close()
}

jQuery(document).ready(function () {

    config = jQuery('#parcel_machine_id').data('geowidget_config');
    if (config === undefined) {
        config = jQuery('#easypack_default_machine_id').data('geowidget_config')
    }
    //console.log(config);

    geowidgetModal = new jBox('Modal', {
        width: easypackAdminGeowidgetSettings.width,
        height: easypackAdminGeowidgetSettings.height,
        attach: '.settings-geowidget',
        title: easypackAdminGeowidgetSettings.title,
        content: '<inpost-geowidget onpoint="selectPointCallback" token="' + easypackAdminGeowidgetSettings.token + '" language="pl" config="' + config + '"></inpost-geowidget>'
    });


    jQuery('.settings-geowidget').click(function (e) {
        e.preventDefault();
    });


});
