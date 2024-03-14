<?php

use  Premmerce\WoocommerceMulticurrency\Admin\Admin ;
defined( 'WPINC' ) || die;
?>


    <div class="wrap">
        <h1><?php 
_e( 'Advanced Settings', 'premmerce-woocommerce-multicurrency' );
?></h1>
        <div class="form-wrap">
            <?php 
?>


            <form id="premmerce-clean-cache" method="post" action="<?php 
echo  esc_url( admin_url( 'admin-post.php' ) ) ;
?>">
                <?php 
wp_nonce_field( Admin::CLEAN_PLUGIN_CACHE_ACTION, 'premmerceNonce' );
?>
                <input type="hidden" name="action" value="<?php 
echo  Admin::CLEAN_PLUGIN_CACHE_ACTION ;
?>">
                <div class="premmerce-multicurrency-advanced-settings-buttons">
                    <?php 
submit_button(
    __( 'Clear cache', 'premmerce-woocommerce-multicurrency' ),
    'secondary',
    'cleanCache',
    false
);
?>
                </div>
            </form>


        </div>
    </div>






