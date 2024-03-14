<?php
/**
 * Adds custom meta box and meta fields
 *
 * @package wp_rig
 */

/* phpcs:disable */
if ( ! class_exists('FB_Meta_Box') ) :
    class FB_Meta_Box {

        /**
         * @var string|array $post_type post types to add meta box to.
         */
        public $post_type;

        /**
         * @var string $context side|normal|advanced location of the meta box.
         */
        public $context;

        /**
         * @var string $priority high|low position of the meta box.
         */
        public $priority;

        /**
         * @var string $hook_priority priority of triggering the hook. Default is 10.
         */
        public $hook_priority = 10;

        /**
         * @var array $fields meta fields to be added.
         */
        public $fields;

        /**
         * @var string $meta_box_id meta box id.
         */
        public $meta_box_id;

        /**
         * @var string $label meta box label.
         */
        public $label;

        /**
         * Initialize the class and set its properties.
         *
         * @since    1.0.0
         * @param      array    $args The class arguments.
         */
        public function __construct( $args = null ) {
            $this->meta_box_id   = $args['meta_box_id'] ?: 'fb_meta_box';
            $this->label         = $args['label'] ?: 'FB Metabox';
            $this->post_type     = $args['post_type'] ?: 'post';
            $this->context       = $args['context'] ?: 'normal';
            $this->priority      = $args['priority'] ?: 'high';
            $this->hook_priority = $args['hook_priority'] ?: 10;
            $this->fields        = $args['fields'] ?: array();

            self::hooks();
        }

        /**
         * Sethe admin hooks for this class.
         *
         * @since    1.0.0
         */
        public function hooks() {
            add_action( 'add_meta_boxes', array( $this, 'add_meta_box' ), $this->hook_priority );
            add_action( 'save_post', array( $this, 'save_meta_fields' ), 10, 2 );
            add_action( 'admin_head', array( $this, 'scripts' ) );
        }

        /**
         * Create the metabox that will contain all the metafields.
         *
         * @since    1.0.0
         */
        public function add_meta_box() {
            if ( is_array( $this->post_type ) ) {
                foreach ( $this->post_type as $post_type ) {
                    add_meta_box( $this->meta_box_id, $this->label, array( $this, 'meta_fields_callback' ), $post_type, $this->context, $this->priority );
                }
            } else {
                add_meta_box( $this->meta_box_id, $this->label, array( $this, 'meta_fields_callback' ), $this->post_type, $this->context, $this->priority );
            }
        }

        /**
         * The metabox callback function.
         *
         * @since    1.0.0
         */
        public function meta_fields_callback() {
            global $post;

            echo wp_kses(
                '<input type="hidden" name="fb_nonce" id="fb_nonce" value="' . wp_create_nonce( plugin_basename( __FILE__ ) ) . '" />',
                array( 
                    'input' => array(
                        'type'  => array(),
                        'name'  => array(),
                        'id'    => array(),
                        'value' => array(),
                    )
                )
            );

            foreach ( $this->fields as $field ) {

                switch($field['type']) {
                    case 'text':
                    case 'number':
                        echo $this->field_text( $field, false, '' );
                        break;
                    case 'textarea':
                        echo $this->field_textarea( $field, false, '' );
                        break;
                    case 'radio':
                        echo $this->field_radio( $field, false, '' );
                        break;
                    case 'checkbox':
                        echo $this->field_checkbox( $field, false, '' );
                        break;
                    case 'select':
                        echo $this->field_select( $field, false, '' );
                        break;
                    case 'button':
                        echo $this->field_button( $field );
                        break;
                    case 'repeater':
                        echo $this->field_repeater( $field);
                        break;
                }

            }

        }

        /**
         * Save all of the metabox values into post meta.
         *
         * @since    1.0.0
         * @param      int    $post_id The post id.
         * @param      object $post    The post object.
         */
        public function save_meta_fields( $post_id, $post ) {

            if (
                ! isset( $_POST['fb_nonce'] ) ||
                ! wp_verify_nonce( $_POST['fb_nonce'], plugin_basename( __FILE__ ) ) ||
                ! current_user_can( 'edit_post', $post->ID ) ||
                $post->post_type == 'revision'
            ) {
                return $post_id;
            }

            foreach ( $this->fields as $field ) {
                $key = $field['name'];
                if ( isset( $_POST[ $key ] ) && '' !== $_POST[ $key ] ) {
                    if ( $field['type'] === 'repeater' ) {
                        $meta_values[ $key ] = sanitize_text_field( $_POST[ $key ] );
                        
                        foreach ( $field['fields'] as $subfield ) {
                            if ( isset( $_POST[ $subfield['name'] ] )  && ! empty( $_POST[ $subfield['name'] ] ) ) {
                                $meta_values[ $subfield['name'] ] = serialize( $this->recursive_sanitize_text_field( $_POST[ $subfield['name'] ] ) );
                            }
                        }
                    
                    } else {
                        $meta_values[ $key ] = sanitize_text_field( $_POST[ $key ] );
                    }
                }
            }

            foreach ( $meta_values as $key => $value ) {
                $value = implode( ',', ( array )$value );
                if ( get_post_meta( $post->ID, $key, FALSE ) ) {
                    update_post_meta( $post->ID, $key, $value );
                } else {
                    add_post_meta( $post->ID, $key, $value );
                }
                if ( ! $value ) delete_post_meta( $post->ID, $key );
            }

        }

        /**
         * Recursive sanitation for an array.
         * 
         * @since 1.0.0
         * 
         * @param $array
         *
         * @return mixed
         */
        private function recursive_sanitize_text_field( $array ) {
            
            foreach ( $array as $key => &$value ) {
                if ( is_array( $value ) ) {
                    $value = $this->recursive_sanitize_text_field( $value );
                } else {
                    $value = sanitize_text_field( $value );
                }
            }

            return $array;
        }

        /**
         * Create a text input field.
         *
         * @since    1.0.0
         * @param      array   $post_id The field attributes array.
         * @param      boolean $post    Check if the field is part of a repeatable field or not.
         * @param      mixed   $post    Check if the field is part of a repeatable field and has an index.
         */
        public function field_text( $field, $repeatable, $index ) {
            global $post;

            $field['default'] = ( isset( $field['default'] ) ) ? $field['default'] : null;
            if ( '' !== $index )  {
                $value = get_post_meta( $post->ID, $field['name'], true ) != '' ? esc_attr( unserialize( get_post_meta( $post->ID, $field['name'], true ) )[$index] ) : $field['default'];
            } else {
                $value = get_post_meta( $post->ID, $field['name'], true ) != '' ? esc_attr( get_post_meta( $post->ID, $field['name'], true ) ) : $field['default'];
            }
            $class = isset( $field['class'] ) && !is_null( $field['class'] ) ? $field['class'] : 'regular-text';
            $readonly = isset( $field['readonly'] ) && ( $field['readonly'] == true ) ? " readonly" : "";
            $disabled = isset( $field['disabled'] ) && ( $field['disabled'] == true ) ? " disabled" : "";
            $name     = ( $repeatable ) ? $field['name'] . '[]' : $field['name'];
            $step     = isset( $field['step'] ) && ( $field['step'] !== '0' ) ? 'step="' . $field['step'] . '"' : "";
            
            $html = sprintf( '<div class="fb-row" id="fb_fieldset_%1$s">', $name );
                $html .= sprintf( '<label class="fb-label" for="fb_%1$s">%2$s</label>', $name, $field['label'] );
                $html .= sprintf( '<input type="%1$s" class="%2$s" id="fb_%3$s" name="%3$s" value="%4$s" %5$s %6$s  %7$s/>', $field['type'], $class, $name, $value, $readonly, $disabled, $step );
                $html .= $this->field_description( $field );
            $html .= '</div>';
            return $html;
        }

        /**
         * Create a radio input field.
         *
         * @since    1.0.0
         * @param      array   $field      The field attributes array.
         * @param      boolean $repeatable Check if the field is part of a repeatable field or not.
         * @param      mixed   $index      Check if the field is part of a repeatable field and has an index.
         */
        public function field_radio( $field, $repeatable, $index ) {
            global $post;

            $field['default'] = ( isset( $field['default'] ) ) ? $field['default'] : null;
            if ( '' !== $index )  {
                $value = get_post_meta( $post->ID, $field['name'], true ) != '' ? esc_attr( unserialize( get_post_meta( $post->ID, $field['name'], true ) )[$index] ) : $field['default'];
            } else {
                $value = get_post_meta( $post->ID, $field['name'], true ) != '' ? esc_attr( get_post_meta( $post->ID, $field['name'], true ) ) : $field['default'];
            }
            $class = isset( $field['class'] ) && !is_null( $field['class'] ) ? $field['class'] : 'regular-text';
            $disabled = isset( $field['disabled'] ) && ( $field['disabled'] == true ) ? " disabled" : "";
            $name     = ( $repeatable ) ? $field['name'] . '[]' : $field['name'];

            $html = sprintf( '<div class="fb-row" id="fb_fieldset_%1$s%2$s">', $name );
                $html .= '<label class="fb-label">' . $field['label'] . '</label>';
                foreach ( $field['options'] as $key => $label ) {
                    $html .= sprintf( '<label for="%1$s[%2$s]">', $name, $key );
                    $html .= sprintf( '<input type="radio" class="radio %1$s" id="%2$s[%3$s]" name="%2$s" value="%3$s" %4$s %5$s />', $class, $name, $key, checked( $value, $key, false ), $disabled );
                    $html .= sprintf( '%1$s</label>', $label );
                }
                $html .= $this->field_description( $field );
            $html .= '</div>';

            return $html;
        }

        /**
         * Create a textarea field.
         *
         * @since    1.0.0
         * @param      array   $field      The field attributes array.
         * @param      boolean $repeatable Check if the field is part of a repeatable field or not.
         * @param      mixed   $index      Check if the field is part of a repeatable field and has an index.
         */
        public function field_textarea( $field, $repeatable, $index ) {
            global $post;

            $field['default'] = ( isset( $field['default'] ) ) ? $field['default'] : null;
            if ( '' !== $index )  {
                $value = get_post_meta( $post->ID, $field['name'], true ) != '' ? esc_attr( unserialize( get_post_meta( $post->ID, $field['name'], true ) )[$index] ) : $field['default'];
            } else {
                $value = get_post_meta( $post->ID, $field['name'], true ) != '' ? esc_attr( get_post_meta( $post->ID, $field['name'], true ) ) : $field['default'];
            }
            $class     = isset( $field['class'] ) && ! is_null( $field['class'] ) ? $field['class'] : 'regular-text';
            $cols      = isset( $field['columns'] ) ? $field['columns'] : 24;
            $rows      = isset( $field['rows'] ) ? $field['rows'] : 5;
            $readonly  = isset( $field['readonly'] ) && ( $field['readonly'] == true ) ? " readonly" : "";
            $disabled  = isset( $field['disabled'] ) && ( $field['disabled'] == true ) ? " disabled" : "";
            $name      = ( $repeatable ) ? $field['name'] . '[]' : $field['name'];

            $html = sprintf( '<div class="fb-row" id="fb_fieldset_%1$s">', $name );
                $html .= sprintf( '<label class="fb-label" for="fb_%1$s">%2$s</label>', $name, $field['label'] );
                $html .= sprintf( '<textarea rows="' . $rows . '" cols="' . $cols . '" class="%1$s-text" id="fb_%2$s" name="%2$s" %3$s %4$s >%5$s</textarea>', $class, $name, $readonly, $disabled, $value );
                $html .= $this->field_description( $field );
            $html .= '</div>';

            return $html;
        }

        /**
         * Create a checkbox input field.
         *
         * @since    1.0.0
         * @param      array   $field      The field attributes array.
         * @param      boolean $repeatable Check if the field is part of a repeatable field or not.
         * @param      mixed   $index      Check if the field is part of a repeatable field and has an index.
         */
        public function field_checkbox( $field, $repeatable, $index ) {
            global $post;
            
            $field['default'] = ( isset( $field['default'] ) ) ? $field['default'] : null;
            if ( '' !== $index )  {
                $value = get_post_meta( $post->ID, $field['name'], true ) != '' ? esc_attr( unserialize( get_post_meta( $post->ID, $field['name'], true ) )[$index] ) : $field['default'];
            } else {
                $value = get_post_meta( $post->ID, $field['name'], true ) != '' ? esc_attr( get_post_meta( $post->ID, $field['name'], true ) ) : $field['default'];
            }
            $class    = isset( $field['class'] ) && !is_null( $field['class'] ) ? $field['class'] : 'regular-text';
            $disabled = isset( $field['disabled'] ) && ( $field['disabled'] == true ) ? " disabled" : "";
            $name     = ( $repeatable ) ? $field['name'] . '[]' : $field['name'];

            $html = sprintf( '<div class="fb-row" id="fb_fieldset_%1$s">', $name );
                $html .= sprintf( '<label class="fb-label" for="fb_%1$s">%2$s</label>', $name, $field['label'] );
                $html .= sprintf( '<input type="checkbox" class="checkbox" id="fb_%1$s" name="%1$s" value="on" %2$s %3$s />', $name, checked( $value, 'on', false ), $disabled );
                $html .= $this->field_description( $field, true ) . '';
            $html .= '</div>';
            return $html;
        }

        /**
         * Create a select field.
         *
         * @since    1.0.0
         * @param      array   $field      The field attributes array.
         * @param      boolean $repeatable Check if the field is part of a repeatable field or not.
         * @param      mixed   $index      Check if the field is part of a repeatable field and has an index.
         */        
        public function field_select( $field, $repeatable, $index ) {
            global $post;
                        
            $field['default'] = ( isset( $field['default'] ) ) ? $field['default'] : null;
            if ( '' !== $index )  {
                $value = get_post_meta( $post->ID, $field['name'], true ) != '' ? unserialize( get_post_meta( $post->ID, $field['name'], true ) )[$index] : $field['default'];
            } else {
                $value = get_post_meta( $post->ID, $field['name'], true ) != '' ? get_post_meta( $post->ID, $field['name'], true ) : $field['default'];
            }

            $class    = ( ! empty( $field['class'] ) && ! is_null( $field['class'] ) ) ? $field['class'] : 'regular-text';
            $disabled = ! empty( $field['disabled'] ) ? " disabled" : "";
            $multiple = ! empty( $field['multiple'] ) ? " multiple" : "";
            $name     = ! empty( $field['multiple'] ) ? $field['name'] . '[]' : $field['name'];
            $name     = ( $repeatable ) ? $name . '[]' : $name;

            $html = sprintf( '<div class="fb-row" id="fb_fieldset_%1$s">', $name);
                $html .= sprintf( '<label class="fb-label" for="fb_%1$s">%2$s</label>', $name, $field['label'] );
                $html .= sprintf( '<select class="%1$s" name="%2$s" id="fb_%2$s" %3$s %4$s>', $class, $name, $disabled, $multiple );

                if ( $multiple == '' ) {

                    foreach ( $field['options'] as $key => $label ) {
                        $html .= sprintf( '<option value="%s"%s>%s</option>', $key, selected( $value, $key, false ), $label );
                    }

                } else {
                    $values = $value;
                    foreach ( $field['options'] as $key => $label ) {
                        $selected = $values && in_array( $key, $values ) && $key != '' ? ' selected' : '';
                        $html     .= sprintf( '<option value="%s"%s>%s</option>', $key, $selected, $label );
                    }
                }

                $html .= sprintf( '</select>' );
                $html .= $this->field_description( $field );
            $html .= '</div>';

            return $html;
        }

        /**
         * Create a button input.
         *
         * @since    1.0.0
         * @param      array   $field      The field attributes array.
         * @param      boolean $repeatable Check if the field is part of a repeatable field or not.
         * @param      mixed   $index      Check if the field is part of a repeatable field and has an index.
         */
        public function field_button( $field ) {
            global $post;
            
            $field['value'] = ( isset( $field['value'] ) ) ? $field['value'] : null;
            $class          = isset( $field['class'] ) && !is_null( $field['class'] ) ? $field['class'] : 'button';
            $disabled       = isset( $field['disabled'] ) && ( $field['disabled'] == true ) ? " disabled" : "";
            $name           = $field['name'];
            $button_type    = isset( $field['button_type'] ) && !is_null( $field['button_type'] ) ? $field['button_type'] : 'submit';
            $text           = isset( $field['text'] ) && !is_null( $field['text'] ) ? $field['text'] : 'Submit';

            if ( 'submit' === $button_type ) {
                $html = sprintf( '<div class="fb-row" id="fb_fieldset_%1$s">', $name );
                    $html .= sprintf( '<button type="%1$s" class="%2$s" id="fb_%3$s" name="%3$s" value="%4$s" %5$s> %6$s </button>', $button_type, $class, $name, $field['value'], $disabled, $text );
                    $html .= $this->field_description( $field );
                $html .= '</div>';
            } elseif ( 'link' === $button_type ) {
                $html = sprintf( '<div class="fb-row note" id="fb_fieldset_%1$s">', $name );
                    $html .= sprintf( '<a href="%1$s" target="%2$s" class="%3$s" id="fb_%4$s"> %5$s </a>', $field['url'], $field['target'], $class, $name, $text );
                    $html .= $this->field_description( $field );
                $html .= '</div>';
            }
            
            return $html;
        }

        /**
         * Create a repeater field.
         *
         * @since    1.0.0
         * @param      array   $field      The field attributes array.
         */  
        public function field_repeater( $field ) {
            global $post;

            $field['default']   = ( isset( $field['default'] ) ) ? $field['default'] : null;
            $rows_number        = get_post_meta( $post->ID, $field['name'], true ) != '' ? esc_attr( get_post_meta( $post->ID, $field['name'], true ) ) : $field['default'];
            $rows_max           = ( isset( $field['max'] ) ) ? $field['max'] : null;
            $class              = ( ! empty( $field['class'] ) && ! is_null( $field['class'] ) ) ? $field['class'] : 'regular-text';
            $add_button_text    = ! empty( $field['add_button_text'] ) ? $field['add_button_text'] : __( 'Add row', 'cargus' );
            $delete_button_text = ! empty( $field['remove_button_text'] ) ? $field['remove_button_text'] : __( 'Remove row', 'cargus' );

            $html = sprintf( '<div class="fb-row" id="fb_fieldset_%1$s">', $field['name'] );
                $html .= sprintf('<div class="fb-row fb-row-meta-data">');
                    $html .= sprintf( '<h4 class="fb-label" id="fb_%1$s">%2$s: <b>%3$s</b></h4>', $field['name'], $field['label'], $rows_number );
                    $html .= sprintf( '<a href="javascript:void(0)" class="fb-label button btn-primary add" id="fb_button_add_%1$s">%2$s</a>', $field['name'], $add_button_text );
                    $html .= sprintf( '<input type="hidden" name="%1$s" class="fb-label" id="%1$s" value="%2$s" max="%3$s"></input>', $field['name'], $rows_number, $rows_max );
                $html .= sprintf('</div>');
                $html .= sprintf( '<div class="repeater_container">' );
                
                    for ( $i = 0; $i < $rows_number; $i++ ) {
                        $html .= sprintf('<div class="fb-row repeatable_block repeatable_block_%1$s">', $i);
                            $html .= sprintf('<div class="fb-row fb-row-meta-data">');
                                $html .= sprintf('<h4 class="fb-label" >Colet <b>#<span class="cargus-numar-colet">%s</span></b></h4>', $i + 1 );
                                $html .= sprintf( '<a href="javascript:void(0)" data-index="%3$s" class="fb-label button btn-secondary remove" id="fb_button_remove_%1$s">%2$s</a>', $field['name'], $delete_button_text, $i );
                            $html .= sprintf('</div>');
                        foreach ( $field['fields'] as $subfield ) {

                                switch( $subfield['type'] ) {
                                    case 'text':
                                    case 'number':
                                        $html .= $this->field_text( $subfield, true, $i );
                                        break;
                                    case 'textarea':
                                        $html .= $this->field_textarea( $subfield, true, $i );
                                        break;
                                    case 'radio':
                                        $html .= $this->field_radio( $subfield, true, $i );
                                        break;
                                    case 'checkbox':
                                        $html .= $this->field_checkbox( $subfield, true, $i );
                                        break;
                                    case 'select':
                                        $html .= $this->field_select( $subfield, true, $i );
                                        break;
                                }
                
                            }

                        $html .= sprintf('</div>');
                    }

                $html .= sprintf('</div>');

            $html .= sprintf('</div>');

            return $html;
        }

        /**
         * Set the field description.
         *
         * @since    1.0.0
         * @param      array   $args      The field attributes array.
         */  
        public function field_description( $args ) {
            if ( ! empty( $args['desc'] ) ) {
                if ( isset( $args['desc_p'] ) ) {
                    $desc = sprintf( '<p class="description">%s</p>', $args['desc'] );
                } else {
                    $desc = sprintf( '<small class="fb-small">%s</small>', $args['desc'] );
                }
            } else {
                $desc = '';
            }

            return $desc;
        }

        /**
         * Set the field class scripts.
         *
         * @since    1.0.0
         * @param      array   $args      The field attributes array.
         */ 
        public function scripts() {
            ?>

            <style type="text/css">
                .form-table th {
                    padding: 20px 10px;
                }

                .fb-row {
                    border-bottom: 1px solid #ebebeb;
                    padding: 8px 4px;
                }

                .fb-row .button {
                    display: block;
                    width: 140px;
                    text-align: center;
                }

                .fb-row:last-of-type {
                    border-bottom: none;
                    padding-bttom: 0;
                }
                
                .fb-row-meta-data {
                    display: flex;
                    flex-wrap: wrap;
                    align-items: center;
                    justify-content: space-between;
                }

                .fb-label {
                    display: inline-block;
                    vertical-align: top;
                    width: 200px;
                }

                .fb-meta-field, .fb-meta-field-text {
                    width: 100%;
                }

                #wpbody-content .metabox-holder {
                    padding-top: 5px;
                }

                .repeatable_block {
                    padding: 4px 8px;
                    background-color: #f0f0f0;
                }

                .repeater_container h4,
                .repeater_container .fb-row-meta-data {
                    margin-top: 0;
                    margin-bottom: 0;
                } 

                .repeatable_block:not(:first-child) {
                    margin-top: 10px;
                }

                .fb-label.button {
                    text-align: center;
                }

            </style>
            <?php
        }

    }
endif;
