<?php

namespace Dev4Press\Plugin\GDPOL\Table;

use Dev4Press\Plugin\GDPOL\Basic\Poll;
use Dev4Press\v43\WordPress\Admin\Table;
use Dev4Press\v43\Core\Quick\Sanitize;
use Dev4Press\v43\Core\Plugins\DBLite;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Votes extends Table {
	public $_sanitize_orderby_fields = array(
		'v.vote_id',
		'v.user_id',
		'v.poll_id',
		'v.answer_id',
		'v.voted',
		'l.component',
		'l.logged',
	);
	public $_table_class_name = 'gdpol-grid-votes';
	public $_self_nonce_key = 'gdpol-admin-panel';

	public $polls = array();

	public function __construct( $args = array() ) {
		parent::__construct( array(
			'singular' => 'vote',
			'plural'   => 'votes',
			'ajax'     => false,
		) );
	}

	protected function db() : ?DBLite {
		return gdpol_db();
	}

	protected function process_request_args() {
		$this->_request_args = array(
			'filter-poll-id'   => Sanitize::_get_absint( 'poll' ),
			'filter-user-id'   => Sanitize::_get_absint( 'user' ),
			'filter-answer-id' => Sanitize::_get_absint( 'answer' ),
			'search'           => $this->_get_field( 's' ),
			'orderby'          => $this->_get_field( 'orderby', 'v.vote_id' ),
			'order'            => $this->_get_field( 'order', 'DESC' ),
			'paged'            => $this->_get_field( 'paged' ),
		);
	}

	protected function filter_block_top() {
		$_poll   = $this->get_request_arg( 'filter-poll-id' );
		$_user   = $this->get_request_arg( 'filter-user-id' );
		$_answer = $this->get_request_arg( 'filter-answer-id' );

		$_poll   = $_poll == 0 ? '' : $_poll;
		$_user   = $_user == 0 ? '' : $_user;
		$_answer = $_answer == 0 ? '' : $_answer;

		echo '<div class="alignleft actions">';
		echo '<input name="poll" title="' . __( 'Poll ID', 'gd-topic-polls' ) . '" style="width: 120px;"  type="number" value="' . $_poll . '" placeholder="' . __( 'Poll ID', 'gd-topic-polls' ) . '" />';
		echo '<input name="user" title="' . __( 'User ID', 'gd-topic-polls' ) . '" style="width: 120px;" type="number" value="' . $_user . '" placeholder="' . __( 'User ID', 'gd-topic-polls' ) . '" />';
		echo '<input name="answer" title="' . __( 'Answer ID', 'gd-topic-polls' ) . '" style="width: 120px;" type="number" value="' . $_answer . '" placeholder="' . __( 'Answer ID', 'gd-topic-polls' ) . '" />';
		submit_button( __( 'Filter', 'gd-topic-polls' ), 'button', false, false, array( 'id' => 'gdpol-polls-submit' ) );
		echo '</div>';
	}

	public function rows_per_page() : int {
		$per_page = get_user_meta( get_current_user_id(), 'gdpol_votes_rows_per_page', true );

		if ( empty( $per_page ) || $per_page < 1 ) {
			$per_page = 25;
		}

		return $per_page;
	}

	public function get_columns() : array {
		return array(
			'cb'        => '<input type="checkbox" />',
			'vote_id'   => __( 'ID', 'gd-topic-polls' ),
			'user_id'   => __( 'User', 'gd-topic-polls' ),
			'poll_id'   => __( 'Poll & Topic', 'gd-topic-polls' ),
			'answer_id' => __( 'Response', 'gd-topic-polls' ),
			'voted'     => __( 'Voted', 'gd-topic-polls' ),
		);
	}

	protected function get_sortable_columns() : array {
		return array(
			'vote_id'   => array( 'v.vote_id', false ),
			'user_id'   => array( 'v.user_id', false ),
			'poll_id'   => array( 'v.poll_id', false ),
			'answer_id' => array( 'v.answer_id', false ),
			'voted'     => array( 'v.voted', false ),
		);
	}

	protected function get_bulk_actions() : array {
		return array(
			'delete' => __( 'Delete', 'gd-topic-polls' ),
		);
	}

	protected function column_cb( $item ) : string {
		return sprintf( '<input type="checkbox" name="%1$s[]" value="%2$s" />', $this->_args['singular'], $item->vote_id );
	}

	protected function column_poll_id( $item ) : string {
		$poll = $this->polls[ $item->poll_id ];

		$actions = array(
			'view'  => '<a href="' . $poll->url() . '">' . __( 'View', 'gd-topic-polls' ) . '</a>',
			'edit'  => '<a href="' . $poll->url_edit() . '">' . __( 'Edit', 'gd-topic-polls' ) . '</a>',
			'votes' => '<a href="admin.php?page=gd-topic-polls-votes&poll=' . $poll->id . '">' . __( 'Votes', 'gd-topic-polls' ) . '</a>',
		);

		return '<strong>' . $poll->question . '</strong><br/>' . __( 'for topic', 'gd-topic-polls' ) . ' <em>' . bbp_get_topic_title( $poll->topic_id ) . '</em>' . $this->row_actions( $actions );
	}

	protected function column_answer_id( $item ) : string {
		$poll = $this->polls[ $item->poll_id ];

		$actions = array(
			'votes'  => '<a href="admin.php?page=gd-topic-polls-votes&poll=' . $poll->id . '&answer=' . $item->answer_id . '">' . __( 'Votes', 'gd-topic-polls' ) . '</a>',
			'delete' => '<a class="gdpol-button-delete-vote" href="' . $this->_self( 'single-action=delete&vote=' . $item->vote_id, true ) . '">' . __( 'Delete', 'gd-topic-polls' ) . '</a>',
		);

		return '[' . $item->answer_id . '] ' . $poll->get_answer_by_id( $item->answer_id ) . $this->row_actions( $actions );
	}

	protected function column_user_id( $item ) : string {
		$render = get_avatar( $item->user_email, 40 );
		$render .= '<strong>' . $item->display_name . '</strong><br/>' . $item->user_email;

		return $render;
	}

	protected function column_voted( $item ) : string {
		$timestamp = gdpol()->datetime()->timestamp_gmt_to_local( strtotime( $item->voted ) );

		return date( 'Y.m.d', $timestamp ) . '<br/>@ ' . date( 'H:m:s', $timestamp );
	}

	public function prepare_items() {
		$this->_column_headers = array( $this->get_columns(), array(), $this->get_sortable_columns() );

		$per_page = $this->rows_per_page();

		$sql = array(
			'select' => array(
				'v.*',
				'u.user_login',
				'u.display_name',
				'u.user_email',
			),
			'from'   => array(
				gdpol_db()->votes . ' v',
				'INNER JOIN ' . gdpol_db()->wpdb()->users . ' u ON u.ID = v.user_id',
				'INNER JOIN ' . gdpol_db()->wpdb()->posts . ' p ON p.ID = v.poll_id',
			),
			'where'  => array(
				"p.post_type = '" . gdpol()->post_type_poll() . "'",
			),
		);

		$_sel_poll_id   = $this->get_request_arg( 'filter-poll-id' );
		$_sel_user_id   = $this->get_request_arg( 'filter-user-id' );
		$_sel_answer_id = $this->get_request_arg( 'filter-answer-id' );

		if ( ! empty( $_sel_poll_id ) && $_sel_poll_id > 0 ) {
			$sql['where'][] = 'v.poll_id = ' . $_sel_poll_id;
		}

		if ( ! empty( $_sel_user_id ) && $_sel_user_id > 0 ) {
			$sql['where'][] = 'v.user_id = ' . $_sel_user_id;
		}

		if ( ! empty( $_sel_answer_id ) && $_sel_answer_id > 0 ) {
			$sql['where'][] = 'v.answer_id = ' . $_sel_answer_id;
		}

		$this->query_items( $sql, $per_page );

		foreach ( $this->items as $item ) {
			$_poll_id = $item->poll_id;

			if ( ! isset( $this->polls[ $_poll_id ] ) ) {
				$this->polls[ $_poll_id ] = Poll::load( $_poll_id );
			}
		}
	}
}
