<?php
/*
Plugin Name: BotBlocker
Plugin URI: http://www.lform.com/botblocker/
Description: Kills spam-bots, leaves humans standing. No CAPTCHAS, no math questions, no passwords, just spam blocking that stops comment spam-bots dead in their tracks.
Version: 1.0.4
Author: Lform Design (Brandon Fenning)
Author URI: http://www.lform.com/botblocker/
License: GPLv2
*/

/* 
 * TODO: add complete obscusfication
 * TODO: add themeable error page hook option
 * TODO: make honey pot togglable
 * TODO: add unit tests
 * 
 */

session_start();
new BotBlocker_plugin();

class BotBlocker_plugin {
	 
	protected $options;	
	protected $isSpam = FALSE;
	protected $disabled = FALSE;
	
	protected $debug = FALSE;
	protected $msg;
		
	function __construct() {
		add_action( 'admin_init', array( &$this, 'adminInit' ) );
		register_activation_hook( __FILE__, array( &$this, 'activate' )  );
		add_filter('comment_form_default_fields', array( &$this, 'generateHoneypot' ),1);
		
		add_filter('preprocess_comment', array( &$this, 'validateComment' ));
		add_action('init', array( &$this, 'preprocessComment' ));
		add_action('wp_enqueue_scripts', array( &$this, 'addStylesheet' ));
		add_action('admin_menu', array( &$this, 'adminSettingsPage' ));
		$plugin = plugin_basename(__FILE__);
		add_filter("plugin_action_links_$plugin", array( &$this, 'adminSettingsLink' ) );
		add_action('comment_post', array( &$this, 'commentSaveAction' ));
		add_filter('pre_comment_approved',array( &$this, 'commentApproval' ),  '99', 2 );
		add_action('comment_form_top', array( &$this, 'printError' ));
		add_action('pre_comment_on_post', array( &$this, 'preventRawSubmit' ));
		
		
		$this->options = $this->getWpOptions();		
		$this->msg = &$_SESSION['_spamMsg'];		
	
		// # Used to access user data
		//global $current_user;
		//get_currentuserinfo();					
	}
	
	// # Prevents spammers from directly submitting comments to wp-comments-post.php
	function preventRawSubmit() {
		$fieldName = $this->getHoneyPotName();
		if (!$this->disabled && !isset($_POST[$fieldName])) {
			$this->isSpam = TRUE;
			wp_die(__('<strong>ERROR</strong>: '.$this->getErrorMsg()));
		}
		
	}
	
	function printError() {
		$options = $this->getOptions();

		if ($this->msg && $options['honeypot_error_type'] == 'Reload') {
			echo '<div class="_errorMsg"><span>'.implode(' ', $this->msg).'</span></div>';
			$this->msg = FALSE;
		}
	}
	
	function logError($data) {
		if ($data) {
			$this->msg[] = $data;
		}
	}
	
	function getErrorMsg() {
		
		$options = $this->getOptions();	

		if ($options['honeypot_reaction'] == 'Block') {
			$approved = '0';
			$errorMsg = $options['honeypot_error_msg_block'];
		}
		else if ($options['honeypot_reaction'] == 'Spam') {
			$approved = 'spam';
			$errorMsg = $options['honeypot_error_msg_flag'];
		}
		else if ($options['honeypot_reaction'] == 'Hold') {
			$approved = '0';
			$errorMsg = $options['honeypot_error_msg_hold'];
		}

		return ($options['honeypot_error_msg'].' '.$errorMsg);
		
	}
	
	function commentApproval($approved, $commentData) {
		
		if ($this->isSpam == TRUE) {
			
			$options = $this->getOptions();	
			/*
			if ($options['honeypot_reaction'] == 'Block') {
				$approved = '0';
				$errorMsg = $options['honeypot_error_msg_block'];
			}
			else if ($options['honeypot_reaction'] == 'Spam') {
				$approved = 'spam';
				$errorMsg = $options['honeypot_error_msg_flag'];
			}
			else if ($options['honeypot_reaction'] == 'Hold') {
				$approved = '0';
				$errorMsg = $options['honeypot_error_msg_hold'];
			}

			$this->logError($options['honeypot_error_msg'].' '.$errorMsg);
			*/
			
			$this->logError($this->getErrorMsg());
			
			if ($options['honeypot_error_type'] == 'Die') {
				if (!$options['honeypot_error_msg']) {
					$options['honeypot_error_msg'] = 'spam-bot detected.';
				}
				$msg = $this->msg;
				$this->msg = FALSE;
				wp_die(__('<strong>ERROR</strong>: '.implode(' ', $msg)));
			}
			else if ($options['honeypot_error_type'] == 'Silent') {
			}
		}		
		
		return $approved;
	}
	
	function commentSaveAction($comment_id) {
		
		$options = $this->getOptions();
		
		// # Fires if WP_die not called
		if ($this->isSpam == TRUE) {
			if ($options['honeypot_reaction'] == 'Block') {
				wp_delete_comment($comment_id, TRUE);			
			}				
		}
		return FALSE;
	}
	
	function getHoneyPotName() {
		$options = $this->getOptions();
		
		$additionalRandom = '';
		if ($options['honeypot_random'] == 'Yes') {
			$additionalRandom = $this->getAdditionalRandom();		 
		}
		
		if ($options['honeypot_method'] == 'Smart') {
			$todaysDecoy = $this->getTodaysDecoy($options['fields']);
			$honeypotName = $additionalRandom.$todaysDecoy;
		}
		else if ($options['honeypot_method'] == 'Static') {
			$honeypotName = $additionalRandom.$options['honeypot_field'];
		}
		else {
			$todaysRandom = $this->getTodaysRandom();
			$honeypotName = $additionalRandom.$todaysRandom;
		}
		return $honeypotName;
	}
	
	function generateHoneypot($fields) {
		$options = $this->getOptions();

		// # Update field cache
		$fieldList = array_keys($fields);
		$fieldList = array_combine($fieldList, $fieldList);
		if ($fieldList != $options['fields']) {
			$options['fields'] = $fieldList;
			update_option("BotBlocker_options", $options);			
		}

		$additionalRandom = '';
		if ($options['honeypot_random'] == 'Yes') {
			$additionalRandom = $this->getAdditionalRandom();		 
		}

		if ($options['obfuscation'] == 'Swap Email and Name') {
			$fields['author'] = preg_replace('/name="author"/i', 'name="'.$additionalRandom.'email"', $fields['author']);
			$fields['email'] = preg_replace('/name="email"/i', 'name="'.$additionalRandom.'author"', $fields['email']);
		}
		else if ($options['obfuscation'] == 'Completely Obfuscate') {
			// # Todo: Add ability to completely obfuscate the field names with random characters
		}
		
		$honeypotName = $this->getHoneyPotName();
		
		/*
		if ($options['honeypot_method'] == 'Smart') {
			$todaysDecoy = $this->getTodaysDecoy($fields);
			$honeypotName = $additionalRandom.$todaysDecoy;
		}
		else if ($options['honeypot_method'] == 'Static') {
			$honeypotName = $additionalRandom.$options['honeypot_field'];
		}
		else {
			$todaysRandom = $this->getTodaysRandom();
			$honeypotName = $additionalRandom.$todaysRandom;
		}
		 */
		

		$honeyClass = '';
		$honeyStyle = '';
		$honeyJs = '';
		
		if ($this->debug != TRUE) {
			if ($options['honeypot_hide'] == 'CSS') {
				$honeyClass = '_hidden hide';
			}
			else if ($options['honeypot_hide'] == 'Inline-CSS') {
				$honeyStyle = 'style="display:none;"';
			}
			else if ($options['honeypot_hide'] == 'Javascript') {
				$honeyJs = '<script>jQuery("#'.$honeypotName.'").hide();</script>';
			}
		}

		$fields[$honeypotName] = '<input type="text" name="'.$honeypotName.'" id="'.$honeypotName.'" value="" class="'.$honeyClass.'" '.$honeyStyle.' />'.$honeyJs;	 

		return $fields;
	}
	
	function preprocessComment() {
		//# Disable plugin for logged in users so theyre not flagged as spam.
		if (is_user_logged_in()) {
			$this->disabled = TRUE;
		}
		
		$additionalRandom = '';
		$options = $this->getOptions();
		if ($options['honeypot_random'] == 'Yes') {
			$additionalRandom = $this->getAdditionalRandom();		 
		}

		// # If form obscusfication is enabled
		if ($options['obfuscation'] == 'Swap Email and Name') {
			$comment_author = ( isset($_POST[$additionalRandom.'email']) )  ? trim(strip_tags($_POST[$additionalRandom.'email'])) : null;
			$comment_author_email = ( isset($_POST[$additionalRandom.'author']) )   ? trim($_POST[$additionalRandom.'author']) : null;

			if (isset($_POST[$additionalRandom.'author'])) {
				$_POST['author'] = $comment_author;
			}

			if (isset($_POST[$additionalRandom.'email'])) {
				$_POST['email'] = $comment_author_email;
			}
		}
		else if ($options['obfuscation'] == 'Completely Obfuscate') {
			// # Todo: Add ability to completely obfuscate the field names with random characters
		}
	}
	
	function validateComment($comment) {
		$additionalRandom = '';
		$options = $this->getOptions();
		if ($options['honeypot_random'] == 'Yes') {
			$additionalRandom = $this->getAdditionalRandom();		 
		}

		if ($options['honeypot_method'] == 'Smart') {
			$todaysDecoy = $this->getTodaysDecoy($fields);
			$honeypotField = $additionalRandom.$todaysDecoy;
		}
		else if ($options['honeypot_method'] == 'Static') {
			$honeypotField = $additionalRandom.$options['honeypot_field'];
		}
		else {
			$todaysRandom = $this->getTodaysRandom();
			$honeypotField = $additionalRandom.$todaysRandom;		 
		}

		$isSpam = FALSE;
		if (!isset($_POST[$honeypotField])) {
			$isSpam = TRUE;
		}
		else if ($_POST[$honeypotField] != '') {
			$isSpam = TRUE;
		}

		if ($isSpam) {
			$this->isSpam = TRUE;
		}
		
		if ($this->disabled == TRUE) {
			$this->isSpam = FALSE;	// # Logged in users not flagged as spam.
		}

		// # Used to access user data
		//global $current_user;
		//get_currentuserinfo();
		
		//if (is_user_logged_in()) { 
		//	$this->isSpam = FALSE;			
		//}
		
		return $comment;
	}
	
	function getDecoyFields($fields = FALSE) {
		$decoyFields = array(
			'address',
			'suite',
			'company',
			'phone',
			'title',
			'city',
			'state',
			'fax',
			'newsletter',
			'webites',
			'zipcode',
			'address2',
			'firstname',
			'lastname',
			'birthday'
		);

		if ($fields && is_array($fields)) {
			foreach ($decoyFields as $index=>$decoy) {
				if (isset($fields[$decoy])) {
					unset($decoyFields[$index]);
				}
			}
		}

		sort($decoyFields);
		return $decoyFields;
	}
	
	function getAdditionalRandom() {
		$options = $this->getOptions();
		$additionalRandom = substr(sha1(date('Y-m-d').$options['seed']),0,6);
		return $additionalRandom;
	}
	
	function getTodaysDecoy($fields) {
		$options = $this->getOptions();
		$decoyFields = $this->getDecoyFields($fields);
		$max = count($decoyFields);
		srand($options['seed'].date('Ymd'));
		$randDecoyIndex = rand(0,$max);
		return $decoyFields[$randDecoyIndex];
	}

	function getTodaysRandom() {
		$options = $this->getOptions();
		srand($options['seed'].date('Ymd'));
		$number = rand(0,9999999);
		$hash = substr(sha1($number),0,8);
		return $hash;
	}
	
	function adminSettingsLink($links) { 
		$settings_link = '<a href="options-general.php?page=BotBlocker_options">Settings</a>'; 
		array_unshift($links, $settings_link); 
		return $links; 
	}

	function adminValidate($input) {	
		return $input;
	}
	
	function adminInit() {
		register_setting( 'BotBlocker_options', 'BotBlocker_options', array( &$this, 'adminValidate' ));
	}
	
	function adminSettingsPage() {
		add_options_page('BotBlocker Settings', 'BotBlocker', 'manage_options', 'BotBlocker_options', array( &$this, 'adminSettingsPageForm' ));
	}
	
	function addStylesheet() {	
		$pluginPath = WP_PLUGIN_URL.'/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__));
		wp_register_style( 'BotBlockerCSS', $pluginPath.'/styles.css');
		wp_enqueue_style('BotBlockerCSS');
	}

	function activate() {
		$this->getWpOptions();
	}
	
	function getWpOptions() {
		// Get options
		$options = get_option('BotBlocker_options');

		// options exist? if not set defaults
		if ( !is_array($options) ) {
			$options = array(
				'honeypot_method'=>'Smart',
				'honeypot_field'=>'field',
				'honeypot_random'=>'Yes',
				'honeypot_hide'=>'CSS',
				'honeypot_error_type'=>'Die',
				'honeypot_error_msg'=>'Spam bot detected.',
				'honeypot_error_msg_block' => 'Your comment was blocked from being posted.',
				'honeypot_error_msg_flag' => 'Your comment was marked as spam and is being held for moderation.',
				'honeypot_error_msg_hold' => 'Your comment is being held for moderation.',
				'honeypot_reaction'=>'Block',
				'obfuscation'=>'Swap Email and Name',
				'seed'=>rand(0,99999999),
				'fields'=>array(), // # Caches fields for smart system
			);
			add_option("BotBlocker_options",$options);
		}
		if (!isset($options['seed'])) {
			$options['seed'] = rand(0,99999);
			add_option("BotBlocker_options",$options);
		}
		$this->options = $options;
		return $options;
	}
	
	function getOptions($field = FALSE) {
		if ($field) {
			return $this->options['field'];
		}
		else {
			return $this->options;
		}
	}
	
	function adminSettingsPageForm() {
		?>
		<div class="wrap spam-form">
			<h2>BotBlocker Settings</h2>
			<form method="post" action="options.php">
				<?php settings_fields('BotBlocker_options'); ?>
				<?php 
				$options= $this->getOptions(); 
				?>
				<table class="form-table">
					<tr valign="top">
						<td scope="row" colspan="2" class="heading">
							<h3>Honeypot Options</h3>
							<p>While BotBlocker works fine out of the box, the honeypot can be configured a couple different ways:</p>
						</td>
					</tr>

					<tr valign="top">
						<th scope="row">Method</th>
						<td>
							<select name="BotBlocker_options[honeypot_method]">
								<option value="Smart" <?php  selected($options['honeypot_method'], 'Smart'); ?> />Smart</option>
								<option value="Static" <?php  selected($options['honeypot_method'], 'Static'); ?> />Static</option>							
								<option value="Random" <?php  selected($options['honeypot_method'], 'Random'); ?> />Random</option>
							</select><br>							
							<ul class="help">							
								<li>The <b>Smart</b> method chooses from a list of decoy fields, while ensuring it doesn't conflict with any fields you may have added, changes daily.<br>
									<small>Decoy List: <? echo implode(', ', $this->getDecoyFields()) ?></small></li>
								<li>The <b>Static</b> method will use a set name for the field every time.</li>
								<li>The <b>Random</b> method will generate a string of alpha-numeric characters for the field name, changes daily.</li>
							</ul>
						</td>					
					</tr>		
					<tr valign="top"><th scope="row">Static Field Name</th>
						<td><input type="text" name="BotBlocker_options[honeypot_field]" value="<?php echo $options['honeypot_field']; ?>" size="25" />
							<br><small>If the method is set to <b>Static</b>, this will be the name of the honeypot field.</small></td>
					</tr>
					<tr valign="top">
						<th scope="row">How should spam-bots be handled?</th>
						<td>
							<select name="BotBlocker_options[honeypot_reaction]">
								<option value="Block" <?php  selected($options['honeypot_reaction'], 'Block'); ?> />Block</option>
								<option value="Spam" <?php  selected($options['honeypot_reaction'], 'Spam'); ?> />Flag as Spam</option>
								<option value="Hold" <?php  selected($options['honeypot_reaction'], 'Hold'); ?> />Hold for Approval</option>
							</select>
							<br>
							<ul class="help">							
								<li><b>Block</b> will prevent the comment from being submitted</li>
								<li><b>Flag as spam</b> will hold it for moderation in the spam queue.</li>
								<li><b>Hold for approval</b> will require the comment to be approved by a moderator.</li>
							</ul>
						</td>					
					</tr>
					
					<tr valign="top">
						<th scope="row">Spam-bot Error Action</th>
						<td>
							<select name="BotBlocker_options[honeypot_error_type]">
								<option value="Die" <?php  selected($options['honeypot_error_type'], 'Die'); ?> />Show Error</option>	
								<option value="Reload" <?php  selected($options['honeypot_error_type'], 'Reload'); ?> />Show Error on Page</option>
								<option value="Silent" <?php  selected($options['honeypot_error_type'], 'Silent'); ?> />Silent</option>
							</select>
							<br>
							<ul class="help">							
								<li><b>Show Error</b> will use the default Wordpress error pages to display any messages. (Default)</li>
								<li><b>Show Error on Page</b> will show any messages at the top of the comment form where it was submitted.</li>
								<li><b>Silent</b> will cause BotBlocker to perform its blocking actions without displaying any errors or alerts.</li>
							</ul>						
						</td>					
					</tr>
					<tr valign="top">
						<th scope="row">Error Messages</th>
						<td>
							<table width="100%">
								<tr valign="top">
									<td>Global Message</td><td><input type="text" name="BotBlocker_options[honeypot_error_msg]" value="<?php echo $options['honeypot_error_msg']; ?>" size="50" />
							<br><small>If using <b>Show Error</b>, this is the message that will appear for spam bots.</small></td>									
								</tr>
								<tr valign="top">
									<td>Blocked Message</td><td><input type="text" name="BotBlocker_options[honeypot_error_msg_block]" value="<?php echo $options['honeypot_error_msg_block']; ?>" size="50" />
									<br><small>If using <b>Show Error</b> & <b>Block</b> on spam-bots, this is the extra message that will appear.</small></td>									
								</tr>
								<tr valign="top">
									<td>Flagged Message</td><td><input type="text" name="BotBlocker_options[honeypot_error_msg_flag]" value="<?php echo $options['honeypot_error_msg_flag']; ?>" size="50" />
									<br><small>If using <b>Show Error</b> & <b>Flag as Spam</b> on spam-bots, this is the extra message that will appear.</small></td>									
								</tr>
								<tr valign="top">
									<td>Holding Message</td><td><input type="text" name="BotBlocker_options[honeypot_error_msg_hold]" value="<?php echo $options['honeypot_error_msg_hold']; ?>" size="50" />
									<br><small>If using <b>Show Error</b> & <b>Holding for approval</b> on spam-bots, this is the extra message that will appear.</small></td>									
								</tr>
							</table>							
						</td>					
					</tr>
					<tr valign="top">
						<th scope="row">Hiding Method</th>
						<td>
							<select name="BotBlocker_options[honeypot_hide]">
								<option value="CSS" <?php selected($options['honeypot_hide'], 'CSS'); ?> />CSS</option>
								<option value="Inline-CSS" <?php selected($options['honeypot_hide'], 'Inline-CSS'); ?> />Inline-CSS</option>							
								<option value="Javascript" <?php selected($options['honeypot_hide'], 'Javascript'); ?> />Javascript</option>
							</select>
							<br><small>The method by which the honeypot field is hidden from normal users. (Default: CSS)</small>
						</td>					
					</tr>
					<tr valign="top">						
						<th scope="row">Additional Randomness</th>
						<td>
							<select name="BotBlocker_options[honeypot_random]">						
								<option value="Yes" <?php selected($options['honeypot_random'], 'Yes'); ?> />Yes</option>							
								<option value="No" <?php selected($options['honeypot_random'], 'No'); ?> />No</option>
							</select>
							<br><small class="help"><b>Additional randomness</b> will prefix or suffix a random character to the selected method, which will change daily. (Default: Yes)</small>
						</td>
					</tr>
					<tr valign="top">
						<td scope="row" colspan="2" class="heading">
							<h3>Obfuscation</h3>						
							<p>BotBlocker can take the default wordpress comment fields and rename them to make it harder for spam bots to figure out.<br></p>
												
						</td>
					</tr>
					
					<tr valign="top">
						<th scope="row">Obfuscation</th>
						<td>
							<select name="BotBlocker_options[obfuscation]">							
								<option value="Swap Email and Name" <?php selected($options['obfuscation'], 'Swap Email and Name'); ?> />Swap Email and Name</option>							
								<!-- <option value="Completely Obfuscate" <?php selected($options['obfuscation'], 'Completely Obfuscate' ); ?> />Completely Obfuscate</option> -->
								<option value="None" <?php selected($options['obfuscation'], 'None'); ?> />None</option>
							</select><br>
							<ul class="help">							
								<li>The <b>Swap Email and Name</b> option swaps the field names for the name and email text boxes. Thus a spam bot will see 'name' for the email field, and wordpress will reject it as an invalid email. (Default)</li>
								<!-- <li>The <b>Completely Obfuscate</b> option will make all the fields completely randomly generated names, making it impossible to identify what data should go where, changes daily.</li> -->
								<li>The <b>None</b> option will disable obfuscation. If you have heavily customized your comment form, you may need to select this. </li>
							</ul>		
						</td>					
					</tr>
					
					<tr valign="top">
						<td scope="row" colspan="2" class="heading">
							<h3>Advanced Options</h3>								
						</td>
					</tr>					
					<tr valign="top">
						<th scope="row">Seed</th>
						<td>
							<input type="text" name="BotBlocker_options[seed]" value="<?php echo $options['seed']; ?>" size="25" />
							<br><small>Randomly generated when you activated the plugin to prevent spam bot counter-detection. Not necessary to change.</small>
						</td>
					</tr>		

					
				</table>
				<p class="submit"><br><br>
					<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
				</p>
			</form>
		</div>
		<style>
			.spam-form { }		
			.spam-form p { line-height: 1.3; margin-bottom: 1em;}
			.spam-form ul { margin-left: 1.5em;}	
			.spam-form li { list-style-type: disc; margin-bottom: 0.75em;}	
			.spam-form small {line-height:1.3; font-size: 0.9em; color:#666;}
			.spam-form select {width:200px; margin-bottom: 5px;}
			.spam-form input { margin-bottom: 5px;}
			.spam-form table {}
			.spam-form th {
				font-size:1em;
				width: 220px;
				padding-bottom: 10px;
				border-bottom: 1px solid #ddd;
				background: #f4f4f4;
				border-left: 35px solid #fff;
			}
			.spam-form td {
				line-height:1.3;
				border-bottom: 1px solid #eee;
			}
			.spam-form td.heading {
				border: 0;
				padding-left: 0;
				padding-top: 20px;
				border-left: 0px;
			}
			.spam-form tr:first-child td.heading {
				padding-top: 0px;
			}
			.spam-form .help {
				font-size: 0.9em;
				color: #666;				
			}
		</style>
		<?php	
	}
} 
