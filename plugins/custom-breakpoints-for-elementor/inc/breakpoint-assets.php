<?php
namespace MasterCustomBreakPoint\Inc;
use Elementor\Core\Responsive\Responsive;
use MasterCustomBreakPoint\Master_Custom_Breakpoint;

defined( 'ABSPATH' ) || exit;

class JLTMA_Master_Custom_Breakpoint_Assets{

    private static $_instance = null;

    public function __construct(){

        add_action('admin_print_scripts', [$this, 'jltma_mcb_admin_js']);

        // enqueue scripts
        add_action( 'admin_enqueue_scripts', [$this, 'jltma_mcb_admin_enqueue_scripts'] );

		add_action('admin_head',[ $this, 'jltma_mcb_admin_styles' ]);
    }


    // Custom Admin Styles for Master Custom Breakpoint page
    public function jltma_mcb_admin_styles(){
    	$screen = get_current_screen();
        if($screen->id == 'master-addons_page_master-custom-breakpoints'){
        	$style = '#wpwrap{ background: #efeff5;
					margin-left: auto; margin-right: auto;
        	}
            .jltma-disabled{
                  pointer-events: none;
                  opacity: 0.4;
            }';
        	echo '<style>' . $style . '</style>';
        }

    }

    // Declare Variable for Rest API
    public function jltma_mcb_admin_js(){
        echo "<script type='text/javascript'>\n";
        echo $this->jltma_common_js();
        echo "\n</script>";
    }

    public function jltma_common_js(){
        ob_start(); ?>
            var masteraddons = { resturl: '<?php echo get_rest_url() . 'masteraddons/v2/'; ?>', }
        <?php
        $output = ob_get_contents();
        ob_end_clean();
        return $output;
    }


    public function jltma_mcb_admin_enqueue_scripts(){

        $screen = get_current_screen();

        if ( is_plugin_active( 'master-addons/master-elementor-addons.php' ) ) {
            $screen->id == 'master-addons_page_master-custom-breakpoints';
        } else{
            $screen->id == 'toplevel_page_master-custom-breakpoints';
        }


        if($screen->id){

            // CSS
            wp_enqueue_style( 'master-cbp-admin', JLTMA_MCB_PLUGIN_URL . 'assets/css/master-cbp-admin.css');

            // JS
            wp_enqueue_script( 'master-cbp-admin', JLTMA_MCB_PLUGIN_URL . 'assets/js/master-cbp-admin.js', array( 'jquery'), true, JLTMA_MCB_VERSION );
            wp_enqueue_script('jquery-ui-sortable');

            // Localize Scripts
            $jltma_mcb_localize_data = array(
                'plugin_url'    => JLTMA_MCB_PLUGIN_URL,
                'ajaxurl'       => admin_url( 'admin-ajax.php' ),
                'resturl'       => get_rest_url() . 'masteraddons/v2/'
            );
            wp_localize_script( 'master-cbp-admin', 'masteraddons', $jltma_mcb_localize_data );
        }
    }


    public static function get_instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

}

JLTMA_Master_Custom_Breakpoint_Assets::get_instance();