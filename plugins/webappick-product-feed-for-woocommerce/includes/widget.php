<?php
/**
 * Created by PhpStorm.
 * User: wahid
 * Date: 5/22/20
 * Time: 9:02 PM
 */


if ( ! function_exists('webappick_add_dashboard_widgets') ) {
    /**
     * Add a widget to the dashboard.
     *
     * This function is hooked into the 'wp_dashboard_setup' action below.
     */
    function webappick_add_dashboard_widgets() {
        global $wp_meta_boxes;

        add_meta_box('aaaa_webappick_latest_news_dashboard_widget', __('Latest News from WebAppick Blog', 'woo-feed' ), 'webappick_dashboard_widget_render','dashboard','side','high');

    }
    add_action( 'wp_dashboard_setup', 'webappick_add_dashboard_widgets',1);
}

if ( ! function_exists('webappick_dashboard_widget_render') ) {
    /**
     * Function to get dashboard widget data.
     */
    function webappick_dashboard_widget_render() {

        // Initialize variable.
        $allposts = '';

        // Enter the name of your blog here followed by /wp-json/wp/v2/posts and add filters like this one that limits the result to 2 posts.
        $response = wp_remote_get( 'https://webappick.com/wp-json/wp/v2/posts?per_page=5' );

        // Exit if error.
        if ( is_wp_error( $response ) ) {
            return;
        }

        // Get the body.
        $posts = json_decode( wp_remote_retrieve_body( $response ) );

        // Exit if nothing is returned.
        if ( empty( $posts ) ) {
            return;
        }
        ?>
        <p> <a style="text-decoration: none;font-weight: bold;" href="<?php echo esc_url( 'https://webappick.com' ); ?>" target=_balnk><?php echo esc_html__("WEBAPPICK.COM",'woo-feed'); ?></a></p>
        <hr>
        <?php

        $ctx_pro_image =  WOO_FEED_PLUGIN_URL . "admin/images/pro-large-bg-black.png";
        $column_one = [
                esc_html__('Enable conditional pricing','woo-feed'),
                esc_html__('Multilingual product feed','woo-feed'),
                esc_html__('Filters + advanced filters','woo-feed')
        ];
        $column_two = [
            esc_html__('Use attribute mapping', 'woo-feed'),
            esc_html__('Generate feed by categories', 'woo-feed'),
            esc_html__('Leverage dynamic attribute', 'woo-feed')
        ];

        if( ! \CTXFeed\V5\Common\Helper::is_pro() ) { ?>
            <div class="woo-feed-widget-banner">
                <div class="woo-feed-widget-banner-image">
                    <img src='<?php echo esc_url($ctx_pro_image); ?>'>
                </div>
                <div class="woo-feed-widget-banner-heading" ><?php echo esc_html__('Unlock Exclusive Features for Product Feed Generation!', 'woo-feed')?></div>
                <div class="woo-feed-widget-banner-list">
                    <div class="woo-feed-widget-list-item">
                        <ul>
                            <?php foreach ($column_one as $value): ?>
                                <li class="woo-feed-widget-item">
                                    <div>
                                        <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 20 20" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                    </div>
                                    <span><?php echo $value; ?></span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <div class="woo-feed-widget-list-item">
                        <ul>
                            <?php foreach ($column_two as $value): ?>
                                <li class="woo-feed-widget-item">
                                    <div>
                                        <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 20 20" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                    </div>
                                    <span><?php echo $value; ?></span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
                <div class="woo-feed-widget-footer">
                    <div class="woo-feed-widget-button">
                        <a href="<?php echo esc_url('https://webappick.com/plugin/woocommerce-product-feed-pro/?utm_source=free_plugin_side&utm_medium=dashboard_banner&utm_campaign=free_to_pro&utm_term=ctx_feed')?>" target="_blank" ><?php echo esc_html__('Get Your CTX Feed Pro', 'woo-feed'); ?> <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 16 16" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg"><path d="m12.14 8.753-5.482 4.796c-.646.566-1.658.106-1.658-.753V3.204a1 1 0 0 1 1.659-.753l5.48 4.796a1 1 0 0 1 0 1.506z"></path></svg></a>
                    </div>
                </div>
            </div>
        <?php }

        // If there are posts.
        if ( ! empty( $posts ) ) {
            // For each post.
            foreach ( $posts as $post ) {
                $fordate = date( 'M j, Y', strtotime( $post->modified ) ); ?>
                <p class="webappick-feeds"> <a style="text-decoration: none;" href="<?php echo esc_url( $post->link ); ?>" target=_balnk><?php echo esc_html( $post->title->rendered ); ?></a> - <?php echo $fordate;?></p>
                <span><?php echo wp_trim_words( $post->content->rendered, 35, '...'); ?></span>
                <?php
            }
            ?>
            <hr>
            <p> <a style="text-decoration: none;" href="<?php echo esc_url( 'https://webappick.com/blog/' ); ?>" target=_balnk><?php echo esc_html__("Get more woocommerce tips & news on our blog...",'woo-feed'); ?></a></p>
            <?php
        }
    }
}