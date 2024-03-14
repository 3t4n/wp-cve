<?php if (!defined('ABSPATH')) {exit;}
/**
* Plugin Debug Page
*
* @link			https://icopydoc.ru/
* @since		1.3.0
*/

class GUPFW_Debug_Page {
	private $pref = 'gupfw';	
	private $feedback;

	public function __construct($pref = null) {
		if ($pref) {$this->pref = $pref;}
		$this->feedback = new GUPFW_Feedback();

		$this->listen_submit();
		$this->get_html_form();	
	}

	public function get_html_form() { ?>
 		<div class="wrap">
			<h1><?php _e('Debug page', $this->get_pref()); ?> v.<?php echo gupfw_optionGET('gupfw_version'); ?></h1>
			<?php do_action('my_admin_notices'); ?>
			<div id="dashboard-widgets-wrap">
				<div id="dashboard-widgets" class="metabox-holder">
					<div id="postbox-container-1" class="postbox-container">
						<div class="meta-box-sortables">
							<?php $this->get_html_block_logs(); ?>
						</div>
					</div>
					<div id="postbox-container-2" class="postbox-container">
						<div class="meta-box-sortables">
							<?php $this->get_html_block_possible_problems(); ?>
						</div>
					</div>
					<div id="postbox-container-3" class="postbox-container">
						<div class="meta-box-sortables">
							<?php $this->get_html_block_sandbox(); ?>
						</div>
					</div>
					<div id="postbox-container-4" class="postbox-container">
						<div class="meta-box-sortables">
							<?php do_action('gupfw_before_support_project'); ?>
							<?php $this->feedback->get_form(); ?>
						</div>
					</div>
				</div>
			</div>		
		</div><?php // end get_html_form();
	}

	public function get_html_block_logs() { 
		$gupfw_keeplogs = gupfw_optionGET($this->get_input_name_keeplogs());
		$gupfw_disable_notices = gupfw_optionGET($this->get_input_name_disable_notices()); ?>		    	 
		<div class="postbox">
			<h2 class="hndle"><?php _e('Logs', $this->get_pref()); ?></h2>
			<div class="inside">
				<p><?php if ($gupfw_keeplogs === 'on') {
					$upload_dir = wp_get_upload_dir();
					echo '<strong>'. __("Log-file here", $this->get_pref()).':</strong><br /><a href="'.$upload_dir['baseurl'].'/gift-upon-purchase-for-woocommerce/plugin.log" target="_blank">'.$upload_dir['basedir'].'/gift-upon-purchase-for-woocommerce/plugin.log</a>';			
				} ?></p>
				<form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post" enctype="multipart/form-data">
					<table class="form-table"><tbody>
					<tr>
						<th scope="row"><label for="<?php echo $this->get_input_name_keeplogs(); ?>"><?php _e('Keep logs', $this->get_pref()); ?></label><br />
							<input class="button" id="<?php echo $this->get_submit_name_clear_logs(); ?>" type="submit" name="<?php echo $this->get_submit_name_clear_logs(); ?>" value="<?php _e('Clear logs', $this->get_pref()); ?>" />
						</th>
						<td class="overalldesc">
							<input type="checkbox" name="<?php echo $this->get_input_name_keeplogs(); ?>" id="<?php echo $this->get_input_name_keeplogs(); ?>" <?php checked($gupfw_keeplogs, 'on' ); ?>/><br />
							<span class="description"><?php _e('Do not check this box if you are not a developer', $this->get_pref()); ?>!</span>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="<?php echo $this->get_input_name_disable_notices(); ?>"><?php _e('Disable notices', $this->get_pref()); ?></label></th>
						<td class="overalldesc">
							<input type="checkbox" name="<?php echo $this->get_input_name_disable_notices(); ?>" id="<?php echo $this->get_input_name_disable_notices(); ?>" <?php checked($gupfw_disable_notices, 'on'); ?>/><br />
							<span class="description"><?php _e('Disable notices from', $this->get_pref()); ?> Gift upon purchase for WooCommerce</span>
						</td>
					</tr>		 
					<tr>
						<th scope="row"><label for="button-primary"></label></th>
						<td class="overalldesc"><?php wp_nonce_field($this->get_nonce_action_debug_page(), $this->get_nonce_field_debug_page()); ?><input id="button-primary" class="button-primary" type="submit" name="<?php echo $this->get_submit_name(); ?>" value="<?php _e('Save', $this->get_pref()); ?>" /><br />
						<span class="description"><?php _e('Click to save the settings', $this->get_pref()); ?></span></td>
					</tr>         
					</tbody></table>
				</form>
			</div>
		</div><?php
	} // end get_html_block_logs();

	public function get_html_block_possible_problems() { ?>
		<div class="postbox">
			<h2 class="hndle"><?php _e('Possible problems', $this->get_pref()); ?></h2>
			<div class="inside"><?php
				$possible_problems_arr = $this->get_possible_problems_list();
				if ($possible_problems_arr[1] > 0) { // $possibleProblemsCount > 0) {
					echo '<ol>'.$possible_problems_arr[0].'</ol>';
				} else {
					echo '<p>'. __('Self-diagnosis functions did not reveal potential problems', $this->get_pref()).'.</p>';
				}
			?></div>
		</div><?php
	} // end get_html_block_sandbox();

	public function get_html_block_sandbox() { ?>
		<div class="postbox">
			<h2 class="hndle"><?php _e('Sandbox', $this->get_pref()); ?></h2>
			<div class="inside"><?php
				require_once GUPFW_PLUGIN_DIR_PATH.'sandbox.php';
				try {
					gupfw_run_sandbox();
				} catch (Exception $e) {
					echo 'Exception: ',  $e->getMessage(), "\n";
				}
			?></div>
	   </div><?php
	} // end get_html_block_sandbox();

	public static function get_possible_problems_list() {
		$possibleProblems = ''; $possibleProblemsCount = 0; $conflictWithPlugins = 0; $conflictWithPluginsList = ''; 				
		if (class_exists('MPSUM_Updates_Manager')) {
			$possibleProblemsCount++;
			$conflictWithPlugins++;
			$conflictWithPluginsList .= 'Easy Updates Manager<br/>';
		}
		if (class_exists('OS_Disable_WordPress_Updates')) {
			$possibleProblemsCount++;
			$conflictWithPlugins++;
			$conflictWithPluginsList .= 'Disable All WordPress Updates<br/>';
		}
		if ($conflictWithPlugins > 0) {
			$possibleProblemsCount++;
			$possibleProblems .= '<li><p>'. __('Most likely, these plugins negatively affect the operation of', 'gift-upon-purchase-for-woocommerce'). ' YML for Yandex Market:</p>'.$conflictWithPluginsList.'<p>'. __('If you are a developer of one of the plugins from the list above, please contact me', 'gift-upon-purchase-for-woocommerce').': <a href="mailto:support@icopydoc.ru">support@icopydoc.ru</a>.</p></li>';
		}
		return array($possibleProblems, $possibleProblemsCount, $conflictWithPlugins, $conflictWithPluginsList);
	}
	
	private function get_pref() {
		return $this->pref;
	}

	private function get_input_name_keeplogs() {
		return $this->get_pref().'_keeplogs';
	}

	private function get_input_name_disable_notices() {
		return $this->get_pref().'_disable_notices';
	}

	private function get_submit_name() {
		return $this->get_pref().'_submit_debug_page';
	}

	private function get_nonce_action_debug_page() {
		return $this->get_pref().'_nonce_action_debug_page';
	}

	private function get_nonce_field_debug_page() {
		return $this->get_pref().'_nonce_field_debug_page';
	}

	private function get_submit_name_clear_logs() {
		return $this->get_pref().'_submit_clear_logs';
	}	

	private function listen_submit() {
		if (isset($_REQUEST[$this->get_submit_name()])) {
			$this->seve_data();
			$message = __('Updated', $this->get_pref());
			$class = 'notice-success';	

			add_action('my_admin_notices', function() use ($message, $class) { 
				$this->admin_notices_func($message, $class);
			}, 10, 2);
		}
		
		if (isset($_REQUEST[$this->get_submit_name_clear_logs()])) {
			$filename = GUPFW_PLUGIN_UPLOADS_DIR_PATH.'/plugin.log';
			$res = unlink($filename);
			if ($res == true) {
				$message = __('Logs were cleared', $this->get_pref());
				$class = 'notice-success';				
			} else {
				$message = __('Error accessing log file. The log file may have been deleted previously', $this->get_pref());
				$class = 'notice-warning';	
			}

			add_action('my_admin_notices', function() use ($message, $class) { 
				$this->admin_notices_func($message, $class);
			}, 10, 2);
		}
		return;
	}

	private function seve_data() {
		if (!empty($_POST) && check_admin_referer($this->get_nonce_action_debug_page(), $this->get_nonce_field_debug_page())) { 
			if (isset($_POST[$this->get_input_name_keeplogs()])) {
				$keeplogs = sanitize_text_field( $_POST[$this->get_input_name_keeplogs()] );
			} else {
				$keeplogs = '';
			}
			if (isset($_POST[$this->get_input_name_disable_notices()])) {
				$disable_notices = sanitize_text_field( $_POST[$this->get_input_name_disable_notices()] );
			} else {
				$disable_notices = '';
			}
			if (is_multisite()) {
				update_blog_option(get_current_blog_id(), 'gupfw_keeplogs', $keeplogs);
				update_blog_option(get_current_blog_id(), 'gupfw_disable_notices', $disable_notices);
			} else {
				update_option('gupfw_keeplogs', $keeplogs);
				update_option('gupfw_disable_notices', $disable_notices);
			}
		}
		return;
	}

	private function admin_notices_func($message, $class) {
		printf('<div class="notice %1$s"><p>%2$s</p></div>', $class, $message);
	}
}