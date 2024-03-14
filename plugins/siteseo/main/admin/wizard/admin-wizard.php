<?php
/**
 * Setup Wizard Class.
 *
 * Takes new users through some basic steps to setup SiteSEO.
 *
 * @version	 3.5.8
 */
if ( ! defined('ABSPATH')) {
	exit;
}

/**
 * SITESEO_Admin_Setup_Wizard class.
 */
class SITESEO_Admin_Setup_Wizard {
	/**
	 * Current step.
	 *
	 * @var string
	 */
	private $step = '';

	/**
	 * Parent step.
	 *
	 * @var string
	 */
	private $parent = '';

	/**
	 * Steps for the setup wizard.
	 *
	 * @var array
	 */
	private $steps = [];

	/**
	 * Hook in tabs.
	 */
	public function __construct() {
		if (apply_filters('siteseo_enable_setup_wizard', true) && current_user_can(siteseo_capability('manage_options', 'Admin_Setup_Wizard'))) {
			add_action('admin_menu', [$this, 'admin_menus']);
			add_action('admin_init', [$this, 'setup_wizard']);

			//Remove notices
			remove_all_actions( 'admin_notices' );
			remove_all_actions( 'all_admin_notices' );

			//Disable Query Monitor
			add_filter('user_has_cap', 'siteseo_disable_qm', 10, 3);

			//Load our scripts and CSS
			add_action('admin_enqueue_scripts', [$this, 'enqueue_scripts']);
		}
	}

	/**
	 * Add admin menus/screens.
	 */
	public function admin_menus() {
		add_dashboard_page('', '', siteseo_capability('manage_options', 'menu'), 'siteseo-setup', '');
	}

	/**
	 * Register/enqueue scripts and styles for the Setup Wizard.
	 *
	 * Hooked onto 'admin_enqueue_scripts'.
	 */
	public function enqueue_scripts() {
		$prefix = '';
		wp_enqueue_style('siteseo-setup', SITESEO_ASSETS_DIR. '/css/setup' . $prefix . '.css', ['install'], SITESEO_VERSION);
		wp_register_script('siteseo-migrate-ajax', SITESEO_ASSETS_DIR . '/js/siteseo-migrate' . $prefix . '.js', ['jquery'], SITESEO_VERSION, true);
		wp_enqueue_media();
		wp_register_script('siteseo-media-uploader', SITESEO_ASSETS_DIR . '/js/siteseo-media-uploader' . $prefix . '.js', ['jquery'], SITESEO_VERSION, true);

		$siteseo_migrate = [
			'siteseo_aio_migrate' => [
				'siteseo_nonce' => wp_create_nonce('siteseo_aio_migrate_nonce'),
				'siteseo_aio_migration' => admin_url('admin-ajax.php'),
			],
			'siteseo_yoast_migrate' => [
				'siteseo_nonce' => wp_create_nonce('siteseo_yoast_migrate_nonce'),
				'siteseo_yoast_migration' => admin_url('admin-ajax.php'),
			],
			'siteseo_seo_framework_migrate'	=> [
				'siteseo_nonce' => wp_create_nonce('siteseo_seo_framework_migrate_nonce'),
				'siteseo_seo_framework_migration' => admin_url('admin-ajax.php'),
			],
			'siteseo_rk_migrate' => [
				'siteseo_nonce' => wp_create_nonce('siteseo_rk_migrate_nonce'),
				'siteseo_rk_migration' => admin_url('admin-ajax.php'),
			],
			'siteseo_squirrly_migrate' => [
				'siteseo_nonce' => wp_create_nonce('siteseo_squirrly_migrate_nonce'),
				'siteseo_squirrly_migration' => admin_url('admin-ajax.php'),
			],
			'siteseo_seo_ultimate_migrate' => [
				'siteseo_nonce' => wp_create_nonce('siteseo_seo_ultimate_migrate_nonce'),
				'siteseo_seo_ultimate_migration' => admin_url('admin-ajax.php'),
			],
			'siteseo_wp_meta_seo_migrate' => [
				'siteseo_nonce' => wp_create_nonce('siteseo_meta_seo_migrate_nonce'),
				'siteseo_wp_meta_seo_migration' => admin_url('admin-ajax.php'),
			],
			'siteseo_premium_seo_pack_migrate' => [
				'siteseo_nonce' => wp_create_nonce('siteseo_premium_seo_pack_migrate_nonce'),
				'siteseo_premium_seo_pack_migration' => admin_url('admin-ajax.php'),
			],
			'siteseo_wpseo_migrate' => [
				'siteseo_nonce' => wp_create_nonce('siteseo_wpseo_migrate_nonce'),
				'siteseo_wpseo_migration' => admin_url('admin-ajax.php'),
			],
			'siteseo_platinum_seo_migrate' => [
				'siteseo_nonce' => wp_create_nonce('siteseo_platinum_seo_migrate_nonce'),
				'siteseo_platinum_seo_migration' => admin_url('admin-ajax.php'),
			],
			'siteseo_smart_crawl_migrate' => [
				'siteseo_nonce' => wp_create_nonce('siteseo_smart_crawl_migrate_nonce'),
				'siteseo_smart_crawl_migration' => admin_url('admin-ajax.php'),
			],
			'siteseo_seopressor_migrate' => [
				'siteseo_nonce' => wp_create_nonce('siteseo_seopressor_migrate_nonce'),
				'siteseo_seopressor_migration' => admin_url('admin-ajax.php'),
			],
			'siteseo_slim_seo_migrate' => [
				'siteseo_nonce' => wp_create_nonce('siteseo_slim_seo_migrate_nonce'),
				'siteseo_slim_seo_migration' => admin_url('admin-ajax.php'),
			],
			'siteseo_metadata_csv' => [
				'siteseo_nonce' => wp_create_nonce('siteseo_export_csv_metadata_nonce'),
				'siteseo_metadata_export' => admin_url('admin-ajax.php'),
			],
			'i18n' => [
				'migration' => esc_html__('Migration completed!', 'siteseo'),
				'export' => esc_html__('Export completed!', 'siteseo'),
			],
		];
		wp_localize_script('siteseo-migrate-ajax', 'siteseoAjaxMigrate', $siteseo_migrate);
	}

	/**
	 * Show the setup wizard.
	 */
	public function setup_wizard() {
		if (empty($_GET['page']) || 'siteseo-setup' !== $_GET['page']) {
			return;
		}

		$seo_title = 'SiteSEO';
		if (function_exists('siteseo_get_toggle_white_label_option') && '1' == siteseo_get_toggle_white_label_option()) {
			$seo_title = method_exists(siteseo_pro_get_service('OptionPro'), 'getWhiteLabelListTitle') && siteseo_pro_get_service('OptionPro')->getWhiteLabelListTitle() ? siteseo_pro_get_service('OptionPro')->getWhiteLabelListTitle() : 'SiteSEO';
		}

		$default_steps = [
			'welcome' => [
				'breadcrumbs' => true,
				'name'	=> esc_html__('Welcome', 'siteseo'),
				'view'	=> [$this, 'siteseo_setup_welcome'],
				'handler' => [$this, 'siteseo_setup_import_settings_save'],
				'sub_steps' => [
					'welcome' => esc_html__('Welcome','siteseo'),
					'import_settings' => esc_html__('Import metadata','siteseo')
				],
				'parent' => 'welcome'
			],
			'import_settings' => [
				'name'	=> esc_html__('Import SEO metadata', 'siteseo'),
				'view'	=> [$this, 'siteseo_setup_import_settings'],
				'handler' => [$this, 'siteseo_setup_import_settings_save'],
				'sub_steps' => [
					'welcome' => esc_html__('Welcome','siteseo'),
					'import_settings' => esc_html__('Import metadata','siteseo')
				],
				'parent' => 'welcome'
			],
			'site'	 => [
				'breadcrumbs' => true,
				'name'	=> esc_html__('Your site', 'siteseo'),
				'view'	=> [$this, 'siteseo_setup_site'],
				'handler' => [$this, 'siteseo_setup_site_save'],
				'sub_steps' => [
					'site' => esc_html__('General','siteseo'),
					'social_accounts' => esc_html__('Your social accounts','siteseo')
				],
				'parent' => 'site'
			],
			'social_accounts'	 => [
				'name'	=> esc_html__('Your site', 'siteseo'),
				'view'	=> [$this, 'siteseo_setup_social_accounts'],
				'handler' => [$this, 'siteseo_setup_social_accounts_save'],
				'sub_steps' => [
					'site' => esc_html__('General','siteseo'),
					'social_accounts' => esc_html__('Your social accounts','siteseo')
				],
				'parent' => 'site'
			],
			'indexing_post_types'	=> [
				'breadcrumbs' => true,
				'name'	=> esc_html__('Indexing', 'siteseo'),
				'view'	=> [$this, 'siteseo_setup_indexing_post_types'],
				'handler' => [$this, 'siteseo_setup_indexing_post_types_save'],
				'sub_steps' => [
					'indexing_post_types' => esc_html__('Post Types','siteseo'),
					'indexing_archives' => esc_html__('Archives','siteseo'),
					'indexing_taxonomies' => esc_html__('Taxonomies','siteseo')
				],
				'parent' => 'indexing_post_types'
			],
			'indexing_archives'	=> [
				'name'	=> esc_html__('Indexing', 'siteseo'),
				'view'	=> [$this, 'siteseo_setup_indexing_archives'],
				'handler' => [$this, 'siteseo_setup_indexing_archives_save'],
				'sub_steps' => [
					'indexing_post_types' => esc_html__('Post Types','siteseo'),
					'indexing_archives' => esc_html__('Archives','siteseo'),
					'indexing_taxonomies' => esc_html__('Taxonomies','siteseo')
				],
				'parent' => 'indexing_post_types'
			],
			'indexing_taxonomies'	=> [
				'name'	=> esc_html__('Indexing', 'siteseo'),
				'view'	=> [$this, 'siteseo_setup_indexing_taxonomies'],
				'handler' => [$this, 'siteseo_setup_indexing_taxonomies_save'],
				'sub_steps' => [
					'indexing_post_types' => esc_html__('Post Types','siteseo'),
					'indexing_archives' => esc_html__('Archives','siteseo'),
					'indexing_taxonomies' => esc_html__('Taxonomies','siteseo')
				],
				'parent' => 'indexing_post_types'
			],
			'advanced'	=> [
				'breadcrumbs' => true,
				'name'	=> esc_html__('Advanced options', 'siteseo'),
				'view'	=> [$this, 'siteseo_setup_advanced'],
				'handler' => [$this, 'siteseo_setup_advanced_save'],
				'sub_steps' => [
					'advanced' => esc_html__('Advanced','siteseo'),
					'universal' => esc_html__('Universal SEO metabox','siteseo'),
				],
				'parent' => 'advanced'
			],
			'universal'	=> [
				'name'	=> esc_html__('Advanced options', 'siteseo'),
				'view'	=> [$this, 'siteseo_setup_universal'],
				'handler' => [$this, 'siteseo_setup_universal_save'],
				'sub_steps' => [
					'advanced' => esc_html__('Advanced','siteseo'),
					'universal' => esc_html__('Universal SEO metabox','siteseo'),
				],
				'parent' => 'advanced'
			],
		];
		if (function_exists('siteseo_get_toggle_white_label_option') && '1' === siteseo_get_toggle_white_label_option()) {
			//do nothing
		} elseif ((! is_multisite()) || ! is_plugin_active('siteseo-pro/siteseo-pro.php')){
			$sub_steps = [
				'pro' => esc_html__('PRO','siteseo'),
			];

			if (is_plugin_active('siteseo-pro/siteseo-pro.php')) {
				unset($sub_steps['pro']);
			}

			if ( ! is_plugin_active('siteseo-pro/siteseo-pro.php')) {
				$default_steps['pro'] = [
					'name'	=> sprintf(__('Extend %s', 'siteseo'), $seo_title),
					'view'	=> [$this, 'siteseo_setup_pro'],
					'handler' => '',
					'sub_steps' => $sub_steps,
					'parent' => 'pro'
				];
			}

			if (!is_plugin_active('siteseo-pro/siteseo-pro.php')) {
				$default_steps['pro']['breadcrumbs'] = true;
			}
		}

		$default_steps['ready']  = [
				'breadcrumbs' => true,
				'name'	=> esc_html__('Ready!', 'siteseo'),
				'view'	=> [$this, 'siteseo_setup_ready'],
				'handler' => '',
				'sub_steps' => [
					'ready' => esc_html__('Ready!', 'siteseo')
				]
		];

		$this->steps = apply_filters('siteseo_setup_wizard_steps', $default_steps);
		$this->step  = isset($_GET['step']) ? sanitize_key($_GET['step']) : current(array_keys($this->steps));
		$this->parent  = isset($_GET['parent']) ? sanitize_key($_GET['parent']) : current(array_keys($this->steps));

		if ( !empty($_POST['save_step']) && isset($this->steps[$this->step]['handler'])) {
			call_user_func($this->steps[$this->step]['handler'], $this);
		}

		ob_start();
		$this->setup_wizard_header();
		$this->setup_wizard_steps();
		$this->setup_wizard_content();
		$this->setup_wizard_footer();
		exit;
	}

	/**
	 * Get the URL for the next step's screen.
	 *
	 * @param string $step slug (default: current step)
	 *
	 * @return string URL for next step if a next step exists.
	 *				Admin URL if it's the last step.
	 *				Empty string on failure.
	 *
	 * @since 3.5.8
	 */
	public function get_next_step_link($step = '') {
		if ( ! $step) {
			$step = $this->step;
		}

		$keys = array_keys($this->steps);
		if (end($keys) === $step) {
			return admin_url();
		}

		$step_index = array_search($step, $keys, true);
		if (false === $step_index) {
			return '';
		}

		$parent = '';
		$all = $this->steps;
		if (isset($all[$step]['parent'])) {
			$key = $keys[$step_index + 1];
			if (isset($all[$key]['parent'])) {
				$parent = $all[$key]['parent'];
			}
		}

		return add_query_arg(
			[
				'step' => $keys[$step_index + 1],
				'parent' => $parent,
			],
			remove_query_arg( 'parent' )
		);
	}

	/**
	 * Setup Wizard Header.
	 */
	public function setup_wizard_header() {
		set_current_screen();

		$seo_title = 'SiteSEO';
		if (function_exists('siteseo_get_toggle_white_label_option') && '1' == siteseo_get_toggle_white_label_option()) {
			$seo_title = method_exists(siteseo_pro_get_service('OptionPro'), 'getWhiteLabelListTitle') && siteseo_pro_get_service('OptionPro')->getWhiteLabelListTitle() ? siteseo_pro_get_service('OptionPro')->getWhiteLabelListTitle() : 'SiteSEO';
		}
		?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
	<meta name="viewport" content="width=device-width" />
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title><?php printf(esc_html__('%s &rsaquo; Setup Wizard', 'siteseo'), esc_html($seo_title)); ?>
	</title>
	<?php do_action('admin_enqueue_scripts'); ?>
	<?php do_action('admin_print_styles'); ?>
	<?php wp_print_scripts('siteseo-migrate-ajax'); ?>
	<?php wp_print_scripts('siteseo-media-uploader'); ?>
	<?php do_action('admin_head'); ?>
</head>

<body
	class="siteseo-setup siteseo-option wp-core-ui">
	<?php
	}

	/**
	 * Setup Wizard Footer.
	 */
	public function setup_wizard_footer() {
		?>
	</div>
	<div class="siteseo-setup-footer">
		<?php if ('welcome' === $this->step) { ?>
		<a class="siteseo-setup-footer-links"
			href="<?php echo esc_url(admin_url()); ?>"><?php esc_html_e('Not right now', 'siteseo'); ?></a>
		<?php } elseif (
			'import_settings' === $this->step ||
			'social_accounts' === $this->step ||
			'indexing_post_types' === $this->step ||
			'indexing_archives' === $this->step ||
			'indexing_taxonomies' === $this->step ||
			'universal' === $this->step ||
			'site' === $this->step ||
			'indexing' === $this->step ||
			'advanced' === $this->step ||
			'pro' === $this->step
			) {
				$skip_link = esc_url($this->get_next_step_link());
		
				echo '<a class="siteseo-setup-footer-links" href="'.esc_url($skip_link).'">'.esc_html__('Skip this step', 'siteseo').'</a>';
			}
			do_action('siteseo_setup_footer');
			do_action( 'admin_footer', '' );
			do_action( 'admin_print_footer_scripts' );
		?>
	</div>
	</div>
</body>

</html>
<?php
	}

	/**
	 * Output the steps.
	 */
	public function setup_wizard_steps() {

		$output_steps = $this->steps;
		$parent = $this->parent; ?>
<div id="wpcontent" class="siteseo-option">
	<ol class="siteseo-setup-steps">
		<?php
		$i = 1;

		foreach ($output_steps as $step_key => $step) {
			if (!isset($step['breadcrumbs'])) {
				continue;
			}

			$is_completed = array_search($this->step, array_keys($this->steps), true) > array_search($step_key, array_keys($this->steps), true);

			if ($step_key === $this->step || $step_key === $this->parent) {
				?>
		<li class="active">
			<div class="icon" data-step="<?php echo esc_attr($i); ?>"></div>
			<span><?php echo esc_html($step['name']); ?></span>
			<div class="divider"></div>
		</li>
		<?php
			} elseif ($is_completed) {
				?>
		<li class="done">
			<div class="icon" data-step="<?php echo esc_attr($i); ?>"></div>
			<a
				href="<?php echo esc_url(add_query_arg(
					['step' => $step_key, 'parent' => $parent]
				)); ?>">
				<?php echo esc_html($step['name']); ?>
			</a>
			<div class="divider"></div>
		</li>
		<?php
			} else {
				?>
		<li>
			<div class="icon" data-step="<?php echo esc_attr($i); ?>"></div>
			<span><?php echo esc_html($step['name']); ?></span>
			<div class="divider"></div>
		</li>
		<?php
			}
			++$i;
		} ?>
	</ol>
	<?php
	}

	/**
	 * Output the sub steps.
	 */
	public function setup_wizard_sub_steps() {
		$output_steps	  = $this->steps;
		$current_step	  = $this->step;
		$parent			= $this->parent;
		?>
		<div id="siteseo-tabs" class="wrap">
			<div class="nav-tab-wrapper">
				<ol class="siteseo-setup-sub-steps">
					<?php
						if (!empty($output_steps[$current_step]['sub_steps'])) {
							foreach($output_steps[$current_step]['sub_steps'] as $key => $value) {
								$class = $key === $current_step ? 'nav-tab-active' : '';
								?>
								<a <?php echo 'class="nav-tab '.esc_attr($class).'"'; ?> href="<?php echo esc_url(admin_url('admin.php?page=siteseo-setup&step='.$key.'&parent='.$parent)); ?>">
									<?php echo esc_html($value); ?>
								</a>
							<?php }
						}
					?>
				</ol>
			</div>
	<?php
	}

	/**
	 * Output the content for the current step.
	 */
	public function setup_wizard_content() {
		if ( ! empty($this->steps[$this->step]['view'])) {
			call_user_func($this->steps[$this->step]['view'], $this);
		}
	}

	/**
	 * Init "Step 1.1: Welcome".
	 */
	public function siteseo_setup_welcome() {
		$seo_title = 'SiteSEO';
		if (function_exists('siteseo_get_toggle_white_label_option') && '1' == siteseo_get_toggle_white_label_option()) {
			$seo_title = method_exists(siteseo_pro_get_service('OptionPro'), 'getWhiteLabelListTitle') && siteseo_pro_get_service('OptionPro')->getWhiteLabelListTitle() ? siteseo_pro_get_service('OptionPro')->getWhiteLabelListTitle() : 'SiteSEO';
		}
		?>
	<div class="siteseo-setup-content">
		<h1><?php printf(esc_html__('Welcome to %s!', 'siteseo'), esc_html($seo_title)); ?><hr role="presentation"></h1>

		<?php $this->setup_wizard_sub_steps(); ?>

		<div class="siteseo-tab active">
			<form method="post">
				<?php wp_nonce_field('siteseo-setup'); ?>
				<h2><?php printf(esc_html__('Configure %s with the best settings for your site','siteseo'), esc_html($seo_title)); ?></h2>
				<p class="store-setup intro"><?php printf(esc_html__('The following wizard will help you configure %s and get you started quickly.', 'siteseo'), esc_html($seo_title)); ?>
				</p>

				<p class="siteseo-setup-actions step">
					<button type="submit" class="btnPrimary btn btnNext"
						value="<?php esc_attr_e('Next step', 'siteseo'); ?>"
						name="save_step">
						<?php esc_html_e('Next step', 'siteseo'); ?>
					</button>

					<?php wp_nonce_field('siteseo-setup'); ?>
				</p>
			</form>
		</div>
	</div>
<?php
	}

	/**
	 * Init "Step 1.2: Import SEO settings".
	 */
	public function siteseo_setup_import_settings() {
		$seo_title = 'SiteSEO';
		if (function_exists('siteseo_get_toggle_white_label_option') && '1' == siteseo_get_toggle_white_label_option()) {
			$seo_title = method_exists(siteseo_pro_get_service('OptionPro'), 'getWhiteLabelListTitle') && siteseo_pro_get_service('OptionPro')->getWhiteLabelListTitle() ? siteseo_pro_get_service('OptionPro')->getWhiteLabelListTitle() : 'SiteSEO';
		} ?>
		<div class="siteseo-setup-content">
			<h1><?php printf(esc_html__('Migrate your SEO metadata to %s!', 'siteseo'), esc_html($seo_title)); ?><hr role="presentation"></h1>

			<?php $this->setup_wizard_sub_steps(); ?>

			<div class="siteseo-tab active">
				<form method="post">
					<?php wp_nonce_field('siteseo-setup'); ?>

					<p class="store-setup intro"><?php esc_html_e('The first step is to import your previous post and term metadata from other plugins to keep your SEO.', 'siteseo'); ?>
					</p>

					<?php
						$plugins = [
							'yoast'			=> 'Yoast SEO',
							'aio'			  => 'All In One SEO',
							'seo-framework'	=> 'The SEO Framework',
							'rk'			   => 'Rank Math',
							'squirrly'		 => 'Squirrly SEO',
							'seo-ultimate'	 => 'SEO Ultimate',
							'wp-meta-seo'	  => 'WP Meta SEO',
							'premium-seo-pack' => 'Premium SEO Pack',
							'wpseo'			=> 'wpSEO',
							'platinum-seo'	 => 'Platinum SEO Pack',
							'smart-crawl'	  => 'SmartCrawl',
							'seopressor'	   => 'SeoPressor',
							'slim-seo'		 => 'Slim SEO',
						];

					echo '<p>
											<select id="select-wizard-import" name="select-wizard-import">
												<option value="none">' . esc_html__('Select an option', 'siteseo') . '</option>';

					foreach ($plugins as $plugin => $name) {
						echo '<option value="' . esc_attr($plugin) . '-migration-tool">' . esc_html($name) . '</option>';
					}
					echo '</select>
										</p>

									<p class="description">' . esc_html__('You don\'t have to enable the selected SEO plugin to run the import.', 'siteseo') . '</p>';

					foreach ($plugins as $plugin => $name) {
						echo wp_kses_post(siteseo_migration_tool($plugin, $name));
					} ?>


				<p class="store-setup"><?php esc_html_e('No data to migrate? Click "Next step" button!', 'siteseo'); ?></p>

				<p class="siteseo-setup-actions step">
					<button type="submit" class="btnPrimary btn btnNext"
						value="<?php esc_attr_e('Next step', 'siteseo'); ?>"
						name="save_step">
						<?php esc_html_e('Next step', 'siteseo'); ?>
					</button>

					<?php wp_nonce_field('siteseo-setup'); ?>
				</p>
				</form>
			</div>
		</div>
	<?php
	}

	/**
	 * Save step 1.2 settings.
	 */
	public function siteseo_setup_import_settings_save() {
		check_admin_referer('siteseo-setup');
		wp_safe_redirect(esc_url_raw($this->get_next_step_link()));
		exit;
	}

	/**
	 * Init "Step 2.0: Your site - General".
	 */
	public function siteseo_setup_site() {
		$docs = siteseo_get_docs_links();
		$siteseo_titles_option = get_option('siteseo_titles_option_name');
		$siteseo_social_option = get_option('siteseo_social_option_name');

		$current_user = wp_get_current_user();
		$current_user_email = isset($current_user->user_email) ? $current_user->user_email : null;

		$site_sep		= isset($siteseo_titles_option['titles_sep']) ? $siteseo_titles_option['titles_sep'] : null;
		$site_title	  = isset($siteseo_titles_option['titles_home_site_title']) ? $siteseo_titles_option['titles_home_site_title'] : null;
		$alt_site_title  = isset($siteseo_titles_option['titles_home_site_title_alt']) ? $siteseo_titles_option['titles_home_site_title_alt'] : null;
		$knowledge_type  = isset($siteseo_social_option['social_knowledge_type']) ? $siteseo_social_option['social_knowledge_type'] : null;
		$knowledge_name  = isset($siteseo_social_option['social_knowledge_name']) ? $siteseo_social_option['social_knowledge_name'] : null;
		$knowledge_img   = isset($siteseo_social_option['social_knowledge_img']) ? $siteseo_social_option['social_knowledge_img'] : null;
		$knowledge_email = isset($siteseo_social_option['siteseo_social_knowledge_email']) ? $siteseo_social_option['siteseo_social_knowledge_email'] : $current_user_email;
		$knowledge_nl	= isset($siteseo_social_option['siteseo_social_knowledge_nl']); ?>

		<div class="siteseo-setup-content">
			<h1><?php esc_html_e('Your site', 'siteseo'); ?><hr role="presentation"></h1>

			<?php $this->setup_wizard_sub_steps(); ?>

			<div class="siteseo-tab active">

				<form method="post">
					<h2><?php esc_html_e('Tell us more about your site','siteseo'); ?></h2>
					<p><?php esc_html_e('To build title tags and knowledge graph for Google, you need to fill out the fields below to configure the general settings.', 'siteseo'); ?>
					</p>

					<p>
						<label for="site_sep"><?php esc_html_e('Separator', 'siteseo'); ?></label>
						<input type="text" id="site_sep" class="location-input" name="site_sep"
							placeholder="<?php esc_html_e('eg: |', 'siteseo'); ?>"
							required value="<?php echo (!empty($site_sep) ? esc_attr($site_sep) : '%%sep%%'); ?>" />
					</p>

					<p class="description">
						<?php echo wp_kses_post(__('This separator will be used by the dynamic variable <strong>%%sep%%</strong> in your title and meta description templates.', 'siteseo')); ?>
					</p>

					<p>
						<label for="site_title"><?php esc_html_e('Home site title', 'siteseo'); ?></label>
						<input type="text" id="site_title" class="location-input" name="site_title"
							placeholder="<?php esc_html_e('eg: My super website', 'siteseo'); ?>"
							required value="<?php echo (!empty($site_title) ? esc_attr($site_title) : '%%sitetitle%%'); ?>" />
					</p>

					<p class="description">
						<?php echo wp_kses_post(__('The site title will be used by the dynamic variable <strong>%%sitetitle%%</strong> in your title and meta description templates.', 'siteseo')); ?>
					</p>

					<p>
						<label for="alt_site_title"><?php esc_html_e('Alternative site title', 'siteseo'); ?></label>
						<input type="text" id="alt_site_title" class="location-input" name="alt_site_title" placeholder="<?php esc_html_e('eg: My alternative site title', 'siteseo'); ?>" value="<?php echo esc_attr($alt_site_title); ?>" />
					</p>

					<p class="description"><?php printf(wp_kses_post(__('The alternate name of the website (for example, if there\'s a commonly recognized acronym or shorter name for your site), if applicable. Make sure the name meets the <a href="%s" target="_blank">content guidelines</a>.<span class="dashicons dashicons-external"></span>', 'siteseo')), esc_url($docs['titles']['alt_title'])); ?></p>

					<p>
						<label for="knowledge_type"><?php esc_html_e('Person or organization', 'siteseo'); ?></label>
						<?php
						echo '<select id="knowledge_type" name="knowledge_type" data-placeholder="' . esc_attr__('Choose a knowledge type', 'siteseo') . '"	class="location-input wc-enhanced-select dropdown">';
						echo ' <option ';
						if ('None' == $knowledge_type) {
							echo 'selected="selected"';
						}
						echo ' value="none">' . esc_html__('None (will disable this feature)', 'siteseo') . '</option>';
						echo ' <option ';
						if ('Person' == $knowledge_type) {
							echo 'selected="selected"';
						}
						echo ' value="Person">' . esc_html__('Person', 'siteseo') . '</option>';
						echo '<option ';
						if ('Organization' == $knowledge_type) {
							echo 'selected="selected"';
						}
						echo ' value="Organization">' . esc_html__('Organization', 'siteseo') . '</option>';
						echo '</select>'; ?>
					</p>

					<p class="description">
						<?php echo wp_kses_post(__('Choose between <strong>"Organization"</strong> (for companies, associations, organizations), or <strong>"Personal"</strong> for a personal site, to help Google better understand your type of website and generate a Knowledge Graph panel.', 'siteseo')); ?>
					</p>

					<p>
						<label for="knowledge_name"><?php esc_html_e('Your name/organization', 'siteseo'); ?></label>
						<input type="text" id="knowledge_name" class="location-input" name="knowledge_name"
							placeholder="<?php esc_html_e('eg: My Company Name', 'siteseo'); ?>"
							value="<?php echo esc_attr($knowledge_name); ?>" />
					</p>

					<p>
						<label for="knowledge_img_meta"><?php esc_html_e('Your photo/organization logo', 'siteseo'); ?></label>
						<input type="text" id="knowledge_img_meta" class="location-input" name="knowledge_img"
						placeholder="<?php esc_html_e('eg: https://www.example.com/logo.png', 'siteseo'); ?>"
						value="<?php echo esc_attr($knowledge_img); ?>" />

						<input id="knowledge_img_upload" class="btn btnSecondary" type="button" value="<?php esc_html_e('Upload an Image', 'siteseo'); ?>" />
					</p>

					<?php if (function_exists('siteseo_get_toggle_white_label_option') && '1' !== siteseo_get_toggle_white_label_option()) { ?>
						<p>
							<label for="knowledge_email"><?php esc_html_e('Your email', 'siteseo'); ?></label>
							<input type="text" id="knowledge_email" class="location-input" name="knowledge_email"
								placeholder="<?php esc_html_e('eg: enter', 'siteseo'); ?>"
								value="<?php echo esc_attr($knowledge_email); ?>" />
						</p>

						<!--<p>
							<label for="knowledge_nl">
								<input id="knowledge_nl" class="location-input" name="knowledge_nl" type="checkbox" <?php if ('1' == $knowledge_nl) {
								echo 'checked="yes"';
							} ?> value="1"/>
								<?php esc_html_e('Be alerted to changes in Google’s algorithm, get product updates, tutorials and ebooks to improve your conversion and traffic.'); ?>
							</label>
						</p>-->
					<?php } ?>

					<p class="siteseo-setup-actions step">
						<button type="submit" class="btnPrimary btn btnNext"
							value="<?php esc_attr_e('Save & Continue', 'siteseo'); ?>"
							name="save_step">
							<?php esc_html_e('Save & Continue', 'siteseo'); ?>
						</button>
						<?php wp_nonce_field('siteseo-setup'); ?>
					</p>
				</form>
			</div>
		</div>
	<?php
	}

	/**
	 * Save step 2.0 settings.
	 */
	public function siteseo_setup_site_save() {
		check_admin_referer('siteseo-setup');

		//Get options
		$siteseo_titles_option = get_option('siteseo_titles_option_name');
		$siteseo_social_option = get_option('siteseo_social_option_name');

		//Titles
		$siteseo_titles_option['titles_sep'] = isset($_POST['site_sep']) ? esc_attr(siteseo_opt_post('site_sep')) : '';
		$siteseo_titles_option['titles_home_site_title'] = isset($_POST['site_title']) ? sanitize_text_field(wp_unslash($_POST['site_title'])) : '';
		$siteseo_titles_option['titles_home_site_title_alt'] = isset($_POST['alt_site_title']) ? sanitize_text_field(wp_unslash($_POST['alt_site_title'])) : '';

		//Social
		$siteseo_social_option['social_knowledge_type']   = isset($_POST['knowledge_type']) ? esc_attr(siteseo_opt_post('knowledge_type')) : '';
		$siteseo_social_option['social_knowledge_name']   = isset($_POST['knowledge_name']) ? siteseo_opt_post('knowledge_name') : '';
		$siteseo_social_option['social_knowledge_img']	= isset($_POST['knowledge_img']) ? siteseo_opt_post('knowledge_img') : '';
		$siteseo_social_option['siteseo_social_knowledge_email']  = isset($_POST['knowledge_email']) ? siteseo_opt_post('knowledge_email') : '';
		$siteseo_social_option['siteseo_social_knowledge_nl']	 = isset($_POST['knowledge_nl']) ? esc_attr(siteseo_opt_post('knowledge_nl')) : null;

		//Save options
		update_option('siteseo_titles_option_name', $siteseo_titles_option, false);
		update_option('siteseo_social_option_name', $siteseo_social_option, false);

		//Send email to SG if we have user consent
		if (function_exists('siteseo_get_toggle_white_label_option') && '1' !== siteseo_get_toggle_white_label_option()) {
			if (isset($siteseo_social_option['siteseo_social_knowledge_email']) && $siteseo_social_option['siteseo_social_knowledge_nl'] === '1') {
				$endpoint_url = SITESEO_WEBSITE.'/subscribe/';
				$body = ['email' => $siteseo_social_option['siteseo_social_knowledge_email'], 'lang' => siteseo_get_locale()];

				$response = wp_remote_post( $endpoint_url, array(
						'method' => 'POST',
						'body' => $body,
						'timeout' => 5,
						'blocking' => true
					)
				);
			}
		}

		wp_safe_redirect(esc_url_raw($this->get_next_step_link()));
		exit;
	}

	/**
	 * Init "Step 2.1: Your site - Social accounts".
	 */
	public function siteseo_setup_social_accounts() {
		$siteseo_social_option = get_option('siteseo_social_option_name');

		$knowledge_fb	= isset($siteseo_social_option['social_accounts_facebook']) ? $siteseo_social_option['social_accounts_facebook'] : null;
		$knowledge_tw	= isset($siteseo_social_option['social_accounts_twitter']) ? $siteseo_social_option['social_accounts_twitter'] : null;
		$knowledge_pin   = isset($siteseo_social_option['social_accounts_pinterest']) ? $siteseo_social_option['social_accounts_pinterest'] : null;
		$knowledge_insta = isset($siteseo_social_option['social_accounts_instagram']) ? $siteseo_social_option['social_accounts_instagram'] : null;
		$knowledge_yt	= isset($siteseo_social_option['social_accounts_youtube']) ? $siteseo_social_option['social_accounts_youtube'] : null;
		$knowledge_li	= isset($siteseo_social_option['social_accounts_linkedin']) ? $siteseo_social_option['social_accounts_linkedin'] : null; ?>

		<div class="siteseo-setup-content">
			<h1><?php esc_html_e('Your site', 'siteseo'); ?><hr role="presentation"></h1>

			<?php $this->setup_wizard_sub_steps(); ?>

			<div class="siteseo-tab active">

				<form method="post">
					<h2><?php esc_html_e('Link your site to your social networks','siteseo'); ?></h2>

					<p><?php esc_html_e('Fill in your social accounts for search engines.', 'siteseo'); ?>
					</p>

					<p>
						<label for="knowledge_fb"><?php esc_html_e('Facebook page URL', 'siteseo'); ?></label>
						<input type="text" id="knowledge_fb" class="location-input" name="knowledge_fb"
							placeholder="<?php esc_html_e('eg: https://facebook.com/my-page-url', 'siteseo'); ?>"
							value="<?php echo esc_attr($knowledge_fb); ?>" />
					</p>

					<p>
						<label for="knowledge_tw"><?php esc_html_e('Twitter Username', 'siteseo'); ?></label>
						<input type="text" id="knowledge_tw" class="location-input" name="knowledge_tw"
							placeholder="<?php esc_html_e('eg: @my_twitter_account', 'siteseo'); ?>"
							value="<?php echo esc_attr($knowledge_tw); ?>" />
					</p>

					<p>
						<label for="knowledge_pin"><?php esc_html_e('Pinterest URL', 'siteseo'); ?></label>
						<input type="text" id="knowledge_pin" class="location-input" name="knowledge_pin"
							placeholder="<?php esc_html_e('eg: https://pinterest.com/my-page-url/', 'siteseo'); ?>"
							value="<?php echo esc_attr($knowledge_pin); ?>" />
					</p>

					<p>
						<label for="knowledge_insta"><?php esc_html_e('Instagram URL', 'siteseo'); ?></label>
						<input type="text" id="knowledge_insta" class="location-input" name="knowledge_insta"
							placeholder="<?php esc_html_e('eg: https://www.instagram.com/my-page-url/', 'siteseo'); ?>"
							value="<?php echo esc_attr($knowledge_insta); ?>" />
					</p>

					<p>
						<label for="knowledge_yt"><?php esc_html_e('YouTube URL', 'siteseo'); ?></label>
						<input type="text" id="knowledge_yt" class="location-input" name="knowledge_yt"
							placeholder="<?php esc_html_e('eg: https://www.youtube.com/my-channel-url', 'siteseo'); ?>"
							value="<?php echo esc_attr($knowledge_yt); ?>" />
					</p>

					<p>
						<label for="knowledge_li"><?php esc_html_e('LinkedIn URL', 'siteseo'); ?></label>
						<input type="text" id="knowledge_li" class="location-input" name="knowledge_li"
							placeholder="<?php esc_html_e('eg: http://linkedin.com/company/my-company-url/', 'siteseo'); ?>"
							value="<?php echo esc_attr($knowledge_li); ?>" />
					</p>

					<p class="siteseo-setup-actions step">
						<button type="submit" class="btnPrimary btn btnNext"
							value="<?php esc_attr_e('Save & Continue', 'siteseo'); ?>"
							name="save_step">
							<?php esc_html_e('Save & Continue', 'siteseo'); ?>
						</button>
						<?php wp_nonce_field('siteseo-setup'); ?>
					</p>
				</form>
			</div>
		</div>
	<?php
	}

	/**
	 * Save step 2.1 settings.
	 */
	public function siteseo_setup_social_accounts_save() {
		check_admin_referer('siteseo-setup');

		//Get options
		$siteseo_social_option = get_option('siteseo_social_option_name');

		//Social accounts
		$siteseo_social_option['social_accounts_facebook']   = isset($_POST['knowledge_fb']) ? sanitize_text_field(wp_unslash($_POST['knowledge_fb'])) : '';
		$siteseo_social_option['social_accounts_twitter']	= isset($_POST['knowledge_tw']) ? sanitize_text_field(wp_unslash($_POST['knowledge_tw'])) : '';
		$siteseo_social_option['social_accounts_pinterest']  = isset($_POST['knowledge_pin']) ? sanitize_text_field(wp_unslash($_POST['knowledge_pin'])) : '';
		$siteseo_social_option['social_accounts_instagram']  = isset($_POST['knowledge_insta']) ? sanitize_text_field(wp_unslash($_POST['knowledge_insta'])) : '';
		$siteseo_social_option['social_accounts_youtube']	= isset($_POST['knowledge_yt']) ? sanitize_text_field(wp_unslash($_POST['knowledge_yt'])) : '';
		$siteseo_social_option['social_accounts_linkedin']   = isset($_POST['knowledge_li']) ? sanitize_text_field(wp_unslash($_POST['knowledge_li'])) : '';

		//Save options
		update_option('siteseo_social_option_name', $siteseo_social_option, false);

		wp_safe_redirect(esc_url_raw($this->get_next_step_link()));
		exit;
	}

	/**
	 *	Init "Step 3.0: Indexing Post Types Step".
	 */
	public function siteseo_setup_indexing_post_types() {
		$siteseo_titles_option = get_option('siteseo_titles_option_name'); ?>

		<div class="siteseo-setup-content">
			<h1><?php esc_html_e('Indexing', 'siteseo'); ?><hr role="presentation"></h1>

			<?php $this->setup_wizard_sub_steps(); ?>

			<div class="siteseo-tab active">

				<form method="post" class="siteseo-wizard-indexing-form">
					<?php
					$postTypes = siteseo_get_service('WordPressData')->getPostTypes();
					if ( ! empty($postTypes)) { ?>
					<h2>
						<?php esc_html_e('For which single post types, should indexing be disabled?', 'siteseo'); ?>
					</h2>

					<p><?php echo wp_kses_post(__('Custom post types are a content type in WordPress. By default, <strong>Post</strong> and <strong>Page</strong> are the <strong>default post types</strong>.','siteseo')); ?></p>
					<p><?php echo wp_kses_post(__('You can create your own type of content like "product" or "business": these are <strong>custom post types</strong>.','siteseo')); ?></p>

					<ul>
						<?php
										//Post Types
										foreach ($postTypes as $siteseo_cpt_key => $siteseo_cpt_value) {
											$titles_single_titles = isset($siteseo_titles_option['titles_single_titles'][$siteseo_cpt_key]['noindex']); ?>

						<h3><?php echo esc_html($siteseo_cpt_value->labels->name); ?>
							<em><small>[<?php echo esc_html($siteseo_cpt_value->name); ?>]</small></em>
						</h3>

						<li class="siteseo-wizard-service-item checkbox">
							<label
								for="siteseo_titles_single_cpt_noindex[<?php echo esc_attr($siteseo_cpt_key); ?>]">
								<input
									id="siteseo_titles_single_cpt_noindex[<?php echo esc_attr($siteseo_cpt_key); ?>]"
									name="siteseo_titles_option_name[titles_single_titles][<?php echo esc_attr($siteseo_cpt_key); ?>][noindex]"
									type="checkbox" <?php if ('1' == $titles_single_titles) {
												echo 'checked="yes"';
											} ?>
								value="1"/>
								<?php echo wp_kses_post(__('Do not display this single post type in search engine results <strong>(noindex)</strong>', 'siteseo')); ?>
							</label>
						</li>
						<?php
										}
									?>
					</ul>
					<?php } ?>

					<p class="siteseo-setup-actions step">
						<button type="submit" class="btnPrimary btn btnNext"
							value="<?php esc_attr_e('Save & Continue', 'siteseo'); ?>"
							name="save_step">
							<?php esc_html_e('Save & Continue', 'siteseo'); ?>
						</button>

						<?php wp_nonce_field('siteseo-setup'); ?>
					</p>
				</form>
			</div>
		</div>
	<?php
	}

	/**
	 * Save Step 3.0 Post Types settings.
	 */
	public function siteseo_setup_indexing_post_types_save() {
		check_admin_referer('siteseo-setup');

		//Get options
		$siteseo_titles_option = get_option('siteseo_titles_option_name');
		$postTypes = siteseo_get_service('WordPressData')->getPostTypes();
		//Post Types noindex
		foreach ($postTypes as $siteseo_cpt_key => $siteseo_cpt_value) {
			if (isset($_POST['siteseo_titles_option_name']['titles_single_titles'][$siteseo_cpt_key]['noindex'])) {
				$noindex = sanitize_text_field(wp_unslash($_POST['siteseo_titles_option_name']['titles_single_titles'][$siteseo_cpt_key]['noindex']));
			} else {
				$noindex = null;
			}
			$siteseo_titles_option['titles_single_titles'][$siteseo_cpt_key]['noindex'] = $noindex;
		}

		//Save options
		update_option('siteseo_titles_option_name', $siteseo_titles_option);

		wp_safe_redirect(esc_url_raw($this->get_next_step_link()));
		exit;
	}

	/**
	 *	Init "Step 3.1: Indexing Archives Step".
	 */
	public function siteseo_setup_indexing_archives() {
		$siteseo_titles_option = get_option('siteseo_titles_option_name'); ?>

		<div class="siteseo-setup-content">
			<h1><?php esc_html_e('Indexing', 'siteseo'); ?><hr role="presentation"></h1>

			<?php $this->setup_wizard_sub_steps(); ?>

			<div class="siteseo-tab active">

				<form method="post" class="siteseo-wizard-indexing-form">
					<?php
					$postTypes = siteseo_get_service('WordPressData')->getPostTypes();
					if ( ! empty($postTypes)) {
						$cpt = $postTypes;
						unset($cpt['post']);
						unset($cpt['page']);
						?>

						<?php
							if (!empty($cpt)) { ?>
							<h2>
								<?php esc_html_e('For which post type archives, should indexing be disabled?', 'siteseo'); ?>
							</h2>

							<p><?php echo wp_kses_post(__('<strong>Archive pages</strong> are automatically generated by WordPress. They group specific content such as your latest articles, a product category or your content by author or date.', 'siteseo')); ?></p>
							<p><?php echo wp_kses_post(__('Below the list of your <strong>post type archives</strong>:', 'siteseo')); ?></p>

							<ul>
							<?php
								foreach ($cpt as $siteseo_cpt_key => $siteseo_cpt_value) {
										$titles_archive_titles = isset($siteseo_titles_option['titles_archive_titles'][$siteseo_cpt_key]['noindex']); ?>
										<h3><?php echo esc_html($siteseo_cpt_value->labels->name); ?>
											<em><small>[<?php echo esc_html($siteseo_cpt_value->name); ?>]</small></em>
										</h3>

										<li class="siteseo-wizard-service-item checkbox">
											<label
												for="siteseo_titles_archive_cpt_noindex[<?php echo esc_attr($siteseo_cpt_key); ?>]">
												<input
													id="siteseo_titles_archive_cpt_noindex[<?php echo esc_attr($siteseo_cpt_key); ?>]"
													name="siteseo_titles_option_name[titles_archive_titles][<?php echo esc_attr($siteseo_cpt_key); ?>][noindex]"
													type="checkbox" <?php if ('1' == $titles_archive_titles) {
																	echo 'checked="yes"';
																} ?>
												value="1"/>
												<?php echo wp_kses_post(__('Do not display this post type archive in search engine results <strong>(noindex)</strong>', 'siteseo')); ?>
											</label>
										</li>
									<?php
									}
								}
							if (!empty($cpt)) { ?>
							</ul>
						<?php }

						if (empty($cpt)) { ?>
						<p><?php esc_html_e('You don‘t have any post type archives, you can continue to the next step.','siteseo'); ?></p>
						<?php }
					} ?>

					<p class="siteseo-setup-actions step">
						<button type="submit" class="btnPrimary btn btnNext"
							value="<?php esc_attr_e('Save & Continue', 'siteseo'); ?>"
							name="save_step">
							<?php esc_html_e('Save & Continue', 'siteseo'); ?>
						</button>

						<?php wp_nonce_field('siteseo-setup'); ?>
					</p>
				</form>
			</div>
		</div>
	<?php
	}

	/**
	 * Save Step 3.1 Archives settings.
	 */
	public function siteseo_setup_indexing_archives_save() {
		check_admin_referer('siteseo-setup');

		//Get options
		$siteseo_titles_option = get_option('siteseo_titles_option_name', []);
		$postTypes = siteseo_get_service('WordPressData')->getPostTypes();

		//Post Type archives noindex
		foreach ($postTypes as $siteseo_cpt_key => $siteseo_cpt_value) {
			if (isset($_POST['siteseo_titles_option_name']['titles_archive_titles'][$siteseo_cpt_key]['noindex'])) {
				$noindex = sanitize_text_field(wp_unslash($_POST['siteseo_titles_option_name']['titles_archive_titles'][$siteseo_cpt_key]['noindex']));
			} else {
				$noindex = null;
			}
			$siteseo_titles_option['titles_archive_titles'][$siteseo_cpt_key]['noindex'] = $noindex;
		}

		//Save options
		update_option('siteseo_titles_option_name', $siteseo_titles_option);

		wp_safe_redirect(esc_url_raw($this->get_next_step_link()));
		exit;
	}

	/**
	 *	Init "Step 3.2: Indexing Taxonomies Step".
	 */
	public function siteseo_setup_indexing_taxonomies() {
		$siteseo_titles_option = get_option('siteseo_titles_option_name', []); ?>

		<div class="siteseo-setup-content">
			<h1><?php esc_html_e('Indexing', 'siteseo'); ?><hr role="presentation"></h1>

			<?php $this->setup_wizard_sub_steps(); ?>

			<div class="siteseo-tab active">

				<form method="post" class="siteseo-wizard-indexing-form">
					<?php

					$taxonomies = siteseo_get_service('WordPressData')->getTaxonomies();

					if ( ! empty($taxonomies)) { ?>
					<h2>
						<?php esc_html_e('For which taxonomy archives, should indexing be disabled?', 'siteseo'); ?>
					</h2>

					<p><?php echo wp_kses_post(__('<strong>Taxonomies</strong> are the method of classifying content and data in WordPress. When you use a taxonomy you’re grouping similar things together. The taxonomy refers to the sum of those groups.','siteseo')); ?></p>
					<p><?php echo wp_kses_post(__('<strong>Categories</strong> and <strong>Tags</strong> are the default taxonomies. You can add your own taxonomies like "product categories": these are called <strong>custom taxonomies</strong>.','siteseo')); ?></p>

					<ul>
						<?php
						//Archives
						foreach ($taxonomies as $siteseo_tax_key => $siteseo_tax_value) {
							$titles_tax_titles = isset($siteseo_titles_option['titles_tax_titles'][$siteseo_tax_key]['noindex']); ?>
						<h3><?php echo esc_html($siteseo_tax_value->labels->name); ?>
							<em><small>[<?php echo esc_html($siteseo_tax_value->name); ?>]</small></em>
						</h3>

						<li class="siteseo-wizard-service-item checkbox">
							<label
								for="siteseo_titles_tax_noindex[<?php echo esc_attr($siteseo_tax_key); ?>]">
								<input
									id="siteseo_titles_tax_noindex[<?php echo esc_attr($siteseo_tax_key); ?>]"
									name="siteseo_titles_option_name[titles_tax_titles][<?php echo esc_attr($siteseo_tax_key); ?>][noindex]"
									type="checkbox" <?php if ('1' == $titles_tax_titles) {
								echo 'checked="yes"';
							} ?>
								value="1"/>
								<?php echo wp_kses_post(__('Do not display this taxonomy archive in search engine results <strong>(noindex)</strong>', 'siteseo')); ?>
								<?php if ($siteseo_tax_key =='post_tag') { ?>
									<div class="siteseo-notice is-warning is-inline">
										<p>
											<?php echo wp_kses_post(__('We do not recommend indexing <strong>tags</strong> which are, in the vast majority of cases, a source of duplicate content.', 'siteseo')); ?>
										</p>
									</div>
								<?php } ?>
							</label>
						</li>
						<?php
						}
						?>
					</ul>

					<?php } ?>

					<p class="siteseo-setup-actions step">
						<button type="submit" class="btnPrimary btn btnNext"
							value="<?php esc_attr_e('Save & Continue', 'siteseo'); ?>"
							name="save_step">
							<?php esc_html_e('Save & Continue', 'siteseo'); ?>
						</button>

						<?php wp_nonce_field('siteseo-setup'); ?>
					</p>
				</form>
			</div>
		</div>
	<?php
	}

	/**
	 * Save Step 3.2 taxonomies settings.
	 */
	public function siteseo_setup_indexing_taxonomies_save() {
		check_admin_referer('siteseo-setup');

		//Get options
		$siteseo_titles_option = get_option('siteseo_titles_option_name');

		//Archives noindex
		foreach (siteseo_get_service('WordPressData')->getTaxonomies() as $siteseo_tax_key => $siteseo_tax_value) {
			if (isset($_POST['siteseo_titles_option_name']['titles_tax_titles'][$siteseo_tax_key]['noindex'])) {
				$noindex = sanitize_text_field(wp_unslash($_POST['siteseo_titles_option_name']['titles_tax_titles'][$siteseo_tax_key]['noindex']));
			} else {
				$noindex = null;
			}
			$siteseo_titles_option['titles_tax_titlestitles_tax_titles'][$siteseo_tax_key]['noindex'] = $noindex;
		}

		//Save options
		update_option('siteseo_titles_option_name', $siteseo_titles_option);

		wp_safe_redirect(esc_url_raw($this->get_next_step_link()));
		exit;
	}

	/**
	 *	Init "Step 4: Advanced Step".
	 */
	public function siteseo_setup_advanced() {
		$siteseo_titles_option			= get_option('siteseo_titles_option_name');
		$author_noindex				= isset($siteseo_titles_option['titles_archives_author_noindex']);
		$siteseo_advanced_option		= get_option('siteseo_advanced_option_name');
		$attachments_file			= isset($siteseo_advanced_option['advanced_attachments_file']);
		$category_url				= isset($siteseo_advanced_option['advanced_category_url']);
		$product_category_url			= isset($siteseo_advanced_option['advanced_product_cat_url']); ?>

		<div class="siteseo-setup-content">

			<h1><?php esc_html_e('Advanced options', 'siteseo'); ?><hr role="presentation"></h1>

			<?php $this->setup_wizard_sub_steps(); ?>

			<div class="siteseo-tab active">
				<h2><?php esc_html_e('Almost done!','siteseo'); ?></h2>

				<p><?php esc_html_e('Final step before being ready to rank on search engines.', 'siteseo'); ?></p>

				<form method="post">
					<ul>
						<!-- Noindex on author archives -->
						<li class="siteseo-wizard-service-item checkbox">
							<label for="author_noindex">
								<input id="author_noindex" class="location-input" name="author_noindex" type="checkbox" <?php if ('1' == $author_noindex) {
							echo 'checked="yes"';
						} ?> value="1"/>
								<?php echo wp_kses_post(__('Do not display author archives in search engine results <strong>(noindex)</strong>', 'siteseo')); ?>
							</label>
						</li>
						<li class="description">
							<?php esc_html_e('You only have one author on your site? Check this option to avoid duplicate content.', 'siteseo'); ?>
						</li>

						<!-- Redirect attachment pages to URL -->
						<li class="siteseo-wizard-service-item checkbox">
							<label for="attachments_file">
								<input id="attachments_file" class="location-input" name="attachments_file" type="checkbox" <?php if ('1' == $attachments_file) {
							echo 'checked="yes"';
						} ?> value="1"/>
								<?php esc_html_e('Redirect attachment pages to their file URL (https://www.example.com/my-image-file.jpg)', 'siteseo'); ?>
							</label>
						</li>
						<li class="description">
							<?php esc_html_e('By default, SiteSEO redirects your Attachment pages to the parent post. Optimize this by redirecting the user directly to the URL of the media file.', 'siteseo'); ?>
						</li>

						<!-- Remove /category/ in URLs -->
						<li class="siteseo-wizard-service-item checkbox">
							<label for="category_url">
								<input id="category_url" name="category_url" type="checkbox" class="location-input" <?php if ('1' == $category_url) {
							echo 'checked="yes"';
						} ?> value="1"/>
								<?php
									$category_base = '/category/';
						if (get_option('category_base')) {
							$category_base = '/' . get_option('category_base');
						}

						printf(wp_kses_post(__('Remove <strong>%s</strong> in your permalinks', 'siteseo')), esc_html($category_base)); ?>
							</label>
						</li>
						<li class="description">
							<?php printf(esc_html__('Shorten your URLs by removing %s and improve your SEO.', 'siteseo'), esc_html($category_base)); ?>
						</li>

						<?php if (is_plugin_active('woocommerce/woocommerce.php')) { ?>
							<!-- Remove /product-category/ in URLs -->
							<li class="siteseo-wizard-service-item checkbox">
								<label for="product_category_url">
									<input id="product_category_url" name="product_category_url" type="checkbox" class="location-input"
										<?php if ('1' == $product_category_url) {
								echo 'checked="yes"';
							} ?> value="1"/>
									<?php
										$category_base = get_option('woocommerce_permalinks');
							$category_base			 = $category_base['category_base'];

							if ('' != $category_base) {
								$category_base = '/' . $category_base . '/';
							} else {
								$category_base = '/product-category/';
							}

							printf(wp_kses_post(__('Remove <strong>%s</strong> in your permalinks', 'siteseo')), esc_html($category_base)); ?>
								</label>
							</li>
							<li class="description">
								<?php printf(esc_html__('Shorten your URLs by removing %s and improve your SEO.', 'siteseo'), esc_html($category_base)); ?>
							</li>
						<?php } ?>
					</ul>

					<p class="siteseo-setup-actions step">
						<button type="submit" class="btn btnPrimary btnNext"
							value="<?php esc_attr_e('Save & Continue', 'siteseo'); ?>"
							name="save_step">
							<?php esc_html_e('Save & Continue', 'siteseo'); ?>
						</button>

						<?php wp_nonce_field('siteseo-setup'); ?>
					</p>
				</form>
			</div>
		</div>
	<?php
	}

	/**
	 * Save step 4.1 settings.
	 */
	public function siteseo_setup_advanced_save() {
		check_admin_referer('siteseo-setup');

		//Get options
		$siteseo_titles_option   = get_option('siteseo_titles_option_name');
		$siteseo_advanced_option = get_option('siteseo_advanced_option_name');

		//Author indexing
		$siteseo_titles_option['titles_archives_author_noindex'] = isset($_POST['author_noindex']) ? siteseo_opt_post('author_noindex') : null;

		//Advanced
		$siteseo_advanced_option['advanced_attachments_file'] = isset($_POST['attachments_file']) ? siteseo_opt_post('attachments_file') : null;
		$siteseo_advanced_option['advanced_category_url'] = isset($_POST['category_url']) ? siteseo_opt_post('category_url') : null;

		if (is_plugin_active('woocommerce/woocommerce.php')) {
			$siteseo_advanced_option['advanced_product_cat_url'] = isset($_POST['product_category_url']) ? siteseo_opt_post('product_category_url') : null;
		}

		//Save options
		update_option('siteseo_titles_option_name', $siteseo_titles_option, false);
		update_option('siteseo_advanced_option_name', $siteseo_advanced_option, false);

		wp_safe_redirect(esc_url_raw($this->get_next_step_link()));

		exit;
	}

	/**
	 *	Init "Step 4.2: Advanced Step".
	 */
	public function siteseo_setup_universal() {
		$siteseo_advanced_option		 = get_option('siteseo_advanced_option_name');
		$universal_seo_metabox			= isset($siteseo_advanced_option['appearance_universal_metabox_disable']) ? esc_attr($siteseo_advanced_option['appearance_universal_metabox_disable']) : null;
		?>

		<div class="siteseo-setup-content">

			<h1><?php esc_html_e('Advanced options', 'siteseo'); ?><hr role="presentation"></h1>

			<?php $this->setup_wizard_sub_steps(); ?>

			<div class="siteseo-tab active">

				<form method="post">
					<h2>
						<?php esc_html_e('Improve your workflow with the Universal SEO metabox', 'siteseo'); ?>
					</h2>

					<p><?php esc_html_e('Edit your SEO metadata directly from your page or theme builder.', 'siteseo'); ?></p>
					<ul>
						<!-- Universal SEO metabox overview -->
						<?php if ((function_exists('siteseo_get_toggle_white_label_option') && '1' !== siteseo_get_toggle_white_label_option())) { ?>
							<li class="description">
								<?php echo wp_oembed_get('https://www.youtube.com/@SiteSEOPlugin'); //phpcs:ignore?>
							</li>
						<?php } ?>

						<!-- Universal SEO metabox for page builers -->
						<li class="siteseo-wizard-service-item checkbox">
							<label for="universal_seo_metabox">
								<input id="universal_seo_metabox" name="universal_seo_metabox" type="checkbox" class="location-input" <?php if ('1' !== $universal_seo_metabox) {
							echo 'checked="yes"';
						} ?> value="1"/>
								<?php esc_html_e('Yes, please enable the universal SEO metabox!', 'siteseo'); ?>
							</label>
						</li>
						<li class="description">
							<?php esc_html_e('You can change this setting at anytime from SEO, Advanced settings page, Appearance tab.', 'siteseo'); ?>
						</li>
					</ul>

					<p class="siteseo-setup-actions step">
						<button type="submit" class="btn btnPrimary btnNext"
							value="<?php esc_attr_e('Save & Continue', 'siteseo'); ?>"
							name="save_step">
							<?php esc_html_e('Save & Continue', 'siteseo'); ?>
						</button>

						<?php wp_nonce_field('siteseo-setup'); ?>
					</p>
				</form>
			</div>
		</div>
	<?php
	}

	/**
	 * Save step 4.2 settings.
	 */
	public function siteseo_setup_universal_save() {
		check_admin_referer('siteseo-setup');

		//Get options
		$siteseo_advanced_option = get_option('siteseo_advanced_option_name');

		//Advanced
		$siteseo_advanced_option['appearance_universal_metabox_disable'] = isset($_POST['universal_seo_metabox']) ? '' : '1';
		$siteseo_advanced_option['appearance_universal_metabox'] = isset($_POST['universal_seo_metabox']) ? '1' : '';

		//Save options
		update_option('siteseo_advanced_option_name', $siteseo_advanced_option, false);

		wp_safe_redirect(esc_url_raw($this->get_next_step_link()));

		exit;
	}

	/**
	 *	Init "Step 5.0: PRO Step".
	 */
	public function siteseo_setup_pro() {
		$docs = siteseo_get_docs_links(); ?>

		<div class="siteseo-setup-content">

			<h1 class="siteseo-setup-actions step">
				<?php esc_html_e('SiteSEO Pro','siteseo'); ?><hr role="presentation">
			</h1>

			<?php $this->setup_wizard_sub_steps(); ?>

			<div class="siteseo-tab active">
				<h2><?php esc_html_e('Premium SEO features to increase your rankings', 'siteseo'); ?></h2>

				<p class="siteseo-setup-actions step">
					<span class="dashicons dashicons-minus"></span><?php echo wp_kses_post(__('Improve your business\'s presence in <strong>local search results</strong>.', 'siteseo')); ?>
				</p>
				<p class="siteseo-setup-actions step">
					<span class="dashicons dashicons-minus"></span><?php echo wp_kses_post(__('Optimize your SEO from your favorite e-commerce plugin: <strong>WooCommerce or Easy Digital Downloads</strong>.', 'siteseo')); ?>
				</p>
				<p class="siteseo-setup-actions step">
					<span class="dashicons dashicons-minus"></span><?php echo wp_kses_post(__('Add an infinity of <strong>Google structured data (schema)</strong> to your content to improve its visibility in search results.', 'siteseo')); ?>
				</p>
				<p class="siteseo-setup-actions step">
					<span class="dashicons dashicons-minus"></span><?php echo wp_kses_post(__('Add your custom <strong>breadcrumbs</strong>.', 'siteseo')); ?>
				</p>
				<p class="siteseo-setup-actions step">
					<span class="dashicons dashicons-minus"></span><?php echo wp_kses_post(__('Configure your <strong>robots.txt and .htaccess files</strong>.', 'siteseo')); ?>
				</p>
				<p class="siteseo-setup-actions step">
					<span class="dashicons dashicons-minus"></span><?php echo wp_kses_post(__('Observe the evolution of your site via <strong>Google Analytics stats</strong> directly from your WordPress Dashboard.', 'siteseo')); ?>
				</p>

				<p class="siteseo-setup-actions step">
					<span class="dashicons dashicons-minus"></span><?php esc_html_e('And so many other features to increase your rankings, sales and productivity.', 'siteseo'); ?>
				</p>

				<p class="siteseo-setup-actions step">
					<a class="btn btnPrimary"
						href="<?php echo esc_url($docs['addons']['pro']); ?>"
						target="_blank">
						<?php esc_html_e('Get SiteSEO PRO', 'siteseo'); ?>
					</a>
				</p>
			</div>
		</div>
		<?php
	}

	/**
	 * Final step.
	 */
	public function siteseo_setup_ready() {
		//Remove SiteSEO notice
		$siteseo_notices				  = get_option('siteseo_notices');
		$siteseo_notices['notice-wizard'] = '1';
		update_option('siteseo_notices', $siteseo_notices, false);

		$docs = siteseo_get_docs_links();

		//Flush permalinks
		flush_rewrite_rules(false); ?>

		<div class="siteseo-setup-content">

			<h1><?php esc_html_e('Your site is now ready for search engines!', 'siteseo'); ?><hr role="presentation"></h1>

			<?php $this->setup_wizard_sub_steps(); ?>

			<div class="siteseo-tab active">

				<ul class="siteseo-wizard-next-steps">
					<?php do_action('siteseo_wizard_setup_ready'); ?>

					<li class="siteseo-wizard-next-step-item">
						<div class="siteseo-wizard-next-step-description">
							<p class="next-step-heading"><?php esc_html_e('Next step', 'siteseo'); ?>
							</p>
							<h3 class="next-step-description"><?php esc_html_e('Create your XML sitemaps', 'siteseo'); ?>
							</h3>
							<p class="next-step-extra-info"><?php esc_html_e("Build custom XML sitemaps to improve Google's crawling of your site.", 'siteseo'); ?>
							</p>
						</div>
						<div class="siteseo-wizard-next-step-action">
							<p class="siteseo-setup-actions step">
								<a class="btn btnSecondary"
									href="<?php echo esc_url(admin_url('admin.php?page=siteseo-xml-sitemap')); ?>">
									<?php esc_html_e('Configure your XML sitemaps', 'siteseo'); ?>
								</a>
							</p>
						</div>
					</li>

					<?php if (!function_exists('siteseo_get_toggle_white_label_option') || '1' !== siteseo_get_toggle_white_label_option()) {
						$current_user = wp_get_current_user();
						$user_email = $current_user->user_email ? esc_html( $current_user->user_email ) : '';
						?>
						<li class="siteseo-wizard-next-step-item">
							<div class="siteseo-wizard-next-step-description">
								<p class="next-step-heading"><?php esc_html_e('Newsletter', 'siteseo'); ?>
								</p>
								<h3 class="next-step-description"><?php esc_html_e('SEO news in your inbox. Free.', 'siteseo'); ?>
								</h3>
								<ul class="next-step-extra-info">
									<li><span class="dashicons dashicons-minus"></span><?php esc_html_e('Be alerted to changes in Google’s algorithm', 'siteseo'); ?></li>
									<li><span class="dashicons dashicons-minus"></span><?php esc_html_e('The latest innovations of our products', 'siteseo'); ?></li>
									<li><span class="dashicons dashicons-minus"></span><?php esc_html_e('Improve your conversions and traffic with our new blog posts', 'siteseo'); ?></li>
								</ul>
							</div>
							<div class="siteseo-wizard-next-step-action">
								<p class="siteseo-setup-actions step">
									<a class="btn btnSecondary" target="_blank"
										href="<?php echo esc_url($docs['subscribe'] .'&email='. $user_email); ?>">
										<?php esc_html_e('Subscribe', 'siteseo'); ?>
									</a>
								</p>
							</div>
						</li>
					<?php } ?>

					<li class="siteseo-wizard-additional-steps">
						<div class="siteseo-wizard-next-step-description">
							<p class="next-step-heading"><?php esc_html_e('You can also:', 'siteseo'); ?>
							</p>
						</div>
						<div class="siteseo-wizard-next-step-action step">
							<p class="siteseo-setup-actions step">
								<a class="btn btnSecondary"
									href="<?php echo esc_url(admin_url()); ?>">
									<?php esc_html_e('Visit Dashboard', 'siteseo'); ?>
								</a>
								<a class="btn btnSecondary"
									href="<?php echo esc_url(admin_url('admin.php?page=siteseo')); ?>">
									<?php esc_html_e('Review Settings', 'siteseo'); ?>
								</a>
								<?php if (!function_exists('siteseo_get_toggle_white_label_option') || '1' !== siteseo_get_toggle_white_label_option()) { ?>
									<a class="btn btnSecondary"
										href="<?php echo esc_url($docs['guides']); ?>"
										target="_blank">
										<?php esc_html_e('Knowledge base', 'siteseo'); ?>
									</a>
								<?php } ?>
							</p>
						</div>
					</li>
				</ul>
			</div>
		</div>
	</div>
<?php
	}
}

new SITESEO_Admin_Setup_Wizard();
