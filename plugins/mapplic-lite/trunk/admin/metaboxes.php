<?php

// Validations
if (!function_exists('mplc_equals')) {
	function mplc_equals(&$field, $equals = null, $true = null, $false = '') {
		if (isset($field) && (($field == $equals) || is_null($equals))) echo (is_null($true)) ? $field : $true;
		else echo $false;
	}
}

if (!function_exists('mplc_checked')) {
	function mplc_checked(&$field) {
		if (isset($field) && (($field == 'true') || $field == true)) echo ' checked';
	}
}

if (!function_exists('mplc_field')) {
	function mplc_field(&$field) {
		echo isset($field) ? $field : '';
	}
}

// Actions
if (!function_exists('mplc_actions')) {
	function mplc_actions() {
		$actions = array(
			'tooltip' => __('Tooltip', 'mapplic'),
			'open-link' => __('Open link', 'mapplic'),
		);

		$actions = apply_filters('mapplic_actions', $actions);

		return $actions;
	}
}

// Location metabox
function mapplic_landmark_box($post, $param) {
	$data = json_decode($post->post_content, true);
	if (!is_array($data)) return;

	$styles = $data['styles'];
	$categories = $data['categories'];

	// Pin types
	$pins = array(
		'pin-circular',
		'pin-classic pin-label',
		'pin-marker pin-label',
		'pin-disk pin-label',
		'pin-ribbon pin-label',
		'pin-dot pin-label'
	);
	$pins = apply_filters('mapplic_pins', $pins);
	?>

	<div id="landmark-settings">
		<div>
			<input type="button" class="delete-landmark button" value="<?php _e('Delete', 'mapplic'); ?>">
			<input type="button" class="save-landmark button button-primary right" value="<?php _e('Save', 'mapplic'); ?>">
		</div>
		<div class="clear"></div>
		<hr>

		<label><strong><?php _e('Title', 'mapplic'); ?>:</strong><input type="text" class="title-input input-text"></label>
		<label><strong><?php _e('ID (unique)', 'mapplic'); ?>:</strong><input type="text" class="id-input input-text"></label>
		<?php wp_editor('', 'descriptioninput', array('drag_drop_upload' => true)); ?>

		<?php do_action('mapplic_landmark_fields'); // Custom fields ?>

		<div class="landmark-geolocation">
			<p><strong><?php _e('Geolocation', 'mapplic'); ?></strong></p>
			<input type="text" class="landmark-lat input-text geopos-field" placeholder="Latitude">
			<input type="text" class="landmark-lng input-text geopos-field" placeholder="Longitude">
		</div>

		<p><strong><?php _e('Color and Pin Type', 'mapplic'); ?></strong></p>
		<div>
			<ul id="pins-input">
				<li><div class="mapplic-pin hidden" data-pin="hidden">pin</div></li>
			<?php foreach ($pins as &$pin) : ?>
				<li><div class="mapplic-pin <?php echo $pin; ?>" data-pin="<?php echo $pin; ?>">m</div></li>
			<?php endforeach; ?>
			</ul>
		</div>
		<input type="text" class="label-input input-text" placeholder="<?php _e('Label', 'mapplic'); ?>">
		<input type="text" class="mapplic-color-picker fill-input">

		<p><strong><?php _e('Attributes', 'mapplic'); ?></strong></p>
		<label><?php _e('Link', 'mapplic'); ?>:<input type="text" class="link-input input-text"></label>

		<?php if (!empty($styles)) : ?>
		<label><?php _e('Style', 'mapplic'); ?>
			<select class="style-select input-select">
				<option value="">(No Style)</option>
			<?php foreach ($styles as &$style) : ?>
				<option value="<?php echo $style['class']; ?>"><?php echo $style['class']; ?></option>
			<?php endforeach; ?>
			</select>
		</label>
		<?php endif; ?>

		<label><?php _e('Action', 'mapplic'); ?>
			<select class="action-select input-select">
				<option value="default" selected><?php _e('Default', 'mapplic'); ?></option>
				<?php 
					foreach (mplc_actions() as $value => $action) : 
				?>
				<option value="<?php echo $value; ?>"<?php if ($data['action'] == $value) echo ' selected'; ?>><?php echo $action; ?></option>
				<?php endforeach; ?>
			</select>
		</label>

		<label><?php _e('Zoom Level', 'mapplic'); ?><input type="text" class="zoom-input input-text" placeholder="Auto"></label>

		<?php if (!empty($categories)) : ?>
		<label><?php _e('Groups', 'mapplic'); ?>
			<select disabled="true" class="category-select input-select" multiple>
			<?php foreach ($categories as &$category) : ?>
				<option value="<?php echo $category['id']; ?>"><?php echo $category['title']; ?></option>
			<?php endforeach; ?>
			</select>
		</label>
		<?php endif; ?>

		<div>
			<label><?php _e('Image', 'mapplic'); ?><br>
				<input disabled="true" type="text" class="input-text image-input buttoned" value="">
				<button disabled="true" class="button media-button"><span class="dashicons dashicons-format-image"></span></button>
			</label>
		</div>

		<div>
			<label><?php _e('Thumbnail', 'mapplic'); ?><br>
				<input disabled="true" type="text" class="input-text thumbnail-input buttoned" value="">
				<button disabled="true" class="button media-button"><span class="dashicons dashicons-format-image"></span></button>
			</label>
		</div>

		<label><?php _e('Reveal Zoom', 'mapplic'); ?><input disabled="true" type="text" class="reveal-input input-text" placeholder="Disabled"></label>

		<label><input disabled="true" type="checkbox" class="hide-input"<?php mplc_equals($data['hide'], 'true', ' checked', ''); ?>> <?php _e('Hide from sidebar', 'mapplic'); ?></label>

		<label><?php _e('About', 'mapplic'); ?>:<input disabled="true" type="text" class="about-input input-text" placeholder="Text visible on sidebar"></label>

		<input disabled="true" type="button" class="duplicate-landmark button right" value="<?php _e('Duplicate', 'mapplic'); ?>">
	</div>

	<input type="button" id="new-landmark" class="button" value="<?php _e('Add New', 'mapplic'); ?>">
	
	<?php
	unset($pins);
	unset($category);
}

// Floors Metabox
function mapplic_floors_box($post, $param) {
	$data = json_decode($post->post_content, true);
	if (!is_array($data)) return;

	$floors = array_reverse($data['levels']);
	?>

	<ul id="floor-list" class="sortable-list">
		<li class="list-item new-item">
			<div class="list-item-handle">
				<span class="menu-item-title"><?php _e('New Floor', 'mapplic'); ?></span>
				<a href="#" class="menu-item-toggle"></a>
			</div>
			<div class="list-item-settings">
				<label>
					<?php _e('Name', 'mapplic'); ?><br><input type="text" class="input-text title-input" value="<?php _e('New Floor', 'mapplic'); ?>">
				</label>
				<label><?php _e('ID (unique)', 'mapplic'); ?><br><input type="text" class="input-text id-input" value=""></label>

				<div>
					<label><?php _e('Map', 'mapplic'); ?><br>
						<input type="text" class="input-text map-input buttoned" value="">
						<button class="button media-button"><span class="dashicons dashicons-upload"></span></button>
					</label>
				</div>

				<div>
					<label><?php _e('Minimap', 'mapplic'); ?><br>
						<input type="text" class="input-text minimap-input buttoned" value="">
						<button class="button media-button"><span class="dashicons dashicons-upload"></span></button>
					</label>
				</div>

				<div>
					<a href="#" class="item-cancel"><?php _e('Cancel'); ?></a>
				</div>
			</div>
		</li>
	
	<?php foreach ($floors as &$floor) : ?>

		<li class="list-item">
			<div class="list-item-handle">
				<span class="menu-item-title"><?php echo $floor['title']; ?></span>
				<a href="#" class="menu-item-toggle"></a>
			</div>
			<div class="list-item-settings">
				<label><?php _e('Name', 'mapplic'); ?><br><input type="text" class="input-text title-input" value="<?php echo $floor['title']; ?>"></label>
				<label><?php _e('ID (unique)', 'mapplic'); ?><br><input type="text" class="input-text id-input" value="<?php echo $floor['id']; ?>" disabled></label>

				<?php $shown = (isset($floor['show']) && ($floor['show'] == 'true')) ? 'checked' : ''; ?>
				<label>
					<input type="radio" name="shown-floor" class="show-input" <?php echo $shown; ?> value="<?php echo $floor['id']; ?>"> <?php _e('Show by default', 'mapplic'); ?>
				</label>

				<div>
					<label><?php _e('Map', 'mapplic'); ?><br>
						<input type="text" class="input-text map-input buttoned" value="<?php echo $floor['map']; ?>">
						<button class="button media-button"><span class="dashicons dashicons-upload"></span></button>
					</label>
				</div>

				<div>
					<label>Minimap<br>
						<input disabled="true" type="text" class="input-text minimap-input buttoned" value="<?php echo $floor['minimap']; ?>">
						<button disabled="true" class="button media-button"><span class="dashicons dashicons-upload"></span></button>
					</label>
				</div>

				<div>
					<a href="#" class="item-cancel"><?php _e('Cancel'); ?></a>
				</div>
			</div>
		</li>

	<?php endforeach; ?>
	</ul>
	<input type="button" id="new-floor" disabled="true" class="button" value="<?php _e('New Floor', 'mapplic'); ?>">
	<input type="submit" name="submit" class="button button-primary form-submit right" value="<?php _e('Save', 'mapplic'); ?>">
	<div class="clear"></div>
	<?php
	unset($floor);
}

// Styles metabox
function mapplic_styles_box($post, $param) {
	$data = json_decode($post->post_content, true);
	if (!is_array($data)) return;
	?>
	<ul id="style-list" class="sortable-list">

		<li class="list-item new-item">
			<div class="list-item-handle">
				<span class="menu-item-title"><?php _e('New Style', 'mapplic'); ?></span>
				<a href="#" class="menu-item-toggle"></a>
			</div>
			<div class="list-item-settings">

				<label><?php _e('Class', 'mapplic'); ?><br><input type="text" class="input-text class-input" value=""></label>

				<div>
					<a href="#" class="item-delete"><?php _e('Delete'); ?></a>
					<span class="meta-sep"> | </span>
					<a href="#" class="item-cancel"><?php _e('Cancel'); ?></a>
				</div>
			</div>
		</li>

	<?php if ($data['styles']) : ?>
	<?php foreach ($data['styles'] as &$style) : ?>
		<li class="list-item">
			<div class="list-item-handle">
				<span class="menu-item-title"><?php echo $style['class']; ?></span>
				<a href="#" class="menu-item-toggle"></a>
			</div>
			<div class="list-item-settings">
				<label><?php _e('Class Name', 'mapplic'); ?><br><input type="text" class="input-text class-input" value="<?php echo $style['class']; ?>"></label>

				<label><strong><?php _e('Base', 'mapplic'); ?></strong></label>
				<input type="text" class="mapplic-alpha-color-picker base-fill" data-text="Fill Color" value="<?php echo isset($style['base']['fill']) ? $style['base']['fill'] : ''; ?>"><br>
				
				<label><strong><?php _e('Hover & Highlight', 'mapplic'); ?></strong></label>
				<input type="text" class="mapplic-alpha-color-picker hover-fill" data-text="Fill Color" value="<?php echo isset($style['hover']['fill']) ? $style['hover']['fill'] : ''; ?>"><br>

				<label><strong><?php _e('Active', 'mapplic'); ?></strong></label>
				<input type="text" class="mapplic-alpha-color-picker active-fill" data-text="Fill Color" value="<?php echo isset($style['active']['fill']) ? $style['active']['fill'] : ''; ?>"><br>

				<div>
					<a href="#" class="item-delete"><?php _e('Delete'); ?></a>
					<span class="meta-sep"> | </span>
					<a href="#" class="item-cancel"><?php _e('Cancel'); ?></a>
				</div>
			</div>
		</li>
	<?php endforeach; ?>
	<?php else: ?>
		<p><?php _e('There are no reusable styles yet.'); ?></p>
	<?php endif; ?>
	</ul>
	<input type="button" id="new-style" class="button" value="<?php _e('New Style', 'mapplic'); ?>">
	<input type="submit" name="submit" class="button button-primary form-submit right" value="<?php _e('Save', 'mapplic'); ?>">
	<div class="clear"></div>	
	<?php
	unset($style);
}

// Categories metabox
function mapplic_categories_box($post, $param) {
	$data = json_decode($post->post_content, true);
	if (!is_array($data)) return;
	?>
	<ul id="category-list" class="sortable-list">

		<li class="list-item new-item">
			<div class="list-item-handle">
				<span class="menu-item-title"><?php _e('New Group', 'mapplic'); ?></span>
				<a href="#" class="menu-item-toggle"></a>
			</div>
			<div class="list-item-settings">

				<label>
					<?php _e('Name', 'mapplic'); ?><br><input type="text" class="input-text title-input" value="<?php _e('New Group', 'mapplic'); ?>">
				</label>
				<label><?php _e('ID (unique)', 'mapplic'); ?><br><input type="text" class="input-text id-input" value=""></label>

				<div>
					<a href="#" class="item-delete"><?php _e('Delete'); ?></a>
					<span class="meta-sep"> | </span>
					<a href="#" class="item-cancel"><?php _e('Cancel'); ?></a>
				</div>
			</div>
		</li>

	<?php foreach ($data['categories'] as &$category) : ?>
		<li class="list-item">
			<div class="list-item-handle">
				<span class="menu-item-title"><?php echo $category['title']; ?></span>
				<a href="#" class="menu-item-toggle"></a>
			</div>
			<div class="list-item-settings">

				<label><?php _e('Name', 'mapplic'); ?><br><input type="text" class="input-text title-input" value="<?php echo $category['title']; ?>"></label>
				<label><?php _e('ID (unique)', 'mapplic'); ?><br><input type="text" class="input-text id-input" value="<?php echo $category['id']; ?>"></label>
				<label><?php _e('About', 'mapplic'); ?><br><input type="text" class="input-text about-input" value="<?php mplc_field($category['about']); ?>" placeholder="Text visible in sidebar"></label>

				<?php if (!empty($data['styles'])) : ?>
				<label><?php _e('Style', 'mapplic'); ?>
					<select class="style-select input-select">
						<option value="">(No Style)</option>
					<?php foreach ($data['styles'] as &$style) : ?>
						<option value="<?php echo $style['class']; ?>"<?php mplc_equals($category['style'], $style['class'], ' selected', ''); ?>><?php echo $style['class']; ?></option>
					<?php endforeach; ?>
					</select>
				</label>
				<?php endif; ?>

				<label>
					<input type="checkbox" class="legend-input"<?php mplc_equals($category['legend'], 'true', ' checked', ''); ?>><?php _e('Add to legend', 'mapplic'); ?>
				</label>
				<label>
					<input type="checkbox" class="hide-input"<?php mplc_equals($category['hide'], 'true', ' checked', ''); ?>><?php _e('Hide from sidebar', 'mapplic'); ?>
				</label>
				<label>
					<input type="checkbox" class="toggle-input"<?php mplc_equals($category['toggle'], 'true', ' checked', ''); ?>><?php _e('Enable toggle mode', 'mapplic'); ?>
				</label>
				<label>
					<input type="checkbox" class="switchoff-input"<?php mplc_equals($category['switchoff'], 'true', ' checked', ''); ?>><?php _e('Switch off by default', 'mapplic'); ?>
				</label>

				<input type="text" class="mapplic-color-picker color-input" value="<?php echo isset($category['color']) ? $category['color'] : ''; ?>" data-default-color="#aaaaaa">

				<div>
					<a href="#" class="item-delete"><?php _e('Delete'); ?></a>
					<span class="meta-sep"> | </span>
					<a href="#" class="item-cancel"><?php _e('Cancel'); ?></a>
				</div>
			</div>
		</li>
	<?php endforeach; ?>
	</ul>
	<input disabled="true" type="button" id="new-category" class="button" value="<?php _e('New Group', 'mapplic'); ?>">
	<input type="submit" name="submit" class="button button-primary form-submit right" value="<?php _e('Save', 'mapplic'); ?>">
	<div class="clear"></div>	
	<?php
	unset($category);
}

// Geoposition metabox
function mapplic_geoposition_box($post, $param) {
	$data = json_decode($post->post_content, true);
	if (!is_array($data)) return;
	?>
	<div id="geopos">
		<div class="geopos-corner tl"></div>
		<input disabled="true" type="text" class="geopos-field" id="topLat" placeholder="Top Latitude" value="<?php mplc_equals($data['topLat']); ?>">
		<div class="geopos-corner tr"></div><br>
		<input disabled="true" type="text" class="geopos-field" id="leftLng" placeholder="Left Longitude" value="<?php mplc_equals($data['leftLng']); ?>">
		<input disabled="true" type="text" class="geopos-field" id="rightLng" placeholder="Right Longitude" value="<?php mplc_equals($data['rightLng']); ?>">
		<br><div class="geopos-corner bl"></div>
		<input disabled="true" type="text" class="geopos-field" id="bottomLat" placeholder="Bottom Latitude" value="<?php mplc_equals($data['bottomLat']); ?>">
		<div class="geopos-corner br"></div>
	</div>
	<?php
}

// Settings metabox
function mapplic_settings_box($post, $param) {
	$data = json_decode($post->post_content, true);
	if (!is_array($data)) return;

	if (!is_numeric($data['mapwidth']) || !is_numeric($data['mapheight'])) :
	?>
		<div class="notice notice-error">
			<p><?php _e('Map file dimensions either not set or invalid!', 'mapplic'); ?></p>
		</div>
	<?php
	endif;

	?>
	<h4><?php _e('Map container height', 'mapplic'); ?> <span class="dashicons dashicons-editor-help help-toggle"></span></h4>
	<p class="help-content"><i>Three value types accepted, example: <b>auto</b> (default), <b>600px</b> (fixed, defined in pixels) and <b>80%</b> (percent of the browser height).</i></p>
	<input type="text" data-setting="height" value="<?php echo isset($data['height']) ? $data['height'] : ''; ?>" placeholder="auto">
	<span>[mapplic id="<?php echo $post->ID; ?>" h="<span id="h-attribute"><?php echo (empty($data['height']) || $data['height'] == '') ? 'auto' : $data['height']; ?></span>"]</span>

	<h4><?php _e('Map file dimensions (REQUIRED)', 'mapplic'); ?></h4>
	<label>
		<?php _e('File Width', 'mapplic'); ?><br>
		<input type="text" id="setting-mapwidth" value="<?php echo $data['mapwidth']; ?>" placeholder="<?php _e('REQUIRED', 'mapplic'); ?>"><span> px</span>
	</label>
	<label>
		<?php _e('File Height', 'mapplic'); ?><br>
		<input type="text" id="setting-mapheight" value="<?php echo $data['mapheight']; ?>" placeholder="<?php _e('REQUIRED', 'mapplic'); ?>"><span> px</span>
	</label>

	<!-- General -->
	<h4><?php _e('General', 'mapplic'); ?></h4>
	<label>
		<?php _e('Portrait breakpoint', 'mapplic'); ?><br>
		<input type="text" data-setting="portrait" value="<?php echo isset($data['portrait']) ? $data['portrait'] : ''; ?>" placeholder="<?php _e('668 (Default)', 'mapplic'); ?>">
	</label>
	<label><?php _e('Default action', 'mapplic'); ?><br>
		<select data-setting="action">
			<?php foreach (mplc_actions() as $value => $action) : ?>
			<option value="<?php echo $value; ?>"<?php if ($data['action'] == $value) echo ' selected'; ?>><?php echo $action; ?></option>
			<?php endforeach; ?>
		</select>
	</label>
	<?php if (!empty($data['styles'])) : ?>
	<label><?php _e('Default style', 'mapplic'); ?><br>
		<select data-setting="defaultstyle">
			<option value=""></option>
		<?php foreach ($data['styles'] as &$style) : ?>
			<option value="<?php echo $style['class']; ?>"<?php mplc_equals($data['defaultstyle'], $style['class'], ' selected', ''); ?>><?php echo $style['class']; ?></option>
		<?php endforeach; ?>
		</select>
	</label>
	<?php endif; ?>
	<label>
		<?php _e('More button text', 'mapplic'); ?><br>
		<input type="text" data-setting="moretext" value="<?php echo isset($data['moretext']) ? $data['moretext'] : ''; ?>" placeholder="<?php _e('More', 'mapplic'); ?>">
	</label>
	<label>
		<input type="checkbox" data-setting="hovertip"<?php mplc_checked($data['hovertip']); ?>> <?php _e('Hover tooltip', 'mapplic'); ?>
	</label>
	<label>
		<input disabled="true" type="checkbox" data-setting="fullscreen"> <?php _e('Enable fullscreen', 'mapplic'); ?>
	</label>
	<label>
		<input disabled="true" type="checkbox" data-setting="smartip"> <?php _e('Smart tooltip', 'mapplic'); ?>
	</label>
	<label>
		<input disabled="true" type="checkbox" data-setting="deeplinking"> <?php _e('Deeplinking', 'mapplic'); ?>
	</label>
	<label>
		<input disabled="true" type="checkbox" data-setting="linknewtab"> <?php _e('Open links in new tab', 'mapplic'); ?>
	</label>
	<label>
		<input disabled="true" type="checkbox" data-setting="minimap"> <?php _e('Enable minimap', 'mapplic'); ?>
	</label>

	<!-- Zoom options -->
	<h4><?php _e('Zoom options', 'mapplic'); ?></h4>
	<label>
		<input type="checkbox" class="settings-toggle" data-setting="zoom"<?php mplc_checked($data['zoom']); ?>> <?php _e('Enable zoom', 'mapplic'); ?>
	</label>
	<div class="settings-group" data-group="zoom">
		<label>
			<span><?php _e('Maximum zoom level', 'mapplic'); ?></span><br>
			<input type="text" data-setting="maxscale" value="<?php echo isset($data['maxscale']) ? $data['maxscale'] : '3'; ?>" placeholder="<?php _e('No zoom', 'mapplic'); ?>">
		</label>
		<label>
			<span><?php _e('Zoom margin', 'mapplic'); ?></span><br>
			<input type="text" data-setting="zoommargin" value="<?php echo isset($data['zoommargin']) ? $data['zoommargin'] : ''; ?>" placeholder="<?php _e('200 (Default)', 'mapplic'); ?>">
		</label>
		<label>
			<input type="checkbox" data-setting="zoombuttons"<?php mplc_checked($data['zoombuttons']); ?>> <span><?php _e('Zoom buttons', 'mapplic'); ?></span>
		</label>
		<label>
			<input type="checkbox" data-setting="clearbutton"<?php mplc_checked($data['clearbutton']); ?>> <span><?php _e('Clear button', 'mapplic'); ?></span>
		</label>
		<label>
			<input type="checkbox" data-setting="zoomoutclose"<?php mplc_checked($data['zoomoutclose']); ?>> <span><?php _e('Zoom out when closing popup', 'mapplic'); ?></span>
		</label>
		<label>
			<input type="checkbox" data-setting="closezoomout"<?php mplc_checked($data['closezoomout']); ?>> <span><?php _e('Close popup when zoomed out', 'mapplic'); ?></span>
		</label>
		<label>
			<input type="checkbox" data-setting="mousewheel"<?php mplc_checked($data['mousewheel']); ?>> <span><?php _e('Mouse wheel', 'mapplic'); ?></span>
		</label>
		<label>
			<input type="checkbox" data-setting="mapfill"<?php mplc_checked($data['mapfill']); ?>> <span><?php _e('Always fill the container', 'mapplic'); ?></span>
		</label>
	</div>

	<!-- Sidebar options -->
	<h4><?php _e('Sidebar options', 'mapplic'); ?></h4>
	<label>
		<input type="checkbox" class="settings-toggle" data-setting="sidebar"<?php mplc_checked($data['sidebar']); ?>> <?php _e('Enable sidebar', 'mapplic'); ?>
	</label>
	<div class="settings-group" data-group="sidebar">
		<label>
			<input type="checkbox" data-setting="search"<?php mplc_checked($data['search']); ?>> <span><?php _e('Search field', 'mapplic'); ?></span>
		</label>
		<label>
			<input class="dis" disabled type="checkbox" data-setting="searchdescription"> <span><?php _e('Search description', 'mapplic'); ?></span>
		</label>
		<label>
			<span><?php _e('Minimum keyword length', 'mapplic'); ?></span><br>
			<input type="text" class="dis" disabled data-setting="searchlength" value="<?php echo isset($data['searchlength']) ? $data['searchlength'] : ''; ?>" placeholder="<?php _e('1 (Default)', 'mapplic'); ?>">
		</label>
		<label>
			<input class="dis" disabled="true" type="checkbox" data-setting="alphabetic"> <span><?php _e('Alphabetically ordered list', 'mapplic'); ?></span>
		</label>
		<label>
			<input class="dis" disabled="true" type="checkbox" data-setting="thumbholder"> <span><?php _e('Thumbnail placeholder', 'mapplic'); ?></span>
		</label>
		<label>
			<input class="dis" disabled="true" type="checkbox" data-setting="hidenofilter"> <span><?php _e('Hide locations when no filter', 'mapplic'); ?></span>
		</label>
		<label>
			<input class="dis" disabled="true" type="checkbox" data-setting="highlight"> <span><?php _e('Highlight map on filter', 'mapplic'); ?></span>
		</label>
	</div>

	<!-- CSV Support -->
	<h4><?php _e('CSV Support', 'mapplic'); ?> <span class="dashicons dashicons-editor-help help-toggle"></span></h4>
	<p class="help-content"><i><a href="https://www.mapplic.com/docs/#csv" target="_blank">Click here</a> to leard more about CSV Support.</i></p>
	<label><?php _e('CSV file', 'mapplic'); ?><br>
		<input disabled type="text" data-setting="csv" class="input-text buttoned" value="<?php echo isset($data['csv']) ? $data['csv'] : ''; ?>">
		<button disabled class="button media-button"><span class="dashicons dashicons-media-spreadsheet"></span></button>
	</label>

	<!-- Custom CSS -->
	<h4><?php _e('Custom CSS', 'mapplic'); ?></h4>
	<textarea disabled data-setting="customcss" rows="8" spellcheck="false"><?php echo isset($data['customcss']) ? $data['customcss'] : ''; ?></textarea>

	<?php
	do_action('mapplic_settings', $data);
}
?>