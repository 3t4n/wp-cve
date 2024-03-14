<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if( $products->max_num_pages <= max( 1, $products->query_vars['paged'] ) ){
	return;
}
?>
<div 
	class="wcpt-infinite-scroll-dots wcpt-device-<?php echo $device; ?>" 
	title="loading more results"
>
	<span class="wcpt-infinite-scroll-dots__single-dot"></span>
	<span class="wcpt-infinite-scroll-dots__single-dot"></span>
	<span class="wcpt-infinite-scroll-dots__single-dot"></span>
</div>