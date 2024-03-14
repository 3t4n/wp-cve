<?php
class Admin_NavWtbp extends ModuleWtbp {
	public function getBreadcrumbsList() {
		$res = array(
			array('label' => WTBP_WP_PLUGIN_NAME, 'url' => FrameWtbp::_()->getModule('adminmenu')->getMainLink()),
		);
		// Try to get current tab breadcrumb
		$activeTab = FrameWtbp::_()->getModule('options')->getActiveTab();
		if (!empty($activeTab) && 'main_page' != $activeTab) {
			$tabs = FrameWtbp::_()->getModule('options')->getTabs();
			if (!empty($tabs) && isset($tabs[ $activeTab ])) {
				if (isset($tabs[ $activeTab ]['add_bread']) && !empty($tabs[ $activeTab ]['add_bread'])) {
					if (!is_array($tabs[ $activeTab ]['add_bread'])) {
						$tabs[ $activeTab ]['add_bread'] = array( $tabs[ $activeTab ]['add_bread'] );
					}
					foreach ($tabs[ $activeTab ]['add_bread'] as $addForBread) {
						$res[] = array(
							'label' => $tabs[ $addForBread ]['label'], 'url' => $tabs[ $addForBread ]['url'],
						);
					}
				}
				if ('comparison_edit' == $activeTab) {
					$id = (int) ReqWtbp::getVar('id', 'get');
					if ($id) {
						$tabs[ $activeTab ]['url'] .= '&id=' . $id;
					}
				}
				$res[] = array(
					'label' => $tabs[ $activeTab ]['label'], 'url' => $tabs[ $activeTab ]['url'],
				);
				if ('statistwtbp' == $activeTab) {
					$statTabs = FrameWtbp::_()->getModule('statistwtbp')->getStatTabs();
					$currentStatTab = FrameWtbp::_()->getModule('statistwtbp')->getCurrentStatTab();
					if (isset($statTabs[ $currentStatTab ])) {
						$res[] = array(
							'label' => $statTabs[ $currentStatTab ]['label'], 'url' => $statTabs[ $currentStatTab ]['url'],
						);
					}
				}
			}
		}
		return $res;
	}
}
