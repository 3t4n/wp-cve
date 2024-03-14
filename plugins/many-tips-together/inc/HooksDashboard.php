<?php
/**
 * Dashboard hooks
 *
 * @package AdminTweaks
 */

namespace ADTW;

class HooksDashboard {
	private $dashboard_widgets = array();

	/**
	 * Check options and dispatch hooks
	 * 
	 * @param  array $options
	 * @return void
	 */
	public function __construct() {

        # REMOVE WELCOME 
		$welcome = ADTW()->getop('dashboard_remove') 
				? in_array( 'welcome', ADTW()->getop('dashboard_remove') ) 
				: false;
		if( $welcome ){
			add_action( 
                'admin_head-index.php', 
                [$this, 'removeWelcome'] 
            );
        }

        # REMOVE OTHER WIDGETS
        if( ADTW()->getop('dashboard_remove') ) {
            add_action( 
                'wp_dashboard_setup', 
                [$this, 'removeWidgets'], 
                0 
            );
		}
        
        # WIDGET FOLDER SIZE
        if( ADTW()->getop('dashboard_folder_size') 
            and current_user_can( 'install_plugins' ) ) 
        {
            HooksDashboardWidgets::init( ADTW()->getop('dashboard_folder_size') );
        }
        
        # ADD WIDGETS
		if( ADTW()->getop('dashboard_add_widgets') && ADTW()->getop('dashboard_custom_widgets_enable') ) {
			add_action( 
                'wp_dashboard_setup', 
                [$this, 'addWidgets'], 
                0 
            );
        }
	}


	public function removeWelcome() {
		?>
		<style type="text/css">
			#welcome-panel {display:none}
		</style>
		<script type="text/javascript">
		jQuery(document).ready( function($) {
				$("label[for='wp_welcome_panel-hide']").remove();
			});     
		</script>
		<?php
	}
	

	public function removeWidgets() {
        $base = [
            'activity'    => 'normal',
            'plugins'     => 'normal',
            'primary'     => 'side',
            'quick_press' => 'side',
            'right_now'   => 'normal',
            'site_health' => 'normal',                 
        ];
        foreach( $base as $mb => $place ) {
            if( in_array( $mb, ADTW()->getop('dashboard_remove') ) ) {
                remove_meta_box( "dashboard_$mb", 'dashboard', $place );
            }
        }
	}
	
	public function addWidgets() {	
        $i = 0;
        $enabled = ADTW()->getop('dashboard_custom_widgets_enable');
        $titles   = ADTW()->getop('dashboard_custom_widgets_title');
        $roles   = ADTW()->getop('dashboard_custom_widgets_roles');
        
		foreach( $titles as $k => $v ) {
            $ucan = 
                empty( $roles[$k] ) 
                ? true 
                : ADTW()->current_user_has_role_array( $roles[$k] );
                
			if( $ucan && $enabled[$k] ) {
				$title = 
						empty( $titles[$k] ) 
						? '&nbsp;&nbsp;' 
						: stripslashes( $titles[$k] );

				wp_add_dashboard_widget( "mtt_widget_$i", $title, [$this, 'addDashboardContent'] );
			}
			$i++;
		}
	}


	public function addDashboardContent( $object, $box ) {
		$id = str_replace('mtt_widget_', '', $box['id'] );
        $content = ADTW()->getop('dashboard_custom_widgets_content');
		echo do_shortcode( stripslashes( $content[$id] ) );
	}
}