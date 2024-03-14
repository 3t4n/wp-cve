<?php

namespace Dev4Press\Plugin\GDPOL\Admin;

use Dev4Press\Plugin\GDPOL\Basic\Poll;
use Dev4Press\v43\Core\Admin\GetBack as BaseGetBack;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class GetBack extends BaseGetBack {
	protected function process() {
		parent::process();

		if ( $this->a()->panel === 'votes' ) {
			if ( $this->is_single_action( 'delete' ) ) {
				$this->vote_delete();
			}

			if ( $this->is_bulk_action() ) {
				$this->vote_delete_bulk();
			}
		}

		if ( $this->a()->panel === 'polls' ) {
			if ( $this->is_single_action( 'disable' ) ) {
				$this->poll_disable();
			}

			if ( $this->is_single_action( 'enable' ) ) {
				$this->poll_enable();
			}

			if ( $this->is_single_action( 'delete' ) ) {
				$this->poll_delete();
			}

			if ( $this->is_single_action( 'clear' ) ) {
				$this->poll_empty();
			}
		}
	}

	private function poll_disable() {
		check_ajax_referer( 'gdpol-admin-panel' );

		$poll_id = isset( $_GET['poll'] ) ? absint( $_GET['poll'] ) : 0;
		$poll    = Poll::load( $poll_id );

		$msg = 'poll-disable-failed';

		if ( ! is_wp_error( $poll ) ) {
			$poll->set_status( 'disable' );
			$msg = 'poll-disable-ok';
		}

		$url = $this->a()->current_url() . '&message=' . $msg;

		wp_redirect( $url );
		exit;
	}

	private function poll_enable() {
		check_ajax_referer( 'gdpol-admin-panel' );

		$poll_id = isset( $_GET['poll'] ) ? absint( $_GET['poll'] ) : 0;
		$poll    = Poll::load( $poll_id );

		$msg = 'poll-enable-failed';

		if ( ! is_wp_error( $poll ) ) {
			$poll->set_status();
			$msg = 'poll-enable-ok';
		}

		$url = $this->a()->current_url() . '&message=' . $msg;

		wp_redirect( $url );
		exit;
	}

	private function poll_delete() {
		check_ajax_referer( 'gdpol-admin-panel' );

		$poll_id = isset( $_GET['poll'] ) ? absint( $_GET['poll'] ) : 0;
		$poll    = Poll::load( $poll_id );

		$msg = 'poll-delete-failed';

		if ( ! is_wp_error( $poll ) ) {
			delete_post_meta( $poll->topic_id, '_bbp_topic_poll_id' );
			wp_delete_post( $poll_id, true );
			gdpol_db()->empty_votes( $poll_id );
			$msg = 'poll-delete-ok';
		}

		$url = $this->a()->current_url() . '&message=' . $msg;

		wp_redirect( $url );
		exit;
	}

	private function poll_empty() {
		check_ajax_referer( 'gdpol-admin-panel' );

		$poll_id = isset( $_GET['poll'] ) ? absint( $_GET['poll'] ) : 0;
		$poll    = Poll::load( $poll_id );

		$msg = 'poll-empty-failed';

		if ( ! is_wp_error( $poll ) ) {
			gdpol_db()->empty_votes( $poll_id );
			$msg = 'poll-empty-ok';
		}

		$url = $this->a()->current_url() . '&message=' . $msg;

		wp_redirect( $url );
		exit;
	}

	private function vote_delete() {
		check_ajax_referer( 'gdpol-admin-panel' );

		$vote_id = isset( $_GET['vote'] ) ? absint( $_GET['vote'] ) : 0;

		$msg = 'vote-delete-failed';

		if ( $vote_id > 0 ) {
			gdpol_db()->remove_vote_by_id( $vote_id );
			$msg = 'vote-delete-ok';
		}

		$url = $this->a()->current_url() . '&message=' . $msg;

		wp_redirect( $url );
		exit;
	}

	public function vote_delete_bulk() {
		check_admin_referer( 'bulk-votes' );

		$action = $this->get_bulk_action();

		$msg = 'vote-delete-failed';

		if ( $action != '' ) {
			$items = isset( $_GET['vote'] ) ? (array) $_GET['vote'] : array();

			if ( ! empty( $items ) ) {
				gdpol_db()->remove_votes_bulk( $items );
				$msg = 'vote-delete-ok';
			}
		}

		$url = $this->a()->current_url() . '&message=' . $msg;

		wp_redirect( $url );
		exit;
	}
}
