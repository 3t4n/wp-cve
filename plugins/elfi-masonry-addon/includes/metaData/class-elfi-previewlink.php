<?php 

class elfiPreviewlinkdataLite {

     private $screens = array('elfi');

     private $fields = array(
       array(
         'label' => 'Preview Link',
         'id' => 'elfi__info_for_item_video_link',
         'type' => 'url',
        ),
       array(
         'label' => 'Download Link',
         'id' => 'elfidwnlink',
         'type' => 'url',
        )  
     );

     public function __construct() {
       add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
     }

     public function add_meta_boxes() {
       foreach ( $this->screens as $s ) {
         add_meta_box(
           'elfiPreviewURL',
           __( '<span class="dashicons-before dashicons-tagcloud"> Elfi Masonry Preview & Download URL (Premium features)</span>', 'elfi-masonry-addon' ),
           array( $this, 'meta_box_callback' ),
           $s,
           'normal',
           'default'
         );
       }
     }

     public function meta_box_callback( $post ) {
       wp_nonce_field( 'elfiPreviewURL_data', 'elfiPreviewURL_nonce' ); 
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
           default:
             $input = sprintf(
             '<input %s id="%s" name="%s" type="%s" value="%s">',
             $field['type'] !== 'color' ? 'style="width: 100%"' : '',
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

   if (class_exists('elfiPreviewlinkdataLite')) {
     new elfiPreviewlinkdataLite;
   };

   