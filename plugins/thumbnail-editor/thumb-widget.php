<?php

class Thumbnail_Widget extends WP_Widget {
	
	public function __construct() {
		parent::__construct(
	 		'thumb_wid',
			'Thumbnail Widget',
			array( 'description' => __( 'This is a widget to display thumbnail images.', 'thumbnail-editor' ), )
		);
	 }

	public function widget( $args, $instance ) {
		echo $args['before_widget'];
		if ( ! empty( $instance['wid_title'] ) ) {
			echo $args['before_title'] . apply_filters( 'widget_title', $instance['wid_title'] ) . $args['after_title'];
		}
		if ( empty( $instance['wid_thumb_type'] ) ) {
			$instance[ 'wid_thumb_type' ] = 'full';
		}
		$image_attributes = wp_get_attachment_image_src( $instance['wid_thumb'], $instance[ 'wid_thumb_type' ] );
		if($image_attributes[0] || ! empty( $instance['wid_thumb_caption'] )){
			echo '<div class="thumb-widget '.$args['widget_id'].'">';
			if($image_attributes[0]){
				echo '<div class="thumb-widget-image"><img src="'.$image_attributes[0].'" alt="'.get_post_meta( $instance['wid_thumb'], '_wp_attachment_image_alt', true ).'"></div>';
			}
			if ( ! empty( $instance['wid_thumb_caption'] ) ) {
				echo '<div class="thumb-widget-image-caption">'.html_entity_decode($instance['wid_thumb_caption']).'</div>';
			}
			echo '</div>';
		}
		echo $args['after_widget'];
	}

	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['wid_title'] = esc_html( $new_instance['wid_title'] );
		$instance['wid_thumb'] = sanitize_text_field( $new_instance['wid_thumb'] );
		$instance['wid_thumb_type'] = sanitize_text_field( $new_instance['wid_thumb_type'] );
		$instance['wid_thumb_caption'] = esc_html( $new_instance['wid_thumb_caption'] );
		return $instance;
	}

	public function form( $instance ) {
		$wid_title = '';
		if(!empty($instance[ 'wid_title' ])){
			$wid_title = esc_html($instance[ 'wid_title' ]);
		}
		if(!empty($instance[ 'wid_thumb' ])){
			$wid_thumb = sanitize_text_field($instance[ 'wid_thumb' ]);
		}
		if(!empty($instance[ 'wid_thumb_type' ])){
			$wid_thumb_type = sanitize_text_field($instance[ 'wid_thumb_type' ]);
		}
		if(!empty($instance[ 'wid_thumb_caption' ])){
			$wid_thumb_caption = esc_html($instance[ 'wid_thumb_caption' ]);
		}
		$thumb_image = '';
		if($wid_thumb){
			$thumb_image .= '<img src="'.wp_get_attachment_url( $wid_thumb ).'" class="wid-thumb">';
			$thumb_image .= '<p>';
			$thumb_image .= '<a href="javascript:void(0);" onclick="ap_thumb_remove(\''.$this->get_field_id('selected_thumb').'\', \''.$this->get_field_id('wid_thumb').'\')">Remove</a>';
			$thumb_image .= ' | ';
			$thumb_image .= '<a href="options-general.php?page=thumbnail_editor_setup_data&att_id='.$wid_thumb.'" title="Crop Thumbnail">'.__('Crop Thumbnail','thumbnail-editor').'</a>';
			$thumb_image .= '</p>';
		}
		?>
		<p><label for="<?php echo $this->get_field_id('wid_title'); ?>"><?php _e('Title','thumbnail-editor'); ?> </label>
        <input type="text" name="<?php echo $this->get_field_name('wid_title');?>" id="<?php echo $this->get_field_id('wid_title');?>" value="<?php echo $wid_title;?>" class="widefat">
		</p>
        <p>
        <input type="hidden" name="<?php echo $this->get_field_name('wid_thumb');?>" id="<?php echo $this->get_field_id('wid_thumb');?>" value="<?php echo $wid_thumb;?>">
		<a href="javascript:ap_thumb_upload('<?php echo $this->get_field_id('selected_thumb');?>', '<?php echo $this->get_field_id('wid_thumb');?>')" class="button button-text-center button-ap-large widefat"><?php _e('Upload Thumbnail','thumbnail-editor'); ?></a>
        </p>
        <div id="<?php echo $this->get_field_id('selected_thumb');?>"><?php echo $thumb_image;?></div>
        <p>
        <label for="<?php echo $this->get_field_id('wid_thumb_type'); ?>"><?php _e('Thumbnail Type','thumbnail-editor'); ?> </label>
        <select name="<?php echo $this->get_field_name('wid_thumb_type');?>" id="<?php echo $this->get_field_id('wid_thumb_type');?>" class="widefat">
        	<option value="">-</option>
        	<?php
			$sizes = get_intermediate_image_sizes();
    		if(is_array($sizes)){
				foreach($sizes as $key => $value){
					if( $wid_thumb_type == $value){
						echo '<option value="'.$value.'" selected>'.$value.'</option>';	
					} else {
						echo '<option value="'.$value.'">'.$value.'</option>';	
					}
				}
			}
        	?>
        </select>
        </p>
        <p><label for="<?php echo $this->get_field_id('wid_thumb_caption'); ?>"><?php _e('Caption','thumbnail-editor'); ?> </label>
        <input type="text" name="<?php echo $this->get_field_name('wid_thumb_caption');?>" id="<?php echo $this->get_field_id('wid_thumb_caption');?>" value="<?php echo $wid_thumb_caption;?>" class="widefat">
		</p>
		<?php 
	}
} 