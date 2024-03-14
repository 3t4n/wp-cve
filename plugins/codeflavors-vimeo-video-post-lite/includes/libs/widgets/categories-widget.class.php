<?php

namespace Vimeotheque\Widgets;

use Vimeotheque\Admin\Helper_Admin;
use Vimeotheque\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Categories_Widget
 * @package Vimeotheque\Widgets
 * @ignore
 */
class Categories_Widget extends \WP_Widget{
	/**
	 * Constructor
	 */
	function __construct(){
		/* Widget settings. */
		$widget_options = [
			'classname' 	=> 'widget_categories cvm-video-categories',
			'description' 	=> __('A list or dropdown of video categories.', 'codeflavors-vimeo-video-post-lite')
		];

		/* Widget control settings. */
		$control_options = [
			'id_base' => 'cvm-video-categories-widget'
		];

		/* Create the widget. */
		parent::__construct(
			'cvm-video-categories-widget',
			__('Vimeo video categories', 'codeflavors-vimeo-video-post-lite'),
			$widget_options,
			$control_options
		);
	}

	/**
	 * (non-PHPdoc)
	 * @see WP_Widget::widget()
	 */
	function widget( $args, $instance ){
		/**
		 * @var string $before_title
		 * @var string $after_title
		 * @var string $before_widget
		 * @var string $after_widget
		 */
		extract($args);

		$widget_title = '';
		if( isset( $instance['title'] ) && !empty( $instance['title'] ) ){
			$widget_title = $before_title . apply_filters( 'widget_title', $instance['title'] ) . $after_title;
		}

		$args = [
			'taxonomy' => Plugin::instance()->get_cpt()->get_post_tax(),
			'pad_counts' => true,
			'title_li'	=> false,
			'show_count' => $instance['post_count'],
			'hierarchical' => $instance['hierarchy']
		];

		echo $before_widget;
		echo $widget_title;
		echo '<ul>';
		wp_list_categories( $args );
		echo '</ul>';
		echo $after_widget;
	}

	/**
	 * (non-PHPdoc)
	 * @param $new_instance
	 * @param $old_instance
	 *
	 * @return array
	 * @see WP_Widget::update()
	 */
	function update($new_instance, $old_instance){

		$instance = $old_instance;
		$instance['title'] 				= $new_instance['title'];
		$instance['dropdown'] 			= (bool)$new_instance['dropdown'];
		$instance['post_count']	  		= (bool)$new_instance['post_count'];
		$instance['hierarchy'] 			= (bool)$new_instance['hierarchy'];

		return $instance;
	}

	/**
	 * (non-PHPdoc)
	 * @param $instance
	 *
	 * @see WP_Widget::form()
	 */
	function form( $instance ){

		$defaults 	= $this->get_defaults();;
		$options 	= wp_parse_args( (array)$instance, $defaults );

		?>
		<p>
			<label for="<?php echo  $this->get_field_id('title');?>"><?php _e('Title', 'codeflavors-vimeo-video-post-lite');?>: </label>
			<input type="text" name="<?php echo  $this->get_field_name('title');?>" id="<?php echo  $this->get_field_id('title');?>" value="<?php echo $options['title'];?>" class="widefat" />
		</p>
		<p>
			<input class="checkbox" type="checkbox" name="<?php echo $this->get_field_name('post_count');?>" id="<?php echo $this->get_field_id('post_count')?>"<?php Helper_Admin::check((bool)$options['post_count']);?> />
			<label for="<?php echo $this->get_field_id('post_count')?>"><?php _e('Show videos count', 'codeflavors-vimeo-video-post-lite');?></label>
			<br />
			<input class="checkbox" type="checkbox" name="<?php echo $this->get_field_name('hierarchy');?>" id="<?php echo $this->get_field_id('hierarchy')?>"<?php Helper_Admin::check((bool)$options['hierarchy']);?> />
			<label for="<?php echo $this->get_field_id('hierarchy')?>"><?php _e('Show hierarchy', 'codeflavors-vimeo-video-post-lite');?></label>
		</p>
		<?php
	}

	/**
	 * Default widget values
	 */
	private function get_defaults(){
		return [
			'title' 			=> '',
			'post_count'		=> false,
			'hierarchy'			=> false
		];
	}
}