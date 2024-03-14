<div id="web-fonts-font-selectors">
	<form id="web-fonts-font-selectors-inner" method="post">
		<h2 class="nav-tab-wrapper">
			<a class="nav-tab" data-bind="click: function() { visible_tab('font'); }, css: { 'nav-tab-active': show_by_font }" ><?php _e('By Font'); ?></a>
			<a class="nav-tab" data-bind="click: function() { visible_tab('selector'); }, css: { 'nav-tab-active': show_by_selector }" ><?php _e('By Selector'); ?></a>
		</h2>
		
		<?php settings_errors(); ?>
		
		<?php include('selectors-by-font.php'); ?>
		
		<?php include('selectors-by-selector.php'); ?>
		
		<?php wp_nonce_field('web-fonts-manage-stylesheet', 'web-fonts-manage-stylesheet-nonce'); ?>
	</form>
</div>

<?php
$font_and_selector_data = apply_filters('web_fonts_manage_stylesheet_fonts_and_selectors', array('fonts' => array(), 'selectors' => array())); 
?>

<script type="text/javascript">
	var WebFontsStylesheetFonts = <?php echo json_encode($font_and_selector_data['fonts']); ?>;
	var WebFontsStylesheetSelectors = <?php echo json_encode($font_and_selector_data['selectors']); ?>;
</script>
 
<?php do_action('web_fonts_manage_stylesheet_page_bottom'); ?>
