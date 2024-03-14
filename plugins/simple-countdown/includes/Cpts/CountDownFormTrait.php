<?php
namespace GPLSCore\GPLS_PLUGIN_WPSCTR\Cpts;

defined( 'ABSPATH' ) || exit;

/**
 * CountDown Form Related.
 *
 */
trait CountDownFormTrait {

    /**
     * Timer Subscribe Form.
     *
     * @param int $post_id
     * @return void
     */
    private function timer_subscribe_form( $post_id ) {
        $form_status = $this->settings->get_settings( 'subscribe_form_status', $post_id );
        if ( 'on' !== $form_status ) {
            return;
        }

        $custom_form_shortcode = $this->settings->get_settings( 'subscribe_form_shortcode', $post_id );

        if ( ! empty( $custom_form_shortcode ) ) {
            // @codingStandardsIgnoreStart
            echo do_shortcode( $custom_form_shortcode );
            // @codingStandardsIgnoreEnd
        } else {
            $this->subscribe_form( $post_id );
        }
    }

    /**
     * Subscribe Form.
     *
     * @param int $post_id
     * @return void
     */
    private function subscribe_form( $post_id ) {
        ?>
        <div data-id="<?php echo absint( esc_attr( $post_id ) ); ?>" class="<?php echo esc_attr( self::$plugin_info['classes_prefix'] . '-timer-subscribe-form' ); ?>">
            <div class="<?php echo esc_attr( self::$plugin_info['classes_prefix'] . '-timer-subscribe-form-wrapper' ); ?>">
                <?php
                // Form Title.
                $form_title            = $this->settings->get_settings( 'subscribe_form_title', $post_id );
                $form_title_tag        = $this->settings->get_settings( 'subscribe_form_title_tag', $post_id );
                if ( ! empty( $form_title ) ) {
                    ?>
                    <<?php echo esc_attr( $form_title_tag ); ?> class="<?php echo esc_attr( self::$plugin_info['classes_prefix'] . '-form-title' ); ?>"><?php printf( esc_html__( '%s', '%s' ), $form_title, self::$plugin_info['text_domain'] ); ?></<?php echo esc_attr( $form_title_tag ); ?>>
                    <?php
                }
                ?>
                <div class="<?php echo esc_attr( self::$plugin_info['classes_prefix'] . '-inline-wrapper' ); ?>">
                    <!-- Subscribe Email Field -->
                    <input title="<?php echo esc_attr( 'name@domain.com' ); ?>" type="email" class="<?php echo esc_attr( self::$plugin_info['classes_prefix'] . '-subscribe-email-field' ); ?>" pattern="^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)+$" placeholder="<?php esc_attr_e( 'Your email address...', 'simple-countdown' ); ?>">

                    <?php do_action( self::$plugin_info['name'] . '-timer-subscription-form-custom-fields', $post_id ); ?>

                    <!-- Submit Button -->
                    <button class="<?php echo esc_attr( self::$plugin_info['classes_prefix'] . '-subscribe-submit-btn' ); ?>"><?php ! empty( $submit_btn_text ) ? printf( esc_html__( '%s', 'simple-countdown' ), $submit_btn_text ) : esc_html_e( 'Submit', 'simple-countdown' ); ?></button>
                </div>
                <?php
                // Form Consent.
                $custom_consent_text = $this->settings->get_settings( 'subscribe_form_consent', $post_id );
                if ( ! empty( $custom_consent_text ) ) {
                    ?>
                    <div class="<?php echo esc_attr( self::$plugin_info['classes_prefix'] . '-consent-text' ); ?>">
                        <?php echo wp_kses_post( $custom_consent_text ); ?>
                    </div>
                    <?php
                }
                ?>
            </div>
            <?php
            // Form Complete Text.
            $form_complete_text = $this->settings->get_settings( 'subscribe_form_past_subscribe', $post_id );
            if ( ! empty( $form_complete_text ) ) {
                ?>
                <div style="display: none" class="<?php echo esc_attr( self::$plugin_info['classes_prefix'] . '-timer-post-form-submit-text' ); ?>">
                    <?php echo wp_kses_post( $form_complete_text ); ?>
                </div>
                <?php
            }
        ?>
        </div>
        <?php
    }

    /**
     * AJAX Subscribe Form Submit.
     *
     * @return void
     */
    public function ajax_subscribe_form() {
        if ( empty( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], self::$plugin_info['name'] . '-nonce' ) ) {
            self::expired_response();
        }

        $post_id = ! empty( $_POST['post_id'] ) ? absint( sanitize_text_field( wp_unslash( $_POST['post_id'] ) ) ) : 0;

        if ( ! $post_id ) {
            self::invalid_submitted_data_response();
        }

        $post = get_post( $post_id );
        if ( ! is_a( $post, '\WP_Post' ) || ( $this->_get_cpt_key() !== $post->post_type ) ) {
            self::invalid_submitted_data_response();
        }

        $email = ! empty( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';
        if ( ! $email ) {
            self::invalid_submitted_data_response();
        }

        do_action( self::$plugin_info['name'] . '-timer-subscription-form-save', $post_id );

        $this->add_timer_subscription( $post_id, $email );

        wp_send_json_success( array(), 200 );
    }

    /**
     * AJAX Clear Subscriptions
     *
     * @return void
     */
    public function ajax_clear_subscriptions() {
        if ( true || empty( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], self::$plugin_info['name'] . '-admin-nonce' ) ) {
            self::expired_response();
        }

        $post_id = ! empty( $_POST['post_id'] ) ? absint( sanitize_text_field( wp_unslash( $_POST['post_id'] ) ) ) : 0;

        if ( ! $post_id ) {
            self::invalid_submitted_data_response();
        }

        $post = get_post( $post_id );
        if ( ! is_a( $post, '\WP_Post' ) || ( $this->_get_cpt_key() !== $post->post_type ) ) {
            self::invalid_submitted_data_response();
        }

        // $this->clear_timer_subscriptions( $post_id );

        self::ajax_response( 'List has been cleared successfully!' );
    }

    /**
     * Add Timer Form Subscription.
     *
     * @param int $post_id
     * @param string $email
     * @return void
     */
    private function add_timer_subscription( $post_id, $email ) {
        $emails = $this->get_timer_subscriptions( $post_id );
        if ( in_array( $email, $emails ) ) {
            return;
        }
        $emails[] = $email;
        $this->update_timer_subscriptions( $post_id, $emails );
    }

    /**
     * Get Timer Form Subscriptions.
     *
     * @param int $post_id
     * @return array
     */
    public function get_timer_subscriptions( $post_id ) {
        $emails = get_post_meta( $post_id, $this->form_subscriptions_key, true );
        return empty( $emails ) ? array() : $emails;
    }

    /**
     * Update timer Form Subscriptions
     *
     * @param int $post_id
     * @param array $emails
     * @return void
     */
    private function update_timer_subscriptions( $post_id, $emails ) {
        update_post_meta( $post_id, $this->form_subscriptions_key, $emails );
    }

    /**
     * Clear timer Form Subscriptions
     *
     * @param int $post_id
     * @return void
     */
    private function clear_timer_subscriptions( $post_id ) {
        update_post_meta( $post_id, $this->form_subscriptions_key, array() );
    }

}
