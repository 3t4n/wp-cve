<?php
namespace Shop_Ready\system\base;

/*
* Wordpress Default Plugin Action
* Will Show In Plugin list
*/
class Meta {

    /*
    * Register 
    * return void
    */
    public function register() {
     
        add_filter( 'plugin_row_meta', [ $this, 'plugin_row_meta' ], 10, 2 );
        add_filter( 'plugin_action_links_'.SHOP_READY_PLUGIN_BASE, [ $this ,'add_plugin_page_settings_link'] );
    }

    function add_plugin_page_settings_link( $links ) {
	
        $links[] = '<a href="' .
            admin_url( 'admin.php?page='.SHOP_READY_SETTING_PATH ) .
            '">' . esc_html__('Settings','shopready-elementor-addon') . '</a>';

        if( defined( 'SHOP_READY_PRO' ) ){
            return $links;
        }    
    
        $installed_plugins = array_keys( get_plugins() );
            if ( !in_array('shop-ready-pro/shop-ready-pro.php',$installed_plugins) ) {

            $links[] = '<a style="color: #325DF6; font-weight: bold;"  href="' .
            esc_url( SHOP_READY_DEMO_URL ) .
            '">' . esc_html__('Go Pro','shopready-elementor-addon') . '</a>';
        }
    
        return $links;
    
    }
    /*
    * Wp plugin plugin_action_links_ hook
    */
    public function plugin_row_meta( $plugin_meta, $plugin_file ) {

		if ( SHOP_READY_PLUGIN_BASE === $plugin_file ) {
            
			$row_meta = [
				'docs' => '<a target="__blank" href="https://quomodosoft.com/plugins-docs" aria-label="' . esc_attr__( 'View Documentation', 'shopready-elementor-addon' ) . '" target="_blank">' . esc_html__( 'Docs & FAQs', 'shopready-elementor-addon' ) . '</a>',
				'plugin-demos' => '<a target="__blank" href="http://plugins.quomodosoft.com/shopready/" aria-label="' . esc_attr__( 'View Demos', 'shopready-elementor-addon' ) . '" target="_blank">' . esc_html__( 'Demos', 'shopready-elementor-addon' ) . '</a>',
				'plugin-support' => '<a target="__blank" href="http://help.quomodosoft.com/" aria-label="' . esc_attr__( 'Get Support', 'shopready-elementor-addon' ) . '" target="_blank">' . esc_html__( 'Get Support', 'shopready-elementor-addon' ) . '</a>',
			];

			$plugin_meta = array_merge( $plugin_meta, $row_meta );
		}

		return $plugin_meta;
	}

    
}    