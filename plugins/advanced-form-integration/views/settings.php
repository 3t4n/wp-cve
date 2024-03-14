<div class="wrap">

    <div id="icon-options-general" class="icon32"></div>
    <h1><?php esc_attr_e( "Advanced Form Integration - Settings", "advanced-form-integration" ); ?></h1>

    <?php
    $current_tab = isset( $_REQUEST['tab'] ) ? $_REQUEST['tab'] : 'general';
    ?>
    <h2 class="nav-tab-wrapper">
        <?php foreach ($tabs as $tab_key => $tab_label) { ?>
            <a class="nav-tab <?php echo ( $current_tab == $tab_key ) ? 'nav-tab-active' : ''; ?>" href="<?php echo admin_url( 'admin.php?page=advanced-form-integration-settings&tab=' ) . $tab_key; ?>"><?php echo $tab_label ?></a>
        <?php } ?>
    </h2>

    <?php
    if( $current_tab == 'general' ) {

    }

    do_action( 'adfoin_settings_view', $current_tab );
    ?>
</div> <!-- .wrap -->