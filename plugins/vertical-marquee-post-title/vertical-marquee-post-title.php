<?php
/*
Plugin Name: Vertical marquee post title
Description: It will create the vertical marquee effect in your website, if you want your post title to move vertically (scroll upward or downwards) in the screen use this plug-in.
Author: Gopi Ramasamy
Version: 4.0
Plugin URI: http://www.gopiplus.com/work/2012/09/02/vertical-marquee-post-title-wordpress-plugin/
Author URI: http://www.gopiplus.com/work/2012/09/02/vertical-marquee-post-title-wordpress-plugin/
Donate link: http://www.gopiplus.com/work/2012/09/02/vertical-marquee-post-title-wordpress-plugin/
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Text Domain: vertical-marquee-post-title
Domain Path: /languages
*/

function vmptshow()
{
	$vmpt_setting = get_option('vmpt_setting');
	$array = array("setting" => $vmpt_setting);
	echo vmpt_shortcode($array);	
}

function vmpt_shortcode( $atts ) 
{
	global $wpdb;
	$vmpt_marquee = "";
	$vmpt = "";
	$link = "";
	
	//[vmpt setting="1"]
	if ( ! is_array( $atts ) )	{ return ''; }
	$setting = $atts['setting'];
	switch ($setting) 
	{ 
		case 1: 
			$vmpt_setting = get_option('vmpt_setting1');
			break;
		case 2: 
			$vmpt_setting = get_option('vmpt_setting2');
			break;
		case 3: 
			$vmpt_setting = get_option('vmpt_setting3');
			break;
		case 4: 
			$vmpt_setting = get_option('vmpt_setting4');
			break;
		default:
			$vmpt_setting = get_option('vmpt_setting1');
	}
	
	@list($vmpt_scrollamount, $vmpt_scrolldelay, $vmpt_direction, $vmpt_style, $vmpt_noofpost, $vmpt_categories, $vmpt_orderbys, $vmpt_order, $vmpt_spliter) = explode("~~", @$vmpt_setting);
	
	if(!is_numeric($vmpt_scrollamount)){ $vmpt_scrollamount = 2; } 
	if(!is_numeric($vmpt_scrolldelay)){ $vmpt_scrolldelay = 5; } 
	if(!is_numeric($vmpt_noofpost)){ $vmpt_noofpost = 10; }
	
	$sSql = query_posts('cat='.$vmpt_categories.'&orderby='.$vmpt_orderbys.'&order='.$vmpt_order.'&showposts='.$vmpt_noofpost);
	
	if ( ! empty($sSql) ) 
	{
		$count = 0;
		foreach ( $sSql as $sSql ) 
		{
			$title = stripslashes($sSql->post_title);
			$link = get_permalink($sSql->ID);
			if($count==0) 
			{  
				if($link != "") { $vmpt = $vmpt . "<a target='' href='".$link."'>"; } 
				$vmpt = $vmpt . $title;
				if($link != "") { $vmpt = $vmpt . "</a>"; }
			}
			else
			{
				$vmpt = $vmpt . "   <br /><br />   ";
				if($link != "") { $vmpt = $vmpt . "<a target='' href='".$link."'>"; } 
				$vmpt = $vmpt . $title;
				if($link != "") { $vmpt = $vmpt . "</a>"; }
			}
			$count = $count + 1;
		}
	}
	else
	{
		$vmpt = __('No records found.', 'vertical-marquee-post-title');
	}
	wp_reset_query();
	$vmpt_marquee = $vmpt_marquee . "<div style='padding:3px;' class='vmpt_marquee'>";
	$vmpt_marquee = $vmpt_marquee . "<marquee style='$vmpt_style' scrollamount='$vmpt_scrollamount' scrolldelay='$vmpt_scrolldelay' direction='$vmpt_direction' onmouseover='this.stop()' onmouseout='this.start()'>";
	$vmpt_marquee = $vmpt_marquee . $vmpt;
	$vmpt_marquee = $vmpt_marquee . "</marquee>";
	$vmpt_marquee = $vmpt_marquee . "</div>";
	return $vmpt_marquee;	
}

function vmpt_install() 
{
	add_option('vmpt_title', "Marquee post title");
	add_option('vmpt_setting', "1");
	add_option('vmpt_setting1', "2~~5~~up~~height:100px;~~10~~~~ID~~DESC");
	add_option('vmpt_setting2', "2~~5~~up~~color:#FF0000;font:Arial;height:100px;~~10~~~~ID~~DESC");
	add_option('vmpt_setting3', "2~~5~~down~~color:#FF0000;font:Arial;height:120px;~~10~~~~title~~DESC");
	add_option('vmpt_setting4', "2~~5~~down~~color:#FF0000;font:Arial;height:140px;~~10~~~~rand~~DESC");
}
	
function vmpt_deactivation() 
{
	// No action required.
}

function vmpt_option() 
{
?>
<div class="wrap">
  <div class="form-wrap">
    <div id="icon-edit" class="icon32 icon32-posts-post"><br>
    </div>
	<h2><?php _e('Vertical marquee post title', 'vertical-marquee-post-title'); ?></h2>
    <?php
	global $wpdb;
	
	$vmpt_setting1 = get_option('vmpt_setting1');
	$vmpt_setting2 = get_option('vmpt_setting2');
	$vmpt_setting3 = get_option('vmpt_setting3');
	$vmpt_setting4 = get_option('vmpt_setting4');
	
	list($a1, $b1, $c1, $d1, $e1, $f1, $g1, $h1) = explode("~~", $vmpt_setting1);
	list($a2, $b2, $c2, $d2, $e2, $f2, $g2, $h2) = explode("~~", $vmpt_setting2);
	list($a3, $b3, $c3, $d3, $e3, $f3, $g3, $h3) = explode("~~", $vmpt_setting3);
	list($a4, $b4, $c4, $d4, $e4, $f4, $g4, $h4) = explode("~~", $vmpt_setting4);
	
	if (isset($_POST['vmpt_submit'])) 
	{	
		check_admin_referer('vmpt_form_setting');
		
		$a1 = stripslashes(sanitize_text_field($_POST['vmpt_scrollamount1']));
		$b1 = stripslashes(sanitize_text_field($_POST['vmpt_scrolldelay1']));
		$c1 = stripslashes(sanitize_text_field($_POST['vmpt_direction1']));
		$d1 = stripslashes(sanitize_text_field($_POST['vmpt_style1']));
		$e1 = stripslashes(sanitize_text_field($_POST['vmpt_noofpost1']));
		$f1 = stripslashes(sanitize_text_field($_POST['vmpt_categories1']));
		$g1 = stripslashes(sanitize_text_field($_POST['vmpt_orderbys1']));
		$h1 = stripslashes(sanitize_text_field($_POST['vmpt_order1']));
		
		$a2 = stripslashes(sanitize_text_field($_POST['vmpt_scrollamount2']));
		$b2 = stripslashes(sanitize_text_field($_POST['vmpt_scrolldelay2']));
		$c2 = stripslashes(sanitize_text_field($_POST['vmpt_direction2']));
		$d2 = stripslashes(sanitize_text_field($_POST['vmpt_style2']));
		$e2 = stripslashes(sanitize_text_field($_POST['vmpt_noofpost2']));
		$f2 = stripslashes(sanitize_text_field($_POST['vmpt_categories2']));
		$g2 = stripslashes(sanitize_text_field($_POST['vmpt_orderbys2']));
		$h2 = stripslashes(sanitize_text_field($_POST['vmpt_order2']));
		
		$a3 = stripslashes(sanitize_text_field($_POST['vmpt_scrollamount3']));
		$b3 = stripslashes(sanitize_text_field($_POST['vmpt_scrolldelay3']));
		$c3 = stripslashes(sanitize_text_field($_POST['vmpt_direction3']));
		$d3 = stripslashes(sanitize_text_field($_POST['vmpt_style3']));
		$e3 = stripslashes(sanitize_text_field($_POST['vmpt_noofpost3']));
		$f3 = stripslashes(sanitize_text_field($_POST['vmpt_categories3']));
		$g3 = stripslashes(sanitize_text_field($_POST['vmpt_orderbys3']));
		$h3 = stripslashes(sanitize_text_field($_POST['vmpt_order3']));
		
		$a4 = stripslashes(sanitize_text_field($_POST['vmpt_scrollamount4']));
		$b4 = stripslashes(sanitize_text_field($_POST['vmpt_scrolldelay4']));
		$c4 = stripslashes(sanitize_text_field($_POST['vmpt_direction4']));
		$d4 = stripslashes(sanitize_text_field($_POST['vmpt_style4']));
		$e4 = stripslashes(sanitize_text_field($_POST['vmpt_noofpost4']));
		$f4 = stripslashes(sanitize_text_field($_POST['vmpt_categories4']));
		$g4 = stripslashes(sanitize_text_field($_POST['vmpt_orderbys4']));
		$h4 = stripslashes(sanitize_text_field($_POST['vmpt_order4']));	
		
		update_option('vmpt_setting1', @$a1 . "~~" . @$b1 . "~~" . @$c1 . "~~" . @$d1 . "~~" . @$e1 . "~~" . @$f1 . "~~" . @$g1 . "~~" . @$h1 . "~~" . @$i1 );
		update_option('vmpt_setting2', @$a2 . "~~" . @$b2 . "~~" . @$c2 . "~~" . @$d2 . "~~" . @$e2 . "~~" . @$f2 . "~~" . @$g2 . "~~" . @$h2 . "~~" . @$i2 );
		update_option('vmpt_setting3', @$a3 . "~~" . @$b3 . "~~" . @$c3 . "~~" . @$d3 . "~~" . @$e3 . "~~" . @$f3 . "~~" . @$g3 . "~~" . @$h3 . "~~" . @$i3 );
		update_option('vmpt_setting4', @$a4 . "~~" . @$b4 . "~~" . @$c4 . "~~" . @$d4 . "~~" . @$e4 . "~~" . @$f4 . "~~" . @$g4 . "~~" . @$h4 . "~~" . @$i4 );
		
		?>
		<div class="updated fade">
			<p><strong><?php _e('Details successfully updated.', 'vertical-marquee-post-title'); ?></strong></p>
		</div>
		<?php
	}
	
	echo '<form name="vmpt_form" method="post" action="">';
	?>
    <table width="800" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td><?php
		echo '<h3>'.__('Setting 1', 'vertical-marquee-post-title').'</h3>';
		
		echo '<p>'.__('Scroll amount :', 'vertical-marquee-post-title').'<br><input  style="width: 100px;" type="text" value="';
		echo $a1 . '" name="vmpt_scrollamount1" id="vmpt_scrollamount1" /></p>';
		
		echo '<p>'.__('Scroll delay :', 'vertical-marquee-post-title').'<br><input  style="width: 100px;" type="text" value="';
		echo $b1 . '" name="vmpt_scrolldelay1" id="vmpt_scrolldelay1" /></p>';
		
		echo '<p>'.__('Scroll direction :', 'vertical-marquee-post-title').'<br><input  style="width: 100px;" type="text" value="';
		echo $c1 . '" name="vmpt_direction1" id="vmpt_direction1" /> '.__('(Up / Down)', 'vertical-marquee-post-title').'</p>';
		
		echo '<p>'.__('Scroll style :', 'vertical-marquee-post-title').'<br><input  style="width: 250px;" type="text" value="';
		echo $d1 . '" name="vmpt_style1" id="vmpt_style1" /></p>';
	
		echo '<p>'.__('Number of post :', 'vertical-marquee-post-title').'<br><input  style="width: 100px;" type="text" value="';
		echo $e1 . '" name="vmpt_noofpost1" id="vmpt_noofpost1" /></p>';
		
		echo '<p>'.__('Post categories :', 'vertical-marquee-post-title').'<br><input  style="width: 200px;" type="text" value="';
		echo $f1 . '" name="vmpt_categories1" id="vmpt_categories1" /> (Example: 1, 3, 4) <br> '.__('Category IDs, separated by commas.', 'vertical-marquee-post-title').'</p>';
		
		echo '<p>'.__('Post orderbys :', 'vertical-marquee-post-title').'<br><input  style="width: 200px;" type="text" value="';
		echo $g1 . '" name="vmpt_orderbys1" id="vmpt_orderbys1" />  '.__('(Any 1 from below list)', 'vertical-marquee-post-title').' <br> ID / author / title / rand / date / category / modified</p>';
		
		echo '<p>'.__('Post order :', 'vertical-marquee-post-title').'<br><input  style="width: 100px;" type="text" value="';
		echo $h1 . '" name="vmpt_order1" id="vmpt_order1" /> ASC/DESC </p>';
		?>
        </td>
        <td><?php
		echo '<h3>'.__('Setting 2', 'vertical-marquee-post-title').'</h3>';
		
		echo '<p>'.__('Scroll amount :', 'vertical-marquee-post-title').'<br><input  style="width: 100px;" type="text" value="';
		echo $a2 . '" name="vmpt_scrollamount2" id="vmpt_scrollamount2" /></p>';
		
		echo '<p>'.__('Scroll delay :', 'vertical-marquee-post-title').'<br><input  style="width: 100px;" type="text" value="';
		echo $b2 . '" name="vmpt_scrolldelay2" id="vmpt_scrolldelay2" /></p>';
		
		echo '<p>'.__('Scroll direction :', 'vertical-marquee-post-title').'<br><input  style="width: 100px;" type="text" value="';
		echo $c2 . '" name="vmpt_direction2" id="vmpt_direction2" /> '.__('(Up / Down)', 'vertical-marquee-post-title').'</p>';
		
		echo '<p>'.__('Scroll style :', 'vertical-marquee-post-title').'<br><input  style="width: 250px;" type="text" value="';
		echo $d2 . '" name="vmpt_style2" id="vmpt_style2" /></p>';
	
		echo '<p>'.__('Number of post :', 'vertical-marquee-post-title').'<br><input  style="width: 100px;" type="text" value="';
		echo $e2 . '" name="vmpt_noofpost2" id="vmpt_noofpost2" /></p>';
		
		echo '<p>'.__('Post categories :', 'vertical-marquee-post-title').'<br><input  style="width: 200px;" type="text" value="';
		echo $f2 . '" name="vmpt_categories2" id="vmpt_categories2" /> (Example: 1, 3, 4) <br> '.__('Category IDs, separated by commas.', 'vertical-marquee-post-title').'</p>';
		
		echo '<p>'.__('Post orderbys :', 'vertical-marquee-post-title').'<br><input  style="width: 200px;" type="text" value="';
		echo $g2 . '" name="vmpt_orderbys2" id="vmpt_orderbys2" /> '.__('(Any 1 from below list)', 'vertical-marquee-post-title').' <br> ID / author / title / rand / date / category / modified</p>';
		
		echo '<p>'.__('Post order :', 'vertical-marquee-post-title').'<br><input  style="width: 100px;" type="text" value="';
		echo $h2 . '" name="vmpt_order2" id="vmpt_order2" /> ASC/DESC </p>';
		?>
        </td>
      </tr>
      <tr>
        <td><?php
		echo '<h3>'.__('Setting 3', 'vertical-marquee-post-title').'</h3>';
		
		echo '<p>'.__('Scroll amount :', 'vertical-marquee-post-title').'<br><input  style="width: 100px;" type="text" value="';
		echo $a3 . '" name="vmpt_scrollamount3" id="vmpt_scrollamount3" /></p>';
		
		echo '<p>'.__('Scroll delay :', 'vertical-marquee-post-title').'<br><input  style="width: 100px;" type="text" value="';
		echo $b3 . '" name="vmpt_scrolldelay3" id="vmpt_scrolldelay3" /></p>';
		
		echo '<p>'.__('Scroll direction :', 'vertical-marquee-post-title').'<br><input  style="width: 100px;" type="text" value="';
		echo $c3 . '" name="vmpt_direction3" id="vmpt_direction3" /> '.__('(Up / Down)', 'vertical-marquee-post-title').'</p>';
		
		echo '<p>'.__('Scroll style :', 'vertical-marquee-post-title').'<br><input  style="width: 250px;" type="text" value="';
		echo $d3 . '" name="vmpt_style3" id="vmpt_style3" /></p>';
		
		echo '<p>'.__('Number of post :', 'vertical-marquee-post-title').'<br><input  style="width: 100px;" type="text" value="';
		echo $e3 . '" name="vmpt_noofpost3" id="vmpt_noofpost3" /></p>';
		
		echo '<p>'.__('Post categories :', 'vertical-marquee-post-title').'<br><input  style="width: 200px;" type="text" value="';
		echo $f3 . '" name="vmpt_categories3" id="vmpt_categories3" /> (Example: 1, 3, 4) <br> '.__('Category IDs, separated by commas.', 'vertical-marquee-post-title').'</p>';
		
		echo '<p>'.__('Post orderbys :', 'vertical-marquee-post-title').'<br><input  style="width: 200px;" type="text" value="';
		echo $g3 . '" name="vmpt_orderbys3" id="vmpt_orderbys3" /> '.__('(Any 1 from below list)', 'vertical-marquee-post-title').' <br> ID / author / title / rand / date / category / modified</p>';
		
		echo '<p>'.__('Post order :', 'vertical-marquee-post-title').'<br><input  style="width: 100px;" type="text" value="';
		echo $h3 . '" name="vmpt_order3" id="vmpt_order3" /> ASC/DESC </p>';
		?>
        </td>
        <td><?php
		echo '<h3>'.__('Setting 4', 'vertical-marquee-post-title').'</h3>';
		
		echo '<p>'.__('Scroll amount :', 'vertical-marquee-post-title').'<br><input  style="width: 100px;" type="text" value="';
		echo $a4 . '" name="vmpt_scrollamount4" id="vmpt_scrollamount4" /></p>';
		
		echo '<p>'.__('Scroll delay :', 'vertical-marquee-post-title').'<br><input  style="width: 100px;" type="text" value="';
		echo $b4 . '" name="vmpt_scrolldelay4" id="vmpt_scrolldelay4" /></p>';
		
		echo '<p>'.__('Scroll direction :', 'vertical-marquee-post-title').'<br><input  style="width: 100px;" type="text" value="';
		echo $c4 . '" name="vmpt_direction4" id="vmpt_direction4" /> '.__('(Up / Down)', 'vertical-marquee-post-title').'</p>';
		
		echo '<p>'.__('Scroll style :', 'vertical-marquee-post-title').'<br><input  style="width: 250px;" type="text" value="';
		echo $d4 . '" name="vmpt_style4" id="vmpt_style4" /></p>';
		
		echo '<p>'.__('Number of post :', 'vertical-marquee-post-title').'<br><input  style="width: 100px;" type="text" value="';
		echo $e4 . '" name="vmpt_noofpost4" id="vmpt_noofpost4" /></p>';
		
		echo '<p>'.__('Post categories :', 'vertical-marquee-post-title').'<br><input  style="width: 200px;" type="text" value="';
		echo $f4 . '" name="vmpt_categories4" id="vmpt_categories4" /> (Example: 1, 3, 4) <br> '.__('Category IDs, separated by commas.', 'vertical-marquee-post-title').'</p>';
		
		echo '<p>'.__('Post orderbys :', 'vertical-marquee-post-title').'<br><input  style="width: 200px;" type="text" value="';
		echo $g4 . '" name="vmpt_orderbys4" id="vmpt_orderbys4" /> '.__('(Any 1 from below list)', 'vertical-marquee-post-title').' <br> ID / author / title / rand / date / category / modified</p>';
		
		echo '<p>'.__('Post order :', 'vertical-marquee-post-title').'<br><input  style="width: 100px;" type="text" value="';
		echo $h4 . '" name="vmpt_order4" id="vmpt_order4" /> ASC/DESC </p>';
		?>
        </td>
      </tr>
    </table>
	<br />
	<input name="vmpt_submit" id="vmpt_submit" lang="publish" class="button-primary" value="<?php _e('Update', 'vertical-marquee-post-title'); ?>" type="Submit" />
	<?php wp_nonce_field('vmpt_form_setting'); ?>
	<a target="_blank" href="http://www.gopiplus.com/work/2012/09/02/vertical-marquee-post-title-wordpress-plugin/">
		<input class="button-primary" type="button" value="<?php _e('Short Code', 'wp-anything-slider'); ?>" />
	</a>
	</form>
	<br />
    <p class="description"><?php _e('Check plugin official website for more information ', 'vertical-marquee-post-title'); ?>
	<a href="http://www.gopiplus.com/work/2012/09/02/vertical-marquee-post-title-wordpress-plugin/" target="_blank"><?php _e('Click here', 'vertical-marquee-post-title'); ?></a></p>
  </div>
</div>
<?php
}

function vmpt_add_to_menu() 
{
	add_options_page( __('Vertical marquee post title', 'vertical-marquee-post-title'), 
							__('Vertical marquee post title', 'vertical-marquee-post-title'), 'manage_options', __FILE__, 'vmpt_option' );
}

if (is_admin()) 
{
	add_action('admin_menu', 'vmpt_add_to_menu');
}

class vmpt_widget_register extends WP_Widget 
{
	function __construct() 
	{
		$widget_ops = array('classname' => 'widget_text newsticker-widget', 'description' => __('Vertical marquee post title', 'vertical-marquee-post-title'), 'vertical-marquee-post-title');
		parent::__construct('vertical-marquee-post-title', __('Vertical marquee post title', 'vertical-marquee-post-title'), $widget_ops);
	}
	
	function widget( $args, $instance ) 
	{
		extract( $args, EXTR_SKIP );

		$title 				= apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );
		$vmpt_setting		= $instance['vmpt_setting'];

		echo $args['before_widget'];
		if ( ! empty( $title ) )
		{
			echo $args['before_title'] . $title . $args['after_title'];
		}
		// Call widget method
		$arr = array();
		$arr["setting"] 	= $vmpt_setting;
		echo vmpt_shortcode($arr);
		
		// Call widget method
		echo $args['after_widget'];
	}
	
	function update( $new_instance, $old_instance ) 
	{
		$instance 						= $old_instance;
		$instance['title'] 				= ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['vmpt_setting'] 		= ( ! empty( $new_instance['vmpt_setting'] ) ) ? strip_tags( $new_instance['vmpt_setting'] ) : '';
		return $instance;
	}

	function form( $instance ) 
	{
		$defaults = array(
			'title' 		=> '',
			'vmpt_setting' 	=> ''
        );
		
		$instance 			= wp_parse_args( (array) $instance, $defaults);
        $title 				= $instance['title'];
		$vmpt_setting 		= $instance['vmpt_setting'];
		
		?>
		<p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Widget title', 'vertical-marquee-post-title'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
        </p>
		<p>
            <label for="<?php echo $this->get_field_id('vmpt_setting'); ?>"><?php _e('Setting', 'vertical-marquee-post-title'); ?></label>
			<select class="widefat" id="<?php echo $this->get_field_id('vmpt_setting'); ?>" name="<?php echo $this->get_field_name('vmpt_setting'); ?>">
				<option value="1" <?php $this->vmpt_render_selected($vmpt_setting=='1'); ?>>Setting 1</option>
				<option value="2" <?php $this->vmpt_render_selected($vmpt_setting=='2'); ?>>Setting 2</option>
				<option value="3" <?php $this->vmpt_render_selected($vmpt_setting=='3'); ?>>Setting 3</option>
				<option value="4" <?php $this->vmpt_render_selected($vmpt_setting=='4'); ?>>Setting 4</option>
			</select>
        </p>
		<p>
			<?php _e('Check official website for more information', 'vertical-marquee-post-title'); ?>
			<a target="_blank" href="http://www.gopiplus.com/work/2012/09/02/vertical-marquee-post-title-wordpress-plugin/"><?php _e('click here', 'vertical-marquee-post-title'); ?></a>
		</p>
		<?php
	}

	function vmpt_render_selected($var) 
	{
		if ($var==1 || $var==true) 
		{
			echo 'selected="selected"';
		}
	}
}

function vmpt_widget_loading()
{
	register_widget( 'vmpt_widget_register' );
}

function vmpt_textdomain() 
{
	  load_plugin_textdomain( 'vertical-marquee-post-title', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}

add_action('plugins_loaded', 'vmpt_textdomain');
register_activation_hook(__FILE__, 'vmpt_install');
register_deactivation_hook(__FILE__, 'vmpt_deactivation');
add_action( 'widgets_init', 'vmpt_widget_loading');
add_shortcode( 'vmpt', 'vmpt_shortcode' );
?>