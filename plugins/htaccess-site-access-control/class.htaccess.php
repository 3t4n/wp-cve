<?php
/**
 * Main class for the WPSOS .htaccess plugin
 * @package htaccess-plugin
 * @version 1.0.0
 */
 
class WPSOS_HP {
	
	public $htpasswd_file;
	public $htaccess_root;
	public $htaccess_admin;
	
	function __construct(){
		$this->htpasswd_file = ABSPATH . '.htpasswd';
		$this->htaccess_root = ABSPATH . '.htaccess';
		$this->htaccess_admin = ABSPATH . 'wp-admin/.htaccess';		
	}
	
	/**
	 * Adds new user to the .htpasswd file
	 * @return boolean
	 */
	function add_user() {
		if( count( $this->get_htpasswd_users()) > 0 ){
			$_SESSION['wpsos_msg']=__( 'Adding more than one user is for premium version only.' );
			return false;
		}
		//Check the referer
		check_admin_referer( 'wpsos-hp-add-user' );
		//If the fields are set
		if( isset( $_POST['new_username'] ) && trim( $_POST['new_username'] ) ){
			//sanitize the text field
			$username = sanitize_text_field( $_POST['new_username'] );
		}
		//If the password field is set
		if( isset( $_POST['new_password'] ) && trim( $_POST['new_password'] ) ){
			$password = $_POST['new_password'];
		}
		//if both fields exists
		if( $username && $password ){
			//If user can manage options
			if( current_user_can( 'manage_options' ) ){
				$this->modify_htpasswd( $username, $password, 'add' );
				$_SESSION['wpsos_msg']=__( 'New user added' );
				return true;
			}
		}
		$_SESSION['wpsos_msg']=__( 'Something went wrong. Please try again.' );
	}
	
	/**
	 * Removes user from .htpasswd file
	 */
	function remove_user(){
		//If current user can manage options
		if( current_user_can( 'manage_options' ) ){
			//If the username to remove is set
			if( isset( $_POST['username'] ) ){
				$username = $_POST['username'];
				//Check the admin referer
				check_admin_referer( "wpsos-hp-$username" );
				//Remove the user
				$this->modify_htpasswd( $username, '', 'delete' );
				$_SESSION['wpsos_msg']=__( 'User removed.' );
				
				//If there are no users left after deleting
				if( !count( $this->get_htpasswd_users() ) ){
					//Disable the plugin by removing the htaccess rows not to get locked out
					$this->remove_admin_htaccess_rows();
					$this->remove_root_htaccess_rows();
					// Modify the saved options
					$options = array( 'wpsos_hp_login_pwd_enabled'=>0, 'wpsos_hp_enabled'=>0, 'wpsos_all_enabled'=>0 );
					update_option( 'wpsos_hp_options', serialize( $options ) );
					$_SESSION['wpsos_msg']=__( 'User removed. The locks were disabled: there are no users left.' );
				}
				return true;
			}
		}
		$_SESSION['wpsos_msg']=__( 'Something went wrong. Please try again.' );
	}
	
	/**
	 * Change user password in the htpasswd file
	 */
	function modify_user(){
		//If user has admin permissions
		if( current_user_can( 'manage_options' ) ){
			//If username is set
			if( isset( $_POST['username'] ) ){
				$username = $_POST['username'];
				//Check the referer
				check_admin_referer( "wpsos-hp-$username" );
				//If the password is set
				if( isset( $_POST['pwd_user'] ) && strlen(trim($_POST['pwd_user']))){
					$password = $_POST['pwd_user'];
					//Modify the file
					$this->modify_htpasswd( $username, $password, 'modify' );
					$_SESSION['wpsos_msg']=__( 'User password changed.' );
					return true;
				}
			}
		}
		$_SESSION['wpsos_msg']=__( 'Something went wrong. Please try again.' );
	}
	
	/**
	 * Modifies the .htpasswd file
	 * 
	 * @param String $username
	 * @param String $pass
	 * @param String $action
	 * @return boolean
	 */
	function modify_htpasswd( $username, $pass, $action ){
		if (!file_exists( $this->htpasswd_file ) || is_writeable( $this->htpasswd_file ) ) {
			//Encrypt the password
			$password=base64_encode(sha1($pass, true));
			$content='';
			//If file doesn't exist
			if (!file_exists( $this->htpasswd_file ) ) {
				//Take the content of the file as empty
				$lines = '';
			}
			//Otherwise get the content
			else {
				$lines=explode( "\n", implode( '', file( $this->htpasswd_file ) ) );
			}
		
			if ( !$f = @fopen( $this->htpasswd_file, 'w' ) )
				return false;
		
			//If any lines exist in the file
			if( $lines ){
				$found=false;
				//render the lines and compare the the username
				foreach($lines as $line)
				{
					$line = preg_replace( '/\s+/', '', $line ); // remove spaces
					if ( $line ) {
						if( strpos( $line, ':{SHA}') !== false ){
							list( $user, $pass ) = explode( ":{SHA}", $line, 2 );
						}
						else {
							$user = $pass = false;
						}
						//If it's for removing the user
						if( $action == 'delete' ){
							//If the line isn't for the user being removed, add the line to the file
							if( $user != $username && ( $user && $pass ) ){
								$content .= $user.':{SHA}'.$pass."\n";
							}
						}
						//In other cases
						else {
							//If user found
							if ($user == $username) {
								//Add new password
								$content .= $username.':{SHA}'.$password."\n";
								//Mark the user found
								$found=true;
							} else {
								if( $user && $pass ){
									$content .= $user.':{SHA}'.$pass."\n";
								}
							}
						}
					}
				}
			}
			//If it's not removing the user
			if( $action != 'delete' ){
				//If there is no content, or the user to modify wasn't find, add the user to the end
				if( !strlen(trim($content)) || !$found ){
					$content.=$username.':{SHA}'.$password;
				}
			}
			$content = explode( "\n", $content );
			//Write the content to the file
			foreach ( $content as $contentline )
			{
				fwrite( $f, "{$contentline}
" );
			}
			fclose($f);
		}
	}
	
	/**
	 * Removes the rows from .htaccess that password protect wp-login.php
	 */
	function remove_root_htaccess_rows(){
		$this->remove_with_markers( $this->htaccess_root, 'WPSOS htaccess plugin' );
	}
	
	/**
	 * Remove the added rows from .htaccess file for locking up wp-admin
	 */
	function remove_admin_htaccess_rows(){
		$this->remove_with_markers( $this->htaccess_admin, 'WPSOS htaccess plugin' );
	}
	
	/**
	 * Gets the list of the users entered to .htpasswd
	 */
	function get_htpasswd_users(){
		//Create an empty array for users
		$users = array();
		//If the file doesn't exist or isn't readable, return
		if (!file_exists( $this->htpasswd_file ) || !is_readable( $this->htpasswd_file ) ){
			return $users;
		}
		//If can't open the file, return
		if ( !$f = @fopen( $this->htpasswd_file, 'r' ) ){
			return $users;
		}
		//Create an array of the lines in file
		$lines = explode( "\n", implode( '', file( $this->htpasswd_file ) ) );
		//If no lines, return
		if( !$lines ){
			return $users;
		}
		//Loop the lines
		foreach($lines as $line)
		{
			//If line exists after trim
			if ( trim( $line ) ) {
				//Split the line by ':{SHA}'
				if( strpos( $line, ':{SHA}') !== false ){
					list( $user, $pass ) = explode( ":{SHA}", $line, 2 );
					//Add the found user to users' array
					$users[]=$user;
				}
			}
		}
		fclose( $f );
		//Return the found users
		return $users;
	}
	
	/**
	 * Register plugin scripts
	 */
	function register_plugin_scripts(){
		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'wpsos-hp-script', plugin_dir_url( WPSOS_HP_FILE ) . 'js/script.js', array( 'jquery' ) );
		wp_enqueue_style( 'wpsos-hp-style', plugin_dir_url( WPSOS_HP_FILE ) . 'css/style.css' );
		
	}
	
	/**
	 * Match username/password from .htpasswd file. Return false if don't match.
	 * @param String $username
	 * @param String $p
	 * @return boolean
	 */
	function test_username_password( $username, $p ){
		if (!file_exists( $this->htpasswd_file ) || !is_readable( $this->htpasswd_file ) || !$f = @fopen( $this->htpasswd_file, 'r' ) ) {
			return false;
		}
		//Encrypt the password
		$password=base64_encode(sha1($p, true));
		$content='';
		$lines=explode( "\n", implode( '', file( $this->htpasswd_file ) ) );
	
		//If any lines exist in the file
		if( $lines ){
			//render the lines and compare the the username
			foreach($lines as $line)
			{
				$line = preg_replace( '/\s+/', '', $line ); // remove spaces
				if ( $line ) {
					if( strpos( $line, ':{SHA}') !== false ){
						list( $user, $pass ) = explode( ":{SHA}", $line, 2 );
						//If user found
						if ($user == $username && $pass == $password) {
							return true;
						}
					}
				}
			}
		}
		fclose($f);
		return false;
	}
	
	/**
	 * Enable or disable the wp-admin and/or wp-login.php password protection
	 */
	function enable_disable_lock() {
		//Check the form referer for security reasons
		check_admin_referer( "wpsos-hp-enable" );
		//Only allow is the user can manage options
		if( current_user_can( 'manage_options' ) ){
			//If either of the settings is set to enabling
			if( $_POST['wpsos-hp-enabled'] || $_POST['wpsos-hp-login'] /*|| $_POST['wpsos-all-enabled']*/ ){
				//If the user doesn't know the username, password, don't change the settings
				if ( !$this->test_username_password( $_POST['test_username'], $_POST['test_password']) ){
					$_SESSION['wpsos_msg']=__( 'The username and the password did not match. Please try again.' );
					return true;
				}
			}
			//Get the existing options
			$options = unserialize( get_option( 'wpsos_hp_options' ) );
			//Modify and update the options
			$options['wpsos_hp_enabled'] = $_POST['wpsos-hp-enabled'];
			$options['wpsos_hp_login_pwd_enabled'] = $_POST['wpsos-hp-login'];
			$options['wpsos_all_enabled'] = 0;
			update_option( 'wpsos_hp_options', serialize( $options ) );
			
			//If the wp-admin lock was enabled
			if( $_POST['wpsos-hp-enabled'] ){
				$this->add_wp_admin_protection();
			}
			//Else remove the .htaccess rows
			else {
				$this->remove_admin_htaccess_rows();
			}
			
			//If all the site protection was enabled
			/*if( $_POST['wpsos-all-enabled'] ){
				$this->add_root_htaccess_protection( 'all' );
			}*/
			//If the wp-login.php lock was enabled
			if( $_POST['wpsos-hp-login'] ){
				$this->add_root_htaccess_protection( 'login' );
			}
			//Else remove the .htaccess rows for the root htaccess file
			else {
				$this->remove_root_htaccess_rows();
			}
			$_SESSION['wpsos_msg']=__( 'Settings saved.' );
		}
	}
	
	/**
	 * Add wp-admin password protection
	 */
	function add_wp_admin_protection(){
		$insertion = 'AuthUserFile '.$this->htpasswd_file.'
AuthType basic
AuthName "Restricted"
require valid-user
ErrorDocument 401 "Authorization Required"
	
# Stop Apache from serving .ht* files
<Files ~ "^\.ht">
Order allow,deny
Deny from all
</Files>
		
<Files admin-ajax.php>
Order allow,deny
Allow from all
Satisfy any
</Files>
		
<Files ~ "\.(css|js|svg|png|jpeg|jpg|gif)$">
Order allow,deny
Allow from all
Satisfy any
</Files>';
		
		//If the function doesn't exist, require it
		if ( ! function_exists( 'insert_with_markers' ) ) {
			require_once( ABSPATH . 'wp-admin/includes/misc.php' );
		}
		//Since it has to be an array, explode
		$insertion = explode( "\n", $insertion );
		insert_with_markers( $this->htaccess_admin, 'WPSOS htaccess plugin', $insertion );
	}
	
	/**
	 * Add wp-login.php password protection
	 */
	function add_root_htaccess_protection( $type ){
		//If the function doesn't exist, require it
		if ( ! function_exists( 'insert_with_markers' ) ) {
			require_once( ABSPATH . 'wp-admin/includes/misc.php' );
		}
		$insertion = '# Stop Apache from serving .ht* files
<Files ~ "^\.ht">
Order allow,deny
Deny from all
</Files>';
		if( $type == 'login' ){
			$insertion .='
# Protect wp-login
<Files wp-login.php>';
		}
		$insertion .='
AuthUserFile '.$this->htpasswd_file.'
AuthType basic
AuthName "Restricted"
require valid-user
ErrorDocument 401 "Authorization Required"';
		if( $type == 'login' ){
			$insertion .='
</Files>';
		}
		$insertion.='
<Files admin-ajax.php>
order allow,deny
allow from all
</Files>';
		
		//Since it has to be an array, explode
		$insertion = explode( "\n", $insertion );
		insert_with_markers( $this->htaccess_root, 'WPSOS htaccess plugin', $insertion );
	}
	
	/**
	 * Check if the plugin has sufficient permissions
	 * @return boolean
	 */
	function plugin_has_sufficient_permissions() {
		$_SESSION['htaccess_admin']=1;
		$_SESSION['htaccess_root']=1;
		$_SESSION['htpasswd']=1;
		//Check if .htaccess for admin folder is writeable
		$has_permissions = true;
		//If file exists but is not writeable, return false
		if( file_exists( $this->htaccess_admin ) && !is_writeable( $this->htaccess_admin  ) ){
			$_SESSION['htaccess_admin']=0;
			$has_permissions = false;
		}
		//If file doesn't exist and the folder isn't writeable, return false
		else if( !file_exists( $this->htaccess_admin  ) && !is_writeable( ABSPATH.'wp-admin/'  ) ){
			$_SESSION['htaccess_admin']=0;
			$has_permissions = false;
		}
		//Check if .htpasswd file is writeable
		//If file doesn't exist and the folder isn't writeable
		if ( !file_exists( $this->htpasswd_file ) && !is_writeable( ABSPATH ) ){
			$_SESSION['htpasswd']=0;
			$has_permissions = false;
		}
		else if( file_exists( $this->htpasswd_file ) && !is_writeable( $this->htpasswd_file ) ){
			$_SESSION['htpasswd']=0;
			$has_permissions = false;
		}
		//Check if .htaccess file for wp-login.php is writeable
		//If file doesn't exist and the folder isn't writeable
		if ( !file_exists( $this->htaccess_root ) && !is_writeable( ABSPATH ) ){
			$_SESSION['htaccess_root']=0;
			$has_permissions = false;
		}
		else if( file_exists( $this->htaccess_root ) && !is_writeable( $this->htaccess_root ) ){
			$_SESSION['htaccess_root']=0;
			$has_permissions = false;
		}
		return $has_permissions;
	}
	
	/**
	 * Remove lines from .htaccess files with markers
	 * @param string $filename
	 * @param string $marker
	 * @return boolean
	 */
	function remove_with_markers( $filename, $marker ){
		if (!file_exists( $filename ) || is_writeable( $filename ) ) {
			if (!file_exists( $filename ) ) {
				$markerdata = '';
			} else {
				$markerdata = explode( "\n", implode( '', file( $filename ) ) );
			}
		
			if ( !$f = @fopen( $filename, 'w' ) )
				return false;
		
			if ( $markerdata ) {
				$state = true;
				foreach ( $markerdata as $n => $markerline ) {
					if (strpos($markerline, '# BEGIN ' . $marker) !== false)
						$state = false;
					if ( $state ) {
						if ( $n + 1 < count( $markerdata ) )
							fwrite( $f, "{$markerline}\n" );
						else
							fwrite( $f, "{$markerline}" );
					}
				}
			}
			fclose( $f );
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * On plugin activation
	 */
	function activate(){
		//Create the default options
		$options = array( 'wpsos_hp_login_pwd_enabled'=>0, 'wpsos_hp_enabled'=>0, 'wpsos_all_enabled'=>0 );
		update_option( 'wpsos_hp_options', serialize( $options ), true );
	}
	
	/**
	 * On plugin deactivation
	 */
	function deactivate(){
		//Remove rows for locking up
		try {
			$this->remove_root_htaccess_rows();
		}
		catch(Error $e){}
		try {
			$this->remove_admin_htaccess_rows();
		}
		catch(Error $e){}
		
		//Set options to default
		$options = array( 'wpsos_hp_login_pwd_enabled'=>0, 'wpsos_hp_enabled'=>0, 'wpsos_all_enabled'=>0 );
		update_option( 'wpsos_hp_options', serialize( $options ) );
	}
	
}

?>