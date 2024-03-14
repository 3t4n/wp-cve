<?php

use Codemanas\Typesense\Backend\Admin;

$args                   = $args ?? [];
$search_config_settings = Admin::get_search_config_settings();
$collections            = [];
foreach ( $search_config_settings['enabled_post_types'] as $enabled_post_type ) {
	$collections[ $enabled_post_type ] = \Codemanas\Typesense\Main\TypesenseAPI::getInstance()->getCollectionNameFromSchema( $enabled_post_type );
}
?>
<div class="cm-autocomplete"
     data-site_url="<?php echo esc_html( apply_filters( 'cm_typesense_autocomplete_search_url', trailingslashit( home_url() ) . '?s=' ) ) ?>"
     data-collections="<?php echo _wp_specialchars( json_encode( $collections ), ENT_QUOTES, 'UTF-8', true ); ?>"
     data-placeholder="<?php esc_html_e( $args['placeholder'] ?? '' ); ?>"
     data-settings="<?php echo _wp_specialchars( json_encode( apply_filters( 'cm_typesense_search_autocomplete_settings', [] ) ), ENT_QUOTES, 'UTF-8', true ); ?>"
     data-query_by="<?php esc_html_e( $args['query_by'] ?? '' ); ?>"
     data-additional_autocomplete_params="<?php echo _wp_specialchars( json_encode( apply_filters( 'cm_typesense_additional_autocomplete_params', [] ) ), ENT_QUOTES, 'UTF-8', true ); ?>"
></div>