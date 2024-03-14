<?php
	/*
		Plugin Name: Calculoid - Calculator builder
		Plugin URI: https://wordpress.org/plugins/calculoid/
		Description: Plugin easily inserts a caclulator from Calculoid.com service.
		Version: 1.4
		Author: Calculoid.com
		Author URI: http://calculoid.com
	*/	
	
	class Calculoid {
		# Global plugin vars
		var $plugin_file_path,
			$plugin_file_url,
			$calculoid_options,
			$calculoid_counter;
			
		# Initialize plugin
		public function __construct() {
			$this->plugin_file_path = str_replace('\\', '/', trailingslashit(dirname(__FILE__)));
			$this->plugin_file_url  = str_replace('\\', '/', trailingslashit(plugins_url('', __FILE__)));
			
			require_once($this->plugin_file_path . 'post-meta-manager-api/index.php');
			$this->set_shortcode_generator_admin_options();
			add_action('admin_menu', array($this, 'add_admin_page'));
			add_shortcode('calculoid', array($this, 'shortcode_calculoid'));
			add_filter('body_class', array($this, 'body_class_filter'), 9999999);
		}
		
		# Set admin fields (options)
		private function set_shortcode_generator_admin_options() {
			$this->calculoid_options = array(
				'Calculoid Options' => array(
					array(
						'name' => 'API Key',
						'key'  => 'apikey',
						'desc' => 'Input your Calculoid API key here. Your API key is visible in your profile at <a href="http://calculoid.com/#/profile">http://calculoid.com/#/profile</a>',
						'type' => PostMetaManagerFieldType::Textbox
					),
					array(
						'type' => PostMetaManagerFieldType::Delimiter
					),
					array(
						'type' => PostMetaManagerFieldType::Label,
						'custom_vars' => array('text' => '<i style="font-size: 12px; color: #555;">This plugin utilizes <a href="http://codex.wordpress.org/Shortcode" target="_blank">WordPress Shortcodes</a>, so in order to produce a calculator output on the front-end, you need to know a specific <a href="http://calculoid.com/" target="_blank">calculator ID</a>.<br />You can then use the shortcode anywhere within your WordPress instance like so => <strong style="font-weight: 800;">[calculoid id="123"]</strong> (replace 123 with ID of the calculator you want to embed)<br /><br />Make sure your Calculoid API key is valid and entered as well.</i>')
					),
				)
			);		
		}
		
		# Add admin page to WP admin
		public function add_admin_page() {
			add_options_page('Calculoid Admin', 'Calculoid Admin', 'manage_options', 'calc-admin', array($this, 'render_admin_page'));
		}
		
		# Render admin page markup
		public function render_admin_page() {
			# Save new data
			if (isset($_POST['calculoid_options']) && is_array($_POST['calculoid_options'])) {
				$post_data = $_POST['calculoid_options'];
				update_option('calculoid_options', $post_data);
				?><div class="updated"><p><strong>Options saved.</strong></p></div><?php
			}
			
			# Load existing data
			$saved_calculoid_options = get_option('calculoid_options');
			$saved_calculoid_options = $saved_calculoid_options === false ? array() : $saved_calculoid_options;
			$pmm = new PostMetaManager('calculoid_options', $saved_calculoid_options);
			
			# Render page markup
			if (count($this->calculoid_options) > 0) {
			?>
				<?php /* HTML */ ?>
				<form action="" method="POST" class="calcShortcodeGeneratorForm">	
					<div class="wrap">
						<div id="icon-options-general" class="icon32"><br></div>
						<h2>Calculoid Options</h2>
						<br />			
						<div class="clear"></div>
						<div id="wrp">
							<div id="options-tabs" style="visibility: hidden;">
								<ul>
									<?php foreach ($this->calculoid_options as $field_name => $field_data) { ?>
										<li><a href="#<?php _e(sanitize_title($field_name)) ?>"><?php _e($field_name) ?></a></li>
									<?php } ?>
								</ul>
								<?php foreach ($this->calculoid_options as $field_name => $fields) { ?>
									<div id="<?php _e(sanitize_title($field_name)) ?>">
										<?php
										$pmm->start();
										foreach ($fields as $field_data) $pmm->add_fields($field_data);
										$pmm->end();
										$pmm->flush();
										?>
									</div>
								<?php } ?>
							</div>
						</div>
						<div class="clear"></div>
						<script type="text/javascript">
							jQuery(function ($)
							{
								$("#options-tabs").tabs().css("visibility", "visible");
							});
						</script>
						<p class="submit">
							<input type="button" class="button button-primary calcSubmitForm" value="Save Changes">
						</p>
					</div>
				</form>
				
				<?php /* JS */ ?>
				<script type="text/javascript">
					jQuery(function ($)
					{
						$(".calcSubmitForm").click(function (e)
						{
							e.preventDefault();
							$(".calcShortcodeGeneratorForm").submit();
						});
					});
				</script>
			<?php
			}
		}
		
		# Shortcode
		public function shortcode_calculoid($atts) {
			$saved_calculoid_options = get_option('calculoid_options');

			extract(shortcode_atts(
				array(
					'id' => 0,
					'show_title' => 1,
					'show_description' => 1
				), $atts
			));

			if (isset($saved_calculoid_options) && is_array($saved_calculoid_options)) {
				extract($saved_calculoid_options);
				$apikey = html_entity_decode($apikey);
				$apikey = esc_attr($apikey);
			} else {
				$apiKey = '';
			}

			if (is_numeric($id) && $id > 0) {
				ob_start();
				?>
					<?php if ($this->calculoid_counter == 0) : ?>
						<link rel="stylesheet" href="https://embed.calculoid.com/styles/main.css" />
						<script type="text/javascript" src="https://embed.calculoid.com/scripts/combined.min.js"></script>
					<?php endif; ?>
					<div ng-controller="CalculoidMainCtrl" ng-init="init({calcId:<?php _e($id) ?>,apiKey:'<?php _e($apikey) ?>',showTitle:<?php _e($show_title) ?>,showDescription:<?php _e($show_description) ?>})" ng-include="load()"></div>					
				<?php
				$content = ob_get_clean();
				$this->calculoid_counter++;
				return $content;
			}
		}
		
		public function body_class_filter($class) {
			$class[] = '" ng-app="calculoid';
			return $class;
		}
	}
	
	# Initialize plugin
	new Calculoid();
?>