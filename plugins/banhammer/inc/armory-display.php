<?php // Display Armory

if (!defined('ABSPATH')) exit;

function banhammer_display_armory() {
	
	?>
	
	<div id="banhammer" class="wrap">
		
		<?php banhammer_display_intro(); ?>
		
		<div class="banhammer-ui banhammer-armory" style="display:none;">
				
			<?php banhammer_display_tools(); ?>
			
			<?php banhammer_display_paging(); ?>
			
			<?php banhammer_display_header(); ?>
			
			<?php banhammer_display_response(); ?>
			
		</div>
		
	</div>
	
	<?php 
	
}

function banhammer_display_intro() {
	
	?>
	
	<h1><span class="dashicons-banhammer"></span> <?php esc_html_e('Banhammer Armory', 'banhammer'); ?></h1>
	
	<p><?php esc_html_e('Welcome to the Armory! Monitor traffic and hammer the enemy! Need Help? Visit the Help tab.', 'banhammer'); ?></p>
	
	<noscript><?php esc_html_e('JavaScript Required! The Armory uses Ajax to make it all sweet. So you need to enable JavaScript to make it work.', 'banhammer'); ?></noscript>
	
	<audio class="banhammer-fx-ban">
		<source src="<?php echo apply_filters('banhammer_fx_ban', plugins_url('/banhammer/fx/banhammer-ban.mp3')); ?>"></source>
		<source src="<?php echo apply_filters('banhammer_fx_ban', plugins_url('/banhammer/fx/banhammer-ban.ogg')); ?>"></source>
	</audio>
	
	<audio class="banhammer-fx-warn">
		<source src="<?php echo apply_filters('banhammer_fx_warn', plugins_url('/banhammer/fx/banhammer-warn.mp3')); ?>"></source>
		<source src="<?php echo apply_filters('banhammer_fx_warn', plugins_url('/banhammer/fx/banhammer-warn.ogg')); ?>"></source>
	</audio>
	
	<audio class="banhammer-fx-delete">
		<source src="<?php echo apply_filters('banhammer_fx_delete', plugins_url('/banhammer/fx/banhammer-delete.mp3')); ?>"></source>
		<source src="<?php echo apply_filters('banhammer_fx_delete', plugins_url('/banhammer/fx/banhammer-delete.ogg')); ?>"></source>
	</audio>
	
	<?php
	
}

function banhammer_display_tools() {
	
	?>
	
	<div class="banhammer-items banhammer-tools" data-toggle="hide">
		<div class="banhammer-item banhammer-tools-item">
			<span class="banhammer-tools-text"><?php esc_html_e('Display', 'banhammer'); ?></span> 
			<input type="number" class="banhammer-page-items" min="1" max="10"> 
			<span class="banhammer-tools-text"><?php esc_html_e('rows', 'banhammer'); ?></span> 
			<span class="banhammer-tools-text banhammer-hover-info"><?php esc_html_e('(Press Enter key to save)', 'banhammer-pro'); ?></span>
		</div>
		<div class="banhammer-item banhammer-tools-item">
			<a class="banhammer-toggle-link" 
				href="#banhammer" 
				data-view-adv="<?php esc_attr_e('Advanced view', 'banhammer'); ?>"
				data-view-bsc="<?php esc_html_e('Basic view', 'banhammer'); ?>">
				<?php esc_html_e('Basic view', 'banhammer'); ?>
			</a> 
			<span class="banhammer-sep">|</span> 
			<a class="banhammer-fx-link" 
				href="#banhammer" 
				data-fx-on="<?php esc_html_e('Disable sound fx', 'banhammer'); ?>" 
				data-fx-off="<?php esc_html_e('Enable sound fx', 'banhammer'); ?>">
				<?php esc_html_e('Enable sound fx', 'banhammer'); ?>
			</a> 
			<span class="banhammer-sep">|</span> 
			<a class="banhammer-delete-link" href="#banhammer"><?php esc_html_e('Delete all items', 'banhammer'); ?></a>
		</div>
	</div>
	
	<?php
	
}

function banhammer_display_paging() {
	
	?>
	
	<div class="banhammer-items banhammer-paging">
		<div class="banhammer-item banhammer-paging-item">
			<select class="banhammer-select-bulk">
				<option value="" selected="selected"><?php esc_html_e('Bulk Action:', 'banhammer'); ?></option>
				<option value="delete"><?php esc_html_e('Delete', 'banhammer'); ?></option>
			</select> 
			<button class="banhammer-action-bulk"><?php esc_attr_e('Apply', 'banhammer'); ?></button> 
		</div>
		<div class="banhammer-item banhammer-paging-item">
			<button class="banhammer-page-prev"><?php esc_attr_e('Prev', 'banhammer'); ?></button> 
			<input type="number" class="banhammer-page-jump" value="1" min="1"> 
			<span class="banhammer-paging-of"><?php esc_html_e('of', 'banhammer'); ?></span> 
			<span class="banhammer-page-total"></span> 
			<button class="banhammer-page-next"><?php esc_attr_e('Next', 'banhammer'); ?></button>
		</div>
		<div class="banhammer-item banhammer-paging-item">
			<select class="banhammer-select-sort">
				<option value="" selected="selected"><?php esc_html_e('Sort by:', 'banhammer'); ?></option>
				
				<?php 
					
					foreach (banhammer_armory_cols() as $k => $v) {
						
						echo '<option value="'. esc_attr($k) .'">'. esc_html($v) .'</option>'; // hee haw! ;)
						
					}
					
				?>
				
			</select> 
			<select class="banhammer-select-order">
				<option value="" selected="selected"><?php esc_html_e('Order:', 'banhammer'); ?></option>
				<option value="asc"><?php  esc_html_e('Ascend',  'banhammer'); ?></option>
				<option value="desc"><?php esc_html_e('Descend', 'banhammer'); ?></option>
			</select>
		</div>
	</div>
	
	<?php
	
}

function banhammer_display_header() {
	
	?>
	
	<div class="banhammer-items banhammer-header">
		<div class="banhammer-item banhammer-header-item">
			<input class="banhammer-select-all" type="checkbox" title="<?php esc_attr_e('Select all', 'banhammer'); ?>" data-title="<?php esc_attr_e('Bulk Action', 'banhammer'); ?>"> 
			<span class="banhammer-count"><?php esc_html_e('Loading results', 'banhammer'); ?></span> <span class="banhammer-sep">|</span> 
			<a class="banhammer-reload-link" href="#reset"><?php esc_html_e('Reset', 'banhammer'); ?></a> <span class="banhammer-sep">|</span> 
			<a class="banhammer-reload-current" href="#refresh"><?php esc_html_e('Refresh', 'banhammer'); ?></a> <span class="banhammer-sep">|</span> 
			<a class="banhammer-tools-link" href="#tools"><?php esc_html_e('Tools', 'banhammer'); ?></a> <span class="banhammer-sep">|</span> 
			<select class="banhammer-select-status">
				<option value=""><?php esc_html_e('Status:', 'banhammer'); ?></option>
				<option value="all" selected="selected"><?php esc_html_e('All', 'banhammer'); ?></option>
				<option value="ban"><?php esc_html_e('Banned', 'banhammer'); ?></option>
				<option value="warn"><?php esc_html_e('Warned', 'banhammer'); ?></option>
				<option value="restore"><?php esc_html_e('Restored', 'banhammer'); ?></option>
			</select>
		</div>
		<div class="banhammer-item banhammer-header-item">
			<input type="search" class="banhammer-action-search" placeholder="<?php esc_attr_e('Search', 'banhammer'); ?>"> 
			<select class="banhammer-select-filter">
				<option value=""><?php esc_html_e('Filter:', 'banhammer'); ?></option>
				<option value="all" selected="selected"><?php esc_html_e('Everything', 'banhammer'); ?></option>
				<?php foreach (banhammer_armory_cols() as $k => $v) echo '<option value="'. esc_attr($k) .'">'. esc_html($v) .'</option>'; ?>
			</select>
		</div>
	</div>
	
	<?php
	
}

function banhammer_display_response() {
	
	?>
	
	<div class="banhammer-response"></div>
	<div class="banhammer-loading" style="display:none;">
		<div class="banhammer-loading-wrap">
			<?php // ::before ?>
			<div class="banhammer-loading-message">
				<span><?php esc_html_e('Loading', 'banhammer'); ?></span> 
				<a class="banhammer-reload-current" href="#reload"><?php esc_html_e('Reload', 'banhammer'); ?></a>
			</div>
		</div>
	</div>
	
	<?php
	
}
