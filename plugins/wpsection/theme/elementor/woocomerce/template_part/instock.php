


<?php if (isset($settings['show_instock']) && $settings['show_instock']) {  ?>


                                    <?php if (!get_post_meta( get_the_id(), 'meta_show_instock', true ) ) : ?>  

                                          <div class="wps_order order-<?php echo $settings['position_order_five']; ?> ">     
                                          <div class="wps_instock">
                                            <button class="wps_instock_text"> 
                                            <?php
                                               if ($stock_quantity > 0) { ?>
                                                <i class=" <?php echo str_replace("icon ", " ", esc_attr( $settings['instock_icon']['value']));?>"></i> 
                                              <?php
                                                  echo $settings['instock_text'] .$sale_stock_quantity;
                                                } else { ?>
                                                <span class="wps_out_stock_icont"><i class=" <?php echo str_replace("icon ", " ", esc_attr( $settings['outstock_icon']['value']));?>"></i> 
                                                  <?php   echo $settings['instock_text_not'];  ?>
                                                </span> 
                                               <?php }
                                            ?>
                                            </button>
                                          </div>
                                        </div>
                                    <?php endif; ?>                                         
                                    <?php } ?>
                          