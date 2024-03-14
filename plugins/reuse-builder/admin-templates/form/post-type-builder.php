<div id="reuseb_post_type_builder"></div>

<input type="hidden" id="reuseb_post_types_data" name="_reuse_builder_post_types_data">

<?php
/**
 * Localize the updated data from database
 */
wp_localize_script( 'reuseb_post_type_builder', 'REUSEB_ADMIN', apply_filters('reuseb_admin_generator_localize_args', array( 'UPDATED_POST_TYPES' => get_post_meta( $post->ID, '_reuse_builder_post_types_data', true ) )
) );
