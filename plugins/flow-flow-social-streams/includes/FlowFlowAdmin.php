<?php namespace flow;

use flow\social\cache\LAFacebookCacheManager;
use flow\tabs\FFAddonsTab;
use flow\tabs\FFBackupTab;
use flow\tabs\FFSupportTab;
use flow\tabs\FFModerationTab;
use flow\tabs\FFSourcesTab;
use flow\tabs\FFStreamsTab;
use flow\db\FFDBMigrationManager;
use la\core\tabs\LAGeneralTab;
use la\core\tabs\LAAuthTab;

if ( ! defined( 'WPINC' ) ) die;
/**
 * Flow-Flow.
 *
 * Plugin class. This class should ideally be used to work with the
 * administrative side of the WordPress site.
 *
 * If you're interested in introducing public-facing
 * functionality, then refer to `FlowFlow.php`
 *
 * @package   FlowFlowAdmin
 * @author    Looks Awesome <email@looks-awesome.com>
 * @link      http://looks-awesome.com
 * @copyright Looks Awesome
 */
class FlowFlowAdmin extends LAAdminBase{
	/**
	 * Render the settings page for this plugin.
	 * @since 1.0.0
	 */
	public function display_plugin_admin_subpage(){
		$context = $this->context;
		
		/** @var LAFacebookCacheManager $facebookCache */
		$facebookCache = $context['facebook_cache'];

		$context['admin_page_title'] = esc_html( get_admin_page_title() );
		$context['options'] = FlowFlow::get_instance($context)->get_options();
		$context['auth_options'] = FlowFlow::get_instance($context)->get_auth_options();
		$context['extended_facebook_access_token'] = $facebookCache->getAccessToken();
		$context['extended_facebook_access_token_error'] = $facebookCache->getError();
		$this->db->dataInit();
		$context['streams'] = $this->db->streamsWithStatus();

		$tab_prefix = 'ff';
		$context['form-action'] = '';
		$context['tabs'][] = new FFStreamsTab();
		$context['tabs'][] = new FFSourcesTab();
		
		$context['tabs'][] = new FFModerationTab();
		$context['tabs'][] = new LAGeneralTab($tab_prefix);
		$context['tabs'][] = new LAAuthTab($tab_prefix);
		$context['tabs'][] = new FFBackupTab();
        $context['tabs'][] = new FFAddonsTab();
        $context['tabs'][] = new FFSupportTab();

		$context['buttons-after-tabs'] = '<li id="request-tab"><span>Save changes</span> <i class="flaticon-paperplane"></i></li>';
		$context = apply_filters('ff_change_context', $context);

		/** @noinspection PhpIncludeInspection */
		include_once($context['root']  . 'views/admin.php');
	}
	
	protected function initPluginAdminPage(){
		$mm = new FFDBMigrationManager($this->context);
		$mm->migrate();
		unset($mm);
	}
	
	protected function enqueueAdminStylesAlways($plugin_directory){
		wp_enqueue_style($this->getPluginSlug() .'-admin-icon-styles', $plugin_directory . 'css/admin-icon.css', array(), $this->context['version'] );
	}
	
	protected function enqueueAdminScriptsAlways($plugin_directory){
		wp_enqueue_script($this->getPluginSlug() . '-global-admin-script', $plugin_directory . 'js/global_admin.js', array('jquery', 'backbone', 'underscore'), $this->context['version']);
	}

	protected function enqueueAdminStylesOnlyAtPluginPage($plugin_directory){
		wp_enqueue_style($this->getPluginSlug() . '-admin-styles', $plugin_directory . 'css/admin.css', array(), $this->context['version']);
		wp_enqueue_style($this->getPluginSlug() . '-colorpickersliders', $plugin_directory . 'css/jquery-colorpickersliders.css', array(), $this->context['version']);
		
		// Load web font
		wp_register_style('ff-admin-fonts', '//fonts.googleapis.com/css?family=Montserrat:400,600|Roboto+Slab|Lato:300,400', array(), null, 'all');
		wp_enqueue_style('ff-admin-fonts');
		
		//for preview
		//TODO move to filter
		FlowFlow::get_instance($this->context)->enqueue_styles();


	}
	
	protected function enqueueAdminScriptsOnlyAtPluginPage($plugin_directory){
		$context = $this->context;

		wp_enqueue_script($this->getPluginSlug() . '-zeroclipboard', $plugin_directory . 'js/zeroclipboard/ZeroClipboard.min.js', array('jquery'), $this->context['version']);
		wp_enqueue_script($this->getPluginSlug() . '-tinycolor', $plugin_directory . 'js/tinycolor.js', array('jquery'), $this->context['version']);
		wp_enqueue_script($this->getPluginSlug() . '-colorpickersliders', $plugin_directory . 'js/jquery.colorpickersliders.js', array('jquery'), $this->context['version']);
		wp_enqueue_script($this->getPluginSlug() . '-streams-script', $plugin_directory . 'js/streams.js', array('jquery'), $this->context['version']);
		wp_enqueue_script($this->getPluginSlug() . '-admin-script', $plugin_directory . 'js/admin.js', array('jquery', 'backbone', 'underscore'), $this->context['version']);
		// wp_enqueue_script('flow-flow-paddle', 'https://cdn.paddle.com/paddle/paddle.js', array(), $this->context['version']);
		wp_localize_script($this->getPluginSlug() . '-admin-script', 'WP_FF_admin', array());
		// old
//		wp_localize_script($this->getPluginSlug() . '-admin-script', 'isWordpress', (string)FF_USE_WP);
//		wp_localize_script($this->getPluginSlug() . '-admin-script', '_siteurl', site_url());
//		wp_localize_script($this->getPluginSlug() . '-admin-script', 'la_plugin_slug_down', $context['slug_down']);
//		wp_localize_script($this->getPluginSlug() . '-admin-script', '_nonce', wp_create_nonce('flow_flow_nonce'));

		wp_localize_script($this->getPluginSlug() . '-admin-script', 'la_plugin', array('slug_down' => $context['slug_down']));

		// new
		/*
		if (file_exists(plugin_dir_path(__DIR__) . 'env.json')) {
			$env = json_decode( file_get_contents(plugin_dir_path( __DIR__ ) . 'env.json'), true);
		}
		*/

		$this->context['boosts'] = $this->db->getOption('boosts_email') != false;

		wp_localize_script($this->getPluginSlug() . '-admin-script', 'flow_flow_vars', array(
			'isWordpress' => (string)FF_USE_WP,
			'ajaxurl' => (string)$this->context['ajax_url'],
			'siteurl' => site_url(),
			'nonce' => wp_create_nonce('flow_flow_nonce'),
			//'m' => /*isset ( $env ) ? $env['mode'] : 'l' */ 'l'
			'm' => $this->context['boosts'] ?  'p' : 'l'
		));

		//for preview
		//TODO move to filter
		FlowFlow::get_instance()->enqueue_scripts();
	}
	
	protected function addPluginAdminSubMenu($displayAdminPageFunction){
		add_submenu_page(
			'flow-flow',
			'Flow-Flow Lite',
			'Flow-Flow Lite',
			'manage_options',
			$this->getPluginSlug() . '-admin',
			$displayAdminPageFunction
		);
	}
}