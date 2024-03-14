<?php
/**
 * Testimonials item template
 */


$preset = $this->get_settings( 'preset' );

$item_image = $this->_loop_item( array( 'item_image', 'url' ), '%s' );
$item_image = apply_filters('lakit_wp_get_attachment_image_url', $item_image);

$post_classes = ['lakit-testimonials__item'];
$post_classes[] = $this->_loop_item( array( 'el_class' ), '%s' );
$post_classes[] = $this->_loop_item( array( '_id' ), 'elementor-repeater-item-%s' );

if(filter_var( $this->get_settings_for_display('enable_carousel'), FILTER_VALIDATE_BOOLEAN )){
    $post_classes[] = 'swiper-slide';
}
else{
    $post_classes[] = lastudio_kit_helper()->col_new_classes('columns', $this->get_settings_for_display());
}

?>
<div class="<?php echo esc_attr(join(' ', $post_classes)); ?>">
    <?php
    if(!empty($item_image) && $preset === 'type-12'){
        echo '<div class="lakit-testimonials__figure">';
        do_action('lastudio-kit/testimonials/output/before_image', $preset);
        echo sprintf('<span class="lakit-testimonials__tag-img"><span style="background-image: url(\'%1$s\')"></span></span>', $item_image );
        do_action('lastudio-kit/testimonials/output/after_image', $preset);
        echo '</div>';
    }
    ?>
	<div class="lakit-testimonials__item-inner">
        <?php
            if( !empty($item_image) && $preset === 'type-13' ){
                echo '<div class="lakit-testimonials__figure">';
                do_action('lastudio-kit/testimonials/output/before_image', $preset);
                echo sprintf('<span class="lakit-testimonials__tag-img"><span style="background-image: url(\'%1$s\')"></span></span>', $item_image );
                do_action('lastudio-kit/testimonials/output/after_image', $preset);
                echo '</div>';
            }
        ?>
		<div class="lakit-testimonials__content"><?php
            if(!empty($item_image) && !in_array($preset, ['type-13', 'type-12'])){
                echo '<div class="lakit-testimonials__figure">';
                do_action('lastudio-kit/testimonials/output/before_image', $preset);
                echo sprintf('<span class="lakit-testimonials__tag-img"><span style="background-image: url(\'%1$s\')"></span></span>', $item_image );
                do_action('lastudio-kit/testimonials/output/after_image', $preset);
                echo '</div>';
            }
            if( $this->get_settings('use_title_field') === 'yes' ){
                echo $this->_loop_item( array( 'item_title' ), '<div class="lakit-testimonials__title">%s</div>' );
            }

            echo '<div class="lakit-testimonials__comment">';
                if($preset === 'type-11'){
                    if($this->get_settings('replace_star')){
	                    $this->maybe_render_quote_icon();
                    }
                    else{
                        $item_rating = $this->_loop_item( array( 'item_rating' ), '%d' );
                        if(absint($item_rating)> 0){
                            $percentage =  (absint($item_rating) * 10) . '%';
                            echo '<div class="lakit-testimonials__rating"><span class="star-rating"><span style="width: '.$percentage.'"></span></span></div>';
                        }
                    }
                }
                echo $this->_loop_item( array( 'item_comment' ), '<div>%s</div>' );
            echo '</div>';

            if( in_array($preset, ['type-10', 'type-11']) ){
                echo '<div class="lakit-testimonials__infowrap">';
                echo '<div class="lakit-testimonials__infowrap2">';
            }

            echo $this->_loop_item( array( 'item_name' ), '<div class="lakit-testimonials__name"><span>%s</span></div>' );
            echo $this->_loop_item( array( 'item_position' ), '<div class="lakit-testimonials__position"><span>%s</span></div>' );

            if( in_array($preset, ['type-10', 'type-11']) ){
                echo '</div>';
            }
            if($preset !== 'type-11'){
                if($this->get_settings('replace_star')){
                    $this->maybe_render_quote_icon();
                }
                else{
                    $item_rating = $this->_loop_item( array( 'item_rating' ), '%d' );
                    if(absint($item_rating)> 0){
                        $percentage =  (absint($item_rating) * 10) . '%';
                        echo '<div class="lakit-testimonials__rating"><span class="star-rating"><span style="width: '.$percentage.'"></span></span></div>';
                    }
                }
            }
            if(in_array($preset, ['type-10', 'type-11'])){
                echo '</div>';
            }
		?></div>
	</div>
</div>