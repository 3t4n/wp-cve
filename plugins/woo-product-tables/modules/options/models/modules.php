<?php
class ModulesModelWtbp extends ModelWtbp {
	public function __construct() {
		$this->_setTbl('modules');
	}
	public function get( $d = array() ) {
		if (isset($d['id']) && $d['id'] && is_numeric($d['id'])) {
			$fields = FrameWtbp::_()->getTable('modules')->fillFromDB($d['id'])->getFields();
			$fields['types'] = array();
			$types = FrameWtbp::_()->getTable('modules_type')->fillFromDB();
			foreach ($types as $t) {
				$fields['types'][$t['id']->value] = $t['label']->value;
			}
			return $fields;
		} elseif (!empty($d)) {
			$data = FrameWtbp::_()->getTable('modules')->get('*', $d);
			return $data;
		} else {
			return FrameWtbp::_()->getTable('modules')
				->innerJoin(FrameWtbp::_()->getTable('modules_type'), 'type_id')
				->getAll(FrameWtbp::_()->getTable('modules')->alias() . '.*, ' . FrameWtbp::_()->getTable('modules_type')->alias() . '.label as type');
		}
	}
	public function put( $d = array() ) {
		$res = new ResponseWtbp();
		$id = $this->_getIDFromReq($d);
		$d = prepareParamsWtbp($d);
		if (is_numeric($id) && $id) {
			if (isset($d['active'])) {
				$d['active'] = ( ( is_string($d['active']) && 'true' == $d['active'] ) || 1 == $d['active'] ) ? 1 : 0;           //mmm.... govnokod?....)))
			}
			
			if (FrameWtbp::_()->getTable('modules')->update($d, array('id' => $id))) {
				$res->messages[] = esc_html__('Module Updated', 'woo-product-tables');
				$mod = FrameWtbp::_()->getTable('modules')->getById($id);
				$newType = FrameWtbp::_()->getTable('modules_type')->getById($mod['type_id'], 'label');
				$newType = $newType['label'];
				$res->data = array(
					'id' => $id, 
					'label' => $mod['label'], 
					'code' => $mod['code'], 
					'type' => $newType,
					'active' => $mod['active'], 
				);
			} else {
				$tableErrors = FrameWtbp::_()->getTable('modules')->getErrors();
				if ($tableErrors) {
					$res->errors = array_merge($res->errors, $tableErrors);
				} else {
					$res->errors[] = esc_html__('Module Update Failed', 'woo-product-tables');
				}
			}
		} else {
			$res->errors[] = esc_html__('Error module ID', 'woo-product-tables');
		}
		return $res;
	}
	protected function _getIDFromReq( $d = array() ) {
		$id = 0;
		if (isset($d['id'])) {
			$id = $d['id'];
		} elseif (isset($d['code'])) {
			$fromDB = $this->get(array('code' => $d['code']));
			if (isset($fromDB[0]) && $fromDB[0]['id']) {
				$id = $fromDB[0]['id'];
			}
		}
		return $id;
	}
}
