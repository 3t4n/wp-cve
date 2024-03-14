<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

?>
<thead>
	<?php
	$headings_mkp = '';
	$hide_headings = true;
	if( ! empty( $columns ) ){
		foreach( $columns as $column_index => $column ){
			$GLOBALS['wcpt_col_index'] = $column_index;
			wcpt_parse_style_2($column['heading']);
			$col_id = 'wcpt-' . $column['heading']['id'];
			$curr_heading_mkp = wcpt_parse_2($column['heading']['content']);
			if( $curr_heading_mkp ){
				$hide_headings = false;
			}
			$headings_mkp .= '<th class="wcpt-heading ' . $col_id . '" data-wcpt-column-index="'. $column_index .'" '. apply_filters('wcpt_heading_cell_html_attributes', '', $column) .' >' . $curr_heading_mkp . '</th>';
		}
	}
	?>	
	<?php	do_action( 'wcpt_before_heading_row', $columns, $device ); ?>
	<tr 
		class="wcpt-heading-row <?php echo $hide_headings ? 'wcpt-hide' : ''; ?>"
	><?php 
		do_action('wcpt_after_heading_row_open');
		echo $headings_mkp; 
		do_action('wcpt_after_heading_row_close');	
	?></tr>
	<?php	do_action( 'wcpt_after_heading_row', $columns, $device ); ?>
</thead>
<?php
