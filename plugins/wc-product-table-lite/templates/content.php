<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if( $product->get_type() == 'variation' ){
	$content = $product->get_description();

}else{
	$content = get_the_content();

}

if( ! empty( $shortcode_action ) ){
	if( $shortcode_action === 'process' ){
		remove_shortcode('product_table');		
		$content = do_shortcode( $content );
		add_shortcode('product_table', 'wcpt_shortcode_product_table');

	}else if( $shortcode_action === 'strip' ){
		$content = strip_shortcodes( $content );

	}
}

// this code is common between the 'Short description' and 'Content' elements

// -- begin

if( ! $content ){
	return;
}

// complete unclosed tags in $content
if(
	defined( 'LIBXML_DOTTED_VERSION' ) &&
	version_compare( LIBXML_DOTTED_VERSION, '2.7.0', '>' ) 
){
	$_errors = libxml_use_internal_errors( true );
	$dom = new DOMDocument();
	$dom->loadHTML( mb_convert_encoding( '<div>' . $content . '</div>', 'HTML-ENTITIES', "UTF-8"), LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD );

	$_content = '';
	foreach ($dom->documentElement->childNodes as $child) {
		$_content .= $dom->saveHTML($child);
	}

	$content = $_content;	
	libxml_use_internal_errors( $_errors );
}

// content stripped of html and slashes
$content__html_stripped = strip_tags( stripslashes( $content ) );

// no markup if limit is defined by user
if( ! empty( $limit ) ){
	$content = $content__html_stripped;
}

// get truncation symbol "..."
$truncation_symbol_content = "…"; // default
if( ! empty( $truncation_symbol ) ){
	if( $truncation_symbol == 'hide' ){
		$truncation_symbol_content = '';
	}else if( $truncation_symbol == 'custom' ){
		$truncation_symbol_content = $custom_truncation_symbol;
	}
}

// truncate content
$truncate = false;
$limit = empty( $limit ) ? 1000 : (int) $limit;
$rtrim_chars = ' .,…';

if( count( explode( ' ', $content__html_stripped ) ) > $limit ){
	$truncate = true;
	$content__html_stripped__truncated = rtrim( implode( ' ', array_slice( explode( ' ', trim( $content__html_stripped ) ), 0, $limit) ), $rtrim_chars ) . $truncation_symbol_content;
}

// toggle enabled
if( 
	! empty( $toggle_enabled ) &&
	$truncate
){
	
	$html_class .= ' wcpt-toggle-enabled ';
	$show_more_label = empty( $show_more_label ) ? 'show more (+)' : $show_more_label;
	$show_less_label = empty( $show_less_label ) ? 'show less (-)' : $show_less_label;

	ob_start();
	?>
	<!-- before toggle -->
	<span class="wcpt-pre-toggle">
		<?php echo $content__html_stripped__truncated; ?>
		<span class="wcpt-toggle-trigger">
			<?php echo wcpt_parse_2( $show_more_label ); ?>
		</span>
	</span>
	<!-- after toggle -->
	<span class="wcpt-post-toggle">
		<?php echo $content; ?>
		<span class="wcpt-toggle-trigger">
			<?php echo wcpt_parse_2( $show_less_label ); ?>
		</span>
	</span>
	<?php
	$content = ob_get_clean();

// toggle disabled
}else{

	if( $truncate ){
		$content = $content__html_stripped__truncated;
	}

	// 'read more' link
	$read_more = false;
	if( 
		! empty( $read_more_label ) &&
		wcpt_parse_2( $read_more_label )
	){
		$content .= ' <a class="wcpt-read-more" href="'. $product->get_permalink() .'">' 
		. wcpt_parse_2( $read_more_label ) . 
		'</a>';	
	}	
}

// -- end

echo '<div class="wcpt-content '. $html_class .'">';
echo stripslashes( $content );
echo '</div>';
