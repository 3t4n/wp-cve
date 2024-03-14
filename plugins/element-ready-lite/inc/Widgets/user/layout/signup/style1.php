<?php
    if ( isset( $_SESSION[ 'element_ready_quomodo_reg_msg' ] ) ) {
        $errors = map_deep($_SESSION["element_ready_quomodo_reg_msg"], 'sanitize_text_field');     
    } else {
        if ( isset( $_SESSION[ 'element_ready_quomodo_reg_msg_success' ] ) ) {
            echo wp_kses_post( sprintf('<h3 class="success">%s</h3>', esc_html( $_SESSION[ 'element_ready_quomodo_reg_msg_success' ] )) ); 
        }
    }
?>
<?php if( isset($errors) && count( $errors) ): ?>
    <ul class="errors">
        <?php foreach( $errors as $error ): ?>
            <li><?php echo wp_kses_post( $error ); ?></li>
        <?php endforeach; ?>
    </ul>
<?php 
    unset($_SESSION['element_ready_quomodo_reg_msg']); 
    unset($_SESSION['element_ready_quomodo_reg_msg_success']); 
endif; 
?>
<form method="post" class="register form-register element-ready-register-form">
        <?php if ( $settings['custom_redirect'] == 'yes' ) : ?>
            <input type="hidden" name="er_redirect" value="<?php echo esc_url($settings['login_redirect_url']); ?>" />
         <?php endif; ?>                                      
    <?php if ( $settings['signup_show_name'] =='yes' ) : ?>
        <div class="input-box er-name">
            <?php if($settings['custom_lebel'] == 'yes' ): ?>
                <label for="username"> <?php echo esc_html($settings['signup_name_label']); ?> </label>
            <?php endif; ?>
            <?php \Elementor\Icons_Manager::render_icon( $settings['signup_name_icon'], [ 'aria-hidden' => 'true' ] ); ?>
            <input type="text" required name="name" placeholder="<?php echo esc_attr($settings['signup_name_placeholder']); ?>" class="input-text">
        </div>
    <?php else: ?>
        <input type="hidden" name="name" value="<?php echo esc_attr__('no name','element-ready-lite'); ?>">
    <?php endif; ?>
    
    <div class="input-box er-username">
            <?php if ( $settings['custom_lebel'] == 'yes' ) : ?>
                <label for="username"><?php echo esc_html($settings['signup_username_label']); ?> </label>
            <?php endif; ?>
            <?php \Elementor\Icons_Manager::render_icon( $settings['signup_username_icon'], [ 'aria-hidden' => 'true' ] ); ?>
        <input type="text" name="username" required placeholder="<?php echo esc_attr($settings['signup_username_placeholder']); ?>" class="input-text">
    </div>

    <div class="input-box er-email">
            <?php if ( $settings['custom_lebel'] == 'yes' ) : ?>
                <label for="username"><?php echo esc_html($settings['signup_email_label']); ?></label>
            <?php endif; ?>
            <?php \Elementor\Icons_Manager::render_icon( $settings['signup_email_icon'], [ 'aria-hidden' => 'true' ] ); ?>
        <input type="email" name="email" required placeholder="<?php echo esc_attr($settings['signup_email_placeholder']); ?>" class="input-text">
    </div>
    <div class="input-box er-pass">
            <?php if ( $settings['custom_lebel'] == 'yes' ) : ?>
                <label for="username"><?php echo esc_html($settings['signup_password_label']); ?> </label>
            <?php endif; ?>
            <?php \Elementor\Icons_Manager::render_icon( $settings['signup_password_icon'], [ 'aria-hidden' => 'true' ] ); ?>
        <input type="password" name="password" required class="input-text" placeholder="<?php echo esc_attr($settings['signup_password_placeholder']); ?>">
    </div>

    <?php if ($settings[ 'checkbox_show' ] == 'yes' ) : ?>
        <div class="input-box form-checkbox element-ready-modal-checkbox er-terms">
            <label>
                <input name="terms" type="checkbox" class="input-checkbox">
                <span>
                    <?php echo wp_kses_post( str_replace( ['{','}'],['<span>','</span>'], $settings[ 'term_text' ] ) ); ?>
                </span>
            </label>
        </div>
    <?php endif; ?>
    <input type="hidden" name="element_ready_quomodo_registration_form" />
    <?php wp_nonce_field('element_ready_quomodo_registration_action'); ?>
    <div class="input-box">
        <button class="main-btn element-ready-user-signup-btn"> 
            <?php echo esc_attr($settings['signup_submit_text']); ?>
            <?php \Elementor\Icons_Manager::render_icon( $settings['signup_submit_icon'], [ 'aria-hidden' => 'true' ] ); ?>
         </button>
    </div>
</form>