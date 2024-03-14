<?php
$facets = $args['facet'] ?? [];
$config = $args['config'] ?? [];
$schema = $args['schema'] ?? [];

$passed_args = $args['passed_args'] ?? [];
// Removed a logic to check multiple posttypes here so multi faceting can work
if ( $passed_args['filter'] === 'show' && ! empty( $facets ) ) { ?>
    <div class="cmswt-FilterPanel">
        <div class="cmswt-FilterPanel-toggle">
            <span class="cmswt-FilterPanel-toggleLabel">
                 <?php _e( 'Filter', 'search-with-typesense' ); ?>
            </span>
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 cmswt-FilterPanel-toggleIcon" fill="none"
                 viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
            </svg>
        </div>
        <div class="cmswt-FilterPanel-items">
            <div class="cmswt-FilterPanel-itemsPopupHeader">
                <div class="cmswt-FilterPanel-itemsPopupLabel">
                    <h3 class="cmswt-FilterPanel-itemsPopupLabelHeader"><?php _e( 'Filter Search Results', 'search-with-typesense' ); ?></h3>
                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-6 w-6 cmswt-FilterPanel-itemsPopupHeaderCloseLogo cmswt-FilterPanel-itemsClose"
                         fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </div>
            </div>
            <div class="cmswt-FilterPanel-itemsContent">
				<?php
				do_action( 'cm_typesense_instant_search_results_before_filter_panel', $facets );
				foreach ( $facets as $post_type => $filters ) {
					foreach ( $filters as $filter ) {
						?>
                        <div class="cmswt-Filter cmswt-Filter-<?php echo esc_html( $filter ); ?> cmswt-Filter-collection_<?php echo $post_type; ?>"
                             data-title="<?php esc_html_e(
							     apply_filters(
								     'cm_typesense_search_facet_title',
								     sprintf( 'Filter by %s', ucwords( esc_html( $filter ) ) )
								     ,
								     $filter, $post_type ),
							     'search-with-typesense' ); ?>"
                             data-settings="<?php echo _wp_specialchars( json_encode( apply_filters( 'cm_typesense_search_facet_settings', [ 'searchable' => false ], $filter, $post_type ) ), ENT_QUOTES, 'UTF-8', true ); ?>"
                             data-filter_type="<?php echo apply_filters( 'cm_typesense_filter_type', 'refinement', $filter, $post_type ) ?>"
                        ></div>
					<?php }
				}
				do_action( 'cm_typesense_instant_search_results_after_filter_panel', $facets );
				?>
            </div>
            <div class="cmswt-FilterPanel-itemsFooter">
                <a class="cmswt-FilterPanel-itemsFooterCloseLink cmswt-FilterPanel-itemsClose">Close</a>
            </div>
        </div>
    </div>
<?php }