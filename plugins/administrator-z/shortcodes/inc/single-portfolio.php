<?php 
$link = get_permalink(get_the_ID());
$has_lightbox = '';
if($lightbox == 'true'){
    $link = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), $lightbox_image_size );
    $link = $link[0];
    $has_lightbox = 'lightbox-gallery';
}
$image = get_post_thumbnail_id();
$classes_col = array('col');
// Add Columns for Grid style
if($type == 'grid'){
    if($grid_total > $current_grid) $current_grid++;
    $current = $current_grid-1;
    $classes_col[] = 'grid-col';
    if($grid[$current]['height']) $classes_col[] = 'grid-col-'.$grid[$current]['height'];
    if($grid[$current]['span']) $classes_col[] = 'large-'.$grid[$current]['span'];
    if($grid[$current]['md']) $classes_col[] = 'medium-'.$grid[$current]['md'];
    // Set image size
    if($grid[$current]['size']) $image_size = $grid[$current]['size']; }
?>
<?php         
$data_term = strip_tags( get_the_term_list( get_the_ID(), 'featured_item_category', "[&quot;", "&quot;,&quot;", "&quot;]" ) );
?>
<div class="<?php echo esc_attr(implode(' ', $classes_col)); ?>" data-terms="<?php echo esc_attr($data_term); ?>" <?php echo esc_attr($animate); ?>>
    <div class="col-inner" <?php echo get_shortcode_inline_css($css_col); ?>>
        <a href="<?php echo esc_attr($link); ?>" class="plain <?php echo esc_attr($has_lightbox); ?>">
            <div class="<?php echo esc_attr(implode(' ', $classes_box)); ?>">
                <div class="box-image" <?php echo get_shortcode_inline_css( $css_args_img ); ?>>
                    <div class="<?php echo esc_attr(implode(' ', $classes_image)); ?>" <?php echo get_shortcode_inline_css($css_image_height); ?>>
                    <?php echo wp_get_attachment_image($image, $image_size); ?>
                    <?php if($image_overlay) { ?>
                        <div class="overlay" style="background-color:<?php echo esc_attr($image_overlay); ?>"></div>
                    <?php } ?>
                    <?php if($style == 'shade'){ ?>
                        <div class="shade"></div>
                    <?php } ?>
                    </div>
                </div>
                <div class="<?php echo esc_attr(implode(' ', $classes_text)); ?>" <?php echo get_shortcode_inline_css( $css_args ); ?>>
                    <div class="box-text-inner">             
                        <h6 class="uppercase portfolio-box-title"><?php the_title(); ?></h6>
                        <p class="uppercase portfolio-box-category is-xsmall op-6">
                            <span class="show-on-hover">
                                <?php  
                                    $after_title_text = strip_tags( get_the_term_list( get_the_ID(), 'featured_item_category', "",", " ) );
                                    echo esc_attr($after_title_text);
                                ?>
                            </span>
                        </p>
                    </div>
                </div>
            </div>
        </a>
    </div>
</div>