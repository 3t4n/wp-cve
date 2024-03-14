<?php
	namespace sv_provenexpert;

	class widget extends modules {
		protected static $widget_class_name = '';

		public function init() {
			$this->set_section_title( __( 'Widget Settings', 'sv_provenexpert' ) );
			$this->set_section_desc(__( 'Manage your widgets', 'sv_provenexpert' )  );
			$this->set_section_type( 'settings' );
			$this->get_root()->add_section( $this );
			
			$this->load_settings()->register_scripts();
			
			$widget	= static::$widgets->create( $this )
				->set_title( __( 'SV ProvenExpert',$this->get_root()->get_prefix() ) )
				->set_ID($this->get_prefix())
				->set_description( __( 'Show Review Stars in Google SERPs',$this->get_root()->get_prefix() ) )
				->set_template_path( 'lib/frontend/tpl/widget.php' );
			
			static::$widget_class_name  = $widget->set_widget_class_name(get_class(new class($widget) extends \sv_core\sv_widget{protected static $sv;}))->load();
			
			add_shortcode( 'sv_provenexpert', array( $this, 'shortcode' ) );
			
			// legacy
			add_shortcode( 'sv_proven_expert', array( $this, 'shortcode' ) );

			$this->clear_cache->init();

			// conditionally load Custom CSS for active Widgets
			add_action('wp', function(){
				//if(is_active_widget(false, false, 'sv_provenexpert_widget') !== false ){ // disabled as doesn't work when blocks are used in sidebar
					$this->get_script( 'config' )->set_is_enqueued();
					$this->get_script( 'frontend' )->set_is_enqueued();
				//}
			}, 9999999999);
		}

		public function shortcode() {
			$this->get_script( 'frontend' )->set_is_enqueued();

			ob_start();
			
			the_widget( static::$widget_class_name, array(), array(
				'before_widget'										=> '',
				'after_widget'										=> '',
				'before_title'										=> '',
				'after_title'										=> ''
			) );
			
			$output													= ob_get_clean();

			return $output;
		}

		protected function register_scripts(): widget {
			$this->get_script( 'frontend' )->set_path( 'lib/frontend/css/widget.css' );
			
			return $this;
		}
	}