<?php
/**
 * Facebook Widget Class
 */
if ( !defined('ABSPATH')) exit;

class themeidol_facebook_widget extends WP_Widget {

    /** constructor */
    function __construct() {
        parent::__construct(
                'themeidol_facebook_id', __('Themeidol-Facebook Page Like Widget', 'themeidol-all-widget')
        );
        // Refreshing the widget's cached output with each new post
        add_action( 'save_post',    array( $this, 'flush_widget_cache' ) );
        add_action( 'deleted_post', array( $this, 'flush_widget_cache' ) );
        add_action( 'delete_attachment', array( $this, 'flush_group_cache' ) );
        add_action( 'switch_theme', array( $this, 'flush_widget_cache' ) ); 
    }

    /** @see WP_Widget::widget */
    function widget($args, $instance) {

        global $app_id, $select_lng;
         $cache    = (array) wp_cache_get( 'themeidol-facebookpage', 'widget' );

         if(!is_array($cache)) $cache = array();
      
         if(isset($cache[$args['widget_id']])){
            echo $cache[$args['widget_id']];
            return;
         }
        ob_start();
        extract($args);

        $title = apply_filters('widget_title', esc_attr($instance['title']));
        $app_id = esc_attr($instance['app_id']);
        $fb_url = esc_url($instance['fb_url']);
        $width = esc_attr($instance['width']);
        $height = esc_attr($instance['height']);
        $data_small_header = isset($instance['data_small_header']) && $instance['data_small_header'] != '' ? 'true' : 'false';
        $data_adapt_container_width = isset($instance['data_adapt_container_width']) && $instance['data_adapt_container_width'] != '' ? 'true' : 'false';
        if($data_adapt_container_width)
        {
           $width = (int)767; 
        }
        $data_hide_cover = isset($instance['data_hide_cover']) && $instance['data_hide_cover'] != '' ? 'true' : 'false';
        $data_show_facepile = isset($instance['data_show_facepile']) && $instance['data_show_facepile'] != '' ? 'true' : 'false';
        $data_show_posts = isset($instance['data_show_posts']) && $instance['data_show_posts'] != '' ? 'true' : 'false';
        $select_lng = esc_attr($instance['select_lng']);
        if (strpos($before_widget, 'widget ') !== false) {
            $before_widget = preg_replace('/widget /', "idol-widget ", $before_widget, 1);
        }
        echo $before_widget;
        if ($title)
            echo $before_title . $title . $after_title;

        wp_register_script('milapfbwidgetscript', THEMEIDOL_WIDGET_JS_URL . 'fb.js', array('jquery'));
        wp_enqueue_script('milapfbwidgetscript');
        $local_variables = array('app_id' => $app_id, 'select_lng' => $select_lng);
        wp_localize_script('milapfbwidgetscript', 'milapfbwidgetvars', $local_variables);
        echo '<center><div class="loader"><img src="' . THEMEIDOL_WIDGET_IMAGES_URL . 'loader.gif" /></div></center>';
        echo '<div id="fb-root"></div>
        <div class="fb-page" data-href="' . $fb_url . '" data-width="' . $width . '" data-height="' . $height . '" data-small-header="' . $data_small_header . '" data-adapt-container-width="' . $data_adapt_container_width . '" data-hide-cover="' . $data_hide_cover . '" data-show-facepile="' . $data_show_facepile . '" data-show-posts="' . $data_show_posts . '"></div>';
        echo $after_widget;
        $widget_string = ob_get_flush();
        $cache[$args['widget_id']] = $widget_string;
        wp_cache_add('themeidol-facebookpage', $cache, 'widget');
    }

    /** @see WP_Widget::update */
    function update($new_instance, $old_instance) {

        $instance = $old_instance;
        $instance = array('data_small_header' => 'false', 'data_adapt_container_width' => 'false', 'data_hide_cover' => 'false', 'data_show_facepile' => 'false', 'data_show_posts' => 'true');
        foreach ($instance as $field => $val) {
            if (isset($new_instance[$field]))
                $instance[$field] = 'true';
        }
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['app_id'] = strip_tags($new_instance['app_id']);
        $instance['fb_url'] = strip_tags($new_instance['fb_url']);
        $instance['width'] = strip_tags($new_instance['width']);
        $instance['height'] = strip_tags($new_instance['height']);
        $instance['data_small_header'] = strip_tags($new_instance['data_small_header']);
        $instance['data_adapt_container_width'] = strip_tags($new_instance['data_adapt_container_width']);
        $instance['data_hide_cover'] = strip_tags($new_instance['data_hide_cover']);
        $instance['data_show_facepile'] = strip_tags($new_instance['data_show_facepile']);
        $instance['data_show_posts'] = strip_tags($new_instance['data_show_posts']);
        $instance['select_lng'] = strip_tags($new_instance['select_lng']);
        return $instance;
    }

    public function flush_widget_cache() {
            wp_cache_delete( 'themeidol-facebookpage', 'widget' );
    }

    /** @see WP_Widget::form */
    function form($instance) {

        /**
         * Set Default Value for widget form
         */
        $defaults = array('title' => 'Like Us On Facebook', 'app_id' => '503595753002055', 'fb_url' => 'http://facebook.com/WordPress', 'width' => '767', 'height' => '350', 'data_small_header' => 'false', 'select_lng' => 'en_US', 'data_small_header' => 'false', 'data_adapt_container_width' => 'false', 'data_hide_cover' => 'false', 'data_show_facepile' => 'on', 'data_show_posts' => 'true');
        $instance = wp_parse_args((array) $instance, $defaults);
        $title = esc_attr($instance['title']);
        $app_id = isset($instance['app_id']) ? esc_attr($instance['app_id']) : "503595753002055";
        $fb_url = isset($instance['fb_url']) ? esc_url($instance['fb_url']) : "http://www.facebook.com/wordpress";
        $width = esc_attr($instance['width']);
        $height = esc_attr($instance['height']);
        $data_adapt_container_width = esc_attr($instance['data_adapt_container_width']);
        $data_hide_cover = esc_attr($instance['data_hide_cover']);
        $data_show_facepile = esc_attr($instance['data_show_facepile']);
        $data_show_posts = esc_attr($instance['data_show_posts']);
        $select_lng = esc_attr($instance['select_lng']);
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'themeidol-all-widget'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('app_id'); ?>"><?php _e('Facebook Application Id:', 'themeidol-all-widget'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('app_id'); ?>" name="<?php echo $this->get_field_name('app_id'); ?>" type="text" value="<?php echo $app_id ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('fb_url'); ?>"><?php _e('Facebook Page Url:', 'themeidol-all-widget'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('fb_url'); ?>" name="<?php echo $this->get_field_name('fb_url'); ?>" type="text" value="<?php echo $fb_url; ?>" />
            <small>
                <?php _e('Works with only'); ?>
                <a href="http://www.facebook.com/help/?faq=174987089221178" target="_blank">
                    <?php _e('Valid Facebook Pages'); ?>
                </a>
            </small>
        </p>
        <p>
            <input class="checkbox" type="checkbox" <?php checked($instance['data_show_posts'], "on") ?> id="<?php echo $this->get_field_id('data_show_posts'); ?>" name="<?php echo $this->get_field_name('data_show_posts'); ?>" />
            <label for="<?php echo $this->get_field_id('data_show_posts'); ?>"><?php _e('Show posts from the Page timeline', 'themeidol-all-widget'); ?></label>
        </p>
        <p>
            <input class="checkbox" type="checkbox" <?php checked($instance['data_hide_cover'], "on") ?> id="<?php echo $this->get_field_id('data_hide_cover'); ?>" name="<?php echo $this->get_field_name('data_hide_cover'); ?>" />
            <label for="<?php echo $this->get_field_id('data_hide_cover'); ?>"><?php _e('Hide Cover', 'themeidol-all-widget'); ?></label>
        </p>
        <p>
            <input class="checkbox" type="checkbox" <?php checked($instance['data_show_facepile'], "on") ?> id="<?php echo $this->get_field_id('data_show_facepile'); ?>" name="<?php echo $this->get_field_name('data_show_facepile'); ?>" />
            <label for="<?php echo $this->get_field_id('data_show_facepile'); ?>"><?php _e('Show profile photos when friends like this', 'themeidol-all-widget'); ?></label>
        </p>
        <p>
            <input class="checkbox" type="checkbox" <?php checked($instance['data_small_header'], "on") ?> id="<?php echo $this->get_field_id('data_small_header'); ?>" name="<?php echo $this->get_field_name('data_small_header'); ?>" />
            <label for="<?php echo $this->get_field_id('data_small_header'); ?>"><?php _e('Show Small Header', 'themeidol-all-widget'); ?></label>
        </p>
        <p>
            <input onclick="shoWidth();" class="checkbox" type="checkbox" <?php checked($instance['data_adapt_container_width'], "on") ?> id="<?php echo $this->get_field_id('data_adapt_container_width'); ?>" name="<?php echo $this->get_field_name('data_adapt_container_width'); ?>" />
            <label for="<?php echo $this->get_field_id('data_adapt_container_width'); ?>"><?php _e('Adapt To Plugin Container Width', 'themeidol-all-widget'); ?></label>
        </p>
        <p class="width_option <?php echo $instance['data_adapt_container_width'] == 'on' ? 'hideme' : ''; ?>">
            <label for="<?php echo $this->get_field_id('width'); ?>"><?php _e('Set Width:', 'themeidol-all-widget'); ?></label>
            <input size="5" id="<?php echo $this->get_field_id('width'); ?>" name="<?php echo $this->get_field_name('width'); ?>" type="text" value="<?php echo $width; ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('height'); ?>"><?php _e('Set Height:', 'themeidol-all-widget'); ?></label>
            <input size="5" id="<?php echo $this->get_field_id('height'); ?>" name="<?php echo $this->get_field_name('height'); ?>" type="text" value="<?php echo $height; ?>" />
        </p>
        <?php
        $filename = "http://www.facebook.com/translations/FacebookLocales.xml";
        if (ini_get('allow_url_fopen')) {
            $langs = file_get_contents($filename);
            $xmlcont = new SimpleXMLElement($langs);
            ?>
            <p>
                <label for="<?php echo $this->get_field_id('select_lng'); ?>"><?php _e('Language:', 'themeidol-all-widget'); ?></label>
                <select name="<?php echo $this->get_field_name('select_lng'); ?>" id="<?php echo $this->get_field_id('select_lng'); ?>" style="width: 95%;">
                    <?php
                    if (!empty($xmlcont)) {
                        foreach ($xmlcont as $languages) {
                            $lan_title = $languages->englishName;
                            $representation = $languages[0]->codes->code->standard->representation[0];
                            ?>
                            <option value="<?php echo $representation; ?>"<?php selected($instance['select_lng'], $representation); ?>><?php _e($lan_title . " => " . $representation); ?></option>
                            <?php
                        }
                    }
                    ?>
                </select>
            </p>
            <?php
        } elseif (function_exists('curl_version')) {
            if (!function_exists('file_get_contents_curl_mine')) {

                function file_get_contents_curl_mine($url) {
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_HEADER, 0);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($ch, CURLOPT_URL, $url);
                    $data = curl_exec($ch);
                    curl_close($ch);
                    return $data;
                }

            }
            if (!function_exists('xmlstring2array_mine')) {

                function xmlstring2array_mine($string) {
                    $xml = simplexml_load_string($string, 'SimpleXMLElement', LIBXML_NOCDATA);
                    $array = json_decode(json_encode($xml), TRUE);
                    return $array;
                }

            }
            $langs = file_get_contents_curl_mine($filename);
            $xmlcont = xmlstring2array_mine($langs);
            $xmlcont = $xmlcont['locale'];
            ?>   
            <p>
                <label for="<?php echo $this->get_field_id('select_lng'); ?>"><?php _e('Language:', 'themeidol-all-widget'); ?></label>
                <select name="<?php echo $this->get_field_name('select_lng'); ?>" id="<?php echo $this->get_field_id('select_lng'); ?>">
                    <?php
                    if (!empty($xmlcont)) {
                        foreach ($xmlcont as $languages) {
                            $title = $languages['englishName'];
                            $representation = $languages['codes']['code']['standard']['representation'];
                            ?>
                            <option value="<?php echo $representation; ?>"<?php selected($instance['select_lng'], $representation); ?>><?php _e($title . " => " . $representation); ?></option>
                            <?php
                        }
                    }
                    ?>
                </select>
            </p>    
            <?php
        } else {
            ?>
            <p>
                <label for="<?php echo $this->get_field_id('select_lng'); ?>"><?php _e('Language:', 'themeidol-all-widget'); ?></label>
                <b>English</b> <br />(Your PHP configuration does not allow to read <a href="http://www.facebook.com/translations/FacebookLocales.xml" target="_blank">this</a> file.
                To unable language option, enable curl extension OR allow_url_fopen in your server configuration.)
            </p>
            <?php
        }
        ?>

        <script type="text/javascript">
            function shoWidth() {
                if (jQuery(".width_option").hasClass('hideme'))
                    jQuery(".width_option").removeClass('hideme');
                else
                    jQuery(".width_option").addClass('hideme');
            }
        </script>
        <style type="text/css">.hideme {display: none;}</style>
        <?php
    }

}
add_action( 'widgets_init', create_function( '', 'return register_widget("themeidol_facebook_widget");' ) );