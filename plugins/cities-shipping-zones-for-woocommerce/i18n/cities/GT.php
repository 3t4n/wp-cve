<?php

/**
 * List of cities for: Guatemala
 * Source: https://en.wikipedia.org/wiki/List_of_places_in_Guatemala
 * Version: 1.1
 * Author: Condless
 * Author URI: https://www.condless.com/
 * License: GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 */

/**
 * Exit if accessed directly
 */
defined( 'ABSPATH' ) || exit;

$country_states = ( include WC()->plugin_path() . '/i18n/states.php' )['GT'];

$country_cities = [
	'GT-AV' => [
		'GTCOBÁN' => 'Cobán',
		'GTSAN_CRISTÓBAL_VERAPAZ' => 'San Cristóbal Verapaz',
		'GTPANZÓS' => 'Panzós',
		'GTCHISEC' => 'Chisec',
		'GTSAN_PEDRO_CARCHÁ' => 'San Pedro Carchá',
		'GTSANTA_CATALINA_LA_TINTA' => 'Santa Catalina la Tinta',
	],
	'GT-BV' => [
		'GTSALAMÁ' => 'Salamá',
	],
	'GT-CM' => [
		'GTCHIMALTENANGO' => 'Chimaltenango',
		'GTTECPÁN_GUATEMALA' => 'Tecpán Guatemala',
		'GTPATZÚN' => 'Patzún',
		'GTSAN_ANDRÉS_ITZAPA' => 'San Andrés Itzapa',
		'GTPATZICÍA' => 'Patzicía',
		'GTEL_TEJAR' => 'El Tejar',
	],
	'GT-CQ' => [
		'GTCHIQUIMULA' => 'Chiquimula',
		'GTESQUIPULAS' => 'Esquipulas',
	],
	'GT-PR' => [
		'GTSANARATE' => 'Sanarate',
		'GTGUASTATOYA' => 'Guastatoya',
	],
	'GT-QC' => [
		'GTCHICHICASTENANGO' => 'Chichicastenango',
		'GTSANTA_CRUZ_DEL_QUICHÉ' => 'Santa Cruz del Quiché',
		'GTSANTA_MARIA_NEBAJ' => 'Santa Maria Nebaj',
		'GTCHAJUL' => 'Chajul',
	],
	'GT-ES' => [
		'GTESCUINTLA' => 'Escuintla',
		'GTSANTA_LUCÍA_COTZUMALGUAPA' => 'Santa Lucía Cotzumalguapa',
		'GTPALÍN' => 'Palín',
		'GTPUERTO_SAN_JOSÉ' => 'Puerto San José',
		'GTLA_GOMERA' => 'La Gomera',
		'GTTIQUISATE' => 'Tiquisate',
		'GTNUEVA_CONCEPCIÓN' => 'Nueva Concepción',
	],
	'GT-GU' => [
		'GTGUATEMALA_CITY' => 'Guatemala City',
		'GTMIXCO' => 'Mixco',
		'GTVILLA_NUEVA' => 'Villa Nueva',
		'GTSAN_MIGUEL_PETAPA' => 'San Miguel Petapa',
		'GTSAN_JUAN_SACATEPÉQUEZ' => 'San Juan Sacatepéquez',
		'GTVILLA_CANALES' => 'Villa Canales',
		'GTCHINAUTLA' => 'Chinautla',
		'GTAMATITLÁN' => 'Amatitlán',
		'GTSANTA_CATARINA_PINULA' => 'Santa Catarina Pinula',
		'GTSAN_JOSÉ_PINULA' => 'San José Pinula',
		'GTSAN_PEDRO_AYAMPUC' => 'San Pedro Ayampuc',
		'GTFRAIJANES' => 'Fraijanes',
		'GTPALENCIA' => 'Palencia',
		'GTSAN_PEDRO_SACATEPÉQUEZ' => 'San Pedro Sacatepéquez',
	],
	'GT-HU' => [
		'GTHUEHUETENANGO' => 'Huehuetenango',
		'GTJACALTENANGO' => 'Jacaltenango',
		'GTLA_DEMOCRACIA' => 'La Democracia',
		'GTSANTA_CRUZ_BARILLAS' => 'Santa Cruz Barillas',
	],
	'GT-IZ' => [
		'GTPUERTO_BARRIOS' => 'Puerto Barrios',
		'GTMORALES' => 'Morales',
		'GTEL_ESTOR' => 'El Estor',
		'GTLIVINGSTON' => 'Livingston',
	],
	'GT-JA' => [
		'GTJALAPA' => 'Jalapa',
	],
	'GT-JU' => [
		'GTJUTIAPA' => 'Jutiapa',
		'GTASUNCIÓN_MITA' => 'Asunción Mita',
	],
	'GT-PE' => [
		'GTSAN_BENITO' => 'San Benito',
		'GTFLORES' => 'Flores',
		'GTPOPTÚN' => 'Poptún',
		'GTMELCHOR_DE_MENCOS' => 'Melchor de Mencos',
	],
	'GT-QZ' => [
		'GTQUETZALTENANGO' => 'Quetzaltenango',
		'GTCOATEPEQUE' => 'Coatepeque',
		'GTOSTUNCALCO' => 'Ostuncalco',
		'GTOLINTEPEQUE' => 'Olintepeque',
		'GTCANTEL' => 'Cantel',
		'GTCOLOMBA' => 'Colomba',
		'GTEL_PALMAR' => 'El Palmar',
		'GTLA_ESPERANZA' => 'La Esperanza',
		'GTALMOLONGA' => 'Almolonga',
		'GTSALCAJÁ' => 'Salcajá',
	],
	'GT-RE' => [
		'GTRETALHULEU' => 'Retalhuleu',
		'GTSAN_SEBASTIÁN' => 'San Sebastián',
		'GTNUEVO_SAN_CARLOS' => 'Nuevo San Carlos',
	],
	'GT-SA' => [
		'GTANTIGUA_GUATEMALA' => 'Antigua Guatemala',
		'GTCIUDAD_VIEJA' => 'Ciudad Vieja',
		'GTSANTIAGO_SACATEPÉQUEZ' => 'Santiago Sacatepéquez',
		'GTSUMPANGO' => 'Sumpango',
		'GTJOCOTENANGO' => 'Jocotenango',
		'GTSAN_LUCAS_SACATEPÉQUEZ' => 'San Lucas Sacatepéquez',
		'GTSANTA_MARÍA_DE_JESÚS' => 'Santa María de Jesús',
		'GTALOTENANGO' => 'Alotenango',
	],
	'GT-SM' => [
		'GTSAN_PEDRO_SACATEPÉQUEZ' => 'San Pedro Sacatepéquez',
		'GTSAN_MARCOS' => 'San Marcos',
		'GTMALACATÁN' => 'Malacatán',
		'GTCOMITANCILLO' => 'Comitancillo',
		'GTAYUTLA' => 'Ayutla',
		'GTSAN_PABLO' => 'San Pablo',
	],
	'GT-SR' => [
		'GTBARBERENA' => 'Barberena',
		'GTCUILAPA' => 'Cuilapa',
		'GTCHIQUIMULILLA' => 'Chiquimulilla',
	],
	'GT-SO' => [
		'GTSOLOLÁ' => 'Sololá',
		'GTNAHUALÁ' => 'Nahualá',
		'GTSAN_LUCAS_TOLIMÁN' => 'San Lucas Tolimán',
		'GTPANAJACHEL' => 'Panajachel',
	],
	'GT-SU' => [
		'GTMAZATENANGO' => 'Mazatenango',
		'GTCHICACAO' => 'Chicacao',
		'GTSAN_PABLO_JOCOPILAS' => 'San Pablo Jocopilas',
		'GTPATULUL' => 'Patulul',
		'GTSAN_FRANCISCO_ZAPOTITLÁN' => 'San Francisco Zapotitlán',
	],
	'GT-TO' => [
		'GTTOTONICAPÁN' => 'Totonicapán',
		'GTSAN_FRANCISCO_EL_ALTO' => 'San Francisco El Alto',
		'GTMOMOSTENANGO' => 'Momostenango',
	],
	'GT-ZA' => [
		'GTZACAPA' => 'Zacapa',
		'GTGUALÁN' => 'Gualán',
	]
];
