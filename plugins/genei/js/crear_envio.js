jQuery(document).ready(function ($) {
    if (jQuery('#servicio_recogida').val() != 1) {
        jQuery("#boton_crear_envio").prop('disabled', false);
    }
    jQuery("#boton_crear_envio").on('click', function () {
        jQuery("#boton_crear_envio").prop('disabled', true);
        error_crear_envio = false;
        error_txt = '';
        if (jQuery('#seguro').prop('checked')) {
            if ((!parseFloat(jQuery('#cantidad_seguro').val()) > 0)) {
                error_txt += "\nCantidad seguro incorrecta";
                error_crear_envio = true;
            }
        }

        if (jQuery('#servicio_recogida').val() == 1 && !jQuery('#caja_desde').is(':visible') && jQuery('#no_recoger').length > 0 && !jQuery('#no_recoger').is(':checked')) {
            error_txt += "\nFecha de recogida incorrecta";
            error_crear_envio = true;
        }

        if (jQuery('#reembolso').prop('checked')) {
            if (!(parseFloat(jQuery('#cantidad_reembolso').val()) > 0)) {
                error_txt += "\nCantidad reembolso incorrecta";
                error_crear_envio = true;
            }
        }
        if (jQuery('#categorias_envios').length > 0 && !(parseFloat(jQuery('#categorias_envios').val()) > 0)) {
            error_txt += "\nTipo mercancía incorrecta";
            error_crear_envio = true;
        }

        if (jQuery('#valor_mercancia').length > 0) {
            if (!(parseFloat(jQuery('#valor_mercancia').val()) > 0))
            {                
                error_crear_envio = true;
                error_txt += "\nValor mercancia incorrecto";
            }
        }

        if (!error_crear_envio) {
            jQuery('#formulario_creacion_envio').submit();
        } else {
            jQuery('#div_error_txt').html(error_txt);
        }

    });


    jQuery(function () {
        jQuery('#entrega_oficina_destino').change(function () {
            if (jQuery("#entrega_oficina_destino").prop('checked') == true) {
                jQuery("#div_map_oficinas_destino").show();
            } else {
                jQuery("#div_map_oficinas_destino").hide();
            }
        });
        jQuery('#categorias_envios').change(function () {
            error_crear_envio = false;
            error_txt = '';
            jQuery("#boton_crear_envio").prop('disabled', false);
        });
    });

});
