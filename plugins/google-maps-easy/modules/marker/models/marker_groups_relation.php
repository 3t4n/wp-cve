<?php
class marker_groups_relationModelGmp extends modelGmp {
	function __construct() {
		$this->_setTbl('marker_groups_relation');
	}

	public function getRelationsByMarkerId($id){
//		$relations =  frameGmp::_()->getTable('marker_groups_relation')->get('groups_id', 'marker_id = ' . $id, '', 'col');
			global $wpdb;
			$relations = $wpdb->get_col("SELECT groups_id FROM {$wpdb->prefix}gmp_marker_groups_relation AS gmp_mrgrr WHERE " . $wpdb->prepare("marker_id = %s", $id));
			return $relations;
	//	return frameGmp::_()->getTable('marker_groups_relation')->get('groups_id', 'marker_id = ' . $id, '', 'col');
	}

}
