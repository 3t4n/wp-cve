<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

if (!defined('JSJOBS_FILE_TYPE_ERROR')) {
    define('JSJOBS_FILE_TYPE_ERROR', 'JSJOBS_FILE_TYPE_ERROR');
    define('JSJOBS_FILE_SIZE_ERROR', 'JSJOBS_FILE_SIZE_ERROR');
    define('JSJOBS_ALREADY_EXIST', 'JSJOBS_ALREADY_EXIST');
    define('JSJOBS_NOT_EXIST', 'JSJOBS_NOT_EXIST');
    define('JSJOBS_IN_USE', 'JSJOBS_IN_USE');
    define('JSJOBS_SET_DEFAULT', 'JSJOBS_SET_DEFAULT');
    define('JSJOBS_SET_DEFAULT_ERROR', 'JSJOBS_SET_DEFAULT_ERROR');
    define('JSJOBS_STATUS_CHANGED', 'JSJOBS_STATUS_CHANGED');
    define('JSJOBS_STATUS_CHANGED_ERROR', 'JSJOBS_STATUS_CHANGED_ERROR');
    define('JSJOBS_APPROVED', 'JSJOBS_APPROVED');
    define('JSJOBS_APPROVE_ERROR', 'JSJOBS_APPROVE_ERROR');
    define('JSJOBS_REJECTED', 'JSJOBS_REJECTED');
    define('JSJOBS_REJECT_ERROR', 'JSJOBS_REJECT_ERROR');
    define('JSJOBS_UN_PUBLISHED', 'JSJOBS_UN_PUBLISHED');
    define('JSJOBS_UN_PUBLISH_ERROR', 'JSJOBS_UN_PUBLISH_ERROR');
    define('JSJOBS_UNPUBLISH_DEFAULT_ERROR', 'JSJOBS_UNPUBLISH_DEFAULT_ERROR');
    define('JSJOBS_PUBLISHED', 'JSJOBS_PUBLISHED');
    define('JSJOBS_PUBLISH_ERROR', 'JSJOBS_PUBLISH_ERROR');
    define('JSJOBS_REQUIRED', 'JSJOBS_REQUIRED');
    define('JSJOBS_REQUIRED_ERROR', 'JSJOBS_REQUIRED_ERROR');
    define('JSJOBS_NOT_REQUIRED', 'JSJOBS_NOT_REQUIRED');
    define('JSJOBS_NOT_REQUIRED_ERROR', 'JSJOBS_NOT_REQUIRED_ERROR');
    define('JSJOBS_ORDER_UP', 'JSJOBS_ORDER_UP');
    define('JSJOBS_ORDER_UP_ERROR', 'JSJOBS_ORDER_UP_ERROR');
    define('JSJOBS_ORDER_DOWN', 'JSJOBS_ORDER_DOWN');
    define('JSJOBS_ORDER_DOWN_ERROR', 'JSJOBS_ORDER_DOWN_ERROR');
    define('JSJOBS_SAVED', 'JSJOBS_SAVED');
    define('JSJOBS_SAVE_ERROR', 'JSJOBS_SAVE_ERROR');
    define('JSJOBS_DELETED', 'JSJOBS_DELETED');
    define('JSJOBS_DELETE_ERROR', 'JSJOBS_DELETE_ERROR');
    define('JSJOBS_VERIFIED', 'JSJOBS_VERIFIED');
    define('JSJOBS_APPLY', 'JSJOBS_APPLY');
    define('JSJOBS_APPLY_ERROR', 'JSJOBS_APPLY_ERROR');
    define('JSJOBS_UN_VERIFIED', 'JSJOBS_UN_VERIFIED');
    define('JSJOBS_VERIFIED_ERROR', 'JSJOBS_VERIFIED_ERROR');
    define('JSJOBS_UN_VERIFIED_ERROR', 'JSJOBS_UN_VERIFIED_ERROR');
    define('JSJOBS_INVALID_REQUEST', 'JSJOBS_INVALID_REQUEST');
    define('JSJOBS_ENABLED', 'JSJOBS_ENABLED');
    define('JSJOBS_DISABLED', 'JSJOBS_DISABLED');
    define('JSJOBS_PLUGIN_PATH', plugin_dir_path( __DIR__ ));
    define('JSJOBS_PLUGIN_URL', plugin_dir_url( __DIR__ ));

    define('JSJOBS_ALLOWED_TAGS',array(
        'div'      => array(
            'class'  => array(),
            'id' => array(),
            'data-sitekey' => array(),
            'title' => array(),
            'role' => array(),
            'onclick' => array(),
            'onmouseout' => array(),
            'onmouseover' => array(),
            'data-section' => array(),
            'data-sectionid' => array(),
            'data-boxid' => array(),
            'data-id' => array(),
            'style' => array(),
            'data-jsjobs-terms-and-conditions' => array(),
        ),
        'button'      => array(
            'class'  => array(),
            'id' => array(),
            'type' => array(),
            'title' => array(),
            'role' => array(),
            'onclick' => array(),
            'data-dismiss' => array(),
            'aria-label' => array(),
            'style' => array(),
        ),
        'i'      => array(
            'class'  => array(),
            'id' => array(),
            'style' => array(),
        ),
        'h1'      => array(
            'class'  => array(),
            'id' => array(),
            'style' => array(),
        ),
        'h2'      => array(
            'class'  => array(),
            'id' => array(),
            'style' => array(),
        ),
        'h3'      => array(
            'class'  => array(),
            'id' => array(),
            'style' => array(),
        ),
        'h4'      => array(
            'class'  => array(),
            'id' => array(),
            'style' => array(),
        ),
        'h5'      => array(
            'class'  => array(),
            'id' => array(),
            'style' => array(),
        ),
        'h6'      => array(
            'class'  => array(),
            'id' => array(),
            'style' => array(),  
        ),
        'font'      => array(
            'class'  => array(),
            'id' => array(),
            'style' => array(),
            'color' => array(),
        ),
        'span'      => array(
            'class'  => array(),
            'id' => array(),
            'aria-hidden' => array(),
            'style' => array(),
            'onclick' => array(),
        ),
        'input'      => array(
            'type'  => array(),
            'id' => array(),
            'class' => array(),
            'name' => array(),
            'value' => array(),
            'onclick' => array(),
            'onchange' => array(),
            'data-validation' => array(),
            'ckbox-group-name' => array(),
            'required' => array(),
            'size' => array(),
            'placeholder' => array(),
            'checked' => array(),
            'autocomplete' => array(),
            'multiple' => array(),
            'rel' => array(),
            'maxlength' => array(),
            'disabled' => array(),
            'readonly' => array(),
            'data-for' => array(),
            'credit_userid' => array(),
            'data-dismiss' => array(),
            'data-validation-optional' => array(),
            'data-myrequired' => array(),
            'style' => array(),
        ),
        'textarea'     => array(
            'rows' => array(),
            'name' => array(),
            'class' => array(),
            'id' => array(),
            'value' => array(),
            'cols' => array(),
            'data-validation' => array(),
            'data-myrequired' => array(),
            'autocomplete' => array(),
            'style' => array(),
        ),
        'button'      => array(
            'type'  => array(),
            'id' => array(),
            'class' => array(),
            'name' => array(),
            'value' => array(),
            'onclick' => array(),
            'data-validation' => array(),
            'required' => array(),
            'data-dismiss' => array(),
            'style' => array(),
        ),
        'select'      => array(
            'id' => array(),
            'class' => array(),
            'name' => array(),
            'onchange' => array(),
            'data-validation' => array(),
            'required' => array(),
            'multiple' => array(),
            'disabled' => array(),
            'data-myrequired' => array(),
            'style' => array(),
        ),
        'option'      => array(
            'id' => array(),
            'class' => array(),
            'name' => array(),
            'value' => array(),
            'selected' => array(),
            'style' => array(),
        ),
        'iframe'      => array(
            'title' => array(),
            'width' => array(),
            'height' => array(),
            'src' => array(),
            'frameborder' => array(),
            'allowfullscreen' => array(),
            'allow' => array(),
        ),
        'img'      => array(
            'src'  => array(),
            'id' => array(),
            'class' => array(),
            'onclick' => array(),
            'alt' => array(),
            'width' => array(),
            'height' => array(),
            'border' => array(),
            'style' => array(),
        ),
        'link'      => array(
            'src'  => array(),
            'id' => array(),
            'rel' => array(),
            'href' => array(),
            'media' => array(),
            'style' => array(),
        ),
        'meta'      => array(
            'property'  => array(),
            'content' => array(),
            'style' => array(),
        ),
        'a'      => array(
            'href'  => array(),
            'title' => array(),
            'onclick' => array(),
            'id' => array(),
            'class' => array(),
            'name' => array(),
            'data-toggle' => array(),
            'data-id' => array(),
            'data-name' => array(),
            'data-email' => array(),
            'data-id' => array(),
            'data-name' => array(),
            'data-email' => array(),
            'message' => array(),
            'confirmmessage' => array(),
            'data-for' => array(),
            'data-sortby' => array(),
            'data-image1' => array(),
            'data-image2' => array(),
            'data-showmore' => array(),
            'data-scrolltask' => array(),
            'data-offset' => array(),
            'data-section' => array(),
            'target' => array(),
            'style' => array(),
        ),
        'ul'      => array(
            'type'  => array(),
            'id' => array(),
            'class' => array(),
            'style' => array(),
        ),
        'ol'      => array(
            'id' => array(),
            'class' => array(),
            'style' => array(),
        ),
        'li'      => array(
            'id' => array(),
            'class' => array(),
            'onclick' => array(),
            'style' => array(),
        ),
        'dl'      => array(
            'id' => array(),
            'class' => array(),
            'style' => array(),
        ),
        'dt'      => array(
            'id' => array(),
            'class' => array(),
            'style' => array(),
        ),
        'dd'      => array(
            'id' => array(),
            'class' => array(),
            'style' => array(),
        ),
        'table'      => array(
            'id' => array(),
            'class' => array(),
            'style' => array(),
        ),
        'tr'      => array(
            'id' => array(),
            'class' => array(),
            'style' => array(),
        ),
        'td'      => array(
            'id' => array(),
            'class' => array(),
            'style' => array(),
        ),
        'th'      => array(
            'id' => array(),
            'class' => array(),
            'style' => array(),
        ),
        'p'      => array(
            'id' => array(),
            'class' => array(),
            'style' => array(),
        ),
        'form'      => array(
            'id' => array(),
            'class' => array(),
            'method' => array(),
            'action' => array(),
            'enctype' => array(),
        ),
        'label'      => array(
            'id' => array(),
            'class' => array(),
            'for' => array(),
            'onclick' => array(),
            'style' => array(),
        ),
        'i'     => array(
            'id' => array(),
            'class' => array(),
            'aria-hidden' => array(),
            'style' => array(),
        ),
        'style'     => array(
            'src' => array(),
            'type' => array(),
            'class' => array(),
            'style' => array(),
        ),
        'script'     => array(
            'src' => array(),
            'type' => array(),
            'class' => array(),
            'style' => array(),
        ),
        'br'     => array(
            'style' => array(),),
        'hr'     => array(
            'id' => array(),
            'class' => array(),
            'style' => array(),),
        'b'     => array(
            'style' => array(),),
        'em'     => array(
            'style' => array(),),
        'strong' => array(
            'style' => array(),
        ),
        'small' => array(
            'style' => array(),),
        ' ' => array(),
    ));

}
?>
