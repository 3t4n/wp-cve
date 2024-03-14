<?php 
/**
 * Admin link management page
 * 
 * @author Pavel Kulbakin <p.kulbakin@gmail.com>
 */
class PMLC_Admin_Keywords extends PMLC_Controller_Admin {
	
	protected function init() {
		if ( ! current_user_can('manage_options')) {
			wp_die(__('You do not have permission to view this page.', 'pmlc_plugin'), __('Error', 'pmlc_plugin'));
		}
	}
	
	public function index() {
		$get = $this->input->get(array(
			's' => '',
			'type' => '',
			'order_by' => 'keywords',
			'order' => 'ASC',
			'pagenum' => 1,
			'perPage' => 10,
		));
		extract($get);
		$get['pagenum'] = absint($get['pagenum']);
		$this->data += $get;
		
		$list = new PMLC_Keyword_List();
		switch ($type) {
			case 'trash': $by = array('is_trashed' => 1); break;
			default: $by = array('is_trashed' => 0); break;
		}
		if ('' != $s) {
			$like = '%' . preg_replace('%\s+%', '%', preg_replace('/[%?]/', '\\\\$0', $s)) . '%';
			$by[] = array(array('keywords LIKE' => $like, 'url LIKE' => $like), 'OR');
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
		$this->data['id'] = $id = $this->input->getpost('id');
		$this->data['item'] = $item = new PMLC_Keyword_Record();
		if ($id and $item->getById($id)->isEmpty()) { // ID corresponds to no link
			wp_redirect($this->baseUrl);
			die();
		}
		// read/set record properties
		if ( ! $id) {
			$item->set(array(
				'keywords' => '',
				'url' => '',
				'replace_limit' => 0,
				'match_case' => 0,
				'rel_nofollow' => 0,
				'target_blank' => 0,
				'post_id_param' => '',
			));
		}
		$this->data['pass_post_id'] = $pass_post_id = $this->input->post('pass_post_id', '' != $item->post_id_param);
		$this->data['post'] = $post = $this->input->post(array(
			'url' => $item->url,
			'replace_limit' => $item->replace_limit,
			'match_case' => $item->match_case,
			'rel_nofollow' => $item->rel_nofollow,
			'target_blank' => $item->target_blank,
			'post_id_param' => $pass_post_id ? $item->post_id_param : '',
		));
		$this->data['post']['keywords'] = $post['keywords'] = implode(', ', array_filter($this->input->post('keywords', explode(', ', $item->keywords))));
		if ($this->input->post('is_unlimited')) {
			$this->data['post']['replace_limit'] = $post['replace_limit'] = 0;
		}
		
		if ($this->input->post('is_submitted')) {
			check_admin_referer('edit-keyword', '_wpnonce_edit-keyword');
			
			// validate
			if (empty($post['keywords'])) {
				$this->errors->add('form-validation', __('At least one keyword must be set', 'pmlc_plugin'));
			} else {
				$keywords = array(); $is_word_error = FALSE;
				foreach (preg_split('%,\s*%', $post['keywords']) as $word) {
					$word = trim($word);
					if ('' != $word) {
						$check = new PMLC_Keyword_Record();
						$regexp = '(^|, )' . str_replace('\\', '\\\\', preg_quote($word)) . '(, |$)';
						$check_by = array();
						if ($id) {
							$check_by = array('id !=' => $id);
						}
						if ($post['match_case']) {
							$check_by[] = array(
								array(
									array(
										array(
											'keywords REGEXP' => $regexp,
											'match_case' => 0
										), 'AND'
									),
									array(
										array(
											'keywords REGEXP BINARY' => $regexp,
											'match_case' => 1
										), 'AND'
									),
								), 'OR'
							);
						} else {
							$check_by['keywords REGEXP'] = $regexp;
						}
						if ( ! $check->getBy($check_by)->isEmpty()) {
							$this->errors->add('form-validation', sprintf(__('Link for `%s` keyword already defined', 'pmlc_plugin'), $word));
							$is_word_error = TRUE;
						} else {
							$keywords[] = $word;
						}
					}
				}
				$post['keywords'] = implode(', ', $keywords);
			}
			if (empty($post['url'])) {
				$this->errors->add('form-validation', __('Link To URL must be set', 'pmlc_plugin'));
			} else if ( ! preg_match('%^https?://[\w\d:#@\%/;$()\[\]~_?+=\\\\&.-]+$%i', $post['url'])) {
				$this->errors->add('form-validation', sprintf(__('Specified URL `%s` has wrong format', 'pmlc_plugin'), $post['url']));
			}
			if ( ! preg_match('%^\d+$%', $post['replace_limit'])) {
				$this->errors->add('form-validation', __('Replace Limit must be a non-negative number', 'pmlc_plugin'));
			}
			if ($pass_post_id and '' == $post['post_id_param']) {
				$this->errors->add('form-validation', __('Query variable name for Post/Page ID must be set', 'pmlc_plugin'));
			}
			
			if ( ! $this->errors->get_error_codes()) { // no validation errors detected
				$item->set($post);
				if ($id) {
					$item->update();
					$msg = __('Auto-linked keyword updated', 'pmlc_plugin');
				} else {
					$item->insert();
					$msg = __('Auto-linked keyword added', 'pmlc_plugin');
				}
				wp_redirect(add_query_arg('pmlc_nt', urlencode($msg), $this->baseUrl));
			}
		}
		
		$this->render();
	}
	
	/**
	 * Bulk action handler
	 */
	public function bulk() {
		check_admin_referer('bulk-keywords', '_wpnonce_bulk-keywords');
		if ($this->input->post('doaction2')) {
			$action = $this->input->post('bulk-action2');
		} else {
			$action = $this->input->post('bulk-action');
		}
		$ids = $this->input->post('keywords');
		$list = new PMLC_Keyword_List();
		if (empty($action) or ! in_array($action, array('delete', 'restore')) or empty($ids) or $list->getBy('id', $ids)->isEmpty()) {
			wp_redirect($this->baseUrl);
			die();
		}
		foreach ($list->convertRecords() as $item) {
			switch ($action) {
				case 'delete':
					if ($item->is_trashed) {
						$item->delete();
					} else {
						$item->set('is_trashed', 1)->update();
					}
					break;
				case 'restore':
					$item->set('is_trashed', 0)->update();
					break;
			}
		}
		switch ($action) {
			case 'delete':
				if ('trash' == $this->input->get('type')) {
					$msg = sprintf(_n('%d keyword deleted permanentry', '%d keywords deleted permanently', $list->count(), 'pmlc_plugin'), $list->count());
				} else {
					$msg = sprintf(_n('%d keyword moved to the Trash', '%d keywords moved to the Trash', $list->count(), 'pmlc_plugin'), $list->count());
				}
				break;
			case 'restore':
				$msg = sprintf(_n('%d keyword restored from the Trash', '%d keywords restored from the Trash', $list->count(), 'pmlc_plugin'), $list->count());
				break;
		}
		wp_redirect(add_query_arg('pmlc_nt', urlencode($msg)));
		die();
	}
	
	/**
	 * Restore from trash
	 */
	public function restore() {
		check_admin_referer('restore-keyword');
		$id = $this->input->get('id');
		$item = new PMLC_Keyword_Record();
		if ( ! $id or $item->getById($id)->isEmpty()) {
			wp_redirect($this->baseUrl);
			die();
		}
		$item->set('is_trashed', 0)->update();
		wp_redirect(add_query_arg('pmlc_nt', urlencode(__('Keyword restored from the Trash', 'pmlc_plugin')), $this->baseUrl));
		die();
	}
	
	/**
	 * Delete link
	 */
	public function delete() {
		check_admin_referer('delete-keyword');
		$id = $this->input->get('id');
		$item = new PMLC_Keyword_Record();
		if ( ! $id or $item->getById($id)->isEmpty()) {
			wp_redirect($this->baseUrl);
			die();
		}
		if ($item->is_trashed) {
			$item->delete();
			wp_redirect(add_query_arg('pmlc_nt', urlencode(__('Keyword deleted', 'pmlc_plugin')), $this->baseUrl));
		} else {
			$item->set('is_trashed', 1)->update();
			wp_redirect(add_query_arg('pmlc_nt', urlencode(__('Keyword moved to the Trash', 'pmlc_plugin')), $this->baseUrl));
		}
		die();
	}
}