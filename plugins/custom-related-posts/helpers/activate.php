<?php

class CRP_Activate {

    public function __construct()
    {
        register_activation_hook( CustomRelatedPosts::get()->pluginFile, array( $this, 'activate_plugin' ) );
        add_action( 'admin_notices',    array( $this, 'activation_notice' ) );
    }

    public function activate_plugin()
    {
        set_transient( 'crp_activated', true, 1 * HOUR_IN_SECONDS );
    }

    public function activation_notice() {
        if ( get_transient( 'crp_activated' ) ) {
?>
<div class="updated crp_notice">
    <h3>Welcome to Custom Related Posts!</h3>
    <p><strong>New here?</strong> Please check out our <a href="https://help.bootstrapped.ventures/category/159-getting-started" target="_blank">Getting Started documentation</a>!</p>
</div>
<?php
            delete_transient( 'crp_activated' );
        }
    }
}