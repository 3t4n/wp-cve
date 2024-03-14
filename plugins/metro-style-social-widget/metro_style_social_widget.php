<?php
/*
Plugin Name: Metro Style Social Widget
Plugin URI: http://www.aidful.com/metro-style-social-widget-wordpress
Description: Sidebar widget that displays Metro Style Social Icons and links to your Social Profiles.
Version: 1.0.2
Author: Manivannan Muthaiyan
Author URI: http://www.aidful.com
License: GPLv2 or Later
*/


class Metro_Style_Socialicons_Widget extends WP_Widget {


	function Metro_Style_Socialicons_Widget() {
	
		/* Widget settings */
		$widget_ops = array( 'classname' => 'metro_style_social_widget', 'description' => __('Widget displays metro style social icons and links to your social profiles', 'metro_style_social_widget' ));

		/* Widget control settings */
		$control_ops = array('width' => 500, 'id_base' => 'metro_style_social_widget');

		/* Create the widget */
		parent::__construct('metro_style_social_widget', 'Metro Style Social Icons', $widget_ops, $control_ops);

		add_action('wp_enqueue_scripts', array($this, 'register_widget_styles'));

		add_action( 'admin_notices', array($this, 'metro_style_admin_notice'));

		add_action('admin_init', array($this, 'metro_style_message_ignore'));

		add_action( 'admin_enqueue_scripts', array($this, 'metro_color_picker'));

		add_action( 'admin_footer-widgets.php', array( $this, 'print_scripts' ));
	}

	function metro_style_admin_notice() {
		global $current_user ;
        	$user_id = $current_user->ID;
		if ( ! get_user_meta($user_id, 'metro_style_hide_notice2') ) {
        	echo '<div class="updated"><a style="float: right;line-height: 30px;font-weight: bold;" href="?metro_style_message_ignore=0">Dismiss</a><p><b>Metro Style Social Widget</b></p>';
        	printf(__('<p>Widget settings updated and added more options to it. Please check widget options and update it. Now you can set any color to any icons in widget. | <a href="%1$s">Hide Notice</a>'), '?metro_style_message_ignore=0');
        	echo "</p></div>";
		}
	}

	function metro_style_message_ignore() {
		global $current_user;
        	$user_id = $current_user->ID;
        	if ( isset($_GET['metro_style_message_ignore']) && '0' == $_GET['metro_style_message_ignore'] ) {
             		add_user_meta($user_id, 'metro_style_hide_notice2', 'true', true);
		}
	}

	function register_widget_styles() {
		wp_enqueue_style('metro_style_social_widget', plugins_url('metro-style-social-widget/CSS/metro.css'));
	}

	function metro_color_picker( $hook_suffix ) {
		if ( 'widgets.php' !== $hook_suffix ) {
	        	return;
	        }
    		wp_enqueue_style( 'wp-color-picker' );
    		wp_enqueue_script( 'wp-color-picker' );
		wp_enqueue_script( 'underscore' );
	}

        public function print_scripts() {
	                ?>
	                <script>
	                        ( function( $ ){
	                                function initColorPicker( widget ) {
	                                        widget.find( '.metro-color-picker' ).wpColorPicker( {
	                                                change: _.throttle( function() { // For Customizer
	                                                        $(this).trigger( 'change' );
	                                                }, 3000 )
	                                        });
	                                }
	
	                                function onFormUpdate( event, widget ) {
	                                        initColorPicker( widget );
	                                }
	
	                                $( document ).on( 'widget-added widget-updated', onFormUpdate );
	
	                                $( document ).ready( function() {
	                                        $( '#widgets-right .widget:has(.metro-color-picker)' ).each( function () {
	                                                initColorPicker( $( this ) );
	                                        } );
	                                } );
	                        }( jQuery ) );
	                </script>
	                <?php
	        }

	/* Widget form creation */
	function form( $instance ) {
	
		/* Default widget settings */
		$defaults = array(
		'title' => 'Metro Social Media',
		'rssfeed' => 'http://feeds.howopensource.com/HowOpenSource',
		'rss_color' => '#e9a01c',
		'specificfeed' => 'http://www.specificfeeds.com/howopensource',
		'twitter' => 'howopensource',
		'twitter_color' => '#43b3e5',
		'facebook' => 'howopensource',
		'fb_color' => '#1f69b3',	
		'google' => '108151203181049867089',
		'google_color' => '#da4a38',
		'pinterest' => 'howopensource',
		'pinterest_color' => '#d73532',
		'linkedin' => '',
		'linkedin_color' => '#0097bd',
		'select_linkedin' => '',
		'youtube' => '',
		'youtube_color' => '#e64a41',
		'noyoutube' => false,
		'nolinkedin' => false,
		'nopinterest' => false,
		'nogoogle' => false,
		'norssfeed' => false,
		'nospecificfeed' => true,
		'button' => 'button',
		'width' => '',
		'autofix' => true
		);

		$instance = wp_parse_args( (array) $instance, $defaults ); ?>

		<p>
		<table>
			<col width=30%>
			<col width=45%>
			<tr>
			<td>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:') ?></label>
			</td>
			<td>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" />
			</td>
			</tr>
		</table>
		</p>

		<p>
		<table>
			<col width=30%>
			<col width=45%>
			<col width=25%>
			<tr>
			<td>
			<label for="<?php echo $this->get_field_id( 'facebook' ); ?>"><?php _e('Facebook Page ID:') ?></label>
			</td>
			<td>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'facebook' ); ?>" name="<?php echo $this->get_field_name( 'facebook' ); ?>" value="<?php echo $instance['facebook']; ?>" />
			</td>
			<td>
			<input class="metro-color-picker" type="text" id="<?php echo $this->get_field_id( 'fb_color' ); ?>" name="<?php echo $this->get_field_name( 'fb_color' ); ?>" value="<?php echo $instance['fb_color']; ?>" data-default-color="#1f69b3" />
			</td>
			</tr>
		</table>
		</p>
		
		<p>
		<table>
			<col width=30%>
			<col width=45%>
			<col width=25%>
			<tr>
			<td>
			<label for="<?php echo $this->get_field_id( 'google' ); ?>"><?php _e('Google+ ID:') ?></label>
			</td>
			<td>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'google' ); ?>" name="<?php echo $this->get_field_name( 'google' ); ?>" value="<?php echo $instance['google']; ?>" />
			</td>
			<td>
<input class="metro-color-picker" type="text" id="<?php echo $this->get_field_id( 'google_color' ); ?>" name="<?php echo $this->get_field_name( 'google_color' ); ?>" value="<?php echo $instance['google_color']; ?>" data-default-color="#da4a38"/>
			</td>
			</tr>
		</table>
		</p>

		<p>
		<table>
			<col width=30%>
			<col width=45%>
			<col width=25%>
			<tr>
			<td>
			<label for="<?php echo $this->get_field_id( 'twitter' ); ?>"><?php _e('Twitter Name:') ?></label>
			</td>
			<td>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'twitter' ); ?>" name="<?php echo $this->get_field_name( 'twitter' ); ?>" value="<?php echo $instance['twitter']; ?>" />
			</td>
			<td>
			<input class="metro-color-picker" type="text" id="<?php echo $this->get_field_id( 'twitter_color' ); ?>" name="<?php echo $this->get_field_name( 'twitter_color' ); ?>" value="<?php echo $instance['twitter_color']; ?>" data-default-color="#43b3e5"/>
			</td>
			</tr>
		</table>
		</p>

		<p>
		<table>
			<col width=30%>
			<col width=45%>
			<col width=25%>
			<tr>
			<td>
			<label for="<?php echo $this->get_field_id( 'pinterest' ); ?>"><?php _e('Pinterest ID:') ?></label>
			</td>
			<td>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'pinterest' ); ?>" name="<?php echo $this->get_field_name( 'pinterest' ); ?>" value="<?php echo $instance['pinterest']; ?>" />
			</td>
			<td>
			<input class="metro-color-picker" type="text" id="<?php echo $this->get_field_id( 'pinterest_color' ); ?>" name="<?php echo $this->get_field_name( 'pinterest_color' ); ?>" value="<?php echo $instance['pinterest_color']; ?>" data-default-color="#d73532"/>
			</td>
			</tr>
		</table>
		</p>

		<p>
		<table>
			<col width=30%>
			<col width=45%>
			<col width=25%>
			<tr>
			<td>
			<label for="<?php echo $this->get_field_id( 'youtube' ); ?>"><?php _e('Youtube ID:') ?></label>
			</td>
			<td>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'youtube' ); ?>" name="<?php echo $this->get_field_name( 'youtube' ); ?>" value="<?php echo $instance['youtube']; ?>" />
			</td>
			<td>
			<input class="metro-color-picker" type="text" id="<?php echo $this->get_field_id( 'youtube_color' ); ?>" name="<?php echo $this->get_field_name( 'youtube_color' ); ?>" value="<?php echo $instance['youtube_color']; ?>" data-default-color="#e64a41"/>
			</td>
			</tr>
		</table>
		</p>

		<p>
		<table>
			<col width=30%>
			<col width=45%>
			<col width=25%>
			<tr>
			<td>
			<label for="<?php echo $this->get_field_id( 'rssfeed' ); ?>"><?php _e('RSS Feed URL:') ?></label>
			</td>
			<td>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'rssfeed' ); ?>" name="<?php echo $this->get_field_name( 'rssfeed' ); ?>" value="<?php echo $instance['rssfeed']; ?>" />
			</td>
			<td>
			<input class="metro-color-picker" type="text" id="<?php echo $this->get_field_id( 'rss_color' ); ?>" name="<?php echo $this->get_field_name( 'rss_color' ); ?>" value="<?php echo $instance['rss_color']; ?>" data-default-color="#e9a01c"/>
			</td>
			</tr>
		</table>
		</p>

		<p>
		<table>
			<col width=30%>
			<col width=45%>
			<col width=25%>
			<tr>
			<td>
			<label for="<?php echo $this->get_field_id( 'linkedin' ); ?>"><?php _e('Linkedin ID/Full URL:') ?></label>
			</td>
			<td>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'linkedin' ); ?>" name="<?php echo $this->get_field_name( 'linkedin' ); ?>" value="<?php echo $instance['linkedin']; ?>" />
			</td>
			<td>
			<input class="metro-color-picker" type="text" id="<?php echo $this->get_field_id( 'linkedin_color' ); ?>" name="<?php echo $this->get_field_name( 'linkedin_color' ); ?>" value="<?php echo $instance['linkedin_color']; ?>" data-default-color="#0097bd"/>
			</td>
			</tr>
		</table>
		</p>

		<p>
		<table>
			<col width=30%>
			<col width=45%>
			<tr>
			<td>
			<label for="<?php echo $this->get_field_id('select_linkedin'); ?>"><?php _e('Select linkedin Type'); ?></label>
			</td>
			<td>
			<select name="<?php echo $this->get_field_name('select_linkedin'); ?>" id="<?php echo $this->get_field_id('select_width'); ?>" class="widefat">
			<?php 
			$options = array('Member', 'Company');
			foreach ($options as $option) { ?>
				<option <?php selected( $instance['select_linkedin'], $option ); ?> value="<?php echo $option; ?>"><?php echo $option; ?></option>
			<?php	}
			?>
			</select>
			</td>
			</tr>
		</table>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'specificfeed' ); ?>"><?php _e('SpecificFeeds (Free & easy RSS2Email tool):') ?></label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'specificfeed' ); ?>" name="<?php echo $this->get_field_name( 'specificfeed' ); ?>" value="<?php echo $instance['specificfeed']; ?>" />Enter the link you received after setting it up on <a href="http://www.specificfeeds.com/rss">www.specificfeeds.com/rss</a>
		</p>

		<p>
			<label for="hide">Please select the checkbox which you want to hide:</label>
		</p>

		<p>
		<table>
			<tr>
			<td>
			<label for="<?php echo $this->get_field_id('noyoutube'); ?>"><input id="<?php echo $this->get_field_id( 'noyoutube' ); ?>" type="checkbox" name="<?php echo $this->get_field_name( 'noyoutube' ); ?>" value="1" <?php checked( 1, $instance['noyoutube'] ); ?>/> <?php esc_html_e( 'Youtube', 'metro_style_social_widget' ); ?></label>
		 	</td>
			<td>
			<label for="<?php echo $this->get_field_id('nolinkedin'); ?>"><input id="<?php echo $this->get_field_id( 'nolinkedin' ); ?>" type="checkbox" name="<?php echo $this->get_field_name( 'nolinkedin' ); ?>" value="1" <?php checked( 1, $instance['nolinkedin'] ); ?>/> <?php esc_html_e( 'Linkedin', 'metro_style_social_widget' ); ?></label>
		 	</td>
			<td>
			<label for="<?php echo $this->get_field_id('nopinterest'); ?>"><input id="<?php echo $this->get_field_id( 'nopinterest' ); ?>" type="checkbox" name="<?php echo $this->get_field_name( 'nopinterest' ); ?>" value="1" <?php checked( 1, $instance['nopinterest'] ); ?>/> <?php esc_html_e( 'Pinterest', 'metro_style_social_widget' ); ?></label>
		 	</td>
			<td>
			<label for="<?php echo $this->get_field_id('nogoogle'); ?>"><input id="<?php echo $this->get_field_id( 'nogoogle' ); ?>" type="checkbox" name="<?php echo $this->get_field_name( 'nogoogle' ); ?>" value="1" <?php checked( 1, $instance['nogoogle'] ); ?>/> <?php esc_html_e( 'Google+', 'metro_style_social_widget' ); ?></label>
		 	</td>
			<td>
			<label for="<?php echo $this->get_field_id('norssfeed'); ?>"><input id="<?php echo $this->get_field_id( 'norssfeed' ); ?>" type="checkbox" name="<?php echo $this->get_field_name( 'norssfeed' ); ?>" value="1" <?php checked( 1, $instance['norssfeed'] ); ?>/> <?php esc_html_e( 'rssfeed', 'metro_style_social_widget' ); ?></label>
		 	</td>
			<td>
			<label for="<?php echo $this->get_field_id('nospecificfeed'); ?>"><input id="<?php echo $this->get_field_id( 'nospecificfeed' ); ?>" type="checkbox" name="<?php echo $this->get_field_name( 'nospecificfeed' ); ?>" value="1" <?php checked( 1, $instance['nospecificfeed'] ); ?>/> <?php esc_html_e( 'Specificfeed', 'metro_style_social_widget' ); ?></label>
			</td>
			</tr>
		</table>
		</p>

		<p>
			<label for="optional">Optional:</label>
		</p>

		<p>
		<table>
			<tr>
			<td>
			<label for="<?php echo $this->get_field_id('button'); ?>"><input id="<?php echo $this->get_field_id( 'button' ); ?>" type="checkbox" name="<?php echo $this->get_field_name( 'button' ); ?>" value="1" <?php checked( 1, $instance['button'] ); ?>/> <?php esc_html_e( 'Add Like/Follow Button', 'metro_style_social_widget' ); ?></label>
			</td>
			<td>
			<label for="<?php echo $this->get_field_id('autofix'); ?>"><input id="<?php echo $this->get_field_id( 'autofix' ); ?>" type="checkbox" name="<?php echo $this->get_field_name( 'autofix' ); ?>" value="1" <?php checked( 1, $instance['autofix'] ); ?>/> <?php esc_html_e( 'Autofix, if the plugin didn\'t displayed on your site', 'metro_style_social_widget' ); ?></label>
			</td>
			</tr>
		</table>
		</p>

		<p>
		<table>
			<col width=30%>
			<col width=45%>
			<tr>
			<td>
			<label for="<?php echo $this->get_field_id( 'width' ); ?>"><?php _e('Type the width:') ?></label>
			</td>
			<td>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'width' ); ?>" name="<?php echo $this->get_field_name( 'width' ); ?>" value="<?php echo $instance['width']; ?>" />
			</td>
			</tr>
		</table>
		</p>

	<?php
	}

	/* Widget update */
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		$instance['title'] = strip_tags( $new_instance['title'] );

		$instance['width'] = $new_instance['width'];
		
		$instance['facebook'] = $new_instance['facebook'];

		$instance['fb_color'] = $new_instance['fb_color'];
	
		$instance['google'] = $new_instance['google'];

		$instance['google_color'] = $new_instance['google_color'];

		$instance['twitter'] = $new_instance['twitter'];

		$instance['twitter_color'] = $new_instance['twitter_color'];

		$instance['pinterest'] = $new_instance['pinterest'];

		$instance['pinterest_color'] = $new_instance['pinterest_color'];

		$instance['linkedin'] = $new_instance['linkedin'];

		$instance['linkedin_color'] = $new_instance['linkedin_color'];

		$instance['rssfeed'] = $new_instance['rssfeed'];

		$instance['rss_color'] = $new_instance['rss_color'];

		$instance['specificfeed'] = $new_instance['specificfeed'];

		$instance['youtube'] = $new_instance['youtube'];

		$instance['youtube_color'] = $new_instance['youtube_color'];

		$instance['noyoutube'] = $new_instance['noyoutube'];

		$instance['nolinkedin'] = $new_instance['nolinkedin'];

		$instance['nopinterest'] = $new_instance['nopinterest'];

		$instance['nogoogle'] = $new_instance['nogoogle'];

		$instance['norssfeed'] = $new_instance['norssfeed'];

		$instance['nospecificfeed'] = $new_instance['nospecificfeed'];

		$instance['button'] = $new_instance['button'];

		$instance['autofix'] = $new_instance['autofix'];

		$instance['select_linkedin'] = $new_instance['select_linkedin'];

		return $instance;
	}

	/* Widget display */
	function widget( $args, $instance ) {
		extract( $args );

		/* Available Widget Options */
		$title = apply_filters('widget_title', $instance['title'] );
		$facebook = $instance['facebook'];
		$fb_color = $instance['fb_color'];
		$google = $instance['google'];
		$google_color = $instance['google_color'];
		$twitter = $instance['twitter'];
		$twitter_color = $instance['twitter_color'];
		$pinterest = $instance['pinterest'];
		$pinterest_color = $instance['pinterest_color'];
		$linkedin = $instance['linkedin'];
		$linkedin_color = $instance['linkedin_color'];
		$rssfeed = $instance['rssfeed'];
		$rss_color = $instance['rss_color'];
		$specificfeed = $instance['specificfeed'];
		$youtube = $instance['youtube'];
		$youtube_color = $instance['youtube_color'];
		$noyoutube = $instance['noyoutube'];
		$nolinkedin = $instance['nolinkedin'];
		$nopinterest = $instance['nopinterest'];
		$nogoogle = $instance['nogoogle'];
		$norssfeed = $instance['norssfeed'];
		$nospecificfeed = $instance['nospecificfeed'];
		$button = $instance['button'];
		$width = $instance['width'];
		$autofix = $instance['autofix'];
		$select_linkedin = $instance['select_linkedin'];

		/* Before widget */
		if ($autofix) {
		    $before_widget = $instance[''];
		}
		else
		  {
		    echo $before_widget;
		}

		/* Check if title is set */
		if ( $title )
			echo $before_title . $title . $after_title;

		if ( $select_linkedin == 'Member' ) 
		{ 
			$linkedin_url = $linkedin; 
		}
		else if ( $select_linkedin == 'Company' )
		{
			$linkedin_url = "http://www.linkedin.com/company/".$linkedin;
		}
	   	?>


		<?php if (!$button) {
			include('style_without_buttons.php');
		}

		else if ($button) {
			include('style_with_buttons.php');
		}

	?>

        <?php
		/* After widget */
		if ($autofix) {
		    $after_widget = $instance[''];
		}
		else
		  {
		    echo $after_widget;
		}
	}
}

/* Register widget */
add_action('widgets_init', create_function('', 'return register_widget("Metro_Style_Socialicons_Widget");'));
?>
