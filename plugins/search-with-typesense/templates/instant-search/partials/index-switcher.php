<?php

use Codemanas\Typesense\Main\TypesenseAPI;

$args                    = $args ?? [];
$unique_id               = $args['passed_args']['unique_id'];
$switcher_html           = '';
$active_collection_label = '';
$single_source_class     = "";
if ( ! empty( $args['schema'] ) ) {
	$i                   = 0;
	$single_source_class = ( count( $args['schema'] ) > 1 ) ? '' : 'cmswt-CollectionMenu--singleSource';
	ob_start();
	?>
    <ul class="cmswt-IndexSwitcher">
		<?php
		foreach ( $args['schema'] as $post_type => $collection ) {
			$label = esc_html( cm_swt_get_label( $post_type, $args['config'] ) );
			if ( $i == 0 ) {
				$active_collection_label = $label;
			}
			?>
            <li class="cmswt-IndexSwitcher-item <?php echo ( 0 == $i ) ? 'active' : ''; ?>">
                <a href="#"
                   class="cmswt-IndexSwitcher-link"
                   data-instance_id="<?php echo $unique_id ?>"
                   data-collection="<?php esc_html_e( TypesenseAPI::getInstance()->getCollectionNameFromSchema( $post_type ) ); ?>">
					<?php echo $label; ?>
                </a>
            </li>
			<?php
			$i ++;
		}
		?>
    </ul>
	<?php
	$switcher_html = ob_get_clean();
}


?>
<div class="cmswt-CollectionMenu <?php echo $single_source_class; ?>">
    <div class="cmswt-CollectionMenu-current">
        <span class="cmswt-CollectionMenu-currentLabel"><?php echo $active_collection_label; ?></span>
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 cmswt-CollectionMenu-icon" fill="none"
             viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
            <path stroke-linecap="round" stroke-linejoin="round"
                  d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
        </svg>
    </div>
	<?php echo $switcher_html ?? ''; ?>
</div>