<?php
class Zozuk_Transliterator{

	function __construct($hook){
		$this->enqueue_style();
		if ( $hook == 'post-new.php' || $hook == 'post.php' ) {
			$this->enqueue_API_scripts();
			$this->enqueue_editor_script();
			$this->setup_classic_editor();
			$this->setup_block_editor();
		}

	}

	private function setup_classic_editor(){
		wp_enqueue_script(
			'transliterateClassicEditor',
			plugin_dir_url(__DIR__).'assets/js/classic-editor.js',
			'wphindi-writer',
			WPHINDI_VERSION,
			true
		);

		// Add toggle button
		add_action('media_buttons',function(){
			echo '
			<button class="button active enabled" id="toggle-transliterator">
			<span class="wphindi-logo"></span>
			<span class="text">Disable WPHindi</span>
			</button>';
		});

	}
	private function setup_block_editor(){
		
		wp_enqueue_script(
			'transliterateZozukBlock',
			plugin_dir_url(__DIR__).'assets/js/block.js',
			['googleTransliterateAPI','wp-blocks','wp-element','wp-editor'],
			WPHINDI_VERSION,
			true
		);
		// List 
		wp_enqueue_script(
			'wphindi',
			plugin_dir_url(__DIR__).'assets/js/list-block.js',
			['wp-blocks',
			'wp-dom-ready',
			'wp-edit-post'],
			WPHINDI_VERSION
		);
		

	}

	private function enqueue_API_scripts(){

		wp_register_script(
			'googleTransliterateAPI',
			plugin_dir_url(__DIR__).'assets/js/api.js',
			null,
			WPHINDI_VERSION
		);

		

	}
	
	private function enqueue_style(){
		wp_enqueue_style(
			'transliterateCSS',
			plugin_dir_url(__DIR__).'assets/css/transliteration.css',
			null,
			WPHINDI_VERSION
		);
		
        wp_enqueue_style(
			'WPHindi',
			plugin_dir_url(__DIR__).'/assets/css/wphindi-admin.css',
			null,
			WPHINDI_VERSION
		);

	}

	private function enqueue_editor_script(){
		wp_enqueue_script(
			'wphindi-writer',
			plugin_dir_url(__DIR__).'assets/js/wphindi-writer.js',
			'googleTransliterateAPI',
			WPHINDI_VERSION
		);
	}
}