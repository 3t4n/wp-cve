<?php
/**
 * Post Filter block layout default - php render.
 */
    if( $postMargin ) {
        $postClass = 'cvmm-post--imagemargin column--'.esc_html( $blockColumn );
    } else {
        $postClass = 'cvmm-post-no--imagemargin column--'.esc_html( $blockColumn );
    }

    $taxonomies = get_taxonomies( array( 'object_type' => array( $posttype ) ) );
    foreach( $taxonomies as $taxonomy ) {
        $taxonomy_name = $taxonomy;
        break;
    }
    if( isset( $attributes['blockDynamicCss'] ) ) unset( $attributes['blockDynamicCss'] );
?>
<div class="cvmm-title-posts-main-wrapper">
    <div class="cvmm-post-filter-cat-title-wrapper">
        <?php 
            if( !empty( $blockTitle ) ) {
                echo '<h2 class="cvmm-block-title layout--'.esc_html( $blockTitleLayout ).'"><span>'.esc_html( $blockTitle ).'</span></h2>';
            }
        ?>
        <ul class="cvmm-term-titles-wrap">
        <input type="hidden" name="wpmagazine_modules_lite_post_filter_attrs" value="<?php echo esc_html( json_encode( $attributes ) ); ?>" />
            <?php
                $terms = get_terms( array(
                    'taxonomy'  => esc_html( $taxonomy_name ),
                    'include'   => ( is_array( $postCategory ) ) ? array_map( 'absint', $postCategory ) : absint( $postCategory ),
                    'hide_empty' => false,
                ) );
                foreach( $terms as $key => $term ) :
                    if(  $key == 0 ) $term_id = $term->term_id;
            ?>
                    <li id="term-<?php echo esc_attr( $term->term_id ); ?>" class="single-term-title<?php if( $key == 0 ) echo " active"; ?>" data-id="<?php echo esc_attr( $term->term_id ); ?>">
                        <?php echo esc_html( $term->name ); ?>
                    </li>
            <?php
                endforeach;
            ?>
        </ul>
    </div>  
    <div class="cvmm-post-wrapper <?php echo esc_html( $postClass ); ?>">
        <?php
            include( plugin_dir_path( __FILE__ ) . '/template.php' );
        ?>
    </div>
</div><!-- .cvmm-title-posts-main-wrapper -->