<?php
if ( !defined('ABSPATH')) exit;

/* AJAX TABS */
class gabfire_ajaxtabs extends WP_Widget {

	function __construct() {
		$widget_ops = array( 'classname' => 'gabfire_ajaxtabs', 'description' => 'Display recent entries and comments (ajax tab supported)' );
		$control_ops = array( 'width' => 250, 'height' => 350, 'id_base' => 'gabfire_ajaxtabs' );
		parent::__construct( 'gabfire_ajaxtabs', 'Gabfire: Posts Tabs Widget', $widget_ops, $control_ops);
	}

	public function widget($args, $instance) {

		global $post;

		extract( $args );
		$title	= $instance['title'];
		$post_label	= $instance['post_label'];
		$popular_label	= $instance['popular_label'];
		$postmeta	= $instance['postmeta'];
		$post_nr	= $instance['post_nr'];
		$comment_label	= $instance['comment_label'];
		$post_title	= $instance['post_title'];
		$popular_title	= $instance['popular_title'];
		$comments_title	= $instance['comments_title'];
		$comment_nr	= $instance['comment_nr'];
		$popular_nr	= $instance['popular_nr'];
		$colorsc	= $instance['colorsc'] ? '1' : '0';
		$postmeta	= $instance['postmeta'] ? '1' : '0';
		$avatar	= $instance['a_avatar'] ? '1' : '0';

		echo $before_widget;

			if ( $title ) {
				echo $before_title . $title . $after_title;
			}
			?>	
			
			<script type='text/javascript'>
				(function ($) {
					$(document).ready(function () {
						$('ul.gabfire_tabs li').click(function(){
							var tab_id = $(this).attr('data-tab');

							$('ul.gabfire_tabs li').removeClass('current_tab');
							$('.gabfire_tab-content').removeClass('current_tab');

							$(this).addClass('current_tab');
							$("#"+tab_id).addClass('current_tab');
						});
					});
				})(jQuery);

			</script>
			
			<div id="<?php if($colorsc) { echo 'light_colorscheme'; } else { echo 'dark_colorscheme'; } ?>">
			
				<ul class="tab_titles gabfire_tabs">
					<?php if (intval($post_nr) > 0 ) { ?><li class="gab_firsttab current_tab" data-tab="tab-1"><a href="#first"><?php echo esc_attr( $post_label ); ?></a></li><?php } ?>
					<?php if (intval($popular_nr) > 0 ) { ?><li class="gab_secondtab" data-tab="tab-2"><a href="#third"><?php echo esc_attr( $popular_label ); ?></a></li><?php } ?>
					<?php if (intval($comment_nr) > 0 ) { ?><li class="gab_thirdtab" data-tab="tab-3"><a href="#second"><?php echo esc_attr( $comment_label ); ?></a></li><?php } ?>
				</ul>

				<div class="panes">
					<?php if (intval($post_nr) > 0 ) { ?>
					<div id="tab-1" class="gabfire_tab-content current_tab">
						<?php if($post_title) { ?>
							<h3 class="widgettitle_in"><?php echo esc_attr($post_title); ?></h3>
						<?php } ?>
						<ul>
							<?php
							$count = 1;
							$args = array( 'posts_per_page'=> $post_nr, 'ignore_sticky_posts' => 1 );
							$gab_query = new WP_Query();$gab_query->query($args);
							while ($gab_query->have_posts()) : $gab_query->the_post();
							?>
								<li>
									<?php
									if (function_exists('gab_media')) {
										gab_media(array(
											'name' => 'ajaxtabs',
											'imgtag' => 1,
											'link' => 1,
											'enable_video' => 0,
											'catch_image' => 0,
											'enable_thumb' => 1,
											'resize_type' => 'c',
											'media_width' => 35,
											'media_height' => 35,
											'thumb_align' => '',
											'enable_default' => 0
										));
									} else {
										echo get_the_post_thumbnail($post->ID, 'thumbnail');
									}
									?><a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'gabfire-widget-pack' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark" class="block"><?php the_title(); ?></a>
									<?php if($postmeta) { ?>
										<span class="block"><?php _e('by','gabfire-widget-pack'); ?> <?php the_author_posts_link(); ?> - <?php comments_popup_link(__('No Comment','gabfire-widget-pack'), __('1 Comment','gabfire-widget-pack'), __('% Comments','gabfire-widget-pack'));?></span>
									<?php } ?>
								</li>
							<?php $count++; endwhile; wp_reset_query(); ?>
						</ul>
					</div>
					<?php } ?>

					<?php if (intval($popular_nr) > 0 ) { ?>
					<div id="tab-2" class="gabfire_tab-content">
						<?php if($popular_title) { ?>
							<h3 class="widgettitle_in"><?php echo esc_attr($popular_title); ?></h3>
						<?php } ?>
						<ul>
							<?php
							$count=1;
							$args = array( 'posts_per_page'=> $popular_nr, 'orderby' => 'comment_count', 'ignore_sticky_posts' => 1 );
							$gab_query = new WP_Query();$gab_query->query($args);
							while ($gab_query->have_posts()) : $gab_query->the_post();
							?>
								<li>
									<?php
									if (function_exists('gab_media')) {
										gab_media(array(
											'name' => 'ajaxtabs',
											'imgtag' => 1,
											'link' => 1,
											'enable_video' => 0,
											'catch_image' => of_get_option('of_catch_img', 0),
											'enable_thumb' => 1,
											'resize_type' => 'c',
											'media_width' => 35,
											'media_height' => 35,
											'thumb_align' => '',
											'enable_default' => 0
										));
									} else {
										echo get_the_post_thumbnail($post->ID, 'thumbnail');
									}
									?>
									<a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'gabfire-widget-pack' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title(); ?></a>
									<?php if($postmeta) { ?>
										<span class="block"><?php _e('by','gabfire-widget-pack'); ?> <?php the_author_posts_link(); ?> - <?php comments_popup_link(__('No Comment','gabfire-widget-pack'), __('1 Comment','gabfire-widget-pack'), __('% Comments','gabfire-widget-pack'));?></span>
									<?php }?>
								</li>
							<?php $count++; endwhile; wp_reset_query(); ?>
						</ul>
					</div>
					<?php } ?>

					<?php if (intval($comment_nr) > 0 ) { ?>
					<div id="tab-3" class="gabfire_tab-content">
						<?php if($comments_title) { ?>
							<h3 class="widgettitle_in"><?php echo esc_attr($comments_title); ?></h3>
						<?php } ?>
						<ul>
							<?php
								$args = array(
									'status' => 'approve',
									'number' => $comment_nr
								);
								$comments = get_comments($args);
								foreach($comments as $comment) :
									echo '<li class="no-list-image">';
										if($avatar) {
											echo get_avatar( $comment->comment_author_email, 35 );
										}
										echo ( '<strong>' . $comment->comment_author . '</strong>: <a href="' . get_permalink($comment->comment_post_ID) . '" rel="bookmark">' . get_the_title($comment->comment_post_ID) . '</a>');
									echo '</li>';
								endforeach;
							?>
						</ul>
					</div>
					<?php } ?>
				</div>
				
				<div class="clear"></div>
			</div>

			<?php

		echo "<div class='clear'></div>$after_widget";
	}

	function update($new_instance, $old_instance) {
		$instance['title'] 		= ( ! empty( $new_instance['title'] ) ) ? sanitize_text_field( $new_instance['title'] ) : '';
		$instance['post_label'] = ( ! empty( $new_instance['post_label'] ) ) ? sanitize_text_field( $new_instance['post_label'] ) : '';
		$instance['postmeta']	= $new_instance['postmeta'] ? '1' : '0';
		$instance['a_avatar']	= $new_instance['a_avatar'] ? '1' : '0';
		$instance['colorsc']	= $new_instance['colorsc'] ? '1' : '0';
		$instance['post_nr'] = (int) $new_instance['post_nr'];
		$instance['post_title']	= ( ! empty( $new_instance['post_title'] ) ) ? sanitize_text_field( $new_instance['post_title'] ) : '';
		$instance['popular_title']	= ( ! empty( $new_instance['popular_title'] ) ) ? sanitize_text_field( $new_instance['popular_title'] ) : '';
		$instance['comments_title']	= ( ! empty( $new_instance['comments_title'] ) ) ? sanitize_text_field( $new_instance['comments_title'] ) : '';
		$instance['comment_label']	= ( ! empty( $new_instance['comment_label'] ) ) ? sanitize_text_field( $new_instance['comment_label'] ) : '';
		$instance['popular_label']	= ( ! empty( $new_instance['popular_label'] ) ) ? sanitize_text_field( $new_instance['popular_label'] ) : '';
		$instance['comment_nr'] 	= (int) $new_instance['comment_nr'];
		$instance['popular_nr'] 	= (int) $new_instance['popular_nr'];
		return $new_instance;
	}

	function form($instance) {
		$defaults = array(
			'title' => 'Posts',
			'post_label' => 'Latest',
			'comment_label' => 'Comments',
			'popular_label' => 'Popular',
			'post_nr' => '5',
			'comment_nr' => '5',
			'popular_nr' => '5',
			'post_title' => '',
			'popular_title' => '',
			'comments_title' => '',
			'colorsc' => '1',
			'postmeta' => '1',
			'a_avatar' => '1',
		);
		$instance = wp_parse_args( (array) $instance, $defaults );
		?>

		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title','gabfire-widget-pack'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($instance['title']); ?>" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id('post_label'); ?>"><?php _e('Recent Posts tab label','gabfire-widget-pack'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('post_label'); ?>" name="<?php echo $this->get_field_name('post_label'); ?>" type="text" value="<?php echo esc_attr($instance['post_label']); ?>" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id('comment_label'); ?>"><?php _e('Comments tab label','gabfire-widget-pack'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('comment_label'); ?>" name="<?php echo $this->get_field_name('comment_label'); ?>" type="text" value="<?php echo esc_attr($instance['comment_label']); ?>" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id('popular_label'); ?>"><?php _e('Popular tab label','gabfire-widget-pack'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('popular_label'); ?>" name="<?php echo $this->get_field_name('popular_label'); ?>" type="text" value="<?php echo esc_attr($instance['popular_label']); ?>" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id('post_title'); ?>"><?php _e('Recent Posts List Title','gabfire-widget-pack'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('post_title'); ?>" name="<?php echo $this->get_field_name('post_title'); ?>" type="text" value="<?php echo esc_attr($instance['post_title']); ?>" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id('popular_title'); ?>"><?php _e('Popular Posts List Title','gabfire-widget-pack'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('popular_title'); ?>" name="<?php echo $this->get_field_name('popular_title'); ?>" type="text" value="<?php echo esc_attr($instance['popular_title']); ?>" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id('comments_title'); ?>"><?php _e('Comments List Title','gabfire-widget-pack'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('comments_title'); ?>" name="<?php echo $this->get_field_name('comments_title'); ?>" type="text" value="<?php echo esc_attr($instance['comments_title']); ?>" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'colorsc' ); ?>"><?php _e('Color Scheme','gabfire-widget-pack'); ?></label>
			<select id="<?php echo $this->get_field_id( 'colorsc' ); ?>" name="<?php echo $this->get_field_name( 'colorsc' ); ?>">
				<option value="1" <?php selected( $instance['colorsc'], '1' ); ?>><?php _e('Light','gabfire-widget-pack'); ?></option>
				<option value="0" <?php selected( $instance['colorsc'], '0' ); ?>><?php _e('Dark','gabfire-widget-pack'); ?></option>
			</select>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'postmeta' ); ?>"><?php _e('Display post meta below title','gabfire-widget-pack'); ?></label>
			<select id="<?php echo $this->get_field_id( 'postmeta' ); ?>" name="<?php echo $this->get_field_name( 'postmeta' ); ?>">
				<option value="1" <?php selected( $instance['postmeta'] , '1' ); ?>><?php _e('Enable','gabfire-widget-pack'); ?></option>
				<option value="0" <?php selected( $instance['postmeta'] , '0' ); ?>><?php _e('Disable','gabfire-widget-pack'); ?></option>
			</select>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'a_avatar' ); ?>"><?php _e('Display author avatar for comments','gabfire-widget-pack'); ?></label>
			<select id="<?php echo $this->get_field_id( 'a_avatar' ); ?>" name="<?php echo $this->get_field_name( 'a_avatar' ); ?>">
				<option value="1" <?php selected( $instance['a_avatar'], '1' ); ?>><?php _e('Enable','gabfire-widget-pack'); ?></option>
				<option value="0" <?php selected( $instance['a_avatar'], '0' ); ?>><?php _e('Disable','gabfire-widget-pack'); ?></option>
			</select>
		</p>

		<p>
			<label for="<?php echo $this->get_field_name( 'post_nr' ); ?>"><?php _e('Number of posts','gabfire-widget-pack'); ?></label>
			<select id="<?php echo $this->get_field_id( 'post_nr' ); ?>" name="<?php echo $this->get_field_name( 'post_nr' ); ?>">
			<?php
				for ( $i = 0; $i <= 15; ++$i )
				echo "<option value='$i' " . selected( $instance['post_nr'], $i, false ) . ">$i</option>";
			?>
			</select>
		</p>

		<p>
			<label for="<?php echo $this->get_field_name( 'comment_nr' ); ?>"><?php _e('Number of Comments','gabfire-widget-pack'); ?></label>
			<select id="<?php echo $this->get_field_id( 'comment_nr' ); ?>" name="<?php echo $this->get_field_name( 'comment_nr' ); ?>">
			<?php
				for ( $i = 0; $i <= 15; ++$i )
				echo "<option value='$i' " . selected( $instance['comment_nr'], $i, false ) . ">$i</option>";
			?>
			</select>
		</p>

		<p>
			<label for="<?php echo $this->get_field_name( 'popular_nr' ); ?>"><?php _e('Number of Popular Posts','gabfire-widget-pack'); ?></label>
			<select id="<?php echo $this->get_field_id( 'popular_nr' ); ?>" name="<?php echo $this->get_field_name( 'popular_nr' ); ?>">
			<?php
				for ( $i = 0; $i <= 15; ++$i )
				echo "<option value='$i' " . selected( $instance['popular_nr'], $i, false ) . ">$i</option>";
			?>
			</select>
		</p>
<?php
	}
}

function register_gabfire_ajaxtabs() {
	register_widget('gabfire_ajaxtabs');
}

add_action('widgets_init', 'register_gabfire_ajaxtabs');