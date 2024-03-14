<?php

namespace TenWebPluginIO;

class SignUpView
{
    public $connect_link = '';

    public function __construct()
    {
        $this->connect_link = \TenWebPluginIO\Connect::getConnectionLink();
        wp_enqueue_style('iowd-open-sans', 'https://fonts.googleapis.com/css?family=Open+Sans:300,400,500,600,700,800&display=swap');
        wp_enqueue_style(TENWEBIO_PREFIX . 'connect_css',
            TENWEBIO_URL . '/assets/css/connect.css', array('iowd-open-sans'), TENWEBIO_VERSION);
        echo $this->getConnectView();
    }

    private function getConnectView()
    {
        $subscription_id_from_so = \TenWebWpTransients\OptimizerTransients::get('tenweb_subscription_id');
        $connected_from_io = get_option('tenwebio_was_connected');
        if ($subscription_id_from_so || $connected_from_io) {
            $connect_button_text = __('Connect', 'tenweb-image-optimizer');
        } else {
            $connect_button_text = __('Sign up & connect', 'tenweb-image-optimizer');
        }
        ob_start();
        ?>
        <div class="iowd-container disconnected" dir="ltr">
            <div class="iowd-header">
                <img src="<?php echo TENWEBIO_URL_IMAGES . '/10web_logo.svg'; ?>" alt="10Web" class="iowd-header-img"/>
            </div>
            <div class="iowd-body-container">
                <div class="iowd-body">
                    <div class="iowd-signup-text-part">
                        <div class="iowd-greeting">
                            <img src="<?php echo TENWEBIO_URL_IMAGES . '/waving_hand.png'; ?>" alt="Hey"
                                 class="iowd-waving-hand"/>
                            <?php _e('Hello!', 'tenweb-image-optimizer'); ?>
                        </div>
                        <div class="iowd-plugin-status">
                            <?php _e('Welcome to 10Web Image Optimizer', 'tenweb-image-optimizer'); ?>
                        </div>
                        <div class="iowd-plugin-description">
                            <?php _e('Follow these steps to get started:', 'tenweb-image-optimizer'); ?>
                        </div>
                        <div class="iowd-steps">
                            <div class="iowd-step iowd-step-1">
                                <div class="iowd-step-check">
                                    <div class="iowd-step-check-inner iowd-check"></div>
                                </div>
                                <div class="iowd-step-title">
                                    <?php _e('Step 1', 'tenweb-image-optimizer'); ?>
                                </div>
                                <div class="iowd-step-body">
                                    <div class="iowd-step-header">
                                        <?php _e('Connect your website to 10Web', 'tenweb-image-optimizer'); ?>
                                    </div>
                                    <div class="iowd-step-description">
                                        <?php _e('Sign up and connect your website to 10Web ', 'tenweb-image-optimizer'); ?>
                                        <br>
                                        <?php _e('to enable the 10Web Image optimizer.', 'tenweb-image-optimizer'); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="iowd-step iowd-step-2">
                                <div class="iowd-step-check">
                                    <div class="iowd-step-check-inner iowd-flash"></div>
                                </div>
                                <div class="iowd-step-title">
                                    <?php _e('Step 2', 'tenweb-image-optimizer'); ?>
                                </div>
                                <div class="iowd-step-body">
                                    <div class="iowd-step-header">
                                        <?php _e('Optimize your images', 'tenweb-image-optimizer'); ?>
                                    </div>
                                    <div class="iowd-step-description">
                                        <?php _e('Optimize all website images and media library ', 'tenweb-image-optimizer'); ?>
                                        <br>
                                        <?php _e('automatically.', 'tenweb-image-optimizer'); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="iowd-button-container">
                            <a href="<?php echo esc_url( $this->connect_link ); ?>"
                               class="iowd-button iowd-button-connect" <?php disabled( !$this->connect_link ); ?>>
                                <?php echo esc_html($connect_button_text); ?>
                            </a>
                        </div>
                    </div>
                    <div class="iowd-image-container">
                        <img src="<?php echo TENWEBIO_URL_IMAGES . '/connect_1.png'; ?>" alt="Welcome to 10Web"
                             class="iowd-welcome-image"/>
                    </div>
                </div>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }
}