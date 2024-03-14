<?php
/*
Plugin Name: SEO Tag Cloud Widget
Plugin URI: http://blog.fleischer.hu/wordpress/seo-tag-cloud/
Description: SEO Tag Cloud Widget displays the tag cloud in a SEO-friendly way, using a search engine optimized html markup.
Version: 1.8.2
Author: Gavriel Fleischer
Author URI: http://blog.fleischer.hu/author/gavriel/
*/

// Multi-language support
if (defined('WPLANG') && function_exists('load_plugin_textdomain')) {
	load_plugin_textdomain('seo-tag-cloud', PLUGINDIR.'/'.dirname(plugin_basename(__FILE__)).'/lang', dirname(plugin_basename(__FILE__)).'/lang');
}

/**
 * Display tag cloud.
 *
 * The text size is set by the 'smallest' and 'largest' arguments, which will
 * use the 'unit' argument value for the CSS text size unit. The 'format'
 * argument can be 'flat' (default), 'list', 'nolink', or 'array'. The flat value for the
 * 'format' argument will separate tags with spaces. The list value for the
 * 'format' argument will format the tags in a UL HTML list. The nolink value for the
 * 'format' argument will display the tags without links. The array value for the
 * 'format' argument will return in PHP array type format.
 *
 * The 'orderby' argument will accept 'name' or 'count' and defaults to 'name'.
 * The 'order' is the direction to sort, defaults to 'ASC' and can be 'DESC'.
 *
 * The 'number' argument is how many tags to return. By default, the limit will
 * be to return the top 20 tags in the tag cloud list.
 *
 * The 'topic_count_text_callback' argument is a function, which, given the count
 * of the posts  with that tag, returns a text for the tooltip of the tag link.
 * @see default_topic_count_text
 *
 * The 'exclude' and 'include' arguments are used for the {@link get_tags()}
 * function. Only one should be used, because only one will be used and the
 * other ignored, if they are both set.
 *
 * @since 2.3.0
 *
 * @param array|string $args Optional. Override default arguments.
 * @return array Generated tag cloud, only if no failures and 'array' is set for the 'format' argument.
 */
function seo_tag_cloud( $args = '' ) {
	$defaults = array(
		'largest' => 10, 'number' => 20,
		'format' => 'flat', 'orderby' => 'name', 'order' => 'ASC',
		'exclude' => '', 'include' => '', 'link' => 'view', 'target' => '',
	);
	$args = wp_parse_args( $args, $defaults );

	$tags = get_tags( array_merge( $args, array( 'orderby' => 'count', 'order' => 'DESC' ) ) ); // Always query top tags

	if ( empty( $tags ) )
		return;

	foreach ( $tags as $key => $tag ) {
		if ( 'edit' == $args['link'] )
			$link = get_edit_tag_link( $tag->term_id );
		else
			$link = get_tag_link( $tag->term_id );
		if ( is_wp_error( $link ) )
			return false;

		$tags[ $key ]->link = $link;
		$tags[ $key ]->id = $tag->term_id;
	}

	$return = seo_tag_cloud_generate( $tags, $args ); // Here's where those top tags get sorted according to $args

	$return = apply_filters( 'wp_tag_cloud', $return, $args );

	if ( 'array' == $args['format'] )
		return $return;

	echo $return;
}

/**
 * Generates a tag cloud (heatmap) from provided data.
 *
 * The text size is set by the 'smallest' and 'largest' arguments, which will
 * use the 'unit' argument value for the CSS text size unit. The 'format'
 * argument can be 'flat' (default), 'list', 'nolink' or 'array'. The flat value for the
 * 'format' argument will separate tags with spaces. The list value for the
 * 'format' argument will format the tags in a UL HTML list. The nolink value for the
 * 'format' argument will display the tags without links. The array value for the
 * 'format' argument will return in PHP array type format.
 *
 * The 'orderby' argument will accept 'name' or 'count' and defaults to 'name'.
 * The 'order' is the direction to sort, defaults to 'ASC' and can be 'DESC' or
 * 'RAND'.
 *
 * The 'number' argument is how many tags to return. By default, the limit will
 * be to return the entire tag cloud list.
 *
 * The 'topic_count_text_callback' argument is a function, which given the count
 * of the posts  with that tag returns a text for the tooltip of the tag link.
 * @see default_topic_count_text
 *
 *
 * @todo Complete functionality.
 * @since 2.3.0
 *
 * @param array $tags List of tags.
 * @param string|array $args Optional, override default arguments.
 * @return string
 */
function seo_tag_cloud_generate( $tags, $args = '' ) {
	global $wp_rewrite;
	$defaults = array(
		'largest' => 10, 'number' => 0,
		'format' => 'flat', 'orderby' => 'name', 'order' => 'ASC',
		'topic_count_text_callback' => 'seo_tag_cloud_default_topic_count_text',
		'target' => '', 'size-curve' => 'uniformly-distributed'
	);

	if ( !isset( $args['topic_count_text_callback'] ) && isset( $args['single_text'] ) && isset( $args['multiple_text'] ) ) {
		$body = 'return sprintf (
			__ngettext('.var_export($args['single_text'], true).', '.var_export($args['multiple_text'], true).', $count),
			number_format_i18n( $count ));';
		$args['topic_count_text_callback'] = create_function('$count', $body);
	}

	$options = wp_parse_args( $args, $defaults );

	if ( empty( $tags ) )
		return;

	// SQL cannot save you; this is a second (potentially different) sort on a subset of data.
	uasort( $tags, create_function('$a, $b', 'return ($a->count < $b->count);') );
	if ( $options['number'] > 0 )
		$tags = array_slice($tags, 0, $options['number']);
	if ('posts' != $options['orderby']) {
		uasort( $tags, create_function('$a, $b', 'return strnatcasecmp($a->'.$options['orderby'].', $b->'.$options['orderby'].');') );
	}
	if ( 'DESC' == $options['order'] )
		$tags = array_reverse( $tags, true );
	elseif ( 'RAND' == $options['order'] ) {
		$keys = array_rand( $tags, count( $tags ) );
		foreach ( $keys as $key )
			$temp[$key] = $tags[$key];
		$tags = $temp;
		unset( $temp );
	}

	$counts = array();
	foreach ( (array) $tags as $key => $tag ) {
		$counts[ $key ] = $tag->count;
	}

	if ( 'proportional' == $options['size-curve'] ) {
		$min_count = min( $counts );
		$spread = max( $counts ) - $min_count;
		if ( $spread <= 0 ) {
			$spread = 1;
		}
		$em_step = $options['largest'] / $spread;
		foreach ($counts as $count) {
			$count2em[$count] = ($count - $min_count) * $em_step;
		}
	} elseif ( 'uniformly-distributed' == $options['size-curve'] ) {
		sort($counts);
		$counts = array_values(array_unique($counts));
		$spread = count($counts);
		if ( $spread <= 1 ) {
			$spread = 2;
		}
		$em_step = ($options['largest']) / ($spread - 1);
		$count2em = array();
		foreach ($counts as $i => $count) {
			$count2em[$count] = floor($i * $em_step);
		}
	}

	$a = array();

	$rel = ( is_object( $wp_rewrite ) && $wp_rewrite->using_permalinks() ) ? ' rel="tag"' : '';
	$target_str = empty($options['target']) ? '' : ' target="'.$options['target'].'"';
	$topic_count_text_callback = $options['topic_count_text_callback'];

	foreach ( $tags as $key => $tag ) {
		$count = $tag->count;
		$tag_link = '#' != $tag->link ? clean_url( $tag->link ) : '#';
		$tag_id = isset($tags[ $key ]->id) ? $tags[ $key ]->id : $key;
		$tag_name = $tags[ $key ]->name;
		$extra = $tags[ $key ]->extra;
		$em1 = '<span>';
		$em2 = '</span>';
		$number_of_ems_needed = $count2em[$count];
		for ($i = 0; $i < $number_of_ems_needed; ++$i) {
			$em1 .= '<em>';
			$em2 = '</em>' . $em2;
		}
		$title = ' title="' . attribute_escape( $topic_count_text_callback( $count, $tag_name ) ) . '"';
		if ('nolink' == $options['format'])
			$span = '<span'.$title.$rel.'>'.$tag_name.'</span>'.$extra;
		else
			$span = '<a href="'.$tag_link.'"'.$title.$rel.$target_str.'>'.$tag_name.'</a>'.$extra;
		$a[] = $em1 . $span . $em2;
	}

	$class = '';
	$return = '';
	switch ( $options['format'] ) :
	case 'array' :
		$return =& $a;
		break;
	case 'ball' :
		$class = ' ball';
		$return = '<script type="text/javascript">'."\n".
			'<!--'."\n".
			'jQuery(function(){jQuery("ul.ball").height(jQuery("ul.ball").width()).ball("li", {scale:1.5,speed:25,framerate:25,onReady:function(jqball){jqball.css("visibility","visible")}});});'."\n".
			'//-->'."\n".
			'</script>'."\n";
	case 'list' :
		$return .= '<ul class="seo-tag-cloud' . $class . '">'."\n\t".'<li>';
		$return .= join( "</li>\n\t<li>", $a );
		$return .= "</li>\n</ul>\n";
		break;
	case 'nolink' :
	case 'flat' :
	default :
		$return = '<div class="seo-tag-cloud">'."\n\t";
		$return .= join( "\n", $a );
		$return .= '</div>'."\n";
		break;
	endswitch;

	return apply_filters( 'wp_generate_tag_cloud', $return, $tags, $options );
}

/**
 * Default text for tooltip for tag links
 *
 * @param integer $count number of posts with that tag
 * @return string text for the tooltip of a tag link.
 */
function seo_tag_cloud_default_topic_count_text( $count, $tag ) {
	return sprintf( __ngettext('%s: %s topic', '%s: %s topics', $count, 'seo-tag-cloud'), $tag, number_format_i18n( $count ) );
}

/**
 * Display tag cloud widget.
 *
 * @since 2.3.0
 *
 * @param array $args Widget arguments.
 */
function seo_tag_cloud_widget($args) {
	$defaults = array(
		'title' => __('Tag Cloud', 'seo-tag-cloud'),
		'format' => 'flat', 'orderby' => 'name', 'order' => 'ASC',
		'topic_count_text_callback' => 'seo_tag_cloud_default_topic_count_text',
		'target' => '', 'em_step' => 1.0
	);
	$options = get_option('widget_seo_tag_cloud');
	$options = wp_parse_args($options, $defaults);
	if (empty($options['title'])) {
		$options['title'] = $defaults['title'];
	}
	$options = wp_parse_args($args, $options);
	$title = apply_filters('widget_title', $options['title']);

	echo $options['before_widget'];
	echo $options['before_title'] . $title . $options['after_title'];
	seo_tag_cloud($options);
	if ((bool)$options['show-credit']) {
		printf('<span class="credit">'.__('Powered by %s','seo-tag-cloud').'</span>', '<a href="http://blog.fleischer.hu/wordpress/seo-tag-cloud/" title="'.__('SEO Tag Cloud Widget Plugin for Wordpress','seo-tag-cloud').'">'.__('SEO Tag Cloud','seo-tag-cloud').'</a>');
	}
	echo $options['after_widget'];
}

/**
 * Manage WordPress Tag Cloud widget options.
 *
 * Displays management form for changing the tag cloud widget title.
 *
 * @since 2.3.0
 */
function seo_tag_cloud_widget_control() {
	$options = $newoptions = get_option('widget_seo_tag_cloud');

	if ( isset($_POST['seo-tag-cloud-submit']) ) {
		$newoptions['title'] = strip_tags(stripslashes($_POST['seo-tag-cloud-title']));
		$newoptions['number'] = strip_tags(stripslashes($_POST['seo-tag-cloud-number']));
		$newoptions['text-transform'] = strip_tags(stripslashes($_POST['seo-tag-cloud-text-transform']));
		$newoptions['show-credit'] = strip_tags(stripslashes($_POST['seo-tag-cloud-show-credit']));
		$newoptions['target'] = strip_tags(stripslashes($_POST['seo-tag-cloud-target']));
		$newoptions['format'] = strip_tags(stripslashes($_POST['seo-tag-cloud-format']));
		$newoptions['size-curve'] = strip_tags(stripslashes($_POST['seo-tag-cloud-size-curve']));
	}

	if ( $options != $newoptions ) {
		$options = $newoptions;
		update_option('widget_seo_tag_cloud', $options);
	}

	$title = attribute_escape( $options['title'] );
	$number = attribute_escape( $options['number'] );
	$text_transform = attribute_escape( $options['text-transform'] );
	$format = attribute_escape( $options['format'] );
	$target = attribute_escape( $options['target'] );
	$size_curve = attribute_escape( $options['size-curve'] );
	$show_credit = (bool) $options['show-credit'];
?>
<div class="seo-tag-cloud">
	<p><a href="#" class="preview-link"><?php _e('Preview', 'seo-tag-cloud') ?> <span class="preview-arrow">▾</span></a> 
	    <iframe class="seo-tag-cloud-preview-iframe" src="" class="widefat" style="display:none;width:100%"></iframe>
	    <script type="text/javascript">
	    var seoTagCloudPreviewUrl = "<?php echo WP_PLUGIN_URL .'/' .dirname(plugin_basename(__FILE__)) ?>/preview.php";
	    </script>
	    <script type="text/javascript" src="<?php echo WP_PLUGIN_URL .'/' .dirname(plugin_basename(__FILE__)) ?>/preview.js"></script>
	</p>
	<p><label for="seo-tag-cloud-title">
	<?php _e('Title', 'seo-tag-cloud') ?>: <input type="text" class="widefat seo-tag-cloud-title" name="seo-tag-cloud-title" value="<?php echo $title ?>" /></label>
	</p>
	<p><label for="seo-tag-cloud-number">
	<?php _e('Number of tags to show', 'seo-tag-cloud') ?>: <input type="text" class="widefat seo-tag-cloud-number" style="width: 25px; text-align: center;" name="seo-tag-cloud-number" value="<?php echo $number ?>" /></label>
	</p>
	<p>
		<?php _e('Text transform', 'seo-tag-cloud') ?>:<br />
		<label for="seo-tag-cloud-text-transform-none" style="text-transform:none"><input type="radio" class="radio seo-tag-cloud-text-transform seo-tag-cloud-text-transform-none" name="seo-tag-cloud-text-transform" value="none"<?php echo 'none' == $text_transform || '' == $text_transform ? ' checked="checked"' : '' ?> /> <?php _e('none', 'seo-tag-cloud') ?></label>
		<label for="seo-tag-cloud-text-transform-uppercase" style="text-transform:uppercase"><input type="radio" class="radio seo-tag-cloud-text-transform seo-tag-cloud-text-transform-uppercase" name="seo-tag-cloud-text-transform" value="uppercase"<?php echo 'uppercase' == $text_transform ? ' checked="checked"' : '' ?> /> <?php _e('uppercase', 'seo-tag-cloud') ?></label>
		<label for="seo-tag-cloud-text-transform-capitalize" style="text-transform:capitalize"><input type="radio" class="radio seo-tag-cloud-text-transform seo-tag-cloud-text-transform-capitalize" name="seo-tag-cloud-text-transform" value="capitalize"<?php echo 'capitalize' == $text_transform ? ' checked="checked"' : '' ?> /> <?php _e('capitalize', 'seo-tag-cloud') ?></label>
		<label for="seo-tag-cloud-text-transform-lowercase" style="text-transform:lowercase"><input type="radio" class="radio seo-tag-cloud-text-transform seo-tag-cloud-text-transform-lowercase" name="seo-tag-cloud-text-transform" value="lowercase"<?php echo 'lowercase' == $text_transform ? ' checked="checked"' : '' ?> /> <?php _e('lowercase', 'seo-tag-cloud') ?></label>
	</p>
	<p>
		<?php _e('Format', 'seo-tag-cloud') ?>:<br />
		<label for="seo-tag-cloud-format-flat"><input type="radio" class="radio seo-tag-cloud-format seo-tag-cloud-format-flat" id="seo-tag-cloud-format-flat" name="seo-tag-cloud-format" value="flat"<?php echo 'flat' == $format || '' == $format ? ' checked="checked"' : '' ?> /> <?php _e('flat', 'seo-tag-cloud') ?></label>
		<label for="seo-tag-cloud-format-ball"><input type="radio" class="radio seo-tag-cloud-format seo-tag-cloud-format-ball" id="seo-tag-cloud-format-ball" name="seo-tag-cloud-format" value="ball"<?php echo 'ball' == $format ? ' checked="checked"' : '' ?> /> <?php _e('ball', 'seo-tag-cloud') ?></label>
	</p>
	<p>
		<?php _e('Size curve', 'seo-tag-cloud') ?>:<br />
		<label for="seo-tag-cloud-size-curve-uniformly-distributed"><input type="radio" class="radio seo-tag-cloud-size-curve seo-tag-cloud-size-curve-uniformly-distributed" id="seo-tag-cloud-size-curve-uniformly-distributed" name="seo-tag-cloud-size-curve" value="uniformly-distributed"<?php echo 'uniformly-distributed' == $size_curve || '' == $size_curve ? ' checked="checked"' : '' ?> /> <?php _e('uniformly-distributed', 'seo-tag-cloud') ?></label>
		<label for="seo-tag-cloud-size-curve-proportional"><input type="radio" class="radio seo-tag-cloud-size-curve seo-tag-cloud-size-curve-proportional" id="seo-tag-cloud-size-curve-proportional" name="seo-tag-cloud-size-curve" value="proportional"<?php echo 'proportional' == $size_curve ? ' checked="checked"' : '' ?> /> <?php _e('proportional', 'seo-tag-cloud') ?></label>
	</p>
	<p><label for="seo-tag-cloud-target">
	<?php _e('Target for links', 'seo-tag-cloud') ?>: <input type="text" class="widefat seo-tag-cloud-target" style="width: 100px;" name="seo-tag-cloud-target" value="<?php echo $target ?>" /></label>
	</p>
	<p>
		<?php _e('How satisfied you are with the plugin?','seo-tag-cloud') ?><br />
		<ul>
			<li>Very much - <a href="https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=MDHEGFZF7ZSY2&lc=IL&item_name=SEO%20Tag%20Cloud%20Wordpress%20Plugin&currency_code=USD&bn=PP%2dDonationsBF%3abtn_donate_LG%2egif%3aNonHosted" target="_blank"><img src="<?php echo WP_PLUGIN_URL.'/'.dirname(plugin_basename(__FILE__)).'/donate.gif'; ?>" alt="<?_e('Donate')?>" style="vertical-align:middle" /></a></li>
			<li>Not that much - <label for="seo-tag-cloud-show-credit" title="<?php echo htmlspecialchars(translate('Display "Powered by SEO Tag Cloud" link', 'seo-tag-cloud')); ?>"><input class="checkbox seo-tag-cloud-show-credit" id="seo-tag-cloud-show-credit" name="seo-tag-cloud-show-credit" type="checkbox" <?php checked( $show_credit, true ); ?> /> <?php _e('Show credit','seo-tag-cloud'); ?></label></li>
		</ul>
	</p>
	<input type="hidden" name="seo-tag-cloud-submit" class="seo-tag-cloud-submit" value="1" />
</div>
<?php
}

function seo_tag_cloud_widget_style($args) {
	$defaults = array(
		'text-transform' => 'none',
		'format' => 'flat',
	);
	$options = get_option('widget_seo_tag_cloud');
	$options = wp_parse_args($options, $defaults);
	$options = wp_parse_args($args, $options);

	if ('ball' == $options['format']) :
	?>
<link rel="stylesheet" href="<?php echo WP_PLUGIN_URL . '/' . dirname(plugin_basename(__FILE__)) ?>/ball/jquery.ball.css" type="text/css" media="screen"/>
	<?php endif;?>
<style type="text/css">
.seo-tag-cloud {font-size: 1.0em; text-transform: <?php echo !empty($options['text-transform']) ? $options['text-transform'] : 'none' ?>;}
.seo-tag-cloud li {display: inline;}
.seo-tag-cloud div {padding: 10px;}
.seo-tag-cloud em {font-style: normal; font-size: 1.1em;}
.seo-tag-cloud a {color: #8b00ff;}
.seo-tag-cloud em a {color: #00a;}
.seo-tag-cloud em em a {color: #00f;}
.seo-tag-cloud em em em a {color: #0a0;}
.seo-tag-cloud em em em em a {color: #0f0;}
.seo-tag-cloud em em em em em a {color: #aa0;}
.seo-tag-cloud em em em em em em a {color: #ff0;}
.seo-tag-cloud em em em em em em em a {color: #aa4a00;}
.seo-tag-cloud em em em em em em em em a {color: #ff7f00;}
.seo-tag-cloud em em em em em em em em em a {color: #a00;}
.seo-tag-cloud em em em em em em em em em em a {color: #f00;}
.credit {font-size: 50%;}
<?php if ('ball' == $options['format']) : ?>
.seo-tag-cloud.ball {visibility:hidden}
#sidebar ul ul.seo-tag-cloud.ball {border:none;list-style-type:none;margin:0}
.seo-tag-cloud.ball a {border:none}
.seo-tag-cloud.ball a:hover {text-decoration: underline;}                                                                                                                                             
<?php endif;?>
</style>
	<?php
}

/**
 * Register all of the default WordPress widgets on startup.
 *
 * Calls 'widgets_init' action after all of the WordPress widgets have been
 * registered.
 *
 * @since 2.2.0
 */
function seo_tag_cloud_widget_register() {
	if ( !is_blog_installed() )
		return;
	$widget_ops = array('classname' => 'seo_tag_cloud_widget', 'description' => __( "Your most used tags in SEO-friendly cloud format", 'seo-tag-cloud') );
	wp_register_sidebar_widget('seo_tag_cloud', __('SEO Tag Cloud', 'seo-tag-cloud'), 'seo_tag_cloud_widget', $widget_ops);
	wp_register_widget_control('seo_tag_cloud', __('SEO Tag Cloud', 'seo-tag-cloud'), 'seo_tag_cloud_widget_control' );
	if ( is_active_widget('seo_tag_cloud_widget') ) {
		$options = get_option('widget_seo_tag_cloud');
		if ('ball' == $options['format']) {
		    wp_enqueue_script('jquery.ball', WP_PLUGIN_URL . '/' . dirname(plugin_basename(__FILE__)) .' /ball/jquery.ball.js', array('jquery'), '0.6.2');
		}
		add_action('wp_head', 'seo_tag_cloud_widget_style');
	}
}

add_action('init', 'seo_tag_cloud_widget_register', 1);
