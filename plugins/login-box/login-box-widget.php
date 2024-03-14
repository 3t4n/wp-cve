<?php
/*
Based on My Widget by Kaf Oseo (http://szub.net)

My Widget is released under the GNU General Public License (GPL)
http://www.gnu.org/licenses/gpl.txt

This is a WordPress plugin (http://wordpress.org) and widget
(http://automattic.com/code/widgets/).
*/

function loginbox_widget_init() {

	if (!function_exists('register_sidebar_widget')) return;

	function loginbox_widget($args) {
		extract($args);
		$options = get_option('loginbox');
		$title = empty($options['widget_title']) ? 'Login-box' : $options['widget_title'];

		if (!is_user_logged_in()) {
			echo $before_widget;
			echo $before_title . $title . $after_title;
			echo '<ul>';
			echo '<li><a href="';
			bloginfo('wpurl');
			echo '/wp-login.php';
			if (LB_BACKTOPAGE) echo '?redirect_to='.$_SERVER['REQUEST_URI'].'" ';
			echo ' title="'.get_bloginfo('name').' - '.__('Login').'" rel="loginbox-toggle">'.__('Login').'</a>';
			echo '';
			loginbox();
			echo '</li>';
			if ($options['widget_register']) {
				echo '<li><a href="';
				bloginfo('wpurl');
				echo '/wp-login.php?action=register" title="'.__('Register').'">'.__('Register').'</a></li>';
			}
			echo '</ul>';
			echo $after_widget;
		}
	}

	function loginbox_widget_control() {
		$options = get_option('loginbox');
		if ($_POST['loginbox-submit']) {
			$newoptions['widget_title'] = strip_tags(stripslashes($_POST['loginbox-title']));
			$newoptions['widget_register'] = strip_tags(stripslashes($_POST['loginbox-register']));

			$newoptions = array_merge($options, $newoptions);
			update_option('loginbox', $newoptions);
		}

	$title = htmlspecialchars($options['widget_title'], ENT_QUOTES);
	$register = htmlspecialchars($options['widget_register'], ENT_QUOTES);
?>
        <div>
        <label for="loginbox-title">
		<?php _e('Title'); ?>:
		<input class="widefat" type="text" id="loginbox-title" name="loginbox-title" value="<?php echo $title; ?>" />
	</label>
	<br/>
        <label for="loginbox-register">
		<input class="checkbox" type="checkbox" id="loginbox-register" name="loginbox-register" value="1" <?php if ($register) echo 'checked="checked"'; ?> />
		<?php _e('Show "Register" link', 'login-box'); ?>
	</label>

        <input type="hidden" name="loginbox-submit" id="loginbox-submit" value="1" />
        </div>
<?php
	}

	register_sidebar_widget('Login-box', 'loginbox_widget');
	register_widget_control('Login-box', 'loginbox_widget_control');
}

add_action('plugins_loaded', 'loginbox_widget_init');
?>