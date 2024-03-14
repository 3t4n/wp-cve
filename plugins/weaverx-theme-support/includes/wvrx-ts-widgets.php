<?php
/*
 *  Weaver X Widgets and shortcodes - widgets
 */


class WeaverX_Widget_Text extends WP_Widget {

	function __construct() {
		$widget_ops = array('classname' => 'WeaverX_Widget_Text',
		 'description' => esc_html__('Text Widget with Two Columns - with HTML and shortcode support. Also adds shortcodes to standard Text widget.','weaverx-theme-support' /*adm*/));
		$control_ops = array('width' => 400, 'height' => 350);
		parent::__construct('wvrx2_text', esc_html__('Weaver Text 2 Col','weaverx-theme-support' /*adm*/), $widget_ops, $control_ops);
	}

	function widget( $args, $instance ) {
		extract($args);
		$title = apply_filters( 'widget_title', empty($instance['title']) ? '' : $instance['title'], $instance, $this->id_base);
		$text = apply_filters( 'weaverx_text', $instance['text'], $instance );
		$text2 = apply_filters( 'weaverx_text', $instance['text2'], $instance );
		echo $before_widget;
		if ( !empty( $title ) ) { echo $before_title . $title . $after_title; } ?>
			<div class="textwidget"><div style="float: left; width: 48%; padding-right: 2%;">
			<?php
			if ($instance['filter']) {
				echo(wpautop($text)); echo('</div><div style="float: left; width: 48%; padding-left: 2%;">');
				echo(wpautop($text2)); echo('</div><div style="clear: both;"></div>');
			} else {
			    echo($text); echo('</div><div style="float: left; width: 48%; padding-left: 2%;">');
			    echo($text2); echo('</div><div style="clear: both;"></div>');
			}
			?>
			</div>
		<?php
		echo $after_widget;
	}

	function update( $new_instance, $old_instance ):array {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		if ( current_user_can('unfiltered_html') ) {
			$instance['text'] =  $new_instance['text'];
			$instance['text2'] =  $new_instance['text2'];
		}
		else {
			$instance['text'] = stripslashes( wp_filter_post_kses( addslashes($new_instance['text']) ) ); // wp_filter_post_kses() expects slashed
			$instance['text2'] = stripslashes( wp_filter_post_kses( addslashes($new_instance['text2']) ) );
		}
		$instance['filter'] = isset($new_instance['filter']);
		return $instance;
	}

	function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => '', 'text' => '', 'text2' => '',  'filter' => 0) );
		$title = strip_tags($instance['title']);
		$text = format_to_edit($instance['text']);
		$text2 = format_to_edit($instance['text2']);
?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php echo('Title:' /*a*/ ); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></p>

		<textarea class="widefat" rows="8" cols="20" id="<?php echo $this->get_field_id('text'); ?>" name="<?php echo $this->get_field_name('text'); ?>"><?php echo $text; ?></textarea>
		<textarea class="widefat" rows="8" cols="20" id="<?php echo $this->get_field_id('text2'); ?>" name="<?php echo $this->get_field_name('text2'); ?>"><?php echo $text2; ?></textarea>
		<p><input id="<?php echo $this->get_field_id('filter'); ?>" name="<?php echo $this->get_field_name('filter'); ?>" type="checkbox" <?php checked(isset($instance['filter']) ? $instance['filter'] : 0); ?> />
			&nbsp;<label for="<?php echo $this->get_field_id('filter'); ?>"><?php echo 'Automatically add paragraphs'; ?></label></p>
<?php
	}
}

/**
 * Weaver X Per Page Text
 */
class WeaverX_Widget_PPText extends WP_Widget {

    function __construct() {
	$widget_ops = array('classname' => 'wvrx_widget_pptext', 'description' =>
	    esc_html__('Display text on a Per Page basis. Add to Widget area to see instructions.','weaverx-theme-support' /*adm*/) );
	parent::__construct('wvrx_pptext', esc_html__('Weaver Per Page Text','weaverx-theme-support' /*adm*/), $widget_ops);
    }

    function widget( $args, $instance ) {
	extract($args);
	$title = get_post_meta(get_the_ID(),'wvrx_ts_pp_title',true);
	$text = get_post_meta(get_the_ID(),'wvrx_ts_pp_text',true);

	if (empty($title) && empty($text)) {
            return;
        }

	echo $before_widget;
	if ( !empty( $title ) ) {
        echo $before_title . wp_kses_post($title) . $after_title;
    }
    echo do_shortcode(wp_kses_post($text));
	echo $after_widget;
    }

	function update( $new_instance, $old_instance ):array {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);

		return $instance;
	}

	function form( $instance ) {
?>
<p><?php echo wp_kses_post(__('This widget will work like a text widget, but the title and content are defined by custom
fields set on a Per Page basis. For any page, define the Custom Field <em>wvrx_ts_pp_title</em>
if you want a title, and define Custom Field <em>wvrx_ts_pp_text</em> as the content. Content can include arbitrary text,
HTML, and shortcodes. The text will not automatically add paragraphs. The widget will display only if the custom
fields are defined when that page is displayed. (This widget won\'t display on the default blog or other archive-like pages.)','weaverx-theme-support' /*adm*/)); ?><p>
<?php
	}
}

/**
 * Weaver X login
 */
class WeaverX_Widget_Login extends WP_Widget {

	function __construct() {
		$widget_ops = array('classname' => 'wvrx_widget_login', 'description' => esc_html__( "Log in/out, admin", 'weaverx-theme-support' /*adm*/ ) );
		parent::__construct('wvrx_login', esc_html__('Weaver Login','weaverx-theme-support' /*adm*/), $widget_ops);
	}

	function widget( $args, $instance ) {
		extract($args);
		$title = apply_filters('widget_title', empty($instance['title']) ? esc_html__('Login', 'weaverx-theme-support' /*adm*/ ) : $instance['title'], $instance, $this->id_base);

		echo $before_widget;
		if ( $title )
			{echo $before_title . $title . $after_title;}
		global $current_user;
		$current_user = wp_get_current_user();
		if (isset($current_user->display_name))
			{echo '<span class="wvrx-welcome-user">' . esc_html__('Welcome','weaverx-theme-support' /*adm*/) . ' ' . $current_user->display_name . ".</span><br />\n";}
?>
		<ul>
		<?php wp_register(); ?>
		<li><?php wp_loginout(); ?></li>
		</ul>
<?php
	echo $after_widget;
	}

	function update( $new_instance, $old_instance ):array {
	    $instance = $old_instance;
	    $instance['title'] = strip_tags($new_instance['title']);

	    return $instance;
	}

	function form( $instance ) {
	    $instance = wp_parse_args( (array) $instance, array( 'title' => '' ) );
	    $title = strip_tags($instance['title']);
?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php esc_html_e('Title:','weaverx-theme-support' /*adm*/); ?></label> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></p>
<?php
	}
}

// ###################################### ELEMENTOR WIDGET ####################################

if ((defined('WVRX_TS_PAGEBUILDERS') && WVRX_TS_PAGEBUILDERS) && defined( 'ELEMENTOR_VERSION' ) ) :
class Weaver_Widget_Elementor extends WP_Widget {

	function __construct() {
		$widget_ops = array('classname' => 'Weaver_Widget_Elementor',
		 'description' => esc_html__('Show an Elementor Page or Post','show-sliders' /*adm*/));
		$control_ops = array('width' => 400, 'height' => 350);
		parent::__construct('wvr_elementor_page', esc_html__('Weaver Elementor Page','show-sliders' /*adm*/), $widget_ops, $control_ops);
	}

	function widget($args, $instance) {
		extract($args);

		$instance['title'] = apply_filters('widget_title', $instance['title'], $instance, $this->id_base);
		$use_post_title = ! empty( $instance['use_post_title'] ) ? '1' : '0';

		$post_list = $instance['post_list'];
		if (!$post_list) {$post_list = '';}

		$pb_post_ID = $instance['pb_post_ID'];
		if (!$pb_post_ID) {$pb_post_ID ='';}

		$show_post = $post_list;

		if ( $pb_post_ID )
			{$show_post = $pb_post_ID;}			// override list selection.


		echo $before_widget;

		if ( $use_post_title ) {
			$post_title = get_the_title($show_post);
			echo $args['before_title'] . esc_html($post_title) . $args['after_title'];
		} else if ( !empty($instance['title']) )
			{echo $args['before_title'] . $instance['title'] . $args['after_title'];}


		$before = $show_post ? "<div class='weaver-pagebuilder-elementor weaver-pagebulder-$show_post'>" : '';
		$after = $show_post ? "</div>" : '';

		$out = '';


	// This widget is elementor specific...

	$is_elementor = ! ! get_post_meta( $show_post, '_elementor_edit_mode', true );
	if  ( $is_elementor ) {

		// okay, gotta fetch the_post for this post so that it will be properly intercepted by the page builder
		$args = array(
			'p'         => $show_post, // ID of a page, post, or custom type
			'post_type' => 'page',
		);

		$use_posts = new WP_Query($args);
		while ( $use_posts->have_posts() ) {
			$use_posts->the_post();

			$out .= '<div id="post-' . $show_post . '" class="' . join( ' ', get_post_class('content-page-builder')) . '">';
			$out .= apply_filters('the_content', get_the_content());
			$out .= "</div>\n";
		}
		wp_reset_query();		// undo our WP_Query
		wp_reset_postdata();
	 } else {
		$out = esc_html__('Sorry, you did not specify an Elementor Page.', 'weaverx-theme-support');
	 }

		echo $before . $out . $after;

		echo $after_widget;
}

function update( $new_instance, $old_instance ):array {
	$instance['title'] = strip_tags( stripslashes($new_instance['title']) );
	$instance['post_list'] = $new_instance['post_list'];
	$post_id_string = strip_tags( stripslashes(trim($new_instance['pb_post_ID']) ));
	$instance['use_post_title'] = !empty($new_instance['use_post_title']) ? 1 : 0;

	$post_id = (int) $post_id_string;
	if ( (string) $post_id == $post_id_string && $post_id != 0 ) {
		$instance['pb_post_ID'] = $post_id_string;
	} else {
		$instance['pb_post_ID'] ='';
	}
	return $instance;
}

function form( $instance ) {
	$title = isset( $instance['title'] ) ? $instance['title'] : '';
	$post_list = isset( $instance['post_list'] ) ? $instance['post_list'] : '0';
	$pb_post_ID = isset( $instance['pb_post_ID'] ) ? $instance['pb_post_ID'] :'';
	$use_post_title = isset( $instance['use_post_title'] ) ? (bool) $instance['use_post_title'] : false;

	//echo "<p>post list id: {$post_list} - specific: {$pb_post_ID} </p>\n";
?>
	<p>
	<label for="<?php echo $this->get_field_id('title'); ?>"><?php echo('Title (optional):' /*a*/ ) ?></label>
	<input type="text" class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo esc_textarea($title); ?>" />
	</p>
	<p><input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('use_post_title'); ?>" name="<?php echo $this->get_field_name('use_post_title'); ?>"<?php checked( $use_post_title ); ?> />
		<label for="<?php echo $this->get_field_id('use_post_title'); ?>"><?php esc_html_e( 'Use the Title of the selected Page for widget title.' ); ?></label>
	</p>

<?php

	if ( $pb_post_ID ) {
		echo "<p>Please clear the <em>ID of an Elementor Page or Post</em> value below to show a selection list of Elementor Pages.</p>\n";
	} else {
		$pargs = array (
			//'meta_key' => '_elementor_edit_mode',
			//'meta_value' => true,
			'post_type' => 'page',
		);
		$posts = get_pages($pargs);
	?>

		<p>
		<label for="<?php echo $this->get_field_id('post_list'); ?>"><?php echo('Select an Elementor Page. (Override this selection in Page/Post field below to select Page OR Post by ID.)'); ?></label><br />
		<select id="<?php echo $this->get_field_id('post_list'); ?>" name="<?php echo $this->get_field_name('post_list'); ?>">
	<?php
			foreach ( $posts as $post) {
				if ( ! ! get_post_meta( $post->ID, '_elementor_edit_mode', true ) ) {
					$selected = $post_list == $post->ID ? ' selected="selected"' : '';
					echo '<option'. $selected .' value="' . $post->ID .'">'. substr( $post->post_title, 0, 60) .'</option>';		// make the title fit, more or less
				}
			}
	?>
		</select>
		</p>
<?php
	}
?>

		<p>
		<label for="<?php echo $this->get_field_id('pb_post_ID'); ?>"><?php echo('ID of an Elementor Page or Post (overrides Selection list above):'  ) ?></label>
		<input type="text" size="12" id="<?php echo $this->get_field_id('pb_post_ID'); ?>" name="<?php echo $this->get_field_name('pb_post_ID'); ?>" value="<?php echo esc_textarea($pb_post_ID); ?>" />
		</p>
<?php

}
}
endif;

// ###################################### SITEORIGIN PAGE BUILDER WIDGET ####################################
if ( (defined('WVRX_TS_PAGEBUILDERS') && WVRX_TS_PAGEBUILDERS) && defined('SITEORIGIN_PANELS_VERSION' ) ) :
class Weaver_Widget_SiteOrigin extends WP_Widget {

	function __construct() {
		$widget_ops = array('classname' => 'Weaver_Widget_SiteOrigin',
		 'description' => esc_html__('Show a SiteOrigin Page or Post','show-sliders' /*adm*/));
		$control_ops = array('width' => 400, 'height' => 350);
		parent::__construct('wvr_siteorigin_page', esc_html__('Weaver SiteOrigin Page','show-sliders' /*adm*/), $widget_ops, $control_ops);
	}

	function widget($args, $instance) {
		extract($args);

		$instance['title'] = apply_filters('widget_title', $instance['title'], $instance, $this->id_base);
		$use_post_title = ! empty( $instance['use_post_title'] ) ? '1' : '0';

		$post_list = $instance['post_list'];
		if (!$post_list) {$post_list = '';}

		$pb_post_ID = $instance['pb_post_ID'];
		if (!$pb_post_ID) {$pb_post_ID ='';}

		$show_post = $post_list;

		if ( $pb_post_ID )
			{$show_post = $pb_post_ID;}			// override list selection.


		echo $before_widget;

		if ( $use_post_title ) {
			$post_title = get_the_title($show_post);
			echo $args['before_title'] . esc_html($post_title) . $args['after_title'];
		} else if ( !empty($instance['title']) )
			{echo $args['before_title'] . $instance['title'] . $args['after_title'];}


		$before = $show_post ? "<div class='weaver-pagebuilder-siteorigin weaver-pagebulder-$show_post'>" : '';
		$after = $show_post ? "</div>" : '';

		$out = '';


	// This widget is SiteOrigin specific...

	$is_siteorigin = ! ! get_post_meta( $show_post, 'panels_data', true );
	if  ( $is_siteorigin ) {

		// okay, gotta fetch the_post for this post so that it will be properly intercepted by the page builder
		$args = array(
			'p'         => $show_post, // ID of a page, post, or custom type
			'post_type' => 'page',
		);

		$use_posts = new WP_Query($args);
		while ( $use_posts->have_posts() ) {
			$use_posts->the_post();

			$out .= '<div id="post-' . $show_post . '" class="' . join( ' ', get_post_class('content-page-builder')) . '">';
			$out .= apply_filters('the_content', get_the_content());
			$out .= "</div>\n";
		}
		wp_reset_query();		// undo our WP_Query
		wp_reset_postdata();
	 } else {
		$out = esc_html__('Sorry, you did not specify a SiteOrigin Page.', 'weaverx-theme-support');
	 }

		echo $before . $out . $after;

		echo $after_widget;
}

function update( $new_instance, $old_instance ):array {
	$instance['title'] = strip_tags( stripslashes($new_instance['title']) );
	$instance['post_list'] = $new_instance['post_list'];
	$post_id_string = strip_tags( stripslashes(trim($new_instance['pb_post_ID']) ));
	$instance['use_post_title'] = !empty($new_instance['use_post_title']) ? 1 : 0;

	$post_id = (int) $post_id_string;
	if ( (string) $post_id == $post_id_string && $post_id != 0 ) {
		$instance['pb_post_ID'] = $post_id_string;
	} else {
		$instance['pb_post_ID'] ='';
	}
	return $instance;
}

function form( $instance ) {
	$title = isset( $instance['title'] ) ? $instance['title'] : '';
	$post_list = isset( $instance['post_list'] ) ? $instance['post_list'] : '0';
	$pb_post_ID = isset( $instance['pb_post_ID'] ) ? $instance['pb_post_ID'] :'';
	$use_post_title = isset( $instance['use_post_title'] ) ? (bool) $instance['use_post_title'] : false;

	//echo "<p>post list id: {$post_list} - specific: {$pb_post_ID} </p>\n";
?>
	<p>
	<label for="<?php echo $this->get_field_id('title'); ?>"><?php echo('Title (optional):' /*a*/ ) ?></label>
	<input type="text" class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo esc_textarea($title); ?>" />
	</p>
	<p><input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('use_post_title'); ?>" name="<?php echo $this->get_field_name('use_post_title'); ?>"<?php checked( $use_post_title ); ?> />
		<label for="<?php echo $this->get_field_id('use_post_title'); ?>"><?php esc_html_e( 'Use the Title of the selected Page for widget title.' ); ?></label>
	</p>

<?php

	if ( $pb_post_ID ) {
		echo "<p>Please clear the <em>ID of a SiteOrigin Page or Post</em> value below to show a selection list of SiteOrigin Pages.</p>\n";
	} else {
		$pargs = array (
			'post_type' => 'page',
		);
		$posts = get_pages($pargs);
	?>

		<p>
		<label for="<?php echo $this->get_field_id('post_list'); ?>"><?php echo('Select an SiteOrigin Page. (Override this selection in Page/Post field below to select Page OR Post by ID.)'); ?></label><br />
		<select id="<?php echo $this->get_field_id('post_list'); ?>" name="<?php echo $this->get_field_name('post_list'); ?>">
	<?php
			foreach ( $posts as $post) {
				if ( ! ! get_post_meta( $post->ID, 'panels_data', true ) ) {
					$selected = $post_list == $post->ID ? ' selected="selected"' : '';
					echo '<option'. $selected .' value="' . $post->ID .'">'. substr( $post->post_title, 0, 60) .'</option>';		// make the title fit, more or less
				}
			}
	?>
		</select>
		</p>
<?php
	}
?>

		<p>
		<label for="<?php echo $this->get_field_id('pb_post_ID'); ?>"><?php echo('ID of an SiteOrigin Page or Post (overrides Selection list above):'  ) ?></label>
		<input type="text" size="12" id="<?php echo $this->get_field_id('pb_post_ID'); ?>" name="<?php echo $this->get_field_name('pb_post_ID'); ?>" value="<?php echo esc_textarea($pb_post_ID); ?>" />
		</p>
<?php

}
}
endif;



add_action("widgets_init", "wvrx_ts_load_widgets");
add_filter('weaverx_text', 'do_shortcode');
add_filter('widget_text', 'do_shortcode');		// add to standard text widget, too.

function wvrx_ts_load_widgets():void {
	register_widget('WeaverX_Widget_Text');
	register_widget('WeaverX_Widget_PPText');
	register_widget('WeaverX_Widget_Login');
	if (defined('WVRX_TS_PAGEBUILDERS') && WVRX_TS_PAGEBUILDERS) :
	if (defined( 'ELEMENTOR_VERSION' ) ) {		// only provide if elementor is active
		register_widget('Weaver_Widget_Elementor');
	}
	if (defined( 'SITEORIGIN_PANELS_VERSION' ) ) {		// only provide if elementor is active
		register_widget('Weaver_Widget_SiteOrigin');
	}
	endif;
}

