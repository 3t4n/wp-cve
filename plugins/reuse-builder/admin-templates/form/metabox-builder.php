<div id="reuseb_metabox_builder_reuse_form" class="reuseb__builder_reuse_form"></div>
<div id="reuseb_metabox_builder_form_builder"></div>

<input type="hidden" id="reuseb_metabox_builder_output" name="_reuseb_metabox_builder_output" class="reuseb__builder_output">
<?php

$reuse_builder_meta_pre_data = get_post_meta( $post->ID, '_reuseb_metabox_builder_output', true );

/**
 * Localize the updated data from database
 */
wp_localize_script( 'reuseb_form_builder', 'REUSEB_ADMIN', apply_filters('reuseb_admin_generator_localize_args', array( 'UPDATED_METABOX' => $reuse_builder_meta_pre_data )
) );

/**
 * Localize the updated data from database
 */
wp_localize_script( 'reuseb_metabox_builder', 'REUSEB_ADMIN', apply_filters('reuseb_admin_generator_localize_args', array( 'UPDATED_METABOX' => $reuse_builder_meta_pre_data )
) );
