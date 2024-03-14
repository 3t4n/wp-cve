<?php
/**
 * Testimonials template
 */

$preset             = $this->get_settings_for_display('preset');
$enable_carousel    = filter_var( $this->get_settings_for_display('enable_carousel'), FILTER_VALIDATE_BOOLEAN );


$this->add_render_attribute( 'main-container', 'id', 'tt_' . $this->get_id() );

$this->add_render_attribute( 'main-container', 'class', array(
    'lakit-testimonials',
    'preset-' . $preset,
) );

$this->add_render_attribute( 'list-container', 'class', array(
    'lakit-testimonials__list'
) );

$this->add_render_attribute( 'list-container', 'data-item_selector', array(
    '.lakit-testimonials__item'
) );

$this->add_render_attribute( 'list-wrapper', 'class', 'lakit-testimonials__list_wrapper');

$is_carousel = false;

if ( filter_var( $this->get_settings_for_display( 'use_comment_corner' ), FILTER_VALIDATE_BOOLEAN ) ) {
    $this->add_render_attribute('list-container', 'class', 'lakit-testimonials--comment-corner');
}

if(!$enable_carousel){
    $this->add_render_attribute( 'list-container', 'class', 'col-row' );
}
if( $enable_carousel ){
    $slider_options = $this->get_advanced_carousel_options('columns');
    if(!empty($slider_options)){
        $is_carousel = true;
        $this->add_render_attribute( 'main-container', 'data-slider_options', json_encode($slider_options) );
        $this->add_render_attribute( 'main-container', 'dir', is_rtl() ? 'rtl' : 'ltr' );
        $this->add_render_attribute( 'list-wrapper', 'class', 'swiper-container');
        $this->add_render_attribute( 'list-container', 'class', 'swiper-wrapper' );
        $this->add_render_attribute( 'main-container', 'class', 'lakit-carousel' );
        $carousel_id = $this->get_settings_for_display('carousel_id');
        if(empty($carousel_id)){
            $carousel_id = 'lakit_carousel_' . $this->get_id();
        }
        $this->add_render_attribute( 'list-wrapper', 'id', $carousel_id );
    }
}

?>

<div <?php echo $this->get_render_attribute_string( 'main-container' ); ?>>
    <?php
    if($is_carousel){
        echo '<div class="lakit-carousel-inner">';
    }
    ?>
    <div <?php echo $this->get_render_attribute_string( 'list-wrapper' ); ?>>
        <div <?php echo $this->get_render_attribute_string('list-container'); ?>>
            <?php $this->_get_global_looped_template( 'testimonials', 'item_list' ); ?>
        </div>
    </div>
    <?php
    if($is_carousel){
        echo '</div>';

        if (filter_var($this->get_settings_for_display('carousel_dots'), FILTER_VALIDATE_BOOLEAN)) {
            echo '<div class="lakit-carousel__dots lakit-carousel__dots_'.$this->get_id().' swiper-pagination"></div>';
        }
        if (filter_var($this->get_settings_for_display('carousel_arrows'), FILTER_VALIDATE_BOOLEAN)) {
            echo sprintf('<div class="lakit-carousel__prev-arrow-%s lakit-arrow prev-arrow">%s</div>', $this->get_id(), $this->_render_icon('carousel_prev_arrow', '%s', '', false));
            echo sprintf('<div class="lakit-carousel__next-arrow-%s lakit-arrow next-arrow">%s</div>', $this->get_id(), $this->_render_icon('carousel_next_arrow', '%s', '', false));
        }
        if (filter_var($this->get_settings_for_display('carousel_scrollbar'), FILTER_VALIDATE_BOOLEAN)) {
	        echo sprintf('<div class="lakit-carousel__scrollbar swiper-scrollbar lakit-carousel__scrollbar_%1$s"></div>', $this->get_id());
        }
    }
    ?>
</div>