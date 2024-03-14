<div class="wrap crp-import">

    <div id="icon-themes" class="icon32"></div>
    <h2><?php _e( 'Import XML', 'custom-related-posts' ); ?></h2>
    <h3><?php _e( 'Before importing', 'custom-related-posts' ); ?></h3>
    <ol>
        <li><?php _e( "It's a good idea to backup your WP database before using the import feature.", 'custom-related-posts' ); ?></li>
        <li>Select the XML file containing relations in the Custom Related Posts format:</li>
    </ol>
    <form method="POST" action="<?php echo admin_url( 'options-general.php?page=crp_import_xml_manual' ); ?>" enctype="multipart/form-data">
        <input type="hidden" name="action" value="import_xml_manual">
        <?php wp_nonce_field( 'crp_import_xml', 'crp_import_xml' ); ?>
        <input type="file" name="xml">
        <?php submit_button( __( 'Import XML', 'custom-related-posts' ) ); ?>
    </form>
</div>