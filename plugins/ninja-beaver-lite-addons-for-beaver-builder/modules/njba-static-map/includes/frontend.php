<?php $admin_option = get_option( 'njba_options' ); ?>
<?php if ( $admin_option['google_static_map_api_key'] !== '' ) { ?>
    <img src="<?php echo $module->njba_get_google_static_map_url(); ?>" alt="<?php echo sprintf( __( '%1$s Location', 'bb-njba' ), get_bloginfo( 'name' ) ); ?>">
<?php } else { ?>
    <p> You need to enter a places map api key which can be found here.</p>
<?php } ?>
