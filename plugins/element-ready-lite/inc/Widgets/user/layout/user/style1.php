    <!--====== USER LOGIN SIGNUP START ======-->
        <div class="block-account element-ready-block-header element-ready-dropdown">
            <a class="element-ready-user-interface" href="javascript:void(0);" data-gnash="gnash-dropdown">
                <?php \Elementor\Icons_Manager::render_icon( $settings['interface_icon'], [ 'aria-hidden' => 'true' ] ); ?>
                <?php if($settings['interface_text'] !=''): ?>
                    <?php echo esc_html($settings['interface_text']); ?> 
                <?php endif; ?>
            </a>
            <div class="header-account element-ready-submenu">
            <?php if( $settings['modal_template_id'] > 0 && $settings['modal_template_id'] !='' ): ?>
                <?php echo \Elementor\Plugin::instance()->frontend->get_builder_content_for_display( $settings['modal_template_id'] ); ?>
                <?php else: ?>
                <div class="header-user-form-tabs">
                    <ul class="nav nav-tabs d-flex" id="<?php echo esc_attr($widget_id) ?>userTab" role="tablist">
                        <?php if($settings['tab_one_enable'] =='yes'): ?>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link active" id="<?php echo esc_attr($widget_id) ?>login-tab" data-toggle="tab" href="#<?php echo esc_attr($widget_id) ?>login" role="tab" aria-controls="home" aria-selected="true"> <?php echo esc_html($settings['tab_one_text']); ?> </a>
                            </li>
                        <?php endif; ?>
                        <?php if($settings['tab_two_enable'] =='yes'): ?>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" id="<?php echo esc_attr($widget_id) ?>register-tab" data-toggle="tab" href="#<?php echo esc_attr($widget_id) ?>register" role="tab" aria-controls="register" aria-selected="false"><?php echo esc_html($settings['tab_two_text']); ?></a>
                            </li>
                        <?php endif; ?>
                    </ul>
                    <div class="tab-content" id="<?php echo esc_attr($widget_id) ?>Content">
                        <?php if($settings['tab_one_enable'] =='yes'): ?>
                            <div class="tab-pane fade show active" id="<?php echo esc_attr($widget_id) ?>login" role="tabpanel" aria-labelledby="<?php echo esc_attr($widget_id) ?>login-tab">
                                <div id="header-tab-login" class="tab-panel active">
                                    <?php
                                        $errors = [];
                                        if(isset($_SESSION["element_ready_quomodo_login_msg"])){
                                            $errors = map_deep( $_SESSION["element_ready_quomodo_login_msg"], 'sanitize_text_field' ) ;      
                                        }       
                                    ?>
                                    <?php if( count( $errors) ): ?>
                                        <ul class="errors">
                                            <?php foreach($errors as $error): ?>
                                                <li> <?php echo wp_kses_post($error); ?> </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    <?php 
                                        unset($_SESSION["element_ready_quomodo_login_msg"]); 
                                        endif; 
                                    ?>
                                    <?php if(isset( $_SESSION["element_ready_quomodo_login_success_msg"]) ): ?>
                                        <h2 class="success"><?php echo esc_html($_SESSION["element_ready_quomodo_login_success_msg"]); unset($_SESSION["element_ready_quomodo_login_success_msg"]); ?> </h2> 
                                    <?php endif; ?>
                                    <form action="#" method="POST" class="login form-login">
                                        <?php wp_nonce_field('element_ready_quomodo_login_action'); ?>
                                        <p class="form-row form-row-wide">
                                            <input name="username" type="text" placeholder=" <?php echo esc_attr($settings['login_username_placeholder']); ?>" class="input-text">
                                        </p>
                                        <p class="form-row form-row-wide">
                                            <input name="password" type="password" class="input-text" placeholder="<?php echo esc_attr($settings['login_password_placeholder']); ?>">
                                        </p>
                                        <p class="form-row">
                                            <?php if($settings['remember_show'] =='yes'): ?>
                                                <label class="form-checkbox">
                                                    <input name="rememberme" type="checkbox" class="input-checkbox">
                                                    <span>
                                                        <?php echo esc_html($settings['remember_text']); ?>
                                                    </span>
                                                </label>
                                            <?php endif; ?>
                                            <input type="submit" name="submit" class="button element-ready-user-login-btn" value="<?php echo esc_attr($settings['login_submit_text']); ?>">
                                        </p>
                                       <?php if($settings['lost_password_show'] == 'yes'): ?>
                                            <p class="element_ready_lost_password">
                                                <a target="<?php echo esc_attr($settings['lost_password_url']['is_external'] == ''?'_self':'_blank'); ?>" href="<?php echo esc_url($settings['lost_password_url']['url']); ?>"> <?php echo esc_html($settings['lost_password_text']); ?> </a>
                                            </p>
                                        <?php endif; ?>
                                    </form>
                                </div>
                            </div>
                        <?php endif; ?>
                        <?php if($settings['tab_two_enable'] =='yes'): ?>
                            <div class="tab-pane fade" id="<?php echo esc_attr($widget_id) ?>register" role="tabpanel" aria-labelledby="<?php echo esc_attr($widget_id) ?>register-tab">
                                <div id="header-tab-rigister" class="tab-panel">
                                    <form method="post" class="register form-register">
                                         <?php if($settings['signup_show_name'] =='yes'): ?>
                                            <p class="form-row form-row-wide">
                                                <input type="text" name="name" placeholder="<?php echo esc_attr($settings['signup_name_placeholder']); ?>" class="input-text">
                                            </p>
                                         <?php else: ?>
                                            <input type="hidden" name="name" value="no name">
                                         <?php endif; ?>
                                        <p class="form-row form-row-wide">
                                            <input type="text" name="username" required placeholder="<?php echo esc_attr($settings['signup_username_placeholder']); ?>" class="input-text">
                                        </p>
                                        <p class="form-row form-row-wide">
                                            <input type="email" name="email" required placeholder="<?php echo esc_attr($settings['signup_email_placeholder']); ?>" class="input-text">
                                        </p>
                                        <p class="form-row form-row-wide">
                                            <input type="password" name="password" required class="input-text" placeholder="<?php echo esc_attr($settings['signup_password_placeholder']); ?>">
                                        </p>
                                        <input type="hidden" name="element_ready_quomodo_registration_form" />
                                        <?php wp_nonce_field('element_ready_quomodo_registration_action'); ?>
                                        <p class="form-row">
                                            <input type="submit" class="button element-ready-user-signup-btn" value="<?php echo esc_attr($settings['signup_submit_text']); ?>">
                                        </p>
                                    </form>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    <!--====== USER LOGIN SIGNUP END ======-->