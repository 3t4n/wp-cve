<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>

<div id="revi_back" class="container">

    <div class="row">
      
        <div class="col-12">
            <img src="<?php echo plugins_url( '../assets/img/logo-256x256.png', dirname(__FILE__) )?>" /><br />
        </div>
       
       
        <div class="col-6" style="margin-top:40px;">
            
            <h2><?php esc_html_e('¿Do you have an account?', 'revi-io-customer-and-product-reviews')?></h2>

            <form method="post">
                <div class="form-group row" style="margin-top:10px;">
                    <label class="col-12"><?php esc_html_e('API KEY', 'revi-io-customer-and-product-reviews')?></label>
                    <div class="col-12">
                        <input type="text" size="30" name="REVI_API_KEY" value="" placeholder="API KEY"/>
                    </div>
                    <div class="col-12" style="margin-top:5px;">
                        <input class="btn btn-primary" type="submit" name="submitConfiguration" value="<?php esc_html_e('log in', 'revi-io-customer-and-product-reviews')?>" class="revi_button revi_button_small" />
                    </div>
                </div>
            </form>

            <?php if ($message):?>
            <div class="row">
                <div class="col-12">
                    <div class="alert <?=($result_update)?'alert-success':'alert-danger'?>" role="alert"><?=$message?></div>
                </div>
            </div>
            <?php endif;?>
        </div>
        
        <div class="col-6" style="margin-top:40px;">
            <h2><?php esc_html_e('Easy -> Professional -> Free', 'revi-io-customer-and-product-reviews')?></h2>
            <p><?php esc_html_e('Install & Configure Revi in 5 minutes', 'revi-io-customer-and-product-reviews')?></p>
            <a class="btn btn-primary" target="_blank" href="https://revi.io/en"><?php esc_html_e('Register for free')?></a>
        </div>
        
        
    </div>



</div>