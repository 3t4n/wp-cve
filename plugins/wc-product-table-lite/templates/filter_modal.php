<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<a 
	class="wcpt-rn-button wcpt-rn-filter <?php echo $html_class;?>" 
	href="javascript:void(0)" 
	data-wcpt-modal="filter" 
>
	<?php echo wcpt_parse_2( $label ); ?>
</a>
