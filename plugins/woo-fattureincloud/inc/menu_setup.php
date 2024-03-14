<?php

/**
 *
 * @ Better separated voice to check quicker what every single value in add_menu_page is
 *
 */
function woo_fattureincloud_setup_menu()
{
    $parent_slug = 'woocommerce';
    $page_title  = 'WooCommerce Fattureincloud Admin Page';
    $menu_title  = 'Fattureincloud';
    $capability  = 'manage_woocommerce';
    $menu_slug   = 'woo-fattureincloud';
    $function    = 'woo_fattureincloud_setup_page_display';

    add_submenu_page($parent_slug, $page_title, $menu_title, $capability, $menu_slug, $function);
}

function page_tabs($current = 'ordine') 
{
    if ( is_admin() ) {

    $tabs = array(
    'ordine'   => __('Ordine', 'woo-fattureincloud'),
    'impostazioni'  => __('Impostazioni', 'woo-fattureincloud'),
    'fatture' => __('Fatture', 'woo-fattureincloud'),
    'connetti' => __('Connetti', 'woo-fattureincloud')
    );
    $html = '<h2 class="nav-tab-wrapper">';
    foreach ($tabs as $tab => $name) {
        $class = ( $tab == $current ) ? 'nav-tab-active' : '';
        $html .= '<a class="nav-tab ' . $class . '" href="?page=woo-fattureincloud&tab=' . $tab . '">' . $name . '</a>';
    }
    $html .= '</h2>';
    echo $html;
    
    }

}

/**
* Include the new Navigation Bar the Admin page.
*/

function add_wfic_to_woocommerce_navigation_bar() {

    if ( is_admin() ) {

    if ( function_exists( 'wc_admin_connect_page' ) ) {

        wc_admin_connect_page(
            
                        array(
					        'id'        => 'woo-fattureincloud',
					        'screen_id' => 'woocommerce_page_woo-fattureincloud',
                            'title'     => __( 'Fattureincloud', 'woo-fattureincloud' ),
           
                            'path'      => add_query_arg(
                                            array(
                                                'page' => 'woo-fattureincloud',
                                                'tab'  => 'ordine',
                                            ),
                                            
                                            'admin.php' ),
                            
	        			)
        );
        
    }

    }
}
