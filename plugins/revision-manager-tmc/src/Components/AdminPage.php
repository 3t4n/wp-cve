<?php
namespace tmc\revisionmanager\src\Components;

use shellpress\v1_4_0\src\Shared\Components\IComponent;
use tmc\revisionmanager\src\App;
use WP_Error;
use WP_Post_Type;
use WP_REST_Request;
use WP_REST_Response;
use WP_Role;
use WP_Screen;

/**
 * @author jakubkuranda@gmail.com
 * Date: 28.01.2022
 * Time: 13:19
 */
class AdminPage extends IComponent {
	
	protected function onSetUp() {
		
		add_action( 'admin_menu', function(){
			
			add_submenu_page(
				'options-general.php',
				'Revision Manager TMC',
				'Revision Manager TMC',
				'manage_options',
				$this::s()->getPrefix( '_settings' ),
				array( $this, '_a_adminPage' )
			);
			
		} );
		
		add_action( 'admin_enqueue_scripts', function(){
		
			$screen = get_current_screen();
			
			if( $screen && $screen->base === 'settings_page_rm_tmc_settings' ){
				
				wp_enqueue_editor();
    
//				wp_enqueue_script( 'vue3', 'https://unpkg.com/vue@next', array(), $this::s()->getFullPluginVersion(), true );
				wp_enqueue_script( 'rm_tmc_AdminPageOptions', $this::s()->getUrl( 'assets/js/AdminPageOptions/dist/revision-manager-tmc.umd.min.js' ), array( 'wp-tinymce' ), $this::s()->getFullPluginVersion(), true );
				wp_enqueue_style( 'rm_tmc_AdminPageOptions', $this::s()->getUrl( 'assets/js/AdminPageOptions/dist/revision-manager-tmc.css' ), array(), $this::s()->getFullPluginVersion() );

                wp_add_inline_script( 'rm_tmc_AdminPageOptions', sprintf( 'const rm_tmc_settings = %1$s;', json_encode( $this->s()->options->get() ) ), 'before' );
                wp_add_inline_script( 'rm_tmc_AdminPageOptions', sprintf( 'const rm_tmc_fieldsData = %1$s;', json_encode( $this->getFieldsData() ) ), 'before' );
                
			}
		
		} );
        
        add_action( 'rest_api_init', function(){
            
            register_rest_route( 'rm_tmc/v1', '/options/save', array(
                'methods'               =>  'POST',
                'callback'              =>  array( $this, '_a_ajaxSaveOptions' ),
                'permission_callback'   =>  function(){
                    return current_user_can( 'manage_options' );
                }
            ) );
            
            register_rest_route( 'rm_tmc/v1', '/options/load', array(
                'methods'               =>  'POST',
                'callback'              =>  array( $this, '_a_ajaxLoadOptions' ),
                'permission_callback'   =>  function(){
                    return current_user_can( 'manage_options' );
                }
            ) );
            
        } );
        
        add_filter( 'plugin_action_links_revision-manager-tmc/revision-manager-tmc.php', function( $actions, $plugin_file ){
        
            $actions['settings'] = sprintf(
                '<a href="%1$s">%2$s</a>',
                get_admin_url( null, 'options-general.php?page=rm_tmc_settings' ),
                __( 'Settings' )
            );
            
            return $actions;
            
        }, 10, 2 );
		
	}
	
	public function getFieldsData(){
  
		return array(
			'postTypes'                 =>  $this->getAllPostTypes(),
            'capabilities'              =>  $this->getAllCapabilitiesNames(),
            'roles'                     =>  $this->getAllRolesNames(),
            'quickEmailTestActionName'  =>  Notifications::SEND_QUICK_EMAIL_TEST_ACTION_NAME,
            'ajaxUrl'                   =>  get_admin_url( null, 'admin-ajax.php' ),
            'restApiLoadOptionsUrl'     =>  rest_url( 'rm_tmc/v1/options/load' ),
            'restApiSaveOptionsUrl'     =>  rest_url( 'rm_tmc/v1/options/save' ),
            'restApiActivateCode'       =>  rest_url( 'rm_tmc/v1/jetplugs/a' ),
            'restApiDeactivateCode'     =>  rest_url( 'rm_tmc/v1/jetplugs/d' ),
            'pluginUrl'                 =>  $this::s()->getUrl(),
            'currentUserEmail'          =>  wp_get_current_user()->user_email,
            'wpnonce'                   =>  wp_create_nonce( 'wp_rest' ),
            'hasCode'                   =>  (bool) App::i()->jetPlugs->getCode(),
            'isCodeActive'           =>  App::i()->jetPlugs->isCodeActive()
		);
		
	}
	
	/**
	 * @return array
	 */
    protected function getAllPostTypes() {
	
        $results = array();
        
	    $postTypes = get_post_types( array(
		    'show_ui'       =>  true
	    ), 'objects' );
        
        $exclude = array(
            'wp_block',
            'attachment'
        );
	
	    foreach( $postTypes as $postType ){
            if( ! in_array( $postType->name, $exclude ) ){
	            $results[$postType->name] = $postType->label;
            }
	    }
        
        return $results;
     
    }
	
	/**
	 * @return string[]
	 */
	protected function getAllCapabilitiesNames() {
		
		$role               = get_role( 'administrator' );    //  Administrator is used for listing all possible capabilities
		$capabilitiesNames  = array();
		
		if( $role ){
			foreach( $role->capabilities as $key => $value ) {
				$capabilitiesNames[$key] = $key;
			}
		}
		
		return $capabilitiesNames;
		
	}
	
	/**
	 * @return string[]
	 */
	protected function getAllRolesNames() {
		
		$roles = wp_roles()->role_objects;
		$names = wp_roles()->role_names;
		
		$rolesNames         = array();
		$rolesNames['']     = __( '- Do not send -', 'rm_tmc' );
		
		foreach( $roles as $role ) {    /** @var WP_Role $role */
			$rolesNames[ $role->name ] = array_key_exists( $role->name, $names ) ? $names[ $role->name ] : $role->name;
		}
		
		return $rolesNames;
		
	}
 
	public function _a_adminPage(){
		?>
		
		<div class="wrap">
			<h1>Revision Manager TMC</h1>
			<div id="rm_tmc-app">Loading ...</div>
		</div>
		
		<?php
	}
	
	/**
	 * @param WP_REST_Request $restRequest
	 *
	 * @return WP_REST_Response
	 */
    public function _a_ajaxSaveOptions( $restRequest ){
    
        $response = new WP_REST_Response();
        $settings = $restRequest->get_param( 'settings' );
        
        if( $settings ){
	        App::s()->options->set( '', $settings );
	        App::s()->options->flush();
	
	        $response->set_data( 'Save completed!' );
	        $response->set_status( 200 );
        } else {
	        $response->set_data( 'Received empty settings.' );
	        $response->set_status( 501 );
        }
        
        return $response;
    
    }
    
	/**
	 * @param WP_REST_Request $restRequest
	 *
	 * @return WP_REST_Response
	 */
    public function _a_ajaxLoadOptions( $restRequest ){
    
        $response = new WP_REST_Response();
        $settings = $this::s()->options->get();
        
        $response->set_data( array( 'settings' => $settings ) );
        
        return $response;
    
    }
 
}