<?PHP
/************************************
*
* Set options and display stats
* modified 9/24/2017
*
*************************************/
if (!defined('ABSPATH')) exit; // just in case

if(!current_user_can('manage_options')) {
	die('Access Denied');
}

function astound_admin_menu() {
	add_options_page('Astounding Spam Prevention', 'Astounding Spam Prevention', 'manage_options', 'astound_control','astound_control');
	
}

    add_action( 'wp_ajax_astound_set_option', 'astound_update_option' );

	add_action( 'wp_ajax_astound_set_spamwords', 'astound_update_spamwords' );

	add_action( 'wp_ajax_astound_set_wlist', 'astound_update_wlist' );

	add_action( 'wp_ajax_astound_set_tldlist', 'astound_update_tldlist' );

	add_action( 'wp_ajax_astound_set_badtldlist', 'astound_update_badtldlist' );

	add_action( 'wp_ajax_astound_diags', 'astound_diags' );

	add_action( 'wp_ajax_astound_show_option_dump', 'astound_show_option_dump' );

	add_action( 'wp_ajax_astound_show_logs', 'astound_show_logs' );

	add_action( 'wp_ajax_astound_delete_log', 'astound_delete_log' );

	add_action( 'wp_ajax_astound_clear_cache', 'astound_clear_cache' );

	add_action( 'wp_ajax_astound_show_cache', 'astound_show_cache' );




function astound_delete_log() {
	$astound_opt=$_POST['astound_opt'];
	$astound_opt=sanitize_text_field($astound_opt);
	if (!wp_verify_nonce($astound_opt,'astound_options')) {	
		echo "Session timeout, please refresh the page";
		exit;
	}
	$ansa=astound_delete_log_file();
	exit;
}
function astound_diags() {
	astound_errorsonoff();
	$astound_opt=$_POST['astound_opt'];
	$astound_opt=sanitize_text_field($astound_opt);
	if (!wp_verify_nonce($astound_opt,'astound_options')) {	
		echo "Session timeout, please refresh the page";
		exit;
	}
	// run the diags
	$ansa=astound_run_tests();
	echo '<h2>Diagnostics Results</h2><br>';
	echo $ansa;
	astound_errorsonoff('off');
	exit;
}
function astound_show_cache() {
	try {
		$astound_opt=$_POST['astound_opt'];
		$astound_opt=sanitize_text_field($astound_opt);
		if (!wp_verify_nonce($astound_opt,'astound_options')) {	
			echo "Session timeout, please refresh the page";
			exit;
		}
		//$astound_opt=$_POST['astound_opt'];
		//$astound_opt=sanitize_text_field($astound_opt);
		//$ansa="Got something?";
		$ansa=astound_get_cache();
		echo $ansa;
	} catch (Exception $e) {
		echo ("exception in show cache $e");
	}
	//return "show";
}

function astound_get_cache() {
	try {
		$cache=get_option('astound_cache');
		$ansa="";
		if (empty($cache) || !is_array($cache)) {
			return "Cache is empty";
		}

		foreach($cache as $key => $value) {
			$reason=$value['reason'];
			$time=$value['time'];
			$time = date ('Y-m-d H:i:s',$time);
			$ansa.="$key - $time - $reason<br>";
		}
		
		// for testing
		//$raw=print_r($cache,true);
		//$raw=esc_textarea($raw);
		//$ansa.="<br>".$raw;

		if (empty($ansa) || strlen($ansa)<=10) {
			$ansa="nothing in cache";
		}
		return $ansa;
	} catch (Exception $e) {
		return ("exception in show get $e");
	}
}
function astound_update_option() { 
	// Handle request then generate response using WP_Ajax_Response
	//astound_errorsonoff();
	$name=$_POST['name'];
	$name=sanitize_text_field($name);
	$checked=$_POST['checked'];
	$checked=sanitize_text_field($checked);
	$astound_opt=$_POST['astound_opt'];
	$astound_opt=sanitize_text_field($astound_opt);
	if (!wp_verify_nonce($astound_opt,'astound_options')) {	
		echo "Session timeout, please refresh the page";
		exit;
	}
	if ($checked=='true') {
		$checked='Y'; 
	} else {
		$checked='N';
	}
	if (!function_exists('astound_set_option') ) {
		echo "function not found????";
		exit;
	}
	
	astound_set_option($name,$checked);
	
	echo "I'm back";
	//astound_errorsonoff('off');
	exit;
}

function astound_update_spamwords() {
	// Handle request then generate response using WP_Ajax_Response
	//astound_errorsonoff();
	$name='spamwords';
	$astound_opt=$_POST['astound_opt'];
	$astound_opt=sanitize_text_field($astound_opt);
	if (!wp_verify_nonce($astound_opt,'astound_options')) {	
		echo "Session timeout, please refresh the page";
		exit;
	}
	//convert the textarea to an array.
	$spamwords=$_POST['spamwords'];
	$spamwords=sanitize_textarea_field($spamwords);
	if(empty($spamwords)) {
		$spamwords=array();
	} else {
		$spamwords=esc_textarea($spamwords);
		$spamwords=explode("\n",$spamwords);
	}
	$tblist=array();
	foreach($spamwords as $bl) {
		$bl=trim($bl);
		if (!empty($bl)) $tblist[]=$bl;
	}
	$options['spamwords']=$tblist;				
	$spamwords=$tblist;
	astound_set_option('spamwords',$spamwords);
	
	echo "OK";
	// close the textarea
	
	//astound_errorsonoff('off');
	exit;	
}

function astound_update_wlist() {
	// Handle request then generate response using WP_Ajax_Response
	//astound_errorsonoff();
	$name='wlist';
	$astound_opt=$_POST['astound_opt'];
	$astound_opt=sanitize_text_field($astound_opt);
	if (!wp_verify_nonce($astound_opt,'astound_options')) {	
		echo "Session timeout, please refresh the page";
		exit;
	}
	//convert the textarea to an array.
	$wlist=$_POST['wlist'];
	$wlist=sanitize_textarea_field($wlist);
	if(empty($wlist)) {
		$wlist=array();
	} else {
		$wlist=esc_textarea($wlist);
		$wlist=explode("\n",$wlist);
	}
	$tblist=array();
	foreach($wlist as $bl) {
		$bl=trim($bl);
		if (!empty($bl)) $tblist[]=$bl;
	}
	$options['wlist']=$tblist;				
	$wlist=$tblist;
	astound_set_option('wlist',$wlist);
	
	echo "OK";
	// close the textarea
	
	//astound_errorsonoff('off');
	exit;	
}
function astound_update_tldlist() {
	// Handle request then generate response using WP_Ajax_Response
	//astound_errorsonoff();
	$name='tldlist';
	$astound_opt=$_POST['astound_opt'];
	$astound_opt=sanitize_text_field($astound_opt);
	if (!wp_verify_nonce($astound_opt,'astound_options')) {	
		echo "Session timeout, please refresh the page";
		exit;
	}
	//convert the textarea to an array.
	$tldlist=$_POST['tldlist'];
	$tldlist=sanitize_textarea_field($tldlist);
	if(empty($tldlist)) {
		$tldlist=array();
	} else {
		$tldlist=esc_textarea($tldlist);
		$tldlist=explode("\n",$tldlist);
	}
	$tblist=array();
	foreach($tldlist as $bl) {
		$bl=trim($bl);
		if (!empty($bl)) $tblist[]=$bl;
	}
	$options['tldlist']=$tblist;				
	$tldlist=$tblist;
	astound_set_option('tldlist',$tldlist);
	
	echo "OK";
	// close the textarea
	
	//astound_errorsonoff('off');
	exit;	
}
function astound_update_badtldlist() {
	// Handle request then generate response using WP_Ajax_Response
	//astound_errorsonoff();
	$name='badtldlist';
	$astound_opt=$_POST['astound_opt'];
	$astound_opt=sanitize_text_field($astound_opt);
	if (!wp_verify_nonce($astound_opt,'astound_options')) {	
		echo "Session timeout, please refresh the page";
		exit;
	}
	//convert the textarea to an array.
	$tldlist=$_POST['badtldlist'];
	$tldlist=sanitize_textarea_field($tldlist);
	if(empty($tldlist)) {
		$tldlist=array();
	} else {
		$tldlist=esc_textarea($tldlist);
		$tldlist=explode("\n",$tldlist);
	}
	$tblist=array();
	foreach($tldlist as $bl) {
		$bl=trim($bl);
		if (!empty($bl)) $tblist[]=$bl;
	}
	$options['badtldlist']=$tblist;				
	$tldlist=$tblist;
	astound_set_option('badtldlist',$tldlist);
	
	echo "OK";
	// close the textarea
	
	//astound_errorsonoff('off');
	exit;	
}
function astound_control()  {
	// this is the display of information about the page.

	if (array_key_exists('resetOptions',$_POST)) {
		astound_force_reset_options();
	}

	$ip=astound_get_ip();



	$nonce=wp_create_nonce('astound_options');

	$options=astound_get_options();
	extract($options);

	?>

<div class="wrap" id="astound_div">
  <h2>Astounding Spam Prevention Version <?PHP echo ASTOUND_VERSION; ?></h2>
 <astound_tab>
    <ul>
      <li class="astound_select"><span id="astoundm0"  onclick="astoundmenu(0);return false;">Settings</span></li>
      <li><span id="astoundm1" onclick="astoundmenu(1);return false;">Log</span></li>
      <li><span id="astoundm2" onclick="astoundmenu(2);return false;">Diagnostics</span></li>
      <li><span id="astoundm4" onclick="astoundmenu(4);return false;">Cache</span></li>
      <li><span id="astoundm5" onclick="astoundmenu(5);return false;">Raw Options</span></li>
      <li><span id="astoundm3" onclick="astoundmenu(3);return false;">About</span></li>
    </ul>
  </astound_tab>
   <div style="clear:both;"><br>
  </div>
  <div id="astoundd0">
    <p>To turn an option on or off just click the button. The options are automatically saved.<br>
      Some options have a list associated with them. Click the edit button next to them and add or delete list items. Use a separate line for each item.</p>
    <form method="post" name="astound_form" action="options.php" id="astoundOptions">
      <input type="hidden" name="astound_opt" value="<?php echo $nonce;?>" />
      <table width="90%" align=center>
        <tr>
          <td colspan="2" style="border-top:solid thin black;border-bottom:solid thin black;"><h3>Recommended settings</h3>
            These are the settings to are most effective in identifying spam and still do not give false positives. It is recommended that these options are all turned on. </td>
        </tr>
        <tr>
          <td width="50px" align=left><label class="switch">
            <input type="checkbox" name="astound_chkaccept" value="chkaccept" 
	<?php if ($astound_chkaccept=='Y') {?>checked="checked"<?php } ?> onclick="astoundProcess(this)">
            <span class="slider round"></span>
            </label>
          </td>
          <td><em><b>Check Accept Header</b></em><br>
            Spammers often neglect to send the proper HTML headers. This rejects updates without the proper accept header </td>
        </tr>
        <tr>
          <td width="50px" align=left><label class="switch">
            <input type="checkbox" name="astound_chkagent" value="chkagent" 
	<?php if ($astound_chkagent=='Y') {?>checked="checked"<?php } ?> onclick="astoundProcess(this)">
            <span class="slider round"></span>
            </label>
          </td>
          <td><em><b>Check User Agent</b></em><br>
            User Agent identifies the kind of browser accessing the web page. A missing or non-standard user agent identifies a spammer.</td>
        </tr>
        <tr>
          <td width="50px" align=left><label class="switch">
            <input type="checkbox" name="astound_chkbadneighborhoods" value="chkbadneighborhoods"
	<?php if ($astound_chkbadneighborhoods=='Y') {?>checked="checked"<?php } ?> onclick="astoundProcess(this)">
            <span class="slider round"></span>
            </label>
          </td>
          <td><em><b>Check Bad Neighborhoods</b></em><br>
            Every month I run lists of spammer IP addresses through a program which distills them into a list of bad neighborhoods. This should show the most recent spam sources. It is mostly Russian, Chinese, Indian and Eastern European hosting companies with some US addresses included. </td>
        </tr>
        <tr>
          <td width="50px" align=left><label class="switch">
            <input type="checkbox" name="astound_chkbbcode" value="chkbbcode"
	<?php if ($astound_chkbbcode=='Y') {?>checked="checked"<?php } ?> onclick="astoundProcess(this)">
            <span class="slider round"></span>
            </label>
          </td>
          <td><em><b>Check bbcode</b></em><br>
            Spammers like to put bbcode like [url]Spam website[/url]. This option rejects anyone using bbcode in a comment. </td>
        </tr>
        <tr>
          <td width="50px" align=left><label class="switch">
            <input type="checkbox" name="astound_chkdisp" value="chkdisp" 
	<?php if ($astound_chkdisp=='Y') {?>checked="checked"<?php } ?> onclick="astoundProcess(this)">
            <span class="slider round"></span>
            </label>
          </td>
          <td><em><b>Check Disposable Emails</b></em><br>
            Disposable email accounts are used by spammers to help them remain annonymous. This checks against a recent list of disposable email servers and rejects anyone using them </td>
        </tr>
        <tr>
          <td width="50px" align=left><label class="switch">
            <input type="checkbox" name="astound_chkdnsbl" value="chkdnsbl" 
	<?php if ($astound_chkdnsbl=='Y') {?>checked="checked"<?php } ?> onclick="astoundProcess(this)">
            <span class="slider round"></span>
            </label>
          </td>
          <td><em><b>Check zen.spamhaus.org Black List</b></em><br>
            <b>Note: this options can cause errors if zen.spamhaus.org can't be reached. It is recommended that if you turn it on that you check the PHP error logs for your site regularly.</b><br>
			Spamhaus is one the largest and most comprehensive spam blacklists. This is a check on a users internet address to see if Spamhaus sees the user as a spammer. (This option has been disabled)</td>
        </tr>
        <tr>
          <td width="50px" align=left><label class="switch">
            <input type="checkbox" name="astound_chksfs" value="chksfs" 
	<?php if ($astound_chksfs=='Y') {?>checked="checked"<?php } ?> onclick="astoundProcess(this)">
            <span class="slider round"></span>
            </label>
          </td>
          <td><em><b>Check Stop Forum Spam Black List</b></em><br>
            This does a quick lookup on the SFS database for any spam that has occurred in the last 99 days with a frequency greater than twice for the users IP. It is possible that the SFS is under one of its frequent denial of services attacks so this may not report spam correctly.</td>
        </tr>
        <tr>
          <td width="50px" align=left><label class="switch">
            <input type="checkbox" name="astound_chkexploits" value="chkexploits" 
	<?php if ($astound_chkexploits=='Y') {?>checked="checked"<?php } ?> onclick="astoundProcess(this)">
            <span class="slider round"></span>
            </label>
          </td>
          <td><em><b>Check for Exploits</b></em><br>
            Spammers sometimes try to put SQL injection, JavaScript or other pieces of code into forms. This checks for some common exploits and rejects anyone trying to use them. </td>
        </tr>
        <tr>
          <td width="50px" align=left><label class="switch">
            <input type="checkbox" name="astound_chkisphosts" value="chkisphosts" 
	<?php if ($astound_chkisphosts=='Y') {?>checked="checked"<?php } ?> onclick="astoundProcess(this)">
            <span class="slider round"></span>
            </label>
          </td>
          <td><em><b>Check for Hosting Companies</b></em><br>
            Comments and registrations should come from real users. A web host only tries to access your site if it is a robot controlled by spammers. This rejects any request that comes from a hosting company. </td>
        </tr>
        <tr>
          <td width="50px" align=left><label class="switch">
            <input type="checkbox" name="astound_chkreferer" value="chkreferer" 
	<?php if ($astound_chkreferer=='Y') {?>checked="checked"<?php } ?> onclick="astoundProcess(this)">
            <span class="slider round"></span>
            </label>
          </td>
          <td><em><b>Check HTTP_REFERER</b></em><br>
            The referer(sp) is the url of the page that submitted the comment or registration form. All forms submits must come from your website. Sometimes a spammer is lazy and the referer is missing or incorrect. </td>
        </tr>
        <tr>
          <td width="50px" align=left><label class="switch">
            <input type="checkbox" name="astound_chklong" value="chklong" 
	<?php if ($astound_chklong=='Y') {?>checked="checked"<?php } ?> onclick="astoundProcess(this)">
            <span class="slider round"></span>
            </label>
          </td>
          <td><em><b>Check for long names and email</b></em><br>
            Spammers can't resist putting their smarmy message anywhere they can. Sometimes the email is hundreds of characters long full of spam messages. This option rejects long email addresses, subjects and user names. </td>
        </tr>
        <tr>
          <td width="50px" align=left><label class="switch">
            <input type="checkbox" name="astound_chkshort" value="chkshort" 
		<?php if ($astound_chkshort=='Y') {?>checked="checked"<?php } ?> onclick="astoundProcess(this)">
            <span class="slider round"></span>
            </label>
          </td>
          <td><em><b>Check for short names and email</b></em><br>
            Spammers sometimes leave off the email or don't use a valid one. This checks for short email addresses and rejects them. </td>
		</tr>
        <tr>
          <td width="50px" align=left><label class="switch">
            <input type="checkbox" name="astound_chkdomains" value="chkdomains" 
		<?php if ($astound_chkdomains=='Y') {?>checked="checked"<?php } ?> onclick="astoundProcess(this)" >
            <span class="slider round"></span>
            </label>
          </td>
          <td><em><b>Check for Spam Domains</b></em><br>
            This checks messages, subjects and email addresses against a list of domains used by spammers. If the
            domain is present (usually in links), the spammer is rejected.<br>
          </td>
        </tr>
        <tr>
          <td width="50px" align=left><label class="switch">
            <input type="checkbox" name="astound_chkspamwords" value="chkspamwords" 
		<?php if ($astound_chkspamwords=='Y') {?>checked="checked"<?php } ?> onclick="astoundProcess(this)">
            <span class="slider round"></span>
            </label>
          </td>
          <td><em><b>Check for Common Spam Words</b></em><br>
            This checks messages, subjects and email addresses against a list of the most common spammy words.<br>
            <button name="astound_show_spamwords_button" onclick="astound_show_spamwords();return false;">edit spamwords list</button>
            <div id="astound_edit_spamwords" style="display:none" title="">
              <textarea name="astound_spamwords" id='astound_spamwords' cols="32" rows="8"><?php
	foreach($spamwords as $p) {
		echo $p."\r\n";
	}
	?>
	</textarea>
              <br>
              <button onclick="astound_save_spamwords(this);return false;">save spamwords list</button>
              <button onclick="astound_cancel_spamwords();return false;">cancel</button>
            </div></td>
        </tr>
        <tr>
          <td width="50px" align=left><label class="switch">
            <input type="checkbox" name="astound_chktor" value="chktor" 
		<?php if ($astound_chktor=='Y') {?>checked="checked"<?php } ?> onclick="astoundProcess(this)">
            <span class="slider round"></span>
            </label>
          </td>
          <td><em><b>Check for Tor Exit Nodes</b></em><br>
            Tor, or &quot;the dark net&quot;, is used by a few privacy advocates, and a great many drug dealers, paedophiles and other criminals including spammers. You should ask yourself why you need comments from someone who has taken great lengths to hide their identity.</td>
        </tr>
        <tr>
          <td width="50px" align=left><label class="switch">
            <input type="checkbox" name="astound_chktoxic" value="chktoxic" 
		<?php if ($astound_chktoxic=='Y') {?>checked="checked"<?php } ?> onclick="astoundProcess(this)">
            <span class="slider round"></span>
            </label>
          </td>
          <td><em><b>Check for Toxic Networks</b></em><br>
            There are internet networks that have never been used for anything but spam. Stop Forum Spam keeps an up to date list of them. This rejects anyone who comes from one of these networks. </td>
        </tr>
        <tr>
          <td width="50px" align=left><label class="switch">
            <input type="checkbox" name="astound_chkwlist" value="astound_chkwlist" 
		<?php if ($astound_chkwlist=='Y') {?>checked="checked"<?php } ?> onclick="astoundProcess(this)">
            <span class="slider round"></span>
            </label>
          </td>
          <td><em><b>User White List</b></em><br>
            Add ip addresses here, one to a line to add a user to a white list so that spam checking is bypassed<br>
            <button name="astound_show_wlist" onclick="astound_show_whitelist();return false;">edit white list</button>
            <div id="astound_edit_wlist" style="display:none" title="">
              <textarea name="astound_wlist" id='astound_wlist' cols="32" rows="8"><?php
	foreach($wlist as $p) {
		echo $p."\r\n";
	}
	?>
	</textarea>
              <br>
              <button onclick="astound_save_whitelist(this);return false;">save white list</button>
              <button onclick="astound_cancel_whitelist();return false;">cancel</button>
            </div></td>
        </tr>
        <tr>
          <td colspan="2" style="border-top:solid thin black;border-bottom:solid thin black;"><h3>Optional settings</h3>
            These settings are effective, but can result in false spam detection, annoying your users. Turn these on if you have lots of trouble with spam and do not need to worry about e-commerce. </td>
        </tr>
        <tr>
          <td width="50px" align=left><label class="switch">
            <input type="checkbox" name="astound_displayall" value="astound_displayall" 
	<?php if ($astound_displayall=='Y') {?>checked="checked"<?php } ?> onclick="astoundProcess(this)">
            <span class="slider round"></span>
            </label>
          </td>
          <td><em><b>Show all reasons for rejection</b></em><br>
            This writes all reasons for rejection to the log. It makes the log longer, but you get to see all of the reasons for rejecting a spammer.</td>
        </tr>
        <tr>
          <td width="50px" align=left><label class="switch">
            <input type="checkbox" name="astound_chksession" value="chksession" 
	<?php if ($astound_chksession=='Y') {?>checked="checked"<?php } ?> onclick="astoundProcess(this)">
            <span class="slider round"></span>
            </label>
          </td>
          <td><em><b>Check quick response</b></em><br>
            This uses a cookie to see how fast a user fills in a form. Anything 3 seconds or less is too quick and the spammer is rejected. </td>
        </tr>
      <tr>
        <td width="50px" align=left><label class="switch">
            <input type="checkbox" name="astound_chktld" value="chktld" 
	<?php if ($astound_chktld=='Y') {?>checked="checked"<?php } ?> onclick="astoundProcess(this)">
            <span class="slider round"></span>
            </label>
          </td>
          <td><em><b>Check non-generic TLDs</b></em><br>
            If a person uses a domain type (TLD) other than .COM, .ORG, .NET, .EDU, .MIL, or .GOV he could be a spammer.
            It is very easy to get a yahoo or gmail account, so if you don't have one it is a potential problem.
            It is harder to abuse a gmail address than it is to abuse mail.ru. If you deal with mostly non-US users than you will want to turn this off.<br>
            You can add the the list of allowed TLDs by clicking the edit button here.<br>
            <button name="astound_show_tldlist" onclick="astound_show_validtldlist();return false;">edit valid TLD list</button>
            <div id="astound_edit_tldlist" style="display:none" title=""> Note: These are the allowed TLDs. Any TLD not in this list will be rejected. If you wish to allow country codes like .ca or .de you would have to add them here or else uncheck the TLD option to the left.<br>
              <textarea name="astound_tldlist" id='astound_tldlist' cols="32" rows="8"><?php
	foreach($tldlist as $p) {
		echo $p."\r\n";
	}
	?>
	</textarea>
              <br>
              <button onclick="astound_save_tldlist(this);return false;">save TLD list</button>
              <button onclick="astound_cancel_tldlist();return false;">cancel</button>
            </div></td>
        </tr>
        <tr>
          <td width="50px" align=left><label class="switch">
            <input type="checkbox" name="astound_chksubdomains" value="chksubdomains" 
		<?php if ($astound_chksubdomains=='Y') {?>checked="checked"<?php } ?> onclick="astoundProcess(this)">
            <span class="slider round"></span>
            </label>
          </td>
          <td><em><b>Check for subdomains in email address</b></em><br>
            Using a subdomain for an email address like mail.users.frank.social.com is a clue that the user might be a spammer. This check is turned off by default, but turn it on if you start getting email spam like this.</td>
        </tr>
        <tr>
          <td width="50px" align=left><label class="switch">
            <input type="checkbox" name="astound_chkbadtld" value="chkbadtld" 
	<?php if ($astound_chkbadtld=='Y') {?>checked="checked"<?php } ?> onclick="astoundProcess(this)">
            <span class="slider round"></span>
            </label>
          </td>
          <td><em><b>Check banned TLDs</b></em><br>
            Top level domains like .com and .org are not as easy to use for spam as ones like .top or .xxx. This is a list of TLDs that will be rejected when found in email addresses<br>
            You can add the the list of banned TLDs by clicking the edit button here.<br>
            <button name="astound_show_badtldlist" onclick="astound_show_invalidtldlist();return false;">edit banned TLD list</button>
            <div id="astound_edit_badtldlist" style="display:none" title=""> Note: These are the banned TLDs. Any TLD in this list will be rejected. If you wish to allow country codes like .top or .ru you would have to remove them here or else uncheck the banned TLD option to the left.<br>
              <textarea name="astound_badtldlist" id='astound_badtldlist' cols="32" rows="8"><?php
	foreach($badtldlist as $p) {
		echo $p."\r\n";
	}
	?>
	</textarea>
              <br>
              <button onclick="astound_save_badtldlist(this);return false;">save banned TLD list</button>
              <button onclick="astound_cancel_badtldlist();return false;">cancel</button>
            </div></td>
        </tr>
        <tr>
          <td width="50px" align=left><label class="switch">
            <input type="checkbox" name="astound_chkperiods" value="chkperiods" 
		<?php if ($astound_chkperiods=='Y') {?>checked="checked"<?php } ?> onclick="astoundProcess(this)">
            <span class="slider round"></span>
            </label>
          </td>
          <td><em><b>Check for too many periods in email</b></em><br>
            You can put extra periods in the name part of an email address to make it readable. Spammers like to add lots of periods. More than 3 periods in an email address is the sign of a spammer. </td>
        </tr>
        <tr>
          <td width="50px" align=left><label class="switch">
            <input type="checkbox" name="astound_chkredherring" value="chkredherring" 
		<?php if ($astound_chkredherring=='Y') {?>checked="checked"<?php } ?> onclick="astoundProcess(this)">
            <span class="slider round"></span>
            </label>
          </td>
          <td><em><b>Check Red Herring Form</b></em><br>
            This places a fake form on every page. The spammers see the form and then submit it. It is the first form on the page so it is likely that many spam bots will think it is the real thing, hence the term <em>Red Herring</em>.<br>
            When using this option check your page after you turn it on to see if it is compatable with your theme. It works with most themes, but sometimes a theme or plugin can use javascript to look for elements on a page and the red herring form throws them off. </td>
        </tr>
        <tr>
          <td width="50px" align=left><label class="switch">
            <input type="checkbox" name="astound_chkvpn" value="chkvpn" 
		<?php if ($astound_chkvpn=='Y') {?>checked="checked"<?php } ?> onclick="astoundProcess(this)">
            <span class="slider round"></span>
            </label>
          </td>
          <td><em><b>Check for VPNs and other Anonymizers</b></em><br>
            Virtual Private Networks (VPNs) are a way of life in some countries where the internet access is filtered or blocked. However, spammers use VPNs to hide who they really are. Using this option blocks users from <i>some</i> VPNs. It does not block all or even most VPNs.<br>
            This option only blocks access from VPNs that are known sources of spam. This would included many
            free VPNs, but will not include many VPNs that require fees.</td>
        </tr>
        <tr>
          <td width="50px" align=left><label class="switch">
            <input type="checkbox" name="astound_chkphish" value="chkphish" 
		<?php if ($astound_chkphish=='Y') {?>checked="checked"<?php } ?> onclick="astoundProcess(this)">
            <span class="slider round"></span>
            </label>
          </td>
          <td><em><b>Check for Phishing sites in text</b></em><br>
            Checks for common phishing domain in text, links, subjects, etc.</td>
        </tr>
        <tr>
          <td width="50px" align=left><label class="switch">
            <input type="checkbox" name="astound_chkmyip" value="chkmyip" 
		<?php if ($astound_chkmyip=='Y') {?>checked="checked"<?php } ?> onclick="astoundProcess(this)">
            <span class="slider round"></span>
            </label>
          </td>
          <td><em><b>Check for MyIP Blacklist IPs</b></em><br>
            Checks for incoming ip addresses that are found on the MyIP blacklist.</td>
        </tr>
      </table>
    </form>
  </div>
  <div id="astoundd4"  style="display:none;">
 
   <form method="POST" action="">
      <?PHP $nonce=wp_create_nonce('astound_options'); ?>
      <input type="hidden" name="astound_opt" value="<?php echo $nonce;?>" />
  	  <p>The plugin keeps a history of who has been rejected in a cache. Anyone who has been rejected will not be able log in for any reason for 15 minutes.<br>
	  <button onclick="astound_clear_cache();return false;">Clear Cache</button>
	  </p>
	  <p>
	  <span id="astound_cache_msg"></span><br>
	  </p>
	  <p>
	 <span id="astound_cache"></span>
	  </p>	  
    </form>
<p>
end of cache
	 
</p>	  
 
  </div>
  <div id="astoundd3"  style="display:none;">
    <p>Astounding Spam Prevention is an effective safe anti-spam plugin that protects against comment spam and bogus registrations.</p>
    <p> Astounding Spam Prevention is open source and is derived from the popular Stop Spammers Spam Protection plugin under GNU licensing.</p>
    <p> Stop Spammers was very aggressive and blocks login attempts by spammers, but it sometimes blocks legitimate users. Astounding Spam protection uses many of the Stop Spammers methods to prevent comment spam and registration spam, but it does no block logins. It is generally effective, although not as aggressive as the plugin from which it is derived.</p>
    <p> The plugin is very light weight in that it has a very small memory footprint while installed. It only loads the resources it needs when it is actually checking a comment or a registration. Unlike some other plugins that load up many hundreds of kb of program space, Astounding will not slow down or cause problems on systems that have restrained resources.</p>
    <p> Astounding Spam Prevention has been thoroughly tested and many bugs found in original program have been fixed.</p>
    <p> Astounding Spam Prevention does not use the Stop Forum Spam database due to issues with their API. </p>
    <p> There are several new methods for detecting spam that are proving effective and have been added to the Astounding Spam Protection Plugin.</p>


<p>If you wish to support my programming, anything you can do would be appreciated.</p>
<p>There are several ways to do this.</p>
<p>First, you can go to the plugin pages on WordPress.org and click on a few stars for the plugin rating, and check off the “it works” information. You might, if so moved, say a few nice words under reviews.</p>
<p>Second, If you feel that you’d like to encourage me, please buy one of my books and give it a good review. I worked hard on these books, and they are worth reading.
<a href="https://amzn.to/42BjwXv" target="_new">My Author Page at Amazon</a> 



<p>You can also donate a few dollars. Cash is a little short right now, so my wife convinced me to go the low road and ask for money.&nbsp;There are three levels of donations. First, at $2.51 you can support me. I like this level because it does not put any stress on you. I think everyone can afford this without any pain. Second, for those who think they owe a little more, I have a $9.98 level. This is for those who have money to burn and drive expensive sports cars. Last, there is the $29.98 level. I don’t expect anyone to use this level, but there are possibly a few sysops with a company credit card, and an unlimited budget who might sympathize with a fellow coder and click this button.</p>
<p>You can alternately pay using PayPal. All you need is a credit card. There is no PayPal account required. Just click and follow the instructions. You can request a refund and I will gladly comply. I have applied to other payment services and may add new methods for those, especially in Europe, who have problems using PayPal.</p>
<table>
  <tbody>
    <tr>
      <td>Level 1) $2.51</td>
      <td><form action="https://www.paypal.com/cgi-bin/webscr" method="post">
          <input name="cmd" type="hidden" value="_s-xclick">
          <br>
          <input name="hosted_button_id" type="hidden" value="9V4ZE99S2VYQA">
          <br>
          <input alt="PayPal - The safer, easier way to pay online!" name="submit" src="https://www.paypalobjects.com/en_US/i/btn/btn_buynowCC_LG.gif" type="image">
          <br>
          <img src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" alt="" scale="0" width="1" height="1" border="0">
        </form></td>
    </tr>
    <tr>
      <td>Level 2) $9.98</td>
      <td><form action="https://www.paypal.com/cgi-bin/webscr" method="post">
          <input name="cmd" type="hidden" value="_s-xclick">
          <br>
          <input name="hosted_button_id" type="hidden" value="2UCJBHR44HQAJ">
          <br>
          <input alt="PayPal - The safer, easier way to pay online!" name="submit" src="https://www.paypalobjects.com/en_US/i/btn/btn_buynowCC_LG.gif" type="image">
          <br>
          <img src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" alt="" scale="0" width="1" height="1" border="0">
        </form></td>
    </tr>
    <tr>
      <td>Level 3) $29.98</td>
      <td><form action="https://www.paypal.com/cgi-bin/webscr" method="post">
          <input name="cmd" type="hidden" value="_s-xclick">
          <br>
          <input name="hosted_button_id" type="hidden" value="EG83EZCTGYYQQ">
          <br>
          <input alt="PayPal - The safer, easier way to pay online!" name="submit" src="https://www.paypalobjects.com/en_US/i/btn/btn_buynowCC_LG.gif" type="image">
          <br>
          <img src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" alt="" scale="0" width="1" height="1" border="0">
        </form></td>
    </tr>
  </tbody>
</table>
<p>I would like to thank you for your support. The very best way to support me is to report all bugs, ask for enhancements, send me your ideas, and drop me a line of encouragement. I will never get rich writing plugins, so you can help me by adding to the fun of coding. I have spent the last 50 years writing programs. I started in 1969 writing FORTRAN on an old IBM 1400, and I have learned every cool language that has appeared since. Programming has given me a good life and I would rather write code than almost anything. I am retired now, but I still find time for little programming projects.</p>
<p>Thanks,</p>
<p>Keith P. Graham</p>



	</div>
   <div id="astoundd1"  style="display:none">
    <h2>Log messages</h2>
    <p>The log can get long so delete it from time to time. Please backup logs if you need them. <br>
      When the plugin updates to a new version, the logs will be deleted.
    <form method="POST" action="">
     <button onclick="astound_delete_logs();return false;">Delete Log</button>
	  </p>	  
    </form>
 
    <div>
      <pre>
	<label id="astound_logs">
	</label>
	</pre>
    </div>
  </div>
  <div id="astoundd5" style="display:none">
    <h2>Raw Options</h2>
    This is just a dump of the options that set in WordPress to check that the options are saving correctly. <br>
    <form method="POST" action="">
      <?PHP $nonce=wp_create_nonce('astound_options'); ?>
      <input type="hidden" name="astound_opt" value="<?php echo $nonce;?>" />
      <input type="hidden" name="resetOptions" value="resetOptions">
      <input type="submit" value="Force Reset of all options">
    </form>
    <div>
      <pre>
	<label id="astound_options"></label>
	</pre>
    </div>
  </div>
  <div id="astoundd2" style="display:none">
    <h2>diagnostics testing</h2>
    <form method="POST" action="" name="astound_diags">
      <input type="hidden" value="testing" name="testing">
      IP address:
      <input name="ip" type="text" value="<?php echo $ip; ?>">
      (Your Server address is <?php echo $hip;?>)<br>
      Email:
      <input name="email" type="text" value="<?php echo $email; ?>">
      <br>
      Author/User:
      <input name="author" type="text" value="<?php echo $author; ?>">
      <br>
      Subject:
      <input name="subject" type="text" value="<?php echo $subject; ?>">
      <br>
      Comment:
      <textarea name="comment" cols="45" rows="12"><?php echo $comment; ?></textarea>
      <br>
      <div style="width:50%;float:left;">
        <p class="submit">
          <button onclick="astoundDiags();return false;" name="testopt" class="button-primary" value="Test Options">Test Options</button>
        </p>
      </div>
    </form>
    <p>&nbsp;</p>
    <div style="clear:both" id="astound_results"></div>
  </div>
</div>
<?PHP 
}

function astound_show_logs() {
	$astound_opt=$_POST['astound_opt'];
	$astound_opt=sanitize_text_field($astound_opt);
	if (!wp_verify_nonce($astound_opt,'astound_options')) {	
		echo "Session timeout, please refresh the page";
		exit;
	}
	if(file_exists(ASTOUND_PLUGIN_FILE.'.astound_debug_output.txt')) {
		$logsize=filesize(ASTOUND_PLUGIN_FILE.'.astound_debug_output.txt');
		if ($logsize>5000) {
			echo "The log file is $logsize bytes in size. Please consider deleting it.\r\n\r\n";
		}
		$logarray=file(ASTOUND_PLUGIN_FILE.'.astound_debug_output.txt');
		$logarray=array_reverse($logarray);
		$log= "\r\n".implode("\r\n",$logarray);
		$log=str_replace("\r\n\r\n","\r\n",$log);
		$log=str_replace("~~~~","\r\n",$log);
		$log=esc_textarea($log);
		
		echo $log;
	} else {
		echo "Log file not found";
	}
	exit;
}
function astound_show_option_dump() {
	$astound_opt=$_POST['astound_opt'];
	$astound_opt=sanitize_text_field($astound_opt);
	if (!wp_verify_nonce($astound_opt,'astound_options')) {	
		echo "Session timeout, please refresh the page";
		exit;
	}
	if (!function_exists('astound_get_options')) {
		astound_require('includes/astound-init-options.php');
	}
	echo "\r\n";
	$raw=print_r(astound_get_options(),true);
	$raw=esc_textarea($raw);
	echo $raw;
	exit;
}
function astound_clear_cache() {
	try {
		$astound_opt=$_POST['astound_opt'];
		$astound_opt=sanitize_text_field($astound_opt);
		if (!wp_verify_nonce($astound_opt,'astound_options')) {	
			echo "Session timeout, please refresh the page";
			exit;
		}
		if (!function_exists('astound_get_options')) {
			astound_require('includes/astound-init-options.php');
		}
		delete_option('astound_cache');
		astound_log("cache cleared");
		return "";
	} catch (Exception $e) {
		astound_log("exception $e");
	}
	return "";

}
function astound_delete_log_file() {
	if(file_exists(ASTOUND_PLUGIN_FILE.'.astound_debug_output.txt')) {
		unlink(ASTOUND_PLUGIN_FILE."/.astound_debug_output.txt");
		astound_log("log deleted");
	} else {
		astound_log("new log file");
	}
	return "";
}
function astound_force_reset_options() {
	$astound_opt=$_POST['astound_opt'];
	$astound_opt=sanitize_text_field($astound_opt);
	if (!wp_verify_nonce($astound_opt,'astound_options')) {	
		echo "Session timeout, please refresh the page";
		exit;
	}
	if (!function_exists('astound_reset_options')) {
		astound_require('includes/astound-init-options.php');
	}

	astound_reset_options();
	// clear the cache
	delete_option('astound_cache');
}

function astound_run_tests() {
	// runs the tests
	$check=array(
		'astound_chkwlist',
		'astound_chkbadtld',
		'astound_chkcache',
		'astound_chkaccept',
		'astound_chkagent',
		'astound_chkbadneighborhoods',
		'astound_chkbbcode',
		'astound_chkdisp',
		'astound_chkdnsbl',
		'astound_chkdomains',
		'astound_chkexploits',
		'astound_chklong',
		'astound_chkperiods',
		'astound_chkredherring',
		'astound_chkreferer',
		'astound_chksession',
		'astound_chkshort',
		'astound_chksubdomains',
		'astound_chktld',
		'astound_chkspamwords',
		'astound_chkinvalidip',
		'astound_chkisphosts',
		'astound_chksfs',
		'astound_chktor',
		'astound_chkvpn',
		'astound_chkphish',
		'astound_chkmyip',
		'astound_chktoxic'
	);
	astound_require('includes/astound-class-loader.php');
	$ansa="";
	foreach($check as $module) {
		//echo "testing $module ";
		$ansa.= "testing $module result=";
		$ip=$_POST['ip'];
		$ip=sanitize_text_field($ip);
		$res=astound_load_module($module,$ip);
		if ($res===false) {
			$res="OK";
		}
		//echo "results=$res<br>";
		$ansa.= "$res <br>";
	}	
	return $ansa;
}
function astound_redirect() {
		$uri=$_SERVER["REQUEST_URI"];	
	if (empty($uri)) {
		$uri=$_SERVER["SCRIPT_NAME"];	
	}
	wp_redirect($uri);
	exit;
}
?>
