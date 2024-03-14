<?php

namespace Elementor;
defined('ABSPATH') || exit;
$uid = uniqid();

// check if the collapse enabled
$collapse	   = false;
$collapse_expand = '';


/**
 * 
 * Check weather the collapse enabled or not 
 * 
 */ 
if( $settings['shopengine_filter_view_mode']['desktop'] === 'collapse' ) {
	$collapse	   = true;
}
/**
 * 
 * Check weather the collapse expand enable or not 
 * 
 */ 

if(!empty($_GET) && !empty($_GET["color_nonce"]) && wp_verify_nonce( sanitize_text_field(wp_unslash($_GET["color_nonce"])), "color_filter")) {

    foreach ($_GET as $key => $value) { 
        if(strpos($key, 'shopengine_filter_color') !== false) {
            $collapse_expand = 'open';
            break;
        }
    }
}

if(  $settings['shopengine_filter_color_expand_collapse']['desktop'] === true ) {
	$collapse_expand = 'open';
}


?>

<div class="shopengine-filter-single <?php echo esc_attr( $collapse ? 'shopengine-collapse' : '' ) ?>">
    <div class="shopengine-filter">
        
        <?php 
            /**
             * 
             * show filter title
             * 
             */
            if(isset($settings['shopengine_filter_color_title']['desktop'])) : 
        ?>
            <div class="shopengine-filter <?php echo esc_attr( $collapse_expand ) ?>">
                <h3 class="shopengine-product-filter-title">
                    <?php 
                        echo esc_html($settings['shopengine_filter_color_title']['desktop']);
                        if( $collapse ) echo '<i class="eicon-chevron-right shopengine-collapse-icon"></i>';
                    ?>
                </h3>
            </div>

		<?php 
        
        endif;  // end of filter title 

        if( $collapse ) echo '<div class="shopengine-collapse-body '. esc_attr($collapse_expand) .'">';
				
        /**
         * 
         * loop through attribute list item
         * 
         */
        foreach($color_options as $option) : ?>
            <div class="filter-input-group">
                <input
                    class="shopengine-filter-colors shopengine_filter_color_<?php echo esc_attr($option->taxonomy); ?>-<?php echo esc_attr($option->slug); ?>"
                    name="shopengine_filter_color_<?php echo esc_attr($option->taxonomy); ?>"
                    type="checkbox"
                    id="xs-filter-color-<?php echo esc_attr($uid . '-' . $option->term_id); ?>"
                    value="<?php echo esc_attr($option->slug); ?>"
                    data-taxo="<?php echo esc_attr($option->taxonomy); ?>" />

                <label class="shopengine-filter-color-label" for="xs-filter-color-<?php echo esc_attr($uid . '-' . $option->term_id); ?>">
					<span class="shopengine-checkbox-icon">
						<span>
							<?php render_icon($settings['shopengine_check_icon']['desktop'], ['aria-hidden' => 'true']); ?>
						</span>
					</span>
					<?php if($settings['shopengine_filter_color_dot_status']['desktop']) : ?>
                        <span class="color-filter-dot"
                              style="background: <?php echo strpos($option->color, '#') === 0 ? "" : "#"; ?><?php echo esc_attr($option->color); ?>"></span>
					<?php endif; ?>
					<?php echo esc_html($option->name); ?>
                </label>
            </div>
		<?php 
            endforeach; 
            if( $collapse ) echo '</div>'; // end of collapse body container
        ?>
    </div>

    <form action="" method="get" class="shopengine-filter" id="shopengine_color_form">
        <input type="hidden" name="shopengine_filter_color" class="shopengine-filter-colors-value">
        <input type="hidden" name="color_nonce" value="<?php echo esc_attr(wp_create_nonce("color_filter")) ?>">
    </form>
</div>
