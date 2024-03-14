<?php
if(!class_exists('EOD_Stock_Prices_Plugin')) {
    class EOD_Converter_Widget extends WP_Widget
    {
        public static $widget_base_id = 'EOD_Converter_Widget';

        function __construct()
        {
            parent::__construct(
                self::$widget_base_id,
                __('EODHD Currency Converter', 'eod-stock-prices'),
                array('description' => __('-', 'eod-stock-prices'))
            );
        }

        /*
         * Display on the site
         */
        public function widget($args, $instance)
        {
            $widget_html = eod_load_template(
                "widget/template/converter-widget.php",
                array(
                    '_this'              => $this,
                    'args'               => $args,
                    'props'              => array(
                        'target'            => $instance['first_currency'].':'.$instance['second_currency'],
                        'whitelist'         => $instance['whitelist'],
                        'amount'            => $instance['amount'],
                        'changeable'        => $instance['changeable'],
                    ),
                    'title'              => apply_filters('widget_title', $instance['title'])
                )
            );

            echo $widget_html;
        }

        /*
         * Display in admin panel
         */
        public function form($instance)
        {
            global $eod_api;
            $widget_html = eod_load_template(
                "widget/template/converter-widget-form.php",
                array(
                    '_this'             => $this,
                    'first_currency'    => isset($instance['first_currency']) ? $instance['first_currency'] : '',
                    'second_currency'   => isset($instance['second_currency']) ? $instance['second_currency'] : '',
                    'whitelist'         => isset($instance['whitelist']) ? $instance['whitelist'] : '',
                    'amount'            => isset($instance['amount']) ? $instance['amount'] : 1,
                    'changeable'        => isset($instance['changeable']) ? $instance['changeable'] : '1',
                    'widget_title'      => isset($instance['title']) ? $instance['title'] : '',
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
            $instance['title']           = (!empty($new_instance['title'])) ? strip_tags($new_instance['title']) : '';
            $instance['first_currency']  = (!empty($new_instance['first_currency'])) ? strip_tags($new_instance['first_currency']) : '';
            $instance['second_currency'] = (!empty($new_instance['second_currency'])) ? strip_tags($new_instance['second_currency']) : '';
            $instance['whitelist']       = (!empty($new_instance['whitelist'])) ? strip_tags($new_instance['whitelist']) : '';
            $instance['amount']          = (!empty($new_instance['amount'])) ? $new_instance['amount'] : 1;
            $instance['changeable']      = strip_tags($new_instance['changeable']);
            return $instance;
        }
    }
}