jQuery(document).ready(function (jQuery) {
    if (jQuery('#grupoimpultec_cajas_personalizadas').children('option').length < 1) {            
            jQuery('#grupoimpultec_tipo_calculo_precio_p_1').prop('checked', true);
            jQuery('#grupoimpultec_tipo_calculo_precio_p_2').prop('checked', false);            
            jQuery('#grupoimpultec_tipo_calculo_precio_p_2').prop('disabled', true);            
            jQuery('#grupoimpultec_tipo_calculo_precio_p_2').hide();            
            desactivar_cajas();
        }
    if (jQuery('[name = "grupoimpultec_tipo_calculo_precio_p"]:checked').val() == '1') {
        jQuery('#grupoimpultec_max_weigth_box').prop('disabled', true);
        jQuery('#grupoimpultec_name_box').prop('disabled', true);        
        jQuery('#grupoimpultec_width_box').prop('disabled', true);
        jQuery('#grupoimpultec_height_box').prop('disabled', true);
        jQuery('#grupoimpultec_length_box').prop('disabled', true);
        jQuery('#grupoimpultec_cajas_personalizadas').prop('disabled', true);
    } 
    jQuery('[name = "grupoimpultec_tipo_calculo_precio_p"]').change(function () {
        if (jQuery(this).val() == '1') {
            desactivar_cajas();
        }
        if (jQuery(this).val() == '2') {
            activar_cajas();
            actualizar_valor_caja_personalizada();

        }
    });

    jQuery("#grupoimpultec_cajas_personalizadas").change(function () {
        actualizar_valor_caja_personalizada();
    });

});

function actualizar_valor_caja_personalizada()
{
    jQuery('#grupoimpultec_width_box').val(jQuery('#grupoimpultec_cajas_personalizadas').find(':selected').data('ancho'));
    jQuery('#grupoimpultec_id_box').val(jQuery('#grupoimpultec_cajas_personalizadas').find(':selected').data('id'));
    jQuery('#grupoimpultec_name_box').val(jQuery('#grupoimpultec_cajas_personalizadas').find(':selected').data('name'));
    jQuery('#grupoimpultec_height_box').val(jQuery('#grupoimpultec_cajas_personalizadas').find(':selected').data('alto'));
    jQuery('#grupoimpultec_length_box').val(jQuery('#grupoimpultec_cajas_personalizadas').find(':selected').data('largo'));
    jQuery('#grupoimpultec_max_weigth_box').val(jQuery('#grupoimpultec_cajas_personalizadas').find(':selected').data('peso'));
}

function desactivar_cajas() {
    jQuery('#grupoimpultec_max_weigth_box').prop('disabled', true);
    jQuery('#grupoimpultec_cajas_personalizadas').prop('disabled', true);
    
    jQuery('#grupoimpultec_name_box').prop('disabled', true);
    jQuery('#grupoimpultec_width_box').prop('disabled', true);
    jQuery('#grupoimpultec_height_box').prop('disabled', true);
    jQuery('#grupoimpultec_length_box').prop('disabled', true);
}

function activar_cajas() {
    
    jQuery('#grupoimpultec_name_box').prop('disabled', false);
    jQuery('#grupoimpultec_width_box').prop('disabled', false);
    jQuery('#grupoimpultec_height_box').prop('disabled', false);
    jQuery('#grupoimpultec_length_box').prop('disabled', false);
    jQuery('#grupoimpultec_max_weigth_box').prop('disabled', false);
    jQuery('#grupoimpultec_cajas_personalizadas').prop('disabled', false);
}