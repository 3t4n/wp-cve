<?php
/** 
 *	Question Set
 */
add_action('plugins_loaded', array('YESNO_Set', 'load') );

class YESNO_Set extends YESNO_Paging {
	public $currenttime = null;
	public $url = null;

	/**
	 *	Load
	 */
	public static function load() {
		add_action('init', array('YESNO_Set', 'init') );
	}

	/** 
	 *	Init
	 */
	public static function init() {
		add_filter('admin_data_list_filter', array('YESNO_Set', 'set_filter') );
		add_filter('admin_data_list_order', array('YESNO_Set', 'set_order') );

		add_filter('yesno_allow_generate', array('YESNO_Set', 'allow_generate') );
	}

	/** 
	 *	CONSTRUCT
	 */
	public function __construct( $atts = array() ) {
		global $yesno, $wpdb;
		extract(
			$atts = shortcode_atts(
				array(
					'limit' 	=> $yesno->options['set']['list_per_page'],
					'order'		=> 'created DESC ',
				),
				$atts
			)
		);

		$prefix = $wpdb->prefix.YESNO::TABLEPREFIX;
		$table = $prefix.'set';
		$this->query = "SELECT %FIELDS% FROM {$table} "
					  ."WHERE 1 ";
		if ( ! empty( $order ) ) {
			$this->query .= "ORDER BY ".$order." ";
		}

		$this->limit = $limit;
		$this->recordmax = $this->record_max( $atts );
		$this->pagemax = ( $this->recordmax ) ? ceil( $this->recordmax / $this->limit ) : 1;
		$this->get_current_page();
	}

	/** 
	 *	Get count of records
	 */
	public function record_max( $atts ){
		global $wpdb;

		$prefix = $wpdb->prefix.YESNO::TABLEPREFIX;
		$table = $prefix.'set';

		$query = strtr( $this->query, array('%FIELDS%' => 'COUNT(*) as c') );
		$ret = $wpdb->get_row( $query, ARRAY_A );
		return $ret['c'];
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
		$table = $prefix.'set';
		$query = "SELECT * FROM {$table} ";
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
			'where' => 'sid='.$id
		);
		return self::get_query( $atts );
	}

	/** 
	 *	Get number of question 
	 */
	public static function get_qcount( $records ){
		global $yesno, $wpdb;

		$numbers = array();
		if ( ! empty( $records ) ) {
			$ids = array();
			foreach ( $records as $r ) {
				$ids[] = $r['sid'];
			}
			$prefix = $wpdb->prefix.YESNO::TABLEPREFIX;
			$table = $prefix.'question';
			$query = "SELECT sid, COUNT(*) as num FROM {$table} "
					."WHERE 1 "
					."GROUP BY sid ";
			$ret = $wpdb->get_results( $query, ARRAY_A );
			if ( ! empty( $ret ) ) {
				foreach ( $ret as $r ) {
					$numbers[ $r['sid'] ] = $r['num'];
				}
			}
		}
		return $numbers;
	}

	/**
	 *  Insert
	 */
	public static function insert( $data ){
		global $wpdb;

		$prefix = $wpdb->prefix.YESNO::TABLEPREFIX;
		$table = $prefix.'set';
		$columns = $wpdb->get_col("DESC {$table}", 0 );

		$data['created'] = date('Y-m-d H:i:s', current_time('timestamp') );

		$keys = $values = array();
		foreach ( $data as $key => $val ) {
			if ( in_array( $key, $columns ) ) {
				$keys[] = sprintf("`%s`", $key );
				$values[] = sprintf("'%s'", $val );
			}
		}
		if ( ! empty( $keys ) ) {
			// Insert on duplicate
			if ( in_array('sid', $keys ) ) {
				$pairs = array();
				foreach ( $keys as $i => $key ) {
					// Skip id
					if ('sid' == $key ) {
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
			$ret = $wpdb->query( $query );
		}
	}
 
	/**
	 *  Update
	 */
	public static function update( $data ){
 		global $wpdb;

		$prefix = $wpdb->prefix.YESNO::TABLEPREFIX;
		$table = $prefix.'set';
		$columns = $wpdb->get_col("DESC {$table}", 0 );

		$pairs = array();
		$where = '';
		foreach ( $data as $key => $val ) {
			if ( in_array( $key, $columns ) ) {
				if ('sid' == $key ) {
					$where = "WHERE `sid`=".$val;
				}
				else {
					$pairs[] = sprintf("`%s`='%s'", $key, $val );
				}
			}
		}
		if ( ! empty( $pairs ) ) {
			$query = "UPDATE {$table} "
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

 		// Delete "SET"
		$prefix = $wpdb->prefix.YESNO::TABLEPREFIX;
		$table = $prefix.'set';
		$query = "DELETE FROM {$table} WHERE `sid`={$id} ";
		$ret = $wpdb->query( $query );

		// Delete "QUESTION"
		$prefix = $wpdb->prefix.YESNO::TABLEPREFIX;
		$table = $prefix.'question';
		$query = "DELETE FROM {$table} WHERE `sid`={$id} ";
		$ret = $wpdb->query( $query );
	}

	/**
	 *	Which roles are not allowed to be generated
	 */
	public static function allow_generate( $args = null ) {
		if ( current_user_can('administrator') ) {
			return true;
		}
		return false;
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
		// URL
		$base_url = get_admin_url().basename( $_SERVER['SCRIPT_NAME'] );
		$url = add_query_arg( $yesno->page['qs'], $base_url );

		$option_header['tabs'] = array(
			'list'     => __('List', 'yesno'),
			'setting'  => __('Settings', 'yesno'),
		);
		if ( empty( $current_tab ) ) {
			$current_tab = $option_header['current_tab'] = 'list';
		}

		// Management
		$options_group = 'set';
		if ('POST' == $_SERVER ['REQUEST_METHOD'] && 'admin.php' == $pagenow ) {

			check_admin_referer('yesno');
			$args = null;
			if ( ! apply_filters('yesno_allow_generate', $args ) ) {
				wp_die( __( 'You are not allowed to this action.', 'yesno' ) );
			}

			if ( isset( $_POST['action'] ) ) {
				$prefix = $wpdb->prefix.YESNO::TABLEPREFIX;
				$table = $prefix;
				$action = preg_replace('/[^a-z]/', '', $_POST['action'] );
				switch ( $action ) {
					case 'list' :
						if ( isset( $_POST['new'] ) && ! empty( $_POST['new']['title'] ) ) {
							$new = array(
								'title' => sanitize_text_field( $_POST['new']['title'] )
							);
							YESNO_Set::insert( $new );
							$message = __('New quesiton set is added.', 'yesno');
						}

						if ( ! empty( $_POST['data'] ) ) {
							foreach ( $_POST['data'] as $i => $input ) {
								$sid = absint( wp_unslash( $input['sid'] ) );
								$title = sanitize_text_field( $input['title'] );
								if ( $sid ) {
									$data = array(
										'sid'   => $sid,
										'title' => ( ! empty( $title ) ) ? $title : ''
									);
									YESNO_Set::update( $data );
								}
							}
							$message = __('Updated.', 'yesno');
						}

						if ( ! empty( $_POST['del'] ) ) {
							foreach ( $_POST['del'] as $id ) {
								if ( absint( wp_unslash( $id ) ) ) {
									YESNO_Set::delete( absint( wp_unslash( $id ) ) );
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
		switch ( $current_tab ) :

			case 'list' :
				$obj = new YESNO_Set();
				$records = $obj->get();
				if ( empty( $records ) ) {
					$message = sprintf('<div class="alert alert-warning">%s</div>', __('No record.', 'yesno') );
				}
				$link_url = add_query_arg( 'page', YESNO::PLUGIN_ID.'-question', $base_url );
				$qadd_url = add_query_arg( 'tab', 'addnew', $link_url );
				$qlist_url = add_query_arg( 'tab', 'list', $link_url );
?>
<div class="metabox-holder">
<div id="post-body">
<div id="post-body-content">
<div class="postbox">

<h3><span><?php _e('Question Set list', 'yesno'); ?></span></h3>
<div class="inside">
<div id="message">
<?php echo ( $message ) ? wp_kses_post( $message ) : ''; ?>
</div>

<form id="admin_data_list" method="post"> 
<div class="submit_and_navi">
<?php if ( $set->recordmax ) : ?>
<input type="submit" name="submit" class="button-primary" value="<?php _e('Update', 'yesno'); ?>" class="large-text code" />
<?php echo wp_kses_post( $obj->page_navi_full( $obj ) ); ?>
<?php endif; ?>
</div>

<table class="wp-list-table widefat admin_data_list">
<thead>
<tr>
<th class="del"><?php _e('DEL', 'yesno'); ?></th>
<th class="sid"><?php _e('Set ID', 'yesno'); ?></th>
<th class="title"><?php _e('Set title', 'yesno'); ?></th>
<th class="shortcode"><?php _e('Shortcode', 'yesno'); ?></th>
<th class="qcount"><?php _e('Questions', 'yesno'); ?></th>
<th class="addq"><?php _e('Add Question', 'yesno'); ?></th>
<th class="created"><?php _e('Created', 'yesno'); ?></th>
</tr>
</thead>

<!-- Add new -->
<tr id="new">
<td class="del"><span class="dashicons dashicons-plus-alt"></span></td>
<td class="sid">-</td>
<td class="title"><input type="text" name="new[title]" value="" class="qset_title" /></td>
<td class="shortcode"><?php if ( $set->recordmax ) _e('(&darr;Copied when focused)', 'yesno'); ?></td>
<td class="qcount">-</td>
<td class="addq">-</td>
<td class="created">-</td>
</tr>

<?php if ( ! empty( $records ) ) : ?>
<tbody id="the-list">
<?php
				$qcount = self::get_qcount( $records );
				foreach( $records as $r ) :
					$sid = absint( $r['sid'] );
					$qadd_link = add_query_arg('sid', $sid, $qadd_url );
					$qlist_link = add_query_arg('sid', $sid, $qlist_url );
					$qc = 0;
					if ( isset( $qcount[ $sid ] ) ) {
						$qc = sprintf('<a href="%s">%s</a>', $qlist_link, $qcount[ $sid ] );
					}
?>
<tr>
<td class="del"><input type="checkbox" name="del[]" value="<?php echo esc_attr( $sid ); ?>" /></td>
<td class="sid"><?php echo esc_attr( $sid ); ?>
<input type="hidden" name="data[<?php echo esc_attr( $sid ); ?>][sid]" value="<?php echo esc_attr( $sid ); ?>" /></td>
<td class="title"><input type="text" name="data[<?php echo esc_attr( $sid ); ?>][title]" value="<?php echo esc_html( $r['title'] ); ?>" class="qset_title" /></td>
<td class="shortcode"><input class="shortcode" type="text" name="dummy" value='<?php printf('[yesno_chart sid="%d"]', absint( $sid ) ); ?>' readonly /></td>
<td class="qcount"><?php echo wp_kses_post( $qc ); ?></td>
<td class="addq"><a href="<?php echo esc_url_raw( $qadd_link ); ?>"><?php _e('Add Question', 'yesno'); ?></a></td>
<td class="created"><?php echo esc_html( $r['created'] ); ?></td>
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

</div><!-- .inside -->

</div><!-- .postbox -->
</div><!-- #post-body-content -->
</div><!-- #post-body -->

</div><!-- .metabox-holder -->
<?php
				@YESNO_Info::get_feed();
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
}
?>
