<?php

class InvelityMyGLSConnectProcess
{
	private $launcher;
	private $options;
	public $successful = [];
	public $unsuccessful = [];

	/**
	 * Loads plugin textdomain and sets the options attribute from database
	 */
	public function __construct(InvelityMyGLSConnect $launecher)
	{
		$this->launcher = $launecher;
		load_plugin_textdomain($this->launcher->getPluginSlug(), false, dirname(plugin_basename(__FILE__)) . '/lang/');
		$this->options = get_option('invelity_my_gls_export_options');
		add_action('admin_footer-edit.php', [$this, 'custom_bulk_admin_footer']);
		add_action('load-edit.php', [$this, 'custom_bulk_action']);
		add_action('admin_notices', [$this, 'custom_bulk_admin_notices']);
	}

	/**
	 * Adds option to export invoices to orders page bulk select
	 */
	function custom_bulk_admin_footer()
	{
		global $post_type;

		if ($post_type == 'shop_order') {
			?>
            <script type="text/javascript">
                jQuery(document).ready(function () {
                    jQuery('<option>').val('my_gls').text('<?php _e('Export MyGLS')?>').appendTo("select[name='action']");
                    jQuery('<option>').val('my_gls').text('<?php _e('Export MyGLS')?>').appendTo("select[name='action2']");
                });
            </script>
			<?php
		}
	}

	/**
	 * Sets up action to be taken after export option is selected
	 * If export is selected, provides export and refreshes page
	 * After refresh, notices are shown
	 */
	function custom_bulk_action()
	{

		global $typenow;
		$post_type = $typenow;

		if ($post_type == 'shop_order') {
			$wp_list_table = _get_list_table('WP_Posts_List_Table');  // depending on your resource type this could be WP_Users_List_Table, WP_Comments_List_Table, etc
			$action = $wp_list_table->current_action();


			$allowed_actions = ["my_gls"];
			if (!in_array($action, $allowed_actions)) {
				return;
			}

			// security check
			check_admin_referer('bulk-posts');

			// make sure ids are submitted.  depending on the resource type, this may be 'media' or 'ids'

			if (isset($_REQUEST['post'])) {
				$post_ids = array_map('intval', $_REQUEST['post']);
			}


			if (empty($post_ids)) {
				return;
			}

			// this is based on wp-admin/edit.php
			$sendback = remove_query_arg(['exported', 'untrashed', 'deleted', 'ids'], wp_get_referer());
			if (!$sendback) {
				$sendback = admin_url("edit.php?post_type=$post_type");
			}

			$pagenum = $wp_list_table->get_pagenum();
			$sendback = add_query_arg('paged', $pagenum, $sendback);

			switch ($action) {
				case 'my_gls':
					$options = [];
					$myGLS = new MyGLS($this->options);
					foreach ($post_ids as $postId) {
						global $woocommerce;
						$order = new WC_Order($postId);
						if ($order->has_shipping_method('local_pickup')) {
							$this->unsuccessful[] = [
								'orderId' => $postId,
								'message' => __('Order has local pickup shipping method', $this->launcher->getPluginSlug()),
							];
							continue;
						}

						$options[] = $myGLS->setParcelOptions($order);
					}

					$labels = $myGLS->printLabels($options);
					if (!$labels['success']) {
					    foreach ($labels['errors'] as $error){
						    $this->unsuccessful[] = $error;
                        }
					} else {
						$this->successful[] = [
							'url' => $labels['url'],
							'success' => true,
						];
					}

					$sendback = remove_query_arg(['exported', 'untrashed', 'deleted', 'ids'], wp_get_referer());
					if (!$sendback) {
						$sendback = admin_url("edit.php?post_type=$post_type");
					}
					$pagenum = $wp_list_table->get_pagenum();
					$sendback = add_query_arg('paged', $pagenum, $sendback);

					$successful = urlencode(serialize($this->successful));
					$unsuccessful = urlencode(serialize($this->unsuccessful));
					$sendback = add_query_arg(['my-gls-successful' => $successful, 'my-gls-unsuccessful' => $unsuccessful], $sendback);
					$sendback = remove_query_arg([
						'action',
						'action2',
						'tags_input',
						'post_author',
						'comment_status',
						'ping_status',
						'_status',
						'post',
						'bulk_edit',
						'post_view',
					], $sendback);
					wp_redirect($sendback);
					die();
					break;
				default:
					return;
			}
		}
	}

	function getHash($data)
	{
		$hashBase = '';
		foreach ($data as $key => $value) {
			if ($key != 'services'
				&& $key != 'hash'
				&& $key != 'timestamp'
				&& $key != 'printit'
				&& $key != 'printertemplate'
				&& $key != 'customlabel'
			) {
				$hashBase .= $value;
			}
		}
		return sha1($hashBase);
	}

	function getEaster($year)
	{ //Generates holidays. Default: Slovakia
		$sviatky = [];
		$s = ['01-01', '01-06', '', '', '05-01', '05-08', '07-05', '08-29', '09-01', '09-15', '11-01', '11-17', '12-24', '12-25', '12-26'];
		$easter = date('m-d', easter_date($year));
		$sdate = strtotime($year . '-' . $easter);
		$s[2] = date('m-d', strtotime('-2 days', $sdate)); //Firday
		$s[3] = date('m-d', strtotime('+1 day', $sdate)); //Monday
		foreach ($s as $day) {
			$sviatky[] = $year . '-' . $day;
		}
		return $sviatky;
	}

	function isSviatok($date)
	{
		$year = apply_filters('InvelityMyGLSConnectProcessIsSviatokYearFilter', date('Y'));
		$thisyear = $this->getEaster($year);
		$nextyear = $this->getEaster($year + 1); //generates next year for delivering after December in actual year
		$sviatky = [];
		$sviatky = array_merge($thisyear, $nextyear);
		$sviatky[] = '2018-10-30';
		$sviatky = apply_filters('InvelityMyGLSConnectProcessIsSviatokFilter', $sviatky);
		if (in_array($date, $sviatky)) {
			return true;
		}
		return false;
	}

	/**
	 * Displays the notice
	 */
	function custom_bulk_admin_notices()
	{
		global $post_type, $pagenow;

		if ($pagenow == 'edit.php' && $post_type == 'shop_order' && (isset($_REQUEST['my-gls-successful']) || isset($_REQUEST['my-gls-unsuccessful']))) {
			$successful = unserialize(str_replace('\\', '', urldecode($_REQUEST['my-gls-successful'])));
			$unsuccessful = unserialize(str_replace('\\', '', urldecode($_REQUEST['my-gls-unsuccessful'])));
			?>
            <style>
                .woocommerce-layout__notice-list-hide {
                    display: block;
                }
            </style>
            <?php
			if (count($successful) != 0) {
				echo "<div class=\"updated\">";
				foreach ($successful as $message) {
					$messageContent = sprintf(__('Your labels are ready to <a href="%s">download</a>', $this->launcher->getPluginSlug()), $message['url']);
					echo "<p>{$messageContent}</p>";
				}
				echo "</div>";
			}
			if (count($unsuccessful) != 0) {
				echo "<div class=\"error\">";
				foreach ($unsuccessful as $message) {
				    if ($message['order_id'] == 'global'){
					    $messageContent = sprintf(__('Labels was not generated. Error: %s', $this->launcher->getPluginSlug()), $message['message']);
					    echo "<p>{$messageContent}</p>";
                    } else {
					    $messageContent = sprintf(__('Order no. %s Was not generated. Error: %s', $this->launcher->getPluginSlug()), $message['order_id'], $message['message']);
					    echo "<p>{$messageContent}</p>";
				    }
				}
				echo "</div>";
			}
		}
	}

}