<?php
class Pincode{

	private static $tableName;
	private static $wPDB;

	public function __construct(){
		global $table_prefix,$wpdb;
		self::$tableName = $table_prefix.'check_pincode_p';
		self::$wPDB 	 = $wpdb;
	}

	public static function all($search = '',$order = ''){
		if($order == 'desc'){
			return self::$wPDB->get_results( self::$wPDB->prepare( "SELECT * FROM ".self::$tableName." ORDER BY ".$search." DESC"),ARRAY_A  ); 
		}elseif($order == 'asc'){
			return self::$wPDB->get_results( self::$wPDB->prepare( "SELECT * FROM ".self::$tableName." ORDER BY ".$search." ASC" ),ARRAY_A  ); 
		}else{
			return self::$wPDB->get_results( self::$wPDB->prepare( "SELECT * FROM ".self::$tableName),ARRAY_A  ); 
		}
	} 	


	public static function select($search,$field){
		$response = [];
		switch ($field) {
			case 'id':
				$response = self::$wPDB->get_results( self::$wPDB->prepare( "SELECT * FROM `".self::$tableName."` where `id` = %d" ,$search ),ARRAY_A );		
				break;
			
			case 'pincode':
				$response = self::$wPDB->get_results( self::$wPDB->prepare( "SELECT * FROM `".self::$tableName."` where `pincode` = %s" ,$search ),ARRAY_A );
		}
		return $response;
	}

	public static function insert($request){
		// print_r($request); die();
		return self::$wPDB->query( self::$wPDB->prepare( "INSERT INTO `".self::$tableName."` SET `pincode` = %s , `city` = %s , `state` = %s, `country` = %s, `dod` = %s, `cod` = %s",$request['pincode'] , $request['city'], $request['state'], $request['country'], $request['dod'], $request['cod'] ) );
	}

	public static function update($request,$ID){
		return self::$wPDB->query( self::$wPDB->prepare( "UPDATE `".self::$tableName."` SET `pincode` = %s , `city` = %s , `state` = %s, `country` =%s, `dod` = %s, `cod` = %s where `id` = %d",$request['pincode'] , $request['city'], $request['state'], $request['country'], $request['dod'], $request['cod'], $ID ) );
	}

	public static function delete($ID){
		return self::$wPDB->query( self::$wPDB->prepare( "DELETE FROM `".self::$tableName."` WHERE `id` = %s", $ID  ) );
	}
}
?>