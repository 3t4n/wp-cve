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

class GP_Staff_List_Widget extends WP_Widget
{
	var $allowed_order_by_keys = array(
		'first_name' => 'First Name',
		'last_name' => 'Last Name',
		'title' => 'Title',
		'phone' => 'Phone Number',
		'email' => 'Email Address',
		'address' => 'Mailing Address',
		'website' => 'Website URL',
		'department' => 'Department',
		'rand' => 'Random Order'
	);
	var $default_order_by_key = 'last_name';
	
	function __construct()
	{
		$widget_ops = array(
			'classname' => 'GP_Staff_List_Widget GP_Staff_List_Widget_Compact',
			'description' => 'Displays a list of your staff.'
		);
		
		$options = get_option( 'sd_options' );		
		$menu_order_enabled = ( !isset($options['enable_manual_staff_order']) || !empty($options['enable_manual_staff_order']) );
		if ( $menu_order_enabled ) {
			$this->allowed_order_by_keys['menu_order'] = 'Manual Order';
			$this->default_order_by_key = 'menu_order';
		}
		
		parent::__construct('GP_Staff_List_Widget', 'Company Directory - Staff List', $widget_ops);
	}
	
	// PHP4 style constructor for backwards compatibility
	function GP_Staff_List_Widget()
	{
		$this->__construct();
	}

	function form($instance){
		$instance = wp_parse_args( 
			(array) $instance, 
			array( 	'title' => '',
					'use_excerpt' => 0,
					'count' => 1,
					'category' => '',
					'style' => 'list',
					'show_name' => true,
					'show_title' => true,
					'show_bio' => true,
					'show_photo' => true,
					'show_email' => true,
					'show_address' => true,
					'show_website' => true,
					'show_department' => false,
					'order_by' => $this->default_order_by_key,
					'order' => 'ASC',
					'staff_per_page' => 'all',
					'per_page' => '10',
					) 
		);
		
		$title = !empty($instance['title']) ? $instance['title'] : 'Our Staff';
		$category = !empty($instance['category']) ? $instance['category'] : '';
		$style = !empty($instance['style']) ? $instance['style'] : 'list';
		$show_name = isset($instance['show_name']) ? $instance['show_name'] : true;
		$show_title = isset($instance['show_title']) ? $instance['show_title'] : true;
		$show_bio = isset($instance['show_bio']) ? $instance['show_bio'] : true;
		$show_photo = isset($instance['show_photo']) ? $instance['show_photo'] : true;
		$show_email = isset($instance['show_email']) ? $instance['show_email'] : true;
		$show_phone = isset($instance['show_phone']) ? $instance['show_phone'] : true;
		$show_address = isset($instance['show_address']) ? $instance['show_address'] : true;
		$show_website = isset($instance['show_website']) ? $instance['show_website'] : true;
		$show_department = isset($instance['show_department']) ? $instance['show_department'] : false;
		$order_by = !empty($instance['order_by']) ? $instance['order_by'] : $this->default_order_by_key;
		$order = !empty($instance['order']) ? $instance['order'] : 'ASC';
		$staff_per_page = !empty($instance['staff_per_page']) ? $instance['staff_per_page'] : 'all';
		$per_page = !empty($instance['per_page']) ? $instance['per_page'] : '10';
				
		$staff_categories = get_terms( 'staff-member-category', 'orderby=title&hide_empty=0' );
		?>
		<div class="gp_widget_form_wrapper">
			<p class="hide_in_popup">
				<label for="<?php echo esc_attr( $this->get_field_id('title') ); ?>">Title:</label><br />
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id('title') ); ?>" name="<?php echo esc_attr( $this->get_field_name('title') ); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id('category') ); ?>">Category:</label><br />
				<select name="<?php echo esc_attr( $this->get_field_name('category') ); ?>" id="<?php echo esc_attr( $this->get_field_id('category') ); ?>">
					<option value="" <?php if(esc_attr($category) == ""): echo 'selected="SELECTED"'; endif; ?>>All Categories</option>
					<?php foreach($staff_categories as $cat):?>
						<option value="<?php echo esc_attr( $cat->slug ); ?>" <?php if(esc_attr($category) == $cat->slug): echo 'selected="SELECTED"'; endif; ?>><?php echo wp_kses( $cat->name, 'post' ); ?></option>
					<?php endforeach; ?>
				</select>
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id('order_by') ); ?>">Order Staff Members By:</label><br />
				<select name="<?php echo esc_attr( $this->get_field_name('order_by') ); ?>" id="<?php echo esc_attr( $this->get_field_id('order_by') ); ?>">
					<?php foreach ($this->allowed_order_by_keys as $key => $label): ?>
					<option value="<?php echo esc_attr( $key ); ?>" <?php if($order_by == $key): echo 'selected="SELECTED"'; endif; ?>><?php echo wp_kses( $label, 'post' ); ?></option>
					<?php endforeach; ?>
				</select>
				<select name="<?php echo esc_attr( $this->get_field_name('order') ); ?>" id="<?php echo esc_attr( $this->get_field_id('order') ); ?>">
					<option value="ASC" <?php if($order == "ASC"): echo 'selected="SELECTED"'; endif; ?>>A-Z</option>
					<option value="DESC" <?php if(empty($order) || $order == "DESC"): echo 'selected="SELECTED"'; endif; ?>>Z-A</option>
				</select>
			</p>
			
			<?php if ( in_array( esc_attr($style),  array("grid", "table") ) ): ?>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id('style') ); ?>">Style:</label><br />
				<select name="<?php echo esc_attr( $this->get_field_name('style') ); ?>" id="<?php echo esc_attr( $this->get_field_id('style') ); ?>">
					<option value="list" <?php if(esc_attr($style) == "list"): echo 'selected="SELECTED"'; endif; ?>>List View</option>
					<?php if ($this->is_pro()): ?>
					<option value="grid" <?php if(esc_attr($style) == "grid"): echo 'selected="SELECTED"'; endif; ?>>Grid View</option>
					<option value="table" <?php if(esc_attr($style) == "table"): echo 'selected="SELECTED"'; endif; ?>>Table View</option>
					<?php else: ?>
					<option disabled="true" value="grid" <?php if(esc_attr($style) == "grid"): echo 'selected="SELECTED"'; endif; ?>>Grid View (Requires PRO)</option>
					<option disabled="true" value="table" <?php if(esc_attr($style) == "table"): echo 'selected="SELECTED"'; endif; ?>>Table View (Requires PRO)</option>
					<?php endif; ?>
				</select>
			</p>
			<?php endif; ?>
			
			<fieldset class="radio_text_input">
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
			
			<fieldset class="radio_text_input">
				<legend>Staff Members Per Page</legend>
				<div class="radio_wrapper">
					<p class="radio_option">
						<label>
							<input type="radio" name="<?php echo esc_attr( $this->get_field_name('staff_per_page') ); ?>" value="all" class="tog" <?php echo ($staff_per_page == 'all' ? 'checked="checked"' : '');?>>All On One Page
						</label>
					</p>
					<p class="radio_option">
						<label>
							<input type="radio" name="<?php echo esc_attr( $this->get_field_name('staff_per_page') ); ?>" value="max" class="tog" <?php echo ($staff_per_page == 'max' ? 'checked="checked"' : '');?>>Max Per Page: 
						</label>
						<input type="text" name="<?php echo esc_attr( $this->get_field_name('per_page') ); ?>" id="<?php echo esc_attr( $this->get_field_id('per_page') ); ?>" class="small-text" value="<?php echo esc_attr($per_page); ?>">
					</p>
				</div>
			</fieldset>
			
			<input type="hidden" value="" name="<?php echo esc_attr( $this->get_field_name('columns') ); ?>" data-always-include="1" />
		</div>
		<?php
	}

	function update($new_instance, $old_instance)
	{
		$instance = $old_instance;
		$instance['title'] = $new_instance['title'];
		$instance['count'] = $new_instance['count'];
		$instance['category'] = $new_instance['category'];
		$instance['style'] = $new_instance['style'];
		$instance['show_name'] = $new_instance['show_name'];
		$instance['show_title'] = $new_instance['show_title'];
		$instance['show_bio'] = $new_instance['show_bio'];
		$instance['show_photo'] = $new_instance['show_photo'];
		$instance['show_email'] = $new_instance['show_email'];
		$instance['show_address'] = $new_instance['show_address'];
		$instance['show_website'] = $new_instance['show_website'];
		$instance['show_department'] = $new_instance['show_department'];
		$instance['show_phone'] = $new_instance['show_phone'];
		$instance['order_by'] = $new_instance['order_by'];
		$instance['order'] = $new_instance['order'];
		$instance['per_page'] = $new_instance['per_page'];
		$instance['staff_per_page'] = $new_instance['staff_per_page'];
		return $instance;
	}

	function widget($args, $instance)
	{
		extract($args, EXTR_SKIP);

		
		$title = !empty($instance['title']) ? $instance['title'] : '';
		$title = apply_filters('widget_title', $title);
		
		// start the widget
		echo wp_kses( $before_widget, 'post' );

		if (!empty($title)){
			echo wp_kses($before_title, 'post');
			echo wp_kses($title, 'strip');
			echo wp_kses($after_title, 'post');
		}
		
		// build the shortcode's attributes
		$sc_atts = $this->build_shortcode_atts($instance);				
		$sc = '[staff_list in_widget="1" ' . $sc_atts . ']';
		$output = do_shortcode($sc);
		
		// give the user a chance to modify the output before echo'ing it
		echo wp_kses( apply_filters('staff_list_widget_html', $output), 'post' );
		
		// finish the widget
		echo wp_kses($after_widget, 'post');
	}
	
	function build_shortcode_atts($instance)
	{
		$atts = '';
		
		$opts['category'] 		= !empty($instance['category']) ? $instance['category'] : '';
		$opts['style'] 			= !empty($instance['style']) ? $instance['style'] : '';
		$opts['show_name'] 		= isset($instance['show_name']) ? $instance['show_name'] : true;
		$opts['show_title'] 	= isset($instance['show_title']) ? $instance['show_title'] : true;
		$opts['show_bio'] 		= isset($instance['show_bio']) ? $instance['show_bio'] : true;
		$opts['show_photo'] 	= isset($instance['show_photo']) ? $instance['show_photo'] : true;
		$opts['show_email'] 	= isset($instance['show_email']) ? $instance['show_email'] : true;
		$opts['show_phone'] 	= isset($instance['show_phone']) ? $instance['show_phone'] : true;
		$opts['show_address'] 	= isset($instance['show_address']) ? $instance['show_address'] : true;
		$opts['show_website'] 	= isset($instance['show_website']) ? $instance['show_website'] : true;
		$opts['show_department'] 	= isset($instance['show_department']) ? $instance['show_department'] : false;
		$opts['order_by'] 		= isset($instance['order_by']) ? $instance['order_by'] : $this->default_order_by_key;
		$opts['order'] 			= isset($instance['order']) ? $instance['order'] : 'ASC';
		
		if ( !empty($instance['staff_per_page']) && $instance['staff_per_page'] == 'max' && !empty($instance['per_page']) 
			 && intval($instance['per_page']) > 0 && intval($instance['per_page']) < 1000 )
		{
			$opts['per_page'] = $instance['per_page'];
		}
		
		
		// if we're using the Table View, build the column list based on their selections
		if ($opts['style'] == 'table') {
			$opts['columns'] = $this->build_column_list($instance);
		}		
		
		// Add each attribute + value to the string we're building
		foreach( $opts as $key => $val ) {
			if ( $val || !empty($val) || strlen($val) > 0 ) {
				$atts .= sprintf('%s="%s" ', $key, $val);				
			}
		}
		
		// allow the user to filter the attribute string before returning it
		$atts = trim($atts);
		return apply_filters('staff_list_widget_atts', $atts);
	}
	
	function build_column_list($instance)
	{
		$cols = '';
		
		$opts['name'] 		= isset($instance['show_name']) ? $instance['show_name'] : true;
		$opts['title'] 		= isset($instance['show_title']) ? $instance['show_title'] : true;
		$opts['bio'] 		= isset($instance['show_bio']) ? $instance['show_bio'] : true;
		$opts['photo'] 		= isset($instance['show_photo']) ? $instance['show_photo'] : true;
		$opts['email'] 		= isset($instance['show_email']) ? $instance['show_email'] : true;
		$opts['phone'] 		= isset($instance['show_phone']) ? $instance['show_phone'] : true;
		$opts['address']	= isset($instance['show_address']) ? $instance['show_address'] : true;
		$opts['website']	= isset($instance['show_website']) ? $instance['show_website'] : true;
		$opts['department']	= isset($instance['show_department']) ? $instance['show_department'] : false;
				
		// Add each selected column the string we're building
		foreach( $opts as $key => $val ) {
			if ( $val || !empty($val) ) {
				$cols .= sprintf('%s,', $key);				
			}
		}
		
		// allow the user to filter the column list before returning it
		$cols = rtrim($cols, ',');
		return apply_filters('staff_list_columns', $cols);
	}
	
	function is_pro()
	{
		global $company_directory_config;
		return ( isset($company_directory_config['is_pro']) ? $company_directory_config['is_pro'] : false );
	}
}