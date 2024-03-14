<?php 
if ($status != "SUCCESS") return;

$options = $resolve('options');
$form = $output['form'];
unset($output['form']);
$word = $resolve('word')->load();

$output['catalog'] = $options->extendWord($word, $form);

$depends = $options->extendScriptDeps(array('jquery'), $form);
wp_enqueue_script('form-result-js', $urlHelper->asset('/asset/result.js'), $depends, \AFormsWrap::VERSION, true);
wp_localize_script('form-result-js', 'wqData', $output);

// enqueue a style for this form after those of themes.
wp_enqueue_style('dashicons');
$stylesheet = $options->extendStylesheetUrl($urlHelper->asset('/asset/front.css'), $form);
wp_enqueue_style('front-css', $stylesheet, array('dashicons'), \AFormsWrap::VERSION);

?>
<div id="root"></div>