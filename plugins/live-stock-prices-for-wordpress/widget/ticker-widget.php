<?php
if(!class_exists('EOD_Stock_Prices_Plugin')) {
    class EOD_Stock_Prices_Widget extends WP_Widget
    {
        public static $widget_base_id = 'EOD_Stock_Prices_Widget';

        function __construct()
        {
            parent::__construct(
                self::$widget_base_id,
                __('EODHD Stock Prices Ticker', 'eod-stock-prices'),
                array('description' => __('-', 'eod-stock-prices'))
            );
        }

        /*
         * Display on the site
         */
        public function widget($args, $instance)
        {
            $template = $instance['type'] === 'realtime' ? 'realtime_ticker.php' : 'ticker.php';
            $widget_html = eod_load_template(
                "widget/template/ticker-widget.php",
                array(
                    '_this'              => $this,
                    'args'               => $args,
                    'type'               => 'eod_'.$instance['type'],
                    'display_name'       => $instance['name'],
                    'shortcode_template' => $template,
                    'title'              => apply_filters('widget_title', $instance['title']),
                    'list_of_targets'    => eod_get_ticker_list_from_widget_instance($instance),
                )
            );

            echo $widget_html;
        }

        /*
         * Display in admin panel
         */
        public function form($instance)
        {
            $display_options = get_option('eod_display_settings');
            $ndap = isset($display_options['ndap']) ? $display_options['ndap'] : EOD_DEFAULT_SETTINGS['ndap'];
            $widget_html = eod_load_template(
                "widget/template/ticker-widget-form.php",
                array(
                    '_this'             => $this,
                    'target_json'       => isset($instance['target']) ? $instance['target'] : '',
                    'widget_title'      => isset($instance['title']) ? $instance['title'] : '',
                    'type'              => isset($instance['type']) ? $instance['type'] : 'historical',
                    'name'              => isset($instance['name']) ? $instance['name'] : 'code',
                    'list_of_targets'   => eod_get_ticker_list_from_widget_instance($instance),
                    'ndap'              => $ndap,
                    'eod_options'       => get_option('eod_options'),
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
            $instance['type'] = (!empty($new_instance['type'])) ? strip_tags($new_instance['type']) : 'historical';
            $instance['name'] = (!empty($new_instance['name'])) ? strip_tags($new_instance['name']) : 'code';
            return $instance;
        }
    }
}