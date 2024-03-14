<div id="wtbp-table-wrapper-<?php echo esc_attr($this->viewId); ?>" style="visibility: hidden;" data-table-id="wtbp-table-<?php echo esc_attr($this->viewId); ?>" class="wtbpTableWrapper">
	<?php HtmlWtbp::echoEscapedHtml($this->pre_table); ?> 
	<table id="wtbp-table-<?php echo esc_attr($this->viewId); ?>" data-table-id="<?php echo esc_attr($this->tableId); ?>" class="wtbpContentTable" data-settings="<?php echo esc_attr(htmlspecialchars(json_encode($this->settings['settings']), ENT_QUOTES, 'UTF-8')); ?>">
		<?php HtmlWtbp::echoEscapedHtml($this->html); ?>
	</table>
	<div class="wtbpFilterWrapper">
		<?php HtmlWtbp::echoEscapedHtml($this->filter); ?> 
	</div>
	<div class="wtbpCustomCssWrapper wtbpHidden">
		<?php HtmlWtbp::echoEscapedHtml($this->custom_css); ?>
	</div>
	<?php HtmlWtbp::echoEscapedHtml($this->loader); ?>
</div>
