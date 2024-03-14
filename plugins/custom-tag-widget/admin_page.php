<?php
// Displays the page content for the Tag Widget Admin Submenu
function tw_options_page() {
	// variables for the field and option names 
	$options = $newoptions = get_option('template_tw');
	if (isset($_POST['tw-submit'])) {
		$newoptions['number'] = (int) $_POST['tw-number'];
		$newoptions['minnum'] = (int) $_POST['tw-minnum'];
		$newoptions['maxnum'] = (int) $_POST['tw-maxnum'];
		$newoptions['unit'] = $_POST['tw-unit'];
		$newoptions['smallest'] = strip_tags(stripslashes($_POST['tw-smallest']));
		$newoptions['largest'] = strip_tags(stripslashes($_POST['tw-largest']));
		$newoptions['mincolor'] = strip_tags(stripslashes($_POST['tw-mincolor']));
		$newoptions['maxcolor'] = strip_tags(stripslashes($_POST['tw-maxcolor']));
		$newoptions['format'] = $_POST['tw-format'];
		$newoptions['orderby'] = $_POST['tw-orderby'];
		$newoptions['order'] = $_POST['tw-order'];
		$newoptions['showcount'] = $_POST['tw-showcount'];
		$newoptions['showcats'] = $_POST['tw-showcats'];
		$newoptions['showtags'] = $_POST['tw-showtags'];
		$newoptions['empty'] = $_POST['tw-empty'];

		// Put an options updated message on the screen
?>
		<div class="updated"><p><strong>Tag Widget Options Saved</strong></p></div>
<?php
	}

	if ($options != $newoptions) {
		$options = $newoptions;
		update_option('template_tw', $options);
	}

	$number = (int) $options['number'];
	$minnum = (int) $options['minnum'];
	$maxnum = (int) $options['maxnum'];
	$unit = $options['unit'];
	$smallest = htmlspecialchars($options['smallest'], ENT_QUOTES);
	$largest = htmlspecialchars($options['largest'], ENT_QUOTES);
	$mincolor = htmlspecialchars($options['mincolor'], ENT_QUOTES);
	$maxcolor = htmlspecialchars($options['maxcolor'], ENT_QUOTES);
	$format = $options['format'];
	$orderby = $options['orderby'];
	$order = $options['order'];
	$showcount = $options['showcount'];
	$showcats = $options['showcats'];
	$showtags = $options['showtags'];
	$empty = $options['empty'];

    // Now display the options editing screen
    echo '<div class="wrap">';
    echo "<h2>Tag Widget - Default Settings	</h2>";
	// options form
?>
		<form method="post" action="<?php echo str_replace('%7E','~',$_SERVER['REQUEST_URI']); ?>">
			<p>You can access a single instance of the tag widget using the <strong>&lt;?php tw(); ?&gt;</strong> template tag in your theme. This instance will pull in all the settings defined below.</p>
			<?php wp_nonce_field('update-options') ?>
			<table class="form-table">
				<tr><td width="180"><strong>Number of Tags to Display</strong></td>
					<td width="100"><input style="text-align:right" type="text" id="tw-number" name="tw-number" value="<?php echo wp_specialchars($options['number'], true); ?>" /></td>
					<td style="font-size:0.75em">Controls the total number of tags in your cloud.</td>
				</tr>
				<tr><td><strong>Min. Number of Posts</strong></td>
					<td><input style="text-align:right" type="text" id="tw-minnum" name="tw-minnum" value="<?php echo wp_specialchars($options['minnum'], true); ?>" /></td>
					<td style="font-size:0.75em">Tags with less than this number of posts will not be displayed.</td>
				</tr>
				<tr><td><strong>Max. Number of Posts</strong></td>
					<td><input style="text-align:right" type="text" id="tw-maxnum" name="tw-maxnum" value="<?php echo wp_specialchars($options['maxnum'], true); ?>" /></td>
					<td style="font-size:0.75em">Tags with more than this number of posts will not be displayed.</td>
				</tr>
				<tr><td><strong>Font Display Unit</strong></td>
					<td>
						<select id="tw-unit" name="tw-unit" size="1" value="" />
				   			<option value="px" <?php echo ($unit=="px")?'selected':''?>>Pixel</option>
				   			<option value="pt" <?php echo ($unit=="pt")?'selected':''?>>Point</option>
				   			<option value="em" <?php echo ($unit=="em")?'selected':''?>>Em</option>
					   		<option value="%" <?php echo ($unit=="%")?'selected':''?>>Percent</option>
			   			</select>
					</td>
					<td style="font-size:0.75em">What unit to use for font sizes.</td>
				</tr>
				<tr><td><strong>Smallest Font Size</strong></td>
					<td><input style="text-align:right" type="text" id="tw-smallest" name="tw-smallest" value="<?php echo wp_specialchars($options['smallest'], true); ?>" /></td>
					<td style="font-size:0.75em">Tags will be displayed no smaller than this value.</td>
				</tr>
				<tr><td><strong>Largest Font Size</strong></td>
					<td><input style="text-align:right" type="text" id="tw-largest" name="tw-largest" value="<?php echo wp_specialchars($options['largest'], true); ?>" /></td>
					<td style="font-size:0.75em">Tags will be displayed no larger that this value.</td>
				</tr>
				<tr><td><strong>Min. Tag Color</strong></td>
					<td><input style="text-align:right" type="text" id="tw-mincolor" name="tw-mincolor" value="<?php echo wp_specialchars($options['mincolor'], true); ?>" /></td>
					<td style="font-size:0.75em">Beginning color for tag gradient.  Please include the #.</td>
				</tr>
				<tr><td><strong>Max. Tag Color</strong></td>
					<td><input style="text-align:right" type="text" id="tw-maxcolor" name="tw-maxcolor" value="<?php echo wp_specialchars($options['maxcolor'], true); ?>" /></td>
					<td style="font-size:0.75em">Ending color for tag gradient.  Please include the #.</td>
				</tr>
				<tr><td><strong>Cloud Format</strong></td>
					<td>
						<select id="tw-format" name="tw-format" size="1" value="" />
					   		<option value="flat" <?php echo ($format=="flat")?'selected':''?>>Flat</option>
					   		<option value="list" <?php echo ($format=="list")?'selected':''?>>List</option>
					   		<option value="array" <?php echo ($format=="array")?'selected':''?>>Array</option>
							<option value="drop" <?php echo ($format=="drop")?'selected':''?>>Dropdown</option>
			   			</select>
					</td>
					<td style="font-size:0.75em">How to display the cloud.</td>
				</tr>
				<tr><td><strong>Show Tags</strong></td>
					<td><input type="radio" id="tw-showtags" name="tw-showtags" <?php echo ($showtags=="yes")?'checked="checked"':''?> value="yes" /> Yes <input type="radio" id="tw-showtags" name="tw-showtags" <?php echo ($showtags=="no")?'checked="checked"':''?> value="no" /> No</td>
					<td style="font-size:0.75em">Display tags in cloud.</td>
				</tr>
				<tr><td><strong>Show Categories</strong></td>
					<td><input type="radio" id="tw-showcats" name="tw-showcats" <?php echo ($showcats=="yes")?'checked="checked"':''?> value="yes" /> Yes <input type="radio" id="tw-showcats" name="tw-showcats" <?php echo ($showcats=="no")?'checked="checked"':''?> value="no" /> No</td>
					<td style="font-size:0.75em">Display categories in cloud.</td>
				</tr>
				<tr><td><strong>Show Empty?</strong></td>
					<td><input type="radio" id="tw-empty" name="tw-empty" <?php echo ($empty=="yes")?'checked="checked"':''?> value="yes" /> Yes <input type="radio" id="tw-empty" name="tw-empty" <?php echo ($empty=="no")?'checked="checked"':''?> value="no" /> No</td>
					<td style="font-size:0.75em">Display empty categories in cloud.</td>
				</tr>
				<tr><td><strong>Display Post Count?</strong></td>
					<td><input type="radio" id="tw-showcount" name="tw-showcount" <?php echo ($showcount=="yes")?'checked="checked"':''?> value="yes" /> Yes <input type="radio" id="tw-showcount" name="tw-showcount" <?php echo ($showcount=="no")?'checked="checked"':''?> value="no" /> No</td>
					<td style="font-size:0.75em">Show number of posts for each tag.</td>
				</tr>
				<tr><td><strong>Sort By</strong></td>
					<td>
						<select id="tw-orderby" name="tw-orderby" size="1" value="" />
					   		<option value="name" <?php echo ($orderby=="name")?'selected':''?>>Name</option>
					   		<option value="count" <?php echo ($orderby=="count")?'selected':''?>>Count</option>
					   		<option value="rand" <?php echo ($orderby=="rand")?'selected':''?>>Random</option>
			   			</select>
					</td>
					<td style="font-size:0.75em">What field to sort by.</td>
				</tr>
				<tr><td><strong>Sort Order</strong></td>
					<td>
						<input type="radio" id="tw-order" name="tw-order" <?php echo ($order=="ASC")?'checked="checked"':''?> value="ASC" /> Asc
						<input type="radio" id="tw-order" name="tw-order" <?php echo ($order=="DESC")?'checked="checked"':''?> value="DESC" /> Desc</td>
					<td style="font-size:0.75em">Direction of sort.</td>
				</tr>
			</table>
			<p class="submit">
				<input type="submit" name="Submit" value="<?php _e('Update Options »') ?>" />
			</p>
			<input type="hidden" name="tw-submit" id="tw-submit" value="1" />
		</form>
	</div>

<?php
}

function tw_add_page() {
    // Add a new submenu under Options:
    add_options_page('Tag Widget', 'Tag Widget', 'manage_options', 'twoptions', 'tw_options_page');
}

add_action('admin_menu', 'tw_add_page');
?>