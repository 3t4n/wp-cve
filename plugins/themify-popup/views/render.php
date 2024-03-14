<?php
/**
 * Template to render popups on frontend
 *
 * @var $popups
 */
global $post;

if( ! $popups )
	return;

$tfp_post = $post;
foreach ( $popups as $post ): setup_postdata( $post );

	$id = get_the_id();

	$style = themify_popup_get( 'popup_style', 'classic' );

	$atts = array(
		'id' => "themify-popup-{$id}",
		'class' => 'themify-popup style-' . $style,
		'trigger' => themify_popup_get( 'popup_trigger', 'timedelay' ),
		'data-style' => $style,
		'style' => 'display:none', // hide the popup by default
	);

	if ( $style === 'classic' || $style === 'fullscreen' ) {
            $atts['class'] .= ' mfp-hide';
            if($style === 'classic'){
                $atts['data-position'] = themify_popup_get( 'popup_classic_position', 'center-center' );
                $atts['data-close-overlay'] = themify_popup_check( 'popup_overlay_as_close' ) ? 'yes' : 'no';
            }
	}
        elseif( $style === 'slide-out' ) {
		$atts['class'] .= ' ' . themify_popup_get( 'popup_slide_out_position', 'bottom-right' );
	}

	if ( $atts['trigger'] === 'timedelay' ) {
		$atts['time-delay'] = themify_popup_get( 'popup_trigger_time_delay', 5 );
	} 
        elseif( $atts['trigger'] === 'scroll' ) {
		$atts['scroll-position'] = themify_popup_get( 'popup_trigger_scroll_position', 0 );
		$atts['scroll-on'] = themify_popup_get( 'popup_trigger_scroll_on', 'px' );
	}
	$atts['animation'] = themify_popup_get( 'popup_animation', 'bounce' );
	$atts['animation-exit'] = themify_popup_get( 'popup_animation_exit', 'fadeOut' );

	if ( themify_popup_check( 'popup_auto_close' ) ) {
		$atts['auto-close'] = themify_popup_get( 'popup_auto_close_delay', 5 );
	}

	if ( themify_popup_check( 'popup_limit_count' ) ) {
		$atts['limit-count'] = themify_popup_get( 'popup_limit_count', 1 );
		$atts['cookie-expiration'] = themify_popup_get( 'popup_cookie_expiration', 0 );
	}

	if ( themify_popup_check( 'popup_mobile_disable' ) ) {
		if ( themify_popup_get( 'popup_mobile_disable' ) === 'on' ) {
                    $atts['display'] = 'desktop';
		} 
                elseif ( themify_popup_get( 'popup_mobile_disable' ) === 'mob' ) {
                    $atts['display'] = 'mobile';
		}
	}

	$atts['enableescapekey'] = $style === 'fullscreen' ? 'no' : 'yes';

	$atts = apply_filters( 'themify_popup_attributes', $atts );

	?>
	<div<?php echo self::get_element_attributes( $atts ); ?>>
		<?php self::the_content(); ?>
	</div>
	<?php

	include self::get_view_path( 'styles.php' );

endforeach;
wp_reset_postdata();
$post = $tfp_post;