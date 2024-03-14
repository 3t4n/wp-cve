<tr valign="top">
	<td colspan="2">
		<h2 id="Vbout-settings-tabs" class="nav-tab-wrapper">
			<input type="hidden" name="vb-current-navtab" id="vb-current-navtab" value="<?php echo $current_tab;?>">
			<?php foreach($tab_headers as $tabKey => $tabLabel): $class = ( $tabKey == $current_tab ) ? ' nav-tab-active' : ''; ?>			
			<a class="nav-tab <?php echo $class; ?>" data-tab="<?php echo $tabKey; ?>" href="#"><?php echo $tabLabel; ?></a>
			<?php endforeach; ?>
		</h2>
			
		<?php foreach($settings_tabs as $tabKey => $tabContent): $class = ( $tabKey == $current_tab ) ? ' tabs-panel-active' : ' tabs-panel-inactive'; ?>
		<div id="<?php echo $tabKey; ?>" class="tabs-panel <?php echo $class; ?>">
			<table class="form-table">
				<?php echo $tabContent; ?>
			</table>
		</div>
		<?php endforeach; ?>
	</td>
</tr>