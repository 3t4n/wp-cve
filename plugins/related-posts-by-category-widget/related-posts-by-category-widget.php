<?php
/*
Plugin Name: Related Posts by Category Widget
Plugin URI: http://nicasiodesign.com/blog/category/wordpress-plugins/
Description: Widget showing posts from the same category as the current post. Based on 'Related Links by Category' by Andrew Stephens
Author: Dan Cannon
Version: 1.0.1
Author URI: http://nicasiodesign.com
License: GPL

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

function init_nrc() {
	if ( !function_exists('register_sidebar_widget') )
		return;
	function widget_nrc($args) {
		extract($args);
		$data = get_option('nrc');

		if (is_single()) {
			echo $before_widget . $before_title . $data['title'] . $after_title;
			?>
			<ul>
				<?php
				$already = array();
				$data = get_option('nrc');
				foreach((get_the_category($post->ID)) as $category) {
					$exclude_string = '';
					if($data['multiple'] != 'yes'){
						$exclude_string = implode(',',$already);
					}
					$related = '';

					global $post; $cur_id = $post->ID;
					if($data['orderby'] == 'rand'){
						$order_str = 'orderby='.$data['orderby'];
					} elseif($data['orderby'] && $data['orderby'] != '') {
						$order_str = 'orderby='.$data['orderby'].'&order='.$data['order'];
					} else {
						$order_str = 'orderby=date&order=DESC';
					}
					$catposts = get_posts('category='.$category->cat_ID.'&exclude='.$exclude_string.'&'.$order_str);

					foreach($catposts as $p) {
						if($data['multiple'] != 'yes'){
							$already[] = $p->ID;
						}
						if ($d <= $data['disp']) {
							if ($cur_id != $p->ID) {
								$related .= '<li class="cat-item" id="related-cat-post-'.$p->ID.'"><a href="'.get_permalink($p->ID).'" title="'.$p->post_title.'">'.$p->post_title.'</a></li>';
								$d++;
							}
						}
					}

					if ($related != '') {
						if($data['showname'] = 'yes'){
							echo '<li class="serif">';
								echo '<span class="cat-name">';
								if($data['showlink'] == 'yes'){
									echo '<a href="'.get_category_link($category->cat_ID).'" title="'.$category->cat_name.'">';
								}
									echo $category->cat_name;
								if($data['showlink'] == 'yes'){
									echo '</a>';
								}
								echo '</span>';
								echo '<ul>';
						}
									echo $related;
						if($data['showname'] == 'yes'){
								echo '</ul>';
							echo '</li>';
						}
					}
				}
				?>
			</ul>
			<?php
		}
		echo $after_widget;
	}
	register_sidebar_widget(array('Related Links by Category', 'widgets'), 'widget_nrc');
	register_widget_control('Related Links by Category',  'nrc_control');
}

function nrc_control() {
	$data = get_option('nrc');?>
	<p>
		<label>Title:</label><br /> <input class="widefat" id="nrc_title" name="nrc_title" type="text" value="<?php echo $data['title']; ?>" />
	</p>
	<p>
		<label>Number of posts to show:</label> <input id="nrc_disp" name="nrc_disp" size="3" type="text" value="<?php echo $data['disp']; ?>" />
	</p>
	<p>
		<label>Order By: </label>
			<select name="nrc_order" id="nrc_order">
				<?php $nrc_order_val =  $data['orderby'].'|'.$data['order']; ?>
				<option value="date|DESC"<?php if($nrc_order_val=="date|DESC")echo ' selected'; ?>>Date DESC</option>
				<option value="date|ASC"<?php if($nrc_order_val=="date|ASC")echo ' selected'; ?>>Date ASC</option>
				<option value="title|ASC"<?php if($nrc_order_val=="title|ASC")echo ' selected'; ?>>Title A-Z</option>
				<option value="title|DESC"<?php if($nrc_order_val=="title|DESC")echo ' selected'; ?>>Title Z-A</option>
				<option value="modified|ASC"<?php if($nrc_order_val=="modified|ASC")echo ' selected'; ?>>Modified ASC</option>
				<option value="modified|DESC"<?php if($nrc_order_val=="modified|DESC")echo ' selected'; ?>>Modified DESC</option>
				<option value="ID|ASC"<?php if($nrc_order_val=="ID|ASC")echo ' selected'; ?>>ID ASC</option>
				<option value="ID|DESC"<?php if($nrc_order_val=="ID|DESC")echo ' selected'; ?>>ID DESC</option>
				<option value="rand|ASC"<?php if($nrc_order_val=="rand|ASC")echo ' selected'; ?>>Random</option>
			</select>
		
	</p>
	<p>
		<label>Show Category Names? </label>
			<select name="nrc_showname" id="nrc_showname">
				<option value="yes"<?php if($data['showname']=="yes")echo ' selected'; ?>>Yes</option>
				<option value="no"<?php if($data['showname']=="no")echo ' selected'; ?>>No</option>
			</select>
	</p>
	<p>
		<label>Link Cat Name to Archive? </label>
			<select name="nrc_showlink" id="nrc_showlink">
				<option value="yes"<?php if($data['showlink']=="yes")echo ' selected'; ?>>Yes</option>
				<option value="no"<?php if($data['showlink']=="no")echo ' selected'; ?>>No</option>
			</select>
	</p>
	<p>
		<label>List Posts Multiple Times? </label>
			<select name="nrc_multiple" id="nrc_multiple">
				<option value="yes"<?php if($data['multiple']=="yes")echo ' selected'; ?>>Yes</option>
				<option value="no"<?php if($data['multiple']=="no")echo ' selected'; ?>>No</option>
			</select><br />
			<span>If a post is listed in multiple categories, do you want it to appear under each category, or only under the first?</span>
	</p>
	<p><a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=6LJ9BJN6EUFEY" target="_blank">Make a Small Donation</a></p>
	<?php
	if (isset($_POST['nrc_title'])) {
		$order = explode('|',$_POST['nrc_order']);
		$data['orderby'] = $order[0];
		$data['order'] = $order[1];
		$data['title'] = attribute_escape($_POST['nrc_title']);
		$data['showname'] = $_POST['nrc_showname'];
		$data['showlink'] = $_POST['nrc_showlink'];
		$data['multiple'] = $_POST['nrc_multiple'];
		$data['disp'] = attribute_escape($_POST['nrc_disp']);
		update_option('nrc', $data);
	}
}

function nrc_activate() {
	$data = array( 'title' => 'Related Links' , 'disp' => 5, 'showname' => 'yes', 'showlink' => 'no', 'multiple' => 'yes' );
	if (!get_option('nrc')){
	   add_option('nrc' , $data);
	} else {
	   update_option('nrc' , $data);
	}
}

function nrc_deactivate(){
	delete_option('nrc'); 
}

register_deactivation_hook( __FILE__, 'nrc_deactivate');
register_activation_hook( __FILE__, 'nrc_activate' );
add_action('widgets_init', 'init_nrc');
?>