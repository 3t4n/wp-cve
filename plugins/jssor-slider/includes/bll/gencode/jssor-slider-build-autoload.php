<?php
// Exit if accessed directly
if( !defined( 'ABSPATH') ) exit();

function callback_wjssl_hump_to_line($matches) {
    return '-'.strtolower($matches[0]);
}

function wjssl_autoload_css_parser($class_name, $models_path) {
    $mapping = array(
        'WjsslCssComment' => 'Comment/Comment.php',
        'WjsslCssCommentable' => 'Comment/Commentable.php',

        'WjsslCssAtRuleBlockList' => 'CSSList/AtRuleBlockList.php',
        'WjsslCSSBlockList' => 'CSSList/CSSBlockList.php',
        'WjsslCSSList' => 'CSSList/CSSList.php',
        'WjsslCssDocument' => 'CSSList/Document.php',
        'WjsslCssKeyFrame' => 'CSSList/KeyFrame.php',

        'WjsslCssOutputException' => 'Parsing/OutputException.php',
        'WjsslCssSourceException' => 'Parsing/SourceException.php',
        'WjsslCssUnexpectedTokenException' => 'Parsing/UnexpectedTokenException.php',

        'WjsslCssAtRule' => 'Property/AtRule.php',
        'WjsslCssCharset' => 'Property/Charset.php',
        'WjsslCSSNamespace' => 'Property/CSSNamespace.php',
        'WjsslCssImport' => 'Property/Import.php',
        'WjsslCssSelector' => 'Property/Selector.php',

        'WjsslCssRule' => 'Rule/Rule.php',

        'WjsslCssAtRuleSet' => 'RuleSet/AtRuleSet.php',
        'WjsslCssDeclarationBlock' => 'RuleSet/DeclarationBlock.php',
        'WjsslCssRuleSet' => 'RuleSet/RuleSet.php',

        'WjsslCssColor' => 'Value/Color.php',
        'WjsslCSSFunction' => 'Value/CSSFunction.php',
        'WjsslCSSString' => 'Value/CSSString.php',
        'WjsslCssPrimitiveValue' => 'Value/PrimitiveValue.php',
        'WjsslCssRuleValueList' => 'Value/RuleValueList.php',
        'WjsslCssSize' => 'Value/Size.php',
        'WjsslCssURL' => 'Value/URL.php',
        'WjsslCssValue' => 'Value/Value.php',
        'WjsslCssValueList' => 'Value/ValueList.php',

        'WjsslCssOutputFormat' => 'OutputFormat.php',
        'WjsslCssParser' => 'Parser.php',
        'WjsslCssRenderable' => 'Renderable.php',
        'WjsslCssSettings' => 'Settings.php',

    );

    if (empty($mapping[$class_name])) {
        return false;
    }

    $file = $models_path . '/lib/css/' . $mapping[$class_name];
    if (file_exists($file)) {
        require_once $file;
        return true;
    }
    return false;
}

function wjssl_autoload_model($class_name, $models_path) {
    $design_time_path = $models_path . '/design-time-document';
    $runtime_path = $models_path . '/runtime-document';
    $shared_path = $models_path . '/shared';
    $lib_path = $models_path . '/lib';

    $filename = 'class' . preg_replace_callback('/([A-Z]){1}/', 'callback_wjssl_hump_to_line', $class_name) . '.php';

    if ((strpos($class_name, 'WjsslEnum') === 0) && file_exists($shared_path . '/class-wjssl-enums.php')) {
        require_once $shared_path . '/class-wjssl-enums.php';
        return true;
    } elseif ((strpos($class_name, 'WjsslDesignTime') === 0) && file_exists($design_time_path . '/' . $filename)) {

        require_once $design_time_path . '/' . $filename;
        return true;

    } elseif ((strpos($class_name, 'WjsslRuntime') === 0) && file_exists($runtime_path . '/' . $filename)) {
        require_once $runtime_path . '/' . $filename;
        return true;

    } elseif (file_exists($runtime_path . '/' . $filename)) {
        require_once $runtime_path . '/' . $filename;
        return true;

    } elseif (file_exists($shared_path . '/' . $filename)) {
        require_once $shared_path . '/' . $filename;
        return true;

    } elseif (file_exists($models_path . '/' . $filename)) {
        require_once $models_path . '/' . $filename;
        return true;

    }  elseif (file_exists($lib_path . '/' . $filename)) {
        require_once $lib_path . '/' . $filename;
        return true;
    }

    return false;
}

function wjssl_autoload_class($class_name) {
    if (strpos($class_name, 'Wjssl') !== 0) {
        return false;
    }

    if (defined('WP_JSSOR_SLIDER_PATH')) {
        $plugin_path = WP_JSSOR_SLIDER_PATH;
    } else {
        $plugin_path = dirname(__FILE__) . '/';
    }

    if (stripos($class_name, 'wjsslcss') === 0) {
        return wjssl_autoload_css_parser($class_name, $plugin_path);
    }

    $models_path = $plugin_path . 'includes/models';
    return wjssl_autoload_model($class_name, $models_path);
}

spl_autoload_register('wjssl_autoload_class');
