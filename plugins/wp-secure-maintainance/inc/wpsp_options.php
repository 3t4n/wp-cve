<div class="wrap">
    <h1>WP Secure Maintenance</h1>
    <form action="options.php" method="post" enctype=”multipart/form-data”> 
<?php
        do_settings_sections( 'wpsp-settings', 'wpsp' );
        settings_fields( 'wp-secure-settings_options_group' );

        submit_button();
?>
    </form>
    </div>

<?php