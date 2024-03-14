<?php

class pageViewMenu {
    public function __construct() {
        add_action( 'admin_menu', array($this, 'page_view_menu') );
        add_action('admin_notices', array($this,'pvbw_admin_notice_callback'));
    }
    
    function page_view_menu() {
        
        global $pageView;
        
        add_menu_page( 'User View Counts', 'Page Views', 'manage_options', 'page-views', array($pageView, 'loadPageViewData'),'dashicons-visibility');
    }

    // Function to call admin notices
    function pvbw_admin_notice_callback()
    {
        $current_screen = get_current_screen();
        $activation_date = get_option('pvbw_activation_date');
        $days_since_activation = $activation_date ? floor((time() - $activation_date) / (60 * 60 * 24)) : 0;

        //Admin notice for customize
        if (!(isset($_COOKIE['pvbw_rating_remind_later']) && $_COOKIE['pvbw_rating_remind_later'] === 'true')) {
            if ($days_since_activation > 15) {
				if (!(isset($_COOKIE['pvbw_dismissed']) && $_COOKIE['pvbw_dismissed'] === 'true')) {
                    $notification_template = '<div class="%1$s"><p><strong>%2$s</strong></p><p>%3$s</p>%4$s</div>';
                    $class = esc_attr('pvbw notice notice-info is-dismissible');
                    $message = '<p>' . __('Hey') . ', ' . __('you have been using the Page View Count by Webline for a while now - that\'s great!') . '</p><p>' .
                        __('Could you do us a big favor and <strong>give us your wonderful review on WordPress.org</strong>? This will help us to increase our visibility and to develop even <strong>more features for you</strong>.') . '</p><p>' . __('Thanks!') . '</p>';
                    $buttons =
                        '<div style="margin-bottom: 15px;">'
                        . sprintf(
                            '<a class="button button-primary" style="margin-right: 15px;" href="%s" target="_blank" rel="noopener">%s</a>',
                            'https://wordpress.org/support/plugin/page-view-count-by-webline/reviews/?filter=5#new-post',
                            '<span class="dashicons dashicons-thumbs-up" style="line-height:28px;"></span> ' . __('Of course, you deserve it')
                        )
                        . sprintf(
                            '<a class="pvbw_rating_remind_later button" style="background:none;margin-right: 15px;" href="javascript:void(0);" data-action="pvbw_rating_remind_later">%s</a>',
                            '<span class="dashicons dashicons-backup" style="line-height:28px;"></span> ' . __('Please remind me later')
                        )
                        . '</div>';
                        if ($current_screen && $current_screen->id != 'toplevel_page_page-views') {
                            $buttons .=
                                '<div class="pvbw-customize-text"><p><img src="' .  VC_URL.'/admin/assets/css/images/logo.png" alt="Logo" style="float: left; margin-right: 10px; margin-top: -10px;"> Want to hire Wordpress Developer to finish your wordpress website quicker or need any help in maintenance and upgrades?'
                                . sprintf('<a class="button button-primary" style="float:right; margin-top:-7px; margin-right:-26px;" href="%s" target="_blank" rel="noopener">%s</a>', 'https://www.weblineindia.com/contact-us.html?utm_source=WP-Plugin&utm_medium=Page%View%20Count%20By%20Webline&utm_campaign=Hire%20WP%20Developer', '' . __('Hire Now'))
                                . sprintf(
                                    '',
                                    5,
                                    '<span class="dashicons dashicons-backup" style="line-height:28px;"></span> ' . __('Please remind me later')
                                )
                                . '</p></div>';
                        }

                    printf(
                        $notification_template,
                        $class,
                        'Page View Count by Webline :',
                        $message,
                        $buttons
                    );
                }
            }
        }

        // Check whether current screen is settings page of the Force Login By Webline plugin
        if ($current_screen && $current_screen->id === 'toplevel_page_page-views') {
            return;
        }

        //Admin notice for rating
        if (!(isset($_COOKIE['pvbw_customize_remind_later']) && $_COOKIE['pvbw_customize_remind_later'] === 'true')) {
            if ($days_since_activation < 15) {
                $notification_template = '<div class="%1$s">%2$s %3$s</div>';
                $class = esc_attr('notice notice-info pvbw-admin-notice');
                $message = '<div class="pvbw-plugin-cta-wrap">
                                <h2 class="head">Thank you for downloading our plugin - Page View Count by Webline.</h2>
                                <h2 class="head">We\'re here to help !</h2>
                                <p>Our plugin comes with free, basic support for all users. We also provide plugin customization in case you want to customize our plugin to suit your needs.</p>
                                <a href="https://www.weblineindia.com/contact-us.html?utm_source=WP-Plugin&utm_medium=Page%View%20Count%20By%20Webline&utm_campaign=Free%20Support" target="_blank" class="button">Need help?</a>
                                <a href="https://www.weblineindia.com/contact-us.html?utm_source=WP-Plugin&utm_medium=Page%View%20Count%20By%20Webline&utm_campaign=Plugin%20Customization" target="_blank" class="button">Want to customize plugin?</a>
                            </div>';
                $buttons = '<div class="pvbw-rating-text"><p><img src="' .  VC_URL.'/admin/assets/css/images/logo.png" alt="Logo" style="float: left; margin-right: 10px; margin-top: -10px;"> Want to hire Wordpress Developer to finish your wordpress website quicker or need any help in maintenance and upgrades?'
                    . sprintf('<a class="button button-primary" style="float:right; margin-top:-7px;" href="%s" target="_blank" rel="noopener">%s</a>', 'https://www.weblineindia.com/contact-us.html?utm_source=WP-Plugin&utm_medium=Page%View%20Count%20By%20Webline&utm_campaign=Hire%20WP%20Developer', '' . __('Hire Now'))
                    . sprintf(
                        '<a class="pvbw_customize_remind_later button" href="javascript:void(0);" data-action="pvbw_customize_remind_later" data-add="%d">%s</a>',
                        5,
                        '<span class="dashicons dashicons-backup" style="line-height:28px;"></span> ' . __('Please remind me later')
                    )
                    . '</p></div>';
        
                printf(
                    $notification_template,
                    $class,
                    $message,
                    $buttons
                );
            }
        }
    }
}

new pageViewMenu();