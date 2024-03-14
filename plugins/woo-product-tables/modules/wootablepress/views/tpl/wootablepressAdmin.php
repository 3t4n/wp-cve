<section>
	<div class="woobewoo-item woobewoo-panel">
		<div id="containerWrapper">
			<ul id="wtbpTableTblNavBtnsShell" class="woobewoo-bar-controls">
				<li title="<?php echo esc_attr(__('Delete selected', 'woo-product-tables')); ?>">
					<button class="button button-small" id="wtbpTableRemoveGroupBtn" disabled data-toolbar-button>
						<i class="fa fa-fw fa-trash-o"></i>
						<?php esc_html_e('Delete selected', 'woo-product-tables'); ?>
					</button>
				</li>
				<?php
				if ($this->is_pro) {
					DispatcherWtbp::doAction('addAdminButtons');
				}
				?>
				<li title="<?php echo esc_attr(__('Search', 'woo-product-tables')); ?>">
					<input id="wtbpTableTblSearchTxt" type="text" name="tbl_search" placeholder="<?php echo esc_attr(__('Search', 'woo-product-tables')); ?>">
				</li>
			</ul>
			<div id="wtbpTableTblNavShell" class="woobewoo-tbl-pagination-shell"></div>
			<div class="woobewoo-clear"></div>
			<hr />
			<table id="wtbpTableTbl"></table>
			<div id="wtbpTableTblNav"></div>
			<div id="wtbpTableTblEmptyMsg" class="woobewoo-hidden">
				<h3>
					<?php 
					echo esc_html__('You have no Tables for now.', 'woo-product-tables') . ' <a href="' . esc_url($this->addNewLink) . '">' . esc_html__('Create', 'woo-product-tables') . '</a> ' . esc_html__('your Table', 'woo-product-tables') . '!';
					?>
				</h3>
			</div>
		</div>
		<div class="woobewoo-clear"></div>
	</div>
</section>
