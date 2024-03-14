<?php
if(!class_exists('EOD_Stock_Prices_Plugin')) {
    class EOD_Fundamental_Widget extends WP_Widget
    {
        public static $widget_base_id = 'EOD_Fundamental_Widget';

        function __construct()
        {
            parent::__construct(
                self::$widget_base_id,
                __('EODHD Fundamental Data', 'eod-stock-prices'),
                array('description' => __('-', 'eod-stock-prices'))
            );
        }

        /*
         * Display on the site
         */
        public function widget($args, $instance)
        {
            echo eod_load_template(
                "widget/template/fundamental-widget.php",
                array(
                    'fd'            => new EOD_Fundamental_Data( $instance['preset'] ),
                    '_this'         => $this,
                    'args'          => $args,
                    'target'        => $instance['target'],
                    'title'         => apply_filters('widget_title', $instance['title'])
                )
            );
        }

        /*
         * Display in admin panel
         */
        public function form($instance)
        {
            $fd_presets = get_posts([
                'post_type' => 'fundamental-data',
                'post_status' => 'publish',
                'numberposts' => -1
            ]);

            $selected_preset_type = '';
            if($instance['preset'])
                $selected_preset_type = str_replace('_', ' ', get_post_meta( intval($instance['preset']),'_fd_type', true ) );

            $widget_html = eod_load_template(
                "widget/template/fundamental-widget-form.php",
                array(
                    '_this'                 => $this,
                    'fd_presets'            => $fd_presets,
                    'selected_preset_type'  => $selected_preset_type,
                    'target'                => isset($instance['target']) ? $instance['target'] : '',
                    'preset'                => isset($instance['preset']) ? $instance['preset'] : '',
                    'widget_title'          => isset($instance['title']) ? $instance['title'] : '',
                    'eod_options'           => get_option('eod_options'),
                )
            );

            echo $widget_html;
        }

        /*
         * Update widget data
         */
        public function update($new_instance, $old_instance)
        {
            $instance = array();
            $instance['title'] = (!empty($new_instance['title'])) ? strip_tags($new_instance['title']) : '';
            $instance['target'] = (!empty($new_instance['target'])) ? strip_tags($new_instance['target']) : '';
            $instance['preset'] = (!empty($new_instance['preset'])) ? $new_instance['preset'] : '';
            return $instance;
        }
    }
}