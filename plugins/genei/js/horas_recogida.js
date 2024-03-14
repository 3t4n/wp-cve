
jQuery(function () {
    jQuery("#fecha_recogida").datepicker({minDate: 0, dateFormat: 'dd/mm/yy', beforeShowDay: jQuery.datepicker.noWeekends});
    
});

jQuery(document).ready(function ($) {
    jQuery("#id_d_intervalo").change(function () {
        cambiar_desde();
    });
    jQuery("#id_h_intervalo").change(function () {
        cambiar_hasta();
    });
});
 

function cambiar_desde() {
    var valor_desde = jQuery("#id_d_intervalo option:selected").val();
    var momento_enviado = jQuery('#fecha_recogida').val() + ' ' + valor_desde;
    var nuevo_valor_hasta = moment(momento_enviado, 'DD/MM/YYYY HH:mm').add(intervalo_recogida, 'hour');
    jQuery('#id_h_intervalo').val(moment(nuevo_valor_hasta).format('HH:mm'));
}

function cambiar_hasta() {
    var valor_hasta = jQuery("#id_h_intervalo option:selected").val();
    var valor_desde = jQuery("#id_d_intervalo option:selected").val();
    var momento_enviado_hasta = jQuery('#fecha_recogida').val() + ' ' + valor_hasta;
    var momento_enviado_desde = jQuery('#fecha_recogida').val() + ' ' + valor_desde;
    var nuevo_valor_desde = moment(momento_enviado_hasta, 'DD/MM/YYYY HH:mm').subtract(intervalo_recogida, 'hour');
    nuevo_valor_desde_formateado = moment(nuevo_valor_desde).format('YYYY-MM-DD HH:mm');
    if (nuevo_valor_desde < moment(momento_enviado_desde, 'DD/MM/YYYY HH:mm'))
        jQuery('#id_d_intervalo').val(moment(nuevo_valor_desde_formateado).format('HH:mm'));
}