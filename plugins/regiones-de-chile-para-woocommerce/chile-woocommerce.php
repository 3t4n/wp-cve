<?php

/*
Plugin Name: Regiones de Chile para WooCommerce
Plugin URI: https://mmrm.cl
Description: Con este plugin o complemento podrás utilizar las Regiones de Chile para mejorar la experiencia de envíos.
Version: 0.4
Author: Melvin Ramos <info@conchalevale.cl>
Author URI: https://mmrm.cl/
Contributors: melvisnap
License: GPLv3
Requires at least: 4.0 +
Tested up to: 5.2.1
WC requires at least: 3.0.x
WC tested up to: 3.6.4
*/
/*
*      Copyright 2018 Cónchale vale <info@conchalevale.cl>
*
*      This program is free software; you can redistribute it and/or modify
*      it under the terms of the GNU General Public License as published by
*      the Free Software Foundation; either version 3 of the License, or
*      (at your option) any later version.
*
*      This program is distributed in the hope that it will be useful,
*      but WITHOUT ANY WARRANTY; without even the implied warranty of
*      MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*      GNU General Public License for more details.
*
*      You should have received a copy of the GNU General Public License
*      along with this program; if not, write to the Free Software
*      Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
*      MA 02110-1301, USA.
*/

function rdcw_regiones_de_chile($states) {
	$states['CL'] = array(
		'Antofagasta' => 'Antofagasta',
		'Araucanía' => 'Araucanía',
		'Arica y Parinacota' => 'Arica y Parinacota',
		'Atacama' => 'Atacama',
		'Aysén del General Carlos Ibáñez del Campo' => 'Aysén del General Carlos Ibáñez del Campo',
		'Biobío' => 'Biobío',
		'Coquimbo' => 'Coquimbo',
		'Libertador General Bernardo O’Higgins' => 'Libertador General Bernardo O’Higgins',
		'Los Lagos' => 'Los Lagos',
		'Los Ríos' => 'Los Ríos',
		'Magallanes y la Antártica Chilena' => 'Magallanes y la Antártica Chilena',
		'Maule' => 'Maule',
		'Metropolitana de Santiago' => 'Metropolitana de Santiago',
		'Ñuble' => 'Ñuble',
		'Tarapacá' => 'Tarapacá',
		'Valparaíso' => 'Valparaíso',
	);

	return $states;
}

function rdcw_change_checkout_fields( $fields ) {

	$fields['billing']['billing_state']['placeholder'] = 'Seleccione una Región';
	$fields['billing']['billing_state']['label'] = 'Región';
	$fields['billing']['billing_state']['class'][] = 'mmrm';

	$fields['shipping']['shipping_state']['placeholder'] = 'Seleccione una Región';
	$fields['shipping']['shipping_state']['label'] = 'Región';

	unset($fields['billing']['billing_postcode']);
	unset($fields['shipping']['shipping_postcode']);

	return $fields;
}

add_filter('woocommerce_checkout_fields' , 'rdcw_change_checkout_fields');
add_filter('woocommerce_states', 'rdcw_regiones_de_chile');
?>
