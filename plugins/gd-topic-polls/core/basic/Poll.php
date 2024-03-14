<?php

namespace Dev4Press\Plugin\GDPOL\Basic;

use DateTime;
use WP_Error;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Poll {
	public $id = 0;
	public $status = 'disable';
	public $author_id = 0;
	public $topic_id = 0;
	public $topic_title = '';
	public $activity_id = 0;
	public $slug = '';
	public $question = '';
	public $description = '';

	public $responses = array(
		array( 'id' => 1, 'response' => '#1' ),
		array( 'id' => 2, 'response' => '#2' ),
	);

	public $respond = 'all';
	public $choice = 'single';
	public $choice_limit = 2;
	public $close = 'topic';
	public $close_users = 20;
	public $close_datetime = '';
	public $show = 'always';
	public $removal = 'deny';
	public $notify = 'none';

	public $posted = '';
	public $posted_gmt = '';

	public $mode = 'poll';

	public function __construct( $data = array() ) {
		$this->show = gdpol_settings()->get( 'poll_field_show_default' );

		if ( ! empty( $data ) ) {
			foreach ( $data as $key => $val ) {
				$this->$key = $val;
			}
		}

		if ( empty( $this->close_datetime ) ) {
			$this->close_datetime = date( 'Y-m-d H:i:S', time() + 14 * DAY_IN_SECONDS );
		}
	}

	/** @return Poll|WP_Error */
	public static function load( $poll_id ) {
		static $the_polls = array();

		if ( ! isset( $the_polls[ $poll_id ] ) ) {
			$poll = new Poll();

			$post = get_post( $poll_id );

			if ( is_null( $post ) || $post->post_type != gdpol()->post_type_poll() && $post->post_status == 'publish' ) {
				return new WP_Error( 'invalid-poll', __( 'Poll not found', 'gd-topic-polls' ) );
			}

			$poll->id          = $post->ID;
			$poll->author_id   = $post->post_author;
			$poll->topic_id    = $post->post_parent;
			$poll->slug        = $post->post_name;
			$poll->question    = $post->post_title;
			$poll->description = $post->post_content;
			$poll->posted      = $post->post_date;
			$poll->posted_gmt  = $post->post_date_gmt;

			$meta = get_post_meta( $poll_id );

			foreach ( $meta as $key => $data ) {
				if ( substr( $key, 0, 6 ) == '_poll_' ) {
					$real_key = substr( $key, 6 );

					if ( isset( $data[0] ) ) {
						$poll->$real_key = maybe_unserialize( $data[0] );
					}
				}
			}

			$the_polls[ $poll_id ] = $poll;
		}

		$poll = $the_polls[ $poll_id ];

		if ( isset( $_GET['gdpol-poll-results'] ) ) {
			$poll->mode = 'results';
		} else if ( isset( $_GET['gdpol-vote-saved'] ) ) {
			$poll->mode = 'voted';
		} else if ( isset( $_GET['gdpol-invalid-vote'] ) ) {
			$poll->mode = 'invalid';
		} else if ( $poll->is_open() && $poll->has_voted( $poll->user_id() ) ) {
			$poll->mode = 'voter';
		}

		return $poll;
	}

	public function user_id( $user_id = 0 ) : int {
		if ( $user_id == 0 ) {
			return bbp_get_current_user_id();
		}

		return $user_id;
	}

	public function save() {
		if ( $this->question != '' && ! empty( $this->responses ) && $this->topic_id > 0 ) {
			$args = array(
				'ID'             => $this->id,
				'post_content'   => $this->description,
				'post_title'     => $this->question,
				'post_status'    => 'publish',
				'post_type'      => gdpol()->post_type_poll(),
				'comment_status' => 'closed',
				'ping_status'    => 'closed',
				'post_parent'    => $this->topic_id,
				'meta_input'     => $this->_to_meta_input(),
			);

			if ( $this->id == 0 ) {
				$args['post_author'] = $this->author_id;
			}

			$real = wp_insert_post( $args );

			if ( ! is_wp_error( $real ) && is_numeric( $real ) && $real > 0 ) {
				update_post_meta( $this->topic_id, '_bbp_topic_poll_id', $real );

				do_action( 'gdpol_poll_saved', $this, $real );
			}
		}
	}

	public function set_status( $status = 'enable' ) {
		update_post_meta( $this->id, '_poll_status', $status );
	}

	public function show_form( $user_id = 0 ) : bool {
		$user_id = $this->user_id( $user_id );

		if ( $this->is_open() ) {
			if ( $this->is_allowed_to_vote( $user_id ) ) {
				return true;
			}
		}

		return false;
	}

	public function show_choices( $user_id = 0 ) : bool {
		$user_id = $this->user_id( $user_id );

		if ( $this->is_open() ) {
			if ( ! $this->is_allowed_to_vote( $user_id ) ) {
				return true;
			}
		}

		return false;
	}

	public function show_results( $user_id = 0 ) : bool {
		$user_id = $this->user_id( $user_id );

		if ( $this->is_open() ) {
			if ( $this->show == 'vote' ) {
				return $this->has_voted( $user_id );
			} else if ( $this->show == 'closed' ) {
				return false;
			} else if ( $this->show == 'always' ) {
				return $this->mode == 'results' || $this->has_voted( $user_id );
			}
		} else if ( ! $this->is_open() ) {
			return true;
		}

		return false;
	}

	public function is_enabled() : bool {
		return $this->status == 'enable';
	}

	public function is_open() : bool {
		$open = true;

		switch ( $this->close ) {
			case 'closed':
				$open = false;
				break;
			case 'topic':
				$open = bbp_is_topic_open();
				break;
			case 'users':
				$open = $this->count_voters() < $this->close_users;
				break;
			case 'date':
				$now  = gdpol_db()->datetime( false );
				$open = new DateTime( $now ) < new DateTime( $this->close_datetime );
				break;
		}

		return $open;
	}

	public function is_multi_choice() : bool {
		return $this->choice != 'single';
	}

	public function is_allowed_to_vote( $user_id = 0 ) : bool {
		$user_id = $this->user_id( $user_id );

		if ( $user_id > 0 && $this->is_open() ) {
			if ( ! $this->has_voted( $user_id ) ) {
				return true;
			}
		}

		return false;
	}

	public function has_votes() : bool {
		return $this->count_votes() > 0;
	}

	public function has_voted( $user_id = 0 ) : bool {
		$user_id = $this->user_id( $user_id );

		return gdpol_db()->user_voted_in_poll( $this->id, $user_id );
	}

	public function has_replied( $user_id = 0 ) : bool {
		$user_id = $this->user_id( $user_id );

		return gdpol_db()->user_replied_to_topic( $this->topic_id, $user_id );
	}

	public function render_form_fields() {
		$data = array(
			'choice' => $this->choice,
			'limit'  => $this->choice == 'limit' ? absint( $this->choice_limit ) : 0,
		);

		?>

        <script class="gdpol-poll-settings" type="application/json"><?php echo json_encode( $data ); ?></script>

        <input type="hidden" name="gdpol_poll_id" value="<?php echo $this->id; ?>"/>
        <input type="hidden" name="gdpol_topic_id" value="<?php echo $this->topic_id; ?>"/>

		<?php
	}

	public function render_list_choices() {
		?>

        <ul class="gdpol-poll-choices gdpol-poll-disabled">

			<?php

			foreach ( $this->responses as $r ) {

				?>

                <li><span><?php echo $r['response']; ?></span></li>

				<?php

			}

			?>

        </ul>

		<?php
	}

	public function render_form_choices() {
		$type = $this->choice == 'single' ? 'radio' : 'checkbox';
		$name = $this->choice == 'single' ? 'gdpol_choice' : 'gdpol_choice[]';

		switch ( $this->choice ) {
			default:
			case 'single':
				$_legend = __( 'You may choose one response.', 'gd-topic-polls' );
				break;
			case 'multi':
				$_legend = __( 'You may choose one or more responses.', 'gd-topic-polls' );
				break;
			case 'limit':
				$_legend = sprintf( __( 'You may choose up to %s responses.', 'gd-topic-polls' ), $this->choice_limit );
				break;
		}

		?>

        <fieldset>
            <legend><?php echo $_legend; ?></legend>

            <ul class="gdpol-poll-choices">

				<?php

				foreach ( $this->responses as $r ) {

					?>

                    <li>
                        <label
                        ><input type="<?php echo $type; ?>" name="<?php echo $name; ?>" value="<?php echo $r['id']; ?>"
                            /><span><?php echo $r['response']; ?></span></label>
                    </li>

					<?php

				}

				?>

            </ul>
        </fieldset>

		<?php
	}

	public function render_results() {
		$results = $this->results();

		$class = array( 'gdpol-poll-results' );

		?>

        <ul class="<?php echo join( ' ', $class ); ?>">

			<?php

			foreach ( $results['counts'] as $id => $votes ) {
				$label = $results['labels'][ $id ];

				?>

                <li><?php

					gdpol_response_result_info_template(
						$label,
						$votes,
						$results['percent'][ $id ],
						$results['colors'][ $id ],
						$results['width'][ $id ]
					);

					?></li>

				<?php

			}

			?>

        </ul>

		<?php
	}

	public function admin_render_settings() : string {
		$render = '<ul class="gdpol-poll-results">';
		$render .= '<li>' . __( 'Choice', 'gd-topic-polls' ) . ': <strong>' . $this->_form_value_to_label( 'choice' ) . '</strong></li>';

		if ( $this->choice == 'limit' ) {
			$render .= '<li>' . __( 'Choice Limit', 'gd-topic-polls' ) . ': <strong>' . $this->choice_limit . '</strong></li>';
		}

		$render .= '<li>' . __( 'Close', 'gd-topic-polls' ) . ': <strong>' . $this->_form_value_to_label( 'close' ) . '</strong></li>';

		if ( $this->close == 'users' ) {
			$render .= '<li>' . __( 'Close after', 'gd-topic-polls' ) . ': <strong>' . $this->close_users . ' ' . __( 'users', 'gd-topic-polls' ) . '</strong></li>';
		}

		if ( $this->close == 'date' ) {
			$render .= '<li>' . __( 'Close on', 'gd-topic-polls' ) . ': <strong>' . $this->close_datetime . '</strong></li>';
		}

		$render .= '<li>' . __( 'Show Results', 'gd-topic-polls' ) . ': <strong>' . $this->_form_value_to_label( 'show' ) . '</strong></li>';

		$render .= '</ul>';

		return $render;
	}

	public function admin_render_results() : string {
		$results = $this->results();

		$render = '<ul class="gdpol-poll-results">';

		foreach ( $results['labels'] as $id => $label ) {
			$votes = $results['counts'][ $id ];

			$render .= '<li>[' . $id . '] ' . $label;
			$render .= '<span class="_votes"><a href="admin.php?page=gd-topic-polls-votes&poll=' . $this->id . '&answer=' . $id . '">' . $votes . ' ' . _n( 'vote', 'votes', $votes, 'gd-topic-polls' ) . '</a></span>';
			$render .= '<span class="_percent">' . $results['percent'][ $id ] . '%</span></li>';
		}

		$render .= '</ul>';

		return $render;
	}

	public function render_message( $user_id = 0 ) {
		$user_id = $this->user_id( $user_id );

		$msg = '';
		$vtd = false;
		$cde = 'info';

		if ( $this->mode == 'voted' ) {
			$vtd = true;
			$msg = __( 'Thank you for your vote!', 'gd-topic-polls' );
		} else if ( $this->mode == 'invalid' ) {
			$msg = __( 'Invalid vote attempt.', 'gd-topic-polls' );
			$cde = 'error';
		} else if ( $this->is_open() && $this->has_voted( $user_id ) ) {
			$vtd = true;
			$msg = __( 'You have already voted.', 'gd-topic-polls' );
		} else if ( $this->is_open() && ! is_user_logged_in() ) {
			$msg = __( 'You must be logged in to participate.', 'gd-topic-polls' );
			$cde = 'error';
		}

		if ( $this->show == 'closed' && $vtd ) {
			$msg .= ' ' . __( 'Results will be visible after poll closes.', 'gd-topic-polls' );
		}

		if ( $msg != '' ) {
			if ( gdpol()->bbpress()->theme_package == 'quantum' ) {
				echo '<div class="bbp-template-notice ' . $cde . '"><p>' . $msg . '</p></div>';
			} else {
				echo '<div class="gdpol-message gdpol-message-' . $cde . '">' . $msg . '</div>';
			}
		}
	}

	public function actions( $user_id = 0 ) : array {
		$actions = array();

		if ( $this->show == 'always' && $this->is_open() && $this->has_votes() && $this->mode == 'poll' ) {
			$actions['always'] = '<a class="gdpol-action-show" href="' . $this->url( 'gdpol-poll-results' ) . '">' . __( 'View results', 'gd-topic-polls' ) . '</a>';
		}

		if ( $this->is_open() && ! $this->has_voted( $user_id ) && $this->mode == 'results' ) {
			$actions['always'] = '<a class="gdpol-action-show" href="' . $this->url() . '">' . __( 'Back to voting', 'gd-topic-polls' ) . '</a>';
		}

		if ( ! $this->is_open() ) {
			$actions['closed'] = '<span class="gdpol-action-closed">' . __( 'This poll is now closed.', 'gd-topic-polls' ) . '</span>';
		}

		return $actions;
	}

	public function vote( $user_id = 0 ) : bool {
		$user_id = $this->user_id( $user_id );

		if ( $this->is_allowed_to_vote( $user_id ) && $this->is_open() ) {
			$_raws = (array) $_POST['gdpol_choice'];

			$_votes   = array();
			$_allowed = wp_list_pluck( $this->responses, 'id' );

			foreach ( $_raws as $_id ) {
				$_id = absint( $_id );

				if ( $_id > 0 && in_array( $_id, $_allowed ) ) {
					$_votes[] = $_id;
				}
			}

			if ( empty( $_votes ) ) {
				return false;
			} else {
				gdpol_db()->save_vote( $this->id, $user_id, $_votes );

				do_action( 'gdpol_vote_saved', $this, $user_id, $_votes );

				return true;
			}
		}

		return false;
	}

	public function remove_vote( $user_id = 0 ) {
		$user_id = $this->user_id( $user_id );

		if ( $this->has_voted( $user_id ) ) {
			gdpol_db()->remove_vote( $this->id, $user_id );

			do_action( 'gdpol_vote_removed', $this, $user_id );
		}
	}

	public function url_edit() : string {
		return bbp_get_topic_edit_url( $this->topic_id );
	}

	public function url( $action = '', $value = '' ) {
		$_url = get_permalink( $this->topic_id );

		if ( $action != '' ) {
			$_url = add_query_arg( $action, $value, $_url );
		}

		return $_url;
	}

	public function count_voters() {
		return gdpol_db()->count_poll_voters( $this->id );
	}

	public function count_votes() {
		return gdpol_db()->count_poll_votes( $this->id );
	}

	public function count_responses() : int {
		return count( $this->responses );
	}

	public function results() : array {
		$palette = $this->_colors();

		$db = gdpol_db()->poll_votes( $this->id );

		$results = array(
			'labels'  => array(),
			'counts'  => array(),
			'percent' => array(),
			'colors'  => array(),
			'voters'  => array(),
			'total'   => array(
				'votes'  => 0,
				'voters' => count( $db['unique'] ),
			),
		);

		$i = 0;
		foreach ( $this->responses as $r ) {
			$answer = absint( $r['id'] );
			$counts = $db['counts'][ $answer ] ?? 0;
			$voters = $db['voters'][ $answer ] ?? array();

			$results['labels'][ $answer ]  = $r['response'];
			$results['counts'][ $answer ]  = $counts;
			$results['percent'][ $answer ] = 0;
			$results['width'][ $answer ]   = 0;
			$results['voters'][ $answer ]  = $voters;
			$results['colors'][ $answer ]  = $palette[ $i ];

			$results['total']['votes'] += $counts;

			$i ++;

			if ( $i >= count( $palette ) ) {
				$i = 0;
			}
		}

		if ( $results['total']['votes'] > 0 ) {
			$norm = 100 / $results['total']['votes'];

			if ( $this->choice != 'single' && gdpol_settings()->get( 'calculate_multi_method' ) == 'voters' ) {
				$norm = 100 / $results['total']['voters'];
			}

			$maxx = 100;

			foreach ( $this->responses as $r ) {
				$answer = absint( $r['id'] );

				if ( $results['counts'][ $answer ] > 0 ) {
					$results['percent'][ $answer ] = number_format( $results['counts'][ $answer ] * $norm, 2 );
					$hundred                       = 100 / $results['percent'][ $answer ];

					if ( $hundred < $maxx ) {
						$maxx = $hundred;
					}
				} else {
					$results['percent'][ $answer ] = 0;
				}
			}

			foreach ( $this->responses as $r ) {
				$answer = absint( $r['id'] );

				$results['width'][ $answer ] = $results['percent'][ $answer ] * $maxx;
			}

			if ( gdpol_settings()->get( 'sort_results_by_votes' ) ) {
				asort( $results['counts'], SORT_NUMERIC );

				$results['counts'] = array_reverse( $results['counts'], true );
			}
		}

		return $results;
	}

	public function form_data( $name ) : array {
		switch ( $name ) {
			case 'choice':
				return array(
					'single' => __( 'Single choice only', 'gd-topic-polls' ),
					'multi'  => __( 'Unlimited choices', 'gd-topic-polls' ),
					'limit'  => __( 'Limit number of choices', 'gd-topic-polls' ),
				);
			case 'close':
				return array(
					'no'     => __( 'Do not close it', 'gd-topic-polls' ),
					'topic'  => __( 'When the topic is closed', 'gd-topic-polls' ),
					'closed' => __( 'Poll is closed', 'gd-topic-polls' ),
				);
			case 'show':
				return array(
					'always' => __( 'Include button to show results', 'gd-topic-polls' ),
					'vote'   => __( 'Show results after voting', 'gd-topic-polls' ),
					'closed' => __( 'Show results only after poll is closed', 'gd-topic-polls' ),
				);
		}

		return array();
	}

	public function get_answer_by_id( $id, $not_found = '' ) : string {
		foreach ( $this->responses as $r ) {
			if ( $r['id'] == $id ) {
				return $r['response'];
			}
		}

		return $not_found;
	}

	public function get_posted_human() : string {
		$timestamp = strtotime( $this->posted_gmt );

		return human_time_diff( $timestamp );
	}

	public function get_topic_id() : int {
		return $this->topic_id;
	}

	public function get_forum_id() : int {
		return bbp_get_topic_forum_id( $this->topic_id );
	}

	private function _form_value_to_label( $item ) {
		$values = $this->form_data( $item );

		return $values[ $this->$item ] ?? __( 'Unknown', 'gd-topic-polls' );
	}

	private function _colors() : array {
		return apply_filters( 'gdpol_poll_color_palette', array(
			'#c0392b',
			'#27ae60',
			'#2980b9',
			'#8e44ad',
			'#2c3e50',
			'#d35400',
			'#16a085',
			'#3498db',
			'#9b59b6',
			'#e67e22',
			'#7f8c8d',
			'#34495e',
			'#f39c12',
		), $this );
	}

	private function _to_meta_input() : array {
		$keys = array(
			'responses',
			'status',
			'respond',
			'choice',
			'choice_limit',
			'close',
			'close_users',
			'close_datetime',
			'show',
			'removal',
		);

		$meta = array();

		foreach ( $keys as $key ) {
			$meta[ '_poll_' . $key ] = $this->$key;
		}

		return $meta;
	}
}
