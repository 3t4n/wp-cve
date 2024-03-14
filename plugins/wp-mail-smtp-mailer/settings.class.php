<?php 

if (!defined( 'ABSPATH')) exit;
 
/**
* Admin option page
*/
class WPMS_settings 
{
	public $dir_path;

	public static $instance = null;

	public static function get_instance( $dir_path ){

		if( !empty( self::$instance ) ) return self::$instance;
		self::$instance = new self;

	}

	private function __construct(){

		add_action( 'admin_menu', array($this,'WPMS_admin_settings') );
	}

	public function WPMS_admin_settings() {

		add_options_page( __('WP Mail SMTP7 Options','wp-mail-smtp-mailer'), __('Mail Smtp Mailer','wp-mail-smtp-mailer'), 'manage_options', 
			'wp-mail-smtp-mailer', array($this, 'WPMS_mail_insert_options') );

		add_action( 'admin_print_scripts-settings_page_wp-mail-smtp-mailer',  array($this, 'WPMS_mail_admin_enqueue') );
		
	}

	public function WPMS_mail_insert_options() {
		
		$this->WPMS_send_email();

		$this->WPMS_insert_option();
		
		$option_data = get_option('WPMSM_mail_data', '');

		if ( $option_data == '' ) {

			$data = array( 'host' => '',
				'port'=> '', 'username' => '', 'password' => '',
				'SMTPSecure' => '', 'From' => '', 'FromName' => '',
				'encrypt' => '0' 
			); 
			update_option('WPMSM_mail_data', $data);
			$option_data = get_option('WPMSM_mail_data', '');

		}
		?>


		<h2><?php echo 'WP Mail Smtp Mailer' ?></h2>
		<form action ='options-general.php?page=wp-mail-smtp-mailer' method="POST"> 
		<?php wp_nonce_field('WPMS-mail-option') ?>
		<table class="form-table">
			<tbody>
				<tr valign="top">
				<th><lable><?php _e('Host', 'wp-mail-smtp-mailer') ?></lable></th>
				<td scop="row">
				<input name="host" type="text" placeholder='smtp.example.com' value="<?php  echo isset($option_data['host'])? $option_data['host'] :''?>" class="host" required> 
				</td>
				<td><div class='host-info'></div></td>
				</tr>
				<tr valign="top">
				<th><lable><?php _e('Port', 'wp-mail-smtp-mailer')?></lable></th>
				<td scop="row">
				<input name="port" type="number" placeholder='25' value="<?php  echo isset($option_data['port'])?$option_data['port']:''?>" required> 
				</td>
				</tr>
				<tr valign="top">
				<th><lable><?php _e('Username', 'wp-mail-smtp-mailer') ?></lable></th>
				<td scop="row">
				<input name="username" type="text" placeholder='Your username' value="<?php  echo isset($option_data['username'])?$option_data['username']:''?>" required> 
				</td>
				</tr>
				<tr valign="top">
				<th><lable><?php _e('Password', 'wp-mail-smtp-mailer') ?></lable></th>
				<td scop="row">
				<input name="password" type="text" placeholder='Your password' value="<?php  echo isset($option_data['password'])?$option_data['password']:''?>" required> 
				</td>
				</tr>
				
				<tr valign="top">
				<th><lable><?php _e('Choose SSL or TLS, if necessary for your server', 'wp-mail-smtp-mailer') ?></lable></th>
				<td scop="row">
				<select name='SMTPSecure'>

				<?php  
				$option_data['SMTPSecure'] = isset($option_data['SMTPSecure']) ? $option_data['SMTPSecure']:'';
				if($option_data['SMTPSecure']=='tls'){
					echo "<option value='none'>None</option>";
					echo "<option value='ssl' >SSL</option>";
					echo "<option value='tls' selected='selected'>TLS</option>;?>";
				}elseif($option_data['SMTPSecure']=='ssl'){
					echo "<option value='ssl' selected='selected'>SSL</option>";
					echo "<option value='none'>None</option>";
					echo "<option value='tls'>TLS</option>;?>";
				}else{
					echo "<option value='none'>None</option>";
					echo "<option value='ssl'>SSL</option>";
					echo "<option value='tls'>TLS</option>;?>";
				}
				?>
				
					
				</select>

				</td>
				</tr>
				<tr valign="top">
				<th><lable><?php _e('From', 'wp-mail-smtp-mailer') ?></lable></th>
				<td scop="row">
				<input name="From" type="email"  placeholder='you@yourdomail.com' value="<?php  echo isset($option_data['From'])?$option_data['From']:''?>" /> 
				<p class="from-eg"></p>
				</td>
				</tr>
				<tr valign="top">
				<th><lable><?php _e('From Name', 'wp-mail-smtp-mailer') ?></lable></th>
				<td scop="row">
				<input name="FromName"   type="text" placeholder='Your Name'  value="<?php  echo isset($option_data['FromName'])?$option_data['FromName']:''?>"" /> 
				</td>
				</tr>
				<tr valign="top">
				<th><lable><?php _e('Encrypt', 'wp-mail-smtp-mailer') ?></lable></th>
				<td scop="row">
				<input name="encrypt" class='encrypt' type="checkbox" <?php  echo isset($option_data['encrypt']) && $option_data['encrypt']=='1'?'checked="checked";':''; ?>  /> 
				</td>
				</tr>
				<tr valign="top">
				<td scop="row">
				</td>
				</tr>
			</tbody> 
		</table>
		<input name="submit" class='button button-primary' type="submit" value="Submit" > 
		<div  class='button button-primary' id="resetVal"><?php _e('Reset values', 'wp-mail-smtp-mailer') ?></div>
		<div  class='button button-primary' id="testEmail"><?php _e('Test Email', 'wp-mail-smtp-mailer') ?></div>
		</form>
	
		<div class="testEmail-wrap">
		<form action='options-general.php?page=wp-mail-smtp-mailer' method="POST">
		<?php wp_nonce_field('WPMS-mail-send'); ?> 
		<table class="form-table">
			<tbody>
			
				<tr valign="top">
				<th><lable><?php _e('To Email', 'wp-mail-smtp-mailer') ?></lable></th>
				<td scop="row">
				<input name="to_email" type="email" placeholder='youremail@example.com' required>  
				</td>
				</tr>
				<tr valign="top">
				<th><lable><?php _e('Body', 'wp-mail-smtp-mailer') ?></lable></th>
				<td scop="row">
				<textarea name="email_body" rows="6" type="text" placeholder='Your text email here...!' required></textarea>
				</td>
				</tr>
			</tbody>
		</table>
		<input  class='button button-primary' type="submit" value="Send"> 
		</form>
		</div>

		<div class="clear"></div>
		<?php $rating_link = sprintf( 
				/* translators: screen reader text */
				__(
					'%1$s  %2$s %3$s', 'wp-mail-smtp-mailer'
				),
				'<a href="https://wordpress.org/plugins/wp-mail-smtp-mailer/" target="_blank" >★★★★★</a>',
				'rating. A huge thank you from',
				'<a href="http://ciphercoin.com" target="_blank">CipherCoin</a>'
			);
		$rating_msg = sprintf( 
			/* translators: screen reader text */
			__('If you like %1$s please leave us a %2$s in advance!', 
			'wp-mail-smtp-mailer'), 
			'<strong>WP Mail Smtp Mailer</strong>', 
			$rating_link
		) ?>
		<p Style="float:left;"><?php echo  $rating_msg ?></p>

		<?php 
	}


	public function WPMS_send_email(){


		if( ! isset($_POST['to_email']) ) return;


		check_admin_referer( 'WPMS-mail-send' );
		if ( !current_user_can( 'activate_plugins' ) )  
			wp_die( __( 'You do not have sufficient permissions to access this page.', 'wp-mail-smtp-mailer' ) );
		

		$nonce_vrfy = $_REQUEST['_wpnonce'];
		if ( ! wp_verify_nonce( $nonce_vrfy, 'WPMS-mail-send') ) return;

		$to      = sanitize_email( $_POST['to_email'] );
		$subject = 'WP Mail Smtp Mailer Test Mail';
		$body    = sanitize_text_field( $_POST['email_body'] );
		$headers = array('Content-Type: text/html; charset=UTF-8');
		
		
		$sent = wp_mail( $to, $subject, $body, $headers );

		if( $sent ){

			$msg = __('Message sent..!', 'wp-mail-smtp-mailer' );
			echo "<div class='notice notice-success'><p> $msg </p></div>";
			if ( ! get_option( 'wpmsm_mail_smtp_ignore_notice' ) ) {
				echo '<div class="updated"><p>'; 
				echo sprintf( /* translators: Rating request */
					__('Awesome, you\'ve been using %1$s. May we ask you to give it a 5-star rating on WordPress? | <a href="%3$s" target="_blank">Ok, you deserved it</a> | <a href="%2$s">I alredy did</a> | <a href="%2$s">No, not good enough</a>', 'wp-mail-smtp-mailer'), 
						'<b>Mail SMTP Mailer</b>',
						'options-general.php?page=wp-mail-smtp-mailer&wp_smtp_mailer_nag_ignore=0',
						'https://wordpress.org/plugins/wp-mail-smtp-mailer/'
					);
				echo "</p></div>";
			}

		}else{

			$errorInfo = $GLOBALS['phpmailer']->ErrorInfo;
			echo "<div class='notice notice-error'><p>";
			if( strrpos($errorInfo, 'SMTP connect() failed') !== false ){
				$errorInfo = __('SMTP Error: Could not authenticate..! ', 'wp-mail-smtp-mailer');
			}
			echo $errorInfo;
			echo "</p></div>";
		}
		
	}


	public function WPMS_insert_option(){


		if( empty($_POST['host']) ) return;

		check_admin_referer( 'WPMS-mail-option' );

		if ( !current_user_can( 'activate_plugins' ) )
			wp_die( __( 'You do not have sufficient permissions to access this page.', 'wp-mail-smtp-mailer' ) );
		
		$nonce_vrfy = $_REQUEST['_wpnonce'];
        
		if ( ! wp_verify_nonce( $nonce_vrfy, 'WPMS-mail-option') ) return;
            
		$encrypt     = empty($_POST['encrypt']) ? 0 : 1;
		$host 		 = esc_sql($_POST['host']);
		$port 		 = esc_sql($_POST['port']);
		$username 	 = esc_sql($_POST['username']);
		$password 	 = esc_sql($_POST['password']);
		$SMTPSecure  = esc_sql($_POST['SMTPSecure']);
		$From        = esc_sql($_POST['From']);
		$FromName  	 = esc_sql($_POST['FromName']);
		$host 		 = empty( $encrypt ) ? $host : WPMSM_encryption::encrypt( $host );
		$username 	 = empty( $encrypt ) ? $username : WPMSM_encryption::encrypt( $username );
		$password 	 = empty( $encrypt ) ? $password : WPMSM_encryption::encrypt( $password );
		
		$data = array( 'host' => $host,
			'port'=> $port, 'username' => $username, 'password' => $password,
			'SMTPSecure' => $SMTPSecure, 'From' => $From, 'FromName' => $FromName,
			'encrypt' => $encrypt 
		); 

		update_option('WPMSM_mail_data', $data);

		
    }


	public function WPMS_mail_admin_enqueue(){

		wp_enqueue_style( 'WPMS_mail_admin_style', plugin_dir_url( __FILE__ )  . 'admin.css', array());

		wp_register_script( 'wpmsm_admin_mail', plugin_dir_url( __FILE__ )  .'admin.js', array('jquery'));

		wp_enqueue_script( 'jquery' );

		$data =  array( 'domain_name' => $_SERVER['SERVER_NAME'] );
		wp_localize_script( 'wpmsm_admin_mail', 'wpmsm', $data );

		wp_enqueue_script( 'wpmsm_admin_mail' );

	}

}


/* Display a notice that can be dismissed */


add_action('admin_notices', 'wp_mail_admin_notice');

function wp_mail_admin_notice() {

	$install_date = get_option( 'wpmsm_mailer_install_date', '');
	$install_date = date_create( $install_date );
	$date_now	  = date_create( date('Y-m-d G:i:s') );
	$date_diff    = date_diff( $install_date, $date_now );

	if ( $date_diff->format("%d") < 7 ) return false;
	

	$rated  = get_option( 'wpmsm_mail_smtp_ignore_notice', false );

    if ( empty( $rated ) ) {

        echo '<div class="updated"><p>'; 

        printf(__('Awesome, you\'ve been using <a href="options-general.php?page=wp-mail-smtp-mailer">Mail SMTP Mailer</a> for more than 1 week. May we ask you to give it a 5-star rating on WordPress? | <a href="%2$s" target="_blank">Ok, you deserved it</a> | <a href="%1$s">I alredy did</a> | <a href="%1$s">No, not good enough</a>'), 'options-general.php?page=wp-mail-smtp-mailer&wp_smtp_mailer_nag_ignore=0','https://wordpress.org/plugins/wp-mail-smtp-mailer/');
        echo "</p></div>";
    }
}

add_action('admin_init', 'wp_smtp_mailer_nag_ignore');

function wp_smtp_mailer_nag_ignore() {

    if ( isset($_GET['wp_smtp_mailer_nag_ignore'])  ) 
        update_option( 'wpmsm_mail_smtp_ignore_notice', 'true');
    
}



