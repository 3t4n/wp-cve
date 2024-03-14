<?php
if (! defined('ABSPATH')) {
    exit(); // Exit if accessed directly
}
if (! class_exists('GFPDADMIN')) {

    class GFPDADMIN
    {

        
        public static function gfpd_admin_settings(){
            
            if (! empty($_REQUEST['_wpnonce']) && wp_verify_nonce($_REQUEST['_wpnonce'], 'gfpd_options_save')) {
                foreach ($_REQUEST as $option_name => $option_value) {
                    $prefix = 'gfpd_';
                    $prefixSize = strlen($prefix);
                    if (substr($option_name, 0, $prefixSize) == $prefix) {
                        if ($option_name == 'gfpd_scripts_limit') {
                            $option_value = str_replace(' ', '', $option_value);
                        } // clean up comma seperated emails, no spaces needed
                        update_option($option_name, $option_value);
                    }
                }
                if (empty($_REQUEST['gfpd_post_taxonomies'])) {
                    update_option('gfpd_post_taxonomies', '');
                }
                echo '<div class="updated notice"><p>' . __('Settings saved.') . '</p></div>';
            }
            
            ?>
            <form method="post" action="">
            <?php wp_nonce_field('gfpd') ?>
			
			</fieldset>
			<fieldset class="gfpd_admin_settings" >
  <legend>Settings:</legend><?php 	
			
			$title = __('Exclude following form ids from duplicate prevention');
			$name = 'gfpd_excluded_ids';
			$description = 'enter a come separated list, like : 12,22,35';
			$defaultIds = "";
			gfpd_options_textarea($title, $name, $description, $defaultIds);		
		
			
			?></fieldset>
	
			
			<input type="hidden" name="_wpnonce"
						value="<?php echo wp_create_nonce('gfpd_options_save'); ?>" />
<?php submit_button(__( 'Save options', 'gfpd' ) ); ?>
</form>


<?php 
        }
      
    
    }
}


/*
 * Admin UI Helpers
 */
function gfpd_options_input_text($title, $name, $description, $default='') {
    ?>
	<tr valign="top" id='<?php echo esc_attr($name);?>_row'>
		<th scope="row"><?php echo esc_html($title); ?></th>
	    <td>
			<input name="<?php echo esc_attr($name) ?>" type="text" id="<?php echo esc_attr($title) ?>" style="width: 95%" value="<?php echo esc_attr(get_option($name, $default), ENT_QUOTES); ?>" size="45" /><br />
			<em><?php echo $description; ?></em>
		</td>
	</tr>
	<?php
}
function gfpd_options_input_password($title, $name, $description) {
	?>
	<tr valign="top" id='<?php echo esc_attr($name);?>_row'>
		<th scope="row"><?php echo esc_html($title); ?></th>
	    <td>
			<input name="<?php echo esc_attr($name) ?>" type="password" id="<?php echo esc_attr($title) ?>" style="width: 95%" value="<?php echo esc_attr(get_option($name)); ?>" size="45" /><br />
			<em><?php echo $description; ?></em>
		</td>
	</tr>
	<?php
}

function gfpd_options_textarea($title, $name, $description,$default = '') {
	?>
	<tr valign="top" id='<?php echo esc_attr($name);?>_row'>
		<th scope="row"><?php echo esc_html($title); ?></th>
			<td>
				<textarea name="<?php echo esc_attr($name) ?>" id="<?php echo esc_attr($name) ?>" rows="6" cols="60"><?php echo esc_attr(get_option($name, $default), ENT_QUOTES);?></textarea><br/>
				<em><?php echo $description; ?></em>
			</td>
		</tr>
	<?php
}

function gfpd_options_radio($name, $options, $title='') {
		$option = get_option($name);
		?>
	   	<tr valign="top" id='<?php echo esc_attr($name);?>_row'>
	   		<?php if( !empty($title) ): ?>
	   		<th scope="row"><?php  echo esc_html($title); ?></th>
	   		<td>
	   		<?php else: ?>
	   		<td colspan="2">
	   		<?php endif; ?>
	   			<table>
	   			<?php foreach($options as $value => $text): ?>
	   				<tr>
	   					<td><input id="<?php echo esc_attr($name) ?>_<?php echo esc_attr($value); ?>" name="<?php echo esc_attr($name) ?>" type="radio" value="<?php echo esc_attr($value); ?>" <?php if($option == $value) echo "checked='checked'"; ?> /></td>
	   					<td><?php echo $text ?></td>
	   				</tr>
				<?php endforeach; ?>
				</table>
			</td>
	   	</tr>
<?php
}

function gfpd_options_radio_binary($title, $name, $description, $option_names = '') {
	if( empty($option_names) ) $option_names = array(0 => __('No','dbem'), 1 => __('Yes','dbem'));
	if( substr($name, 0, 7) == 'dbem_ms' ){
		$list_events_page = get_site_option($name);
	}else{
		$list_events_page = get_option($name);
	}
	?>
   	<tr valign="top" id='<?php echo $name;?>_row'>
   		<th scope="row"><?php echo esc_html($title); ?></th>
   		<td>
   			<?php echo $option_names[1]; ?> <input id="<?php echo esc_attr($name) ?>_yes" name="<?php echo esc_attr($name) ?>" type="radio" value="1" <?php if($list_events_page) echo "checked='checked'"; ?> />&nbsp;&nbsp;&nbsp;
			<?php echo $option_names[0]; ?> <input  id="<?php echo esc_attr($name) ?>_no" name="<?php echo esc_attr($name) ?>" type="radio" value="0" <?php if(!$list_events_page) echo "checked='checked'"; ?> />
			<br/><em><?php echo $description; ?></em>
		</td>
   	</tr>
	<?php
}

function gfpd_options_select($title, $name, $list, $description, $default='') {
	$option_value = get_option($name, $default);
	if( $name == 'dbem_events_page' && !is_object(get_page($option_value)) ){
		$option_value = 0; //Special value
	}
	?>
   	<tr valign="top" id='<?php echo esc_attr($name);?>_row'>
   		<th scope="row"><?php echo esc_html($title); ?></th>
   		<td>
			<select name="<?php echo esc_attr($name); ?>" >
				<?php foreach($list as $key => $value) : ?>
 				<option value='<?php echo esc_attr($key) ?>' <?php echo ("$key" == $option_value) ? "selected='selected' " : ''; ?>>
 					<?php echo esc_html($value); ?>
 				</option>
				<?php endforeach; ?>
			</select> <br/>
			<em><?php echo $description; ?></em>
		</td>
   	</tr>
	<?php
}
