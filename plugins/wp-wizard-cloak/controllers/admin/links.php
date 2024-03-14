<?php 
/**
 * Admin link management page
 * 
 * @author Pavel Kulbakin <p.kulbakin@gmail.com>
 */
class PMLC_Admin_Links extends PMLC_Controller_Admin {
	
	protected function init() {
		if ( ! current_user_can('manage_options')) {
			wp_die(__('You do not have permission to view this page.', 'pmlc_plugin'), __('Error', 'pmlc_plugin'));
		}
	}
	
	public function index() {
		$get = $this->input->get(array(
			's' => '',
			'type' => '',
			'order_by' => 'name',
			'order' => 'ASC',
			'pagenum' => 1,
			'perPage' => 10,
		));
		extract($get);
		$get['pagenum'] = absint($get['pagenum']);
		$this->data += $get;
		
		$list = new PMLC_Link_List();
		switch ($type) {
			case 'preset': $by = array('preset NOT IN' => array('', '_temp'), 'is_trashed' => '0'); break;
			case 'trash': $by = array('is_trashed' => 1); break;
			case 'draft': $by = array('preset' => '_temp', 'is_trashed' => '0'); break;
			case 'expired': $by = array('expire_on <' => date('Y-m-d'), 'expire_on !=' => '0000-00-00', 'is_trashed' => '0'); break;
			default: $by = array('is_trashed' => 0); break;
		}
		if ('' != $s) {
			$like = '%' . preg_replace('%\s+%', '%', preg_replace('/[%?]/', '\\\\$0', $s)) . '%';
			$by[] = array(array('slug LIKE' => $like, 'name LIKE' => $like), 'OR');
		}
		$this->data['list'] = $list->getBy($by, "$order_by $order", $pagenum, $perPage)->convertRecords();
		$this->data['page_links'] = paginate_links( array(
			'base' => add_query_arg('pagenum', '%#%', $this->baseUrl),
			'format' => '',
			'prev_text' => __('&laquo;', 'pmlc_plugin'),
			'next_text' => __('&raquo;', 'pmlc_plugin'),
			'total' => ceil($list->total() / $perPage),
			'current' => $pagenum,
		));
		
		$this->render();
	}
	
	public function edit() {
		// deligate operation to other controller
		$controller = new PMLC_Admin_Edit();
		$controller->index();
	}
	
	/**
	 * Bulk action handler
	 */
	public function bulk() {
		check_admin_referer('bulk-links', '_wpnonce_bulk-links');
		if ($this->input->post('doaction2')) {
			$action = $this->input->post('bulk-action2');
		} else {
			$action = $this->input->post('bulk-action');
		}
		$ids = $this->input->post('links');
		$links = new PMLC_Link_List();
		if (empty($action) or ! in_array($action, array('delete', 'restore')) or empty($ids) or $links->getBy('id', $ids)->isEmpty()) {
			wp_redirect($this->baseUrl);
			die();
		}
		foreach ($links->convertRecords() as $l) {
			switch ($action) {
				case 'delete':
					if ($l->is_trashed) {
						$l->delete();
					} else {
						$l->set('is_trashed', 1)->update();
					}
					break;
				case 'restore':
					$l->set('is_trashed', 0)->update();
					break;
			}
		}
		switch ($action) {
			case 'delete':
				if ('trash' == $this->input->get('type')) {
					$msg = sprintf(_n('%d link deleted permanentry', '%d links deleted permanently', $links->count(), 'pmlc_plugin'), $links->count());
				} else {
					$msg = sprintf(_n('%d link moved to the Trash', '%d links moved to the Trash', $links->count(), 'pmlc_plugin'), $links->count());
				}
				break;
			case 'restore':
				$msg = sprintf(_n('%d link restored from the Trash', '%d links restored from the Trash', $links->count(), 'pmlc_plugin'), $links->count());
				break;
		}
		wp_redirect(add_query_arg('pmlc_nt', urlencode($msg)));
		die();
	}
	
	/**
	 * Restore from trash
	 */
	public function restore() {
		check_admin_referer('restore-link');
		$id = $this->input->get('id');
		$item = new PMLC_Link_Record();
		if ( ! $id or $item->getById($id)->isEmpty()) {
			wp_redirect($this->baseUrl);
			die();
		}
		$item->set('is_trashed', 0)->update();
		wp_redirect(add_query_arg('pmlc_nt', urlencode(__('Link restored from the Trash', 'pmlc_plugin')), $this->baseUrl));
		die();
	}
	
	/**
	 * Delete link
	 */
	public function delete() {
		check_admin_referer('delete-link');
		$id = $this->input->get('id');
		$item = new PMLC_Link_Record();
		if ( ! $id or $item->getById($id)->isEmpty()) {
			wp_redirect($this->baseUrl);
			die();
		}
		if ($item->is_trashed) {
			$item->delete();
			wp_redirect(add_query_arg('pmlc_nt', urlencode(__('Link deleted', 'pmlc_plugin')), $this->baseUrl));
		} else {
			$item->set('is_trashed', 1)->update();
			wp_redirect(add_query_arg('pmlc_nt', urlencode(__('Link moved to the Trash', 'pmlc_plugin')), $this->baseUrl));
		}
		die();
	}
}