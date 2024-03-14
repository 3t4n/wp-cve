<?php
// ajax calls
function watu_submit() {	
	require_once(WATU_PATH."/controllers/show_exam.php");
}

function watu_already_rated() {
	update_option('watu_rated', 1, false);
}

function watu_reorder_questions() {
	global $wpdb;
	
	// fill all question IDs in array in the same way they come from the sortable Ajax call
	$qids = [-1];
	$questions = $_POST['questions'] ?? [];
	$exam_id = intval($_POST['exam_id']);

	foreach($questions as $question) {
		$id = intval(str_replace('question-', '', $question));
		$qids[] = intval($id);
	}
	//print_r($qids);
	// find the min sort order for the group
	$min_sort_order = $wpdb->get_var($wpdb->prepare("SELECT MIN(sort_order) FROM ".WATU_QUESTIONS." 
		WHERE exam_id=%d AND ID IN (".implode(',', $qids).")", $exam_id));
	
	// go through the questions and increment the min for each of them
	foreach($qids as $qid) {
		if($qid == -1) continue;
		$wpdb->query($wpdb->prepare("UPDATE ".WATU_QUESTIONS." SET sort_order=%d WHERE ID=%d", $min_sort_order, $qid));
		$min_sort_order++;
	}
}
