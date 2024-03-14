<div id="reuseb_metabox_builder_reuse_form" class="reuseb__builder_reuse_form"></div>
<div id="reuseb_metabox_builder_form_builder"></div>

<input type="hidden" id="reuseb_metabox_builder_output" name="_reuseb_metabox_builder_output" class="reuseb__builder_output">
<?php

/**
 * Localize the updated data from database
 */
_log(get_post_meta( $arg['post_id'], '_reuseb_metabox_builder_output', true ));
wp_localize_script( 'reuseb_metabox_builder', 'REUSEB_ADMIN', apply_filters('reuseb_admin_generator_localize_args', array( 'UPDATED_METABOX' => get_post_meta( $arg['post_id'], '_reuseb_metabox_builder_output', true ) )
) );
