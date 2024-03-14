<?php


class Kama_WP_Smiles_Admin extends Kama_WP_Smiles {

	private static $access_cap = 'manage_options';

	function __construct(){
		parent::__construct();

		add_action( 'admin_menu', [ $this, 'admin_page' ] );

		add_action( 'the_editor', [ $this, 'admin_add_to_editor' ] );
		add_action( 'admin_print_footer_scripts', [ $this, 'admin_js' ], 999 );

		add_action( 'admin_head', [ $this, 'admin_styles' ] );

		add_filter( 'current_screen', [ $this, 'upgrade_init' ] );
	}

	function upgrade_init(){

		require_once KWS_PLUGIN_PATH .'admin/plugin_upgrade.php';

		ksw_version_upgrade();
	}

	function admin_styles(){
		echo '<style>'. kwsmile()->main_css() .'</style>';
	}

	function admin_page(){

		$hookname = add_options_page(
			__( 'Kama WP Smiles Settings', 'kama-wp-smile' ),
			'Kama WP Smiles',
			self::$access_cap,
			'kama_wp_smiles_opt',
			[ $this, 'admin_options_page'	]
		);

		add_action( "load-$hookname", [ $this, 'opt_page_load' ] );
	}

	function admin_options_page(){
		if( ! current_user_can( self::$access_cap ) )
			return;

		include KWS_PLUGIN_PATH .'admin/admin-page.php';
	}

	function opt_page_load(){

		wp_enqueue_style( 'ks_admin_page', KWS_PLUGIN_URL . 'admin/admin-page.css', [], KWS_VER );

		if(
			isset( $_POST['kwps_nonce'] )
			&& wp_verify_nonce( $_POST['kwps_nonce'], 'kwps_options_up' )
			&& check_admin_referer( 'kwps_options_up', 'kwps_nonce' )
		){
			// reset
			if( isset($_POST['kama_sm_reset']) )
				$this->set_def_options();

			// up options
			if( isset( $_POST['kama_sm_submit'] ) )
				$this->update_options_handler();
		}
	}

	function set_def_options(){
		return update_option( self::OPT_NAME, $this->def_options() );
	}

	protected function def_options(){

		return [
			'textarea_id'    => 'comment',
			'spec_tags'      => [ 'pre', 'code' ],
			'additional_css' => '',

			// разделил для того, чтобы упростить поиск вхождений
			'used_sm'        => [
				'smile',
				'sad',
				'laugh',
				'rofl',
				'blum',
				'kiss',
				'yes',
				'no',
				'good',
				'bad',
				'unknw',
				'sorry',
				'pardon',
				'wacko',
				'acute',
				'boast',
				'boredom',
				'dash',
				'search',
				'crazy',
				'yess',
				'cool',
			],

			// (исключения) имеют спец обозначения
			'hard_sm'        => [
				'=)'  => 'smile',
				':)'  => 'smile',
				':-)' => 'smile',
				'=('  => 'sad',
				':('  => 'sad',
				':-(' => 'sad',
				'=D'  => 'laugh',
				':D'  => 'laugh',
				':-D' => 'laugh',
			],

			'all_sm'   => $this->get_dir_smile_names(), // все имеющиеся смайлы
			'sm_start' => '', // начальный тег смайлка
			'sm_end'   => '', // конечный тег смайлка

			'file_ext' => 'gif', // file extension
			'sm_pack'  => 'qip', // пакет смайликов

			'smlist_pos' => '', // позиция списка смаликов
		];
	}

	// update options
	function update_options_handler(){
		$this->opt = array();

		// sanitize
		foreach( array_keys( $this->def_options() ) as $key ){

			$_val = isset( $_POST[ $key ] ) ? stripslashes( $_POST[ $key ] ) : '';

			// textarea_id
			if( 'textarea_id' === $key ){
				$_val = sanitize_key( $_val );
			}
			// additional_css
			elseif( 'additional_css' === $key ){
				$_val = strip_tags( $_val );
			}
			// spec_tags
			elseif( 'spec_tags' === $key ){
				if( ! empty( $_val ) ){
					$_val = preg_replace( '/[^a-z,]/', '', strtolower( $_val ) );
					$_val = explode( ',', $_val );
					$_val = array_map( 'sanitize_key', $_val );
				}
				else $_val = [];
			}
			// used_sm
			elseif( 'used_sm' === $key ){
				$_val = explode( ',', trim( $_val ) );
				$_val = array_map( 'trim', $_val );
				$_val = array_map( 'sanitize_key', $_val ); // protect
				$_val = array_filter( $_val );
			}
			// hard_sm
			elseif( 'hard_sm' === $key ){
				$_val = strip_tags( $_val ); // protect
				$_val = trim( $_val );
				$_val = explode( "\n", $_val );
				$_val = array_map( 'trim', $_val );
				$_val = array_filter( $_val );
				foreach( $_val as $val ){
					$vals                             = preg_split( '/ *>>>+ */', $val );
					$_val['temp'][ trim( $vals[0] ) ] = sanitize_key( trim( $vals[1] ) );
				}
				$_val = $_val['temp'];
			}
			// all_sm
			elseif( 'all_sm' === $key ){
				$_val = $this->get_dir_smile_names();
			}
			// sm_pack & file_ext
			elseif( 'sm_pack' === $key ){
				$_val = sanitize_key( $_val );
				// find extention
				foreach( glob( $this->pack_path . '*' ) as $file ){
					$ext = substr( $file, -3, 3 );
					if( strpos( $file, '.' ) && in_array( $ext, [ 'gif', 'png', 'jpg' ] ) ){
						$this->opt['file_ext'] = $ext;
						break;
					}
				}
			}
			// all_sm
			elseif( 'smlist_pos' === $key ){
				$_val = sanitize_key( $_val );
			}
			// default
			else{
				$_val = sanitize_text_field( $_val );
			}

			$this->opt[ $key ] = $_val;
		}

		update_option( self::OPT_NAME, $this->opt );

		$this->_set_pack_data();
	}

	## добавляем ко всем textarea созданым через the_editor
	function admin_add_to_editor( $html ){
		preg_match('~<textarea[^>]+id=[\'"]([^\'"]+)~i', $html, $match );
		$tx_id = $match[1];

		return str_replace('textarea>', 'textarea>'. $this->get_all_smile_html( $tx_id, array('add_to_editor'=>1) ), $html );
	}

	function admin_js(){
		echo $this->insert_smile_js();
		?>
		<script type="text/javascript">
		// Передвигаем блоки смайликов для визуального редактора и для HTML редактора
		jQuery(document).ready(function( $){
			// Передвигаем смайлы в HTML редактор
			// форм может быть много - перебираем массив
			$('.sm_list').each(function(){
				var $smlist = $(this);
					$quicktags = $smlist.siblings('.quicktags-toolbar');

				if( $quicktags[0] ){
					$quicktags.append( $smlist );
					$smlist
						.css({ position:'absolute', display:'inline-block', padding:'4px 0 0 25px', left:'auto', top:'auto', right:'auto', bottom:'auto', height:'23px' })
						.find('.sm_container').css({ left:'auto', right:0, top:0, bottom:'auto' });
				}

				// проверяем нет ли виз редактора
				var $editortabs = $smlist.closest('.wp-editor-container').prev().find('.wp-editor-tabs');
				if( $editortabs.length ){
					$editortabs.before( $smlist );
					$smlist
						.css({ 'margin-left':'10px' })
						.find('.sm_container').css({ left:0, right:'auto', top:0, bottom:'auto' }); // поправим стили
					//console.log( $smlist[0] );
				}
			});

			/*var $mce_editor = $('#insert-media-button');
			if( 0&& $mce_editor[0] ){
				var $smlist = $('.sm_list').first();
				$mce_editor.after(
					$smlist.css({ position:'relative', padding:'0', margin:'2px 0px 0px 30px', left:'none', top:'none', right:'none', bottom:'none' })
				);
			}*/
		});
		//*/
		</script>
		<?php
	}

	## Выберите смайлики:
	function dir_smiles_img(){
		$hard_sm = array_flip( $this->get_opt('hard_sm') );
		$gather_sm = array();

		foreach( $this->get_dir_smile_names() as $smile ){
			$sm_name = $sm_code = $smile;
			if( @ $hard_sm[ $smile ] ){
				$sm_code = $smile;
				$sm_name = $hard_sm[ $smile ];
			}

			echo '<b id="'. $sm_code .'" title="'. $sm_name .'" class="'. ( in_array( $sm_code, (array) $this->get_opt('used_sm') ) ? 'checked':'' ) . '" >' . sprintf( self::$sm_img, $sm_code, $sm_name ) . '</b>';
		}
	}

	function activation(){

		if( ! get_option( self::OPT_NAME ) )
			$this->set_def_options();

	}

}
