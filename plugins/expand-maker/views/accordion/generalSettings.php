<?php
$id = 0;
if(!empty($_GET['readMoreId'])) {
	$id = $_GET['readMoreId'];
}
$tabs = $this->getOptionValue('yrm-accordion');
$allowedTag = ReadMoreAdminHelper::getAllowedTags();
?>
<?php $shortCode = '[yrm_accordion id="'.esc_attr($id).'"][/yrm_accordion]'; ?>
<?php if($id != 0): ?>
	<div class="yrm-tooltip form-group">
		<span class="yrm-tooltiptext" id="yrm-tooltip"><?php _e('Copy to clipboard', YRM_LANG)?></span>
		<input type="text" id="expm-shortcode-info-div" class="widefat" readonly="readonly" value='<?php echo wp_kses($shortCode, $allowedTag); ?>'>
	</div>
<?php endif; ?>
<?php if($id == 0): ?>
	<div class="no-shortcode form-group">
		<span><?php _e('Please do save Accordion for getting shortcode.', YRM_LANG); ?></span>
	</div>
<?php endif; ?>
<div class="panel panel-default">
	<div class="panel-heading"><?php _e('General Settings', YRM_LANG);?></div>
	<div class="panel-body">
		<div id="accordions-content-wrapper" class="accordions-wrapper">
			<?php foreach ($tabs as $key => $tab): ?>
				<?php include(dirname(__FILE__).'/ItemTemplateWrapper.php')?>
			<?php endforeach; ?>
		</div>
		<div class="footer-control">
			<button class="btn btn-primary yrm-add-accordion">Add New</button>
			<input type="hidden" class="accordion-indexes" data-value="<?php echo json_encode(array_keys($tabs))?>">
		</div>
		<div class="editor-template-wrapper">
			<?php
			$settings = array(
				'wpautop' => false,
				'tinymce' => false,
				'textarea_rows' => '18',
				'media_buttons' => true
			);
			wp_editor('Content', 'customEditorId', $settings);
			?>
		</div>
	</div>
</div>