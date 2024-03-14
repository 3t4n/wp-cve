<?php

function vdb_settings_page() {
global $vdb_options;
 
	ob_start(); ?>
	<div class="wrap">
		<h2>Video Dashboard Options</h2>
 
		<form method="post" action="options.php">
 
			<?php settings_fields('vdb_settings_group'); ?>
            

 
			<h4><?php _e('Youtube', 'vdb_domain'); ?></h4>
            
            <p><?php //Minimum Role Dropdown ?>
				<label class="description" for="vdb_settings[minimum_role]"><?php _e('What is the minimum user role you wish to be able to see the videos in the dashboard?', 'vdb_domain'); ?></label>
                <select id="vdb_settings[minimum_role]" name="vdb_settings[minimum_role]" value="<?php $vdb_options['minimum_role'] ?>">
				<option value="subscriber" <?php if ( 'subscriber' == $vdb_options['minimum_role'] )
  						echo 'selected="selected"'; ?>>Subscriber</option>
                <option value="contributor" <?php if ( 'contributor' == $vdb_options['minimum_role'] )
  						echo 'selected="selected"'; ?>>Contributor</option>
                <option value="author" <?php if ( 'author' == $vdb_options['minimum_role'] )
  						echo 'selected="selected"'; ?>>Author</option>
                <option value="editor" <?php if ( 'editor' == $vdb_options['minimum_role'] )
  						echo 'selected="selected"'; ?>>Editor</option>
                <option value="administrator" <?php if ( 'administrator' == $vdb_options['minimum_role'] )
  						echo 'selected="selected"'; ?>>Administrator</option>
                </select>
			</p>
            
            
            <p>
				<label class="description" for="vdb_settings[youtube_number]"><?php _e('How many YouTube videos would you like to display in the dashboard?', 'vdb_domain'); ?></label>
                <select id="vdb_settings[youtube_number]" name="vdb_settings[youtube_number]" value="<?php $vdb_options['youtube_number'] ?>">
				<?php 
					$range = range(1,50);
					foreach ($range as $videos) {
  						echo '<option value="' . $videos . '" ';
  						if ( $videos == $vdb_options['youtube_number'] )
  						echo 'selected="selected"';
 						echo '>' . $videos . '</option>';
						}
				echo "</select>"; ?>
                
			</p>
            
            <h4>Enter the video ID or URL for each video</h4>
            <?php
for ($i = 1; $i <= 50; $i++) { //Loop through the number of videos
?>
			<p>
				<label class="description" for="vdb_settings[youtube_id<?php _e($i);?>]" <?php if ($i > $vdb_options['youtube_number']) echo 'style="display:none;">'; ?>><?php _e('Video #' . $i, 'vdb_domain'); ?></label>
				<input id="vdb_settings[youtube_id<?php _e($i); ?>]" name="vdb_settings[youtube_id<?php _e($i); ?>]" type="text" value="<?php if (array_key_exists('youtube_id'  . $i, $vdb_options)) { echo $vdb_options['youtube_id' . $i ]; }
				if ($i > $vdb_options['youtube_number']) echo '" style="display:none;'; ?>"/>
			</p>
 <?php
} //End the Loop
?>

            
			<p class="submit">
				<input type="submit" class="button-primary" value="<?php _e('Save Options', 'vdb_domain'); ?>" />
			</p>
            
            
 
		</form>
 
	</div>
	<?php
	echo ob_get_clean();
}


function vdb_add_options_link() { //Add options menu item to settings
	add_options_page('Video Dashboard Plugin Options', 'Video Dashboard', 'manage_options', 'vdb-options', 'vdb_settings_page');
}
add_action('admin_menu', 'vdb_add_options_link');



function vdb_register_settings() {
	// create our settings in the options table
	register_setting('vdb_settings_group', 'vdb_settings');
}
add_action('admin_init', 'vdb_register_settings');










?>