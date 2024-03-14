<?php
class WootablepressModelWtbp extends ModelWtbp {
	public function __construct() {
		$this->_setTbl('tables');
	}

	public function saveCustomTitle( $data ) {
		if ( '' !== $data['settings']['order'] ) {
			$table = json_decode( $data['settings']['order'] );
			$file  = WTBP_DIR . 'languages' . DS . 'customTitle.php';
			if ( file_exists( $file ) ) {
				$str = file_get_contents( $file );
				if ( $str ) {
					preg_match_all( "/__\('(.+?)'/", $str, $matches );
				}
			} else {
				$str        = "<?php\n";
				$matches[1] = array();
			}

			foreach ( $table as $column ) {
				$title = ( isset( $column->display_name ) && '' !== $column->display_name ) ? $column->display_name : $column->original_name;
				if ( ! in_array( $title, $matches[1], true ) ) {
					$str .= "__('{$title}', 'woo-product-tables'); \n";
				}
			}

			$phrases = array(
				'empty_table',
				'table_info',
				'table_info_empty',
				'length_text',
				'search_label',
				'processing_text',
				'zero_records',
				'lang_previous',
				'lang_next'
			);

			foreach ( $phrases as $phrase ) {
				if ( isset( $data['settings'][ $phrase ] ) ) {
					$value = $data['settings'][ $phrase ];
					if ( '' !== $value && ! in_array( $value, $matches[1], true ) ) {
						$str .= "__('{$value}', 'woo-product-tables'); \n";
					}
				}
			}

			file_put_contents( $file, $str );
		}
	}

	public function save( $data = array() ) {

		$id = isset($data['id']) ? $data['id'] : '';
		if (!empty($id)) {

			$data['id'] = (string) $id;
			$data['settings']['order'] = stripslashes($data['settings']['order']);
			$this->saveCustomTitle( $data );
			$settingData = array('settings' => $data['settings']);
			if (isset($settingData['settings']['custom_css'])) {
				$settingData['settings']['custom_css'] = base64_encode(stripslashes($settingData['settings']['custom_css']));
			}
			if (isset($settingData['settings']['custom_js'])) {
				$settingData['settings']['custom_js'] = base64_encode(stripslashes($settingData['settings']['custom_js']));
			}
			$data = DispatcherWtbp::applyFilters('addTableSettings', $data);
			$data['setting_data'] = base64_encode(serialize($settingData));
			$statusUpdate = $this->updateById($data, $id);
			if ($statusUpdate) {
				return $id;
			}
		} else if (empty($id)) {
			$idInsert = $this->insert($data);
			if ($idInsert) {
				if (empty($data['title'])) {
					$data['title'] = (string) $idInsert;
				}
				$data['id'] = (string) $idInsert;
				if (!isset($data['settings'])) {
					$data['settings'] = array('header_show' => 1);
				}
				if (!isset($data['settings']['order'])) {
					$data['settings']['order'] = '';
				}
				$data['settings']['order'] = stripslashes($data['settings']['order']);
				$this->saveCustomTitle( $data );
				$settingData = array('settings' => $data['settings']);
				$data['setting_data'] = base64_encode(serialize($settingData));
				$this->updateById( $data , $idInsert );
			}
			return $idInsert;
		} else {
			$this->pushError (esc_html__('Title can\'t be empty or more than 255 characters', 'woo-product-tables'), 'title');
		}
		return false;
	}
	public function cloneTable( $data = array() ) {

		$id = isset($data['id']) ? $data['id'] : '';
		$title = isset($data['title']) ? trim($data['title']) : '';
		if (strlen($title) == 0) {
			$this->pushError (esc_html__('Title can\'t be empty or more than 255 characters', 'woo-product-tables'), 'title');
		} else if (!empty($id)) {
			$table = $this->getById($id);
			$table['id'] = 0;
			$table['title'] = substr($title, 0, 254);

			$idInsert = $this->insert($table);
			if ($idInsert) {
				return $idInsert;
			}
		}
		return false;
	}
}
