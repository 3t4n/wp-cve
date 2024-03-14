<?php

namespace TenWebPluginIO;

class OnBoarding
{

    public $step;

    public function __construct()
    {
        $this->step = get_option('iowd_onboarding_step', false);
        $this->enqueueScripts();
        echo $this->display();
    }

    /**
     * @return void
     */
    private function enqueueScripts()
    {
        wp_enqueue_script(TENWEBIO_PREFIX . '_onboarding', TENWEBIO_URL . '/onBoarding/assets/script.js', array('jquery'), TENWEBIO_VERSION);
        wp_localize_script(TENWEBIO_PREFIX . '_onboarding', 'onboarding', array(
            'proceed'              => __('Proceed', 'tenweb-image-optimizer'),
            'install'              => __('Install & activate', 'tenweb-image-optimizer'),
            'optimize'             => __('Install & optimize', 'tenweb-image-optimizer'),
            'something_wrong'      => __('Something went wrong, please try again.', 'tenweb-image-optimizer'),
            'dashboard_page'       => add_query_arg(array('page' => TENWEBIO_PREFIX . '_dashboard'), admin_url('admin.php')),
            'onboarding_page'      => add_query_arg(array('page' => 'onboarding_iowd'), admin_url('admin.php')),
            'iowd_nonce'           => wp_create_nonce('iowd_nonce'),
            'speed_ajax_nonce'     => wp_create_nonce('speed_ajax_nonce'),
            'booster_connect_page' => add_query_arg(['page' => 'two_settings_page'], get_admin_url() . 'admin.php'),
        ));

        wp_enqueue_style('iowd-open-sans', 'https://fonts.googleapis.com/css?family=Open+Sans:300,400,500,600,700,800&display=swap');
        wp_enqueue_style(TENWEBIO_PREFIX . '_onboarding', TENWEBIO_URL . '/onBoarding/assets/style.css', array('iowd-open-sans'), TENWEBIO_VERSION);

    }

    /* Display steps views */
    public function display()
    {
        ob_start();
        if (isset($_GET['locked_features'])) {
            ?>
            <div class="iowd_onboarding_container">
                <?php
                $this->onBoardingHeader('iowd_signup_header');
                ?>
                <div class="iowd_onboarding_content iowd_locked_features">
                    <?php
                    echo $this->lockedFeaturesView();
                    ?>
                </div>
            </div>
            <?php
        } else {
            ?>
            <div class="iowd_onboarding_container">
                <?php
                if ($this->step == "sign_up_booster") {
                    $this->onBoardingHeader('iowd_signup_header');
                } else {
                    $this->onBoardingHeader();
                }
                ?>
                <div class="iowd_onboarding_content">
                    <?php
                    if ($this->step == "sign_up_booster") {
                        echo $this->signUpBoosterView();
                    } else {
                        echo $this->welcomeView();
                    }
                    ?>
                </div>
            </div>
            <?php
        }

        return ob_get_clean();
    }

    private function lockedFeaturesView()
    {
        ?>
        <div class="iowd_onboarding_signup_left">
            <div class="iowd_onboarding_signup_left_inner">
                <h2><?php _e('Activate 10Web Booster', 'tenweb-image-optimizer'); ?></h2>
                <p class="iowd_onboarding_welcome_descr"><?php _e('Get access to all the features.', 'tenweb-image-optimizer'); ?></p>
                <div class="iowd_onboarding_signup_steps">
                    <span><?php _e('Optimize your website and get a 90+ PageSpeed score.', 'tenweb-image-optimizer'); ?></span>
                    <span><?php _e('Improve your website’s core web vitals.', 'tenweb-image-optimizer'); ?></span>
                    <span><?php _e('Enable CDN to get an incredibly fast and secure website for higher<br> rankings and conversions.', 'tenweb-image-optimizer'); ?></span>
                    <span><?php _e('Get full website caching.', 'tenweb-image-optimizer'); ?></span>
                </div>
                <a class="iowd_onboarding_button iowd_locked_features_signup_button"><?php _e('Install & optimize', 'tenweb-image-optimizer'); ?></a>
            </div>
        </div>
        <div class="iowd_onboarding_signup_right">
            <div class="img_div"></div>
        </div>
        <?php
    }

    /* Welcome view */
    public function welcomeView()
    {
        ?>
        <div class="iowd_onboarding_welcome_left">
            <h2><?php _e('Welcome to 10Web platform', 'tenweb-image-optimizer'); ?></h2>
            <p class="iowd_onboarding_welcome_descr"><?php _e('Image optimizer is a small part of our platform. Install 10Web Booster,<br> and you can do much more for your entire website.', 'tenweb-image-optimizer'); ?></p>
            <div class="iowd_onboarding_welcome_steps">
                <span><?php _e('Optimize all your images and frontend to improve your Google rankings.', 'tenweb-image-optimizer'); ?></span>
                <span><?php _e('Activate 10Web Booster and get a 90+ PageSpeed score.', 'tenweb-image-optimizer'); ?></span>
                <span><?php _e('Improve your website’s core web vitals.', 'tenweb-image-optimizer'); ?></span>
                <span><?php _e('Get full website caching.', 'tenweb-image-optimizer'); ?></span>
            </div>
            <a class="iowd_onboarding_button iowd_onboarding_signup_button"
               data-onboarding_step="sign_up_booster"><?php _e('Install & activate', 'tenweb-image-optimizer'); ?></a>
            <div class="iowd_onboarding_install_footer_info">
                <p><?php _e('We will install the 10Web Booster plugin from the WordPress.org repository.', 'tenweb-image-optimizer'); ?></p>
            </div>
        </div>
        <div class="iowd_onboarding_welcome_right">
            <span class="skip_to_IO iowd_onboarding_step_change"
                  data-onboarding_step="skipped"><?php _e('Skip to Image Optimizer', 'tenweb-image-optimizer'); ?></span>

        </div>
        <?php
    }

    /* Sign up Booster view */
    public function signUpBoosterView()
    {
        ?>
        <div class="iowd_onboarding_signup_left">
            <h2><?php _e('Your website optimization starts from your homepage', 'tenweb-image-optimizer'); ?></h2>
            <div class="iowd_onboarding_signup_steps">
                <span><?php _e('Optimize all the images on your homepage.', 'tenweb-image-optimizer'); ?></span>
                <span><?php _e('Your homepage will get a 90+ Page Speed score.', 'tenweb-image-optimizer'); ?></span>
                <span><?php _e('Add and optimize more images and pages from your 10Web dashboard.', 'tenweb-image-optimizer'); ?></span>
            </div>
            <div class="iowd_onboarding_signup_homeurl_info">
                <p><?php echo esc_url(get_home_url()); ?></p>
            </div>
            <a class="iowd_onboarding_button iowd_onboarding_step_change"
               data-onboarding_step="done"><?php _e('Sign up & optimize', 'tenweb-image-optimizer'); ?></a>
        </div>
        <div class="iowd_onboarding_signup_right">
            <img alt="Signup Banner" src="<?php echo TENWEBIO_URL . '/onBoarding/assets/images/signup_banner.png'; ?>">
        </div>
        <?php
    }

    /* Header part of onBoarding */
    public function onBoardingHeader($signUp = false)
    {
        ?>
        <div class="iowd_onboarding_header <?php echo $signUp ? esc_attr($signUp) : ''; ?>">
            <div class="iowd_onboarding_header_logo_cont">
                <?php if ($signUp) { ?>
                    <img alt="10Web logo" src="<?php echo TENWEBIO_URL . '/assets/img/10web_logo.svg'; ?>">
                <?php } else { ?>
                    <img alt="Image Optimizer logo" src="<?php echo TENWEBIO_URL . '/assets/img/logo_icon.svg'; ?>">
                    <span><?php esc_html_e('10Web Image Optimizer', 'tenweb-image-optimizer'); ?></span>
                <?php } ?>
            </div>
            <a href="<?php echo esc_url(admin_url('admin.php?page=' . TENWEBIO_PREFIX . '_dashboard')); ?>"
               class="iowd_onboarding_close"></a>
        </div>
        <?php
    }
}

?>