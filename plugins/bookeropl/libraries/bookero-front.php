<?php
class BookeroFrontPage
{
    /**
     * Holds the values to be used in the fields callbacks
     */
    private $options;

    /**
     * Start up
     */
    public function __construct()
    {
        add_action( 'wp_footer', array( $this, 'show_plugin' ) );
        add_shortcode( 'bookero_form', array( $this, 'bookero_form' ) );
    }

    /**
     * Show plugin
     */
    public function show_plugin()
    {
        $this->options = get_option( 'bookero_options' );

        $plugin_id = false;
        if (!empty($this->options['bookero_api_key'])){
            $plugin_id = Bookero::checkApiKey($this->options['bookero_api_key']);
        }

        if(!isset($this->options['show_plugin']))
            $this->options['show_plugin'] = 1;
        if(!isset($this->options['plugin_type']))
            $this->options['plugin_type'] = 1;
        if(!isset($this->options['plugin_css']))
            $this->options['plugin_css'] = 1;
        if(!isset($this->options['plugin_html_id']))
            $this->options['plugin_html_id'] = 'bookero';

        if($this->options['show_plugin'] == 1 && $plugin_id !== false) {

            if ($this->options['plugin_type'] == 1) {
                $container = '';
                $plugin_type = 'sticky';
            }
            elseif ($this->options['plugin_type'] == 3) {
                $container = $this->options['plugin_html_id'];
                $plugin_type = 'full';
            }elseif ($this->options['plugin_type'] == 4) {
                $container = $this->options['plugin_html_id'];
                $plugin_type = 'calendar';
            } else {
                $container = $this->options['plugin_html_id'];
                $plugin_type = 'standard';
            }

            $plugin_css = $this->options['plugin_css'] == 1 ? 'true' : 'false';

            $lang = 'pl';
            $wp_lang = get_locale(); // ZWRACA KOD JEZYKA NP pl_PL
            $wp_lang = explode('_', $wp_lang);
            $lang = strtolower($wp_lang[0]);
            if(!in_array($lang, array('pl', 'en', 'ru', 'de', 'it'))){
                $lang = 'pl';
            }

            $plugin_html = $this->getPlugin($plugin_id, $container, $plugin_type, $plugin_css, $lang);

            echo $plugin_html;
        }
    }

    /**
     * Shortcode for bookero form DIV
     *
     * @return string
     */
    public static function bookero_form($atts){
        $atts = array_change_key_case((array)$atts, CASE_LOWER);
        $params = array();
        if(isset($atts['service']) && !isset($atts['select_service'])){
            $params['use_service_id'] = 'use_service_id: '.(int) $atts['service'];
        }
        if(isset($atts['category']) && !isset($atts['select_category'])){
            $params['use_service_category_id'] = 'use_service_category_id: '.(int) $atts['category'];
        }
        if(isset($atts['select_service']) && !isset($atts['service'])){
            $params['select_service_id'] = 'select_service_id: '.(int) $atts['select_service'];
        }
        if(isset($atts['select_category']) && !isset($atts['category'])){
            $params['select_service_category_id'] = 'select_service_category_id: '.(int) $atts['select_category'];
        }
        if(isset($atts['worker_id'])){
            $params['use_worker_id'] = 'use_worker_id: '.(int) $atts['worker_id'];
        }
        if(isset($atts['hide_worker']) && isset($atts['worker_id'])){
            $params['hide_worker_info'] = 'hide_worker_info: '.(int) $atts['hide_worker'];
        }

        $custom_config = '{}';
        if(!empty($params)){
            $custom_config = '{'.implode(', ', $params).'}';
        }

        return '<script type="text/javascript">var bookero_custom_config = '.$custom_config.';</script><div id="bookero"></div>';
    }

    /**
     * Get new version of plugin
     *
     * @param $plugin_id
     * @param $container
     * @param $plugin_type
     * @param $plugin_css
     *
     * @return string
     */
    public function getPlugin($plugin_id, $container, $plugin_type, $plugin_css, $lang){
        $plugin_html = "<script type=\"text/javascript\">
			var bookero_config = {
                    id: '" . $plugin_id . "',
                    container: '" . $container . "',
                    type: '" . $plugin_type . "',
                    position: '',
                    plugin_css: " . $plugin_css . ",
                    lang: '" . $lang . "',
                    custom_config: typeof bookero_custom_config !== 'undefined' ? bookero_custom_config : {}
               };
    
              (function() {
                var d = document, s = d.createElement('script');
                s.src = 'https://cdn.bookero.pl/plugin/v2/js/bookero-compiled.js';
                d.body.appendChild(s);
              })();
			</script>";

        return $plugin_html;
    }

}