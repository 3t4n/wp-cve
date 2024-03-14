<?php

class MantisAdsWidget extends WP_Widget
{
    public function __construct()
    {
        parent::__construct('mantis_ads', 'Mantis Advertisement');
    }

    public function widget($args, $instance)
    {
        $args = array_merge($args, wp_parse_args($instance, array(
            'zone' => null
        )));

        if ($args['zone']) {
            if (!has_action('wp_footer', 'mantis_publisher_footer')) {
                add_action('wp_footer', 'mantis_publisher_footer', 20);
            }

            $attrs = array(
                'data-mantis-zone' => $args['zone']
            );

            $class = '';
            $style = '';

            if(isset($args['mobileFloat']) && $args['mobileFloat']){
                $class = "mantis-float mantis-float-$args[mobileFloat]";

                wp_enqueue_script('jquery');
            }

            if(isset($args['center']) && $args['center']){
                $style = 'style="text-align:center"';
            }

            $attrs = implode(' ', array_map('mantis_attr_map', $attrs, array_keys($attrs)));

            do_action('mantis_before_widget');

            echo $args['before_widget'];

            echo "<div class='mantis-display $class' $style><div $attrs></div></div>";

            echo $args['after_widget'];

            do_action('mantis_after_widget');
        }
    }

    public function form($instance)
    {
        $zones = mantis_ad_zones();

        require(MANTIS_ROOT . '/html/publisher/widgetform.php');
    }

    public function update($new, $old)
    {
        return $new;
    }
}

function mantis_attr_map($v, $k){
    return "$k='$v'";
}

function mantis_ad_zones()
{
    $site = get_option('mantis_site_id');

    $zones = wp_cache_get('mantis_cache_zones');

    if ($zones === false || count($zones) === 0) {
        $zones = array();

        try {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "https://direct.mantisadnetwork.com/wordpress/zones/$site");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $data = curl_exec($ch);
            curl_close($ch);

            $zones = json_decode($data);

            if(!$zones){
                $zones = array();
            }
        } catch (Exception $ex) {
            error_log($ex);
        }

        if(count($zones) > 0){
            wp_cache_set('mantis_cache_zones', $zones, '', 10);
        }
    }

    return $zones;
}

function mantis_ad_widget()
{
    if (get_option('mantis_site_id')) {
        register_widget('MantisAdsWidget');
    }
}

add_action('widgets_init', 'mantis_ad_widget');
