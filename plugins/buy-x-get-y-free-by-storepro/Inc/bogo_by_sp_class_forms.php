<?php


defined( 'ABSPATH' ) or die( 'No script!' );

if(!class_exists('bogo_by_sp_form')):
class bogo_by_sp_form{

    private $setting;
    private $saved_value; 
    function __construct($setting){

        $this->setting = $setting;

        if(isset( $this->setting['default'] )){
            $this->saved_value = get_option($this->setting['field'], $this->setting['default']);
        }else{
            $this->saved_value = get_option($this->setting['field']);
        }
		
        $this->check_field_type(); 
    }

    

    
    function check_field_type(){
        if(isset($this->setting['type'])):
            switch ($this->setting['type']){
                case 'select':
                    $this->select_box();
                break;

                case 'number':
                    $this->number_box();
                break;

                case 'switch':
                    $this->switch_display();
                break;
                case 'text':
                    $this->text_box();
                break;
				case 'textarea':
                    $this->textarea_box();
                break;
            }
        endif;
    }

 
	

        
    function bootstrap($label, $field, $desc = ""){
        if($this->setting['type'] != 'hidden'){
        ?>

        <div id="row_<?php echo $this->setting['field']; ?>"  class="row sprow ">
			
		<div class="col-sm-6">
            <?php echo $label; ?>
            
            </div>
            <div class="col-sm-6 sp_cs">
            <?php echo $field; ?>
			<?php echo $desc != "" ? $desc: ""; ?>
            </div>
        </div>
        
        <?php
        }
    }

    /*
        Field type: select box
    */
    function select_box(){

        $label = '<label class="h6 mb-0" class="mb-0" for="'.$this->setting['field'].'">'.$this->setting['label'].'</label>';
        $desc = (isset($this->setting['desc'])) ? '<br><small>'.$this->setting['desc'].'</small>' : "";
        
        $field = '<select name="'.$this->setting['field'].'" id="'.$this->setting['field'].'"'
         .(isset($this->setting['multiple']) ? ' multiple="'.$this->setting['multiple'].'"': '')
        .'>';
            foreach($this->setting['value'] as $key => $val){
               $field .= '<option value="'.$key.'" '.( ( $this->saved_value == $key) ? " selected=\"selected\" " : "" ).'>'.$val.'</option>';
            }
        $field .= '</select>';

        $this->bootstrap($label, $field, $desc);

    }
	
    /*
        Field type: Text box
    */
    function text_box(){

        $label = '<label class="h6 mb-0" for="'.$this->setting['field'].'">'.$this->setting['label'].'</label>';
        $desc =  (isset($this->setting['desc'])) ? '<br><small>'.$this->setting['desc'].'</small>' : "";
        $field = '<input type="text" name="'.$this->setting['field'].'" id="'.$this->setting['field'].'" value="'.$this->saved_value.'"'
        
        .'>';
        $this->bootstrap($label, $field, $desc);
    }
	
    /*
    Field type: Textarea box
    */
    function textarea_box(){
        $label = '<label class="h6 mb-0" for="'.$this->setting['field'].'">'.$this->setting['label'].'</label>';
        $desc =  (isset($this->setting['desc'])) ? '<br><small>'.$this->setting['desc'].'</small>' : "";
        $field = '<textarea type="text" name="'.$this->setting['field'].'" id="'.$this->setting['field'].'"'
        .(isset($this->setting['required']) ? ' required="'.$this->setting['required'].'"': '')
        .(isset($this->setting['readonly']) ? ' readonly="'.$this->setting['readonly'].'"': '')
        .'>';
        $field .= $this->saved_value; 
        $field .= '</textarea>';
        $this->bootstrap($label, $field, $desc);
    }
	
    /*
        Field type: Number box
    */
    function number_box(){

        $label = '<label class="h6 mb-0" for="'.$this->setting['field'].'">'.$this->setting['label'].'</label>';
        $desc =  (isset($this->setting['desc'])) ? '<br><small>'.$this->setting['desc'].'</small>' : "";
        $field = '<input type="number" name="'.$this->setting['field'].'" id="'.$this->setting['field'].'" value="'.$this->saved_value.'"'
        .(isset($this->setting['min']) ? ' min="'.$this->setting['min'].'"': '')
        .(isset($this->setting['max']) ? ' max="'.$this->setting['max'].'"': '')
        .(isset($this->setting['step']) ? ' step="'.$this->setting['step'].'"': '')
        .(isset($this->setting['required']) ? ' required="'.$this->setting['required'].'"': '')
        .(isset($this->setting['readonly']) ? ' readonly="'.$this->setting['readonly'].'"': '')
        .'>';
        $this->bootstrap($label, $field, $desc);
    }

    /*
        Field type: switch
    */
    function switch_display(){

        $label = '<label class="h6 mb-0" for="'.$this->setting['field'].'">'.$this->setting['label'].'</label>';
        $desc = (isset($this->setting['desc'])) ? '<small>'.$this->setting['desc'].'</small>' : "";
        
        $field = '<div class="checkbox-inline">
        <input type="checkbox" value="1" class="checkbox-inline" name="'.$this->setting['field'].'" id="'.$this->setting['field'].'"'.(($this->saved_value == true) ? "checked='checked'": "").' >
        <label class="checkbox-inline" for="'.$this->setting['field'].'"></label> <small>'.$this->setting['desc'].'</small>
		</div>';
		$this->bootstrap($label, $field);
    }


}
endif;