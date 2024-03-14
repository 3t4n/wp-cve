<?php

$faqs = [
	[
		'question' => __( '01. Does Dracula Dark Mode works with all WordPress themes?', 'dracula-dark-mode' ),
		'answer'   => __( 'Yes, Dracula Dark Mode has been built to be compatible with all the popular themes like Divi, Avada, Astra, Generatepress, and almost every WordPress compatibility themes.', 'dracula-dark-mode' ),
	],

	[
		'question' => __( '02. Can I customize the dark mode settings in a real-time preview?', 'dracula-dark-mode' ),
		'answer'   => __( 'Yes, Realtime Dark Mode Customization preview is the most unique feature that Dracula Dark Mode plugin has included. You can customize dark mode colors, presets, switches, and texts, and see changes in a real-time preview mode.', 'dracula-dark-mode' ),
	],

	[
		'question' => __( '03. Can I display dark mode toggle button in menu?', 'dracula-dark-mode' ),
		'answer'   => __( 'Yes, you can display the toggle switch button in any menu of your website. Even you can set the positions of the toggle button at the start or end of the menu.', 'dracula-dark-mode' ),
	],

	[
		'question' => __( '04. Can I create & customize my own custom toggle button?', 'dracula-dark-mode' ),
		'answer'   => __( 'Yes, you can create your fully customized own custom toggle button using the Toggle Button builder and display it anywhere on your website using the shortcode. You can also customize the switch color, text, layout, icons and many other options.', 'dracula-dark-mode' ),
	],

	[
		'question' => __( '05. Can I replace light mode images & videos in dark mode?', 'dracula-dark-mode' ),
		'answer'   => __( 'Dracula Dark Mode provides an advanced image & video replacement feature where you can replace any light mode images &  any self-hosted, Youtube, Vimeo, or DailyMotion videos in dark mode.', 'dracula-dark-mode' ),
	],

	[
		'question' => __( '06. Can I exclude certain sections/ elements in a  page from being affected by the dark mode?', 'dracula-dark-mode' ),
		'answer'   => __( 'Yes, you can exclude certain sections or elements on any page to keep them from being affected by dark mode. You have to use proper CSS selectors for the elements in the Excludes settings to exclude them from the dark mode. Even you can also exclude them by just clicking on the elements when you are in live edit dark mode.', 'dracula-dark-mode' ),
	],

	[
		'question' => __( '07. Can I use dark mode on Admin Dashboard?', 'dracula-dark-mode' ),
		'answer'   => __( 'Yes, Dracula Dark Mode allows site admins to enable and use dark mode in their admin dashboard. You can also allow the admin dashboard dark mode based on specific user roles (Administrator, Editor, Subscriber, etc). ', 'dracula-dark-mode' ),
	],

	[
		'question' => __( '08. Can I exclude specific posts or pages from dark mode?', 'dracula-dark-mode' ),
		'answer'   => __( 'Yes, Dracula Dark Mode allows you to exclude certain pages, posts, or any custom post types from dark mode from the Excludes settings.', 'dracula-dark-mode' ),
	],

	[
		'question' => __( '09. Can I schedule dark mode to turn on and off automatically based on a specific time?', 'dracula-dark-mode' ),
		'answer'   => __( 'Yes, you can schedule dark mode to turn it on and off automatically based on your selected time. This setting will work based on the user\'s device time zone.', 'dracula-dark-mode' ),
	],

	[
		'question' => __( '10. Can I set different color schemes for different pages?', 'dracula-dark-mode' ),
		'answer'   => __( 'Yes, you can set different color schemes for different pages by using our page-wise dark mode feature. Using the page-wise dark mode you can use different color schemes for each page to improve your brand image.', 'dracula-dark-mode' ),
	],

	[
		'question' => __( '11. Does Dracula Dark Mode support custom CSS?', 'dracula-dark-mode' ),
		'answer'   => __( 'Yes, Dracula Dark Mode has support for both the normal mode and dark mode custom CSS. That means you can customize both the light mode and dark mode appearance.', 'dracula-dark-mode' ),
	],

	[
		'question' => __( '12. Will Dracula Dark Mode plugin slow my website loading speed?', 'dracula-dark-mode' ),
		'answer'   => __( 'Dracula Dark Mode may have a minimal impact on your site load speed. But we have given ‘Performance Mode’ settings which will improve your website speed loading scripts in a deferred manner to reduce the initial page load time and improve overall website performance.', 'dracula-dark-mode' ),
	],

	[
		'question' => __( '13. Why Dark Mode not working properly with caching plugins (WP Rocket, W3 Total Cache, WP Super Cache, LiteSpeed Cache, WP-Optimize, etc)?', 'dracula-dark-mode' ),
		'answer'   => __( 'Dracula Dark Mode plugin relies on multiple JavaScript files and dependencies to work properly. So, if you are using any caching plugins, you have to disable the JavaScript deferred/ delay/ lazy load settings from the caching plugins to make the dark mode work properly.', 'dracula-dark-mode' ),
	],

];

?>


<div id="help" class="dracula-help getting-started-content">

    <div class="content-heading">
        <h2><?php esc_html_e( 'Frequently Asked Questions', 'dracula-dark-mode' ); ?></h2>
    </div>

    <section class="section-faq">
		<?php foreach ( $faqs as $faq ) : ?>
            <div class="faq-item">
                <div class="faq-header">
                    <i class="dashicons dashicons-arrow-down-alt2"></i>
                    <h3><?php echo esc_html( $faq['question'] ); ?></h3>
                </div>

                <div class="faq-body">
                    <p><?php echo esc_html( $faq['answer'] ); ?></p>
                </div>
            </div>
		<?php endforeach; ?>
    </section>

    <div class="content-heading">
        <h2><?php esc_html_e( 'Need Help?', 'dracula-dark-mode' ); ?></h2>
        <p><?php esc_html_e( 'Read our knowledge base documentation or you can contact us directly.', 'dracula-dark-mode' ); ?></p>
    </div>

    <div class="section-wrap">
        <section class="section-documentation section-half">
            <div class="col-image">
                <img src="<?php echo esc_attr( DRACULA_ASSETS . '/images/getting-started/documentation.png' ); ?>"
                     alt="<?php esc_attr_e( 'Documentation', 'dracula-dark-mode' ); ?>">
            </div>
            <div class="col-description">
                <h2><?php _e( 'Documentation', 'dracula-dark-mode' ) ?></h2>
                <p>
					<?php esc_html_e( 'Check out our detailed online documentation and video tutorials to find out more about what you can do.', 'dracula-dark-mode' ); ?>
                </p>
                <a class="dracula-btn btn-primary" href="https://softlabbd.com/docs-category/dracula-dark-mode-docs/"
                   target="_blank"><?php esc_html_e( 'Documentation', 'dracula-dark-mode' ); ?></a>
            </div>
        </section>

        <section class="section-contact section-half">
            <div class="col-image">
                <img src="<?php echo esc_attr( DRACULA_ASSETS . '/images/getting-started/contact.png' ); ?>"
                     alt="<?php esc_attr_e( 'Get Support', 'dracula-dark-mode' ); ?>">
            </div>
            <div class="col-description">
                <h2><?php esc_html_e( 'Support', 'dracula-dark-mode' ); ?></h2>
                <p><?php esc_html_e( 'We have dedicated support team to provide you fast, friendly & top-notch customer support.', 'dracula-dark-mode' ); ?></p>
                <a class="dracula-btn btn-primary" href="https://softlabbd.com/support" target="_blank">
					<?php esc_html_e( 'Get Support', 'dracula-dark-mode' ); ?>
                </a>
            </div>
        </section>
    </div>

    <section class="facebook-cta">
        <img src="<?php echo DRACULA_ASSETS . '/images/getting-started/facebook-icon.png'; ?>"/>

        <div class="cta-content">
            <h2><?php esc_html_e('Join our Facebook community?', 'dracula-dark-mode'); ?></h2>
            <p>
                <?php esc_html_e('Discuss, and share your problems & solutions for the Dracula Dark Mode WordPress plugin. Let\'s make a better community, share ideas, solve problems and finally build good relations.', 'dracula-dark-mode'); ?>
            </p>
        </div>

        <div class="cta-btn">
            <a href="https://www.facebook.com/groups/dracula.dark.mode" class="dracula-btn btn-primary"
               target="_blank"
            ><?php esc_html_e('Join Now', 'dracula-dark-mode'); ?></a>
        </div>

    </section>

</div>

<script>
    jQuery(document).ready(function ($) {
        $('.dracula-help .faq-item .faq-header').on('click', function () {
            $(this).parent().toggleClass('active');
        });
    });
</script>