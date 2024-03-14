<?php
/**
 * Author: Vitaly Kukin
 * Date: 23.09.2018
 * Time: 18:25
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
<h3 class="product-title-more"><?php _e( '20 Reviews', 'dm' ) ?></h3>
{{#each list}}
<div class="review-item-list row py-4">
	<div class="col-lg-1 text-center">
		<h5>{{author}}</h5>
		<div class="d-inline-block flag flag-{{flag}}"></div> <span class="text-uppercase">{{flag}}</span>
	</div>
	<div class="col">
		<div class="d-inline-block stars">
			<div class="stars-line" style="width:{{ratePercent}}%" title="{{star}}"></div>
		</div>
		<div class="content">{{{feedback}}}</div>
		<div class="date-review">{{date}}</div>
	</div>
</div>
{{/each}}