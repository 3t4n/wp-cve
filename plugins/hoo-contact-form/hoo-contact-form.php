<?php
/*
	Plugin Name: Hoo Contact Form
	Description: Hoosoft Contact Form.
	Author: Hoosoft
	Author URI: https://www.hoosoft.com/
	Version: 1.0.1
	Text Domain: hoo-contact-form
	Domain Path: /languages
	License: GPL v2 or later
*/

if ( !defined('ABSPATH') ) {
    header('HTTP/1.0 403 Forbidden');
    exit;
}

if(!class_exists('hooContactform')){
	
	class hooContactform{
	
		public function __construct() {
			// create custom plugin settings menu
			add_action('admin_menu', array(&$this,'create_menu'));
			add_action( 'admin_enqueue_scripts',  array($this,'admin_scripts' ));
			add_action( 'wp_enqueue_scripts',  array($this,'frontend_scripts' ));
			add_shortcode( 'hoo_contact_form',array(&$this,'shortcode') );
			add_action('wp_ajax_hcf_send_email', array(&$this,'send_email'));
			add_action('wp_ajax_nopriv_hcf_send_email', array(&$this,'send_email'));
			
			if(isset($_GET['code'])){
				
				//输出调用
				$checkcode = hooContactform::make_rand(4);
				session_start();//将随机数存入session中
				$_SESSION['helloweba_gg']=strtolower($checkcode);
				hooContactform::getAuthImage($checkcode);
				exit;
				
				}
		}
		
		function admin_scripts() {
			wp_enqueue_style( 'hoo-contact-form-admin-css',  plugins_url( 'assets/css/admin.css',__FILE__ ), '','', false );
        	wp_enqueue_script( 'hoo-contact-form-admin-js',  plugins_url( 'assets/js/admin.js',__FILE__ ), array( 'jquery' ),'', true );
		}
		
		function frontend_scripts() {
			
			$labels = hooContactform::get_labels();
			$labels['required'] = __(' is required', 'hoo-contact-form');
			$labels['not_valid'] = __(' is not valid', 'hoo-contact-form');
			$labels['waiting'] = __('Sending form, please wait...', 'hoo-contact-form');
			
			wp_enqueue_style( 'bootstrap',  plugins_url( 'assets/plugins/bootstrap/css/bootstrap.min.css',__FILE__ ), '','', false );
			
			wp_enqueue_style( 'bootstrapvalidator',  plugins_url( 'assets/plugins/bootstrapvalidator/css/bootstrapValidator.css',__FILE__ ), '','', false );
			
			wp_enqueue_script( 'bootstrap',  plugins_url( 'assets/plugins/bootstrap/js/bootstrap.min.js',__FILE__ ), array( 'jquery' ),'', true );
			wp_enqueue_script( 'bootstrapvalidator',  plugins_url( 'assets/plugins/bootstrapvalidator/js/bootstrapValidator.js',__FILE__ ), array( 'jquery' ),'', true );
			
			wp_enqueue_style( 'font-awesome',  plugins_url( 'assets/plugins/font-awesome/css/font-awesome.min.css',__FILE__ ), '','', false );			
			wp_enqueue_style( 'hoo-contact-form-css',  plugins_url( 'assets/css/main.css',__FILE__ ), '','', false );
			wp_enqueue_script( 'hoo-contact-form-js',  plugins_url( 'assets/js/main.js',__FILE__ ), array( 'jquery' ),'', true );
			
			wp_localize_script( 'hoo-contact-form-js', 'hcf_params', array(
			'ajaxurl' => admin_url('admin-ajax.php'),
			'themeurl' => get_template_directory_uri(),
			'labels' => $labels,
		)  );
			
			
			}
		
		function create_menu() {
			add_submenu_page(
        		'options-general.php',
       		 	__('Hoo Contact Form','hoo-contact-form'),
       			__('Hoo Contact Form','hoo-contact-form'),
        		'manage_options',
        		'hoo-contact-form',
        		array(&$this,'settings_page') )
				;
		
			//call register settings function
			add_action( 'admin_init', array(&$this,'register_mysettings') );
		}
		
		function default_options(){
			
			$site_title = get_bloginfo('name');
			$admin_mail = get_bloginfo('admin_email');

			$return = array(
				'display_labels'      => '1',
				'hcf_name'            => $site_title,
				'hcf_email'           => $admin_mail,
				'hcf_subject'         => esc_html__('Message sent from your contact form.', 'hoo-contact-form'),
				'hcf_nametext' => esc_html__('Name','hoo-contact-form'),
				'hcf_input_name' => esc_html__('Your Name','hoo-contact-form'),
				'hcf_mailtext' => esc_html__('Email','hoo-contact-form'),
				'hcf_input_email' => esc_html__('Your Email','hoo-contact-form'),
				'hcf_subjtext' => esc_html__('Subject','hoo-contact-form'),
				'hcf_input_subject' => esc_html__('Subject','hoo-contact-form'),
				'hcf_messtext' => esc_html__('Message','hoo-contact-form'),
				'hcf_input_message' => esc_html__('Your Message','hoo-contact-form'),
			);
			
			return $return;
			
			}
		
		function text_validate($input)
		{
			$input['hcf_email']         = sanitize_email($input['hcf_email']);
			$input['hcf_subject']       = sanitize_text_field($input['hcf_subject']);
			$input['display_labels']    = absint($input['display_labels']);
			$input['hcf_nametext']      = sanitize_text_field($input['hcf_nametext']);
			$input['hcf_input_name']    = sanitize_text_field($input['hcf_input_name']);
			$input['hcf_mailtext']      = sanitize_text_field($input['hcf_mailtext']);
			$input['hcf_input_email']   = sanitize_text_field($input['hcf_input_email']);
			$input['hcf_subjtext']      = sanitize_text_field($input['hcf_subjtext']);
			$input['hcf_input_subject'] = sanitize_text_field($input['hcf_input_subject']);
			$input['hcf_messtext']      = sanitize_text_field($input['hcf_messtext']);
			$input['hcf_input_message'] = sanitize_text_field($input['hcf_input_message']);
			
			return $input;
		}
		
		
		function register_mysettings() {
			//register settings
			register_setting( 'hcf-settings', 'hcf_options', array(&$this,'text_validate') );

		}
		
		function settings_page() {
			
		$tabs = array(
        'general-options'   => esc_html__( 'General Options', 'hoo-contact-form' ), 
        'field-labels'  => esc_html__( 'Field Labels & Placeholders', 'hoo-contact-form' ),
		'shortcode'  => esc_html__( 'Shortcode', 'hoo-contact-form' )
    );
	$current = 'general-options';
	if(isset($_GET['tab']))
		$current = $_GET['tab'];
		
    $html = '<h2 class="nav-tab-wrapper">';
    foreach( $tabs as $tab => $name ){
        $class = ( $tab == $current ) ? 'nav-tab-active' : '';
        $html .= '<a class="nav-tab ' . $class . '" href="?page=hoo-contact-form&tab=' . $tab . '">' . $name . '</a>';
    }
    $html .= '</h2>';
    echo $html;
	
		?>

<div class="wrap">
  <form method="post" action="options.php">
    <?php settings_fields( 'hcf-settings' ); ?>
    <?php
				$options     = get_option('hcf_options',hooContactform::default_options());
				$hcf_options = wp_parse_args($options,hooContactform::default_options());
				
			?>
    <?php
	$hide_class = 'hide';
	if($current=='general-options'):
		$hide_class = '';
	 endif;
	 ?>
    <table class="form-table <?php echo $hide_class;?>">
      <tr valign="top">
        <th scope="row"><?php _e('Your Email','hoo-contact-form');?></th>
        <td><input type="text" name="hcf_options[hcf_email]" value="<?php echo esc_attr($hcf_options['hcf_email']);?>"  /></td>
      </tr>
      <tr valign="top">
        <th scope="row"><?php _e('Default Subject','hoo-contact-form');?></th>
        <td><input type="text" name="hcf_options[hcf_subject]" value="<?php echo esc_attr($hcf_options['hcf_subject']);?>" /></td>
      </tr>
    </table>
   
    <?php
	$hide_class = 'hide';
	if($current=='field-labels'):
		$hide_class = '';
	 endif;
	 ?>
    <table class="form-table <?php echo $hide_class;?>">
      <tbody>
      <tr>
          <th scope="row"><label class="description" for="hcf_options[hcf_nametext]">
              <?php _e('Display Labels','hoo-contact-form');?>
            </label></th>
          <td><input type="checkbox" name="hcf_options[display_labels]" <?php if($hcf_options['display_labels']==1 ){ ?>checked="checked"<?php }?> value="1">
          </td>
        </tr>
        
        <tr>
          <th scope="row"><label class="description" for="hcf_options[hcf_nametext]">
              <?php _e('Name Label','hoo-contact-form');?>
            </label></th>
          <td><input type="text" class="regular-text" size="50" maxlength="200" name="hcf_options[hcf_nametext]" value="<?php echo esc_attr($hcf_options['hcf_nametext']);?>">
            <div class="mm-item-caption">
              <?php _e('Label for the Name field','hoo-contact-form');?>
            </div></td>
        </tr>
        <tr>
          <th scope="row"><label class="description" for="hcf_options[hcf_input_name]">
              <?php _e('Name Placeholder','hoo-contact-form');?>
            </label></th>
          <td><input type="text" class="regular-text" size="50" maxlength="200" name="hcf_options[hcf_input_name]" value="<?php echo esc_attr($hcf_options['hcf_input_name']);?>">
            <div class="mm-item-caption">
              <?php _e('Placeholder for the Name field','hoo-contact-form');?>
            </div></td>
        </tr>
        <tr>
          <th scope="row"><label class="description" for="hcf_options[hcf_mailtext]">
              <?php _e('Email Label','hoo-contact-form');?>
            </label></th>
          <td><input type="text" class="regular-text" size="50" maxlength="200" name="hcf_options[hcf_mailtext]" value="<?php echo esc_attr($hcf_options['hcf_mailtext']);?>">
            <div class="mm-item-caption">
              <?php _e('Label for the Email field','hoo-contact-form');?>
            </div></td>
        </tr>
        <tr>
          <th scope="row"><label class="description" for="hcf_options[hcf_input_email]">
              <?php _e('Email Placeholder','hoo-contact-form');?>
            </label></th>
          <td><input type="text" class="regular-text" size="50" maxlength="200" name="hcf_options[hcf_input_email]" value="<?php echo esc_attr($hcf_options['hcf_input_email']);?>">
            <div class="mm-item-caption">
              <?php _e('Placeholder for the Email field','hoo-contact-form');?>
            </div></td>
        </tr>
        <tr>
          <th scope="row"><label class="description" for="hcf_options[hcf_subjtext]">
              <?php _e('Subject Label','hoo-contact-form');?>
            </label></th>
          <td><input type="text" class="regular-text" size="50" maxlength="200" name="hcf_options[hcf_subjtext]" value="<?php echo esc_attr($hcf_options['hcf_subjtext']);?>">
            <div class="mm-item-caption">
              <?php _e('Label for the Subject field','hoo-contact-form');?>
            </div></td>
        </tr>
        <tr>
          <th scope="row"><label class="description" for="hcf_options[hcf_input_subject]">
              <?php _e('Subject Placeholder','hoo-contact-form');?>
            </label></th>
          <td><input type="text" class="regular-text" size="50" maxlength="200" name="hcf_options[hcf_input_subject]" value="<?php echo esc_attr($hcf_options['hcf_input_subject']);?>">
            <div class="mm-item-caption">
              <?php _e('Placeholder for the Subject field','hoo-contact-form');?>
            </div></td>
        </tr>
        <tr>
          <th scope="row"><label class="description" for="hcf_options[hcf_messtext]">
              <?php _e('Message Label','hoo-contact-form');?>
            </label></th>
          <td><input type="text" class="regular-text" size="50" maxlength="200" name="hcf_options[hcf_messtext]" value="<?php echo esc_attr($hcf_options['hcf_messtext']);?>">
            <div class="mm-item-caption">
              <?php _e('Label for the Message field','hoo-contact-form');?>
            </div></td>
        </tr>
        <tr>
          <th scope="row"><label class="description" for="hcf_options[hcf_input_message]">
              <?php _e('Message Placeholder','hoo-contact-form');?>
            </label></th>
          <td><input type="text" class="regular-text" size="50" maxlength="200" name="hcf_options[hcf_input_message]" value="<?php echo esc_attr($hcf_options['hcf_input_message']);?>">
            <div class="mm-item-caption">
              <?php _e('Placeholder for the Message field','hoo-contact-form');?>
            </div></td>
        </tr>
      </tbody>
    </table>
      <?php
	$hide_class = 'hide';
	if($current=='shortcode'):
		$hide_class = '';
	 endif;
	 ?>
    <div class="toggle default-hidden <?php echo $hide_class;?>">
      <p><?php _e('Use this shortcode to display the contact form on any WP Post or Page:','hoo-contact-form');?></p>
      <p><code class="mm-code">[hoo_contact_form]</code></p>
    </div>
    <p class="submit">
      <input type="submit" class="button-primary" value="<?php _e('Save Changes','hoo-contact-form') ?>" />
    </p>
  </form>
</div>
<?php }


	function get_labels(){
		
		$labels = array();
		$options = get_option('hcf_options');
		$default = hooContactform::default_options();
		
		if($options)
			$labels = wp_parse_args($options,$default);
		else
			$labels = $default;
		return $labels;
		
		}
	  
	  function display_contact_form() {
		
		$labels = hooContactform::get_labels();
		extract($labels);
		
		if($display_labels == '1' )
			$col_class = 'col-sm-10';
		else
			$col_class = 'col-sm-12';
		
		$form = '<center><div style="width:98%;max-width:800px;">
		
<form id="hoo-contactForm" class="form-horizontal" method="post" action="#">';
$form .= wp_nonce_field('hcf_nonce', 'hoo-contact-form-nonce', true, false);
$form .= '<div class="form-group">';
	if($display_labels == '1' )
      $form .= '<label for="Name" class="col-sm-2 control-label">'.esc_attr($hcf_nametext).'</label>';
	  
       $form .= '<div class="'.$col_class.'"><div class="input-group"> <span class="input-group-addon"><i class="fa fa-user fa-fw"></i></span>
          <input type="text" id="name"  name="Name" class="form-control" value="" placeholder="'.esc_attr__($hcf_input_name).'">
        </div>
        </div>
		</div>
    <div class="form-group">';
	if($display_labels == '1' )
      $form .= '<label for="Email" class="col-sm-2 control-label">'.esc_attr($hcf_mailtext).'</label>';
	  
      $form .= '<div class="'.$col_class.'">
        <div class="input-group"> <span class="input-group-addon"><i class="fa fa-envelope fa-fw"></i></span>
          <input type="email" id="email" name="Email" class="form-control" placeholder="'.esc_attr__($hcf_input_email).'" >
        </div>
       </div>
    </div>
	<div class="form-group">';
	
      if($display_labels == '1' )
      $form .= '<label for="Subject" class="col-sm-2 control-label">'.esc_attr($hcf_subjtext).'</label>';
	  
      $form .= '<div class="'.$col_class.'">
        <div class="input-group"> <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
          <input type="text" id="subject" name="Subject" class="form-control" placeholder="'.esc_attr__($hcf_input_subject).'">
        </div>
      </div>
    </div>
    <div class="form-group has-error">';
	
      if($display_labels == '1' )
      $form .= '<label for="Message" class="col-sm-2 control-label">'.esc_attr($hcf_messtext).'</label>';
	  
      $form .= '<div class="'.$col_class.'">
        <div class="input-group"> <span class="input-group-addon"><i class="fa fa-comment fa-fw"></i></span>
          <textarea id="message" name="Message" placeholder="'.esc_attr__($hcf_input_message).'" class="form-control" rows="5"></textarea>
        </div>
        </div>
    </div>';
	  
    $form .= '<div class="form-group">';
	
    if($display_labels == '1' )
      $form .= '<div class="col-sm-2"></div>';
	  
      $form .= '<div class="'.$col_class.'" align="center">
	  '.do_action('hcf_security_question').'
        <button type="submit" class="btn btn-primary btn-md btn-submit">'.__('Submit','hoo-contact-form').'</button>
      </div>
    </div>

<div style="padding-top:20px;">
<input name="action" value="hcf_send_email" style="display:none;" />
<div id="status" class="alert alert-success"></div></div>
</form>
</div></center>';
		
		return apply_filters('hoo_filter_contact_form', $form);
	
	  }
	  
	function send_email(){
		  
		$valid   = true;
		$message = '';
		$labels = hooContactform::get_labels();

		if ( ! isset( $_POST['hoo-contact-form-nonce'] ) 
    || ! wp_verify_nonce( $_POST['hoo-contact-form-nonce'], 'hcf_nonce' ) 
	) {
   		_e('Sorry, your nonce did not verify.','hoo-contact-form');
   		exit;
	} 
		
		if(trim($_POST['Name']) === '') {
			$Error = __('Please enter your name.','hoo-contact-form');
			$hasError = true;
		} else {
			$name = sanitize_text_field($_POST['Name']);
		}
	
		if(trim($_POST['Email']) === '')  {
			$Error = __('Please enter your email address.','hoo-contact-form');
			$hasError = true;
		} else if (!preg_match("/^[[:alnum:]][a-z0-9_.-]*@[a-z0-9.-]+\.[a-z]{2,4}$/i", trim($_POST['Email']))) {
			$Error = __('You entered an invalid email address.','hoo-contact-form');
			$hasError = true;
		} else {
			$email = sanitize_email($_POST['Email']);
		}
		
		if(trim($_POST['Subject']) === '') {
			$Error = __('Please enter subject.','hoo-contact-form');
			$hasError = true;
		} else {
			$subject = sanitize_text_field($_POST['Subject']);
		}
	
		if(trim($_POST['Message']) === '') {
			$Error =  __('Please enter a message.','hoo-contact-form');
			$hasError = true;
		} else {
			if(function_exists('stripslashes')) {
				$message = stripslashes(trim($_POST['Message']));
			} else {
				$message = wp_filter_post_kses($_POST['Message']);
			}
		}
		
		if(!isset($hasError)) {
	
		$emailTo = $labels['hcf_email'];
		if($subject =='' )
			$subject = $labels['hcf_subject'];
				
		   if($emailTo !=""){
				$body = sprintf("%1$s: $name \n\n%2$s: $email \n\n%3$s: $message",__('Name','hoo-contact-form'),__('Email','hoo-contact-form'),__('Message','hoo-contact-form'));
				$headers = 'From: '.$name.' <'.$emailTo.'>' . "\r\n" . 'Reply-To: ' . $email;
	
				wp_mail($emailTo, $subject, $body, $headers);
				$emailSent = true;
			}
			echo json_encode(array("msg"=>__("Thank you, we've received your message.","hoo-contact-form"),"error"=>0));
			
		}
		else
		{
			echo json_encode(array("msg"=>$Error,"error"=>1));
		}
		die() ;

		  
	}
	  
	  function shortcode() {
		  return hooContactform::display_contact_form();
	  }

}
}
new hooContactform();
