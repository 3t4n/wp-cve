<?php

namespace Bookit\Classes\Admin;

use Bookit\Classes\Database\Categories;
use Bookit\Helpers\CleanHelper;

class CategoriesController extends DashboardController {

	private static function getCleanRules() {
		return array(
			'id'   => array( 'type' => 'intval' ),
			'name' => array( 'type' => 'strval' ),
		);
	}

	/**
	 * Validate post data
	 */
	public static function validate( $data ) {
		$errors = array();

		if ( ! $data['name'] || ( $data['name'] && strlen( $data['name'] ) < 3 || strlen( $data['name'] ) > 25 ) ) {
			$errors['category_name'] = __( "Category Name can't be empty and must be between 3 and 25 characters long", 'bookit' );
			wp_send_json_error(
				array(
					'errors'  => $errors,
					'message' => __( 'Error occurred!', 'bookit' ),
				)
			);
		}

		if ( ! isset( $data['id'] ) ) {
			$exist_category = Categories::get( 'name', $data['name'] );
			if ( null !== $exist_category ) {
				$errors['category_name'] = __( 'Category with such name already exist', 'bookit' );
				wp_send_json_error(
					array(
						'errors'  => $errors,
						'message' => __( 'Error occurred!', 'bookit' ),
					)
				);
			}
		}
	}

	/**
	 * Save Category
	 */
	public static function save() {
		check_ajax_referer( 'bookit_save_category', 'nonce' );

		if ( ! current_user_can( 'manage_options' ) ) {
			return false;
		}

		$data = CleanHelper::cleanData( $_POST, self::getCleanRules() );
		self::validate( $data );

		if ( ! empty( $data ) ) {
			if ( ! empty( $data['id'] ) ) {
				Categories::update( $data, array( 'id' => $data['id'] ) );
			} else {
				Categories::insert( $data );
				$data['id'] = Categories::insert_id();
			}

			do_action( 'bookit_category_saved', $data['id'] );

			wp_send_json_success(
				array(
					'id'      => $data['id'],
					'message' => __( 'Category Saved!', 'bookit' ),
				)
			);
		}

		wp_send_json_error( array( 'message' => __( 'Error occurred!', 'bookit' ) ) );
	}

	/** Get Categories Assosiated data by id **/
	public static function get_assosiated_total_data_by_id() {
		check_ajax_referer( 'bookit_get_category_assosiated_total_data', 'nonce' );

		if ( ! current_user_can( 'manage_options' ) ) {
			return false;
		}

		$data = CleanHelper::cleanData( $_POST, self::getCleanRules() );

		if ( empty( $data['id'] ) ) {
			wp_send_json_error( array( 'message' => __( 'Error occurred!', 'bookit' ) ) );
		}

		$total = Categories::get_category_total_assosiated_data( $data['id'] );

		$response = array( 'total' => (array) $total );
		wp_send_json_success( $response );
	}

	/**
	 * Delete the Category
	 */
	public static function delete() {
		check_ajax_referer( 'bookit_delete_item', 'nonce' );

		if ( ! current_user_can( 'manage_options' ) ) {
			return false;
		}

		$data = CleanHelper::cleanData( $_GET, self::getCleanRules() );

		if ( isset( $data['id'] ) ) {
			$id = $data['id'];

			Categories::deleteCategory( $id );

			do_action( 'bookit_category_deleted', $id );

			wp_send_json_success( array( 'message' => __( 'Category Deleted!', 'bookit' ) ) );
		}

		wp_send_json_error( array( 'message' => __( 'Error occurred!', 'bookit' ) ) );
	}
}
