<?php
// Version 0.1

class WPPH {
	private $api_url_base = "https://wppluginhosting.com/api";
	// private $api_url_base = "http://0.0.0.0:8080/api";
	private $api_url;
	private $invoices_url;
	private $version = "0.1";

	private $license;
	private $trial;
	private $trialTimeLeft;
	private $idstr = NULL;
	private $lastError = NULL;
	private $now;
	private $slug = NULL;
	private $optkey = NULL;
	private $api_key = NULL;
	private $current_version;

	const BILLING_TYPE_SINGLE = 0;
	const BILLING_TYPE_MONTHLY = 1;
	const BILLING_TYPE_YEARLY = 2;

	private $options = array(
		'testing' => FALSE,
		'check_every' => 43200, //12 hours
		'try_without_license' => TRUE,
		'trial_length' => 604800, // 7 days
		'retry_after_failure' => 1800, // 30 minutes
		'failures_before_invaildate' => 4,
		'takeover_updates' => TRUE,

		//support options
		'enable_license' => FALSE,
		'support_menu_title' => 'Support',
		'support_menu_position' => 100,
		'support_menu_capability' => 'edit_posts',
		'support_menu_slug' => NULL,
		'support_menu_icon' => 'dashicons-editor-help',

		//license page options
		'enable_license' => FALSE,
		'enable_license_purchase' => FALSE,
		'stripe_api_key' => NULL,
		'license_menu_title' => 'License',
		'license_menu_position' => 101,
		'license_menu_capability' => 'edit_posts',
		'license_menu_slug' => NULL,
		'license_menu_icon' => 'dashicons-post-status',

		//normal callbacks
		'isTrial' => NULL,
		'isValid' => NULL,
		'isInvalid' => NULL,

		//event callbacks
		'onValidated' => NULL,
		'onInvalidated' => NULL,
	);

	private $pages = array();

	function __construct($slug, $api_key, $options = array()) {
		$this->slug = $slug;
		$this->optkey = preg_replace("/[^a-zA-Z0-9]/", "_", $slug)."_wpph__";
		$this->api_key = $api_key;

		$this->api_url = $this->api_url_base.'/check/update';
		$this->invoices_url = $this->api_url_base.'/plugin/support/invoices';

		$this->api_info_url = $this->api_url_base.'/info';
		$this->options = array_merge($this->options, $options);
		$this->license = get_option($this->optkey."license");
		$this->now = current_time('timestamp');

		/* If this is the first time that it's been installed, set our timestamp */
		if(!get_option($this->optkey.'installed')) {
			update_option($this->optkey."installed", current_time('timestamp'));
		}

		/* If this is the first time that it's been installed, set our idstr */
		if(!get_option($this->optkey.'idstr')) {
			update_option($this->optkey."idstr", uniqid(substr($slug,0,10)));
		}
		$this->idstr = get_option($this->optkey.'idstr');

		// Get the current version
		if(!function_exists('get_plugins')) {
			include(ABSPATH."/wp-admin/includes/plugin.php");
		}
		$plugins = get_plugins();
		$plugin = $plugins[$this->slug];
		$this->current_version = $plugin['Version'];

		if($this->options['takeover_updates']) {
			$this->takeover_updates();
		}
		$this->check_license();

		if(isset($this->options['enable_support'])) {
			$this->enable_support();
		}

		if(isset($this->options['enable_license'])) {
			$this->enable_license();
		}

		add_action('parse_query', array($this,'iframe_resize_helper'));
		add_action('http_api_curl', array($this, '__add_ca_bundle' ));
	}

	public function iframe_resize_helper($wp_query) {
		if(isset($_GET['_wpph_iframe_height'])) {
			$h = sanitize_text_field($_GET['_wpph_iframe_height']);
			echo <<< EOT
			<html><head></head><body>
				<script type='text/javascript'>
					parent.parent.wpphSetIframeHeight($h);
				</script>
			</html>
EOT;
		die();

		}elseif(isset($_GET['_wpph_clear_invoices'])) {
			$this->clear_invoices();
			die();
		}
	}



	public function enable_license() {
		if(did_action("admin_menu")) {
			$this->_enable_license();
		} else {
			add_action( 'admin_menu', array($this,'enable_license'), 1000);
		}
	}

	private function _enable_license() {
		$menu_title = $this->options['license_menu_title'];
		$menu_pos = $this->options['license_menu_position'];
		$menu_cap = $this->options['license_menu_capability'];
		$menu_icon = $this->options['license_menu_icon'];

		$this->pages['license'] = "/wp-admin/admin.php?page=".$this->slug."-license";
		if($this->options['license_menu_slug']) {
			$menu_hook = add_submenu_page(
				$this->options['license_menu_slug'],
				$menu_title, $menu_title, $menu_cap,
				$this->slug."-license", array($this,"license_menu"));
		} else {
			$menu_hook = add_menu_page($menu_title, $menu_title, $menu_cap,
				$this->slug."-license",
				array($this,"license_menu"), $menu_icon, $menu_pos);
		}

		if($this->options['enable_license_purchase']) {
			add_action( 'admin_head-'.$menu_hook, array($this,'include_purchase'), 1000);
		}

		return $menu_hook;
	}

	private function _alert($msg) {
		return "<div class='updated'><p>$msg</p></div>";
	}

	public function include_purchase() {
		$site_url = get_site_url();
		$types = $this->get_license_types();
		$license_types_str = json_encode($types);
		echo <<< EOT
			<script type="text/javascript">
				window.__wpph_site_url = "$site_url";
				window._wwph_license_types = $license_types_str;
				wpphSetIframeHeight = function(h) {
					jQuery("#wpph-purchase").height(h);
				};
				jQuery(document).ready(function() {
					jQuery("#change-billing").click(function() {
						var frame = jQuery("#wpph-purchase");
						frame.show();
						frame.attr("src", frame.data("src"));
						return false;
					});
				});
			</script>
EOT;
	}

	public function license_menu() {
		$messages = "";
		if(isset($_POST['license'])) {
			$license = sanitize_text_field($_POST['license']);
			$this->set_license($license);
			$valid = $this->check_license(true);
			if($valid) {
				$messages .= $this->_alert("Your license key has been set.");
			} else {
				$messages .= $this->_alert("Your license key is invalid.");
			}
		} else {
			$valid = $this->check_license();
		}

		$menu_title = $this->options['license_menu_title'];
		$license = $this->get_license();
		$invoices = $this->get_invoices();

		echo <<< EOT
			<div class="wrap">
				<div id="icon-themes" class="icon32"><br></div>
				<h2>$menu_title</h2>
				{$messages}
				<form method="POST" action=""/>
					<table class="form-table">
						<tbody>
							<tr valign="top">
								<th scope="row">
									<label for="license">Plugin License Key</label>
								</th>
								<td>
									<input name="license" type="text" id="license" value="{$license}" class="regular-text">
								</td>
							</tr>
						</tbody>
					</table>
					<p class="submit">
						<input type="submit" name="submit" id="submit" class="button button-primary" value="Update License">
					</p>
				</form>
EOT;

		if($valid) {
			$licensetype = $this->get_license_type();
		}

		if($licensetype) {
			$name = $licensetype['name'];
			$data = $licensetype['data'];
			$desc = $licensetype['description'];
			$price = $licensetype['price'];
			$billing_type = $licensetype['billing_type'];
			if($desc) {
				echo esc_attr($desc);
			} else {
				echo <<< EOT
					<table class="form-table">
						<tbody>
							<tr valign="top">
								<th scope="row">
									<label>License Type</label>
								</th>
								<td>$name</td>
							</tr>
				EOT;
				foreach($data as $key => $value) {
					$value = esc_attr($value);
					echo <<< EOT
						<tr valign="top">
							<th scope="row">
								<label>{$key}</label>
							</th>
							<td>
								{$value}
							</td>
						</tr>
					EOT;
				}
				echo '</tbody></table>';
			}
			$url = $this->api_url_base. "/plugin/purchase/form/{$license}?k=" . $this->api_key . "&s=" . get_site_url();
			echo <<< EOT
				<iframe scrolling="no" frameborder=0 id="wpph-purchase" data-src="$url" style="width:100%; min-height: 50px; overflow:hidden; display: none;"></iframe>
EOT;
		} else if($this->options['enable_license_purchase']) {
			$types = $this->get_license_types();
			$url = $this->api_url_base. "/plugin/purchase/form?k=" . $this->api_key . "&s=" . get_site_url();
			echo <<< EOT
				<iframe scrolling="no" frameborder=0 id="wpph-purchase" src="$url" style="width:100%; min-height: 50px; overflow:hidden;"></iframe>
EOT;
		}

		$billing = "$" . $licensetype['price'];
		$inc_cancel = "[<a href='#' id='cancel-license'>cancel license</a>]";

		switch ($licensetype['billing_type']) {
			case self::BILLING_TYPE_MONTHLY:
				$billing .= "/month";
				break;
			case self::BILLING_TYPE_YEARLY:
				$billing .= "/year";
				break;
			default:
				$inc_cancel = "";
				break;
		}

		if($valid) {
			echo <<< EOT
				<h2>Your Billing Information</h2>
				<table class="form-table">
					<tbody>
						<tr valign="top">
							<th scope="row">
								<label>Current License Type:</label>
							</th>
							<td>{$licensetype['name']}</td>
						</tr>
						<tr valign="top">
							<th scope="row">
								<label>Price:</label>
							</th>
							<td>$billing $inc_cancel</td>
						</tr>
					</tbody>
				</table>
EOT;
			if($invoices === FALSE) {
				echo $this->_alert("Whoops - an error occurred while fetching your invoices.");
			} elseif(count($invoices) > 0) {
				echo <<< EOT
					<h3>Your Invoices</h3>
					<table class="wp-list-table widefat fixed">
						<thead>
							<tr>
								<th scope="col" class="manage-column desc" style="">Date</th>
								<th scope="col" class="manage-column desc" style="">Status</th>
								<th scope="col" class="manage-column desc" style="">Amount</th>
								<th scope="col" class="manage-column desc" style="">Card</th>
							</tr>
						</thead>
						<tbody>
EOT;
				foreach($invoices as $invoice) {
					$idate = date_i18n(get_option('date_format'), strtotime($invoice['date']));
					$amount = "$".$invoice['amount'];
					if($invoice['amount_refunded']) {
						$amount .= " ($".$invoice['amount_refunded']. " refunded)";
					}
					$change = "";
					if($invoice['status'] == "Upcoming") {
						$change = "<a href='#' id='change-billing'>[change]</a>";
					}
					echo <<< EOT
							<tr valign="top">
								<td>{$idate}</td>
								<td>{$invoice['status']}</td>
								<td>\${$invoice['amount']}</td>
								<td>XXXX-XXXX-XXXX-{$invoice['card']} $change</td>
							</tr>
EOT;
					// if($invoice['amount_refunded']) {
				}
				echo "</tbody></table>";
			}
		}
		echo <<< EOT
			</div>
EOT;
	}

	public function enable_support_if_should() {
		$licensetype = $this->get_license_type();
		if($licensetype && isset($licensetype['include_support']) && $licensetype['include_support']) {
			$this->enable_support();
		}
	}

	public function enable_support() {
		if(did_action("admin_menu")) {
			$this->_enable_support();
		} else {
			add_action( 'admin_menu', array($this,'enable_support'), 1000);
		}
	}

	private function _enable_support() {
		$menu_title = $this->options['support_menu_title'];
		$menu_pos = $this->options['support_menu_position'];
		$menu_cap = $this->options['support_menu_capability'];
		$menu_icon = $this->options['support_menu_icon'];

		$this->pages['tickets'] = "/wp-admin/admin.php?page=".$this->slug."-support";
		if($this->options['support_menu_slug']) {
			add_submenu_page(
				$this->options['support_menu_slug'],
				$menu_title, $menu_title, $menu_cap,
				$this->slug."-support", array($this,"support_menu"));
		} else {
			add_menu_page($menu_title, $menu_title, $menu_cap,
				$this->slug."-support",
				array($this,"support_menu"), $menu_icon, $menu_pos);
		}

		$this->pages['open-ticket'] = "/wp-admin/admin.php?page=".$this->slug."-open-ticket";
		add_submenu_page(
			null,
	        'Open Support Ticket', 'Open Support Ticket',
        	$menu_cap, $this->slug."-open-ticket",
        	array($this,"support_open_ticket")
	    );

	    $this->pages['view-ticket'] = "/wp-admin/admin.php?page=".$this->slug."-view-ticket";
		add_submenu_page(
			null,
	        'View Support Ticket', 'View Support Ticket',
        	$menu_cap, $this->slug."-view-ticket",
        	array($this,"support_view_ticket")
	    );
	}

	public function support_view_ticket() {
		$user = wp_get_current_user();
		$tid = sanitize_text_field($_GET['tid']);
		$tid = intval($tid);
		if(isset($_POST['reply'])) {
		
			$comment_url = $this->api_url_base . "/plugin/support/ticket/" . $tid . "/comment";
			//handle the ticket request
			$request_string = array(
				'body' => array(
					"id"=>$this->idstr,
					"site-url"=>get_site_url(),
					'api-key' => $this->api_key,
					"license"=>$this->license,
					"email"=> sanitize_email($_POST['comment']['email']),
					"name"=> sanitize_text_field($_POST['comment']['name']),
					"comment"=> sanitize_text_field($_POST['comment']['comment'])
				),
				'user-agent' => 'WordPress/' . $wp_version . '; ' . get_bloginfo('url')
			);
			$result = wp_remote_post($comment_url, $request_string);
			if(is_wp_error($result)) {
				error_log($result->get_error_message());
				echo $this->_alert("An error occurred when posting your comment");
			} else{
				$result = json_decode($result['body']);
				if($result === FALSE || $result->status !== "success") {
					echo $this->_alert("An error occurred when posting your comment");
				} else {
					echo $this->_alert("Comment Posted!");
					echo <<< EOT
						<script type='text/javascript'>
							window.setTimeout(function() {
								window.location = "{$this->pages['view-ticket']}&tid={$tid}";
							}, 1000);
						</script>
EOT;
					return;
				}
			}
		}

		$tickets_url = $this->api_url_base . "/plugin/support/ticket/" . $tid;

		//handle the ticket request
		$request_string = array(
			'body' => array(
				"id"=>$this->idstr,
				"site-url"=>get_site_url(),
				'api-key' => $this->api_key,
				"license"=>$this->license,
			),
			'user-agent' => 'WordPress/' . $wp_version . '; ' . get_bloginfo('url')
		);

		if(isset($_GET['closed'])) {

			$request_string['body']['closed'] = 1;
			$open_current = '';
			$close_current = 'current';
		} else {
			$open_current = 'current';
			$close_current = '';
		}

		$user = wp_get_current_user();
		$result = wp_remote_post($tickets_url, $request_string);
		$ticket = NULL;
		if(is_wp_error($result)) {
			echo $this->_alert("An error occurred when fetching tickets");
		} else{
			$result = json_decode($result['body']);
			if($result === FALSE || $result->status !== "success") {
				echo $this->_alert("An error occurred when fetching tickets");
			} else {
				$ticket = $result->data;
			}
		}

		$message = "";
		if(isset($_GET['created'])) {
			$message = $this->_alert("Your support ticket has been created.");
		}

		echo <<< EOT
			<div class="wrap">
				<div id="icon-themes" class="icon32"><br></div>
				<h2>{$ticket->subject} <small style='padding-left: 10px;'>[{$ticket->status}]</small></h2>
				{$message}
				<table class="form-table">
					<tbody>
EOT;
		$i = 0;
		foreach($ticket->comments as $comment):
			$odd = $i++%2 == 1 ? 'odd' : '';
			$comment_date = date("F jS Y, g:ia", $comment->ts);
			$comment_comment = nl2br($comment->comment);
			echo <<< EOT
						<tr valign="top" class="{$odd}">
							<th scope="row">{$comment->name}<br/>{$comment->email}<br/>{$comment_date}</th>
							<td style='vertical-align: top;'><fieldset><p>{$comment_comment}</p></fieldset></td>
						</tr>
EOT;
		endforeach;
		echo <<< EOT
					<tr valign="top">
						<th scope="row">New Reply</th>
						<td style='vertical-align: top;'>
							<form method='POST'>
								<fieldset>
									<p>Your Name<p>
									<p><input type='text' class='large-text' name='comment[name]' value='{$user->user_nicename}'/></p>
									<p>Your Email<p>
									<p><input type='text' class='large-text'  name='comment[email]' value='{$user->user_email}'/></p>
									<p>Your Reply<p>
									<p><textarea name='comment[comment]' class='large-text' rows="10"></textarea></p>
									<p><input type='submit' name='reply' value='Send Reply' class='button button-primary'/></p>
								</fieldset>
							</form>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
EOT;
	}

	public function support_open_ticket() {
		$alert = "";
		if(isset($_POST['submit'])) {
			$open_url = $this->api_url_base."/plugin/support/new";

			//handle the ticket request
			$request_string = array(
				'body' => array(
					"id"=>$this->idstr,
					"site-url"=>get_site_url(),
					'api-key' => $this->api_key,
					"version" => $this->current_version,
					"license"=>$this->license,
					'name'=>sanitize_text_field($_POST['ticket']['name']),
					'email'=>sanitize_email($_POST['ticket']['email']),
					'subject'=>sanitize_text_field($_POST['ticket']['subject']),
					'message'=>sanitize_text_field($_POST['ticket']['message']),
				),
				'user-agent' => 'WordPress/' . $wp_version . '; ' . get_bloginfo('url')
			);
			if(isset($_POST['ticket']["details"])) {
				ob_start();
				phpinfo(INFO_GENERAL);
				//http://www.php.net/manual/es/function.phpinfo.php#84259
				$phpinfo = array('phpinfo' => array());
				if(preg_match_all('#(?:<h2>(?:<a name=".*?">)?(.*?)(?:</a>)?</h2>)|(?:<tr(?: class=".*?")?><t[hd](?: class=".*?")?>(.*?)\s*</t[hd]>(?:<t[hd](?: class=".*?")?>(.*?)\s*</t[hd]>(?:<t[hd](?: class=".*?")?>(.*?)\s*</t[hd]>)?)?</tr>)#s', ob_get_contents(), $matches, PREG_SET_ORDER))
					foreach($matches as $match)
				        if(strlen($match[1]))
				            $phpinfo[$match[1]] = array();
						elseif(isset($match[3]))
				            $phpinfo[end(array_keys($phpinfo))][$match[2]] = isset($match[4]) ? array($match[3], $match[4]) : $match[3];
				        else
				            $phpinfo[end(array_keys($phpinfo))][] = $match[2];

				ob_end_clean();
				$request_string['body']['data'] = json_encode(array(
					"wp_version" => get_bloginfo('version','raw'),
					"wp_plugins" => get_plugins(),
					"host" => $phpinfo,
				));
			}
			$result = wp_remote_post($open_url, $request_string);
			if(is_wp_error($result)) {
				$alert = "Whoops, an error occrred while submitting your ticket.";
			} else {
				$result_obj = json_decode($result['body']);
				if($result_obj && $result_obj->status == "success") {
					echo "<script>
							window.location='".$this->pages['view-ticket']."&tid=".$result_obj->ticket->id."&created=1';
						</script>";
					exit();
				} else {
					$alert = "Whoops, an error occrred while submitting your ticket.";
					if($result_obj->status == "vfailed") {
						foreach ($result_obj->errors as $field => $str) {
							$alert .= "<br/><b>".$str."</b>";
						}
						foreach ($result_obj->warnings as $field => $str) {
							$alert .= "<br/><b>".$str."</b>";
						}
					}
				}
			}

			$user_name = sanitize_text_field($_POST['ticket']['name']);
			$user_email = sanitize_email($_POST['ticket']['email']);
			$subject = sanitize_text_field($_POST['ticket']['subject']);
			$message = sanitize_text_field(htmlentities($_POST['ticket']['message']));
			$inc_details = isset($_POST['ticket']["details"]) ? 'checked' : '';
		} else {
			$user = wp_get_current_user();
			$user_name = $user->user_nicename;
			$user_email = $user->user_email;
			$subject = "";
			$message = "";
			$inc_details = 'checked';
		}
		if($alert) {
			$_alert = $this->_alert($alert);
		} else {
			$_alert = "";
		}
		echo <<< EOT
			<div class="wrap">
				<div id="icon-edit-pages" class="icon32 icon32-posts-page"><br></div>
				<h2>Open New Ticket</h2>
				{$_alert}
				<form method="post">
					<table class="form-table">
						<tbody>
							<tr valign="top">
								<th scope="row"><label for="blogname">Your Name</label></th>
								<td>
									<input name="ticket[name]" type="text" value="{$user_name}" class="regular-text">
								</td>
							</tr>
							<tr valign="top">
								<th scope="row"><label for="blogname">Your Email</label></th>
								<td>
									<input name="ticket[email]" type="text" value="{$user_email}" class="regular-text">
								</td>
							</tr>
							<tr valign="top">
								<th scope="row"><label for="blogname">Ticket Subject</label></th>
								<td>
									<input name="ticket[subject]" type="text" value="{$subject}" class="regular-text">
								</td>
							</tr>
							<tr valign="top">
								<th scope="row"><label for="blogname">Message</label></th>
								<td>
									<textarea class="regular-text" rows="5" name="ticket[message]">{$message}</textarea>
								</td>
							</tr>
							<tr valign="top">
								<th scope="row"><label for="blogname">Include Details</label></th>
								<td>
									<input name='ticket[details]' type='checkbox' {$inc_details}/><label>Include WordPress installation details including version, installed plugins, and host operating system information.</label>
								</td>
							</tr>
							<tr valign="top">
								<th></th>
								<td><input type="submit" name="submit" id="submit" class="button button-primary" value="Submit Ticket"></td>
							</tr>
						</tbody>
					</table>
				</form>
			</div>
EOT;
	}

	public function support_menu() {
		$tickets_url = $this->api_url_base."/plugin/support/tickets";

		//handle the ticket request
		$request_string = array(
			'body' => array(
				"id"=>$this->idstr,
				"site-url"=>get_site_url(),
				'api-key' => $this->api_key,
				"license"=>$this->license,
			),
			'user-agent' => 'WordPress/' . $wp_version . '; ' . get_bloginfo('url')
		);

		if(isset($_GET['closed'])) {
			$request_string['body']['closed'] = 1;
			$open_current = '';
			$close_current = 'current';
		} else {
			$open_current = 'current';
			$close_current = '';
		}

		$result = wp_remote_post($tickets_url, $request_string);
		$tickets = array();

		if(is_wp_error($result)) {
			echo $this->_alert("An error occurred when fetching tickets");
		} else{
			$result = json_decode($result['body']);
			if($result === FALSE || $result->status !== "success") {
				echo $this->_alert("An error occurred when fetching tickets");
			} else {
				$tickets = $result->data;
			}
		}

		echo <<< EOT
			<div class="wrap">
				<div id="icon-themes" class="icon32"><br></div>
				<h2>Support <a href="{$this->pages['open-ticket']}" class="add-new-h2">Open New Ticket</a></h2>

				<ul class="subsubsub">
					<li><a href="{$this->pages['tickets']}" class="{$open_current}">Open Tickets</a></li>
					<li><a href="{$this->pages['tickets']}&amp;closed" class="{$close_current}">Closed Tickets</a></li>
				</ul>
				<br/>
				<table class="wp-list-table widefat fixed posts" cellspacing="0">
					<thead>
						<tr>
							<th class="manage-column column-cb check-column" style="">
								<label class="screen-reader-text" for="cb-select-all-1">Select All</label><input id="cb-select-all-1" type="checkbox">
							</th>
							<th>Subject</th>
							<th>Status</th>
							<th scope="col" id="tags" class="manage-column column-tags" style="text-align: center;">Comments</th>
						</tr>
					</thead>
					<tfoot>
						<tr>
						<th class="manage-column column-cb check-column" style="">
							<label class="screen-reader-text" for="cb-select-all-1">Select All</label><input id="cb-select-all-1" type="checkbox">
						</th>
						<th>Subject</th>
						<th>Status</th>
						<th scope="col" id="tags" class="manage-column column-tags" style="text-align: center;">Comments</th>
						</tr>
					</tfoot>
					<tbody id="the-list">
EOT;
		if(empty($tickets)):
			echo <<< EOT
				<tr>
					<td colspan='4'>
						There are no tickets here.
					</td>
				</tr>
EOT;
		else:
			foreach($tickets as $ticket):
				echo <<< EOT
					<tr>
						<td>
							<input type="checkbox">
						</td>
						<td><a href='{$this->pages['view-ticket']}&amp;tid={$ticket->id}'>{$ticket->subject}</a></td>
						<td>{$ticket->status}</td>
						<td style='text-align: center;'>{$ticket->comments}</td>
					</tr>
EOT;
			endforeach;
		endif;

		echo <<<EOT
					</tbody>
				</table>
			</div>
EOT;
	}

	public function get_license() {
		return $this->license;
	}

	public function clear_invoices() {
		delete_option($this->optkey."invoices");
	}

	public function get_invoices() {
		$invoices = json_decode(get_option($this->optkey."invoices", "{}"),true);
		if(!is_array($invoices) || count($invoices) == 0) {
			//force a license check to fill the invoices
			$this->check_license(true);
			$invoices = json_decode(get_option($this->optkey."invoices", "{}"),true);
		}
		return $invoices;
	}

	public function fetch_invoices() {
		$request_string = array(
			'body' => array(
				"id"=>$this->idstr,
				"site-url"=>get_site_url(),
				'api-key' => $this->api_key,
				"current-version" => $this->current_version,
				"license"=>$this->license
			),
			'user-agent' => 'WordPress/' . $wp_version . '; ' . get_bloginfo('url')
		);

		$result = wp_remote_post($this->invoices_url, $request_string);
		// error_log(print_r($result,true));

		if (is_wp_error($result)) {
			$jresult = FALSE;
			$this->lastError = $result->get_error_message();
		} else {
			$jresult = json_decode($result['body'],true);
		}

		if($jresult !== FALSE && $jresult['status'] !== "error") {
			$invoices = $jresult['data'];
		} else {
			$invoices = array();
		}

		update_option($this->optkey."invoices", json_encode($invoices));
		return TRUE;
	}

	public function set_license($key) {
		$optkey = $this->optkey."license";
		update_option($this->optkey."license", $key);
		update_option($this->optkey."license_check", 0);
		$this->license = $key;
	}

	public function check_license($force = FALSE) {
		// check once every 12 hours
		$last_check = get_option($this->optkey."license_check");
		$license_data = json_decode(get_option($this->optkey."license_data"), TRUE);
		$valid = get_option($this->optkey."valid_license", 'unknown');
		$fails = get_option($this->optkey."api_failures");
		$url = get_option($this->optkey."api_url");

		$this->enable_support_if_should();

		if(strlen(trim($this->license))==0 && $this->options['try_without_license']) {
			$started = get_option($this->optkey.'installed');
			$time_left = $this->options['trial_length'] - ($this->now - $started);
			if($time_left > 0) {
				$this->trial = TRUE;
				$this->trialTimeLeft = $time_left;
				return true;
			}
		}

		if($force || $last_check === FALSE || $last_check < ($this->now - $this->options['check_every'])) {
			$request_string = array(
				'body' => array(
					"action"=>"check",
					"id"=>$this->idstr,
					"site-url"=>get_site_url(),
					'api-key' => $this->api_key,
					"current-version" => $this->current_version,
					"license"=>$this->license
				),
				'user-agent' => 'WordPress/' . $wp_version . '; ' . get_bloginfo('url')
			);

			// error_log($this->api_url);
			// error_log(print_r($request_string,true));
			$result = wp_remote_post($this->api_url, $request_string);
			// error_log(print_r($result,true));

			if (is_wp_error($result)) {
				$jresult = FALSE;
				$this->lastError = $result->get_error_message();
			} else {
				$jresult = json_decode($result['body'],true);
			}

			if($jresult === FALSE || $jresult['status'] == "error") {
				if($fails === FALSE) {
					//this is our first api failure
					update_option($this->optkey."api_failures",1);
					$fails = 1;
				} else {
					$fails += 1;
				}

				if($fails < $this->options['failures_before_invaildate']) {
					// we are still okay
					update_option($this->optkey."api_failures",$fails);
				} else {
					/* We've failed too many times */
					$valid = false;
					update_option($this->optkey."valid_license",0);
					update_option($this->optkey."api_failures",0);
				}

				// lets try again in a little bit
				update_option($this->optkey."license_check",
				$this->now - $this->options['check_every'] + $this->options['retry_after_failure']);

			} else {
				// if we are here, that mean our api call succeeded
				update_option($this->optkey."api_failures",0);
				$new_valid = $jresult['valid'] == 1;
				$this->set_license_types($jresult['types']);

				if($new_valid) {
					$this->set_current_license($jresult['type']);
					$this->fetch_invoices(); //if it's valid, we must have an invoice - right?
				}
				$this->enable_support_if_should();

				if($valid != $new_valid) {
					// our license key validitiy has changed
					$valid = $new_valid;
					if($valid) {
						update_option($this->optkey."valid_license", 1);
						if(is_callable($this->options['onValidated'])) {
							$this->options['onValidated']();
						}
					} else {
						update_option($this->optkey."valid_license", 0);
						if(is_callable($this->options['onInvalidated'])) {
							$this->options['onInvalidated']();
						}
					}
				}

				update_option($this->optkey."license_check", $this->now);

				if($new_valid === FALSE) {
					$this->lastError = $jresult['reason'];
				}
			}
		}


		if($valid) {
			if(is_callable($this->options['isValid'])) {
				$this->options['isValid']($license_data);
			}
		} else {
			if(is_callable($this->options['onInvalidated'])) {
				$this->options['onInvalidated']();
			}
		}

		return $valid;
	}

	public function is_trial() {
		return $this->trial === TRUE;
	}

	public function trial_time_remaining() {
		return $this->trialTimeLeft;
	}

	public function set_license_types($types) {
		update_option($this->optkey."license_types", json_encode($types));
	}

	public function get_license_types() {
		$types = json_decode(get_option($this->optkey."license_types", "{}"),true);
		if(!is_array($types) || count($types) == 0) {
			//force a license check to fill the types
			$this->check_license(true);
		}
		return $types;
	}

	public function set_current_license($type) {
		update_option($this->optkey."license_type", $type);
	}

	public function get_license_type() {
		$type = get_option($this->optkey."license_type", "");
		$types = json_decode(get_option($this->optkey."license_types", "[]"),true);
		if(isset($types[$type])) {
			return $types[$type];
		} else {
			return FALSE;
		}
	}

	private function takeover_updates() {
		// take over the update check
		
		// take over the plugin info screen
		add_filter('plugins_api', array($this, '_plugin_api_call'), 10, 3);
		// hide from wordpress.org
		add_filter('http_request_args', array($this, '_filter_parse_arr') , 10, 2);
	}

	

	public function _plugin_api_call($def, $action, $args) {
		global $wp_version;
		if (!isset($args->slug) || ($args->slug != $this->slug))
			return false;

		$args = (array) $args;
		// Get the current version
		$plugin_info = get_site_transient('update_plugins');

		$request_string = array(
			'body' => array(
				'action' => $action,
				"id"=>$this->idstr,
				"site-url"=>get_site_url(),
				'request' => json_encode($args),
				'api-key' => $this->api_key,
				"current-version" => $this->current_version,
				'license' => $this->license
			),
			'user-agent' => 'WordPress/' . $wp_version . '; ' . get_bloginfo('url')
		);

		$request = wp_remote_post($this->api_url, $request_string);

		if (is_wp_error($request)) {
			$response = new WP_Error('plugins_api_failed', __('An Unexpected HTTP Error occurred during the API request.</p> <p><a href="?" onclick="document.location.reload(); return false;">Try again</a>'), $request->get_error_message());
		} else {
			$response_arr = json_decode($request['body'], true);

			if ($response_arr === false)
				$response_arr = new WP_Error('plugins_api_failed', __('An unknown error occurred'), $request['body']);

			$response = new stdClass();
			foreach($response_arr as $k=>$v) {
				$response->$k = $v;
			}
		}

		return $response;
	}

	// Don't tell wordpress.org about me
	public function _filter_parse_arr($arr, $url) {
		if (preg_match("|api\.wordpress\.org.*update-check|", $url)) {
			$plugins = null;
			if(isset($arr['body']['plugins'])) {
 				$plugins = maybe_unserialize($arr['body']['plugins']);
				if(is_object($plugins)) {
					unset($plugins->plugins[$this->slug]);
					$arr['body']['plugins'] = serialize($plugins);
				}
			}
		}
		return $arr;
	}

	public function __add_ca_bundle(&$ch) {
		$info = curl_getinfo($ch);

		// Set our SSL CA Bundle if it's our connection
		if(stristr($info['url'], $this->api_url_base) !== FALSE) {
			curl_setopt($ch, CURLOPT_CAINFO, dirname(__FILE__)."/wpph.bundle");
		}
	}
}
