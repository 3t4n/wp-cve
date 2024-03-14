<?php
/**
 * Image Compare template
 */
$box_classes = ['lakit-image-compare'];

$this->add_render_attribute('wrapper', 'class', $box_classes);
$this->add_render_attribute('wrapper', 'data-settings', $this->_get_js_settings());

echo sprintf('<div %1$s>', $this->get_render_attribute_string('wrapper'));
    echo $this->_get_image_before();
    echo $this->_get_image_after();
echo '</div>';