<?php

use Codemanas\Typesense\Main\TypesenseAPI;

$passed_args = $args['passed_args'] ?? [];
$config      = $args['config'] ?? [];
$facet       = $args['facet'] ?? [];
$schema      = $args['schema'] ?? [];
?>
<div class="cmswt-MainPanel">
    <div class="cmswt-Results">
		<?php
		foreach ( $passed_args['post_types'] as $post_type ) {
			/*
			 * Developers need to be aware of the $arguments sent to templates
			 */
			?>
            <div class="cmswt-Result cmswt-Result-<?php echo TypesenseAPI::getInstance()->getCollectionNameFromSchema( $post_type ); ?>">
				<?php
				/**
				 * Codemanas\Typesense\Main\TemplateHooks main_panel_result_body - 5
				 * Codemanas\Typesense\Main\TemplateHooks pagination - 10
				 */
				do_action( 'cm_typesense_instant_search_results_main_panel_body', $config, $post_type );
				?>
            </div>
		<?php }
		?>
    </div>
</div>