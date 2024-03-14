<?php wp_enqueue_style( 'timeline-wp-'. $settings['type'] , plugin_dir_url( __FILE__ ) . 'style.css', null, '1.0' ); ?>
<?php
    $id = $atts['id'];
    $string = get_post_meta($id, 'timelines-data', true ) ;
    $timelines = unserialize( $string );
    if (empty($timelines)) {
        return;
    }
    
?>
<div id="twp-<?php echo esc_attr( $id ); ?>" class="<?php echo esc_attr( $settings['type'] ); ?>">
    <?php foreach ($timelines as $timeline ) : ?>
        <div id="twp-telement-<?php echo esc_attr( $timeline['twp_index'] ); ?>" class="timeline">
            <div class="timeline-content">

                <div class="timeline-year">
                    <?php echo !empty( $timeline['twp_date'] ) ? '<span>' . esc_attr( $timeline['twp_date']) . '</span>' : ''; ?>
                </div>
                
                <div class="timeline-icon">
                    <?php if ( $timeline['icon_type']=='image' ) { 
                        $image_url =  ( $timeline[ 'twp_image' ] !== '0' ) ?  wp_get_attachment_image_src( $timeline['twp_image'], 'twp-icon' )[0] : TIMELINE_WP_IMAGES . 'timeline-default.png';  ?>
                        <img src="<?php echo esc_attr( $image_url) ;?> " > 
                    <?php } else { ?>
                        <i class="<?php echo esc_attr( $timeline['twp_icon'] ) ?>"></i>
                    <?php }  ?>
                </div>

                <?php echo !empty( $timeline['twp_title'] ) ? '<h3 class="title">' . esc_attr( $timeline['twp_title']) . '</h3>' : ''; ?>
                <?php echo !empty( $timeline['twp_description'] ) ? '<div class="description">' . do_shortcode( htmlspecialchars_decode( $timeline['twp_description'] ) ). '</div>' : ''; ?>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<?php require dirname(__FILE__) .DIRECTORY_SEPARATOR. 'style.php' ; ?>