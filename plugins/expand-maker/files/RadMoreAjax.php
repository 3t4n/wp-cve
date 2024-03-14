<?php
class RadMoreAjax {
	
	public function init() {
		add_action('wp_ajax_delete_rm', array($this, 'deleteRm'));
		add_action('wp_ajax_yrm_delete_readmores', array($this, 'deleteReadMores'));
		add_action('wp_ajax_yrm_type_delete', array($this, 'typeDelete'));
		add_action('wp_ajax_yrm_switch_status', array($this, 'switchStatus'));
		add_action('wp_ajax_yrm_far_status', array($this, 'farStatus'));
		add_action('wp_ajax_yrm_export', array($this, 'exportData'));
		add_action('wp_ajax_yrm_import_data', array($this, 'importData'));

		// review panel
		add_action('wp_ajax_yrm_dont_show_review_notice', array($this, 'dontShowReview'));
		add_action('wp_ajax_yrm_change_review_show_period', array($this, 'changeReviewPeriod'));

		add_action('wp_ajax_yrm_support', array($this, 'support'));
		add_action('wp_ajax_expander_storeSurveyResult', array($this, 'surveyResult'));

		add_action('wp_ajax_yrm_add_accordion', array($this, 'addAccordion'));
	}

	public function surveyResult() {
		check_ajax_referer('readMoreAjaxNonce', 'token');

		echo 1;
		die;
	}

	public function support() {
		check_ajax_referer('YrmNonce', 'ajaxNonce');

		echo true;
		die();
	}

	public function changeReviewPeriod() {
		check_ajax_referer('YrmNonce', 'ajaxNonce');
		$messageType = sanitize_text_field($_POST['messageType']);

		$timeDate = new DateTime('now');
		$timeDate->modify('+'.YRM_SHOW_REVIEW_PERIOD.' day');

		$timeNow = strtotime($timeDate->format('Y-m-d H:i:s'));
		update_option('YrmShowNextTime', $timeNow);
		$usageDays = get_option('YrmUsageDays');
		$usageDays += YRM_SHOW_REVIEW_PERIOD;
		update_option('YrmUsageDays', $usageDays);

		echo YCD_AJAX_SUCCESS;
		wp_die();
	}

	public function dontShowReview() {
		check_ajax_referer('yrmReviewNotice', 'ajaxNonce');
		update_option('YrmDontShowReviewNotice', 1);

		echo 1;
		wp_die();
	}

	public function importData()
	{

	}


	public function exportData() {

	}
	
	public function deleteRm() {

		check_ajax_referer('YrmNonce', 'ajaxNonce');
		$id  = (int)$_POST['readMoreId'];
		
		$this->deleteById($id);
		
		echo '';
		die();
	}
	
	public function deleteById($id) {
		$dataObj = new ReadMoreData();
		$dataObj->setId($id);
		$dataObj->delete();
		
		do_action('YrmDeleteReadMore', $id);
	}
	
	public function deleteReadMores() {
		check_ajax_referer('YrmNonce', 'ajaxNonce');
		$list = $_POST['idsList'];
		
		if (is_array($list) && !empty($list)) {
			foreach ($list as $currentId) {
				$this->deleteById((int)$currentId);
			}
		}
		
		echo '1';
		wp_die();
	}

	public function typeDelete() {

		check_ajax_referer('YrmNonce', 'ajaxNonce');
		$id  = (int)$_POST['id'];

		$typeObj = ReadMore::createObjByType('far');
		$typeObj->setSavedId($id);
		$typeObj->delete();

		do_action('YrmTypeDeleteReadMore', $id);

		echo '';
		die();
	}

	public function switchStatus() {
		check_ajax_referer('YrmNonce', 'ajaxNonce');
		$postId = (int)$_POST['readMoreId'];
		$status = -1;

		if ($_POST['isChecked'] == 'true') {
			$status = true;
		}
		update_option('yrm-read-more-'.esc_attr($postId), $status);
		wp_die();
	}

	public function farStatus() {
		check_ajax_referer('YrmNonce', 'ajaxNonce');
		$postId = (int)$_POST['id'];
		$status = 0;

		if ($_POST['isChecked'] == 'true') {
			$status = 1;
		}
		global $wpdb;
		$prepare = $wpdb->prepare('UPDATE '.esc_attr($wpdb->prefix).YRM_FIND_TABLE.' SET enable = %s WHERE id=%d', $status, $postId);
		$wpdb->query($prepare);
		echo 1;
		wp_die();
	}

	public function addAccordion() {
		check_ajax_referer('YrmNonce', 'ajaxNonce');
		ob_start();
		$key = (int)$_GET['nextIndex'];
		$tab = array('label' => 'Tab '.($key+1), 'content' => 'Content');
		include(YRM_VIEWS.'/accordion/ItemTemplateWrapper.php');
		$content = ob_get_contents();
		ob_end_clean();

		echo wp_kses($content, ReadMoreAdminHelper::getAllowedTags());
		wp_die();
	}
}