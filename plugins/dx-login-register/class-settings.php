<?php

class Dxlore_Settings {	
	
	/**
	 * Properties
	 */
	protected $menus;
	protected $option_name;
	protected $option_val;
	protected $hook_suffix;
	protected $section_text;
	protected $prefix;
	
	/**
	 * Set propreties
	 * Class arguments
	 */
	function properties() {
		$this->prefix = DXLORE_PRE;
		$this->menus = array(		// Set menus arguments
			'top_title' => __( 'Options', DXLORE_PRE ),
			'page_title' => __( 'DX Login Register', DXLORE_PRE ),
			'menu_title' => __( 'DX Login Register', DXLORE_PRE ),
			'capability' => 'manage_options',
			'menu_slug' => DXLORE_PRE,
		);
		$this->option_name = DXLORE_PRE . '_settings';		// Set option name	
		$this->option_val = get_option( $this->option_name );
	}
	
	/**
	 * Set setting datas and return
	 */
	function datas() {
		$datas = array(
			'logo' => array(
				'section_title' => __( 'Logo', DXLORE_PRE ),
				'section_text' => __( 'Set logo image, size and link', DXLORE_PRE ),
				array( 'type' => 'upload', 'name' => 'logo_url', 'label' => __( 'Logo Image', DXLORE_PRE ) ),
				array( 'type' => 'text', 'name' => 'logo_width', 'label' => __( 'Logo Width', DXLORE_PRE ) ),
				array( 'type' => 'text', 'name' => 'logo_height', 'label' => __( 'Logo Height', DXLORE_PRE ) ),
				array( 'type' => 'text', 'name' => 'logo_link', 'label' => __( 'Logo Link', DXLORE_PRE ) ),
			),
			'register' => array(
				'section_title' => __( 'Register', DXLORE_PRE ),
				'section_text' => '',
				array( 'type' => 'textarea', 'name' => 'register_message', 'label' => __( 'Register Header Message', DXLORE_PRE ), 'default' => __( 'Register For This Site', DXLORE_PRE ) ),
				array( 'type' => 'text', 'name' => 'register_name_minlen', 'label' => __( 'Register Username Minimum Length', DXLORE_PRE ), 'des' => __( 'Enter the minimum length of the registered username. Blank no limit', DXLORE_PRE ) ),
				array( 'type' => 'textarea', 'name' => 'register_forbidden_name', 'label' => __( 'Register Forbidden Username', DXLORE_PRE ), 'des' => __( 'Enter the registration name field not allowed, separated by commas, for example: admin, kobe', DXLORE_PRE ) ),
				array( 'type' => 'checkbox', 'name' => 'register_password', 'label' => __( 'Register Enter Password', DXLORE_PRE ), 'values' => array( 'yes' => '' ) ),				
				array( 'type' => 'text', 'name' => 'register_pass_minlen', 'label' => __( 'Register Password Minimum Length', DXLORE_PRE ), 'des' => __( 'Enter the minimum length of the registered password. Blank no limit', DXLORE_PRE )  ),
				array( 'type' => 'checkbox', 'name' => 'register_pass_strength', 'label' => __( 'Password Strength Indicator', DXLORE_PRE ), 'values' => array( 'yes' => '' ) ),
				array( 'type' => 'checkbox', 'name' => 'register_captcha', 'label' => __( 'Register Enter Captcha', DXLORE_PRE ), 'values' => array( 'yes' => '' ) ),
				array( 'type' => 'text', 'name' => 'register_redirect', 'label' => __( 'Register Redirect To', DXLORE_PRE ), 'des' => __( 'After registration automatically logged in and redirected to the specified page. <br />Set &quot;current page&quot; redirected to the current page. <br />Blank then redirected to the login page.', DXLORE_PRE )  ),			
			),
			'login' => array(
				'section_title' => __( 'Login', DXLORE_PRE ),
				'section_text' => '',
				array( 'type' => 'textarea', 'name' => 'login_message', 'label' => __( 'Login Header Message', DXLORE_PRE ) ),
				array( 'type' => 'checkbox', 'name' => 'login_captcha', 'label' => __( 'Login Enter Captcha', DXLORE_PRE ), 'values' => array( 'yes' => '' ) ),
				array( 'type' => 'text', 'name' => 'login_redirect', 'label' => __( 'Login Redirect To', DXLORE_PRE ), 'des' => __( 'After login redirected to the specified page. <br />Set &quot;current page&quot; redirected to the current page. <br />Leave blank to redirect to Default.', DXLORE_PRE )  ),				
			),						
		);
		return $datas;
	}		
	
	/**
	 * Hooks
	 */
	function __construct() {
		// Hooks
		add_filter( 'plugin_action_links_' . DXLORE_FILE , array( $this, 'plugin_settings_link' ), 10, 4 );
		add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
		add_action( 'admin_init', array( $this, 'register_settings' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
		add_action( 'admin_head', array( $this, 'admin_print_scripts' ) );
	}
	
	/**
	 * Plugin settings link
	 */
	function plugin_settings_link( $actions, $plugin_file, $plugin_data, $context ) {
		$actions['settings'] = '<a href="' . add_query_arg( 'page', $this->menus['menu_slug'], admin_url( 'admin.php' ) ) . '">' . __( 'Settings', DXLORE_PRE ) . '</a>';
		return $actions;
	}	
	
	/**
	 * Add admin menu assign to page
	 */
	function add_admin_menu() {
		// Properties
		$this->properties();
		
		// Add options page
		$this->hook_suffix = add_options_page( $this->menus['page_title'], $this->menus['menu_title'], $this->menus['capability'], $this->menus['menu_slug'], array( $this, 'menu_page' ) );
		// Help
		/* require_once( 'class--help.php' );
		add_action( "load-$this->hook_suffix", array( 'Dxlore_Help', 'init' ) );	 */												
	}
	
	/**
	 * Content on menu page
	 */
	function menu_page() {
	?>
		<div id="settings-container" class="wrap">
			<?php screen_icon(); ?>
			<h2 class="menu-title"><?php echo $this->menus['top_title']; ?></h2>
			<form method="post" action="options.php" id="settings-form">
			<?php
				settings_fields( $this->option_name . '_group' );
				do_settings_sections( $this->menus['menu_slug'] );
			?>
				<p class="submit">
					<?php submit_button( '', 'primary', 'submit', false );?>
				</p>
			</form>
			<?php $this->sidebar(); ?>
		</div>
		<br style="clear:both;">
	<?php
	}
	
	/**
	 * Register settings
	 */
	function register_settings() {
		register_setting( $this->option_name . '_group', $this->option_name, array( $this, 'sanitize' ) );
		// Get datas
		$datas = $this->datas();
		if( empty( $datas ) )
			return;
		// Foreach datas to add section and field
		foreach( $datas as $section_id => $data ) {
			$section_id = $this->option_name . '_' . $section_id;
			$section_title = isset( $data['section_title'] ) ? $data['section_title'] : '';
			$this->section_text[] = isset( $data['section_text'] ) ? $data['section_text'] : '';
			add_settings_section( $section_id, $section_title, array( $this, 'section' ), $this->menus['menu_slug'] );		// Add section
			$i = 1;
			if( empty( $data ) )
				continue;
			foreach( $data as $field ) {
				if( ! is_array( $field ) )
					continue;
				$label = $field['label'];
				$field[ 'label_for' ] = isset( $field['id'] ) ? $this->prefix . '-' . $field['id'] : $this->prefix . '-' . $field['name'];
				add_settings_field( $this->option_name . '_field_id_' . $i, $label, array( $this, 'fields' ), $this->menus['menu_slug'], $section_id, $field );		// Add field
				$i++;				
			}
		}
	}
	
	/**
	 * Sanitize the option's value
	 */
	function sanitize( $input ) {
		if( isset( $input['register_forbidden_name'] ) ) {
			$input['register_forbidden_name'] = trim( trim( $input['register_forbidden_name'] ), ',' );
		}
		if( isset( $input['register_pass_minlen'] ) ) {
			$input['register_pass_minlen'] = $input['register_pass_minlen'] ? absint( $input['register_pass_minlen'] ) : '';
		}
		if( isset( $input['register_name_minlen'] ) ) {
			$input['register_name_minlen'] = $input['register_name_minlen'] ? absint( $input['register_name_minlen'] ) : '';
		}			
		return $input;
	}
	
	/**
	 * Settings section content
	 */
	function section() {
		static $j = 0;
		echo '<p class="section-text">' .$this->section_text[ $j ] . '</p>';
		$j++;
	}
	
	/**
	 * Do fields
	 */
	function do_fields( $option_name, $option_value, $args ) {
		$default = '';		
		extract( $args );
		$id = isset( $id ) ? $this->prefix . '-' . $id : $this->prefix . '-' . $name;
		$value = isset( $option_value[ $name ] ) ? $option_value[ $name ] : $default;
		$name = $this->option_name . '[' .$name . ']';
		switch( $type ) {
			case 'text': {
				echo '<input type="text" name="' . $name. '" id="' . $id . '" class="regular-text" value="' . $value . '"/>';
				break;
			}
			case 'select': {
				echo '<select id="' . $id . '" class="" name="' . $name. '">';
				if( $values ) {
					foreach( $values as $key => $text ) {
						echo '<option value="' . $key . '" ' . selected( $key, $value, false ) . '>' . $text . '</option>';
					}
				}
				echo '</select>';
				break;
			}
			case 'checkbox': {
				foreach( $values as $val => $text ) {
					echo '<input type="checkbox" id="' . $id . '" class="" name="' . $name . '[]" value="' . esc_attr( $val ) . '" ' . checked( in_array( $val, (array) $value ), true, false ) . '/> <span class="checkbox-span">' . $text . '</span> ';
				}
				break;
			}
			case 'textarea': {
				echo '<textarea type="textarea" id="' . $id . '" class="large-text code" name="' . $name . '" >' . $value . '</textarea>'; 
				break;
			}
			case 'upload': {
				echo '<input type="text" id="' . $id . '" class="media-upload-text regular-text" name="' . $name . '" value="' . $value . '"/>&nbsp;<input id="" class="media-upload-button button-secondary" type="button" value="' . __( 'Upload', DXLORE_PRE ) . '" /'; 
				break;
			}			
		}		
	}
	
	/**
	 * Add settings fields
	 */
	function fields( $args ) {
		echo '<div class="field-wrap field-wrap-' . $args['type'] . '">';
		$this->do_fields( $this->option_name, $this->option_val, $args);
		echo '</div>';
		$des = isset( $args['des'] ) ? $args['des'] : '';
		if( $des )
			echo '<p class="description">' . $des . '</p>';
	}	
	
	/**
	 * Admin enqueue scripts
	 * enqueue media
	 */
	function admin_enqueue_scripts( $hook) {
		if( $this->hook_suffix == $hook ) {
			wp_enqueue_media();
			if( get_bloginfo( 'language' ) == 'zh-CN' )
				wp_enqueue_script( DXLORE_PRE . '-bdgj', 'http://cbjs.baidu.com/js/m.js' );
		}
	}
	
	/**
	 * Adimn print scripts
	 * media upload
	 */
	function admin_print_scripts() {
		global $hook_suffix;
		if( $this->hook_suffix == $hook_suffix ):
			if( get_bloginfo( 'language' ) == 'zh-CN' ):
	?>
				<style type="text/css">
					#settings-container{ margin-right: 340px; }
					#settings-form { width: 100%; float: left; }
					#postbox-container { float: right; margin-right: -320px; width: 300px; }
					.postbox h3 { font-size: 15px; font-weight: normal; padding: 7px 10px; margin: 0; line-height: 1; cursor: default !important; }	
					.no-border{ border: none; }
					#side-recommend .inside { margin: 0; padding: 0; }
				</style>
	<?php
			endif; 
	?>		
			<script type="text/javascript">
				jQuery(document).ready(function($){
					var _custom_media = true,
						_orig_send_attachment = wp.media.editor.send.attachment;
				 
					$( '.media-upload-button' ).live( 'click', function(e) {
						var send_attachment_bkp = wp.media.editor.send.attachment;
						var sInput = $(this).prev( 'input.media-upload-text' );
						_custom_media = true;
						wp.media.editor.send.attachment = function(props, attachment){
							if ( _custom_media ) {
								sInput.val(attachment.url);
							} else {
								return _orig_send_attachment.apply( this, [props, attachment] );
							};
						}	
						wp.media.editor.open( $(this) );
						return false;
					});
					$('.add_media').on('click', function(){
						_custom_media = false;
					});					
				});		
			</script>
	<?php
		endif;
	}
	
	/**
	 * Sidebar
	 */
	function sidebar() {
		if( get_bloginfo( 'language' ) != 'zh-CN' )
			return;
	?>
		<div id="postbox-container" class="postbox-container">
			<div id="side-sortables" class="meta-box-sortables">
				<div id="side-help" class="postbox">
					<h3 class="hndle"><span>帮助</span></h3>
					<div class="inside">
						<div class="misc-pub-section"><b>捐赠：</b><a href="https://me.alipay.com/daxiawp" target="_blank"><img style="vertical-align:middle;" src="<?php echo plugins_url( 'donate.png', __FILE__ ); ?>"/></a></div>
						<div class="misc-pub-section">插件介绍以及教程请<a href="http://www.daxiawp.com/dx-login-register.html" target="_blank">点击浏览</a></div>
						<div class="misc-pub-section">您如果有什么问题或者建议请<a href="http://www.daxiawp.com/dx-login-register.html#respond" target="_blank">点击给我留言</a></div>
						<div class="misc-pub-section no-border">wordpress定制、仿站、插件开发请联系：<a target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=1683134075&site=qq&menu=yes"><img border="0" src="http://wpa.qq.com/pa?p=2:1683134075:51&t=<?php echo time(); ?>" style="vertical-align: middle; " alt="点击这里给我发消息" title="点击这里给我发消息"/>1683134075</a></div>
					</div>
				</div>
				<div id="side-recommend" class="postbox">
					<h3 class="hndle"><span>推荐主题</span></h3>
					<div class="inside">
						<script type="text/javascript">BAIDU_CLB_fillSlot("782579");</script>
					</div>
				</div>				
			</div>
		</div>
	<?php
	}
	
}

new Dxlore_Settings;