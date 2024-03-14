<?php
class LinkTypeReadMore extends ReadMore {

    public function getRemoveOptions() {

        return array(
            'less-button-title' => 1,
            'advanced-hidden-options' => 1,
            'remove-shortcode-content' => 1,
            'animation-duration' => 1,
	        'button-border-bottom' => 1
        );
    }

    public static function params() {

        $data = array();

        return $data;
    }

    public function includeOptionsBlock($dataObj) {
        wp_register_script('YrmLink', YRM_JAVASCRIPT.'YrmLink.js', array('readMoreJs', 'jquery-effects-core'), EXPM_VERSION);
        wp_enqueue_script('YrmLink');
        require_once(YRM_VIEWS_SECTIONS.'linkCutsomOptions.php');
    }
}