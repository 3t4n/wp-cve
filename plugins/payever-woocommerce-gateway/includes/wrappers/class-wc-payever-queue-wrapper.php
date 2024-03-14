<?php


class WC_Payever_Queue_Wrapper {

	/**
	 * @param $queue
	 * @param $item_id
	 *
	 * @return mixed
	 */
	public function delete_item( $queue, $item_id ) {
		return $queue->delete_item( $item_id );
	}
}
