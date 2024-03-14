<?php 
namespace Adminz\Helper;
use Adminz\Admin\Adminz;

class ADMINZ_Helper_Cf7{

	public $form_tags = [
		/*[
			'tag_name'=>'menu-294',
			'callback'=>''
		]*/
	];

	function __construct() {
		
	}

	function make_form_tags(){
		if(!empty($this->form_tags) and is_array($this->form_tags)){
			foreach ($this->form_tags as $key => $item) {
				add_filter( 'wpcf7_form_tag',function($tag,$replace)use($item){
					if(in_array($tag['type'], ['select', 'select*', 'radio', 'checkbox', 'checkbox*'] )){
						if(isset($item['tag_name']) and $tag['name'] == $item['tag_name']){
							$callback_return = call_user_func($item['callback']);
					    	if(!empty($callback_return) and is_array($callback_return)){
					        	// reset array
					        	$tag['values']=[];
				        		$tag['labels']=[];
				        		if(in_array($tag['type'], ['select', 'select*'] )){
				        			// Option đầu tiên được lấy làm mặc định, nó ko có giá trị 
							    	if(isset($tag['raw_values'][0])){
							    		$default = $tag['raw_values'][0];
							    		$tag['values']=[''];
					        			$tag['labels']=[$default];
							    	}
					        	}
					            foreach ($callback_return as $key => $value) {
					                $tag['values'][] = $key;
					                $tag['labels'][] = $value;
					            }

					            if($search = array_search('include_blank', $tag['options'])>=0){
					                if($search == true){$search = 0; }
					                unset($tag['options'][$search]);
					            }
					        }

						}
					}
					return $tag;
				},10,2);
			}
		}
		
	}
}


/*
	////////// CF7 Form tested
	<div style="border: 1px solid black">[select menu-602 "Chọn"]</div> <div style="border: 1px solid black">[select* menu-603 "Chọn"]</div> <div style="border: 1px solid black">[checkbox checkbox-551 use_label_element "Chọn"]</div> <div style="border: 1px solid black">[checkbox* checkbox-553 use_label_element "Chọn"]</div> <div style="border: 1px solid black">[radio radio-774 default:1 "Chọn"]</div> <div style="border: 1px solid black">[radio radio-775 use_label_element default:1 "Chọn"]</div> <div style="border: 1px solid black">[submit "Gửi"]</div>


	// CODE functions.php tested
	$a = new \Adminz\Helper\ADMINZ_Helper_Cf7;
	$a->form_tags = [
		[
			'tag_name'=> 'menu-602',
			'callback'=> function(){
				return [
					"1"=>"Option 1",
					"2"=>"Option 2"
				];
			}
		],
		[
			'tag_name'=> 'menu-603',
			'callback'=> function(){
				return [
					"1"=>"Option 1",
					"2"=>"Option 2"
				];
			}
		],
		[
			'tag_name'=> 'checkbox-551',
			'callback'=> function(){
				return [
					"1"=>"Option 1",
					"2"=>"Option 2"
				];
			}
		],
		[
			'tag_name'=> 'checkbox-553',
			'callback'=> function(){
				return [
					"1"=>"Option 1",
					"2"=>"Option 2"
				];
			}
		],
		[
			'tag_name'=> 'radio-774',
			'callback'=> function(){
				return [
					"1"=>"Option 1",
					"2"=>"Option 2"
				];
			}
		],
		[
			'tag_name'=> 'radio-775',
			'callback'=> function(){
				return [
					"1"=>"Option 1",
					"2"=>"Option 2"
				];
			}
		]
	];
	$a->make_form_tags();
*/