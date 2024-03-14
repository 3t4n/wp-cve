<?php 


if( !function_exists( 'pvtfw_enable_scroll_option' ) ):

	function pvtfw_enable_scroll_option() { 

		$scrollableTableX = PVTFW_COMMON::pvtfw_get_options()->scrollableTableX;
		$table_min_width =  PVTFW_COMMON::pvtfw_get_options()->table_min_width;
		$fullTable = PVTFW_COMMON::pvtfw_get_options()->fullTable;

		if($scrollableTableX == 'on' && $fullTable == 'on'){
	?>
		<style type="text/css">
			.pvt-scroll-x {
				width: 100%;
				overflow-x: scroll;
			}

			.variant{
				min-width: <?php echo $table_min_width; ?>px;
			}
		</style>
	<?php
		}
		elseif($scrollableTableX == 'on' && $fullTable == ''){
	?>
		<style type="text/css">
			.pvt-scroll-x {
				width: 100%;
				overflow-x: scroll;
			}

			.variant{
				min-width: <?php echo $table_min_width; ?>px;
			}
			@media screen and (max-width:  767px){
				.pvt-scroll-x {
					width: auto;
					overflow-x: hidden;
				}
				.variant{
					min-width: auto;
				}
			}
		</style>
	<?php
		}
	}
	add_action('wp_head', 'pvtfw_enable_scroll_option');

endif;
