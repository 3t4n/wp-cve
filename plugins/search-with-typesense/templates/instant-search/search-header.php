<?php

$facets = $args['facet'] ?? [];
$config = $args['config'] ?? [];
$schema = $args['schema'] ?? [];
?>
<div class="cmswt-Header">
    <div class="cmswt-SearchBox"
         data-settings="<?php echo _wp_specialchars( json_encode( apply_filters( 'cm_typesense_search_box_settings', [] ) ),
		     ENT_QUOTES,
		     'UTF-8',
		     true ); ?>"
    ></div>
    <div class="cmswt-SubHeader">
		<?php
		/**
		 * Codemanas\Typesense\Main\TemplateHooks index_switcher - 5
		 * Codemanas\Typesense\Main\TemplateHooks sort_by - 10
		 */
		do_action( 'cm_typesense_instant_search_sub_header', $args['passed_args'], $config, $facets, $schema );
		?>
    </div>
	<?php
	/**
	 * Codemanas\Typesense\Main\TemplateHooks stats -5
	 */
	do_action( 'cm_typesense_instant_search_stats', $args['passed_args'], $config, $facets, $schema );
	?>
    <div class="cmswt-Refinements">
		<?php
		/**
		 * Codemanas\Typesense\Main\TemplateHooks show_current_refinements - 5
		 * Codemanas\Typesense\Main\TemplateHooks show_clear_refinements - 10
		 */
		do_action( 'cm_typesense_instant_search_refinements', $args['passed_args'], $config, $facets, $schema );
		?>
    </div>
</div>