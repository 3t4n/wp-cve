<?php
/**<!--WPJSSTDDEL--!>
 * WP-JoomSport
 * @author      BearDev
 * @package     JoomSport
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class JoomSportAchievmentsHelperEF{
    public static function getEFList($type, $id){
        global $wpdb;
        $sql = "SELECT *"
                . " FROM {$wpdb->jsprtachv_ef}"
                . " WHERE type='".$type."' AND published = '1'";
        return $wpdb->get_results($sql);
    }
    public static function getEFInput(&$ef, $value,$name='ef',$asarr = false){
        global $wpdb;
        $namearr = $asarr?$name.'[]':$name.'['.$ef->id.']';
        switch($ef->field_type){
            case '1': //radio
                    $is_field = array();
                    $is_field[] = JoomSportAchievmentsHelperSelectBox::addOption(0, __("No", "joomsport-achievements"));
                    $is_field[] = JoomSportAchievmentsHelperSelectBox::addOption(1, __("Yes", "joomsport-achievements"));
                    $ef->edit = JoomSportAchievmentsHelperSelectBox::Radio($namearr, $is_field,$value,' id="'.$name.'_'.$ef->id.'"');
                break;
            case '2': //textarea
                    $ef->edit = '';//wp_editor($value, 'ef_'.$ef->id,array("textarea_rows"=>3));
                break;
            case '3': //selectbox
                    $selval = $wpdb->get_results('SELECT id, sel_value as name FROM '.$wpdb->jsprtachv_ef_select.' WHERE fid='.absint($ef->id).' ORDER BY eordering', 'OBJECT') ;
                    $ef->edit = JoomSportAchievmentsHelperSelectBox::Simple($namearr, $selval,$value,' id="'.$name.'_'.$ef->id.'"',true);
                break;
            default:
                $ef->edit = '<input type="text" value="'.esc_attr($value).'" id="'.$name.'_'.$ef->id.'" name="'.$namearr.'" />';
        }
    }
    public static function getEFInputFilters(&$ef, $value,$name='ef',$asarr = false){
        global $wpdb;
        $namearr = $asarr?$name.'[]':$name.'['.$ef->id.']';
        switch($ef->field_type){

            case '3': //selectbox
                    $selval = $wpdb->get_results('SELECT id, sel_value as name FROM '.$wpdb->jsprtachv_ef_select.' WHERE fid='.absint($ef->id).' ORDER BY eordering', 'OBJECT') ;
                    $ef->edit = JoomSportAchievmentsHelperSelectBox::Simple($namearr, $selval,$value,' id="'.$name.'_'.$ef->id.'" onchange="JSACH_filteredPartic(this);"',__("not filtered", "joomsport-achievements"));
            break;
         }
    }
}