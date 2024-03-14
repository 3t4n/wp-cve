<?php

namespace Baqend\WordPress\Service;

/**
 * Service to handle form rendering.
 *
 * Class FormService created on 2018-06-15.
 *
 * @author Konstantin Simon Maria Möllers
 * @package Baqend\WordPress\Service
 */
class FormService {

    /**
     * @var string
     */
    private $slug;

    /**
     * FormService constructor.
     *
     * @param string $slug
     */
    public function __construct( $slug ) {
        $this->slug = $slug;
    }

    /**
     * Generates settings fields by passing an array of data (see the render method).
     *
     * @param array $fields The array that helps build the settings fields
     * @param array $settings The settings array from the options table.
     *
     * @return string The settings field's HTML to be output in the view.
     */
    public function render_fields( array $fields, array $settings ) {
        $output = '<div class="metabox-holder">';
        foreach ( $fields as $field ) {
            $output .= $this->render_field( $field, $settings );
        }
        $output .= '</div>';

        return $output;
    }

    /**
     * Generates HTML for a custom settings field.
     *
     * @param array $field The field to be generated.
     * @param array $settings The settings array from the options table.
     *
     * @return string The settings field's HTML to be output in the view.
     */
    private function render_field( array $field, array $settings ) {
        $slug      = $field['slug'];
        $html_slug = str_replace( '_', '-', $slug );
        $id        = "form-{$this->slug}-$html_slug";
        $label     = '<label for="' . $id . '">' . esc_attr__( $field['label'], 'baqend' ) . '</label>';
        $element   = $this->custom_settings_field_element( $id, $field, $settings[ $slug ] );
        $submit    = $this->render_submit_button( $html_slug );

        // Render the element's help.
        $help = '';
        if ( isset( $field['help'] ) ) {
            $help = '<p class="description">' . $field['help'] . '</p>';
        }

        return <<<HTML
<div class="postbox">
    <div class="inside">
        <table class="form-table">
            <tr>
                <th>$label</th>
                <td>$element$help</td>
            </tr>
        </table>
        $submit
    </div>
</div>
HTML;
    }

    /**
     * Renders an HTML element for the given field.
     *
     * @param string $id The HTML ID.
     * @param array $field The field description.
     * @param mixed $settings_value The element's current value.
     *
     * @return string The field's element's HTML.
     */
    private function custom_settings_field_element( $id, array $field, $settings_value ) {
        $name = $this->slug . '[' . $field['slug'] . ']';

        switch ( $field['type'] ) {
            case 'choice':
                $multiple = isset( $field['multiple'] ) ? $field['multiple'] : false;
                $name     = $name . ( $multiple ? '[]' : '' );
                $options  = implode( array_map(
                    function ( $key, $value ) use ( $settings_value, $multiple ) {
                        $isSelected = $multiple ? in_array( $key, $settings_value, true ) : $settings_value == $key;

                        return '<option value="' . $key . '"' . ( $isSelected ? ' selected' : '' ) . '>' . $value . '</option>';
                    },
                    array_keys( $field['options'] ),
                    array_values( $field['options'] ) ) );

                return '<select id="' . $id . '" name="' . $name . '" class="regular-text"' . ( $multiple ? ' multiple="multiple"' : '' ) . '>' . $options . '</select>';
            case 'textarea':
                return '<textarea id="' . $id . '" name="' . $name . '" rows="10" class="regular-text" style="white-space:nowrap; background:#f1f1f1;">' . $settings_value . '</textarea>';
            case 'list':
                $settings_value = (array) $settings_value;

                return '<textarea id="' . $id . '" name="' . $name . '" rows="10" class="regular-text" wrap="soft" style="font-family:monospace;width:100%;background:#f1f1f1;">' . implode( "\n", $settings_value ) . '</textarea>';
            case 'checkbox':
                return '<label><input name="' . $name . '" type="hidden" value="false"><input id="' . $id . '" name="' . $name . '" type="checkbox" value="true"' . ( $settings_value ? ' checked' : '' ) . '>' . $field['checkbox_label'] . '</label>';
            case 'checkboxes':
                return $this->render_checkboxes( $id, $name, $field, $settings_value );
            case 'pages':
                return $this->render_pages( $id, $name, $field, $settings_value );
            case 'image_optimization':
                return $this->render_image_optimization( $id, $name, $field, $settings_value );
            default:
                return "<input type=\"{$field['type']}\" id=\"{$id}\" name=\"{$name}\" value=\"{$settings_value}\" class=\"regular-text\">";
        }
    }

    /**
     * @param string $id The HTML ID.
     * @param string $name The HTML name.
     * @param array $field The field description.
     * @param mixed $settings_value The element's current value.
     *
     * @return string The field's element's HTML.
     */
    private function render_checkboxes( $id, $name, array $field, $settings_value ) {
        $array  = (array) $settings_value;
        $output = '<fieldset>';
        foreach ( $field['options'] as $key => $value ) {
            $checked  = in_array( $key, $array, true ) ? ' checked' : '';
            $input_id = $id . '-' . $key;
            $input    = "<input id='$input_id' name='{$name}[]' type='checkbox' value='$key'{$checked}>";
            $output   .= '<label>' . $input . $value . '</label><br>';
        }
        $output .= '</fieldset>';

        return $output;
    }

    /**
     * @param string $id The HTML ID.
     * @param string $name The HTML name.
     * @param array $field The field description.
     * @param mixed $settings_value The element's current value.
     *
     * @return string The field's element's HTML.
     */
    private function render_pages( $id, $name, array $field, $settings_value ) {
        if ( ! is_array( $settings_value ) ) {
            $array = [];
        } else {
            $array = $settings_value;
        }

        // Build enabled pages JSON data
        $enabled = [];
        foreach ( $array as $index => $item ) {
            $label        = __( $item['type'] );
            $content_name = $this->get_name( $item );
            $enabled[]    = [
                'title' => $label,
                'name'  => $content_name,
                'type'  => $item['type'],
                'id'    => $item['id'],
            ];
        }

        // Build options to select JSON
        $options    = [];
        $categories = [];
        /** @var \WP_Term $category */
        foreach ( get_categories() as $category ) {
            $categories[] = [
                'type'  => 'Categories',
                'title' => __( 'Categories' ),
                'id'    => $category->term_id,
                'name'  => $category->name,
            ];
        }
        $options[] = [ 'name' => __( 'Categories' ), 'items' => $categories ];

        $pages = [];
        /** @var \WP_Post $page */
        foreach ( get_pages() as $page ) {
            $pages[] = [
                'type'  => 'Page',
                'title' => __( 'Page' ),
                'id'    => $page->ID,
                'name'  => $page->post_title,
            ];
        }
        $options[] = [ 'name' => __( 'Pages' ), 'items' => $pages ];

        // Encode JSON
        $json = esc_attr( json_encode( [
            'name'        => $name,
            'id'          => $id,
            'pages'       => $enabled,
            'options'     => $options,
            'placeholder' => __( 'Please select an element to add ...', 'baqend' ),
            'removeLabel' => __( 'Remove' ),
            'emptyLabel'  => __( 'The list of enabled pages is empty. Speed Kit will then be enabled on all pages.', 'baqend' ),
        ] ) );

        return "<div id='pages-vue' data-init='$json'></div>";
    }

    /**
     * @param string $id The HTML ID.
     * @param string $name The HTML name.
     * @param array $field The field description.
     * @param mixed $settings_value The element's current value.
     *
     * @return string The field's element's HTML.
     */
    private function render_image_optimization( $id, $name, array $field, $settings_value ) {
        if ( is_array( $settings_value ) ) {
            $array = $settings_value;
        } else {
            $array = [ 'quality' => '85', 'webp' => '1', 'pjpeg' => '1', 'disabled' => '0' ];
        }

        // Encode JSON
        $json = esc_attr( json_encode( [
            'name'  => $name,
            'id'    => $id,
            'state' => [
                'quality'  => (int) $array['quality'],
                'webp'     => (int) $array['webp'],
                'pjpeg'    => (int) $array['pjpeg'],
                'disabled' => isset( $array['disabled'] ) ? (bool) $array['disabled'] : false,
            ],
        ] ) );

        return "<div id='image-optimization-vue' data-init='$json'></div>";
    }

    /**
     * @param array $item
     *
     * @return string
     */
    private function get_name( array $item ) {
        switch ( $item['type'] ) {
            case 'Page':
                return get_post( $item['id'] )->post_title;
            case 'Categories':
                return get_category( $item['id'] )->name;
            default:
                return '???';
        }
    }

    /**
     * Renders a submit button for the settings field.
     *
     * @param string $html_slug The slug used in HTML.
     *
     * @return string The button's HTML code.
     */
    private function render_submit_button( $html_slug ) {
        $options = [
            'id'    => "submit-{$this->slug}-$html_slug",
            'style' => 'float:right;',
        ];
        $text    = __( 'Save Settings', 'baqend' );
        $submit  = get_submit_button( $text, 'primary', 'submit', false, $options );

        return '<div class="submit">' . $submit . '</div>';
    }
}
