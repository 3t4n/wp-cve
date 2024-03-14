<?php

class ConfigModel {

	public $dbtable;

	public function __construct() {
		 global $wpdb;

		$this->dbtable = $wpdb->prefix . 'tblight_configs';
	}

	public function getItems() {
		global $wpdb;

		$rows = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT * FROM {$this->dbtable}"
			)
		);

		return $rows;
	}

	public function getItemById( $id = 0 ) {
		global $wpdb;

		$row = $wpdb->get_row(
			$wpdb->prepare(
				"SELECT * FROM {$this->dbtable} WHERE id = %d",
				(int) $id
			)
		);

		return $row;
	}

	public function getDefaultData() {
		$row = new stdClass();

		$row->id    = 0;
		$row->title = '';

		return $row;
	}

	public function store( $post_data ) {
		global $wpdb;

		$id    = (int) $post_data['id'];
		$title = $post_data['title'];
		$alias = sanitize_title( $post_data['title'] );

		if ( $id == 1 ) { // general settings
			$row = $wpdb->update(
				$this->dbtable,
				array(
					'title'       => $title,
					'alias'       => $alias,
					'text'        => json_encode( $post_data['configdata'] ),
					'modified_by' => get_current_user_id(),
					'modified'    => current_time( 'Y-m-d H:i:s' ),
				),
				array(
					'id' => $id,
				)
			);
		} elseif ( $id == 2 ) { // price settings
			$row = $wpdb->update(
				$this->dbtable,
				array(
					'title'       => $title,
					'alias'       => $alias,
					'text'        => json_encode( $post_data['configdata'] ),
					'modified_by' => get_current_user_id(),
					'modified'    => current_time( 'Y-m-d H:i:s' ),
				),
				array(
					'id' => $id,
				)
			);
		} elseif ( $id == 3 ) { // map settings
			$row = $wpdb->update(
				$this->dbtable,
				array(
					'title'       => $title,
					'alias'       => $alias,
					'text'        => json_encode( $post_data['configdata'] ),
					'modified_by' => get_current_user_id(),
					'modified'    => current_time( 'Y-m-d H:i:s' ),
				),
				array(
					'id' => $id,
				)
			);
		} elseif ( $id == 4 ) { // base settings
			$row = $wpdb->update(
				$this->dbtable,
				array(
					'title'       => $title,
					'alias'       => $alias,
					'text'        => json_encode( $post_data['configdata'] ),
					'modified_by' => get_current_user_id(),
					'modified'    => current_time( 'Y-m-d H:i:s' ),
				),
				array(
					'id' => $id,
				)
			);
		} elseif ( $id == 5 ) { // orderemail settings
			$post_data['configdata']['header_info']  = $post_data['header_info'];
			$post_data['configdata']['contact_info'] = $post_data['contact_info'];
			$row                                     = $wpdb->update(
				$this->dbtable,
				array(
					'title'       => $title,
					'alias'       => $alias,
					'text'        => json_encode( $post_data['configdata'] ),
					'modified_by' => get_current_user_id(),
					'modified'    => current_time( 'Y-m-d H:i:s' ),
				),
				array(
					'id' => $id,
				)
			);
		} elseif ( $id == 6 ) { // terms settings
			$post_data['configdata']['terms_conditions'] = $post_data['terms_conditions'];
			$row = $wpdb->update(
				$this->dbtable,
				array(
					'title'       => $title,
					'alias'       => $alias,
					'text'        => json_encode( $post_data['configdata'] ),
					'modified_by' => get_current_user_id(),
					'modified'    => current_time( 'Y-m-d H:i:s' ),
				),
				array(
					'id' => $id,
				)
			);
		} elseif ( $id == 7 ) { // design settings
			if ( $post_data['configdata']['show_map_in_popup_only'] == 1 ) {
				$post_data['configdata']['show_map_on_desktop'] = $post_data['configdata']['show_map_on_mobile'] = 0;
			}

			$row = $wpdb->update(
				$this->dbtable,
				array(
					'title'       => $title,
					'alias'       => $alias,
					'text'        => json_encode( $post_data['configdata'] ),
					'modified_by' => get_current_user_id(),
					'modified'    => current_time( 'Y-m-d H:i:s' ),
				),
				array(
					'id' => $id,
				)
			);
		}

		return $id;
	}

	public function delete( $id = 0 ) {
		global $wpdb;

		return $wpdb->delete(
			$this->dbtable,
			array( 'id' => $id ),
			array( '%d' )
		);
	}
}
