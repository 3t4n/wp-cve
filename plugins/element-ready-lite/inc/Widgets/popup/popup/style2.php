            <!--====== USER LOGIN SIGNUP start ======-->
            <div class="element-ready-block-header">
                <a class="element-ready-user-interface" data-toggle="modal" data-target="#<?php echo esc_attr($widget_id) ?>-dialog-modal" href="javascript:void(0);">
                    <?php \Elementor\Icons_Manager::render_icon( $settings['interface_icon'], [ 'aria-hidden' => 'true' ] ); ?>
                    <?php if($settings['interface_text'] !=''): ?>
                        <?php echo esc_html($settings['interface_text']); ?> 
                    <?php endif; ?>
                </a>
            </div>
            <!-- Modal -->
            <div class="modal fade " id="<?php echo esc_attr($widget_id) ?>-dialog-modal" tabindex="-1" aria-labelledby="element-ready-user-dialog-modal" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content element-ready-user-modal-content">
                        <?php if( $settings['modal_template_id'] > 0 && $settings['modal_template_id'] !='' ): ?>
                            <?php echo \Elementor\Plugin::instance()->frontend->get_builder_content_for_display( $settings['modal_template_id'] ); ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        
        <!--====== USER LOGIN SIGNUP END ======-->