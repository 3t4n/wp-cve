 <?php
    $tile_skin_scheme =  ! isset( $tile_skin ) || 'darken' === $tile_skin  ? 'aux-white' : 'aux-black';
    $tile_skin_hover  =  ! isset( $tile_skin ) || 'darken' === $tile_skin  ? 'aux-black' : 'aux-white';
    
    // validate the boolean variables
    $show_title = auxin_is_true( $display_title );
    $show_info  = auxin_is_true( $show_info );
    $display_read_more = ! empty( $display_read_more ) ? auxin_is_true( $display_read_more ) : false ;
?>
                        <article <?php post_class( 'aux-item-overlay' . ' ' . $hover_classes . '  ' . $item_inner_classes ); ?> >
                                <div class="entry-media <?php echo esc_attr( $frame_effect_classes . ' ' . $item_inner_classes ); ?>">
                                    <?php echo $the_media; ?>
                                </div>

                                <div class="aux-overlay-content">
                                    <?php if( $show_lightbox ) { ?>
                                    <div class="aux-portfolio-overlay-buttons">
                                        <div class="aux-hover-circle-plus aux-delay-2x">
                                            <a href="<?php echo auxin_get_the_attachment_url( $post->ID, 'full' )?>" <?php echo $lightbox_attrs; ?> class="aux-lightbox-btn " >
                                                <div class="aux-arrow-nav aux-round aux-hover-slide aux-outline aux-semi-small <?php echo esc_attr( $tile_skin_scheme );?>">
                                                    <span class="aux-overlay"></span>
                                                    <span class="aux-svg-arrow aux-medium-plus <?php echo esc_attr( $tile_skin_scheme );?>"></span>
                                                    <span class="aux-hover-arrow aux-svg-arrow aux-medium-plus <?php echo esc_attr( $tile_skin_hover );?>"></span>
                                                </div>
                                            </a>
                                        </div>
                                        <div class="aux-arrow-post-link aux-hover-circle-link">
                                            <a href="<?php echo !empty( $the_link ) ? $the_link : get_permalink(); ?>">
                                                <div class="aux-arrow-nav aux-round aux-hover-slide aux-outline aux-semi-small <?php echo esc_attr( $tile_skin_scheme );?>">
                                                    <span class="aux-overlay"></span>
                                                    <span class="aux-svg-arrow aux-medium-right <?php echo esc_attr( $tile_skin_scheme );?>"></span>
                                                    <span class="aux-hover-arrow aux-svg-arrow aux-medium-right <?php echo esc_attr( $tile_skin_hover );?>"></span>
                                                </div>
                                            </a>
                                        </div>

                                    </div>
                                    <?php } ?>
                                    <?php if( $show_title || $show_info || $display_read_more ) { ?>
                                    <div class="entry-main">

                                    <?php if( $show_title ) { ?>
                                    <header class="entry-header">
                                        <h3 class="entry-title aux-hover-move-up">
                                            <a href="<?php echo !empty( $the_link ) ? $the_link : get_permalink(); ?>">
                                                <?php echo !empty( $the_name ) ? $the_name : get_the_title(); ?>
                                            </a>
                                        </h3>
                                    </header>
                                    <?php } ?>
                                    <?php if( $show_info ) { ?>
                                    <div class="entry-info aux-hover-move-up aux-delay-1x">
                                        <span class="entry-tax">
                                            <?php // the_category(' '); we can use this template tag, but customizable way is needed! ?>
                                            <?php $tax_name = 'portfolio-cat';
                                                if( $cat_terms = wp_get_post_terms( $post->ID, $tax_name ) ){
                                                    foreach( $cat_terms as $term ){
                                                        echo '<a href="'. get_term_link( $term->slug, $tax_name ) .'" title="'.__("View all posts in ", 'auxin-portfolio'). $term->name .'" rel="category" >'. $term->name .'</a>';
                                                    }
                                                }
                                            ?>
                                        </span>
                                    </div>
                                    <?php } ?>
                                    <?php if( $display_read_more ) { ?>
                                    <div class="entry-read-more aux-hover-move-up aux-delay-1x">
                                        <span class="aux-read-more">
                                            <a href="<?php echo get_permalink( $post->ID );?>"><?php echo esc_html__( 'Read More', 'auxin-portfolio' );?></a>                                            
                                        </span>
                                    </div>
                                    <?php } ?>
                                    </div>
                                    <?php } ?>
                            </div>
                        </article>
