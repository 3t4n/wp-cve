<?php
// if called directly, abort.
if (!defined('WPINC')) { die; }

class raysgrid_Field {
    
    public function rsgd_taxonomy($id) {
        
        $dbObj = new raysgrid_Tables();
        if (isset($id) && $id != '') {
            $getDb = $dbObj->rsgd_selectWithId($id);
            $ct = $getDb[0]->rsgd_cats;
            $tgg = $getDb[0]->rsgd_tags;
        } else {
            $ct = $tgg = '';
        }
	    $output = '';
        // Custom Categories List
        $rsgd_cats = get_terms('rg-categories', array(
            'hide_empty' => false,
        ));
        if ($rsgd_cats && !is_wp_error($rsgd_cats)) {
            $output.= "<div id='cats_select'>";
                $output.= "<select multiple class='form-control'>";
                foreach ($rsgd_cats as $cat) {
                    if ($cat->count == 1) {
                        $catno = ' (' . $cat->count . esc_html__( ' Item' , RSGD_SLUG ) . ')';
                    } else {
                        $catno = ' (' . $cat->count . esc_html__( ' Items' , RSGD_SLUG ) . ')';
                    }
                    $output.= "<option value='" . esc_attr($cat->slug) . "'>" . $cat->name . $catno . "</option>";
                }
                $output.= '</select>';
            $output.= '<input name="rsgd_data[rsgd_cats]" type="hidden" id="cats_vl"  value="' . esc_attr($ct) . '"   class="" /></div>';
        } else {
            $output.= " <div id='cats_select'>";
                $output.= '<span class="in-message msg-danger">'.esc_html__('Please insert categories in the Portfolio Posts to be shown here.', RSGD_SLUG).'</span>';
            $output.= '</div>';
        }

        // Custom Tags List
        $rsgd_tags = get_terms('rg-tags', array(
            'hide_empty' => false,
        ));
        if ($rsgd_tags && !is_wp_error($rsgd_tags)) {

            $output.= "<div id='tags_select'>";
                echo "<select multiple class='form-control'>";
                foreach ($rsgd_tags as $tg) {
                    if ($tg->count == 1) {
                        $tgno = ' (' . $tg->count . ' Item)';
                    } else {
                        $tgno = ' (' . $tg->count . ' Items)';
                    }
                    echo "<option value='" . esc_attr($tg->slug) . "'>" . wp_kses($tg->name . $tgno, true) . "</option>";
                }
                $output.= "</select>";
            $output.= "<input name='rsgd_data[rsgd_tags]' type='hidden' id='tags_vl' value='" . sanitize_text_field($tgg) . "'  class='' /></div>";
        } else {
            $output.= "<div id='tags_select'>";
                $output.= '<span class="in-message msg-danger">'.esc_html__('Please insert tags in the Portfolio Posts to be shown here.', RSGD_SLUG).'</span>';
            $output.= '</div>';
        }

        return $output;
    }

    public function rsgd_display_field($id, $section_slug, $config_data) {

        extract($config_data);
        
        $rsgd_tbls = new raysgrid_Tables();

		$std = $config_data['std'];
	    $name = $config_data['name'];
	    $not_null = $config_data['not_null'];
	    $type = $config_data['type'];
	    $choices = $config_data['choices'];
	    $class = $config_data['class'];
	    $min = $config_data['min'];
	    $max = $config_data['max'];
	    $placeholder = $config_data['placeholder'];

        $val = $std;
        if (isset($id) && $id != '') {
            $rsgd_getDb = $rsgd_tbls->rsgd_selectWithId($id);
            $val = ($name == 'oldalias') ? $rsgd_getDb[0]->alias : $rsgd_getDb[0]->$name;
        }
        
        $rsgd_req = ($not_null == 'NOT NULL') ? " required='required'" : "";

        switch ($type) {
            case 'text':
                echo "<input type='text'{$rsgd_req} name='rsgd_data[" . esc_attr($name) . "]' class='dep-inp form-control " . esc_attr($class) . "' id='" . esc_attr($name) . "' placeholder='" . esc_attr($placeholder) . "' value='" . esc_attr($val) . "' />";
                break;
                
            case 'disabledtext':
                echo "<input type='text' readonly name='rsgd_data[" . esc_attr($name) . "]' class='dep-inp form-control " . esc_attr($class) . "' id='" . esc_attr($name) . "' placeholder='" . esc_attr($placeholder) . "' value='" . esc_attr($val) . "' />";
                break;

            case 'hidden':
                echo "<input type='hidden' name='rsgd_hidden[" . esc_attr($name) . "]' class='dep-inp form-control " . esc_attr($class) . "' id='" . esc_attr($name) . "'  value='" . esc_attr($val) . "'  />";
                break;

            case 'radio':

                foreach ($choices as $key => $value) {
                    echo ' <div class="' . esc_attr($class) . '"><input id="' . esc_attr($name) . '" data-name="' . esc_attr($value) . '" type="radio" name="' . esc_attr($key) . '" value="' . esc_attr($key) . '"';
                    if ($key == $val) {
                        echo 'checked="checked"';
                    }
                    echo '><label class="radio-lbl">'.esc_attr($value).'</label></div>';
                }
                echo '<input class="rsgd-choose-skin" id="' . esc_attr($name) . '" data-name="' . esc_attr($value) . '" type="hidden" name="rsgd_data[' . esc_attr($name) . ']" value="' . esc_attr($value) . '" />';
                break;

            case 'dropdown':
                if ($name == 'rsgd_select_taxonomy') {
                    echo '<select name="rsgd_data[' . esc_attr($name) . ']" id="' . esc_attr($name) . '" class="dep-inp form-control ' . esc_attr($class) . '"  id="nav_select">';
                } else {
                    echo '<select name="rsgd_data[' . esc_attr($name) . ']" id="' . esc_attr($name) . '" class="dep-inp form-control ' . esc_attr($class) . '">';
                }
                foreach ($choices as $key => $value) {
                    echo '<option value="' . esc_attr( $key ) . '" ';

                    if ($val == $key) {
                        echo ' selected="selected"';
                    }
                    echo ' >' . esc_attr($value) . '</option>';
                }
                echo '</select>';
                break;
                
            case 'multidropdown':

                echo '<select multiple="multiple" data-nam="' . esc_attr($name) . '" class="dep-inp form-control">';
                    foreach ($choices as $key => $value) {
                        echo '<option value="' . esc_attr($key) . '">' . wp_kses($value, true) . '</option>';
                    }
                echo '</select>';
                echo "<input type='hidden' name='rsgd_data[" . esc_attr($name) . "]' class='dep-inp form-control " . esc_attr($class) . "' id='" . esc_attr($name) . "'  value='" . esc_attr($val) . "'  />";
                break;
            
            case 'taxsdropdown':

                echo '<select multiple="multiple" data-nam="' . esc_attr($name) . '" class="dep-inp form-control">';

                    foreach ( rsgd_post_types() as $post_typ => $typ ) {

                        $taxonomies = get_object_taxonomies( $post_typ );
                                                
                        foreach ($taxonomies as $tax){
                                                        
                            $terms = get_terms( $tax, array( 'hide_empty' => false ));
                            if ( ! empty( $terms ) && ! is_wp_error( $terms ) ){
                                echo '<option class="'.esc_attr($tax).' dis_opt" data-type="'.esc_attr($post_typ).'" disabled> -- '.wp_kses($tax, true).' -- </option>';
                                foreach ( $terms as $term ) {
                                    echo '<option class="'.esc_attr($tax).'" data-type="'.esc_attr($post_typ).'" value="'.esc_attr($tax).'||'.esc_attr($term->slug).'||'.esc_attr($term->name).'||'.esc_attr($term->count).'">'.wp_kses($term->name, true).' ('.wp_kses($term->count, true).' Items)'. ' [ Slug: '.wp_kses($term->slug, true).']</option>';
                                }
                            }
                        }
                    }
                
                echo '</select>';
                echo "<input type='hidden' name='rsgd_data[" . esc_attr($name) . "]' class='dep-inp form-control " . esc_attr($class) . "' id='" . esc_attr($name) . "'  value='" . esc_attr($val) . "'  />";
                break;

            case 'number':

                echo '<div class="slidernum" data-min="' . esc_attr( $min ) . '" data-max="' . esc_attr( $max ) . '"></div>';
                echo '<input type="number" name="rsgd_data[' . esc_attr($name) . ']" id="' . esc_attr($name) . '" class="num-txt dep-fld form-control ' . esc_attr($class) . '" id="' . esc_attr($name) . '" placeholder="' . esc_attr($placeholder) . '" value="' . sanitize_text_field($val) . '" />';
                break;
                
            case 'color':
                    echo '<input class="rsgd_color'. esc_attr($class) .'" type="text"  data-alpha="true" id="' . esc_attr($name) . '" name="rsgd_data[' . esc_attr($name) . ']" placeholder="' . esc_attr($placeholder) . '" value="' . esc_attr( $val ) . '" />';
                break; 
                
            case 'twonumber':
                $firstVal = explode('|', $val );
                $lastVal = substr( $val , strpos( $val , "|") + 1);
                    echo '<input class="form-control rsgd_num-txt no-slider rsgd_firstVL" type="number" placeholder="' . esc_attr($firstVal[0]) . '" value="' . sanitize_text_field($firstVal[0]) . '" /> : ';
                    echo '<input class="form-control rsgd_num-txt no-slider rsgd_lastVL" type="number" placeholder="' . esc_attr($lastVal) . '" value="' . sanitize_text_field($lastVal) . '" />';
                    echo '<input class="rsgd_hid_two_num ' . esc_attr($class) . '" type="hidden" id="' . esc_attr($name) . '" name="rsgd_data[' . esc_attr($name) . ']" placeholder="' . esc_attr($placeholder) . '" value="' . sanitize_text_field($val) . '" />';
                break;

            case 'checkbox':

                echo '<input type="hidden" id="'. esc_attr($name) .'" class="dep-inp checktxt ' . esc_attr($class) . '" value= "' . esc_attr($val) . '" name="rsgd_data[' . esc_attr($name) . ']"  />';
                echo '<span class="rsgd_chk"><span class="rsgd_switch"></span></span>';
                break;

            case 'textarea':
            
                echo '<textarea type="text" id="' . esc_attr($name) . '" placeholder="' . esc_attr($placeholder) . '"  class="form-control ' . esc_attr($class) . '" name="rsgd_data[' . esc_attr($name) . ']" style="width: 100%">' . sanitize_textarea_field($val) . '</textarea>';
                break;

            default:
                break;
        }

    }

}
new raysgrid_Field();