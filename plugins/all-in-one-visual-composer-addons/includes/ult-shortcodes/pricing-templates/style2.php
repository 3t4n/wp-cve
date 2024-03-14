<?php 
    $is_featured = ( $active == 'yes' ) ? 'vca-mid' : '' ;
 ?>
<div class="cols-class">
    <div class="vca-style2">
        <div class="vca-pricingTable <?php echo $is_featured; ?>">
            <div class="vca-pricingTable-header">
                <span class="vca-heading">
                    <h3><?php echo $table_title; ?></h3>
                </span>
                <span class="vca-price-value"><span class="vca-price">Price</span><span class="mo"><?php echo $table_currency; ?><?php echo $table_price; ?> <?php echo $table_price_period; ?></span></span>
            </div>

            <div class="vca-pricingContent">
                    <?php echo $content; ?>
            </div><!-- /  CONTENT BOX-->
            <?php if ($table_show_button=='yes') {  ?>
            <div class="vca-pricingTable-sign-up">
                <a href="<?php echo ($table_link != '') ? $table_link : 'javascript:void(0)'; ?>" class="btn btn-block btn-default"><?php echo $table_button_text; ?></a>
            </div><!-- BUTTON BOX-->
            <?php   } ?>
        </div>
    </div>
</div>