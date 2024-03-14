<?php 
    $is_featured = ( $active == 'yes' ) ? 'vca-mid' : '' ;
?>
<div class="cols-class">
    <div class="vca-style4">
        <div class="vca-pricingTable <?php echo $is_featured; ?>">
            <div class="vca-pricingTable-header">
                <span class="vca-heading">
                    <h3><?php echo $table_title; ?></h3>
                    <?php if ( $active == 'yes' ): ?>
                        <span class="vca-popular"><?php echo $active_text; ?></span>    
                    <?php endif ?>
                </span>
                <span class="vca-price-value"><?php echo $table_currency; ?><span><?php echo $table_price; ?></span><span class="mo"> <?php echo $table_price_period; ?></span></span>
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
