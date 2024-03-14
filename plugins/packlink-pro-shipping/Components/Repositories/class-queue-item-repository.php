<?php
/**
 * Packlink PRO Shipping WooCommerce Integration.
 *
 * @package Packlink
 */

namespace Packlink\WooCommerce\Components\Repositories;

use Logeecom\Infrastructure\Logger\Logger;
use Logeecom\Infrastructure\ORM\Entity;
use Logeecom\Infrastructure\ORM\Exceptions\QueryFilterInvalidParamException;
use Logeecom\Infrastructure\ORM\Interfaces\QueueItemRepository as QueueItemRepositoryInterface;
use Logeecom\Infrastructure\ORM\QueryFilter\QueryFilter;
use Logeecom\Infrastructure\ORM\Utility\IndexHelper;
use Logeecom\Infrastructure\TaskExecution\Exceptions\QueueItemSaveException;
use Logeecom\Infrastructure\TaskExecution\Interfaces\Priority;
use Logeecom\Infrastructure\TaskExecution\QueueItem;
use Packlink\WooCommerce\Components\Utility\Database;

/**
 * Class Queue_Item_Repository
 *
 * @package Packlink\WooCommerce\Components\Repositories
 * @method QueueItem[] translateToEntities( array $result )
 */
class Queue_Item_Repository extends Base_Repository implements QueueItemRepositoryInterface {

	/**
	 * Returns full class name.
	 *
	 * @noinspection SenselessMethodDuplicationInspection
	 *
	 * @return string Full class name.
	 */
	public static function getClassName() {
		return __CLASS__;
	}

	/**
	 * Finds list of earliest queued queue items per queue. Following list of criteria for searching must be satisfied:
	 *      - Queue must be without already running queue items
	 *      - For one queue only one (oldest queued) item should be returned
	 *
	 * @param int $priority Queue item priority.
	 * @param int $limit Result set limit. By default max 10 earliest queue items will be returned.
	 *
	 * @return QueueItem[] Found queue item list
	 */
	public function findOldestQueuedItems( $priority, $limit = 10 ) {
		if ( Priority::NORMAL !== $priority ) {
			return array();
		}

		$this->table_name = $this->db->prefix . Database::BASE_TABLE;

		/**
		 * Entity object.
		 *
		 * @var Entity $entity
		 */
		$entity    = new $this->entity_class();
		$type      = $this->escape_value( $entity->getConfig()->getType() );
		$index_map = IndexHelper::mapFieldsToIndexes( $entity );

		$status_index     = 'index_' . $index_map['status'];
		$queue_name_index = 'index_' . $index_map['queueName'];

		$running_queues_query = "SELECT $queue_name_index FROM `$this->table_name` q2 WHERE q2.`$status_index` = '"
								. QueueItem::IN_PROGRESS . "' AND q2.`type` = $type";

		$sql = "SELECT queueTable.* 
	            FROM (
	                 SELECT $queue_name_index, MIN(id) AS id
	                 FROM `$this->table_name` AS q
	                 WHERE q.`type` = $type AND q.`$status_index` = '" . QueueItem::QUEUED . "' AND q.`$queue_name_index` NOT IN ($running_queues_query)
	                 GROUP BY `$queue_name_index` LIMIT $limit
	            ) AS queueView  
	            INNER JOIN `$this->table_name` as queueTable
	            ON queueView.id = queueTable.id";

		$result = $this->db->get_results( $sql, ARRAY_A );

		return $this->translateToEntities( $result );
	}

	/**
	 * Creates or updates given queue item. If queue item id is not set, new queue item will be created otherwise update will be performed.
	 *
	 * @param QueueItem $queue_item Item to save.
	 * @param array     $additional_where List of key/value pairs that must be satisfied upon saving queue item.
	 *                                    Key is queue item property and value is condition value for that property.
	 *
	 * @return int Id of saved queue item.
	 * @throws QueueItemSaveException If queue item could not be saved.
	 */
	public function saveWithCondition( QueueItem $queue_item, array $additional_where = array() ) {
		$item_id = null;
		try {
			$queue_item_id = $queue_item->getId();
			if ( null === $queue_item_id || $queue_item_id <= 0 ) {
				$item_id = $this->save( $queue_item );
			} else {
				$this->update_queue_item( $queue_item, $additional_where );
				$item_id = $queue_item_id;
			}
		} catch ( \Exception $exception ) {
			throw new QueueItemSaveException(
				'Failed to save queue item with id: ' . $item_id,
				0,
				$exception
			);
		}

		return $item_id;
	}

	/**
	 * Updates database record with data from provided $queueItem.
	 *
	 * @param QueueItem $queue_item Queue item.
	 * @param array     $conditions Array of update conditions.
	 *
	 * @throws QueueItemSaveException Queue item save exception.
	 * @throws QueryFilterInvalidParamException If filter condition is invalid.
	 */
	private function update_queue_item( QueueItem $queue_item, array $conditions = array() ) {
		$conditions = array_merge( $conditions, array( 'id' => $queue_item->getId() ) );

		$item = $this->select_for_update( $conditions );
		$this->check_if_record_exists( $item );

		if ( null !== $item ) {
			$this->update_with_condition( $queue_item, $conditions );
		}
	}

	/**
	 * Executes select query for update.
	 *
	 * @param array $conditions Array of update conditions.
	 *
	 * @return QueueItem|null First found entity or NULL.
	 * @throws QueryFilterInvalidParamException If filter condition is invalid.
	 */
	private function select_for_update( array $conditions ) {
		$this->table_name = $this->db->prefix . Database::BASE_TABLE;

		/**
		 * Entity object.
		 *
		 * @var Entity $entity
		 */
		$entity          = new $this->entity_class();
		$type            = $entity->getConfig()->getType();
		$field_index_map = IndexHelper::mapFieldsToIndexes( $entity );

		$filter = $this->build_query_filter( $conditions );

		$query  = "SELECT * FROM {$this->table_name} WHERE type = '$type' ";
		$query .= $this->apply_query_filter( $filter, $field_index_map );
		$query .= ' FOR UPDATE';

		$raw_results = $this->db->get_results( $query, ARRAY_A );

		$entities = $this->translateToEntities( $raw_results );

		return ! empty( $entities ) ? $entities[0] : null;
	}

	/**
	 * Builds query filter from conditions array.
	 *
	 * @noinspection PhpDocMissingThrowsInspection
	 *
	 * @param array $conditions Array of conditions.
	 *
	 * @return QueryFilter Query filter object.
	 */
	private function build_query_filter( array $conditions ) {
		$filter = new QueryFilter();
		$filter->setOffset( 0 );
		$filter->setLimit( 1 );
		foreach ( $conditions as $column => $value ) {
			if ( null === $value ) {
				/** @noinspection PhpUnhandledExceptionInspection */
				$filter->where( $column, 'IS NULL' );
			} else {
				/** @noinspection PhpUnhandledExceptionInspection */
				$filter->where( $column, '=', $value );
			}
		}

		return $filter;
	}

	/**
	 * Validates if item exists.
	 *
	 * @param QueueItem $item Queue item.
	 *
	 * @throws QueueItemSaveException Queue item save exception.
	 */
	private function check_if_record_exists( QueueItem $item = null ) {
		if ( null === $item ) {
			$message = 'Failed to save queue item, update condition(s) not met.';
			Logger::logDebug( 'Failed to save queue item, update condition(s) not met.', 'Integration' );

			throw new QueueItemSaveException( $message );
		}
	}

	/**
	 * Updates single record.
	 *
	 * @param QueueItem $item Queue item.
	 * @param array     $conditions List of simple search filters as key-value pair to find records to update.
	 *
	 * @return bool TRUE if operation succeeded; otherwise, FALSE.
	 *
	 * @throws \InvalidArgumentException Invalid argument.
	 */
	private function update_with_condition( QueueItem $item, array $conditions ) {
		$this->table_name = $this->db->prefix . Database::BASE_TABLE;
		$field_index_map  = IndexHelper::mapFieldsToIndexes( $item );
		$prepared         = $this->prepare_entity_for_storage( $item );

		$indexed_conditions = array();
		foreach ( $conditions as $key => $value ) {
			if ( 'id' === $key ) {
				$indexed_conditions[ $key ] = (int) $value;
			} else {
				$indexed_conditions[ 'index_' . $field_index_map[ $key ] ] = IndexHelper::castFieldValue( $value, gettype( $value ) );
			}
		}

		// Only one record should be updated.
		return 1 === $this->db->update( $this->table_name, $prepared, $indexed_conditions );
	}
}
