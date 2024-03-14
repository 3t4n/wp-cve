<?php
// if called directly, abort.
if (!defined('WPINC')) { die; }

class raysgrid_Portfolio_ShortCode {
    
    function __construct() {
        add_action( 'vc_after_init', array( $this, 'it_vc_shortcodes' ) );
        include_once plugin_dir_path( __FILE__ ) . "vc_portfolio.php";
    }
    
    public function it_vc_shortcodes(){
        vc_lean_map( RSGD_PFX , null, plugin_dir_path( __FILE__ ). 'shortcode-portfolio.php' );
        
        if ( ! function_exists( 'it_dropdown_grids' ) ) {
            function it_dropdown_grids() {
                $dbObj = new raysgrid_Tables();
                
                $tbl = $dbObj->rsgd_select();
                $arr = array();
                
                foreach ($tbl[0] as $sel) {
                    $arr[$sel->title] = $sel->alias;
                }
                $smenu = array(__('-- Select Grid --',RSGD_SLUG) => '');                
                return $smenu + $arr;
            }
        }
    }
    
}

new raysgrid_Portfolio_ShortCode();