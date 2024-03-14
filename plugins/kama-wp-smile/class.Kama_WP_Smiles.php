<?php

class Kama_WP_Smiles {

	const OPT_NAME = 'kwsmile_opt';

	public $opt;

	static $sm_start = '(:';
	static $sm_end   = ':)';

	static $sm_img; // шаблон замены

	public $pack_path = '';
	public $pack_url = '';
	public $more_packs_path = '';

	static $instance;

	static function instance(){

		! self::$instance && self::$instance = ( is_admin() && ! defined('DOING_AJAX') ) ? new Kama_WP_Smiles_Admin() : new self;

		return self::$instance;
	}

	function __construct(){

		$this->opt = get_option( self::OPT_NAME );

		if( false === $this->opt )
			$this->opt = get_option('wp_sm_opt'); // for ver less then 1.9.0

		$this->_set_pack_data();

		self::_set_sm_start_end( $this->opt );

		self::load_textdomain();

		// init

		if( $this->get_opt( 'textarea_id' ) )
			add_action( 'wp_footer', [ $this, 'footer_scripts' ] );

		add_action( 'wp_head', [ $this, 'styles' ] );
		add_filter( 'comment_text', [ $this, 'convert_smilies' ], 5 );
		add_filter( 'the_content', [ $this, 'convert_smilies' ], 5 );
		add_filter( 'the_excerpt', [ $this, 'convert_smilies' ], 5 );
	}

	static function load_textdomain(){
		load_plugin_textdomain( 'kama-wp-smile', false, basename( KWS_PLUGIN_PATH ) . '/languages' );
	}

	function _set_pack_data(){

		$this->more_packs_path = untrailingslashit( KWS_PLUGIN_PATH ) . '-packs/';

		$this->pack_url  = KWS_PLUGIN_URL . 'packs/qip/';
		$this->pack_path = KWS_PLUGIN_PATH . 'packs/qip/';

		// external folder - /plugins/kama-wp-smile-packs/
		$pack_name = $this->get_opt( 'sm_pack' );
		if( is_dir( untrailingslashit( KWS_PLUGIN_PATH ) . "-packs/$pack_name" ) ){
			$this->pack_url  = untrailingslashit( KWS_PLUGIN_URL ) . "-packs/$pack_name/";
			$this->pack_path = untrailingslashit( KWS_PLUGIN_PATH ) . "-packs/$pack_name/";
		}
		// inner folder - /plugins/kama-wp-smile/packs/
		elseif( is_dir( KWS_PLUGIN_PATH . "packs/$pack_name" ) ){
			$this->pack_url  = KWS_PLUGIN_URL . "packs/$pack_name/";
			$this->pack_path = KWS_PLUGIN_PATH . "packs/$pack_name/";
		}

		/**
		 * Allow to change Kama_WP_Smile::more_packs_path, Kama_WP_Smile::pack_url, Kama_WP_Smile::pack_path properties.
		 */
		apply_filters_ref_array( 'kwsmile_pack_path_url', [ $this ] );

		self::$sm_img = '<img class="kws-smiley" src="' . $this->pack_url . '%s.' . $this->get_opt( 'file_ext' ) . '" alt="%s" />';
	}

	static function _set_sm_start_end( $opt ){
		if( ! empty($opt['sm_start']) ) self::$sm_start = $opt['sm_start'];
		if( ! empty($opt['sm_end']) )   self::$sm_end = $opt['sm_end'];
	}

	/**
	 * Retrives the specified option by name orr reurns all options.
	 *
	 * @param string $name
	 *
	 * @return mixed|void
	 */
	function get_opt( $name = '' ){

		// important options
		if( empty( $this->opt['file_ext'] ) ){
			$this->opt['file_ext'] = 'gif';
		}

		if( empty( $this->opt['sm_pack'] ) ){
			$this->opt['sm_pack'] = 'qip';
		}

		if( $name ){
			$opt = isset( $this->opt[ $name ] ) ? $this->opt[ $name ] : null;
		}
		else {
			$opt = $this->opt;
		}

		/**
		 * Allow to change single option.
		 */
		return apply_filters( 'kws_get_opt', $opt, $name );
	}

	/**
	 * Replace smiles codes to smiles in specified text. Ex: `(:good:) >>> <img ...>`.
	 *
	 * @param string $text Text to replace smiles codes in.
	 *
	 * @return string Parsed text.
	 */
	function convert_smilies( $text ){
		$pattern = [];

		// общий паттерн смайликов для замены (:good:)
		$pattern[] = preg_quote( self::$sm_start, '/' ) . '([a-zA-Z0-9_-]{1,20})' . preg_quote( self::$sm_end, '/' );

		// спец смайлики типа `:)`
		foreach( $this->get_opt( 'hard_sm' ) as $sm_code => $sm_name ){
			$pat = preg_quote( $sm_code, '/' );
			$pat .= '(?=[.,\s\n<\'"]|$)'; // после смайлика должен идти: пробел|перенос/конец строки|начало тега|кавычки

			// если код смайлика начинается с ";" добавим возможность использвать спецсимволы вроде &quot;)
			// &#34; &#165; &#8254; &quot;
			// {2,6} Lookbehinds need to be zero-width, thus quantifiers are not allowed
			if( $pat[0] === ';' )
				$pat = '(?<!&.{2}|&.{3}|&.{4}|&.{5}|&.{6})' . $pat;

			$pattern[] = $pat;
		}

		// Объединим все патерны. NOTE: Так работае в 50 раз медленнее, лучше обрабатывать по отдельности.
		//$combine_pattern = implode( '|', $pattern );

		$skip_tags = array_merge( [ 'style', 'script', 'textarea' ], $this->get_opt( 'spec_tags' ) );
		$skip_tags_patt = [];

		foreach( $skip_tags as $tag ){
			$skip_tags_patt[] = "(<$tag.*?$tag>)"; // (<code.*?code>)|(<pre.*?pre>)
		}

		$skip_tags_patt = implode( '|', $skip_tags_patt );

		$text_parts = preg_split( "/$skip_tags_patt/si", $text, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY );

		$new_text = '';

		$skip_tags_patt2 = '^<(?:' . implode( '|', $skip_tags ) . ')'; // ^<(?:code|pre|blockquote)
		foreach( $text_parts as $txpart ){

			if( ! preg_match( "/$skip_tags_patt2/i", $txpart ) ){
				// заменяем по отдельности, так в 50 раз быстрее
				foreach( $pattern as $patt ){
					$txpart = preg_replace_callback( "/$patt/", [ $this, '_smiles_replace_cb' ], $txpart );
				}
			}

			$new_text .= $txpart;
		}

		return $new_text;
	}

	/**
	 * Replace callback for convert_smilies() mothod.
	 *
	 * @param array $match
	 *
	 * @return mixed|string
	 */
	function _smiles_replace_cb( $match ){

		// сначала заменяем полные патерны с названием файла - (:smile:)
		$filename = isset( $match[1] ) ? $match[1] : '';
		if( $filename ){
			if( in_array( $filename, $this->get_opt( 'all_sm' ) ) ){
				return sprintf( self::$sm_img, $match[1], $match[1] );
			}
			else{
				return '<span title="' . $filename . '.' . $this->get_opt( 'file_ext' ) . ' - no smiley file...">--</span>';
			}
		}
		// теперь специальные обозначения
		else{
			$hard_sm = $this->get_opt( 'hard_sm' );
			if( $hard_sm && isset( $hard_sm[ $match[0] ] ) ){
				return sprintf( self::$sm_img, $hard_sm[ $match[0] ], $hard_sm[ $match[0] ] );
			}
		}

		return $match[0]; // " {smile $match[0] not defined} ";
	}

	function footer_scripts(){
		if( ! is_singular() || ( isset($GLOBALS['post']) && $GLOBALS['post']->comment_status != 'open' ) )
			return;

		$all_smile = addslashes( $this->get_all_smile_html( $this->get_opt('textarea_id') ) );

		?>
		<!-- Kama WP Smiles -->
		<?php echo $this->insert_smile_js(); ?>
		<script type="text/javascript">
			var tx = document.getElementById('<?php echo $this->get_opt('textarea_id') ?>');
			if( tx ){
				var
				txNext = tx.nextSibling,
				txPar  = tx.parentNode,
				txWrapper = document.createElement('DIV');

				txWrapper.innerHTML = '<?php echo $all_smile ?>';
				txWrapper.setAttribute('class', 'kws-wrapper');
				txWrapper.appendChild(tx);
				txWrapper = txPar.insertBefore(txWrapper, txNext);
			}
		</script>
		<?php
	}

	function get_all_smile_html( $textarea_id = '', $args = array() ){

		$all_smiles = $this->all_smiles( $textarea_id );

		// прячем src чтобы не было загрузки картинок при загрузке страницы, только при наведении
		$all_smiles = str_replace( 'style', 'bg', $all_smiles );

		$out = '
		<div class="sm_list '.( isset($args['add_to_editor']) ? '' : $this->get_opt('smlist_pos') ) . ' ' . $this->get_opt('sm_pack') . '" style="width:30px; height:30px; background-image:url(' . $this->pack_url . 'smile.' . $this->get_opt('file_ext') . '); background-position:center center; background-repeat:no-repeat;"
			onmouseover="
			var el = this.childNodes[0];
			if( el.style.display == \'block\' )	return;

			el.style.display=\'block\';

			for( var i=0; i < el.childNodes.length; i++ ){
				var l = el.childNodes[i];
				var bg = l.getAttribute(\'bg\');
				if( bg )
					l.setAttribute( \'style\', bg );
			}
			"
			onmouseout="this.childNodes[0].style.display = \'none\'">
			<div class="sm_container">'. $all_smiles .'</div>
		</div>';

		// нужно в одну строку, используется в js
		return str_replace( [ "\n", "\t", "\r" ], '', $out );
	}

	function all_smiles( $textarea_id = false ){

		// собираем все в 1 массив
		$gather_sm = [];

		// переварачиваем и избавляемся от дублей
		$hard_sm = array_flip( $this->get_opt( 'hard_sm' ) );

		foreach( $this->get_opt( 'used_sm' ) as $sm ){

			if( isset( $hard_sm[ $sm ] ) )
				$gather_sm[ $sm ] = $hard_sm[ $sm ];
			else
				$gather_sm[ $sm ] = self::$sm_start . $sm . self::$sm_end;
		}

		// преобразуем в картинки
		$out = '';
		foreach( $gather_sm as $name => $smcode ){

			$onclick = "kmsmileInsert( '$smcode', '" . ( $textarea_id ?: $this->get_opt( 'textarea_id' ) ) ."' );";
			$bg_image = "{$this->pack_url}{$name}." . $this->get_opt( 'file_ext' );

			$out .= sprintf(
				'<div class="smiles_button" onclick="%s" style="background-image:url(%s);" title="%s"></div>',
				$onclick, $bg_image, $smcode
			);
		}

		return $out;
	}


	/**
	 * Callback for `wp_head` adds styles.
	 */
	function styles(){

		if( ! is_singular() || $GLOBALS['post']->comment_status !== 'open' ){
			return;
		}

		echo '<style>' . $this->main_css() . strip_tags( $this->get_opt( 'additional_css' ) ) . '</style>';
	}

	function main_css(){
		ob_start();
		?>
		<style>
		/* kwsmiles preset */
		.kws-wrapper{ position:relative; z-index:99; }
		.sm_list{ z-index:9999; position:absolute; bottom:.3em; left:.3em; }
		.sm_container{
			display:none; position:absolute; top:0; left:0; box-sizing:border-box;
			width:410px; background:#fff; padding:5px;
			border-radius:2px; box-shadow: 0 1px 2px rgba(0, 0, 0, 0.35);
			max-height:200px; overflow-y:auto; overflow-x:hidden;
		}
		.sm_container:after{ content:''; display:table; clear:both; }
		.sm_container .smiles_button{ cursor:pointer; width:50px; height:35px; display:block; float:left; background-position:center center; background-repeat:no-repeat; /*background-size:contain;*/ }
		.sm_container .smiles_button:hover{ background-color:rgba(200, 222, 234, 0.32); }
		.kws-smiley{ display:inline !important; border:none !important; box-shadow:none !important; background:none !important; padding:0; margin:0 .07em !important; vertical-align:-0.4em !important;
		}

		.sm_list.topright{ top:.3em; right:.3em; bottom:auto; left:auto; }
		.sm_list.topright .sm_container{ right:0; left:auto; }
		.sm_list.bottomright{ top:auto; right:.3em; bottom:.3em; left:auto; }
		.sm_list.bottomright .sm_container{ top:auto; right:0; bottom:0; left:auto; }

		.sm_list.skype_big, .sm_list.skype_big .smiles_button{ background-size:contain; }
		</style>
		<?php

		return preg_replace( '~<style>|</style>|^\t{2}~m', '', ob_get_clean() );
	}

	function insert_smile_js(){

		static $once; if( $once++ ) return null;

		$space = apply_filters( 'kwsmile__insert_smile_space', ' ' );

		ob_start();
		?>
		<script type="text/javascript">
		function kmsmileInsert( smcode, textareaId ){

			const tx = document.getElementById( textareaId );

			if( ! tx )
				return;

			if( typeof tx.selectionStart === 'undefined' ){
				console.warn( 'Kama WP Smile can\'t work properly because your browser is too old.' );
				return;
			}

			tx.focus();

			let startPos = tx.selectionStart;
			let endPos = tx.selectionEnd;
			let startText = tx.value.substr( 0, startPos );
			let endText = tx.value.substr( endPos );

			smcode = '<?= $space ?>' + smcode;

			tx.value = startText + smcode + endText;

			tx.selectionStart = startPos + smcode.length;
			tx.selectionEnd = startPos + smcode.length;

			document.querySelector( '.sm_container' ).style.display = 'none';

			if( typeof tinyMCE !== 'undefined' )
				tinyMCE.execCommand( 'mceInsertContent', false, smcode );
		}
		</script>
		<?php
		return ob_get_clean();
	}

	/**
	 * читаем файлы с каталога. вернет массив
	 *
	 * @param string $dir
	 *
	 * @return array
	 */
	function get_dir_smile_names( $dir = '' ){
		$out = [];

		if( ! $dir )
			$dir = $this->pack_path;

		foreach( glob( trailingslashit( $dir ) . '*.{gif,png}', GLOB_BRACE ) as $fpath ){
			$fname = basename( $fpath );
			$out[] = preg_replace( '/\.[^.]+$/', '', $fname ); // удяляем расширение
		}

		return $out;
	}

}


