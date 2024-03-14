<?php

// Do not allow direct access to the file.
if( ! defined( 'ABSPATH' ) ) {
	exit;
}




// Getting Attachment IDs Function
function stillbe_iqc_get_attachment_ids( $target = null ) {

	$args = array(
		'post_type'      => 'attachment',
		'post_status'    => 'inherit',
		'post_mime_type' => 'image/*',
		'posts_per_page' => -1,
		'fields'         => 'ids',
	);

	// Date
	if( ! empty( $target['date']['start'] ) || ! empty( $target['date']['end'] ) ) {
		$start = empty( $target['date']['start'] ) ? '1970-01-01'            : wp_date( 'Y-m-d', @strtotime( $target['date']['start'] ) );
		$end   = empty( $target['date']['end'] )   ? current_time( 'Y-m-d' ) : wp_date( 'Y-m-d', @strtotime( $target['date']['end']   ) );
		$args['date_query'] = array(
			array(
				'compare'   => 'BETWEEN',
				'inclusive' => true,
				'after'     => $start,
				'before'    => $end,
			),
		);
	}

	// Mime-Type
	$types = array();
	if( ! empty( $target['type'] ) && is_array( $target['type'] )
	      && ( isset( $target['type']['auto-webp'] ) && ! $target['type']['auto-webp'] ) ) {
		foreach( $target['type'] as $mime => $bool ) {
			if( $bool && false === strpos( $mime, '-' ) ) {
				$types[] = 'image/'. strval( $mime );
			}
		}
		$args['post_mime_type'] = empty( $types ) ? 'image/sb+iqc+unthinkable+type' : $types;
	}

	// Get Attachment IDs
	$attachment_ids = get_posts( $args );

	// Sort
	rsort( $attachment_ids, SORT_NUMERIC );

	// Save to wp_options
	$result=update_option( '_sb-iqc-image-ids', $attachment_ids, false );
file_put_contents(__DIR__.'/test.dat', json_encode($result));

	return $target ? array( 'ids' => $attachment_ids, 'args' => $args ) : $attachment_ids;

}





// END

?>