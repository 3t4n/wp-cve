<div class="cols-class"> 
    <div class="price-value">
        <h2><a href="#"> <?php echo $table_title; ?></a></h2>
        <h5><span><?php echo $table_currency; ?><?php echo $table_price; ?></span><lable> <?php echo $table_price_period; ?></lable></h5>
        <?php 
            if ($active=='yes') { ?>
                <div class="sale-box">
                    <span class="on_sale title_shop"><?php echo $active_text; ?></span>
                </div> 
        <?php   }
        ?>
        

    </div>
    <div class="price-bg">
    <?php echo $content; ?>
        <?php 
            if ($table_show_button=='yes') { ?>
                <div class="cart1">
                    <a class="popup-with-zoom-anim" target="<?php echo $table_target; ?>" href="<?php echo $table_link; ?>"><?php echo $table_button_text; ?></a>
                </div>
        <?php   } ?>
        
    </div>
</div>