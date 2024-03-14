<?php
defined( 'ABSPATH' ) || exit;

class MobX_Term_Meta {
    public function __construct( $tax, $metas ) {

        $this->metas = $metas;
        $this->tax = $tax;

        add_action( $tax . '_add_form_fields', array($this, 'add'), 10, 2 );
        add_action( $tax . '_edit_form_fields', array($this, 'edit'), 10, 2 );
        add_action( 'created_' . $tax, array($this, 'save'), 10, 2 );
        add_action( 'edited_' . $tax, array($this, 'save'), 10, 2 );
    }

    function add(){
        if(empty($this->metas)) return;
        foreach($this->metas as $meta) {
            if ($meta['type'] == 'upload') {
                wp_enqueue_script("bootstrap-3", MobX_URL."js/bootstrap.js", array('jquery'), MobX_VERSION, true);
                wp_enqueue_script("meta", MobX_URL."js/meta.js", array('jquery', 'jquery-ui-core', 'bootstrap-3', 'wp-color-picker'), MobX_VERSION, true);
                wp_enqueue_media();
            }

            $thml = '<div class="form-field">
            <label for="wpcom_' . $meta['name'] . '">' . $meta['title'] . '</label>
            ' . $this->get_html( $meta ) . '
            ' . (isset($meta['desc']) ? '<p>' . $meta['desc'] . '</p>' : '') . '</div>';

            echo $thml;
        }
    }

    function edit($term){
        if(empty($this->metas)) return;
        foreach($this->metas as $meta) {
            if ($meta['type'] == 'upload') {
                wp_enqueue_script("bootstrap-3", MobX_URL."js/bootstrap.js", array('jquery'), MobX_VERSION, true);
                wp_enqueue_script("meta", MobX_URL."js/meta.js", array('jquery', 'jquery-ui-core', 'bootstrap-3', 'wp-color-picker'), MobX_VERSION, true);
                wp_enqueue_media();
            }
            $html = '<tr class="form-field">
            <th scope="row" valign="top"><label for="wpcom_' . $meta['name'] . '">' . $meta['title'] . '</label></th>
            <td>
            ' . $this->get_html($meta, $term->term_id) . '
            ' . (isset($meta['desc']) ? '<p class="description">' . $meta['desc'] . '</p>' : '') . '
            </td>
        </tr>';
            echo $html;
        }
    }

    function save($term_id){
        if(empty($this->metas)) return;
        $values = array();
        foreach($this->metas as $meta) {
            if (isset($_POST[$meta['name']])) {
                $values[$meta['name']] = $_POST[$meta['name']];
            }
        }
        if(!empty($values)){
            update_term_meta( $term_id, '_wpcom_metas', $values );
        }
    }

    function get_html( $meta, $term_id = 0 ){
        $html = '';
        $val = '';
        if($term_id){
            $val = get_term_meta( $term_id, 'wpcom_'.$meta['name'], true );
        }

        switch($meta['type']){
            case 'select':
                $html = '<select name="'.$meta['name'].'" id="wpcom_'.$meta['name'].'">';
                if($meta['options']){
                    foreach($meta['options'] as $k=>$v){
                        $k = $k==='_empty_'?'':$k;
                        $k = $k===0?'0':$k;
                        $html .= '<option value="'.$k.'"'.($k==$val?' selected':'').'>'.$v.'</option>';
                    }
                }
                $html .= '</select>';
                break;
            case 'text':
            case 'input':
                $html = '<input type="text" name="'.$meta['name'].'" id="wpcom_'.$meta['name'].'" value="'.$val.'">';
                break;
            case 'toggle':
                $html = '<select name="'.$meta['name'].'" id="wpcom_'.$meta['name'].'">';
                $meta['options'] = array(
                    '0' => __('Disable', 'wp-mobile-x'),
                    '1' => __('Enable', 'wp-mobile-x')
                );
                if($meta['options']){
                    foreach($meta['options'] as $k=>$v){
                        $html .= '<option value="'.$k.'"'.($k==$val?' selected':'').'>'.$v.'</option>';
                    }
                }
                $html .= '</select>';
                break;
            case 'textarea':
                $html = '<textarea rows="4" name="'.$meta['name'].'" id="wpcom_'.$meta['name'].'">'.$val.'</textarea>';
                break;
            case 'upload':
                $html = '<input style="width: 50%;" type="text" name="'.$meta['name'].'" id="wpcom_'.$meta['name'].'" value="'.$val.'">
                <button id="wpcom_'.$meta['name'].'_upload" type="button" class="button upload-btn">'.__('Upload', 'wp-mobile-x').'</button>';
                break;
        }

        return $html;
    }
}