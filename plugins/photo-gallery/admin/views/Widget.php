<?php

/**
 * Class WidgetView_bwg
 */
class WidgetView_bwg {
  /**
   * @param $args
   * @param $instance
   */
	function widget($args, $instance) {
		extract($args);
		$title = (!empty($instance['title']) ? sanitize_text_field($instance['title']) : "");
		$type  = (!empty($instance['type']) ? sanitize_text_field($instance['type']) : "gallery");
		$view_type = (!empty($instance['view_type']) ? sanitize_text_field($instance['view_type']) : "thumbnails");
		$gallery_id = (!empty($instance['gallery_id']) ? intval($instance['gallery_id']) : 0);
		$album_id = (!empty($instance['album_id']) ? intval($instance['album_id']) : 0);
		$theme_id = (!empty($instance['theme_id']) ? intval($instance['theme_id']) : 0);
		$show  = (!empty($instance['show']) ? sanitize_text_field($instance['show']) : "random");
		$sort_by = 'order';
		if ($show == 'random') {
			$sort_by = 'random';
		}
		$order_by = 'ASC';
		if ($show == 'last') {
			$order_by = 'DESC';
		}

		$count  = (!empty($instance['count']) ? intval($instance['count']) : intval(BWG()->options->image_column_number));
		$width  = (!empty($instance['width']) ? intval($instance['width']) : intval(BWG()->options->thumb_width));
		$height = (!empty($instance['height']) ? intval($instance['height']) : intval(BWG()->options->thumb_height));
		// Before widget.
		echo $before_widget;
		// Title of widget.
		if ($title) {
		  echo $before_title . $title . $after_title;
		}
		// Widget output.
		$params = array (
		  'from' => 'widget',
		  'theme_id' => intval($theme_id),
		  'sort_by'  => sanitize_text_field($sort_by),
		  'order_by' => sanitize_text_field($order_by),
		  'image_enable_page' => 0
		);
		require_once(BWG()->plugin_dir . '/frontend/controllers/controller.php');
		$controller_class = 'BWGControllerSite';
		if ($type == 'gallery') {
			if ($view_type == 'thumbnails') {
				$gallery_type = 'thumbnails';
				$view = 'Thumbnails';
			}
			else if ($view_type == 'masonry') {
				$gallery_type = 'thumbnails_masonry';
				$view = 'Thumbnails_masonry';
			}

			$params['gallery_type']  = sanitize_text_field($gallery_type);
			$params['gallery_id'] 	 = intval($gallery_id);
			$params['thumb_width'] 	 = intval($width);
			$params['thumb_height']  = intval($height);
			$params['image_column_number'] = intval($count);
			$params['images_per_page'] = intval($count);
		}
		else {
			$view = 'Album_compact_preview';

			$params['gallery_type']  = 'album_compact_preview';
			$params['album_id'] = $album_id;
			$params['compuct_albums_per_page'] = intval($count);
			$params['compuct_album_thumb_width'] = intval($width);
			$params['compuct_album_thumb_height'] = intval($height);
			$params['compuct_album_image_thumb_width'] = intval($width);
			$params['compuct_album_image_thumb_height'] = intval($height);
			$params['all_album_sort_by']  = sanitize_text_field($sort_by);
			$params['all_album_order_by'] = sanitize_text_field($order_by);
			$params['compuct_album_enable_page'] = 0;
		}
		$controller = new $controller_class($view);
		$bwg = WDWLibrary::unique_number();
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
			'title' =>  __('Photo Gallery', 'photo-gallery'),
			'type' => 'gallery',
			'view_type' => 'thumbnails',
			'gallery_id' => 0,
			'album_id' => 0,
			'show' => 'random',
			'count' => 4,
			'width' => 100,
			'height' => 100,
			'theme_id' => 0,
		);		
		$instance = wp_parse_args( (array) $instance, $defaults );
    if (!isset($instance['view_type'])) {
      $instance['view_type'] = "thumbnails";
    }
    ?>    
		<p>
		  <label for="<?php echo esc_attr($id_title); ?>"><?php esc_html_e('Title:', 'photo-gallery'); ?></label>
		  <input class="widefat" id="<?php echo esc_attr($id_title); ?>" name="<?php echo esc_attr($name_title); ?>'" type="text" value="<?php echo htmlspecialchars( $instance['title'] ); ?>"/>
		</p>
		<p>
		  <label for="<?php echo esc_attr($id_show); ?>"><?php esc_html_e('Type:', 'photo-gallery'); ?></label><br>
		  <input type="radio" name="<?php echo esc_attr($name_type); ?>" id="<?php echo esc_attr($id_type . "_1"); ?>" value="gallery" class="sel_gallery" onclick="bwg_change_type(event, this)" <?php if ($instance['type'] == "gallery") echo 'checked="checked"'; ?> /><label for="<?php echo esc_attr($id_type . "_1"); ?>"><?php esc_html_e('Gallery', 'photo-gallery'); ?></label><br>
		  <input type="radio" name="<?php echo esc_attr($name_type); ?>" id="<?php echo esc_attr($id_type . "_2"); ?>" value="album" class="sel_album" onclick="bwg_change_type(event, this)" <?php if ($instance['type'] == "album") echo 'checked="checked"'; ?> /><label for="<?php echo esc_attr($id_type . "_2"); ?>"><?php esc_html_e('Gallery groups', 'photo-gallery'); ?></label>
		</p>	
		<p id="p_galleries" style="display:<?php echo ($instance['type'] == "gallery") ? "" : "none" ?>;">
		  <label for="<?php echo esc_attr($id_gallery_id); ?>"><?php esc_html_e('Galleries:', 'photo-gallery'); ?></label><br>
		  <select name="<?php echo esc_attr($name_gallery_id); ?>" id="<?php echo esc_attr($id_gallery_id); ?>" class="widefat">
			<option value="0"><?php esc_html_e('All images', 'photo-gallery'); ?></option>
			<?php
			foreach ($gallery_rows as $gallery_row) {
			  ?>
			  <option value="<?php echo intval($gallery_row->id); ?>" <?php echo (($instance['gallery_id'] == $gallery_row->id) ? 'selected="selected"' : ''); ?>><?php echo esc_html($gallery_row->name); ?></option>
			  <?php
			}
			?>
		  </select>
		</p>
		<p id="view_type_container" style="display: <?php echo $instance['type'] != 'album' ? 'block' : 'none'; ?>;">
		  <label for="<?php echo esc_attr($id_view_type); ?>"><?php esc_html_e('Gallery Type:', 'photo-gallery'); ?></label><br>
		  <input type="radio" name="<?php echo esc_attr($name_view_type); ?>" id="<?php echo esc_attr($id_view_type . "_1"); ?>" value="thumbnails" class="sel_thumbnail_gallery"  <?php if (isset($instance['view_type']) && $instance['view_type'] == "thumbnails") echo 'checked="checked"';  ?> /><label for="<?php echo esc_attr($id_view_type . "_1"); ?>"><?php esc_html_e('Thumbnail', 'photo-gallery'); ?></label><br>
		  <input type="radio" name="<?php echo esc_attr($name_view_type); ?>" id="<?php echo esc_attr($id_view_type . "_2"); ?>" value="masonry" class="sel_masonry_gallery"  <?php if (isset($instance['view_type']) && $instance['view_type'] == "masonry") echo 'checked="checked"'; ?> /><label for="<?php echo esc_attr($id_view_type . "_2"); ?>"><?php esc_html_e('Masonry', 'photo-gallery'); ?></label>
		</p>
		<p id="p_albums" style="display:<?php echo ($instance['type'] == "album") ? "" : "none" ?>;">
		  <label for="<?php echo esc_attr($id_album_id); ?>"><?php esc_html_e('Gallery Groups:', 'photo-gallery'); ?></label><br>
		  <select name="<?php echo esc_attr($name_album_id); ?>" id="<?php echo esc_attr($id_album_i); ?>" class="widefat">
			<option value="0"><?php esc_html_e('All Galleries', 'photo-gallery'); ?></option>
			<?php
			foreach ($album_rows as $album_row) {
			  ?>
			  <option value="<?php echo intval($album_row->id); ?>" <?php echo (($instance['album_id'] == $album_row->id) ? 'selected="selected"' : ''); ?>><?php echo esc_html($album_row->name); ?></option>
			  <?php
			}
			?>
		  </select>
		</p>    
		<p>
		<label for="<?php echo esc_attr($id_show); ?>"><?php esc_html_e('Sort:', 'photo-gallery'); ?></label><br>
		  <input type="radio" name="<?php echo esc_attr($name_show); ?>" id="<?php echo esc_attr($id_show . "_1"); ?>" value="random" <?php if ($instance['show'] == "random") echo 'checked="checked"'; ?> /><label for="<?php echo esc_attr($id_show . "_1"); ?>"><?php esc_html_e('Random', 'photo-gallery'); ?></label><br>
		  <input type="radio" name="<?php echo esc_attr($name_show); ?>" id="<?php echo esc_attr($id_show . "_2"); ?>" value="first" <?php if ($instance['show'] == "first") echo 'checked="checked"'; ?> /><label for="<?php echo esc_attr($id_show . "_2"); ?>"><?php esc_html_e('First', 'photo-gallery'); ?></label><br>
		  <input type="radio" name="<?php echo esc_attr($name_show); ?>" id="<?php echo esc_attr($id_show . "_3"); ?>" value="last" <?php if ($instance['show'] == "last") echo 'checked="checked"'; ?> /><label for="<?php echo esc_attr($id_show . "_3"); ?>"><?php esc_html_e('Last', 'photo-gallery'); ?></label>
		</p>
		<p>
		  <label for="<?php echo esc_attr($id_count); ?>"><?php esc_html_e('Count:', 'photo-gallery'); ?></label><br>
		  <input class="widefat" style="width:25%;" id="<?php echo esc_attr($id_count); ?>" name="<?php echo esc_attr($name_count); ?>'" type="text" value="<?php echo intval($instance['count']); ?>"/>
		</p>
		<p>
		  <label for="<?php echo esc_attr($id_width); ?>"><?php esc_html_e('Dimensions:', 'photo-gallery'); ?></label><br>
		  <input class="widefat" style="width:25%;" id="<?php echo esc_attr($id_width); ?>" name="<?php echo esc_attr($name_width); ?>'" type="text" value="<?php echo intval($instance['width']); ?>"/> x
		  <input class="widefat" style="width:25%;" id="<?php echo esc_attr($id_height); ?>" name="<?php echo esc_attr($name_height); ?>'" type="text" value="<?php echo intval($instance['height']); ?>"/> px
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
		<script>
		  function bwg_change_type(event, obj) {
			var div = jQuery(obj).closest("div");
			if (jQuery(jQuery(div).find(".sel_gallery")[0]).prop("checked")) {
			  jQuery(jQuery(div).find("#p_galleries")).css("display", "");
			  jQuery(jQuery(div).find("#p_albums")).css("display", "none");
			  jQuery(jQuery(div).find("#view_type_container")).css("display", "block");
			  jQuery(jQuery(div).find("#view_type_container")).next("p.description").css("display", "block");
			}
			else {
			  jQuery(jQuery(div).find("#p_galleries")).css("display", "none");
			  jQuery(jQuery(div).find("#p_albums")).css("display", "");
			  jQuery(jQuery(div).find("#view_type_container")).css("display", "none");
			  jQuery(jQuery(div).find("#view_type_container")).next("p.description").css("display", "none");
			}
		  }
		</script>
    <?php
	}
}
