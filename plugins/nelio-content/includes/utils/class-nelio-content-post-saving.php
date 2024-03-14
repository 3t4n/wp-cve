<?php
/**
 * This file tracks the saving of a post regardless of the scope in which the saving occurs.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/includes/utils
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      2.0.0
 */

defined( 'ABSPATH' ) || exit;

class Nelio_Content_Post_Saving {

	const LATE_PRIORITY = 9999;

	protected static $instance;

	public static function instance() {

		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}//end if

		return self::$instance;

	}//end instance()

	public function init() {
		add_action( 'plugins_loaded', array( $this, 'add_hooks_to_trigger_custom_save_post_action' ) );
		add_action( 'plugins_loaded', array( $this, 'maybe_add_hooks_to_notify_post_followers' ) );
		add_action( 'nelio_content_save_post', array( $this, 'add_default_post_followers' ), 1, 2 );
	}//end init()

	public function add_hooks_to_trigger_custom_save_post_action() {
		$on_regular_post_save = function( $post_id, $post, $update ) {
			if ( defined( 'REST_REQUEST' ) && REST_REQUEST ) {
				return;
			}//end if
			$this->trigger_save_post_action( $post_id, ! $update );
		};

		$on_rest_post_save = function( $post, $request, $creating ) {
			$this->trigger_save_post_action( $post->ID, $creating );
		};

		add_action( 'wp_insert_post', $on_regular_post_save, self::LATE_PRIORITY, 3 );

		$settings   = Nelio_Content_Settings::instance();
		$post_types = $settings->get( 'calendar_post_types', array() );
		foreach ( $post_types as $post_type ) {
			add_action( "rest_after_insert_{$post_type}", $on_rest_post_save, self::LATE_PRIORITY, 4 );
		}//end foreach

	}//end add_hooks_to_trigger_custom_save_post_action()

	public function maybe_add_hooks_to_notify_post_followers() {

		$settings = Nelio_Content_Settings::instance();
		if ( ! $settings->get( 'use_notifications' ) ) {
			return;
		}//end if

		$old_post_values      = array();
		$save_old_post_values = function( $post_id ) use ( &$old_post_values ) {
			if ( isset( $old_post_values[ $post_id ] ) ) {
				return;
			}//end if
			$post_helper                 = Nelio_Content_Post_Helper::instance();
			$old_post_values[ $post_id ] = array(
				'status'    => get_post_status( $post_id ),
				'followers' => $post_helper->get_post_followers( $post_id ),
			);
		};

		$make_sure_we_save_old_post_values = function( $new_status, $old_status, $post ) use ( &$old_post_values ) {
			if ( isset( $old_post_values[ $post->ID ] ) ) {
				return;
			}//end if
			$post_helper                  = Nelio_Content_Post_Helper::instance();
			$old_post_values[ $post->ID ] = array(
				'status'    => $old_status,
				'followers' => $post_helper->get_post_followers( $post->ID ),
			);
		};

		$notify_post_followers = function( $post_id ) use ( &$old_post_values ) {
			$prev_values = array(
				'status'    => 'auto-draft',
				'followers' => array(),
			);

			if ( isset( $old_post_values[ $post_id ] ) ) {
				$prev_values = $old_post_values[ $post_id ];
			}//end if

			$this->notify_post_followers( $post_id, $prev_values );
		};

		add_action( 'pre_post_update', $save_old_post_values, 1 );
		add_action( 'transition_post_status', $make_sure_we_save_old_post_values, 1, 3 );
		add_action( 'nelio_content_save_post', $notify_post_followers, self::LATE_PRIORITY );

	}//end maybe_add_hooks_to_notify_post_followers()

	public function add_default_post_followers( $post_id, $creating ) {

		$default = array();

		if ( $creating ) {
			/**
			 * Filters whether the post creator (i.e. the current user) should be added in the post followers list or not.
			 *
			 * This filter only runs when a post is being created.
			 *
			 * @param boolean $auto_subscribe whether the post creator should be a follower or not. Default: `true`.
			 *
			 * @since 2.0.0
			 */
			if ( apply_filters( 'nelio_content_notification_auto_subscribe_post_creator', true ) ) {
				$user      = wp_get_current_user();
				$default[] = $user ? $user->ID : 0;
			}//end if
		}//end if

		/**
		 * Filters whether the post author should be added in the post followers list or not.
		 *
		 * @param boolean $auto_subscribe whether the post author should be a follower or not. Default: `true`.
		 *
		 * @since 1.4.2
		 */
		if ( apply_filters( 'nelio_content_notification_auto_subscribe_post_author', true ) ) {
			$post      = get_post( $post_id );
			$default[] = ! empty( $post ) ? $post->post_author : 0;
		}//end if

		$default = array_values( array_filter( $default ) );
		if ( empty( $default ) ) {
			return true;
		}//end if

		$post_helper = Nelio_Content_Post_Helper::instance();
		$followers   = $post_helper->get_post_followers( $post_id );

		$new_followers = array_merge( $followers, $default );
		if ( count( $new_followers ) === count( $followers ) ) {
			return;
		}//end if

		$post_helper->save_post_followers( $post_id, $new_followers );

	}//end add_default_post_followers()

	public function trigger_save_post_action( $post_id, $creating ) {

		/**
		 * This action is triggered after a post is saved so that we can notify its followers.
		 *
		 * @param int     $post_id  the post we’ve just saved.
		 * @param boolean $creating `true` when creating a post, `false` when updating.
		 *
		 * @since 2.0.0
		 */
		do_action( 'nelio_content_save_post', $post_id, $creating );

	}//end trigger_save_post_action()

	private function notify_post_followers( $post_id, $prev_values ) {

		$post_helper = Nelio_Content_Post_Helper::instance();

		$post          = get_post( $post_id );
		$followers     = $post_helper->get_post_followers( $post_id );
		$old_status    = $prev_values['status'];
		$old_followers = $prev_values['followers'];

		/**
		 * This action is triggered after a post is saved so that we can notify its followers.
		 *
		 * @param WP_Post $post          the post.
		 * @param array   $followers     list with current post followers.
		 * @param string  $old_status    previous post status (i.e. before the update).
		 * @param array   $old_followers list with previous post followers (i.e. before the update).
		 *
		 * @since 2.0.0
		 */
		do_action( 'nelio_content_notify_post_followers', $post, $followers, $old_status, $old_followers );

	}//end notify_post_followers()

}//end class
