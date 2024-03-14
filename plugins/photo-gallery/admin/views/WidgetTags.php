<?php
/**
 * Class WidgetTagsView_bwg
 */
class WidgetTagsView_bwg {
  /**
   * Widget.
   *
   * @param $args
   * @param $instance
   */
	function widget($args, $instance) {
		extract($args);
		$title = (isset($instance['title']) ? sanitize_text_field($instance['title']) : "");
		$type = (isset($instance['type']) ? sanitize_text_field($instance['type']) : "text");
		$show_name = (isset($instance['show_name']) ? intval($instance['show_name']) : 0);
		$open_option = (isset($instance['open_option']) ? sanitize_text_field($instance['open_option']) : 'page');
		$count = (isset($instance['count']) ? intval($instance['count']) : 0);
		$width = (isset($instance['width']) ? intval($instance['width']) : 250);
		$height = (isset($instance['height']) ? intval($instance['height']) : 250);
		$background_transparent = (isset($instance['background_transparent']) ? floatval($instance['background_transparent']) : 1);
		$background_color = (isset($instance['background_color']) ? sanitize_text_field($instance['background_color']) : "000000");
		$text_color = (isset($instance['text_color']) ? sanitize_text_field($instance['text_color']) : "eeeeee");
		$theme_id = (isset($instance['theme_id']) ? intval($instance['theme_id']) : 0);
		// Before widget.
		echo $before_widget;
		// Title of widget.
		if ($title) {
		  echo $before_title . $title . $after_title;
		}
		// Widget output.
		require_once(BWG()->plugin_dir . '/frontend/controllers/BWGControllerWidget.php');
		$controller_class = 'BWGControllerWidgetFrontEnd';
		$controller = new $controller_class();
		$params = array (
		  'type' => $type,
		  'bwg' => ( !WDWLibrary::elementor_is_active() ? WDWLibrary::unique_number() : 0 ),
		  'show_name' => $show_name,
		  'open_option' => $open_option,
		  'count' => $count, 
		  'width' => $width, 
		  'height' => $height, 
		  'background_transparent' => $background_transparent, 
		  'background_color' => $background_color, 
		  'text_color' => $text_color,
		  'theme_id' => $theme_id);
		$controller->execute($params);
		// After widget.
		echo $after_widget;
	}

	/**
	 * Widget Control Panel.
	 *
	 * @param $params
	 * @param $instance
	 */
	function form($params, $instance) {
		wp_register_script(BWG()->prefix . '_jscolor', BWG()->plugin_url . '/js/jquery.jscolor.js', array('jquery'), '2.4.8');
		wp_enqueue_script(BWG()->prefix . '_jscolor');
		extract($params);
		$defaults = array(
		  'title' => __('Photo Gallery Tags Cloud', 'photo-gallery'),
		  'type' => 'text',      
		  'show_name' => 0,      
		  'open_option' => 'page',
		  'count' => 0,
		  'width' => 250,
		  'height' => 250,
		  'background_transparent' => 1,
		  'background_color' => '000000',
		  'text_color' => 'eeeeee',
		  'theme_id' => 0,
		);
		$instance = wp_parse_args((array) $instance, $defaults);   
		?>    
		<p>
		  <label for="<?php echo $id_title; ?>"><?php esc_html_e('Title:', 'photo-gallery'); ?></label>
		  <input class="widefat" id="<?php echo esc_attr($id_title); ?>" name="<?php echo esc_attr($name_title); ?>'" type="text" value="<?php echo htmlspecialchars( $instance['title'] ); ?>"/>
		</p>    
		<p>
		  <label for="<?php echo $id_title; ?>"><?php esc_html_e('Type:', 'photo-gallery'); ?></label><br>
		  <input type="radio" name="<?php echo esc_attr($name_type); ?>" id="<?php echo esc_attr($id_type . "_1"); ?>" value="text" class="sel_text" <?php if ($instance['type'] == "text") echo 'checked="checked"'; ?> onclick="jQuery(this).nextAll('.bwg_hidden').first().attr('value', 'text'); jQuery(this).closest('div').find('#p_show_name').hide();" /><label for="<?php echo esc_attr($id_type . "_1"); ?>"><?php echo __('Text', 'photo-gallery'); ?></label><br>
		  <input type="radio" name="<?php echo esc_attr($name_type); ?>" id="<?php echo esc_attr($id_type . "_2"); ?>" value="image" class="sel_image" <?php if ($instance['type'] == "image") echo 'checked="checked"'; ?> onclick="jQuery(this).nextAll('.bwg_hidden').first().attr('value', 'image'); jQuery(this).closest('div').find('#p_show_name').show();" /><label for="<?php echo esc_attr($id_type . "_2"); ?>"><?php echo __('Image', 'photo-gallery'); ?></label>
		  <input type="hidden" name="<?php echo esc_attr($name_type); ?>" id="<?php echo esc_attr($id_type); ?>" value="<?php echo esc_html($instance['type']); ?>" class="bwg_hidden" />
		</p>
		<p id="p_show_name" style="display:<?php echo ($instance['type'] == 'image') ? "" : "none" ?>;">
		  <label><?php esc_html_e('Show Tag Names:', 'photo-gallery'); ?></label><br>
		  <input type="radio" name="<?php echo esc_attr($name_show_name); ?>" id="<?php echo esc_attr($id_show_name . "_1"); ?>" value="1" <?php if ($instance['show_name']) echo 'checked="checked"'; ?> onclick='jQuery(this).nextAll(".bwg_hidden").first().attr("value", "1");' /><label for="<?php echo esc_attr($id_show_name . "_1"); ?>"><?php esc_html_e('Yes', 'photo-gallery'); ?></label><br>
		  <input type="radio" name="<?php echo esc_attr($name_show_name); ?>" id="<?php echo esc_attr($id_show_name . "_0"); ?>" value="0" <?php if (!$instance['show_name']) echo 'checked="checked"'; ?> onclick='jQuery(this).nextAll(".bwg_hidden").first().attr("value", "0");' /><label for="<?php echo esc_attr($id_show_name . "_0"); ?>"><?php esc_html_e('No', 'photo-gallery'); ?></label>
		  <input type="hidden" name="<?php echo esc_attr($name_show_name); ?>" id="<?php echo esc_attr($id_show_name); ?>" value="<?php echo esc_html($instance['show_name']); ?>" class="bwg_hidden" />
		</p>
		<p>
		  <label><?php esc_html_e('Open in:', 'photo-gallery'); ?></label><br>
		  <input type="radio" name="<?php echo esc_attr($name_open_option); ?>" id="<?php echo esc_attr($id_open_option . "_1"); ?>" value="page" <?php if ($instance['open_option'] == 'page') echo 'checked="checked"'; ?> onclick='jQuery(this).nextAll(".bwg_hidden").first().attr("value", "page");' /><label for="<?php echo esc_attr($id_open_option . "_1"); ?>"> <?php esc_html_e('Page', 'photo-gallery'); ?></label><br>
		  <input type="radio" name="<?php echo esc_attr($name_open_option); ?>" id="<?php echo esc_attr($id_open_option . "_0"); ?>" value="lightbox" <?php if ($instance['open_option'] == 'lightbox') echo 'checked="checked"'; ?> onclick='jQuery(this).nextAll(".bwg_hidden").first().attr("value", "lightbox");' /><label for="<?php echo esc_attr($id_open_option . "_0"); ?>"> <?php esc_html_e('Lightbox', 'photo-gallery'); ?></label>
		  <input type="hidden" name="<?php echo esc_attr($name_open_option); ?>" id="<?php echo esc_attr($id_open_option); ?>" value="<?php echo esc_html($instance['open_option']); ?>" class="bwg_hidden" />
		</p>
		<p>
		  <label for="<?php echo esc_attr($id_count); ?>"><?php esc_html_e('Number:', 'photo-gallery'); ?></label><br>
		  <input class="widefat" style="width:25%;" id="<?php echo esc_attr($id_count); ?>" name="<?php echo esc_attr($name_count); ?>'" type="text" value="<?php echo intval($instance['count']); ?>"/><br>
		  <small><?php esc_html_e('0 for all.', 'photo-gallery'); ?></small>
		</p>
		<p>
		  <label for="<?php echo esc_attr($id_width); ?>"><?php esc_html_e('Dimensions:', 'photo-gallery'); ?></label><br>
		  <input class="widefat" style="width:25%;" id="<?php echo esc_attr($id_width); ?>" name="<?php echo esc_attr($name_width); ?>'" type="text" value="<?php echo intval($instance['width']); ?>"/> x
		  <input class="widefat" style="width:25%;" id="<?php echo esc_attr($id_height); ?>" name="<?php echo esc_attr($name_height); ?>'" type="text" value="<?php echo intval($instance['height']); ?>"/> px
		</p>
		<p>
		  <label><?php esc_html_e('Transparent Background:', 'photo-gallery'); ?></label><br>
		  <input type="radio" name="<?php echo esc_attr($name_background_transparent); ?>" id="<?php echo esc_attr($id_background_transparent . "_1"); ?>" value="1" <?php if ($instance['background_transparent']) echo 'checked="checked"'; ?> onclick="jQuery(this).nextAll('.bwg_hidden').first().attr('value', '1'); jQuery(this).closest('div').find('#p_bg_color').hide();" class="bg_transparent" /><label for="<?php echo esc_attr($id_background_transparent . "_1"); ?>"><?php esc_html_e('Yes', 'photo-gallery'); ?></label><br>
		  <input type="radio" name="<?php echo esc_attr($name_background_transparent); ?>" id="<?php echo esc_attr($id_background_transparent . "_0"); ?>" value="0" <?php if (!$instance['background_transparent']) echo 'checked="checked"'; ?> onclick="jQuery(this).nextAll('.bwg_hidden').first().attr('value', '0'); jQuery(this).closest('div').find('#p_bg_color').show();" /><label for="<?php echo esc_attr($id_background_transparent . "_0"); ?>"><?php esc_html_e('No', 'photo-gallery'); ?></label>
		  <input type="hidden" name="<?php echo esc_attr($name_background_transparent); ?>" id="<?php echo esc_attr($id_background_transparent); ?>" value="<?php echo esc_html($instance['background_transparent']); ?>" class="bwg_hidden" />
		</p>
		<p id="p_bg_color" style="display:<?php echo (!$instance['background_transparent']) ? "" : "none" ?>;">
		  <label for="<?php echo esc_attr($id_background_color); ?>"><?php esc_html_e('Background Color:', 'photo-gallery'); ?></label><br>
		  <input class="jscolor" style="width:25%;" id="<?php echo esc_attr($id_background_color); ?>" name="<?php echo esc_attr($name_background_color); ?>'" type="text" value="<?php echo esc_html($instance['background_color']); ?>"/>
		</p> 
		<p>
		  <label for="<?php echo esc_attr($id_text_color); ?>"><?php esc_html_e('Text Color:', 'photo-gallery'); ?></label><br>
		  <input class="jscolor" style="width:25%;" id="<?php echo esc_attr($id_text_color); ?>" name="<?php echo esc_attr($name_text_color); ?>'" type="text" value="<?php echo esc_html($instance['text_color']); ?>"/>
		</p> 
		<p>
		  <label for="<?php echo $id_theme_id; ?>"><?php esc_html_e('Themes:', 'photo-gallery'); ?></label><br>
		  <select name="<?php echo esc_attr($name_theme_id); ?>" id="<?php echo esc_attr($id_theme_id); ?>" class="widefat">
			<?php
			foreach ($theme_rows as $theme_row) {
			  ?>
			  <option value="<?php echo esc_attr($theme_row->id); ?>" <?php echo (($instance['theme_id'] == $theme_row->id || $theme_row->default_theme == 1) ? 'selected="selected"' : ''); ?>><?php echo esc_html($theme_row->name); ?></option>
			  <?php
			}
			?>
		  </select>
		</p>
		<?php
	}
}
