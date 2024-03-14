<?php
namespace MBSocial;

/* Elements of sharing functionality */ 
class Share 
{

	public function __construct($id = 0) 
	{
		
	
	}
	
	public function save($post) 
	{
		$fields = sanitize_text_field($post['defined_fields']); 
		
		$fields = explode(',', $fields); 
		
		$data = array(); 
		
		foreach($fields as $field_name) 
		{
			
			$field_value = isset($post[$field]) ? sanitize_text_field($post[$field]) : false; 
			$data[$field_name] = $field_value; 
		}
		
		// save here 
	}
	
	public function load() 
	{
		
	}
	
	public function getDataItem($item) 
	{
	
	}
	






}
