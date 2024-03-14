<?php defined( 'ABSPATH' ) || exit; ?>
<div class="tochatbe-widget">

    <div class="tochatbe-widget__body" data-tochatbe-popup-status="0">
        <div class="tochatbe-widget__body-header">
            <span class="tochatbe-icon-close tochatbe-widget-close" data-tochatbe-trigger>
                <?php require TOCHATBE_PLUGIN_PATH . 'assets/icons/close.svg'; ?>
            </span>
            <?php
                $about_message = tochatbe_appearance_option( 'about_message' );
                $about_message = apply_filters( 'tochatbe_about_message', $about_message );
                
                echo esc_textarea( $about_message ); 
            ?>
        </div>
        <?php tochatbe_gdpr_check(); ?>
        <div class="tochatbe-support-persons">
            <?php if ( tochatbe_get_agents() ) : ?>
                <?php foreach ( tochatbe_get_agents() as $agent ) : ?>
                    <?php if ( 'group' === $agent->get_type() ) : ?>
                        <a href="https://chat.whatsapp.com/<?php echo esc_html( $agent->get_group_id() ); ?>" class="tochatbe-support-person" target="_blank">
                            <div class="tochatbe-support-person__img">
                                <img src="<?php echo esc_url( $agent->get_image() ); ?>" alt="//">
                            </div>
                            <div class="tochatbe-support-person__meta">
                                <div class="tochatbe-support-person__name"><?php echo esc_html( $agent->get_name() ); ?></div>
                                <div class="tochatbe-support-person__title"><?php echo esc_html( $agent->get_title() ); ?></div>
                            </div>
                        </a>
                    <?php else : ?>
                        <?php
                            $agent_pre_message = str_replace( 
                                '{{url}}', 
                                tochatbe_get_current_url(), 
                                $agent->get_pre_defined_message() 
                            );
                        ?>
                        <div 
                            class="tochatbe-support-person" 
                            data-tochatbe-person 
                            data-tochatbe-number="<?php echo esc_attr( $agent->get_number() ); ?>" 
                            data-tochatbe-message="<?php echo esc_attr( $agent_pre_message ); ?>">
                            <div class="tochatbe-support-person__img">
                                <img src="<?php echo esc_url( $agent->get_image() ); ?>" alt="//">
                            </div>
                            <div class="tochatbe-support-person__meta">
                                <div class="tochatbe-support-person__name"><?php echo esc_html( $agent->get_name() ); ?></div>
                                <div class="tochatbe-support-person__title"><?php echo esc_html( $agent->get_title() ); ?></div>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
                <?php else : ?>
                    <?php
                        if ( current_user_can( 'manage_options' ) ) {
                            echo sprintf( 'Click <a href="%s">here</a> to add new agent.', admin_url( 'post-new.php?post_type=tochatbe_agent' ) );
                        }
                    ?>
            <?php endif; ?>
            <?php if ( $custom_offer = tochatbe_appearance_option( 'custom_offer' ) ) : ?>
                <div class="tochatbe-custom-offer">
                    <?php echo wp_kses_post( $custom_offer ); ?>
                </div>
            <?php endif; ?>
        </div>
        <?php if ( 'yes' === tochatbe_type_and_chat_option( 'type_and_chat' ) ) : ?>
            <div class="tochatbe-input-wrapper">
                <span class="tochatbe-blinking-cursor">|</span>
                <input type="text" id="tochatbe-type-and-chat-input" placeholder="<?php echo esc_attr( tochatbe_type_and_chat_option( 'type_and_chat_placeholder' ) ); ?>">
                <div class="tochatbe-input-icon">
                    <img src="<?php echo TOCHATBE_PLUGIN_URL . 'assets/images/whatsapp-icon.svg'; ?>" width="30" alt="//">
                </div>
            </div>
        <?php endif; ?>
    </div>

    <div class="tochatbe-widget__trigger" data-tochatbe-trigger>
        <span class="tochatbe-icon-whatsapp">
            <?php require TOCHATBE_PLUGIN_PATH . 'assets/icons/whatsapp.svg'; ?>
        </span>
        <?php echo esc_html( tochatbe_appearance_option( 'trigger_btn_text' ) ); ?>
    </div>

</div>