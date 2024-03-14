<?php
if(get_option('cwmp_adddon_correios')=="S"){ include "shipping/correios.php"; }
if(get_option('cwmp_adddon_frenet')=="S"){ include "shipping/frenet.php"; }
if(get_option('cwmp_adddon_kangu')=="S"){ include "shipping/kangu.php"; }
if(get_option('cwmp_adddon_mandabem')=="S"){ include "shipping/mandabem.php"; }
if(get_option('cwmp_adddon_melhorenvio')=="S"){ include "shipping/melhorenvio.php"; }
add_action( 'woocommerce_after_shipping_rate', 'action_after_shipping_rate', 20, 2 );
function action_after_shipping_rate ( $method, $index ) {
    if( is_cart() ) return; // Exit on cart page
	if(get_option('cwmp_adddon_correios')=="S"){
		if(get_option('cwmo_day_aditional_correios')){
			$days = $method->meta_data['prazo']+get_option('cwmo_day_aditional_correios');
		}else{
			$days = $method->meta_data['prazo'];
		}
		if( 'cwmp_method_shipping_correios_sedex' === $method->id ) {
			 echo __("<p>".str_replace("{{prazo}}",gmdate('d/m/Y', strtotime('+ '.$days.' days')),get_option('cwmo_format_correios'))."</p>");
		}
		if( 'cwmp_method_shipping_correios_pac_mini' === $method->id ) {
			 echo __("<p>".str_replace("{{prazo}}",gmdate('d/m/Y', strtotime('+ '.$days.' days')),get_option('cwmo_format_correios'))."</p>");
		}
		if( 'cwmp_method_shipping_correios_pac' === $method->id ) {
			echo __("<p>".str_replace("{{prazo}}",gmdate('d/m/Y', strtotime('+ '.$days.' days')),get_option('cwmo_format_correios'))."</p>");
		}
    }
    if( 'cwmp_method_shipping_mandabem_sedex' === $method->id ) {
         echo __("<p>".str_replace("{{prazo}}",gmdate('d/m/Y', strtotime('+ '.$days.' days')),get_option('cwmo_format_correios'))."</p>");
    }
    if( 'cwmp_method_shipping_mandabem_pac_mini' === $method->id ) {
         echo __("<p>".str_replace("{{prazo}}",gmdate('d/m/Y', strtotime('+ '.$days.' days')),get_option('cwmo_format_correios'))."</p>");
    }
    if( 'cwmp_method_shipping_mandabem_pac' === $method->id ) {
        echo __("<p>".str_replace("{{prazo}}",gmdate('d/m/Y', strtotime('+ '.$days.' days')),get_option('cwmo_format_correios'))."</p>");
    }

	for($i=1;$i<=6;$i++){
		if( 'cwmp_method_shipping_kangu:'.$i === $method->id ) {
			echo __("<p>".str_replace("{{prazo}}",gmdate('d/m/Y', strtotime('+ '.$days.' days')),get_option('cwmo_format_correios'))."</p>");
		}
	}
	for($i=1;$i<=6;$i++){
		if( 'cwmp_method_shipping_frenet:'.$i === $method->id ) {
			echo __("<p>".str_replace("{{prazo}}",gmdate('d/m/Y', strtotime('+ '.$days.' days')),get_option('cwmo_format_correios'))."</p>");
		}
	}
	for($i=1;$i<=6;$i++){
		if( 'cwmp_method_shipping_melhorenvio:'.$i === $method->id ) {
			echo __("<p>".str_replace("{{prazo}}",gmdate('d/m/Y', strtotime('+ '.$days.' days')),get_option('cwmo_format_correios'))."</p>");
		}
	}
}