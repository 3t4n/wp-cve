<?php
class TrustindexWoocommercePlugin extends tmpTrustindexPlugin
{
private $plugin_file_path;
private $plugin_name;
private $platform_name;
public $shortname;
private $version;
public static $company_default_languages = [
'de' => "Deutsch",
'en' => "English",
'es' => "Español",
'fr' => "Français",
'it' => "Italiano",
'hu' => "Magyar",
'pl' => "Polski",
'pt' => "Português",
'ru' => "Русский",
'sv' => "Svenska",
'tr' => "Türkçe",
'vi' => 'Tiếng Việt',
'ar' => "العربية",
'zh' => "汉语",
'hi' => "हिन्दी",
'ja' => '日本',
'ko' => '한국어'
];


public function uninstall()
{
foreach ($this->get_option_names() as $opt_name)
{
delete_option($this->get_option_name($opt_name));
}
global $wpdb;
if($this->is_noreg_table_exists())
{
$wpdb->query('DROP TABLE `'. $this->get_noreg_tablename() .'`');
}
if($this->is_schedule_table_exists())
{
$wpdb->query('DROP TABLE `'. $this->get_schedule_tablename() .'`');
}
if($this->is_unsubscribe_table_exists())
{
$wpdb->query('DROP TABLE `'. $this->get_unsubscribe_tablename() .'`');
}
if($timestamp = wp_next_scheduled($this->get_schedule_cronname()))
{
wp_unschedule_event($timestamp, $this->get_schedule_cronname());
}
if(is_file($this->getCssFile()))
{
unlink($this->getCssFile());
}
}
public function getShortName()
{
return 'trustindex';
}
public function is_noreg_linked()
{
return true;
}
public function loadI18N()
{
load_plugin_textdomain('trustindex-woocommerce', false, $this->get_plugin_slug() . DIRECTORY_SEPARATOR . 'languages');
}
public static function ___($text, $params = null)
{
if (!is_array($params))
{
$params = func_get_args();
$params = array_slice($params, 1);
}
return vsprintf(__($text, 'trustindex-woocommerce'), $params);
}
public function admin_init()
{
$sub_id = $this->is_trustindex_connected();
if($sub_id)
{
$token = get_option($this->get_option_name("ti-token"));
if(!$token)
{
$remote_url = "https://admin.trustindex.io/" . "api/restoreIntegration?". http_build_query([
'type' => 'woocommerce',
'subscription_id' => $sub_id,
'source_id' => get_option($this->get_option_name('source-id', null)),
'url' => admin_url('admin-ajax.php') . '?action=trustindex_review_webhook'
]);
$response = wp_remote_get($remote_url);
if(is_wp_error($response))
{
echo $this->get_alertbox('error', '<br />' .$this->___('Could not create webhook for review auto refresh.<br />Please reload the page.<br />If the problem persists, please write an email to support@trustindex.io.'));
echo $response->get_error_message();
exit;
}
$json = json_decode($response['body'], true);
if(isset($json['token']) && $json['token'])
{
update_option($this->get_option_name('ti-token'), $json['token'], false);
}
}
}
}
public function plugin_loaded()
{
global $wpdb;
$this->handleCssFile();
$used_options = [];
foreach($this->get_option_names() as $opt_name)
{
$used_options []= $this->get_option_name($opt_name);
}
$wpdb->query('DELETE FROM `'. $wpdb->options .'` WHERE option_name LIKE "trustindex-'. $this->shortname .'-%" AND option_name NOT IN ("'. implode('", "', $used_options) .'")');

}
public function get_option_names()
{
return [
'active',
'version',
'page-details',
'subscription-id',
'style-id',
'review-content',
'filter',
'scss-set',
'css-content',
'lang',
'no-rating-text',
'dateformat',
'rate-us',
'verified-icon',
'enable-animation',
'show-arrows',
'content-saved-to',
'show-reviewers-photo',
'download-timestamp',
'widget-setted-up',
'disable-font',
'show-logos',
'show-stars',
'domain',
'source-id',
'campaign-active',
'trigger-event',
'trigger-delay',
'campaign-subject',
'campaign-text',
'sender-name',
'sender-email',
'ti-token',
'load-css-inline',
'align',
'amp-hidden-notification'
];
}
public function is_campaign_active()
{
return get_option($this->get_option_name("campaign-active"));
}
public function is_trustindex_connected()
{
$sub_id = get_option($this->get_option_name("subscription-id"), null);
if(!$sub_id)
{
return false;
}
$source_id = get_option($this->get_option_name("source-id"), null);
if(!$source_id)
{
return false;
}
return $sub_id;
}
public function save_trustindex()
{
global $wpdb;
require_once(ABSPATH . 'wp-admin' . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'upgrade.php');
$source_id = get_option($this->get_option_name('source-id', null));
$dbtable = $this->get_noreg_tablename();
if(!$this->is_noreg_table_exists())
{
dbDelta("CREATE TABLE $dbtable (
id TINYINT(1) NOT NULL AUTO_INCREMENT,
user VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci,
user_photo TEXT,
text TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
rating DECIMAL(3,1),
highlight VARCHAR(11),
date DATE,
PRIMARY KEY (id)
);");
}
if(!$this->is_noreg_table_exists() || !$source_id)
{
delete_option($this->get_option_name('page-details'));
delete_option($this->get_option_name('subscription-id'));
delete_option($this->get_option_name('source-id'));
delete_option($this->get_option_name('domain'));
echo '
<div class="ti-notice notice-error" style="margin: 25px 0 0 0">
<p>
'. TrustindexWoocommercePlugin::___('We can not create MySQL table for the reviews!') .'
</p>
</div>';
exit;
}
$response = $this->download_noreg_details([ 'id' => $source_id ]);
if($response['success'])
{
$page_details = $response['result'];
update_option($this->get_option_name('page-details'), $page_details, false);
$GLOBALS['wp_object_cache']->delete($this->get_option_name('page-details'), 'options');
}
$wpdb->query("TRUNCATE `$dbtable`;");
$reviews = $this->download_noreg_reviews([ 'id' => $source_id ], null);
$reviews = $reviews['success'] ? $reviews['result'] : [];
foreach($reviews as $row)
{
$this->save_review($row);
}
}
public function save_review($review)
{
global $wpdb;
$dbtable = $this->get_noreg_tablename();
$date = isset($review['created_at']) ? $review['created_at'] : (isset($review['date']) ? $review['date'] : '');
$wpdb->insert($dbtable, [
'user' => $review['reviewer']['name'],
'user_photo' => $review['reviewer']['avatar_url'],
'text' => $review['text'],
'rating' => $review['rating'] ? $review['rating'] : 5,
'date' => substr($date, 0, 10)
]);
}
public function getEmailText($orderId = null, $email = null, $text = null)
{
if(!$text)
{
$text = get_option($this->get_option_name('campaign-text'), self::getDefaultCampaignText());
}
$url = $this->getDefaultLandingPage();
$user_name = '{name}';
if($orderId)
{
$url .= '&defaults[order-id]='. $orderId;
$order = new WC_Order($orderId);
if($order)
{
$user = $order->get_user();
if($user)
{
$user_name = $user->user_nicename;
}
}
}
$text = strtr($text, [
'{name}' => $user_name,
'{link}' => $url,
'{unsubscribe_url}' => get_site_url() .'?ti-woocommerce-unsubscribe='. urlencode($email) .'&q='. md5($email)
]);
return $text;
}
public function sendMail($email, $orderId = null, $subject = null, $text = null)
{
if(!$subject)
{
$subject = get_option($this->get_option_name('campaign-subject'), self::getDefaultCampaignSubject());
}
$old_sender_email = self::getDefaultSenderEmail();
$old_sender_name = self::getDefaultSenderName();
$sender_email = get_option($this->get_option_name('sender-email'), $old_sender_email);
$sender_name = get_option($this->get_option_name('sender-name'), $old_sender_name);
if($sender_email !== $old_sender_email)
{
update_option('woocommerce_email_from_address', $sender_email, false);
}
if($sender_name !== $old_sender_name)
{
update_option('woocommerce_email_from_name', $sender_name, false);
}
$wc_mailer = WC()->mailer();
$wc_mailer->send($email, $subject, $wc_mailer->wrap_message($subject, $this->getEmailText($orderId, $email, $text)));
if($sender_email !== $old_sender_email)
{
update_option('woocommerce_email_from_address', $old_sender_email, false);
}
if($sender_name !== $old_sender_name)
{
update_option('woocommerce_email_from_name', $old_sender_name, false);
}
}
public static function getDefaultCampaignSubject()
{
$domain = get_bloginfo('name');
return "✩✩✩✩✩ Your opinion matters to $domain";
}
public static function getDefaultCampaignText()
{
$domain = get_bloginfo('name');
return "Dear {name}.<br />Thank you for choosing $domain.<br /><br />Our customers' opinion is important to us, as this way we can increase their satisfaction!<br /><br />Please share your experiences with us!<br /><br /><span style='font-size: 18pt'><a class='link' href='{link}'>Click here and review us! »</a></span><br />It's only a minute for you, but a huge help for us.<br /><br/>Thank you in advance,<br />$domain team<br /><br /><small>This is an automated e-mail sent to you because of your recent purchase made on $domain. Sharing your opinion concerning this purchase and your experiences with the product you bought can provide future clients with useful information.</small><br /><br /><small>If you do not wish to receive further e-mails from Trustindex, please <a href='{unsubscribe_url}' target='_blank'>click here</a>. Please note that this affects all invitation e-mails in connection with $domain.</small>";
}
public static function getDefaultSenderEmail()
{
$mailer = WC()->mailer();
return $mailer->get_from_address();
}
public static function getDefaultSenderName()
{
$mailer = WC()->mailer();
return $mailer->get_from_name();
}
public function getDefaultLandingPage()
{
$domain = get_option($this->get_option_name('domain', null));
return 'https://trustindex.io/write-review/'. $domain .'?defaults[rating]=5';
}
public function getCompanyPage()
{
$domain = get_option($this->get_option_name('domain', null));
return 'https://trustindex.io/reviews/'. $domain;
}


public function unsubscribe()
{
global $wpdb;
if(isset($_GET['ti-woocommerce-unsubscribe']))
{
$email = strtolower(sanitize_email($_GET['ti-woocommerce-unsubscribe']));
$md5 = sanitize_text_field($_GET['q']);
if(!$email || $md5 !== md5($email))
{
header("HTTP/1.0 404 Not Found");
exit;
}
$table_name = $this->get_unsubscribe_tablename();
if(!$this->is_unsubscribe_table_exists())
{
require_once(ABSPATH . 'wp-admin' . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'upgrade.php');
dbDelta("CREATE TABLE $table_name (
id BIGINT(20) NOT NULL AUTO_INCREMENT,
email VARCHAR(255) NOT NULL,
created_at DATETIME,
PRIMARY KEY (id)
);");
}
if($this->is_email_unsubscribed($email))
{
echo "Email already unsubscribed!";
exit;
}
$wpdb->insert($table_name, [
'email' => $email,
'created_at' => date('Y-m-d H:i:s')
]);
if($this->is_schedule_table_exists())
{
$wpdb->delete($this->get_schedule_tablename(), [ 'email' => $email, 'sent' => 0 ]);
}
echo "Email unsubscribed successfully!";
exit;
}
}
public function get_unsubscribe_tablename()
{
global $wpdb;
return $wpdb->prefix .'trustindex_woocommerce_unsubscribes';
}
public function is_unsubscribe_table_exists()
{
global $wpdb;
$dbtable = $this->get_unsubscribe_tablename();
return ($wpdb->get_var("SHOW TABLES LIKE '$dbtable'") == $dbtable);
}
public function is_email_unsubscribed($email)
{
global $wpdb;
if(!$this->is_unsubscribe_table_exists())
{
return false;
}
$email = sanitize_email($email);
$res = $wpdb->get_results('SELECT id FROM '. $this->get_unsubscribe_tablename() .' WHERE email LIKE "'. $email .'" LIMIT 1');
return count($res) == 1;
}
public function get_unsubscribes($page = 1, $query = "")
{
global $wpdb;
if(!$this->is_unsubscribe_table_exists())
{
return (object) [
'total' => 0,
'max_num_pages' => 0,
'unsubscribes' => []
];
}
require_once(ABSPATH . 'wp-admin' . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'upgrade.php');
$limit = 10;
$dbtable = $this->get_unsubscribe_tablename();
$sql = "SELECT * FROM `$dbtable` WHERE email LIKE '%$query%' ORDER BY `created_at` DESC";
$total = $wpdb->get_results(str_replace('*', 'COUNT(id) as num', $sql))[0]->num;
$sql .= ' LIMIT ' . (($page - 1) * $limit) . ', ' . $limit;
return (object) [
'total' => $total,
'max_num_pages' => ceil($total / $limit),
'unsubscribes' => $wpdb->get_results($sql)
];
}


public function get_schedule_tablename()
{
global $wpdb;
return $wpdb->prefix .'trustindex_woocommerce_schedule_list';
}
public function get_schedule_cronname()
{
return 'trustindex_woocommerce_cron';
}
public function is_schedule_table_exists()
{
global $wpdb;
$dbtable = $this->get_schedule_tablename();
return ($wpdb->get_var("SHOW TABLES LIKE '$dbtable'") == $dbtable);
}
public function get_pending_schedules()
{
global $wpdb;
if(!$this->is_schedule_table_exists())
{
return [];
}
require_once(ABSPATH . 'wp-admin' . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'upgrade.php');
$timestamp = time();
$dbtable = $this->get_schedule_tablename();
return $wpdb->get_results("SELECT id, email, order_id FROM `$dbtable` WHERE `timestamp` <= $timestamp AND sent = 0");
}
public function get_schedules($page = 1, $query = "")
{
global $wpdb;
if(!$this->is_schedule_table_exists())
{
return (object) [
'total' => 0,
'max_num_pages' => 0,
'schedules' => []
];
}
require_once(ABSPATH . 'wp-admin' . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'upgrade.php');
$limit = 10;
$dbtable = $this->get_schedule_tablename();
$sql = "SELECT * FROM `$dbtable` WHERE email LIKE '%$query%' ORDER BY `timestamp`";
$total = $wpdb->get_results(str_replace('*', 'COUNT(id) as num', $sql))[0]->num;
$sql .= ' LIMIT ' . (($page - 1) * $limit) . ', ' . $limit;
return (object) [
'total' => $total,
'max_num_pages' => ceil($total / $limit),
'schedules' => $wpdb->get_results($sql)
];
}
public function register_schedule_sent($email, $order_id, $schedule_id = null)
{
global $wpdb;
if(!$this->is_schedule_table_exists())
{
return false;
}
$dbtable = $this->get_schedule_tablename();
if($schedule_id)
{
$wpdb->query("UPDATE `$dbtable` SET sent = 1 WHERE id = '$schedule_id'");
}
else
{
$wpdb->insert($dbtable, [
'email' => $email,
'order_id' => $order_id,
'timestamp' => time(),
'sent' => 1,
'created_at' => date('Y-m-d H:i:s')
]);
}
}
public function get_previous_orders($page = 1, $query = "")
{
global $wpdb;
$id_list = [];
if($this->is_schedule_table_exists())
{
$tmp = $wpdb->get_results('SELECT GROUP_CONCAT(order_id) as id_list FROM `'. $this->get_schedule_tablename() .'`');
$id_list = explode(',', $tmp[0]->id_list);
}
$orders = wc_get_orders([
'status' => 'wc-completed',
'exclude' => $id_list,
'paginate' => true,
'paged' => $page,
'limit' => 10,
'query_term' => $query
]);
return $orders;
}


public function get_shortcode_name()
{
return 'trustindex-woocommerce';
}
public function shortcode_func($atts)
{
$class = new TrustindexWoocommercePlugin('woocommerce', __FILE__, "do-not-care-3.2.1", "", "");
if(!$class->is_noreg_table_exists())
{
return self::get_alertbox(
"error",
'<br />' . TrustindexWoocommercePlugin::___("Please fill out <strong>all the required fields</strong> in the <a href='%s'>widget settings</a> page", [ admin_url('admin.php?page='. $this->get_plugin_slug() .'/settings.php&tab=setup&step=4') ]),
false
);
}
else
{
return $class->get_noreg_list_reviews();
}
}

public function add_setting_menu_wc()
{
foreach(get_declared_classes() as $class_name)
{
if(strpos($class_name, 'TrustindexPlugin') !== FALSE)
{
$this->add_setting_menu();
break;
}
}
add_submenu_page(
'woocommerce',
'Trustindex.io',
self::___('Customer Reviews') . ' (Trustindex) <span class="awaiting-mod">'. self::___('New') .'</span>',
'edit_pages',
$this->get_plugin_slug() . '/settings.php'
);
}
public function add_plugin_action_links($links, $file)
{
$plugin_file = $this->get_plugin_slug() . '.php';
if (basename($file) == $plugin_file)
{
if(!class_exists('Woocommerce'))
{
return [ '<span style="color: red; font-weight: bold">'. TrustindexWoocommercePlugin::___('Activate WooCommerce first!') .'</span>' ];
}
$new_item2 = '<a target="_blank" href="https://www.trustindex.io" target="_blank">by <span style="background-color: #4067af; color: white; font-weight: bold; padding: 1px 8px;">Trustindex.io</span></a>';
$new_item1 = '<a href="' . admin_url('admin.php?page=' . $this->get_plugin_slug() . '/settings.php') . '">' . TrustindexWoocommercePlugin::___('Settings') . '</a>';
array_unshift($links, $new_item2, $new_item1);
}
return $links;
}
public function add_plugin_meta_links( $meta, $file )
{
$plugin_file = $this->get_plugin_slug() . '.php';
if (basename($file) == $plugin_file)
{
$meta[] = "<a href='http://wordpress.org/support/view/plugin-reviews/".$this->get_plugin_slug()."' target='_blank' rel='noopener noreferrer' title='" . TrustindexWoocommercePlugin::___('Rate our plugin') . ': '.$this->plugin_name . "'>" . TrustindexWoocommercePlugin::___('Rate our plugin') . '</a>';
}
return $meta;
}


public function trustindex_add_scripts($hook)
{
$plugin_slug = $this->get_plugin_slug();
$tmp = explode('/', $hook);
$current_slug = array_shift($tmp);
if($plugin_slug == $current_slug)
{
if(file_exists($this->get_plugin_dir() . 'static' . DIRECTORY_SEPARATOR . 'css' . DIRECTORY_SEPARATOR . 'admin.css'))
{
wp_enqueue_style('trustindex-woocommerce-admin-css', $this->get_plugin_file_url('static/css/admin.css'));
}
if(file_exists($this->get_plugin_dir() . 'static' . DIRECTORY_SEPARATOR . 'js' . DIRECTORY_SEPARATOR . 'admin-common.js'))
{
wp_enqueue_script('trustindex-woocommerce-admin-common-js', $this->get_plugin_file_url('static/js/admin-common.js'));
}
if(file_exists($this->get_plugin_dir() . 'static' . DIRECTORY_SEPARATOR . 'js' . DIRECTORY_SEPARATOR . 'admin.js'))
{
wp_enqueue_script('trustindex-woocommerce-admin-js', $this->get_plugin_file_url('static/js/admin.js'));
}
}
wp_register_script('trustindex_admin_popup', $this->get_plugin_file_url('static/js/admin-popup.js') );
wp_enqueue_script('trustindex_admin_popup');
}
}
?>