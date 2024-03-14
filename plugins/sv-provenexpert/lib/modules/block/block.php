<?php
	namespace sv_provenexpert;

	class block extends modules {
		public function init() {
			$this->register_scripts();
		}
		protected function register_scripts(): block {
			$this->get_script( 'editor' )
				->set_path('editor.css');

			$this->get_script( 'block' )
				->set_type('js')
				->set_path('build/block.build.js')
				->set_deps(array( 'wp-blocks', 'wp-element', 'wp-components', 'wp-editor' ));

			register_block_type( 'sv-provenexpert/sv-provenexpert', array(
				'attributes' => array(
					'blockValue' => array(
						'type' => 'string',
						'default' => '[sv_provenexpert]',
					),
				),
				'editor_script' => $this->get_script( 'block' )->get_handle(),
				'render_callback' => function ($attributes) {
					return do_shortcode($attributes['blockValue']);
				},
				'style'	=> $this->get_module('widget')->get_script( 'frontend' )->get_handle(),
				'editor_style'	=> $this->get_script( 'editor' )->get_handle()
			) );
			
			return $this;
		}
	}