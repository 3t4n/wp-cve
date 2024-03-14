<?php

namespace Dev4Press\Plugin\GDPOL\Table;

use Dev4Press\Plugin\GDPOL\Basic\Poll;
use Dev4Press\v43\WordPress\Admin\Table;
use Dev4Press\v43\Core\Plugins\DBLite;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Polls extends Table {
	public $total = 0;
	public $_table_class_name = 'gdpol-grid-polls';
	public $_self_nonce_key = 'gdpol-admin-panel';

	public function __construct( $args = array() ) {
		parent::__construct( array(
			'singular' => 'poll',
			'plural'   => 'polls',
			'ajax'     => false,
		) );
	}

	protected function db() : ?DBLite {
		return gdpol_db();
	}

	protected function process_request_args() {
		$this->_request_args = array(
			'orderby' => $this->_get_field( 'orderby', 'p.ID' ),
			'order'   => $this->_get_field( 'order', 'DESC' ),
			'paged'   => $this->_get_field( 'paged' ),
		);
	}

	public function extra_tablenav( $which ) {

	}

	public function rows_per_page() : int {
		$per_page = get_user_meta( get_current_user_id(), 'gdpol_polls_rows_per_page', true );

		if ( empty( $per_page ) || $per_page < 1 ) {
			$per_page = 10;
		}

		return $per_page;
	}

	public function get_columns() : array {
		return array(
			'id'        => __( 'ID', 'gd-topic-polls' ),
			'question'  => __( 'Question & Description', 'gd-topic-polls' ),
			'topic'     => __( 'Topic', 'gd-topic-polls' ),
			'responses' => __( 'Responses', 'gd-topic-polls' ),
			'settings'  => __( 'Settings', 'gd-topic-polls' ),
			'status'    => __( 'Status', 'gd-topic-polls' ),
			'posted'    => __( 'Posted', 'gd-topic-polls' ),
		);
	}

	protected function get_sortable_columns() : array {
		return array(
			'id'     => array( 'p.ID', false ),
			'posted' => array( 'p.post_date', false ),
		);
	}

	protected function get_row_classes( $item, $classes = array() ) : array {
		$classes = array();

		if ( $item->status == 'disable' ) {
			$classes[] = 'gdpol-poll-is-disabled';
		}

		return $classes;
	}

	/** @param Poll $item */
	protected function column_question( $item ) : string {
		$actions = array(
			'view'  => '<a href="' . $item->url() . '">' . __( 'View', 'gd-topic-polls' ) . '</a>',
			'edit'  => '<a href="' . $item->url_edit() . '">' . __( 'Edit', 'gd-topic-polls' ) . '</a>',
			'votes' => '<a href="admin.php?page=gd-topic-polls-votes&poll=' . $item->id . '">' . __( 'Votes', 'gd-topic-polls' ) . '</a>',
		);

		if ( $item->status == 'enable' ) {
			$actions['disable'] = '<a class="gdpol-button-disable-poll" href="' . $this->_self( 'single-action=disable&poll=' . $item->id, true ) . '">' . __( 'Disable', 'gd-topic-polls' ) . '</a>';
		} else {
			$actions['enable'] = '<a href="' . $this->_self( 'single-action=enable&poll=' . $item->id, true ) . '">' . __( 'Enable', 'gd-topic-polls' ) . '</a>';
		}

		$actions['delete'] = '<a class="gdpol-button-delete-poll" href="' . $this->_self( 'single-action=delete&poll=' . $item->id, true ) . '">' . __( 'Delete', 'gd-topic-polls' ) . '</a>';
		$actions['empty']  = '<a class="gdpol-button-empty-poll" href="' . $this->_self( 'single-action=clear&poll=' . $item->id, true ) . '">' . __( 'Empty', 'gd-topic-polls' ) . '</a>';

		return '<strong>' . $item->question . '</strong><br/><em>' . $item->description . '</em>' . $this->row_actions( $actions );
	}

	/** @param Poll $item */
	protected function column_topic( $item ) : string {
		$forum = bbp_get_topic_forum_title( $item->topic_id );

		return '<strong>' . $item->topic_title . '</strong><br/>' . __( 'in forum', 'gd-topic-polls' ) . ' <em>' . $forum . '</em>';
	}

	/** @param Poll $item */
	protected function column_responses( $item ) : string {
		return $item->admin_render_results();
	}

	/** @param Poll $item */
	protected function column_settings( $item ) : string {
		return $item->admin_render_settings();
	}

	/** @param Poll $item */
	protected function column_status( $item ) : string {
		return $item->status == 'disable' ? '<strong>' . __( 'Disabled', 'gd-topic-polls' ) . '</strong>' : __( 'Enabled', 'gd-topic-polls' );
	}

	/** @param Poll $item */
	protected function column_posted( $item ) : string {
		return mysql2date( 'Y.m.d', $item->posted ) . '<br/>@ ' . mysql2date( 'H:m:s', $item->posted );
	}

	public function prepare_items() {
		$this->prepare_column_headers();

		$per_page = $this->rows_per_page();

		$sql = array(
			'select' => array(
				'p.ID',
				't.post_title as topic_title',
			),
			'from'   => array(
				gdpol_db()->wpdb()->posts . ' p',
				'INNER JOIN ' . gdpol_db()->wpdb()->posts . ' t ON t.ID = p.post_parent',
			),
			'where'  => array(
				"p.post_type = '" . gdpol()->post_type_poll() . "'",
			),
		);

		$this->query_items( $sql, $per_page );

		foreach ( $this->items as &$item ) {
			$poll_id     = $item->ID;
			$topic_title = $item->topic_title;

			$item              = Poll::load( $poll_id );
			$item->topic_title = $topic_title;
		}
	}
}
