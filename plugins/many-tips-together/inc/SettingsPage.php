<?php 

namespace ADTW;

class SettingsPage {

    protected function __construct() { }

    public static function init() 
    {
        global $adtw_option;
        add_action(
            'admin_menu', 
            [__CLASS__, 'adminMenus'], 
            9999
        );

        add_action(
            'load-settings_page_admintweaks', 
            function(){
                add_action(
                    'admin_bar_menu', 
                    [__CLASS__, 'adminBarMenus'], 
                    999999
                );
            }
        );

        # ADTW()->debug("Admin Tweaks v".AdminTweaks::VERSION);
        
        add_filter(
            'plugin_action_links_' . ADTW_BASE,
            [__CLASS__, 'settings_plugin_link'],
            10, 2
        );
        
        /* Redux CSS */
        add_action(
            'redux/page/' . $adtw_option . '/enqueue',
            [__CLASS__, 'add_panel_css']
        );

        /* Redux Help Tab */
        add_action(
            'redux/page/' . $adtw_option . '/load',
            [__CLASS__, 'redux_load']
        );

        # CRON PLUGINS STATS
        /*add_filter(
            'cron_schedules',
            [__CLASS__, 'cronSchedules']
        );
        if( !wp_next_scheduled('mttScheduleHook', [false]) )
        {
            add_action('init', function (){
                wp_schedule_event(time(), '1week', [__CLASS__, 'scheduleHook'], [false]);
            });
        }*/
        # TEST
        ##add_action('admin_head-plugins.php', [__CLASS__, 'scheduleHook']);
    }

    public static function cronSchedules ($schedules){
        if(!isset($schedules["1week"])){
            $schedules["1week"] = array(
                'interval' => 60*60*24*7,
                'display' => __('Every 7 days'));
        }
        return $schedules;
    }

    /**
     * [name] => Admin Tweaks
     * [slug] => many-tips-together
     * [version] => 3.0.5
     * [author] => <a href="http://brasofilo.com/">Rodolfo Buaiz</a>
     * [author_profile] => https://profiles.wordpress.org/brasofilo/
     * [requires] => 5.0
     * [tested] => 6.2
     * [requires_php] => 
     * [requires_plugins] => Array()
     * [compatibility] => Array()
     * [rating] => 94
     * [ratings] => Array ( [5] => 28, [4] => 3, [3] => 0, [2] => 1, [1] => 1)
     * [num_ratings] => 33
     * [support_threads] => 0
     * [support_threads_resolved] => 0
     * [downloaded] => 32521
     * [last_updated] => 2023-04-08 10:35pm GMT
     * [added] => 2011-09-14
     * [homepage] => http://wordpress.org/plugins/many-tips-together
     *
     * @return void
     */
    public static function scheduleHook(){
        $plugins = ['many-tips-together', 'code-snippets', 'slate-admin-theme', 'query-monitor', 'wp-accessibility', 'pojo-accessibility', 'accessibility-checker'];
        foreach ( $plugins as $plugin ) 
        {
            $api = wp_remote_get( 
                "https://api.wordpress.org/plugins/info/1.0/$plugin.json",
                [
                    'timeout' => 120, 
                    'httpversion' => '1.1' 
                ] 
            );
            if ( $api['response']['code'] == '200' )
            {
                $data = json_decode( $api['body'], true );
                //echo '<pre>' . print_r( $data, true ) . '</pre>';
                $rating = self::calcRating($data['ratings'], $data['num_ratings']);
            }
        }

    }

    private static function calcRating ($ratings, $num_ratings)
    {
        $total = 0;
        for ( $i=1; $i<6; $i++ )
        {
            $total += $i * $ratings[$i];
        }
        return round($total/$num_ratings, 1);
    }
    
    public static function redux_load() 
    {
        get_current_screen()->remove_help_tab('redux-hint-tab');
    }

    public static function adminMenus() 
    {
        ADTW()->setSupport();
    }

    public static function adminBarMenus($bar) 
    {
        ADTW()->setSupportBar($bar);
    }

    public static function add_panel_css() 
    {
        $css = '/assets/adtw.css';
        wp_register_style(
            'redux-custom-css',
            ADTW_URL . $css,
            [ 'redux-admin-css' ], 
            ADTW()->cache($css),
            'all'
        );  
        wp_enqueue_style('redux-custom-css');
    }


	/**
	 * Add link to settings in Plugins list page
	 *
	 * @wp-hook plugin_action_links
	 * @return Plugin link
	 */
	public static function settings_plugin_link( $links, $file ) 
    {
        $links[] = sprintf(
            '<a href="%s">%s</a>',
                admin_url( 'admin.php?page=admintweaks' ),
                __( 'Settings' )
        );
		return $links;
	}

}