<?php

namespace TotalContestVendors\TotalCore\PostTypes;

/**
 * Post type base class
 * @package TotalContestVendors\TotalCore\PostTypes
 * @since   1.0.0
 */
abstract class PostType {
	/**
	 * Post type base constructor.
	 */
	public function __construct() {
		// Hook into WordPress for post type registration.
		did_action( 'init' ) || doing_action( 'init' ) ? $this->register() : add_action( 'init', [ $this, 'register' ] );
		add_filter( 'post_updated_messages', [ $this, 'registerMessages' ] );
		add_action( 'activated_plugin', 'flush_rewrite_rules', 99 );
	}

	/**
	 * Register post type.
	 *
	 * @return \WP_Error|\WP_Post_Type WP_Post_Type on success, WP_Error otherwise.
	 * @since 1.0.0
	 */
	public function register() {
		/**
		 * @filter totalcore/filters/post-type/args Filter passed arguments to register_post_type
		 * @since  1.0.0
		 */
		return register_post_type( $this->getName(), apply_filters( 'totalcore/filters/post-type/args', $this->getArguments() ) );
	}

	/**
	 * Get CPT name.
	 *
	 * @return string
	 */
	abstract public function getName();

	/**
	 * Get CPT args.
	 *
	 * @return array
	 */
	abstract public function getArguments();

	/**
	 * Register post type messages.
	 *
	 * @param array $messages Messages.
	 *
	 * @return mixed
	 * @since 1.0.0
	 */
	final public function registerMessages( $messages ) {
		global $post;

		// Assign messages to current post type.
		$messages[ $this->getName() ] = (array) $this->getMessages( $post );

		// Return messages array back to WordPress
		return $messages;
	}

	/**
	 * @param \WP_Post $post WordPress post.
	 *
	 * @return array
	 * @since 1.0.0
	 */
	abstract public function getMessages( $post );
}