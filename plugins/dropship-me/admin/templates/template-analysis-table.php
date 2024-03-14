<?php
/**
 * Author: Vitaly Kukin
 * Date: 10.11.2018
 * Time: 12:12
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>

<div class="row align-items-center analysis-header d-none d-sm-flex">
	<div class="col-12 col-md-6"><?php _e( 'Product info', 'dm' ) ?></div>
	<div class="col-md d-none d-md-block"><?php _e( 'Store Name', 'dm' ) ?></div>
	<div class="col-md text-center d-none d-md-block"><?php _e( 'Logistics Reliability', 'dm' ) ?></div>
</div>
<div class="row align-items-center analysis-item">
	<div class="col-12 col-sm-4 col-md-2 text-center text-sm-left">
        <img src="{{imageUrl}}_220x220.jpg" class="img-fluid">
    </div>
    <div class="col-12 col-sm-8 col-md-4 text-center text-sm-left">
        <a href="{{url}}" target="_blank">{{subject}}</a>
    </div>
	<div class="col-12 col-md text-center text-md-left py-2 py-md-0">
        <span class="d-inline-block d-md-none"><?php _e( 'Store Name', 'dm' ) ?>: </span>
        <a href="{{storeUrl}}" target="_blank">{{storeName}}</a>
	</div>
	<div class="col-12 col-md logistic text-center py-2 py-md-0">
        <span class="d-inline-block d-md-none"><?php _e( 'Logistics Reliability', 'dm' ) ?>: </span>
        {{#ifCond logisticsReliability "===" "U"}}<?php _e( 'Unknown', 'dm' ) ?>{{/ifCond}}
        {{#ifCond logisticsReliability "===" "H"}}<?php _e( 'Excellent', 'dm' ) ?>{{/ifCond}}
        {{#ifCond logisticsReliability "===" "M"}}<?php _e( 'Average', 'dm' ) ?>{{/ifCond}}
	</div>
</div>