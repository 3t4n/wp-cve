<?php
defined('ABSPATH') or die;

add_action('wpcf7_init', 'dco_add_form_tag_address');

function dco_add_form_tag_address() {
    wpcf7_add_form_tag(array('dco_address', 'dco_address*'), 'dco_address_form_tag_handler', array('name-attr' => true));
    wpcf7_add_form_tag(array('dco_address_gmaps', 'dco_address_gmaps*'), 'dco_address_gmaps_form_tag_handler', array('name-attr' => true));
}

function dco_address_form_tag_handler($tag) {
    if (empty($tag->name)) {
        return '';
    }

    $validation_error = wpcf7_get_validation_error($tag->name);

    $class = wpcf7_form_controls_class($tag->type);

    if ($validation_error) {
        $class .= ' wpcf7-not-valid';
    }

    $atts = array();

    $atts['size'] = $tag->get_size_option('40');
    $atts['class'] = $tag->get_class_option($class);
    $atts['id'] = $tag->get_id_option();
    $atts['tabindex'] = $tag->get_option('tabindex', 'signed_int', true);

    if ($tag->has_option('readonly')) {
        $atts['readonly'] = 'readonly';
    }

    if ($tag->is_required()) {
        $atts['aria-required'] = 'true';
    }

    $atts['aria-invalid'] = $validation_error ? 'true' : 'false';

    $value = (string) reset($tag->values);

    $values = explode(' : ', $value);

    if (strpos($values[0], ':') === 0) {
        $value = '';
        $values[1] = substr($values[0], 2);
    } else {
        $value = $values[0];
    }

    if (isset($values[1])) {
        $atts['data-search-restriction'] = $values[1];
    }

    if ($tag->has_option('placeholder') || $tag->has_option('watermark')) {
        $atts['placeholder'] = $value;
        $value = '';
    }

    $value = $tag->get_default_option($value);

    $value = wpcf7_get_hangover($tag->name, $value);

    $atts['value'] = $value;

    $atts['type'] = 'text';

    $atts['name'] = $tag->name;

    $atts = wpcf7_format_atts($atts);

    $html = sprintf(
            '<span class="wpcf7-form-control-wrap %1$s"><input %2$s />%3$s</span>', sanitize_html_class($tag->name), $atts, $validation_error);

    wp_enqueue_script('dco-address-field-yandex-maps-api');
    wp_enqueue_script('dco-address-field-for-contact-form-7');

    return $html;
}

function dco_address_gmaps_form_tag_handler($tag) {
    if (empty($tag->name)) {
        return '';
    }

    $validation_error = wpcf7_get_validation_error($tag->name);

    $class = wpcf7_form_controls_class($tag->type);

    if ($validation_error) {
        $class .= ' wpcf7-not-valid';
    }

    $atts = array();

    $atts['size'] = $tag->get_size_option('40');
    $atts['class'] = $tag->get_class_option($class);
    $atts['id'] = $tag->get_id_option();
    $atts['tabindex'] = $tag->get_option('tabindex', 'signed_int', true);

    if ($tag->has_option('readonly')) {
        $atts['readonly'] = 'readonly';
    }

    if ($tag->is_required()) {
        $atts['aria-required'] = 'true';
    }

    $atts['aria-invalid'] = $validation_error ? 'true' : 'false';

    $value = (string) reset($tag->values);

    $values = explode(' : ', $value);

    if (strpos($values[0], ':') === 0) {
        $value = '';
        $values[1] = substr($values[0], 2);
    } else {
        $value = $values[0];
    }

    if (isset($values[1])) {
        $atts['data-search-restriction'] = $values[1];
    }

    if ($tag->has_option('placeholder') || $tag->has_option('watermark')) {
        $atts['placeholder'] = $value;
        $value = '';
    }

    $value = $tag->get_default_option($value);

    $value = wpcf7_get_hangover($tag->name, $value);

    $atts['value'] = $value;

    $atts['type'] = 'text';

    $atts['name'] = $tag->name;

    $atts = wpcf7_format_atts($atts);

    $html = sprintf(
            '<span class="wpcf7-form-control-wrap %1$s"><input %2$s />%3$s</span>', sanitize_html_class($tag->name), $atts, $validation_error);

    wp_enqueue_script('dco-address-field-google-maps-api');
    wp_enqueue_script('dco-address-field-for-contact-form-7');

    return $html;
}

add_filter('wpcf7_validate_dco_address', 'dco_address_validation_filter', 10, 2);
add_filter('wpcf7_validate_dco_address*', 'dco_address_validation_filter', 10, 2);
add_filter('wpcf7_validate_dco_address_gmaps', 'dco_address_validation_filter', 10, 2);
add_filter('wpcf7_validate_dco_address_gmaps*', 'dco_address_validation_filter', 10, 2);

function dco_address_validation_filter($result, $tag) {
    $name = $tag->name;

    $value = isset($_POST[$name]) ? sanitize_text_field($_POST[$name]) : '';

    if ($tag->is_required() && '' == $value) {
        $result->invalidate($tag, wpcf7_get_message('invalid_required'));
    }

    return $result;
}

add_action('wpcf7_admin_init', 'dco_add_tag_generator_address', 18);

function dco_add_tag_generator_address() {
    $tag_generator = WPCF7_TagGenerator::get_instance();
    $tag_generator->add('dco_address', __('DCO Address Yandex', 'dco-address-field-for-contact-form-7'), 'dco_tag_generator_address');
    $tag_generator->add('dco_address_gmaps', __('DCO Address Google', 'dco-address-field-for-contact-form-7'), 'dco_tag_generator_address_gmaps');
}

function dco_tag_generator_address($contact_form, $args = '') {
    $args = wp_parse_args($args, array());
    $type = 'dco_address';
    ?>
    <div class="control-box">
        <fieldset>
            <legend><?php esc_html_e('Generate a form-tag for a field for address input with autocomplete suggestion from Yandex Maps.', 'dco-address-field-for-contact-form-7'); ?></legend>

            <table class="form-table">
                <tbody>
                    <tr>
                        <th scope="row"><?php echo esc_html(__('Field type', 'contact-form-7')); ?></th>
                        <td>
                            <fieldset>
                                <legend class="screen-reader-text"><?php echo esc_html(__('Field type', 'contact-form-7')); ?></legend>
                                <label><input type="checkbox" name="required" /> <?php echo esc_html(__('Required field', 'contact-form-7')); ?></label>
                            </fieldset>
                        </td>
                    </tr>

                    <tr>
                        <th scope="row"><label for="<?php echo esc_attr($args['content'] . '-name'); ?>"><?php echo esc_html(__('Name', 'contact-form-7')); ?></label></th>
                        <td><input type="text" name="name" class="tg-name oneline" id="<?php echo esc_attr($args['content'] . '-name'); ?>" /></td>
                    </tr>

                    <tr>
                        <th scope="row"><label for="<?php echo esc_attr($args['content'] . '-values'); ?>"><?php echo esc_html_e('Search address only in', 'dco-address-field-for-contact-form-7'); ?></label></th>
                        <td>
                            <fieldset>
                                <legend class="screen-reader-text"><?php echo esc_html_e('Default value', 'contact-form-7'); ?></legend>
                                <input type="text" name="values" class="oneline" id="<?php echo esc_attr($args['content'] . '-values'); ?>" placeholder="<?php echo esc_attr_e('Default value', 'contact-form-7'); ?> : <?php echo esc_attr_e('Search address only in', 'dco-address-field-for-contact-form-7'); ?>" /><br />
                                <label for="<?php echo esc_attr($args['content'] . '-values'); ?>"><span class="description"><?php echo _e('If necessary, you can specify in which country and/or city the search should be performed in format <code>Default value : County, City</code><br> (e.g. <code>Enter the address : Russia, Moscow</code>, <code>Your address : London</code>, <code>: Moscow</code>).', 'dco-address-field-for-contact-form-7'); ?></span></label>
                                <label><input type="checkbox" name="placeholder" class="option" /> <?php echo esc_html(__('Use this text as the placeholder of the field', 'contact-form-7')); ?></label>
                            </fieldset>
                        </td>
                    </tr>

                    <tr>
                        <th scope="row"><label for="<?php echo esc_attr($args['content'] . '-id'); ?>"><?php echo esc_html(__('Id attribute', 'contact-form-7')); ?></label></th>
                        <td><input type="text" name="id" class="idvalue oneline option" id="<?php echo esc_attr($args['content'] . '-id'); ?>" /></td>
                    </tr>

                    <tr>
                        <th scope="row"><label for="<?php echo esc_attr($args['content'] . '-class'); ?>"><?php echo esc_html(__('Class attribute', 'contact-form-7')); ?></label></th>
                        <td><input type="text" name="class" class="classvalue oneline option" id="<?php echo esc_attr($args['content'] . '-class'); ?>" /></td>
                    </tr>
                </tbody>
            </table>
        </fieldset>
    </div>

    <div class="insert-box">
        <input type="text" name="<?php echo $type; ?>" class="tag code" readonly="readonly" onfocus="this.select()" />

        <div class="submitbox">
            <input type="button" class="button button-primary insert-tag" value="<?php echo esc_attr(__('Insert Tag', 'contact-form-7')); ?>" />
        </div>

        <br class="clear" />

        <p class="description mail-tag"><label for="<?php echo esc_attr($args['content'] . '-mailtag'); ?>"><?php echo sprintf(esc_html(__("To use the value input through this field in a mail field, you need to insert the corresponding mail-tag (%s) into the field on the Mail tab.", 'contact-form-7')), '<strong><span class="mail-tag"></span></strong>'); ?><input type="text" class="mail-tag code hidden" readonly="readonly" id="<?php echo esc_attr($args['content'] . '-mailtag'); ?>" /></label></p>
    </div>
    <?php
}

function dco_tag_generator_address_gmaps($contact_form, $args = '') {
    $args = wp_parse_args($args, array());
    $type = 'dco_address_gmaps';
    ?>
    <div class="control-box">
        <fieldset>
            <legend><?php esc_html_e('Generate a form-tag for a field for address input with autocomplete suggestion from Google Maps.', 'dco-address-field-for-contact-form-7'); ?></legend>

            <table class="form-table">
                <tbody>
                    <tr>
                        <th scope="row"><?php echo esc_html(__('Field type', 'contact-form-7')); ?></th>
                        <td>
                            <fieldset>
                                <legend class="screen-reader-text"><?php echo esc_html(__('Field type', 'contact-form-7')); ?></legend>
                                <label><input type="checkbox" name="required" /> <?php echo esc_html(__('Required field', 'contact-form-7')); ?></label>
                            </fieldset>
                        </td>
                    </tr>

                    <tr>
                        <th scope="row"><label for="<?php echo esc_attr($args['content'] . '-name'); ?>"><?php echo esc_html(__('Name', 'contact-form-7')); ?></label></th>
                        <td><input type="text" name="name" class="tg-name oneline" id="<?php echo esc_attr($args['content'] . '-name'); ?>" /></td>
                    </tr>

                    <tr>
                        <th scope="row"><label for="<?php echo esc_attr($args['content'] . '-values'); ?>"><?php echo esc_html_e('Search address only in', 'dco-address-field-for-contact-form-7'); ?></label></th>
                        <td>
                            <fieldset>
                                <legend class="screen-reader-text"><?php echo esc_html_e('Default value', 'contact-form-7'); ?></legend>
                                <input type="text" name="values" class="oneline" id="<?php echo esc_attr($args['content'] . '-values'); ?>" placeholder="<?php echo esc_attr_e('Default value', 'contact-form-7'); ?> : <?php echo esc_attr_e('Search address only in', 'dco-address-field-for-contact-form-7'); ?>" /><br />
                                <label for="<?php echo esc_attr($args['content'] . '-values'); ?>"><span class="description"><?php echo _e('If necessary, you can specify in which country the search should be performed in format <code>Default value : County</code><br> (e.g. <code>Enter the address : ru</code>).', 'dco-address-field-for-contact-form-7'); ?></span></label>
                                <label><input type="checkbox" name="placeholder" class="option" /> <?php echo esc_html(__('Use this text as the placeholder of the field', 'contact-form-7')); ?></label>
                            </fieldset>
                        </td>
                    </tr>

                    <tr>
                        <th scope="row"><label for="<?php echo esc_attr($args['content'] . '-id'); ?>"><?php echo esc_html(__('Id attribute', 'contact-form-7')); ?></label></th>
                        <td><input type="text" name="id" class="idvalue oneline option" id="<?php echo esc_attr($args['content'] . '-id'); ?>" /></td>
                    </tr>

                    <tr>
                        <th scope="row"><label for="<?php echo esc_attr($args['content'] . '-class'); ?>"><?php echo esc_html(__('Class attribute', 'contact-form-7')); ?></label></th>
                        <td><input type="text" name="class" class="classvalue oneline option" id="<?php echo esc_attr($args['content'] . '-class'); ?>" /></td>
                    </tr>
                </tbody>
            </table>
        </fieldset>
    </div>

    <div class="insert-box">
        <input type="text" name="<?php echo $type; ?>" class="tag code" readonly="readonly" onfocus="this.select()" />

        <div class="submitbox">
            <input type="button" class="button button-primary insert-tag" value="<?php echo esc_attr(__('Insert Tag', 'contact-form-7')); ?>" />
        </div>

        <br class="clear" />

        <p class="description mail-tag"><label for="<?php echo esc_attr($args['content'] . '-mailtag'); ?>"><?php echo sprintf(esc_html(__("To use the value input through this field in a mail field, you need to insert the corresponding mail-tag (%s) into the field on the Mail tab.", 'contact-form-7')), '<strong><span class="mail-tag"></span></strong>'); ?><input type="text" class="mail-tag code hidden" readonly="readonly" id="<?php echo esc_attr($args['content'] . '-mailtag'); ?>" /></label></p>
    </div>
    <?php
}