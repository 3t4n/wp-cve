Number.prototype.toFixed2 = function (precision) {
    var num = Number(this);
    return (+(Math.round(+(num + 'e' + precision)) + 'e' + -precision)).toFixed(precision);
}
jQuery(document).ready(function ($) {
    
jQuery(".moneda").on('keyup', function () {
     jQuery(this).val($(this).val().replace(/,/g, '.'));       
    });    
    jQuery(".moneda").on('blur', function () {          
         jQuery(this).val(parseFloat(jQuery(this).val()).toFixed2(2));     
    });

});

var datos_usuario = {
    'usuario_servicio': jQuery('#usuario_servicio').val(),
    'password_servicio': jQuery('#password_servicio').val(),
    'servicio': jQuery('#servicio').val(),
}

jQuery(function () {
    if (typeof iva_exento === 'undefined')
    {
        iva_exento = 0;
    }
    if (iva_exento == 1) {
        jQuery("#label_switch_iva_parent").hide();
    }
    jQuery('#dropshipping').change(function () {
        if (jQuery("#dropshipping").prop('checked') == true) {
            obtener_direcciones(datos_usuario);

            jQuery("#div_direccion_predeterminada_dropshipping").show();
        } else {
            jQuery("#div_direccion_predeterminada_dropshipping").hide();
        }
    });

    jQuery('#no_recoger').change(function () {
        if (jQuery("#no_recoger").prop('checked') == true) {
            jQuery('#caja_desde').hide();
            jQuery('#caja_hasta').hide();
            jQuery('#id_d_intervalo').val('09:00');
            jQuery('#id_h_intervalo').val('13:00');
            if (jQuery("#fecha_recogida").val() != '') {
                jQuery("#boton_crear_envio").prop('disabled', false);
            }
        } else {
            jQuery("#div_direccion_predeterminada_dropshipping").hide();
            jQuery("#fecha_recogida").val('');
            jQuery("#boton_crear_envio").prop('disabled', true);
        }
    });


});



jQuery(function () {
    jQuery('#seguro').change(function () {
        if (jQuery("#seguro").prop('checked') == true) {
            jQuery("#div_cantidad_seguro").show();
        } else {
            jQuery("#div_cantidad_seguro").hide();
        }
    });
});

function obtener_direcciones(datos_usuario,api_server) {


    my_url = 'http://www.'+api_server+'/json_interface/obtener_listado_direcciones_parcial_usuario/?callback=?';
    jQuery.getJSON(my_url, datos_usuario, function (response) {
        console.log(response + 'success');
        var $dropdown = jQuery("#direccion_predeterminada_dropshipping");
        jQuery.each(response, function () {
            $dropdown.append(new Option(this.codigo + ' - ' + this.nombre + ', ' + this.direccion + ', ' + this.codigo_postal + ' - ' + this.poblacion, this.id_direccion));
        });
    })
            .fail(function (d, textStatus, error) {                
            });
}

function consulta_horas_recogida(api_server)
{
    if (jQuery("#no_recoger").prop('checked') == true)
    {
        if (jQuery("#fecha_recogida").val() != '') {
            jQuery("#boton_crear_envio").prop('disabled', false);
        }
        return;
    }
    var datos_usuario = {
        'usuario_servicio': jQuery('#usuario_servicio').val(),
        'password_servicio': jQuery('#password_servicio').val(),
        'servicio': jQuery('#servicio').val(),
        'id_agencia': jQuery('#id_agencia').val(),
        'fecha_recogida_aux': jQuery('#fecha_recogida').val(),
        'id_usuario': jQuery('#id_usuario').val(),
        'codigos_origen': jQuery('#codigos_origen').val(),
        'codigos_destino': jQuery('#codigos_destino').val(),
        'id_pais_origen': jQuery('#id_pais_salida').val(),
        'id_pais_destino': jQuery('#id_pais_llegada').val(),
    } 


    my_url = 'https://www.'+api_server+'/json_interface/obtener_horas_recogida/?callback=?';
    jQuery.getJSON(my_url, datos_usuario, function (response) {
        var $id_d_intervalo = jQuery("#id_d_intervalo");
        var $id_h_intervalo = jQuery("#id_h_intervalo");
        intervalo_recogida = response.intervalo_recogida;
        horario_inicial = response.horario.inicial;
        horario_final = response.horario.final;        
        jQuery.each(horario_inicial, function (i, val) {
            $id_d_intervalo.append(new Option(val, val));
        });
        jQuery.each(horario_final, function (i, val) {
            $id_h_intervalo.append(new Option(val, val));
        });
        if (horario_inicial == null || horario_final == null || horario_inicial.length <= 0 || horario_final.length <= 0) {
            jQuery('#caja_desde').hide();
            jQuery('#caja_hasta').hide();
            jQuery('#id_no_intervalo').show();
            jQuery('#boton_crear_envio').prop('disabled', true);
        } else {
            jQuery('#caja_desde').show();
            jQuery('#caja_hasta').show();
            jQuery('#id_no_intervalo').hide();
            jQuery('#boton_crear_envio').prop('disabled', false);
        }
    });
}





function eliminar_bulto(num_bulto)
{
    ocultar_tabla_precios();
    var div_bultos = document.getElementById("div_bultos");
    div_bultos.removeChild(document.getElementById("bulto_" + num_bulto));
    max_bultos--;
    jQuery('#text_max_bultos').val(max_bultos);
    if (max_bultos < 2)
        jQuery('#boton_eliminar_bulto').hide();
}


function preparar_crear_envio(id_agencia)
{
    if (jQuery('#recoger_tienda').prop('checked') == true) {
        recoger_tienda = 1;
    } else {
        recoger_tienda = 0;
    }
    var select_oficinas_destino_necesario = 0;
    id_agencia_crear_envio = id_agencia;
    if (id_agencia_crear_envio == 5014 || id_agencia_crear_envio == 5016 || id_agencia_crear_envio == 5017 || id_agencia_crear_envio == 5024 || id_agencia_crear_envio == 5040 || id_agencia_crear_envio == 5041 || id_agencia_crear_envio == 5042 || id_agencia_crear_envio == 5106 || id_agencia_crear_envio == 5108 || id_agencia_crear_envio == 5109 || id_agencia_crear_envio == 5110 || id_agencia_crear_envio == 5111 || id_agencia_crear_envio == 5135 || id_agencia_crear_envio == 5136) {
        recoger_tienda = 1;
    }
    if (id_agencia_crear_envio == 65 || id_agencia_crear_envio == 62 || id_agencia_crear_envio == 73 || id_agencia_crear_envio == 5040 || id_agencia_crear_envio == 5041)
    {
        jQuery('#select_oficinas_destino').val(select_oficinas_destino);
        if (id_agencia_crear_envio == 65 || id_agencia_crear_envio == 73)
        {
            jQuery('#modal_establecer_fecha .modal-body').css({'min-height': '410px'});
            jQuery('#capa_entrega_mapas_correos').show();
            jQuery('#capa_entrega_mapas_hapiick').hide();
            jQuery('#capa_entrega_mapas_mondial_relay').hide();
        } else if (id_agencia_crear_envio == 62)
        {
            jQuery('#modal_establecer_fecha .modal-body').css({'min-height': '410px'});
            jQuery('#capa_entrega_mapas_hapiick').show();
            jQuery('#capa_entrega_mapas_correos').hide();
            jQuery('#capa_entrega_mapas_mondial_relay').hide();
        }
        if (id_agencia_crear_envio == 5040 || id_agencia_crear_envio == 5041)
        {
            select_oficinas_destino_necesario = 1;
            jQuery('#capa_entrega_mapas_correos').hide();
            jQuery('#capa_entrega_mapas_hapiick').hide();
            if (select_oficinas_destino > 0) {
                jQuery('#capa_entrega_mapas_mondial_relay').hide();
            } else {
                jQuery('#modal_establecer_fecha .modal-body').css({'min-height': '650px'});
                jQuery('#capa_entrega_mapas_mondial_relay').show();
                switch (id_pais_llegada_crear_envio) {
                    case 1:
                        var countrycode = 'ES';
                        break;
                    case 8:
                        var countrycode = 'BE';
                        break;
                    case 9:
                        var countrycode = 'LU';
                        break;
                    default:
                        var countrycode = 'FR';
                        break;
                }
                mapa_mondial_relay_destino(codigos_destino_crear_envio, countrycode);
                setTimeout(function () {
                    jQuery('.MR-Widget input[type="text"]').css('display', 'inline');
                }, 1000);
            }
        }
        cargar_mapas(-1);
    } else
    {
        jQuery('#capa_entrega_mapas_hapiick').hide();
        jQuery('#capa_entrega_mapas_correos').hide();
        jQuery('#capa_entrega_mapas_mondial_relay').hide();
        jQuery('#modal_establecer_fecha .modal-body').css({'min-height': '130px'});
    }
    if (id_agencia_crear_envio == 74)
    {
        jQuery('#capa_mercancias_correos').show();
    } else
    {
        jQuery('#capa_mercancias_correos').hide();
    }
    if (recoger_tienda == 0 || (select_oficinas_destino_necesario == 1 && jQuery('#select_oficinas_destino').val() == ''))
    {
        console.log('adsaddsa');
        jQuery("#boton_crear_envio").hide();
        jQuery('#modal_establecer_fecha').modal('show');
    } else
    {
        fecha_hoy = moment().format('DD/MM/YYYY');
        crear_envio_integrado(id_agencia, recoger_tienda, fecha_hoy, '10:00', '18:00');
    }
}
function preparar_crear_envio_recogida()
{
    crear_envio_integrado(id_agencia_crear_envio, recoger_tienda, jQuery('#fecha_recogida').val(), jQuery('#id_d_intervalo').val(), jQuery('#id_h_intervalo').val(), jQuery('#unidad_correo').val());
}





function ocultar_tabla_precios()
{
    jQuery("#tabla_precios").hide();
}
function actualizar_estado_envio(codigo_envio)
{
    jQuery("#boton_actualizar_estado").html('');
    jQuery("#boton_actualizar_estado").prop('disabled', true);
    datos = {
        'id_order': id_order
    }
    jQuery.ajax({
        type: "POST",
        url: "{$geneiajaxcontrollerlink|escape:'htmlall':'UTF-8'}&action=actualizar_estado_pedido",
        data: {
            datos_json_actualizar_estado_pedido: JSON.stringify(datos),
        }
    })
            .done(function (data, textStatus, jqXHR) {
                location.reload();

            })
            .fail(function (jqXHR, textStatus, errorThrown) {
                console.log("Ajax problem: " + textStatus + ". " + errorThrown);
            });
}






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


function guardar_configuracion()
{
    url_guardar_configuracion = "{$geneiajaxcontrollerlink|escape:'htmlall':'UTF-8'}&action=guardar_configuracion";
    jQuery('#boton_guardar_configuracion').prop('disabled', true);
    datos = {
        'usuario_servicio': jQuery('#usuario_servicio').val(),
        'password_servicio': jQuery('#password_servicio').val(),
        'metodo_pago_contrareembolso_defecto_default': jQuery('#metodo_pago_contrareembolso_defecto_default').val(),
        'atributos_defecto_default': jQuery('#atributos_defecto_default').val(),
        'peso_defecto': jQuery('#peso_defecto').val(),
        'largo_defecto': jQuery('#largo_defecto').val(),
        'ancho_defecto': jQuery('#ancho_defecto').val(),
        'alto_defecto': jQuery('#alto_defecto').val(),
        'num_bultos_defecto': jQuery('#num_bultos_defecto').val(),
        'estado_recogida_tramitada_default': jQuery('#estado_recogida_tramitada_default').val(),
        'estado_envio_transito_default': jQuery('#estado_envio_transito_default').val(),
        'estado_envio_incidencia_default': jQuery('#estado_envio_incidencia_default').val(),
        'estado_envio_entregado_default': jQuery('#estado_envio_entregado_default').val(),
        'estados_automaticos_pedidos': jQuery('#estados_automaticos_pedidos').val(),
        'variation_type': jQuery('#variation_type').val(),
        'variation_price_amount': jQuery('#variation_price_amount').val(),
        'direccion_predeterminada': jQuery('#direccion_predeterminada').val()
    }
    jQuery.ajax({
        type: "POST",
        url: url_guardar_configuracion,
        data: {
            datos_json_guardar_configuracion: JSON.stringify(datos),
        }
    })
            .done(function (data, textStatus, jqXHR) {
                location.reload();
            });
}

function actualizar_precio_envio()
{
    if (iva_exento == 1) {
        iva = 0;
    }
    var porcentaje_reembolso = parseFloat(jQuery('#porcentaje_reembolso').val());
    if (isNaN(porcentaje_reembolso)) {
        porcentaje_reembolso = 0;
    }
    var porcentaje_seguro = parseFloat(jQuery('#porcentaje_seguro').val());
    if (isNaN(porcentaje_seguro)) {
        porcentaje_seguro = 0;
    }
    var cantidad_reembolso = parseFloat(jQuery('#cantidad_reembolso').val());
    if (isNaN(cantidad_reembolso)) {
        cantidad_reembolso = 0;
    }
    var comision_reembolso = (cantidad_reembolso / (1 + (21 / 100))) * (porcentaje_reembolso / 100);

    var minimo_reembolso = parseFloat(jQuery('#minimo_reembolso').val() / (1 + (iva / 100)));
    if (comision_reembolso > 0 && comision_reembolso < minimo_reembolso) {
        comision_reembolso = minimo_reembolso;
    }
    var importe_base = parseFloat(jQuery('#importe_base').val());
    var cantidad_seguro = parseFloat(jQuery('#cantidad_seguro').val());
    var comision_seguro = (cantidad_seguro / (1 + (iva / 100))) * (porcentaje_seguro / 100);
    var importe_base_total = importe_base + comision_reembolso + comision_seguro;
    var importe_iva = importe_base_total * (iva / 100);
    var importe_total = importe_base_total * (1 + (iva / 100));
    jQuery('#resumen_comision_reembolso').html(comision_reembolso.toFixed2(2));
    jQuery('#resumen_comision_seguro').html(comision_seguro.toFixed2(2));
    jQuery('#resumen_iva').html(importe_iva.toFixed2(2));
    jQuery('#resumen_total_importe_base').html(importe_base_total.toFixed2(2));
    jQuery('#resumen_total_importe').html(importe_total.toFixed2(2));

} 