<style type="text/css">
.woobewoo-main {
	display:none;
}
.woobewoo-plugin-loader {
	width: 100%;
	height: 100px;
	text-align: center;
}
.woobewoo-plugin-loader div {
	font-size: 30px;
	position: relation;
	margin-top: 40px;
}
</style>
<div class="wrap woobewoo-wrap">
	<div class="woobewoo-plugin woobewoo-main">
		<section class="woobewoo-content">
			<nav class="woobewoo-navigation woobewoo-sticky <?php DispatcherWtbp::doAction('adminMainNavClassAdd'); ?>">
				<ul>
					<?php foreach ($this->tabs as $tabKey => $t) { ?>
						<?php 
						if (isset($t['hidden']) && $t['hidden']) {
							continue;
						}
						?>
						<li class="woobewoo-tab-<?php echo esc_attr($tabKey); ?> <?php echo ( ( $this->activeTab == $tabKey || in_array($tabKey, $this->activeParentTabs) ) ? 'active' : '' ); ?>">
							<a href="<?php echo esc_url($t['url']); ?>" title="<?php echo esc_attr($t['label']); ?>">
								<?php if (isset($t['fa_icon'])) { ?>
									<i class="fa <?php echo esc_attr($t['fa_icon']); ?>"></i>
								<?php } elseif (isset($t['wp_icon'])) { ?>
									<i class="dashicons-before <?php echo esc_attr($t['wp_icon']); ?>"></i>
								<?php } elseif (isset($t['icon'])) { ?>
									<i class="<?php echo esc_attr($t['icon']); ?>"></i>
								<?php } ?>
								<span class="sup-tab-label"><?php echo esc_html($t['label']); ?></span>
							</a>
						</li>
					<?php } ?>
				</ul>
			</nav>
			<div class="woobewoo-container woobewoo-<?php echo esc_attr($this->activeTab); ?>">
				<?php HtmlWtbp::echoEscapedHtml($this->breadcrumbs); ?>
				<?php HtmlWtbp::echoEscapedHtml($this->content); ?>
				<div class="clear"></div>
			</div>
		</section>
		<div id="wtbpAddDialog" class="woobewoo-plugin woobewoo-hidden" title="<?php echo esc_attr(__('Create new table', 'woo-product-tables')); ?>">
			<div class="row col-xs-12 wtbpAddDialogTitle">
				<input id="addDialog_title" class="woobewoo-text woobewoo-flat-input wtbp-width200" type="text" placeholder="<?php echo esc_attr(__('Enter Table Name', 'woo-product-tables')); ?>"/>
				<div id="wtbpCreateTableFilters" class="wtbpDialogTableFilters">
					<label><?php esc_html_e('Select Products to add:', 'woo-product-tables'); ?></label>
					<select name="filter_author" class="woobewoo-flat-input">
						<option value=""><?php esc_html_e('Select author', 'woo-product-tables'); ?></option>
						<?php HtmlWtbp::echoEscapedHtml($this->authors_html); ?>
					</select>
					<select name="filter_category" class="woobewoo-flat-input">
						<option value=""><?php esc_html_e('Select category', 'woo-product-tables'); ?></option>
						<?php HtmlWtbp::echoEscapedHtml($this->categories_html); ?>
					</select>
					<select name="filter_tag" class="woobewoo-flat-input">
						<option value=""><?php esc_html_e('Select tag', 'woo-product-tables'); ?></option>
						<?php HtmlWtbp::echoEscapedHtml($this->tags_html); ?>
					</select>
					<select name="filter_attribute" class="woobewoo-flat-input wtbp-mr30">
						<option value=""><?php esc_html_e('Select attribute', 'woo-product-tables'); ?></option>
						<?php HtmlWtbp::echoEscapedHtml($this->attributes_html); ?>
					</select>
					<div class="wtbpCreateTableFilter">
						<input type="checkbox" name="filter_attribute_exactly" value="1"> <?php esc_html_e('only current attribute', 'woo-product-tables'); ?>
					</div>
					<div class="wtbpCreateTableFilter">
						<input type="checkbox" name="show_variations" value="1"> <?php esc_html_e('show variations', 'woo-product-tables'); ?>
					</div>
					<div class="wtbpCreateTableFilter">
						<input type="checkbox" name="filter_private" value="1"> <?php esc_html_e('show private', 'woo-product-tables'); ?>
					</div>
				</div>
			</div>
			<div class="wtbpAdminTableWrapp">
				<input type="hidden" id="wtbpCreateTableSelectAll" value="0">
				<input type="hidden" id="wtbpCreateTableSelectExclude" value="">
				<table id="wtbpCreateTable" class="wtbpSearchTable">
					<?php HtmlWtbp::echoEscapedHtml($this->search_table); ?>
				</table>
			</div>
			<div id="formError" class="woobewoo-hidden">
				<p></p>
			</div>
			<!-- /#formError -->
		</div>
		<!-- /#addDialog -->
	</div>
	<div class="woobewoo-plugin-loader">
		<div>Loading...<i class="fa fa-spinner fa-spin"></i></div>
	</div>
</div>


