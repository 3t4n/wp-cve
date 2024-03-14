<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://rextheme.com/
 * @since      1.0.0
 *
 * @package    Wpvr
 * @subpackage Wpvr/admin/partials
 */
?>
<?php
/**
 * get rollback version of WPVR
 *
 * @return array|mixed
 *
 * @src Inspired from Elementor roll back options
 */
function rex_wpvr_get_roll_back_versions() {
    $rollback_versions = get_transient( 'rex_wpvr_rollback_versions_' . WPVR_VERSION );
    if ( false === $rollback_versions ) {
        $max_versions = 5;
        require_once ABSPATH . 'wp-admin/includes/plugin-install.php';
        $plugin_information = plugins_api(
            'plugin_information', [
                'slug' => 'wpvr',
            ]
        );
        if ( empty( $plugin_information->versions ) || ! is_array( $plugin_information->versions ) ) {
            return [];
        }

        krsort( $plugin_information->versions );

        $rollback_versions = [];

        $current_index = 0;
        foreach ( $plugin_information->versions as $version => $download_link ) {
            if ( $max_versions <= $current_index ) {
                break;
            }

            $lowercase_version = strtolower( $version );
            $is_valid_rollback_version = ! preg_match( '/(trunk|beta|rc|dev)/i', $lowercase_version );

            /**
             * Is rollback version is valid.
             *
             * Filters the check whether the rollback version is valid.
             *
             * @param bool $is_valid_rollback_version Whether the rollback version is valid.
             */
            $is_valid_rollback_version = apply_filters(
                'rex_wpvr_is_valid_rollback_version',
                $is_valid_rollback_version,
                $lowercase_version
            );

            if ( ! $is_valid_rollback_version ) {
                continue;
            }

            if ( version_compare( $version, WPVR_VERSION, '>=' ) ) {
                continue;
            }

            $current_index++;
            $rollback_versions[] = $version;
        }

        set_transient( 'rex_wpvr_rollback_versions_' . WPVR_VERSION, $rollback_versions, WEEK_IN_SECONDS );
    }
    return $rollback_versions;
}

$rollback_versions     = function_exists( 'rex_wpvr_get_roll_back_versions' ) ? rex_wpvr_get_roll_back_versions() : array();

?>
<!-- This file should display the admin pages -->
<div class="rex-onboarding">
    <ul class="tabs tabs-icon rex-tabs">
        <li class="tab col s3 wpvr_tabs_row">
            <a href="#tab1">
                <svg height="20px" width="20px" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 330 330" xml:space="preserve">
                    <path d="M165,0C74.019,0,0,74.02,0,165.001C0,255.982,74.019,330,165,330s165-74.018,165-164.999C330,74.02,255.981,0,165,0z
                        M165,300c-74.44,0-135-60.56-135-134.999C30,90.562,90.56,30,165,30s135,60.562,135,135.001C300,239.44,239.439,300,165,300z" />
                    <path d="M164.998,70c-11.026,0-19.996,8.976-19.996,20.009c0,11.023,8.97,19.991,19.996,19.991
                        c11.026,0,19.996-8.968,19.996-19.991C184.994,78.976,176.024,70,164.998,70z" />
                    <path d="M165,140c-8.284,0-15,6.716-15,15v90c0,8.284,6.716,15,15,15c8.284,0,15-6.716,15-15v-90C180,146.716,173.284,140,165,140z
                        " />
                </svg>

                <?php _e('Info', 'wpvr'); ?>
            </a>
        </li>

        <li class="tab col s3 wpvr_tabs_row">
            <a href="#tab2">
                <svg id="Capa_1" enable-background="new 0 0 512 512" height="14" width="18" viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg">
                    <g>
                        <path d="m338.95 243.28-120-75c-4.625-2.89-10.453-3.043-15.222-.4-4.77 2.643-7.729 7.667-7.729 13.12v150c0 5.453 2.959 10.476 7.729 13.12 2.266 1.256 4.77 1.88 7.271 1.88 2.763 0 5.522-.763 7.95-2.28l120-75c4.386-2.741 7.05-7.548 7.05-12.72s-2.663-9.979-7.049-12.72zm-112.95 60.656v-95.873l76.698 47.937z" />
                        <path d="m437 61h-362c-41.355 0-75 33.645-75 75v240c0 41.355 33.645 75 75 75h362c41.355 0 75-33.645 75-75v-240c0-41.355-33.645-75-75-75zm45 315c0 24.813-20.187 45-45 45h-362c-24.813 0-45-20.187-45-45v-240c0-24.813 20.187-45 45-45h362c24.813 0 45 20.187 45 45z" />
                    </g>
                </svg>
                <?php _e('Video Tutorials', 'wpvr'); ?>
            </a>
        </li>

        <?php
        if (!is_plugin_active('wpvr-pro/wpvr-pro.php')) {
        ?>
            <li class="tab col s3 wpvr_tabs_row">
                <a href="#tab3">
                    <svg height="16px" viewBox="0 -10 511.98685 511" width="17px" xmlns="http://www.w3.org/2000/svg">
                        <path d="m114.59375 491.140625c-5.609375 0-11.179688-1.75-15.933594-5.1875-8.855468-6.417969-12.992187-17.449219-10.582031-28.09375l32.9375-145.089844-111.703125-97.960937c-8.210938-7.167969-11.347656-18.519532-7.976562-28.90625 3.371093-10.367188 12.542968-17.707032 23.402343-18.710938l147.796875-13.417968 58.433594-136.746094c4.308594-10.046875 14.121094-16.535156 25.023438-16.535156 10.902343 0 20.714843 6.488281 25.023437 16.511718l58.433594 136.769532 147.773437 13.417968c10.882813.980469 20.054688 8.34375 23.425782 18.710938 3.371093 10.367187.253906 21.738281-7.957032 28.90625l-111.703125 97.941406 32.9375 145.085938c2.414063 10.667968-1.726562 21.699218-10.578125 28.097656-8.832031 6.398437-20.609375 6.890625-29.910156 1.300781l-127.445312-76.160156-127.445313 76.203125c-4.308594 2.558594-9.109375 3.863281-13.953125 3.863281zm141.398438-112.875c4.84375 0 9.640624 1.300781 13.953124 3.859375l120.277344 71.9375-31.085937-136.941406c-2.21875-9.746094 1.089843-19.921875 8.621093-26.515625l105.472657-92.5-139.542969-12.671875c-10.046875-.917969-18.6875-7.234375-22.613281-16.492188l-55.082031-129.046875-55.148438 129.066407c-3.882812 9.195312-12.523438 15.511718-22.546875 16.429687l-139.5625 12.671875 105.46875 92.5c7.554687 6.613281 10.859375 16.769531 8.621094 26.539062l-31.0625 136.9375 120.277343-71.914062c4.308594-2.558594 9.109376-3.859375 13.953126-3.859375zm-84.585938-221.847656s0 .023437-.023438.042969zm169.128906-.0625.023438.042969c0-.023438 0-.023438-.023438-.042969zm0 0" />
                    </svg>
                    <?php _e('Free vs Pro', 'wpvr'); ?>
                </a>
            </li>
        <?php
        }
        ?>
        <?php
        if (is_plugin_active('wpvr-pro/wpvr-pro.php')) {
        ?>
            <li class="tab col s3 wpvr_tabs_row">
                <a href="#tab4">
                    <svg id="bold" enable-background="new 0 0 24 24" height="512" viewBox="0 0 24 24" width="512" xmlns="http://www.w3.org/2000/svg">
                        <path d="m12 6c-3.309 0-6 2.691-6 6s2.691 6 6 6 6-2.691 6-6-2.691-6-6-6zm3 7h-2v2c0 .552-.448 1-1 1s-1-.448-1-1v-2h-2c-.552 0-1-.448-1-1s.448-1 1-1h2v-2c0-.552.448-1 1-1s1 .448 1 1v2h2c.552 0 1 .448 1 1s-.448 1-1 1z" />
                        <path d="m1.5 12c0-5.789 4.71-10.5 10.5-10.5 2.079 0 4.055.607 5.732 1.707l-1.512 1.513c-.472.47-.139 1.28.53 1.28h4.5c.414 0 .75-.336.75-.75v-4.5c0-.665-.806-1.004-1.28-.53l-1.914 1.914c-1.971-1.369-4.322-2.134-6.806-2.134-6.617 0-12 5.383-12 12 0 1.173.173 2.339.513 3.466.119.395.534.621.935.502.396-.12.621-.538.501-.935-.298-.987-.449-2.007-.449-3.033z" />
                        <path d="m23.487 8.534c-.12-.397-.535-.623-.935-.502-.396.12-.621.538-.501.935.298.987.449 2.007.449 3.033 0 5.789-4.71 10.5-10.5 10.5-2.075 0-4.048-.604-5.722-1.7l1.505-1.522c.468-.474.132-1.278-.533-1.278h-4.5c-.2 0-.393.08-.533.223s-.219.335-.217.535l.05 4.5c.006.666.819.99 1.283.519l1.878-1.899c1.967 1.362 4.312 2.122 6.789 2.122 6.617 0 12-5.383 12-12 0-1.173-.173-2.339-.513-3.466z" />
                    </svg>
                    <?php _e('Import', 'wpvr'); ?>
                </a>
            </li>
        <?php
        }
        ?>

        <li class="tab col s3 wpvr_tabs_row">
            <a href="#tab5">
                <svg id="Layer_1" enable-background="new 0 0 512 512" height="17px" viewBox="0 0 512 512" width="17px" xmlns="http://www.w3.org/2000/svg">
                    <path d="m272.066 512h-32.133c-25.989 0-47.134-21.144-47.134-47.133v-10.871c-11.049-3.53-21.784-7.986-32.097-13.323l-7.704 7.704c-18.659 18.682-48.548 18.134-66.665-.007l-22.711-22.71c-18.149-18.129-18.671-48.008.006-66.665l7.698-7.698c-5.337-10.313-9.792-21.046-13.323-32.097h-10.87c-25.988 0-47.133-21.144-47.133-47.133v-32.134c0-25.989 21.145-47.133 47.134-47.133h10.87c3.531-11.05 7.986-21.784 13.323-32.097l-7.704-7.703c-18.666-18.646-18.151-48.528.006-66.665l22.713-22.712c18.159-18.184 48.041-18.638 66.664.006l7.697 7.697c10.313-5.336 21.048-9.792 32.097-13.323v-10.87c0-25.989 21.144-47.133 47.134-47.133h32.133c25.989 0 47.133 21.144 47.133 47.133v10.871c11.049 3.53 21.784 7.986 32.097 13.323l7.704-7.704c18.659-18.682 48.548-18.134 66.665.007l22.711 22.71c18.149 18.129 18.671 48.008-.006 66.665l-7.698 7.698c5.337 10.313 9.792 21.046 13.323 32.097h10.87c25.989 0 47.134 21.144 47.134 47.133v32.134c0 25.989-21.145 47.133-47.134 47.133h-10.87c-3.531 11.05-7.986 21.784-13.323 32.097l7.704 7.704c18.666 18.646 18.151 48.528-.006 66.665l-22.713 22.712c-18.159 18.184-48.041 18.638-66.664-.006l-7.697-7.697c-10.313 5.336-21.048 9.792-32.097 13.323v10.871c0 25.987-21.144 47.131-47.134 47.131zm-106.349-102.83c14.327 8.473 29.747 14.874 45.831 19.025 6.624 1.709 11.252 7.683 11.252 14.524v22.148c0 9.447 7.687 17.133 17.134 17.133h32.133c9.447 0 17.134-7.686 17.134-17.133v-22.148c0-6.841 4.628-12.815 11.252-14.524 16.084-4.151 31.504-10.552 45.831-19.025 5.895-3.486 13.4-2.538 18.243 2.305l15.688 15.689c6.764 6.772 17.626 6.615 24.224.007l22.727-22.726c6.582-6.574 6.802-17.438.006-24.225l-15.695-15.695c-4.842-4.842-5.79-12.348-2.305-18.242 8.473-14.326 14.873-29.746 19.024-45.831 1.71-6.624 7.684-11.251 14.524-11.251h22.147c9.447 0 17.134-7.686 17.134-17.133v-32.134c0-9.447-7.687-17.133-17.134-17.133h-22.147c-6.841 0-12.814-4.628-14.524-11.251-4.151-16.085-10.552-31.505-19.024-45.831-3.485-5.894-2.537-13.4 2.305-18.242l15.689-15.689c6.782-6.774 6.605-17.634.006-24.225l-22.725-22.725c-6.587-6.596-17.451-6.789-24.225-.006l-15.694 15.695c-4.842 4.843-12.35 5.791-18.243 2.305-14.327-8.473-29.747-14.874-45.831-19.025-6.624-1.709-11.252-7.683-11.252-14.524v-22.15c0-9.447-7.687-17.133-17.134-17.133h-32.133c-9.447 0-17.134 7.686-17.134 17.133v22.148c0 6.841-4.628 12.815-11.252 14.524-16.084 4.151-31.504 10.552-45.831 19.025-5.896 3.485-13.401 2.537-18.243-2.305l-15.688-15.689c-6.764-6.772-17.627-6.615-24.224-.007l-22.727 22.726c-6.582 6.574-6.802 17.437-.006 24.225l15.695 15.695c4.842 4.842 5.79 12.348 2.305 18.242-8.473 14.326-14.873 29.746-19.024 45.831-1.71 6.624-7.684 11.251-14.524 11.251h-22.148c-9.447.001-17.134 7.687-17.134 17.134v32.134c0 9.447 7.687 17.133 17.134 17.133h22.147c6.841 0 12.814 4.628 14.524 11.251 4.151 16.085 10.552 31.505 19.024 45.831 3.485 5.894 2.537 13.4-2.305 18.242l-15.689 15.689c-6.782 6.774-6.605 17.634-.006 24.225l22.725 22.725c6.587 6.596 17.451 6.789 24.225.006l15.694-15.695c3.568-3.567 10.991-6.594 18.244-2.304z" />
                    <path d="m256 367.4c-61.427 0-111.4-49.974-111.4-111.4s49.973-111.4 111.4-111.4 111.4 49.974 111.4 111.4-49.973 111.4-111.4 111.4zm0-192.8c-44.885 0-81.4 36.516-81.4 81.4s36.516 81.4 81.4 81.4 81.4-36.516 81.4-81.4-36.515-81.4-81.4-81.4z" />
                </svg>
                <?php _e('Settings', 'wpvr'); ?>
            </a>
        </li>
    </ul>

    <div id="tab1" class="block-wrapper info-tab">
        <div class="info-wrapper">

            <div class="single-block banner">
                <a href="https://rextheme.com/wpvr/" target="_blank">
                    <img loading="lazy" src="<?php echo WPVR_PLUGIN_DIR_URL . 'admin/icon/wpvr-banner.jpg' ?>" alt="wpvr-banner">
                </a>
            </div>

            <div class="single-block share-block">
                <div class="upgrade-pro">
                    <img loading="lazy" src="<?php echo WPVR_PLUGIN_DIR_URL . 'admin/icon/wpvr-logo.png' ?>" alt="logo">
                    <?php if (!is_plugin_active('wpvr-pro/wpvr-pro.php')) { ?>
                        <a class="wpvr-btn" href="https://rextheme.com/wpvr/#pricing" target="_blank">
                            <?php _e('Upgrade to Pro ', 'wpvr'); ?>
                        </a>
                    <?php } ?>
                </div>
                <div class="social-share">
                    <h4><?php _e('Share On', 'wpvr'); ?></h4>
                    <ul>
                        <li>
                            <a href="https://www.facebook.com/sharer/sharer.php?u=https%3A//wordpress.org/plugins/wpvr/" title="Facebook" target="_blank">
                                <img loading="lazy" src="<?php echo WPVR_PLUGIN_DIR_URL . 'admin/icon/fb-regular.jpg' ?>" alt="Facebook" class="regular">
                                <img loading="lazy" src="<?php echo WPVR_PLUGIN_DIR_URL . 'admin/icon/fb-hover.jpg' ?>" alt="Facebook" class="hover">
                            </a>
                        </li>
                        <li>
                            <a href="https://twitter.com/home?status=https%3A//wordpress.org/plugins/wpvr/" title="Twitter" target="_blank">
                                <img loading="lazy" src="<?php echo WPVR_PLUGIN_DIR_URL . 'admin/icon/tw-regular.jpg' ?>" alt="Twitter" class="regular">
                                <img loading="lazy" src="<?php echo WPVR_PLUGIN_DIR_URL . 'admin/icon/tw-hover.jpg' ?>" alt="Twitter" class="hover">
                            </a>
                        </li>
                        <li>
                            <a href="https://www.linkedin.com/shareArticle?mini=true&url=https%3A//wordpress.org/plugins/wpvr/&title=&summary=&source=" title="Linkedin" target="_blank">
                                <img loading="lazy" src="<?php echo WPVR_PLUGIN_DIR_URL . 'admin/icon/in-regular.jpg' ?>" alt="Linked in" class="regular">
                                <img loading="lazy" src="<?php echo WPVR_PLUGIN_DIR_URL . 'admin/icon/in-hover.jpg' ?>" alt="Linked in" class="hover">
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="single-block doc">
                <div class="single-block-heading">
                    <span class="icon">
                        <img loading="lazy" src="<?php echo WPVR_PLUGIN_DIR_URL . 'admin/icon/doc-icon.png' ?>" class="doc-icon" alt="doc-icon">
                    </span>
                    <h4><?php _e('Documentation', 'wpvr'); ?></h4>
                </div>
                
                <p><?php _e('Before You start, you can check our Documentation to get familiar with WP VR - 360 Panorama and virtual tour creator for WordPress.', 'wpvr'); ?></p>
                <a class="wpvr-btn" href="https://rextheme.com/docs/wp-vr/" target="_blank"><?php _e('Documentation', 'wpvr'); ?></a>
            </div>

            <div class="single-block support">
                <div class="single-block-heading">
                    <span class="icon">
                        <img loading="lazy" src="<?php echo WPVR_PLUGIN_DIR_URL . 'admin/icon/support-icon.png' ?>" class="support-icon" alt="support-icon">
                    </span>
                    <h4><?php _e('Support', 'wpvr'); ?></h4>
                </div>

                <p><?php _e('Can\'t find solution on with our documentation? Just Post a ticket on Support forum. We are to solve your issue.', 'wpvr'); ?></p>
                <a class="wpvr-btn" href="https://wordpress.org/support/plugin/wpvr" target="_blank"><?php _e('Post a Ticket', 'wpvr'); ?></a>
            </div>

            <div class="single-block rating">
                <div class="single-block-heading">
                    <span class="icon">
                        <img loading="lazy" src="<?php echo WPVR_PLUGIN_DIR_URL . 'admin/icon/rating-icon.png' ?>" class="rating-icon" alt="rating-icon">
                    </span>
                    <h4><?php _e('Make WPVR Popular', 'wpvr'); ?></h4>
                </div>

                <p><?php _e('Your rating and feedback matters to us. If you are happy with WP VR - 360 Panorama and virtual tour creator for WordPress give us a rating.', 'wpvr'); ?> </p>
                <a class="wpvr-btn" href="https://wordpress.org/plugins/wpvr/#reviews" target="_blank"><?php _e('Rate Us ', 'wpvr'); ?></a>
            </div>

        </div>

        <div class="promotion-area">
            <h4 class="title"><?php _e('Check out our other amazing free plugins!', 'wpvr'); ?></h4>
            <div class="single-block m2c">
                <div class="single-block-heading">
                    <span class="icon">
                        <img loading="lazy" src="<?php echo WPVR_PLUGIN_DIR_URL . 'admin/icon/wpfunnels.png' ?>" alt="icon">
                    </span>
                    <h4><?php _e('WPFunnels', 'wpvr'); ?></h4>
                </div>
                <p><?php _e('Create high-converting slaes funnels on a visual drag & drop funnel builder canvas and increase your online sales from today.', 'wpvr'); ?></p>

                <a class="wpvr-btn" href="https://wordpress.org/plugins/wpfunnels/" target="_blank"><?php _e('Get It Now', 'wpvr'); ?></a>
            </div>

            <div class="single-block sb">

                <div class="single-block-heading">
                    <span class="icon">
                        <img loading="lazy" src="<?php echo WPVR_PLUGIN_DIR_URL . 'admin/icon/cart-lift.png' ?>" alt="icon">
                    </span>
                    <h4><?php _e('Cart Lift', 'wpvr'); ?></h4>

                </div>

                <p><?php _e('Recover abandoned carts with automated email drip campaigns. Start getting back lost sales on your online store.', 'wpvr'); ?></p>

                <a class="wpvr-btn" href="https://wordpress.org/plugins/cart-lift/" target="_blank"><?php _e('Get It Now', 'wpvr'); ?></a>
            </div>
        </div>
    </div>

    <div id="tab2" class="block-wrapper">
        <div class="video-wrapper">

            <div class="video-left">
                <iframe src="https://www.youtube.com/embed/videoseries?list=PLelDqLncNWcUndi1NkXJh2BH62OYmIayt" width="100%" height="100%" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
            </div>

            <div class="video-right">
                <div class="single-block share-block">
                    <div class="upgrade-pro">
                        <img loading="lazy" src="<?php echo WPVR_PLUGIN_DIR_URL . 'admin/icon/wpvr-logo.png' ?>" alt="logo">
                        <?php if (!is_plugin_active('wpvr-pro/wpvr-pro.php')) { ?>
                            <a class="wpvr-btn" href="https://rextheme.com/wpvr/#pricing" target="_blank"><?php _e('Upgrade to Pro ', 'wpvr'); ?></a>
                        <?php } ?>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <?php
    if (!is_plugin_active('wpvr-pro/wpvr-pro.php')) {
    ?>
        <div id="tab3" class="block-wrapper">
            <div class="wpvr-compare">
                <div class="compare-header">
                    <h4><?php _e('WPVR Feature Comparison', 'wpvr'); ?></h4>
                    <?php $pro_url = add_query_arg('wpvr-dashboard', '1', 'https://rextheme.com/wpvr/#pricing'); ?>
                    <a class="wpvr-btn get-pro" href="<?php echo $pro_url; ?>" title="Upgrade to Pro" target="_blank"><?php _e('Upgrade to Pro', 'wpvr'); ?></a>
                </div>

                <div class="compare-tbl-wrapper">
                    <ul class="single-feature list-header">
                        <li class="feature"><?php _e('features', 'wpvr'); ?></li>
                        <li class="free"><?php _e('free', 'wpvr'); ?></li>
                        <li class="pro"><?php _e('pro', 'wpvr'); ?></li>
                    </ul>

                    <ul class="single-feature feature-list">
                        <li class="feature"><?php _e('Unlimited Scenes (Up to 5 in Free)', 'wpvr'); ?></li>
                        <li class="free">
        <span class="icon no">
            <img loading="lazy" src="<?php echo WPVR_PLUGIN_DIR_URL . 'admin/icon/cross.png' ?>" alt="cross">
        </span>
                        </li>
                        <li class="pro">
        <span class="icon yes">
            <img loading="lazy" src="<?php echo WPVR_PLUGIN_DIR_URL . 'admin/icon/check.png' ?>" alt="check">
        </span>
                        </li>
                    </ul>

                    <ul class="single-feature feature-list">
                        <li class="feature"><?php _e('Unlimited Hotspots (Up to to 5 for a scene in free)', 'wpvr'); ?></li>
                        <li class="free">
                            <span class="icon no">
                                <img loading="lazy" src="<?php echo WPVR_PLUGIN_DIR_URL . 'admin/icon/cross.png' ?>" alt="cross">
                            </span>
                        </li>
                        <li class="pro">
                            <span class="icon yes">
                                <img loading="lazy" src="<?php echo WPVR_PLUGIN_DIR_URL . 'admin/icon/check.png' ?>" alt="check">
                            </span>
                        </li>
                    </ul>

                    <ul class="single-feature feature-list">
                        <li class="feature"><?php _e('Publish Tours Anywhere (Embed Add-on)', 'wpvr'); ?></li>
                        <li class="free">
                            <span class="icon no">
                                <img loading="lazy" src="<?php echo WPVR_PLUGIN_DIR_URL . 'admin/icon/cross.png' ?>" alt="cross">
                            </span>
                        </li>
                        <li class="pro">
                            <span class="icon yes">
                                <img loading="lazy" src="<?php echo WPVR_PLUGIN_DIR_URL . 'admin/icon/check.png' ?>" alt="check">
                            </span>
                        </li>
                    </ul>

                    <ul class="single-feature feature-list">
                        <li class="feature"><?php _e('WooCommerce Add-on', 'wpvr'); ?></li>
                        <li class="free">
                            <span class="icon no">
                                <img loading="lazy" src="<?php echo WPVR_PLUGIN_DIR_URL . 'admin/icon/cross.png' ?>" alt="cross">
                            </span>
                        </li>
                        <li class="pro">
                            <span class="icon yes">
                                <img loading="lazy" src="<?php echo WPVR_PLUGIN_DIR_URL . 'admin/icon/check.png' ?>" alt="check">
                            </span>
                        </li>
                    </ul>

                    <ul class="single-feature feature-list">
                        <li class="feature"><?php _e('Gyroscope Support', 'wpvr'); ?></li>
                        <li class="free">
                            <span class="icon no">
                                <img loading="lazy" src="<?php echo WPVR_PLUGIN_DIR_URL . 'admin/icon/cross.png' ?>" alt="cross">
                            </span>
                        </li>
                        <li class="pro">
                            <span class="icon yes">
                                <img loading="lazy" src="<?php echo WPVR_PLUGIN_DIR_URL . 'admin/icon/check.png' ?>" alt="check">
                            </span>
                        </li>
                    </ul>

                    <ul class="single-feature feature-list">
                        <li class="feature"><?php _e('Panorama Scene Gallery', 'wpvr'); ?></li>
                        <li class="free">
                            <span class="icon no">
                                <img loading="lazy" src="<?php echo WPVR_PLUGIN_DIR_URL . 'admin/icon/cross.png' ?>" alt="cross">
                            </span>
                        </li>
                        <li class="pro">
                            <span class="icon yes">
                                <img loading="lazy" src="<?php echo WPVR_PLUGIN_DIR_URL . 'admin/icon/check.png' ?>" alt="check">
                            </span>
                        </li>
                    </ul>
                    <ul class="single-feature feature-list">
                        <li class="feature"><?php _e('Explainer Video', 'wpvr'); ?></li>
                        <li class="free">
                            <span class="icon no">
                                <img loading="lazy" src="<?php echo WPVR_PLUGIN_DIR_URL . 'admin/icon/cross.png' ?>" alt="cross">
                            </span>
                        </li>
                        <li class="pro">
                            <span class="icon yes">
                                <img loading="lazy" src="<?php echo WPVR_PLUGIN_DIR_URL . 'admin/icon/check.png' ?>" alt="check">
                            </span>
                        </li>
                    </ul>

                    <ul class="single-feature feature-list">
                        <li class="feature"><?php _e('Tour Background Audio', 'wpvr'); ?></li>
                        <li class="free">
                            <span class="icon no">
                                <img src="<?php echo WPVR_PLUGIN_DIR_URL . 'admin/icon/cross.png' ?>" alt="cross">
                            </span>
                        </li>
                        <li class="pro">
                            <span class="icon yes">
                                <img src="<?php echo WPVR_PLUGIN_DIR_URL . 'admin/icon/check.png' ?>" alt="check">
                            </span>
                        </li>
                    </ul>

                    <ul class="single-feature feature-list">
                        <li class="feature"><?php _e('Info-type Hotspots (Heading, Image, Text, Video, Gif, Links)', 'wpvr'); ?></li>
                        <li class="free">
                            <span class="icon no">
                                <img src="<?php echo WPVR_PLUGIN_DIR_URL . 'admin/icon/check.png' ?>" alt="cross">
                            </span>
                        </li>
                        <li class="pro">
                            <span class="icon yes">
                                <img src="<?php echo WPVR_PLUGIN_DIR_URL . 'admin/icon/check.png' ?>" alt="check">
                            </span>
                        </li>
                    </ul>

                    <ul class="single-feature feature-list">
                        <li class="feature"><?php _e('Scene-type Hotspots (Connect Panoramas)', 'wpvr'); ?></li>
                        <li class="free">
                            <span class="icon no">
                                <img src="<?php echo WPVR_PLUGIN_DIR_URL . 'admin/icon/check.png' ?>" alt="cross">
                            </span>
                        </li>
                        <li class="pro">
                            <span class="icon yes">
                                <img src="<?php echo WPVR_PLUGIN_DIR_URL . 'admin/icon/check.png' ?>" alt="check">
                            </span>
                        </li>
                    </ul>

                    <ul class="single-feature feature-list">
                        <li class="feature"><?php _e('Custom Icon & Color for Hotspots (Using CSS)', 'wpvr'); ?></li>
                        <li class="free">
                            <span class="icon no">
                                <img src="<?php echo WPVR_PLUGIN_DIR_URL . 'admin/icon/check.png' ?>" alt="cross">
                            </span>
                        </li>
                        <li class="pro">
                            <span class="icon yes">
                                <img src="<?php echo WPVR_PLUGIN_DIR_URL . 'admin/icon/check.png' ?>" alt="check">
                            </span>
                        </li>
                    </ul>

                    <ul class="single-feature feature-list">
                        <li class="feature"><?php _e('900+ Icons & RGB Color Support for Hotspots', 'wpvr'); ?></li>
                        <li class="free">
                            <span class="icon no">
                                <img src="<?php echo WPVR_PLUGIN_DIR_URL . 'admin/icon/cross.png' ?>" alt="cross">
                            </span>
                        </li>
                        <li class="pro">
                            <span class="icon yes">
                                <img src="<?php echo WPVR_PLUGIN_DIR_URL . 'admin/icon/check.png' ?>" alt="check">
                            </span>
                        </li>
                    </ul>

                    <ul class="single-feature feature-list">
                        <li class="feature"><?php _e('Partial Panorama / Mobile Panorama Support', 'wpvr'); ?></li>
                        <li class="free">
                            <span class="icon no">
                                <img src="<?php echo WPVR_PLUGIN_DIR_URL . 'admin/icon/cross.png' ?>" alt="cross">
                            </span>
                        </li>
                        <li class="pro">
                            <span class="icon yes">
                                <img src="<?php echo WPVR_PLUGIN_DIR_URL . 'admin/icon/check.png' ?>" alt="check">
                            </span>
                        </li>
                    </ul>

                    <ul class="single-feature feature-list">
                        <li class="feature"><?php _e('360 Video Support', 'wpvr'); ?></li>
                        <li class="free">
                            <span class="icon no">
                                <img src="<?php echo WPVR_PLUGIN_DIR_URL . 'admin/icon/check.png' ?>" alt="cross">
                            </span>
                        </li>
                        <li class="pro">
                            <span class="icon yes">
                                <img src="<?php echo WPVR_PLUGIN_DIR_URL . 'admin/icon/check.png' ?>" alt="check">
                            </span>
                        </li>
                    </ul>

                    <ul class="single-feature feature-list">
                        <li class="feature"><?php _e('Google Street View', 'wpvr'); ?></li>
                        <li class="free">
                            <span class="icon no">
                                <img src="<?php echo WPVR_PLUGIN_DIR_URL . 'admin/icon/cross.png' ?>" alt="cross">
                            </span>
                        </li>
                        <li class="pro">
                            <span class="icon yes">
                                <img src="<?php echo WPVR_PLUGIN_DIR_URL . 'admin/icon/check.png' ?>" alt="check">
                            </span>
                        </li>
                    </ul>
                    <ul class="single-feature feature-list">
                        <li class="feature"><?php _e('Cubemap Image Support', 'wpvr'); ?></li>
                        <li class="free">
                            <span class="icon no">
                                <img src="<?php echo WPVR_PLUGIN_DIR_URL . 'admin/icon/cross.png' ?>" alt="cross">
                            </span>
                        </li>
                        <li class="pro">
                            <span class="icon yes">
                                <img src="<?php echo WPVR_PLUGIN_DIR_URL . 'admin/icon/check.png' ?>" alt="check">
                            </span>
                        </li>
                    </ul>
                    <ul class="single-feature feature-list">
                        <li class="feature"><?php _e('VR Glass Support for Video Tours', 'wpvr'); ?></li>
                        <li class="free">
                            <span class="icon no">
                                <img src="<?php echo WPVR_PLUGIN_DIR_URL . 'admin/icon/check.png' ?>" alt="cross">
                            </span>
                        </li>
                        <li class="pro">
                            <span class="icon yes">
                                <img src="<?php echo WPVR_PLUGIN_DIR_URL . 'admin/icon/check.png' ?>" alt="check">
                            </span>
                        </li>
                    </ul>

                    <ul class="single-feature feature-list">
                        <li class="feature"><?php _e('Fluent Forms Add-on', 'wpvr'); ?></li>
                        <li class="free">
                            <span class="icon no">
                                <img src="<?php echo WPVR_PLUGIN_DIR_URL . 'admin/icon/cross.png' ?>" alt="cross">
                            </span>
                        </li>
                        <li class="pro">
                            <span class="icon yes">
                                <img src="<?php echo WPVR_PLUGIN_DIR_URL . 'admin/icon/check.png' ?>" alt="check">
                            </span>
                        </li>
                    </ul>

                    <ul class="single-feature feature-list">
                        <li class="feature"><?php _e('Autoload Tours', 'wpvr'); ?></li>
                        <li class="free">
                            <span class="icon no">
                                <img src="<?php echo WPVR_PLUGIN_DIR_URL . 'admin/icon/check.png' ?>" alt="cross">
                            </span>
                        </li>
                        <li class="pro">
                            <span class="icon yes">
                                <img src="<?php echo WPVR_PLUGIN_DIR_URL . 'admin/icon/check.png' ?>" alt="check">
                            </span>
                        </li>
                    </ul>
                    <ul class="single-feature feature-list">
                        <li class="feature"><?php _e('Custom Rotation Settings', 'wpvr'); ?></li>
                        <li class="free">
                            <span class="icon no">
                                <img src="<?php echo WPVR_PLUGIN_DIR_URL . 'admin/icon/check.png' ?>" alt="cross">
                            </span>
                        </li>
                        <li class="pro">
                            <span class="icon yes">
                                <img src="<?php echo WPVR_PLUGIN_DIR_URL . 'admin/icon/check.png' ?>" alt="check">
                            </span>
                        </li>
                    </ul>
                    <ul class="single-feature feature-list">
                        <li class="feature"><?php _e('Full Page Virtual Tours', 'wpvr'); ?></li>
                        <li class="free">
                            <span class="icon no">
                                <img src="<?php echo WPVR_PLUGIN_DIR_URL . 'admin/icon/check.png' ?>" alt="cross">
                            </span>
                        </li>
                        <li class="pro">
                            <span class="icon yes">
                                <img src="<?php echo WPVR_PLUGIN_DIR_URL . 'admin/icon/check.png' ?>" alt="check">
                            </span>
                        </li>
                    </ul>
                    <ul class="single-feature feature-list">
                        <li class="feature"><?php _e('Custom Preview Image & Text', 'wpvr'); ?></li>
                        <li class="free">
                            <span class="icon no">
                                <img src="<?php echo WPVR_PLUGIN_DIR_URL . 'admin/icon/check.png' ?>" alt="cross">
                            </span>
                        </li>
                        <li class="pro">
                            <span class="icon yes">
                                <img src="<?php echo WPVR_PLUGIN_DIR_URL . 'admin/icon/check.png' ?>" alt="check">
                            </span>
                        </li>
                    </ul>

                    <ul class="single-feature feature-list">
                        <li class="feature"><?php _e('Company Logo & Description', 'wpvr'); ?></li>
                        <li class="free">
                            <span class="icon no">
                                <img src="<?php echo WPVR_PLUGIN_DIR_URL . 'admin/icon/cross.png' ?>" alt="cross">
                            </span>
                        </li>
                        <li class="pro">
                            <span class="icon yes">
                                <img src="<?php echo WPVR_PLUGIN_DIR_URL . 'admin/icon/check.png' ?>" alt="check">
                            </span>
                        </li>
                    </ul>

                    <ul class="single-feature feature-list">
                        <li class="feature"><?php _e('Import & Export Virtual Tours', 'wpvr'); ?></li>
                        <li class="free">
                            <span class="icon no">
                                <img src="<?php echo WPVR_PLUGIN_DIR_URL . 'admin/icon/cross.png' ?>" alt="cross">
                            </span>
                        </li>
                        <li class="pro">
                            <span class="icon yes">
                                <img src="<?php echo WPVR_PLUGIN_DIR_URL . 'admin/icon/check.png' ?>" alt="check">
                            </span>
                        </li>
                    </ul>

                    <ul class="single-feature feature-list">
                        <li class="feature"><?php _e('Duplicate Tours with One Click', 'wpvr'); ?></li>
                        <li class="free">
                            <span class="icon no">
                                <img src="<?php echo WPVR_PLUGIN_DIR_URL . 'admin/icon/cross.png' ?>" alt="cross">
                            </span>
                        </li>
                        <li class="pro">
                            <span class="icon yes">
                                <img src="<?php echo WPVR_PLUGIN_DIR_URL . 'admin/icon/check.png' ?>" alt="check">
                            </span>
                        </li>
                    </ul>

                    <ul class="single-feature feature-list">
                        <li class="feature"><?php _e('Control Horizontal & Vertical View of Panorama Images', 'wpvr'); ?></li>
                        <li class="free">
                            <span class="icon no">
                                <img src="<?php echo WPVR_PLUGIN_DIR_URL . 'admin/icon/cross.png' ?>" alt="cross">
                            </span>
                        </li>
                        <li class="pro">
                            <span class="icon yes">
                                <img src="<?php echo WPVR_PLUGIN_DIR_URL . 'admin/icon/check.png' ?>" alt="check">
                            </span>
                        </li>
                    </ul>

                    <ul class="single-feature feature-list">
                        <li class="feature"><?php _e('Custom Zoom Settings', 'wpvr'); ?></li>
                        <li class="free">
                            <span class="icon no">
                                <img src="<?php echo WPVR_PLUGIN_DIR_URL . 'admin/icon/cross.png' ?>" alt="cross">
                            </span>
                        </li>
                        <li class="pro">
                            <span class="icon yes">
                                <img src="<?php echo WPVR_PLUGIN_DIR_URL . 'admin/icon/check.png' ?>" alt="check">
                            </span>
                        </li>
                    </ul>

                    <ul class="single-feature feature-list">
                        <li class="feature"><?php _e('Custom Panorama Loading Face', 'wpvr'); ?></li>
                        <li class="free">
                            <span class="icon no">
                                <img src="<?php echo WPVR_PLUGIN_DIR_URL . 'admin/icon/cross.png' ?>" alt="cross">
                            </span>
                        </li>
                        <li class="pro">
                            <span class="icon yes">
                                <img src="<?php echo WPVR_PLUGIN_DIR_URL . 'admin/icon/check.png' ?>" alt="check">
                            </span>
                        </li>
                    </ul>

                    <ul class="single-feature feature-list">
                        <li class="feature"><?php _e('Background Panoramas', 'wpvr'); ?></li>
                        <li class="free">
                            <span class="icon no">
                                <img src="<?php echo WPVR_PLUGIN_DIR_URL . 'admin/icon/cross.png' ?>" alt="cross">
                            </span>
                        </li>
                        <li class="pro">
                            <span class="icon yes">
                                <img src="<?php echo WPVR_PLUGIN_DIR_URL . 'admin/icon/check.png' ?>" alt="check">
                            </span>
                        </li>
                    </ul>

                </div>
                <!-- /compare-tbl-wrapper -->

                <div class="wpvr-more-feature">
                    <h5 class="heading"><?php _e('More Pro Features', 'wpvr'); ?></h5>
                    <ul>
                        <li>
                            <img src="<?php echo WPVR_PLUGIN_DIR_URL . 'admin/icon/check.png' ?>" alt="check">
                            <?php _e('Home Button to visit Default Scene', 'wpvr'); ?>
                        </li>
                        <li>
                            <img src="<?php echo WPVR_PLUGIN_DIR_URL . 'admin/icon/check.png' ?>" alt="check">
                            <?php _e('Scene Title', 'wpvr'); ?>
                        </li>
                        <li>
                            <img src="<?php echo WPVR_PLUGIN_DIR_URL . 'admin/icon/check.png' ?>" alt="check">
                            <?php _e('Scene Author with URL', 'wpvr'); ?>
                        </li>
                        <li>
                            <img src="<?php echo WPVR_PLUGIN_DIR_URL . 'admin/icon/check.png' ?>" alt="check">
                            <?php _e('Enable or Disable Keyboard Movement Control.', 'wpvr'); ?>
                        </li>
                        <li>
                            <img src="<?php echo WPVR_PLUGIN_DIR_URL . 'admin/icon/check.png' ?>" alt="check">
                            <?php _e('Enable or Disable Keyboard Zoom Control.', 'wpvr'); ?>
                        </li>
                        <li>
                            <img src="<?php echo WPVR_PLUGIN_DIR_URL . 'admin/icon/check.png' ?>" alt="check">
                            <?php _e('Enable or Disable Mouse Drag Control.', 'wpvr'); ?>
                        </li>
                        <li>
                            <img src="<?php echo WPVR_PLUGIN_DIR_URL . 'admin/icon/check.png' ?>" alt="check">
                            <?php _e('Enable or Disable Mouse Zoom control.', 'wpvr'); ?>
                        </li>
                        <li>
                            <img src="<?php echo WPVR_PLUGIN_DIR_URL . 'admin/icon/check.png' ?>" alt="check">
                            <?php _e('Mouse Control.', 'wpvr'); ?>
                        </li>
                        <li>
                            <img src="<?php echo WPVR_PLUGIN_DIR_URL . 'admin/icon/check.png' ?>" alt="check">
                            <?php _e('On Screen Compass.', 'wpvr'); ?>
                        </li>
                        <li>
                            <img src="<?php echo WPVR_PLUGIN_DIR_URL . 'admin/icon/check.png' ?>" alt="check">
                            <?php _e('Scene Titles on Gallery.', 'wpvr'); ?>
                        </li>
                        <li>
                            <img src="<?php echo WPVR_PLUGIN_DIR_URL . 'admin/icon/check.png' ?>" alt="check">
                            <?php _e('Customize Icon & Logo of Control Buttons.', 'wpvr'); ?>
                        </li>
                        <li>
                            <img src="<?php echo WPVR_PLUGIN_DIR_URL . 'admin/icon/check.png' ?>" alt="check">
                            <?php _e('Autoload & Autoplay Video Tours.', 'wpvr'); ?>
                        </li>
                    </ul>
                </div>


                <div class="footer-btn">
                    <a class="wpvr-btn get-pro" href="<?php echo $pro_url; ?>" title="Upgrade to Pro" target="_blank"><?php _e('Upgrade to Pro', 'wpvr'); ?></a>
                </div>
            </div>
        </div>
    <?php
    }
    ?>

    <?php
    if (is_plugin_active('wpvr-pro/wpvr-pro.php')) {
    ?>
        <div id="tab4" class="block-wrapper import-tab">
            <div class="import-tab-wrapper">
                <h4 class="tab-title"><?php _e('Import tour file: ', 'wpvr'); ?></h4>
                <div class="parent" style="width:100%;">
                    <form id="wpvr_import_from">
                        <a class="wpvr-import-btn" id="wpvr_button_upload"><i class="material-icons"><?php echo __('add','wpvr')?></i></a>
                        <p class="vr-notice"><?php _e('Do not close or refresh the page during import process. It may take few minutes.', 'wpvr'); ?></p>
                        <div class="file-path-wrapper">
                            <input class="file-path validate" id="wpvr_file_url" type="text" value="" data-value="">
                        </div>
                        <div id="wpvr_progress" class="progress" style="display:none;">
                            <div class="indeterminate"></div>
                        </div>
                        <button class="wpvr-btn" type="submit" id="wpvr_button_submit"><?php echo __('Submit','wpvr') ?></button>
                    </form>
                </div>
            </div>
        </div>
    <?php
    }
    ?>

    <div id="tab5" class="block-wrapper">
        <div class="rex-upgrade wpvr-settings <?php echo is_plugin_active('wpvr-pro/wpvr-pro.php') ? 'pro-active' : ''; ?>">
            <h4><?php _e('General Setup Options', 'wpvr'); ?></h4>
            <div class="parent settings-wrapper">
                <div class="wpvr_role-container">
                    <ul>
                        <?php
                        $is_wpvr_premium = apply_filters('is_wpvr_premium', false);
                        $is_integration_module = apply_filters('is_integration_module', false);
                        $editor_active = get_option('wpvr_editor_active');
                        $author_active = get_option('wpvr_author_active');
                        $fontawesome_disable = get_option('wpvr_fontawesome_disable');
                        $cardboard_disable = get_option('wpvr_cardboard_disable');
                        $wpvr_webp_conversion = get_option('wpvr_webp_conversion');
                        $mobile_media_resize = get_option('mobile_media_resize');
                        $wpvr_script_control = get_option('wpvr_script_control');
                        $wpvr_script_list = get_option('wpvr_script_list');
                        $wpvr_video_script_control = get_option('wpvr_video_script_control');
                        $wpvr_video_script_list = get_option('wpvr_video_script_list');
                        $high_res_image = get_option('high_res_image');
                        $dis_on_hover = get_option('dis_on_hover');
                        $enable_woocommerce = get_option('wpvr_enable_woocommerce', false);
                        ?>
                        <li>
                            <h6><?php echo __("Allow the Editors of your site to Create, Edit, Update, and Delete virtual tours (They can access other users' tours):", "wpvr"); ?></h6>
                            <span class="wpvr-switcher">
                                <?php
                                if ($editor_active == "true") {
                                ?>
                                    <input id="wpvr_editor_active" type="checkbox" checked>
                                <?php
                                } else {
                                ?>
                                    <input id="wpvr_editor_active" type="checkbox">
                                <?php
                                }
                                ?>
                                <label for="wpvr_editor_active"></label>
                            </span>

                            <span class="wpvr-tooltip">
                                <span class="icon">
                                    <img src="<?php echo WPVR_PLUGIN_DIR_URL . 'admin/icon/question-icon.png' ?>" alt="check">
                                </span>
                                <p><?php echo __('Editors will be able to Create, Edit, Update, and Delete all virtual tours.', 'wpvr'); ?></p>
                            </span>
                        </li>

                        <li>
                            <h6><?php echo __("Allow the Authors of your site to Create, Edit, Update, and Delete virtual tours (They can access their own tours only):", "wpvr"); ?></h6>

                            <span class="wpvr-switcher">
                                <?php
                                if ($author_active == "true") {
                                ?>
                                    <input id="wpvr_author_active" type="checkbox" checked>
                                <?php
                                } else {
                                ?>
                                    <input id="wpvr_author_active" type="checkbox">
                                <?php
                                }
                                ?>
                                <label for="wpvr_author_active"></label>
                            </span>

                            <span class="wpvr-tooltip">
                                <span class="icon">
                                    <img src="<?php echo WPVR_PLUGIN_DIR_URL . 'admin/icon/question-icon.png' ?>" alt="check">
                                </span>
                                <p><?php echo __('Authors will be able to Create, Edit, Update, and Delete their own virtual tours only.', 'wpvr'); ?></p>
                            </span>
                        </li>

                        <li>
                            <h6><?php echo __("Disable Fontawesome from WP VR:", "wpvr"); ?></h6>

                            <span class="wpvr-switcher">
                                <?php
                                if ($fontawesome_disable == "true") {
                                ?>
                                    <input id="wpvr_fontawesome_disable" type="checkbox" checked>
                                <?php
                                } else {
                                ?>
                                    <input id="wpvr_fontawesome_disable" type="checkbox">
                                <?php
                                }
                                ?>
                                <label for="wpvr_fontawesome_disable"></label>
                            </span>

                            <span class="wpvr-tooltip">
                                <span class="icon">
                                    <img src="<?php echo WPVR_PLUGIN_DIR_URL . 'admin/icon/question-icon.png' ?>" alt="check">
                                </span>
                                <p><?php echo __('WP VR will not load Font Awesome library.', 'wpvr'); ?></p>
                            </span>
                        </li>

                        <li>
                            <h6><?php echo __("Enable mobile media resizer:", "wpvr"); ?></h6>

                            <span class="wpvr-switcher">
                                <?php
                                if ($mobile_media_resize == "true") {
                                ?>
                                    <input id="mobile_media_resize" type="checkbox" checked>
                                <?php
                                } else {
                                ?>
                                    <input id="mobile_media_resize" type="checkbox">
                                <?php
                                }
                                ?>
                                <label for="mobile_media_resize"></label>
                            </span>

                            <span class="wpvr-tooltip">
                                <span class="icon">
                                    <img src="<?php echo WPVR_PLUGIN_DIR_URL . 'admin/icon/question-icon.png' ?>" alt="check">
                                </span>
                                <p><?php echo __('WP VR will resize each scenes for mobile devices.', 'wpvr'); ?></p>
                            </span>
                        </li>

                        <li>
                            <h6><?php echo __("Disable WordPress Large Image Handler on WP VR:", "wpvr"); ?></h6>

                            <span class="wpvr-switcher">
                                <?php
                                if ($high_res_image == "true") {
                                ?>
                                    <input id="high_res_image" type="checkbox" checked>
                                <?php
                                } else {
                                ?>
                                    <input id="high_res_image" type="checkbox">
                                <?php
                                }
                                ?>
                                <label for="high_res_image"></label>
                            </span>

                            <span class="wpvr-tooltip">
                                <span class="icon">
                                    <img src="<?php echo WPVR_PLUGIN_DIR_URL . 'admin/icon/question-icon.png' ?>" alt="check">
                                </span>
                                <p><?php echo __("WordPress's default large image handler for images larger than 2560px will be disabled for WP VR. So can create virtual tours with extremely high-quality images. Enabling it will also show high res image on mobile devices. Many devices may not support that resolution.", 'wpvr'); ?></p>
                            </span>
                        </li>

                        <li>
                            <h6><?php echo __("Disable On Hover Content for Mobile:", "wpvr"); ?></h6>

                            <span class="wpvr-switcher">
                                <?php
                                if ($dis_on_hover == "true") {
                                ?>
                                    <input id="dis_on_hover" type="checkbox" checked>
                                <?php
                                } else {
                                ?>
                                    <input id="dis_on_hover" type="checkbox">
                                <?php
                                }
                                ?>
                                <label for="dis_on_hover"></label>
                            </span>

                            <span class="wpvr-tooltip">
                                <span class="icon">
                                    <img src="<?php echo WPVR_PLUGIN_DIR_URL . 'admin/icon/question-icon.png' ?>" alt="check">
                                </span>
                                <p><?php echo __("You can disable on hover content for mobile devices. As most of the devices are touch based.", 'wpvr'); ?></p>
                            </span>
                        </li>

                        <li>

                            <h6><?php echo __("Enable script control (It will load the WP VR scripts on the pages with virtual tours only):", "wpvr"); ?></h6>

                            <span class="wpvr-switcher">
                                <?php
                                if ($wpvr_script_control == "true") {
                                ?>
                                    <input id="wpvr_script_control" type="checkbox" checked>
                                <?php
                                } else {
                                ?>
                                    <input id="wpvr_script_control" type="checkbox">
                                <?php
                                }
                                ?>
                                <label for="wpvr_script_control"></label>
                            </span>

                            <span class="wpvr-tooltip">
                                <span class="icon">
                                    <img src="<?php echo WPVR_PLUGIN_DIR_URL . 'admin/icon/question-icon.png' ?>" alt="check">
                                </span>
                                <p><?php echo __("WP VR assets will be loaded on your allowed pages only. If you turn this on, you have to list the URL's of the pages with virtual tours on the 'List of allowed pages to load WP VR scripts' option", 'wpvr'); ?></p>
                            </span>
                        </li>

                        <li class="enqueue-script wpvr_enqueue_script_list">
                            <h6><?php echo __('List of allowed pages to load WP VR scripts (The URLs of the pages on your site with virtual tours):', 'wpvr'); ?> </h6>

                            <span class="wpvr-tooltip">
                                <span class="icon">
                                    <img src="<?php echo WPVR_PLUGIN_DIR_URL . 'admin/icon/question-icon.png' ?>" alt="check">
                                </span>
                                <p><?php echo __("List the pages with virtual tours like this: https://example.com/tour1/, https://example.com/tour2/", 'wpvr'); ?></p>
                            </span>

                            <textarea id="wpvr_script_list" class="materialize-textarea" placeholder="https://example.com/tour1/,https://example.com/tour2/"><?php echo $wpvr_script_list; ?></textarea>
                        </li>

                        <li>

                            <h6><?php echo __("Enable Video JS control (It will load the WP VR Video JS library in the listed pages only):", "wpvr"); ?></h6>

                            <span class="wpvr-switcher">
                                <?php
                                if ($wpvr_video_script_control == "true") {
                                ?>
                                    <input id="wpvr_video_script_control" type="checkbox" checked>
                                <?php
                                } else {
                                ?>
                                    <input id="wpvr_video_script_control" type="checkbox">
                                <?php
                                }
                                ?>
                                <label for="wpvr_video_script_control"></label>
                            </span>

                            <span class="wpvr-tooltip">
                                <span class="icon">
                                    <img src="<?php echo WPVR_PLUGIN_DIR_URL . 'admin/icon/question-icon.png' ?>" alt="check">
                                </span>
                                <p><?php echo __("WP VR assets will be loaded on your allowed pages only. If you turn this on, you have to list the URL's of the pages with virtual tours on the 'List of allowed pages to load WP VR scripts' option", 'wpvr'); ?></p>
                            </span>
                        </li>

                        <li class="enqueue-video-script enqueue-script wpvr_enqueue_video_script_list">
                            <h6><?php echo __('List of allowed pages to load WP VR Video JS library (The URLs of the pages on your site, You want to load Video JS):', 'wpvr'); ?> </h6>

                            <span class="wpvr-tooltip">
                                <span class="icon">
                                    <img src="<?php echo WPVR_PLUGIN_DIR_URL . 'admin/icon/question-icon.png' ?>" alt="check">
                                </span>
                                <p><?php echo __("List the pages like this: https://example.com/tour1/, https://example.com/tour2/", 'wpvr'); ?></p>
                            </span>

                            <textarea id="wpvr_video_script_list" class="materialize-textarea" placeholder="https://example.com/video-tour1/,https://example.com/video-tour2/"><?php echo $wpvr_video_script_list; ?></textarea>
                        </li>

                        <!-- WPVR front-end notice -->
                        <li class="enqueue-script front-notice">
                            <?php
                            $wpvr_frontend_notice = false;
                            $wpvr_frontend_notice_area = '';
                            $wpvr_frontend_notice = get_option('wpvr_frontend_notice');
                            $wpvr_frontend_notice_area = get_option('wpvr_frontend_notice_area');
                            if (!$wpvr_frontend_notice_area) {
                                $wpvr_frontend_notice_area = __("Flip the phone to landscape mode for a better experience of the tour.", "wpvr");
                            }
                            ?>
                            <h6><?php echo __("Front-End Notice for Mobile Visitors:", "wpvr"); ?></h6>

                            <span class="wpvr-switcher">
                                <?php
                                if ($wpvr_frontend_notice == "true") {
                                ?>
                                    <input id="wpvr_frontend_notice" type="checkbox" checked>
                                <?php
                                } else {
                                ?>
                                    <input id="wpvr_frontend_notice" type="checkbox">
                                <?php
                                }
                                ?>
                                <label for="wpvr_frontend_notice"></label>
                            </span>

                            <span class="wpvr-tooltip">
                                <span class="icon">
                                    <img src="<?php echo WPVR_PLUGIN_DIR_URL . 'admin/icon/question-icon.png' ?>" alt="check">
                                </span>
                                <p><?php echo __("The notice will appear on the front end of the virtual tour if viewed from a mobile device.", 'wpvr'); ?></p>
                            </span>
                            <textarea id="wpvr_frontend_notice_area" class="materialize-textarea" placeholder="Add your notice here"><?php echo $wpvr_frontend_notice_area; ?></textarea>
                        </li>
                        <!-- WPVR front-end notice -->
                        <?php if (is_plugin_active('wpvr-pro/wpvr-pro.php')) { ?>
                        <li>
                            <h6><?php echo __("VR GLass Support:", "wpvr"); ?></h6>

                            <span class="wpvr-switcher">
                                <?php
                                if ($cardboard_disable == 'true') {
                                ?>
                                    <input id="wpvr_cardboard_disable" type="checkbox" checked>
                                <?php
                                } else {
                                ?>
                                    <input id="wpvr_cardboard_disable" type="checkbox" >
                                <?php
                                }
                                ?>
                                <label for="wpvr_cardboard_disable"></label>
                            </span>

                            <span class="wpvr-tooltip">
                                <span class="icon">
                                    <img src="<?php echo WPVR_PLUGIN_DIR_URL . 'admin/icon/question-icon.png' ?>" alt="check">
                                </span>
                                <p><?php echo __('Will activate the VR Glass support on mobile devices. This is the Beta release. So, if you face any issues with it, please contact us at support@rextheme.com', 'wpvr'); ?></p>
                            </span>
                        </li>

                        <li>
                            <h6><?php echo __("Convert any jpeg or png format image to webp on media upload:", "wpvr"); ?></h6>

                            <span class="wpvr-switcher">
                                <?php
                                if ($wpvr_webp_conversion == 'true') {
                                ?>
                                    <input id="wpvr_webp_conversion" type="checkbox" checked>
                                <?php
                                } else {
                                ?>
                                    <input id="wpvr_webp_conversion" type="checkbox" >
                                <?php
                                }
                                ?>
                                <label for="wpvr_webp_conversion"></label>
                            </span>

                            <span class="wpvr-tooltip">
                                <span class="icon">
                                    <img src="<?php echo WPVR_PLUGIN_DIR_URL . 'admin/icon/question-icon.png' ?>" alt="check">
                                </span>
                                <p><?php echo __('Will convert any jpeg or png image to webp format during media upload. will decrease the image size with no quality compromise and help the site to load faster.', 'wpvr'); ?></p>
                            </span>
                        </li>
                        <?php } ?>

                        <li>
                            <form class="wpvr-version" id="trigger-rollback">
                                <?php wp_nonce_field( 'wpvr_rollback','wpvr_rollback' ); ?>
                                <h6><?php _e('Select a Version to Rollback', 'wpvr'); ?></h6>
                                <select name="wpvr_version">
                                    <?php
                                    foreach ( $rollback_versions as $version ) {
                                        echo "<option value='".esc_attr( $version )."'>".esc_html($version)."</option>";
                                    }
                                    ?>
                                </select>


                                <input class="wpvr-btn" type="submit" value="Rollback">
                            </form>
                        </li>
                        
                    </ul>

                    <div class="save-progress-bar">
                        <div id="wpvr_role_progress" class="progress" style="display:none;">
                            <div class="indeterminate"></div>
                        </div>
                    </div>

                    <button class="btn wpvr-btn" type="submit" id="wpvr_role_submit"><?php echo __('Save', 'wpvr'); ?></button>
                </div>

                <?php if (!is_plugin_active('wpvr-pro/wpvr-pro.php')) { ?>
                    <div class="upgrade-pro">
                        <img src="<?php echo WPVR_PLUGIN_DIR_URL . 'admin/icon/wpvr-logo.png' ?>" alt="logo">
                        <a class="wpvr-btn" href="https://rextheme.com/wpvr/#pricing" target="_blank"><?php _e('Upgrade to Pro ', 'wpvr'); ?></a>
                    </div>
                <?php } ?>

            </div>
        </div>
    </div>

</div>

<?php

 if(is_plugin_active('divi-builder/divi-builder.php')){
     ?>
     <script>
         (function ($) {
         $(".rex-onboarding .block-wrapper:not(#tab1)").hide()
         $('.rex-onboarding li.tab a').first().addClass("active");
         $('.rex-onboarding li.tab').on('click', function(){
             var target_id = $(this).find("a").attr('href');
             $(".rex-onboarding li.tab a").removeClass('active');
             $(this).find("a").addClass('active');
             $(target_id).show();
             $(target_id).siblings('.block-wrapper').hide();
         })
         })(jQuery);
     </script>
     <?php
 }

?>