<?php
/*
This file is part of Company Directory.

Company Directory is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

Company Directory is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with Company Directory.  If not, see <http://www.gnu.org/licenses/>.

*/

class GP_Single_Staff_Widget extends WP_Widget
{
	
	function __construct()
	{
		$widget_ops = array(
			'classname' => 'GP_Single_Staff_Widget GP_Single_Staff_Widget_Compact',
			'description' => 'Displays a specified staff member.'
		);
		parent::__construct('GP_Single_Staff_Widget', 'Company Directory - Single Staff', $widget_ops);
	}
	
	// PHP4 style constructor for backwards compatibility
	function GP_Single_Staff_Widget()
	{
		$this->__construct();
	}

	function form($instance){
		$instance = wp_parse_args( 
			(array) $instance, 
			array( 	'title' => '',
					'id' => '',
					'show_name' => true,
					'show_title' => true,
					'show_bio' => true,
					'show_photo' => true,
					'show_email' => true,
					'show_address' => true,
					'show_website' => true,
					'show_department' => false,
					) 
		);
		
		$title = !empty($instance['title']) ? $instance['title'] : '';
		$id = !empty($instance['id']) ? $instance['id'] : '';
		$show_name = isset($instance['show_name']) ? $instance['show_name'] : true;
		$show_title = isset($instance['show_title']) ? $instance['show_title'] : true;
		$show_bio = isset($instance['show_bio']) ? $instance['show_bio'] : true;
		$show_photo = isset($instance['show_photo']) ? $instance['show_photo'] : true;
		$show_email = isset($instance['show_email']) ? $instance['show_email'] : true;
		$show_phone = isset($instance['show_phone']) ? $instance['show_phone'] : true;
		$show_address = isset($instance['show_address']) ? $instance['show_address'] : true;
		$show_website = isset($instance['show_website']) ? $instance['show_website'] : true;
		$show_department = isset($instance['show_department']) ? $instance['show_department'] : false;
		
		$staff_members = get_posts('post_type=staff-member&posts_per_page=-1&nopaging=true&meta_key=_ikcf_last_name&orderby=meta_value&order=asc');
		
		?>
		<div class="gp_widget_form_wrapper">
			<p class="hide_in_popup">
				<label for="<?php echo esc_attr( $this->get_field_id('title') ); ?>">Title:</label><br />
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id('title') ); ?>" name="<?php echo esc_attr( $this->get_field_name('title') ); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id('id') ); ?>">Staff Member to Display</label>
				<select id="<?php echo esc_attr( $this->get_field_id('id') ); ?>" name="<?php echo esc_attr( $this->get_field_name('id') ); ?>" data-shortcode-key="id">
				<?php if($staff_members): ?>
					<?php foreach ( $staff_members as $staff_member  ) : ?>
					<option value="<?php echo esc_attr( $staff_member->ID ); ?>"  <?php if($id == $staff_member->ID): ?> selected="SELECTED" <?php endif; ?>><?php echo wp_kses( $staff_member->post_title, 'post' ); ?></option>
					<?php endforeach; ?>
				<?php endif;?>
				</select>
			</p><fieldset class="radio_text_input">
				<legend>Fields To Display</legend>
				<p>					
					<label for="<?php echo esc_attr( $this->get_field_id('show_name') ); ?>">
						<input name="<?php echo esc_attr( $this->get_field_name('show_name') ); ?>" type="hidden" value="0" />
						<input class="widefat" id="<?php echo esc_attr( $this->get_field_id('show_name') ); ?>" name="<?php echo esc_attr( $this->get_field_name('show_name') ); ?>" type="checkbox" value="1" <?php if($show_name){ ?>checked="CHECKED"<?php } ?> data-shortcode-value-if-unchecked="0"/>
						Name
					</label>
				</p>
				<p>
					<label for="<?php echo esc_attr( $this->get_field_id('show_title') ); ?>">
						<input name="<?php echo esc_attr( $this->get_field_name('show_title') ); ?>" type="hidden" value="0" />
						<input class="widefat" id="<?php echo esc_attr( $this->get_field_id('show_title') ); ?>" name="<?php echo esc_attr( $this->get_field_name('show_title') ); ?>" type="checkbox" value="1" <?php if($show_title){ ?>checked="CHECKED"<?php } ?> data-shortcode-value-if-unchecked="0"/>
						Title
					</label>
				</p>
				<p>
					<label for="<?php echo esc_attr( $this->get_field_id('show_bio') ); ?>">
						<input name="<?php echo esc_attr( $this->get_field_name('show_bio') ); ?>" type="hidden" value="0" />
						<input class="widefat" id="<?php echo esc_attr( $this->get_field_id('show_bio') ); ?>" name="<?php echo esc_attr( $this->get_field_name('show_bio') ); ?>" type="checkbox" value="1" <?php if($show_bio){ ?>checked="CHECKED"<?php } ?> data-shortcode-value-if-unchecked="0"/>
						Bio
					</label>
				</p>
				<p>
					<label for="<?php echo esc_attr( $this->get_field_id('show_photo') ); ?>">
						<input name="<?php echo esc_attr( $this->get_field_name('show_photo') ); ?>" type="hidden" value="0" />
						<input class="widefat" id="<?php echo esc_attr( $this->get_field_id('show_photo') ); ?>" name="<?php echo esc_attr( $this->get_field_name('show_photo') ); ?>" type="checkbox" value="1" <?php if($show_photo){ ?>checked="CHECKED"<?php } ?> data-shortcode-value-if-unchecked="0"/>
						Photo
					</label>
				</p>
				<p>
					<label for="<?php echo esc_attr( $this->get_field_id('show_email') ); ?>">
						<input name="<?php echo esc_attr( $this->get_field_name('show_email') ); ?>" type="hidden" value="0" />
						<input class="widefat" id="<?php echo esc_attr( $this->get_field_id('show_email') ); ?>" name="<?php echo esc_attr( $this->get_field_name('show_email') ); ?>" type="checkbox" value="1" <?php if($show_email){ ?>checked="CHECKED"<?php } ?> data-shortcode-value-if-unchecked="0"/>
						Email
					</label>
				</p>
				<p>
					<label for="<?php echo esc_attr( $this->get_field_id('show_phone') ); ?>">
						<input name="<?php echo esc_attr( $this->get_field_name('show_phone') ); ?>" type="hidden" value="0" />
						<input class="widefat" id="<?php echo esc_attr( $this->get_field_id('show_phone') ); ?>" name="<?php echo esc_attr( $this->get_field_name('show_phone') ); ?>" type="checkbox" value="1" <?php if($show_phone){ ?>checked="CHECKED"<?php } ?> data-shortcode-value-if-unchecked="0"/>
						Phone
					</label>
				</p>
				<p>
					<label for="<?php echo esc_attr( $this->get_field_id('show_address') ); ?>">
						<input name="<?php echo esc_attr( $this->get_field_name('show_address') ); ?>" type="hidden" value="0" />
						<input class="widefat" id="<?php echo esc_attr( $this->get_field_id('show_address') ); ?>" name="<?php echo esc_attr( $this->get_field_name('show_address') ); ?>" type="checkbox" value="1" <?php if($show_address){ ?>checked="CHECKED"<?php } ?> data-shortcode-value-if-unchecked="0"/>
						Address
					</label>
				</p>
				<p>
					<label for="<?php echo esc_attr( $this->get_field_id('show_website') ); ?>">
						<input name="<?php echo esc_attr( $this->get_field_name('show_website') ); ?>" type="hidden" value="0" />
						<input class="widefat" id="<?php echo esc_attr( $this->get_field_id('show_website') ); ?>" name="<?php echo esc_attr( $this->get_field_name('show_website') ); ?>" type="checkbox" value="1" <?php if($show_website){ ?>checked="CHECKED"<?php } ?> data-shortcode-value-if-unchecked="0"/>
						Website
					</label>
				</p>			
				<p>
					<label for="<?php echo esc_attr( $this->get_field_id('show_department') ); ?>">
						<input name="<?php echo esc_attr( $this->get_field_name('show_department') ); ?>" type="hidden" value="0" />
						<input class="widefat" id="<?php echo esc_attr( $this->get_field_id('show_department') ); ?>" name="<?php echo esc_attr( $this->get_field_name('show_department') ); ?>" type="checkbox" value="1" <?php if($show_department){ ?>checked="CHECKED"<?php } ?> data-shortcode-value-if-unchecked="0"/>
						Department
					</label>
				</p>			
			</fieldset>
		</div>
		<?php
	}

	function update($new_instance, $old_instance)
	{
		$instance = $old_instance;
		$instance['title'] = $new_instance['title'];
		$instance['id'] = $new_instance['id'];
		$instance['show_name'] = $new_instance['show_name'];
		$instance['show_title'] = $new_instance['show_title'];
		$instance['show_bio'] = $new_instance['show_bio'];
		$instance['show_photo'] = $new_instance['show_photo'];
		$instance['show_email'] = $new_instance['show_email'];
		$instance['show_address'] = $new_instance['show_address'];
		$instance['show_website'] = $new_instance['show_website'];
		$instance['show_phone'] = $new_instance['show_phone'];
		$instance['show_department'] = $new_instance['show_department'];
		return $instance;
	}

	function widget($args, $instance)
	{
		extract($args, EXTR_SKIP);

		
		$title = !empty($instance['title']) ? $instance['title'] : '';
		$title = apply_filters('widget_title', $title);
		
		// start the widget
		echo wp_kses($before_widget, 'post');

		if (!empty($title)){
			echo wp_kses($before_title, 'post');
			echo wp_kses($title, 'strip');
			echo wp_kses($after_title, 'post');
		}
		
		// build the shortcode's attributes
		$sc_atts = $this->build_shortcode_atts($instance);				
		$sc = '[single_staff in_widget="1" ' . $sc_atts . ']';
		$output = do_shortcode($sc);
		
		// give the user a chance to modify the output before echo'ing it
		echo wp_kses( apply_filters('single_staff_widget_html', $output), 'post' );
		
		// finish the widget
		echo wp_kses($after_widget, 'post');
	}
	
	function build_shortcode_atts($instance)
	{
		$atts = '';
		
		$opts['id'] 			= isset($instance['id']) ? $instance['id'] : '';
		$opts['show_name'] 		= isset($instance['show_name']) ? $instance['show_name'] : true;
		$opts['show_title'] 	= isset($instance['show_title']) ? $instance['show_title'] : true;
		$opts['show_bio'] 		= isset($instance['show_bio']) ? $instance['show_bio'] : true;
		$opts['show_photo'] 	= isset($instance['show_photo']) ? $instance['show_photo'] : true;
		$opts['show_email'] 	= isset($instance['show_email']) ? $instance['show_email'] : true;
		$opts['show_phone'] 	= isset($instance['show_phone']) ? $instance['show_phone'] : true;
		$opts['show_address'] 	= isset($instance['show_address']) ? $instance['show_address'] : true;
		$opts['show_website'] 	= isset($instance['show_website']) ? $instance['show_website'] : true;		
		$opts['show_department'] 	= isset($instance['show_department']) ? $instance['show_department'] : false;
		
		// Add each attribute + value to the string we're building
		foreach( $opts as $key => $val ) {
			if ( $val || !empty($val) || strlen($val) > 0 ) {
				$atts .= sprintf('%s="%s" ', $key, $val);				
			}
		}
		
		// allow the user to filter the attribute string before returning it
		$atts = trim($atts);
		return apply_filters('single_staff_widget_atts', $atts);
	}
}