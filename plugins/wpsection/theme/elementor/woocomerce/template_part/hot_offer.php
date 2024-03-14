

<?php if (isset($settings['show_hot']) && $settings['show_hot']) { 


                                            if ($product->is_on_sale()) {
                                              $prices = mr_get_product_prices($product);
                                              $returned = mr_product_special_price_calc($prices);
                                              if (isset($returned['percent']) && $returned['percent']) {
                                          ?>
                                         <?php if (!get_post_meta( get_the_id(), 'meta_show_hotsale', true ) ) : ?>    
                                                <div class="mr_hot" style="position:absolute;z-index:9; width:100%">
                                                    <button class="hot_text" style="background:<?php echo wp_kses(get_post_meta(get_the_id(), 'meta_hot_color', true), wp_kses_allowed_html('post')); ?>!important;">
                                                      <?php if ($settings['show_hot_percent']) { ?>
                                                      <?php echo sprintf(esc_html__('%d%% ', 'wpsection'), $returned['percent']); ?>
                                                      <?php } ?>
                                                      <?php if (wp_kses(get_post_meta(get_the_id(), 'meta_hot_text', true), wp_kses_allowed_html('post')) ) {
                                                      echo wp_kses(get_post_meta(get_the_id(), 'meta_hot_text', true), wp_kses_allowed_html('post')); }
                                                       else { echo $settings['hot_text']; } ?>
                                                    </button>
                                                </div>
                                          <?php endif ; ?>

                                          <?php }}}  ?>

