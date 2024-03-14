<?php

defined( 'WPINC' ) || die;
?>

<div data-recalculate-progress-bar></div>
<div id="recalculate-info">
    <p id="recalculation-percentage"></p>
</div>
<div id="col-container" class="wp-clearfix">

    <div id="col-left">
        <div class="col-wrap">
            <div class="form-wrap">
                <h2><?php 
_e( 'Add a new currency', 'premmerce-woocommerce-multicurrency' );
?></h2>
                <form id="add-currency" method="post"
                      action="<?php 
echo  esc_url( admin_url( 'admin-post.php' ) ) ;
?>" class="validate">
                    <input type="hidden" name="action" value="add_currency"/>

                    <?php 
wp_nonce_field( 'premmerce-currency-add' );
echo  $formFields ;
submit_button( __( 'Add currency', 'premmerce-woocommerce-multicurrency' ), 'primary', 'submitBtn' );
?>
                </form>
            </div>
        </div>
    </div>
    <div id="col-right">
        <div class="col-wrap">

            <?php 
$currenciesTable->prepare_items();
$currenciesTable->display();
?>

            <?php 
?>

        </div>
    </div>
</div>