    <?php
        if(isset($_SESSION["element_ready_quomodo_login_msg"])){
            $errors = map_deep( $_SESSION["element_ready_quomodo_login_msg"], 'sanitize_text_field' ) ;      
        }       
    ?>
    <?php if( isset($errors) && count( $errors) ): ?>
        <ul class="errors">
            <?php foreach($errors as $error): ?>
                <li> <?php echo wp_kses_post($error); ?> </li>
            <?php endforeach; ?>
        </ul>
    <?php 
        unset($_SESSION['element_ready_quomodo_login_msg']); 
        endif; 
    ?>
    <?php if(isset( $_SESSION['element_ready_quomodo_login_success_msg']) ): ?>
        <h2 class="success"><?php echo esc_html( $_SESSION[ 'element_ready_quomodo_login_success_msg' ] ); unset($_SESSION[ 'element_ready_quomodo_login_success_msg' ] ); ?></h2> 
    <?php endif; ?>
    <form action="#" method="POST" class="login form-login element-ready-login-form">
         <?php if($settings['custom_redirect'] == 'yes'): ?>
            <input type="hidden" name="er_redirect" value="<?php echo esc_url($settings['login_redirect_url']); ?>" />
         <?php endif; ?>
        <?php wp_nonce_field('element_ready_quomodo_login_action'); ?>
        <div class="input-box er-username">
              <?php if($settings['custom_lebel'] == 'yes' ): ?>
                <label for="username"> <?php echo esc_html($settings['login_username_label']); ?> </label>
              <?php endif; ?>
             <?php if($settings['custom_fld_icon'] == 'yes'): ?>
                <?php \Elementor\Icons_Manager::render_icon( $settings['login_username_icon'], [ 'aria-hidden' => 'true' ] ); ?>
             <?php endif; ?>
            <input name="username" type="text" placeholder=" <?php echo esc_attr($settings['login_username_placeholder']); ?> " class="input-text">
        </div>
        <div class="input-box er-pass">
            <?php if($settings['custom_lebel'] == 'yes' ): ?>
                <label for="pass"> <?php echo esc_html($settings['login_password_label']); ?> </label>
              <?php endif; ?>
            <?php if($settings['custom_fld_icon'] == 'yes'): ?>
                <?php \Elementor\Icons_Manager::render_icon( $settings['login_password_icon'], [ 'aria-hidden' => 'true' ] ); ?>
             <?php endif; ?>
            <input name="password" type="password" class="input-text" placeholder="<?php echo esc_attr($settings['login_password_placeholder']); ?>">
        </div>
        <?php if($settings['remember_show'] == 'yes'): ?>
        <div class="input-box form-checkbox element-ready-modal-checkbox">
            <label>
                <input name="rememberme" type="checkbox" class="input-checkbox">
                <span>
                    <?php echo wp_kses_post( str_replace(['{','}'],['<span>','</span>'],$settings['remember_text']) ); ?>
                </span>
            </label>
        </div>
        <?php endif; ?>
        <div class="input-btn">
            <button class="main-btn element-ready-user-login-btn" type="submit"> <?php \Elementor\Icons_Manager::render_icon( $settings['signup_submit_icon'], [ 'aria-hidden' => 'true' ] ); ?> <?php echo esc_attr($settings['login_submit_text']); ?></button>
            <?php if( $settings['signup_'] == 'yes' ): ?>
            <a class="main-btn element-ready-user-signup-btn" href="<?php echo esc_url($settings['signup_url']); ?>"><?php echo esc_attr($settings['signup_text']); ?></a>
            <?php endif; ?>
            <?php if($settings['lost_password_show'] == 'yes'): ?>
                <a class="element_ready_modal_lost_password" target="<?php echo esc_attr($settings['lost_password_url']['is_external'] == ''?'_self':'_blank'); ?>" href="<?php echo esc_url($settings['lost_password_url']['url']); ?>"> <?php echo esc_html($settings['lost_password_text']); ?> </a>
            <?php endif; ?>
        </div>
    </form>

