<?php
/*
Plugin Name: WordPress QR Code
Plugin URI: http://en.bainternet.info
Description: Easily insert QR codes in your blog, with Widget or Shortcode.
Version: 1.4
Author: Bainternet
Author URI: http://en.bainternet.info
*/
/*
		* 	Copyright (C) 2011 - 2014  Ohad Raz aKa Bainternet
		*	http://en.bainternet.info
		*	admin@bainternet.info

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

/* Disallow direct access to the plugin file */
if (basename($_SERVER['PHP_SELF']) == basename (__FILE__)) {
	die('Sorry, but you cannot access this page directly.');
}


//shortcode class
if (!class_exists('simple_qr_code')){
class simple_qr_code{
	
	/*
	 * constractor 
	 */ 
	function simple_qr_code(){
		add_shortcode('QR',array($this,'get_QR_code'));
	}
	
	
	/*
	* function to get QR code Image from google chart API
	* @Params:
	* $content - (string) the content to store inside the QR code (eg: url,address,string..)
	* $size - (string) the size of the QR code image , must be in heightxwidth format(eg: 150x150 , 320x320)
	* $ImgTag - (bool) if set to true the function will return  the full img tag of QR code image ,
	* $link - if set to true the function will return out the full img tag of QR code image with a link wrapper , 
	*           if false then the function will return the image src (default = true)
	*/

	function get_QR_code($atts,$content=null){
		extract(shortcode_atts(array(
	   	 'size' => '150x150',
	   	 'ImgTag' => 'yes',
		 'link' => 'no'
	  	), $atts));
	  	
	    if ($size == null){
	        $size = '150x150';
	    }
	    if ($content == null){
	    	$content = wp_get_shortlink();
	    }
	    $content = urlencode($content);
	    if ($ImgTag == 'yes'){
	    	if ($link == 'yes'){
	        	return '<a href="'.urldecode($content).'"><img src="//chart.apis.google.com/chart?cht=qr&chs='.$size.'&choe=UTF-8&chld=H&chl='.$content .'"></a>';
	    	}else{
	    		return '<img src="//chart.apis.google.com/chart?cht=qr&chs='.$size.'&choe=UTF-8&chld=H&chl='.$content .'">';
	    	}
	    }else{
	    	$protocol = is_ssl() ? 'https://': 'http://';
	        return $protocol.'chart.apis.google.com/chart?cht=qr&chs='.$size.'&choe=UTF-8&chld=H&chl='.$content;
	    }
	}
}//end class
}//end if

$sqrcode = new simple_qr_code();

//widget class
if (!class_exists('QR_Code_Widget')){
	/**
	 * QR_Code_Widget Class
	 */
	class QR_Code_Widget extends WP_Widget {
		/** constructor */
		function QR_Code_Widget() {
			parent::WP_Widget( 'qrcwidget', $name = 'Simple QR Code' );
		}
	
		/** @see WP_Widget::widget */
		function widget( $args, $instance ) {
			extract( $args );
			$title = apply_filters( 'widget_title', $instance['title'] );
			echo $before_widget;
			if ( $title )
				echo $before_title . $title . $after_title;
			//do widget here			
			echo $this->do_qr_code($instance['size'],$instance['link_w'],$instance['url'],$instance['center']);
			echo $after_widget;
		}
	
		/** @see WP_Widget::update */
		function update( $new_instance, $old_instance ) {
			$instance = $old_instance;
			$instance['title'] = strip_tags($new_instance['title']);
			$instance['size'] = strip_tags($new_instance['size']);
			$instance['link_w'] = $new_instance['link_w'];
			$instance['center'] = $new_instance['center'];
			$instance['url'] = strip_tags($new_instance['url']);
			return $instance;
		}
	
		/** @see WP_Widget::form */
		function form( $instance ) {
			if ( $instance ) {
				$title = esc_attr( $instance[ 'title' ] );
				$size = esc_attr( $instance[ 'size' ] );
				$link = esc_attr( $instance[ 'link_w' ] );
				$center = esc_attr( $instance[ 'center' ] );
				$url = esc_attr( $instance[ 'url' ] );
			}
			else {
				$title = __( 'QR Code', 'sqrc' );
				$size = '200x200';
				$link = false;
				$center = true;
				$url = '';
			}
			?>
			<p>
				<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label> 
				<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('url'); ?>"><?php _e('URL to Encode in QR Code:'); ?></label> 
				<input class="widefat" id="<?php echo $this->get_field_id('url'); ?>" name="<?php echo $this->get_field_name('url'); ?>" type="text" value="<?php echo $url; ?>" />
				<br/><small>leave blank to use current page url in each page, or enter content you would like encoded in QR Code.</small>
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('size'); ?>"><?php _e('Size:'); ?></label> 
				<select class="widefat" id="<?php echo $this->get_field_id('size'); ?>" name="<?php echo $this->get_field_name('size'); ?>">
					<option value="50x50" <?php if ($size == "50x50") echo 'selected="selected"'; ?>>50x50</option>
					<option value="100x100" <?php if ($size == "100x100") echo 'selected="selected"'; ?>>100x100</option>
					<option value="150x150" <?php if ($size == "150x150") echo 'selected="selected"'; ?>>150x150</option>
					<option value="200x200" <?php if ($size == "200x200") echo 'selected="selected"'; ?>>200x200</option>
					<option value="250x250" <?php if ($size == "250x250") echo 'selected="selected"'; ?>>250x250</option>
					<option value="280x280" <?php if ($size == "280x280") echo 'selected="selected"'; ?>>280x280</option>
					<option value="300x300" <?php if ($size == "300x300") echo 'selected="selected"'; ?>>300x300</option>
					<option value="350x350" <?php if ($size == "350x350") echo 'selected="selected"'; ?>>350x350</option>
					<option value="400x400" <?php if ($size == "400x400") echo 'selected="selected"'; ?>>400x400</option>
				</select>
			</p>
			<p>
				<label for="<?php echo $this->get_field_id( 'link_w' ); ?>"><?php _e('Wrap QR Code with a link?'); ?></label>
				<input class="checkbox" type="checkbox" <?php checked( (bool) $link, true ); ?> id="<?php echo $this->get_field_id( 'link_w' ); ?>" name="<?php echo $this->get_field_name( 'link_w' ); ?>" />
				<br/><small>check this if you want to make the QR Code image clickable.</small>
			</p>
			<p>
				<label for="<?php echo $this->get_field_id( 'center' ); ?>"><?php _e('Center QR Code?'); ?></label>
				<input class="checkbox" type="checkbox" <?php checked( (bool) $center, true ); ?> id="<?php echo $this->get_field_id( 'center' ); ?>" name="<?php echo $this->get_field_name( 'center' ); ?>" />
				<br/><small>check this if you want to Center the QR Code image.</small>
			</p>
			<?php 
		}
		
		/**
		 * function to get the actual QR_Code
		 */
		function do_qr_code($size = '150x150',$link = false,$content = null,$center = true){
			$out = do_shortcode('[QR size="'.$size.'" link="'.$link.'"]'.$content.'[/QR]');
			if ($center){
				$out = '<div style="text-align: center;">'.$out.'</div>';
			}
			return $out;
		}
	} // class QR_Code_Widget
}//end if

add_action( 'widgets_init', create_function( '', 'return register_widget("QR_Code_Widget");' ) );

/**
* SimpleQR_CODE_TinyMCE_button
*/
class SimpleQR_CODE_TinyMCE_button{
    
    function __construct(){
    	add_action('admin_head', array($this,'my_add_mce_button'));
    }
    // Hooks your functions into the correct filters
	function my_add_mce_button() {
		// check user permissions
		if ( !current_user_can( 'edit_posts' ) && !current_user_can( 'edit_pages' ) ) {
			return;
		}
		// check if WYSIWYG is enabled
		if ( 'true' == get_user_option( 'rich_editing' ) ) {
			add_filter( 'mce_buttons',array($this,'mce_buttons' ));
	        	add_filter( 'tiny_mce_before_init',array($this,'tiny_mce_before_init' ));
		}
		echo '<style> i.mce-i-QR_Code-icon { background-image: url("'.plugins_url( '7fhhXUr.png' , __FILE__ ).'"); }</style>';
	}
 
 
    function mce_buttons( $buttons  ){
        $buttons[] = 'QR_Code';
        return $buttons;
    }
 
    function tiny_mce_before_init( $initArray ){
	    $initArray['setup'] = <<<JS
[function(ed) {
    ed.addButton( 'QR_Code', {
		text: 'QR Code',
		icon: 'QR_Code-icon',
		onclick: function() {
			ed.windowManager.open( {
				title: 'Insert QR code Shortcode',
				body: [
					{
						type: 'textbox',
						name: 'code',
						label: 'Content to Encode (usually URL)',
						multiline: true,
						minWidth: 300,
						minHeight: 100,
						value: ''
					},
					{
						type: 'listbox',
						name: 'size',
						label: 'QR code size',
						'values': [
							{text: '50x50', value: '50x50'},
							{text: '100x100', value: '100x100'},
							{text: '150x150', value: '150x150'},
							{text: '200x200', value: '200x200'},
							{text: '250x250', value: '250x250'},
							{text: '280x280', value: '280x280'},
							{text: '300x300', value: '300x300'},
							{text: '350x350', value: '350x350'},
							{text: '400x400', value: '400x400'},
							{text: '500x500', value: '500x500'}
						]
					},
					{
						type: 'listbox',
						name: 'clickable',
						label: 'make QR Code Image clickable?',
						'values': [
							{text: 'Yes', value: 'yes'},
							{text: 'No', value: 'no'}
						]
					}
				],
				onsubmit: function( e ) {
					ed.insertContent( '[QR size="' + e.data.size + '" link="' + e.data.clickable + '"]'+ e.data.code+'[/QR]');
				}
			});
		}
	});
}][0]
JS;
	    return $initArray;
	}
}
new SimpleQR_CODE_TinyMCE_button();