<?php

use Codemanas\Typesense\Main\TypesenseAPI;

$args = $args ?? [];
?>

<div class="cmswt-Sort">
	<?php
	foreach ( $args['passed_args']['post_types'] as $post_type ) {
		if ( $post_type === 'category' ) {
			$sortByItems = apply_filters( 'cm_typesense_search_sortby_items', [
				[
					'label' => __( 'Most Posts', 'search-with-typesense' ),
					'value' => TypesenseAPI::getInstance()->getCollectionNameFromSchema( $post_type )
				],
				[
					'label' => __( 'Least Posts', 'search-with-typesense' ),
					'value' => TypesenseAPI::getInstance()->getCollectionNameFromSchema( $post_type ) . '/sort/posts_count:asc'
				],
			], $post_type );
		} else {
			$sortByItems = apply_filters( 'cm_typesense_search_sortby_items', [
				[
					'label' => __( 'Recent', 'search-with-typesense' ),
					'value' => TypesenseAPI::getInstance()->getCollectionNameFromSchema( $post_type )
				],
				[
					'label' => __( 'Oldest', 'search-with-typesense' ),
					'value' => TypesenseAPI::getInstance()->getCollectionNameFromSchema( $post_type ) . '/sort/sort_by_date:asc'
				],
			], $post_type );
		}
		?>
        <div class="cmswt-SortBy cmswt-SortBy-<?php echo esc_html( TypesenseAPI::getInstance()->getCollectionNameFromSchema( $post_type ) ); ?>"
             data-settings="<?php echo _wp_specialchars( json_encode( apply_filters( 'cm_typesense_search_sortby_settings', [
			     'items' => $sortByItems,
		     ], $post_type ) ),
			     ENT_QUOTES,
			     'UTF-8',
			     true ); ?>"
        ></div>

	<?php } ?>
</div>