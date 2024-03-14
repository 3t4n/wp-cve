<?php


class ClassWPCounterUpMetaForm {
    
    public function __construct() {
    }




    /**
     *  textTypo : 11.09.22
     */

    public function bgColorTypo(array $args) {
        global $post;

        if ( ! isset( $args['id_color'], $args['name_color'] , $args['label_color'] ) ) {
            return;
        }

        // Color
        $default_value_color = isset( $args['default_color'] ) ? $args['default_color'] : '';
        $meta_color          = get_post_meta( $post->ID, '_save_meta_lgx_counter_generator', true );
        $meta_value_color    = (! empty( $meta_color[$args['id_color']] ) ? $meta_color[$args['id_color']] : $default_value_color);

        $status_color           = (isset( $args['status_color'] ) ? $args['status_color'] : '');
        $is_pro_color           = (( $status_color == 'disabled') ? 'disabled' : '');
        $is_pro_label_color     = (( $status_color == 'disabled') ? '<span class="lgx_meta_field_mark_pro_wrap"><span class="lgx_meta_field_mark">'.__('Pro', 'wp-counter-up').'</span></span>' : '');

        // label_hColor
        $default_value_hColor = isset( $args['default_hColor'] ) ? $args['default_hColor'] : '';
        $meta_hColor          = get_post_meta( $post->ID, '_save_meta_lgx_counter_generator', true );
        $meta_value_hColor    = (! empty( $meta_hColor[$args['id_hColor']] ) ? $meta_hColor[$args['id_hColor']] : $default_value_hColor);

        $status_hColor           = (isset( $args['status_hColor'] ) ? $args['status_hColor'] : '');
        $is_pro_hColor           = (( $status_hColor == 'disabled') ? 'disabled' : '');
        $is_pro_label_hColor     = (( $status_hColor == 'disabled') ? '<span class="lgx_meta_field_mark_pro_wrap"><span class="lgx_meta_field_mark">'.__('Pro', 'wp-counter-up').'</span></span>' : '');


        //Start 

        $output= '<tr>';
        $output.= '<th scope="row">';
        $output.= '<h4 class="lgx_app_meta_label"><label for="'.$args['id'].'">'. $args['label'].'</label></h4>';
        $output.= '<p class="lgx_input_desc lgx_app_meta_desc">'. $args['desc'].'</p>';
        $output.= '</th>';    

        $output.= '<td>';
        $output.= '<div class="lgx_group_field_wrap">';

        // Item
        $output.= '<div class="lgx_group_field_item">';
        $output.= '<div class="lgx_group_field_label">'.$args['label_color'].'</div>';
        $output.= '<input type="text" value="'.$meta_value_color.'" id="'.$args['id_color'].'" class="lgx_input_width lgx_app_meta_filed lgx_color_picker" name="'. $args['name_color'].'" data-default-color="'.$meta_value_color.'" data-alpha-enabled="true" '.$is_pro_color.'>';
        $output.= $is_pro_label_color;
        $output.= '</div>';//item 1

        $output.= '<div class="lgx_group_field_item">';
        $output.= '<div class="lgx_group_field_label">'.$args['label_hColor'].'</div>';
        $output.= '<input type="text" value="'.$meta_value_hColor.'" id="'.$args['id_hColor'].'" class="lgx_input_width lgx_app_meta_filed lgx_color_picker" name="'. $args['name_hColor'].'" data-default-color="'.$meta_value_hColor.'" data-alpha-enabled="true" '.$is_pro_hColor.'>';
        $output.= $is_pro_label_hColor;
        $output.= '</div>';//item 2

         // Item End
        $output.= '</div>';//wrap
       //$output.= $is_pro_label;
        $output.= '</td>';

        $output.= '</tr>';

        echo force_balance_tags($output);
     }



    /**
     *  textTypo : 11.09.22
     */

    public function borderTypo(array $args) {
        global $post;

        if ( ! isset( $args['id_color'], $args['name_color'] , $args['label_color'] ) ) {
            return;
        }

        // Color
        $default_value_color = isset( $args['default_color'] ) ? $args['default_color'] : '';
        $meta_color          = get_post_meta( $post->ID, '_save_meta_lgx_counter_generator', true );
        $meta_value_color    = (! empty( $meta_color[$args['id_color']] ) ? $meta_color[$args['id_color']] : $default_value_color);

        $status_color           = (isset( $args['status_color'] ) ? $args['status_color'] : '');
        $is_pro_color           = (( $status_color == 'disabled') ? 'disabled' : '');
        $is_pro_label_color     = (( $status_color == 'disabled') ? '<span class="lgx_meta_field_mark_pro_wrap"><span class="lgx_meta_field_mark">'.__('Pro', 'wp-counter-up').'</span></span>' : '');


        // width
        $default_value_width= isset( $args['default_width'] ) ? $args['default_width'] : '';
        $meta_width          = get_post_meta( $post->ID, '_save_meta_lgx_counter_generator', true );
        $meta_value_width    = (! empty( $meta_width[$args['id_width']] ) ? $meta_width[$args['id_width']] : $default_value_width);

        $status_width           = (isset( $args['status_width'] ) ? $args['status_width'] : '');
        $is_pro_width           = (( $status_width == 'disabled') ? 'disabled' : '');
        $is_pro_label_width    = (( $status_width== 'disabled') ? '<span class="lgx_meta_field_mark_pro_wrap"><span class="lgx_meta_field_mark">'.__('Pro', 'wp-counter-up').'</span></span>' : '');


        // Radius
        $default_value_radius = isset( $args['default_radius'] ) ? $args['default_radius'] : '';
        $meta_radius          = get_post_meta( $post->ID, '_save_meta_lgx_counter_generator', true );
        $meta_value_radius    = (! empty( $meta_radius[$args['id_radius']] ) ? $meta_radius[$args['id_radius']] : $default_value_radius);

        $status_radius           = (isset( $args['status_radius'] ) ? $args['status_radius'] : '');
        $is_pro_radius           = (( $status_radius == 'disabled') ? 'disabled' : '');
        $is_pro_label_radius     = (( $status_radius == 'disabled') ? '<span class="lgx_meta_field_mark_pro_wrap"><span class="lgx_meta_field_mark">'.__('Pro', 'wp-counter-up').'</span></span>' : '');

        // label_hColor
        $default_value_hColor = isset( $args['default_hColor'] ) ? $args['default_hColor'] : '';
        $meta_hColor          = get_post_meta( $post->ID, '_save_meta_lgx_counter_generator', true );
        $meta_value_hColor    = (! empty( $meta_hColor[$args['id_hColor']] ) ? $meta_hColor[$args['id_hColor']] : $default_value_hColor);

        $status_hColor           = (isset( $args['status_hColor'] ) ? $args['status_hColor'] : '');
        $is_pro_hColor           = (( $status_hColor == 'disabled') ? 'disabled' : '');
        $is_pro_label_hColor     = (( $status_hColor == 'disabled') ? '<span class="lgx_meta_field_mark_pro_wrap"><span class="lgx_meta_field_mark">'.__('Pro', 'wp-counter-up').'</span></span>' : '');


        //Start 

        $output= '<tr>';
        $output.= '<th scope="row">';
        $output.= '<h4 class="lgx_app_meta_label"><label for="'.$args['id'].'">'. $args['label'].'</label></h4>';
        $output.= '<p class="lgx_input_desc lgx_app_meta_desc">'. $args['desc'].'</p>';
        $output.= '</th>';    

        $output.= '<td>';
        $output.= '<div class="lgx_group_field_wrap">';

        // Item
        $output.= '<div class="lgx_group_field_item">';
        $output.= '<div class="lgx_group_field_label">'.$args['label_color'].'</div>';
        $output.= '<input type="text" value="'.$meta_value_color.'" id="'.$args['id_color'].'" class="lgx_input_width lgx_app_meta_filed lgx_color_picker" name="'. $args['name_color'].'" data-default-color="'.$meta_value_color.'" data-alpha-enabled="true" '.$is_pro_color.'>';
        $output.= $is_pro_label_color;
        $output.= '</div>';//item 1

        $output.= '<div class="lgx_group_field_item">';
        $output.= '<div class="lgx_group_field_label">'.$args['label_width'].'</div>';
        $output.= '<input type="text" value="'.$meta_value_width.'" placeholder="'.$meta_value_width.'"  id="'.$args['id_width'].'" class="lgx_input_width lgx_app_meta_filed lgx_app_meta_text" name="'. $args['name_width'].'" '.$is_pro_width.'>';
        $output.= $is_pro_label_width;
        $output.= '</div>';//item 2

        $output.= '<div class="lgx_group_field_item">';
        $output.= '<div class="lgx_group_field_label">'.$args['label_radius'].'</div>';
        $output.= '<input type="text" value="'.$meta_value_radius.'" placeholder="'.$meta_value_radius.'"  id="'.$args['id_radius'].'" class="lgx_input_radius lgx_app_meta_filed lgx_app_meta_text" name="'. $args['name_radius'].'" '.$is_pro_radius.'>';
        $output.= $is_pro_label_radius;
        $output.= '</div>';//item 3

        $output.= '<div class="lgx_group_field_item">';
        $output.= '<div class="lgx_group_field_label">'.$args['label_hColor'].'</div>';
        $output.= '<input type="text" value="'.$meta_value_hColor.'" id="'.$args['id_hColor'].'" class="lgx_input_width lgx_app_meta_filed lgx_color_picker" name="'. $args['name_hColor'].'" data-default-color="'.$meta_value_hColor.'" data-alpha-enabled="true" '.$is_pro_hColor.'>';
        $output.= $is_pro_label_hColor;
        $output.= '</div>';//item 4

         // Item End
        $output.= '</div>';//wrap
       //$output.= $is_pro_label;
        $output.= '</td>';

        $output.= '</tr>';

        echo force_balance_tags($output);
     }


   /**
     *  textTypo : 11.09.22
     */

    public function textMulti(array $args) {
        global $post;

        if ( ! isset( $args['id_1'], $args['name_1'] , $args['label_1'] ) ) {
            return;
        }

      

        // Group Item1 1
        $item_html_1 = '';

        if( isset($args['id_1'])) {
        
        $default_value_1    = isset( $args['default_1'] ) ? $args['default_1'] : '';
        $meta_1             = get_post_meta( $post->ID, '_save_meta_lgx_counter_generator', true );
        $meta_value_1       = (! empty( $meta_1[$args['id_1']] ) ? $meta_1[$args['id_1']] : $default_value_1);

        $status_1           = (isset( $args['status_1'] ) ? $args['status_1'] : '');
        $is_pro_1           = (( $status_1 == 'disabled') ? 'disabled' : '');
        $is_pro_label_1     = (( $status_1== 'disabled') ? '<span class="lgx_meta_field_mark_pro_wrap"><span class="lgx_meta_field_mark">'.__('Pro', 'wp-counter-up').'</span></span>' : '');

        $item_html_1    = '<div class="lgx_group_field_item">';
        $item_html_1    .= '<div class="lgx_group_field_label">'.$args['label_1'].'</div>';
        $item_html_1    .= '<input type="text" value="'.$meta_value_1.'" placeholder="'.$meta_value_1.'"  id="'.$args['id_1'].'" class="lgx_input_width lgx_app_meta_filed lgx_app_meta_text" name="'. $args['name_1'].'" '.$is_pro_1.'>';
        $item_html_1    .= $is_pro_label_1;
        $item_html_1    .= '</div>';//item 1

        }



         // Group Item 2
         $item_html_2 = '';

         if( isset($args['id_2'])) {
             
             $default_value_2    = isset( $args['default_2'] ) ? $args['default_2'] : '';
             $meta_2             = get_post_meta( $post->ID, '_save_meta_lgx_counter_generator', true );
             $meta_value_2       = (! empty( $meta_2[$args['id_2']] ) ? $meta_2[$args['id_2']] : $default_value_1);
     
             $status_2           = (isset( $args['status_2'] ) ? $args['status_2'] : '');
             $is_pro_2           = (( $status_2 == 'disabled') ? 'disabled' : '');
             $is_pro_label_2     = (( $status_2== 'disabled') ? '<span class="lgx_meta_field_mark_pro_wrap"><span class="lgx_meta_field_mark">'.__('Pro', 'wp-counter-up').'</span></span>' : '');
     
             $item_html_2    = '<div class="lgx_group_field_item">';
             $item_html_2    .= '<div class="lgx_group_field_label">'.$args['label_2'].'</div>';
             $item_html_2    .= '<input type="text" value="'.$meta_value_2.'" placeholder="'.$meta_value_2.'"  id="'.$args['id_2'].'" class="lgx_input_width lgx_app_meta_filed lgx_app_meta_text" name="'. $args['name_2'].'" '.$is_pro_2.'>';
             $item_html_2    .= $is_pro_label_2;
             $item_html_2    .= '</div>';//item 2

         }


          // Group Item 3
          $item_html_3 = '';

          if( isset($args['id_3'])) {
              
              $default_value_3    = isset( $args['default_3'] ) ? $args['default_3'] : '';
              $meta_3             = get_post_meta( $post->ID, '_save_meta_lgx_counter_generator', true );
              $meta_value_3       = (! empty( $meta_3[$args['id_3']] ) ? $meta_3[$args['id_3']] : $default_value_3);
      
              $status_3          = (isset( $args['status_3'] ) ? $args['status_3'] : '');
              $is_pro_3           = (( $status_3 == 'disabled') ? 'disabled' : '');
              $is_pro_label_3    = (( $status_3== 'disabled') ? '<span class="lgx_meta_field_mark_pro_wrap"><span class="lgx_meta_field_mark">'.__('Pro', 'wp-counter-up').'</span></span>' : '');
      
              $item_html_3    = '<div class="lgx_group_field_item">';
              $item_html_3    .= '<div class="lgx_group_field_label">'.$args['label_3'].'</div>';
              $item_html_3    .= '<input type="text" value="'.$meta_value_3.'" placeholder="'.$meta_value_3.'"  id="'.$args['id_3'].'" class="lgx_input_width lgx_app_meta_filed lgx_app_meta_text" name="'. $args['name_3'].'" '.$is_pro_3.'>';
              $item_html_3    .= $is_pro_label_3;
              $item_html_3    .= '</div>';//item 3      
            
          }

           // Group Item 4
           $item_html_4 = '';

           if( isset($args['id_4'])) {
               
               $default_value_4    = isset( $args['default_4'] ) ? $args['default_4'] : '';
               $meta_4             = get_post_meta( $post->ID, '_save_meta_lgx_counter_generator', true );
               $meta_value_4       = (! empty( $meta_4[$args['id_4']] ) ? $meta_4[$args['id_4']] : $default_value_4);
       
               $status_4          = (isset( $args['status_4'] ) ? $args['status_4'] : '');
               $is_pro_4           = (( $status_4 == 'disabled') ? 'disabled' : '');
               $is_pro_label_4    = (( $status_4== 'disabled') ? '<span class="lgx_meta_field_mark_pro_wrap"><span class="lgx_meta_field_mark">'.__('Pro', 'wp-counter-up').'</span></span>' : '');
       
               $item_html_4    = '<div class="lgx_group_field_item">';
               $item_html_4    .= '<div class="lgx_group_field_label">'.$args['label_4'].'</div>';
               $item_html_4    .= '<input type="text" value="'.$meta_value_4.'" placeholder="'.$meta_value_4.'"  id="'.$args['id_4'].'" class="lgx_input_width lgx_app_meta_filed lgx_app_meta_text" name="'. $args['name_4'].'" '.$is_pro_4.'>';
               $item_html_4    .= $is_pro_label_4;
               $item_html_4    .= '</div>';//item 3      
             
           }

             // Group Item 5

             $item_html_5 = '';

             if( isset($args['id_5'])) {
                 
                 $default_value_5    = isset( $args['default_5'] ) ? $args['default_5'] : '';
                 $meta_5             = get_post_meta( $post->ID, '_save_meta_lgx_counter_generator', true );
                 $meta_value_5       = (! empty( $meta_5[$args['id_5']] ) ? $meta_5[$args['id_5']] : $default_value_5);
         
                 $status_5          = (isset( $args['status_5'] ) ? $args['status_5'] : '');
                 $is_pro_5           = (( $status_5 == 'disabled') ? 'disabled' : '');
                 $is_pro_label_5    = (( $status_5== 'disabled') ? '<span class="lgx_meta_field_mark_pro_wrap"><span class="lgx_meta_field_mark">'.__('Pro', 'wp-counter-up').'</span></span>' : '');
         
                 $item_html_5    = '<div class="lgx_group_field_item">';
                 $item_html_5    .= '<div class="lgx_group_field_label">'.$args['label_5'].'</div>';
                 $item_html_5    .= '<input type="text" value="'.$meta_value_5.'" placeholder="'.$meta_value_5.'"  id="'.$args['id_5'].'" class="lgx_input_width lgx_app_meta_filed lgx_app_meta_text" name="'. $args['name_5'].'" '.$is_pro_5.'>';
                 $item_html_5    .= $is_pro_label_5;
                 $item_html_5    .= '</div>';//item 3      
               
             }

               // Group Item 6

               $item_html_6 = '';

               if( isset($args['id_6'])) {
                   
                   $default_value_6    = isset( $args['default_6'] ) ? $args['default_6'] : '';
                   $meta_6             = get_post_meta( $post->ID, '_save_meta_lgx_counter_generator', true );
                   $meta_value_6       = (! empty( $meta_6[$args['id_6']] ) ? $meta_6[$args['id_6']] : $default_value_6);
           
                   $status_6          = (isset( $args['status_6'] ) ? $args['status_6'] : '');
                   $is_pro_6           = (( $status_6 == 'disabled') ? 'disabled' : '');
                   $is_pro_label_6    = (( $status_6== 'disabled') ? '<span class="lgx_meta_field_mark_pro_wrap"><span class="lgx_meta_field_mark">'.__('Pro', 'wp-counter-up').'</span></span>' : '');
           
                   $item_html_6    = '<div class="lgx_group_field_item">';
                   $item_html_6    .= '<div class="lgx_group_field_label">'.$args['label_6'].'</div>';
                   $item_html_6    .= '<input type="text" value="'.$meta_value_6.'" placeholder="'.$meta_value_6.'"  id="'.$args['id_6'].'" class="lgx_input_width lgx_app_meta_filed lgx_app_meta_text" name="'. $args['name_6'].'" '.$is_pro_6.'>';
                   $item_html_6    .= $is_pro_label_6;
                   $item_html_6    .= '</div>';//item 3      
                 
               }

    
        // Output
        $output= '<tr>';

        $output.= '<th scope="row">';
        $output.= '<h4 class="lgx_app_meta_label"><label for="'.$args['id'].'">'. $args['label'].'</label></h4>';
        $output.= '<p class="lgx_input_desc lgx_app_meta_desc">'. $args['desc'].'</p>';
        $output.= '</th>';    

        $output.= '<td>';
        $output.= '<div class="lgx_group_field_wrap">';

        $output.= (( isset($args['id_1']))  ? $item_html_1 : '');
        $output.= (( isset($args['id_2']))  ? $item_html_2 : '');
        $output.= (( isset($args['id_3']))  ? $item_html_3 : '');
        $output.= (( isset($args['id_3']))  ? $item_html_4 : '');
        $output.= (( isset($args['id_3']))  ? $item_html_5 : '');
        $output.= (( isset($args['id_3']))  ? $item_html_6 : '');

         // Item End
        $output.= '</div>';//wrap
        $output.= '</td>';

        $output.= '</tr>';

        echo force_balance_tags($output);
     }



    /**
     *  textTypo : 11.09.22
     */

     public function textTypo(array $args) {
        global $post;

        if ( ! isset( $args['id_color'], $args['name_color'] , $args['label_color'] ) ) {
            return;
        }

        // Color
        $default_value_color = isset( $args['default_color'] ) ? $args['default_color'] : '';
        $meta_color          = get_post_meta( $post->ID, '_save_meta_lgx_counter_generator', true );
        $meta_value_color    = (! empty( $meta_color[$args['id_color']] ) ? $meta_color[$args['id_color']] : $default_value_color);

        $status_color           = (isset( $args['status_color'] ) ? $args['status_color'] : '');
        $is_pro_color           = (( $status_color == 'disabled') ? 'disabled' : '');
        $is_pro_label_color     = (( $status_color == 'disabled') ? '<span class="lgx_meta_field_mark_pro_wrap"><span class="lgx_meta_field_mark">'.__('Pro', 'wp-counter-up').'</span></span>' : '');


        // Size
        $default_value_size = isset( $args['default_size'] ) ? $args['default_size'] : '';
        $meta_size          = get_post_meta( $post->ID, '_save_meta_lgx_counter_generator', true );
        $meta_value_size    = (! empty( $meta_size[$args['id_size']] ) ? $meta_size[$args['id_size']] : $default_value_size);

        $status_size           = (isset( $args['status_size'] ) ? $args['status_size'] : '');
        $is_pro_size           = (( $status_size == 'disabled') ? 'disabled' : '');
        $is_pro_label_size     = (( $status_size == 'disabled') ? '<span class="lgx_meta_field_mark_pro_wrap"><span class="lgx_meta_field_mark">'.__('Pro', 'wp-counter-up').'</span></span>' : '');


        // Weight
        $default_value_weight = isset( $args['default_weight'] ) ? $args['default_weight'] : '';
        $meta_weight          = get_post_meta( $post->ID, '_save_meta_lgx_counter_generator', true );
        $meta_value_weight    = (! empty( $meta_weight[$args['id_weight']] ) ? $meta_weight[$args['id_weight']] : $default_value_weight);

        $status_weight           = (isset( $args['status_weight'] ) ? $args['status_weight'] : '');
        $is_pro_weight           = (( $status_weight == 'disabled') ? 'disabled' : '');
        $is_pro_label_weight     = (( $status_weight == 'disabled') ? '<span class="lgx_meta_field_mark_pro_wrap"><span class="lgx_meta_field_mark">'.__('Pro', 'wp-counter-up').'</span></span>' : '');


        //Start 

        $output= '<tr>';
        $output.= '<th scope="row">';
        $output.= '<h4 class="lgx_app_meta_label"><label for="'.$args['id'].'">'. $args['label'].'</label></h4>';
        $output.= '<p class="lgx_input_desc lgx_app_meta_desc">'. $args['desc'].'</p>';
        $output.= '</th>';    

        $output.= '<td>';
        $output.= '<div class="lgx_group_field_wrap">';

        // Item
        $output.= '<div class="lgx_group_field_item">';
        $output.= '<div class="lgx_group_field_label">'.$args['label_color'].'</div>';
        $output.= '<input type="text" value="'.$meta_value_color.'" id="'.$args['id_color'].'" class="lgx_input_width lgx_app_meta_filed lgx_color_picker" name="'. $args['name_color'].'" data-default-color="'.$meta_value_color.'" data-alpha-enabled="true" '.$is_pro_color.'>';
        $output.= $is_pro_label_color;
        $output.= '</div>';//item 1

        $output.= '<div class="lgx_group_field_item">';
        $output.= '<div class="lgx_group_field_label">'.$args['label_size'].'</div>';
        $output.= '<input type="text" value="'.$meta_value_size.'" placeholder="'.$meta_value_size.'"  id="'.$args['id_size'].'" class="lgx_input_width lgx_app_meta_filed lgx_app_meta_text" name="'. $args['name_size'].'" '.$is_pro_size.'>';
        $output.= $is_pro_label_size;
        $output.= '</div>';//item 2

        $output.= '<div class="lgx_group_field_item">';
        $output.= '<div class="lgx_group_field_label">'.$args['label_weight'].'</div>';
        $output.= '<input type="text" value="'.$meta_value_weight.'" placeholder="'.$meta_value_weight.'"  id="'.$args['id_weight'].'" class="lgx_input_width lgx_app_meta_filed lgx_app_meta_text" name="'. $args['name_weight'].'" '.$is_pro_weight.'>';
        $output.= $is_pro_label_weight;
        $output.= '</div>';//item 3


         // Item End
        $output.= '</div>';//wrap
       //$output.= $is_pro_label;
        $output.= '</td>';

        $output.= '</tr>';

        echo force_balance_tags($output);
     }


    /**
     * @param array $args
     */

    public function group2SelectText( array $args ) {
        global $post;

        if ( ! isset( $args['id'], $args['name'] , $args['label'] ) ) {
            return;
        }

        if ( ! isset( $args['options_select'] ) && count( $args['options_select'] ) < 1 ) {
            return;
        }

        $status       = (isset( $args['status'] ) ? $args['status'] : '');
        $is_pro       = (( $status == 'disabled') ? 'disabled' : '');
        $is_pro_label = (( $status== 'disabled') ? '<span class="lgx_meta_field_mark_pro_wrap"><span class="lgx_meta_field_mark">'.__('Pro', 'wp-counter-up').'</span></span>' : '');

        $output= '<tr>';

        $output.= '<th scope="row">';
        $output.= '<h4 class="lgx_app_meta_label"><label for="'.$args['id'].'">'. $args['label'].'</label></h4>';
        $output.= '<p class="lgx_input_desc lgx_app_meta_desc">'. $args['desc'].'</p>';
        $output.= '</th>';

        //Select
        $default_value = isset( $args['default_select'] ) ? $args['default_select'] : '';
        $meta          = get_post_meta( $post->ID, '_save_meta_lgx_counter_generator', true );
      
        $meta_value    = (! empty( $meta[$args['id_select']] ) ? $meta[$args['id_select']] : $default_value);

       //Text
       $default_value_text = isset( $args['default_text'] ) ? $args['default_text'] : '';
       $meta_text          = get_post_meta( $post->ID, '_save_meta_lgx_counter_generator', true );
       $meta_value_text    = (! empty( $meta_text[$args['id_text']] ) ? $meta_text[$args['id_text']] : $default_value_text);


        $output.= '<td>';
        $output.= '<div class="lgx_group_field_wrap">';
        $output.= '<div class="lgx_group_field_item">';
        $output.= '<div class="lgx_group_field_label">'.$args['label_select'].'</div>';
        $options = '';
        foreach ( $args['options_select'] as $option_value => $option_text ) {
            $selected = ( $option_value == $meta_value ) ? ' selected=selected' : '';
            $pro_disabled_label = ((($is_pro == 'disabled') && ($option_value !== $meta_value )) ? 'disabled="disabled"' : '');
            $options .= '<option value="'. $option_value .'" '.$selected.'  '.$pro_disabled_label.'>'. $option_text . ((($is_pro == 'disabled') && ($option_value !== $meta_value )) ? ' ( Pro )' : '').'</option>';
        }

        $is_multiple = ( isset( $args['multiple'] ) && ($args['multiple'] == 'yes')  ? 'multiple' : '');

        $output.= '<select name="'. $args['name_select'].'" class="lgx_input_width lgx_app_meta_filed lgx_app_meta_select '. (($is_pro == 'disabled') ? 'lgx_app_meta_select_disabled' : '').'"  id="'.$args['id_select'].'" '.$is_multiple.'>';
        $output.= $options;

        $output.= '</select>';
        $output.= '</div>';//item 1
        $output.= '<div class="lgx_group_field_item">';
        $output.= '<div class="lgx_group_field_label">'.$args['label_text'].'</div>';

        $output.= '<input type="text" value="'.$meta_value_text.'" placeholder="'.$meta_value_text.'"  id="'.$args['id_text'].'" class="lgx_input_width lgx_app_meta_filed lgx_app_meta_text" name="'. $args['name_text'].'" '.$is_pro.'>';
        $output.= $is_pro_label;
        $output.= '</div>';//item 2
        $output.= '</div>';//wrap
       //$output.= $is_pro_label;
        $output.= '</td>';

        $output.= '</tr>';

        echo force_balance_tags($output);
    }


    /* Usage
        text, number, url, textarea, checkbox,select, color, upload
     *
     * 'multiple' => 'yes'// no
     * 'status'=>'disabled',// enabled
     *

    $this->meta_form->checkbox(
    array(
        'label'   => __( 'Show Brand Name', $this->plugin_name ),
        'desc'    => __( 'Show brand name in your showcase.', $this->plugin_name ),
        'name'    => 'meta_lgx_lsp_shortcodes[lgx_brand_name_en]',
        'id'      => 'lgx_brand_name_en',
       // 'status'  => LGX_LS_PLUGIN_META_FIELD_PRO,
        'default' => 'no'
    )
);



        /**
     * @param array $args
     *
     *   title, text , link
     */

    public function buy_pro( array $args ) {
        global $post;

        if ((! isset( $args['status'] ) || ($args['status'] != 'disabled')) ) {
            return;
        }

        if ( ! isset( $args['text'] ) ) {
            $args['text']  = 'To unlock all premium options and enjoy all exclusive  features, please ';
        }

        if ( ! isset( $args['text'] ) ) {
            $args['link']  = 'https://logichunt.com/';
        }

        $output= '<tr>';
        $output.= '<td class="lgx_app_meta_buy_pro_td" colspan="2"><div  class="lgx_app_meta_buy_pro_wrap">';
        $output.= ((isset( $args['title'])) ? '<h3 class="lgx_app_meta_buy_pro_title">'. $args['title'].'</h3>' : '');
        $output.= '<p class="lgx_input_desc lgx_app_meta_buy_pro_desc">'. $args['text'].' <a href="'.esc_url($args['link'] ).'" target="_blank">Upgrade To Pro</a> .</p>';
        $output.= '</div></td>';
        $output.= '</tr>';

        echo force_balance_tags($output);
    }



    /**
     * @param array $args
     */

    public function switch( array $args ) {
        global $post;

        if ( ! isset( $args['id'], $args['name'] , $args['label'] ) ) {
            return;
        }

        $yes_label     = isset( $args['yes_label'] ) ? $args['yes_label'] : __('ON', 'wp-counter-up');
        $no_label    = isset( $args['no_label'] ) ? $args['no_label'] : __('OFF', 'wp-counter-up');
        $status       = isset( $args['status'] ) ? $args['status'] : '';
        $is_pro       = (( $status == 'disabled') ? 'disabled' : '');
        $is_pro_label = (( $status== 'disabled') ? '<span class="lgx_meta_field_mark_pro_wrap"><span class="lgx_meta_field_mark">'.__('Pro', 'wp-counter-up').'</span>' : '');


        $output= '<tr>';

        $output.= '<th scope="row">';
        $output.= '<h4 class="lgx_app_meta_label"><label for="'.$args['id'].'">'. $args['label'].'</label></h4>';
        $output.= '<p class="lgx_input_desc lgx_app_meta_desc">'. $args['desc'].'</p>';
        $output.= '</th>';

        $default_value = isset( $args['default'] ) ? $args['default'] : '';
        $meta          = get_post_meta( $post->ID, '_save_meta_lgx_counter_generator', true );
        $meta_value    = (! empty( $meta[$args['id']] ) ? $meta[$args['id']] : $default_value);

        $is_checked = ( $meta_value == 'yes') ? 'checked' : '';

        $output.= '<td>';
        $output.='<label class="lgx_switch  '.(($is_pro=='disabled') ? 'lgx_switch_pro' : '').'">';
        $output.= '<input type="checkbox" value="yes" id="'.$args['id'].'" class="lgx_app_meta_filed lgx_app_meta_checkbox" name="'. $args['name'].'" '.$is_checked.' '.$is_pro.' >';
        $output.=' <div class="lgx_switch_slider lgx_switch_round">';
        $output.='<span class="lgx_switch_on">'.$yes_label.'</span>';
        $output.='<span class="lgx_switch_off">'.$no_label.'</span>';
        $output.='</div>';
        $output.='</label>';
        $output.= $is_pro_label;
        $output.= '</td>';

        $output.= '</tr>';

        echo force_balance_tags($output);
    }



    /**
     * @param array $args
     *
     *  id, name, label, value, default
     */

    public function header_spacer( array $args ) {
        global $post;

        if ( ! isset( $args['label'] ) ) {
            return;
        }


        $output= '<tr>';
        $output.= '<td colspan="2" >';
        $output.= '<div  class="lgx_app_meta_header_spacer"><h3>'. $args['label'].'</h3></div>';
        //$output.= '<p class="lgx_input_desc lgx_app_meta_desc">'. $args['desc'].'</p>';
        $output.= '</td>';
        $output.= '</tr>';

        echo force_balance_tags($output);
    }


    /**
     * @param array $args
     *
     *  id, name, label, value, default
     */

    public function text( array $args ) {
        global $post;

        if ( ! isset( $args['id'], $args['name'] , $args['label'] ) ) {
            return;
        }


        $status        = isset( $args['status'] ) ? $args['status'] : '';
        $is_pro       = (( $status == 'disabled') ? 'disabled' : '');
        $is_pro_label = (( $status== 'disabled') ? '<span class="lgx_meta_field_mark_pro_wrap"><span class="lgx_meta_field_mark">'.__('Pro', 'wp-counter-up').'</span></span>' : '');


        $output= '<tr>';

        $output.= '<th scope="row">';
        $output.= '<h4 class="lgx_app_meta_label"><label for="'.$args['id'].'">'. $args['label'].'</label></h4>';
        $output.= '<p class="lgx_input_desc lgx_app_meta_desc">'. $args['desc'].'</p>';
        $output.= '</th>';

        $default_value = isset( $args['default'] ) ? $args['default'] : '';
        $meta          = get_post_meta( $post->ID, '_save_meta_lgx_counter_generator', true );
        $meta_value    = (! empty( $meta[$args['id']] ) ? $meta[$args['id']] : $default_value);

        $output.= '<td>';
        $output.= '<input type="text" value="'.$meta_value.'" placeholder="'.$meta_value.'"  id="'.$args['id'].'" class="lgx_input_width lgx_app_meta_filed lgx_app_meta_text" name="'. $args['name'].'" '.$is_pro.'>';
        $output.= $is_pro_label;
        $output.= '</td>';

        $output.= '</tr>';

        echo force_balance_tags($output);
    }



    /**
     * @param array $args
     */
    public function number( array $args ) {
        global $post;

        if ( ! isset( $args['id'], $args['name'] , $args['label'] ) ) {
            return;
        }

        $status        = isset( $args['status'] ) ? $args['status'] : '';
        $is_pro       = (( $status == 'disabled') ? 'disabled' : '');
        $is_pro_label = (( $status== 'disabled') ? '<span class="lgx_meta_field_mark_pro_wrap"><span class="lgx_meta_field_mark">'.__('Pro', 'wp-counter-up').'</span></span>' : '');

        $output= '<tr>';

        $output.= '<th scope="row">';
        $output.= '<h4 class="lgx_app_meta_label"><label for="'.$args['id'].'">'. $args['label'].'</label></h4>';
        $output.= '<p class="lgx_input_desc lgx_app_meta_desc">'. $args['desc'].'</p>';
        $output.= '</th>';

        $default_value = isset( $args['default'] ) ? $args['default'] : '';
        $meta          = get_post_meta( $post->ID, '_save_meta_lgx_counter_generator', true );
        $meta_value    = (! empty( $meta[$args['id']] ) ? $meta[$args['id']] : $default_value);

        $output.= '<td>';
        $output.= '<input type="number" value="'.$meta_value.'" placeholder="'.$meta_value.'"  id="'.$args['id'].'" class="lgx_input_width lgx_app_meta_filed lgx_app_meta_number" name="'. $args['name'].'" '.$is_pro.'>';
        $output.= $is_pro_label;
        $output.= '</td>';

        $output.= '</tr>';

        echo force_balance_tags($output);
    }


    /**
     * @param array $args
     */
    public function url( array $args ) {
        global $post;

        if ( ! isset( $args['id'], $args['name'] , $args['label'] ) ) {
            return;
        }

        $status        = isset( $args['status'] ) ? $args['status'] : '';
        $is_pro       = (( $status == 'disabled') ? 'disabled' : '');
        $is_pro_label = (( $status== 'disabled') ? '<span class="lgx_meta_field_mark_pro_wrap"><span class="lgx_meta_field_mark">'.__('Pro', 'wp-counter-up').'</span></span>' : '');

        $output= '<tr>';

        $output.= '<th scope="row">';
        $output.= '<h4 class="lgx_app_meta_label"><label for="'.$args['id'].'">'. $args['label'].'</label></h4>';
        $output.= '<p class="lgx_input_desc lgx_app_meta_desc">'. $args['desc'].'</p>';
        $output.= '</th>';

        $default_value = isset( $args['default'] ) ? $args['default'] : '';
        $meta          = get_post_meta( $post->ID, '_save_meta_lgx_counter_generator', true );
        $meta_value    = (! empty( $meta[$args['id']] ) ? $meta[$args['id']] : $default_value);

        $output.= '<td>';
        $output.= '<input type="url" value="'.$meta_value.'" id="'.$args['id'].'" class="lgx_app_meta_filed lgx_app_meta_url" name="'. $args['name'].'" '.$is_pro.'>';
        $output.= $is_pro_label;
        $output.= '</td>';

        $output.= '</tr>';

        echo force_balance_tags($output);
    }


    /**
     * @param array $args
     */
    public function textarea( array $args ) {
        global $post;

        if ( ! isset( $args['id'], $args['name'] , $args['label'] ) ) {
            return;
        }

        $status        = isset( $args['status'] ) ? $args['status'] : '';
        $is_pro       = (( $status == 'disabled') ? 'disabled' : '');
        $is_pro_label = (( $status== 'disabled') ? '<span class="lgx_meta_field_mark_pro_wrap"><span class="lgx_meta_field_mark">'.__('Pro', 'wp-counter-up').'</span></span>' : '');

        $output= '<tr>';

        $output.= '<th scope="row">';
        $output.= '<h4 class="lgx_app_meta_label"><label for="'.$args['id'].'">'. $args['label'].'</label></h4>';
        $output.= '<p class="lgx_input_desc lgx_app_meta_desc">'. $args['desc'].'</p>';
        $output.= '</th>';

        $default_value = isset( $args['default'] ) ? $args['default'] : '';
        $meta          = get_post_meta( $post->ID, '_save_meta_lgx_counter_generator', true );
        $meta_value    = (! empty( $meta[$args['id']] ) ? $meta[$args['id']] : $default_value);

        $output.= '<td>';
        $output.= '<textarea  name="'. $args['name'].'" placeholder="'. __( $args['name'], $this->plugin_name ).'"  class="lgx_input_width lgx_app_meta_filed lgx_app_meta_textarea" '.$is_pro.'>'.$meta_value.'</textarea>';
        $output.= $is_pro_label;
        $output.= '</td>';

        $output.= '</tr>';

        echo force_balance_tags($output);
    }



    /**
     * @param array $args
     */

    public function checkbox( array $args ) {
        global $post;

        if ( ! isset( $args['id'], $args['name'] , $args['label'] ) ) {
            return;
        }

        $status        = isset( $args['status'] ) ? $args['status'] : '';
        $is_pro       = (( $status == 'disabled') ? 'disabled' : '');
        $is_pro_label = (( $status== 'disabled') ? '<span class="lgx_meta_field_mark_pro_wrap"><span class="lgx_meta_field_mark">'.__('Pro', 'wp-counter-up').'</span></span>' : '');


        $output= '<tr>';

        $output.= '<th scope="row">';
        $output.= '<h4 class="lgx_app_meta_label"><label for="'.$args['id'].'">'. $args['label'].'</label></h4>';
        $output.= '<p class="lgx_input_desc lgx_app_meta_desc">'. $args['desc'].'</p>';
        $output.= '</th>';

        $default_value = isset( $args['default'] ) ? $args['default'] : '';
        $meta          = get_post_meta( $post->ID, '_save_meta_lgx_counter_generator', true );
        $meta_value    = (! empty( $meta[$args['id']] ) ? $meta[$args['id']] : $default_value);

        $is_checked = ( $meta_value == 'yes') ? 'checked' : '';

        $output.= '<td>';
        $output.= '<input type="checkbox" value="yes" id="'.$args['id'].'" class="lgx_app_meta_filed lgx_app_meta_checkbox" name="'. $args['name'].'" '.$is_checked.' '.$is_pro.' >';
        $output.= $is_pro_label;
        $output.= '</td>';

        $output.= '</tr>';

        echo force_balance_tags($output);
    }


    /**
     * @param array $args
     *  type="'
     */

    public function select( array $args ) {
        global $post;

        if ( ! isset( $args['id'], $args['name'] , $args['label'] ) ) {
            return;
        }

        if ( ! isset( $args['options'] ) && count( $args['options'] ) < 1 ) {
            return;
        }

        $status       = (isset( $args['status'] ) ? $args['status'] : '');
        $is_pro       = (( $status == 'disabled') ? 'disabled' : '');
        $is_pro_label = (( $status== 'disabled') ? '<span class="lgx_meta_field_mark_pro_wrap"><span class="lgx_meta_field_mark">'.__('Pro', 'wp-counter-up').'</span></span>' : '');

        $output= '<tr>';

        $output.= '<th scope="row">';
        $output.= '<h4 class="lgx_app_meta_label"><label for="'.$args['id'].'">'. $args['label'].'</label></h4>';
        $output.= '<p class="lgx_input_desc lgx_app_meta_desc">'. $args['desc'].'</p>';
        $output.= '</th>';

        $default_value = isset( $args['default'] ) ? $args['default'] : '';
        $meta          = get_post_meta( $post->ID, '_save_meta_lgx_counter_generator', true );
       // print_r($meta);
        $meta_value    = (! empty( $meta[$args['id']] ) ? $meta[$args['id']] : $default_value);
       /// print_r($args['options'] );
        $output.= '<td>';

        $options = '';

        foreach ( $args['options'] as $option_value => $option_text ) {
            $selected = ( $option_value == $meta_value ) ? ' selected=selected' : '';
            $pro_disabled_label = ((($is_pro == 'disabled') && ($option_value !== $meta_value )) ? 'disabled="disabled"' : '');
            $options .= '<option value="'. $option_value .'" '.$selected.'  '.$pro_disabled_label.'>'. $option_text . ((($is_pro == 'disabled') && ($option_value !== $meta_value )) ? ' ( Pro )' : '').'</option>';
        }

        $is_multiple = ( isset( $args['multiple'] ) && ($args['multiple'] == 'yes')  ? 'multiple' : '');

        $output.= '<select name="'. $args['name'].'" class="lgx_input_width lgx_app_meta_filed lgx_app_meta_select '. (($is_pro == 'disabled') ? 'lgx_app_meta_select_disabled' : '').'"  id="'.$args['id'].'" '.$is_multiple.'>';
        $output.= $options;

        $output.= '</select>';
       //$output.= $is_pro_label;
        $output.= '</td>';


        $output.= '</tr>';

        echo force_balance_tags($output);
    }




    /**
     * @param array $args
     *  hexa, rgba
     */
    public function color( array $args ) {
        global $post;

        if ( ! isset( $args['id'], $args['name'] , $args['label'] ) ) {
            return;
        }

        $status        = isset( $args['status'] ) ? $args['status'] : '';
        $is_pro       = (( $status == 'disabled') ? 'disabled' : '');
        $is_pro_label = (( $status== 'disabled') ? '<span class="lgx_meta_field_mark_pro_wrap"><span class="lgx_meta_field_mark">'.__('Pro', 'wp-counter-up').'</span></span>' : '');

        $output= '<tr>';

        $output.= '<th scope="row">';
        $output.= '<h4 class="lgx_app_meta_label"><label for="'.$args['id'].'">'. $args['label'].'</label></h4>';
        $output.= '<p class="lgx_input_desc lgx_app_meta_desc">'. $args['desc'].'</p>';
        $output.= '</th>';

        $default_value = isset( $args['default'] ) ? $args['default'] : '';
        $meta          = get_post_meta( $post->ID, '_save_meta_lgx_counter_generator', true );
        $meta_value    = (! empty( $meta[$args['id']] ) ? $meta[$args['id']] : $default_value);

        $output.= '<td>';
        $output.= '<input type="text" value="'.$meta_value.'" id="'.$args['id'].'" class="lgx_app_meta_filed lgx_color_picker" name="'. $args['name'].'" data-default-color="'.$meta_value.'" data-alpha-enabled="true" '.$is_pro.'>';
        $output.= $is_pro_label;
        $output.= '</td>';

        $output.= '</tr>';

        echo force_balance_tags($output);
    }



    /**
     * @param array $args
     *  hexa, rgba
     */
    public function upload( array $args ) {

        global $post;

        if ( ! isset( $args['id'], $args['name'] , $args['label'] ) ) {
            return;
        }

        $status        = isset( $args['status'] ) ? $args['status'] : '';
        $is_pro       = (( $status == 'disabled') ? 'disabled' : '');
        $is_pro_label = (( $status== 'disabled') ? '<span class="lgx_meta_field_mark_pro_wrap"><span class="lgx_meta_field_mark">'.__('Pro', 'wp-counter-up').'</span></span>' : '');

        $output= '<tr>';

        $output.= '<th scope="row">';
        $output.= '<h4 class="lgx_app_meta_label"><label for="'.$args['id'].'">'. $args['label'].'</label></h4>';
        $output.= '<p class="lgx_input_desc lgx_app_meta_desc">'. $args['desc'].'</p>';
        $output.= '</th>';


        $default_value = isset( $args['default'] ) ? $args['default'] : '';
        $meta          = get_post_meta( $post->ID, '_save_meta_lgx_counter_generator', true );
        $meta_value    = (! empty( $meta[$args['id']] ) ? $meta[$args['id']] : $default_value);

        $output.= '<td>';
        $output.= '<button type="button" class="button button-large lgx_icon_image_button" data-icon-field-name="'. $args['name']. '" data-icon-img-id="'.$args['id'].'"><i class="lgxicon lgx-icon-file-photo-o"></i> Select Icon</button>';
        $output.= '&nbsp;&nbsp;&nbsp;<button type="button" class="button button-large lgx_icon_image_button_clear" data-icon-field-name="'. $args['name']. '" data-icon-img-id="'.$args['id'].'"><i class="lgxicon lgx-icon-remove"></i> Remove Icon</button>';
        $output.= '&nbsp;&nbsp;&nbsp;<img src="" alt="" id="'. $args['id']. '" style="width: 24px; margin-top: 5px;">';
        $output.= '<input type="hidden" value="'.$meta_value.'" name="'. $args['name'].'" '.$is_pro.'>';
        $output.= $is_pro_label;
        $output.= '</td>';

        $output.= '</tr>';

        echo force_balance_tags($output);

    }





}