<?php
/** 
 *	Functions
 */

class YESNO_Function {
	/*
	 *	Load 
	 */
	public static function load() {
		add_action('init', array('YESNO_Function', 'init') );
	}

	/**
	 *	Initialize
	 */
	public static function init(){
		add_action( 'wp_enqueue_scripts', array('YESNO_Function', 'add_front_script') );
		add_shortcode('yesno_chart', array('YESNO_Function', 'chart') );
	}

	/**
	 *	Front script
	 */
	public static function add_front_script() {
		global $yesno;
		// css
		wp_enqueue_style(
			YESNO::PLUGIN_ID.'_style',		// handle
			$yesno->mypluginurl.'css/style.css',	// src
			false, 							// deps
			YESNO::PLUGIN_VERSION, 			// ver
			'all'							// media
		);
		// js
		wp_register_script(
			YESNO::PLUGIN_ID.'_script',		// handle
			$yesno->mypluginurl.'js/yesno.js',	// src
			array( 'jquery' ),				// deps
			YESNO::PLUGIN_VERSION, 			// ver
			true 							// in footer
		);
		wp_enqueue_script( YESNO::PLUGIN_ID.'_script' );
		wp_localize_script(
			YESNO::PLUGIN_ID.'_script',		// handle
			'yesno_text',
			array(
				'back' => __( 'Back', 'yesno' ),
			)
		);
	}

	/**
	 *	Shortcode:[yesno_chart id="1"]
	 */
	public static function chart( $atts = array(), $content = null ) {
		global $wpdb, $yesno;
		extract(
			shortcode_atts(
				array(
					'sid' => null
				),
				$atts
			)
		);
		if ( empty( $sid ) ){
			return sprintf('<p>%s</p>', __('Question Set ID is not specified.', 'yesno') );
		}
		if ( ! is_numeric( $sid ) || intval( $sid ) != $sid || $sid <= 0 ) {
			return sprintf('<p>%s</p>', __('Invalid Question Set ID.', 'yesno') );
		}
		$set = YESNO_Set::get_row( $sid );
		if ( empty( $set ) ) {
			return sprintf('<p>%s</p>', __('Invalid Question Set ID.', 'yesno') );
		}

		$prefix = $wpdb->prefix.YESNO::TABLEPREFIX;
		$table = $prefix.'question';
		$query = "SELECT * FROM {$table} "
				."WHERE sid=%d "
				."ORDER BY qnum ASC ";
		$q = $wpdb->get_row( $wpdb->prepare( $query, $sid ), ARRAY_A );
		if ( empty( $q ) ) {
			return sprintf('<p>%s</p>', __('No question.', 'yesno') );
		}
		$clist = unserialize( $q['choices'] );
		$choices = '';
		if ( ! empty( $clist ) ) {
			$choices .= '<ul id="choices">'."\n";
			foreach ( $clist as $c ) {
				$choices .= sprintf('<li><button value="%s">%s</button>'."\n", esc_html( $c['goto'] ), esc_html( $c['label'] ) );
			}
			$choices .= "</ul>\n";
		}
		$html = '';
		$subject = <<<EOD
<div id="yesno_wrap">
<input type="hidden" id="sid" name="sid" value="%SID%" />
<div id="question_wrap">
<dl class="yesno_q q%QNUM%" id="q%QID%">
	<dt><span>%TITLE%</span></dt>
	<dd>%QUESTION%</dd>
</dl>
</div>
%CHOICES%
</div>
EOD;
		$title = ( ! empty( $q['title'] ) ) ? $q['title'] : sprintf( __('Q%d', 'yesno'), $q['qnum'] ); 
		$search = array(
			'%SID%',
			'%QID%',
			'%QNUM%',
			'%QUESTION%',
			'%CHOICES%',
			'%TITLE%'
		);
		$replace = array(
			absint( $q['sid'] ),
			absint( $q['qid'] ),
			absint( $q['qnum'] ),
			htmlspecialchars_decode( $q['question'] ),
			$choices,
			esc_html( $title )
		);
		$html = str_replace( $search, $replace, $subject );
		return $html;
	}

	/**
	 *	Question set selector (with redirect)
	 */
	public static function select_set( $current, $name ) {
		/*
		$current = 1, 2 など
		*/
		global $wpdb, $yesno;

		$prefix = $wpdb->prefix.YESNO::TABLEPREFIX;
		$table = $prefix.'set';
		$query = "SELECT * FROM {$table} "
				."WHERE %d ";
		$ret = $wpdb->get_results( $wpdb->prepare( $query, 1 ), ARRAY_A );
		$html = '';
		if ( ! empty( $ret ) ) {
			$html = 
			$option = sprintf('<option value="">%s'."\n", __('Select any set', 'yesno') );
			foreach ( $ret as $r ) {
				$selected = ( $current == absint( $r['sid'] ) ) ? 'selected' : '';
				$option .= sprintf('<option value="%d" %s >%d: %s'."\n", absint( $r['sid'] ), $selected, absint( $r['sid'] ), esc_html( $r['title'] ) );
			}
			$html = sprintf('<select name="%s">'."\n", $name );
			$html .= $option;
			$html .= "</select>\n";
		}
		return $html;
	}
}
