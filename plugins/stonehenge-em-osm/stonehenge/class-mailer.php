<?php
if( !defined('ABSPATH') ) exit;
use Coduo\PHPHumanizer\StringHumanizer;

if( !class_exists('Stonehenge_Mailer')) :
Class Stonehenge_Mailer extends Stonehenge_Forms {

	private $email;
	private $object;

	#===============================================
	public function mailer_actions() {
		if( !isset($_POST['stonehenge_mailer_nonce']) ) {
			$response = stonehenge()->show_notice( __wp('Invalid form submission.'), 'error');
			wp_send_json( $response );
			exit();
		}

		$class 		= sanitize_text_field( $_POST['email_processor'] );
		$type 	 	= sanitize_text_field( $_POST['email_type'] );
		$processor 	= "process_{$type}";
		$input 		= $_POST[$type];

		$response 	= (new $class)->$processor( $input );
		wp_send_json( $response );
		exit();
	}


	#===============================================
	public function render_email_metabox( $section, $plugin ) {
		$section_id = str_replace('-', '_', $section['id']);
		?>
		<br style="clear:both;">
		<div class="stonehenge-metabox" id="<?php echo esc_attr($section_id); ?>_metabox">
			<div class="stonehenge-metabox-header">
				<h3 class="handle"><?php echo esc_html($section['label']); ?></h3>
			</div>
				<?php echo $this->render_email_form( $section, $plugin ); ?>
		</div>
		<br style="clear:both;">
		<?php
	}


	#===============================================
	public function render_email_form( $section, $plugin ) {
		$section_id 	= str_replace('-', '_', $section['id']);
		$submit_label 	= __em('Send Email');

		?>
		<form id="stonehenge_mailer_form" method="post" action="<?php echo admin_url('admin-ajax.php?action=stonehenge_mailer'); ?>" data-parsley-validate="" novalidate="">
			<div id="stonehenge_mailer_fields">
				<?php wp_nonce_field('stonehenge_mailer_nonce', 'stonehenge_mailer_nonce'); ?>
				<input type="hidden" name="email_processor" value="<?php echo esc_attr($plugin['class']); ?>" readonly>
				<input type="hidden" name="email_type" value="<?php echo esc_attr($section_id); ?>" readonly>
				<section class="stonehenge-section" id="<?php echo esc_attr($section_id); ?>">
					<table class="stonehenge-table">
						<?php
							stonehenge()->render_fields( $section['fields'], $section_id );
							stonehenge()->render_default_fields( $submit_label, $section_id );
						?>
					</table>
				</section>
			</div>
			<div id="stonehenge_mailer_result"></div>
		</form>
		<?php
		return;
	}


	#===============================================
	public function email_settings_used() {
		if( function_exists('em_email_users') ) {
			$name 	= $this->has_email_pro() ? 'EM - Email Users Pro' : 'EM - Email Users (free version)';
			$url 	= admin_url('admin.php?page=em_email_users');
		}

		elseif( method_exists('EM_Mailer', 'send') ) {
			$name 	= 'Events Manager';
			$url 	= is_multisite() ? network_admin_url('admin.php?page=events-manager-options#general') : admin_url('edit.php?post_type=event&page=events-manager-options#emails');
		}

		elseif( method_exists('EDD_Emails', 'send') ) {
			$name 	= 'Easy Digital Downloads';
			$url 	= admin_url('edit.php?post_type=download&page=edd-settings&tab=emails');
		}

		else {
			$name 	= sprintf('WordPress &rArr; %1$s &rArr; %2$s', __wp('Settings'), __wp('General') );
			$url 	= admin_url('options-general.php');
		}

		$used = sprintf( __('This plugin will use the credentials in %s to send emails.', $this->plugin['text']), sprintf('<a href="%1$s" target="_blank">%2$s</a>', $url, $name) );

		if( $this->has_email_pro() ) {
			$used = $used .' '. __('If defined, the header, footer and CSS markup will be automatically applied.', $this->plugin['text']);
		}
		return $used;
	}


	#===============================================
	public function get_from() {
		if( function_exists('em_email_users') ) {
			$saved 	= get_option('em_email_users');
			$name 	= $saved['general']['from_name'];
			$email 	= $saved['general']['from_address'];
		}
		elseif( method_exists('EM_Mailer', 'send') ) {
			$name 	= get_site_option('dbem_mail_sender_name');
			$email 	= get_site_option('dbem_mail_sender_address');
		}
		elseif( method_exists('EDD_Emails', 'send') ) {
			$EDD 	= new EDD_Emails();
			$name 	= $EDD->get_from_name();
			$email	= $EDD->get_from_address();
		}
		else {
			$name 	= get_bloginfo('name');
			$email 	= get_bloginfo('admin_email');
		}

		return array(
			'name' 	=> esc_html($name),
			'email'	=> is_email($email),
		);
	}


	#===============================================
	public function get_smtp($mail) {
		if( function_exists('em_email_users') ) {
			$options = get_option('em_email_users');
			if( isset($options['general']['method']) && $options['general']['method'] === 'smtp' ) {
				$mail->isSMTP();
				$mail->Host 		= $options['general']['smtp_host'];
				$mail->Username 	= $options['general']['smtp_user'];
				$mail->Password 	= $options['general']['smtp_pass'];
				$mail->Port 		= $options['general']['smtp_port'];
				$mail->SMTPAuth 	= true;
				$mail->SMTPAutoTLS 	= true;
				if( isset($options['general']['smtp_encryption']) && 'none' != $options['general']['smtp_encryption'] ) {
					$mail->SMTPSecure = $options['general']['smtp_encryption'];
				}
			}
		}
		elseif( method_exists('EM_Mailer', 'send') ) {
			if( get_option('dbem_rsvp_mail_send_method') === 'smtp' ) {
				$mail->isSMTP();
				$mail->Host 	= get_option('dbem_smtp_host');
				$mail->Port 	= get_option('dbem_rsvp_mail_port');
				$mail->Username = get_option('dbem_smtp_username');
				$mail->Password = get_option('dbem_smtp_password');

				if( get_option('dbem_smtp_encryption') ) {
					$mail->SMTPSecure = get_option('dbem_smtp_encryption');
				}

				if( get_option('dbem_rsvp_mail_SMTPAuth') == '1'){
					$mail->SMTPAuth = true;
			    }
				$mail->SMTPAutoTLS = get_option('dbem_smtp_autotls') == 1;
			}
		}
		return $mail;
	}


	#===============================================
	public function get_subject($subject) {
		$subject = stripslashes( strip_tags($subject) );
		return $this->output($subject);
	}


	#===============================================
	public function get_content($content) {
		$content 		= html_entity_decode( wp_kses_allowed($content) );

		if( $this->has_email_pro() ) {
			$saved 		= get_option('em_email_users');
			$css 		= stonehenge()->minify_css( @$saved['markup']['css'] );
			$header		= wp_kses_allowed( @$saved['markup']['header'] );
			$footer 	= wp_kses_allowed( @$saved['markup']['footer'] );
			$html 		= $header . $content . $footer;
			$content 	= stonehenge()->css_inline($html, $css);
		}

		$content = apply_filters('stonehenge_content', $content); // Allow shortcodes.
		$content = stripslashes($content);
		$content = $this->output($content) . '<p style="clear:both;">&nbsp;</p>'; // Add extra line in case of attachments.
		$content = nl2br($content, false);
		return $content;
	}


	#===============================================
	public function output($string) {
		if( is_object($this->object) && method_exists($this->object, 'output') ) {
			$string = $this->object->output($string);
		}
		return $string;
	}


	#===============================================
	public function get_plain_text($text) {
		$text = StringHumanizer::removeShortcodeTags($text);
		$text = preg_replace( array(
			'@<head[^>]*?>.*?</head>@siu',
			'@<style[^>]*?>.*?</style>@siu',
			'@<script[^>]*?.*?</script>@siu',
			'@<object[^>]*?.*?</object>@siu',
			'@<embed[^>]*?.*?</embed>@siu',
			'@<noscript[^>]*?.*?</noscript>@siu',
			'@<noembed[^>]*?.*?</noembed>@siu',
			'@\t+@siu',
			'@\n+@siu'
			), '', $text
		);
		$text = preg_replace( array('@</?((div)|(h[1-9])|(/tr)|(p)|(pre))@iu'), "\n\$0", $text );
		$text = preg_replace( array('@</((td)|(th))@iu'), " \$0", $text );
		$text = strip_tags( $text );
		return $text;
	}


	#===============================================
	public function get_attachment($input) {
		if( filter_var($input, FILTER_VALIDATE_URL) === false ) {
		    return $input;
		}

		$path = get_attached_file( (int) attachment_url_to_postid( esc_url_raw($input) ) );
		if( file_exists($path) ) {
			return $path;
		}

		return false;
	}


	#===============================================
	public function send_email( $input, $object ) {
		$this->email 	= $input;
		$this->object 	= $object;

		$input 	= apply_filters('stonehenge_mailer_init', $input, $object);

		global $wp_version;
		if( $wp_version < '5.5') {
			require_once(ABSPATH . WPINC . '/class-phpmailer.php');
			require_once(ABSPATH . WPINC . '/class-smtp.php');
			$mail = new PHPMailer( true );
		}
		else {
			require_once(ABSPATH . WPINC . '/PHPMailer/PHPMailer.php');
			require_once(ABSPATH . WPINC . '/PHPMailer/SMTP.php');
			require_once(ABSPATH . WPINC . '/PHPMailer/Exception.php');
			$mail = new PHPMailer\PHPMailer\PHPMailer( true );
		}

		try {
			$mail->isMail();
			$mail->isHTML(true);

			if( isset($input['from_email']) && is_email($input['from_email']) ) {
				$mail->setFrom( $input['from_email'], $input['from_name'] );
			} else {
				$from = $this->get_from();
				$mail->setFrom( $from['email'], $from['name'] );
			}

			$mail->addAddress( strtolower(is_email($input['recipient'])) );
			$mail->Sender 		= $mail->From;
			$mail->ReturnPath 	= $mail->Sender;
			$mail->CharSet 		= get_bloginfo('charset');

			$mail->Subject 		= $this->get_subject($input['subject']);
			$mail->Body    		= $this->get_content($input['content']);
			$mail->AltBody 		= $this->get_plain_text($mail->Body);

			if( isset($input['reply_to']) ) {
				$owner 		= html_entity_decode($object->output("#_CONTACTEMAIL"));
				$reply_to 	= is_email($input['reply_to']) ? $input['reply_to'] : $owner;
				$mail->addReplyTo( strtolower($reply_to) );
			}

			if( isset($input['attachment']) && !empty($input['attachment']) ) {
				$mail->addAttachment( $this->get_attachment($input['attachment']) );
			}

			// Add CC Recipients.
			if( isset($input['cc']) && !empty($input['cc']) ) {
				if( is_array($input['cc']) ) {
					foreach( $input['cc'] as $cc ) {
						$mail->addCC( strtolower($cc) );
					}
				} else {
					$mail->addCC( strtolower($input['cc']) );
				}
			}

			// Add BCC Recipients.
			if( isset($input['bcc']) && !empty($input['bcc']) ) {
				if( is_array($input['bcc']) ) {
					foreach( $input['bcc'] as $bcc ) {
						$mail->addBCC( strtolower($bcc) );
					}
				} else {
					$mail->addBCC( strtolower($input['bcc']) );
				}
			}

			$this->get_smtp($mail);

			$mail = apply_filters('stonehenge_mailer_before_send', $mail, $object);

			if( $mail ) {
				$mail->Send();
				$result = stonehenge()->show_notice( __wp('Email sent.'), 'success');
			} else {
				$result = stonehenge()->show_notice( __wp('This action has been disabled by the administrator.'), 'info');
			}
		}
		catch( phpmailerException $e ) {
			error_log( "Stonehenge_Email error: " . print_r( strip_tags($e->errorMessage()), true) );
			$result = stonehenge()->show_notice( strip_tags($e->errorMessage()), 'error');
		}

		return $result;
	}

} // End class.
endif;
