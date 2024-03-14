<?php

use Codemanas\Typesense\Backend\Admin;
use Codemanas\Typesense\Main\TypesenseAPI;

$args = $args ?? [];

$args['post_types'] = is_array( $args['post_types'] ) ? $args['post_types'] : explode( ",", $args['post_types'] );
//support for multi-site with same cluster
$collection_names_from_post_type = [];
foreach ( $args['post_types'] as $post_type ) {
	$collection_names_from_post_type[] = TypesenseAPI::getInstance()->getCollectionNameFromSchema( $post_type );
}
$args['collections'] = $collection_names_from_post_type;

$schemas = [];
$facets  = [];
if ( ! empty( $args['post_types'] ) && is_array( $args['post_types'] ) ):
	foreach ( $args['post_types'] as $post_type ) {
		$schemaName            = TypesenseAPI::getInstance()->getCollectionNameFromSchema( $post_type );
		$schemas[ $post_type ] = TypesenseAPI::getInstance()->getCollectionInfo( $schemaName );

		if ( is_wp_error( $schemas[ $post_type ] ) ) {
			if ( current_user_can( 'administrator' ) ) {
				$cm_ts_settings_url = admin_url( "admin.php?page=codemanas-typesense" );
				?>
                <strong><?php echo wp_sprintf( 'There seems to be some issue with settings. Please check the logs from %1$shere%2$s or troubleshoot from %3$shere%4$s', '<a href="' . $cm_ts_settings_url . '">', '</a>', '<a href="https://docs.wptypesense.com/debug/debug/">', '</a>' ); ?></strong>
				<?php
				return;
			}
		} else {

			foreach ( $schemas[ $post_type ]->fields as $field ) {
				if ( isset( $field->facet ) && $field->facet ) {
					$facets[ $schemaName ][] = $field->name;
				}
			}
		}
	}
endif;
$config                = Admin::get_search_config_settings();
$additional_classes    = [ $args['unique_id'] ];
$additional_classes [] = ( $args['filter'] === 'show' && 1 === count( $args['post_types'] ) ) ? 'single-source' : 'multi-source';
if ( isset( $args['custom_class'] ) && ! empty( $args['custom_class'] ) ) {
	$custom_classes = explode( ',', $args['custom_class'] );
	foreach ( $custom_classes as $custom_class ) {
		$additional_classes[] = $custom_class;
	}
}
?>

<div class="cmswt-InstantSearch ais-InstantSearch <?php echo esc_html( implode( ' ', $additional_classes ) ); ?>"
     data-id="<?php echo esc_html( $args['unique_id'] ); ?>"
     data-config="<?php echo _wp_specialchars( json_encode( $args ), ENT_QUOTES, 'UTF-8', true ); ?>"
     data-facets="<?php echo _wp_specialchars( json_encode( $facets ), ENT_QUOTES, 'UTF-8', true ); ?>"
     data-placeholder="<?php echo esc_html( $args['placeholder'] ?? "Search for..." ); ?>"
     data-query_by="<?php echo esc_html( $args['query_by'] ); ?>"
     data-sticky_first="<?php echo esc_html( $args['sticky_first'] ); ?>"
     data-additional_search_params="<?php echo _wp_specialchars( json_encode( apply_filters( 'cm_typesense_additional_search_params', [] ) ), ENT_QUOTES, 'UTF-8', true ); ?>"
     data-search_query="<?php echo esc_html( $args['search_query'] ); ?>"
     data-routing="<?php echo esc_attr( apply_filters( 'cm_typesense_routing', $args['routing'] ) ); ?>"
>
	<?php
	do_action( 'cm_typesense_before_instant_search_results_output', $args, $config, $facets, $schemas );


	/**
	 * @hooked Codemanas\Typesense\Main\TemplateHooks search_bar - 5
	 * @hooked Codemanas\Typesense\Main\TemplateHooks filter_panel - 10
	 * @hooked Codemanas\Typesense\Main\TemplateHooks main_panel - 15 [includes title, sort_by, result, pagination]
	 */
	do_action( 'cm_typesense_instant_search_results_output', $args, $config, $facets, $schemas );
	do_action( 'cm_typesense_after_instant_search_results_output', $config, $args ); ?>
</div>
