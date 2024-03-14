<?php
/**
 * Handle the history object.
 *
 * @package     EverAccounting\Models
 * @class       Note
 * @version     1.1.0
 */

namespace EverAccounting\Models;

use EverAccounting\Abstracts\Resource_Model;
use EverAccounting\Repositories;

defined( 'ABSPATH' ) || exit;

/**
 * Class Note
 *
 * @since   1.1.0
 *
 * @package EverAccounting\Models
 */
class Note extends Resource_Model {
	/**
	 * This is the name of this object type.
	 *
	 * @var string
	 */
	protected $object_type = 'note';

	/**
	 * Cache group.
	 *
	 * @since 1.1.0
	 *
	 * @var string
	 */
	public $cache_group = 'ea_notes';

	/**
	 * Item Data array.
	 *
	 * @since 1.1.0
	 *
	 * @var array
	 */
	protected $data = array(
		'parent_id'    => null,
		'type'         => '',
		'note'         => '',
		'extra'        => '',
		'creator_id'   => '',
		'date_created' => null,
	);

	/**
	 * Get the account if ID is passed, otherwise the account is new and empty.
	 *
	 * @since 1.1.0
	 *
	 * @param int|object|Account $data object to read.
	 */
	public function __construct( $data = 0 ) {
		parent::__construct( $data );

		if ( $data instanceof self ) {
			$this->set_id( $data->get_id() );
		} elseif ( is_numeric( $data ) ) {
			$this->set_id( $data );
		} elseif ( ! empty( $data->id ) ) {
			$this->set_id( $data->id );
		} elseif ( is_array( $data ) ) {
			$this->set_props( $data );
		} else {
			$this->set_object_read( true );
		}

		$this->repository = Repositories::load( 'notes' );

		if ( $this->get_id() > 0 ) {
			$this->repository->read( $this );
		}

		$this->required_props = array(
			'parent_id' => __( 'Document ID', 'wp-ever-accounting' ),
			'type'      => __( 'Document type', 'wp-ever-accounting' ),
			'note'      => __( 'Note content', 'wp-ever-accounting' ),
		);
	}
	/**
	|--------------------------------------------------------------------------
	| Getters
	|--------------------------------------------------------------------------
	 */

	/**
	 * Return the id.
	 *
	 * @since  1.1.0
	 *
	 * @param string $context What the value is for. Valid values are 'view' and 'edit'.
	 *
	 * @return string
	 */
	public function get_parent_id( $context = 'edit' ) {
		return $this->get_prop( 'parent_id', $context );
	}

	/**
	 * Return the type of parent
	 *
	 * @since  1.1.0
	 *
	 * @param string $context What the value is for. Valid values are 'view' and 'edit'.
	 *
	 * @return string
	 */
	public function get_type( $context = 'edit' ) {
		return $this->get_prop( 'type', $context );
	}

	/**
	 * Return the note.
	 *
	 * @since  1.1.0
	 *
	 * @param string $context What the value is for. Valid values are 'view' and 'edit'.
	 *
	 * @return string
	 */
	public function get_note( $context = 'edit' ) {
		return $this->get_prop( 'note', $context );
	}

	/**
	 * Return highlight.
	 *
	 * @since  1.1.0
	 *
	 * @param string $context What the value is for. Valid values are 'view' and 'edit'.
	 *
	 * @return string
	 */
	public function get_extra( $context = 'edit' ) {
		return $this->get_prop( 'extra', $context );
	}

	/**
	 * Return creator id.
	 *
	 * @since  1.1.0
	 *
	 * @param string $context What the value is for. Valid values are 'view' and 'edit'.
	 *
	 * @return string
	 */
	public function get_creator_id( $context = 'edit' ) {
		return $this->get_prop( 'creator_id', $context );
	}

	/*
	|--------------------------------------------------------------------------
	| Setters
	|--------------------------------------------------------------------------
	*/

	/**
	 * set the id.
	 *
	 * @since  1.1.0
	 *
	 * @param int $parent_id .
	 */
	public function set_parent_id( $parent_id ) {
		$this->set_prop( 'parent_id', absint( $parent_id ) );
	}

	/**
	 * set the id.
	 *
	 * @since  1.1.0
	 *
	 * @param string $type .
	 */
	public function set_type( $type ) {
		$this->set_prop( 'type', eaccounting_clean( $type ) );
	}

	/**
	 * set the note.
	 *
	 * @since  1.1.0
	 *
	 * @param string $note .
	 */
	public function set_note( $note ) {
		$this->set_prop( 'note', eaccounting_sanitize_textarea( $note ) );
	}

	/**
	 * set the note.
	 *
	 * @since  1.1.0
	 *
	 * @param string $extra .
	 */
	public function set_extra( $extra ) {
		$this->set_prop( 'extra', eaccounting_clean( $extra ) );
	}

	/**
	 * Set object creator id.
	 *
	 * @since 1.0.2
	 *
	 * @param int $creator_id Creator id.
	 */
	public function set_creator_id( $creator_id = null ) {
		$this->set_prop( 'creator_id', absint( $creator_id ) );
	}

}
