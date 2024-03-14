<?php defined('ABSPATH') || exit; ?>

<div id="wpz-custom-css-js-settings-container">
    <div id="wpz-custom-css-js-settings">

        <div id="wpz-custom-css-js-settings-header">
            <div class="wpz-custom-css-js-settings-logo">
                <img alt="ai-image-generator-logo"
                     src=" <?php echo( plugins_url( '../images/custom-css-js.svg', __FILE__ ) ); ?>">
                <h1> <?php esc_html_e( 'Custom CSS and JavaScript by WP Zone', 'wpz-custom-css-js' ) ?>  </h1>
            </div>

            <div id="wpz-custom-css-js-settings-header-links">
                <a class="wpz-custom-css-js-settings-header-link"
                   href="https://www.facebook.com/wpzoneco/"
                   target="_blank"><?php esc_html_e( 'Facebook', 'wpz-custom-css-js' ); ?></a>
                <a class="wpz-custom-css-js-settings-header-link"
                   href="https://wordpress.org/plugins/custom-css-and-javascript/#reviews"
                   target="_blank"><?php esc_html_e( 'Reviews', 'wpz-custom-css-js' ); ?></a>
                <a class="wpz-custom-css-js-settings-header-link"
                   href="https://wordpress.org/support/plugin/custom-css-and-javascript/"
                   target="_blank"><?php esc_html_e( 'Support', 'wpz-custom-css-js' ); ?></a>
                <a class="wpz-custom-css-js-settings-header-link"
                   href="https://wpzone.co/"
                   target="_blank"><?php esc_html_e( 'Visit WP Zone', 'wpz-custom-css-js' ); ?></a>
            </div>
        </div>


        <ul id="wpz-custom-css-js-settings-tabs">
            <li class="wpz-custom-css-js-settings-active">
                <a href="#CSS"><?php esc_html_e( 'Custom CSS', 'wpz-custom-css-js' ); ?></a>
            </li>
            <li>
                <a href="#JavaScript"><?php esc_html_e( 'Custom JavaScript', 'wpz-custom-css-js' ); ?></a>
            </li>
            <li>
                <a href="#addons"><?php esc_html_e( 'Addons', 'wpz-custom-css-js' ) ?></a>
            </li>
        </ul>

        <div id="wpz-custom-css-js-settings-tabs-content">
			<div id="wpz-custom-css-js-settings-code" class="wpz-custom-css-js-settings-active">
				<?php include( __DIR__ . '/admin-page-modes.php' ); ?>
				<?php include( __DIR__ . '/banners.php' ); ?>
			</div>
			
            <div id="wpz-custom-css-js-settings-addons">
				<?php
				 WPZ_Custom_CSS_JS_Addons::outputList();
				?>
            </div>


            <p class="wpz-custom-css-js-notification">
<!--                <img class="wpz-custom-css-js-notification-logo" alt="ai-image-generator-logo" width="80px"-->
<!--                     src=" --><?php //echo( plugins_url( '../images/custom-css-js.svg', __FILE__ ) ); ?><!--">-->
                <span class="wpz-custom-css-js-notification-wrapper">
                    <span><?php esc_html_e( 'If you like Custom CSS and JavaScript free plugin, please:', 'wpz-custom-css-js' ) ?> </span>
                    <a class="wpz-custom-css-js-button-secondary"
                       href="https://wordpress.org/support/view/plugin-reviews/<?php echo( $potent_slug ); ?>"
                       target="_blank" class="button-secondary"><?php esc_html_e( 'Write a Review', 'wpz-custom-css-js' ) ?></a>
                    <a class="wpz-custom-css-js-button-secondary" href="https://www.facebook.com/wpzoneco/"
                       target="_blank" class="button-secondary"><?php esc_html_e( 'Like us on Facebook', 'wpz-custom-css-js' ) ?></a>
                    <a class="wpz-custom-css-js-button-secondary"
                       href="https://www.youtube.com/channel/UCQx-0YMZwsnNBXQZNQTl1bA" target="_blank"
                       class="button-secondary"> <?php esc_html_e( 'Subscribe our YouTube Channel', 'wpz-custom-css-js' ) ?></a>
                </span>
            </p>
        </div>


        <p id="wpz-ai-images-settings-footnote">
            <small><?php printf(
					esc_html__( 'Custom CSS and JavaScript by %sWP Zone%s.', 'wpz-custom-css-js' ),
					'<a href="https://wpzone.co" target="_blank">',
					'</a>'
				); ?></small>
        </p>

    </div>
</div>


