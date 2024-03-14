<?php

class PeepSoConfigEmails
{
	private static $_instance = NULL;

	private $aEmails = array();

	private function __construct()
	{
		add_action('peepso_admin_config_save-email-reset', array(&$this, 'reset_emails'),1);
		add_action('peepso_admin_config_save-email', array(&$this, 'save_config'),10);
		add_action('peepso_admin_config_tab-email', array(&$this, 'output_form'),10);

		$this->aEmails = array(
			'email_new_user' => array(
				'title' => __('New User Email', 'peepso-core'),
				'description' => __('This will be sent to new users upon completion of the registration process', 'peepso-core')),
			'email_new_user_no_approval' => array(
				'title' => __('New User Email (No Account Verification)', 'peepso-core'),
				'description' => __('This will be sent to new users upon completion of the registration process when Account Verification is disabled', 'peepso-core')),
			'email_new_user_no_password' => array(
				'title' => __('New User Email (No Password Fields)', 'peepso-core'),
				'description' => __('This will be sent to new users upon completion of the registration when the "Hide password fields" option is enabled', 'peepso-core')),
            'email_user_approved' => array(
                'title' => __('Account Approved', 'peepso-core'),
                'description' => __('This will be sent when an Admin approves a user registration.', 'peepso-core')),
            'email_notification_digest' => array(
                'title' => __('Notification digest', 'peepso-core'),
                'description' => __('This will be sent if user has unread notifications and is not receiving real-time emails.', 'peepso-core')),
			'email_activity_notice' => array(
				'title' => __('Activity Notice', 'peepso-core'),
				'description' => __('This will be sent when someone interacts with a user\'s Activity Stream', 'peepso-core')),
			'email_like_post' => array(
				'title' => __('Like Post', 'peepso-core'),
				'description' => __('This will be sent when a user "likes" another user\'s post', 'peepso-core')),
			'email_user_comment' => array(
				'title' => __('User Comment', 'peepso-core'),
				'description' => __('This will be sent to a post owner when another user comments on the post', 'peepso-core')),
			'email_user_reply_comment' => array(
				'title' => __('User Reply Comment', 'peepso-core'),
				'description' => __('This will be sent to a comment owner when another user comments on the comment', 'peepso-core')),
			'email_like_comment' => array(
				'title' => __('Like User Comment', 'peepso-core'),
				'description' => __('This will be sent when a user "likes" another user\'s comments on the post', 'peepso-core')),
			'email_wall_post' => array(
				'title' => __('Wall Post', 'peepso-core'),
				'description' => __('This will be sent when a user writes on another user\'s wall', 'peepso-core')),
			'email_share' => array(
				'title' => __('Share Post', 'peepso-core'),
				'description' => __('This will be sent when a user "shared" another user\'s post', 'peepso-core')),
			'email_password_recover' => array(
				'title' => __('Forgot Password', 'peepso-core'),
				'description' => __('This will be sent when a user requests a password recovery', 'peepso-core')),
			'email_password_changed' => array(
				'title' => __('Password Changed', 'peepso-core'),
				'description' => __('This will be sent when a user changes their password after recovery', 'peepso-core')),
			'email_like_profile' => array(
				'title' => __('Like Profile', 'peepso-core'),
				'description' => __('This will be sent when a user "likes" another user\'s profile', 'peepso-core')),
            'email_new_user_registration' => array(
                'title' => __('New User Registration', 'peepso-core'),
                'description' =>
                    __('This will be sent to admin user when new user needs approval', 'peepso-core')
                    ."<br>"
                    . __('These emails will be sent to the email account setup in WordPress settings. <a href="options-general.php" target="_blank">You can change it here</a>.', 'peepso-core')),
            'email_reported_content' => array(
                'title' => __('New Content Reported', 'peepso-core'),
                'description' =>
                    __('This will be sent to Administrators and Community Administrators when content is reported', 'peepso-core')
            ),
			'email_tagged' => array(
				'title' => __('User Mentioned In Post', 'peepso-core'),
				'description' => __('This will be sent to a user when mentioned in a post.', 'peepso-core')),
			'email_tagged_comment' => array(
				'title' => __('User Mentioned In Comment', 'peepso-core'),
				'description' => __('This will be sent to a user when mentioned in a comment.', 'peepso-core')
			),
			'email_export_data_complete' => array(
				'title' => __('Export Data Complete', 'peepso-core'),
				'description' => __('This will be sent to a user when export data is completed.', 'peepso-core')	
			),
			'email_failed_login_attempts' => array(
				'title' => __('Failed Login Attempts', 'peepso-core'),
				'description' => __('This will be sent to user when failed to login after several attempts.', 'peepso-core')	
			)
		);

		if(isset($_REQUEST['reset'])) {
			do_action('peepso_admin_config_save-email-reset');
		}

		$this->aEmails = apply_filters('peepso_config_email_messages', $this->aEmails);
	}

	// Outputs the config form
	public function output_form()
	{
		if (isset($_REQUEST['peepso-config-nonce']) &&
			wp_verify_nonce($_REQUEST['peepso-config-nonce'], 'peepso-config-nonce')) {
			do_action('peepso_admin_config_save');
		}

		$adm = PeepSoAdmin::get_instance();
		$adm->admin_notices();


		$cfg = PeepSoConfig::get_instance();
		$cfg->render_tabs();

		wp_enqueue_script('peepso-admin-config');



		echo '<form action="', admin_url('admin.php?page=peepso_config&tab=email'), '" method="post" >';
		echo '<input type="hidden" name="peepso-email-nonce" value="', wp_create_nonce('peepso-email-nonce'), '"/>';
        $language = NULL;

        if(PeepSo::is_dev_mode('trp_emails') && PeepSo3_Third_Party::has_multilingual_trp()) {
            if(isset($_REQUEST['language'])) {
                $language = strtolower($_REQUEST['language']);
            }

            $trp_settings = get_option('trp_settings');
            $wp_languages = wp_get_available_translations();


            if(is_array($trp_settings) && array_key_exists('translation-languages', $trp_settings) && is_array($trp_settings['translation-languages'])) {
                $trp_languages = $trp_settings['translation-languages'];
                $default = array_shift($trp_languages);
                $bold ='';
                $bold_style = "font-weight:bold";
                if(!strlen($language)) {
                    $bold = $bold_style;
                }

                echo '<ul><li><a style="'.$bold.'" href="' . admin_url('admin.php?page=peepso_config&tab=email').'">Default</a></li>';

                foreach($trp_languages as $trp_language) {
                    $bold ='';
                    if($language == strtolower($trp_language)) {
                        $bold = $bold_style;
                    }
                    $label = $trp_language;
                    if(is_array($wp_languages) && array_key_exists($trp_language, $wp_languages)) {
                        $label = $wp_languages[$trp_language]['english_name'];
                    }
                    echo '<li><a style="'.$bold.'" href="' . admin_url('admin.php?page=peepso_config&tab=email&language='.strtolower($trp_language)).'">'.$label.'</a></li>';
                }
                echo '</ul>';
            }

        }

        ?>

        <input type="hidden" name="language" value="<?php echo esc_attr($language);?>" />

		<style type="text/css">
		 #reset-do:disabled  {
			 opacity: 0.5;
		 }
		</style>
		<div id="tokens" class="meta-box-sortables col-xs-4 col-sm-4" style="float:right; margin-right:0">
			<div class="postbox">
				<div class="inside">
					<h3><?php echo __('Reset all emails', 'peepso-core');?></h3>
					<p>
					<?php
					echo __('This will  reset all email templates to default values', 'peepso-core');
					#echo ' v'.PeepSo::PLUGIN_VERSION;
					?>
					</p>

					<p>
						<label>
							<input type="checkbox" id="reset-check" /> <?php echo __('Yes, I\'m sure!', 'peepso-core');?>
						</label>
					</p>
					<button disabled id="reset-do">Reset all emails to default</button>
				</div>
			</div>
			<div class="postbox">
				<div class="inside">
					<h3><?php echo __('Allowed Tokens', 'peepso-core');?></h3>

					<?php echo __('The following tokens can be used within the content of emails:', 'peepso-core');?>
					<ul>
						<li>{date} - <?php echo __('Current date in the format that WordPress displays dates.', 'peepso-core');?></li>
						<li>{datetime} - <?php echo __('Current date and time in the format that WordPress displays dates with time.', 'peepso-core');?></li>
						<li>{sitename} - <?php echo __('Name of your site from the WordPress title configuration.', 'peepso-core');?></li>
						<li>{siteurl} - <?php echo __('URL of your site.', 'peepso-core');?></li>
						<li>{unsubscribeurl} - <?php echo __('URL to receiving user\'s Alert Configuration page.', 'peepso-core');?></li>
						<li>{year} - <?php echo __('The current four digit year.', 'peepso-core');?></li>
						<li>{permalink} - <?php echo __('Link to the post, comment or other item referenced; context specific.', 'peepso-core');?></li>
						<li>{thatuseremail} - <?php echo __('Pending user\'s email address.', 'peepso-core');?></li>
					</ul>

					<?php echo __('These are referring to the user causing the alert, such as "{fromlogin} liked your post...":', 'peepso-core');?>
					<ul>
						<li>{fromemail} - <?php echo __('Message sender\'s email address.', 'peepso-core');?></li>
						<li>{fromfullname} - <?php echo __('Message sender\'s full name.', 'peepso-core');?></li>
						<li>{fromfirstname} - <?php echo __('Message sender\'s first name.', 'peepso-core');?></li>
						<li>{fromlogin} - <?php echo __('Message sender\'s username.', 'peepso-core');?></li>
					</ul>

					<?php echo __('These are referring to the receiving user on all messages, such as "Welcome {userfirstname}...":', 'peepso-core');?><br/>
					<ul>
						<li>{useremail} - <?php echo __('Message recipient\'s email address.', 'peepso-core');?></li>
						<li>{userfullname} - <?php echo __('Message recipient\'s full name', 'peepso-core');?></li>
						<li>{userfirstname} - <?php echo __('Message recipient\'s first name', 'peepso-core');?></li>
						<li>{userlogin} - <?php echo __('Message recipient\'s username', 'peepso-core');?></li>
						<li>{userpassword} - <?php echo __('Message recipient\'s password', 'peepso-core');?></li>
					</ul>
				</div>
			</div>

		</div>
		<div id="peepso" class="col-xs-8 col-sm-8">
			<div id="left-sortables" class="meta-box-sortables">
		<?php
		foreach ($this->aEmails as $name => $aData) {
			echo '<div class="postbox">', PHP_EOL;

			echo '<div class="handlediv" title="Click to toggle"><br></div>', PHP_EOL;
			echo '<h3 class="hndle"><span>', $aData['title'], '</span></h3>', PHP_EOL;
			echo	'<div class="inside">', PHP_EOL;
			echo		'<div class="form-group">', PHP_EOL;
			echo			'<p>', $aData['description'], '</p>', PHP_EOL;
			echo			'<label id="', $name, '-label" for="', $name, '" class="form-label  control-label col-sm-3">', $aData['title'], ':</label>', PHP_EOL;
			echo			'<div class="form-field controls col-sm-8">', PHP_EOL;

			$data = 'Email contents';
            $data_translated = '';

            // attempt loading translated version
            if($language) {
                $data_translated = get_option('peepso_' . $name.'_'.$language, '');
            }

            if(strlen($data_translated)) {
                $data = $data_translated;
            } else {
                $data = get_option('peepso_' . $name, $data);
            }

            if($language) {
                $name = $name.'_'.$language;
            }

            //echo $name;
			echo			'<div xclass="col-sm-7">', PHP_EOL;
			echo				'<textarea name="', esc_attr($name), '" class="email-content">', esc_textarea($data), '</textarea>', PHP_EOL;
			echo				'<span class="lbl"></span>', PHP_EOL;
			echo			'</div>', PHP_EOL;

			echo		'</div>', PHP_EOL;		// .form-group
			echo	'</div>', PHP_EOL;			// .inside
			echo	'<div class="clearfix"></div>', PHP_EOL;
			echo '</div>', PHP_EOL;				// .handlediv
			echo '</div>', PHP_EOL;				// .postbox
		}
//

		echo '<div width="100%" style="display:block; clear:both; text-align:center">', PHP_EOL;
		echo '<button name="save-email" class="btn btn-info" type="submit">';
		echo	'<i class="ace-icon fa fa-check bigger-110"></i>';
		echo	'Save';
		echo '</button>', PHP_EOL;
		echo '</div>', PHP_EOL;

//		echo '</div>', PHP_EOL;		// .postbox
////////

		echo '</div>', PHP_EOL;		// .meta-box-sortables

		echo '</div>', PHP_EOL;		// outer column
		echo '</form>', PHP_EOL;
	}

	// Return the singleton instance of PeepSoConfigEmails
	public static function get_instance()
	{
		if (NULL === self::$_instance)
			self::$_instance = new self();
		return (self::$_instance);
	}

	// Saves config to options
	public function save_config()
	{
		$input = new PeepSoInput();
		$updated = FALSE;

		if (isset($_POST['save-email'])) {
            $language = '';
            if(isset($_POST['language'])) {
                $language = strtolower($_POST['language']);
            }
			foreach (array_keys($this->aEmails) as $email_name) {

                if(strlen($language)){
                    $email_name = $email_name .'_'.$language;
                }
				$contents = $input->value($email_name,'',false);//SQL safe, admin only
				$contents = PeepSoSecurity::strip_content($contents);

				update_option('peepso_' . $email_name, $contents);
				$updated = TRUE;
			}
		}

		if ($updated) {
			$adm = PeepSoAdmin::get_instance();
			$adm->add_notice(__('Email contents updated.', 'peepso-core'), 'note');
		}
	}

	public function reset_emails()
	{
		require_once(dirname(__FILE__) . DIRECTORY_SEPARATOR . '../install' . DIRECTORY_SEPARATOR . 'activate.php');
		$install = new PeepSoActivate();
		$defaults = $install->get_email_contents();

		$defaults = apply_filters('peepso_config_email_messages_defaults', $defaults);
		$this->aEmails = apply_filters('peepso_config_email_messages', $this->aEmails);

		foreach (array_keys($this->aEmails) as $email_name) {
			$contents = $defaults[$email_name];
			$contents = PeepSoSecurity::strip_content($contents);

			update_option('peepso_' . $email_name, $contents);
			$updated = TRUE;
		}

		if ($updated) {
			$adm = PeepSoAdmin::get_instance();
			$adm->add_notice(__('Email contents reset.', 'peepso-core'), 'note');
			PeepSo::redirect('admin.php?page=peepso_config&tab=email');
			die();
		}
	}

	public function get_emails()
	{
		return $this->aEmails;
	}
}

// EOF
