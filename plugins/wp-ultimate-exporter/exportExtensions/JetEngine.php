<?php
/**
 * WP Ultimate Exporter plugin file.
 *
 * Copyright (C) 2010-2020, Smackcoders Inc - info@smackcoders.com
 */
namespace Smackcoders\SMEXP;

if ( ! defined( 'ABSPATH' ) )
exit; // Exit if accessed directly

/**
 * Class LearnPressExport
 * @package Smackcoders\SMEXP
 */

class JetEngineExport extends ExportExtension{

	protected static $instance = null,$export_instance;	
	public static function getInstance() {
		if ( null == self::$instance ) {
			self::$instance = new self;
			JetEngineExport::$export_instance = ExportExtension::getInstance();
		}
		return self::$instance;
	}

	/**
	 * CustomerReviewExport constructor.
	 */
    /**
	 * Function to export the meta information based on Fetch ACF field information to be export
	 * @param $id
	 * @return mixed
	 */
	public function __construct() {

			global $wpdb;	
            global $optionalType;
		$this->plugin = Plugin::getInstance();
    

	}
    public function jetEngineActivate($id)
	{

        if(is_plugin_active('jet-engine/jet-engine.php')){

			$jetEnginefields = $wpdb->get_results("SELECT id, meta_fields FROM {$wpdb->prefix}jet_post_types WHERE slug = '$optionalType' AND status IN ('publish','built-in')", ARRAY_A);
			$jetEnginefields[0]['meta_fields']=isset($jetEnginefields[0]['meta_fields'])?$jetEnginefields[0]['meta_fields']:'';

			$unserializedMeta = maybe_unserialize($jetEnginefields[0]['meta_fields']);
			$unserializedMeta=isset($unserializedMeta)?$unserializedMeta:'';

			if(is_array($unserializedMeta)){
				foreach($unserializedMeta as $jet_key => $jetValue){
					$jetFieldLabel = $jetValue['title'];
					$jetFieldType = $jetValue['type'];
					if($jetFieldType != 'repeater' && $jetFieldType != 'media' && $jetFieldType != 'gallery' && $jetFieldType != 'posts' && $jetFieldType != 'html' ){					
						$jetFieldNameArr[] = $jetValue['name'];
					}
					else{
						$jetFieldNameArr[] = $jetValue['name'];
						$fields=$jetValue['repeater-fields'];
						if(is_array($fields)){
							foreach($fields as $repFieldKey => $repFieldVal){
								$jetFieldName[] = $repFieldVal['name'];

							}
						}
					}
				}	
			}

			if(isset($jetFieldName) && is_array($jetFieldName) ){
				if(is_array($jetFieldNameArr)){
					$jetCPTFieldsName=array_merge($jetFieldNameArr,$jetFieldName);
				}
				else{
					$jetCPTFieldsName= $jetFieldName;
				}

			}
			else{
				$jetFieldNameArr = isset($jetFieldNameArr) ? $jetFieldNameArr : '';
				$jetCPTFieldsName= $jetFieldNameArr;
			}

			//jeteng metabox fields

			global $wpdb;	
		
			$getMetaFields = $wpdb->get_results( $wpdb->prepare("SELECT option_value FROM {$wpdb->prefix}options WHERE option_name=%s",'jet_engine_meta_boxes'),ARRAY_A);			
			if(!empty($getMetaFields)){
				$unserializedMeta = maybe_unserialize($getMetaFields[0]['option_value']);
			}
			else{
				$unserializedMeta = '';
			}

			if(is_array($unserializedMeta)){
				$arraykeys = array_keys($unserializedMeta);

				foreach($arraykeys as $val){
					$values = explode('-',$val);
					$v = $values[1];
				}
			}


			$jetMetaFieldName = [];
			if(isset($v)){
				for($i=1 ; $i<=$v ; $i++){
					$unserializedMeta['meta-'.$i]= isset($unserializedMeta['meta-'.$i])? $unserializedMeta['meta-'.$i] : '';
					$fields= $unserializedMeta['meta-'.$i];					
					if(!empty($fields)){
						foreach($fields['meta_fields'] as $jet_key => $jetValue){
							if($jetValue['type'] != 'repeater'){
								$jetMetaFieldName[] = $jetValue['name'];
							}
							else{
								$jetMetaFieldName[] = $jetValue['name'];
								$jetRepFields = $jetValue['repeater-fields'];
								foreach($jetRepFields as $jetRepKey => $jetRepVal){
									$jetRepFieldName[] = $jetRepVal['name'];
								}
							}
						}
					}

				}
			}	
			if( isset($jetRepFieldName) && is_array($jetRepFieldName)){
				if(is_array($jetMetaFieldName)){
					$jetFName = array_merge($jetMetaFieldName,$jetRepFieldName);
				}
				else{
					$jetFName= $jetRepFieldName;
				}
			}
			else{
				$jetFName= $jetMetaFieldName;
			}
		}
		else{
			$jetCPTFieldsName =$jetFName= $jet_tax_fields_name = '';
		}
    }

  
}

global $jetengine_exp_class;
$jetengine_exp_class = new JetEngineExport();