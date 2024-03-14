<?php
class Admin_NavViewWtbp extends ViewWtbp {
	public function getBreadcrumbs() {
		$this->assign('breadcrumbsList', DispatcherWtbp::applyFilters('mainBreadcrumbs', $this->getModule()->getBreadcrumbsList()));
		return parent::getContent('adminNavBreadcrumbs');
	}
}
