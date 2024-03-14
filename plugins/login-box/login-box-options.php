<?php

function loginbox_options() {
	// First, get the options from WordPress database
	$options = get_option('loginbox');

	// If Login-box options are empty, get the default values
	if ($options == '') {
		loginbox_set_default_options();
		$options = get_option('loginbox');
	}

	// If a from was sended, update the options
	if ($_POST['submit']) {
		$newoptions['theme'] = strip_tags(stripslashes($_POST['loginbox-theme']));
		$newoptions['key'] = strip_tags(stripslashes($_POST['loginbox-key']));
		$newoptions['ctrl'] = strip_tags(stripslashes($_POST['loginbox-ctrl']));
		$newoptions['backtopage'] = strip_tags(stripslashes($_POST['loginbox-backtopage']));
		$newoptions['fade'] = strip_tags(stripslashes($_POST['loginbox-fade']));

		// Merge new and old options
		// To use a unique database key for LB options and LB widget options
		$newoptions = array_merge($options, $newoptions);
		update_option('loginbox', $newoptions);

		// ...and show a nice message to user
		echo '<div class="updated"><p><strong>'.__('Options saved.').'</strong></p></div>';
	}

    echo '<div class="wrap">';
    echo '<h2>Login-box</h2>';
?>

<script type="text/javascript">
jQuery(function($) {
	/* Making the keyboard */
	function keyboard() {
		var keymap = new Array(
			"Q","W","E","R","T","Y","U","I","O","P","BR",
			"A","S","D","F","G","H","J","K","L","BR",
			"Z","X","C","V","B","N","M"
		);
		$("#loginbox-opt-key input[name='loginbox-key']").parent("label").after("<span id='keyboard' class='tablenav'></span>");
		$("#keyboard").hide();
		$.each(keymap, function(i, n){
			if (n == "BR")
				var html = "<br/>";
			else
				var html = "<input type='button' value='"+n+"' class='key button-secondary'/>";
			$("#keyboard").append(html);
		});
	}
	keyboard();

	/* Keyboard functions */
	function keyboard_open() {
		$("#keyboard").fadeIn();
	}
	function keyboard_close() {
		$("#keyboard").fadeOut();
	}

	$("#loginbox-opt-key input[name='loginbox-key']").focus(function() {
		keyboard_open();
	});
	$("#loginbox-opt-key input[name='loginbox-key']").blur(function() {
		keyboard_close();
	});
	$("#loginbox-opt-key input[name='loginbox-key']").change(function() {
		var l = $(this).val();
		$("#loginbox-ctrl-key").html(l);
	});

	$("#keyboard input").click(function() {
		var l = $(this).val();
		$("#loginbox-opt-key input[name='loginbox-key']").val(l);
		$("#loginbox-ctrl-key").html(l);
		keyboard_close();
	});

	/* Keyboard styles (Yeap, I don't want use a CSS file) */
	function keyboard_style() {
		$("#keyboard").css("height", "auto");

		/* Don't ask me why, I only know that the correct height is the original height plus the Answer to Life, the Universe, and Everything */
		var h = $("#keyboard").height() + 42;

		$("#loginbox-opt-key").css({
			"position": "relative"
		});
		$("#keyboard").css({
			"position": "absolute",
			"top": "-"+h+"px",
			"cursor": "default",
			"margin": "0 4px",
			"padding": "8px 10px",
			"text-align": "center",
			"border": "1px solid #eee",
			"-moz-border-radius": "5px",
			"-khtml-border-radius": "5px",
			"-webkit-border-radius": "5px",
			"border-radius": "5px"
		});
		$("#keyboard input").css({
			"width": "28px",
			"padding": "4px 0",
			"margin": "2px",
			"-moz-border-radius": "5px",
			"-khtml-border-radius": "5px",
			"-webkit-border-radius": "5px",
			"border-radius": "5px"
		});
	}

	/* Now, run the functions! */
	keyboard_style();

	/* Screenshot block */
	function preview() {
		$("#loginbox-form").prepend("<div id='loginbox-theme-preview'></div>");
		$("#loginbox-theme-preview").append("<img src='<?php echo get_bloginfo('wpurl')."/wp-content/plugins/login-box/".$options['theme']."/screenshot.png"; ?>' alt='<?php echo $options['theme']; ?>'/>");
		$("#loginbox-theme-preview").css({
			"width": "200px",
			"height": "200px",
			"margin": "10px",
			"padding": "2px",
			"float": "left"
		});
		$("#loginbox-options").css("margin-left", "224px");
	}
	preview();

	$("input[name='loginbox-theme']").click(function() {
		tname = $(this).val();
		tsrc  = "<?php echo get_bloginfo('wpurl'); ?>";
		tsrc += "/wp-content/plugins/login-box/";
		tsrc += tname;
		tsrc += "/screenshot.png";

		$("#loginbox-theme-preview img").attr("src", tsrc);
		$("#loginbox-theme-preview img").attr("alt", tname);
	});

	$("hr").css({
		"border-color": $(".wrap h2").css("border-color"),
		"border-style": $(".wrap h2").css("border-style"),
		"border-size": "1px 0 0 0"
	});

	$("#loginbox-donate").css("text-align", "center");
});
</script>

<form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>" id="loginbox-form">

<div id="loginbox-options">
<p>
<strong><?php _e('Login-box theme', 'login-box'); ?></strong>

<?php
function loginbox_get_themes() {
	$options = get_option('loginbox');
	$olddir = getcwd();
	chdir(ABSPATH.'/wp-content/plugins/login-box');
	if ($dh = opendir('.')) {
		while (($file = readdir($dh)) !== false) {
			if (is_file($file.'/style.css')) { ?>
				<br/>
				<label>
				<input type="radio" name="loginbox-theme" value="<?php echo $file; ?>"
				<?php if ($options['theme'] == $file) echo 'checked="checked"'; ?>
				>
				<?php echo $file; ?>
				</label>
				<?php
			}
		}
	closedir($dh);
	}
	chdir($olddir);
}

loginbox_get_themes();
?>
</p>

<p id="loginbox-opt-key">
<label><?php _e('Open with <strong>Alt</strong> +', 'login-box'); ?> 
<input type="text" name="loginbox-key" value="<?php echo $options['key']; ?>" size="1">
</label>

<br/>
<label>
<input type="checkbox" name="loginbox-ctrl" value="1" <?php if ($options['ctrl']) echo 'checked="checked"'; ?>>
<?php printf(__('Also open with <strong>Ctrl</strong> + <span id="loginbox-ctrl-key">%s</span>', 'login-box'), $options['key']); ?>
</label>
</p>

<p>
<strong><?php _e('When login', 'login-box'); ?></strong>,

<br/>
<label>
<input type="radio" name="loginbox-backtopage" value="1" <?php if ($options['backtopage']) echo 'checked="checked"'; ?>>
<?php _e('Back to page', 'login-box'); ?>
</label>

<br/>
<label>
<input type="radio" name="loginbox-backtopage" value="0" <?php if ($options['backtopage'] == 0) echo 'checked="checked"'; ?>>
<?php _e('Go to Dashboard', 'login-box'); ?>
</label>
</p>

<p>
<label>
<input type="checkbox" name="loginbox-fade" value="1" <?php if ($options['fade']) echo 'checked="checked"'; ?>>
<?php _e('Use <strong>fadeIn/fadeOut</strong> effects', 'login-box'); ?>
</label>
</p>
</div>

<p class="submit">
<input type="submit" id="submit" name="submit" value="<?php _e('Update Options »', 'login-box') ?>" />
</p>

</form>

<hr/>

<form id="loginbox-donate" method="post" action="https://www.paypal.com/cgi-bin/webscr">
<input type="hidden" name="cmd" value="_donations">
<input type="hidden" name="business" value="mdanillo@gmail.com">
<input type="hidden" name="item_name" value="Contribute with Login-box development">
<input type="hidden" name="no_shipping" value="0">
<input type="hidden" name="no_note" value="1">
<input type="hidden" name="currency_code" value="USD">
<input type="hidden" name="tax" value="0">
<input type="hidden" name="lc" value="BR">
<input type="hidden" name="bn" value="PP-DonationsBF">
<input type="image" src="https://www.paypal.com/en_US/i/btn/btn_donate_LG.gif" border="0" name="submit" title="Make a donation to Login-box!" alt="Donate with PayPal" style="vertical-align: bottom">
<span style="font-size: 2em">US$ </span><input type="text" name="amount" value="5" style="font-size: 2em; border: none; padding: 0;" size="2">
</form>

</form>
</div>

<?php
}

// Add the function above as a new page in WordPress panel
function loginbox_add_page() {
	add_submenu_page('themes.php', 'Login-box', 'Login-box', 'edit_themes', 'login-box', 'loginbox_options');
}

// Function to set default options and update in database
function loginbox_set_default_options() {
	$options['theme']      = 'wp25';
	$options['key']        = 'E';
	$options['ctrl']       = '1';
	$options['backtopage'] = '1';
	$options['fade']       = '1';

	update_option('loginbox', $options);
}

// Now, defines the options as constants, to be used by Login-box core
function loginbox_set_options() {
	$options = get_option('loginbox');

	if (!defined('LB_THEME'))      define("LB_THEME", $options['theme']);
	if (!defined('LB_KEY'))        define("LB_KEY", $options['key']);
	if (!defined('LB_FADE'))      define("LB_FADE", $options['fade']);
	if (!defined('LB_CTRL'))       define("LB_CTRL", $options['ctrl']);
	if (!defined('LB_BACKTOPAGE')) define("LB_BACKTOPAGE", $options['backtopage']);
	if (!defined('LB_AUTO'))       define("LB_AUTO", true);
}

loginbox_set_options();
?>
