<?php

namespace Dev4Press\Plugin\GDPOL\Admin;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class bbPress {
	public function __construct() {
		add_action( 'admin_head-edit.php', array( $this, 'admin_head' ) );

		add_filter( 'bbp_admin_topics_column_headers', array( $this, 'add_columns_topic' ) );
		add_action( 'bbp_admin_topics_column_data', array( $this, 'handle_columns_topic' ), 10, 2 );
	}

	public static function instance() : bbPress {
		static $instance = false;

		if ( $instance === false ) {
			$instance = new bbPress();
		}

		return $instance;
	}

	public function admin_head() { ?>
        <style type="text/css">
            /*<![CDATA[*/
            .wp-list-table.posts th.column-gdpol_poll {
                width: 12%;
            }

            /*]]>*/
        </style><?php
	}

	public function add_columns_topic( $columns ) {
		$columns['gdpol_poll'] = __( 'Poll', 'gd-topic-polls' );

		return $columns;
	}

	public function handle_columns_topic( $column, $topic_id ) {
		if ( $column == 'gdpol_poll' ) {
			if ( gdpol_topic_has_poll( $topic_id ) ) {
				gdpol_init_poll_from_topic( $topic_id );

				echo '<a href="' . admin_url( 'admin.php?page=gd-topic-polls-votes&poll=' . gdpol_get_poll()->id ) . '" title="' . __( 'Show all votes', 'gd-topic-polls' ) . '">' . gdpol_get_poll()->question . '</a><br/>';
				echo __( 'Available answers', 'gd-topic-polls' ) . ': ' . gdpol_get_poll()->count_responses();
			} else {
				echo '&minus;';
			}
		}
	}
}
