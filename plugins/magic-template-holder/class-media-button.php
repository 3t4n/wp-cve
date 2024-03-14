<?php
/*
$args = array(
	'id' => 'insert-mth-template',
	'class' => 'mth-tempalte',
	'href' => 'javascript:void(0);',
	'text' => 'エディターボタン',
	'icon' => '<i class="dashicons-media-document"></i>',

	// オプション 呼び出し側でアクションフックを使用可能なため
	'css_handle' => 'css_handle',
	'css_for_buttons' => 'css_url',
	'js_handle' => 'js_handle',
	'script_for_buttons' => 'js_url',
	'echo' => true,
)
*/

// エディターボタンの追加
if( ! class_exists( 'Nora_Editor_Button' ) ) {
	class Nora_Editor_Button {

		// ショートコード名に使用
		public $input_array_holder = array(); 

		function __construct( $args ) {

			$this->input_array_holder = wp_parse_args( $args, array(
				'href' => 'javascript: void( 0 );',
				'echo' => true,
			) );

			add_action( 'media_buttons', array( $this, 'add_editor_buttons' ) );

			add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
			add_action( 'admin_footer', array( $this, 'admin_footer' ) );

		}

		// メディアを追加の横にボタンを設置する場合
		function add_editor_buttons( $editor_id ) {

			$button = '<a ';

				$button .= 'id="' . esc_attr( 
					! empty( $this->input_array_holder[ 'id' ] )
					? esc_attr( $this->input_array_holder[ 'id' ] )
					: ''
				) . '" ';

				$button .= 'class="button nora-editor-button ' . esc_attr( 
					! empty( $this->input_array_holder[ 'class' ] )
					? esc_attr( $this->input_array_holder[ 'class' ] )
					: ''
				) . '" ';

				$button .= 'href="' . ( 
					! empty( $this->input_array_holder[ 'href' ] ) 
					? esc_url( $this->input_array_holder[ 'href' ] ) 
					: 'javascript: void( 0 );'
				) . '" ';

				$button .= 'title="' . ( 
					! empty( $this->input_array_holder[ 'text' ] )
					? esc_attr( $this->input_array_holder[ 'text' ]  )
					: ''
				) . '"';

			$button .= '>';

				$button .= $this->input_array_holder[ 'icon' ] . esc_attr( $this->input_array_holder[ 'text' ] );

			$button .= '</a>';

			if( $this->input_array_holder[ 'echo' ] ) echo $button;
			
			return $button;

		}

		function admin_enqueue_scripts() {

			if( ! empty( $this->input_array_holder[ 'css_handle' ] ) ) {
				wp_enqueue_style( 
					$this->input_array_holder[ 'css_handle' ], 
					$this->input_array_holder[ 'css_url' ]
				);
			}

			if( ! empty( $this->input_array_holder[ 'js_handle' ] ) ) {
				wp_enqueue_script( 
					$this->input_array_holder[ 'js_handle' ], 
					$this->input_array_holder[ 'js_url' ], 
					array( 'jquery' )
				);
			}

		}

		function admin_footer() {

			// 出力しておきたいデータなど

		}

	}//end class
}
?>