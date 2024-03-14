<?php
	if (!function_exists("APBD_GetHTMLRadioByArray")) {
		
		function  APBD_GetHTMLRadioByArray($title,$name, $id, $isRequired, $options, $checkedValue, $isDisabled=false, $isInline = true,$class="",$attr=array(),$group_class=''){
			foreach ($options as $key=>$value){
				$attrStr=" ";
				if(is_array($attr) && count($attr)>0){
					foreach ($attr as $skey=>$svalue){
						$attrStr.=$skey.'="'.$svalue.'" ';
					}
				}
				?>
				<div class=" <?php echo $group_class; ?>  md-radio <?php echo $isInline?' md-radio-inline ':''; ?>">
					<input class="<?php echo $class;?>" <?php echo $attrStr;?>
					       id="<?php echo $id."-".$key;?>" type="radio"
						<?php echo $checkedValue==$key?' checked ':'';?>
						<?php if(!$isDisabled){?> name="<?php echo $name;?>" <?php }else{?>
							disabled="disabled" <?php }?> value="<?php echo $key;?>"
						<?php if(!$isDisabled && $isRequired){?> data-bv-notempty="true"
							data-bv-notempty-message="Choose <?php echo $title;?>" <?php }?> />
					<label class="" for="<?php echo $id."-".$key;?>"><?php echo $value;?></label>
				</div>
				
				
				<?php
			}
		}
	}
	

	
	if (!function_exists("APBD_GetHTMLRadio")) {
		
		function  APBD_GetHTMLRadio($optionTitle,$optionValue,$name, $id,$isRequired,$checkedValue, $isDisabled=false,$class='',$group_class='',$isInline = true,$attr=array()){
			    $attrStr="";
				if(is_array($attr) && count($attr)>0){
					foreach ($attr as $skey=>$svalue){
						$attrStr.=$skey.'="'.$svalue.'" ';
					}
				}
				?>
                <div class=" <?php echo $group_class; ?>  md-radio <?php echo $isInline?' md-radio-inline ':''; ?>">
                    <input class="<?php echo $class;?>" <?php echo $attrStr;?>
                           id="<?php echo $id."-".$optionValue;?>" type="radio"
						<?php echo $checkedValue==$optionValue?' checked ':'';?>
						<?php if(!$isDisabled){?> name="<?php echo $name;?>" <?php }else{?>
                            disabled="disabled" <?php }?> value="<?php echo $optionValue;?>"
						<?php if(!$isDisabled && $isRequired){?> data-bv-notempty="true"
                            data-bv-notempty-message="Choose <?php echo $optionTitle;?>" <?php }?> />
                    <label class="" for="<?php echo $id."-".$optionValue;?>"><?php echo $optionTitle;?></label>
                </div>
				
				
				<?php
			
		}
	}
	
	if(!function_exists("APBD_GetHTMLSwitchButton")){
		function APBD_GetHTMLSwitchButton($id,$name,$default_value="",$boolvalue,$checkedValue,$isDisabled=false,$input_class='',$label_class='bg-mat',$group_class='material-switch-sm'){
			?><div class="material-switch <?php echo $group_class; ?> ">
			<input  name="<?php echo $name; ?>" value="<?php echo $default_value; ?>" type="hidden">
			<input  class="<?php echo $input_class; ?>" id="<?php echo $id; ?>" <?php echo $isDisabled?' disabled="disabled"' :'name="'.$name.'"';?>  type="checkbox" <?php echo $checkedValue ==$boolvalue? "checked" : ""?>  value="<?php echo $boolvalue;?>" >
			<label for="<?php echo $id; ?>" class="<?php echo $label_class; ?>"></label>
			</div><?php
			
		}
	}
	
	
	if ( ! function_exists( "APBD_PostValue" ) ) {
		function APBD_PostValue( $index, $default = NULL ) {
			if ( ! isset( $_POST[ $index ] ) ) {
				return $default;
			} else {
				return $_POST[ $index ];
			}
		}
	}
	
	if ( ! function_exists( "APBD_GetHTMLOption" ) ) {
		function APBD_GetHTMLOption( $value, $text, $selected = "" ,$attr=array()){
			$attrStr="";
			if(is_array($attr) && count($attr)>0){
				foreach ($attr as $key=>$kvalue){
					$attrStr.=" ".$key.'="'.$kvalue.'"';
				}
			}
			?>
            <option <?php echo $attrStr;?> <?php echo $selected==$value?"selected='selected'":"";?>
                    value="<?php echo $value;?>"><?php echo $text;?></option>
			<?php
		}
	}
	if(!function_exists("APBD_GetHTMLOptionByArray")){
		function APBD_GetHTMLOptionByArray($options,$selected="",$attr=[]){
			if(is_array($options)){
				foreach ($options as $key=>$value){
					if(is_array($selected)){
						APBD_GetHTMLOption($key,$value,(in_array($key,$selected)?$key:""),$attr);
					}else{
						APBD_GetHTMLOption($key,$value,$selected,$attr);
					}
					
				}
			}
			
		}
	}