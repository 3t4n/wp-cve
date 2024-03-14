<?php 
$product_link = "https://codebangers.com/product/all-in-one-time-clock/";
$view_text = esc_attr_x('View Pro Product Page', 'aio-time-clock-lite');
$thickbox_link = "#TB_inline?width=1000&height=650&inlineId=aio-modal-window-id";
?>
<div class="wrap aio_admin_wrapper">
    <div class="proDiv">
        <h2><?php echo esc_attr_x('All In One Time Clock Pro', 'aio-time-clock-lite'); ?></h2>
        <hr>
        <i><?php echo esc_attr_x('More features than you can shake a stick at', 'aio-time-clock-lite'); ?></i>    
        <hr>
    </div>

    <div class="aio-container">
        <?php 
            $features = $this->getProFeatures();
            $count = 0;
            foreach($features as $feature){        
                ?>
                <a class="aio-item-link" onClick="getProPopup(this)" data-count="<?php echo esc_attr($count); ?>">
                    <div class="aio-item">
                        <div id="featureTitle-<?php echo esc_attr($count);?>" class="aio-item-row aioCardTitle">
                            <?php echo esc_attr($feature["title"]); ?>
                        </div>
                        <div class="aio-item-row">
                            <img class="aioCardImage" id="featureImage-<?php echo esc_attr($count);?>" src="<?php echo esc_url(plugins_url($feature["image"], __FILE__)); ?>">
                        </div>
                        <div class="aio-item-row">
                            <p class="aioCardDescText" id="featureDesc-<?php echo esc_attr($count);?>">
                                <?php echo esc_attr($feature["description"]); ?>
                            </p>
                        </div>
                    </div>
                </a>      
                <?php 
                $count++;
            }
        ?>
    </div>

    <div class="proDiv">
        <hr>
        <p><a target="_blank" class="button-primary" href="<?php echo esc_url($product_link); ?>"><?php echo esc_attr($view_text); ?></a></p>
        <hr>
    </div>

    <?php add_thickbox(); ?>
    <div id="aio-modal-window-id" style="display:none;">
        <div id="aio_modal_title"></div>
        <hr>
        <div id="aio_modal_image"></div>
        <hr>
        <div id="aio_modal_description">Loading...</div>
        <div class="getProButton">
            <hr>
            <a target="_blank" class="button-primary" href="<?php echo esc_url($product_link); ?>"><?php echo esc_attr($view_text); ?></a>
        </div>
    </div>
<div>