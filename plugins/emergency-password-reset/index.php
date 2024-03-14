<?php
/*
 Plugin Name: Emergency password reset
 Plugin URI: http://www.themoyles.co.uk
 Description: Resets all passwords, emailing them the reset link -  <a href="./users.php?page=emergency_password_reset_main">Reset Passwords now</a>
 Version: 8.0
 Author: andymoyle
 Author URI:http://www.themoyles.co.uk
 Text Domain: emergency-password-reset
 */


//Menu
add_action('admin_menu','add_emergency_password_reset_menu_item');
function add_emergency_password_reset_menu_item()
{
    add_submenu_page('users.php', 'Emergency Password Reset', 'Emergency Password Reset', 'administrator', 'emergency_password_reset_main', 'emergency_password_reset_main' );
    add_submenu_page('options-general.php', 'Reset SALTS', 'Reset SALTs', 'administrator', 'emergency_password_reset/index.php', 'emergency_password_reset_salts' );
}

function emergency_password_reset_main()
{
    if(current_user_can('manage_options'))
    {
        global $wpdb;
        $wpdb->show_errors();
        $settings=get_option('emergency_password_reset_settings');
        if(empty($settings))
        {
            $settings=array('email_subject'=>'Password reset for '.site_url(),
                            'from_name'=>get_option('blogname'),
                            'from_email'=>get_option('admin_email'),
                            'message'=>'<p>We have reset the passwords for the website [url]. Your username is still the same, please reset the password to one of your choosing at [link]</p>'
                        );
            update_option('emergency_password_reset_settings',$settings);
        }



        echo'<h2>Emergency Password Reset Main</h2>';
        echo'<p><form class="right" action="https://www.paypal.com/cgi-bin/webscr" method="post"><input type="hidden" name="cmd" value="_s-xclick"><input type="hidden" name="hosted_button_id" value="R7YWSEHFXEU52"><input type="image"  src="https://www.paypal.com/en_GB/i/btn/btn_donate_LG.gif"  name="submit" alt="PayPal - The safer, easier way to pay online."><img alt=""  border="0" src="https://www.paypal.com/en_GB/i/scr/pixel.gif" width="1" height="1"></form></p>';
        if(!empty($_POST['epr-settings']))
        {
            if(!empty($_POST['email_subject']))
            {
                $subject=stripslashes($_POST['email_subject']);
                $settings['email_subject']=$subject;
            }
            if(!empty($_POST['from_name']))
            {
                $from_name=stripslashes($_POST['from_name']);
                $settings['from_name']=$from_name;
            }
            if(!empty($_POST['from_email']))
            {
                $from_email=stripslashes($_POST['from_email']);
                $settings['from_email']=$from_email;
            }
            if(!empty($_POST['message']))
            {
                $message=stripslashes($_POST['message']);
                $settings['message']=$message;
            }
            echo'<div class="notice notice_success"><h2>Settings saved</h2></div>';
            update_option('emergency_password_reset_settings',$settings);
        }
        if(!empty($_POST['emergency_accept']) && check_admin_referer('emergency_reset','emergency_reset'))
        {
			echo'<p>Okay...</p>';
            echo'<p>Password reset emails will be sent in batches of ten to the site admin address with BCC to the user emails</p>';
            emergency_password_reset_now();
            /*
            $results=$wpdb->get_results('SELECT ID FROM '.$wpdb->prefix.'users');
            $numRows=
            if($results){foreach($results AS $row){emergency_password_reset($row->ID);}}
            */
            echo '<h2>'.__('All done','emergency-password-reset').'</h2><p>'.__('Please express your relief and appreciation with a coffee donation!','emergency-password-reset').' <form class="right" action="https://www.paypal.com/cgi-bin/webscr" method="post"><input type="hidden" name="cmd" value="_s-xclick"><input type="hidden" name="hosted_button_id" value="R7YWSEHFXEU52"><input type="image"  src="https://www.paypal.com/en_GB/i/btn/btn_donate_LG.gif"  name="submit" alt="PayPal - The safer, easier way to pay online."><img alt=""  border="0" src="https://www.paypal.com/en_GB/i/scr/pixel.gif" width="1" height="1"></form></p>';
            
        }
		else  if(!empty($_POST['admin_change']) &&!empty($_POST['admin']) && check_admin_referer('admin_change','admin_change'))
		{
			$wpdb->query('UPDATE '.$wpdb->users.' SET user_login="'.esc_sql(sanitize_user(stripslashes($_POST['admin']))).'" WHERE user_login="admin"');
			echo'<div class="updated fade"><p><strong>'.sprintf(__('Admin username reset to %1$s','emergency-password-reset'),sanitize_user(stripslashes($_POST['admin']))).'</strong></p></div>';
			echo'<p><form action="" method="post">';
            echo wp_nonce_field('emergency_reset','emergency_reset');
            echo'<input type="hidden" name="emergency_accept" value="yes"/><input type="submit" class="button-primary" value="'.__('Reset all passwords','emergency-password-reset').'"/></form></p>';
		}
        else
        {
            $settings=get_option('emergency_password_reset_settings');
            echo'<h2>Settings</h2>';
            echo'<p><form action="" method="post">';
            echo wp_nonce_field('emergency_reset','emergency_reset');
            echo'<table class="form-table">';
            echo'<tr><th scope="row">Email subject</th><td><input type="text" name="email_subject" ';
            if(!empty($settings['email_subject'])) echo 'value="'.esc_html($settings['email_subject']).'" ';
            echo'/></td></tr>';
            echo'<tr><th scope="row">Email from name</th><td><input type="text" name="from_name" ';
            if(!empty($settings['from_name'])) echo 'value="'.esc_html($settings['from_name']).'" ';
            echo'/></td></tr>';
            echo'<tr><th scope="row">Email address</th><td><input type="text" name="from_email" ';
            if(!empty($settings['from_email']))
            {
                echo 'value="'.esc_html($settings['from_email']).'" ';
            }
            else
            {
                echo 'value="'.esc_html(get_option('admin_email')).'" ';
            }
            echo'/></td></tr>';
            echo'<tr><th>Message</th><td><textarea name="message">';

            if(!empty($settings['message']))
            {
                echo $settings['message'];
            }
            else
            {
                echo '<p>We have reset the passwords for the website [url]. Your username is still [username], please reset the password to one of your choosing at [link]</p>';
            }
            echo'</textarea></td></tr>';
            echo'</table>';
            echo '<p><input type="hidden" name="epr-settings" value=1"/><input type="submit" class="button-primary" value="'.__('Save settings','emergency-password-reset').'"/></form></p>';
            echo'<h2>Reset now</h2>';
            echo'<p><form action="" method="post">';
            echo wp_nonce_field('emergency_reset','emergency_reset');
            echo'<input type="hidden" name="emergency_accept" value="yes"/><input type="submit" class="button-primary" value="'.__('Reset all passwords','emergency-password-reset').'"/></form></p>';
			
        }
		$sql='SELECT ID FROM '. $wpdb->users.' WHERE user_login="admin"';
		
		$admin=$wpdb->get_var($sql);
		if(!empty($admin))
			{
				echo '<h3>'.__('You still have a user called "admin" - that is inviting to be hacked.','emergency-password-reset').'</h3>';
				echo'<p><form action="" method="post">';
				echo wp_nonce_field('admin_change','admin_change');
				echo'<input type="text" required="required" name="admin" placeholder="'.__('New admin username','emergency-password-reset').'"/><input type="submit" value="'.__('Change admin username','emergency-password-reset').'"/></form></p>';
			
			}
    }
    else{echo '<p>'.__("You don't have permission to use this password reset",'emergency-password-reset').'</p>';}
}
function emergency_password_reset_salts()
{
    echo '<h2>Changing the SALTs</h2>';
    $config_file     = ABSPATH . 'wp-config.php';
    if ( file_exists( $config_file ) && is_writable( $config_file ) ) 
    {
        echo'<p>Located the wp-config.php file and it is writable</p>';
        $old_salts=array('AUTH_KEY'=>AUTH_KEY,
                         'SECURE_AUTH_KEY'=>SECURE_AUTH_KEY,
                         'LOGGED_IN_KEY'=>LOGGED_IN_KEY,
                         'NONCE_KEY'=>NONCE_KEY,
                         'AUTH_SALT'=>AUTH_SALT,
                         'SECURE_AUTH_SALT'=>SECURE_AUTH_SALT,
                         'LOGGED_IN_SALT'=>LOGGED_IN_SALT,
                         'NONCE_SALT'=>NONCE_SALT
                        );
        $new_salts=array();
        foreach($old_salts AS $key=>$value)
        {
            $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()-_ []{}<>~`+=,.;:/?|';
            $max = strlen($chars) - 1;
            $salt = '';
            for ( $j = 0; $j < 64; $j++ ) 
            {
                $salt .= substr( $chars, random_int( 0, $max ), 1 );
            }
                $new_salts[$key] = $salt;
            
        }
        echo'<p>Working...</p>';

        $reading_config  = fopen( $config_file, 'r' );
        $tmp_config_file = ABSPATH . 'wp-config-tmp.php';
        $writing_config = fopen( $tmp_config_file, 'w' );
        while ( ! feof( $reading_config ) ) 
        {
            $line = fgets( $reading_config );
			foreach ( $old_salts as $salt_key => $salt_value ) 
            {
				if ( stristr( $line, $salt_value ) ) {
						$line = str_replace($salt_value,$new_salts[ $salt_key ],$line). "\n";
				}
			}
            fputs( $writing_config, $line );
		}
        fclose( $reading_config );
		fclose( $writing_config );
		rename( $tmp_config_file, $config_file );
        echo'<p>'.__('Done and you are logged out','emergency-password-reset').' ;-)<br/><a class="button-primary" href="https://paypal.me/andymoyle">'.__('Please buy me a coffee!','emergency-password-reset').'</a></p>';
        
    }else{echo '<p>'.__("Unfortunately we can't update wp-config.php",'emergency-password-reset').'</p>';}
}

function emergency_password_reset_now()
{
    global $wpdb;

    $results=$wpdb->get_results('SELECT ID FROM '.$wpdb->prefix.'users',ARRAY_N);
    
    $numRows=$wpdb->num_rows;
    if(!empty($results))
    {
      
        $batches=ceil($numRows/10);
        echo'<h3>No of batches of 10 emails : '.$batches.'</h3>';
        for($y=0;$y<$batches;$y++)
        {
            echo'<h4>Batch #'.($y+1).'</h4>';
            $users=array();
            for($i=0;$i<10;$i++)
            {
                
                if(!empty($results[0][$i+($y*10)]))$users[]=$results[0][$i+($y*10)];
            }
            
            if(!empty($users))emergency_password_reset_batch($users);
        }
    }
   
}



function emergency_password_reset_batch($IDS)
{
    if(!is_array($IDS))return 'No users to reset';
  
    if(current_user_can('manage_options'))
    {
        $settings=get_option('emergency_password_reset_settings');
        
        $BCC=array();
          
    	$reset_link = '<a href="' . wp_lostpassword_url().'">'.__('reset your password','emergency-password-reset').'</a>';
        foreach($IDS AS $key=>$ID)
        {
            $user=get_user_by('id',$ID);
            if(!empty($user))
            {
                $password=wp_generate_password();
                wp_set_password( $password, $user->ID );
                $BCC[]=$user->user_email;
                echo'<p>'.sprintf(__('Password changed for %1$s','emergency-password-reset'),esc_html($user->user_login)).'</p>';
            }
        }
       
        if(!empty($BCC))
        {
            $message=$settings['message'];
            $message=str_replace('[url]',site_url(),$message);
            $message=str_replace('[link]',$reset_link,$message);
            
            $headers=array();
            $headers[] = 'From: '.$settings['from_name'].'<'.$settings['from_email'].'>';
            foreach($BCC AS $key=>$email)
            {
                $headers[]=$headers[] = 'Bcc: '.$email;
            }
            add_filter( 'wp_mail_from_name', 'emergency_password_reset_from_name');
            add_filter( 'wp_mail_from', 'emergency_password_reset_from_email' );
            add_filter('wp_mail_content_type','emergency_password_reset_set_html_mail_content_type');
            if(wp_mail(get_option('admin_email'),$settings['email_subject'],$message,$headers))
            {
                echo '<p>Email sent BCC to '.esc_html(implode(',',$BCC)).'</p>';
            }else
            {
                echo '<p>Email failure BCC to '.esc_html(implode(',',$BCC)).'</p>';
                echo'<pre>';
                print_r($GLOBALS['phpmailer']);
                echo'</pre>';
            }
            remove_filter( 'wp_mail_from_name', 'emergency_password_reset_from_name');
            remove_filter( 'wp_mail_from', 'emergency_password_reset_from_email' );
            remove_filter('wp_mail_content_type','emergency_password_reset_set_html_mail_content_type');
        }
    }
}
function emergency_password_reset_from_name(){ $settings=get_option('emergency_password_reset_settings'); return $settings['from_name'];}
function emergency_password_reset_from_email(){ $settings=get_option('emergency_password_reset_settings'); return $settings['from_email'];}
function emergency_password_reset_set_html_mail_content_type() {
    return 'text/html';
}
// Adding WordPress plugin action links
 
add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'emergency_password_reset_add_plugin_action_links' );
function emergency_password_reset_add_plugin_action_links( $links ) {
 
	return array_merge(
		array(
			'settings' => '<a href="' . get_bloginfo( 'wpurl' ) . '/wp-admin/users.php?page=emergency_password_reset_main">'.__('Reset Passwords','emergency-password-reset').'</a>',
            'salts'=>'<a href="' . get_bloginfo( 'wpurl' ) . '/wp-admin/options-general.php?page=emergency_password_reset%2Findex.php">'.__('Reset SALTs','emergency-password-reset').'</a>'
		),
		$links
	);
 
}

