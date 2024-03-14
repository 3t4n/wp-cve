<?php
class iconsModelGmp extends modelGmp {
   /* public static $tableObj;
    function __construct() {
        if(empty(self::$tableObj)){
            self::$tableObj=  frameGmp::_()->getTable("icons");
        }
    }*/

	public function getIconsByIds($ids) {
		//$icons = frameGmp::_()->getTable('icons')->get('*', array('additionalCondition' => 'id IN ('. implode(',', $ids). ')'));
		global $wpdb;
		$ids = implode(',', array_map('absint', $ids));
		$icons = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$wpdb->prefix}gmp_icons WHERE id IN (%1s)", $ids), ARRAY_A);
        if(empty($icons) ){
			return $icons ;
        }
		if(!empty($icons)) {
			$iconsArr = array();
			foreach($icons as $i => $icon){
				$icon['path'] = $this->getIconUrl($icon['path']);
				$iconsArr[$icon['id']] = $icon;
			}
		}
        return $iconsArr;
	}
    public function getIcons($params = array()) {
				global $wpdb;
				$res = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}gmp_icons AS gmp_icons", ARRAY_A);
				if (empty($res) && !$res) {
					return $res;
				}
				$iconsArr = array();
				foreach($res as $icon){
            $icon['path'] = $this->getIconUrl($icon['path']);
            $iconsArr[$icon['id']] = $icon;
        }
        return $iconsArr;
    }
    public function saveNewIcon($params){
        if(!isset($params['url'])){
            $this->pushError(__("Icon no found", GMP_LANG_CODE));
            return false;
        }
        $url = $params['url'];
        //$exists = frameGmp::_()->getTable('icons')->get("*", "`path`='".$url."'");
				global $wpdb;
        $exists = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}gmp_icons WHERE " . $wpdb->prepare("path = %s", $url), ARRAY_A);
        if(!empty($exists)){
            return $exists[0]['id'];
        }
				$size = $this->_getIconSize($url);
				$tableName = $wpdb->prefix . "gmp_icons";
				$res = $wpdb->insert($tableName, array(
						'path' => $url,
						'title' => $params['title'],
						'description' => $params['description'],
						'width' => (int)$size[0],
						'height' => (int)$size[1],
				));
				if ($res) {
					return $dbResId = $wpdb->insert_id;
				} else {
					return $res;
				}
        // return frameGmp::_()->getTable('icons')->insert(array(
				// 	'path' => $url,
				// 	'title' => $params['title'],
				// 	'description' => $params['description'],
				// 	'width' => $params['width'],
				// 	'height' => $params['height'],
				// ));
    }
   /* public function getIconsPath(){
        return 'icons_files/def_icons/';
    }*/
	private function _getIconsDir() {
		return GMP_CODE. '_icons';
	}
	private function _getDefIconsDir() {
		return 'icons_files/def_icons/';
	}
    public function getIconsFullDir(){
        static $uplDir = '';
		if(empty($uplDir))
			$uplDir = wp_upload_dir();
        $modPath = $this->getModule()->getModPath();
        $path  = $modPath. $this->_getDefIconsDir();
        return $path;
    }

    public function getIconsFullPath(){
        $uplDir = wp_upload_dir();
        $path = $uplDir['basedir']. $this->_getIconsDir();
        return $path;
    }
    public function setDefaultIcons(){
		$jsonFile = frameGmp::_()->getModule('icons')->getModDir(). 'icons_files/icons.json';
		$icons = utilsGmp::jsonDecode(file_get_contents($jsonFile));
		$uplDir = wp_upload_dir();
		wp_mkdir_p($uplDir['basedir']. DS. $this->_getIconsDir());
        $qItems = array();
        foreach($icons as $icon){
					$size = $this->_getIconSize($this->_getIconPath($icon['img'], true));
					global $wpdb;
					$tableName = $wpdb->prefix . "gmp_icons";
					$wpdb->insert($tableName, array(
							'title' => $icon['title'],
							'description' => $icon['description'],
							'path' => $icon['img'],
							'width' => (int)$size[0],
							'height' => (int)$size[1],
							'is_def' => 1,
					));
				}
		update_option(GMP_CODE. '_def_icons_installed', true);
    }
	private function _getIconPath($iconName, $isDef = false) {
		if($isDef) {
			return $this->getModule()->getModDir(). $this->_getDefIconsDir(). $iconName;
		}
	}
	private function _getIconSize($iconFile) {
		if(function_exists('getimagesize')) {
			return getimagesize($iconFile);
		}
		return array(0, 0);
	}


    public function downloadIconFromUrl($url){
        $filename = basename($url);
        if(empty($filename)){
            $this->pushError(__('File not found', GMP_LANG_CODE));
            return false;
        }
        $imageinfo = getimagesize ( $url,$imgProp );
        if(empty($imageinfo)){
            $this->pushError(__('Cannot get image', GMP_LANG_CODE));
            return false;
        }
        $fileExt = str_replace("image/","",$imageinfo['mime']);
        $filename = utilsGmp::getRandStr(8).".".$fileExt;
        $dest = $this->getIconsFullPath().$filename;
        file_put_contents($dest, fopen($url, 'r'));
        $newIconId = frameGmp::_()->getTable('icons')->store(array('path'=>$filename),"insert");
        if($newIconId){
           return array('id'=>$newIconId,'path'=>$this->getIconsFullDir().$filename);
        }else{
            $this->pushError(__('cannot insert to table', GMP_LANG_CODE));
            return false;
        }
    }

	public function getIconFromId($id){
		global $wpdb;
		$res = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}gmp_icons WHERE " . $wpdb->prepare("id = %s", $id), ARRAY_A);
		//$res = frameGmp::_()->getTable('icons')->get('*', array('id' => $id));
		if(empty($res)){
			return $res;
		}
		$icon = $res[0];
		$icon['path'] = $this->getIconUrl($icon['path']);
		return $icon;
	}
	function getIconUrl($icon){
		if(!empty($icon)){
			$isUrl = strpos($icon, 'http');
			if($isUrl === false){
				$isWpContent = strpos($icon, 'wp-content/uploads');
				if ($isWpContent === false) {
					$icon = $this->getIconsFullDir(). $icon;
				} else {
					$icon = $icon;
				}
			}
			if(uriGmp::isHttps()) {
				$icon = uriGmp::makeHttps($icon);
			}
		}
		return $icon;
	}
	public function iconExists($iconId) {
		global $wpdb;
		return $res = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}gmp_icons WHERE " . $wpdb->prepare("id = %s", $iconId), ARRAY_A);
		//return frameGmp::_()->getTable('icons')->exists($iconId, 'id');
	}
	public function remove($d = array()) {
		$d['id'] = isset($d['id']) ? (int) $d['id'] : 0;
		if($d['id']) {

			global $wpdb;
			$table = $wpdb->prefix . "gmp_icons";
			$deleted = $wpdb->delete( $table, array('id' => $d['id']) );

			if($deleted) {
				$this->replaceDeletedIconIdToDefault($d['id']);
				return true;
			} else
				$this->pushError (frameGmp::_()->getTable('icons')->getErrors());
		} else
			$this->pushError (__('Invalid ID', GMP_LANG_CODE));
		return false;
	}
	public function replaceDeletedIconIdToDefault($idIcon){
		if(frameGmp::_()->getModule('marker')->getModel()->replaceDeletedIconIdToDefault($idIcon)) {
			return true;
		} else {
			$this->pushError (frameGmp::_()->getTable('icons')->getErrors());
		}
		return false;
	}

}
