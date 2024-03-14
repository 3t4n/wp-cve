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
            <div class="modal fade" id="<?php echo esc_attr($widget_id) ?>-dialog-modal" tabindex="-1" aria-labelledby="element-ready-user-dialog-modal" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content element-ready-user-modal-content">
                        <?php if( $settings['modal_template_id'] > 0 && $settings['modal_template_id'] !='' ): ?>
                            <?php echo \Elementor\Plugin::instance()->frontend->get_builder_content_for_display( $settings['modal_template_id'] ); ?>
                        <?php else: ?>
                            <div class="modal-header">
                                <?php if($settings['modal_heading_text'] !=''): ?>
                                    <div class="logo">
                                        <h4 class="title"> <?php echo esc_html($settings['modal_heading_text']); ?> </h4>
                                    </div>
                                <?php endif; ?>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                <?php if($settings['tab_one_enable'] =='yes'): ?>
                                    <div class="col-lg-<?php echo esc_attr($settings['login_column']); ?>">
                                        <?php if($settings['tab_one_text'] !=''): ?>
                                            <div class="section-title mt-30">
                                                <h3 class="title"><?php echo esc_html($settings['tab_one_text']); ?></h3>
                                            </div>
                                        <?php endif; ?>
                                        <?php
                                          $errors = [];
                                            if(isset($_SESSION["element_ready_quomodo_login_msg"])){
                                                $errors = map_deep($_SESSION[ "element_ready_quomodo_reg_msg" ], 'sanitize_text_field');      
                                            }       
                                        ?>
                                        <?php if( count( $errors) ): ?>
                                            <ul class="errors">
                                               <?php foreach($errors as $error): ?>
                                                    <li><?php echo wp_kses_post($error); ?></li>
                                                <?php endforeach; ?>
                                            </ul>
                                       <?php 
                                            unset($_SESSION["element_ready_quomodo_login_msg"]); 
                                            endif; 
                                        ?>

                                        <?php if(isset( $_SESSION["element_ready_quomodo_login_success_msg"]) ): ?>
                                            <h2 class="success"><?php echo esc_html($_SESSION["element_ready_quomodo_login_success_msg"]); unset($_SESSION["element_ready_quomodo_login_success_msg"]); ?></h2> 
                                        <?php endif; ?>
                                        <form action="#" method="POST" class="login form-login">
                                            <?php wp_nonce_field('element_ready_quomodo_login_action'); ?>
                                            <div class="input-box">
                                                <input name="username" type="text" placeholder=" <?php echo esc_attr($settings['login_username_placeholder']); ?> " class="input-text">
                                            </div>
                                            <div class="input-box">
                                                <input name="password" type="password" class="input-text" placeholder="<?php echo esc_attr($settings['login_password_placeholder']); ?>">
                                            </div>
                                            <div class="input-box form-checkbox element-ready-modal-checkbox">
                                                <label>
                                                    <input name="rememberme" type="checkbox" class="input-checkbox">
                                                    <span>
                                                        <?php echo esc_html($settings['remember_text']); ?>
                                                    </span>
                                                </label>
                                            </div>
                                            <div class="input-btn">
                                                <button class="main-btn element-ready-user-login-btn" type="submit"><?php echo esc_attr($settings['login_submit_text']); ?></button>
                                                <?php if($settings['lost_password_show'] == 'yes'): ?>
                                                    <a class="element_ready_modal_lost_password" target="<?php echo esc_attr($settings['lost_password_url']['is_external'] == ''?'_self':'_blank'); ?>" href="<?php echo esc_url($settings['lost_password_url']['url']); ?>"> <?php echo esc_html($settings['lost_password_text']); ?></a>
                                                <?php endif; ?>
                                            </div>
                                        </form>
                                    </div>
                                    <?php endif; ?>
                                    <?php if($settings['tab_two_enable'] =='yes'): ?>
                                    <div class="col-lg-<?php echo esc_attr($settings['signup_column']); ?>">
                                        <?php if($settings['tab_two_text'] !=''): ?>
                                            <div class="section-title mt-30">
                                                <h3 class="title"><?php echo esc_html($settings['tab_two_text']); ?></h3>
                                            </div>
                                        <?php endif; ?>
                                        <form method="post" class="register form-register">
                                            <?php if($settings['signup_show_name'] =='yes'): ?>
                                                <div class="input-box">
                                                    <input type="text" required name="name" placeholder="<?php echo esc_attr( $settings[ 'signup_name_placeholder' ] ); ?>" class="input-text">
                                                </div>
                                            <?php else: ?>
                                                <input type="hidden" name="name" value="no name">
                                            <?php endif; ?>
                                            <div class="input-box">
                                                <input type="text" name="username" required placeholder="<?php echo esc_attr( $settings[ 'signup_username_placeholder' ] ); ?>" class="input-text">
                                            </div>
                                            <div class="input-box">
                                                <input type="email" name="email" required placeholder="<?php echo esc_attr( $settings[ 'signup_email_placeholder' ] ); ?>" class="input-text">
                                            </div>
                                            <div class="input-box">
                                                <input type="password" name="password" required class="input-text" placeholder="<?php echo esc_attr( $settings[ 'signup_password_placeholder' ] ); ?>">
                                            </div>
                                            <input type="hidden" name="element_ready_quomodo_registration_form" />
                                            <?php wp_nonce_field('element_ready_quomodo_registration_action'); ?>
                                            <div class="input-box">
                                                <button class="main-btn element-ready-user-signup-btn"><?php echo esc_attr( $settings[ 'signup_submit_text' ] ); ?></button>
                                            <?php echo wp_kses_post( wpautop( $settings['modal_footer_text'] ) ); ?>
                                            </div>
                                        
                                        </form>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        
        <!--====== USER LOGIN SIGNUP END ======-->