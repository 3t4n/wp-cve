<div class="wrap moove-taxonomy-settings-plugin-wrap" id="radioselect-settings-cnt">
	<h1><?php _e('Taxonomy Buttons - Plugin Settings','moove'); ?></h1>
    <?php
        $current_tab = isset( $_GET['tab'] ) ? sanitize_text_field( wp_unslash( urlencode( $_GET['tab'] ) ) ) : '';
        $current_tab = strtolower( esc_attr( $current_tab ) );
        $allowed_tabs = array( 'post_type_radioselect', 'plugin_documentation' );
        if( isset( $current_tab ) && in_array( $current_tab, $allowed_tabs )  &&  $current_tab !== '' ) {
            $active_tab = $current_tab;
        } else {
            $active_tab = "post_type_radioselect";
        } // end if
    ?>
    <br />
    <div class="radioselect-tab-section-cnt">
        <h2 class="nav-tab-wrapper">
            <a href="?page=moove-taxonomy-settings&tab=post_type_radioselect" class="nav-tab <?php echo $active_tab == 'post_type_radioselect' ? 'nav-tab-active' : ''; ?>">
                <?php _e('Plugin Settings','moove'); ?>
            </a>

            <a href="?page=moove-taxonomy-settings&tab=plugin_documentation" class="nav-tab <?php echo $active_tab == 'plugin_documentation' ? 'nav-tab-active' : ''; ?>">
                <?php _e('Documentation','moove'); ?>
            </a>
        </h2>
        <div class="moove-form-container <?php echo $active_tab; ?>">           
            <?php
            if( $active_tab == 'post_type_radioselect' ) : ?>
                <form action="options.php" method="post" class="moove-taxonomy-settings-form">
                    <?php
                    settings_fields( 'moove_radio_select' );
                    do_settings_sections( 'moove-taxonomy-settings' );
                    echo "<hr>";
                    submit_button();
                    ?>
                </form>
            <?php elseif( $active_tab == 'plugin_documentation' ): ?>
                <?php echo Moove_Radioselect_View::load( 'moove.admin.settings.documentation' , true ); ?>
            <?php endif; ?>
        </div>
        <!-- moove-form-container -->
    </div>
    <!--  .radioselect-tab-section-cnt -->
    <?php 
        $view_cnt = new Moove_Radioselect_View();
        echo $view_cnt->load( 'moove.admin.settings.plugin_boxes', array() );
    ?>
</div>
<!-- wrap -->