<?php

namespace Dev4Press\Plugin\GDPOL\Basic;

use Dev4Press\v43\Core\Plugins\DB as BaseDB;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * @property string $votes
 */
class DB extends BaseDB {
	protected $plugin_name = 'gd-topic-polls';
	public $_prefix = 'gdpol';
	public $_tables = array( 'votes' );

	public static function instance() : DB {
		static $instance = null;

		if ( ! isset( $instance ) ) {
			$instance = new DB();
		}

		return $instance;
	}

	public function dashboard_votes() {
		$sql = array(
			'select' => array( 'v.*' ),
			'from'   => array(
				gdpol_db()->votes . ' v',
				'INNER JOIN ' . gdpol_db()->wpdb()->posts . ' p ON p.ID = v.poll_id',
			),
			'where'  => array(
				"p.post_type = '" . gdpol()->post_type_poll() . "'",
			),
			'limit'  => '0, 10',
			'order'  => 'v.voted DESC',
		);

		$query = $this->build_query( $sql );

		return $this->get_results( $query );
	}

	public function dashboard_votes_counts() {
		$query = "SELECT count(*) as votes, count(distinct `user_id`) as users FROM " . $this->votes;

		return $this->get_row( $query );
	}

	public function dashboard_polls() : array {
		$sql = array(
			'select' => array( 'p.ID' ),
			'from'   => array(
				gdpol_db()->wpdb()->posts . ' p',
				'INNER JOIN ' . gdpol_db()->wpdb()->posts . ' t ON t.ID = p.post_parent',
			),
			'where'  => array(
				"p.post_type = '" . gdpol()->post_type_poll() . "'",
			),
			'limit'  => '0, 10',
			'order'  => 'p.post_date DESC',
		);

		$query = $this->build_query( $sql );
		$raw   = $this->get_results( $query );

		$polls = array( 'count' => $this->get_found_rows(), 'polls' => array() );

		foreach ( $raw as $poll ) {
			$polls['polls'][] = Poll::load( $poll->ID );
		}

		return $polls;
	}

	public function save_vote( $poll_id, $user_id, $votes ) {
		foreach ( $votes as $answer_id ) {
			$this->insert( $this->votes, array(
				'poll_id'   => $poll_id,
				'user_id'   => $user_id,
				'answer_id' => $answer_id,
				'voted'     => $this->datetime(),
			) );
		}
	}

	public function remove_vote( $poll_id, $user_id ) {
		$sql = $this->wpdb()->prepare(
			"DELETE FROM " . $this->votes . " WHERE `poll_id` = %d AND `user_id` = %d",
			$poll_id, $user_id
		);

		$this->query( $sql );
	}

	public function remove_vote_by_id( $vote_id ) {
		$sql = $this->wpdb()->prepare(
			"DELETE FROM " . $this->votes . " WHERE `vote_id` = %d",
			$vote_id
		);

		$this->query( $sql );
	}

	public function remove_votes_bulk( $ids ) {
		$sql = "DELETE FROM " . $this->votes . " WHERE `vote_id` IN (" . join( ", ", $this->clean_ids_list( $ids ) ) . ")";

		$this->query( $sql );
	}

	public function empty_votes( $poll_id ) {
		$sql = $this->wpdb()->prepare(
			"DELETE FROM " . $this->votes . " WHERE `poll_id` = %d",
			$poll_id
		);

		$this->query( $sql );
	}

	public function count_poll_voters( $poll_id ) : int {
		$sql = $this->wpdb()->prepare(
			"SELECT COUNT(DISTINCT(`user_id`)) FROM " . $this->votes . " WHERE `poll_id` = %d",
			$poll_id
		);

		return absint( $this->get_var( $sql ) );
	}

	public function count_poll_votes( $poll_id ) : int {
		$sql = $this->wpdb()->prepare(
			"SELECT COUNT(*) FROM " . $this->votes . " WHERE `poll_id` = %d",
			$poll_id
		);

		return absint( $this->get_var( $sql ) );
	}

	public function user_voted_in_poll( $poll_id, $user_id = 0 ) : bool {
		if ( $user_id == 0 ) {
			$user_id = bbp_get_current_user_id();
		}

		$sql = $this->wpdb()->prepare(
			"SELECT COUNT(*) FROM " . $this->votes . " WHERE `user_id` = %d AND `poll_id` = %d",
			$user_id, $poll_id
		);

		return $this->get_var( $sql ) > 0;
	}

	public function user_replied_to_topic( $topic_id = 0, $user_id = 0 ) : bool {
		if ( $topic_id == 0 ) {
			$topic_id = bbp_get_topic_id();
		}

		if ( $user_id == 0 ) {
			$user_id = bbp_get_current_user_id();
		}

		$sql = $this->wpdb()->prepare(
			"SELECT COUNT(*) FROM " . $this->wpdb()->posts . " WHERE `post_author` = %d AND 
                ID IN (" . $this->_reply_inner_query( $topic_id ) . ") AND `post_type` = %s",
			$user_id, bbp_get_reply_post_type()
		);

		return $this->get_var( $sql ) > 0;
	}

	public function poll_votes( $poll_id ) : array {
		$sql = $this->wpdb()->prepare(
			"SELECT `user_id`, `answer_id` FROM " . $this->votes . " WHERE `poll_id` = %d",
			$poll_id
		);

		$raw = $this->get_results( $sql );

		$results = array(
			'counts' => array(),
			'voters' => array(),
			'unique' => array(),
		);

		foreach ( $raw as $r ) {
			$user_id   = absint( $r->user_id );
			$answer_id = absint( $r->answer_id );

			if ( ! isset( $results['counts'][ $answer_id ] ) ) {
				$results['counts'][ $answer_id ] = 0;
				$results['voters'][ $answer_id ] = array();
			}

			$results['counts'][ $answer_id ] ++;
			$results['voters'][ $answer_id ][] = $user_id;

			if ( ! in_array( $user_id, $results['unique'] ) ) {
				$results['unique'][] = $user_id;
			}
		}

		return $results;
	}

	private function _reply_inner_query( $topic_id ) : ?string {
		return $this->wpdb()->prepare(
			"SELECT `post_id` FROM " . $this->wpdb()->postmeta . "
            WHERE `meta_key` = '_bbp_topic_id' AND `meta_value` = %s AND `post_id` != %d",
			$topic_id, $topic_id );
	}
}
