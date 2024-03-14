<?php

namespace MABEL_BHI_LITE\Core
{
	class Admin
	{

		public $loader;

		public $options_manager;

		private $script_dependencies;

		private $script_variables;

		public function __construct(Options_Manager $options_manager)
		{
			$this->script_variables = [];
			$this->script_dependencies = [ 'jquery' ];

			$this->loader = Registry::get_loader();
			$this->options_manager = $options_manager;

			$this->loader->add_action('admin_menu', $this, 'add_menu');
			$this->loader->add_filter('plugin_action_links_' . Config_Manager::$plugin_base, $this, 'add_settings_link');
			$this->loader->add_action( 'admin_enqueue_scripts', $this, 'register_styles' );
			$this->loader->add_action( 'admin_enqueue_scripts', $this, 'register_scripts' );
			$this->loader->add_action( 'admin_init', $this, 'init_settings');

		}

		public function add_settings_link( $links )
		{
			$my_links = [
				'<a href="' . admin_url( 'options-general.php?page=' .Config_Manager::$slug ) . '">' .__('Settings' , Config_Manager::$slug). '</a>'
			];
			return array_merge( $links, $my_links );
		}
		public function add_menu()
		{
			$capability = 'manage_options';

			if(has_filter('mbhi_capability')) {
				$capability = apply_filters( 'mbhi_capability', $capability );
			}

			add_options_page('', Config_Manager::$name, $capability, Config_Manager::$slug, [ $this,'display_settings' ] );
		}

		public function init_settings()
		{
			register_setting( Config_Manager::$slug , Config_Manager::$settings_key );

			foreach($this->options_manager->get_sections() as $section){

				add_settings_section($section->id, '', null, Config_Manager::$slug);

				foreach($section->get_options() as $option){

					add_settings_field(
						$option->id,
						$option->title,
						[ $this->options_manager,'display_field' ],
						Config_Manager::$slug,
						$section->id,
						[ 'option' => $option ]
					);
				}
			}


		}

		public function display_settings()
		{
			$sections = $this->options_manager->get_sections();
			$slug = Config_Manager::$slug;

			ob_start();
			include Config_Manager::$dir . 'core/templates/start.php';
			echo ob_get_clean();
		}

		public function register_styles() {
			if(isset($_GET['page']) && $_GET['page'] == Config_Manager::$slug)
				wp_enqueue_style(
					Config_Manager::$slug,
					Config_Manager::$url . 'admin/css/admin.min.css',
					[],
					Config_Manager::$version,
					'all' );
		}

		public function register_scripts()
		{
			if(isset($_GET['page']) && $_GET['page'] == Config_Manager::$slug)
			{
				wp_enqueue_script( Config_Manager::$slug, Config_Manager::$url . 'admin/js/admin.min.js', $this->script_dependencies, Config_Manager::$version, false );
				wp_localize_script(Config_Manager::$slug,'mabel_ajax',$this->script_variables);
			}
		}


		public function add_ajax_function($name,$component,$callable,$frontend = true,$backend = true)
		{
			if($frontend)
				$this->loader->add_action('wp_ajax_nopriv_' . $name,$component,$callable);
			if($backend)
				$this->loader->add_action('wp_ajax_' . $name,$component,$callable);
		}

		public function add_script_variable($key,$value)
		{
			$this->script_variables[$key] = $value;
		}

		public function add_script_dependencies(array $dependencies)
		{
			$this->script_dependencies = array_merge($this->script_dependencies,$dependencies);
		}

	}

}