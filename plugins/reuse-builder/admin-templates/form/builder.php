<div id = "reuse_form_builder"></div>

<input type="text" id="reuse_form_builder_data" name="_reuse_form_builder_data" class="reuseb__builder_output">

<?php
/**
 * Localize the updated data from database
 */
wp_localize_script( 'reuseb_form_builder', 'REUSEB_ADMIN', apply_filters('reuseb_admin_generator_localize_args', array( 'UPDATED_FORM_BUILDER' => get_post_meta( $post->ID, '_reuse_form_builder_data', true ) )
) );
