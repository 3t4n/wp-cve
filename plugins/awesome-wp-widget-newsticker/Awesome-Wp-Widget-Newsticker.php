<?php 
/*

 * Plugin Name: Awesome Wp Widget Newsticker
 * Author: Nayon
 * Author uri: http://www.nayonbd.com
 * Description: Flexible & Customizable wordpress News Ticker Plugin - Easy Ticker
 * Version: 1.0

*/

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}




// some file added hook
add_action('wp_enqueue_scripts','Awwn_news_enqueue_script_area');
function Awwn_news_enqueue_script_area(){
	
 // Some code needs css file
	wp_enqueue_style('news',PLUGINS_URL('css/news.css',__FILE__));
	
 // Some code needs easing min js file means  for animation	
	wp_enqueue_script('easing',PLUGINS_URL('js/jquery.easing.min.js',__FILE__),array('jquery'));
	
// Some code needs main news ticker js file 	
	wp_enqueue_script('ticker',PLUGINS_URL('js/jquery.easy-ticker.js',__FILE__),array('jquery'));
	
}

add_action('init','Awwn_main_area');
function Awwn_main_area(){
	load_plugin_textdomain('Awwn_newsticker_textdomain', false, dirname( __FILE__).'/lang');
}
//widgets main hook 
add_action('widgets_init','Awwn_news_widget_area');

//widgets hook function 
function Awwn_news_widget_area(){
	register_widget('news_ticker');
}
/**
 * Main plugin class
 */
class news_ticker extends wp_widget{

   /**
     * Class constructor
     *
     * @access public
     * @return void
     */
	 
	public function __construct(){
		parent::__construct('news-ticker','widget newsticker',array(
			'description'=>__('News Ticker widget add your website','Awwn_newsticker_textdomain')
		));
	}
	
// start function of the widget
	public function widget($args,$instance){
		?>
		<div class="news-main-area">
			<?php echo $args['before_widget']; ?>
			<?php echo $args['before_title']; ?>
			<h4 class="haeding-area"><?php if(isset($instance['title'])){ echo $instance['title']; }    ?></h4>
			<?php echo $args['after_title']; ?>
			
			<div class="demo1 demof">
				<ul>
					 <?php $post_count = isset($instance['post-count']) ? $instance['post-count'] : ''; ?>
					<?php $news = new wp_Query(array(

						'post_type'=>'post',
						'posts_per_page'=>$post_count 

					)); ?>

					<?php while( $news->have_posts() ) : $news->the_post(); ?>
					<li>
						<a href="<?php the_permalink(); ?>">
							<?php the_post_thumbnail(); ?>
							<?php the_title(); ?>
						</a>
					</li>
					<?php endwhile; ?>
				</ul>
			</div>
<!-- jquery editing height -->
			<?php
			$height = $instance['height'];
			echo '<script> 
				jQuery(".demo1").easyTicker({
					direction: "up",
					easing: "swing",
					height:"'.$height.'",
				});
			</script>';
			?>
			<?php echo $args['after_widget']; ?>

		</div>


		<?php


	} // end function of the widget
	
// start function of the widget
	public function form($instance){
		?>

			<label for="<?php echo $this->get_field_id('title'); ?>">Title</label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php if(isset($instance['title'])){ echo $instance['title']; }  ?>">

			<label for="<?php echo $this->get_field_id('post-count'); ?>">post count</label>
			<input type="number" class="widefat" id="<?php echo $this->get_field_id('post-count'); ?>" name="<?php echo $this->get_field_name('post-count'); ?>" value="<?php if(isset($instance['post-count'])){ echo $instance['post-count']; }  ?>">


			<label for="<?php echo $this->get_field_id('height'); ?>">Height</label>
			<input type="number" class="widefat" id="<?php echo $this->get_field_id('height'); ?>" name="<?php echo $this->get_field_name('height'); ?>" value="<?php if(isset($instance['height'])){ echo $instance['height']; }  ?>">

		<?php
}// end function of the widget

} //widget function end

new news_ticker();
