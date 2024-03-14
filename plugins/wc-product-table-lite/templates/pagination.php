<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

?>
<div class="wcpt-pagination wcpt-device-<?php echo $device; ?> <?php if( $products->max_num_pages <= 1 ) echo " wcpt-hide "; ?>">
	<?php
		$args = array(
			'format'       => '?'. $table_id .'_paged=%#%',
			'total'        => $products->max_num_pages,
			'current'      => max( 1, $products->query_vars['paged'] ),
			'prev_next'    => false,
			'prev_text'    => false,
			'next_text'    => false,

			'prev_next' => true,
			// 'prev_text' => __('&#8249;'),
			// 'next_text' => __('&#8250;'),

			'prev_text' => __( wcpt_get_icon('chevron-left') ),			
			'next_text' => __( wcpt_get_icon('chevron-right') ),			

			'type'         => 'plain',
			'end_size'     => 1,
			'mid_size'     => 1,
			'before_page_number' => '',
			'after_page_number'  => '',
			'add_args'     => false,
		);
		
		if( $mkp = paginate_links( apply_filters( 'wcpt_pagination_options', $args ) ) ){
			echo str_replace(' current', ' current wcpt-active', $mkp);
		}
	?>
</div>
