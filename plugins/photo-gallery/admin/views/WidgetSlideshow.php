<?php

/**
 * Class WidgetSlideshowView_bwg
 */
class WidgetSlideshowView_bwg {
  /**
   * @param $args
   * @param $instance
   */
	function widget($args, $instance) {
		extract($args);

		$title = (isset($instance['title']) ? sanitize_text_field($instance['title']) : "");
		$gallery_id = (isset($instance['gallery_id']) ? intval($instance['gallery_id']) : 0);
		$theme_id = (isset($instance['theme_id']) ? intval($instance['theme_id']) : 0);
		$width = (!empty($instance['width']) ? intval($instance['width']) : intval(BWG()->options->slideshow_width));
		$height = (!empty($instance['height']) ? intval($instance['height']) : intval(BWG()->options->slideshow_height));
		$filmstrip_height = (!empty($instance['filmstrip_height']) ? intval($instance['filmstrip_height']) : intval(BWG()->options->slideshow_filmstrip_height));
		$slideshow_effect = (!empty($instance['effect']) ? sanitize_text_field($instance['effect']) : "fade");
		$slideshow_interval = (!empty($instance['interval']) ? intval($instance['interval']) : intval(BWG()->options->slideshow_interval));
		$enable_slideshow_shuffle = (isset($instance['shuffle']) ? intval($instance['shuffle']) : 0);
		$enable_slideshow_autoplay = (isset($instance['enable_autoplay']) ? intval($instance['enable_autoplay']) : 0);
		$enable_slideshow_ctrl = (isset($instance['enable_ctrl_btn']) ? intval($instance['enable_ctrl_btn']) : 0);

		// Before widget.
		echo $before_widget;
		// Title of widget.
		if ($title) {
		  echo $before_title . $title . $after_title;
		}
		// Widget output.
		require_once(BWG()->plugin_dir . '/frontend/controllers/controller.php');
		$controller_class = 'BWGControllerSite';
		$view = 'Slideshow';
		$controller = new $controller_class($view);
    $bwg = WDWLibrary::unique_number();
    $params = array (
		  'from' => 'widget',
		  'gallery_type' => 'slideshow',
		  'gallery_id' => $gallery_id,
		  'theme_id' => $theme_id,
		  'slideshow_width' => $width,
		  'slideshow_height' => $height,
		  'slideshow_filmstrip_height' => $filmstrip_height,
		  'slideshow_effect' => $slideshow_effect,
		  'slideshow_interval' => $slideshow_interval,
		  'enable_slideshow_shuffle' => $enable_slideshow_shuffle,
		  'enable_slideshow_autoplay' => $enable_slideshow_autoplay,
		  'enable_slideshow_ctrl' => $enable_slideshow_ctrl,
		);
		$pairs = WDWLibrary::get_shortcode_option_params( $params );
		$controller->execute($pairs, 1, $bwg);
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
		extract($params);
		$defaults = array(
		  'title' => __('Photo Gallery Slideshow', 'photo-gallery'),
		  'gallery_id' => 0,
		  'width' => 200,
		  'height' => 200,
		  'filmstrip_height' => 40,
		  'effect' => 'fade',
		  'interval' => 5,
		  'shuffle' => 0,
		  'theme_id' => 0,
		  'enable_ctrl_btn' => 0,
		  'enable_autoplay' => 0,
		);		
		$instance = wp_parse_args((array) $instance, $defaults);
		?>
		<p>
		  <label for="<?php echo esc_attr($id_title); ?>"><?php esc_html_e('Title:', 'photo-gallery'); ?></label>
		  <input class="widefat" id="<?php echo esc_attr($id_title); ?>" name="<?php echo esc_attr($name_title); ?>" type="text" value="<?php echo htmlspecialchars( $instance['title'] ); ?>"/>
		</p>    
		<p>
			<label for="<?php echo $id_gallery_id; ?>"><?php esc_html_e('Galleries:', 'photo-gallery'); ?></label><br>
		  <select name="<?php echo esc_attr($name_gallery_id); ?>" id="<?php echo esc_attr($id_gallery_id); ?>" class="widefat">
			<option value="0"><?php esc_html_e('Select', 'photo-gallery'); ?></option>
			<?php
			foreach ($gallery_rows as $gallery_row) {
			  ?>
			  <option value="<?php echo intval($gallery_row->id); ?>" <?php echo (($instance['gallery_id'] == $gallery_row->id) ? 'selected="selected"' : ''); ?>><?php echo $gallery_row->name; ?></option>
			  <?php
			}
			?>
		  </select>
		</p>
		<p>
		  <label for="<?php echo esc_attr($id_effect); ?>"><?php esc_html_e('Slideshow effect:', 'photo-gallery'); ?></label><br>
		  <select name="<?php echo esc_attr($name_effect); ?>" id="<?php echo esc_attr($id_effect); ?>" class="widefat">
			<?php
			foreach ($slideshow_effects as $key => $slideshow_effect) {
			  ?>
			  <option value="<?php echo esc_html($key); ?>"
                <?php if ($instance['effect'] == $key) echo 'selected="selected"'; ?>><?php echo esc_html($slideshow_effect); ?></option>
			  <?php
			}
			?>
		  </select>
		</p>		
		<p>
		  <label><?php esc_html_e('Enable shuffle:', 'photo-gallery'); ?></label><br>
		  <input type="radio" name="<?php echo esc_attr($name_shuffle); ?>" id="<?php echo esc_attr($id_shuffle . "_1"); ?>" value="1" <?php if ($instance['shuffle']) echo 'checked="checked"'; ?> onclick='jQuery(this).nextAll(".bwg_hidden").first().attr("value", "1");' /><label for="<?php echo esc_attr($id_shuffle . "_1"); ?>"><?php esc_html_e('Yes', 'photo-gallery'); ?></label><br>
		  <input type="radio" name="<?php echo esc_attr($name_shuffle); ?>" id="<?php echo esc_attr($id_shuffle . "_0"); ?>" value="0" <?php if (!$instance['shuffle']) echo 'checked="checked"'; ?> onclick='jQuery(this).nextAll(".bwg_hidden").first().attr("value", "0");' /><label for="<?php echo esc_attr($id_shuffle . "_0"); ?>"><?php esc_html_e('No', 'photo-gallery'); ?></label>
		  <input type="hidden" name="<?php echo esc_attr($name_shuffle); ?>" id="<?php echo esc_attr($id_shuffle); ?>" value="<?php echo esc_html($instance['shuffle']); ?>" class="bwg_hidden" />
		</p>
		<p>
		  <label><?php esc_html_e('Enable autoplay:', 'photo-gallery'); ?></label><br>
		  <input type="radio" name="<?php echo esc_attr($name_enable_autoplay); ?>" id="<?php echo esc_attr($id_enable_autoplay . "_1"); ?>" value="1" <?php if ($instance['enable_autoplay']) echo 'checked="checked"'; ?> onclick='jQuery(this).nextAll(".bwg_hidden").first().attr("value", "1");' /><label for="<?php echo esc_attr($id_enable_autoplay . "_1"); ?>"><?php esc_html_e('Yes', 'photo-gallery'); ?></label><br>
		  <input type="radio" name="<?php echo esc_attr($name_enable_autoplay); ?>" id="<?php echo esc_attr($id_enable_autoplay . "_0"); ?>" value="0" <?php if (!$instance['enable_autoplay']) echo 'checked="checked"'; ?> onclick='jQuery(this).nextAll(".bwg_hidden").first().attr("value", "0");' /><label for="<?php echo esc_attr($id_enable_autoplay . "_0"); ?>"><?php esc_html_e('No', 'photo-gallery'); ?></label>
		  <input type="hidden" name="<?php echo esc_attr($name_enable_autoplay); ?>" id="<?php echo esc_attr($id_enable_autoplay); ?>" value="<?php echo esc_html($instance['enable_autoplay']); ?>" class="bwg_hidden" />
		</p>
		 <p>
		  <label><?php esc_html_e('Enable control buttons:', 'photo-gallery'); ?></label><br>
		  <input type="radio" name="<?php echo esc_attr($name_enable_ctrl_btn); ?>" id="<?php echo esc_attr($id_enable_ctrl_btn . "_1"); ?>" value="1" <?php if ($instance['enable_ctrl_btn']) echo 'checked="checked"'; ?> onclick='jQuery(this).nextAll(".bwg_hidden").first().attr("value", "1");' /><label for="<?php echo esc_attr($id_enable_ctrl_btn . "_1"); ?>"><?php esc_html_e('Yes', 'photo-gallery'); ?></label><br>
		  <input type="radio" name="<?php echo esc_attr($name_enable_ctrl_btn); ?>" id="<?php echo esc_attr($id_enable_ctrl_btn . "_0"); ?>" value="0" <?php if (!$instance['enable_ctrl_btn']) echo 'checked="checked"'; ?> onclick='jQuery(this).nextAll(".bwg_hidden").first().attr("value", "0");' /><label for="<?php echo esc_attr($id_enable_ctrl_btn . "_0"); ?>"><?php esc_html_e('No', 'photo-gallery'); ?></label>
		  <input type="hidden" name="<?php echo esc_attr($name_enable_ctrl_btn); ?>" id="<?php echo esc_attr($id_enable_ctrl_btn); ?>" value="<?php echo esc_html($instance['enable_ctrl_btn']); ?>" class="bwg_hidden" />
		</p>
		<p>
		  <label for="<?php echo esc_attr($id_width); ?>"><?php esc_html_e('Dimensions:', 'photo-gallery'); ?></label><br>
		  <input class="widefat" style="width:25%;" id="<?php echo esc_attr($id_width); ?>" name="<?php echo esc_attr($name_width); ?>" type="text" value="<?php echo intval($instance['width']); ?>"/> x
		  <input class="widefat" style="width:25%;" id="<?php echo esc_attr($id_height); ?>" name="<?php echo esc_attr($name_height); ?>" type="text" value="<?php echo intval($instance['height']); ?>"/> px
		</p>
		<p>
		  <label for="<?php echo $id_filmstrip_height; ?>"><?php esc_html_e('Filmstrip height:', 'photo-gallery'); ?></label><br>
		  <input class="widefat" style="width: 25%;" id="<?php echo esc_attr($id_filmstrip_height); ?>" name="<?php echo esc_attr($name_filmstrip_height); ?>" type="text" value="<?php echo intval($instance['filmstrip_height']); ?>"/> px
		</p>
		<p>
		  <label for="<?php echo $id_interval; ?>"><?php esc_html_e('Time interval:', 'photo-gallery'); ?></label><br>
		  <input class="widefat" style="width:25%;" id="<?php echo esc_attr($id_interval); ?>" name="<?php echo esc_attr($name_interval); ?>" type="text" value="<?php echo intval($instance['interval']); ?>" /> sec.
		</p>
		<p>
		  <label for="<?php echo esc_attr($id_theme_id); ?>"><?php esc_html_e('Themes:', 'photo-gallery'); ?></label><br>
		  <select name="<?php echo esc_attr($name_theme_id); ?>" id="<?php echo esc_attr($id_theme_id); ?>" class="widefat">
			<?php
			foreach ($theme_rows as $theme_row) {
			  ?>
			  <option value="<?php echo intval($theme_row->id); ?>" <?php echo (($instance['theme_id'] == $theme_row->id || $theme_row->default_theme == 1) ? 'selected="selected"' : ''); ?>><?php echo esc_html($theme_row->name); ?></option>
			  <?php
			}
			?>
		  </select>
		</p>
		<?php
	}
}
