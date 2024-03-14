<?php
/**
 * Setup message asking for review.
 *
 * @author   UpStream
 * @category Admin
 * @package  UpStream/Admin
 * @version  1.0.0
 */

// Exit if accessed directly or already defined.
if ( ! defined( 'ABSPATH' ) || class_exists( 'UpStream_Import' ) ) {
	return;
}

/**
 * Class UpStream_Import
 */
class UpStream_Import {

	/**
	 * Option created by
	 *
	 * @var int
	 */
	protected $option_created_by = 1;

	/**
	 * Columns
	 *
	 * @var array
	 */
	protected $columns = array();

	/**
	 * Model manager
	 *
	 * @var object
	 */
	protected $model_manager;

	/**
	 * UpStream_Admin_Import constructor.
	 */
	public function __construct() {
		$this->option_created_by = wp_get_current_user()->ID;
		$this->model_manager     = \UpStream_Model_Manager::get_instance();
		$this->model_manager->loadAll();
	}

	/**
	 * Set project column
	 *
	 * @param int $project_column Project column.
	 */
	public function set_project_column( $project_column ) {
		$this->project_column = $project_column;
	}

	/**
	 * Import file.
	 *
	 * @param string $file File path.
	 * @param int    $line_start Line start.
	 * @return string|null
	 */
	public static function import_file( $file, $line_start ) {
		$error    = '';
		$importer = new UpStream_Import();

		ini_set( 'auto_detect_line_endings', true );
		$handle = fopen( $file, 'r' );

		try {
			$line_no = 0;
			while ( ( $data = fgetcsv( $handle ) ) !== false ) {

				if ( 0 === $line_no || $line_no >= $line_start ) {
					$importer->import_table_line( $data, $line_no );
				}
				$line_no++;
				if ( $line_no >= $line_start + 100 ) {
					break;
				}
			}
		} catch ( \Exception $e ) {
			$error = __( 'Error loading file: line ', 'upstream' ) . ( $line_no + 1 ) . ' ' . $e->getMessage();
		}

		fclose( $handle );
		ini_set( 'auto_detect_line_endings', false );

		return $error;
	}

	/**
	 * Prepare file.
	 *
	 * @param string $file File path.
	 */
	public static function prepare_file( $file ) {
		$message  = '';
		$importer = new UpStream_Import();

		ini_set( 'auto_detect_line_endings', true );
		$handle = fopen( $file, 'r' );

		try {
			$line_no = 0;
			while ( ( $data = fgetcsv( $handle ) ) !== false ) {
				$line_no++;
			}
		} catch ( \Exception $e ) {
			$message = __( 'Error loading file: line ', 'upstream' ) . ( $line_no + 1 ) . ' ' . $e->getMessage();
		}

		fclose( $handle );
		ini_set( 'auto_detect_line_endings', false );

		return array(
			'message' => $message,
			'lines'   => $line_no,
		);
	}

	/**
	 * Clean line.
	 *
	 * @param array $line Line.
	 * @return array
	 */
	protected function clean_line( &$line ) {
		$newline = array();

		foreach ( $line as $l ) {
			$newline[] = trim( $l );
		}

		return $newline;
	}

	/**
	 * Import table line
	 *
	 * @param array $arr Array line.
	 * @param int   $line_no Line no.
	 * @throws UpStream_Import_Exception Exception.
	 */
	protected function import_table_line( &$arr, $line_no ) {
		if ( 0 === $line_no ) {
			$this->load_header( $arr );
		} else {
			$line = $this->clean_line( $arr );

			// load project.
			$project_id = $this->find_item_field( UPSTREAM_ITEM_TYPE_PROJECT, 'id', $line );
			if ( ! $project_id ) {
				$title = $this->find_item_field( UPSTREAM_ITEM_TYPE_PROJECT, 'title', $line );
				if ( $title ) {
					$project_id = $this->find_or_create_item_by_title( UPSTREAM_ITEM_TYPE_PROJECT, $title );
				}
			}

			$project = null;
			if ( $project_id ) {
				try {
					$project = $this->model_manager->getByID( UPSTREAM_ITEM_TYPE_PROJECT, $project_id );
				} catch ( \UpStream_Model_ArgumentException $e ) {
					throw new UpStream_Import_Exception(
						sprintf(
							// translators: %s: Project ID.
							__( 'Project with ID %s does not exist.', 'upstream' ),
							$project_id
						)
					);
				}
			}

			if ( $project ) {
				$this->set_fields( $line, $project );
			}

			// load milestone.
			$milestone_id = $this->find_item_field( UPSTREAM_ITEM_TYPE_MILESTONE, 'id', $line );
			if ( ! $milestone_id ) {
				$title = $this->find_item_field( UPSTREAM_ITEM_TYPE_MILESTONE, 'title', $line );
				if ( $title ) {
					$milestone_id = $this->find_or_create_item_by_title( UPSTREAM_ITEM_TYPE_MILESTONE, $title, $project );
				}
			}

			$milestone = null;
			if ( $milestone_id ) {
				try {
					$milestone = $this->model_manager->getByID( UPSTREAM_ITEM_TYPE_MILESTONE, $milestone_id );
				} catch ( \UpStream_Model_ArgumentException $e ) {
					throw new UpStream_Import_Exception(
						sprintf(
							// translators: %s: Milestone ID.
							__( 'Milestone with ID %s does not exist.', 'upstream' ),
							$milestone_id
						)
					);
				}
			}

			if ( $milestone ) {
				$this->set_fields( $line, $milestone );
			}

			$this->import_children_of_type( UPSTREAM_ITEM_TYPE_TASK, $project, $milestone, $line );
			$this->import_children_of_type( UPSTREAM_ITEM_TYPE_FILE, $project, $milestone, $line );
			$this->import_children_of_type( UPSTREAM_ITEM_TYPE_BUG, $project, $milestone, $line );

		}
	}

	/**
	 * Find child item.
	 *
	 * @param string $type Child type.
	 * @param object $project Project data.
	 * @param int    $item_id Item id.
	 * @return mixed
	 */
	protected function find_child_item( $type, &$project, $item_id ) {
		if ( $project ) {
			$pid = $project->id;

			return $this->model_manager->getByID( $type, $item_id, UPSTREAM_ITEM_TYPE_PROJECT, $project->id );
		}
	}


	/**
	 * Import child of type.
	 *
	 * @param string $type Child type.
	 * @param object $project Project data.
	 * @param object $milestone Milestone data.
	 * @param int    $line Line number.
	 * @throws UpStream_Import_Exception Exception.
	 */
	protected function import_children_of_type( $type, &$project, &$milestone, &$line ) {
		// look for tasks.
		$item_id = $this->find_item_field( $type, 'id', $line );
		if ( ! $item_id ) {
			$title = $this->find_item_field( $type, 'title', $line );
			if ( $title ) {
				$item_id = $this->find_or_create_item_by_title( $type, $title, $project, $milestone );
			}
		}

		if ( $item_id ) {
			try {
				$item = $this->find_child_item( $type, $project, $item_id );
			} catch ( \UpStream_Model_ArgumentException $e ) {
				throw new UpStream_Import_Exception(
					sprintf(
						// translators: %1$s: Item type, %2$s: Item id.
						__( 'Item %1$s with ID %2$s does not exist.', 'upstream' ),
						$type,
						$item_id
					)
				);
			}

			$this->set_fields( $line, $item );
		}

	}

	/**
	 * Find or create item by title.
	 *
	 * @param string $type Child type.
	 * @param string $title Item title.
	 * @param mixed  $project Project data.
	 * @param mixed  $milestone Milestone data.
	 * @return |null
	 */
	protected function find_or_create_item_by_title( $type, $title, $project = null, $milestone = null ) {
		if ( UPSTREAM_ITEM_TYPE_PROJECT === $type ) {

			$matches = $this->model_manager->findAllByCallback(
				function( $item ) use ( $title ) {
					return UPSTREAM_ITEM_TYPE_PROJECT === $item->type && $item->title == $title;
				}
			);

			$obj = null;

			if ( count( $matches ) > 0 ) {
				$obj = $matches[0];
			} else {
				$obj = $this->model_manager->createObject( $type, $title, $this->option_created_by );
				$obj->store();
			}

			return $obj->id;
		}

		if ( ! $project ) {
			return null;
		}

		$matches = $this->model_manager->findAllByCallback(
			function( $item ) use ( $title, $type, $project ) {
				return $item->type === $type && $item->parentId === $project->id && $item->title == $title; // phpcs:ignore
			}
		);

		if ( count( $matches ) > 0 ) {
			$obj = $matches[0];
		} else {
			$obj = $this->model_manager->createObject( $type, $title, $this->option_created_by, $project->id );
		}

		if ( UPSTREAM_ITEM_TYPE_TASK === $type && $milestone ) {
			$obj->milestone = $milestone;
		}

		$obj->store();

		return $obj->id;
	}

	/**
	 * Find item field.
	 *
	 * @param string $type Item type.
	 * @param string $field Field name.
	 * @param array  $line Line.
	 * @return mixed|null
	 */
	protected function find_item_field( $type, $field, &$line ) {
		$column_count = count( $this->columns );

		for ( $i = 0; $i < $column_count; $i++ ) {

			if ( $this->columns[ $i ]->itemType === $type && $this->columns[ $i ]->fieldName === $field ) {
				return $line[ $i ];
			}
		}

		return null;
	}


	/**
	 * Sets the fields of the object based on the fields in the table.
	 *
	 * @param array $line Line data.
	 * @param array $item Item data.
	 * @throws UpStream_Import_Exception Exception.
	 */
	protected function set_fields( &$line, &$item ) {
		$changed = false;

		if ( ! $item ) {
			return;
		}

		$line_count = count( $line );

		for ( $i = 0; $i < $line_count; $i++ ) {

			if ( ! $this->columns[ $i ] ) {
				continue;
			}

			if ( $this->columns[ $i ]->itemType === $item->type ) {
				$val = null;
				try {
					$val = $item->{$this->columns[ $i ]->fieldName};
				} catch ( \UpStream_Model_ArgumentException $e ) {
					throw new UpStream_Import_Exception( 'Error.' );
				}

				if ( $line[ $i ] && $val != $line[ $i ] ) {
					try {
						$item->{$this->columns[ $i ]->fieldName} = htmlentities( iconv( 'cp1252', 'utf-8', trim( $line[ $i ] ) ), ENT_IGNORE, 'UTF-8' );
						$changed                                 = true;
					} catch ( \UpStream_Model_ArgumentException $e ) {
						throw new UpStream_Import_Exception(
							sprintf(
								// translators: %1$s: column number, %2$s: field name.
								__( '(column %1$s, field %2$s)', 'upstream' ),
								$i + 1,
								$this->columns[ $i ]->fieldName
							) . ' ' . $e->getMessage()
						);
					}
				}
			}
		}

		if ( $changed ) {
			$item->store();
		}

		return $changed;
	}


	/**
	 * Load header.
	 *
	 * @param array $header Header.
	 * @throws UpStream_Import_Exception Exception.
	 */
	protected function load_header( &$header ) {
		$header_count = count( $header );

		for ( $i = 0; $i < $header_count; $i++ ) {

			$header[ $i ] = trim( $header[ $i ] );
			$header[ $i ] = trim( $header[ $i ], chr( 239 ) . chr( 187 ) . chr( 191 ) );
			$s            = null;

			if ( $header[ $i ] ) {
				$parts = explode( '.', $header[ $i ] );

				if ( count( $parts ) < 2 ) {
					throw new UpStream_Import_Exception(
						sprintf(
							// translators: %s: column name.
							__( 'Header column %s must be of the form item.field (example: project.title).', 'upstream' ),
							$header[ $i ]
						)
					);
				}

				$item_type  = $parts[0];
				$field_name = $parts[1];

				if ( ! in_array(
					$item_type,
					array(
						UPSTREAM_ITEM_TYPE_PROJECT,
						UPSTREAM_ITEM_TYPE_BUG,
						UPSTREAM_ITEM_TYPE_MILESTONE,
						UPSTREAM_ITEM_TYPE_TASK,
						UPSTREAM_ITEM_TYPE_FILE,
					)
				) ) {
					throw new UpStream_Import_Exception(
						sprintf(
							// translators: %s Item type.
							__( 'Item type %s is not valid.', 'upstream' ),
							$item_type
						)
					);
				}

				// TODO: check if field is valid.
				$s            = new stdClass();
				$s->itemType  = $item_type; // phpcs:ignore
				$s->fieldName = $field_name; // phpcs:ignore
			}

			$this->columns[] = $s;
		}
	}

}

/**
 * Class UpStream_Import_Exception
 */
class UpStream_Import_Exception extends Exception {}
