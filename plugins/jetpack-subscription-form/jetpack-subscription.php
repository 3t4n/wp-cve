<?php
/*
  Plugin Name: Jetpack Subscription Form
  Description: Customizable subscription UI for Jetpack
  Version: 1.1.3
  Author: Kiran Antony
  Author URI: http://www.kiranantony.com

  Copyright 2016 Kiran Antony | mail@kiranantony.com

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License, version 2, as
  published by the Free Software Foundation.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program; if not, write to the Free Software
  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

 */

class Jetpack_Subscriptions_Widget_Custom extends WP_Widget {

    function __construct() {
        $widget_ops = array('classname' => 'jetpack_subscription_custom_widget', 'description' => __('Add an email signup form to allow people to subscribe to your blog.', 'jetpack'));
        $control_ops = array('width' => 300);

        parent::__construct(
                'blog_subscription_custom_jetpack',
                /** This filter is documented in modules/widgets/facebook-likebox.php */ __('Blog Subscriptions Customized(Jetpack)', 'jetpack'), $widget_ops, $control_ops
        );
    }

    function widget($args, $instance) {
        if (
                (!defined('IS_WPCOM') || !IS_WPCOM ) &&
                /** This filter is already documented in modules/contact-form/grunion-contact-form.php */
                false === apply_filters('jetpack_auto_fill_logged_in_user', false)
        ) {
            $subscribe_email = '';
        } else {
            global $current_user;
            if (!empty($current_user->user_email)) {
                $subscribe_email = esc_attr($current_user->user_email);
            } else {
                $subscribe_email = '';
            }
        }
        $source = 'widget';
        $success_message = isset($instance['success_message']) ? stripslashes($instance['success_message']) : '';
        $widget_id = esc_attr(!empty($args['widget_id']) ? esc_attr($args['widget_id']) : mt_rand(450, 550) );
        $instance = wp_parse_args((array) $instance, $this->defaults());
        $subscribe_text = isset($instance['subscribe_text']) ? stripslashes($instance['subscribe_text']) : '';
        $subscribe_placeholder = isset($instance['subscribe_placeholder']) ? stripslashes($instance['subscribe_placeholder']) : '';
        $subscribe_text_class = isset($instance['subscribe_text_class']) ? stripslashes($instance['subscribe_text_class']) : '';
        $subscribe_button = isset($instance['subscribe_button']) ? stripslashes($instance['subscribe_button']) : '';
        $subscribe_submit_image = isset($instance['subscribe_submit_image']) ? stripslashes($instance['subscribe_submit_image']) : '';
        $subscrib_logo = isset($instance['subscrib_logo']) ? stripslashes($instance['subscrib_logo']) : '';
        $show_subscribers_total = (bool) $instance['show_subscribers_total'];
        $remove_p_tags_caza_jet = (bool) $instance['remove_p_tags_caza_jet'];
        $subscribe_submit_class = isset($instance['subscribe_submit_class']) ? stripslashes($instance['subscribe_submit_class']) : '';
        $subscribers_total = $this->fetch_subscriber_count();
        $widget_id = esc_attr(!empty($args['widget_id']) ? esc_attr($args['widget_id']) : mt_rand(450, 550) );

        if (!is_array($subscribers_total))
            $show_subscribers_total = FALSE;

        // Give the input element a unique ID
        /**
         * Filter the subscription form's ID prefix.
         *
         * @module subscriptions
         *
         * @since 2.7.0
         *
         * @param string subscribe-field Subscription form field prefix.
         * @param int $widget_id Widget ID.
         */
        // Give the input element a unique ID  
        $subscribe_field_id = apply_filters('subscribe_field_id', 'subscribe-field', $widget_id);

        // Enqueue the form's CSS
        wp_register_style('jetpack-subscriptions', plugins_url('subscriptions/subscriptions.css', __FILE__));
        wp_enqueue_style('jetpack-subscriptions');

        // Display the subscription form
        echo $args['before_widget'];
        // Only show the title if there actually is a title
        if (!empty($instance['title'])) {
            echo $args['before_title'] . esc_attr($instance['title']) . $args['after_title'] . "\n";
        }

        $referer = set_url_scheme('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);


        // Display any errors
        if (isset($_GET['subscribe'])) :
            switch ($_GET['subscribe']) :
                case 'invalid_email' :
                    ?>
                    <p class="error"><?php esc_html_e('The email you entered was invalid. Please check and try again.', 'jetpack'); ?></p>
                    <?php
                    break;
                case 'opted_out' :
                    ?>
                    <p class="error"><?php
                        printf(__('The email address has opted out of subscription emails. <br /> You can manage your preferences at <a href="%1$s" title="%2$s" target="_blank">subscribe.wordpress.com</a>', 'jetpack'), 'https://subscribe.wordpress.com/', __('Manage your email preferences.', 'jetpack')
                        );
                        ?>
                        <?php
                        break;
                    case 'already' :
                        ?>
                    <p class="error"><?php esc_html_e('You have already subscribed to this site. Please check your inbox.', 'jetpack'); ?></p>
                    <?php
                    break;
                case 'success' :
                    ?>
                    <div class="success"><?php echo wpautop(str_replace('[total-subscribers]', number_format_i18n($subscribers_total['value']), $success_message)); ?></div>
                    <?php
                    break;
                default :
                    ?>
                    <p class="error"><?php esc_html_e('There was an error when subscribing. Please try again.', 'jetpack'); ?></p>
                    <?php
                    break;
            endswitch;
        endif;

        // Display a subscribe form
        if (isset($_GET['subscribe']) && 'success' == $_GET['subscribe']) {
            ?>
        <?php } else {
            ?>

            <form action="#" method="post" accept-charset="utf-8" id="subscribe-blog-<?php echo $widget_id; ?>">
                <?php
                if (!isset($_GET['subscribe']) || 'success' != $_GET['subscribe']) {
                    if (!empty($subscrib_logo)) {
                        ?>
                        <div id="subscribe-logo">  <img src="<?php echo $subscrib_logo; ?>" /></div>
                        <?php
                    }
                }
                if (!isset($_GET['subscribe']) || 'success' != $_GET['subscribe']) {
                    ?><div id="subscribe-text"><?php echo wpautop(str_replace('[total-subscribers]', number_format_i18n($subscribers_total['value']), $subscribe_text)); ?></div><?php
                    }

                    if ($show_subscribers_total && 0 < $subscribers_total['value']) {
                        echo wpautop(sprintf(_n('Join %s other subscriber', 'Join %s other subscribers', $subscribers_total['value'], 'jetpack'), number_format_i18n($subscribers_total['value'])));
                    }
                    ?>
                    <?php
                    if (!isset($_GET['subscribe']) || 'success' != $_GET['subscribe']) {
                        ?>
                        <?php
                        if (!$remove_p_tags_caza_jet)
                            echo '<p id="subscribe-email">';
                        else
                            echo '<div id="subscribe-inner">';
                        ?>
						<input type="email" name="email" required="required" class="jetpack-required <?php echo esc_attr($subscribe_text_class); ?>" value="<?php echo esc_attr( $subscribe_email ); ?>" id="<?php echo esc_attr( $subscribe_field_id ) . '-' . esc_attr( $widget_id ); ?>" placeholder="<?php echo esc_attr( $subscribe_placeholder ); ?>" />
                    <?php
                    if (!$remove_p_tags_caza_jet)
                        echo '</p>';
                    ?>

                    <?php
                    if (!$remove_p_tags_caza_jet)
                        echo '<p id="subscribe-submit">';
                    ?>
                    <input type="hidden" name="action" value="subscribe" />
                    <input type="hidden" name="source" value="<?php echo esc_url($referer); ?>" />
                    <input type="hidden" name="sub-type" value="<?php echo esc_attr($source); ?>" />
                    <input type="hidden" name="redirect_fragment" value="<?php echo $widget_id; ?>" />
                    <?php
                    if (is_user_logged_in()) {
                        wp_nonce_field('blogsub_subscribe_' . get_current_blog_id(), '_wpnonce', false);
                    }
                    ?>
                    <button type="submit" class="<?php echo esc_attr($subscribe_submit_class); ?>" name="jetpack_subscriptions_widget" ><?php if (!empty($subscribe_submit_image)) { ?><img src="<?php echo $subscribe_submit_image; ?>" /><?php } else { ?><?php
                            echo esc_attr($subscribe_button);
                        }
                        ?></button>
                    <?php
                    if (!$remove_p_tags_caza_jet)
                        echo '</p>';
                    else
                        echo '</div>';
                    ?>
            <?php } ?>
            </form>

            <script>
                /*
                 Custom functionality for safari and IE
                 */
                (function (d) {
                    // In case the placeholder functionality is available we remove labels
                    if (('placeholder' in d.createElement('input'))) {
                        var label = d.querySelector('label[for=subscribe-field-<?php echo $widget_id; ?>]');
                        label.style.clip = 'rect(1px, 1px, 1px, 1px)';
                        label.style.position = 'absolute';
                        label.style.height = '1px';
                        label.style.width = '1px';
                        label.style.overflow = 'hidden';
                    }

                    // Make sure the email value is filled in before allowing submit
                    var form = d.getElementById('subscribe-blog-<?php echo $widget_id; ?>'),
                            input = d.getElementById('<?php echo esc_attr($subscribe_field_id) . '-' . esc_attr($widget_id); ?>'),
                            handler = function (event) {
                                if ('' === input.value) {
                                    input.focus();

                                    if (event.preventDefault) {
                                        event.preventDefault();
                                    }

                                    return false;
                                }
                            };

                    if (window.addEventListener) {
                        form.addEventListener('submit', handler, false);
                    } else {
                        form.attachEvent('onsubmit', handler);
                    }
                })(document);
            </script>
        <?php } ?>

        <?php
        echo "\n" . $args['after_widget'];
    }

    function increment_subscriber_count($current_subs_array = array()) {
        $current_subs_array['value'] ++;

        set_transient('wpcom_subscribers_total', $current_subs_array, 3600); // try to cache the result for at least 1 hour

        return $current_subs_array;
    }

    function fetch_subscriber_count() {
        $subs_count = get_transient('wpcom_subscribers_total');

        if (FALSE === $subs_count || 'failed' == $subs_count['status']) {
            Jetpack:: load_xml_rpc_client();

            $xml = new Jetpack_IXR_Client(array('user_id' => JETPACK_MASTER_USER,));

            $xml->query('jetpack.fetchSubscriberCount');

            if ($xml->isError()) { // if we get an error from .com, set the status to failed so that we will try again next time the data is requested
                $subs_count = array(
                    'status' => 'failed',
                    'code' => $xml->getErrorCode(),
                    'message' => $xml->getErrorMessage(),
                    'value' => ( isset($subs_count['value']) ) ? $subs_count['value'] : 0,
                );
            } else {
                $subs_count = array(
                    'status' => 'success',
                    'value' => $xml->getResponse(),
                );
            }

            set_transient('wpcom_subscribers_total', $subs_count, 3600); // try to cache the result for at least 1 hour
        }

        return $subs_count;
    }

    function update($new_instance, $old_instance) {
        $instance = $old_instance;

        $instance['title'] = wp_kses(stripslashes($new_instance['title']), array());
        $instance['subscrib_logo'] = wp_kses(stripslashes($new_instance['subscrib_logo']), array());
        $instance['subscribe_text'] = wp_filter_post_kses(stripslashes($new_instance['subscribe_text']));
        $instance['subscribe_placeholder'] = wp_filter_post_kses(stripslashes($new_instance['subscribe_placeholder']));
        $instance['subscribe_text_class'] = wp_filter_post_kses(stripslashes($new_instance['subscribe_text_class']));
        $instance['subscribe_submit_class'] = wp_filter_post_kses(stripslashes($new_instance['subscribe_submit_class']));
        $instance['subscribe_logged_in'] = wp_filter_post_kses(stripslashes($new_instance['subscribe_logged_in']));
        $instance['subscribe_button'] = wp_kses(stripslashes($new_instance['subscribe_button']), array());
        $instance['show_subscribers_total'] = isset($new_instance['show_subscribers_total']) && $new_instance['show_subscribers_total'];
        $instance['remove_p_tags_caza_jet'] = isset($new_instance['remove_p_tags_caza_jet']) && $new_instance['remove_p_tags_caza_jet'];
        $instance['success_message'] = wp_kses(stripslashes($new_instance['success_message']), array());
        $instance['subscribe_submit_image'] = wp_kses(stripslashes($new_instance['subscribe_submit_image']));

        return $instance;
    }

    public static function defaults() {
        return array(
            'title' => esc_html__('Subscribe to Blog via Email', 'jetpack'),
            'subscrib_logo' => '',
            'subscribe_text' => esc_html__('Enter your email address to subscribe to this blog and receive notifications of new posts by email.', 'jetpack'),
            'subscribe_placeholder' => esc_html__('Email Address', 'jetpack'),
            'subscribe_button' => esc_html__('Subscribe', 'jetpack'),
            'success_message' => esc_html__('Success! An email was just sent to confirm your subscription. Please find the email now and click activate to start subscribing.', 'jetpack'),
            'subscribe_logged_in' => esc_html__('Click to subscribe to this blog and receive notifications of new posts by email.', 'jetpack'),
            'show_subscribers_total' => true,
            'subscribe_text_class' => '',
            'subscribe_submit_class' => '',
            'subscribe_submit_image' => '',
            'remove_p_tags_caza_jet' => FALSE,
        );
    }

    function form($instance) {
        $instance = wp_parse_args((array) $instance, $this->defaults());

        $title = stripslashes($instance['title']);
        $subscrib_logo = stripslashes($instance['subscrib_logo']);
        $subscribe_text = stripslashes($instance['subscribe_text']);
        $subscribe_placeholder = stripslashes($instance['subscribe_placeholder']);
        $subscribe_text_class = stripslashes($instance['subscribe_text_class']);
        $subscribe_submit_class = stripslashes($instance['subscribe_submit_class']);
        $subscribe_submit_image = stripslashes($instance['subscribe_submit_image']);
        $subscribe_button = stripslashes($instance['subscribe_button']);
        $success_message = stripslashes($instance['success_message']);
        $show_subscribers_total = checked($instance['show_subscribers_total'], true, false);
        $remove_p_tags_caza_jet = checked($instance['remove_p_tags_caza_jet'], true, false);

        $subs_fetch = $this->fetch_subscriber_count();

        if ('failed' == $subs_fetch['status']) {
            printf('<div class="error inline"><p>' . __('%s: %s', 'jetpack') . '</p></div>', esc_html($subs_fetch['code']), esc_html($subs_fetch['message']));
        }
        $subscribers_total = number_format_i18n($subs_fetch['value']);
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>">
        <?php _e('Widget title:', 'jetpack'); ?>
                <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
            </label>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('subscrib_logo'); ?>">
        <?php _e('Widget Logo Url:', 'jetpack'); ?>
                <input class="widefat" id="<?php echo $this->get_field_id('subscrib_logo'); ?>" name="<?php echo $this->get_field_name('subscrib_logo'); ?>" type="text" value="<?php echo esc_attr($subscrib_logo); ?>" />
            </label>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('subscribe_text'); ?>">
        <?php _e('Optional text to display to your readers:', 'jetpack'); ?>
                <textarea style="width: 95%" id="<?php echo $this->get_field_id('subscribe_text'); ?>" name="<?php echo $this->get_field_name('subscribe_text'); ?>" type="text"><?php echo esc_html($subscribe_text); ?></textarea>
            </label>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('subscribe_placeholder'); ?>">
        <?php _e('Subscribe Placeholder:', 'jetpack'); ?>
                <input class="widefat" id="<?php echo $this->get_field_id('subscribe_placeholder'); ?>" name="<?php echo $this->get_field_name('subscribe_placeholder'); ?>" type="text" value="<?php echo esc_attr($subscribe_placeholder); ?>" />
            </label>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('subscribe_text_class'); ?>">
        <?php _e('Css Class For the Email Field:', 'jetpack'); ?>
                <input class="widefat" id="<?php echo $this->get_field_id('subscribe_text_class'); ?>" name="<?php echo $this->get_field_name('subscribe_text_class'); ?>" type="text" value="<?php echo esc_attr($subscribe_text_class); ?>" />
            </label>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('subscribe_button'); ?>">
        <?php _e('Subscribe Button:', 'jetpack'); ?>
                <input class="widefat" id="<?php echo $this->get_field_id('subscribe_button'); ?>" name="<?php echo $this->get_field_name('subscribe_button'); ?>" type="text" value="<?php echo esc_attr($subscribe_button); ?>" />
            </label>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('success_message'); ?>">
        <?php _e('Success Message Text:', 'jetpack'); ?>
                <textarea style="width: 95%" id="<?php echo $this->get_field_id('success_message'); ?>" name="<?php echo $this->get_field_name('success_message'); ?>" type="text"><?php echo esc_html($success_message); ?></textarea>
            </label>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('subscribe_submit_class'); ?>">
        <?php _e('Css Class For the Submit Button:', 'jetpack'); ?>
                <input class="widefat" id="<?php echo $this->get_field_id('subscribe_submit_class'); ?>" name="<?php echo $this->get_field_name('subscribe_submit_class'); ?>" type="text" value="<?php echo esc_attr($subscribe_submit_class); ?>" />
            </label>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('subscribe_submit_image'); ?>">
        <?php _e('Submit Button Image:', 'jetpack'); ?>
                <input class="widefat" id="<?php echo $this->get_field_id('subscribe_submit_image'); ?>" name="<?php echo $this->get_field_name('subscribe_submit_image'); ?>" type="text" value="<?php echo esc_attr($subscribe_submit_image); ?>" />
            </label>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('show_subscribers_total'); ?>">
                <input type="checkbox" id="<?php echo $this->get_field_id('show_subscribers_total'); ?>" name="<?php echo $this->get_field_name('show_subscribers_total'); ?>" value="1"<?php echo $show_subscribers_total; ?> />
        <?php echo esc_html(sprintf(_n('Show total number of subscribers? (%s subscriber)', 'Show total number of subscribers? (%s subscribers)', $subscribers_total, 'jetpack'), $subscribers_total)); ?>
            </label>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('remove_p_tags_caza_jet'); ?>">
                <input type="checkbox" id="<?php echo $this->get_field_id('remove_p_tags_caza_jet'); ?>" name="<?php echo $this->get_field_name('remove_p_tags_caza_jet'); ?>" value="1"<?php echo $remove_p_tags_caza_jet; ?> />

        <?php _e('Remove P tags Surronding The Input field And Submit Button:', 'jetpack'); ?>
            </label>
        </p>
        <?php
    }

}

//add_action( 'widgets_init', function(){
//     register_widget( 'Jetpack_Subscriptions_Widget_Custom' );
//});
// register Foo_Widget widget
function register_jtetpack_custom_widget() {
    register_widget('Jetpack_Subscriptions_Widget_Custom');
}

add_action('widgets_init', 'register_jtetpack_custom_widget');
