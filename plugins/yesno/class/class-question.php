<?php
/** 
 *	Questions
 */
add_action('plugins_loaded', array('YESNO_Question', 'load') );

class YESNO_Question extends YESNO_Paging {
	public $currenttime = null;
	public $url = null;

	/**
	 *	Load
	 */
	public static function load() {
		add_action('init', array('YESNO_Question', 'init') );
	}

	/** 
	 *	Init
	 */
	public static function init() {
		add_filter('yesno_question_list_filter', array('YESNO_Question', 'set_filter') );
		add_filter('yesno_question_list_order', array('YESNO_Question', 'set_order') );
		add_action('admin_init', array('YESNO_Question', 'formaction') );
	}

	/** 
	 *	CONSTRUCT
	 */
	public function __construct( $atts = array() ) {
		global $yesno, $wpdb;
		extract(
			$atts = shortcode_atts(
				array(
					'target' 	=> '',		// "room_id = 2" or "user_id = 3" or "email = 'xxx'"
					'limit' 	=> $yesno->options['question']['list_per_page'], 		// 1ページあたりの件数
					'order'		=> 'sid ASC, qnum ASC ',
				),
				$atts
			)
		);
		$args = array();
		$args = apply_filters('yesno_question_list_filter', $args );
		// filter: Set ID
		if ( isset( $args['sid'] ) ) {
			if ( ! empty( $target ) ) {
				$target .= ' AND ';
			}
			$target .= sprintf("sid=%d", $args['sid'] );
		}

		$prefix = $wpdb->prefix.YESNO::TABLEPREFIX;
		$table = $prefix.'question';
		$this->query = "SELECT %FIELDS% FROM ".$table.' ';
		$where = '';
		if ( ! empty( $target ) ) {
			$where .= "WHERE ".$target." ";	// "id = 2" or "cid = 'xxx'" ...
		}
		$this->query .= $where;
		if ( ! empty( $order ) ) {
			$this->query .= "ORDER BY ".$order." ";
		}

		$this->limit = $limit;
		$this->recordmax = $this->record_max( $atts );
		$this->pagemax = ceil( $this->recordmax / $this->limit );	// ページ数
		$this->get_current_page();
	}

	/** 
	 *	Get count of records
	 */
	public function record_max( $atts ){
		global $wpdb;

		$prefix = $wpdb->prefix.YESNO::TABLEPREFIX;
		$query = strtr( $this->query, array('%FIELDS%' => 'COUNT(*) as count') );
		$ret = $wpdb->get_row( $query, ARRAY_A );
		return $ret['count'];
	}

	/** 
	 *	Get records
	 */
	public function get( $atts = array() ){
		global $yesno, $wpdb;

		$limit = '';
		if ( $this->limit != 0 ) {
			if ( $this->offset != 0 ) {
				$limit = sprintf(' LIMIT %d, %d', $this->offset, $this->limit );
			}
			else {
				$limit = sprintf(' LIMIT %d', $this->limit );
			}
		}
		$fields = '*';
		$query = strtr( $this->query, array('%FIELDS%' => $fields ) );
		$query.= $limit;
		$ret = $wpdb->get_results( $query, ARRAY_A );
		return $ret;
	}

	/** 
	 *	Get with where
	 */
	public static function get_query( $atts = array() ){
		global $yesno, $wpdb;

		$prefix = $wpdb->prefix.YESNO::TABLEPREFIX;
		$table = $prefix.'question';
		$query = "SELECT * FROM ".$table." ";
		if ( isset( $atts['where'] ) ) {
			$query .= "WHERE ".$atts['where'];
		}
		$ret = $wpdb->get_row( $query, ARRAY_A );
		return $ret;
	}

	/** 
	 *	Get row
	 */
	public static function get_row( $id ){

		$atts = array(
			'where' => 'qid='.$id
		);
		return self::get_query( $atts );
	}

	/**
	 *	Next Question Number
	 */
	public static function get_next_qnum( $sid ){
		global $yesno, $wpdb;

		$prefix = $wpdb->prefix.YESNO::TABLEPREFIX;
		$table = $prefix.'question';
		$query = "SELECT qnum FROM {$table} "
				."WHERE sid=%d "
				."ORDER BY qnum DESC "
				."LIMIT 1 ";
		$ret = $wpdb->get_var( $wpdb->prepare( $query, $sid ), 0 );
		if ( empty( $ret ) ) {
			$qnum = 1;
		}
		else {
			$qnum = $ret + 1;
		}
		return $qnum;

	}

	/**
	 *  Insert
	 */
	public static function insert( $data ){
		global $wpdb;

		$prefix = $wpdb->prefix.YESNO::TABLEPREFIX;
		$table = $prefix.'question';
		$columns = $wpdb->get_col("DESC {$table}", 0 );

		$keys = $values = array();
		foreach ( $data as $key => $val ) {
			if ( in_array( $key, $columns ) ) {
				if ( 'choices' == $key ) {
					$val = serialize( $val );
				}
				$keys[] = sprintf("`%s`", $key );
				$values[] = sprintf("'%s'", $val );
			}
		}
		if ( ! empty( $keys ) ) {
			// Insert on duplicate
			if ( in_array('qid', $keys ) ) {
				$pairs = array();
				foreach ( $keys as $i => $key ) {
					// Skip id
					if ('qid' == $key ) {
						continue;
					}
					$pairs[] = sprintf('%s=%s', $key, $values[ $i ] );
				}
				$query = "INSERT INTO ".$table." "
						."(".implode(',', $keys ).") "
						."VALUES (".implode(',', $keys ).") "
						."ON DUPLICATE KEY UPDATE ".implode(',', $pairs );
			}
			else {
				$query = "INSERT INTO ".$table." "
						."(".implode(',', $keys ).") "
						."VALUES (".implode(',', $values ).") ";
			}
			// Get Next ID
			$get_status = "SHOW TABLE STATUS LIKE '".$table."' ";
 			$status = $wpdb->get_row( $get_status, ARRAY_A );
 			$next_id = 1;
 			if ( $status ) {
 				$next_id = $status['Auto_increment'];
 			}
 			// Insert
			$ret = $wpdb->query( $query );
			// Get Recent ID
			$get_recent = "SELECT qid FROM {$table} ORDER BY qid DESC ";
			$recent = $wpdb->get_row( $get_recent, ARRAY_A );
			if ( $next_id == $recent['qid'] ) {
				return $recent['qid'];
			}
		}
		return;
	}
 
	/**
	 *  Update
	 */
	public static function update( $data ){
 		global $wpdb;

		$prefix = $wpdb->prefix.YESNO::TABLEPREFIX;
		$table = $prefix.'question';
		$columns = $wpdb->get_col("DESC {$table}", 0 );

		$pairs = array();
		$where = '';
		foreach ( $data as $key => $val ) {
			if ( in_array( $key, $columns ) ) {
				if ('qid' == $key ) {
					$where = "WHERE `qid`=".$val;
				}
				else {
					if ( 'choices' == $key ) {
						if ( ! empty( $val ) ) {
							for ( $i = 0; $i < count( $val ); $i++ ) {
								$val[ $i ]['label'] = str_replace( array('\"', "\'"), array('&quot;', '&#39;'), $val[ $i ]['label'] );
							}
						}
						$val = serialize( $val );
					}
					$pairs[] = sprintf("`%s`='%s'", $key, $val );
				}
			}
		}
		if ( ! empty( $pairs ) ) {
			$query = "UPDATE ".$table." "
					."SET ".implode(',', $pairs )
					.$where;
			$ret = $wpdb->query( $query );
		}
	}
 
	/**
	 *  Delete
	 */
	public static function delete( $id ){
 		global $wpdb;

 		// Question
		$prefix = $wpdb->prefix.YESNO::TABLEPREFIX;
		$table = $prefix.'question';
		$query = "DELETE FROM ".$table." WHERE `qid`=".$id;
		$ret = $wpdb->query( $query );
	}

	/**
	 *	Sorting order
	 */
	public static function set_order( $order ) {
		global $yesno;

		$order = array(
			'qid'     => array(),
			'sid'     => array(),
			'qnum'    => array(),
		);

		foreach ( $order as $key => $array ) {
			$order[ $key ]['orderby'] = $key;
			if ( isset( $yesno->page['qs']['orderby'] ) &&  $yesno->page['qs']['orderby'] == $key ) {
				$order[ $key ]['order'] = ( $yesno->page['qs']['order'] == 'asc') ? 'desc' : 'asc';
			} else {
				$order[ $key ]['order'] = 'asc';
			}
		}
		return $order;
	}

	/**
	 *	Extract Filter
	 */
	public static function set_filter( $args = array() ) {
		global $yesno;

		$qs = $yesno->page['qs'];
		if ( is_admin() ) {
			if ( isset( $_SERVER['QUERY_STRING'] ) ) {
				parse_str( $_SERVER['QUERY_STRING'], $qs );
			}
		}

		foreach ( $qs as $key => $val ) {
			if ( in_array( $key, array('sid') ) ) {
				$args[ $key ] = urldecode( $val );
			}
		}
		return $args;
	}

	public static function formaction(){
		global $pagenow, $yesno;

		if ('POST' == $_SERVER ['REQUEST_METHOD'] && 'admin.php' == $pagenow ) {
			if ( isset( $_POST['new'] )
				&& $yesno->page['qs']['page'] == 'yesno-question' 
				&& $yesno->page['qs']['tab'] == 'addnew' ) {

				check_admin_referer('yesno');
				$args = null;
				if ( ! apply_filters('yesno_allow_generate', $args ) ) {
					wp_die( __( 'You are not allowed to this action.', 'yesno' ) );
				}
				$input = $_POST['new'];
				$sid = absint( wp_unslash( $input['sid'] ) );
				$qnum = absint( wp_unslash( $input['qnum'] ) );
				$question = ( $input['question'] ) ? htmlspecialchars( $input['question'] ) : '';
				$title = ( $input['title'] ) ? sanitize_text_field( $input['title'] ) : '';
				$url = ( $input['url'] ) ? esc_url_raw( $input['url'] ) : '';
				if ( $sid > 0 && ( ! empty( $question ) || ! empty( $url ) ) ) {
					$data = array(
						'sid'      => $sid,
						'qnum'     => ( $qnum ) ? $qnum : self::get_next_qnum( $sid ),
						'question' => $question,
						'title'    => $title,
						'url'      => $url,
					);
					// Question
					if ( intval( $_POST['opt_type'] ) ) {
						// Yes/No
						$data['choices'] = array(
							array(
								'cnum'  => 1,
								'label' => __('Yes', 'yesno'),
								'goto'  => null
							),
							array(
								'cnum'  => 2,
								'label' => __('No', 'yesno'),
								'goto'  => null
							)
						);
					}
					$qid = YESNO_Question::insert( $data );
					$q = YESNO_Question::get_row( $qid );
					if ( $qid ) {
						$base_url = get_admin_url().basename( $_SERVER['SCRIPT_NAME'] );
						$url = add_query_arg( $yesno->page['qs'], $base_url );
						$redirect_to = add_query_arg('tab', 'list', $url );
						$redirect_to = add_query_arg('sid', $q['sid'], $redirect_to );
						wp_redirect( $redirect_to );
						exit;
					}
				}
			}
		}
	}

	/**
	 *	Action in Admin page
	 */
	public static function admin_action( $param ) {
		global $wpdb, $yesno, $pagenow;
		/*
		$param = array(
			'options_group'  => 'customer',
			'message'        => '',
			'option_header'  => array(
				'header'       => '',
				'current_page' => $current_page,
				'current_tab'  => $current_tab,
				'tabs'         => array(),
			),
		);
		*/
		extract( $param );	// $options_group, $message, $option_header 
		$current_page = $option_header['current_page'];
		$current_tab = $option_header['current_tab'];
		if ( empty( $current_tab ) ) {
			$current_tab = $option_header['current_tab'] = 'list';
		}
		// URL
		$base_url = get_admin_url().basename( $_SERVER['SCRIPT_NAME'] );
		$url = add_query_arg( $yesno->page['qs'], $base_url );

		$option_header['tabs'] = array(
			'list'     => __('List', 'yesno'),
			'addnew'   => __('Add new', 'yesno'),
			'setting'  => __('Settings', 'yesno'),
		);

		// Management
		$options_group = 'question';

		if ('POST' == $_SERVER ['REQUEST_METHOD'] && 'admin.php' == $pagenow ) {

			check_admin_referer('yesno');
			$args = null;
			if ( ! apply_filters('yesno_allow_generate', $args ) ) {
				wp_die( __( 'You are not allowed to this action.', 'yesno' ) );
			}

			if ( isset( $_POST['action'] ) ) {
				$prefix = $wpdb->prefix.YESNO::TABLEPREFIX;
				$table = $prefix.'question';
				$action = preg_replace('/[^a-z]/', '', $_POST['action'] );
				switch ( $action ) {
					case 'addnew' :
						// After newly added, it is already redirected to the question list
						break;

					case 'edit' :
						if ( ! empty( $_POST['data'] ) ) {
							foreach ( $_POST['data'] as $i => $input ) {
								$qid = absint( wp_unslash( $input['qid'] ) );
								$sid = absint( wp_unslash( $input['sid'] ) );
								$qnum = absint( wp_unslash( $input['qnum'] ) );
								$question = ( $input['question'] ) ? htmlspecialchars( $input['question'] ) : '';
								$title = ( $input['title'] ) ? sanitize_text_field( $input['title'] ) : '';
								$url = ( $input['url'] ) ? esc_url_raw( $input['url'] ) : '';
								$choices  = array();
								$j = 1;
								foreach ( $input['choices'] as $c ) {
									if ( ! empty( trim( $c['label'] ) ) ) {
										$label = ( $c['label'] ) ? sanitize_text_field( $c['label'] ) : '';
										$goto = absint( wp_unslash( $c['goto'] ) );
										if ( $label ) {
											$choices[] = array(
												'label' => $label,
												'goto'  => ( $goto ) ? $goto : 0,
												'cnum'  => $j
											);
											$j++;
										}
									}
								}
								if ( $qid > 0 && $sid > 0 && ( ! empty( $question ) || ! empty( $url ) ) ) {
									$data = array(
										'qid'      => $qid,
										'sid'      => $sid,
										'qnum'     => ( $qnum ) ? $qnum : 1,
										'question' => $question,
										'title'    => $title,
										'url'      => $url,
										'choices'  => $choices
									);
									YESNO_Question::update( $data );
								}
							}
							$message = __('Updated.', 'yesno');
						}
						break;

					case 'list' :
						if ( ! empty( $_POST['del'] ) ) {
							foreach ( $_POST['del'] as $id ) {
								if ( absint( wp_unslash( $id ) ) ) {
									YESNO_Question::delete( absint( wp_unslash( $id ) ) );
								}
							}
							$message = __('Updated.', 'yesno');
						}
						if ( ! empty( $_POST['data'] ) ) {
							foreach ( $_POST['data'] as $i => $input ) {
								$qid = absint( wp_unslash( $input['qid'] ) );
								$qnum = absint( wp_unslash( $input['qnum'] ) );
								if ( $qid ) {
									$data = array(
										'qid'  => $qid,
										'qnum' => $qnum
									);
									YESNO_Question::update( $data );
								}
							}
							$message = __('Updated.', 'yesno');
						}
						break;

					case 'setting' :
						$options_key  = YESNO::PLUGIN_ID;
						$group = $options_group;
						$default_option = YESNO::default_option();
						// Reset
						if ( isset( $_POST[ YESNO::PLUGIN_ID.'_reset'] ) ) {
							$plugin_option = get_option( $options_key );
							$plugin_option[ $group ] = $default_option[ $group ]; 
							update_option( $options_key, $plugin_option );
							$message = __('Settings are reset', 'yesno');
						}

						// Update
						elseif ( isset( $_POST[ YESNO::PLUGIN_ID.'_options'] ) ) {
							$plugin_option = get_option( $options_key );
							if ( isset( $_POST[ YESNO::PLUGIN_ID.'_options']['list_per_page'] ) ) {
								$value = absint( wp_unslash( $_POST[ YESNO::PLUGIN_ID.'_options']['list_per_page'] ) );
								$plugin_option[ $group ]['list_per_page'] = ( $value ) ? $value : $default_option[ $group ]['list_per_page'];
							}
							$plugin_option = apply_filters( YESNO::PLUGIN_ID.'_update_option', $plugin_option );
							update_option( $options_key, $plugin_option );
							$message = __('Settings are updated', 'yesno');
						}
						break;
				}

			}
		}

		$param['options_group'] = $options_group;
		$param['message']       = $message;
		$param['option_header'] = $option_header;
		return $param;
	}

	/**
	 *	Admin page
	 */
	public static function admin_page( $param ) {
		global $wpdb, $yesno, $current_user;
		/*
		$param = array(
			'options_group'  => 'customer',
			'message'        => '',
			'option_header'  => array(
				'header'       => '',
				'current_page' => $current_page,
				'current_tab'  => $current_tab,
				'tabs'         => array(),
			),
		);
		*/
		extract( $param );	// $options_group, $message, $option_header 
		$current_page = $option_header['current_page'];
		$current_tab = $option_header['current_tab'];

		// URL
		$base_url = get_admin_url().basename( $_SERVER['SCRIPT_NAME'] );
		$url = add_query_arg( $yesno->page['qs'], $base_url );

		$plugin_option = get_option( YESNO::PLUGIN_ID );
		// Question set 
		$set = new YESNO_Set();
		if ( empty( $set->recordmax ) ) :
			$goto_qset = array(
				'page' => YESNO::PLUGIN_ID.'-set',
				'tab'  => 'list'
			);
			$message = sprintf('<div class="alert alert-warning"><a href="%s">%s</a></div>', add_query_arg( $goto_qset, $base_url ), __('Add new Question set.', 'yesno') );
?>
<div class="metabox-holder">
<div id="post-body">
<div id="post-body-content">
<div class="postbox">

<h3><span><?php _e('List', 'yesno'); ?></span></h3>
<div class="inside">
<div id="message">
<?php echo ( $message ) ? wp_kses_post( $message ) : ''; ?>
</div>

</div><!-- .inside -->
</div><!-- .postbox -->
</div><!-- #post-body-content -->
</div><!-- #post-body -->
</div><!-- .metabox-holder -->
<?php
			return;
		endif;

		switch ( $current_tab ) :

			case 'list' :
				$record = array();
				// Filter
				$filter = array();
				$filter = apply_filters('yesno_question_list_filter', $filter );
				$sid = ( !empty( $filter['sid'] ) ) ? $filter['sid'] : null;
				$obj = new YESNO_Question( $filter );
				if ( empty( $sid ) ) {
					$message = sprintf('<div class="alert alert-warning">%s</div>', __('Select Question Set', 'yesno') );
				}
				else {
					$records = $obj->get();
					if ( empty( $records ) ) {
						$message = sprintf('<div class="alert alert-warning">%s</div>', __('No record.', 'yesno') );
					}
				}

				// Sorting
				$order = array();
				$order = apply_filters('yesno_question_list_order', $order );

				$goto_edit = array(
					'page' => YESNO::PLUGIN_ID.'-question',
					'tab'  => 'edit',
				);
				$edit_url = add_query_arg( $goto_edit, $base_url );
?>
<div class="metabox-holder">
<div id="post-body">
<div id="post-body-content">
<div class="postbox">

<h3><span><?php _e('List', 'yesno'); ?></span></h3>
<div class="inside">
<div id="message">
<?php echo ( $message ) ? wp_kses_post( $message ) : ''; ?>
</div>
<?php
$allow = array(
	'form'=>array(
		'name'=>array(),
		'class'=>array()
	),
	'span'=>array(),
	'a'=>array(
		'href' =>array(),
	),
	'select'=>array(
		'name'=>array(),
		'onchange'=>array()
	),
	'option'=>array(
		'value'=>array(),
		'selected'=>array()
	)
);
echo wp_kses( self::select_set_redirect( $sid ), $allow );
?>
<?php if ( ! empty( $records ) ) : ?>

<form id="yesno_list" method="post"> 
<div class="submit_and_navi">
<input type="submit" name="submit" class="button-primary" value="<?php _e('Update', 'yesno'); ?>" class="large-text code" />
<?php echo  wp_kses_post( $obj->page_navi_full( $obj ) ); ?>
</div>

<table class="wp-list-table widefat admin_data_list">
<thead>
<tr>
<th class="del"><?php _e('DEL', 'yesno'); ?></th>
<th class="set"><?php _e('Question Set', 'yesno'); ?></th>
<th class="qnum"><?php _e('Question Number', 'yesno'); ?></th>
<th class="question"><?php _e('Question', 'yesno'); ?></th>
</tr>
</thead>

<?php if ( ! empty( $records ) ) : ?>
<tbody id="the-list">
<?php
		foreach( $records as $r ) :
			$qid = absint( $r['qid'] );
			$sid = absint( $r['sid'] );
			$set = YESNO_Set::get_row( $sid );
			$qedit_url = add_query_arg('qid', $qid, $edit_url );
			if ( ! empty( $r['question'] ) ) {
				$qtext = strip_tags( htmlspecialchars_decode( $r['question'] ) );
				$qtext = ( mb_strlen( $qtext ) <= 30 ) ? $qtext : mb_substr( $qtext, 0, 30 ).'...';
			}
			else {
				$qtext = $r['qnum'];
			}
?>
<tr>
<td><input type="checkbox" name="del[]" value="<?php echo esc_attr( $qid ); ?>" />
<input type="hidden" name="data[<?php echo esc_attr( $qid ); ?>][qid]" value="<?php echo esc_attr( $qid ); ?>" /></td>
<td><?php printf('%d:%s', absint( $sid ), esc_html( $set['title'] ) ); ?></td>
<td><input type="text" name="data[<?php echo esc_attr( $qid ); ?>][qnum]" value="<?php echo absint( $r['qnum'] ); ?>" size="3" /></td>
<td><a href="<?php echo esc_url_raw( $qedit_url ); ?>"><?php echo esc_html( $qtext ); ?></a></td>
</tr>
<?php
		endforeach;
?>
</tbody>
<?php endif; ?>
</table>

<div class="submit_and_navi">
<input type="hidden" name="action" value="list" />
<input type="submit" name="submit" class="button-primary" value="<?php _e('Update', 'yesno'); ?>" class="large-text code" />
<?php wp_nonce_field('yesno'); ?>
<?php echo wp_kses_post( $obj->page_navi_full( $obj ) ); ?>
</div>
</form>
<?php endif; ?>

</div><!-- .inside -->

</div><!-- .postbox -->
</div><!-- #post-body-content -->
</div><!-- #post-body -->

</div><!-- .metabox-holder -->
<?php
				break;


			case 'edit':
				$qid = $r = null;
				if ( isset( $yesno->page['qs']['qid'] ) ) {
					$qid = absint( $yesno->page['qs']['qid'] );
					$obj = new YESNO_Question();
					$r = $obj->get_row( $qid );
					if ( empty( $r ) ) {
						$message = sprintf('<div class="alert alert-warning">%s</div>',  __('Invalid "qid"', 'yesno') );
					}
				}
				else {
					$message = sprintf('<div class="alert alert-warning">%s</div>',  __('"qid" is not specified.', 'yesno') );
				}
?>
<div class="metabox-holder has-right-sidebar">
<div id="post-body">
<div id="post-body-content">
<div class="postbox">

<h3><span><?php _e('Edit', 'yesno'); ?></span></h3>
<div class="inside">
<div id="message">
<?php echo ( $message ) ? wp_kses_post( $message ) : ''; ?>
</div>
<?php if ( ! empty( $r ) ): ?>
<?php $sid = absint( $r['sid'] ); ?>

<script>
jQuery( document ).ready( function( $ ) {
	var sid = <?php echo absint( $r['sid'] ); ?>;
    var list_tab_href = $('.nav-yesno-question-list').attr('href');
    $('.nav-yesno-question-list').attr('href', list_tab_href+'&sid='+sid );
})
</script>
<form class="yesno_customer" method="post"> 

<table class="yesno_form">
<tbody>
<tr>
<th class="fright"><?php _e('Question Set ID', 'yesno'); ?></th>
<td>
<input type="hidden" name="data[<?php echo esc_attr( $qid ); ?>][qid]" value="<?php echo esc_attr( $qid ); ?>" />
<?php echo wp_kses( YESNO_Function::select_set( $sid, sprintf('data[%s][sid]', $qid ) ), array('select'=>array('name'=>array() ), 'option'=>array('value'=>array(), 'selected'=>array() ) ) ); ?></td>
</td>
</tr>

<tr>
<th><?php _e('Question Number', 'yesno'); ?></th>
<td><input type="text" name="data[<?php echo esc_attr( $qid ); ?>][qnum]" value="<?php echo absint( $r['qnum'] ); ?>" size="3" /></td>
</tr>

<tr>
<th><?php _e('Question', 'yesno'); ?></th>
<td><textarea name="data[<?php echo esc_attr( $qid ); ?>][question]" rows="3" cols="50"><?php echo esc_html( $r['question'] ); ?></textarea></td>
</tr>

<tr>
<th><?php _e('Title', 'yesno'); ?></th>
<td class="rtitle"><input type="text" name="data[<?php echo esc_attr( $qid ); ?>][title]" value="<?php echo esc_html( $r['title'] ); ?>" /></td>
</tr>

<tr>
<th><?php _e('Redirect to(Result)', 'yesno'); ?></th>
<td class="redirect_to"><input type="text" name="data[<?php echo esc_attr( $qid ); ?>][url]" value="<?php echo esc_url_raw( $r['url'] ); ?>" /></td>
</tr>

</tbody>
</table>

<hr>
<table class="yesno_form">
<thead>
<tr>
<th class="cid"><?php _e('Choice ID', 'yesno'); ?></th>
<th class="clabel"><?php _e('Label', 'yesno'); ?></th>
<th class="goto"><?php _e('Next question', 'yesno'); ?></th>
</tr>
</thead>

<tbody>
<?php
	$choices = unserialize( $r['choices'] );
	for ( $i = 0; $i < 10; $i++ ) :
		if ( isset( $choices[ $i ] ) ) :
?>
<tr>
<td class="cnum"><?php echo absint( $choices[ $i ]['cnum'] ); ?>
<input type="hidden" name="data[<?php echo esc_attr( $qid ); ?>][choices][<?php echo absint( $i ); ?>][cnum]" value="<?php echo absint( $choices[ $i ]['cnum'] ); ?>" /></th>
<td class="clabel"><input type="text" name="data[<?php echo esc_attr( $qid ); ?>][choices][<?php echo absint( $i ); ?>][label]" value="<?php echo esc_html( $choices[ $i ]['label'] ); ?>" /></th>
<td class="goto"><?php echo wp_kses( self::select_next_question( $choices[ $i ]['goto'], sprintf('data[%d][choices][%d][goto]', $qid, $i ), $sid ), array('select'=>array('name'=>array() ), 'option'=>array('value'=>array(), 'selected'=>array() ) ) ); ?></th>
</tr>
<?php   else : ?>
<tr>
<td class="cnum"><?php echo absint( $i + 1 ); ?>
<input type="hidden" name="data[<?php echo esc_attr( $qid ); ?>][choices][<?php echo absint( $i ); ?>][cnum]" value="<?php echo absint( $i + 1 ); ?>" /></th>
<td class="clabel"><input type="text" name="data[<?php echo esc_attr( $qid ); ?>][choices][<?php echo absint( $i ); ?>][label]" value="" /></th>
<td class="goto"><?php echo wp_kses( self::select_next_question( null, sprintf('data[%d][choices][%d][goto]', $qid, $i  ), $sid ), array('select'=>array('name'=>array() ), 'option'=>array('value'=>array(), 'selected'=>array() ) ) ); ?></th>
</tr>
<?php
		endif;
	endfor;
?>
</tbody>

<tfoot>
<tr><td colspan="2"><hr></td></tr>
<tr>
<th scope="row">&nbsp;</th>
<td>
<input type="hidden" name="action" value="edit" />
<input type="submit" name="save" class="button-primary" value="<?php _e('Update', 'yesno'); ?>" class="large-text code" />
<?php wp_nonce_field('yesno'); ?>
</td>
</tr>
</tfoot>
</table>
</form>
<?php endif; ?>
</div><!-- .inside -->

</div><!-- .postbox -->
</div><!-- #post-body-content -->
</div><!-- #post-body -->

<div class="inner-sidebar">
<?php do_action( YESNO::PLUGIN_ID.'_plugin_info'); ?>
<?php do_action( YESNO::PLUGIN_ID.'_update_info'); ?>
<?php do_action( YESNO::PLUGIN_ID.'_extensions_info'); ?>
</div><!-- .inner-sidebar -->

</div><!-- .metabox-holder -->
<?php
				break;



			case 'addnew' :
				$sid = ( isset( $yesno->page['qs']['sid'] ) ) ? absint( $yesno->page['qs']['sid'] ) : '';
				$args = array(
					'page' => YESNO::PLUGIN_ID.'-question',
					'tab'  => 'list',
					'sid'  => $sid
				);
				$qset_url = add_query_arg( $args, $base_url );
?>
<div class="metabox-holder has-right-sidebar">
<div id="post-body">
<div id="post-body-content">
<div class="postbox">

<h3><span><?php _e('Add new', 'yesno'); ?></span></h3>
<div class="inside">
<div id="message">
<?php if ( $message ) : echo wp_kses_post( $message ); ?>
<?php endif; ?>
</div>
<?php if ( ! isset( $yesno->page['qs']['result'] ) ) : ?>
<script>
jQuery( document ).ready( function( $ ) {
	var sid = <?php echo esc_attr( $sid ); ?>;
    var list_tab_href = $('.nav-yesno-question-list').attr('href');
    $('.nav-yesno-question-list').attr('href', list_tab_href+'&sid='+sid );
})
</script>
<form class="yesno_customer" method="post"> 
<table class="yesno_form">
<tbody>

<tr>
<th><?php _e('Question Set ID', 'yesno'); ?></th>
<td><?php echo wp_kses( YESNO_Function::select_set( $sid, 'new[sid]'), array('select'=>array('name'=>array() ), 'option'=>array('value'=>array(), 'selected'=>array() ) ) ); ?></td>
</tr>

<tr>
<th><?php _e('Question Number', 'yesno'); ?></th>
<td><input type="text" name="new[qnum]" value="<?php echo esc_attr( self::get_next_qnum( $sid ) ); ?>" size="3" />
 <span class="eg"><?php _e('e.g.', 'yesno'); ?> "<span class="point">3</span>" &rarr; "Q<span class="point">3</span>"</span></td>
</tr>

<tr>
<th><?php _e('Question', 'yesno'); ?></th>
<td><textarea name="new[question]" rows="3" cols="50"></textarea></td>
</tr>

<tr>
<th><?php _e('Type', 'yesno'); ?></th>
<td><label><input type="radio" name="opt_type" value="1" checked /> <?php _e('Question', 'yesno'); ?><?php _e('(Branch off)', 'yesno'); ?><br>
<label><input type="radio" name="opt_type" value="0" /> <?php _e('Result', 'yesno'); ?><?php _e('(Don&#39;t branch)', 'yesno'); ?></td>
</tr>

<tr>
<th><?php _e('Title', 'yesno'); ?></th>
<td class="rtitle"><input type="text" name="new[title]" value="" /> 
 <span class="eg"><?php _e('e.g.', 'yesno'); ?> "<span class="point">Q3</span>" &rarr; "<span class="point"><?php _e('About hobbies', 'yesno'); ?></span>" <?php _e('etc.', 'yesno'); ?></span><br>
<p class="description"><?php _e('("Title" is displayed instead of the "Question number")', 'yesno'); ?></p></td>
</tr>

<tr>
<th><?php _e('Redirect to(Result)', 'yesno'); ?></th>
<td class="redirect_to"><input type="text" name="new[url]" value="" /><br>
<p class="description"><?php _e('(Show results on other pages you created)', 'yesno'); ?></p></td>
</tr>
</tbody>

<tfoot>
<tr><td colspan="2"><hr></td></tr>
<tr>
<th>&nbsp;</th>
<td>
<input type="hidden" name="action" value="addnew" />
<input type="submit" name="save" class="button-primary" value="<?php _e('Add new', 'yesno'); ?>" class="large-text code" />
<?php wp_nonce_field('yesno'); ?>
</td>
</tr>
</tfoot>
</table>
</form>
<?php endif; ?>
</div><!-- .inside -->

</div><!-- .postbox -->
</div><!-- #post-body-content -->
</div><!-- #post-body -->

<div class="inner-sidebar">
<?php do_action( YESNO::PLUGIN_ID.'_plugin_info'); ?>
<?php do_action( YESNO::PLUGIN_ID.'_update_info'); ?>
<?php do_action( YESNO::PLUGIN_ID.'_extensions_info'); ?>
</div><!-- .inner-sidebar -->

</div><!-- .metabox-holder -->
<?php
				break;

			case 'setting':
				$options = $plugin_option[ $options_group ];
?>
<div class="metabox-holder has-right-sidebar">
<div id="post-body">
<div id="post-body-content">
<div class="postbox">

<h3><span><?php _e('Settings', 'yesno'); ?></span></h3>
<div class="inside">
<form method="post"> 
<table class="form-table">

<tr valign="top">
<th scope="row"><?php _e('Lines per page', 'yesno'); ?></th>
<td>
<input type="text" name="yesno_options[list_per_page]" id="" value="<?php echo absint( $options['list_per_page'] ); ?>" size="5" />
<p class="description"></p>
</td>
</tr>

<tr><td colspan="2"><hr /></td></tr>
<tr valign="top">
<th scope="row">&nbsp;</th>
<td>
<input type="hidden" name="action" value="setting" />
<?php wp_nonce_field('yesno'); ?>
<input type="submit" name="save" class="button-primary" value="<?php _e('Update', 'yesno'); ?>" class="large-text code" />
<label><input type="checkbox" name="yesno_reset[]" id="" value="<?php echo esc_html( $options_group ); ?>" />
<input type="hidden" name="group" id="" value="<?php echo esc_html( $options_group ); ?>" />
<?php _e('Reset these settings ?', 'yesno'); ?></label>
</td>
</tr>
</table>
</form>
</div><!-- .inside -->

</div><!-- .postbox -->
</div><!-- #post-body-content -->
</div><!-- #post-body -->

<div class="inner-sidebar">
<?php do_action( YESNO::PLUGIN_ID.'_plugin_info'); ?>
<?php do_action( YESNO::PLUGIN_ID.'_update_info'); ?>
<?php do_action( YESNO::PLUGIN_ID.'_extensions_info'); ?>
</div><!-- .inner-sidebar -->

</div><!-- .metabox-holder -->
<?php
			break;
		endswitch;
	}

	/**
	 *	Question set selector (with redirect)
	 */
	public static function select_set_redirect( $current ) {
		/*
		$current = 1, 2 など
		*/
		global $wpdb, $yesno;

		$base_url = get_admin_url().basename( $_SERVER['SCRIPT_NAME'] );
		$url = add_query_arg( $yesno->page['qs'], $base_url );

		$prefix = $wpdb->prefix.YESNO::TABLEPREFIX;
		$table = $prefix.'set';
		$query = "SELECT * FROM {$table} "
				."WHERE 1 ";
		$ret = $wpdb->get_results( $query, ARRAY_A );
		$html = '';
		if ( ! empty( $ret ) ) {
			$html = <<<EOD
<form name="qsetlist" class="qsetlist">
<select name="qset" onChange="if(document.qsetlist.qset.value){location.href=document.qsetlist.qset.value;}">
<option value="">%SELECT%
%OPTION%
</select>
%ADD_Q%
</form>
EOD;
			$option = '';
			foreach ( $ret as $r ) {
				$sid = absint( $r['sid'] );
				$redirect_to = esc_url_raw( add_query_arg('sid', $sid, $url ) );
				$selected = ( $current == $r['sid'] ) ? 'selected' : '';
				$option .= sprintf('<option value="%s" %s >%d: %s'."\n", $redirect_to, $selected, $sid, esc_html( $r['title'] ) );
			}
			$add_q = '';
			if ( ! empty( $current ) ) {
				$add_q = sprintf('&nbsp;<span id="qadd_link"><a href="%s">%s</a></span>'."\n", add_query_arg('tab', 'addnew', $redirect_to ), __('Add Question to this set', 'yesno') );
			}
			$search = array(
				'%SELECT%',
				'%OPTION%',
				'%ADD_Q%'
			);
			$replace = array(
				__('Select any set', 'yesno'),
				$option,
				$add_q
			);
			$html = str_replace( $search, $replace, $html );
		}
		return $html;
	}

	/**
	 *	Question set selector (with redirect)
	 */
	public static function select_next_question( $current, $name, $sid ) {
		/*
		$current = 1, 2 など
		$name    = 'choice[ 1 ][ goto ]
		$sid     = 1, 2 など
		*/
		global $wpdb, $yesno;

		$prefix = $wpdb->prefix.YESNO::TABLEPREFIX;
		$table = $prefix.'question';
		$query = "SELECT * FROM {$table} "
				."WHERE sid=".$sid." "
				."ORDER BY qnum ASC ";
		$questions = $wpdb->get_results( $query, ARRAY_A );
		$html = '';
		if ( ! empty( $questions ) ) {
			$html = <<<EOD
<select name="%NAME%">
<option value="">%SELECT%
%OPTION%
</select>
EOD;
			$option = '';
			foreach ( $questions as $q ) {
				$selected = ( $current == $q['qid'] ) ? 'selected' : '';
				$qtext = strip_tags( htmlspecialchars_decode( $q['question'] ) );
				$qtext = ( mb_strlen( $qtext ) <= 30 ) ? $qtext : mb_substr( $qtext, 0, 30 ).'...';
				$option .= sprintf('<option value="%s" %s >%d: %s'."\n", absint( $q['qid'] ), $selected, absint( $q['qnum'] ), $qtext );
			}
			$search = array(
				'%NAME%',
				'%SELECT%',
				'%OPTION%'
			);
			$replace = array(
				$name,
				__('Select any question', 'yesno'),
				$option
			);
			$html = str_replace( $search, $replace, $html );
		}
		return $html;
	}


}
?>