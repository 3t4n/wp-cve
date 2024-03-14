<?php // Display Tower

if (!defined('ABSPATH')) exit;

function banhammer_display_tower() {
	
	?>
	
	<div id="banhammer" class="wrap">
		
		<h1><span class="dashicons-banhammer"></span> <?php esc_html_e('Banhammer Tower', 'banhammer'); ?></h1>
		
		<p><?php esc_html_e('Welcome to the Tower! Here you can manage banned users and bots. Need Help? Visit the Help tab.', 'banhammer'); ?></p>
		
		<noscript><?php esc_html_e('JavaScript Required! The Armory uses Ajax to make it all sweet. So you need to enable JavaScript to make it work.', 'banhammer'); ?></noscript>
		
		<audio class="banhammer-fx-ban">
			<source src="<?php echo apply_filters('banhammer_fx_ban', plugins_url('/banhammer/fx/banhammer-ban.mp3')); ?>"></source>
			<source src="<?php echo apply_filters('banhammer_fx_ban', plugins_url('/banhammer/fx/banhammer-ban.ogg')); ?>"></source>
		</audio>
		
		<audio class="banhammer-fx-warn">
			<source src="<?php echo apply_filters('banhammer_fx_warn', plugins_url('/banhammer/fx/banhammer-warn.mp3')); ?>"></source>
			<source src="<?php echo apply_filters('banhammer_fx_warn', plugins_url('/banhammer/fx/banhammer-warn.ogg')); ?>"></source>
		</audio>
		
		<audio class="banhammer-fx-restore">
			<source src="<?php echo apply_filters('banhammer_fx_restore', plugins_url('/banhammer/fx/banhammer-restore.mp3')); ?>"></source>
			<source src="<?php echo apply_filters('banhammer_fx_restore', plugins_url('/banhammer/fx/banhammer-restore.ogg')); ?>"></source>
		</audio>
		
		<audio class="banhammer-fx-delete">
			<source src="<?php echo apply_filters('banhammer_fx_delete', plugins_url('/banhammer/fx/banhammer-delete.mp3')); ?>"></source>
			<source src="<?php echo apply_filters('banhammer_fx_delete', plugins_url('/banhammer/fx/banhammer-delete.ogg')); ?>"></source>
		</audio>
		
		<div class="banhammer-ui banhammer-tower" style="display:none;">
			
			<div class="banhammer-header">
				<div class="banhammer-header-item">
					<input class="banhammer-select-all" type="checkbox" title="<?php esc_attr_e('Select all', 'banhammer'); ?>" data-title="<?php esc_attr_e('Bulk Action', 'banhammer'); ?>"> 
					<select class="banhammer-select-bulk">
						<option value="" selected="selected"><?php esc_html_e('Bulk Action', 'banhammer'); ?></option>
						<option value="ban"><?php     esc_html_e('Ban',       'banhammer'); ?></option>
						<option value="warn"><?php    esc_html_e('Warn',      'banhammer'); ?></option>
						<option value="restore"><?php esc_html_e('Restore',   'banhammer'); ?></option>
						<option value="delete"><?php  esc_html_e('Delete',    'banhammer'); ?></option>
					</select> 
					<span class="banhammer-sep">|</span> <span class="banhammer-count"><?php esc_html_e('Loading results', 'banhammer'); ?></span> 
					<span class="banhammer-sep">|</span> <a class="banhammer-reload-current" href="#refresh"><?php esc_html_e('Refresh', 'banhammer'); ?></a>
				</div>
				<div class="banhammer-header-item">
					<select class="banhammer-select-sort">
						<option value="" selected="selected"><?php esc_html_e('Sort', 'banhammer'); ?></option>
						<option value="banned"><?php   esc_html_e('Banned',   'banhammer'); ?></option>
						<option value="warned"><?php   esc_html_e('Warned',   'banhammer'); ?></option>
						<option value="restored"><?php esc_html_e('Restored', 'banhammer'); ?></option>
					</select> 
					<select class="banhammer-select-type">
						<option value="" selected="selected"><?php esc_html_e('Type', 'banhammer'); ?></option>
						<option value="ip"><?php   esc_html_e('IP Address', 'banhammer'); ?></option>
						<option value="user"><?php esc_html_e('WP User',    'banhammer'); ?></option>
					</select>
				</div>
			</div>
			
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
			
		</div>
		
	</div>
	
	<?php 
	
}
