<?php

class Dxlore_Custom_Form {
	
	/**
	 * Hooks
	 */
	function __construct() {
		add_action( 'login_enqueue_scripts', array( $this, 'pass_strength_enqueue_script' ) );
		add_action( 'login_head', array( $this, 'head_scripts' ) );
		add_action( 'login_headerurl', array( $this, 'login_headerurl' ) );
		add_action( 'login_headertitle', array( $this, 'login_headertitle' ) );
		add_filter( 'login_message', array( $this, 'loginhead_message' ) );
		add_action( 'register_form', array( $this, 'register_password' ), 10 );
		add_action( 'register_form', array( $this, 'register_captcha' ), 11 );
		add_action( 'login_form', array( $this, 'login_captcha' ) );
	}
	
	/**
	 * Password strongth
	 */
	function pass_strength_enqueue_script() {
		$this->settings = get_option( DXLORE_PRE . '_settings' );
		$this->pass_strength = isset( $this->settings['register_pass_strength'][0] ) ? $this->settings['register_pass_strength'][0] : '';
		if( 'yes' == $this->pass_strength && isset( $_REQUEST['action'] ) && 'register' == $_REQUEST['action'] ) {
			wp_enqueue_script('password-strength-meter');
			wp_enqueue_script( DXLORE_PRE . '_pass_strength', plugins_url( 'password-strength.js', __FILE__ ), '', '', true );
		}
	}
	
	/**
	 * Print head scripts
	 * Custom logo
	 */
	function head_scripts() {
		$logo_url = isset( $this->settings['logo_url'] ) ? $this->settings['logo_url'] : '';
		$logo_width = isset( $this->settings['logo_width'] ) ? $this->settings['logo_width'] : '';
		$logo_height = isset( $this->settings['logo_height'] ) ? $this->settings['logo_height'] : '';
		
		// Logo
		$style = '<style type="text/css">';
		if( $logo_url )
			$style .= ".login h1 a { background-image: url($logo_url); }";
			
		
		// Size
		if( $logo_width && $logo_height ) {
			$height = $logo_height + 4;
			$style .= ".login h1 a { background-size: {$logo_width}px {$logo_height}px; height:{$height}px;}";
			
			// @since 1.0.1
			if( version_compare( $GLOBALS['wp_version'], '3.8', '>=' ) ) {
				$style .= ".login h1 a { width:{$logo_width}px; height: {$logo_height}px;}";
			}
		}
		
		$style .= '</style>';
		
		echo $style;
	}
	
	/**
	 * Login header url
	 */
	function login_headerurl( $url ) {
		$logo_link = isset( $this->settings['logo_link'] ) ? $this->settings['logo_link'] : '';
		if( $logo_link )
			$url = $logo_link;
		return $url;
	}
	
	/**
	 * Login header title
	 */
	function login_headertitle( $title ) {
		$logo_link = isset( $this->settings['logo_link'] ) ? $this->settings['logo_link'] : '';
		if( $logo_link )
			$title = get_bloginfo( 'name' );
		return $title;
	}
	
	/**
	 * Register head message
	 */
	function loginhead_message( $message ) {
		$register_message = isset( $this->settings['register_message'] ) ? $this->settings['register_message'] : __( 'Register For This Site', DXLORE_PRE );
		$login_message = isset( $this->settings['login_message'] ) ? $this->settings['login_message'] : '';
		$action = isset( $_REQUEST['action'] ) ? $_REQUEST['action'] : 'login';
		switch( $action ) {
			case 'login': $message = $login_message ? '<p class="message register">' . $login_message . '</p>' : ''; break;
			case 'register': $message = $register_message ? '<p class="message register">' . $register_message . '</p>' : ''; break;
		}
		return $message;
	}		
	
	/**
	 * Add custom paddword form
	 */
	function register_password() {
		$pass = isset( $this->settings['register_password'][0] ) ? $this->settings['register_password'][0] : '';
		if( 'yes' == $pass ):
		?>			
			<style type="text/css">
				.login form input[type="password"] {
					color: #555;
					font-weight: 200;
					font-size: 24px;
					line-height: 1;
					width: 100%;
					padding: 3px;
					margin-top: 2px;
					margin-right: 6px;
					margin-bottom: 16px;
					border: 1px solid #e5e5e5;
					background: #fbfbfb;
					outline: none;
					-webkit-box-shadow: inset 1px 1px 2px rgba(200, 200, 200, 0.2);
					box-shadow: inset 1px 1px 2px rgba(200, 200, 200, 0.2);				
				}
			</style>			
			<p>
				<label for="user_pass"><?php _e( 'Password', DXLORE_PRE ); ?><br />
				<input type="password" name="user_pass" id="user_pass" class="pass" value="" size="25" /></label>
			</p>
			<p>
				<label for="pass_comfirm"><?php _e( 'Confirm Password', DXLORE_PRE ); ?><br />
				<input type="password" name="pass_comfirm" id="pass_comfirm" class="pass" value="" size="25" /></label>
			</p>
			<?php if( 'yes' == $this->pass_strength && isset( $_REQUEST['action'] ) && 'register' == $_REQUEST['action'] ): ?>
				<?php $pass_strength_width = ( version_compare( $GLOBALS['wp_version'], '3.8', '>=' ) ) ? ' width: 260px;' : ''?>
				<div id="pass-strength-result" style="display: block; margin-bottom: 15px; margin-top: -5px;<?php echo $pass_strength_width; ?>"><?php _e( 'Strength indicator' ); ?></div>
			<?php endif; ?>								
		<?php
		endif;
	}
	
	/**
	 * Add captcha form
	 */
	function captcha() {		
	?>
		<p>		
			<label for="captcha"><?php _e( 'Captcha', DXLORE_PRE ); ?><br />
			<input type="text" name="captcha" id="captcha" class="text" value="" size="25" />
			<img title="<?php _e( 'Click to refresh', DXLORE_PRE ); ?>" style="cursor:pointer;" src="<?php echo plugins_url( 'captcha/captcha.php', __FILE__ ); ?>" onclick="javascript:this.src='<?php echo plugins_url( 'captcha/captcha.php', __FILE__ ); ?>?tm='+Math.random();" /></label>			
		</p>
		<br />		
	<?php
	}
	
	/**
	 * Register captcha
	 */
	function register_captcha() {
		$register_captcha = isset( $this->settings['register_captcha'][0] ) ? $this->settings['register_captcha'][0] : '';
		if( 'yes' == $register_captcha ) {
			$this->captcha();
		}
	}
	
	/**
	 * Login captcha
	 */
	function login_captcha() {
		$login_captcha = isset( $this->settings['login_captcha'][0] ) ? $this->settings['login_captcha'][0] : '';
		if( 'yes' == $login_captcha ) {
			$this->captcha();
		}		
	}
	
}

new Dxlore_Custom_Form;