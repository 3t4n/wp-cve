<?php
/**
 * Manage project list.
 *
 * @package MPG
 */

// If check class exists or not.
if ( ! class_exists( 'ProjectsListManage' ) ) {

	/**
	 * Declare class `ProjectsListManage`
	 */
	class ProjectsListManage {

		/**
		 * Display form Data
		 *
		 * @param string $search Search string.
		 * @param int    $per_page per page.
		 * @return mix.
		 */
		public function projects_list( $search = '', $per_page = 20 ) {
			global $wpdb;
			$where = '';
			if ( ! empty( $search ) ) {
				$search = preg_replace( '/[^A-Za-z0-9\-]/', '', $search );
				$search = $wpdb->esc_like( $search );
				$where .= " WHERE name LIKE '%$search%'";
			}
			$orderby = 'ORDER BY name DESC';
			$paged   = isset( $_REQUEST['paged'] ) ? max( 0, intval( $_REQUEST['paged'] - 1 ) * $per_page ) : 0;
			if ( isset( $_GET['_mpg_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['_mpg_nonce'] ) ), MPG_BASENAME ) ) {
				if ( ! empty( $_GET['orderby'] ) && ! empty( $_GET['order'] ) ) {
					$get_orderby = sanitize_text_field( wp_unslash( $_GET['orderby'] ) );
					$order       = strtoupper( sanitize_text_field( wp_unslash( $_GET['order'] ) ) );
					if ( in_array( $get_orderby, array( 'name', 'created_at' ), true ) && in_array( $order, array( 'DESC', 'ASC' ), true ) ) {
						$orderby = "ORDER by $get_orderby $order";
					}
				}
			}
			$where     .= sprintf( ' %s LIMIT %d OFFSET %d', $orderby, $per_page, $paged );
			$table_name = $wpdb->prefix . MPG_Constant::MPG_PROJECTS_TABLE;
			// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared, WordPress.DB.PreparedSQL.NotPrepared, WordPress.DB.DirectDatabaseQuery
			$retrieve_data = $wpdb->get_results( "SELECT * FROM $table_name" . $where );
			return $retrieve_data;
		}

		/**
		 * Total Projects
		 *
		 * @return object.
		 */
		public function total_projects() {
			global $wpdb;
			$table_name = $wpdb->prefix . MPG_Constant::MPG_PROJECTS_TABLE;
			$search     = isset( $_GET['s'] ) ? sanitize_text_field( wp_unslash( $_GET['s'] ) ) : '';
			$where      = '';
			if ( ! empty( $search ) ) {
				$search = preg_replace( '/[^A-Za-z0-9\-]/', '', $search );
				$search = $wpdb->esc_like( $search );
				$where .= " WHERE name LIKE '%$search%'";
			}
			$total_projects = $wpdb->get_results( "SELECT COUNT(*) as count FROM $table_name" . $where ); // phpcs:ignore
			$total_projects = reset( $total_projects );
			return (int) $total_projects->count;
		}

		/**
		 * Delete record by id
		 *
		 * @param int $del_id Id.
		 * @return mix.
		 */
		public function delete_project( $del_id ) {
			global $wpdb;
			$table_name = $wpdb->prefix . MPG_Constant::MPG_PROJECTS_TABLE;
			return $wpdb->delete( $table_name, array( 'id' => $del_id ) ); // phpcs:ignore
		}

		/**
		 * Bulk delete
		 *
		 * @param int $ids ids.
		 */
		public function bulk_delete( $ids ) {
			global $wpdb;
			if ( ! empty( $ids ) ) {
				$table_name = $wpdb->prefix . MPG_Constant::MPG_PROJECTS_TABLE;
				$ids        = implode( ',', array_map( 'absint', $ids ) );
				return $wpdb->query( "DELETE FROM $table_name WHERE id IN( $ids )" ); // phpcs:ignore
			}
			return false;
		}
	}
}
