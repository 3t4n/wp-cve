<?php 
namespace Adminz\Admin;
use WP_Error;

class ADMINZ_Security extends Adminz
{
	public $options_group = "adminz_security";
	public $title = 'Security';
	static $slug  = 'adminz_security';
	static $options;
	function __construct() {
		
        $this::$options = get_option('adminz_security', []);
		add_filter( 'adminz_setting_tab', [$this,'register_tab']);
		add_action( 'adminz_tabs_html',[$this,'tab_html']);

		add_action(	'admin_init', [$this,'register_option_setting'] );
		add_action( 'init', array( $this, 'init' ) );
	}
	function register_tab($tabs) {
 		if(!$this->title) return;
 		$this->title = $this->get_icon_html('shield-virus').$this->title;
        $tabs[self::$slug] = array(
            'title' => $this->title,
            'slug' => self::$slug,
        );
        return $tabs;
    }
	function init(){				
		if($this->check_option('adminz_xmlrpc_enabled',false,"on")){		
			add_filter( 'xmlrpc_enabled', '__return_false' );
		}
		if($this->check_option('adminz_disable_x_pingback',false,"on")){				
			add_filter( 'wp_headers', [$this,'disable_x_pingback'] );
		}
		if($this->check_option('adminz_disable_json',false,"on")){				
			add_filter( 'rest_authentication_errors', function( $result ) {
			    if ( ! empty( $result ) ) {
			        return $result;
			    }
			    if ( ! is_user_logged_in() ) {
			        return new WP_Error( 'rest_not_logged_in', 'AdministratorZ alert: Need logged in ', array( 'status' => 401 ) );
			    }
			    return $result;
			});
			add_filter( 'json_enabled', '__return_false' );
			add_filter( 'json_jsonp_enabled', '__return_false' );
		}	
		if($this->check_option('adminz_disable_file_edit',false,"on")){			
			define( 'DISALLOW_FILE_EDIT', true );
			define('DISALLOW_FILE_MODS',true);
		}

	}
	function disable_x_pingback( $headers ) {
	    unset( $headers['X-Pingback'] );
		return $headers;
	}
	
	function tab_html(){
		if(!isset($_GET['tab']) or $_GET['tab'] !== self::$slug) return;
		?>
		<form method="post" action="options.php">
	        <?php 
	        settings_fields($this->options_group);
	        do_settings_sections($this->options_group);	        
	        ?>
	        <table class="form-table">
	        	<tr valign="top">
	        		<th><h3>Enable functions</h3></th>
	        		<td></td>
	        	</tr>
	        	<tr valign="top">	        		
	        		<th>
	        			Disable use XML-RPC
	        		</th>
	        		<td>
	        			<div>
	        				<?php
	        				$checked = "";
	        				if($this->check_option('adminz_xmlrpc_enabled',false,"on")){		        				
	        					$checked = "checked";
	        				}
	        				?>
	        				<input type="checkbox" <?php echo esc_attr($checked); ?>  name="adminz_security[adminz_xmlrpc_enabled]"/>
	        			</div>
	        		</td>
	        	</tr>
	        	<tr valign="top">	        		
	        		<th>
	        			Disable X-Pingback to header
	        		</th>
	        		<td>
	        			<div>
	        				<?php
	        				$checked = "";
	        				if($this->check_option('adminz_disable_x_pingback',false,"on")){		        				
	        					$checked = "checked";
	        				}
	        				?>
	        				<input type="checkbox" <?php echo esc_attr($checked); ?>  name="adminz_security[adminz_disable_x_pingback]"/>
	        			</div>
	        		</td>
	        	</tr>
	        	<tr valign="top">	        		
	        		<th>
	        			Disable REST API (wp-json)
	        		</th>
	        		<td>
	        			<div>
	        				<?php
	        				$checked = "";
	        				if($this->check_option('adminz_disable_json',false,"on")){		        				
	        					$checked = "checked";
	        				}
	        				?>
	        				<input type="checkbox" <?php echo esc_attr($checked); ?>  name="adminz_security[adminz_disable_json]"/>
	        			</div>
	        		</td>
	        	</tr>
	        	<tr valign="top">	        		
	        		<th>
	        			Disable file edit
	        		</th>
	        		<td>
	        			<div>
	        				<?php
	        				$checked = "";
	        				if($this->check_option('adminz_disable_file_edit',false,"on")){		        				
	        					$checked = "checked";
	        				}
	        				?>
	        				<input type="checkbox" <?php echo esc_attr($checked); ?>  name="adminz_security[adminz_disable_file_edit]"/>
	        			</div>
	        		</td>
	        	</tr>
	        </table>
	        <?php submit_button(); ?>
        </form>
		<?php
	}
	function register_option_setting(){
		register_setting( $this->options_group, 'adminz_security');		
	}
}