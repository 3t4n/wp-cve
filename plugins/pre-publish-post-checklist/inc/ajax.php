<?php
/**
 * Register ajax calls for managing settings and list items
 */

// load in the list item class
require_once('entities/list-item.php');
require_once('entities/list-item-instance.php');

/**
 * Handles settings updates
 *
 * @function pc_ajax_update_settings
 */
function pc_ajax_update_setting() {
	$key      = $_POST['name'];
	$value    = $_POST['value'];
	$response = ( object ) array();

	// stop if invalid key
	if ($key != 'pc_on_publish') {
		http_response_code(400);
		$response->errorMessage = 'Invalid option.';
		echo json_encode($response);
		die();
	}

	// stop if invalid option value
	$value_search = array_search($value, array('stop', 'warn', 'nothing'));
	if (gettype($value_search) === 'boolean' && $value_search == false) {
		http_response_code(400);
		$response->errorMessage = 'Invalid option value.';
		echo json_encode($response);
		die();
	}

	// make valid settings change
	update_option($key, $value);
	http_response_code(200);

	die();
}

add_action('wp_ajax_pc_update_setting', 'pc_ajax_update_setting');

function pc_get_setting() {
	$settingId = $_POST['name'];

	// ensure you only return pc_ settings
	if (strpos($settingId, 'pc_') === 0 && get_option($settingId) !== false) {
		echo json_encode(array(
			'name'    => $settingId,
			'value' => get_option($settingId)
		));
		die();
	} else {
		http_response_code(400);
		die();
	}

	die();
}

add_action('wp_ajax_pc_get_setting', 'pc_get_setting');

/**
 * Handle creation of new item for a list
 *
 * @example POST new_list_item
 * @function pc_create_list_item
 *
 * @param $text String of item description
 */
function pc_create_list_item() {
	$_POST = array_map('stripslashes', $_POST);
	$text = $_POST['description'];
	$item = new ListItem(null, null);
	$item->setText($text);
	$result = $item->save();

	if ($result['status'] === 'success') {
		echo json_encode($result['data']);
	} else {
		echo 'false';
	}

	die();
}

add_action('wp_ajax_pc_create_list_item', 'pc_create_list_item');

/**
 * Handle update of an existing item for a list
 *
 * @example POST new_list_item
 * @function pc_create_list_item
 *
 * @param $text String of item description
 */
function pc_update_list_item() {
	$_POST = array_map('stripslashes', $_POST);
	$id          = $_POST['id'];
	$description = $_POST['description'];
	$item        = new ListItem($id, null);
	$item->setText($description);
	$result = $item->save();

	if ($result['status'] === 'success') {
		echo json_encode($result['data']);
	} else {
		echo 'false';
	}

	die();
}

add_action('wp_ajax_pc_update_list_item', 'pc_update_list_item');

/**
 * Return the items for a give list id. If no list id is provided, the default
 * list items will be returned
 *
 * @function pc_get_list
 */
function pc_get_list() {
	global $wpdb;
	$results = $wpdb->query("SHOW TABLES LIKE 'pc_list_item'");

	if ($results === 0) {
		echo json_encode(array());
	} else {
		$results = $wpdb->get_results('SELECT * FROM pc_list_item');
		echo json_encode($results);
	}

	die();
}

add_action('wp_ajax_pc_get_list', 'pc_get_list');

/**
 * Handler for removing a list item from a list
 *
 * @function pc_delete_list_item
 */
function pc_delete_list_item() {
	$itemId = $_POST['itemId'];

	if ( ! $itemId) {
		http_response_code(400);
		die();
	}

	$item = new ListItem($itemId, null);
	$item->delete();
	die();
}

add_action('wp_ajax_pc_delete_list_item', 'pc_delete_list_item');


/*
 * Return all list items and status for a given list id
 */
function pc_get_list_info_for_page() {
	$pageId = $_POST['pageId'];
	$formattedResults = array();

	new ListItem(null, null); // hack to create a table that should already be there
	new PCPage(null, 1);

	// return all list items
	global $wpdb;
	// TODO - this query is not working. Returning the data for all pages.
	$results = $wpdb->get_results($wpdb->prepare('SELECT * , pc_list_item.id AS list_item_id FROM pc_list_item LEFT JOIN pc_list_item_instance ON pc_list_item.id = pc_list_item_instance.list_item_id AND pc_list_item_instance.page_id = %d', $pageId));

	foreach ($results as $result) {
		array_push($formattedResults, array(
			'id'          => $result->list_item_id, // item id
			'description' => $result->description,
			'listId'      => $result->list_id,
			'instance'    => array(
				'id' => $result->id,
				'pageId' => $result->page_id,
				'status' => $result->status
			)
		));
	}

	echo json_encode($formattedResults);
	die();
}

add_action('wp_ajax_pc_get_list_info_for_page', 'pc_get_list_info_for_page');

/**
 * Handles the marking of list items as complete
 *
 * @function pc_complete_list_item
 */
function pc_complete_list_item() {
	global $wpdb;
	$postId     = $_POST['postId'];
	$listItemId = $_POST['listItemId'];
	$status     = $_POST['status'];

	$item = new PCPage(null, $postId);

	if ($status == "1") {
		$item->markItemComplete($listItemId);
	} else {
		$item->markItemIncomplete($listItemId);
	}

	$result = $wpdb->get_results($wpdb->prepare('SELECT * , pc_list_item.id AS list_item_id FROM pc_list_item LEFT JOIN pc_list_item_instance on pc_list_item.id = pc_list_item_instance.list_item_id WHERE pc_list_item.id = %d', $listItemId));

	if (count($result) > 0) {
		$result = $result[0];
		echo json_encode(array(
			'id'          => $result->list_item_id, // item id
			'description' => $result->description,
			'listId'      => $result->list_id,
			'instance'    => array(
				'id' => $result->id,
				'pageId' => $result->page_id,
				'status' => $result->status
			)
		));
	}

	die();
}

add_action('wp_ajax_pc_complete_list_item', 'pc_complete_list_item');