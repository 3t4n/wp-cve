<?php
// included into kwplite.php > class kwplite > function adminPage

$vars_to_save = array();
?>

	<div class="wrap" id="<?php echo self::$abbr; ?>">

		<h2><?php echo self::$full_name; ?></h2>

		<p>✔ <?php _e('Check to hide.') ?></p>

		<form method="post" action="options.php">

<nav role="navigation" class="tabs">
	<a href="#col_menu_items" class="active">Menu items</a>
	<?php 
	$i = 0;
	foreach (self::$selectors as $s_group_name => $s_group_data) {
		$i++;
		if (empty($s_group_data)) continue; 
		echo '<a href="#col_'.$i.'">'.$s_group_name.'</a>'; 
	} ?>
	
	<a href="#col_custom_css">Custom CSS</a>
	<div style="clear:both"></div>
</nav>

<?php
// ============================================================
// Menu
?>

		<div class="col" id="col_menu_items">
		<h3>Menu items</h3>
		<ul class="col-content tall">
		<?php
		$vars_to_save[] = 'menuitems';

		// restructure $menu
		$menu = self::$remember['menu'];
		$submenu = self::$remember['submenu'];

		foreach ($menu as $menuitem) { 

			// url 
			if (false !== strpos($menuitem[2], '/'))
			 	$url = get_bloginfo('wpurl').'/wp-admin/admin.php?page='.$menuitem[2];
			else 
			  	$url = $menuitem[2]; ?>

			<li><label>
				<input type="checkbox" name="<?php echo self::$abbr . '_menuitems[1'.md5($menuitem[2]).']'; ?>"
				<?php if (isset(self::$settings['menuitems']['1'.md5($menuitem[2])])) echo 'checked="checked"'; ?> />

				<strong><?php 
					if ($menuitem[0])
						echo $menuitem[0] . '&nbsp;<a href="'. $url . '">&rarr;</a>';
					else 
						echo '— <em>separator</em> —' ?></strong>
			</label>

			<?php

			if (array_key_exists($menuitem[2], $submenu)) {
				echo '<ul>';
				foreach ($submenu[$menuitem[2]] as $menuitem) {
					
					// url 
					if (false !== strpos($menuitem[2], '/'))
					 	$url = get_bloginfo('wpurl').'/wp-admin/admin.php?page='.$menuitem[2];
					else 
					  	$url = $menuitem[2]; ?>

			<li><label>
				<input type="checkbox" name="<?php echo self::$abbr . '_menuitems[2'.md5($menuitem[2]).']'; ?>"
				<?php if (isset(self::$settings['menuitems']['2'.md5($menuitem[2])])) echo 'checked="checked"'; ?> />
				<?php echo $menuitem[0] ?>&nbsp;<a href="<?php echo $url ?>">&rarr;</a>
			</label>

					<?php
				}
				echo '</ul>';

			}
			echo '</li>';

		} ?>
		</ul>
		</div>

<?php
// ============================================================
// Selector-based lists
// go trough self::$settings[elements_to_hide] and echo identifiers from self::$selectors[...][...]

$vars_to_save[] = 'elements_to_hide';
$i = 0;
foreach (self::$selectors as $s_group_name => $s_group_data) {
	$i++;
	if (empty($s_group_data)) continue; ?>

	<div class="col <?php echo $s_group_name ?>" id="col_<?php echo $i ?>">
	<h3><?php echo $s_group_name ?></h3>
	<ul class="col-content">
	<?php

	$separated = false;
	foreach ($s_group_data as $e_id => $e_data) {

		if (empty($e_data)) {
			$separated = true;
			continue;
		} ?>

		<li<?php echo $separated ? ' class="separated"':''; ?>><label>
			<input type="checkbox"
			name="<?php echo self::$abbr . '_elements_to_hide['.$s_group_name.']['.$e_id.']'; ?>"
			<?php if (isset(self::$settings['elements_to_hide'][$s_group_name][$e_id])) echo ' checked="checked"'; ?> />
			<?php echo $e_data[1] ?>
		</label>

		<?php
		$separated = false;

	} ?>
	</ul>

	</div>

<?php
}

// ============================================================
// Custom CSS ?>

		<div class="col" id="col_custom_css">
			<h3>Custom CSS</h3>
			<div class="col-content">
				<?php $vars_to_save[] = 'custom_css'; ?>
				<p>Will be applied on administration only. Example content: <code>#whatever {display:none;}</code></p>
				<p><textarea name="<?php echo self::$abbr; ?>_custom_css" cols="70" rows="13"><?php echo htmlspecialchars(self::$settings['custom_css']); ?></textarea></p>
				<p><small><strong>BTW:</strong> You may also use selectors like <code>body.level_lt_10</code> or <code>.level_2</code> to modify look only for some user-groups</small></p>
			</div>
		</div>

		<div class="cleaner"></div>

		<h3>User level</h3>
		<?php $vars_to_save[] = 'userlevel'; ?>
		<p>Apply settings only for users on level &lt;  <input type="number" min="0" max="11" size="4" name="<?php echo self::$abbr ?>_userlevel" value="<?php echo @self::$settings['userlevel'] ?>" /> (leave empty to apply settings to all user-levels)
			<br />Help: <a href="http://codex.wordpress.org/Roles_and_Capabilities#Capability_vs._Role_Table">Levels vs. Roles</a></p>

		<p class="submit">
			<?php wp_nonce_field('update-options') ?>
			<input type="hidden" name="action" value="update" />
			<input type="hidden" name="page_options" value="<?php foreach ($vars_to_save as $var) echo self::$abbr .'_'. $var .','; ?>" />
			<input type="submit" name="<?php echo self::$abbr ?>_submit_update" value="<?php _e('Save Changes') ?>" class="button button-primary" />
		</p>

		</form>

<?php
if (self::DEV) { ?>

	<hr />
	<h3>Reset settings</h3>

	<?php
	if (@$_POST['action'] == 'reset') {
		self::uninstall();
		echo '<div id="message" class="updated fade"><p>Reset hopefully done. Is it ok now?</p></div>';
	} ?>

	<form action="?page=<?php echo $_GET['page'] ?>" method="post">
		<input type="hidden" name="action" value="reset">
		<input type="submit" value="Reset it!">
	</form>

<?php
} ?>
	</div><!-- /wrap -->
