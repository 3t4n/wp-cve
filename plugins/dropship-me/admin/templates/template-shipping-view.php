<?php
/**
 * Author: Vitaly Kukin
 * Date: 21.10.2018
 * Time: 21:44
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
<div class="row align-items-center shipping-header d-none d-sm-flex">
	<div class="col-sm country"><?php _e( 'Warehouse location', 'dm' ) ?></div>
	<div class="col-sm company"><?php _e( 'Shipping company', 'dm' ) ?></div>
	<div class="col-sm"><?php _e( 'Shipping cost', 'dm' ) ?></div>
	<div class="col-sm text-center"><?php _e( 'Processing time', 'dm' ) ?></div>
	<div class="col-sm text-center"><?php _e( 'Estimated delivery time', 'dm' ) ?></div>
	<div class="col-sm text-center"><?php _e( 'Tracking information', 'dm' ) ?></div>
</div>
{{#each list}}
	<div class="row align-items-center shipping-item">
		<div class="col-xs-12 col-sm country">
            <span class="d-inline-block d-sm-none"><?php _e( 'Warehouse location', 'dm' ) ?>: </span>
			{{country}}
		</div>
        <div class="col-xs-6 col-sm company">
			{{company}}
		</div>
		<div class="col-xs-6 col-sm">
			{{#if free}}
				<?php _e( 'Free shipping', 'dm' ) ?>
			{{else}}
				{{#if s}}
					<span class="color-orange">{{format_price total}}</span>
				{{else}}
					<span class="color-orange">{{format_price price}}</span>
				{{/if}}
			{{/if}}
		</div>
		<div class="col-xs-12 col-sm text-left text-sm-center">
            <span class="d-inline-block d-sm-none"><?php _e( 'Processing', 'dm' ) ?>: </span>
            {{processingTime}} <?php _e( 'days', 'dm' ) ?>
		</div>
		<div class="col-xs-12 col-sm text-left text-sm-center">
            <span class="d-inline-block d-sm-none"><?php _e( 'Delivery time', 'dm' ) ?>: </span>
            {{time}} <?php _e( 'days', 'dm' ) ?>
		</div>
		<div class="col-xs-12 col-sm text-left text-sm-center">
            <span class="d-inline-block d-sm-none"><?php _e( 'Tracking', 'dm' ) ?>: </span>
			{{#if isTracked}}
				<?php _e( 'Available', 'dm' ) ?>
			{{else}}
				<?php _e( 'Not available', 'dm' ) ?>
			{{/if}}
		</div>
	</div>
{{/each}}