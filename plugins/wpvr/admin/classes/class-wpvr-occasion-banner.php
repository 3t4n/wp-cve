<?php

/**
 * SpecialOccasionBanner Class
 *
 * This class is responsible for displaying a special occasion banner in the WordPress admin.
 *
 */
class WPVR_Special_Occasion_Banner {

    /**
     * The occasion identifier.
     *
     * @var string
     */
    private $occasion;

    /**
     * The start date and time for displaying the banner.
     *
     * @var int
     */
    private $start_date;

    /**
     * The end date and time for displaying the banner.
     *
     * @var int
     */
    private $end_date;

    /**
     * Constructor method for SpecialOccasionBanner class.
     *
     * @param string $occasion   The occasion identifier.
     * @param string $start_date The start date and time for displaying the banner.
     * @param string $end_date   The end date and time for displaying the banner.
     */
    public function __construct($occasion, $start_date, $end_date) {
        $this->occasion     = $occasion;
        $this->start_date   = strtotime($start_date);
        $this->end_date     = strtotime($end_date);

        if ( !defined('WPVR_PRO_VERSION') && 'no' === get_option( '_wpvr_christmas_23', 'no' )) {

            // Hook into the admin_notices action to display the banner
            add_action('admin_notices', array($this, 'display_banner'));

            // Add styles
            add_action('admin_head', array($this, 'add_styles'));
        }
    }


    /**
     * Displays the special occasion banner if the current date and time are within the specified range.
     */
    public function display_banner() {

        $screen                     = get_current_screen();
        $promotional_notice_pages   = ['dashboard', 'plugins', 'edit-wpvr_item', 'toplevel_page_wpvr', 'wp-vr_page_wpvr-setup-wizard'];
        $current_date_time          = current_time('timestamp');

        if (!in_array($screen->id, $promotional_notice_pages)) {
            return;
        }

        if ( $current_date_time < $this->start_date || $current_date_time > $this->end_date ) {
            return;
        }
        // Calculate the time remaining in seconds
        $time_remaining = $this->end_date - $current_date_time;
        $btn_link = 'https://rextheme.com/wpvr/?utm_source=plugin-CTA&utm_medium=WPVR-plugin&utm_campaign=christmas-campaign-2023#pricing';
        ?>
        <div class="wpvr-<?php echo esc_attr($this->occasion); ?>-banner notice">
            <div class="wpvr-promotional-banner">
                <div class="banner-overflow">
                    
                    <div class="wpvr-promotional-banner-wrapper">
                        <figure class="promotional-logo">
                            <img loading="lazy" src="<?php echo esc_url( WPVR_PLUGIN_DIR_URL.'admin/icon/christmas-logo.webp' ); ?>" alt="christmas-logo" />
                        </figure>

                        <!-- <div class="promotional-content">
                            <h4>
                                Super Sale <span>Is Live!</span>
                            </h4>
                        </div> -->
                        
                        <div class="promotional-discount">
                            <figure>
                                <img loading="lazy" src="<?php echo esc_url( WPVR_PLUGIN_DIR_URL.'admin/icon/christmas-discount-percent.webp' ); ?>" alt="christmas 25% off" />
                            </figure>
                        </div>

                        <!-- <div class="promotional-counter">
                            <ul class="countdown" id="wpvr_countdown">
                                <li><span id="wpvr_days">00</span> days</li>
                                <li><span id="wpvr_hours">00</span> hours</li>
                                <li><span id="wpvr_minutes">00</span> mins</li>
                                <li><span id="seconds">59</span> seconds</li>
                            </ul>
                        </div> -->

                        <div class="get-plugin-btn">
                            <a href="<?php echo esc_url($btn_link); ?>" target="_blank">
                                Flat <span>25%</span> OFF
                            </a>
                        </div>
                    </div>
                </div>

                <a class="close-promotional-banner wpvr-black-friday-close-promotional-banner" type="button" aria-label="close banner" id="wpvr-black-friday-close-button" href="https://rextheme.com/wpvr/#pricing" target="_blank">
                    <svg width="12" height="13" fill="none" viewBox="0 0 12 13" xmlns="http://www.w3.org/2000/svg"><path stroke="#7A8B9A" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 1.97L1 11.96m0-9.99l10 9.99"/></svg>
                </a>
            </div>
        </div>

        <script>
            var timeRemaining = <?php echo esc_js($time_remaining); ?>;

            // Update the countdown every second
            setInterval(function() {
                // var countdownElement    = document.getElementById('wpvr_countdown');
                // var daysElement         = document.getElementById('wpvr_days');
                // var hoursElement        = document.getElementById('wpvr_hours');
                // var minutesElement      = document.getElementById('wpvr_minutes');

                // Decrease the remaining time
                timeRemaining--;

                // Calculate new days, hours, and minutes
                var days = Math.floor(timeRemaining / (60 * 60 * 24));
                var hours = Math.floor((timeRemaining % (60 * 60 * 24)) / (60 * 60));
                var minutes = Math.floor((timeRemaining % (60 * 60)) / 60);


                // Format values with leading zeros
                days = (days < 10) ? '0' + days : days;
                hours = (hours < 10) ? '0' + hours : hours;
                minutes = (minutes < 10) ? '0' + minutes : minutes;

                // Update the HTML
                // daysElement.textContent = days;
                // hoursElement.textContent = hours;
                // minutesElement.textContent = minutes;

                // Check if the countdown has ended
                if (timeRemaining <= 0) {
                    countdownElement.innerHTML = 'Campaign Ended';
                }
            }, 1000); // Update every second
        </script>
        <?php
    }


    /**
     * Adds internal CSS styles for the special occasion banners.
     */
    public function add_styles() {
        ?>
        <style id="wpvr-promotional-banner-style" type="text/css">
            @font-face {
                font-family: "Circular Std Book";
                src: url(<?php echo WPVR_PLUGIN_DIR_URL.'admin/fonts/CircularStd-Book.woff2'; ?>) format("woff2"),
                url(<?php echo WPVR_PLUGIN_DIR_URL.'admin/fonts/CircularStd-Book.woff'; ?>) format("woff");
                font-weight: normal;
                font-style: normal;
                font-display: swap;
            }

            @font-face {
                font-family: 'Inter', sans-serif;
                src: url(<?php echo WPVR_PLUGIN_DIR_URL.'admin/fonts/Inter-Bold.woff2'; ?>) format('woff2'),
                url(<?php echo WPVR_PLUGIN_DIR_URL.'admin/fonts/Inter-Bold.woff'; ?>) format('woff');
                font-weight: 700;
                font-style: normal;
                font-display: swap;
            }

            @font-face {
                font-family: 'Lexend Deca';
                src: url(<?php echo WPVR_PLUGIN_DIR_URL.'admin/fonts/LexendDeca-Bold.woff2'; ?>) format('woff2'),
                url(<?php echo WPVR_PLUGIN_DIR_URL.'admin/fonts/LexendDeca-Bold.woff'; ?>) format('woff');
                font-weight: 700;
                font-style: normal;
                font-display: swap;
            }

            @font-face {
                font-family: 'Lexend Deca';
                src: url(<?php echo WPVR_PLUGIN_DIR_URL.'admin/fonts/LexendDeca-Medium.woff2'; ?>) format('woff2'),
                url(<?php echo WPVR_PLUGIN_DIR_URL.'admin/fonts/LexendDeca-Medium.woff'; ?>) format('woff');
                font-weight: 500;
                font-style: normal;
                font-display: swap;
            }

            .wpvr-promotional-banner, 
            .wpvr-promotional-banner * {
                box-sizing: border-box;
            }
                        
            .wpvr-christmas-banner.notice {
                border: none;
                padding: 0;
                display: block;
                background: transparent;
                box-shadow: none;
            }

            .wp-vr_page_wpvr-setup-wizard .wpvr-promotional-banner,
            .wp-vr_page_wpvr-addons .wpvr-promotional-banner,
            .toplevel_page_wpvr .wpvr-promotional-banner {
                width: calc(100% - 20px);
                margin: 20px 0;
            }

            .wp-vr_page_wpvr-setup-wizard .wpvr-christmas-banner.notice,
            .wp-vr_page_wpvr-addons .wpvr-christmas-banner.notice,
            .toplevel_page_wpvr .wpvr-christmas-banner.notice {
                margin: 0;
            }

            .wpvr-promotional-banner {
                background-color: #224215;
                width: 100%;
                background-image: url(<?php echo esc_url( WPVR_PLUGIN_DIR_URL.'admin/icon/christmas-bg.webp' )?>);
                background-position: center;
                background-repeat: no-repeat;
                background-size: cover;
                position: relative;
                border: none;
                box-shadow: none;
                display: block;
            }

            .wpvr-promotional-banner .banner-overflow {
                overflow: hidden;
                position: relative;
                width: 100%;
            }

            .wpvr-promotional-banner figure {
                margin: 0;
            }

            .wpvr-promotional-banner-wrapper {
                display: flex;
                flex-flow: row wrap;
                align-items: center;
                justify-content: space-between;
                gap: 30px;
                max-width: 1400px;
                margin: 0 auto;
                padding: 0 15px;
                z-index: 1;
                position: relative;
                height: 100%;
            }

            .wpvr-promotional-banner-wrapper .promotional-content {
                max-width: 420px;
            }

            .wpvr-promotional-banner-wrapper .promotional-logo img {
                max-width: 293px;
                position: relative;
                top: 7px;
                display: block;
            }

            .wpvr-promotional-banner-wrapper .promotional-content h4 {
                margin: 0;
                text-transform: none;
                color: #ED8136;
                font-family: 'Lexend Deca';
                font-size: 30px;
                font-style: normal;
                font-weight: 700;
                line-height: 1.37;
            }

            .wpvr-promotional-banner-wrapper .promotional-content h4 span {
                display: block;
                color: #BEB4F4;
                font-family: 'Lexend Deca';
                font-size: 24px;
                font-style: normal;
                font-weight: 600;
                line-height: 1.1;
                letter-spacing: 1.68px;
                text-transform: uppercase;
            }

            .wpvr-promotional-banner-wrapper .promotional-discount img {
                max-width: 338px;
                display: block;
                position: relative;
                top: 8px;
            }

            .wpvr-promotional-banner-wrapper .countdown {
                position: relative;
                top: 3px;
            }
            .wpvr-promotional-banner-wrapper .countdown {
                display: flex;
                justify-content: center;
                gap: 20px;
                margin: 0;
                padding: 0;
            }

            .wpvr-promotional-banner-wrapper .countdown li {
                display: flex;
                flex-direction: column;
                text-align: center;
                width: 68.5px;
                font-size: 16px;
                list-style-type: none;
                font-family: "Circular Std Book";
                line-height: 1.2;
                font-weight: 500;
                letter-spacing: 1.6px;
                text-transform: uppercase;
                text-align: center;
                color: #A89CC3;
                margin: 0;
            }

            .wpvr-promotional-banner-wrapper .countdown li span {
                font-size: 44px;
                font-family: 'Inter', sans-serif;
                font-weight: 700;
                line-height: 1;
                color: #fff;
                text-align: center;
                margin-bottom: 10px;
                padding: 4px 2px;

                border-radius: 10px;
                border: 1px solid #FF0083;
                background: linear-gradient(148deg, #2A0856 21.92%, #140102 80.41%);
                box-shadow: 0px 5px 0px 0px #C90369;

            }

            .wpvr-promotional-banner-wrapper .get-plugin-btn a {
                text-decoration: none;
                padding: 18px 29px 16px;
                border-radius: 15px;
                background: #00B4FF;
                color: #FFF;
                text-align: center;
                font-family: 'Lexend Deca';
                font-size: 20px;
                font-weight: 700;
                line-height: 1;
                display: block;
                outline: none;
                box-shadow: none;
            }
            .wpvr-promotional-banner-wrapper .get-plugin-btn a:focus {
                outline: none;
                box-shadow: none;
            }

            .wpvr-promotional-banner-wrapper .get-plugin-btn span {
                font-family: 'Lexend Deca';
                font-size: 26px;
                font-weight: 700;
                line-height: 1;
            }

            .wpvr-promotional-banner .close-promotional-banner {
                position: absolute;
                top: -10px;
                right: -9px;
                background: #fff;
                border: none;
                padding: 8px 9px;
                border-radius: 50%;
                cursor: pointer;
                z-index: 9;
            }

            .wpvr-promotional-banner .close-promotional-banner svg {
                display: block;
                width: 10px;
            }

            @media only screen and (max-width: 1399px) {
                .wpvr-promotional-banner-wrapper .promotional-logo img {
                    max-width: 243px;
                }
                .wpvr-promotional-banner .promotional-discount img {
                    max-width: 285px;
                }

                .wpvr-promotional-banner-wrapper .promotional-content h4 {
                    font-size: 26px;
                }
                .wpvr-promotional-banner-wrapper .promotional-content h4 span {
                    font-size: 20px;
                }

                .wpvr-promotional-banner-wrapper .get-plugin-btn span {
                    font-size: 22px;
                }

                .wpvr-promotional-banner-wrapper .countdown {
                    gap: 10px;
                }
                .wpvr-promotional-banner-wrapper .countdown li {
                    width: 58px;
                    font-size: 14px;
                }
                .wpvr-promotional-banner-wrapper .countdown li span {
                    font-size: 36px;
                    box-shadow: 0px 3px 0px 0px #C90369;
                }

                .wpvr-promotional-banner-wrapper .get-plugin-btn a {
                    padding: 14px 18px 14px;
                    font-size: 18px;
                    font-weight: 600;
                    border-radius: 10px;
                }
            }

            @media only screen and (max-width: 1199px) {
                .wpvr-promotional-banner-wrapper {
                    gap: 24px;
                }

                .wpvr-promotional-banner-wrapper .promotional-content h4 {
                    font-size: 22px;
                }
                .wpvr-promotional-banner-wrapper .promotional-content h4 span {
                    font-size: 17px;
                }

                .wpvr-promotional-banner-wrapper .countdown li {
                    width: 47px;
                    font-size: 11px;
                }
                .wpvr-promotional-banner-wrapper .countdown li span {
                    font-size: 26px;
                }

                .wpvr-promotional-banner-wrapper .get-plugin-btn a {
                    padding: 14px 18px 14px;
                    font-size: 16px;
                    border-radius: 10px;
                    position: relative;
                    top: 2px;
                }
                .wpvr-promotional-banner-wrapper .get-plugin-btn span {
                    font-size: 18px;
                }
                
            }

            @media only screen and (max-width: 991px) {
                .wpvr-promotional-banner-wrapper .promotional-logo img {
                    max-width: 213px;
                }
                .wpvr-promotional-banner .promotional-discount img {
                    max-width: 265px;
                }
                .wpvr-promotional-banner-wrapper .get-plugin-btn a {
                    top: 0;
                }
                
            }
        </style>
        <?php
    }

}