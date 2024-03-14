<div id="reuseb_template_metabox"></div>

<?php
/**
 * Localize the updated data from database
 */
wp_localize_script( 'reuseb_template_settings', 'REUSEB_ADMIN', apply_filters('reuseb_admin_generator_localize_args', array( 'UPDATED_TEMPLATE' => get_post_meta( $post->ID, '_reuseb_template_data', true ) )
) );

?>
<input type="hidden" id="_reuseb_template_data" name="_reuseb_template_data">
