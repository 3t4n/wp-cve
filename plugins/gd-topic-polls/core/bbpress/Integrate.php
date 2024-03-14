<?php

namespace Dev4Press\Plugin\GDPOL\bbPress;

use Dev4Press\Plugin\GDBBX\Features\Icons;
use Dev4Press\Plugin\GDPOL\Basic\Poll;
use Dev4Press\v43\Core\Quick\Sanitize;
use Dev4Press\v43\Core\Quick\Str;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Integrate {
	/** @var Poll|null */
	public $post_back_poll = null;
	public $theme_package;

	public function __construct() {
		$this->save_poll();

		$this->theme_package = gdpol()->theme_package;

		add_filter( 'bbp_get_template_stack', array( $this, 'add_plugin_stack' ) );

		add_action( 'bbp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

		add_action( 'bbp_new_topic_pre_extras', array( $this, 'new_topic_prepare' ) );
		add_action( 'bbp_edit_topic_pre_extras', array( $this, 'edit_topic_prepare' ) );

		add_action( 'bbp_new_topic', array( $this, 'new_topic_save' ), 5 );
		add_action( 'bbp_edit_topic', array( $this, 'edit_topic_save' ), 5 );

		if ( gdpol_settings()->get( 'global_auto_embed_poll' ) ) {
			add_action( 'bbp_template_before_single_topic', array( $this, 'show_poll_in_topic' ) );
		}

		if ( gdpol_settings()->get( 'global_auto_embed_form' ) ) {
			add_action( 'bbp_theme_before_topic_form_submit_wrapper', array(
				$this,
				'show_form_in_topic',
			), gdpol_settings()->get( 'global_auto_embed_form_priority' ) );
		}

		if ( gdpol_settings()->get( 'global_auto_embed_icon' ) ) {
			add_action( 'bbp_theme_before_topic_title', array( $this, 'show_poll_icon' ), 11 );
		}
	}

	public function save_poll() {
		if ( is_user_logged_in() ) {
			if ( isset( $_GET['gdpol-submit-vote'] ) ) {
				if ( isset( $_POST['gdpol_poll_id'] ) && isset( $_POST['gdpol_topic_id'] ) && isset( $_POST['gdpol_choice'] ) ) {
					$the_url = remove_query_arg( 'gdpol-submit-vote' );

					$poll_id  = absint( $_POST['gdpol_poll_id'] );
					$topic_id = absint( $_POST['gdpol_topic_id'] );

					$poll   = Poll::load( $poll_id );
					$status = 'gdpol-invalid-vote';

					if ( ! is_wp_error( $poll ) && $poll->topic_id == $topic_id ) {
						if ( $poll->vote() ) {
							$status = 'gdpol-vote-saved';
						}
					}

					$the_url = add_query_arg( $status, '', $the_url );

					wp_redirect( $the_url );
					exit;
				}
			}

			if ( isset( $_GET['gdpol-remove-vote'] ) ) {
				$the_url = remove_query_arg( 'gdpol-remove-vote' );

				$poll_id = absint( $_GET['gdpol-remove-vote'] );

				$poll   = Poll::load( $poll_id );
				$status = 'gdpol-invalid-removal';

				if ( ! is_wp_error( $poll ) ) {
					$poll->remove_vote();

					$status = 'gdpol-vote-removed';
				}

				$the_url = add_query_arg( $status, '', $the_url );

				wp_redirect( $the_url );
				exit;
			}
		}
	}

	public function file( $type, $name, $path, $package = 'default' ) : string {
		$get = GDPOL_URL . 'templates/' . $package . '/' . $path . '/' . $name;

		if ( ! gdpol()->is_debug ) {
			$get .= '.min';
		}

		$get .= '.' . $type;

		return $get;
	}

	public function enqueue_scripts() {
		wp_enqueue_style( 'gdpol-topic-polls' );
		wp_enqueue_script( 'gdpol-topic-polls' );

		wp_localize_script( 'gdpol-topic-polls', 'gdpol_polls_data', array(
			'error_question'  => __( 'Poll question field is mandatory.', 'gd-topic-polls' ),
			'error_responses' => __( 'Poll needs at least two responses.', 'gd-topic-polls' ),
		) );

		do_action( 'gdpol_plugin_enqueue_scripts' );
	}

	public function add_plugin_stack( $stack ) {
		if ( $this->theme_package == 'quantum' ) {
			$stack[] = GDPOL_PATH . 'templates/quantum/bbpress';
		}

		$stack[] = GDPOL_PATH . 'templates/default/bbpress';

		return $stack;
	}

	public function show_poll_icon() {
		if ( gdpol_topic_has_poll() ) {
			echo $this->_poll_icon();
		}
	}

	public function show_poll_in_topic() {
		if ( gdpol_topic_has_poll() ) {
			$_poll_id = absint( gdpol_get_topic_poll_id() );

			gdpol_init_poll( $_poll_id );

			if ( gdpol_get_poll()->is_enabled() ) {
				bbp_get_template_part( 'gdpol-poll', 'content' );
			}
		}
	}

	public function show_form_in_topic() {
		$_forum_id = bbp_get_forum_id();

		if ( $_forum_id > 0 ) {
			if ( in_array( $_forum_id, gdpol_settings()->get( 'global_disable_forums' ) ) ) {
				return;
			}
		}

		if ( bbp_has_errors() && ! is_null( $this->post_back_poll ) ) {
			gdpol()->set_poll( $this->post_back_poll );

			if ( bbp_is_topic_edit() ) {
				bbp_get_template_part( 'gdpol-poll', 'edit' );
			} else {
				bbp_get_template_part( 'gdpol-poll', 'new' );
			}
		} else {
			$_topic_poll_id = 0;

			if ( bbp_is_topic_edit() ) {
				$_topic_poll_id = gdpol_get_topic_poll_id( bbp_get_topic_id() );
			}

			if ( $_topic_poll_id > 0 ) {
				gdpol_init_poll( $_topic_poll_id );

				bbp_get_template_part( 'gdpol-poll', 'edit' );
			} else {
				if ( gdpol_user_can_create_poll() ) {
					gdpol()->empty_poll();

					bbp_get_template_part( 'gdpol-poll', 'new' );
				}
			}
		}
	}

	public function new_topic_prepare() {
		if ( isset( $_POST['gdpol']['poll'] ) ) {
			if ( gdpol_user_can_create_poll() ) {
				$gdpol = $_POST['gdpol'];
				$poll  = $this->_poll_cleanup( $gdpol['poll'] );

				$this->post_back_poll = new Poll( $poll );
			}
		}
	}

	public function edit_topic_prepare() {
		$this->new_topic_prepare();
	}

	public function new_topic_save( $topic_id ) {
		if ( ! is_null( $this->post_back_poll ) ) {
			$obj           = $this->post_back_poll;
			$obj->topic_id = $topic_id;
			$obj->save();
		}
	}

	public function edit_topic_save( $topic_id ) {
		$this->new_topic_save( $topic_id );
	}

	private function _poll_cleanup( $_poll, $topic_id = 0 ) : array {
		$poll = array(
			'id'             => Sanitize::absint( $_poll['id'] ),
			'status'         => isset( $_poll['status'] ) ? Sanitize::key( $_poll['status'] ) : 'disable',
			'author_id'      => bbp_get_current_user_id(),
			'topic_id'       => $topic_id,
			'question'       => Sanitize::basic( $_poll['question'] ),
			'description'    => isset( $_poll['description'] ) ? Sanitize::html( $_poll['description'] ) : '',
			'responses'      => array(),
			'respond'        => 'all',
			'choice'         => Sanitize::key( $_poll['choice'] ),
			'choice_limit'   => Sanitize::absint( $_poll['choice_limit'] ),
			'close'          => Sanitize::key( $_poll['close'] ),
			'close_users'    => 20,
			'close_datetime' => '',
			'show'           => Sanitize::key( $_poll['show'] ),
			'removal'        => 'deny',
			'notify'         => 'none',
		);

		if ( $poll['close'] == 'date' ) {
			$_datetime = Sanitize::basic( $_poll['close_datetime'] );

			if ( Str::is_valid_datetime( $_datetime ) ) {
				$poll['close_datetime'] = $_datetime;
			} else {
				$poll['close'] = 'topic';
			}
		}

		$_is_new    = $poll['id'] == 0;
		$_responses = $_poll['responses'];

		$i = 1;
		foreach ( $_responses as $r ) {
			if ( gdpol_settings()->get( 'poll_field_responses_allow_html' ) ) {
				$response = Sanitize::html( $r['response'] );
			} else {
				$response = Sanitize::basic( $r['response'] );
			}

			if ( ! empty( $response ) ) {
				$poll['responses'][] = array(
					'id'       => $_is_new ? $i : absint( $r['id'] ),
					'response' => $response,
				);
			}

			$i ++;
		}

		return $poll;
	}

	private function _poll_icon() {
		$mode = function_exists( 'gdbbx_obj_icons' ) ? gdbbx_obj_icons()->mode : 'images';
		$mode = function_exists( 'gdbbx_obj_icons' ) ? gdbbx_obj_icons()->mode : ( class_exists( 'Dev4Press\Plugin\GDBBX\Features\Icons' ) ? Icons::instance()->mode() : 'images' );

		$the_icon = '';

		if ( $mode == 'images' ) {
			$the_icon = '<span class="bbp-image-mark-poll" title="' . __( 'Topic with Poll', 'gd-topic-polls' ) . '"></span>';
		} else if ( $mode == 'font' ) {
			$the_icon = '<i class="gdbbx-icon-mark gdbbx-icon gdbbx-icon-bar-chart" title="' . __( 'Topic with Poll', 'gd-topic-polls' ) . '"></i> ';
		}

		return apply_filters( 'gdpol_topic_list_poll_icon', $the_icon, $mode );
	}
}
