<?php 


   class ElfidmeolinkdataLite {

     private $fields = array(
  
        array(
          'label' => 'Choose',
          'id' => 'Elfimetcsleiten',
          'type' => 'select',
          'options' => array(
             'Video URL',
             'Shortcode',
             'Raw Html',
          ),
         ),
         array(
           'label' => 'Input',
           'id' => 'elfi__info_for_item_demo_link',
           'type' => 'textarea',
          ),
         array(
                 'label' => 'Select Icon',
                 'id' => 'elfielfis_select_iconds',
                 'default' =>'video',
                 'type' => 'select',
                 'options' => array(
                    'Map',
                    'Envelop',
                    'File',
                    'video',
                    'custom icon',
                 ),
          ),
            array(
              'label' => 'Write icon class (if custom icon is selected above)',
              'id' => 'elfioconclass',
              'type' => 'text',
             ),
   
       
      );

      public function __construct() {
        add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );

      }

      public function add_meta_boxes() {
        $excluded_posttypes = array('attachment','revision','nav_menu_item','custom_css','customize_changeset','oembed_cache','user_request','wp_block','scheduled-action','product_variation','shop_order','shop_order_refund','shop_coupon','elementor_library','e-landing-page','wp_template','page');

        $types = get_post_types();
        $post_types = array_diff($types, $excluded_posttypes);


        foreach ($post_types as $s ) {
          add_meta_box(
            'ElfidmeolinkdataLite',
            __( '<span class="dashicons-before dashicons-tagcloud"> ELFI Video Link/Shortcode/Raw Html (Premium feature)</span>', 'elfi-masonry-addon' ),
            array( $this, 'meta_box_callback' ),
            $s,
            'normal',
            'default'
          );
        }
      }

      public function meta_box_callback( $post ) {
        wp_nonce_field( 'ElfidmeolinkdataLite_data', 'ElfidmeolinkdataLite_nonce' ); 
        $this->field_generator( $post );
      }

      public function field_generator( $post ) {
        $output = '';
        foreach ( $this->fields as $field ) {
          $label = '<label for="' . $field['id'] . '">' . $field['label'] . '</label>';
          $meta_value = get_post_meta( $post->ID, $field['id'], true );
          if ( empty( $meta_value ) ) {
            if ( isset( $field['default'] ) ) {
              $meta_value = $field['default'];
            }
          }
          switch ( $field['type'] ) {
            case 'textarea':
              $input = sprintf(
                '<textarea style="width: 100%%" id="%s" name="%s" rows="5">%s</textarea>',
                $field['id'],
                $field['id'],
                $meta_value
              );
              break;
      
            case 'select':
            $input = sprintf(
              '<select id="%s" name="%s">',
              $field['id'],
              $field['id']
            );
            foreach ( $field['options'] as $key => $value ) {
              $field_value = !is_numeric( $key ) ? $key : $value;
              $input .= sprintf(
                '<option %s value="%s">%s</option>',
                $meta_value === $field_value ? 'selected' : '',
                $field_value,
                $value
              );
            }
            $input .= '</select>';
            break;
      
            default:
              $input = sprintf(
              '<input %s id="%s" name="%s" type="%s" value="%s" placeholder="e.g: fa fa-facebook"> If the font awesome package is not active, activate it first<a href="https://wordpress.org/plugins/font-awesome/"> Font Awesome</a><p><em>You can add class to any icon package, but the icons package must be active on your site</em></p>',
              $field['type'] !== 'color' ? 'style="width:auto"' : '',
              $field['id'],
              $field['id'],
              $field['type'],
              $meta_value

            );
          }
          $output .= $this->format_rows( $label, $input );
        }
        echo '<table class="form-table"><tbody>' . $output . '</tbody></table>';
      }

      public function format_rows( $label, $input ) {
        return '<div style="margin-top: 10px;"><strong>'.$label.'</strong></div><div>'.$input.'</div>';
      }

    

    }
     if (class_exists('ElfidmeolinkdataLite')) {
       new ElfidmeolinkdataLite;
     };
