<?php

namespace QuadLayers\QLWAPP\Models;

use QuadLayers\QLWAPP\Entities\Contact as Contact_Entity;
use QuadLayers\WP_Orm\Builder\CollectionRepositoryBuilder;

/**
 * Models_Contacts Class
 */
class Contacts {

	protected static $instance;
	protected $repository;

	public function __construct() {
		add_filter( 'sanitize_option_qlwapp_contacts', 'wp_unslash' );
		$models_button = Button::instance();
		$button        = $models_button->get();
		$builder       = ( new CollectionRepositoryBuilder() )
		->setTable( 'qlwapp_contacts' )
		->setEntity( Contact_Entity::class )
		->setDefaultEntities(
			array(
				array(
					'allowDelete' => false,
					'phone'       => qlwapp_format_phone( $button['phone'] ),
					'message'     => qlwapp_replacements_vars( $button['message'] ),
				),
			)
		)
		->setAutoIncrement( true );

		$this->repository = $builder->getRepository();
	}

	public function get_table() {
		return $this->repository->getTable();
	}

	public function get_args() {
		$entity   = new Contact_Entity();
		$defaults = $entity->getDefaults();
		return $defaults;
	}

	public function get( int $id ) {
		$entity = $this->repository->find( $id );
		if ( $entity ) {
			return $entity->getProperties();
		}
	}

	public function delete( int $id ) {
		return $this->repository->delete( $id );
	}

	public function update_all( array $contacts ) {
		foreach ( $contacts as $contact ) {
			if ( isset( $contact['id'] ) ) {
				$this->update( $contact['id'], $contact );
			}
		}
		return true;
	}

	public function update( int $id, array $contact ) {
		$entity = $this->repository->update( $id, $this->sanitize_value_data( $contact ) );
		if ( $entity ) {
			return $entity->getProperties();
		}
	}

	public function create( array $contact ) {
		if ( isset( $contact['id'] ) ) {
			unset( $contact['id'] );
		}

		$entity = $this->repository->create( $this->sanitize_value_data( $contact ) );

		if ( $entity ) {
			return $entity->getProperties();
		}
	}

	public function order_contact( $a, $b ) {

		if ( ! isset( $a['order'] ) || ! isset( $b['order'] ) ) {
			return 0;
		}

		if ( $a['order'] == $b['order'] ) {
			return 0;
		}

		return ( $a['order'] < $b['order'] ) ? -1 : 1;
	}

	// TODO: Delete after frontend refactor
	public function get_contacts_reorder() {
		$contacts = $this->get_contacts();
		uasort( $contacts, array( $this, 'order_contact' ) );
		return $contacts;
	}

	public function get_contacts() {
		return $this->get_all();
	}

	public function get_all() {
		$models_button = Button::instance();
		$button        = $models_button->get();
		$entities      = $this->repository->findAll();

		if ( ! $entities ) {
			return array();
		}

		// TODO: Replace with a default contact from ORM
		// if ( ! $entities ) {
		// $defaults_contacts               = array();
		// $defaults_contacts[0]            = $this->get_args();
		// $defaults_contacts[0]['order']   = 1;
		// $defaults_contacts[0]['message'] = $button['message'];
		// $defaults_contacts[0]['phone']   = qlwapp_format_phone( $button['phone'] );
		// $entity                          = $this->create( $defaults_contacts[0] );
		// $defaults_contacts[0]['id']      = $entity['id'];

		// if ( ! is_admin() ) {
		// $defaults_contacts[0]['message'] = qlwapp_replacements_vars( $defaults_contacts[0]['message'] );
		// }

		// return $defaults_contacts;
		// }

		// error_log( 'entities: ' . json_encode( $entities, JSON_PRETTY_PRINT ) );

		$contacts = array();

		foreach ( $entities as $entity ) {
			$contact = $entity->getProperties();

			if ( ! $contact['phone'] ) {
				$contact['phone'] = qlwapp_format_phone( $button['phone'] );
			}

			if ( ! is_admin() ) {
				$contact['message'] = qlwapp_replacements_vars( $contact['message'] );
			}

			// Add the contact to the array without specifying a key.
			$contacts[] = $contact;
		}

		return $contacts;
	}

	public function delete_all() {
		return $this->repository->deleteAll();
	}

	public function sanitize_value_data( $value_data ) {
		$args = $this->get_args();

		foreach ( $value_data as $key => $value ) {
			if ( array_key_exists( $key, $args ) ) {
				$type = $args[ $key ];

				if ( is_null( $type ) && ! is_numeric( $value ) ) {
					$value_data[ $key ] = intval( $value );
				} elseif ( is_bool( $type ) && ! is_bool( $value ) ) {
					$value_data[ $key ] = ( $value === 'true' || $value === '1' || $value === 1 );
				} elseif ( is_string( $type ) && ! is_string( $value ) ) {
					$value_data[ $key ] = strval( $value );
				} elseif ( is_array( $type ) && ! is_array( $value ) ) {
					$value_data[ $key ] = (array) $type;
				}
			} else {
				unset( $value_data[ $key ] );
			}
		}

		return $value_data;
	}

	public static function instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}
}
